<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gracenote;
use App\Models\Gracenotechannel;
use App\Models\Gracenoteevents;
use App\Models\Channel;
use App\User;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
	//
	public function searchUsers(Request $request)
	{ $q=$request->q;
        $imagePath=url("/uploads/channellogo/");
        $first= Gracenoteevents::select('EventTitle',DB::raw("'event' as type"),"CallSign",DB::raw("null as image "),DB::raw("null as genrename "))
        ->where('EventTitle', 'LIKE', '%'.$q.'%');
 $second=Gracenotechannel::select(DB::raw('IFNULL(gracenotechannels.StationName,channels.name) StationName'),DB::raw("'channel' as type"),
 DB::raw('IFNULL(gracenotechannels.CallSign,channels.name) CallSign'), DB::raw("CONCAT('$imagePath/',channels.`image`) image"),DB::raw('IFNULL(genre.name,"Others") genrename'))
        ->where('gracenotechannels.StationName', 'LIKE', '%'.$q.'%')
        ->orWhere('channels.name', 'LIKE', '%'.$q.'%')
        ->rightJoin('channels', 'gracenotechannels.CallSign', '=', 'channels.callSign')
        ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
        ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
->union($first)->orderByRaw(1)->take(10)->get();
       return $second; 
// dd($second);
// SELECT channels.id, IFNULL(channels.callSign,channels.name) callSign,IFNULL(genre.name, 'Others') genrename FROM channels 
// LEFT JOIN channels_genre ON 
//    channels.id=channels_genre.channels_id
//    LEFT JOIN genre ON  channels_genre.genre_id=genre.id
}

        public function searchevents(Request $request){
               $first= Gracenotechannel::select('StationName')
                ->where('StationName', 'LIKE', '%'.$request->q.'%');
$second=Gracenoteevents::select('EventTitle')
->where('EventTitle', 'LIKE', '%'.$request->q.'%')->take(10)
->union($first)

->get();
        // SELECT StationName ,'channel' as type FROM gracenotechannels WHERE StationName LIKE '%bl%' UNION
        //  SELECT EventTitle ,'event' as type FROM gracenote_events WHERE EventTitle LIKE '%bl%' ORDER BY 1 LIMIT 10
        }

        public function showAndchannelAutocomplete(Request $request)
    { $user=Auth::user();
        $q=$request->search;
        if($request->ajax()) {
/*
            SELECT StationName ,
            'channel' as type,
                 CallSign  
           FROM gracenotechannels 
             WHERE StationName LIKE '%com%' 
         UNION
                  SELECT EventTitle ,
                  'event' as type,
                     CallSign 
                  FROM gracenote_events 
                    WHERE EventTitle LIKE '%com%' 
                    
                        ORDER BY 1 LIMIT 10
                        */


        $first= Gracenoteevents::select('EventTitle',DB::raw("'event' as type"),DB::raw("null as CallSign "))
        ->where('EventTitle', 'LIKE', '%'.$q.'%');
return $second=Gracenotechannel::select('StationName',DB::raw("'channel' as type"),'CallSign')
        ->where('StationName', 'LIKE', '%'.$q.'%')
->union($first)->orderByRaw(1)->take(10)->get();
       
      $data=$record->get();
           
            $output = '';  $FRONTEND_URL=env('FRONTEND_URL');
            
            if (count($data)>0) {
              
                $output = '<ul class="list-group" style="display: block; position: relative; z-index: 1">';
              
                foreach ($data as $row){
                   
                // if (!empty($row->CallSign)){
                //     $url=$FRONTEND_URL.'/livetv/'.$row->callSign.'/'.$row->genrename['0'];}else{
                //         $url=$FRONTEND_URL.'/livetv/'.$row->name.'/'.$row->genrename['0']; 
                //     }
                    /*
                    $output .= '<li class="list-group-item">
                    <div class="dddd">
                    <a href='.$url.'>
                    <img style="	display: block;
                    width: auto;
                    margin: 0 auto;
                    height: 1.5rem;" src='.$row->logo.'>
                    '.$row->callSign.'
                   
                    </a>
                    '.$row->name.'
                </div>
                    </li>';*/
                    $output .= '<li class="list-group-item">'.$row->StationName.'';

                }
              
                $output .= '</ul>';
            }
            else {
             
                $output .= '<li class="list-group-item">'.'No results'.'</li>';
            }
            if (!$user->subscribed('main')) {
                return '<li class="list-group-item">you are not subscribed</li>';
            } 
            return $output;
        }
    }

    public function upcomingEvent($callSign,$EventTitle){
        if( Auth::check()){
            $login = true;
        } else {
            $login = false;
            return redirect()->route('home');
        }
        // SELECT EventTitle,EventDescription,StartDateTime,CallSign FROM `gracenote` WHERE 
        // `gracenote`.`CallSign` ='COMEDYP' AND `gracenote`.`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)
        $records  = Gracenote::select("EventTitle","EventDescription","StartDateTime")
        ->whereRaw("CallSign= '$callSign' AND EventTitle= '$EventTitle' AND `StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)")->orderBy('StartDateTime', 'asc')->paginate(10);
        
    //    dd($record->toSql());
       return view('frontend.upcomingevent',compact('records','login'));
        }

}