<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Laravel\Cashier\Exceptions\IncompletePayment;
class Payment extends Controller
{
    public function showPage(){

    	return view('frontend.pages.payment');

    }

    public function pay (Request $request) {
        $user = auth()->user();
        try {
            $newSubscription = $user->newSubscription('main', 'price_1Gr90hLqTwOy7LECCw9EzmLs')->create($request->payment_method, ['email' => $user->email]);
        } catch ( IncompletePayment $exception ){
            return redirect()->back()->with(['error_message' => $exception->getMessage()]);
        }

        return redirect()->back();
    }
    
}
