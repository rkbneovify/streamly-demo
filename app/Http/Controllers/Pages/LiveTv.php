<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Channel;
use App\Models\Gracenote;
use Illuminate\Support\Facades\DB;
use App\Models\Genre;
use KeyOSAuthXMLGenerator;
class LiveTv extends Controller
{ 
    public function showPage(){

    	if( Auth::check()){
            $login = true;
        } else {
            $login = false;
            return redirect()->route('home');
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
        $certificate = config_path("buydrm/9754e0cfddc682c21216de3eb4d11885.pem");
        
     
        try {
            // Create authentication XML generator instance.
            $auth_xml_generator = new KeyOSAuthXMLGenerator($certificate);
            $auth_xml_generator->AuthXMLExpirationTime('+15 minutes');
          
            // Crate Simple PR license
            $pr_lic = $auth_xml_generator->newSimpleLicense();
          
            // Create WV license
            $wv_lic = $auth_xml_generator->newWidevineLicense();
          
            // Create FP license
            $fp_lic = $auth_xml_generator->newFairPlayLicense();
         
             
            // Generate Authentication XML.
            $encoded_auth_xml = $auth_xml_generator->encodeAuthenticationXML($auth_xml_generator->signAuthenticationXML($auth_xml_generator->generateAuthenticationXML()));
            
    
         
    	if( Auth::check()){
            $login = true;
        } else {
            $login = false;
            return redirect()->route('home');
        }
        $ua = request()->server('HTTP_USER_AGENT');
$iPod    = stripos($ua,"iPod");
$iPhone  = stripos($ua,"iPhone");
$iPad    = stripos($ua,"iPad");
$iPadOS = stripos($ua,"Macintosh");
$appletv = stripos($ua,"appletv");
$Android = stripos($ua,"Android");
$Roku = stripos($ua,"Roku");
$Darwin = stripos($ua,"Darwin");
if( $Darwin||$iPod || $iPhone||$iPad||$iPadOS ||$appletv&&!$Android)  { //for apple serve m3u8
    // echo "apple device";
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

$url= Channel::where('callSign', $callSign)->get('url');
// print_r($url); exit;
  $relatedchannel=$record->get()->groupBy('genrename')->get($genre);

}else{ 
    // echo " non apple device";
    //for non apple "dash_url as url"
    $record = Channel::select('channels.id', 'channels.callSign','channels.image', 'channels.dash_url as url')
        
    ->selectRaw("CAST( CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(g.name, 'Others')), ',', '\",\"'), '\"]') AS JSON ) genrename")
    
    
    ->leftJoin('genre as g', DB::raw("FIND_IN_SET(g.id, channels.genres)"), DB::raw('>'),DB::raw('0'))
    ->whereIn('channels.callSign', function($query)
{
    $query->select('CallSign')
          ->from('gracenotechannels');
          
})
->groupBy('channels.id') ->orderBy('channels.callSign', 'asc');
   
$channelData= Gracenote::where('CallSign', $callSign)->get();

$url= Channel::where('callSign', $callSign)->get('dash_url as url');
// print_r($url); exit;
  $relatedchannel=$record->get()->groupBy('genrename')->get($genre);


}

       
      
    	return view('frontend.pages.livetvchannel', compact('login','relatedchannel','genre','channelData','url','encoded_auth_xml'));
    }catch(Exception $e) {
            
            return $e->getMessage();
          }
    }

    public function showAllPage()
    { 

        if( Auth::check()){
            $login = true;
        } else {
            $login = false;
            return redirect()->route('home');
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

    public function favoriteChannel(Request $request)
{ //echo "hggh";exit; 
    $input = $request->all();
    $channelid = $request->input('channelid');
    // $gdg=Auth::user();
    // return response()->json(['success'=>'Got Simple Ajax Request.','channelid'=>$channelid,'user'=>$gdg]);
    Auth::user()->favorites()->syncWithoutDetaching($channelid);

    return back();
}

/**
 * Unfavorite a particular post
 *
 * @param  Post $post
 * @return Response
 */
public function unFavoriteChannel(Request $request)
{  
    $input = $request->all();
    $channelid = $request->input('channelid');
    Auth::user()->favorites()->detach($channelid);

    return back();
}

public function myFavorites()
{ if( Auth::check()){
    $login = true;
    $genres = Genre::pluck('name', 'id');
    $myFavorites = Auth::user()->favorites;
   // $arrVal=explode(',',$myFavorites->genres);
 //dd($myFavorites[0]->name);exit;
    return view('frontend.pages.my_favorites', compact('myFavorites','genres','login'));
} else {
    $login = false;
    return redirect()->route('home');
}

}

public function showRecent()
    { 

        if( Auth::check()){
            $login = true;
            $genres = Genre::pluck('name', 'id');
        } else {
            $login = false;
            return redirect()->route('home');
        }

       
      $myRecent=Auth::user()->recentschannel;
        
        return view('frontend.pages.recentchannel',compact('myRecent','genres','login'));

    }

    public function  epgScreen(){
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
$record->get();
        return view('frontend.pages.epg',compact('record'));
    }

    public function  epgScreen2(){
        return view('frontend.pages.epg3');
    }

    public function webReact()

    { if( Auth::check()){
        $login = true;
    } else {
        $login = false;
        return redirect()->route('home');
    }

       // $logs = LogActivity::logActivityLists();

        return view('app',compact('login'));

    }
    
}
