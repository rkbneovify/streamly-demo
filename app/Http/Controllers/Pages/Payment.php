<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Payment extends Controller
{
    public function showPage(){

    	if( Auth::check()){
            $login = true;
        } else {
            $login = false;
        }

    	return view('frontend.pages.payment', compact('login'));

    }
}
