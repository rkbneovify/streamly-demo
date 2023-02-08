<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\User;
use Mail;
use JWTAuth;
// use App\Http\Controllers\Controller;
// use App\Models\Route;

class StripeController extends Controller

    {
        public function index(Request $request) {

            $payload = @file_get_contents('php://input');
            $event = null;

            try {
            $event = \Stripe\Event::constructFrom(
              json_decode($payload, true)
            );
        } catch(\UnexpectedValueException $e) {
        // Invalid payload
            http_response_code(400);
            exit();
        }

        switch ($event->type) {
            case 'customer.subscription.deleted':
                 $paymentIntent = $event->data->object;

                 if($paymentIntent->status == "canceled"){

                    $planCancelled = DB::table('subscriptions')->where('stripe_id', $paymentIntent->id)->get('user_id');
                    $user= DB::table('users')->where('id', $planCancelled[0]->user_id)->get();

                    $userplanCanceled = DB::table('subscriptions')
                    ->where('stripe_id', $paymentIntent->id)
                    ->update(array(
                    'stripe_status' => 'canceled',
                    'ends_at' => date("Y/m/d h:i:s"),
                    )); 
   
                    $website_link = env('WEBSITE_URL');
   
                    $html='<html>
                    Hi, '.$user[0]->name.'<br><br>
                    Your subscription is cancelled now!
                </html>';

                   Mail::send('emails.general',["html"=>$html] , function($message) use ($request, $user){
                   $message->to($user[0]->email, $user[0]->name)
                   ->subject('subscription cancelled '.env('APP_NAME'));
               });

              }
            break;

            case 'payment_intent.succeeded':
                     $paymentIntent = $event->data;
    
                     if($paymentIntent->description != "Subscription creation"){
    
                         $user= DB::table('users')->where('stripe_id', $paymentIntent->customer)->get();

                         //$recipt = $paymentIntent->charges->data[0]->receipt_url;

                        //  $userplanRenewed = DB::table('subscriptions')
                        //  ->where('stripe_id', $paymentIntent->id)
                        //  ->update(array(
                        //  'stripe_status' => 'canceled',
                        //  'ends_at' => date("Y/m/d h:i:s"),
                        // )); 
       
                        $website_link = env('WEBSITE_URL');
       
                        $html='<html>
                        Hi, '.$user[0]->name.'<br><br>
                        We hope you are enjoying streamlyTV,<br>
                        as a reminder, your subscription is renewed.<br><br>
                        View your receipt by visiting <br><br>
                        Thanks<br>
                        Team Streamly
        
                    </html>';
    
                       Mail::send('emails.general',["html"=>$html] , function($message) use ($request, $user){
                       $message->to($user[0]->email, $user[0]->name)
                       ->subject('Your Streamly Subscription Renewed '.env('APP_NAME'));
                   });
    
                  }
            break;
    
            case 'payment_intent.payment_failed':
                         $paymentIntent = $event->data;
        
                         if($paymentIntent->last_payment_error->code == "expired_card"){
        
                             //$cardExpire = DB::table('subscriptions')->where('stripe_id', $paymentIntent->id)->get('user_id');
                             $user= DB::table('users')->where('stripe_id', $paymentIntent->customer)->get();
    
                             //$recipt = $paymentIntent->charges->data->receipt_url;
           
                            $website_link = env('WEBSITE_URL');
           
                            $html='<html>
                            Hi, '.$user[0]->name.'<br><br>
                            Your card is expired.<br>
                            Please visit my accounts by visiting<br>
                            '.env('APP_URL').'/account/settings
                            <br><br>
                            Thanks<br>
                            Team Streamly
            
                        </html>';
        
                           Mail::send('emails.general',["html"=>$html] , function($message) use ($request, $user){
                           $message->to($user[0]->email, $user[0]->name)
                           ->subject('Your Card Expired '.env('APP_NAME'));
                       });
        
                      }

            }
       
    }
}