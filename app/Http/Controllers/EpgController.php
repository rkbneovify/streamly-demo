<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Channel;
use App\Models\Gracenote;
use Illuminate\Support\Facades\DB;
class EpgController extends Controller
{
    public function channellist(Request $request) 
    { 
        
        
        $type='url';
$record = $this->channeljsonFunc($type);
$pagechannel=$record->get();
 //  return response()->json($record->toSql());
 $response = [
    'responseMessage' => 'Success',
    'responseCode'=>'200',
    'channels'=>$pagechannel, 
    // 'count'=>$pagechannel->count(),
    
];
// return response()->json($reco);
return response()->json($response, 200);
    }

    public function channeljsonFunc($type){
        $record = Channel::select('channels.id as channelId','channels.id as channelStbNumber',DB::raw('IFNULL(channels.stationName,channels.name) channelTitle'),DB::raw('IFNULL(channels.callSign,channels.name) callSign'),'channels.image', "channels.$type as url")
        // ->selectRaw("concat( '[',GROUP_CONCAT(IFNULL(QUOTE(g.name), 'Others')),']') genrename")
        // ->selectRaw("concat( '[',GROUP_CONCAT(IFNULL(QUOTE(g.name), 'Others')),']') genrename")
        // ->selectRaw("GROUP_CONCAT(g.name) genrename")
        ->selectRaw("CAST( CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(g.name, 'Others')), ',', '\",\"'), '\"]') AS JSON ) genrename")
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
            $id = $request->input('id');
	

            /* returns array of entire input query value */
            $query = $request->all();
            $str_arr = explode (",", $query['id']);  
             $query['periodStart'];
             $query['periodEnd'];
            $ids = [1,2,3];
            // $ids = $str_arr;
            // dump($ids);
            // dd($str_arr);
            $users = Channel::wherein('id',$str_arr)->get();
            // SELECT * FROM `gracenote` WHERE StartDateTime BETWEEN '2021-02-01 15:00' AND'2021-02-01 23:59'
            return $users;
        }
}
