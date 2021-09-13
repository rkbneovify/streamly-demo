<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Session;
use JWTAuth;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

use Carbon\Carbon;
use App\SessionTable;
class LoginSuccessful
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = Auth::user();
        $userRole= $user->roles->pluck('name');
         
        if( @$userRole[0]== 'admin' || @$userRole[0]== 'superadmin' ) {
            return '/admin';
        } else{

        $token = JWTAuth::fromUser($user);

        $deviceDetails=getDeviceIdforWeb();
        if($userRole[0]=='user'||'tieruser'){
            $uid=  $user['id'];
            $this->checkLoginLimit($uid,$token,$deviceDetails);    
        }
        
        return true;  }


    }

    public function checkLoginLimit($user_id,$token,$deviceDetails){
        $login_limit = GetLimitForUser($user_id);
    //find all by user_id and and status=active
    $active_session = SessionTable::where([
    ['status', '=', '1'],
    ['users_id', '=', $user_id],
     ])->get()->toArray();
        //check if there's no entry by this used id
        if ((count($active_session)==0)) {
         //new entry of sessionTable
         sessionTableForWeb($user_id,$token,$deviceDetails,$connected_devices=1);
     } else if (count($active_session) > 0 && count($active_session) < $login_limit) {
          //check if device id is same
          
         //new entry of sessionTable
         //and get the number of last connected device
         $prevConnectDevicecount = SessionTable::select('connected_devices')
          ->where([
          ['status', '=', '1'],
          ['users_id', '=', $user_id],
           ])->orderBy('connected_devices', 'desc')
           ->first();
           
        $device=number_format($prevConnectDevicecount['connected_devices']); 
        //the number of device previously connected + 1
        sessionTableForWeb($user_id,$token,$deviceDetails,$connected_devices=$device+1);
        

    } //after iteration we check if the count of logins is still greater than the limit
    if (count($active_session) >= $login_limit) {
        //then return a login error that maximum logins reached
        //echo 'you are not allowed to login as you have breeched the maximum session limit.';
        //exit;
        session(
            [
                'jwttoken' => 'no jwt'
            ]
        );
        return true;
        


    }


    
}
}
