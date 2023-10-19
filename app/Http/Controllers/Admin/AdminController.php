<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Channel;
use App\Models\Gracenotechannel;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription;
class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:superadmin|admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      $mode =DB::table('Stripe_keys')->where('status', 'active')->value('type');
      $Activeusers = User::where([
        ['is_active', '=', '1'],
        ['mode', '=', $mode],
         ])->count();
         $channel=Gracenotechannel::count();
         $Subscription=Subscription::count();
         $Subscription = Subscription::with('user')->whereHas('user', function ($query) use ($mode) {
          $query->where('mode', $mode);
         })->count();
         $Links = Channel::where([
          ['status', '=', '1'],
           ])->count();
         return view('home', ['Activeusers' => $Activeusers,'channel' => $channel,'Subscription' => $Subscription,'Links' => $Links,]);
      
    }

    
}
