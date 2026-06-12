<?php

namespace App\Http\Controllers\Api\User;

use App\Enums\ActivityEventType;
use App\HoaSubUserSubscription;
use App\HoaUser;
use App\Http\Controllers\Controller;
use App\Models\EmailOtp;
use App\Traits\ActivityLogger;
use Illuminate\Http\Request;
use App\Models\FrontendText;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Stripe\Coupon;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Models\RoleUser;
use App\Models\Subscription as ModelsSubscription;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Stripe\Customer as Customer;
use Stripe\PaymentMethod;
use Stripe\Subscription;
use App\SessionTable;
use Laravel\Cashier\Cashier;
use Ratchet\App;
use Tymon\JWTAuth\Facades\JWTAuth;

class StripeController extends Controller
{

    use ActivityLogger;
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
                            $price = $status->currency . ' ' . $status->amount_off / 100 . ' Off ' . $status->duration;
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
            'line2' => 'nullable',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'card_brand' => 'required',
            'card_last_four' => 'required',
            'plan_type' => 'required',
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
            $user->country = $request->country;
            $user->stripe_id = $customer->id;
            $user->card_brand = $request->card_brand;
            $user->card_last_four = $request->card_last_four;

            if ($user->save()) {

                if ($request->plan_type === 'tiered') {
                    $tieruserrole = Role::where('name', 'tieruser')->first();
                    $role_id = $tieruserrole->id;
                } else {
                    $role_id = $this->DEFAULT_ROLE_ID;
                }

                // Assign default role
                $role = RoleUser::create([
                    'user_id' => $user->id,
                    'role_id' => $role_id,
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




                try {
                    $this->activity($user->id)->event("User")
                        ->eventType(ActivityEventType::REGISTER)
                        ->description("New user registered")
                        ->isAdmin()
                        ->properties([
                            "user" => $user,
                        ])->save();
                    if ($coupon = $request->get('coupon')) {
                        $subscription_details = $this->withCouponCheckTrialDays($user, $request, $coupon, $StripeplanId, $quantity, $trial_days, $paymentMethod);
                    } else {
                        $subscription_details = $this->withoutCouponCheckTrialDays($user, $request, $StripeplanId, $quantity, $trial_days, $paymentMethod);
                    }

                    if ($request->trial_days > 0) {
                        $user->trial_taken = 1;
                        $user->save();
                    }
                } catch (\Exception $e) {

                    DB::rollBack();
                    return response()->json([
                        'error' => 'Something Went Wrong.',
                        'message' => $e->getMessage()

                    ]);
                }


                // Send email
                Mail::send('emails.general', ["html" => $html], function ($message) use ($request) {
                    $message->to($request->input('email'), $request->input('name'))
                        ->subject(env('APP_NAME') . ': Account Registration');
                });
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
                'line2' => $request->line2 ?? '',
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
            // $this->sendSubscriptionConfirmationEmailTrail($user);
        } else {
            $newSubscription = $user->newSubscription('main', $StripeplanId)
                ->quantity($quantity)
                ->withCoupon($coupon)
                ->create($paymentMethod, ['email' => $user->email]);

            //send user email after successfull payment
            // Send confirmation email
            // $this->sendSubscriptionConfirmationEmail($user);
        }
        $this->handleTierRoomCreation($user, $request);
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

            // $this->sendSubscriptionConfirmationEmailTrail($user);
        } else {
            $newSubscription = $user->newSubscription('main', $StripeplanId)
                ->quantity($quantity)
                ->create($paymentMethod, ['email' => $user->email]);

            //send user email after successfull payment
            // Send confirmation email
            // $this->sendSubscriptionConfirmationEmail($user);
        }
        $this->handleTierRoomCreation($user, $request);
        return $newSubscription;
    }

    // insert data in hotel room table if tier plan
    private function handleTierRoomCreation($user, $request)
    {
        if ($request->plan_type === 'tiered') {

            $quantity = $request->quantity ?? 1;

            for ($i = 0; $i < $quantity; $i++) {
                DB::table('hotel_rooms')->insert([
                    'user_id' => $user->id,
                    'hotel_room' => 'Room' . $i,
                    'session_id' => null,
                    'created_on' => now(),
                    'modified_on' => now(),
                ]);
            }
        }
    }

    private function sendSubscriptionConfirmationEmail($user)
    {
        // Send user email after successful payment
        $website_link = env('APP_URL');

        $subscriptionDetails = $this->getSubscriptionDetails($user);
        $subscription_data = $subscriptionDetails['subscription_data'];

        $subscription_details = '';
        if (!empty($subscription_data)) {
            foreach ($subscription_data as $key => $value) {
                $subscription_details .= '<br><b>' . ucfirst(str_replace('_', ' ', $key)) . ':</b> ' . $value;
            }
        } else {
            $subscription_details = '<br><b>No subscription details available.</b><br>Error Message:' . $subscriptionDetails['error'];
        }

        $html = '<html>
                    Hi, ' . $user->name . '<br><br>
                    Thanks for Subscribing! ' . env('APP_NAME') . '.<br>Your subscription is now active.<br>
                    Start watching StreamlyTV right here by visiting the link <a href="' . $website_link . '" target=_blank >' . $website_link . '</a><br>, or by downloading the StreamlyTV App from Apple, AppleTV, Android, Amazon Fire, Roku, or some SmartTV stores!
                    <br><br>
                    <b>Subscription Details:</b>' . $subscription_details . '
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
        $subscriptionDetails = $this->getSubscriptionDetails($user);
        $subscription_data = $subscriptionDetails['subscription_data'];
        // print_r($subscriptionDetails);
        // die;
        $subscription_details = '';
        if (!empty($subscription_data)) {
            foreach ($subscription_data as $key => $value) {
                $subscription_details .= '<br><b>' . ucfirst(str_replace('_', ' ', $key)) . ':</b> ' . $value;
            }
        } else {
            $subscription_details = '<br><b>No subscription details available.</b><br>Error Message:' . $subscriptionDetails['error'];
        }
        $html = '<html>
                    Hi, ' . $user->name . '<br><br>
                    Thanks for Subscribing! ' . env('APP_NAME') . '.<br>Your FREE TRIAL PERIOD is now active. After the trial period ends, your credit card will be billed the monthly subscription amount. <br>
                    Start watching StreamlyTV right here by visiting the link <a href="' . $website_link . '" target=_blank >' . $website_link . '</a> ,<br>or by downloading the StreamlyTV App from Apple, AppleTV, Android, Amazon Fire, Roku, or some SmartTV stores!
                    <br><br>
                    <b>Subscription Details:</b>' . $subscription_details . '
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
        $userinfo = JWTAuth::user();
        $resultoffset = $userinfo->zipcode;
        $timezoneData = fetchHomeTimezoneUsingZip($resultoffset);
        $timezone = $timezoneData[0] ?? null;

        $response_to_send['success'] = true;
        $response_to_send['response_code'] = 200;
        $response_to_send['message'] = "Setting details successfully retrieved";
        // Always include user data

        $hoaUser = HoaUser::getCurrentSubUserByToken();


        $user_data = [
            "id" => $userinfo->id,
            "name" => $userinfo->name,
            "email" => $userinfo->email,
            "zipcode" => $resultoffset,
            "timezone" => $timezone,
            "passcode" => HashUserID($userinfo->id),
            "connected_device" => $userinfo->connected_device,
            "is_hoa_main_user" => $request->user()->roles[0]->name == 'hoa' ? true : false,
            "is_hoa_sub_user" => null,


        ];
        $response_to_send['hoa_sub_user_subscription'] = null;
        $response_to_send['hoa_main_custom_subscription'] = null;
        if ($hoaUser != null && $hoaUser->passcode != null) {

            $user_data = [
                "id" => $hoaUser->id,
                "name" => $hoaUser->name,
                "email" => $hoaUser->email,
                "zipcode" => $hoaUser->zipcode,
                "timezone" => $timezone,
                "passcode" => $hoaUser->passcode,
                "connected_device" => $userinfo->connected_device,
                "is_hoa_main_user" => false,
                "is_hoa_sub_user" => true,

            ];

            $hoaSubscription = HoaSubUserSubscription::where('sub_user_id', $hoaUser->id)->latest()->first();
            if ($hoaSubscription != null) {
                $response_to_send['hoa_sub_user_subscription'] = $hoaSubscription;
            }
        }




        $response_to_send['userinfo'] = $user_data;

        try {
            $customerId = $userinfo->stripe_id;

            if (!$customerId) {
                throw new \Exception("Stripe ID is null or invalid.");
            }

            $customer = \Stripe\Customer::retrieve($customerId);

            // Retrieve subscription and plan
            $subscription = $customer->subscriptions->data[0] ?? $userinfo->subscriptions()->first();

            if (!$userinfo->subscribed('main')) {
                $plan = (object) ['amount' => '0'];
                $invoices = $this->get_user_invoices($customerId);
                $renewaldatetime = '';
            } else {
                $STRIPE_KEY = DB::table('Stripe_keys')->where('status', 'active')->get();

                if ($STRIPE_KEY[0]->type == $userinfo->mode) {
                    $plan = $subscription->plan ?? \Stripe\Plan::retrieve(['id' => $subscription->stripe_plan, 'expand' => ["tiers"]]);
                    $invoices_data = \Stripe\Invoice::all(['customer' => $customerId, 'expand' => ['data.charge']]);

                    $invoices = $this->get_user_invoices($customerId);
                    // For renewal date
                    if ($subscription->cancel_at) {
                        $renewaldatetime = 'Plan Expired';
                    } else {
                        $renewaldatetime = isset($subscription->current_period_end) ? date('Y-m-d H:i:s', $subscription->current_period_end) : 'No upcoming invoices';
                    }

                    // For subscription details
                    if (!empty($plan->tiers)) {
                        $plan_name = ($plan->nickname ?? '') . ' ' . ('with ' . $subscription->quantity . ' quantity' ?? '') . ' ' . (Cashier::formatAmount($invoices_data->data[0]->total ?? '0', $invoices_data->data[0]->currency ?? 'USD')) . ' per ' . ($plan->interval_count ?? '') . ' ' . ($plan->interval ?? '');
                        $plan_type = "Tiers" . ' ' . $plan->interval_count . ' ' . $plan->interval;
                    } else {
                        $plan_name = ($plan->nickname ?? '') . ' ' . (Cashier::formatAmount($plan->amount ?? '0', $plan->currency ?? 'USD')) . '/' . ($plan->interval_count ?? '') . ' ' . ($plan->interval ?? '');
                        $plan_type = $plan->billing_scheme;
                    }
                } else {
                    $plan = null;
                    $invoices = [];
                    $renewaldatetime = '';
                }
            }

            $subscription_data = [
                'id' => (string) $subscription->id,
                'name' => $plan_name ?? null,
                'plan_type' => $plan_type ?? null,
                'renewaldatetime' => $renewaldatetime ?? null,
                'payment_method' => ($userinfo->card_brand ?? '') . ' ' . ($userinfo->card_last_four ?? ''),
                'canceled' => $subscription->cancel_at_period_end,
                // 'plan_expired_at' => date('Y-m-d H:i:s', $subscription->current_period_end)
                'plan_expired_at' => date('Y-m-d\TH:i:s.000000\Z', $subscription->current_period_end)
            ];

            // // if user is main user and custom subscription
            // if ($response_to_send['hoa_sub_user_subscription'] == null && $request->user()->subscription('main')->plan_duration != null) {
            //     if (strtolower($request->user()->roles[0]->name) == 'hoa') {
            //         $response_to_send['hoa_main_custom_subscription'] =
            //             [
            //                 "plan" => $request->user()->subscription('main')->plan_duration,
            //                 "renews_at" => $request->user()->subscription('main')->renews_at ?? $request->user()->subscription('main')->canceled_at,
            //             ];
            //         $response_to_send['is_custom_subscription'] = true;
            //         $response_to_send['subscription']['name'] =   $request->user()->subscription('main')->plan_duration;
            //         if ($request->user()->subscription('main')->renews_at != null) {
            //             $response_to_send['subscription']['plan_expired_at'] = date('Y-m-d H:i:s', strtotime($request->user()->subscription('main')->renews_at));
            //         } elseif ($request->user()->subscription('main')->canceled_at != null) {
            //             $response_to_send['subscription']['plan_expired_at'] = date('Y-m-d H:i:s', strtotime($request->user()->subscription('main')->canceled_at));
            //         }
            //     } else {
            //         $response_to_send['is_custom_subscription'] = true;
            //         $response_to_send['subscription']['name'] =   $request->user()->subscription('main')->plan_duration;
            //         if ($request->user()->subscription('main')->renews_at != null) {
            //             $response_to_send['subscription']['plan_expired_at'] = date('Y-m-d H:i:s', strtotime($request->user()->subscription('main')->renews_at));
            //         } elseif ($request->user()->subscription('main')->canceled_at != null) {
            //             $response_to_send['subscription']['plan_expired_at'] = date('Y-m-d H:i:s', strtotime($request->user()->subscription('main')->canceled_at));
            //         }
            //     }
            // }




            if ($response_to_send['hoa_sub_user_subscription'] == null && $request->user()->subscription('main')->plan_duration != null) {
                if (strtolower($request->user()->roles[0]->name) == 'hoa') {
                    $response_to_send['hoa_main_custom_subscription'] =
                        [
                            "plan" => $request->user()->subscription('main')->plan_duration,
                            "renews_at" => $request->user()->subscription('main')->renews_at ?? $request->user()->subscription('main')->canceled_at,
                        ];
                }
            }
            if ($response_to_send['hoa_sub_user_subscription'] == null && $request->user()->subscription('main')->plan_duration != null) {
                $response_to_send['is_custom_subscription'] = true;
                $subscription_data['name'] =   $request->user()->subscription('main')->plan_duration;
                if ($request->user()->subscription('main')->renews_at != null) {
                    $subscription_data['plan_expired_at'] = date('Y-m-d\TH:i:s.000000\Z', strtotime($request->user()->subscription('main')->renews_at));
                } elseif ($request->user()->subscription('main')->canceled_at != null) {
                    $subscription_data['plan_expired_at'] = date('Y-m-d\TH:i:s.000000\Z', strtotime($request->user()->subscription('main')->canceled_at));
                }
            }

            $response_to_send['subscription'] = $subscription_data;
            $response_to_send['plan'] = $plan ?? [];
            $response_to_send['invoices'] = $invoices;
        } catch (\Exception $e) {
            // Handle errors from Stripe or invalid customer ID
            $response_to_send['subscription'] = [];
            $response_to_send['plan'] = [];
            $response_to_send['invoices'] = [];
            $response_to_send['error'] = $e->getMessage();

            // if ($user_data['is_hoa_sub_user'] == null && $request->user()->is_custom_user == 1) {
            //     $hoaMainSubscription = \App\Models\Subscription::where('user_id', $userinfo->id)
            //         // ->where('stripe_status', 'active')
            //         ->first();
            //     if ($hoaMainSubscription != null) {

            //         $response_to_send['hoa_main_custom_subscription'] =
            //             [
            //                 "plan" => $hoaMainSubscription->plan_duration,
            //                 "renews_at" => $hoaMainSubscription->renews_at?? $hoaMainSubscription->canceled_at,
            //             ];
            //     }
            // }

            // if user is main user and custom subscription
            if ($response_to_send['hoa_sub_user_subscription'] == null && $request->user()->subscription('main')->plan_duration != null) {
                if (strtolower($request->user()->roles[0]->name) == 'hoa') {
                    $response_to_send['hoa_main_custom_subscription'] =
                        [
                            "plan" => $request->user()->subscription('main')->plan_duration,
                            "renews_at" => $request->user()->subscription('main')->renews_at ?? $request->user()->subscription('main')->canceled_at,
                        ];
                }
            }

            if ($response_to_send['hoa_sub_user_subscription'] == null && $request->user()->subscription('main')->plan_duration != null) {
                $response_to_send['is_custom_subscription'] = true;
                $response_to_send['subscription']['name'] =   $request->user()->subscription('main')->plan_duration;
                if ($request->user()->subscription('main')->renews_at != null) {
                    $response_to_send['subscription']['plan_expired_at'] = date('Y-m-d\TH:i:s.000000\Z', strtotime($request->user()->subscription('main')->renews_at));
                } elseif ($request->user()->subscription('main')->canceled_at != null) {
                    $response_to_send['subscription']['plan_expired_at'] = date('Y-m-d\TH:i:s.000000\Z', strtotime($request->user()->subscription('main')->canceled_at));
                }
            }
        }

        $card_details = [
            "card_brand" => $userinfo->card_brand ?? null,
            "card_last_four" => $userinfo->card_last_four ?? null
        ];

        if (!empty($userinfo->card_brand)) {
            $response_to_send['card_details'] = $card_details;
        } else {
            $response_to_send['card_details'] = [];
        }

        return response()->json($response_to_send);
    }


    public function destroy($id)
    {
        $user = User::find($id);
        $user = JWTAuth::user();
        DB::beginTransaction();
        try {
            $subscription = ModelsSubscription::where('user_id', $user->id)->first();

            if ($subscription == null) {

                return response()->json([
                    'response_code' => 404,
                    "status" => false,
                    "message" => "Subscription not found"
                ], 404);
            }
            $user->subscription('main')->cancel();
            DB::commit();
            return response()->json(['response_code' => 200, 'message' => 'Subscription Cancelled Successfully'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['response_code' => 500, 'error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function afterLoginSubscriptionPayment(Request $request)
    {





        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'stripeToken' => 'required',
            'trial_days' => 'required',
            'plan_id' => 'required',
            'plan_type' => 'required',
            'quantity' => 'required',
            'line1' => 'required',
            'line2' => 'nullable',
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



            try {
                // fetch user
                $user = JWTAuth::user();
                // dd($user);
                $request->merge(['email' => $user->email]); // Change this dynamically if needed
                $trial_days = $request->trial_days;
                $StripeplanId = $request->plan_id;
                $quantity = $request->quantity ?? 1;

                if ($user->trial_taken == 1) {

                    $trial_days = null;
                }
                // 1. Create Payment Method
                $paymentMethod = PaymentMethod::create([
                    'type' => 'card',
                    'card' => [
                        'token' => $request->stripeToken,
                    ],
                ]);

                $customer = $this->updateStripeCustomer($request, $paymentMethod, $user->stripe_id);

                $user->mode = DB::table('Stripe_keys')->where('status', 'active')->value('type');
                $user->city = $request->city;
                $user->state = $request->state;
                $user->country = $request->country;
                $user->stripe_id = $customer->id;
                $user->card_brand = $request->card_brand;
                $user->card_last_four = $request->card_last_four;

                if ($user->save()) {
                    if ($user->subscribed('main') && !$user->subscribedToPlan($StripeplanId, 'main')) {
                        // Swap subscription if different plan
                        $user->subscription('main')->quantity($quantity)->swap($StripeplanId);
                        // $user->subscription('main')
                        //     ->updateQuantity($quantity);

                        // $user->subscription('main')
                        //     ->swap($StripeplanId);
                    } else {
                        if ($coupon = $request->get('coupon')) {
                            $subscription_details = $this->withCouponCheckTrialDays($user, $request, $coupon, $StripeplanId, $quantity, $trial_days, $paymentMethod);
                        } else {
                            $subscription_details = $this->withoutCouponCheckTrialDays($user, $request, $StripeplanId, $quantity, $trial_days, $paymentMethod);
                        }
                    }
                    $this->updateUserRoleBasedOnSubscription($user, $request);

                    // Perform additional tasks (e.g., checking hotel rooms)
                    CheckPreviousHotelRooms($quantity, $user->id);

                    // Return a success response
                    return response()->json([
                        'message' => 'Payment successful !',
                        'subscription_details' => $subscription_details
                    ]);
                } else {
                    return response()->json(['error' => 'Something Went Wrong.'], 500);
                }
            } catch (\Stripe\Exception\CardException $e) {
                // Handle specific Stripe exceptions
                return response()->json(['error' => $e->getMessage()], 400);
            } catch (\Exception $e) {
                // Handle generic exceptions
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    }

    private function updateStripeCustomer($request, $paymentMethod, $customerId)
    {
        // Attach the payment method to the customer if it's not already attached
        $paymentMethodId = $paymentMethod->id; // Replace with the actual payment method ID
        try {
            $customer = \Stripe\Customer::retrieve($customerId);
            $paymentMethods = $customer->payment_methods;
        } catch (\Exception $e) {
            // 1. Create Payment Method
            $customer = $this->createStripeCustomer($request, $paymentMethod);
            $customerId = $customer->id;
            $paymentMethods = $customer->payment_methods;
        }
        if ($paymentMethods) {
            $attached = false;
            foreach ($paymentMethods as $paymentMethod) {
                if ($paymentMethod->id === $paymentMethodId) {
                    $attached = true;
                    break;
                }
            }
            if (!$attached) {
                $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);
                $paymentMethod->attach(['customer' => $customerId]);
            }
        } else {
            // If there are no payment methods yet, simply attach the provided one
            $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);
            $paymentMethod->attach(['customer' => $customerId]);
        }
        // Update customer details
        $updatedCustomer = Customer::update(
            $customerId,
            [
                'name' => $request->name,
                'email' => $request->email,
                'invoice_settings' => [
                    'default_payment_method' => $paymentMethod->id
                ],
                'address' => [
                    'line1' => $request->line1,
                    'line2' => $request->line2 ?? '',
                    'city' => $request->city,
                    'state' => $request->state,
                    'country' => $request->country,
                    'postal_code' => $request->zip_code
                ]
            ]
        );
        return $updatedCustomer;
    }

    public function updateUserRoleBasedOnSubscription($user, $request)
    {
        if ($request->plan_type == 'tiered') {
            $tieruserrole = Role::where('name', 'tieruser')->first();

            $userRole = $user->roles->first()->display_name;

            if ($userRole !== 'admin' || 'superadmin') {

                $user->roles()->sync([$tieruserrole->id]);
            }
        } else {
            $tieruserrole = Role::where('name', 'user')->first();

            $userRole = $user->roles->first()->display_name;

            if ($userRole !== 'admin' || 'superadmin') {

                $user->roles()->sync([$tieruserrole->id]);
            }
        }
    }

    private function get_user_invoices($customerId)
    {
        $invoices_data = \Stripe\Invoice::all(['customer' => $customerId, 'expand' => ['data.charge']]);
        $invoices = [];
        foreach ($invoices_data->data as $invoice) {
            $amount = Cashier::formatAmount($invoice->total ?? '0', $invoice->currency ?? 'USD');
            $invoice_id = $invoice->id;
            // $date = date('Y-m-d H:i:s', $invoice->created);
            $date = date('Y-m-d H:i:s', $invoice->status_transitions->paid_at ?? $invoice->created);
            $receipt_link = $invoice->charge->receipt_url ?? null;

            $invoices[] = [
                'id' => $invoice_id,
                'amount' => $amount,
                'date' => $date,
                'receipt_link' => $receipt_link
            ];
        }

        return $invoices;
    }

    private function getSubscriptionDetails($user)
    {
        try {
            $customerId = $user->stripe_id;
            if (!$customerId) {
                throw new \Exception("Stripe ID is null or invalid.");
            }

            $customer = \Stripe\Customer::retrieve($customerId);
            $subscription = $customer->subscriptions->data[0] ?? $user->subscriptions()->first();

            // print_r($subscription);
            // var_dump($user->subscribed('main'));
            // die;
            // if (!$user->subscribed('main')) {
            //     return [
            //         'subscription_data' => [],
            //         'error' => ''
            //     ];
            // }

            $STRIPE_KEY = DB::table('Stripe_keys')->where('status', 'active')->get();
            if ($STRIPE_KEY[0]->type != $user->mode) {
                return [
                    'subscription_data' => [],
                    'error' => ''
                ];
            }

            $plan = $subscription->plan ?? \Stripe\Plan::retrieve(['id' => $subscription->stripe_plan, 'expand' => ["tiers"]]);
            $invoices_data = \Stripe\Invoice::all(['customer' => $customerId, 'expand' => ['data.charge']]);
            $renewaldatetime = $subscription->cancel_at
                ? 'Plan Expired'
                : (isset($subscription->current_period_end) ? date('Y-m-d H:i:s', $subscription->current_period_end) : 'No upcoming invoices');

            // For subscription details
            if (!empty($plan->tiers)) {
                $plan_name = ($plan->nickname ?? '') . ' ' . ('with ' . $subscription->quantity . ' quantity' ?? '') . ' ' . (Cashier::formatAmount($invoices_data->data[0]->total ?? '0', $invoices_data->data[0]->currency ?? 'USD')) . ' per ' . ($plan->interval_count ?? '') . ' ' . ($plan->interval ?? '');
                $plan_type = "Tiers" . ' ' . $plan->interval_count . ' ' . $plan->interval;
            } else {
                $plan_name = ($plan->nickname ?? '') . ' ' . (Cashier::formatAmount($plan->amount ?? '0', $plan->currency ?? 'USD')) . '/' . ($plan->interval_count ?? '') . ' ' . ($plan->interval ?? '');
                $plan_type = $plan->billing_scheme;
            }
            return [
                'subscription_data' => [
                    'id' => $subscription->id,
                    'name' => $plan_name,
                    'plan_type' => $plan_type,
                    'renewaldatetime' => $renewaldatetime,
                    'payment_method' => ($user->card_brand ?? '') . ' ' . ($user->card_last_four ?? ''),
                ],
                'error' => ''
            ];
        } catch (\Exception $e) {
            return [
                'subscription_data' => [],
                'error' => $e->getMessage(),
            ];
        }
    }
}
