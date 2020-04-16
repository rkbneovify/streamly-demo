<?php
use Carbon\Carbon;
use Jenssegers\Agent\Agent;
/// check for 3 concurrent login
     function checkLoginLimit($user_id,$token){
        $login_limit = 3;
    //find all by user_id and and status=active
    $active_session = App\SessionTable::where([
    ['status', '=', '1'],
    ['users_id', '=', $user_id],
     ])->get()->toArray();
    // print_r ($active_session); 
    //echo count($active_session); exit;
        //check if there's no entry by this used id
        if ((count($active_session)==0)) {
         //new entry of sessionTable
         

        $date = date('Y-m-d H:i:s');
        
        //Store the login info in 
        $task = new  App\SessionTable();
        //$task->users_id = $request->users_id;
        $task->users_id = $user_id;
        $task->access_token = 'somrthing';//change this field to status
        $task->user_ip = getUserIpAddr();
        $task->mac_address = 'from login entry';
        $task->connected_devices = 1;
       $task->logintime = $date;;
        $task->status = 1;
        $task->created_on = $date;
        $task->modified_on = $date;
         
        if($task->save()){
        return response()->json([
      
          'success' => true,
          'token' => $token,
          'msg'=>'first logged in successfully \r\n ',
          'updatedid'=>$task->id,
          
          
      ])->send();}

        }
        else if (count($active_session) > 0 && count($active_session) < $login_limit) {

         //new entry of sessionTable
         //and get the number of last connected device
         $prevConnectDevicecount = App\SessionTable::select('connected_devices')
          ->where([
          ['status', '=', '1'],
          ['users_id', '=', $user_id],
           ])->orderBy('id', 'desc')
           ->first();
           
         //echo $prevConnectDevicecount['connected_devices']; 
        $device=number_format($prevConnectDevicecount['connected_devices']); 
        //the number of device previously connected + 1
        $newnumofDevice =$device+1; 
        $date = date('Y-m-d H:i:s');
        
        //Store the login info in 
        $task = new App\SessionTable();
        //$task->users_id = $request->users_id;
        $task->users_id = $user_id;
        $task->access_token = 'somrthing';//change this field to status
        $task->user_ip = getUserIpAddr();
        $task->mac_address = 'from login entry with +1 in connected device';
        $task->connected_devices= $newnumofDevice;
        $task->logintime = $date;;
        $task->status = 1;
        $task->created_on = $date;
        $task->modified_on = $date;
        $task->save();
        if($task->save()){
          
          return response()->json([
        
            'success' => true,
            'token' => $token,
            'msg'=>'2nd or 3rd login in successfully \r\n ',
            'updatedid'=>$task->id,
            
        ])->send();}
        
       

    } //after iteration we check if the count of logins is still greater than the limit
    if (count($active_session) >= $login_limit) {
        //then return a login error that maximum logins reached
        //echo 'you are not allowed to login as you have breeched the maximum session limit.';
        //exit;
        return response()->json([
          'success' => true,
          'msg'=>'Your account is in use on 5 devices. Please stop playing on other devices to continue',
          //'loggedindeviceinfointheseId'=>$myarr,
      ])->send();
        


    } else {
        
      echo 'finally logged in successfully';
      echo 'token:'.$token;
    return response()->json([
      
        'success' => true,
        'token' => $token,
        'msg'=>'finally logged in successfully'
        
        
    ])->send();
    }

    //update the logins column to equal to json_encode($logins);

    
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


?>