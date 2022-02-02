<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Gracenote;
use Illuminate\Support\Facades\DB;
use App\Models\Channel;
use Validator;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Agent\Agent;
class ChannelAPIController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['checkdevice','sortdevice','frontEpgchannelDaysSchedule','frontEpgchannelDaysSchedule2','PacificFrontEpgchannelDaysSchedule']]);
        $this->middleware('subscribed')->only('channeljson','channelDaysSchedule','channelschedulejson');
    }


    public function sortdevice(Request $request)
    {  
        $ua = $request->server('HTTP_USER_AGENT');
        $isDevice=isDeviceAppleRokuOthers($ua);
        if($isDevice=='roku'){
            $response = [
                'success' => true,
                'data'=>'ism links', 
                // 'data'=>$pagechannel->count(), 
                'message' => 'For roku devices',
                'response_code'=>200,
            ];
            // return response()->json($reco);
            return response()->json($response, 200);  
        }else if($isDevice=='firetv'){

            $response = [
                'success' => true,
                'useragent'=>$ua,
                'data'=>'mpd links', 
                // 'data'=>$pagechannel->count(), 
                'message' => 'For firetv devices',
                'response_code'=>200,
            ];
            // return response()->json($reco);
            return response()->json($response, 200);
        }else if($isDevice=='appletv'){

            $response = [
                'success' => true,
                'useragent'=>$ua,
                'data'=>'m3u8 links', 
                // 'data'=>$pagechannel->count(), 
                'message' => 'For appletv devices',
                'response_code'=>200,
            ];
            // return response()->json($reco);
            return response()->json($response, 200);
        }else if($isDevice=='apple'){

            $extra2=getBodyClass();
            $extra=$this->logGetDeviceName();
            $response = [
                'success' => true,
                'extra' => $extra,
                'extra2' => $extra2,
                'data'=>'m3u8 links', 
                // 'data'=>$pagechannel->count(), 
                'message' => 'For apple devices',
                'response_code'=>200,
            ];
            // return response()->json($reco);
            return response()->json($response, 200);
        }else{
             $extra2=getBodyClass();
            $extra=$this->logGetDeviceName();
            $response = [
                'success' => true,
                'extra' => $extra,
                'extra2' => $extra2,
                'data'=>'mpd links', 
                // 'data'=>$pagechannel->count(), 
                'message' => 'For non-apple devices',
                'response_code'=>200,
            ];
            // return response()->json($reco);
            return response()->json($response, 200);
        }
    }
    public function checkdevice(Request $request)
    {  
        $ua = $request->server('HTTP_USER_AGENT');
        $isDevice=isDeviceAppleRokuOthers($ua);
        $isTV=isTV($isDevice);
        // print_r($isTV);
        // exit;
        if($isTV !== false){
            if($isTV['device']=='device-rokutv'){
               $result= $this->checkdeviceFunc($device=$isTV['device'],$type='ism',$ua,$isTV);
                
                return $result;  
            }else if($isTV['device']=='device-appletv'){
                $result= $this->checkdeviceFunc($device=$isTV['device'],$type='m3u8',$ua,$isTV);
                
                return $result;  
            }else{
                $result= $this->checkdeviceFunc($device=$isTV['device'],$type='mpd',$ua,$isTV);
                
                return $result;  
            } 
        }
        
        if($isDevice=='apple'){

            $result= $this->checkdeviceFunc($device=$isDevice,$type='m3u8',$ua,$isTV=false);

            return $result;  
           
        }else{
            $result= $this->checkdeviceFunc($device='non-apple',$type='mpd',$ua,$isTV=false);

            return $result;  
        }
    }
   private function checkdeviceFunc($device,$type,$ua,$isTV){
    if($isTV==false){
        $extra2=getBodyClass();
    $extra=$this->logGetDeviceName();
    $response = [
        'success' => true,
        'extra' => $extra,
        'extra2' => $extra2,
        'data'=>$type.' links', 
        // 'data'=>$pagechannel->count(), 
        'message' => 'For '.$device.' devices',
        'response_code'=>200,
    ];
    // return response()->json($reco);
    return response()->json($response, 200);}else{
        $response = [
            'success' => true,
            'extra2' => $isTV,
            'data'=>$type.' links', 
            'message' => 'For '.$device.' devices',
            'response_code'=>200,
        ];
    
        return response()->json($response, 200);  
    }
    
   }
   private function logGetDeviceName()
{ $agent = new Agent();
    if ($agent->is('Android')) {
        $device = $agent->device();
        $version = $agent->version($device);
        $platform = $agent->platform()??'no platform';
        return $platform. ' ' .$device . ' ' . $version;
    } elseif ($agent->is('iPhone')) {
        $device = $agent->device();
        $version = $agent->version($device);
        $platform = $agent->platform()??'No platform';
        return $platform. ' ' .$device . ' ' . $version;
    } else {
        $browser = $agent->browser();
        $version = $agent->version($browser);
        $platform = $agent->platform()??'No platform';
        return $platform. ' ' .$browser . ' ' . $version;
    }
}
  
    public function channeljson(Request $request) 
    {   $ua = $request->server('HTTP_USER_AGENT');
        $isDevice=isDeviceAppleRokuOthers($ua);
        if($isDevice=='roku'){
            

$record= Cache::rememberForever('channelslistsmooth', function () {
    $type='smooth_url';
    return $this->channeljsonFunc($type)->get();});
           
        }else if($isDevice=='apple')  {
$record= Cache::rememberForever('channelslistapple', function () {
    $type='url';
    return $this->channeljsonFunc($type)->get();});
            
            }else{

$record= Cache::rememberForever('channelslistdash', function () {
    $type='dash_url';
    return $this->channeljsonFunc($type)->get();});
   
   }
       
    //JSON_ARRAY
    // $pagechannel=$record->get();
    
       $pagechannel=$record->map(function ($message, $key) {
        
            $message->genrename = json_decode($message->genrename, true);
        
    
        return $message;
    });


    //  return response()->json($record->toSql());
   
    $response = [
        'success' => true,
        'data'=>$pagechannel,
        // 'count'=>$pagechannel->count(), 
        'message' => 'Channel with genre successfully fetched',
        'response_code'=>200,
    ];
    // return response()->json($reco);
    return response()->json($response, 200);
    }

    public function channeljsonFunc($type){
        // select `channels`.`id`, IFNULL(channels.callSign,channels.name) callSign, `channels`.`image`, `channels`.`dash_url` as `url`, 
        // CONCAT('[',GROUP_CONCAT(QUOTE(IFNULL(genre.name,'Others'))),']') genrename 
        // from `channels` left join `channels_genre` on `channels`.`id` = `channels_genre`.`channels_id` 
        // left join `genre` on `channels_genre`.`genre_id` = `genre`.`id` 
        // where `channels`.`status` = 1 GROUP BY `channels`.`id` order by `channels`.`callSign` asc
    $second=Channel::select('channels.id',DB::raw('IFNULL(channels.callSign,channels.name) callSign'),DB::raw('IFNULL(channels.stationName,channels.name) stationName'), 'channels.image',"channels.$type as url")
           
        ->selectRaw("CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(genre.name, 'Others')), ',', '\",\"'), '\"]')  genrename")
           
           ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
           ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
    
    ->where('channels.status','=','1')
    ->groupBy('channels.id')
    ->orderBy('channels.name', 'asc');
    return $second;
    }


    public function channelschedulejson(Request $request){
        
        // $search = date("Y-m-d");
         $callsign = request()->get('search');
     //      $record  = Gracenote::where([
     // ['CallSign', '=', $callsign],
     // ['StartDateTime', 'LIKE', "%{$search}%"],
     //  ]);
     
     $ua = $request->server('HTTP_USER_AGENT');
 $isDevice=isDeviceAppleRokuOthers($ua);
 
 if($isDevice=='roku'){ $type='smooth_url';
     $record = $this->channelschedulejsonFunc($type,$callsign);
 }else if($isDevice=='apple')  {
 $type='url';
 $record = $this->channelschedulejsonFunc($type,$callsign);
 } else {
 $type='dash_url';
 $record = $this->channelschedulejsonFunc($type,$callsign);
     }
 $reco=$record->get();   
      if ($reco->isEmpty()) {
         $response = [
             'success' => false,
             'data' => 'Empty',
             'message' => 'Channel not found.',
             'response_code'=>404,
         ];
         return response()->json($response, 404);
     }    
     //  return response()->json($record->toSql());
     // $destinationPath = public_path('/uploads/channellogo');
     // $imagePath = $destinationPath. "/".  $record->value('image');
     $imagePath=asset("/uploads/channellogo/".$record->value('image'));
         $response = [
             'success' => true,
             'date'=>date("Y-m-d"),
             'logo'=>$imagePath,
             'data' => $reco,
             // 'count' => $reco->count(),
            
             'message' => 'Channel found',
             'response_code'=>200,
         ];
         // return response()->json($reco);
         return response()->json($response, 200);
     }
     public function channelschedulejsonFunc($type,$callsign){
         $imagePath=url("/uploads/channellogo/");
     
         DB::statement(DB::raw('SET SESSION group_concat_max_len = @@max_allowed_packet'));
         $record  = Gracenote::select("sub.callSign as CallSign","id","StationName","$type as url",DB::raw("CONCAT('$imagePath/',`image`) logo"))
         ->selectRaw(" concat( '[', group_concat(json_object( 'title', EventTitle,'description', EventDescription,'start', UNIX_TIMESTAMP(StartDateTime),'end', UNIX_TIMESTAMP(EndDateTime) ) Order By StartDateTime ), ']') programs")
         ->fromSub(function ($query) use ($type) {
           $query->select(DB::raw('IFNULL(gracenote.StationName,channels.name) StationName'),DB::raw('IFNULL(channels.callSign,channels.name) callSign'),
           DB::raw('IFNULL(gracenote.EventTitle,channels.name) EventTitle'),DB::raw('IFNULL(gracenote.EventDescription,"EventDescription") EventDescription'),DB::raw('IFNULL(gracenote.StartDateTime,DATE_FORMAT(CONCAT(date_add(CURDATE(),interval -1 day), " 00:00:00"), "%Y-%m-%d %H:%i:%s")) StartDateTime'),
           DB::raw('IFNULL(gracenote.EndDateTime,DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 1 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")) EndDateTime'),'channels.id','channels.status',DB::raw('IFNULL(channels.image,"notfound.png") image'),"channels.$type")
           ->from('gracenote')
           ->rightJoin('channels', 'gracenote.CallSign', '=', 'channels.callSign')
         
           ->whereRaw('gracenote.`StartDateTime` BETWEEN DATE_FORMAT(CONCAT(date_add(CURDATE(),interval -1 day), " 00:00:00"), "%Y-%m-%d %H:%i:%s") 
           AND DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 1 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")   or `channels`.`callSign` IS NULL')
            ->orderBy('gracenote.StartDateTime', 'asc');
             }, 'sub')
             ->where('sub.status','=','1')
             ->where('sub.callSign','=',$callsign)
         ->orderBy('sub.callSign', 'asc')
         ->groupBy('sub.callSign','StationName','image',$type,'id');
         return $record;
     }

      
//channel with 7 day of schdule
public function channelDaysSchedule(Request $request){
    /* SELECT * FROM `gracenote` WHERE `gracenote`.`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) ORDER BY `gracenote`.`StartDateTime` ASC
    select GROUP_CONCAT(EventTitle SEPARATOR ', ' ORDER BY StartDateTime ASC) programs,CallSign from gracenote WHERE `gracenote`.`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)  group BY CallSign ORDER by CallSign
    SELECT CallSign, JSON_ARRAYAGG( JSON_OBJECT( 'title', EventTitle, 'start', StartDateTime,'end', EndDateTime ) ) AS programs FROM gracenote GROUP BY CallSign
select count(EventTitle),CallSign from gracenote WHERE `gracenote`.`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) group BY CallSign ORDER by CallSign
select GROUP_CONCAT(EventTitle),CallSign from gracenote WHERE `gracenote`.`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) group BY CallSign ORDER by CallSign   
$record  = Gracenote::select('StationName','CallSign','EventTitle','EventDescription','StartDateTime','EndDateTime')->whereRaw('`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)')->orderBy('CallSign', 'asc');
SELECT CallSign,StationName,
      JSON_ARRAYAGG(
          JSON_OBJECT(
              'title', EventTitle,
              'start', StartDateTime,'end', EndDateTime )
      ) AS programs
    FROM gracenote
    GROUP BY CallSign,StationName
$record  = Gracenote::select('CallSign',DB::raw("JSON_ARRAYAGG( JSON_OBJECT( 'title', EventTitle, 'start', StartDateTime,'end', EndDateTime ) ) AS programs"))
$record  = Gracenote::select('CallSign',DB::raw("GROUP_CONCAT( JSON_OBJECT('title', EventTitle, 'start', StartDateTime,'end', EndDateTime ) ) AS programs "),DB::raw('SEPARATOR'),DB::raw("'|'"))->groupBy('CallSign');
$record  = Gracenote::select('CallSign','StationName',DB::raw("GROUP_CONCAT( JSON_OBJECT('title', EventTitle, 'start', StartDateTime,'end', EndDateTime )SEPARATOR '|' ) AS programs "))->groupBy('CallSign','StationName');
SELECT CallSign,JSON_ARRAYAGG( JSON_OBJECT( 'title', sub.EventTitle, 'start', sub.StartDateTime,'end', sub.EndDateTime ) ) AS programs FROM ( SELECT * FROM `gracenote` WHERE `gracenote`.`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) ORDER BY `gracenote`.`StartDateTime` ASC ) AS sub GROUP BY sub.CallSign */
$ua = $request->server('HTTP_USER_AGENT');
$isDevice=isDeviceAppleRokuOthers($ua);

if($isDevice=='roku'){ 
    $reco= Cache::remember('channelscheduleroku',60*60*24, function () {
        $type='smooth_url';
    return $this->channelDaysScheduleFunc($type)->get();});
}else if($isDevice=='apple')  {

$reco = Cache::remember('channelscheduleapple',60*60*24, function () {
    $type='url';
    return $this->channelDaysScheduleFunc($type)->get();});
} else {

$reco= Cache::remember('channelscheduleothers',60*60*24, function () {
    $type='dash_url';
    return $this->channelDaysScheduleFunc($type)->get();});
    }
    

// SELECT CallSign,StationName,
// GROUP_CONCAT(
//   JSON_OBJECT('title', EventTitle,
//                 'start', StartDateTime,'end', EndDateTime 
//   ) SEPARATOR '|'
// ) AS programs
//     FROM gracenote
//     GROUP BY CallSign,StationName
// ->whereRaw('`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)')->orderBy('CallSign', 'asc');
  
// $reco=$record->toSql(); echo $reco;exit; 

// $reco=$record->get(); //echo $reco;exit; 
   if ($reco->isEmpty()) {
      $response = [
          'success' => false,
          'data' => 'Empty',
          'message' => 'Channel not found.',
          'response_code'=>404,
      ];
      return response()->json($response, 404);
  }  
      // return response()->json($record->toSql());
      // return json_encode($api);
//       $record= DB::table('gracenote');
// $reco=$record->get();
// dd($reco);
      $response = [
        'success' => true,
        'date'=>date("Y-m-d"),
      //   'count' => $reco->count(),
        'data' => $reco,
       
        'message' => 'Schedule data for 3 days found',
        'response_code'=>200,
    ];
    $responsejson=json_encode($response);
$data=gzencode($responsejson,9);
// $response = [
//     'success' => true,
//     'date'=>date("Y-m-d"),
//   //   'count' => $reco->count(),
//     'data' => $data,
   
//     'message' => 'Schedule data for 3 days found',
//     'response_code'=>200,
// ];
return response($data)->withHeaders([
    'Access-Control-Allow-Origin' => '*',
    'Access-Control-Allow-Methods'=> 'GET',            
    'Content-type' => 'application/json; charset=utf-8',
    'Content-Length'=> strlen($data),
    'Content-Encoding' => 'gzip'
]);
      
     
  return response()->json($response, 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
  //  return  response()->json([
  //     'clients' => $this->transformClientCollection($api)
  //  ], 200);
  }   
//channel with 7 day of schdule
public function channelDaysSchedulePaginate(Request $request){
$ua = $request->server('HTTP_USER_AGENT');
$isDevice=isDeviceAppleRokuOthers($ua);
// return $this->channelDaysScheduleFunc('dash_url')
// dd($request);
if($request->exists('genre')) {

    $reco=$this->hasSearchChannelDaysScheduleFuncWithGenre($request,$isDevice);
    
}else{
    $reco=$this->channelDaysScheduleFuncwithoutSearch($request,$isDevice);
}
    

// $reco=$record->toSql(); echo $reco;exit; 

// $reco=$record->get(); //echo $reco;exit; 
   if ($reco->isEmpty()) {
      $response = [
          'success' => false,
          'data' => 'Empty',
          'message' => 'Channel not found.',
          'response_code'=>404,
      ];
      return response()->json($response, 404);
  }  
      // return response()->json($record->toSql());
      // return json_encode($api);
//       $record= DB::table('gracenote');
// $reco=$record->get();
// dd($reco);
      $response = [
        'success' => true,
        'date'=>date("Y-m-d"),
      //   'count' => $reco->count(),
        'data' => $reco,
       
        'message' => 'Schedule data for 3 days found',
        'response_code'=>200,
    ];
    $responsejson=json_encode($response);
// $data=gzencode($responsejson,9);
$data=$responsejson;
// $response = [
//     'success' => true,
//     'date'=>date("Y-m-d"),
//   //   'count' => $reco->count(),
//     'data' => $data,
   
//     'message' => 'Schedule data for 3 days found',
//     'response_code'=>200,
// ];
return response($data)->withHeaders([
    'Access-Control-Allow-Origin' => '*',
    'Access-Control-Allow-Methods'=> 'GET',            
    'Content-type' => 'application/json; charset=utf-8',
    // 'Content-Length'=> strlen($data),
    // 'Content-Encoding' => 'gzip'
]);
      
     
  return response()->json($response, 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
  //  return  response()->json([
  //     'clients' => $this->transformClientCollection($api)
  //  ], 200);
  }


      
//channel with 7 day of schdule
public function channelDaysScheduleFuncwithoutSearch($request,$isDevice){

if($isDevice=='roku'){ 
   
    $type='smooth_url';
    $reco=  $this->channelDaysScheduleFunc($type)->paginate(10);
}else if($isDevice=='apple')  {


$type='url';
$reco =  $this->channelDaysScheduleFunc($type)->paginate(10);
} else {
$type='dash_url';
$reco= $this->channelDaysScheduleFunc($type)->paginate(10);
}
return $reco;
}
public function hasSearchChannelDaysScheduleFuncWithGenre($request,$isDevice){
    $arr = explode(',', $request->genre);
    if(in_array('Others', $arr)){

        
        if($isDevice=='roku'){ 
   
            $type='smooth_url';
            $reco= $this->channelDaysScheduleFuncWithGenre($type,$arr)
        // ->toSql();
        ->paginate(10)->appends(['genre' => $request->genre]);
    }else if($isDevice=='apple')  {
    
    
        $type='url';
        $reco= $this->channelDaysScheduleFuncWithGenre($type,$arr)
        // ->toSql();
        ->paginate(10)->appends(['genre' => $request->genre]);
    } else {
        $type='dash_url';
        $reco= $this->channelDaysScheduleFuncWithGenre($type,$arr)
        // ->toSql();
        ->paginate(10)->appends(['genre' => $request->genre]);
        }
        }else{
        
           
        if($isDevice=='roku'){ 
   
            $type='smooth_url';
            $reco= $this->channelDaysScheduleFuncWithGenre($type,$arr)
        // ->toSql();
        ->paginate(10)->appends(['genre' => $request->genre]);
    }else if($isDevice=='apple')  {
    
    
        $type='url';
        $reco= $this->channelDaysScheduleFuncWithGenre($type,$arr)
        // ->toSql();
        ->paginate(10)->appends(['genre' => $request->genre]);
    } else {
        $type='dash_url';
        $reco= $this->channelDaysScheduleFuncWithGenre($type,$arr)
        // ->toSql();
        ->paginate(10)->appends(['genre' => $request->genre]);
        }
        }
        return $reco;
}
public function channelDaysScheduleoffsettz(Request $request){
$ua = $request->server('HTTP_USER_AGENT');

$input = $request->all();
//regex to validate gmt + and - hours
        $validator = Validator::make($input, [
            'hoursoffset' => ['required','regex:/[+-][0-9]{2}:[0-9]{2}\b/']
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' => 'Validation Error.',
                'message' => $validator->errors(),
                'response_code'=>404,
            ];
            return response()->json($response, 404);
        }
        $hoursofset=$request->get('hoursoffset');
$isDevice=isDeviceAppleRokuOthers($ua);

if($isDevice=='roku'){ 
    // $reco= Cache::remember('channelschedulerokutz',60*60*24, function () {
        $type='smooth_url';
        $reco= $this->channelDaysScheduleOffsetFunctz($type,$hoursofset)->get();
// });
}else if($isDevice=='apple')  {

// $reco = Cache::remember('channelscheduleappletz',60*60*24, function () {
    $type='url';
    $reco= $this->channelDaysScheduleOffsetFunctz($type,$hoursofset)->get();
// });
} else {

// $reco= Cache::remember('channelscheduleotherstz',60*60*24, function () {
    $type='dash_url';
    $reco= $this->channelDaysScheduleOffsetFunctz($type,$hoursofset)->get();
// });
    }
    

// SELECT CallSign,StationName,
// GROUP_CONCAT(
//   JSON_OBJECT('title', EventTitle,
//                 'start', StartDateTime,'end', EndDateTime 
//   ) SEPARATOR '|'
// ) AS programs
//     FROM gracenote
//     GROUP BY CallSign,StationName
// ->whereRaw('`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)')->orderBy('CallSign', 'asc');
  
// $reco=$record->toSql(); echo $reco;exit; 

// $reco=$record->get(); //echo $reco;exit; 
   if ($reco->isEmpty()) {
      $response = [
          'success' => false,
          'data' => 'Empty',
          'message' => 'Channel not found.',
          'response_code'=>404,
      ];
      return response()->json($response, 404);
  }  
      // return response()->json($record->toSql());
      // return json_encode($api);
//       $record= DB::table('gracenote');
// $reco=$record->get();
// dd($reco);
// dd($this->channelDaysScheduleFunctz('dash_url')->toSql());
      $response = [
        'success' => true,
        'date'=>date("Y-m-d"),
        // 'sql'=>$this->channelDaysScheduleFunctz('dash_url')->toSql(),
      //   'count' => $reco->count(),
        'data' => $reco,
       
        'message' => 'Schedule data for 3 days found',
        'response_code'=>200,
    ];
    $responsejson=json_encode($response);
$data=gzencode($responsejson,9);
// $response = [
//     'success' => true,
//     'date'=>date("Y-m-d"),
//   //   'count' => $reco->count(),
//     'data' => $data,
   
//     'message' => 'Schedule data for 3 days found',
//     'response_code'=>200,
// ];
return response($data)->withHeaders([
    'Access-Control-Allow-Origin' => '*',
    'Access-Control-Allow-Methods'=> 'GET',            
    'Content-type' => 'application/json; charset=utf-8',
    'Content-Length'=> strlen($data),
    'Content-Encoding' => 'gzip'
]);
      
     
  return response()->json($response, 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
  //  return  response()->json([
  //     'clients' => $this->transformClientCollection($api)
  //  ], 200);
  }

public function channelDaysScheduletz(Request $request){
$ua = $request->server('HTTP_USER_AGENT');
$isDevice=isDeviceAppleRokuOthers($ua);

if($isDevice=='roku'){ 
    // $reco= Cache::remember('channelschedulerokutz',60*60*24, function () {
        $type='smooth_url';
        $reco= $this->channelDaysScheduleFunctz($type)->get();
// });
}else if($isDevice=='apple')  {

// $reco = Cache::remember('channelscheduleappletz',60*60*24, function () {
    $type='url';
    $reco= $this->channelDaysScheduleFunctz($type)->get();
// });
} else {

// $reco= Cache::remember('channelscheduleotherstz',60*60*24, function () {
    $type='dash_url';
    $reco= $this->channelDaysScheduleFunctz($type)->get();
// });
    }
    

// SELECT CallSign,StationName,
// GROUP_CONCAT(
//   JSON_OBJECT('title', EventTitle,
//                 'start', StartDateTime,'end', EndDateTime 
//   ) SEPARATOR '|'
// ) AS programs
//     FROM gracenote
//     GROUP BY CallSign,StationName
// ->whereRaw('`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)')->orderBy('CallSign', 'asc');
  
// $reco=$record->toSql(); echo $reco;exit; 

// $reco=$record->get(); //echo $reco;exit; 
   if ($reco->isEmpty()) {
      $response = [
          'success' => false,
          'data' => 'Empty',
          'message' => 'Channel not found.',
          'response_code'=>404,
      ];
      return response()->json($response, 404);
  }  
      // return response()->json($record->toSql());
      // return json_encode($api);
//       $record= DB::table('gracenote');
// $reco=$record->get();
// dd($reco);
// dd($this->channelDaysScheduleFunctz('dash_url')->toSql());
      $response = [
        'success' => true,
        'date'=>date("Y-m-d"),
        // 'sql'=>$this->channelDaysScheduleFunctz('dash_url')->toSql(),
      //   'count' => $reco->count(),
        'data' => $reco,
       
        'message' => 'Schedule data for 3 days found',
        'response_code'=>200,
    ];
    $responsejson=json_encode($response);
$data=gzencode($responsejson,9);
// $response = [
//     'success' => true,
//     'date'=>date("Y-m-d"),
//   //   'count' => $reco->count(),
//     'data' => $data,
   
//     'message' => 'Schedule data for 3 days found',
//     'response_code'=>200,
// ];
return response($data)->withHeaders([
    'Access-Control-Allow-Origin' => '*',
    'Access-Control-Allow-Methods'=> 'GET',            
    'Content-type' => 'application/json; charset=utf-8',
    'Content-Length'=> strlen($data),
    'Content-Encoding' => 'gzip'
]);
      
     
  return response()->json($response, 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
  //  return  response()->json([
  //     'clients' => $this->transformClientCollection($api)
  //  ], 200);
  }

  //channel with 7 day of schdule
  public function frontEpgchannelDaysSchedule(){
    $imagePath=url("/uploads/channellogo/");
    $input=url('livetv/');
    DB::statement(DB::raw('SET SESSION group_concat_max_len = @@max_allowed_packet'));
    $record  = Gracenote::select(["sub.id","sub.callSign","StationName AS name","genres",DB::raw("CONCAT('$imagePath/',`image`) icon")])
    ->selectRaw("CAST( CONCAT('[\"', REPLACE(GROUP_CONCAT(DISTINCT IFNULL(g.name, 'Others')), ',', '\",\"'), '\"]') AS JSON ) genrename")
    ->selectRaw(" concat( '[', group_concat(json_object( 'title', EventTitle, 'description', EventDescription,'start', (UNIX_TIMESTAMP(StartDateTime)*1000),'end', (UNIX_TIMESTAMP(EndDateTime)*1000) ) Order By StartDateTime ), ']') events")
    ->leftJoin('genre as g', DB::raw("FIND_IN_SET(g.id, sub.genres)"), DB::raw('>'),DB::raw('0'))
    ->fromSub(function ($query) {
        $query->select(DB::raw('IFNULL(gracenote.StationName,channels.name) StationName'),DB::raw('IFNULL(channels.callSign,channels.name) callSign'),DB::raw('IFNULL(gracenote.EventDescription,"EventDescription") EventDescription'),
        DB::raw('IFNULL(gracenote.EventTitle,channels.name) EventTitle'),DB::raw('IFNULL(gracenote.StartDateTime,DATE_FORMAT(CONCAT(date_add(CURDATE(),interval -1 day), " 00:00:00"), "%Y-%m-%d %H:%i:%s") ) StartDateTime'),
        DB::raw('IFNULL(gracenote.EndDateTime,DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 1 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")) EndDateTime'),'channels.id','channels.genres',DB::raw('IFNULL(channels.image,"notfound.png") image'),'channels.url')
        ->from('gracenote')
        ->rightJoin('channels', 'gracenote.CallSign', '=', 'channels.callSign')
       
    ->whereRaw('gracenote.`StartDateTime` BETWEEN DATE_FORMAT(CONCAT(date_add(CURDATE(),interval -1 day), " 00:00:00"), "%Y-%m-%d %H:%i:%s") 
    AND DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 1 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")   or `channels`.`callSign` IS NULL')
     ->orderBy('gracenote.StartDateTime', 'asc');
      }, 'sub')
    
    ->orderBy('sub.callSign', 'asc')
    ->groupBy('sub.callSign','StationName','image','sub.id','genres');
        
   $reco=$record->get()->map(function ($address) use ($input) {

                 
    $address['url'] = $input.'/'.$address['callSign'].'/'.$address['genrename']['0'] ;

    return $address;

});//exit; // echo $reco; exit;
         if ($reco->isEmpty()) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'Channel not found.',
                'response_code'=>404,
            ];
            return response()->json($response, 404);
        }  
    
            $response = [
                'success' => true,
                'date'=>date("Y-m-d"),
                'data' => $reco,
               
                'message' => 'Schedule data for 3 days found',
                'response_code'=>200,
            ];
           
        return response()->json($response, 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
     
        }

        public function frontEpgchannelDaysSchedule2(){
            $imagePath=url("/uploads/channellogo/");
            $input=url('livetv/');
            DB::statement(DB::raw('SET SESSION group_concat_max_len = @@max_allowed_packet'));
            $record  = Gracenote::select(["sub.id","sub.callSign","StationName AS name","TimeZoneName","genres",DB::raw("CONCAT('$imagePath/',`image`) icon")])
            ->selectRaw("CAST( CONCAT('[\"', REPLACE(GROUP_CONCAT(DISTINCT IFNULL(g.name, 'Others')), ',', '\",\"'), '\"]') AS JSON ) genrename")
            ->selectRaw(" concat( '[', group_concat(json_object( 'title', EventTitle,'start', StartDateTime,'end', EndDateTime ) Order By StartDateTime ), ']') events")
            ->leftJoin('genre as g', DB::raw("FIND_IN_SET(g.id, sub.genres)"), DB::raw('>'),DB::raw('0'))
            ->fromSub(function ($query) {
                $query->select(DB::raw('IFNULL(gracenote.StationName,channels.name) StationName'),DB::raw('IFNULL(gracenote.TimeZoneName,"US/Pacific") TimeZoneName'),DB::raw('IFNULL(channels.callSign,channels.name) callSign'),
                DB::raw('IFNULL(gracenote.EventTitle,channels.name) EventTitle'),DB::raw('IFNULL(gracenote.StartDateTime,DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s") ) StartDateTime'),
                DB::raw('IFNULL(gracenote.EndDateTime,DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 3 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")) EndDateTime'),'channels.id','channels.genres',DB::raw('IFNULL(channels.image,"notfound.png") image'),'channels.url')
                ->from('gracenote')
                ->rightJoin('channels', 'gracenote.CallSign', '=', 'channels.callSign')
               
            ->whereRaw('gracenote.`StartDateTime` BETWEEN DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s") 
            AND DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 3 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")   or `channels`.`callSign` IS NULL')
             ->orderBy('gracenote.StartDateTime', 'asc');
              }, 'sub')
            
            ->orderBy('sub.callSign', 'asc')
            ->groupBy('sub.callSign','StationName','image','sub.id','genres');
                
           $reco=$record->get()->map(function ($address) use ($input) {
        
                         
            $address['url'] = $input.'/'.$address['callSign'].'/'.$address['genrename']['0'] ;
             if($address['TimeZoneName']=="Pacific Observing"){
                $address['eventcount']=count($address->events);
                
                foreach($address->events as $element){
                    
                    $address= array_push($element, "manzana", "arándano");
                    
                }
                
               
             }
            return $address;
        
        });//exit; // echo $reco; exit;
                 if ($reco->isEmpty()) {
                    $response = [
                        'success' => false,
                        'data' => 'Empty',
                        'message' => 'Channel not found.',
                        'response_code'=>404,
                    ];
                    return response()->json($response, 404);
                }  
            
                    $response = [
                        'success' => true,
                        'date'=>date("Y-m-d"),
                        'data' => $reco,
                       
                        'message' => 'Schedule data for 3 days found',
                        'response_code'=>200,
                    ];
                   
                return response()->json($response, 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
             
                }
  public function channelDaysScheduleFuncWithGenre($type,$q){
    // dump("yooooo");  
    // dd($q);
//     select CONCAT('[',GROUP_CONCAT(DISTINCT(QUOTE(IFNULL(genre.name,'Others')))),']') genrename ,`sub`.`callSign` as `callSign`, `sub`.`id`, `StationName` as `stationName`, `dash_url` as `url`, CONCAT('http://127.0.0.1:8000/uploads/channellogo/',`image`) logo,  
//     concat( '[', group_concat(json_object( 'title', EventTitle,'description', EventDescription,'start', UNIX_TIMESTAMP(StartDateTime),'end', UNIX_TIMESTAMP(EndDateTime) ) Order By StartDateTime ), ']') programs from (select IFNULL(gracenote.StationName,channels.name) StationName, 
//     IFNULL(channels.callSign,channels.name) callSign, IFNULL(gracenote.EventTitle,channels.name) EventTitle, IFNULL(gracenote.EventDescription,"EventDescription") EventDescription, IFNULL(gracenote.StartDateTime,DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s")) StartDateTime, 
//     IFNULL(gracenote.EndDateTime,DATE_FORMAT(CONCAT(CURDATE(), " 23:59:59"), "%Y-%m-%d %H:%i:%s")) EndDateTime, `channels`.`id`, `channels`.`status`, IFNULL(channels.image,"notfound.png") image, `channels`.`dash_url` from `gracenote` right join `channels` on `gracenote`.`CallSign` = `channels`.`callSign` 
//     where gracenote.`StartDateTime` BETWEEN DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s") 
//     AND DATE_FORMAT(CONCAT(CURDATE(), " 23:59:59"), "%Y-%m-%d %H:%i:%s")   or `channels`.`callSign` IS NULL order by `gracenote`.`StartDateTime` asc) as `sub` left join `channels_genre` on `sub`.`id` = `channels_genre`.`channels_id` left join `genre` on `channels_genre`.`genre_id` = `genre`.`id`where `sub`.`status` = 1  group by `sub`.`callSign`, `StationName`, `image`, `dash_url`, `id` order by `sub`.`callSign` asc
//  

// genre.name in ('Sports')
 $imagePath=url("/uploads/channellogo/");
    DB::statement(DB::raw('SET SESSION group_concat_max_len = @@max_allowed_packet'));
    $record  = Gracenote::select("sub.callSign as callSign","sub.id","StationName as stationName","$type as url",DB::raw("CONCAT('$imagePath/',`image`) logo"))
    // DB::raw("CONCAT('[',GROUP_CONCAT(DISTINCT(QUOTE(IFNULL(genre.name,'Others')))),']') genrename"))

    ->selectRaw("CONCAT('[\"', REPLACE(GROUP_CONCAT(DISTINCT IFNULL(genre.name, 'Others')), ',', '\",\"'), '\"]')  genrename")
    // ->selectRaw("CONCAT('[',GROUP_CONCAT(DISTINCT(QUOTE(IFNULL(genre.name,'Others')))),']') genrename")
       
    ->selectRaw(" concat( '[', group_concat(json_object( 'title', EventTitle,'description', EventDescription,'start', UNIX_TIMESTAMP(StartDateTime),'end', UNIX_TIMESTAMP(EndDateTime) ) Order By StartDateTime ), ']') programs")
    ->fromSub(function ($query) use ($type) {
      $query->select(DB::raw('IFNULL(gracenote.StationName,channels.name) StationName'),DB::raw('IFNULL(channels.callSign,channels.name) callSign'),
      DB::raw('IFNULL(gracenote.EventTitle,channels.name) EventTitle'),DB::raw('IFNULL(gracenote.EventDescription,"EventDescription") EventDescription'),DB::raw('IFNULL(gracenote.StartDateTime,DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s")) StartDateTime'),
      DB::raw('IFNULL(gracenote.EndDateTime,DATE_FORMAT(CONCAT(CURDATE(), " 23:59:59"), "%Y-%m-%d %H:%i:%s")) EndDateTime'),'channels.id','channels.status',DB::raw('IFNULL(channels.image,"notfound.png") image'),"channels.$type")
      ->from('gracenote')
      ->rightJoin('channels', 'gracenote.CallSign', '=', 'channels.callSign')
    
      ->whereRaw('gracenote.`StartDateTime` BETWEEN DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s") 
      AND DATE_FORMAT(CONCAT(CURDATE(), " 23:59:59"), "%Y-%m-%d %H:%i:%s")   or `channels`.`callSign` IS NULL')
       ->orderBy('gracenote.StartDateTime', 'asc');
        }, 'sub')

           
           ->leftJoin('channels_genre', 'sub.id', '=', 'channels_genre.channels_id')
           ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
        ->where('sub.status','=','1');
        if(!in_array('Others', $q)){

            $record->whereIn('genre.name', $q )
            ->orderBy('sub.callSign', 'asc')
            ->groupBy('sub.callSign','StationName','image',$type,'sub.id');
        }else{
            $record->whereRaw('`channels_genre`.`channels_id` IS NULL')
            ->orderBy('sub.callSign', 'asc')
            ->groupBy('sub.callSign','StationName','image',$type,'sub.id');
        }
    return $record;
   
}
  public function channelDaysScheduleFunc($type){
    $imagePath=url("/uploads/channellogo/");
    DB::statement(DB::raw('SET SESSION group_concat_max_len = @@max_allowed_packet'));
    $record  = Gracenote::select("sub.callSign as callSign","id","StationName as stationName","$type as url",DB::raw("CONCAT('$imagePath/',`image`) logo"))
    ->selectRaw(" concat( '[', group_concat(json_object( 'title', EventTitle,'description', EventDescription,'start', UNIX_TIMESTAMP(StartDateTime),'end', UNIX_TIMESTAMP(EndDateTime) ) Order By StartDateTime ), ']') programs")
    ->fromSub(function ($query) use ($type) {
      $query->select(DB::raw('IFNULL(gracenote.StationName,channels.name) StationName'),DB::raw('IFNULL(channels.callSign,channels.name) callSign'),
      DB::raw('IFNULL(gracenote.EventTitle,channels.name) EventTitle'),DB::raw('IFNULL(gracenote.EventDescription,"EventDescription") EventDescription'),DB::raw('IFNULL(gracenote.StartDateTime,DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s")) StartDateTime'),
      DB::raw('IFNULL(gracenote.EndDateTime,DATE_FORMAT(CONCAT(CURDATE(), " 23:59:59"), "%Y-%m-%d %H:%i:%s")) EndDateTime'),'channels.id','channels.status',DB::raw('IFNULL(channels.image,"notfound.png") image'),"channels.$type")
      ->from('gracenote')
      ->rightJoin('channels', 'gracenote.CallSign', '=', 'channels.callSign')
    
      ->whereRaw('gracenote.`StartDateTime` BETWEEN DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s") 
      AND DATE_FORMAT(CONCAT(CURDATE(), " 23:59:59"), "%Y-%m-%d %H:%i:%s")   or `channels`.`callSign` IS NULL')
       ->orderBy('gracenote.StartDateTime', 'asc');
        }, 'sub')
        ->where('sub.status','=','1')
    ->orderBy('sub.callSign', 'asc')
    ->groupBy('sub.callSign','StationName','image',$type,'id');
    return $record;
  }  
  public function channelDaysScheduleFunctz($type){
    $imagePath=url("/uploads/channellogo/");
    DB::statement(DB::raw('SET SESSION group_concat_max_len = @@max_allowed_packet'));
    $record  = Gracenote::select("sub.callSign as callSign","id","StationName as stationName","$type as url",DB::raw("CONCAT('$imagePath/',`image`) logo"))
    ->selectRaw(" concat( '[', group_concat(json_object( 'title', EventTitle,'description', EventDescription,'start', UNIX_TIMESTAMP(StartDateTime),'end', UNIX_TIMESTAMP(EndDateTime),
    'starttz',(UNIX_TIMESTAMP(StartDateTimewithTimezon)),'endtz',(UNIX_TIMESTAMP(EndDateTimewithTimezon)),'TimeZoneName',TimeZoneName ) Order By StartDateTime ), ']') programs")
    ->fromSub(function ($query) use ($type) {
      $query->select(DB::raw('IFNULL(gracenote.StationName,channels.name) StationName'),DB::raw('IFNULL(channels.callSign,channels.name) callSign'),
      DB::raw('IFNULL(gracenote.EventTitle,channels.name) EventTitle'),DB::raw('IFNULL(gracenote.EventDescription,"EventDescription") EventDescription'),
      DB::raw('IFNULL(gracenote.StartDateTime,DATE_FORMAT(CONCAT(date_add(CURDATE(),interval -1 day), " 00:00:00"), "%Y-%m-%d %H:%i:%s")) StartDateTime'),
      DB::raw('IFNULL(gracenote.TimeZoneName,"TimeZoneName") TimeZoneName'),
      DB::raw('IFNULL(gracenote.EndDateTime,DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 3 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")) EndDateTime'),'channels.id','channels.status',DB::raw('IFNULL(channels.image,"notfound.png") image'),"channels.$type",
      DB::raw(" CASE gracenote.TimeZoneName
      WHEN 'Pacific Observing' THEN CONVERT_TZ(gracenote.StartDateTime,'+00:00','-07:00')
      WHEN 'Eastern Observing' THEN CONVERT_TZ(gracenote.StartDateTime,'+00:00','-07:00')
      WHEN 'Central Observing' THEN CONVERT_TZ(gracenote.StartDateTime,'+00:00','-07:00')

      ELSE CONVERT_TZ(DATE_FORMAT(CONCAT(CURDATE(), ' 00:00:00'), '%Y-%m-%d %H:%i:%s'),'+00:00','-07:00')
  end StartDateTimewithTimezon"),DB::raw(" CASE gracenote.TimeZoneName
      WHEN 'Pacific Observing' THEN CONVERT_TZ(gracenote.EndDateTime,'+00:00','-07:00')
      WHEN 'Eastern Observing' THEN CONVERT_TZ(gracenote.EndDateTime,'+00:00','-07:00')
      WHEN 'Central Observing' THEN CONVERT_TZ(gracenote.EndDateTime,'+00:00','-07:00')

      ELSE CONVERT_TZ(DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 3 day), ' 23:59:59'), '%Y-%m-%d %H:%i:%s'),'+00:00','-07:00')
  end EndDateTimewithTimezon"),

      )
      ->from('gracenote')
      ->rightJoin('channels', 'gracenote.CallSign', '=', 'channels.callSign')
    
      ->whereRaw('gracenote.`StartDateTime` BETWEEN DATE_FORMAT(CONCAT(date_add(CURDATE(),interval -1 day), " 00:00:00"), "%Y-%m-%d %H:%i:%s") 
      AND DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 1 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")   or `channels`.`callSign` IS NULL')
       ->orderBy('gracenote.StartDateTime', 'asc');
        }, 'sub')
        ->where('sub.status','=','1')
    ->orderBy('sub.callSign', 'asc')
    ->groupBy('sub.callSign','StationName','image',$type,'id');
    return $record;
  }


  public function channelDaysScheduleOffsetFunctz($type,$timeoffset){
      $tz=$timeoffset??'-07:00';
    $imagePath=url("/uploads/channellogo/");
    DB::statement(DB::raw('SET SESSION group_concat_max_len = @@max_allowed_packet'));
    $record  = Gracenote::select("sub.callSign as callSign","id","StationName as stationName","$type as url",DB::raw("CONCAT('$imagePath/',`image`) logo"))
    ->selectRaw(" concat( '[', group_concat(json_object( 'title', EventTitle,'description', EventDescription,'start', UNIX_TIMESTAMP(StartDateTime),'end', UNIX_TIMESTAMP(EndDateTime),
    'starttz',(UNIX_TIMESTAMP(StartDateTimewithTimezon)),'endtz',(UNIX_TIMESTAMP(EndDateTimewithTimezon)),'TimeZoneName',TimeZoneName ) Order By StartDateTime ), ']') programs")
    ->fromSub(function ($query) use ($type,$tz) {
      $query->select(DB::raw('IFNULL(gracenote.StationName,channels.name) StationName'),DB::raw('IFNULL(channels.callSign,channels.name) callSign'),
      DB::raw('IFNULL(gracenote.EventTitle,channels.name) EventTitle'),DB::raw('IFNULL(gracenote.EventDescription,"EventDescription") EventDescription'),
      DB::raw('IFNULL(gracenote.StartDateTime,DATE_FORMAT(CONCAT(date_add(CURDATE(),interval -1 day), " 00:00:00"), "%Y-%m-%d %H:%i:%s")) StartDateTime'),
      DB::raw('IFNULL(gracenote.TimeZoneName,"TimeZoneName") TimeZoneName'),
      DB::raw('IFNULL(gracenote.EndDateTime,DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 3 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")) EndDateTime'),'channels.id','channels.status',DB::raw('IFNULL(channels.image,"notfound.png") image'),"channels.$type",
      DB::raw(" CASE gracenote.TimeZoneName
      WHEN 'Pacific Observing' THEN CONVERT_TZ(gracenote.StartDateTime,'+00:00','.$tz.')
      WHEN 'Eastern Observing' THEN CONVERT_TZ(gracenote.StartDateTime,'+00:00','.$tz.')
      WHEN 'Central Observing' THEN CONVERT_TZ(gracenote.StartDateTime,'+00:00','.$tz.')

      ELSE CONVERT_TZ(DATE_FORMAT(CONCAT(CURDATE(), ' 00:00:00'), '%Y-%m-%d %H:%i:%s'),'+00:00','.$tz.')
  end StartDateTimewithTimezon"),DB::raw(" CASE gracenote.TimeZoneName
      WHEN 'Pacific Observing' THEN CONVERT_TZ(gracenote.EndDateTime,'+00:00','.$tz.')
      WHEN 'Eastern Observing' THEN CONVERT_TZ(gracenote.EndDateTime,'+00:00','.$tz.')
      WHEN 'Central Observing' THEN CONVERT_TZ(gracenote.EndDateTime,'+00:00','.$tz.')

      ELSE CONVERT_TZ(DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 3 day), ' 23:59:59'), '%Y-%m-%d %H:%i:%s'),'+00:00','.$tz.')
  end EndDateTimewithTimezon"),

      )
      ->from('gracenote')
      ->rightJoin('channels', 'gracenote.CallSign', '=', 'channels.callSign')
    
      ->whereRaw('gracenote.`StartDateTime` BETWEEN DATE_FORMAT(CONCAT(date_add(CURDATE(),interval -1 day), " 00:00:00"), "%Y-%m-%d %H:%i:%s") 
      AND DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 1 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")   or `channels`.`callSign` IS NULL')
       ->orderBy('gracenote.StartDateTime', 'asc');
        }, 'sub')
        ->where('sub.status','=','1')
    ->orderBy('sub.callSign', 'asc')
    ->groupBy('sub.callSign','StationName','image',$type,'id');
    return $record;
  }

  public function PacificFrontEpgchannelDaysSchedule(){
    /*

// $date = Carbon::parse('2018-08-19 10:43:15', 'UTC');echo "\n";
// echo($date->timezone->getName());echo "\n";
// echo($date->format('n/j/y g:i A'));echo "\n";

// $date->setTimezone('America/Denver');

// echo($date->timezone->getName());echo "\n";
// echo($date->format('n/j/y g:i A'));
// exit;
// $now = Carbon\Carbon::now('PST')->setTimezone('UTC')->addHours(2);
$now = Carbon::now();
// $now->tz('America/Los_Angeles'); //pacific time
// $now->tz('America/New_York'); //eastern time
$now->tz('America/Chicago'); //central time
echo "$now\n";
$this->isUtc($now);

echo "Offset hours: {$now->getOffsetString()}\n";

echo "******************************\n";

$now->tz('UTC');
echo "$now\n";
$this->isUtc($now);
echo "Offset hours2: {$now->offsetHours}\n";
$dtNewYork = Carbon::now()->tz('America/New_York');
$Los_Angeles = Carbon::parse($dtNewYork, 'America/Los_Angeles');
echo "\nActual LA time".Carbon::now()->tz('America/Los_Angeles');
echo "\ndtNewYork".$dtNewYork;
echo "\ndLos_Angeles".$Los_Angeles;
echo "\nLosangel-newyork ".$Los_Angeles->diffInHours($dtNewYork); 
echo "\nnewyork - LA ".$dtNewYork->diffInHours($Los_Angeles); 
echo "\nwithsignLosangel-newyork ".$Los_Angeles->diffInHours($dtNewYork,false); 
echo "\nwithsign newyork - LA".$dtNewYork->diffInHours($Los_Angeles,false); 

$LA=Carbon::now('America/Los_Angeles');
$NY=Carbon::now('America/New_York');
echo "\ndiffference".$NY->diffInHours($LA,false);
exit;
$dateUTC = Carbon::now();
$datePST = Carbon::now();
$datePST->setTimezone('America/Los_Angeles');
$dateUTC = Carbon::now('UTC');
$start  = Carbon::parse($dateUTC);
$end    = Carbon::parse($datePST);
$difference = $start->diffInHours($end, false); // set false to see the negative difference
echo "\ndateUTC".$dateUTC;
echo "\nPST".$datePST;
echo "\nStartUTC".$start;
echo "\nEndPST".$end;
// "\n".$datePST = Carbon::now('America/Los_Angeles');
// $difference = $dateUTC->diffInHours($datePST);
echo "\ndifference".$difference;
$startTime  = Carbon::parse('2018-09-18 21:12:35');
$finishTime = Carbon::parse($startTime)->addHours(10); 
$totalDuration = $startTime->diffInHours($finishTime);
echo "\ntotalDuration".$totalDuration;
exit;

echo "PST".Carbon::now('PST');
echo "Los".Carbon::now('America/Los_Angeles');
// echo "\nPacific".Carbon::now('US\Pacific');
echo "\nUTC".Carbon::now('UTC');
echo "\nDiff".Carbon::now('America/Los_Angeles')->diffInHours(Carbon::now('UTC')); exit;
**/
    $imagePath=url("/uploads/channellogo/");
    $input=url('livetv/');
    DB::statement(DB::raw('SET SESSION group_concat_max_len = @@max_allowed_packet'));
    $record  = Gracenote::select(["sub.id","sub.callSign","StationName AS name","genres",DB::raw("CONCAT('$imagePath/',`image`) icon")])
    ->selectRaw("CAST( CONCAT('[\"', REPLACE(GROUP_CONCAT(DISTINCT IFNULL(g.name, 'Others')), ',', '\",\"'), '\"]') AS JSON ) genrename")
    ->selectRaw(" concat( '[', group_concat(json_object( 'title', EventTitle,'start', (UNIX_TIMESTAMP(StartDateTimewithTimezon)*1000),'end', (UNIX_TIMESTAMP(EndDateTimewithTimezon)*1000) ) Order By StartDateTime ), ']') events")
    ->leftJoin('genre as g', DB::raw("FIND_IN_SET(g.id, sub.genres)"), DB::raw('>'),DB::raw('0'))
    ->fromSub(function ($query) {
        $query->select(DB::raw('IFNULL(gracenote.StationName,channels.name) StationName'),DB::raw('IFNULL(channels.callSign,channels.name) callSign'),
        DB::raw('IFNULL(gracenote.EventTitle,channels.name) EventTitle'),DB::raw('IFNULL(gracenote.StartDateTime,DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s") ) StartDateTime'),
        DB::raw('IFNULL(gracenote.EndDateTime,DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 3 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")) EndDateTime'),'channels.id','channels.genres',DB::raw('IFNULL(channels.image,"notfound.png") image'),DB::raw(" CASE TimeZoneName
        
        WHEN 'Eastern Observing' THEN CONVERT_TZ(StartDateTime,'US/Eastern','-03:00')
        WHEN 'Central Observing' THEN CONVERT_TZ(StartDateTime,'US/Central','+02:00')

        ELSE CONVERT_TZ(DATE_FORMAT(CONCAT(CURDATE(), ' 00:00:00'), '%Y-%m-%d %H:%i:%s'),'UTC','US/Pacific')
    end StartDateTimewithTimezon"),DB::raw(" CASE TimeZoneName
    
    WHEN 'Eastern Observing' THEN CONVERT_TZ(EndDateTime,'US/Eastern','-03:00')
    WHEN 'Central Observing' THEN CONVERT_TZ(EndDateTime,'US/Central','+02:00')

    ELSE CONVERT_TZ(DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 3 day), ' 23:59:59'), '%Y-%m-%d %H:%i:%s'),'UTC','US/Pacific')
end EndDateTimewithTimezon"),'channels.url')
        ->from('gracenote')
        ->rightJoin('channels', 'gracenote.CallSign', '=', 'channels.callSign')
       
    ->whereRaw('gracenote.`StartDateTime` BETWEEN DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s") 
    AND DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 3 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")   or `channels`.`callSign` IS NULL')
     ->orderBy('gracenote.StartDateTime', 'asc');
      }, 'sub')
    
    ->orderBy('sub.callSign', 'asc')
    ->groupBy('sub.callSign','StationName','image','sub.id','genres');
        
   $reco=$record->get()->map(function ($address) use ($input) {

                 
    $address['url'] = $input.'/'.$address['callSign'].'/'.$address['genrename']['0'] ;

    return $address;

});//exit; // echo $reco; exit;
         if ($reco->isEmpty()) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'Channel not found.',
                'response_code'=>404,
            ];
            return response()->json($response, 404);
        }  
    
            $response = [
                'success' => true,
                'date'=>date("Y-m-d"),
                'data' => $reco,
               
                'message' => 'Schedule data for 3 days found',
                'response_code'=>200,
            ];
           
        return response()->json($response, 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
     
        }
   //channel with 7 day of schdule
   public function oldchannelDaysSchedule(){
    //  SELECT * FROM `gracenote` WHERE `gracenote`.`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) ORDER BY `gracenote`.`StartDateTime` ASC
  //   select GROUP_CONCAT(EventTitle SEPARATOR ', ' ORDER BY StartDateTime ASC) programs,CallSign from gracenote WHERE `gracenote`.`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)  group BY CallSign ORDER by CallSign
  //   SELECT CallSign, JSON_ARRAYAGG( JSON_OBJECT( 'title', EventTitle, 'start', StartDateTime,'end', EndDateTime ) ) AS programs FROM gracenote GROUP BY CallSign
// select count(EventTitle),CallSign from gracenote WHERE `gracenote`.`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) group BY CallSign ORDER by CallSign
// select GROUP_CONCAT(EventTitle),CallSign from gracenote WHERE `gracenote`.`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) group BY CallSign ORDER by CallSign   
//$record  = Gracenote::select('StationName','CallSign','EventTitle','EventDescription','StartDateTime','EndDateTime')->whereRaw('`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)')->orderBy('CallSign', 'asc');
// SELECT CallSign,StationName,
      // JSON_ARRAYAGG(
      //     JSON_OBJECT(
      //         'title', EventTitle,
      //         'start', StartDateTime,'end', EndDateTime )
      // ) AS programs
//     FROM gracenote
//     GROUP BY CallSign,StationName
// $record  = Gracenote::select('CallSign',DB::raw("JSON_ARRAYAGG( JSON_OBJECT( 'title', EventTitle, 'start', StartDateTime,'end', EndDateTime ) ) AS programs"))
$imagePath=url("/uploads/channellogo/");

DB::statement(DB::raw('SET SESSION group_concat_max_len = @@max_allowed_packet'));
$record  = Gracenote::select("CallSign","id","StationName","url",DB::raw("CONCAT('$imagePath/',`image`) logo"))

->selectRaw(" concat( '[', group_concat(json_object( 'title', EventTitle,'start', UNIX_TIMESTAMP(StartDateTime),'end', UNIX_TIMESTAMP(EndDateTime) ) Order By StartDateTime ), ']') programs")
->fromSub(function ($query) {
  $query->select('gracenote.StationName','gracenote.CallSign','gracenote.EventTitle','gracenote.StartDateTime','gracenote.EndDateTime','channels.id','channels.image','channels.url')
  ->from('gracenote')
  ->join('channels', 'gracenote.CallSign', '=', 'channels.callSign')
  ->whereRaw('gracenote.`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 3 DAY)')
  ->orderBy('gracenote.StartDateTime', 'asc');
}, 'sub')

->orderBy('CallSign', 'asc')
->groupBy('CallSign','StationName','image','url','id');
// SELECT CallSign,StationName,
// GROUP_CONCAT(
//   JSON_OBJECT('title', EventTitle,
//     FROM gracenote
//     GROUP BY CallSign,StationName
// ->whereRaw('`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)')->orderBy('CallSign', 'asc');
  
$reco=$record->get();   
   if ($reco->isEmpty()) {
      $response = [
          'success' => false,
          'data' => 'Empty',
          'message' => 'Channel not found.',
          'response_code'=>404,
      ];
      return response()->json($response, 404);
  }  
      // return response()->json($record->toSql());
      // return json_encode($api);

      $response = [
          'success' => true,
          'date'=>date("Y-m-d"),
          'data' => $reco,
         
          'message' => 'Schedule data for 3 days found',
          'response_code'=>200,
      ];
      // return response()->json($reco);
     
  return response()->json($response, 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
  //  return  response()->json([
  //     'clients' => $this->transformClientCollection($api)
  //  ], 200);
  }
}


// select `sub`.`callSign` as `callSign`, `sub`.`id`, `StationName` as `stationName`, `dash_url` as `url`,
// CONCAT('http://127.0.0.1:8000/uploads/channellogo/',`image`) logo,
// CONCAT('[',GROUP_CONCAT(DISTINCT(QUOTE(IFNULL(genre.name,'Others')))),']') genrename, concat( '[',
// group_concat(json_object( 'title', EventTitle,'description', EventDescription,'start',
// UNIX_TIMESTAMP(StartDateTime),'end', UNIX_TIMESTAMP(EndDateTime) ) Order By StartDateTime ), ']') programs from (select
// IFNULL(gracenote.StationName,channels.name) StationName, IFNULL(channels.callSign,channels.name) callSign,
// IFNULL(gracenote.EventTitle,channels.name) EventTitle, IFNULL(gracenote.EventDescription,"EventDescription")
// EventDescription, IFNULL(gracenote.StartDateTime,DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s"))
// StartDateTime, IFNULL(gracenote.EndDateTime,DATE_FORMAT(CONCAT(CURDATE(), " 23:59:59"), "%Y-%m-%d %H:%i:%s"))
// EndDateTime, `channels`.`id`, `channels`.`status`, IFNULL(channels.image,"notfound.png") image, `channels`.`dash_url`
// from `gracenote` right join `channels` on `gracenote`.`CallSign` = `channels`.`callSign` where gracenote.`StartDateTime`
// BETWEEN DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s")
// AND DATE_FORMAT(CONCAT(CURDATE(), " 23:59:59"), "%Y-%m-%d %H:%i:%s") or `channels`.`callSign` IS NULL order by
// `gracenote`.`StartDateTime` asc) as `sub` left join `channels_genre` on `sub`.`id` = `channels_genre`.`channels_id` left
// join `genre` on `channels_genre`.`genre_id` = `genre`.`id` where `sub`.`status` = 1 and genre.name in ('Sports') group by
// `sub`.`callSign`, `StationName`, `image`, `dash_url`, `id` order by `sub`.`callSign` asc

// select `sub`.`callSign` as `callSign`, `sub`.`id`, `StationName` as `stationName`, `dash_url` as `url`,
// CONCAT('http://127.0.0.1:8000/uploads/channellogo/',`image`) logo,
// CONCAT('[',GROUP_CONCAT(DISTINCT(QUOTE(IFNULL(genre.name,'Others')))),']') genrename, concat( '[',
// group_concat(json_object( 'title', EventTitle,'description', EventDescription,'start',
// UNIX_TIMESTAMP(StartDateTime),'end', UNIX_TIMESTAMP(EndDateTime) ) Order By StartDateTime ), ']') programs from (select
// IFNULL(gracenote.StationName,channels.name) StationName, IFNULL(channels.callSign,channels.name) callSign,
// IFNULL(gracenote.EventTitle,channels.name) EventTitle, IFNULL(gracenote.EventDescription,"EventDescription")
// EventDescription, IFNULL(gracenote.StartDateTime,DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s"))
// StartDateTime, IFNULL(gracenote.EndDateTime,DATE_FORMAT(CONCAT(CURDATE(), " 23:59:59"), "%Y-%m-%d %H:%i:%s"))
// EndDateTime, `channels`.`id`, `channels`.`status`, IFNULL(channels.image,"notfound.png") image, `channels`.`dash_url`
// from `gracenote` right join `channels` on `gracenote`.`CallSign` = `channels`.`callSign` where gracenote.`StartDateTime`
// BETWEEN DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s")
// AND DATE_FORMAT(CONCAT(CURDATE(), " 23:59:59"), "%Y-%m-%d %H:%i:%s") or `channels`.`callSign` IS NULL order by
// `gracenote`.`StartDateTime` asc) as `sub` left join `channels_genre` on `sub`.`id` = `channels_genre`.`channels_id` left
// join `genre` on `channels_genre`.`genre_id` = `genre`.`id` where `sub`.`status` = 1  and `channels_genre`.`channels_id` IS NULL group by
// `sub`.`callSign`, `StationName`, `image`, `dash_url`, `id`  
// ORDER BY `genrename` ASC