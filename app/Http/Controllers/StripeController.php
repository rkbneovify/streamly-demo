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
    protected $bcc = ["projects.neovify@gmail.com", "mina@neovix.com"];
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


            case 'invoice.paid':
                $invoice = $event->data->object;



                $subscriptionId = $invoice->subscription;
                $subscription = \Stripe\Subscription::retrieve($subscriptionId);
                $plan = $subscription->items->data[0]->plan;

                $planName = $plan->nickname ?? 'Default Plan';
                $amount = number_format($plan->amount / 100, 2);
                $interval = $plan->interval;
                $quantity = $subscription->items->data[0]->quantity;

                switch ($invoice->billing_reason) {
                    case 'subscription_create':
                        // subscription without trial
                        if ($invoice->amount_paid > 0) {
                            $message = 'You have subscribed successfully.';
                            $user = DB::table('users')
                                ->where('stripe_id', $invoice->customer)
                                ->first();
                            $this->activity($user->id)->event("Subscription")
                                ->eventType("Subscription")
                                ->descriptionSimple($message)
                                ->notification()
                                ->isUser()
                                ->isAdmin()
                                ->properties([
                                    "user" => $user,
                                    "details" => $invoice,
                                ])->save();
                            $fcmTokens = DB::table('session_table')
                                ->where('users_id', $user->id)
                                ->where('is_main_account', 1)
                                ->where('status', 1)
                                ->whereNotNull('fcm_token')
                                ->pluck('fcm_token')->toArray();
                            if ($fcmTokens != null && count($fcmTokens) > 0) {
                                foreach ($fcmTokens as $token) {
                                    $this->notification->send($token, "Subscription", $message);
                                }
                            }

                            $html = '
                            <html>
                                Hi, ' . ucwords($user->name) . '<br><br>

                                Your subscription has been successfully activated. You have been charged as per the following details:<br><br>

                                <b>Subscription Details:</b><br>
                                Plan: ' . htmlspecialchars($planName) . '<br>
                                Amount: $' . $amount . '<br>
                                Quantity: ' . $quantity . '<br>
                                Billing Interval: ' . ucfirst($interval) . '<br><br>

                                You can now enjoy all the features included in your plan.<br><br>

                                Thank you for choosing to stay with us!<br><br>

                                If you have any questions or find an error, please contact us at contact@streamlytv.com.<br><br>

                                <b>Thanks,<br>
                                Team Streamly</b>
                            </html>';

                            Mail::send('emails.general', ["html" => $html], function ($message) use ($user) {
                                $message->to($user->email, $user->name)
                                    ->bcc($this->bcc)
                                    ->subject('Your streamly subscription is now active - ' . env('APP_NAME'));
                            });
                        } else {
                            // subscription with trial

                            $message = 'Your free trial has started successfully.';
                            $user = DB::table('users')
                                ->where('stripe_id', $invoice->customer)
                                ->first();
                            $this->activity($user->id)->event("Subscription")
                                ->eventType("Subscription")
                                ->descriptionSimple($message)
                                ->notification()
                                ->isUser()
                                ->isAdmin()
                                ->properties([
                                    "user" => $user,
                                    "details" => $invoice,
                                ])->save();
                            $fcmTokens = DB::table('session_table')
                                ->where('users_id', $user->id)
                                ->where('is_main_account', 1)
                                ->where('status', 1)
                                ->whereNotNull('fcm_token')
                                ->pluck('fcm_token')->toArray();
                            if ($fcmTokens != null && count($fcmTokens) > 0) {
                                foreach ($fcmTokens as $token) {
                                    $this->notification->send($token, "Subscription", $message);
                                }
                            }

                            $html = '
                                <html>
                                    Hi, ' . ucwords($user->name) . '<br><br>

                                    Your free trial has started successfully.<br>
                                    Enjoy all the features included in your trial plan during the trial period.<br><br>

                                    <b>Trial Details:</b><br>
                                    Plan: ' . htmlspecialchars($planName) . '<br>
                                    Quantity: ' . $quantity . '<br>
                                    Billing Interval: ' . ucfirst($interval) . '<br>
                                    Trial Ends On: ' . date('d M Y', $subscription->trial_end) . '<br><br>

                                    Once your trial ends, your subscription will become active automatically as per your selected plan.<br><br>

                                    If you have any questions or find an error, please contact us at contact@streamlytv.com.<br><br>

                                    <b>Thanks,<br>
                                    Team Streamly</b>
                                </html>';

                            Mail::send('emails.general', ["html" => $html], function ($message) use ($user) {
                                $message->to($user->email, $user->name)
                                    ->bcc($this->bcc)
                                    ->subject('Your free trial has started - ' . env('APP_NAME'));
                            });
                        }
                        break;

                    case 'subscription_cycle':
                        $message = 'Your subscription has been renewed successfully.';
                        $user = DB::table('users')
                            ->where('stripe_id', $invoice->customer)
                            ->first();
                        $this->activity($user->id)->event("Subscription")
                            ->eventType("Subscription")
                            ->descriptionSimple($message)
                            ->notification()
                            ->isUser()
                            ->isAdmin()
                            ->properties([
                                "user" => $user,
                                "details" => $invoice,
                            ])->save();
                        $fcmTokens = DB::table('session_table')
                            ->where('users_id', $user->id)
                            ->where('is_main_account', 1)
                            ->where('status', 1)
                            ->whereNotNull('fcm_token')
                            ->pluck('fcm_token')->toArray();
                        if ($fcmTokens != null && count($fcmTokens) > 0) {
                            foreach ($fcmTokens as $token) {
                                $this->notification->send($token, "Subscription", $message);
                            }
                        }

                        $html = '
                        <html>
                            Hi, ' . ucwords($user->name) . '<br><br>

                            Your subscription has been successfully renewed. Your payment has been processed as per the following details:<br><br>

                            <b>Subscription Details:</b><br>
                            Plan: ' . htmlspecialchars($planName) . '<br>
                            Amount: $' . $amount . '<br>
                            Quantity: ' . $quantity . '<br>
                            Billing Interval: ' . ucfirst($interval) . '<br><br>

                            Thank you for continuing with us! We appreciate your support.<br><br>

                            If you have any questions or notice any issues, please contact us at contact@streamlytv.com.<br><br>

                            <b>Thanks,<br>
                            Team Streamly</b>
                        </html>';

                        Mail::send('emails.general', ["html" => $html], function ($message) use ($user) {
                            $message->to($user->email, $user->name)
                                ->bcc($this->bcc)
                                ->subject('Your subscription has been renewed - ' . env('APP_NAME'));
                        });
                        break;


                }


                break;


            case 'customer.subscription.updated':

                $eventObj = $event->data->object;

                // ❗ Only handle scheduled cancellation
                if (!$eventObj->cancel_at_period_end) {
                    return;
                }

                $subscription = DB::table('subscriptions')
                    ->where('stripe_id', $eventObj->id)
                    ->latest()
                    ->first();

                if (!$subscription)
                    return;

                $user = DB::table('users')->where('id', $subscription->user_id)->first();
                if (!$user)
                    return;

                $message = 'Your subscription will be canceled at the end of billing period.';

                // 🔔 Notification
                $fcmTokens = DB::table('session_table')
                    ->where('users_id', $user->id)
                    ->where('is_main_account', 1)
                    ->where('status', 1)
                    ->whereNotNull('fcm_token')
                    ->pluck('fcm_token')
                    ->toArray();

                foreach ($fcmTokens as $token) {
                    $this->notification->send($token, "Subscription", $message);
                }

                // 📝 Activity log
                $this->activity($user->id)->event("Subscription")
                    ->eventType("Subscription")
                    ->descriptionSimple($message)
                    ->notification()
                    ->isUser()
                    ->isAdmin()
                    ->properties([
                        "user" => $user,
                        "subscription" => $eventObj
                    ])->save();

                // 🗄️ DB update (still active until period end)
                DB::table('subscriptions')
                    ->where('stripe_id', $eventObj->id)
                    ->update([
                        'stripe_status' => $eventObj->status, // active
                        'ends_at' => date("Y-m-d H:i:s", $eventObj->current_period_end)
                    ]);

                // 📧 Email
                $emailMsg = "Your subscription auto-renewal has been successfully cancelled.<br><br>
                    Your current plan will remain active until " . date("d M Y", $eventObj->current_period_end) . ", after which it will expire automatically, and you will not be charged further.<br><br>
                    If you wish to renew your subscription in the future, you can do so by visiting:<br>" . env('APP_URL');

                $html = '<html>
                        Hi, ' . ucwords($user->name) . '<br><br>
                        ' . $emailMsg . '
                        <br><br>
                        <b>Thanks,<br>Team Streamly</b>
                        </html>';

                Mail::send('emails.general', ["html" => $html], function ($message) use ($user, $eventObj) {
                    $message->to($user->email, $user->name)
                        ->subject('Auto-Renewal Cancelled – Subscription Active Until ' . date("d M Y", $eventObj->current_period_end));
                });
                break;



            case 'invoice.payment_failed':
                $message = 'Your subscription payment failed. Please update your payment method.';
                $eventObj = $event->data->object;
                $user = DB::table('users')->where('stripe_id', $eventObj->customer)->first();
                if ($user == null) {
                    return;
                }

                $fcmTokens = DB::table('session_table')
                    ->where('users_id', $user->id)
                    ->where('is_main_account', 1)
                    ->where('status', 1)
                    ->whereNotNull('fcm_token')
                    ->pluck('fcm_token')->toArray();

                if ($fcmTokens != null && count($fcmTokens) > 0) {
                    foreach ($fcmTokens as $token) {
                        $this->notification->send($token, "Subscription", $message);
                    }
                }
                $this->activity($user->id)->event("Subscription")
                    ->eventType("Subscription")
                    ->descriptionSimple($message)
                    ->notification()
                    ->isUser()
                    ->isAdmin()
                    ->properties([
                        "user" => $user,
                        "subscription" => $eventObj
                    ])->save();

                $html = '<html>
                            Hi ' . ucwords($user->name) . ',<br>
                            <br>
                            Your subscription payment failed .<br>
                            Please visit my accounts by visiting<br>
                            ' . env('APP_URL') . '/user/user-settings
                            <br><br>
                            <b>Thanks,<br>
                            Team Streamly<b>

                        </html>';

                Mail::send('emails.general', ["html" => $html], function ($message) use ($user) {
                    $message->to($user->email, $user->name);
                    $message->bcc($this->bcc)
                        ->subject('Your subscription payment failed' . env('APP_NAME'));
                });

                break;

            case 'customer.subscription.trial_will_end':

                $eventObj = $event->data->object;
                $message = "Your trial will end soon " . date("d/m/y", $eventObj->trial_end) . ". After the trial period ends, your card will be automatically charged the monthly subscription fee.";
                $user = DB::table('users')->where('stripe_id', $eventObj->customer)->first();
                if ($user == null) {
                    return;
                }
                $this->activity($user->id)->event("Subscription")
                    ->eventType("Subscription")
                    ->descriptionSimple($message)
                    ->notification()
                    ->isUser()
                    ->isAdmin()
                    ->properties([
                        "user" => $user,

                    ])->save();
                $fcmTokens = DB::table('session_table')
                    ->where('users_id', $user->id)
                    ->where('status', 1)
                    ->where('is_main_account', 1)
                    ->whereNotNull('fcm_token')
                    ->pluck('fcm_token')->toArray();

                if ($fcmTokens != null && count($fcmTokens) > 0) {
                    foreach ($fcmTokens as $token) {
                        $this->notification->send($token, "Subscription", $message);
                    }
                }

                $html = '<html>
                            Hi ' . ucwords($user->name) . ',<br><br>
                            Your trail period is ending on ' . date("Y-m-d", $eventObj->trial_end) . '.
                            After the trial period ends, your credit card will be billed the monthly subscription amount.<br><br>
                            If you have any questions or if you find an error, please contact us at contact@streamlytv.com.
                            <br><br>
                            <b>
                            Thanks<br>
                            Team Streamly<b>

                        </html>';

                Mail::send('emails.general', ["html" => $html], function ($message) use ($request, $user) {
                    $message->to($user->email, $user->name);
                    $message->bcc($this->bcc)
                        ->subject('Your trail period is ending soon ' . env('APP_NAME'));
                });

                break;

            case 'customer.subscription.deleted':

                $message = 'Your subscription has been canceled successfully.';
                $eventObj = $event->data->object;
                $subscription = DB::table('subscriptions')->where('stripe_id', $eventObj->id)->latest()->first();
                if ($subscription == null) {
                    return;
                }
                $user = DB::table('users')->where('id', $subscription->user_id)->first();
                if ($user == null) {
                    return;
                }
                $fcmTokens = DB::table('session_table')
                    ->where('users_id', $user->id)
                    ->where('is_main_account', 1)
                    ->where('status', 1)
                    ->whereNotNull('fcm_token')
                    ->pluck('fcm_token')->toArray();

                if ($fcmTokens != null && count($fcmTokens) > 0) {
                    foreach ($fcmTokens as $token) {
                        $this->notification->send($token, "Subscription", $message);
                    }
                }
                $this->activity($user->id)->event("Subscription")
                    ->eventType("Subscription")
                    ->descriptionSimple($message)
                    ->notification()
                    ->isUser()
                    ->isAdmin()
                    ->properties([
                        "user" => $user,
                        "subscription" => $eventObj
                    ])->save();

                DB::table('subscriptions')
                    ->where('stripe_id', $eventObj->id)
                    ->update([
                        'stripe_status' => 'canceled',
                        'ends_at' => date("Y/m/d h:i:s")
                    ]);

                // mail

                $html = '<html>
                    Hi, ' . ucwords($user->name) . '<br><br>
                    Your StreamlyTV subscription is now canceled.<br>
                    To renew your subscription, please visit: ' . env('APP_URL') . ', login, and select a plan !.
                    <br><br>
                    <b>Thanks,<br>
                    Team Streamly<b>
                </html>';

                Mail::send('emails.general', ["html" => $html], function ($message) use ($user) {
                    $message->to($user->email, $user->name);
                    $message->bcc($this->bcc)
                        ->subject('Your subscription has been canceled. ' . env('APP_NAME'));
                });

                break;
        }
    }
}
