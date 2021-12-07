<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Http\Request;
use App\Models\Channel;
use App\Models\Genre;
use App\Models\Gracenote;
use Illuminate\Support\Facades\DB;
use Validator;

use Illuminate\Support\Facades\Auth;
class EpgController extends Controller
{
    protected function setUp()
    {
        parent::setUp();
        $this->withoutMiddleware(
            ThrottleRequests::class
        );
    }

    public function channellist(Request $request) 
    { 
        // http://ams-api.astro.com.my/ams/v3/getChannelList
        
        $type='url';
$record = $this->channeljsonFunc($type);
$pagechannel=$record->get()->map(function ($message, $key) {
        
    $message->genrename = json_decode($message->genrename, true);


return $message;
});
 //  return response()->json($record->toSql());
 $response = [
    'responseMessage' => 'Success',
    'responseCode'=>'200',
    'channels'=>$pagechannel, 
    
];
return response()->json($response, 200);
    }

    public function channeljsonFunc($type){
  
    $second=Channel::toBase()->select('channels.id as channelId',DB::raw('IFNULL(channels.stationName,channels.name) channelTitle'),'channels.id as channelStbNumber',DB::raw('IFNULL(channels.callSign,channels.name) CallSign'))
    // select('channels.id',DB::raw('IFNULL(channels.callSign,channels.name) callSign'), 'channels.image',"channels.$type as url")
           
        ->selectRaw("CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(genre.name, 'Others')), ',', '\",\"'), '\"]')  genrename")
           
           ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
           ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
    
    ->where('channels.status','=','1')
    ->groupBy('channels.id')
    ->orderBy('channels.name', 'asc');
    return $second;
    }
    public function programelist(Request $request) {
/**
 * SELECT Concat(ifnull(gracenote.id,'1'), "")            eventID,
       `channels`.`id`                     AS `channelId`,
       Concat(channels.id, "")             channelStbNumber,
       'false'                             AS channelHD,
       ifnull(`gracenote`.`stationname`,channels.name)           AS `channelTitle`,
       'null'                              AS epgEventImage,
       gracenote.rating                    AS certification,
       `startdatetime`                     AS `displayDateTimeUtc`,
       `startdatetime`                     AS `displayDateTime`,
       Timediff(enddatetime, startdatetime)AS displayDuration,
       '1:964:41911209'                    AS siTrafficKey,
       `eventtitle`                        AS `programmeTitle`,
       `gracenote`.`eventid`               AS `programmeId`,
       Concat(gracenote.eventid, "p")      programmeId,
       Concat(gracenote.eventid, "e")      episodeId,
       `eventdescription`                  AS `shortSynopsis`,
       'null'                              AS longSynopsis,
       ''                                  AS actors,
       ''                                  AS directors,
       ''                                  AS producers,
       gracenote.eventtype                 AS genre,
       ''                                  AS subGenre,
       false                               AS live,
       false                               AS premier,
       false                               AS ottBlackout,
       NULL                                AS highlight,
       'false'                             AS contentId,
       'false'                             AS contentImage,
       'null'                              AS groupKey,
       NULL                                AS vernacularData
FROM   `gracenote`
       RIGHT JOIN `channels`
               ON `gracenote`.`callsign` = `channels`.`callsign`
WHERE  channels.status = '1'
       AND `channels`.`id` IN ( '116' ) AND
       (CASE WHEN `gracenote`.`callsign` = `channels`.`callsign` 
                                   THEN `gracenote`.`startdatetime` BETWEEN
           '2021-10-06 18:00' AND '2021-10-06 23:59'
                                   ELSE 1
                               END)
        
ORDER  BY `programmetitle` ASC
 */




         $grace= Gracenote::toBase()->select('gracenote.StationName as channelTitle',DB::raw("TIMEDIFF(EndDateTime,StartDateTime)AS displayDuration"),'channels.id as channelStbNumber',
         'channels.id as channelId','EventTitle as programmeTitle','StartDateTime as displayDateTime','EventDescription as shortSynopsis')
         ->addSelect(DB::raw("'fakeValue' as fakeColumn"),DB::raw("'fakeValue2' as fakeColumn2"),
         DB::raw("'fakeValue3' as fakeColumn3"),DB::raw("null as epgEventImage"),DB::raw("'' as subGenre"),DB::raw("'' as actors"))
         ->join('channels', 'gracenote.CallSign', '=', 'channels.callSign')
        //  select('channelId','channelStbNumber','StationName as channelTitle')
         //,'epgEventImage','programmeTitle','genre','StartDateTime as displayDateTime','epgEventImage','EventDescription as shortSynopsis','subGenre','actors','directors')
        //  ->selectRaw('TIMEDIFF("StartDateTime", "EndDateTime") as displayDuration','epgEventImage as epgEventImage')
        //  ->take(5)
         ->whereRaw('gracenote.`StartDateTime` BETWEEN DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s") 
    AND DATE_FORMAT(CONCAT(CURDATE(), " 23:59:59"), "%Y-%m-%d %H:%i:%s")   or `channels`.`callSign` IS NULL')
     ->orderBy('gracenote.StartDateTime', 'asc')
     ->where('channels.status','=','1')
      ->get();
        //  dd($grace);
         $response = [
            'responseMessage' => 'Success',
            'responseCode'=>'200',
            'getevent'=>$grace, 
            // 'count'=>$pagechannel->count(),
            
        ];
        return response()->json($response, 200);
        }

    public function favoritelist(Request $request) {
        

    $myFavorites = Auth::user()->favorites->pluck('id');
        //  dd($myFavorites);
         $response = [
            'responseMessage' => 'Success',
            'responseCode'=>'200',
            'favorites'=>$myFavorites, 
            // 'count'=>$pagechannel->count(),
            
        ];
        return response()->json($response, 200);
        }
        public function programelistwithid(Request $request) {
            // http://192.168.0.117:3000/getChannelTVGuide
            // ?channelId=86,80,182,64,87,114,65,66,172,92,235,400,84,242,265&
            // periodStart=2021-02-01 15:00&periodEnd=2021-02-01 23:59
            // http://ams-api.astro.com.my/ams/v3/getEvents?channelId=275,80,182,64,87,114,65&periodStart=2021-02-02%2015:00&periodEnd=2021-02-02%2023:59
            $id = $request->input('id');
	

            /* returns array of entire input query value */
            $query = $request->all();
            $str_arr = explode (",", $query['channelId']);  
             
             
            $ids = [1,2,3];
            $validator = Validator::make($query, [
                'channelId' => 'required',
                'periodStart' => 'required|date',
                'periodEnd' => 'required|date'
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
            // $ids = $str_arr;
            // dump($ids);
            // dd($str_arr);

/*** 
 * SELECT Concat(ifnull(gracenote.id,'1'), "")            eventID,
       `channels`.`id`                     AS `channelId`,
       Concat(channels.id, "")             channelStbNumber,
       'false'                             AS channelHD,
       ifnull(`gracenote`.`stationname`,channels.name)           AS `channelTitle`,
       'null'                              AS epgEventImage,
       gracenote.rating                    AS certification,
       ifnull(`startdatetime`,'2021-10-06 18:00')                     AS `displayDateTimeUtc`,
       ifnull(`startdatetime`,'2021-10-06 18:00')                    AS `displayDateTime`,
       Timediff(ifnull(enddatetime,'2021-10-06 23:59'), ifnull(startdatetime,'2021-10-06 18:00'))AS displayDuration,
       '1:964:41911209'                    AS siTrafficKey,
       ifnull(`eventtitle`,channels.name)                         AS `programmeTitle`,
       ifnull(`gracenote`.`eventid`,'1')               AS `programmeId`,
       Concat(gracenote.eventid, "p")      programmeId,
       Concat(gracenote.eventid, "e")      episodeId,
       ifnull(`eventdescription`,'description')                  AS `shortSynopsis`,
       'null'                              AS longSynopsis,
       ''                                  AS actors,
       ''                                  AS directors,
       ''                                  AS producers,
       ifnull(gracenote.eventtype ,'ep')                AS genre,
       ''                                  AS subGenre,
       false                               AS live,
       false                               AS premier,
       false                               AS ottBlackout,
       NULL                                AS highlight,
       'false'                             AS contentId,
       'false'                             AS contentImage,
       'null'                              AS groupKey,
       NULL                                AS vernacularData
FROM   `gracenote`
       RIGHT JOIN `channels`
               ON `gracenote`.`callsign` = `channels`.`callsign`
WHERE  channels.status = '1'
       AND `channels`.`id` IN ( '116' ) AND
       (CASE WHEN `gracenote`.`callsign` = `channels`.`callsign` 
                                   THEN `gracenote`.`startdatetime` BETWEEN
           '2021-10-06 18:00' AND '2021-10-06 23:59'
                                   ELSE 1
                               END)
        
ORDER  BY `programmetitle` ASC*/ 






// DB::raw('IFNULL(gracenote.EventTitle,channels.name) programmeTitle')
// DB::raw('IFNULL(gracenote.EventDescription,channels.name) shortSynopsis')
// DB::raw('IFNULL(gracenote.StationName,name) channelTitle')
// DB::raw('IFNULL(gracenote.Rating,"TV14") certification')
// DB::raw('IFNULL(gracenote.EventID,"1") programmeId')
// ifnull(`startdatetime`,'2021-10-06 18:00')                     AS `displayDateTimeUtc`,

            $users = Gracenote::toBase()->select(DB::raw('concat(ifnull(gracenote.id,"1"),"") eventID'),'channels.id as channelId',
            DB::raw('concat(channels.id,"") channelStbNumber'),DB::raw("'false' as channelHD"),DB::raw('IFNULL(gracenote.StationName,channels.name) channelTitle'),
            DB::raw("'null' as epgEventImage"),
            DB::raw('IFNULL(gracenote.Rating,"TV14") certification'),DB::raw('IFNULL(gracenote.StartDateTime,"'.$query['periodStart'].'") displayDateTimeUtc'),DB::raw('IFNULL(gracenote.StartDateTime,"'.$query['periodStart'].'") displayDateTime'),
            DB::raw("TIMEDIFF(IFNULL(EndDateTime,'".$query['periodEnd']."'),IFNULL(StartDateTime,'".$query['periodStart']."'))AS displayDuration"),DB::raw("'1:964:41911209' as siTrafficKey"),
            DB::raw('IFNULL(gracenote.EventTitle,channels.name) programmeTitle'),DB::raw('IFNULL(gracenote.EventID,"1") programmeId'),DB::raw('concat(IFNULL(gracenote.EventID,"1"),"p") programmeId'),
            DB::raw('concat(IFNULL(gracenote.EventID,"1"),"e") episodeId'),DB::raw('IFNULL(gracenote.EventDescription,channels.name) shortSynopsis'))
            ->addSelect(DB::raw("'null' as longSynopsis"),DB::raw("'' as actors"),DB::raw("'' as directors"),
            DB::raw("'' as producers"),DB::raw("gracenote.EventType as genre"),DB::raw("'' as subGenre"),DB::raw("false as live"),
            DB::raw("false as premier"),DB::raw("false as ottBlackout"),DB::raw("null as highlight"),
            DB::raw("'false' as contentId"),DB::raw("'false' as contentImage"),DB::raw("'null' as groupKey"),DB::raw("null as vernacularData"))
            
           
            // Channel::select('gracenote.')
            ->whereRaw('channels.status = ?','1')
            ->wherein('channels.id',$str_arr)
            
            ->whereRaw("(CASE WHEN `gracenote`.`callsign` = `channels`.`callsign` 
                                   THEN `gracenote`.`startdatetime` BETWEEN
           '".$query['periodStart']."' AND '".$query['periodEnd']."'
                                   ELSE 1
                               END)")
            // ->whereBetween('gracenote.StartDateTime', [$query['periodStart'], $query['periodEnd']])
            ->rightJoin('channels', 'gracenote.CallSign', '=', 'channels.callSign')
            ->get();
            // SELECT * FROM `gracenote` WHERE StartDateTime BETWEEN '2021-02-01 15:00' AND'2021-02-01 23:59'
            // return $users;

         $response = [
            'responseMessage' => 'Success',
            'responseCode'=>'200',
            'getevent'=>$users, 
            // 'count'=>$pagechannel->count(),
            
        ];
        return response()->json($response, 200);
        }
        public function singlechanneldetails(Request $request) {
            // fetchChannelIdList: http://192.168.0.117:3000/getChannelDetails?channelId=389
            // http://ams-api.astro.com.my/ams/v3/getChannels?channelId=389
            $id = $request->input('channelId');
            $query = $request->all();
            $validator = Validator::make($query, [
                'channelId' => 'required'
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
            $imagePath=url("/uploads/channellogo/");
            // $newCompete = array('name'=>'Others');
            $channellist=Channel::select('id as channelId',DB::raw('concat(channels.id,"") siChannelId'),
            DB::raw('IFNULL(stationName,name) channelTitle')
            ,DB::raw("'channelDescription' as channelDescription"),DB::raw("'english' as channelLanguage"),DB::raw("null as channelColor1")
            ,DB::raw("null as channelColor2"),DB::raw("null as channelColor3"),
            'genres as channelCategory',DB::raw('concat(id,"") channelStbNumber'),
            DB::raw("false as channelHD"),DB::raw("null as hdSimulcastChannel"),'created_at as channelStartDate',
            'updated_at as channelEndDate',DB::raw("CONCAT('$imagePath/',`image`) icon"))
            
            ->where('id','=',$id)->get()
            ->map(function ($address)  {
if($address['icon']==null){
    $address['icon']= $address['logo'];
}
                return $address;
            
            });

   
            // ->get();
            //  $channellist->map(function ($item) {
            //     $item['test'] = ['123', '345'];
            //     return $item;
            //   });
            //   dd($channellist);
            // $channellist->setAttribute('test', 'ghgh');
            //   return $channellist;

         $response = [
            'responseMessage' => 'Success',
            'responseCode'=>'200',
            'channel'=>$channellist, 
            // 'count'=>$pagechannel->count(),
            
        ];
        return response()->json($response, 200);

        }
        function arraydata(){

            $channelExtRef = new \stdClass; 
$channelExtRef->system = "IVP"; 
$channelExtRef->subSystem = "Image"; 
$channelExtRef->address = "http://linear-poster.astro.com.my/prod/poster/57GHA_HD_IVP_LAND_448x252.jpg"; 

            $channelExtRef2 = new \stdClass; 
$channelExtRef2->system = "Logo"; 
$channelExtRef2->subSystem = "Pos_600x370"; 
$channelExtRef2->address = "http://linear-poster.astro.com.my/prod/logo/Golden_Hourse_HD_v1.png"; 

            $obj = array($channelExtRef, $channelExtRef2);
            return $obj;
        }

        public function category(){
return Genre::get();
        }

        public function vuehtml(){
            return view('vue');
        }

        public function favoriteChannel(Request $request)
        { try { 
            $channelid = $request->input('channelid');
            Auth::user()->favorites()->syncWithoutDetaching($channelid);
            $response = [
                'success' => true,
                'data' => 'Channel id '.$channelid.' added' ,
                'message' => 'Successfully added to favourite channel','response_code'=>200,
            ];
            return response()->json($response, 200);
        } catch (Exception $exception) {
            
            return response()->json([
                'success' => false,
                'message' => 'Some Error occured',
                'error'=>$exception->getMessage(),
                'response_code'=>500,
                
            ], 500);
        }
            
        }
        
        /**
         * Unfavorite a particular post
         *
         * @param  Post $post
         * @return Response
         */
        public function unFavoriteChannel(Request $request)
        {  try { 
            $channelid = $request->input('channelid');
            Auth::user()->favorites()->detach($channelid);
            $response = [
                'success' => true,
                'data' => 'Channel id '.$channelid.' removed' ,
                'message' => 'Successfully removed from favourite channel list','response_code'=>200,
            ];
            return response()->json($response, 200);
        } catch (Exception $exception) {
            
            return response()->json([
                'success' => false,
                'message' => 'Some Error occured',
                'error'=>$exception->getMessage(),
                'response_code'=>500,
                
            ], 500);
        }
        
            
        }
}
