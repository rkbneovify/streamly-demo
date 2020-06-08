<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Payment extends Controller
{
    public function showPage(){

    	return view('frontend.pages.payment');

    }
}
