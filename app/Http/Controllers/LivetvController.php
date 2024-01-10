<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use DB;



/**
 * Description of BugReportingController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */

class LivetvController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @param  Index  $request
     * @return \Illuminate\Http\Response
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    // }
    public function index($name)
    {
        $PrevNext=DB::table('channels')->where('name', $name)->value('id');
        $all=DB::table('channels')->orderBy('id', 'desc')->select('id')->first();
        $count = $all->id;
        if($PrevNext == 1){
           $PrevNext = 2;
        }
        if($PrevNext == $all->id -1 ){
            $PrevNext = $all->id -2 ;
        }

        $prev = DB::table('channels')->where('id', '<', $PrevNext )->where('status', '1')->max('id');
        $prevChannel = DB::table('channels')->where('id', $prev )->where('status', '1')->select('name', 'genres', 'callSign')->get();
        $next = DB::table('channels')->where('id', '>', $prev )->where('status', '1')->min('id');
        $nextChannel = DB::table('channels')->where('id', $next )->where('status', '1')->select('name', 'genres', 'callSign')->get();
        
        $prevChannelgenre = DB::table('genre')->where('id', $prevChannel[0]->genres)->value('name');
        $nextChannelgenre = DB::table('genre')->where('id', $nextChannel[0]->genres)->value('name');

        if( $prevChannelgenre == null){
            $prevChannelgenre = 'Others';
        }
        if( $nextChannelgenre == null){
            $nextChannelgenre = 'Others';
        }
        if($prevChannel[0]->callSign == ''){
            $urlprev = ''.env("APP_URL").'/livetv/'.$prevChannel[0]->name.'/'.$prevChannelgenre.'';
        }else{
            $urlprev = ''.env("APP_URL").'/livetv/'.$prevChannel[0]->callSign.'/'.$prevChannelgenre.'';
        }
        if($nextChannel[0]->callSign == ''){
            $urlnext = ''.env("APP_URL").'/livetv/'.$nextChannel[0]->name.'/'.$nextChannelgenre.'';
        }else{
            $urlnext = ''.env("APP_URL").'/livetv/'.$nextChannel[0]->callSign.'/'.$nextChannelgenre.'';
        }

        $ResponseChannels = [
            'prev'=> [
                'channelurl' => $urlprev,
                'channelname' => $prevChannel[0]->name,
                'channelgenre' => $prevChannelgenre
            ],
            'next'=> [
                'channelurl' => $urlnext,
                'channelname' => $nextChannel[0]->name,
                'channelgenre' => $nextChannelgenre
            ]
          ];

        return $ResponseChannels;
    }  
    
}