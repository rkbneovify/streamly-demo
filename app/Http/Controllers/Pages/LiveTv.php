<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Channel;
use App\Models\Gracenote;
use Illuminate\Support\Facades\DB;
use App\Models\Genre;
use App\SessionTable;

use App\Models\Gracenotechannel;
class LiveTv extends Controller
{ 
    public function showPage(){

    	if( Auth::check()){
            $login = true;
        } else {
            $login = false;
            return redirect()->route('home');
        }

        $second=Channel::select('channels.id',DB::raw('IFNULL(gracenotechannels.StationName,channels.name) StationName'),
        DB::raw('IFNULL(gracenotechannels.CallSign,channels.name) CallSign'), 'channels.image',DB::raw('IFNULL(genre.name,"Others") genrenae'))
               
               ->leftJoin('gracenotechannels', 'gracenotechannels.CallSign', '=', 'channels.callSign')
               ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
               ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')

    ->where('channels.status','=','1')
    ->orderBy('channels.StationName', 'asc');
    
    
       
      $pagechannel=$second->get()->groupBy('genrenae');
    
        

    	return view('frontend.pages.livetv', compact('login','pagechannel'));

    }

    public function showChannel($callSign,$genre){
     
        try {
            $encoded_auth_xml = getKeyOSAuthToken();
    
         
    	if( Auth::check()){
            $login = true;
            $userRole= Auth::user()->roles->pluck('name');
            if($userRole[0]=='user'){
            if (!Auth::user()->subscribed('main')) {
                return redirect('/selectplan');
            }}
        } else {
            $login = false;
            return redirect()->route('home');
        }
        $login_limit = 5;$user_id = Auth::user()->id;

//find all by user_id and and status=active
$active_session = SessionTable::where([
['status', '=', '1'],
['users_id', '=', $user_id],
 ])->get()->toArray();
 
 $activeNowsession = SessionTable::where([
    ['status', '=', '1'],
    ['users_id', '=', $user_id],
    ['access_token', '=', session()->get('jwttoken')],
     ])->get()->count();   

      if($activeNowsession!=0){ $ua = request()->server('HTTP_USER_AGENT');
        $isDevice=isDeviceAppleRokuOthers($ua);
        
        if($isDevice=='chrome'){
        
            $type='dash_url';
            $record = $this->showChannelFunc($type);
            
           
        $channelData= Gracenote::where('CallSign', $callSign)->get();
        
        $url= Channel::where('callSign', $callSign)->orWhere('name', '=', $callSign)->get('dash_url as url');
        
          $relatedchannel=$record->get()->groupBy('genrename')->get($genre);
        
    	return view('frontend.pages.livetvchannel', compact('login','relatedchannel','genre','channelData','url','encoded_auth_xml'));
        
        }else if($isDevice=='apple')  { //for apple serve m3u8
    // echo "apple device";
    $type='url';
    $record = $this->showChannelFunc($type);
   
$channelData= Gracenote::where('CallSign', $callSign)->get();

$url= Channel::where('callSign', $callSign)->orWhere('name', '=', $callSign)->get('url');

  $relatedchannel=$record->get()->groupBy('genrename')->get($genre);

  return view('frontend.pages.forapplelivetvchannel', compact('login','relatedchannel','genre','channelData','url','encoded_auth_xml'));
}else{ 
    // echo " non apple device";
    //for non apple "dash_url as url"
    $type='dash_url';
    $record = $this->showChannelFunc($type);
    
   
$channelData= Gracenote::where('CallSign', $callSign)->get();

$url= Channel::where('callSign', $callSign)->orWhere('name', '=', $callSign)->get('dash_url as url');

  $relatedchannel=$record->get()->groupBy('genrename')->get($genre);

  return view('frontend.pages.livetvchannel', compact('login','relatedchannel','genre','channelData','url','encoded_auth_xml'));
    
}} else{
    if (count($active_session) <$login_limit) 
    return view('frontend.RefreshVideoPlayToken',compact('login'));
}
if (count($active_session) >= $login_limit) {
    $login = true;
    //  return "Your account is in use on 5 devices. Please stop playing on other devices to continue";
    //  $login = false; 
        return view('frontend.deviceLimitReached',compact('login'));
    }

       
      
    }catch(Exception $e) {
            
            return $e->getMessage();
          }
    }

    //for video.js

    public function showChannel2($callSign,$genre){
     
        try {
            $encoded_auth_xml = getKeyOSAuthToken();
    
         
    	if( Auth::check()){
            $login = true;
            
            $userRole= Auth::user()->roles->pluck('name');
            if($userRole[0]=='user'){
            if (!Auth::user()->subscribed('main')) {
                return redirect('/selectplan');
            }}
        } else {
            $login = false;
            return redirect()->route('home');
        }
        $login_limit = 5;$user_id = Auth::user()->id;

//find all by user_id and and status=active
$active_session = SessionTable::where([
['status', '=', '1'],
['users_id', '=', $user_id],
 ])->get()->toArray();
 if (count($active_session) > $login_limit) {
    $login = true;
    //  return "Your account is in use on 5 devices. Please stop playing on other devices to continue";
    //  $login = false; 
        return view('frontend.deviceLimitReached',compact('login'));
 }
        $ua = request()->server('HTTP_USER_AGENT');
        $isDevice=isDeviceAppleRokuOthers($ua);
if($isDevice=='apple')  { //for apple serve m3u8
    // echo "apple device";
    $type='url';
    $record = $this->showChannelFunc($type);
   
$channelData= Gracenote::where('CallSign', $callSign)->get();

$Channel= Channel::where('callSign', $callSign)->orWhere('name', '=', $callSign);
$url=$Channel->get('url');
$channelId=$Channel->get('id');

$relatedchannel=$record->get()->where('genrenae',$genre);

  return view('frontend.pages.forapplelivetvchannel', compact('login','relatedchannel','genre','channelData','url','encoded_auth_xml','channelId'));
}else{ 
    // echo " non apple device";
    //for non apple "dash_url as url"
    $type='dash_url';
    $record = $this->showChannelFunc($type);
    
   
$channelData= Gracenote::where('CallSign', $callSign)->get();

$Channel= Channel::where('callSign', $callSign)->orWhere('name', '=', $callSign);
$url=$Channel->get('dash_url as url');
$channelId=$Channel->get('id');
  $relatedchannel=$record->get()->where('genrenae',$genre);


}

       
      
    	return view('frontend.pages.livetvchannel', compact('login','relatedchannel','channelData','url','encoded_auth_xml','channelId'));
    }catch(Exception $e) {
            
            return $e->getMessage();
          }
    }
    public function showChannelFunc($type){
      

   $second=Channel::select('channels.id',DB::raw('IFNULL(gracenotechannels.StationName,channels.name) StationName'),
   DB::raw('IFNULL(gracenotechannels.CallSign,channels.name) CallSign'), 
   'channels.image',DB::raw('IFNULL(genre.name,"Others") genrenae'), "channels.$type as url")
          
          ->leftJoin('gracenotechannels', 'gracenotechannels.CallSign', '=', 'channels.callSign')
          ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
          ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')

->where('channels.status','=','1')
->orderBy('channels.StationName', 'asc');

return $second;

    }

    public function showAllPage()
    { 

        if( Auth::check()){
            $login = true;
        } else {
            $login = false;
            return redirect()->route('home');
        }

    $second=Channel::select('channels.id',DB::raw('IFNULL(gracenotechannels.StationName,channels.name) StationName'),
    DB::raw('IFNULL(gracenotechannels.CallSign,channels.name) CallSign'), 'channels.image',DB::raw('IFNULL(genre.name,"Others") genrenae'))
           
           ->leftJoin('gracenotechannels', 'gracenotechannels.CallSign', '=', 'channels.callSign')
           ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
           ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')

->where('channels.status','=','1')
->orderBy('channels.StationName', 'asc');


       
      $pagechannel=$second->get()->unique();
        
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
