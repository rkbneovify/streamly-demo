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

        $allSession = DB::table('Stripe_keys')
        ->select('id','type', 'stripe_key', 'stripe_secret', 'status')
        ->where('status', 'active')
        ->get();
        
        return view('admin.stripe_keys.stripekeys', compact('allSession'));
    }

    public function keysupdate($status){

        if($status == 'live'){
         DB::table('Stripe_keys')
            ->where('id', 2)
            ->update(array(
             'status' => 'active',
          ));

         DB::table('Stripe_keys')
            ->where('id', 1)
            ->update(array(
             'status' => 'inactive',
          ));

          return response()->json([
            'success' => true,
            'response_code'=>200,
            'message' => 'Live keys activated'
          ],200)->send();

        }elseif($status == 'testing'){
            DB::table('Stripe_keys')
            ->where('id', 1)
            ->update(array(
             'status' => 'active',
          ));

         DB::table('Stripe_keys')
            ->where('id', 2)
            ->update(array(
             'status' => 'inactive',
          ));

          return response()->json([
            'success' => true,
            'response_code'=>200,
            'message' => 'Testing keys activated'
          ],200)->send();

        }else{

            return response()->json([
                'success' => false,
                'response_code'=>479,
                'message' => 'technical error!'
              ],479)->send();
        }
    }

}