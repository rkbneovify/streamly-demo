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
            case 'customer.subscription.created':
                // save subscription in DB
                break;

            case 'invoice.paid':
                $invoice = $event->data->object;

                if ($invoice->billing_reason === 'subscription_create') {
                    $message = 'Your subscription has started successfully.';
                } elseif ($invoice->billing_reason === 'subscription_cycle') {
                    $message = 'Your subscription has been renewed successfully.';
                } elseif ($invoice->billing_reason === 'subscription_update') {
                    $message = 'Your subscription update payment was successful.';
                }
                break;

            case 'customer.subscription.updated':
                $subscription = $event->data->object;
                $previous = $event->data->previous_attributes ?? null;

                if (isset($previous->items)) {
                    $message = 'Your subscription plan has been updated successfully.';
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
                $this->activity($user->first()->id)->event("Subscription")
                    ->eventType(ActivityEventType::Failed)
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
                            Your card is expired.<br>
                            Please visit my accounts by visiting<br>
                            ' . env('APP_URL') . '/user/user-settings
                            <br><br>
                            <b>Thanks,<br>
                            Team Streamly<b>

                        </html>';

                Mail::send('emails.general', ["html" => $html], function ($message) use ($user) {
                    $message->to($user->email, $user->name);
                    $message->bcc('annu.sehrawat@oodles.io', 'mina@neovix.com')
                        ->subject('Your Card Expired ' . env('APP_NAME'));
                });

                break;

            case 'customer.subscription.trial_will_end':
                $message = 'Your trial will end soon. Add a payment method to continue.';
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
                    ->eventType(ActivityEventType::CANCELED)
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
                    $message->bcc('annu.sehrawat@oodles.io', 'mina@neovix.com')
                        ->subject('Your subscription has been canceled. ' . env('APP_NAME'));
                });

                break;
        }
    }
}
