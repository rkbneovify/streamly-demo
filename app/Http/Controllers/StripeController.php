<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\Subscription;
use Mail;
use JWTAuth;
// use App\Http\Controllers\Controller;
// use App\Models\Route;

class StripeController extends Controller
{

  public function __construct()
  {
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
          $fcm_token = DB::table('session_table')
          ->where('users_id', $user[0]->id)
            ->where('status', 1)
            ->whereNotNull('fcm_token')
            ->get('fcm_token');

          $fcmResults = [];
          foreach ($fcm_token as $tokens) {
            array_push($fcmResults, $tokens->fcm_token);
          };

          $website_link = env('WEBSITE_URL');

          if (count($fcm_token) == 0) {
          } else {
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://streamlytv.com/api/auth/sendNotification',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => '{
                 "registration_ids": ' . json_encode($fcmResults) . ',
                 "title": "Subscription Cancelled",
                 "body": "HI ' . $user[0]->name . ' your subscription is cancelled to renew your subscription please visit http://streamlytv.com/"
                }',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
              ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
          };

          $userplanCanceled = DB::table('subscriptions')
          ->where('stripe_id', $paymentIntent->id)
            ->update(array(
              'stripe_status' => 'canceled',
              'ends_at' => date("Y/m/d h:i:s"),
            ));

          $html = '<html>
                    Hi, ' . $user[0]->name . '<br><br>
                    Your StreamlyTV subscription is now canceled.<br>
                    To renew your subscription, please visit: ' . $website_link . ', login, and select a plan!.
                    <br><br>
                    Thanks<br>
                    Team Streamly
                </html>';

          Mail::send('emails.general', ["html" => $html], function ($message) use ($request, $user) {
            $message->to($user[0]->email, $user[0]->name);
            $message->bcc('piyush.mishra@oodles.io', 'mina@neovix.com')
            ->subject('Your subscription has been canceled. ' . env('APP_NAME'));
          });
        }
        break;

      case 'payment_intent.succeeded':
        $paymentIntent = $event->data;

        if ($paymentIntent->object->description != "Subscription creation") {

          $user = DB::table('users')->where('stripe_id', $paymentIntent->object->customer)->get();
          $amount = $paymentIntent->object->amount / 100;
          $recipt = $paymentIntent->object->charges->data[0]->receipt_url;
          $subscriptionId  = $paymentIntent->object->invoice;

          $subscription = \Stripe\Invoice::retrieve($subscriptionId);
          $upcomingInvoice = \Stripe\Invoice::upcoming([
            'subscription' => $subscription->subscription, // Use the subscription ID
          ]);

          $nextBillingDate = $upcomingInvoice->next_payment_attempt ?  date('Y-m-d H:i',  $upcomingInvoice->next_payment_attempt) :  'N/A';

          $fcm_token = DB::table('session_table')
          ->where('users_id', $user[0]->id)
            ->where('status', 1)
            ->whereNotNull('fcm_token')
            ->get('fcm_token');

          $fcmResults = [];
          foreach ($fcm_token as $tokens) {
            array_push($fcmResults, $tokens->fcm_token);
          };

          if (count($fcm_token) == 0) {
          } else {
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://streamlytv.com/api/auth/sendNotification',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => '{
                          "registration_ids": ' . json_encode($fcmResults) . ',
                          "title": "Subscription Renewed",
                          "body": "HI ' . $user[0]->name . ' your subscription is renewed."
                         }',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
              ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
          };

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
                    Hi, ' . $user[0]->name . '<br><br>
                    We hope you are enjoying streamlyTV,<br>
                    As a reminder, your subscription is renewed.<br><br>
                    The details are as follows:<br>
                    Email: ' . $user[0]->email . '<br>
                    Next Billing Date: ' . $nextBillingDate . '<br>
                    Amount: $' . $amount . '<br>
                    <br>
                    View your receipt by clicking <a href="' . $recipt . '"">here</a><br>
                    If you have any questions or if you find an error, please contact us at <a href="mailto:contact@streamlytv.com">contact@streamlytv.com</a>.<br><br>
                    Thanks,<br>
                    Team Streamly
                    </html>';

          Mail::send('emails.general', ["html" => $html], function ($message) use ($request, $user) {
            $message->to($user[0]->email, $user[0]->name)
              ->subject('Your Subscription Renewed ' . env('APP_NAME'));
          });
        }
        break;

      case 'payment_intent.payment_failed':
        $paymentIntent = $event->data->object;

        if ($paymentIntent->last_payment_error->code == "expired_card") {

          //$cardExpire = DB::table('subscriptions')->where('stripe_id', $paymentIntent->id)->get('user_id');
          $user = DB::table('users')->where('stripe_id', $paymentIntent->customer)->get();

          //$recipt = $paymentIntent->charges->data->receipt_url;

          $fcm_token = DB::table('session_table')
          ->where('users_id', $user[0]->id)
            ->where('status', 1)
            ->whereNotNull('fcm_token')
            ->get('fcm_token');

          $fcmResults = [];
          foreach ($fcm_token as $tokens) {
            array_push($fcmResults, $tokens->fcm_token);
          };

          if (count($fcm_token) == 0) {
          } else {
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://streamlytv.com/api/auth/sendNotification',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => '{
                          "registration_ids": ' . json_encode($fcmResults) . ',
                          "title": "Payment Failed",
                          "body": "HI ' . $user[0]->name . ' please renew your card to enjoy streamlytv continously."
                         }',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
              ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
          };

          $website_link = env('WEBSITE_URL');

          $html = '<html>
                            Hi, ' . $user[0]->name . '<br><br>
                            Your card is expired.<br>
                            Please visit my accounts by visiting<br>
                            ' . env('APP_URL') . '/account/settings
                            <br><br>
                            Thanks<br>
                            Team Streamly
            
                        </html>';

          Mail::send('emails.general', ["html" => $html], function ($message) use ($request, $user) {
            $message->to($user[0]->email, $user[0]->name);
            $message->bcc('piyush.mishra@oodles.io', 'mina@neovix.com')
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

          $fcm_token = DB::table('session_table')
          ->where('users_id', $user[0]->id)
            ->where('status', 1)
            ->whereNotNull('fcm_token')
            ->get('fcm_token');

          $fcmResults = [];
          foreach ($fcm_token as $tokens) {
            array_push($fcmResults, $tokens->fcm_token);
          };

          if (count($fcm_token) == 0) {
          } else {
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://streamlytv.com/api/auth/sendNotification',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => '{
                          "registration_ids": ' . json_encode($fcmResults) . ',
                          "title": "Trial period is ending soon",
                          "body": "HI ' . $user[0]->name . ' your trail period is ending on ' . date("Y-m-d", $paymentIntent->trial_end) . '"
                         }',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
              ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
          };

          $website_link = env('WEBSITE_URL');

          $html = '<html>
                            Hi, ' . $user[0]->name . '<br><br>
                            Your trail period is ending on ' . date("Y-m-d", $paymentIntent->trial_end) . '.
                            after the trial period ends, your credit card will be billed the monthly subscription amount.<br><br>
                            If you have any questions or if you find an error, please contact us at contact@streamlytv.com.
                            <br><br>
                            Thanks<br>
                            Team Streamly
            
                        </html>';

          Mail::send('emails.general', ["html" => $html], function ($message) use ($request, $user) {
            $message->to($user[0]->email, $user[0]->name);
            $message->bcc('piyush.mishra@oodles.io')
            ->subject('Your trail period is ending soon ' . env('APP_NAME'));
          });
        }

        break;

      case 'customer.subscription.updated':
        $paymentIntent = $event->data->object;

        if ($paymentIntent->status == "active") {

          $user = DB::table('users')->where('stripe_id', $paymentIntent->customer)->get();

          $subscriptions = Subscription::where([
            ['user_id', '=', $user[0]->id],
            ['stripe_status', '=', 'trialing'],
            ['ends_at', '=', Null],
          ])->get('id');

          if ($subscriptions) {
            $updateSubscription = DB::table('subscriptions')
            ->where('id', $subscriptions[0]['id'])
              ->update(array(
                'stripe_status' => 'active',
                'updated_at' => date("Y/m/d h:i:s"),
              ));

            $website_link = env('WEBSITE_URL');

            $html = '<html>
                            Hi, ' . $user[0]->name . '<br><br>
                            Your trail period is end now and you have charged as per your subscription.
                            thankyou for choosing to stay with us.<br><br>
                            If you have any questions or if you find an error, please contact us at contact@streamlytv.com.
                            <br><br>
                            Thanks<br>
                            Team Streamly
            
                        </html>';

            Mail::send('emails.general', ["html" => $html], function ($message) use ($request, $user) {
              $message->to($user[0]->email, $user[0]->name);
              $message->bcc('piyush.mishra@oodles.io')
              ->subject('Subscription Activated ' . env('APP_NAME'));
            });
          }
        }
    }
  }
}