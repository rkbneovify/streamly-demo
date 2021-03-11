<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Gracenote;
use App\Models\Gracenotechannel;
use App\Models\Genre;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
//use App\Traits\ImageUpload;programs
/**
 * Description of ChannelController
 *
 */

class ChannelController extends Controller
{    //use ImageUpload; //Using our created Trait to access inside trait method
       /**
     * Display a listing of the resource.
     *
     * @param  Index  $request
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['index','index2','show','create','edit','update','destroy','store','channelData']]);
    }
    public function index()
    {  
        $record = Channel::with('genre')->paginate(10);
        $encoded_auth_xml = getKeyOSAuthToken(); 
        
        $ua = request()->server('HTTP_USER_AGENT');
        $isDevice=isDeviceAppleRokuOthers($ua);
if( $isDevice=='apple') {return view('admin.channels.fairplayindex', compact('record','encoded_auth_xml'));} else{ return view('admin.channels.index', compact('record','encoded_auth_xml'));}
        
    } 
    public function index2()
    {  
        $record = Channel::with('genre')->paginate(10);
        $encoded_auth_xml = getKeyOSAuthToken(); 
        
        $ua = request()->server('HTTP_USER_AGENT');
        $isDevice=isDeviceAppleRokuOthers($ua);
if( $isDevice=='apple') {return view('admin.channels.fairplayindex', compact('record','encoded_auth_xml'));} else{ return view('admin.channels.index2', compact('record','encoded_auth_xml'));}
        
    }  

    
    //search
    public function searchapi(Request $request)
   {

 $search = request()->get('search');

   /* $books = Book::when($search, function ($query, $search) {
       return $query->where('name', 'LIKE', "%{$search}%");
   })
    ->orderBy('created_at', 'desc')
    ->simplepaginate(12); */
    $ua = $request->server('HTTP_USER_AGENT');
    $isDevice=isDeviceAppleRokuOthers($ua);
    if($isDevice=='roku'){
    $channel = Channel::select('id', 'smooth_url as url','channels.image', DB::raw('IFNULL(channels.callSign,channels.name) callSign'))->where('name', 'LIKE', "%{$search}%")->where('channels.status','=','1')->whereNotNull('smooth_url')->get();
} else if($isDevice=='apple')  {
        $channel = Channel::select('id', 'url','channels.image', DB::raw('IFNULL(channels.callSign,channels.name) callSign'))->where('name', 'LIKE', "%{$search}%")->where('channels.status','=','1')->whereNotNull('url')->get();
    }else{
        $channel = Channel::select('id', 'dash_url as url','channels.image', DB::raw('IFNULL(channels.callSign,channels.name) callSign'))->where('name', 'LIKE', "%{$search}%")->where('channels.status','=','1')->whereNotNull('dash_url')->get();
    }
 
  //$author = Author::where('name', 'LIKE', "%{$search}%")->Paginate(16);
  //$genre = Genre::where('name', 'LIKE', "%{$search}%")->Paginate(16);



   return response()->json(array(
    'data' => $channel, 
    'success' => true,
    //'author'=>$author,
    //'genre'=>$genre,
 ));


}
    /**
     * Display the specified resource.
     *
     * @param  Show  $request
     * @param  Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $channel = Channel::find($id); 
        
        $genres = Genre::pluck('name', 'id'); 
        $arrVal=explode(',',$channel->genres);
        
        return view('admin.channels.show',compact('channel','genres','arrVal'));

    }    /**
     * Show the form for creating a new resource.
     *
     * @param  Create  $request
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $genres = Genre::where('status','=','1')->pluck('name', 'id');
        return view('admin.channels.create', compact('genres'));  
        
       
    }    /**
     * Store a newly created resource in storage.
     *
     * @param  Store  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
          ]);
        
        $input = $request->only('name', 'genre_id', 'url','callSign', 'dash_url', 'smooth_url','status');
        $model=new Channel;
        if ($request->genres!= '') {
            $break_value = implode(",", $request->genres);
            $input['genres'] = $break_value;
        
        }


    //     $model->image = $request->image;
    //     if($model->image){
    //        try {
    //         $filePath = $this->UserImageUpload($model->image); //Passing $data->image as parameter to our created method
    //         $model->image = $filePath;
           

    //    } catch (Exception $e) {
    //        //Write your error message here
    //    }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = $request->name.'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/channellogo');
            $imagePath = $destinationPath. "/".  $name;
            $image->move($destinationPath, $name);
            $model->image = $name;
          }
        
        $model->fill($input);

        if ($model->save()) {
            
        $model->genresync()->attach($request->genres);
            return redirect('admin/channelstatus')->with('info','Channel Added Successfully');
        
            } else {
                return redirect('admin/channelstatus')->with('info','Some error occured');
        
            }
    } 
    /**
     * Show the form for editing the specified resource.
     *
     * @param  Edit  $request
     * @param  Channel  $channel
     * @return \Illuminate\Http\Response
     */
    
    
    public function edit($id)
    {
        $channel = Channel::find($id);
        $genres = Genre::pluck('name', 'id'); 
        $arrVal=explode(',',$channel->genres);
        
        return view('admin.channels.edit',compact('channel','genres','arrVal'));
    }
    
    /**
     * Update a existing resource in storage.
     *
     * @param  Update  $request
     * @param  Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'=>'required',
            'url'=>'required',
            'genre_id'=>'required',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $input = $request->only('name', 'url','smooth_url','callSign','dash_url', 'genre_id');
        // dd($input);
        if ($request->has('toggleswitch')) {
            // $archived="helllo block user on";
             $input['status'] = 0;
            // $user->is_active=0;
         } else{
            // $archived="bye block off";
             //$user->is_active=1;
             $input['status'] = 1;
         }
         if ($request->genres!= '') {
            $break_value = implode(",", $request->genres);
            $input['genres'] = $break_value;
        
        } else{$input['genres']='';}

        
    //            $allpost= $request->post();
    //    dd($input);  //get all the post data
        
        $model = Channel::find($id);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = $request->name.'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/channellogo');
            $imagePath = $destinationPath. "/".  $name;
            $image->move($destinationPath, $name);
            $model->image = $name;
          }
       // dd($input);exit;
        if(!$model){
            return redirect('admin/channelstatus')->with('info','Channel with id ' . $id . ' cannot be found.'); 
        }
        else{$model->update($input); //update the subscription info

            $model->genresync()->sync($request->genres);
            return redirect('admin/channelstatus')->with('info','Channel updated Successfully');
       }
        }  /**
     * Delete a  resource from  storage.
     *
     * @param  Destroy  $request
     * @param  Channel  $channel
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy( $id)
    {
        
             
            $user= Channel::whereId($id)->delete(); 
            if($user){
               return redirect('admin/channelstatus')->with('info','Channel Deleted Successfully');
                         
           
           }else{
               return redirect('admin/channelstatus')->with('info','Error occurred while deleting Channel');
               
           }
        return redirect()->back();
    }
    


    public function channelData()
     {   
        $second=Channel::toBase()->select('channels.id',DB::raw('IFNULL(gracenotechannels.StationName,channels.name) StationName'),'channels.status',DB::raw('IFNULL(channels.callSign,channels.name) callSign'),DB::raw('IFNULL(channels.image,"notfound.png") image'),"channels.url","channels.dash_url","channels.smooth_url")
           
        ->selectRaw("CAST( CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(genre.name, 'Others')), ',', '\",\"'), '\"]') AS JSON ) genrename")
           
           ->leftJoin('gracenotechannels', 'channels.callSign', '=', 'gracenotechannels.CallSign')
           ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
           ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
    
    ->groupBy('channels.id','gracenotechannels.StationName')
    ->orderBy('channels.name', 'asc')->get();
     
        return DataTables::of($second)
        
        ->addColumn('action', function($row) {
            return '<a 
            class="btn btn-xs btn-primary" id="vidplay" data-toggle="modal"  >View video</a> <a href="/admin/channelstatus/edit/'. $row->id .'" class="btn btn-xs btn-info">Edit</a>';
        })
        
      ->rawColumns([ 'action'])
        ->make(true);
    }
   



public function transformClientCollection($api)
{
    return array_map([$this, 'transformClient'], $api->toArray());
}

public function transformClient($clients)
{
  
    return [
        'StationName' => $clients['StationName'],
        'Slug' => $clients['CallSign'],
        'title' => $clients['EventTitle'],
        'description' => $clients['EventDescription'],
        'startdate' => $clients['StartDateTime'],
        'enddate' => $clients['EndDateTime']
    ];
}

  public function isUtc($now): void 
  {
      if ($now->utc) 
      {
          echo "Datetime is in UTC\n";
      } else {
      
          echo "Datetime is not in UTC\n";
          echo "its ".$now->timezone->getName()."\n"; 
      }
  }
 

    //channel with 7 day of schdule
  public function timezoneEpgchannelDaysSchedule(){

    // $date = Carbon::parse('2018-08-19 10:43:15', 'UTC');echo "\n";
    // echo($date->timezone->getName());echo "\n";
    // echo($date->format('n/j/y g:i A'));echo "\n";
    
    // $date->setTimezone('America/Denver');
    
    // echo($date->timezone->getName());echo "\n";
    // echo($date->format('n/j/y g:i A'));
    // exit;
    $imagePath=url("/uploads/channellogo/");
    $input=url('livetv/');
    DB::statement(DB::raw('SET SESSION group_concat_max_len = @@max_allowed_packet'));//SET @@session.time_zone = "+00:00";
    $record  = Gracenote::select(["sub.id","sub.callSign","StationName AS name","genres",DB::raw("CONCAT('$imagePath/',`image`) icon")])
    ->selectRaw("CAST( CONCAT('[\"', REPLACE(GROUP_CONCAT(DISTINCT IFNULL(g.name, 'Others')), ',', '\",\"'), '\"]') AS JSON ) genrename")
    ->selectRaw(" concat( '[', group_concat(json_object( 'start',(UNIX_TIMESTAMP(StartDateTimewithTimezon)*1000),'end',(UNIX_TIMESTAMP(EndDateTimewithTimezon)*1000),'TimeZoneName',TimeZoneName,'title', EventTitle ) Order By StartDateTime ), ']') events")
    ->leftJoin('genre as g', DB::raw("FIND_IN_SET(g.id, sub.genres)"), DB::raw('>'),DB::raw('0'))
    ->fromSub(function ($query) {
        $query->select(DB::raw('IFNULL(gracenote.StationName,channels.name) StationName'),DB::raw('IFNULL(channels.callSign,channels.name) callSign'),
        DB::raw('IFNULL(gracenote.EventTitle,channels.name) EventTitle'),DB::raw('IFNULL(gracenote.StartDateTime,DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s")) StartDateTime'),
        DB::raw('IFNULL(gracenote.EndDateTime,NOW()+INTERVAL 3 DAY) EndDateTime'),DB::raw('IFNULL(gracenote.TimeZoneName,TimeZoneName) TimeZoneName'),
        'channels.id','channels.genres',DB::raw('IFNULL(channels.image,"notfound.png") image'),
        DB::raw(" CASE TimeZoneName
        		WHEN 'Pacific Observing' THEN CONVERT_TZ(StartDateTime,'US/Pacific','Asia/Calcutta')
                WHEN 'Eastern Observing' THEN CONVERT_TZ(StartDateTime,'US/Eastern','Asia/Calcutta')
                WHEN 'Central Observing' THEN CONVERT_TZ(StartDateTime,'US/Central','Asia/Calcutta')
        
                ELSE CONVERT_TZ(DATE_FORMAT(CONCAT(CURDATE(), ' 00:00:00'), '%Y-%m-%d %H:%i:%s'),'US/Pacific','Asia/Calcutta')
        	end StartDateTimewithTimezon"),DB::raw(" CASE TimeZoneName
        		WHEN 'Pacific Observing' THEN CONVERT_TZ(EndDateTime,'US/Pacific','Asia/Calcutta')
                WHEN 'Eastern Observing' THEN CONVERT_TZ(EndDateTime,'US/Eastern','Asia/Calcutta')
                WHEN 'Central Observing' THEN CONVERT_TZ(EndDateTime,'US/Central','Asia/Calcutta')
        
                ELSE CONVERT_TZ(DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 3 day), ' 23:59:59'), '%Y-%m-%d %H:%i:%s'),'US/Pacific','Asia/Calcutta')
        	end EndDateTimewithTimezon"),

        'channels.url')
        ->from('gracenote')
        ->rightJoin('channels', 'gracenote.CallSign', '=', 'channels.callSign')
       
    ->whereRaw('gracenote.`StartDateTime` BETWEEN DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s") 
    AND DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 3 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")   or `channels`.`callSign` IS NULL')
     ->orderBy('gracenote.StartDateTime', 'asc');
      }, 'sub')
    
    ->orderBy('sub.callSign', 'asc')
    ->groupBy('sub.callSign','StationName','image','sub.id','genres');
        
   $reco=$record->get()->map(function ($address) use ($input) {

                 
    $address['url'] = $input.'/'.$address['callSign'].'/'.$address['genrename']['0'] ;

    return $address;

});//exit; // echo $reco; exit;
         if ($reco->isEmpty()) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'Channel not found.',
                'response_code'=>404,
            ];
            return response()->json($response, 404);
        }  
    
            $response = [
                'success' => true,
                'date'=>date("Y-m-d"),
                'data' => $reco,
               
                'message' => 'Schedule data for 3 days found',
                'response_code'=>200,
            ];
           
        return response()->json($response, 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
     
        }
       

}