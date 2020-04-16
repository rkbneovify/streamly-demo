<?php


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
        $task->save(); 
       

        }
        else if (count($active_session) > 0 && count($active_session) < $login_limit) {

         //new entry of sessionTable
         //and get the number of last connected device
         $prevConnectDevicecount = App\SessionTable::select('connected_devices')
          ->where([
          ['status', '=', '1'],
          ['users_id', '=', $user_id],
           ])->orderBy('modified_on', 'desc')
           ->first();
           
         //echo $prevConnectDevicecount['connected_devices']; 
        $device=number_format($prevConnectDevicecount['connected_devices']); 
        //the number of device previously connected + 1
        $newnumofDevice =$device+1; 
        $date = date('Y-m-d H:i:s');
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
        
       

    }else if (count(($active_session)) >= $login_limit) {
        
        $prevConnectLoginIntime = App\SessionTable::select('logintime')->addSelect('id')
          ->where([
          ['status', '=', '1'],
          ['users_id', '=', $user_id],
           ])->orderBy('modified_on', 'desc')
           ->get()->toArray();
         //  print_r($prevConnectLoginIntime); 

           $data = App\SessionTable::select('logintime')->addSelect('id')
           ->where([
           ['status', '=', '1'],
           ['users_id', '=', $user_id],
            ])->orderBy('modified_on', 'desc')
            ->get();
            foreach($data as $fieldnam) {
              //increase this time when creating cms
              if ($fieldnam->logintime < time() - 120) {
                echo "true";
         $fieldnam->logintime ;
         $fieldnam->id ;}
          
        }
           exit;
           
         //find all by user_id and and status=active
        
        foreach ($prevConnectLoginIntime as $key => $login) {
            if ($login->logintime < time() - 120) {
                //this checks if the iterated login is greater than the current time -120seconds and if found to be true then the user is inactive
                //then set this current login to null by using the below statement
                $logins[$key] = 0; // or unset($logins[$key]) either should work;
                //unset($logins[$key]);
            }
        }
    } //after iteration we check if the count of logins is still greater than the limit
    if (count($logins) >= $login_limit) {
        //then return a login error that maximum logins reached
        //echo 'you are not allowed to login as you have breeched the maximum session limit.';
        //exit;
        $login_json = json_encode($logins);
        $active_session->conc_login = $login_json;
        $active_session->save();

        $this->addError($attribute, 'you are not allowed to login as you have breeched the maximum session limit.');

    } else {
        //then login is successsfull
        

        $date = date('Y-m-d H:i:s');
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
        //Store the login info in 
        $task = new SessionTable();
        //$task->users_id = $request->users_id;
        $task->users_id = $user_id;
        $task->access_token = 'somrthing';//
        $task->user_ip = getUserIpAddr();
        $task->mac_address = 'from login entry';
        $task->connected_devices = 1;
       $task->logintime = $date;;
        //$task->description = $request->description;
        $task->created_on = $date;
        $task->modified_on = $date;
        $task->save();
      // to detect browser
            $agent = new Agent();
            $device = $agent->device();
            $platform = $agent->platform();
            $browser = $agent->browser();
           $browser_name =$browser.''.$device.''.$platform;
           $t=$browser_name?$browser_name:'unknown';
    return response()->json([
        'success' => true,
        'token' => $token,
        'browser_name'=>$t,
        'user'=>$user
    ]);
    }

    //update the logins column to equal to json_encode($logins);

    
}


?>