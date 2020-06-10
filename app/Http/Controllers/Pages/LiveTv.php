<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LiveTv extends Controller
{
    public function showPage(){

    	return view('frontend.pages.livetv');

    }

    public function showChannel(){

    	return view('frontend.pages.livetvchannel');

    }
}
