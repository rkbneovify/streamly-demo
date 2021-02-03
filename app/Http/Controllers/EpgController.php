<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Http\Request;
use App\Models\Channel;
use App\Models\Gracenote;
use Illuminate\Support\Facades\DB;
use Validator;
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
$pagechannel=$record->get();
 //  return response()->json($record->toSql());
 $response = [
    'responseMessage' => 'Success',
    'responseCode'=>'200',
    'channels'=>$pagechannel, 
    
];
return response()->json($response, 200);
    }

    public function channeljsonFunc($type){
        // DB::raw('concat(channels.id,"") channelStbNumber')
        $record = Channel::select('channels.id as channelId',DB::raw('IFNULL(channels.stationName,channels.name) channelTitle'),'channels.id as channelStbNumber')
        // ->selectRaw("concat( '[',GROUP_CONCAT(IFNULL(QUOTE(g.name), 'Others')),']') genrename")
        // ->selectRaw("concat( '[',GROUP_CONCAT(IFNULL(QUOTE(g.name), 'Others')),']') genrename")
        // ->selectRaw("GROUP_CONCAT(g.name) genrename")
        // ->selectRaw("CAST( CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(g.name, 'Others')), ',', '\",\"'), '\"]') AS JSON ) genrename")
        ->where('channels.status','=','1')
        // ->selectRaw("JSON_ARRAY(GROUP_CONCAT(IFNULL(g.name, 'Others'))) genrename")
        
        ->leftJoin('genre as g', DB::raw("FIND_IN_SET(g.id, channels.genres)"), DB::raw('>'),DB::raw('0'))
    //     ->whereIn('channels.callSign', function($query)
    // {
    //     $query->select('CallSign')
    //           ->from('gracenotechannels');
              
    // })
    ->orderBy('callSign', 'asc')
    ->groupBy('channels.id');
    return $record;
    }
    public function programelist(Request $request) {
         $grace= Gracenote::select('gracenote.StationName as channelTitle',DB::raw("TIMEDIFF(EndDateTime,StartDateTime)AS displayDuration"),'channels.id as channelStbNumber',
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
            $users = Gracenote::select(DB::raw('concat(gracenote.id,"") eventID'),'channels.id as channelId',
            DB::raw('concat(channels.id,"") channelStbNumber'),DB::raw("'false' as channelHD"),'gracenote.StationName as channelTitle',
            DB::raw("'null' as epgEventImage"),
            DB::raw("gracenote.Rating as certification"),'StartDateTime as displayDateTimeUtc','StartDateTime as displayDateTime',
            DB::raw("TIMEDIFF(EndDateTime,StartDateTime)AS displayDuration"),DB::raw("'1:964:41911209' as siTrafficKey"),
            'EventTitle as programmeTitle','gracenote.EventID as programmeId',DB::raw('concat(gracenote.EventID,"p") programmeId'),
            DB::raw('concat(gracenote.EventID,"e") episodeId'),'EventDescription as shortSynopsis')
            ->addSelect(DB::raw("'null' as longSynopsis"),DB::raw("'' as actors"),DB::raw("'' as directors"),
            DB::raw("'' as producers"),DB::raw("gracenote.EventType as genre"),DB::raw("'' as subGenre"),DB::raw("false as live"),
            DB::raw("false as premier"),DB::raw("false as ottBlackout"),DB::raw("null as highlight"),
            DB::raw("'false' as contentId"),DB::raw("'false' as contentImage"),DB::raw("'null' as groupKey"),DB::raw("null as vernacularData"))
            
           
            // Channel::select('gracenote.')
            ->wherein('channels.id',$str_arr)
            ->whereBetween('gracenote.StartDateTime', [$query['periodStart'], $query['periodEnd']])
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
}
