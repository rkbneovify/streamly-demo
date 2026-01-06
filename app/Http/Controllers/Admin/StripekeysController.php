<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Setting;
use App\Traits\ActionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use RealRashid\SweetAlert\Facades\Alert;

class StripekeysController extends Controller
{
  use ActionTrait;
  public function index()
  {

    $allSession = DB::table('Stripe_keys')
      ->select('id', 'type', 'stripe_key', 'stripe_secret', 'status')
      ->where('status', 'active')
      ->get();

    $setting = Setting::where('key_name', 'qrvisibility')->first();


    return view('admin.stripe_keys.stripekeys', compact('allSession', 'setting'));
  }

  public function keysupdate($status)
  {

    if ($status == 'live') {
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
        'response_code' => 200,
        'message' => 'Live keys activated'
      ], 200)->send();

    } elseif ($status == 'testing') {
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
        'response_code' => 200,
        'message' => 'Testing keys activated'
      ], 200)->send();

    } else {

      return response()->json([
        'success' => false,
        'response_code' => 479,
        'message' => 'technical error!'
      ], 479)->send();
    }
  }

  public function qrCodeStatus()
  {
    return $this->startAction(function () {
      $setting = Setting::where('key_name', 'qrvisibility')->first();
      if ($setting->value == '1') {
        $setting->value = '0';
      } else {
        $setting->value = '1';
      }
      $setting->save();
    })->catch(function ($e) {
      Alert::error('Error !', $e->getMessage());
      return redirect()->back();
    })->finally(function () {
      toast('Status changes successfully !', 'success');
      return redirect()->back();
    })->execute();

  }

}