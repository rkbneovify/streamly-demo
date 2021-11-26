<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Gracenote;
use App\Models\Genre;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

use App\SessionTable;
use Illuminate\Support\Facades\Auth;
/**
 * Description of DashboardController
 *
 */

class DashboardController extends Controller
{
         
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() 
    {
        $users = User::count();
        return view('admin.dashboard', ['users' => $users]);
    }
    public function testing() {
        $channels = Channel::select('url','name',DB::raw('IFNULL(stationName,name) stationName'))
        ->where('status','=','1')
        ->orderBy('name', 'asc')
        ->get();
        $encoded_auth_xml = getKeyOSAuthToken();
        return view('frontend.drm.playlist', compact('encoded_auth_xml','channels'));
    }
    public function qrtesting() {
      
        return view('frontend.drm.qrtesting');
        
    }

    public function qr() {
        
    	if(Auth::check()){
            $login = true;
            
            $userRole= Auth::user()->roles->pluck('name');
            if($userRole[0]=='user'||'tieruser'){
                // dd("you loged in");
                return "you are already logged in, open it in incognito mode";
            // if (!Auth::user()->subscribed('main')) {
            //     return redirect('/selectplan');
            // }
        }
        } else {
            $login = false;

        return view('frontend.drm.qr');
        }
    }
    public function qrscanner() {
        if(Auth::check()){
            $login = true;
            $user_id = Auth::user()->id;
            $activeNowsession = SessionTable::where([
                ['status', '=', '1'],
                ['users_id', '=', $user_id],
                ['access_token', '=', session()->get('jwttoken')],
                 ])->get()->count();   
            
                  if($activeNowsession!=0){

                    return view('frontend.drm.qrscanner',compact('login'));
                  }
                  $active_session = SessionTable::where([
                    ['status', '=', '1'],
                    ['users_id', '=', $user_id],
                     ])->get()->toArray();
                     $login_limit =GetLimitForUser(Auth::user()->id);
                  if (count($active_session) >= $login_limit) {
                    $login = true;
                    return view('frontend.deviceLimitReached',compact('login','login_limit'));
                }
            return view('frontend.RefreshVideoPlayToken',compact('login'));
        
        
        }
        return redirect()->route('home');
    }
    public function qrscanner2() {
        if(Auth::check()){
            $login = true;
            $user_id = Auth::user()->id;
            $activeNowsession = SessionTable::where([
                ['status', '=', '1'],
                ['users_id', '=', $user_id],
                ['access_token', '=', session()->get('jwttoken')],
                 ])->get()->count();   
            
                  if($activeNowsession!=0){

                    return view('frontend.drm.qrscanner2',compact('login'));
                  }
                  $active_session = SessionTable::where([
                    ['status', '=', '1'],
                    ['users_id', '=', $user_id],
                     ])->get()->toArray();
                     $login_limit =GetLimitForUser(Auth::user()->id);
                  if (count($active_session) >= $login_limit) {
                    $login = true;
                    return view('frontend.deviceLimitReached',compact('login','login_limit'));
                }
            return view('frontend.RefreshVideoPlayToken',compact('login'));
        
        
        }
        return redirect()->route('home');
    }

    public function debugmpd(){
        try {
            $encoded_auth_xml = getKeyOSAuthToken();
              return view('frontend.debug', compact('encoded_auth_xml'));
            }
            catch(Exception $e) {
              
              return $e->getMessage();
            }
    }

}
