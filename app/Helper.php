<?php
use Carbon\Carbon;

use Jenssegers\Agent\Agent;
     function checkLoginLimit($user_id,$token,$deviceDetails){
        $login_limit = 5;
    //find all by user_id and and status=active
    $active_session = App\SessionTable::where([
    ['status', '=', '1'],
    ['users_id', '=', $user_id],
     ])->get()->toArray();
        //check if there's no entry by this used id
        if ((count($active_session)==0)) {
         //new entry of sessionTable
         
       
        $date = date('Y-m-d H:i:s');
        
        //Store the login info in 
        $task = new  App\SessionTable();
        $task->users_id = $user_id;
        $task->access_token = $token;
        $task->user_ip = getUserIpAddr();
        $task->mac_address = $deviceDetails;
        $task->connected_devices = 1;
       $task->logintime = Carbon::now();
        $task->status = 1;
        $task->created_on = $date;
        $task->modified_on = Carbon::now();
        
        
        if($task->save()){
        return response()->json([
      
          'success' => true,
          'message'=>'logged in successfully ',
          'response_code'=>200,
          'connected_device'=> '1 device connected  ',
          'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'device_info'=>$deviceDetails
            
          
          
        ],200)->send();}

        } else if (count($active_session) > 0 && count($active_session) < $login_limit) {

         //new entry of sessionTable
         //and get the number of last connected device
         $prevConnectDevicecount = App\SessionTable::select('connected_devices')
          ->where([
          ['status', '=', '1'],
          ['users_id', '=', $user_id],
           ])->orderBy('connected_devices', 'desc')
           ->first();
           
        $device=number_format($prevConnectDevicecount['connected_devices']); 
        //the number of device previously connected + 1
        
        $newnumofDevice =$device+1; 
        
        $date = date('Y-m-d H:i:s');
        
        //Store the login info in 
        $task = new App\SessionTable();
        //$task->users_id = $request->users_id;
        $task->users_id = $user_id;
        $task->access_token = $token;//change this field to status
        $task->user_ip = getUserIpAddr();
        $task->mac_address = $deviceDetails;
        $task->connected_devices= $newnumofDevice;
        $task->logintime = Carbon::now();
        $task->status = 1;
        $task->created_on = $date;
        $task->modified_on = Carbon::now();
        $task->save();
        if($task->save()){
          
          return response()->json([
        
            'success' => true,
            'message'=>'logged in successfully ',
            'connected_device'=> $newnumofDevice.' device connected  ',
            'response_code'=>200,
            

           // 'user'=>$userInfo,
          'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'device_info'=>$deviceDetails
            
          ],200)->send();}
        
       

    } //after iteration we check if the count of logins is still greater than the limit
    if (count($active_session) >= $login_limit) {
        //then return a login error that maximum logins reached
        //echo 'you are not allowed to login as you have breeched the maximum session limit.';
        //exit;
        return response()->json([
          'success' => false,
          'response_code'=>429,
          'message'=>'Your account is in use on 5 devices. Please stop playing on other devices to continue',
          //'loggedindeviceinfointheseId'=>$myarr,
        ],429)->send();
        


    }


    
}
function automaticLogoutWithInterval($user_id){
  $login_limit = 5;
  //find all by user_id and and status=active
  $active_session = App\SessionTable::where([
  ['status', '=', '1'],
  ['users_id', '=', $user_id],
   ])->get()->toArray();
if (count(($active_session)) >= $login_limit) {
  //automatic logout
  // $prevConnectLoginIntime = App\SessionTable::select('logintime')->addSelect('id')
  //   ->where([
  //   ['status', '=', '1'],
  //   ['users_id', '=', $user_id],
  //    ])->orderBy('modified_on', 'desc')
  //    ->get()->toArray();
   //  print_r($prevConnectLoginIntime); 
    //get last login entry of the 
    
     $data = App\SessionTable::select('logintime')->addSelect('id')
     ->where([
     ['status', '=', '1'],
     ['users_id', '=', $user_id],
      ])->orderBy('modified_on', 'desc')
      ->get();
      $myarr=[];
      foreach($data as $fieldnam) {
        //increase this time when creating cms for now time is 2 minutes
        $logintime=$fieldnam->logintime;
        $dateTimeMinutesAgo = new DateTime("2 minutes ago");
        $dateTimeMinutesAgo = $dateTimeMinutesAgo->format("Y-m-d H:i:s");
      //   echo "\r\n Login:";
      //   echo $logintime;
      //   echo "\r\n datetimeTwominutes ago:";
      //   echo $dateTimeMinutesAgo;
        if ($logintime <  $dateTimeMinutesAgo){
        //  echo "true";
        
         array_push($myarr,$fieldnam->id );                
  
    }
    
     }
     //echo 'ids';
    // print_r($myarr);
     //'connected_devices'=> DB::raw('connected_devices-1'),
     $decremen=count($myarr);// number of id we will use this number to decrement the last value
     $updatedExpireConcrenteUser=App\SessionTable::whereIn('id', $myarr)->update(['status' => 0,'modified_on' => Carbon::now()]);
   //  $updatedConnectedDeviceNum=App\SessionTable::whereIn('id', $myarr)->update(['connected_devices' => 0,'modified_on' => Carbon::now()]);
     
     if(!is_null($myarr)){

  //check max device connected with user_id
  
     $prevConnectDeviceMaxcount=App\SessionTable::where([
      ['status', '=', 1],
      ['users_id', '=',$user_id ]
  ])->orderBy('connected_devices','desc')->first(); //echo 'Max count';
     $maxdevice=$prevConnectDeviceMaxcount['connected_devices']; 
    $lastid=$prevConnectDeviceMaxcount['id'];
     
     
     
     $newnumofDevice= $maxdevice-$decremen;  //newNumofDevice
     if(!$lastid== ''){
     
         //now update the new number of device in last active id
         App\SessionTable::where('id', $lastid)->update([
          'connected_devices' => $newnumofDevice
          ]);
      }
       }
     
     
     if($updatedExpireConcrenteUser) {
      config([
        'jwt.blacklist_enabled' => true
    ]);
      return response()->json([
          'success' => true,
          'message'=>'logged out automatically successful',
          'myid'=>$myarr,
      ])->send();
  } 
   
     
 //automatic logout finish  
}}

function LogoutandDecrementDevice($user_id){
  

  //logout
  $myarr=[];
  array_push($myarr,$user_id ); 
     
     $decremen=count($myarr);// number of id we will use this number to decrement the last value
     $updatedExpireConcrenteUser=App\SessionTable::whereIn('id', $myarr)->update(['status' => 0,'modified_on' => Carbon::now()]);
     
     
 //if logout happens minus one from all the active device of user
 //UPDATE `session_table` SET `connected_devices` = `connected_devices` - 1 WHERE `status` = 1 AND `connected_devices`<> 1
    App\SessionTable::where([
            ['status', '=', '1'],
            ['connected_devices', '!=', '1'],
             ])
    ->update([
        'connected_devices' => DB::raw('`connected_devices` - 1 ')
    ]);
    config([
      'jwt.blacklist_enabled' => true
  ]);
    JWTAuth::parseToken()->invalidate();
      return response()->json([
        'response_code'=>200,
        'success' => true,
        'message'=>'logged out successful',
        
      ],200)->send();
    
     
}


function getUserIpAddr(){
  if(!empty($_SERVER['HTTP_CLIENT_IP'])){
      //ip from share internet
      $ip = $_SERVER['HTTP_CLIENT_IP'];
  }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
      //ip pass from proxy
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  }else{
      $ip = $_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}

//working with subscription pack
function addInterval(Carbon $date)
{
    switch ($this->interval) {
        case 'yearly':
            $date->addYear();
            break;
        case 'quarterly':
            $date->addMonths(3);
            break;
        case 'weekly':
            $date->addWeek();
            break;
        case 'monthly':
        default:
            $date->addMonth();
    }
    return $date;
}


?>