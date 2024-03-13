<?php
use Carbon\Carbon;
use App\SessionTable;
//use Illuminate\Support\Facades\Http;
use App\HotelRoomTable;
use App\Models\Subscription;
use App\Models\Zipcode;
use App\Models\PersonalDevice;
use Jenssegers\Agent\Agent;
use Hashids\Hashids;
use App\User;
     function checkLoginLimit($user_id,$token,$deviceDetails){
        $login_limit =  GetLimitForUser($user_id);
    //find all by user_id and and status=active
    $active_session = App\SessionTable::where([
    ['status', '=', '1'],
    ['users_id', '=', $user_id],
     ])->get()->toArray();
        //check if there's no entry by this used id
        if ((count($active_session)==0)) {
         //new entry of sessionTable
         sessionTable($user_id,$token,$deviceDetails,$connected_devices=1);
     } else if (count($active_session) > 0 && count($active_session) < $login_limit) {
          //check if device id is same
          if(duplicateDeviceId($user_id,$deviceDetails)==false){
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
        sessionTable($user_id,$token,$deviceDetails,$connected_devices=$device+1);
        }

    } //after iteration we check if the count of logins is still greater than the limit
    if (count($active_session) >= $login_limit) {
        //then return a login error that maximum logins reached
        //echo 'you are not allowed to login as you have breeched the maximum session limit.';
        //exit;
        // return response()->json([
        //   'success' => false,
        //   'response_code'=>479,
        //   'message'=>'Your account is in use on '.$login_limit .' devices. please stop playing on other devices to continue',
        //   //'loggedindeviceinfointheseId'=>$myarr,
        // ],479)->send();
        
    
        $userEmailID = $user_id;
        // $userEmailID = User::where('email', $userEmail)->first()->id;
         $uid = $userEmailID;
         $user= User::find($uid);
         //$user = JWTAuth::user();//all the user info
        // $uid=  $user['id'];
         //and get the number of last connected device
         $userRole=  $user->roles->first()->name;
         // $user = JWTAuth::user();//all the user info
         // $uid=  $user['id'];
         // //and get the number of last connected device
         // $userRole=  $user->roles->first()->name;
         if($userRole=='tieruser'){
         $prevConnectDevicecount = SessionTable::with(['hotel:id,hotel_room,session_id','pdname'=> function($q) use($uid) {
             // Query the name field in status table
             $q->select('id','personal_devicename','device_id','user_id')->where('user_id', '=', $uid);
         }])
         ->selectRaw("id ,connected_devices, SUBSTRING_INDEX(mac_address, '#~', -1) device_id,SUBSTRING_INDEX(mac_address, '#~', 1) device_name,device")
         ->where([
         ['status', '=', '1'],
         ['users_id', '=', $uid],
          ])->orderBy('connected_devices', 'asc');
         $s='';
         $numofdevice='';
     $s=$prevConnectDevicecount->get()->makeHidden('pdname')
     ->map(function ($address)  {
         
         $address['session_id'] = HashUserID($address['id']);
         if(is_null($address->hotel)){
             $address['hotel_room'] = null;
         }else{
             $address['hotel_room'] = $address->hotel->hotel_room;
 
     
         }
 
         $address['personaldevicename']=$address->pdname?$address->pdname->personal_devicename:$address['device']['personaldevicename']??'empty';
         $address['personaldeviceid'] = HashUserID($address->pdname?$address->pdname->id:$address['id']);
         unset($address['hotel']);
         unset($address['device']);
         unset($address['id']);
         unset($address['pdname']);
        
           return $address;
                     
                     });
                 }else{
                     $prevConnectDevicecount = SessionTable::with(['pdname'=> function($q) use($uid) {
                         // Query the name field in status table
                             $q->select('id','personal_devicename','device_id','user_id')
                             ->where('user_id', '=', $uid); 
                     }])
                     ->where([
                     ['status', '=', '1'],
                     ['users_id', '=', $uid],
                     ])
                     ->selectRaw("id ,connected_devices, SUBSTRING_INDEX(mac_address, '#~', -1) device_id,SUBSTRING_INDEX(mac_address, '#~', 1) device_name,device")
                     ->orderBy('connected_devices', 'asc');
         $s='';
         $numofdevice='';
         $s=$prevConnectDevicecount->get()->makeHidden('pdname')
         ->map(function ($address)  {
         
         $address['session_id'] = HashUserID($address['id']);
     
         $address['personaldevicename']=$address->pdname?$address->pdname->personal_devicename:$address['device']['personaldevicename']??'empty';
         $address['personaldeviceid'] = HashUserID($address->pdname?$address->pdname->id:$address['id']);
         unset($address->pdname);
         unset($address['device']);
         unset($address['id']);
           return $address;
                     
                     });
     
                 }
     $numofdevice=$prevConnectDevicecount->count();
     
     //  dd($s);
          $response = array('numofdevice'=>$numofdevice,'message'=>'Connected device found successfully','data'=>$s);
          //return response()->json($response, 200);

          $resultoffset = $user->zipcode;
          $timeZoneData = fetchHomeTimezoneUsingZip($resultoffset);
          $hrsoffset = $timeZoneData[1];
          $timezone =$timeZoneData[0];
      
      
        
        return response()->json([
            'success' => false,
            'response_code'=>479,
            'message'=>'Your account is in use on '.$login_limit .' devices. please stop playing on other devices to continue',
            'houroffset' => $hrsoffset,
            'timezone' => $timezone,
            'loggedindeviceinfolist'=>$response,
          ],479);

    }
    
}

function checkLoginLimit2($user_id,$token,$deviceDetails){
  $login_limit =  GetLimitForUser($user_id);
//find all by user_id and and status=active
$active_session = App\SessionTable::where([
['status', '=', '1'],
['users_id', '=', $user_id],
])->get()->toArray();
  //check if there's no entry by this used id
  if ((count($active_session)==0)) {
   //new entry of sessionTable
   sessionTable2($user_id,$token,$deviceDetails,$connected_devices=1);
} else if (count($active_session) > 0 && count($active_session) < $login_limit) {
    //check if device id is same
    if(duplicateDeviceId($user_id,$deviceDetails)==false){
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
  sessionTable2($user_id,$token,$deviceDetails,$connected_devices=$device+1);
  }

} //after iteration we check if the count of logins is still greater than the limit
if (count($active_session) >= $login_limit) {
  //then return a login error that maximum logins reached
  //echo 'you are not allowed to login as you have breeched the maximum session limit.';
  //exit;
  // return response()->json([
  //   'success' => false,
  //   'response_code'=>479,
  //   'message'=>'Your account is in use on '.$login_limit .' devices. please stop playing on other devices to continue',
  //   //'loggedindeviceinfointheseId'=>$myarr,
  // ],479)->send();
  

  $userEmailID = $user_id;
  // $userEmailID = User::where('email', $userEmail)->first()->id;
   $uid = $userEmailID;
   $user= User::find($uid);
   //$user = JWTAuth::user();//all the user info
  // $uid=  $user['id'];
   //and get the number of last connected device
   $userRole=  $user->roles->first()->name;
   // $user = JWTAuth::user();//all the user info
   // $uid=  $user['id'];
   // //and get the number of last connected device
   // $userRole=  $user->roles->first()->name;
   if($userRole=='tieruser'){
   $prevConnectDevicecount = SessionTable::with(['hotel:id,hotel_room,session_id','pdname'=> function($q) use($uid) {
       // Query the name field in status table
       $q->select('id','personal_devicename','device_id','user_id')->where('user_id', '=', $uid);
   }])
   ->selectRaw("id ,connected_devices, SUBSTRING_INDEX(mac_address, '#~', -1) device_id,SUBSTRING_INDEX(mac_address, '#~', 1) device_name,device")
   ->where([
   ['status', '=', '1'],
   ['users_id', '=', $uid],
    ])->orderBy('connected_devices', 'asc');
   $s='';
   $numofdevice='';
$s=$prevConnectDevicecount->get()->makeHidden('pdname')
->map(function ($address)  {
   
   $address['session_id'] = HashUserID($address['id']);
   if(is_null($address->hotel)){
       $address['hotel_room'] = null;
   }else{
       $address['hotel_room'] = $address->hotel->hotel_room;


   }

   $address['personaldevicename']=$address->pdname?$address->pdname->personal_devicename:$address['device']['personaldevicename']??'empty';
   $address['personaldeviceid'] = HashUserID($address->pdname?$address->pdname->id:$address['id']);
   unset($address['hotel']);
   unset($address['device']);
   unset($address['id']);
   unset($address['pdname']);
  
     return $address;
               
               });
           }else{
               $prevConnectDevicecount = SessionTable::with(['pdname'=> function($q) use($uid) {
                   // Query the name field in status table
                       $q->select('id','personal_devicename','device_id','user_id')
                       ->where('user_id', '=', $uid); 
               }])
               ->where([
               ['status', '=', '1'],
               ['users_id', '=', $uid],
               ])
               ->selectRaw("id ,connected_devices, SUBSTRING_INDEX(mac_address, '#~', -1) device_id,SUBSTRING_INDEX(mac_address, '#~', 1) device_name,device")
               ->orderBy('connected_devices', 'asc');
   $s='';
   $numofdevice='';
   $s=$prevConnectDevicecount->get()->makeHidden('pdname')
   ->map(function ($address)  {
   
   $address['session_id'] = HashUserID($address['id']);

   $address['personaldevicename']=$address->pdname?$address->pdname->personal_devicename:$address['device']['personaldevicename']??'empty';
   $address['personaldeviceid'] = HashUserID($address->pdname?$address->pdname->id:$address['id']);
   unset($address->pdname);
   unset($address['device']);
   unset($address['id']);
     return $address;
               
               });

           }
$numofdevice=$prevConnectDevicecount->count();

//  dd($s);
    $response = array('numofdevice'=>$numofdevice,'message'=>'Connected device found successfully','data'=>$s);
    //return response()->json($response, 200);

    // $resultoffset = $user->zipcode;

    // if($resultoffset == "86336" || $resultoffset == "85023" || $resultoffset == "85022"){
    //   $hrsoffset = "-07:00";
    //   $timezone = "Mountain Standard Time";
    // }else if($resultoffset == "97352" || $resultoffset == "92832" || $resultoffset == "92831" || $resultoffset == "97305" || $resultoffset == "97305"){
    //   $hrsoffset = "-08:00";
    //   $timezone = "Pacific Standard Time";
    // }else if($resultoffset == "75662"){
    //   $hrsoffset = "-06:00";
    //   $timezone = "Central Standard Time";
    // }else{
    //   $hrsoffset = "+05:30";
    //   $timezone = "Indian Standard Time";
    // }

  
  return response()->json([
      'success' => false,
      'response_code'=>479,
      'message'=>'Your account is in use on '.$login_limit .' devices. please stop playing on other devices to continue',
      'houroffset' => '+05:30',
      'timezone' => 'Indian Time Zone',
      'loggedindeviceinfolist'=>$response,
    ],479)->send();

}

}

function checkHoteluserLoginLimit($user_id,$token,$deviceDetails){
  $login_limit =  GetLimitForUser($user_id);
//find all by user_id and and status=active
$active_session = App\SessionTable::where([
['status', '=', '1'],
['users_id', '=', $user_id],
])->get()->toArray();
  //check if there's no entry by this used id
  if ((count($active_session)==0)) {
   //new entry of sessionTable
   hotelusersessionTable($user_id,$token,$deviceDetails,$connected_devices=1);
} else if (count($active_session) > 0 && count($active_session) < $login_limit) {
    //check if device id is same
    if(hoteluserduplicateDeviceId($user_id,$deviceDetails)==false){
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
  hotelusersessionTable($user_id,$token,$deviceDetails,$connected_devices=$device+1);
  }

} //after iteration we check if the count of logins is still greater than the limit
if (count($active_session) >= $login_limit) {

  $userEmailID = $user_id;
   $uid = $userEmailID;
   $user= User::find($uid);

   //and get the number of last connected device
   $userRole=  $user->roles->first()->name;
   
   if($userRole=='tieruser'){
   $prevConnectDevicecount = SessionTable::with(['hotel:id,hotel_room,session_id','pdname'=> function($q) use($uid) {
       // Query the name field in status table
       $q->select('id','personal_devicename','device_id','user_id')->where('user_id', '=', $uid);
   }])
   ->selectRaw("id ,connected_devices, SUBSTRING_INDEX(mac_address, '#~', -1) device_id,SUBSTRING_INDEX(mac_address, '#~', 1) device_name,device")
   ->where([
   ['status', '=', '1'],
   ['users_id', '=', $uid],
    ])->orderBy('connected_devices', 'asc');
   $s='';
   $numofdevice='';
$s=$prevConnectDevicecount->get()->makeHidden('pdname')
->map(function ($address)  {
   
   $address['session_id'] = HashUserID($address['id']);
   if(is_null($address->hotel)){
       $address['hotel_room'] = null;
   }else{
       $address['hotel_room'] = $address->hotel->hotel_room;


   }

   $address['personaldevicename']=$address->pdname?$address->pdname->personal_devicename:$address['device']['personaldevicename']??'empty';
   $address['personaldeviceid'] = HashUserID($address->pdname?$address->pdname->id:$address['id']);
   unset($address['hotel']);
   unset($address['device']);
   unset($address['id']);
   unset($address['pdname']);
  
     return $address;
               
               });
           }else{
               $prevConnectDevicecount = SessionTable::with(['pdname'=> function($q) use($uid) {
                   // Query the name field in status table
                       $q->select('id','personal_devicename','device_id','user_id')
                       ->where('user_id', '=', $uid); 
               }])
               ->where([
               ['status', '=', '1'],
               ['users_id', '=', $uid],
               ])
               ->selectRaw("id ,connected_devices, SUBSTRING_INDEX(mac_address, '#~', -1) device_id,SUBSTRING_INDEX(mac_address, '#~', 1) device_name,device")
               ->orderBy('connected_devices', 'asc');
   $s='';
   $numofdevice='';
   $s=$prevConnectDevicecount->get()->makeHidden('pdname')
   ->map(function ($address)  {
   
   $address['session_id'] = HashUserID($address['id']);

   $address['personaldevicename']=$address->pdname?$address->pdname->personal_devicename:$address['device']['personaldevicename']??'empty';
   $address['personaldeviceid'] = HashUserID($address->pdname?$address->pdname->id:$address['id']);
   unset($address->pdname);
   unset($address['device']);
   unset($address['id']);
     return $address;
               
               });

           }
$numofdevice=$prevConnectDevicecount->count();

//  dd($s);
    $response = array('numofdevice'=>$numofdevice,'message'=>'Connected device found successfully','data'=>$s);
    //return response()->json($response, 200);

  
  return response()->json([
      'success' => false,
      'response_code'=>479,
      'logindevicelimit' => $login_limit,
      'message'=>'Your hotel user account is in use on '.$login_limit .' devices. please stop playing on other devices to continue',
      'loggedindeviceinfolist'=>$response,
    ],479)->send();

}

}

function hotelusersessionTable($user_id,$token,$deviceDetails,$connected_devices){
    $userInfo = JWTAuth::setToken($token)->toUser();
    $user= User::find($user_id);
    $id= Auth::user()->id;
      
    $passcode = HashUserID($id);
  
   $Saved=sessionTableStore($user_id,$token,$deviceDetails,$connected_devices);
   $personal_device_data = personaldeviceTableStore($user_id,$deviceDetails);
 
    if($Saved['result'] && $personal_device_data['result']){
    
      return response()->json([
    
        'success' => true,
        'message'=>'logged in successfully ',
        'response_code'=>200,
        // 'sessionid'=>HashUserID($Saved['LastInsertId']),
        // 'personaldeviceid'=>HashUserID($personal_device_data['lastDeviceId']),
        'device_type'=>'non-web',
        // 'personaldevicename_popup'=>$personal_device_data['show_popup'],
        'passcode'=> $passcode,   
      ],200)->send();}
  
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
     $hotelroomcount = HotelRoomTable::whereIn('session_id', $myarr)->count();
                if ($hotelroomcount>0) {
                  HotelRoomTable::whereIn('session_id', $myarr)->update([
                        'session_id' => null
                    ]);
                  }
     
 //if logout happens minus one from all the active device of user
 //UPDATE `session_table` SET `connected_devices` = `connected_devices` - 1 WHERE `status` = 1 AND `connected_devices`<> 1
    App\SessionTable::where([
            ['status', '=', '1'],
            ['connected_devices', '!=', '1'],
             ])
    ->update([
        'connected_devices' => DB::raw('`connected_devices` - 1 ')
    ]);
    $error=invalidateToken();
    $res=$error?[
      'response_code'=>200,
      'success' => true,
      'message'=>'logged out successful',
      
    ]:['response_code'=>200,
    'success' => true,
    'message'=>'logged out successful',
  'error'=>$error];
    if(request()->wantsJson()){
      return response()->json($res,200)->send();
    }else{ return true;}
      
    
     
}

function invalidateToken(){
  $errors=[];
  try{
    config([
      'jwt.blacklist_enabled' => true
  ]);

  JWTAuth::parseToken()->invalidate();
  } catch (Exception $e) {
              if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                  $value['error']=$e->getMessage();
                  array_push($errors, $value);
                  
              }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                  $value['error']=$e->getMessage();
                  array_push($errors, $value);
                  
              }else{
                  $value['error']=$e->getMessage();
                  array_push($errors, $value);
                  
              }
          }
          if(!empty($errors)){
            return $errors;}else{
                return false;
            }
 
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
  return $ip=='127.0.0.1'?'43.231.58.253':$ip;
  
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

function show_currency_symbol( $currency ) {
	$currencies = array('aed'=>'AED','afn'=>'&#1547;','all'=>'&#76;&#101;&#107;','amd'=>'AMD','ang'=>'&#402;','aoa'=>'AOA','ars'=>'&#36;','aud'=>'&#36;','awg'=>'&#402;','azn'=>'&#1084;&#1072;&#1085;','bam'=>'&#75;&#77;','bbd'=>'&#36;','bdt'=>'BDT','bgn'=>'&#1083;&#1074;','bhd'=>'BHD','bif'=>'BIF','bmd'=>'&#36;','bnd'=>'&#36;','bob'=>'&#36;&#98;','brl'=>'&#82;&#36;','bsd'=>'&#36;','btn'=>'BTN','bwp'=>'&#80;','byr'=>'&#112;&#46;','bzd'=>'&#66;&#90;&#36;','cad'=>'&#36;','cdf'=>'CDF','chf'=>'&#67;&#72;&#70;','clp'=>'&#36;','cny'=>'&#165;','cop'=>'&#36;','crc'=>'&#8353;','cuc'=>'CUC','cup'=>'&#8369;','cve'=>'CVE','czk'=>'&#75;&#269;','djf'=>'DJF','dkk'=>'&#107;&#114;','dop'=>'&#82;&#68;&#36;','dzd'=>'DZD','egp'=>'&#163;','ern'=>'ERN','etb'=>'ETB','eur'=>'&#8364;','fjd'=>'&#36;','fkp'=>'&#163;','gbp'=>'&#163;','gel'=>'GEL','ggp'=>'&#163;','ghs'=>'&#162;','gip'=>'&#163;','gmd'=>'GMD','gnf'=>'GNF','gtq'=>'&#81;','gyd'=>'&#36;','hkd'=>'&#36;','hnl'=>'&#76;','hrk'=>'&#107;&#110;','htg'=>'HTG','huf'=>'&#70;&#116;','idr'=>'&#82;&#112;','ils'=>'&#8362;','imp'=>'&#163;','inr'=>'&#8377;','iqd'=>'IQD','irr'=>'&#65020;','isk'=>'&#107;&#114;','jep'=>'&#163;','jmd'=>'&#74;&#36;','jod'=>'JOD','jpy'=>'&#165;','kes'=>'KES','kgs'=>'&#1083;&#1074;','khr'=>'&#6107;','kmf'=>'KMF','kpw'=>'&#8361;','krw'=>'&#8361;','kwd'=>'KWD','kyd'=>'&#36;','kzt'=>'&#1083;&#1074;','lak'=>'&#8365;','lbp'=>'&#163;','lkr'=>'&#8360;','lrd'=>'&#36;','lsl'=>'LSL','lyd'=>'LYD','mad'=>'MAD','mdl'=>'MDL','mga'=>'MGA','mkd'=>'&#1076;&#1077;&#1085;','mmk'=>'MMK','mnt'=>'&#8366;','mop'=>'MOP','mro'=>'MRO','mur'=>'&#8360;','mvr'=>'MVR','mwk'=>'MWK','mxn'=>'&#36;','myr'=>'&#82;&#77;','mzn'=>'&#77;&#84;','nad'=>'&#36;','ngn'=>'&#8358;','nio'=>'&#67;&#36;','nok'=>'&#107;&#114;','npr'=>'&#8360;','nzd'=>'&#36;','omr'=>'&#65020;','pab'=>'&#66;&#47;&#46;','pen'=>'&#83;&#47;&#46;','pgk'=>'PGK','php'=>'&#8369;','pkr'=>'&#8360;','pln'=>'&#122;&#322;','prb'=>'PRB','pyg'=>'&#71;&#115;','qar'=>'&#65020;','ron'=>'&#108;&#101;&#105;','rsd'=>'&#1044;&#1080;&#1085;&#46;','rub'=>'&#1088;&#1091;&#1073;','rwf'=>'RWF','sar'=>'&#65020;','sbd'=>'&#36;','scr'=>'&#8360;','sdg'=>'SDG','sek'=>'&#107;&#114;','sgd'=>'&#36;','shp'=>'&#163;','sll'=>'SLL','sos'=>'&#83;','srd'=>'&#36;','ssp'=>'SSP','std'=>'STD','syp'=>'&#163;','szl'=>'SZL','thb'=>'&#3647;','tjs'=>'TJS','tmt'=>'TMT','tnd'=>'TND','top'=>'TOP','try'=>'&#8378;','ttd'=>'&#84;&#84;&#36;','twd'=>'&#78;&#84;&#36;','tzs'=>'TZS','uah'=>'&#8372;','ugx'=>'UGX','usd'=>'&#36;','uyu'=>'&#36;&#85;','uzs'=>'&#1083;&#1074;','vef'=>'&#66;&#115;','vnd'=>'&#8363;','vuv'=>'VUV','wst'=>'WST','xaf'=>'XAF','xcd'=>'&#36;','xof'=>'XOF','xpf'=>'XPF','yer'=>'&#65020;','zar'=>'&#82;','zmw'=>'ZMW');
	if( array_key_exists( $currency, $currencies) ){
		$symbol = $currencies[$currency];
	} else {
		$symbol = $currency;
	}
	return $symbol;
}

/**
 * Computes the distance between two coordinates.
 *
 * Implementation based on reverse engineering of
 * <code>google.maps.geometry.spherical.computeDistanceBetween()</code>.
 *
 * @param float $lat1 Latitude from the first point.
 * @param float $lng1 Longitude from the first point.
 * @param float $lat2 Latitude from the second point.
 * @param float $lng2 Longitude from the second point.
 * @param float $radius (optional) Radius in meters.
 *
 * @return float Distance in meters.
 */
function computeDistance($lat1, $lng1, $lat2, $lng2, $radius = 6378137)
{
    static $x = M_PI / 180;
    $lat1 *= $x; $lng1 *= $x;
    $lat2 *= $x; $lng2 *= $x;
    $distance = 2 * asin(sqrt(pow(sin(($lat1 - $lat2) / 2), 2) + cos($lat1) * cos($lat2) * pow(sin(($lng1 - $lng2) / 2), 2)));

    return $distance * $radius;
}

function getKeyOSAuthToken(){
  $certificate = config_path("buydrm/9754e0cfddc682c21216de3eb4d11885.pem");
  try {  // Create authentication XML generator instance.
    $auth_xml_generator = new \KeyOSAuthXMLGenerator($certificate);
    $auth_xml_generator->AuthXMLExpirationTime('+1 day');
  
    // Crate Simple PR license
    $pr_lic = $auth_xml_generator->newSimpleLicense();
  
    // Create WV license
    $wv_lic = $auth_xml_generator->newWidevineLicense();
  
    // Create FP license
    $fp_lic = $auth_xml_generator->newFairPlayLicense();
 
     
    // Generate Authentication XML.
    $encoded_auth_xml = $auth_xml_generator->encodeAuthenticationXML($auth_xml_generator->signAuthenticationXML($auth_xml_generator->generateAuthenticationXML()));
     return $encoded_auth_xml;
  }
  catch(Exception $e) {
            
    return $e->getMessage();
  }
}

function isDeviceAppleRokuOthers($ua){

$Firetv = (stripos($ua,"AFT") !== false);
$samsung_smart_tv = (stripos($ua,"SMART-TV") !== false);
$smart_tv = (stripos($ua,"SmartTV") !== false);
$Tizen = (stripos($ua,"Tizen") !== false);
  $iPod    = (stripos($ua,"iPod") !== false);
$iPhone  = (stripos($ua,"iPhone") !== false);
$iPad    = (stripos($ua,"iPad") !== false);
$iPadOS = (stripos($ua,"Macintosh") !== false);
$appletv = (stripos($ua,"appletv") !== false);
$Android = (stripos($ua,"Android") !== false);
$Chrome = (stripos($ua,"Chrome") !== false);
$Safari = (stripos($ua,"Safari") !== false);
$Roku = (stripos($ua,"Roku") !== false);
$Darwin = (stripos($ua,"Darwin") !== false);
$Apple_TV = (stripos($ua,"Apple TV") !== false);

 if($Roku){
  return 'roku';
}else if($Firetv){
  return 'firetv';
 }else if($samsung_smart_tv||$Tizen||$smart_tv){
  return 'smarttv';
 }else if( $Darwin||$Apple_TV||$appletv&&!$Android)  {
  return 'appletv';
 }else if($Chrome){
  return 'chrome';
} else if( $iPod || $iPhone||$iPad||$iPadOS||$Safari&&!$Android)  {
  return 'apple';
 }else{
   return 'others';
 }
}

function sessionTable($user_id,$token,$deviceDetails,$connected_devices){
  $userInfo = JWTAuth::setToken($token)->toUser();
  $user= User::find($user_id);
  $resultoffset = $user->zipcode;
  $timeZoneData = fetchHomeTimezoneUsingZip($resultoffset);
  $hrsoffset = $timeZoneData[1];
  $timezone =$timeZoneData[0];
 
  if (App\SessionTable::where('users_id', '=',$user_id)->count()== 0) {
    $termAndCondition=true;
 } else {$termAndCondition=false;}
  

 $Saved=sessionTableStore($user_id,$token,$deviceDetails,$connected_devices);
 $personal_device_data = personaldeviceTableStore($user_id,$deviceDetails);
 $fcm_token = request('fcm_token');
 if($fcm_token){
        $userfcmtoken = DB::table('session_table')
              ->where('access_token', $token)
              ->update(array(
              'fcm_token' => $fcm_token,
              )); 
    }
//     if($userfcmtoken == 1){
//     $tokenResponse = $fcm_token;
//  }else{
//     $tokenResponse = null;
//  }
//  $Saved['result'];
//  $Saved['LastInsertId'];
  if($Saved['result'] && $personal_device_data['result']){
    $active_sessioncount = App\SessionTable::where([
      ['status', '=', '1'],
      ['users_id', '=', $user_id],
       ])->get()->count();
       $rooms=getActiveHotelRooms($user_id);
       $subscriptionStatus =  GetSubscriptionStatusOfUser($user_id);
       $kindlysubscribemsg =  $subscriptionStatus?null:KindlySubscribeMsg();
    return response()->json([
  
      'success' => true,
      'message'=>'logged in successfully ',
      'passcode' => HashUserID($userInfo->id),
      'response_code'=>200,
      'fcm_token' => $fcm_token,
      'sessionid'=>HashUserID($Saved['LastInsertId']),
      'personaldeviceid'=>HashUserID($personal_device_data['lastDeviceId']),
      'device_type'=>'non-web',
      'personaldevicename_popup'=>$personal_device_data['show_popup'],
      'houroffset' => $hrsoffset,
      'timezone' => $timezone,
      // 'unhashedsessionid'=>$Saved['LastInsertId'],
      'connected_device'=> $active_sessioncount.' device connected  ',
      'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => JWTAuth::factory()->getTTL() * 60,
        'device_info'=>$deviceDetails,
        'user'=>$userInfo,
        'termandcondition'=>$termAndCondition,
        'rooms'=>$rooms,
        'userrole'=>$userInfo->roles->pluck('name')[0],
        'subscribed'=>$subscriptionStatus,
        'subscribedmsgis'=>$kindlysubscribemsg
      
      
    ],200);}

}

function sessionTable2($user_id,$token,$deviceDetails,$connected_devices){
  $userInfo = JWTAuth::setToken($token)->toUser();
  $user= User::find($user_id);
  // $resultoffset = $user->zipcode;
      
  //         if($resultoffset == "86336" || $resultoffset == "85023" || $resultoffset == "85022"){
  //           $hrsoffset = "-07:00";
  //           $timezone = "Mountain Standard Time";
  //         }else if($resultoffset == "97352" || $resultoffset == "92832" || $resultoffset == "92831" || $resultoffset == "97305" || $resultoffset == "97305"){
  //           $hrsoffset = "-08:00";
  //           $timezone = "Pacific Standard Time";
  //         }else if($resultoffset == "75662"){
  //           $hrsoffset = "-06:00";
  //           $timezone = "Central Standard Time";
  //         }else{
  //           $hrsoffset = "+05:30";
  //           $timezone = "Indian Standard Time";
  //         }
          
  if (App\SessionTable::where('users_id', '=',$user_id)->count()== 0) {
    $termAndCondition=true;
 } else {$termAndCondition=false;}
  

 $Saved=sessionTableStore($user_id,$token,$deviceDetails,$connected_devices);
 $personal_device_data = personaldeviceTableStore($user_id,$deviceDetails);
$fcm_token = request('fcm_token');
if($fcm_token){
        $userfcmtoken = DB::table('session_table')
              ->where('access_token', $token)
              ->update(array(
              'fcm_token' => $fcm_token,
              ));
    }
//     if($userfcmtoken == 1){
//     $tokenResponse = $fcm_token;
//  }else{
//     $tokenResponse = null;
//  }
//  $Saved['result'];
//  $Saved['LastInsertId'];
  if($Saved['result'] && $personal_device_data['result']){
    $active_sessioncount = App\SessionTable::where([
      ['status', '=', '1'],
      ['users_id', '=', $user_id],
       ])->get()->count();
       $rooms=getActiveHotelRooms($user_id);
       $subscriptionStatus =  GetSubscriptionStatusOfUser($user_id);
       $kindlysubscribemsg =  $subscriptionStatus?null:KindlySubscribeMsg();
    return response()->json([
  
      'success' => true,
      'message'=>'logged in successfully ',
      'response_code'=>200,
      'fcm_token' => $fcm_token,
      'sessionid'=>HashUserID($Saved['LastInsertId']),
      'personaldeviceid'=>HashUserID($personal_device_data['lastDeviceId']),
      'device_type'=>'non-web',
      'personaldevicename_popup'=>$personal_device_data['show_popup'],
      'houroffset' => '+05:30',
      'timezone' => "Indin Time Zone",
      // 'unhashedsessionid'=>$Saved['LastInsertId'],
      'connected_device'=> $active_sessioncount.' device connected  ',
      'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => JWTAuth::factory()->getTTL() * 60,
        'device_info'=>$deviceDetails,
        'user'=>$userInfo,
        'termandcondition'=>$termAndCondition,
        'rooms'=>$rooms,
        'userrole'=>$userInfo->roles->pluck('name')[0],
        'subscribed'=>$subscriptionStatus,
        'subscribedmsgis'=>$kindlysubscribemsg
      
      
    ],200)->send();}

}

function duplicateDeviceId($user_id,$deviceDetails){
  $checkDeviceDetails=App\SessionTable::where([
    ['status', '=', '1'],
    ['users_id', '=', $user_id],
    ['mac_address', '=', $deviceDetails],
  ]);

  if ($checkDeviceDetails->exists()) {
      $explodedstring= explode("#~",$deviceDetails);
       return response()->json([
        'success' => false,
        'response_code'=>429,
        // 'message'=>'Same device('.$explodedstring['0'].') ID = '.$explodedstring['1'].' detected,Kindly remove your session first or remove the device from web or Use the data key given below to logout session!',
        'message'=>'Your device is already in an active session with us. Do you want to logout from that session & start a new session?',
        'data'=>HashUserID($checkDeviceDetails->get('id')[0]->id),
        'access_token'=>$checkDeviceDetails->get('access_token')[0]->access_token
        //'loggedindeviceinfointheseId'=>$myarr,
      ],429)->send();
    // exists
} else{
  return false;
}
}

function fetchHomeTimezoneUsingZip($zipCode) {

  $zipCodeValue = Zipcode::Where("zipcode","=",$zipCode)->first();

  try {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://api.geonames.org/timezoneJSON?lat='.$zipCodeValue->latitude.'&lng='.$zipCodeValue->longitude.'&username=rahulgupta',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $dataArr =  json_decode($response, true);
    $dateTimezone = new DateTimeZone($dataArr['timezoneId']);
    $dateTime = new DateTime('now', $dateTimezone);

    $offset = $dateTimezone->getOffset($dateTime); // Offset in seconds

    // Convert the offset to hours and minutes
    $hours = floor(abs($offset) / 3600);
    $minutes = floor((abs($offset) % 3600) / 60);

    // Determine the sign of the offset (plus or minus)
    $sign = ($offset < 0) ? '-' : '+';

    // Output the offset in the format +/-HH:MM
    $offSet = $sign . sprintf('%02d:%02d', $hours, $minutes);

    // Get the abbreviation using the 'T' format specifier
    $formattedDate = $dateTime->format('T');
    $timezone = convertTimeZoneAbbreviation($formattedDate,$zipCode);
    
  } catch(Exception $e) {
    if($zipCode == "86336" || $zipCode == "85023" || $zipCode == "85022"){
      $offSet = "-07:00";
      $timezone = "Mountain Standard Time";
    }else if($zipCode == "97352" || $zipCode == "92832" || $zipCode == "92831" || $zipCode == "97305" || $zipCode == "97305"){
      $offSet = "-08:00";
      $timezone = "Pacific Standard Time";
    }else if($zipCode == "75662"){
      $offSet = "-06:00";
      $timezone = "Central Standard Time";
    }else{
      $offSet = "+05:30";
      $timezone = "Eastern Standard Time";
    }
  }

  return [$timezone,$offSet];

}

function hoteluserduplicateDeviceId($user_id,$deviceDetails){
  $checkDeviceDetails=App\SessionTable::where([
    ['status', '=', '1'],
    ['users_id', '=', $user_id],
    ['mac_address', '=', $deviceDetails],
  ]);
  $id= Auth::user()->id;
      
  $passcode = HashUserID($id);
  if ($checkDeviceDetails->exists()) {
      $explodedstring= explode("#~",$deviceDetails);
       return response()->json([
        'success' => true,
        "message"=> "logged in successfully ",
        'response_code'=>200,
        "device_type" => "non-web",
        'passcode' => $passcode,
      ],200)->send();
    // exists
} else{
  return false;
}
}


  /**
 * Validates a given coordinate
 *
 * @param float|int|string $lat Latitude
 * @param float|int|string $long Longitude
 * @return bool `true` if the coordinate is valid, `false` if not
 */
function is_coordinate($lat, $long) {

	$lat_pattern  = '/\A[+-]?(?:90(?:\.0{1,18})?|\d(?(?<=9)|\d?)\.\d{1,18})\z/x';
	$long_pattern = '/\A[+-]?(?:180(?:\.0{1,18})?|(?:1[0-7]\d|\d{1,2})\.\d{1,18})\z/x';

	if (preg_match($lat_pattern, $lat) && preg_match($long_pattern, $long)) {
			return true;
	} else {
			return false;
	}
}


function sessionTableForWeb($user_id,$token,$deviceDetails,$connected_devices){
  $userInfo = JWTAuth::user();
  if (SessionTable::where('users_id', '=',$user_id)->count()== 0) {
    $termAndCondition=true;
 } else {$termAndCondition=false;}
 

 $Saved=sessionTableStore($user_id,$token,$deviceDetails,$connected_devices);
//  $Saved['result'];
//  $Saved['LastInsertId'];
  if($Saved['result']){
      session(
          [
              'jwttoken' => $token
          ]
      );
    return true;}

}

function sessionTableStore($user_id,$token,$deviceDetails,$connected_devices){
  $ua =  request()->header('User-Agent');
  $isDevice=isDeviceAppleRokuOthers($ua);
      $isTV=isTV($isDevice);
      if($isTV !== false){$deviceInfoClasses=$isTV;}else{
        $deviceInfoClasses=getBodyClass();
      }
// dd($deviceInfoClasses);
  $date = date('Y-m-d H:i:s');
$session_tab = new SessionTable();
$session_tab->users_id = $user_id;
$session_tab->access_token = $token;
$session_tab->user_ip = getUserIpAddr();
$session_tab->mac_address = $deviceDetails;
$session_tab->device_id = explode("#~",$deviceDetails)[1];
$session_tab->connected_devices = $connected_devices;
$session_tab->device = $deviceInfoClasses;
$session_tab->logintime = Carbon::now();
$session_tab->status = 1;
$session_tab->created_on = $date;
$session_tab->modified_on = Carbon::now();  
$result=$session_tab->save();

$LastInsertId = $session_tab->id;
return ['result'=>$result,'LastInsertId'=>$LastInsertId];
}

function getDeviceIdforWeb(){

  $agent = new Agent();
        
  //check if agent is robot
  if ($agent->isRobot()) {
      $ua= $agent->robot();
  } else{ //if agent is not robot then get agent browser and platform like Chrome in Linux
      $ua = $agent->browser() . " in " . $agent->platform();
      //if agent browser and platform not obtained, then we check the agent technology
      if ($agent == ' in ') {
          $ua =  request()->header('User-Agent');
      }}
  
  // $ua = $request->server('HTTP_USER_AGENT');
  $deviceid= "web".session()->get('_token');

  return $ua.'#~'.$deviceid;
}

function getDeviceIdforAPI($request){
  $agent = new Agent();
        
  //check if agent is robot
  if ($agent->isRobot()) {
      $ua= $agent->robot();
  } else{ //if agent is not robot then get agent browser and platform like Chrome in Linux
      $ua = $agent->browser() . " in " . $agent->platform();
      //if agent browser and platform not obtained, then we check the agent technology
      if ($agent == ' in ') {
          $ua =  request()->header('User-Agent');
      }}
  
  // $ua = $request->server('HTTP_USER_AGENT');
  $deviceid= $request->input('deviceid') ?? 'Not Applicable';
 

  return $ua.'#~'.$deviceid;
}


function sortplayererrorcode($code,$isDevice){
 
  if($isDevice=='appletv') {
    return $color= AppleTVErrorCode($code);
  }
  if($isDevice=='roku'){
    return $color= RokuTVErrorCode($code);
    
  }
  if($isDevice=='videojs'){
    return $color=VideoJSErrorCode($code);
  }
  
  }



function reverseAddressLookup($LAT,$LON){
  try{
    $client = new \GuzzleHttp\Client();
    $response = $client->get('http://nominatim.openstreetmap.org/reverse?email=contact@streamlytv.com&format=json&lat='.$LAT.'&lon='.$LON.'&zoom=27&addressdetails=1');

    $jsonfile=$response->getBody();
    if (!json_decode($jsonfile, TRUE)) {return false;}
    else{
     $resp = json_decode($jsonfile, true);

if( isset($resp['address']['postcode'] ) ){
$zipcode= $resp['address']['postcode'];
$address= $resp['display_name'];
return $resp;
}
 }

}catch(\GuzzleHttp\Exception\RequestException $e){

}
}

 function RokuTVErrorCode($code)
{  
  $rokuerrorcode=[0,-1,-2,-3,-4,-5,-6];
  $color = '';
  if(in_array($code, $rokuerrorcode)){
    switch($code){
      case 0:
      case -2:
        $color='blue';
        break;
    case -1:
      $color='green';
      break;
      case -6:
      $color='yellow';
      break;
      case -5:
      $color='red';
      break;
      case -4:
      $color='pink';
      break; 
      case -3:
      $color='white';
      break;
    } 
    return $color;
  }else{ return 'black';}
} 

function AppleTVErrorCode($code)
{  
  $appleTVerrorcode=[11863,11867,11853,11866];
  $color = '';
  if(in_array($code, $appleTVerrorcode)){
  
    switch($code){
      case 11863:
        $color='blue';
        break;
    case 11867:
      $color='green';
      break;
      case 11853:
      $color='yellow';
      break;
      case 11866:
      $color='red';
      break;
    }
    return $color;
  } else{

    return $color='black';

  }
}
function VideoJSErrorCode($code)
{  $weberrorcode=[0,1,2,3,4,5];
  $color = '';
  if(in_array($code, $appleTVerrorcode)){
    switch($code){
      
      case 2:
        $color='blue';
        break;
    case 3:
      $color='green';
      break;
      case 5:
      $color='yellow';
      break;
      case 4:
      $color='red';
      break;
      case 1:
      $color='pink';
      break; 
      case 0:
      $color='white';
      break;
    }

    return $color;
  }else{ return $color='black';}
  
}
function getBodyClass()
{
    $agent = new Agent();
    $browser_css_classes = array();
    
    $browser_css_classes=isMobileiPadisTablet($agent,$browser_css_classes);
    $browser_css_classes=getBrowser($agent,$browser_css_classes);
    $browser_css_classes=isPlatform($agent,$browser_css_classes);
    
    $browser_css_classes['useragent']= request()->header('User-Agent');
    return $browser_css_classes;
}

function isPlatform($agent,$browser_css_classes){
  $windows=$agent->is('Windows');
    if ($windows) {
        $browser_css_classes['platform'] = 'os-windows';
    }
    $OSX=$agent->is('OS X');
    if ($OSX) {
        $browser_css_classes['platform'] = 'os-mac';
    }
    if ($agent->is('AndroidOS')) {
        $browser_css_classes['platform'] = 'os-andriod';
    }
   $oslinux= $agent->is('Ubuntu')||$agent->is('Linux')&&!$agent->is('AndroidOS');
    if ($oslinux) {
        $browser_css_classes['platform'] = 'os-linux';
    }
    if ($agent->isDesktop()) {
        $browser_css_classes['device'] = 'desktop';

        $browser_css_classes['classes'] = "data-form-factor='1'";
        if($oslinux){
          $browser_css_classes['classes'] = "data-form-factor='1' data-os='3'";
        }
        if($OSX){
          $browser_css_classes['classes'] = "data-form-factor='1' data-os='2'";
        }
        if($windows){
          $browser_css_classes['classes'] = "data-form-factor='1' data-os='1'";
        }
    }
    return $browser_css_classes;
}
function isMobileiPadisTablet($agent,$browser_css_classes){
  if ($mobile = $agent->isMobile()) {
    $browser_css_classes['device'] = 'device-mobile';
    $browser_css_classes['classes'] = "data-form-factor='2' data-os='4'";
}
if ($tablet = $agent->isTablet()) {
    $browser_css_classes['device'] = 'device-tablet';
    $browser_css_classes['classes'] = "data-form-factor='3' data-os='1'";
}

if ($ipad = $agent->is('iPad')) {
  $browser_css_classes['device'] = 'device-ipad';
  $browser_css_classes['classes'] = "data-form-factor='3' data-os='5'";
}
if ($iphone = $agent->is('iPhone')) {
    $browser_css_classes['device'] = 'device-iphone';
    $browser_css_classes['classes'] = "data-form-factor='2' data-os='5'";
}
return $browser_css_classes;
}
function checkSafariBrowser($agent,$browser_css_classes){
  $unsupported = [];
  if ($agent->browser() == "Safari") {
    
    $browser_css_classes['browser'] = 'browser-safari';
    $browser_css_classes['browserclass'] = "data-browser='3'";
    $browser_css_classes['browserversion'] = 'browser-safari-' . (int) $agent->version('Safari');
    if ($agent->version('Safari') < 5) {
        $unsupported['browser'] = 'Safari';
    }
    return ['browsercssclass'=>$browser_css_classes,'unsupported'=>$unsupported];
} else{
  return false;
}
}
function checkChromeBrowser($agent,$browser_css_classes){
  $unsupported = [];
  if ($agent->browser() == "Chrome") {
    
    $other_browser = false;
      $browser_css_classes['browser'] = 'browser-chrome';
      $browser_css_classes['browserversion'] = 'browser-chrome-' . (int) $agent->version('Chrome');

      $browser_css_classes['browserclass'] = "data-browser='1'";
      if ($agent->version('Chrome') < 30) {
          $unsupported['browser'] = 'Chrome';
      }
    return ['browsercssclass'=>$browser_css_classes,'unsupported'=>$unsupported];
} else{
  return false;
}
}
function checkFirefoxBrowser($agent,$browser_css_classes){
  $unsupported = [];
  if ($agent->browser() == "Firefox") {
    $other_browser = false;
    $browser_css_classes['browser'] = 'browser-firefox';
    $browser_css_classes['browserversion'] = 'browser-firefox-' . (int) $agent->version('Firefox');

    $browser_css_classes['browserclass'] = "data-browser='2'";
    if ($agent->version('Firefox') < 25) {
        $unsupported['browser'] = 'Firefox';
    }
    return ['browsercssclass'=>$browser_css_classes,'unsupported'=>$unsupported]; 
}else{
  return false;
}
}
function checkEdgeBrowser($agent,$browser_css_classes){
  $unsupported = [];
  if ($agent->browser() == "Firefox") {
    $other_browser = false;
    $browser_css_classes['browser'] = 'browser-firefox';
    $browser_css_classes['browserversion'] = 'browser-firefox-' . (int) $agent->version('Firefox');

    $browser_css_classes['browserclass'] = "data-browser='2'";
    if ($agent->version('Firefox') < 25) {
        $unsupported['browser'] = 'Firefox';
    }
    return ['browsercssclass'=>$browser_css_classes,'unsupported'=>$unsupported]; 
}else{
  return false;
}
}
function checkOperaBrowser($agent,$browser_css_classes){
  $unsupported = [];
  if ($agent->browser() == "Opera"||$agent->browser() == "Opera Mini") {
    $other_browser = false;
    $browser_css_classes['browser'] = 'browser-opera';
    $browser_css_classes['browserversion'] = 'browser-opera-' . (int) $agent->version('Opera')||$agent->version('Opera Mini');

    $browser_css_classes['browserclass'] = "data-browser='5'";
    if ($agent->version('Opera') < 25) {
        $unsupported['browser'] = 'Opera';
    }

    return ['browsercssclass'=>$browser_css_classes,'unsupported'=>$unsupported]; 
}else{
  return false;
}
}
function checkIEBrowser($agent,$browser_css_classes){
  $unsupported = [];
  if ($browser = $agent->browser() == "IE") {
    $version = $agent->version("IE");
    $version = str_replace('.0', '', $version);
    $other_browser = false;
    $browser_css_classes['browser'] = 'browser-ie';
    $browser_css_classes['browser'] = 'browser-ie-' . (int) $version;

    $browser_css_classes['browserclass'] = "data-browser='4'";
    if ((int) $version < 10) {
        $browser_css_classes['browserversion'] = 'browser-lt-ie10';
    }
    if ((int) $version < 9) {
        $unsupported['browser'] = 'Internet Explorer';
    }
    $browser_css_classes['browserversion'] = 'browser-ie' . $version;

    return ['browsercssclass'=>$browser_css_classes,'unsupported'=>$unsupported]; 
}else{
  return false;
}
}
function getBrowser($agent,$browser_css_classes){
  $unsupported = [];
  $other_browser = $agent->browser();
  $checkSafariBrowser=checkSafariBrowser($agent,$browser_css_classes);
  if($checkSafariBrowser!==false){
    $other_browser = false;
    $browser_css_classes=$checkSafariBrowser['browsercssclass'];
    $unsupported=$checkSafariBrowser['unsupported'];
  }
  $checkChromeBrowser=checkChromeBrowser($agent,$browser_css_classes);
  if($checkChromeBrowser!==false){
    $other_browser = false;
    $browser_css_classes=$checkChromeBrowser['browsercssclass'];
    $unsupported=$checkChromeBrowser['unsupported'];
  }
  $checkFirefoxBrowser=checkFirefoxBrowser($agent,$browser_css_classes);
  if($checkFirefoxBrowser!==false){
    $other_browser = false;
    $browser_css_classes=$checkFirefoxBrowser['browsercssclass'];
    $unsupported=$checkFirefoxBrowser['unsupported'];
  }

  $checkEdgeBrowser=checkEdgeBrowser($agent,$browser_css_classes);
  if($checkEdgeBrowser!==false){
    $other_browser = false;
    $browser_css_classes=$checkEdgeBrowser['browsercssclass'];
    $unsupported=$checkEdgeBrowser['unsupported'];
  }
  $checkOperaBrowser=checkOperaBrowser($agent,$browser_css_classes);
  if($checkOperaBrowser!==false){
    $other_browser = false;
    $browser_css_classes=$checkOperaBrowser['browsercssclass'];
    $unsupported=$checkOperaBrowser['unsupported'];
  }
  
  $checkIEBrowser=checkIEBrowser($agent,$browser_css_classes);
  if($checkIEBrowser!==false){
    $other_browser = false;
    $browser_css_classes=$checkIEBrowser['browsercssclass'];
    $unsupported=$checkIEBrowser['unsupported'];
  }
  
  
  
  
  if ($other_browser) {
      $browser_css_classes['browser'] = 'browser-' . strtolower($other_browser);

      $browser_css_classes['browserclass'] = "data-browser='7'";
      $version = (int) $agent->version($other_browser);
      $browser_css_classes['browserversion'] = 'browser-' . strtolower($other_browser) . '-' . $version;
  }

  return $browser_css_classes;
}
function isTV($device)
{  
  $tv=['roku','appletv','firetv','smarttv'];
  $color = '';
  if(in_array($device, $tv)){
  
    $browser_css_classes = array();
    switch($device){
      case 'roku':

        $browser_css_classes['device'] = 'device-rokutv';
        $browser_css_classes['classes'] = "data-form-factor='10'";
        
        break;
    case 'appletv':
      $browser_css_classes['device'] = 'device-appletv';
      $browser_css_classes['classes'] = "data-form-factor='11'";
      break;
      case 'firetv':
        $browser_css_classes['device'] = 'device-firetv';
        $browser_css_classes['classes'] = "data-form-factor='12'";
      break;
      case 'smarttv':
        $browser_css_classes['device'] = 'device-smarttv';
        $browser_css_classes['classes'] = "data-form-factor='13'";
      break;
    }

    $browser_css_classes['useragent']= request()->header('User-Agent');
    return $browser_css_classes;
  } else{

    return $browser_css_classes=false;

  }
}

function KindlySubscribeMsg(){
  return "We're having some trouble with your current subscription.We'll try again, but in meantime you may want to update your subscription. For more help, please visit https://streamlytv.com or contact at https://streamlytv.com/helpandsupport";
}
function GetSubscriptionStatusOfUser($user_id){
  $user = User::find($user_id);
  $userRole= $user->roles->pluck('name');
 $s= !($user && ! $user->subscribed('main')&& !in_array($userRole[0], array("admin","superadmin")));
 $d= !($user->subscription('main') && $user->subscription('main')->cancelled());
  return $d&&$s;
}
function GetLimitForUser($user_id){
  $limit =5;
  $user = User::find($user_id);
  if($user->subscribed('main')){
    $userIdExistInSubScribed = Subscription::select('quantity')->where(function ($query) use ($user_id) {
      $query->where('user_id', '=', $user_id);
      $query->where('stripe_status', '=', 'active');
    })->value('quantity');
  }
  if(!empty($userIdExistInSubScribed)){
     
     if($userIdExistInSubScribed>5){
         $limit = $userIdExistInSubScribed;
     }

 }
 return $limit;
}

 function CheckPreviousHotelRooms($quantity,$userid){
  $HotelRoomTableCount=HotelRoomTable::where("user_id", "=", $userid)->get()->toArray();
  if(count($HotelRoomTableCount)==0){
    MakeHotelRooms($quantity,$userid);
  } else{
    $userIdExistInSubScribedWithquantity = Subscription::select('quantity')
    ->where('user_id', '=', $userid)->orderBy("id", "DESC")->skip(1)->take(1)->value('quantity');
                                   //  dd($userIdExistInSubScribedWithquantity);
                                   
    if(!empty($userIdExistInSubScribedWithquantity)){
     $newquantity=$quantity;
     $previousquantity =$userIdExistInSubScribedWithquantity;
     if($previousquantity<$newquantity){
       $newquantityofroom= $newquantity-$previousquantity;
       //add rooms
       MakeHotelRooms($newquantityofroom,$userid);
     } else if($previousquantity>$newquantity){
       $newquantityofroom= $previousquantity-$newquantity;
       //remove rooms
       RemoveHotelRooms($newquantityofroom,$userid);
     }
    }else{
      MakeHotelRooms($quantity,$userid);
    }
  }
   
 
 }
 function MakeHotelRooms($quantity,$userid)
{ 
    for ($i=0; $i <$quantity; $i++) { 
      HotelRoomTable::create([
          'hotel_room' => 'Room'.$i,
          'user_id' => $userid
      ]);
  }
} 

function RemoveHotelRooms($quantity,$userid)
{  HotelRoomTable::where("user_id", "=", $userid)
  ->orderBy("id", "DESC")
  ->take($quantity)
  ->delete();
  
}
function HashUserID($id)
{  
  $hashids = new Hashids('streamly',10);
  
  $hasid = $hashids->encode($id);
  return $hasid;
}
function UnHashUserID($hasid)
{ 
  $hashids = new Hashids('streamly',10);
  if (!$hashids->decode($hasid)) {
    return false;
} else {
  return $hashids->decode($hasid)['0'];
}
 
  
}
function getActiveHotelRooms($userid){
 return HotelRoomTable::where([
    ['user_id', '=', $userid],
     ])
     ->whereNull('session_id')
  ->orderBy("id", "DESC")
  ->get();
}

function personaldeviceTableStore($user_id,$deviceDetails){
  $deviceid = explode("#~",$deviceDetails)[1];
  $device_count = PersonalDevice::where([
    ['user_id', '=', $user_id],
    ['device_id', '=', $deviceid],
  ])->get();
  if(count($device_count) == 0){
    $personalDevice = new PersonalDevice();
    $personalDevice->user_id = $user_id;
    $personalDevice->device_id = $deviceid;
    $result = $personalDevice->save();
    $LastInsertId = $personalDevice->id;
    $show_popup = true;
  }elseif(count($device_count) != 0 && $device_count[0]->personal_devicename == NULL){
    $result = $device_count;
    $LastInsertId = $device_count[0]->id;
    $show_popup = true;
  }else{
    $result = $device_count;
    $LastInsertId = $device_count[0]->id;
    $show_popup = false;
  }
  return([ "result"=>$result, "lastDeviceId"=>$LastInsertId, "show_popup"=>$show_popup ]);
}

function convertTimeZoneAbbreviation($abbreviation,$zipCode) {
  $timezoneAbbreviations = array(
      // 'ACDT' => 'Australian Central Daylight Time',
      // 'ACST' => 'Australian Central Standard Time',
      // 'ACT' => 'Acre Time',
      'ADT' => 'Atlantic Daylight Time',
      'AEDT' => 'Australian Eastern Daylight Time',
      'AEST' => 'Australian Eastern Standard Time',
      'AFT' => 'Afghanistan Time',
      'AKDT' => 'Alaska Daylight Time',
      'AKST' => 'Alaska Standard Time',
      'AMST' => 'Amazon Summer Time',
      'AMT' => 'Amazon Time',
      'ART' => 'Argentina Time',
      'AST' => 'Atlantic Standard Time',
      'AWST' => 'Australian Western Standard Time',
      'AZOST' => 'Azores Summer Time',
      'AZOT' => 'Azores Time',
      'AZT' => 'Azerbaijan Time',
      'BDT' => 'Brunei Time',
      'BIOT' => 'British Indian Ocean Time',
      'BIT' => 'Baker Island Time',
      'BOT' => 'Bolivia Time',
      'BRST' => 'Brasília Summer Time',
      'BRT' => 'Brasília Time',
      'BST' => 'Bangladesh Standard Time',
      'BTT' => 'Bhutan Time',
      'CAT' => 'Central Africa Time',
      'CCT' => 'Cocos Islands Time',
      'CDT' => 'Central Daylight Time',
      'CEST' => 'Central European Summer Time',
      'CET' => 'Central European Time',
      'CHADT' => 'Chatham Daylight Time',
      'CHAST' => 'Chatham Standard Time',
      'CHOT' => 'Choibalsan Time',
      'CHOST' => 'Choibalsan Summer Time',
      'CHST' => 'Chamorro Standard Time',
      'CHUT' => 'Chuuk Time',
      'CIST' => 'Clipperton Island Standard Time',
      'CIT' => 'Central Indonesia Time',
      'CKT' => 'Cook Island Time',
      'CLST' => 'Chile Summer Time',
      'CLT' => 'Chile Standard Time',
      'COST' => 'Colombia Summer Time',
      'COT' => 'Colombia Time',
      'CST' => 'Central Standard Time',
      'CT' => 'China Time',
      'CVT' => 'Cape Verde Time',
      'CXT' => 'Christmas Island Time',
      'DAVT' => 'Davis Time',
      'DDUT' => 'Dumont d\'Urville Time',
      'DFT' => 'AIX-specific equivalent of Central European Time',
      'EASST' => 'Easter Island Summer Time',
      'EAST' => 'Easter Island Standard Time',
      'EAT' => 'East Africa Time',
      'ECT' => 'Ecuador Time',
      'EDT' => 'Eastern Daylight Time',
      'EEST' => 'Eastern European Summer Time',
      'EET' => 'Eastern European Time',
      'EGST' => 'Eastern Greenland Summer Time',
      'EGT' => 'Eastern Greenland Time',
      'EIT' => 'Eastern Indonesian Time',
      'EST' => 'Eastern Standard Time',
      'FET' => 'Further Eastern European Time',
      'FJT' => 'Fiji Time',
      'FKST' => 'Falkland Islands Summer Time',
      'FKT' => 'Falkland Islands Time',
      'FNT' => 'Fernando de Noronha Time',
      'GALT' => 'Galápagos Time',
      'GAMT' => 'Gambier Islands Time',
      'GET' => 'Georgia Standard Time',
      'GFT' => 'French Guiana Time',
      'GILT' => 'Gilbert Island Time',
      'GIT' => 'Gambier Island Time',
      'GMT' => 'Greenwich Mean Time',
      'GST' => 'Gulf Standard Time',
      'GYT' => 'Guyana Time',
      // 'HDT' => 'Hawaii–Aleutian Daylight Time',
      // 'HAEC' => 'Heure Avancée d\'Europe Centrale',
      // 'HST' => 'Hawaii–Aleutian Standard Time',
      // 'ICT' => 'Indochina Time',
      'IDLW' => 'International Date Line West',
      'IDT' => 'Israel Daylight Time',
      'IOT' => 'Indian Ocean Time',
      'IRDT' => 'Iran Daylight Time',
      'IRKT' => 'Irkutsk Time',
      'IRST' => 'Iran Standard Time',
      // 'IST' => 'Indian Standard Time',
      // 'JST' => 'Japan Standard Time',
      // 'KALT' => 'Kaliningrad Time',
      // 'KGT' => 'Kyrgyzstan Time',
      // 'KOST' => 'Kosrae Time',
      // 'KRAT' => 'Krasnoyarsk Time',
      // 'KST' => 'Korea Standard Time',
      // 'LHST' => 'Lord Howe Standard Time',
      // 'LINT' => 'Line Islands Time',
      // 'MAGT' => 'Magadan Time',
      // 'MART' => 'Marquesas Islands Time',
      'MAWT' => 'Mawson Station Time',
      'MDT' => 'Mountain Daylight Time',
      'MET' => 'Middle European Time Same zone as CET',
      'MEST' => 'Middle European Summer Time Same zone as CEST',
      'MHT' => 'Marshall Islands Time',
      'MIST' => 'Macquarie Island Station Time',
      'MIT' => 'Marquesas Islands Time',
      'MMT' => 'Myanmar Standard Time',
      'MSK' => 'Moscow Time',
      'MST' => 'Mountain Standard Time',
      // 'MUT' => 'Mauritius Time',
      // 'MVT' => 'Maldives Time',
      // 'MYT' => 'Malaysia Time',
      // 'NCT' => 'New Caledonia Time',
      // 'NDT' => 'Newfoundland Daylight Time',
      // 'NFT' => 'Norfolk Time',
      // 'NOVT' => 'Novosibirsk Time',
      // 'NPT' => 'Nepal Time',
      // 'NST' => 'Newfoundland Standard Time',
      // 'NT' => 'Newfoundland Time',
      'NUT' => 'Niue Time',
      // 'NZDT' => 'New Zealand Daylight Time',
      // 'NZST' => 'New Zealand Standard Time',
      // 'OMST' => 'Omsk Time',
      // 'ORAT' => 'Oral Time',
      'PDT' => 'Pacific Daylight Time',
      'PET' => 'Peru Time',
      'PETT' => 'Kamchatka Time',
      'PGT' => 'Papua New Guinea Time',
      // 'PHOT' => 'Phoenix Island Time',
      // 'PKT' => 'Pakistan Standard Time',
      // 'PMDT' => 'Saint Pierre and Miquelon Daylight Time',
      // 'PMST' => 'Saint Pierre and Miquelon Standard Time',
      // 'PONT' => 'Pohnpei Standard Time',
      'PST' => 'Pacific Standard Time',
      // 'PYST' => 'Paraguay Summer Time',
      // 'PYT' => 'Paraguay Time',
      // 'RET' => 'Réunion Time',
      // 'ROTT' => 'Rothera Research Station Time',
      // 'SAKT' => 'Sakhalin Island Time',
      // 'SAMT' => 'Samara Time',
      // 'SAST' => 'South African Standard Time',
      // 'SBT' => 'Solomon Islands Time',
      // 'SCT' => 'Seychelles Time',
      // 'SDT' => 'Samoa Daylight Time',
      // 'SGT' => 'Singapore Time',
      // 'SLST' => 'Sri Lanka Standard Time',
      // 'SRET' => 'Srednekolymsk Time',
      // 'SRT' => 'Suriname Time',
      // 'SST' => 'Samoa Standard Time',
      // 'SYOT' => 'Syowa Time',
      // 'TAHT' => 'Tahiti Time',
      // 'THA' => 'Thailand Standard Time',
      // 'TFT' => 'Indian/Kerguelen',
      // 'TJT' => 'Tajikistan Time',
      // 'TKT' => 'Tokelau Time',
      // 'TLT' => 'Timor Leste Time',
      // 'TMT' => 'Turkmenistan Time',
      // 'TOT' => 'Tonga Time',
      // 'TVT' => 'Tuvalu Time',
      'ULAST' => 'Ulaanbaatar Summer Time',
      'ULAT' => 'Ulaanbaatar Standard Time',
      'UTC' => 'Coordinated Universal Time',
      // 'UYST' => 'Uruguay Summer Time',
      // 'UYT' => 'Uruguay Standard Time',
      // 'UZT' => 'Uzbekistan Time',
      // 'VET' => 'Venezuelan Standard Time',
      // 'VLAT' => 'Vladivostok Time',
      // 'VOLT' => 'Volgograd Time',
      // 'VOST' => 'Vostok Time',
      // 'VUT' => 'Vanuatu Time',
      // 'WAKT' => 'Wake Island Time',
      // 'WARST' => 'Western Argentine Summer Time',
      // 'WAST' => 'West Africa Summer Time',
      // 'WAT' => 'West Africa Time',
      // 'WEST' => 'Western European Summer Time',
      // 'WET' => 'Western European Time',
      // 'WIT' => 'Western Indonesian Time',
      // 'WST' => 'Western Standard Time',
      // 'YAKT' => 'Yakutsk Time',
      // 'YEKT' => 'Yekaterinburg Time',
  );

  // Check if the abbreviation exists in the mapping
  if (array_key_exists($abbreviation, $timezoneAbbreviations)) {
      return $timezoneAbbreviations[$abbreviation];
  } else {
    if($zipCode == "86336" || $zipCode == "85023" || $zipCode == "85022"){
      $timezone = "Mountain Standard Time";
    }else if($zipCode == "97352" || $zipCode == "92832" || $zipCode == "92831" || $zipCode == "97305" || $zipCode == "97305"){
      $timezone = "Pacific Standard Time";
    }else if($zipCode == "75662"){
      $timezone = "Central Standard Time";
    }else{
      $timezone = "Eastern Standard Time";
    }
    return $timezone;
  }
}

/**
 -user bought tiered plan of 10 devices || 10 htoel rooms were created
-user cancels the tiered plan
-user boughts tiered plan 6 devices || 6 hotel rooms should be present 
*/

?>