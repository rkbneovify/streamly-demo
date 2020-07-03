<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Channel;
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
       
    
       
      $relatedchannel=$record->get()->groupBy('genrename')->get($genre);
      
    	return view('frontend.pages.livetvchannel', compact('login','relatedchannel','genre'));

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
}
