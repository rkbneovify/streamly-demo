<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Laravel\Cashier\Exceptions\IncompletePayment;
class Payment extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth');
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    }
    public function showPage($stripeplanID){

    	if( Auth::check()){
            $login = true;
        } else {
            $login = false;

            return redirect()->route('home');
        }
        $plandetails = \Stripe\Plan::retrieve($stripeplanID);
        
       
        if (Auth::user()->subscription('main')->cancelled()){
        return view('frontend.pages.paymentcancelled', compact('login','stripeplanID','plandetails'));
    }else{
        return view('frontend.pages.payment', compact('login','stripeplanID','plandetails'));
    }
        
        
    }
    public function showPlan(){
        $plans = \Stripe\Plan::all(['active'=>true]);
    	if( Auth::check()){
            $login = true;
        } else {
            $login = false;
            return redirect()->route('home');
        }

    	return view('frontend.pages.plans', compact('login','plans'));
        
    }

    public function pay(Request $request,$StripeplanId) {
        
        $user = auth()->user();
       
        try {
            // check already subscribed and if already subscribed with picked plan
        if( $user->subscribed('main') && ! $user->subscribedToPlan($StripeplanId, 'main') ) {

            // swap if different plan attempt
            $user->subscription('main')->swap($StripeplanId);

        }else {
            
           // if user has a coupon, create new subscription with coupon applied
           if( $coupon = $request->get('coupon') ) {
            
            $user->newSubscription( 'main', $StripeplanId)->trialDays($request->trial_days)
                ->withCoupon($coupon)
                ->create($request->payment_method, ['email' => $user->email]);

        } else {
        
            // Create subscription
            $newSubscription = $user->newSubscription('main', $StripeplanId)
            
            ->trialDays($request->trial_days)->create($request->payment_method, ['email' => $user->email]);
        }}
        } catch ( IncompletePayment $exception ){
            return redirect()->back()->with(['error_message' => $exception->getMessage()]);
        }

        return redirect()->back();
    }
    
}
