<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Gracenote;
use Illuminate\Support\Facades\DB;
use App\Models\Channel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
class TizenEPGController extends Controller
{
    //channel search
    public function channelSearchSchedule(Request $request){
      $search = request()->get('search');
      $StartTime = request()->get('StartTime');
      $record=Gracenote::select('gracenote.EventTitle as Title','gracenote.EventType as CatType','gracenote.EventType as Category',DB::raw('DATE_FORMAT(gracenote.StartDateTime,"%Y-%m-%dT%TZ") StartTime'),DB::raw('DATE_FORMAT(gracenote.EndDateTime,"%Y-%m-%dT%TZ") EndTime'))
      ->selectRaw("JSON_OBJECT('ChanId', concat(channels.id,''), 'CallSign', concat(channels.callSign,''),'ChanNum', concat(channels.id,'')) as Channel")
      ->whereRaw('gracenote.`StartDateTime` BETWEEN ? AND DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 1 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")   AND `gracenote`.`EventTitle` LIKE "%'.$search.'%"',$StartTime)
      ->rightJoin('channels', 'gracenote.CallSign', '=', 'channels.callSign');
      $reco=$record->get();
      // $reco=$record->toSql(); echo $reco;exit; 
      

      $a=["AsOf"=> substr(date(DATE_ISO8601), 0, -5).'Z', "Version"=> "0.28", "Count"=>strval( $reco->count()),
      'Programs' => $reco];
      $response = [
            
          'ProgramList' => $a,
         
      ];
     
  return response()->json($response, 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
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

$type='dash_url';
$record = $this->channelDaysScheduleFunc($type);
    
    

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
$reco=$record->get(); //echo $reco;exit; 
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
$a=["AsOf"=> substr(date(DATE_ISO8601), 0, -5).'Z', "Version"=> "0.28",'Channels' => $reco];
      $response = [
          
        //   'count' => $reco->count(),
        
          'ProgramGuide' => $a,
        //   'Channels' => $reco,
         
      ];
      // return response()->json($reco);
     
  return response()->json($response, 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
  //  return  response()->json([
  //     'clients' => $this->transformClientCollection($api)
  //  ], 200);
  }     
//channel with 7 day of schdule
public function channelDaysScheduleV2(Request $request){

$reco= Cache::remember('channelscheduletizen',60*60*24, function () {
  $type='dash_url';
  return $this->channelDaysScheduleFuncV2($type)->get();});
  
// $reco=$record->toSql(); echo $reco;exit; 
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
$a=["AsOf"=> substr(date(DATE_ISO8601), 0, -5).'Z', "Version"=> "0.28",'Channels' => $reco];
      $response = [
          
        //   'count' => $reco->count(),
        
          'ProgramGuide' => $a,
        //   'Channels' => $reco,
         
      ];
      // return response()->json($reco);
     
    $responsejson=json_encode($response);
    $data=gzencode($responsejson,9);
    return response($data)->withHeaders([
      'Access-Control-Allow-Origin' => '*',
      'Access-Control-Allow-Methods'=> 'GET',            
      'Content-type' => 'application/json; charset=utf-8',
      'Content-Length'=> strlen($data),
      'Content-Encoding' => 'gzip'
  ]);
  // return response()->json($response, 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
  //  return  response()->json([
  //     'clients' => $this->transformClientCollection($api)
  //  ], 200);
  }

  public function channelDaysnew(Request $request){
  
 
    $type='dash_url';
    $timeoffset=$request->get('hoursoffset');
    $reco= $this->channelDaysScheduleFuncV2offsetnew($type,$timeoffset)->get();
 
  
     
  if ($reco->isEmpty()) {
    $response = [
        'success' => false,
        'data' => 'Empty',
        'message' => 'Channel not found.',
        'response_code'=>404,
    ];
    return response()->json($response, 404);
  }  
  

  $a=["AsOf"=> substr(date(DATE_ISO8601), 0, -5).'Z', "Version"=> "0.28",'Channels' => $reco];
        $response = [
      
            'ProgramGuide' => $a,
       
        ];
       
      $responsejson=json_encode($response);
      $data=gzencode($responsejson,9);
      return response($data)->withHeaders([
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods'=> 'GET',            
        'Content-type' => 'application/json; charset=utf-8',
        'Content-Length'=> strlen($data),
        'Content-Encoding' => 'gzip'
    ]);
  }

  public function channelDaysScheduleFuncV2offsetnew($type,$timeoffset){
    $tz=$timeoffset??'-07:00';
    if (str_contains($tz, '-')) { 
    }else
    {
        $tz = "+".$tz;
    }
    $imagePath=url("/uploads/channellogo/");
    DB::statement(DB::raw('SET SESSION group_concat_max_len = @@max_allowed_packet'));
    $record  = Gracenote::select("sub.callSign as CallSign","sub.genre_name as genre_name",
    DB::raw('concat(id,"") ChanId') ,DB::raw('concat(id,"") ChanNum'),"StationName","$type as url",
    DB::raw("CONCAT('$imagePath/',`image`) IconURL"))
    ->selectRaw(" concat( '[', group_concat(json_object( 'Title', EventTitle,'Description', EventDescription,'CatType', CatType,'Category', Category ,'StartTime',DATE_FORMAT(StartDateTimewithTimezon, '%Y-%m-%dT%TZ'),'EndTime',DATE_FORMAT(EndDateTimewithTimezon, '%Y-%m-%dT%TZ'),'TimeZoneName',TimeZoneName ) Order By StartDateTime ), ']') Programs")

    ->fromSub(function ($query) use ($type,$tz) {
      $query->select(DB::raw('DISTINCT IFNULL(genre.name,"Others") genre_name'),
      DB::raw('IFNULL(gracenote.StationName,concat(channels.name)) StationName'),
      DB::raw('IFNULL(channels.callSign,channels.name) callSign'),
      DB::raw('IFNULL(gracenote.EventTitle,channels.name) EventTitle'),
      DB::raw('IFNULL(gracenote.EventDescription,"EventDescription") EventDescription'),
      DB::raw('IFNULL(gracenote.StartDateTime, DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s")) StartDateTime'),
            DB::raw('IFNULL(gracenote.EndDateTime,DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 1 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")) EndDateTime'),
            DB::raw('IFNULL(gracenote.TimeZoneName,"TimeZoneName") TimeZoneName'),
            'channels.id','channels.status',
          
            DB::raw(" CASE gracenote.TimeZoneName
            WHEN 'Pacific Observing' THEN CONVERT_TZ(gracenote.StartDateTime,'+00:00','$tz')
            WHEN 'Eastern Observing' THEN CONVERT_TZ(gracenote.StartDateTime,'+00:00','$tz')
            WHEN 'Central Observing' THEN CONVERT_TZ(gracenote.StartDateTime,'+00:00','$tz')
            ELSE CONVERT_TZ(DATE_FORMAT(CONCAT(CURDATE(), ' 00:00:00'), '%Y-%m-%d %H:%i:%s'),'+00:00','$tz') end StartDateTimewithTimezon"),
            DB::raw(" CASE gracenote.TimeZoneName
            WHEN 'Pacific Observing' THEN CONVERT_TZ(gracenote.EndDateTime,'+00:00','$tz')
            WHEN 'Eastern Observing' THEN CONVERT_TZ(gracenote.EndDateTime,'+00:00','$tz')
            WHEN 'Central Observing' THEN CONVERT_TZ(gracenote.EndDateTime,'+00:00','$tz')
            ELSE CONVERT_TZ(DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 1 day), ' 23:59:59'), '%Y-%m-%d %H:%i:%s'),'+00:00','$tz') end EndDateTimewithTimezon"),
      DB::raw("'series' as Category"),
      DB::raw("'series' as CatType"),
      DB::raw('IFNULL(channels.image,"notfound.png") image'),"channels.$type")
      ->from('gracenote')
      ->rightJoin('channels', 'gracenote.CallSign', '=', 'channels.callSign')
    
   ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
     ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
      ->whereRaw('gracenote.`StartDateTime` 
      BETWEEN DATE_FORMAT(DATE_ADD(CONCAT(CURDATE(), " 00:00:00"),interval -12 hour), "%Y-%m-%d %H:%i:%s") 
      AND DATE_FORMAT(CONCAT(CURDATE(), " 23:59:59"), "%Y-%m-%d %H:%i:%s")
         or `channels`.`callSign` IS NULL')
       ->orderBy('gracenote.StartDateTime', 'asc');
        }, 'sub')
        ->where('sub.status','=','1')
    ->orderBy('id', 'asc')
    ->groupBy('sub.callSign','StationName','image',$type,'id','sub.genre_name');


    return $record;
  }


  public function channelDaysScheduleV2offset(Request $request){
  
 
    $type='dash_url';
    $timeoffset=$request->get('hoursoffset');
    $reco= $this->channelDaysScheduleFuncV2offset($type,$timeoffset)->get();
 
  
     
  if ($reco->isEmpty()) {
    $response = [
        'success' => false,
        'data' => 'Empty',
        'message' => 'Channel not found.',
        'response_code'=>404,
    ];
    return response()->json($response, 404);
  }  
  

  $a=["AsOf"=> substr(date(DATE_ISO8601), 0, -5).'Z', "Version"=> "0.28",'Channels' => $reco];
        $response = [
      
            'ProgramGuide' => $a,
       
        ];
       
      $responsejson=json_encode($response);
      $data=gzencode($responsejson,9);
      return response($data)->withHeaders([
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods'=> 'GET',            
        'Content-type' => 'application/json; charset=utf-8',
        'Content-Length'=> strlen($data),
        'Content-Encoding' => 'gzip'
    ]);
  }

  public function channelDaysScheduleFuncV2offset($type,$timeoffset){
    $tz=$timeoffset??'-07:00';
    if (str_contains($tz, '-')) { 
    }else
    {
        $tz = "+".$tz;
    }
    $imagePath=url("/uploads/channellogo/");
    DB::statement(DB::raw('SET SESSION group_concat_max_len = @@max_allowed_packet'));
    $record  = Gracenote::select("sub.callSign as CallSign","sub.genre_name as genre_name",
    DB::raw('concat(id,"") ChanId') ,DB::raw('concat(id,"") ChanNum'),"StationName","$type as url",
    DB::raw("CONCAT('$imagePath/',`image`) IconURL"))
    ->selectRaw(" concat( '[', group_concat(json_object( 'Title', EventTitle,'Description', EventDescription,'CatType', CatType,'Category', Category ,'StartTime',DATE_FORMAT(StartDateTimewithTimezon, '%Y-%m-%dT%TZ'),'EndTime',DATE_FORMAT(EndDateTimewithTimezon, '%Y-%m-%dT%TZ'),'TimeZoneName',TimeZoneName ) Order By StartDateTime ), ']') Programs")

    ->fromSub(function ($query) use ($type,$tz) {
      $query->select(DB::raw('DISTINCT IFNULL(genre.name,"Others") genre_name'),
      DB::raw('IFNULL(gracenote.StationName,concat(channels.name)) StationName'),
      DB::raw('IFNULL(channels.callSign,channels.name) callSign'),
      DB::raw('IFNULL(gracenote.EventTitle,channels.name) EventTitle'),
      DB::raw('IFNULL(gracenote.EventDescription,"EventDescription") EventDescription'),
      DB::raw('IFNULL(gracenote.StartDateTime, DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s")) StartDateTime'),
            DB::raw('IFNULL(gracenote.EndDateTime,DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 1 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")) EndDateTime'),
            DB::raw('IFNULL(gracenote.TimeZoneName,"TimeZoneName") TimeZoneName'),
            'channels.id','channels.status',
          
            DB::raw(" CASE gracenote.TimeZoneName
            WHEN 'Pacific Observing' THEN CONVERT_TZ(gracenote.StartDateTime,'+00:00','$tz')
            WHEN 'Eastern Observing' THEN CONVERT_TZ(gracenote.StartDateTime,'+00:00','$tz')
            WHEN 'Central Observing' THEN CONVERT_TZ(gracenote.StartDateTime,'+00:00','$tz')
            ELSE CONVERT_TZ(DATE_FORMAT(CONCAT(CURDATE(), ' 00:00:00'), '%Y-%m-%d %H:%i:%s'),'+00:00','$tz') end StartDateTimewithTimezon"),
            DB::raw(" CASE gracenote.TimeZoneName
            WHEN 'Pacific Observing' THEN CONVERT_TZ(gracenote.EndDateTime,'+00:00','$tz')
            WHEN 'Eastern Observing' THEN CONVERT_TZ(gracenote.EndDateTime,'+00:00','$tz')
            WHEN 'Central Observing' THEN CONVERT_TZ(gracenote.EndDateTime,'+00:00','$tz')
            ELSE CONVERT_TZ(DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 1 day), ' 23:59:59'), '%Y-%m-%d %H:%i:%s'),'+00:00','$tz') end EndDateTimewithTimezon"),
      DB::raw("'series' as Category"),
      DB::raw("'series' as CatType"),
      DB::raw('IFNULL(channels.image,"notfound.png") image'),"channels.$type")
      ->from('gracenote')
      ->rightJoin('channels', 'gracenote.CallSign', '=', 'channels.callSign')
    
   ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
     ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
      ->whereRaw('gracenote.`StartDateTime` 
      BETWEEN DATE_FORMAT(DATE_ADD(CONCAT(CURDATE(), " 00:00:00"),interval -12 hour), "%Y-%m-%d %H:%i:%s") 
      AND DATE_FORMAT(CONCAT(CURDATE(), " 23:59:59"), "%Y-%m-%d %H:%i:%s")
         or `channels`.`callSign` IS NULL')
       ->orderBy('gracenote.StartDateTime', 'asc');
        }, 'sub')
        ->where('sub.status','=','1')
    ->orderBy('id', 'asc')
    ->groupBy('sub.callSign','StationName','image',$type,'id','sub.genre_name');


    return $record;
  }

  public function channelDaysScheduleFuncV2($type){
    $imagePath=url("/uploads/channellogo/");
    DB::statement(DB::raw('SET SESSION group_concat_max_len = @@max_allowed_packet'));
    $record  = Gracenote::select("sub.callSign as CallSign","sub.genre_name as genre_name",
    DB::raw('concat(id,"") ChanId') ,DB::raw('concat(id,"") ChanNum'),"StationName","$type as url",
    DB::raw("CONCAT('$imagePath/',`image`) IconURL"))
    ->selectRaw(" concat( '[', group_concat(json_object( 'Title', EventTitle,'Description', EventDescription,'CatType', CatType,'Category', Category,'StartTime', DATE_FORMAT(StartDateTime, '%Y-%m-%dT%TZ'),'EndTime', DATE_FORMAT(EndDateTime, '%Y-%m-%dT%TZ') ) Order By StartDateTime ), ']') Programs")
    
    ->fromSub(function ($query) use ($type) {
      // $query->select(DB::raw('IFNULL(genre.name,"Others") genre_name'),DB::raw('IFNULL(gracenote.StationName,concat("z",channels.name)) StationName'),
      $query->select(DB::raw('IFNULL(genre.name,"Others") genre_name'),DB::raw('IFNULL(gracenote.StationName,concat(channels.name)) StationName'),
      DB::raw('IFNULL(channels.callSign,channels.name) callSign'),
      DB::raw('IFNULL(gracenote.EventTitle,channels.name) EventTitle'),DB::raw('IFNULL(gracenote.EventDescription,"EventDescription") EventDescription'),
      DB::raw('IFNULL(gracenote.StartDateTime,DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s")) StartDateTime'),
      DB::raw('IFNULL(gracenote.EndDateTime,DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 3 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")) EndDateTime'),'channels.id','channels.status',DB::raw("'series' as Category"),DB::raw("'series' as CatType"),DB::raw('IFNULL(channels.image,"notfound.png") image'),"channels.$type")
      ->from('gracenote')
      ->rightJoin('channels', 'gracenote.CallSign', '=', 'channels.callSign')
    
   ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
     ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
      ->whereRaw('gracenote.`StartDateTime` BETWEEN DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s") 
      AND DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 1 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")   or `channels`.`callSign` IS NULL')
       ->orderBy('gracenote.StartDateTime', 'asc');
        }, 'sub')
        ->where('sub.status','=','1')
    ->orderBy('id', 'asc')
    ->groupBy('sub.callSign','StationName','image',$type,'id','sub.genre_name');


    return $record;
  }

    public function channelDaysScheduleFunc($type){
        $imagePath=url("/uploads/channellogo/");
        DB::statement(DB::raw('SET SESSION group_concat_max_len = @@max_allowed_packet'));
        $record  = Gracenote::select("sub.callSign as CallSign",DB::raw('concat(id,"") ChanId') ,DB::raw('concat(id,"") ChanNum'),"StationName","$type as url",DB::raw("CONCAT('$imagePath/',`image`) IconURL"))
        ->selectRaw(" concat( '[', group_concat(json_object( 'Title', EventTitle,'Description', EventDescription,'CatType', CatType,'Category', Category,'StartTime', DATE_FORMAT(StartDateTime, '%Y-%m-%dT%TZ'),'EndTime', DATE_FORMAT(EndDateTime, '%Y-%m-%dT%TZ') ) Order By StartDateTime ), ']') Programs")
        ->fromSub(function ($query) use ($type) {
          $query->select(DB::raw('IFNULL(gracenote.StationName,channels.name) StationName'),DB::raw('IFNULL(channels.callSign,channels.name) callSign'),
          DB::raw('IFNULL(gracenote.EventTitle,channels.name) EventTitle'),DB::raw('IFNULL(gracenote.EventDescription,"EventDescription") EventDescription'),
          DB::raw('IFNULL(gracenote.StartDateTime,DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s")) StartDateTime'),
          DB::raw('IFNULL(gracenote.EndDateTime,DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 3 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")) EndDateTime'),'channels.id','channels.status',DB::raw("'series' as Category"),DB::raw("'series' as CatType"),DB::raw('IFNULL(channels.image,"notfound.png") image'),"channels.$type")
          ->from('gracenote')
          ->rightJoin('channels', 'gracenote.CallSign', '=', 'channels.callSign')
        
          ->whereRaw('gracenote.`StartDateTime` BETWEEN DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s") 
          AND DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 1 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")   or `channels`.`callSign` IS NULL')
           ->orderBy('gracenote.StartDateTime', 'asc');
            }, 'sub')
            ->where('sub.status','=','1')
        ->orderBy('sub.callSign', 'asc')
        ->groupBy('sub.callSign','StationName','image',$type,'id');
        return $record;
      }
}
// https://www.xmltvlistings.com/help/guides/mythtv
// open new video souce 
// add new souce
// tv_find_grabbers
// tv_grab_au
// tv_grab_eu_xmltvse
// tv_grab_au --configure --config-file $HOME/.mythtv/AusEPG.xmltv
// http://wiki.xmltv.org/index.php/HowtoUseGrabbersh
// tv_grab_au --configure --config-file /home/myuser/.myapp/tv_grab_au.conf
// tv_grab_eu_xmltvse --configure --config-file $HOME/.mythtv/tv_grab_eu_xmltvse.conf
// $ > tv_grab_eu_xmltvse --config-file $HOME/.mythtv/tv_grab_eu_xmltvse.conf --days 3 --quiet
// tv_grab_uk_tvguide  --configure --config-file $HOME/.mythtv/tv_grab_uk_tvguide.conf

// tv_grab_uk_tvguide --config-file $HOME/.mythtv/UKEPG.conf --days 5 --quiet
// /bin/tv_grab_eu_xmltvseN
//epg data won't show in mythtv frontend https://forum.mythtv.org/viewtopic.php?t=599
//http://127.0.0.1/mythweb/tv/list
// http://127.0.0.1/mythling/media.php?type=guide&StartTime=2021-04-21T09:30:00.000Z&EndTime=2021-04-21T21:30:00.000Z&showQuery=true
/**
1. First install mythtv control panel 
2. install mythweb
3. use grabber
4. mAKE VIDEO SOURCE
5. CONFIFURE XML FILE(MAKE SURE VIDEO SOURCE NAME IS SIMILAR)
-CHOOSE THE CHANNELS
6. CONFIGURE DAYS 
7. EDIT VIDEO SOURCE RUN THE MYTHFILLDB
/bin/tv_grab_au --configure --config-file $HOME/.mythtv/AUSEPG.xmltv 
*/