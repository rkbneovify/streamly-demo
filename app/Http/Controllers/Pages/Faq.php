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
    public function termsofuse(){

    	if( Auth::check()){
            $login = true;
        } else {
            $login = false;
        }
    	return view('frontend.pages.termofuse', compact('login'));

    } 
    public function privacy(){

    	if( Auth::check()){
            $login = true;
        } else {
            $login = false;
        }
    	return view('frontend.pages.privacy', compact('login'));

    } 
    public function accessibility(){

    	if( Auth::check()){
            $login = true;
        } else {
            $login = false;
        }
    	return view('frontend.pages.accessibility', compact('login'));

    }
   
}
