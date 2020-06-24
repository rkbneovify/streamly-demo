<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Channel;
use App\Models\Gracenotechannel;
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
      $Activeusers = User::where([
        ['is_active', '=', '1'],
         ])->count();
         $channel=Gracenotechannel::count();
         $Subscription=Subscription::count();
         $Links = Channel::where([
          ['status', '=', '1'],
           ])->count();
         return view('home', ['Activeusers' => $Activeusers,'channel' => $channel,'Subscription' => $Subscription,'Links' => $Links,]);
      
    }

    
}
