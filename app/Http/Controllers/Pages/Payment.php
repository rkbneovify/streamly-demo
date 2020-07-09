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
        
    	return view('frontend.pages.payment', compact('login','stripeplanID','plandetails'));
        
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
        //echo $user;exit;
 
        try {
            $newSubscription = $user->newSubscription('main', $StripeplanId)->create($request->payment_method, ['email' => $user->email]);
        } catch ( IncompletePayment $exception ){
            return redirect()->back()->with(['error_message' => $exception->getMessage()]);
        }

        return redirect()->back();
    }
    
}
