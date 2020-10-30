<?php
use Carbon\Carbon;

use Jenssegers\Agent\Agent;
     function checkLoginLimit($user_id,$token,$deviceDetails){
        $login_limit = 5;$userInfo = JWTAuth::user();
    //find all by user_id and and status=active
    $active_session = App\SessionTable::where([
    ['status', '=', '1'],
    ['users_id', '=', $user_id],
     ])->get()->toArray();
     if (App\SessionTable::where('users_id', '=',$user_id)->count()== 0) {
      $termAndCondition=true;
   } else {$termAndCondition=false;}
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
            'device_info'=>$deviceDetails,
            'user'=>$userInfo,
            'termandcondition'=>$termAndCondition
            
          
          
        ],200)->send();}

        } else if (count($active_session) > 0 && count($active_session) < $login_limit) {
          //check if device id is same
          if (App\SessionTable::where([
            ['status', '=', '1'],
            ['users_id', '=', $user_id],
            ['mac_address', '=', $deviceDetails],
             ])->exists()) {
              $explodedstring= explode("#~",$deviceDetails);
               return response()->json([
                'success' => false,
                'response_code'=>429,
                'message'=>'Same device ID = '.$explodedstring['1'].' detected,Kindly remove your session first or remove the device from web!',
                //'loggedindeviceinfointheseId'=>$myarr,
              ],429)->send();
            // exists
        }
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
            

            
          'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'device_info'=>$deviceDetails,
            'user'=>$userInfo,'termandcondition'=>false
            
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
  $iPod    = (stripos($ua,"iPod") !== false);
$iPhone  = (stripos($ua,"iPhone") !== false);
$iPad    = (stripos($ua,"iPad") !== false);
$iPadOS = (stripos($ua,"Macintosh") !== false);
$appletv = (stripos($ua,"appletv") !== false);
$Android = (stripos($ua,"Android") !== false);
$Roku = (stripos($ua,"Roku") !== false);
$Darwin = (stripos($ua,"Darwin") !== false);
$Apple_TV = (stripos($ua,"Apple TV") !== false);
if($Roku){
  return 'roku';
}else if( $Apple_TV||$Darwin||$iPod || $iPhone||$iPad||$iPadOS ||$appletv&&!$Android)  {
  return 'apple';
 }else{
   return 'others';
 }
}


?>