<?php

namespace App\Http\Controllers;

use App\Enums\ActivityEventType;
use App\Traits\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\Subscription;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use JWTAuth;
// use App\Http\Controllers\Controller;
// use App\Models\Route;

class StripeController extends Controller
{

    use ActivityLogger;
    protected $notification;
    protected $token = "fz7jygh1AUa9rmNy8_nHEL:APA91bHuwCVSUxq60sKzeOqYYU8dqjBqrpXIyuOQQy72RQZaUJnF-xEPxocj6rCugNy24m1XeUj0RkXL5I19txIUlm_w-LjBdLIBb-eEHI1jT2w0_ze_4gI";
    public function __construct(NotificationService $notification)
    {
        $this->notification = $notification;
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function index(Request $request)
    {

        $payload = @file_get_contents('php://input');
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
            // Log::info("Stripe Event : " . $event);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        }

        switch ($event->type) {
            case 'customer.subscription.deleted':


                $paymentIntent = $event->data->object;

                if ($paymentIntent->status == "canceled") {



                    $planCancelled = DB::table('subscriptions')->where('stripe_id', $paymentIntent->id)->get('user_id');
                    $user = DB::table('users')->where('id', $planCancelled[0]->user_id)->get();
                    $fcmTokens = DB::table('session_table')
                        ->where('users_id', $user[0]->id)
                        ->where('status', 1)
                        ->whereNotNull('fcm_token')
                        ->pluck('fcm_token')->toArray();

                    $this->activity($user->first()->id)->event("Subscription")
                        ->eventType(ActivityEventType::CANCELED)
                        ->descriptionSimple("Subscription canceled")
                        ->notification()
                        ->isUser()
                        ->isAdmin()
                        ->properties([
                            "user" => $user,
                            "subscription" => $paymentIntent
                        ])->save();

                    // $fcmResults = [];
                    // foreach ($fcm_token as $tokens) {
                    //     array_push($fcmResults, $tokens->fcm_token);
                    // }
                    // ;

                    $website_link = env('WEBSITE_URL');

                    // if (count($fcm_token) == 0) {
                    // } else {

                    //     // $this->notification->send($fcmResults, "Subscription", "Your subscription is cancelled to renew your subscription please visit" . env('APP_URL'));

                    //     //         $curl = curl_init();

                    //     //         curl_setopt_array($curl, array(
                    //     //             CURLOPT_URL => 'https://streamlytv.com/api/auth/sendNotification',
                    //     //             CURLOPT_RETURNTRANSFER => true,
                    //     //             CURLOPT_ENCODING => '',
                    //     //             CURLOPT_MAXREDIRS => 10,
                    //     //             CURLOPT_TIMEOUT => 0,
                    //     //             CURLOPT_FOLLOWLOCATION => true,
                    //     //             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    //     //             CURLOPT_CUSTOMREQUEST => 'POST',
                    //     //             CURLOPT_POSTFIELDS => '{
                    //     //  "registration_ids": ' . json_encode($fcmResults) . ',
                    //     //  "title": "Subscription Cancelled",
                    //     //  "body": "HI ' . ucwords($user[0]->name) . ' your subscription is cancelled to renew your subscription please visit ' . $website_link . '"
                    //     // }',
                    //     //             CURLOPT_HTTPHEADER => array(
                    //     //                 'Content-Type: application/json'
                    //     //             ),
                    //     //         ));
                    //     //         $response = curl_exec($curl);
                    //     //         curl_close($curl);
                    // }
                    if ($fcmTokens != null && count($fcmTokens) > 0) {
                        foreach ($fcmTokens as $token) {
                            $this->notification->send($token, "Subscription", "Your subscription is cancelled. ");
                        }
                    }


                    $userplanCanceled = DB::table('subscriptions')
                        ->where('stripe_id', $paymentIntent->id)
                        ->update(array(
                            'stripe_status' => 'canceled',
                            'ends_at' => date("Y/m/d h:i:s"),
                        ));

                    $html = '<html>
                    Hi, ' . ucwords($user[0]->name) . '<br><br>
                    Your StreamlyTV subscription is now canceled.<br>
                    To renew your subscription, please visit: ' . $website_link . ', login, and select a plan!.
                    <br><br>
                    <b>Thanks,<br>
                    Team Streamly<b>
                </html>';

                    Mail::send('emails.general', ["html" => $html], function ($message) use ($request, $user) {
                        $message->to($user[0]->email, $user[0]->name);
                        $message->bcc('annu.sehrawat@oodles.io', 'mina@neovix.com')
                            ->subject('Your subscription has been canceled. ' . env('APP_NAME'));
                    });
                }
                break;

            case 'payment_intent.succeeded':
                $paymentIntent = $event->data;



                // if ($paymentIntent->object->description != "Subscription creation") {

                $user = DB::table('users')->where('stripe_id', $paymentIntent->object->customer)->get();
                $amount = $paymentIntent->object->amount / 100;
                $recipt = $paymentIntent->object->charges->data[0]->receipt_url;
                $subscriptionId = $paymentIntent->object->invoice;

                $subscription = \Stripe\Invoice::retrieve($subscriptionId);
                $upcomingInvoice = \Stripe\Invoice::upcoming([
                    'subscription' => $subscription->subscription, // Use the subscription ID
                ]);

                $this->activity($user->first()->id)->event("Subscription")
                    ->eventType(ActivityEventType::CREATED)
                    ->descriptionSimple("Subscription created")
                    ->notification()
                    ->isUser()
                    ->isAdmin()
                    ->properties([
                        "user" => $user,
                        "subscription" => $subscription
                    ])->save();
                $nextBillingDate = $upcomingInvoice->next_payment_attempt ? date('Y-m-d H:i', $upcomingInvoice->next_payment_attempt) : 'N/A';

                $fcmTokens = DB::table('session_table')
                    ->where('users_id', $user[0]->id)
                    ->where('status', 1)
                    ->whereNotNull('fcm_token')
                    ->pluck('fcm_token')->toArray();

                // $fcmResults = [];
                // foreach ($fcm_token as $tokens) {
                //     array_push($fcmResults, $tokens->fcm_token);
                // }
                // ;

                if ($fcmTokens != null && count($fcmTokens) > 0) {
                    foreach ($fcmTokens as $token) {
                        $this->notification->send($token, "Subscription", "Your subscription is renewed.");

                    }
                }

                // if (count($fcm_token) == 0) {
                // } else {

                //     $this->notification->send($fcmResults, "Subscription", "Your subscription is renewed.");

                //     // $curl = curl_init();

                //     // curl_setopt_array($curl, array(
                //     //     CURLOPT_URL => 'https://streamlytv.com/api/auth/sendNotification',
                //     //     CURLOPT_RETURNTRANSFER => true,
                //     //     CURLOPT_ENCODING => '',
                //     //     CURLOPT_MAXREDIRS => 10,
                //     //     CURLOPT_TIMEOUT => 0,
                //     //     CURLOPT_FOLLOWLOCATION => true,
                //     //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                //     //     CURLOPT_CUSTOMREQUEST => 'POST',
                //     //     CURLOPT_POSTFIELDS => '{
                //     //   "registration_ids": ' . json_encode($fcmResults) . ',
                //     //   "title": "Subscription Renewed",
                //     //   "body": "HI ' . ucwords($user[0]->name) . ' your subscription is renewed."
                //     //  }',
                //     //     CURLOPT_HTTPHEADER => array(
                //     //         'Content-Type: application/json'
                //     //     ),
                //     // ));
                //     // $response = curl_exec($curl);
                //     // curl_close($curl);
                // }
                // ;

                //  $userplanRenewed = DB::table('subscriptions')
                //  ->where('stripe_id', $paymentIntent->id)
                //  ->update(array(
                //  'stripe_status' => 'canceled',
                //  'ends_at' => date("Y/m/d h:i:s"),
                // ));

                $website_link = env('WEBSITE_URL');

                //     $html='<html>
                //     Hi, '.$user[0]->name.'<br><br>
                //     We hope you are enjoying streamlyTV,<br>
                //     as a reminder, your subscription is renewed.<br><br>
                //     View your receipt by clicking <a href="'.$recipt.'">here</a><br>
                //     If you have any questions or if you find an error, please contact us at contact@streamlytv.com.<br><br>
                //     Thanks<br>
                //     Team Streamly

                // </html>';


                $html = '<html>
                    Hi ' . ucwords($user[0]->name) . ',<br><br>
                    We hope you are enjoying streamlyTV.<br>
                    As a reminder, your subscription is renewed.<br><br>
                    The details are as follows:<br>
                    <strong>
                    Email: ' . $user[0]->email . '<br>
                    Next Billing Date: ' . $nextBillingDate . '<br>
                    Amount: $' . $amount . '<br>
                    </strong>
                    <br>
                    View your Receipt <a href="' . $recipt . '""><strong>Here</strong></a><br>
                    If you have any questions or if you find an error, please contact us at <a href="mailto:contact@streamlytv.com">contact@streamlytv.com</a>.<br><br>
                    <b>Thanks,<br>
                    Team Streamly<b>
                    </html>';

                Mail::send('emails.general', ["html" => $html], function ($message) use ($request, $user) {
                    $message->to($user[0]->email, $user[0]->name)
                        ->subject('Your Subscription Renewed ' . env('APP_NAME'));
                });
                // }
                break;

            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;



                if ($paymentIntent->last_payment_error->code == "expired_card") {

                    //$cardExpire = DB::table('subscriptions')->where('stripe_id', $paymentIntent->id)->get('user_id');
                    $user = DB::table('users')->where('stripe_id', $paymentIntent->customer)->get();

                    //$recipt = $paymentIntent->charges->data->receipt_url;

                    $this->activity($user->first()->id)->event("Subscription")
                        ->eventType(ActivityEventType::CREATED)
                        ->descriptionSimple("Subscription payment failed")
                        ->notification()
                        ->isUser()
                        ->isAdmin()
                        ->properties([
                            "user" => $user,
                            "subscription" => $paymentIntent
                        ])->save();

                    $fcmTokens = DB::table('session_table')
                        ->where('users_id', $user[0]->id)
                        ->where('status', 1)
                        ->whereNotNull('fcm_token')
                        ->pluck('fcm_token')->toArray();

                    // $fcmResults = [];
                    // foreach ($fcm_token as $tokens) {
                    //     array_push($fcmResults, $tokens->fcm_token);
                    // }
                    // ;

                    if ($fcmTokens != null && count($fcmTokens) > 0) {
                        foreach ($fcmTokens as $token) {
                            $this->notification->send($token, "Subscription", "Payment failed. Please renew your card to enjoy streamlytv continously.");
                        }
                    }

                    // if (count($fcm_token) == 0) {
                    // } else {

                    //     $this->notification->send($fcmResults, "Subscription", "Payment failed. Please renew your card to enjoy streamlytv continously.");
                    //     //     $curl = curl_init();

                    //     //     curl_setopt_array($curl, array(
                    //     //         CURLOPT_URL => 'https://streamlytv.com/api/auth/sendNotification',
                    //     //         CURLOPT_RETURNTRANSFER => true,
                    //     //         CURLOPT_ENCODING => '',
                    //     //         CURLOPT_MAXREDIRS => 10,
                    //     //         CURLOPT_TIMEOUT => 0,
                    //     //         CURLOPT_FOLLOWLOCATION => true,
                    //     //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    //     //         CURLOPT_CUSTOMREQUEST => 'POST',
                    //     //         CURLOPT_POSTFIELDS => '{
                    //     //       "registration_ids": ' . json_encode($fcmResults) . ',
                    //     //       "title": "Payment Failed",
                    //     //       "body": "HI ' . ucwords($user[0]->name) . ' please renew your card to enjoy streamlytv continously."
                    //     //      }',
                    //     //         CURLOPT_HTTPHEADER => array(
                    //     //             'Content-Type: application/json'
                    //     //         ),
                    //     //     ));
                    //     //     $response = curl_exec($curl);
                    //     //     curl_close($curl);
                    // }
                    // ;

                    $website_link = env('WEBSITE_URL');

                    $html = '<html>
                            Hi ' . ucwords($user[0]->name) . ',<br><br>
                            Your card is expired.<br>
                            Please visit my accounts by visiting<br>
                            ' . env('APP_URL') . '/user/user-settings
                            <br><br>
                            <b>Thanks,<br>
                            Team Streamly<b>

                        </html>';

                    Mail::send('emails.general', ["html" => $html], function ($message) use ($request, $user) {
                        $message->to($user[0]->email, $user[0]->name);
                        $message->bcc('annu.sehrawat@oodles.io', 'mina@neovix.com')
                            ->subject('Your Card Expired ' . env('APP_NAME'));
                    });
                }
                break;

            case 'customer.subscription.trial_will_end':
                $paymentIntent = $event->data->object;


                if ($paymentIntent->status == "trialing") {

                    //$cardExpire = DB::table('subscriptions')->where('stripe_id', $paymentIntent->id)->get('user_id');
                    $user = DB::table('users')->where('stripe_id', $paymentIntent->customer)->get();

                    //$recipt = $paymentIntent->charges->data->receipt_url;

                    $this->activity($user->first()->id)->event("Subscription")
                        ->eventType(ActivityEventType::TRIALEND)
                        ->descriptionSimple("Trial pack will end" . date("d/m/y", $paymentIntent->trial_end))
                        ->notification()
                        ->isUser()
                        ->isAdmin()
                        ->properties([
                            "user" => $user,

                        ])->save();

                    $fcmTokens = DB::table('session_table')
                        ->where('users_id', $user[0]->id)
                        ->where('status', 1)
                        ->whereNotNull('fcm_token')
                        ->pluck('fcm_token')->toArray();

                    // $fcmResults = [];
                    // foreach ($fcm_token as $tokens) {
                    //     array_push($fcmResults, $tokens->fcm_token);
                    // }
                    // ;

                    if ($fcmTokens != null && count($fcmTokens) > 0) {
                        foreach ($fcmTokens as $token) {
                            $this->notification->send($token, "Subscription", "Your trail period is ending on" . date("d/m/y", $paymentIntent->trial_end));
                        }
                    }

                    // if (count($fcm_token) == 0) {
                    // } else {

                    //     $this->notification->send($fcmResults, "Subscription", "Your trail period is ending on" . date("d/m/y", $paymentIntent->trial_end));
                    //     // $curl = curl_init();

                    //     // curl_setopt_array($curl, array(
                    //     //     CURLOPT_URL => 'https://streamlytv.com/api/auth/sendNotification',
                    //     //     CURLOPT_RETURNTRANSFER => true,
                    //     //     CURLOPT_ENCODING => '',
                    //     //     CURLOPT_MAXREDIRS => 10,
                    //     //     CURLOPT_TIMEOUT => 0,
                    //     //     CURLOPT_FOLLOWLOCATION => true,
                    //     //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    //     //     CURLOPT_CUSTOMREQUEST => 'POST',
                    //     //     CURLOPT_POSTFIELDS => '{
                    //     //   "registration_ids": ' . json_encode($fcmResults) . ',
                    //     //   "title": "Trial period is ending soon",
                    //     //   "body": "HI ' . ucwords($user[0]->name) . ' your trail period is ending on ' . date("Y-m-d", $paymentIntent->trial_end) . '"
                    //     //  }',
                    //     //     CURLOPT_HTTPHEADER => array(
                    //     //         'Content-Type: application/json'
                    //     //     ),
                    //     // ));
                    //     // $response = curl_exec($curl);
                    //     // curl_close($curl);
                    // }
                    // ;

                    $website_link = env('WEBSITE_URL');

                    $html = '<html>
                            Hi ' . ucwords($user[0]->name) . ',<br><br>
                            Your trail period is ending on ' . date("Y-m-d", $paymentIntent->trial_end) . '.
                            after the trial period ends, your credit card will be billed the monthly subscription amount.<br><br>
                            If you have any questions or if you find an error, please contact us at contact@streamlytv.com.
                            <br><br>
                            <b>
                            Thanks<br>
                            Team Streamly<b>

                        </html>';

                    Mail::send('emails.general', ["html" => $html], function ($message) use ($request, $user) {
                        $message->to($user[0]->email, $user[0]->name);
                        $message->bcc('annu.sehrawat@oodles.io')
                            ->subject('Your trail period is ending soon ' . env('APP_NAME'));
                    });
                }

                break;

            case 'customer.subscription.updated':
                $subscription = $event->data->object;
                // Log::info("event : customer.subscription.updated");
                // Log::info("subscription object : " . $subscription);

                // $this->notification->send($this->token, "Subscription", "Your trial period has ended, and your subscription is now active.");
                // exit;


                $user = DB::table('users')
                    ->where('stripe_id', $subscription->customer)
                    ->first();
                if ($user == null) {
                    Log::info("customer.subscription.updated: User not found");
                    exit;
                }

                $this->activity($user->id)->event("Subscription")
                    ->eventType($subscription->status)
                    ->descriptionSimple("Your trial period has ended, and your subscription is now active.")
                    ->notification()
                    ->isUser()
                    ->isAdmin()
                    ->properties([
                        "user" => $user,
                        "subscription" => $subscription,
                    ])->save();

                // $fcmTokens = DB::table('session_table')
                //     ->where('users_id', $user[0]->id)
                //     ->where('status', 1)
                //     ->whereNotNull('fcm_token')
                //     ->get('fcm_token');

                // $fcmResults = [];
                // foreach ($fcm_token as $tokens) {
                //     array_push($fcmResults, $tokens->fcm_token);
                // }
                // if ($fcmTokens != null && count($fcmTokens) > 0) {
                //     foreach ($fcmTokens as $token) {
                //         $this->notification->send($token, "Subscription", "Your trial period has ended, and your subscription is now active.");
                //     }
                // }
                // if ($fcmResults > 0) {
                //     $this->notification->send($fcmResults, "Subscription", "Your trial period has ended, and your subscription is now active.");
                // }


                $updateData = [
                    'stripe_status' => $subscription->status,
                    'stripe_plan' => $subscription->plan->id,
                    'quantity' => $subscription->quantity,
                    'livemode' => (int) $subscription->livemode,
                    'updated_at' => now(),
                ];

                if ($subscription->status === 'active') {
                    $updateData['trial_ends_at'] = null;
                }

                DB::table('subscriptions')
                    ->where('user_id', $user->id)
                    ->update($updateData);


                $plan = $subscription->plan;
                $amount = number_format($plan->amount / 100, 2); // Convert cents to dollars
                $interval = $plan->interval; // e.g., "month", "year"
                $quantity = $subscription->quantity; // Subscription quantity
                $planName = $plan->nickname ?: 'Default Plan';

                $website_link = env('WEBSITE_URL');

                $html = '
                                <html>
                                    Hi, ' . ucwords($user->name) . '<br><br>
                                    Your trial period has ended, and your subscription is now active. You have been charged as per the following details:<br><br>
                                    <b>Subscription Details:</b><br>
                                    Plan: ' . htmlspecialchars($planName) . '<br>
                                    Amount: $' . $amount . '<br>
                                    Quantity: ' . $quantity . '<br>
                                    Billing Interval: ' . ucfirst($interval) . '<br><br>
                                    Thank you for choosing to stay with us!<br><br>
                                    If you have any questions or find an error, please contact us at contact@streamlytv.com.<br><br>
                                    <b>Thanks,<br>
                                    Team Streamly</b>
                                </html>';

                Mail::send('emails.general', ["html" => $html], function ($message) use ($user) {
                    $message->to($user->email, $user->name)
                        ->bcc('annu.sehrawat@oodles.io')
                        ->subject('Subscription Activated - ' . env('APP_NAME'));
                });


        }
    }
}
