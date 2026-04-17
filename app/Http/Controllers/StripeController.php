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

                // Common dates
                $nextBillingDate = date('d M Y', $subscription->current_period_end);
                $trialEndDate = $subscription->trial_end
                    ? date('d M Y', $subscription->trial_end)
                    : null;

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

                    Thanks for Subscribing! ' . env('APP_NAME') . '.<br>
                    Your subscription is now active.<br><br>

                    <b>Subscription Details:</b><br>
                    Plan: ' . htmlspecialchars($planName) . '<br>
                    Amount: $' . $amount . '<br>
                    Quantity: ' . $quantity . '<br>
                    Billing Interval: ' . ucfirst($interval) . '<br>
                    Next Billing Date: ' . $nextBillingDate . '<br><br>

                    Start watching StreamlyTV here:<br>
                    <a href="' . env('APP_URL') . '" target="_blank"
                        style="display:inline-block;padding:10px 18px;margin-top:8px;
                        background-color:#4CAF50;color:#ffffff;text-decoration:none;
                        border-radius:5px;font-weight:bold;">
                        ▶ Start Watching
                    </a><br><br>

                    Or download the StreamlyTV App on your preferred device:<br>
                    Apple • Apple TV • Android • Amazon Fire • Roku • Smart TVs<br><br>

                    If you have any questions, contact us at contact@streamlytv.com.<br><br>

                    Thanks!<br>
                    Team Streamly
                </html>';

                            Mail::send('emails.general', ["html" => $html], function ($message) use ($user) {
                                $message->to($user->email, $user->name)
                                    ->bcc($this->bcc)
                                    ->subject('Your Subscription is Now Active - ' . env('APP_NAME'));
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

                 
                    Your free trial has started successfully.<br><br>

                    <b>Trial Details:</b><br>
                    Plan: ' . htmlspecialchars($planName) . '<br>
                    Quantity: ' . $quantity . '<br>
                    Billing Interval: ' . ucfirst($interval) . '<br>
                    Trial Ends On: ' . $trialEndDate . '<br>
                    First Billing Date: ' . $trialEndDate . '<br><br>

                    Enjoy full access during your trial period.<br>
                    Your subscription will be activated automatically after trial.<br><br>

                    Start watching StreamlyTV here:<br>
                    <a href="' . env('APP_URL') . '" target="_blank"
                        style="display:inline-block;padding:10px 18px;margin-top:8px;
                        background-color:#4CAF50;color:#ffffff;text-decoration:none;
                        border-radius:5px;font-weight:bold;">
                        ▶ Start Watching
                    </a><br><br>

                    Or download the StreamlyTV App on your preferred device:<br>
                    Apple • Apple TV • Android • Amazon Fire • Roku • Smart TVs<br><br>

                    <b>Note:</b> Cancel anytime before ' . $trialEndDate . ' to avoid charges.<br><br>

                    If you have any questions, contact us at contact@streamlytv.com.<br><br>

                    Thanks!<br>
                    Team Streamly
                </html>';

                            Mail::send('emails.general', ["html" => $html], function ($message) use ($user) {
                                $message->to($user->email, $user->name)
                                    ->bcc($this->bcc)
                                    ->subject('Your Free Trial Has Started - ' . env('APP_NAME'));
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
                            Billing Interval: ' . ucfirst($interval) . '<br>
                            Next Billing Date: ' . $nextBillingDate . '<br><br>

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


            // case 'customer.subscription.updated':

            //     $eventObj = $event->data->object;
            //     $subscription = DB::table('subscriptions')
            //         ->where('stripe_id', $eventObj->id)
            //         ->latest()
            //         ->first();
            //     $user = DB::table('users')->where('id', $subscription->user_id)->first();
            //     if (!$subscription)
            //         return;


            //     if (!$user)
            //         return;

            //     // subscription: update status and mode

            //     DB::table('subscriptions')
            //         ->where('stripe_id', $eventObj->id)
            //         ->update([
            //             'stripe_status' => $eventObj->status,
            //             'livemode' => $eventObj->livemode,
            //         ]);
            //     // ❗ Only handle scheduled cancellation
            //     if (!$eventObj->cancel_at_period_end) {
            //         return;
            //     }





            //     $message = 'Your subscription will be canceled at the end of billing period.';

            //     // 🔔 Notification
            //     $fcmTokens = DB::table('session_table')
            //         ->where('users_id', $user->id)
            //         ->where('is_main_account', 1)
            //         ->where('status', 1)
            //         ->whereNotNull('fcm_token')
            //         ->pluck('fcm_token')
            //         ->toArray();

            //     foreach ($fcmTokens as $token) {
            //         $this->notification->send($token, "Subscription", $message);
            //     }

            //     // 📝 Activity log
            //     $this->activity($user->id)->event("Subscription")
            //         ->eventType("Subscription")
            //         ->descriptionSimple($message)
            //         ->notification()
            //         ->isUser()
            //         ->isAdmin()
            //         ->properties([
            //             "user" => $user,
            //             "subscription" => $eventObj
            //         ])->save();

            //     // 🗄️ DB update (still active until period end)
            //     DB::table('subscriptions')
            //         ->where('stripe_id', $eventObj->id)
            //         ->update([
            //             'stripe_status' => $eventObj->status, // active
            //             'ends_at' => date("Y-m-d H:i:s", $eventObj->current_period_end)
            //         ]);


            //     $endDate = date("d M Y", $eventObj->current_period_end);

            //     $html = '
            //     <html>
            //         Hi, ' . ucwords($user->name) . '<br><br>

            //         Your subscription auto-renewal has been successfully cancelled.<br><br>

            //         <b>What this means:</b><br>
            //         • Your subscription will remain active until <b>' . $endDate . '</b><br>
            //         • You will not be charged again after this date<br>
            //         • Your access will end automatically after this period<br><br>

            //         <b>Subscription Ends On:</b> ' . $endDate . '<br><br>

            //         You can continue enjoying all features until the end date.<br><br>



            //         If you need help, contact us at contact@streamlytv.com.<br><br>

            //         Thanks for being with us,<br>
            //         <b>Team Streamly</b>
            //     </html>';

            //     Mail::send('emails.general', ["html" => $html], function ($message) use ($user, $endDate) {
            //         $message->to($user->email, $user->name)
            //             ->subject('Subscription Cancelled – Active Until ' . $endDate . ' | ' . env('APP_NAME'));
            //     });
            //     break;


            case 'customer.subscription.updated':

                $eventObj = $event->data->object;

                // 🔍 Get subscription
                $subscription = DB::table('subscriptions')
                    ->where('stripe_id', $eventObj->id)
                    ->latest()
                    ->first();

                if (!$subscription)
                    return;

                // 👤 Get user
                $user = DB::table('users')
                    ->where('id', $subscription->user_id)
                    ->first();

                if (!$user)
                    return;




                // ============================
                // 🔄 Always update base fields
                // ============================
                DB::table('subscriptions')
                    ->where('stripe_id', $eventObj->id)
                    ->update([
                        'stripe_status' => $eventObj->status,
                        'livemode' => $eventObj->livemode,
                    ]);


                // ============================
                // 🔁 RESUME SUBSCRIPTION
                // ============================
                if (!$eventObj->cancel_at_period_end && $eventObj->canceled_at == null) {

                    DB::table('subscriptions')
                        ->where('stripe_id', $eventObj->id)
                        ->update([
                            'ends_at' => null
                        ]);

                    $message = 'Your subscription has been resumed successfully.';

                    // 🔔 Send Notification
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

                    // 📝 Activity Log
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

                    // 📧 Email
                    $html = '
        <html>
            Hi, ' . ucwords($user->name) . '<br><br>

            Your subscription has been <b>resumed successfully</b>.<br><br>

            <b>What this means:</b><br>
            • Auto-renewal is enabled again<br>
            • Your subscription will continue without interruption<br><br>

            Thank you for staying with us!<br><br>

            <b>Team Streamly</b>
        </html>';

                    Mail::send('emails.general', ["html" => $html], function ($message) use ($user) {
                        $message->to($user->email, $user->name)
                            ->subject('Subscription Resumed | ' . env('APP_NAME'));
                    });

                    break;
                }


                // ============================
                // ❌ CANCEL AT PERIOD END
                // ============================
                if ($eventObj->cancel_at_period_end) {

                    $endDateTime = date("Y-m-d H:i:s", $eventObj->current_period_end);
                    $endDate = date("d M Y", $eventObj->current_period_end);

                    DB::table('subscriptions')
                        ->where('stripe_id', $eventObj->id)
                        ->update([
                            'ends_at' => $endDateTime
                        ]);

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

                    // 📝 Activity Log
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

                    // 📧 Email
                    $html = '
        <html>
            Hi, ' . ucwords($user->name) . '<br><br>

            Your subscription auto-renewal has been successfully cancelled.<br><br>

            <b>What this means:</b><br>
            • Your subscription will remain active until <b>' . $endDate . '</b><br>
            • You will not be charged again after this date<br>
            • Your access will end automatically after this period<br><br>

            <b>Subscription Ends On:</b> ' . $endDate . '<br><br>

            You can continue enjoying all features until the end date.<br><br>

            If you need help, contact us at contact@streamlytv.com.<br><br>

            Thanks for being with us,<br>
            <b>Team Streamly</b>
        </html>';

                    Mail::send('emails.general', ["html" => $html], function ($message) use ($user, $endDate) {
                        $message->to($user->email, $user->name)
                            ->subject('Subscription Cancelled – Active Until ' . $endDate . ' | ' . env('APP_NAME'));
                    });


                }


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

                $html = '
                <html>
                    Hi ' . ucwords($user->name) . ',<br><br>

                    We were unable to process your subscription payment.<br><br>

                    <b>What you can do:</b><br>
                    • Update your payment method<br>
                    • Retry the payment to continue enjoying uninterrupted access<br><br>

                   
                    If the payment is not completed, your subscription may be paused or cancelled.<br><br>

                    If you need any help, contact us at contact@streamlytv.com.<br><br>

                    Thanks,<br>
                    <b>Team Streamly</b>
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



                $trialEndDate = date("d M Y", $eventObj->trial_end);
                $html = '
                    <html>
                        Hi ' . ucwords($user->name) . ',<br><br>

                        Your free trial is ending soon on <b>' . $trialEndDate . '</b>.<br><br>

                        After your trial ends, your subscription will automatically continue and your payment method will be charged as per your selected plan.<br><br>

                        To continue enjoying uninterrupted access, no action is required.<br><br>
                       

                      

                        If you have any questions or need assistance, contact us at contact@streamlytv.com.<br><br>

                        Thanks,<br>
                        <b>Team Streamly</b>
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

                $html = '
                <html>
                    Hi, ' . ucwords($user->name) . '<br><br>

                    Your StreamlyTV subscription has been <b>cancelled successfully</b>.<br><br>

                    Your access to all premium features has ended immediately, and you will not be charged further.<br><br>

                    If you need any help, contact us at contact@streamlytv.com.<br><br>

                    Thanks for being with us,<br>
                    <b>Team Streamly</b>
                </html>';

                Mail::send('emails.general', ["html" => $html], function ($message) use ($user) {
                    $message->to($user->email, $user->name)
                        ->bcc($this->bcc)
                        ->subject('Your Subscription Has Been Cancelled – ' . env('APP_NAME'));
                });

                break;



            case 'payment_intent.succeeded':

                $paymentIntent = $event->data->object;

                // ❌ Skip if not subscription payment
                if (empty($paymentIntent->invoice)) {
                    break;
                }

                // ✅ Get Invoice
                $invoice = \Stripe\Invoice::retrieve($paymentIntent->invoice);

                // ❌ Only allow required cases
                if (!in_array($invoice->billing_reason, ['subscription_create', 'subscription_cycle'])) {
                    break;
                }

                // ✅ Get Subscription
                $subscription = \Stripe\Subscription::retrieve($invoice->subscription);

                // ✅ Plan Details
                $plan = $subscription->items->data[0]->plan;
                $planName = $plan->nickname ?? 'Default Plan';

                // ✅ Get User
                $user = DB::table('users')
                    ->where('stripe_id', $paymentIntent->customer)
                    ->first();

                if (!$user) {
                    break;
                }

                // ✅ Common Data
                $amount = $paymentIntent->amount / 100;
                $receiptUrl = $paymentIntent->charges->data[0]->receipt_url ?? null;
                $nextBillingDate = date('d M Y', $subscription->current_period_end);
                $trialEndDate = $subscription->trial_end
                    ? date('d M Y', $subscription->trial_end)
                    : null;

                /**
                 * ----------------------------------------
                 * 🆕 SUBSCRIPTION CREATE (Trial or Paid)
                 * ----------------------------------------
                 */
                if ($invoice->billing_reason === 'subscription_create') {

                    // ✅ TRIAL CASE
                    if ($invoice->amount_paid == 0 && $trialEndDate) {

                        $message = "Your free trial has started.";
                        $title = "Free Trial Started";
                        $subject = 'Your Free Trial Started - ' . env('APP_NAME');

                        $html = '<html>
            Hi ' . ucwords($user->name) . ',<br><br>

            🎉 Your free trial has started successfully.<br><br>

            The details are as follows:<br>
            <strong>
            Plan: ' . htmlspecialchars($planName) . '<br>
            Email: ' . $user->email . '<br>
            Trial Ends On: ' . $trialEndDate . '<br>
            </strong>
            <br>

            Enjoy full access during your trial period.<br><br>

            <b>Note:</b> Cancel before ' . $trialEndDate . ' to avoid charges.<br><br>

            If you have any questions, contact us at 
            <a href="mailto:contact@streamlytv.com">contact@streamlytv.com</a>.<br><br>

            <b>Thanks,<br>
            Team Streamly</b>
            </html>';

                    } else {

                        // ✅ PAID SUBSCRIPTION
                        $message = "Your subscription has been activated successfully.";
                        $title = "Subscription Activated";
                        $subject = 'Welcome! Your Subscription is Active - ' . env('APP_NAME');

                        $html = '<html>
            Hi ' . ucwords($user->name) . ',<br><br>

            🎉 Welcome to StreamlyTV!<br>
            Your subscription has been activated successfully.<br><br>

            The details are as follows:<br>
            <strong>
            Plan: ' . htmlspecialchars($planName) . '<br>
            Email: ' . $user->email . '<br>
            Next Billing Date: ' . $nextBillingDate . '<br>
            Amount: $' . number_format($amount, 2) . '<br>
            </strong>
            <br>

            ' . ($receiptUrl ? 'View your Receipt <a href="' . $receiptUrl . '"><strong>Here</strong></a><br>' : '') . '

            Start watching now 🚀<br><br>

            If you have any questions, contact us at 
            <a href="mailto:contact@streamlytv.com">contact@streamlytv.com</a>.<br><br>

            <b>Thanks,<br>
            Team Streamly</b>
            </html>';
                    }
                }

                /**
                 * ----------------------------------------
                 * 🔁 SUBSCRIPTION RENEWAL
                 * ----------------------------------------
                 */
                if ($invoice->billing_reason === 'subscription_cycle') {

                    $message = "Your subscription has been renewed successfully.";
                    $title = "Subscription Renewed";
                    $subject = 'Subscription Renewed - ' . env('APP_NAME');

                    $html = '<html>
        Hi ' . ucwords($user->name) . ',<br><br>

        We hope you are enjoying StreamlyTV.<br>
        Your subscription has been renewed successfully.<br><br>

        The details are as follows:<br>
        <strong>
        Plan: ' . htmlspecialchars($planName) . '<br>
        Email: ' . $user->email . '<br>
        Next Billing Date: ' . $nextBillingDate . '<br>
        Amount: $' . number_format($amount, 2) . '<br>
        </strong>
        <br>

        ' . ($receiptUrl ? 'View your Receipt <a href="' . $receiptUrl . '"><strong>Here</strong></a><br>' : '') . '

        If you have any questions, contact us at 
        <a href="mailto:contact@streamlytv.com">contact@streamlytv.com</a>.<br><br>

        <b>Thanks,<br>
        Team Streamly</b>
        </html>';
                }

                // ✅ Activity
                $this->activity($user->id)
                    ->event("Subscription")
                    ->eventType("Subscription Payment")
                    ->descriptionSimple($message)
                    ->notification()
                    ->isUser()
                    ->isAdmin()
                    ->properties([
                        "invoice_id" => $invoice->id,
                        "plan" => $planName,
                        "payment_intent" => $paymentIntent->id,
                        "amount" => $amount
                    ])
                    ->save();

                // ✅ Push Notification
                $fcmTokens = DB::table('session_table')
                    ->where('users_id', $user->id)
                    ->where('status', 1)
                    ->whereNotNull('fcm_token')
                    ->pluck('fcm_token')
                    ->toArray();

                if (!empty($fcmTokens)) {
                    foreach ($fcmTokens as $token) {
                        $this->notification->send($token, $title, $message);
                    }
                }

                // ✅ Email
                Mail::send('emails.general', ["html" => $html], function ($mail) use ($user, $subject) {
                    $mail->to($user->email, $user->name)
                        ->bcc($this->bcc)
                        ->subject($subject);
                });

                break;


            case 'payment_intent.payment_failed':

                $paymentIntent = $event->data->object;

                // ❌ Skip if not subscription payment
                if (empty($paymentIntent->invoice)) {
                    break;
                }

                // ✅ Get Invoice
                $invoice = \Stripe\Invoice::retrieve($paymentIntent->invoice);

                // ✅ Get Subscription
                $subscription = \Stripe\Subscription::retrieve($invoice->subscription);

                // ✅ Plan Name
                $planName = $subscription->items->data[0]->price->nickname ?? 'Default Plan';

                // ✅ Get User
                $user = DB::table('users')
                    ->where('stripe_id', $paymentIntent->customer)
                    ->first();

                if (!$user) {
                    break;
                }

                // ✅ Message
                $message = "Your subscription payment failed.";
                $title = "Payment Failed";
                $subject = 'Payment Failed - ' . env('APP_NAME');

                // ✅ Email (your style)
                $html = '<html>
    Hi ' . ucwords($user->name) . ',<br><br>

    Your subscription payment could not be processed.<br><br>

    The details are as follows:<br>
    <strong>
    Plan: ' . htmlspecialchars($planName) . '<br>
    Email: ' . $user->email . '<br>
    </strong>
    <br>

   
    If you have any questions, contact us at 
    <a href="mailto:contact@streamlytv.com">contact@streamlytv.com</a>.<br><br>

    <b>Thanks,<br>
    Team Streamly</b>
    </html>';

                // 🔔 Activity
                $this->activity($user->id)
                    ->event("Subscription")
                    ->eventType("Payment Failed")
                    ->descriptionSimple($message)
                    ->notification()
                    ->isUser()
                    ->isAdmin()
                    ->properties([
                        "invoice_id" => $invoice->id,
                        "plan" => $planName,
                        "payment_intent" => $paymentIntent->id
                    ])
                    ->save();

                // 📲 Push Notification
                $fcmTokens = DB::table('session_table')
                    ->where('users_id', $user->id)
                    ->where('status', 1)
                    ->whereNotNull('fcm_token')
                    ->pluck('fcm_token')
                    ->toArray();

                if (!empty($fcmTokens)) {
                    foreach ($fcmTokens as $token) {
                        $this->notification->send($token, $title, $message);
                    }
                }

                // 📧 Email
                Mail::send('emails.general', ["html" => $html], function ($mail) use ($user, $subject) {
                    $mail->to($user->email, $user->name)
                        ->bcc($this->bcc)
                        ->subject($subject);
                });

                break;
        }




    }
}
