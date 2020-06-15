<?php

namespace App\Http\Controllers\Pages\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Setting extends Controller
{
    // Account Settings page
    public function showSettingpage(){

    	if( Auth::check()){
            $login = true;
        } else {
            $login = false;
        }

    	return view('frontend.pages.account.setting', compact('login'));
    }
}
