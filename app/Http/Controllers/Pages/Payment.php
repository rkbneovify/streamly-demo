<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Laravel\Cashier\Exceptions\IncompletePayment;
class Payment extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function showPage(){
<<<<<<< HEAD

    	if( Auth::check()){
            $login = true;
        } else {
            $login = false;
        }

    	return view('frontend.pages.payment', compact('login'));
=======
        
        
    	return view('frontend.pages.payment');
>>>>>>> 6e2f28ec4fa0ce5938d6d2b75d5d3e5454154025

    }

    public function pay(Request $request) {
        
        $user = auth()->user();
        //echo $user;exit;
 
        try {
            $newSubscription = $user->newSubscription('main', 'price_1Gr90hLqTwOy7LECCw9EzmLs')->create($request->payment_method, ['email' => $user->email]);
        } catch ( IncompletePayment $exception ){
            return redirect()->back()->with(['error_message' => $exception->getMessage()]);
        }

        return redirect()->back();
    }
    
}
