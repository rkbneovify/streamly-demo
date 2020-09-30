<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Faq extends Controller
{
    public function showPage(){

    	if( Auth::check()){
            $login = true;
        } else {
            $login = false;
        }
    	return view('frontend.pages.faq', compact('login'));

    }
   
}
