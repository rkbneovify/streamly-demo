<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\EmailOtp;
use Illuminate\Http\Request;
use App\Models\FrontendText;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Stripe\Coupon;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Models\RoleUser;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Stripe\Customer;
use Stripe\PaymentMethod;
use Stripe\Subscription;
use App\SessionTable;
use Tymon\JWTAuth\Facades\JWTAuth;

class StripeController extends Controller
{
    private $DEFAULT_ROLE_ID = 3;

    public function __construct()
    {
        $STRIPE_SECRET = DB::table('Stripe_keys')->where('status', 'active')->value('stripe_secret');
        \Stripe\Stripe::setApiKey($STRIPE_SECRET);
    }
    public function getBasicPlan(Request $request, $stripeplanID)
    {
        // Retrieve plan details from Stripe
        $plandetails = \Stripe\Plan::retrieve($stripeplanID);

        // dd($plandetails);
        // Retrieve frontend text
        $frontendjson = FrontendText::find(1)->frontendjson;

        // Retrieve active Stripe key
        $STRIPE_KEY = DB::table('Stripe_keys')->where('status', 'active')->value('stripe_key');

        // Check user subscription status
        /* if (JWTAuth::user()->subscription('main') && JWTAuth::user()->subscription('main')->cancelled()) {
            // Return payment cancelled view
            return response()->json([
                'stripeplanID' => $stripeplanID,
                'plandetails' => $plandetails,
                'frontendjson' => $frontendjson,
                'STRIPE_KEY' => $STRIPE_KEY,
                'message' => 'Payment cancelled'
            ]);
        } else { */
        // Return payment view
        return response()->json([
            'stripeplanID' => $stripeplanID,
            'plandetails' => $plandetails,
            'frontendjson' => $frontendjson,
            'STRIPE_KEY' => $STRIPE_KEY,
            'message' => 'Payment page'
        ]);
        // }
    }

    public function getTierPlan(Request $request, $stripeplanID)
    {
        $plandetails = \Stripe\Plan::retrieve(['id' => $stripeplanID, 'expand' => ["tiers"]]);

        $interval = $plandetails->interval;
        $interval_count = $plandetails->interval_count;
        $ApproxEndDateOfSubscription = Carbon::now()->add($interval_count, $interval);

        $STRIPE_KEY = DB::table('Stripe_keys')->where('status', 'active')->value('stripe_key');
        $frontendjson = FrontendText::find(1)->frontendjson;

        /* if (JWTAuth::user()->subscription('main') && JWTAuth::user()->subscription('main')->cancelled()) {
            return response()->json([
                'stripeplanID' => $stripeplanID,
                'plandetails' => $plandetails,
                'frontendjson' => $frontendjson,
                'ApproxEndDateOfSubscription' => $ApproxEndDateOfSubscription,
                'STRIPE_KEY' => $STRIPE_KEY,
                'message' => 'Payment cancelled tier page'
            ]);
        } else { */
        return response()->json([
            'stripeplanID' => $stripeplanID,
            'plandetails' => $plandetails,
            'frontendjson' => $frontendjson,
            'ApproxEndDateOfSubscription' => $ApproxEndDateOfSubscription,
            'STRIPE_KEY' => $STRIPE_KEY,
            'message' => 'Payment tier page'
        ]);
        // }
    }

    public function validateCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'validation_error',
                'message' => $validator->errors()
            ], 400);
        } else {
            $STRIPE_SECRET = DB::table('Stripe_keys')->where('status', 'active')->value('stripe_secret');

            if ($request->has('coupon')) {
                \Stripe\Stripe::setApiKey($STRIPE_SECRET);
                try {
                    $status = Coupon::retrieve($request->input('coupon'));
                    if ($status->valid) {
                        if ($status->amount_off) {
                            $price =  $status->currency . ' ' . $status->amount_off / 100 . ' Off ' . $status->duration;
                        } else {
                            if ($status->duration == 'repeating') {
                                $duration = '(' . $status->duration_in_months . ' months)';
                            } else {
                                $duration = '(forever)';
                            }
                            $price = $status->percent_off . '% for ' . $duration;
                        }
                    } else {
                        return response()->json(['message' => $request->coupon . " is Invalid or expired."], 400);
                    }
                } catch (\Stripe\Error\InvalidRequest $e) {
                    return response()->json(['message' => $e->getMessage()], 400);
                }
                return response()->json(['message' => 'Coupon is valid! & ' . $price, 'amountoff' => $status->amount_off / 100, 'percentoff' => $status->percent_off], 200);
            }
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'stripeToken' => 'required',
            'trial_days' => 'required',
            'plan_id' => 'required',
            'quantity' => 'required',
            'line1' => 'required',
            'line2' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'card_brand' => 'required',
            'card_last_four' => 'required',
            'zip_code' => ['required', 'regex:/^[0-9]{3,7}$/', 'exists:zipcodes,zipcode,status,1'],
        ], ['zip_code.exists' => 'Unfortunately, the service is not yet available for the submitted zip code.']);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'validation_error',
                'message' => $validator->errors()
            ], 400);
        } else {
            $trial_days = $request->trial_days;
            $StripeplanId = $request->plan_id;
            $quantity = $request->quantity ?? 1;

            // 1. Create Payment Method
            $paymentMethod = PaymentMethod::create([
                'type' => 'card',
                'card' => [
                    'token' => $request->stripeToken,
                ],
            ]);

            $customer = $this->createStripeCustomer($request, $paymentMethod);

            // Create user
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $password = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_+='), 0, 10);
            $user->password = Hash::make($password);
            $user->forced_change = 1;
            $user->zipcode = $request->zip_code;
            $emailOtp = EmailOtp::where('email', $request->email)->select('updated_at')->first();
            if ($emailOtp) {
                // Extract the updated_at value from the $emailOtp object
                $user->email_verified_at = $emailOtp->updated_at;
            } else {
                // Handle the case where no record is found.
                return response()->json(['error' => 'Email not Verified!'], 400);
            }
            $user->is_active = 1;
            $user->mode = DB::table('Stripe_keys')->where('status', 'active')->value('type');
            $user->city = $request->city;
            $user->state = $request->state;
            $user->stripe_id = $customer->id;
            $user->card_brand = $request->card_brand;
            $user->card_last_four = $request->card_last_four;

            if ($user->save()) {
                // Assign default role
                $role = RoleUser::create([
                    'user_id' => $user->id,
                    'role_id' => $this->DEFAULT_ROLE_ID,
                ]);

                // Generate email HTML
                $login_link = env('FRONTEND_URL') . '/auth/login';
                $html = '<html>
                            Hi, ' . $request['name'] . '<br><br>
                            Thank you for registering for ' . env('APP_NAME') . '.
                            <br><br>Username: ' . $user->email . '
                            <br>Password: ' . $password . '<br><br>
                            Please click the following link to login to your account.
                            <br><br><a href="' . $login_link . '" target=_blank style="  display: block; width: 115px; height: 25px; background: #004E92 0% 0% no-repeat padding-box; padding: 10px; text-align: center; border-radius: 5px; color: white; font-weight: bold; line-height: 25px;">Login</a><br><br>
                            Thank you!<br>
                            The ' . env('APP_NAME') . ' Team!
                        </html>';
                // Send email
                Mail::send('emails.general', ["html" => $html], function ($message) use ($request) {
                    $message->to($request->input('email'), $request->input('name'))
                        ->subject(env('APP_NAME') . ': Account Registration');
                });

                if ($coupon = $request->get('coupon')) {
                    $subscription_details = $this->withCouponCheckTrialDays($user, $request, $coupon, $StripeplanId, $quantity, $trial_days, $paymentMethod);
                } else {
                    $subscription_details = $this->withoutCouponCheckTrialDays($user, $request, $StripeplanId, $quantity, $trial_days, $paymentMethod);
                }

                return response()->json([
                    'message' => 'User Registered Successfully !',
                    'subscription_details' => $subscription_details
                ]);
            } else {
                return response()->json(['error' => 'Something Went Wrong.'], 500);
            }
        }
    }

    private function createStripeCustomer($request, $paymentMethod)
    {
        $customer = Customer::create([
            'email' => $request->email,
            'payment_method' => $paymentMethod->id,
            'address' => [
                'line1' => $request->line1,
                'line2' => $request->line2,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'postal_code' => $request->zip_code
            ]
        ]);
        return $customer;
    }

    private function withCouponCheckTrialDays($user, $request, $coupon, $StripeplanId, $quantity, $trial_days, $paymentMethod)
    {

        if (!empty($trial_days)) {
            $newSubscription = $user->newSubscription('main', $StripeplanId)->quantity($quantity)->trialDays($trial_days)
                ->withCoupon($coupon)
                ->create($paymentMethod, ['email' => $user->email]);

            //send user email after successfull payment
            // Send confirmation email Trail
            $this->sendSubscriptionConfirmationEmailTrail($user);
        } else {
            $newSubscription = $user->newSubscription('main', $StripeplanId)
                ->quantity($quantity)
                ->withCoupon($coupon)
                ->create($paymentMethod, ['email' => $user->email]);

            //send user email after successfull payment
            // Send confirmation email
            $this->sendSubscriptionConfirmationEmail($user);
        }
        return $newSubscription;
    }

    private function withoutCouponCheckTrialDays($user, $request, $StripeplanId, $quantity, $trial_days, $paymentMethod)
    {

        //check for trial days
        // Create subscription
        if (!empty($trial_days)) {
            $newSubscription = $user->newSubscription('main', $StripeplanId)
                ->quantity($quantity)
                ->trialDays($trial_days)->create($paymentMethod, ['email' => $user->email]);

            //send user email after successfull payment
            // Send confirmation email Trail
            $this->sendSubscriptionConfirmationEmailTrail($user);
        } else {
            $newSubscription = $user->newSubscription('main', $StripeplanId)
                ->quantity($quantity)
                ->create($paymentMethod, ['email' => $user->email]);

            //send user email after successfull payment
            // Send confirmation email
            $this->sendSubscriptionConfirmationEmail($user);
        }

        return $newSubscription;
    }

    private function sendSubscriptionConfirmationEmail($user)
    {
        // Send user email after successful payment
        $website_link = env('APP_URL');

        $html = '<html>
                    Hi, ' . $user->name . '<br><br>
                    Thanks for Subscribing! ' . env('APP_NAME') . '.<br>Your subscription is now active.<br>
                    Start watching StreamlyTV right here by visiting the link <a href="' . $website_link . '" target=_blank >' . $website_link . '</a><br><br>, or by downloading the StreamlyTV App from Apple, AppleTV, Android, Amazon Fire, Roku, or some SmartTV stores!
                    <br><br>
                    Thanks!<br>
                    Team Streamly
                </html>';

        Mail::send('emails.general', ["html" => $html], function ($message) use ($user) {
            $message->to($user->email, $user->name)
                ->subject('Thanks for Subscribing! ' . env('APP_NAME'));
        });
    }

    private function sendSubscriptionConfirmationEmailTrail($user)
    {
        $website_link = env('APP_URL');

        $html = '<html>
                    Hi, ' . $user->name . '<br><br>
                    Thanks for Subscribing! ' . env('APP_NAME') . '.<br>Your FREE TRIAL PERIOD is now active. After the trial period ends, your credit card will be billed the monthly subscription amount. <br>
                    Start watching StreamlyTV right here by visiting the link <a href="' . $website_link . '" target=_blank >' . $website_link . '</a><br><br>, or by downloading the StreamlyTV App from Apple, AppleTV, Android, Amazon Fire, Roku, or some SmartTV stores!
                    <br><br>
                    Thanks!<br>
                    Team Streamly
                </html>';

        Mail::send('emails.general', ["html" => $html], function ($message) use ($user) {
            $message->to($user->email, $user->name)
                ->subject('Thanks for Subscribing! ' . env('APP_NAME'));
        });
    }

    public function showUserDetails(Request $request)
    {
        $response_to_send = [];

        $id = JWTAuth::user()->id;
        $userinfo = User::find($id);
        $resultoffset = $userinfo->zipcode;
        $timezoneData = fetchHomeTimezoneUsingZip($resultoffset);
        $timezone = $timezoneData[0];
        $allSession = SessionTable::where([
            ['status', '=', '1'],
            ['users_id', '=', $id],
        ])->orderBy('connected_devices', 'asc')->get();

        $subscription = JWTAuth::user()->subscriptions()->first();

        if (!$userinfo->subscribed('main')) {
            $plan = (object) ['amount' => '0'];
        } else {
            $stripe_plan = $subscription->stripe_plan;

            $STRIPE_KEY = DB::table('Stripe_keys')->where('status', 'active')->get();

            if ($STRIPE_KEY[0]->type == $userinfo->mode) {
                $plan = \Stripe\Plan::retrieve(['id' => $stripe_plan, 'expand' => ["tiers"]]);
                $invoices =  \Stripe\Invoice::all(['customer' => JWTAuth::user()->stripe_id, 'expand' => ['data.charge']]);
            } else {
                $plan = null;
                $invoices = '';
            };
        }

        if ($subscription) {
            if ($subscription->ends_at) {
                $renewaldatetime = 'Plan Expired';
            } else {
                $STRIPE_SECRET = DB::table('Stripe_keys')->where('status', 'active')->value('stripe_secret');

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.stripe.com//v1/invoices/upcoming?customer=' . $userinfo->stripe_id . '',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/x-www-form-urlencoded',
                        'Authorization: Bearer ' . $STRIPE_SECRET . ''
                    ),
                ));

                $response = curl_exec($curl);
                // print_r($response);
                // die;
                $renewalDatestripe = json_decode($response);
                curl_close($curl);
                $renewaldatetime = date('Y-m-d H:i:s', $renewalDatestripe->next_payment_attempt) ?? 'No upcoming invoices';
                // $renewaldatetime = 'No upcoming invoices';
            }
        } else {
            $renewaldatetime = '';
        }
        // print_r($allSession);
        // die;
        // Populate response
        // $response_to_send['allSession'] = $allSession->toArray();
        $response_to_send['subscription'] = $subscription;
        $response_to_send['plan'] = $plan;
        $response_to_send['userinfo'] = $userinfo;
        $response_to_send['invoices'] = $invoices;
        $response_to_send['timezone'] = $timezone;
        $response_to_send['renewaldatetime'] = $renewaldatetime;
        $response_to_send['zipcode'] = $resultoffset;

        return response()->json($response_to_send);
    }
}
