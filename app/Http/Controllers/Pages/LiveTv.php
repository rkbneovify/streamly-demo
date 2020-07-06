<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Channel;
use App\Models\Gracenote;
use Illuminate\Support\Facades\DB;
// use App\Models\Genre;
class LiveTv extends Controller
{ 
    public function showPage(){

    	if( Auth::check()){
            $login = true;
        } else {
            $login = false;
            return redirect('/');
        }

        $record = Channel::select('channels.id', 'channels.callSign','channels.image', 'channels.url')
        
        ->selectRaw("CAST( CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(g.name, 'Others')), ',', '\",\"'), '\"]') AS JSON ) genrename")
        
        
        ->leftJoin('genre as g', DB::raw("FIND_IN_SET(g.id, channels.genres)"), DB::raw('>'),DB::raw('0'))
        ->whereIn('channels.callSign', function($query)
    {
        $query->select('CallSign')
              ->from('gracenotechannels');
              
    })
    ->groupBy('channels.id') ->orderBy('channels.callSign', 'asc');
       
    
       
      $pagechannel=$record->get()->groupBy('genrename');
      //$pagechannel->toArray(); 
    
        

    	return view('frontend.pages.livetv', compact('login','pagechannel'));

    }

    public function showChannel($callSign,$genre){

    	if( Auth::check()){
            $login = true;
        } else {
            $login = false;
        }
        

        $record = Channel::select('channels.id', 'channels.callSign','channels.image', 'channels.url')
        
        ->selectRaw("CAST( CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(g.name, 'Others')), ',', '\",\"'), '\"]') AS JSON ) genrename")
        
        
        ->leftJoin('genre as g', DB::raw("FIND_IN_SET(g.id, channels.genres)"), DB::raw('>'),DB::raw('0'))
        ->whereIn('channels.callSign', function($query)
    {
        $query->select('CallSign')
              ->from('gracenotechannels');
              
    })
    ->groupBy('channels.id') ->orderBy('channels.callSign', 'asc');
       
    $channelData= Gracenote::where('CallSign', $callSign)->get();
    
       
      $relatedchannel=$record->get()->groupBy('genrename')->get($genre);
      
    	return view('frontend.pages.livetvchannel', compact('login','relatedchannel','genre','channelData'));

    }

    public function showAllPage()
    { 

        if( Auth::check()){
            $login = true;
        } else {
            $login = false;
        }

        $record = Channel::select('channels.id', 'channels.callSign','channels.image', 'channels.url')
        
        ->selectRaw("CAST( CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(g.name, 'Others')), ',', '\",\"'), '\"]') AS JSON ) genrename")
        
        
        ->leftJoin('genre as g', DB::raw("FIND_IN_SET(g.id, channels.genres)"), DB::raw('>'),DB::raw('0'))
        ->whereIn('channels.callSign', function($query)
    {
        $query->select('CallSign')
              ->from('gracenotechannels');
              
    })
    ->groupBy('channels.id') ->orderBy('channels.callSign', 'asc');
       
    
       
      $pagechannel=$record->get();
        
        return view('frontend.pages.alllivetvchannel', compact('login','pagechannel'));

    }
    public function  videojs(){
        return view('frontend.pages.videojs');
    }
    public function  videojs2(){
        return view('frontend.pages.videojs2');
    }
    
    public function  getcurrentepisode(){
        DB::statement(DB::raw('SET SESSION group_concat_max_len = @@max_allowed_packet'));
$record  = Gracenote::select("CallSign","StationName","image")
->selectRaw(" concat( '[', group_concat(json_object( 'title', EventTitle,'start', StartDateTime,'end', EndDateTime ) Order By StartDateTime ), ']') programs")
->fromSub(function ($query) {
    $callsign = "CNNHD";
    $query->select('gracenote.*','channels.image')
    ->from('gracenote')
    ->join('channels', 'gracenote.CallSign', '=', 'channels.callSign')
    ->whereRaw("gracenote.`StartDateTime` BETWEEN DATE_FORMAT(CONCAT(CURDATE(), ' 00:00:00'), '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 1 day), ' 23:59:59'), '%Y-%m-%d %H:%i:%s')  AND (gracenote.`CallSign`='".$callsign."')")
    ->orderBy('gracenote.StartDateTime', 'asc');
}, 'sub')

->orderBy('CallSign', 'asc')
->groupBy('CallSign','StationName','image');
     $reco=$record->get();  
     $callSign = "CNNHD";
     $cuurentshow=Gracenote::select("CallSign","StationName","EventType","EventID","EventTitle","StartDateTime","EndDateTime")
     ->where('CallSign', $callSign)
     ->whereBetween('StartDateTime', [
        now()->format('Y-m-d H:00:00'),
        now()->addHours(1)->format('Y-m-d H:00:00')
]); 
     if ($reco->isEmpty()) {
        $response = [
            'success' => false,
            'data' => 'Empty',
            'message' => 'Channel not found.',
            'response_code'=>404,
        ];
        return response()->json($response, 404);
    }    
    $imagePath=asset("/uploads/channellogo/".$record->value('image'));
        $response = [
            'success' => true,
            't'=>time(),
            'current'=>$cuurentshow->first(),
            'date'=>date("Y-m-d"),
            'logo'=>$imagePath,
            'data' => $reco,
           
            'message' => 'Channel found',
            'response_code'=>200,
        ];
        // return response()->json($record->toSql());
        return response()->json($response, 200); exit;
    }
    
}
