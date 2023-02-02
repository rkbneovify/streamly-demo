<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JWTAuth;


class StripekeysController extends Controller
{     
    public function index()
    {
        // dd('hkfjsh');
        // die();
        $allSession = 0;
        return view('admin.stripe_keys.stripekeys', compact('allSession'));
    }

    public function keysupdate($status){

        if($status == 'live'){
         DB::table('stripe_credentials')
            ->where('id', 2)
            ->update(array(
             'status' => 'active',
          ));

         DB::table('stripe_credentials')
            ->where('id', 1)
            ->update(array(
             'status' => 'inactive',
          ));
        }elseif($status == 'testing'){
            DB::table('stripe_credentials')
            ->where('id', 1)
            ->update(array(
             'status' => 'active',
          ));

         DB::table('stripe_credentials')
            ->where('id', 2)
            ->update(array(
             'status' => 'inactive',
          ));
        }else{

        }
    }

}