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
    public function index()
    {  
        $record = Channel::with('genre')->paginate(10);
        
        return view('admin.channels.index', compact('record'));
    }  
    
    public function channeljson(Request $request) 
    {   $ua = $request->server('HTTP_USER_AGENT');
        $iPod    = stripos($ua,"iPod");
        $iPhone  = stripos($ua,"iPhone");
        $iPad    = stripos($ua,"iPad");
        $iPadOS = stripos($ua,"Macintosh");
        $appletv = stripos($ua,"appletv");
        $Android = stripos($ua,"Android");
        if( $iPod || $iPhone||$iPad||$iPadOS ||$appletv&&!$Android)  {
            $record = Channel::select('channels.id', DB::raw('IFNULL(channels.callSign,channels.name) callSign'),'channels.image', 'channels.url')
            // ->selectRaw("concat( '[',GROUP_CONCAT(IFNULL(QUOTE(g.name), 'Others')),']') genrename")
            // ->selectRaw("concat( '[',GROUP_CONCAT(IFNULL(QUOTE(g.name), 'Others')),']') genrename")
            // ->selectRaw("GROUP_CONCAT(g.name) genrename")
            ->selectRaw("CAST( CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(g.name, 'Others')), ',', '\",\"'), '\"]') AS JSON ) genrename")
            // ->selectRaw("JSON_ARRAY(GROUP_CONCAT(IFNULL(g.name, 'Others'))) genrename")
            
            ->leftJoin('genre as g', DB::raw("FIND_IN_SET(g.id, channels.genres)"), DB::raw('>'),DB::raw('0'))
        //     ->whereIn('channels.callSign', function($query)
        // {
        //     $query->select('CallSign')
        //           ->from('gracenotechannels');
                  
        // })
        ->orderBy('callSign', 'asc')
        ->groupBy('channels.id');
            }else{
        $record = Channel::select('channels.id', DB::raw('IFNULL(channels.callSign,channels.name) callSign'),'channels.image', 'channels.dash_url as url')
        // ->selectRaw("concat( '[',GROUP_CONCAT(IFNULL(QUOTE(g.name), 'Others')),']') genrename")
        // ->selectRaw("concat( '[',GROUP_CONCAT(IFNULL(QUOTE(g.name), 'Others')),']') genrename")
        // ->selectRaw("GROUP_CONCAT(g.name) genrename")
        ->selectRaw("CAST( CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(g.name, 'Others')), ',', '\",\"'), '\"]') AS JSON ) genrename")
        // ->selectRaw("JSON_ARRAY(GROUP_CONCAT(IFNULL(g.name, 'Others'))) genrename")
        
        ->leftJoin('genre as g', DB::raw("FIND_IN_SET(g.id, channels.genres)"), DB::raw('>'),DB::raw('0'))
    //     ->whereIn('channels.callSign', function($query)
    // {
    //     $query->select('CallSign')
    //           ->from('gracenotechannels');
              
    // })
    ->orderBy('callSign', 'asc')
    ->groupBy('channels.id');}
       
    //JSON_ARRAY
       
       $pagechannel=$record->get(); 
    //   ->paginate(10);
        
      
    //  return response()->json($record->toSql());
    $response = [
        'success' => true,
        'data'=>$pagechannel, 
        // 'data'=>$pagechannel->count(), 
        'message' => 'Channel with genre successfully fetched',
        'response_code'=>200,
    ];
    // return response()->json($reco);
    return response()->json($response, 200);
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
    $iPod    = stripos($ua,"iPod");
    $iPhone  = stripos($ua,"iPhone");
    $iPad    = stripos($ua,"iPad");
    $iPadOS = stripos($ua,"Macintosh");
    $appletv = stripos($ua,"appletv");
$Android = stripos($ua,"Android");
if( $iPod || $iPhone||$iPad||$iPadOS ||$appletv&&!$Android)  {
        $channel = Channel::select('id', 'callSign', 'url')->where('name', 'LIKE', "%{$search}%")->Paginate(16);
    }else{
        $channel = Channel::select('id', 'callSign', 'dash_url as url')->where('name', 'LIKE', "%{$search}%")->Paginate(16);
    }
 
  //$author = Author::where('name', 'LIKE', "%{$search}%")->Paginate(16);
  //$genre = Genre::where('name', 'LIKE', "%{$search}%")->Paginate(16);



   return response()->json(array(
    'message' => $channel, 
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
          ]);
        
        $input = $request->only('name', 'genre_id', 'url','status');
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
        $input = $request->only('name', 'url', 'genre_id');
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
        
        }

        
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
    

    public function channelschedulejson(){
        
       // $search = date("Y-m-d");
        $callsign = request()->get('search');
    //      $record  = Gracenote::where([
    // ['CallSign', '=', $callsign],
    // ['StartDateTime', 'LIKE', "%{$search}%"],
    //  ]);
     DB::statement(DB::raw('SET SESSION group_concat_max_len = @@max_allowed_packet'));
$record  = Gracenote::select("CallSign","StationName","image")
->selectRaw(" concat( '[', group_concat(json_object( 'title', EventTitle,'start', UNIX_TIMESTAMP(StartDateTime),'end', UNIX_TIMESTAMP(EndDateTime) ) Order By StartDateTime ), ']') programs")
->fromSub(function ($query) {
    $callsign = request()->get('search');
    $query->select('gracenote.*','channels.image')
    ->from('gracenote')
    ->join('channels', 'gracenote.CallSign', '=', 'channels.callSign')
    ->whereRaw("gracenote.`StartDateTime` BETWEEN DATE_FORMAT(CONCAT(CURDATE(), ' 00:00:00'), '%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 3 day), ' 23:59:59'), '%Y-%m-%d %H:%i:%s')  AND (gracenote.`CallSign`='".$callsign."')")
    ->orderBy('gracenote.StartDateTime', 'asc');
}, 'sub')

->orderBy('CallSign', 'asc')
->groupBy('CallSign','StationName','image');
     $reco=$record->get();   
     if ($reco->isEmpty()) {
        $response = [
            'success' => false,
            'data' => 'Empty',
            'message' => 'Channel not found.',
            'response_code'=>404,
        ];
        return response()->json($response, 404);
    }    
    //  return response()->json($record->toSql());
    // $destinationPath = public_path('/uploads/channellogo');
    // $imagePath = $destinationPath. "/".  $record->value('image');
    $imagePath=asset("/uploads/channellogo/".$record->value('image'));
        $response = [
            'success' => true,
            'date'=>date("Y-m-d"),
            'logo'=>$imagePath,
            'data' => $reco,
           
            'message' => 'Channel found',
            'response_code'=>200,
        ];
        // return response()->json($reco);
        return response()->json($response, 200);
    }

    public function channelData()
    {   
        return DataTables::of(Channel::query())
        ->addColumn('fullName',function ($data){
            $genres = Genre::pluck('name', 'id'); 
            $arrVal=explode(',',$data->genres);
            $options = '';
            foreach ($genres as $id => $genre) {
                if(in_array($id, $arrVal)){
                                 
                $options .= ''.$genre.' ';
            } else{
                $options .= '';}
                
            }
            $return = 
            '' . $options . '';
     
         return $return;
    
    
        })
        ->addColumn('action', function($row) {
            return '<a href="/admin/channelstatus/view/'. $row->id .'" class="btn btn-xs btn-primary">View</a> <a href="/admin/channelstatus/edit/'. $row->id .'" class="btn btn-xs btn-info">Edit</a>';
        })
        ->editColumn('photo', function ($row) {
            if ($row->image!='') {
                $url=asset("/uploads/channellogo/$row->image");
                return  '<a href="'.$url.'" target="_blank"><img src='.$url.' width="50px" height="50px"></a>';
                
                  
            }

            return '';
        })
      ->rawColumns(['photo', 'action'])
        ->make(true);
    }
    
//channel with 7 day of schdule
public function channelDaysSchedule(Request $request){
    //  SELECT * FROM `gracenote` WHERE `gracenote`.`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) ORDER BY `gracenote`.`StartDateTime` ASC
  //   select GROUP_CONCAT(EventTitle SEPARATOR ', ' ORDER BY StartDateTime ASC) programs,CallSign from gracenote WHERE `gracenote`.`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)  group BY CallSign ORDER by CallSign
  //   SELECT CallSign, JSON_ARRAYAGG( JSON_OBJECT( 'title', EventTitle, 'start', StartDateTime,'end', EndDateTime ) ) AS programs FROM gracenote GROUP BY CallSign
// select count(EventTitle),CallSign from gracenote WHERE `gracenote`.`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) group BY CallSign ORDER by CallSign
// select GROUP_CONCAT(EventTitle),CallSign from gracenote WHERE `gracenote`.`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) group BY CallSign ORDER by CallSign   
//$record  = Gracenote::select('StationName','CallSign','EventTitle','EventDescription','StartDateTime','EndDateTime')->whereRaw('`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)')->orderBy('CallSign', 'asc');
// SELECT CallSign,StationName,
      // JSON_ARRAYAGG(
      //     JSON_OBJECT(
      //         'title', EventTitle,
      //         'start', StartDateTime,'end', EndDateTime )
      // ) AS programs
//     FROM gracenote
//     GROUP BY CallSign,StationName
// $record  = Gracenote::select('CallSign',DB::raw("JSON_ARRAYAGG( JSON_OBJECT( 'title', EventTitle, 'start', StartDateTime,'end', EndDateTime ) ) AS programs"))
// $record  = Gracenote::select('CallSign',DB::raw("GROUP_CONCAT( JSON_OBJECT('title', EventTitle, 'start', StartDateTime,'end', EndDateTime ) ) AS programs "),DB::raw('SEPARATOR'),DB::raw("'|'"))->groupBy('CallSign');
// $record  = Gracenote::select('CallSign','StationName',DB::raw("GROUP_CONCAT( JSON_OBJECT('title', EventTitle, 'start', StartDateTime,'end', EndDateTime )SEPARATOR '|' ) AS programs "))->groupBy('CallSign','StationName');
// SELECT CallSign,JSON_ARRAYAGG( JSON_OBJECT( 'title', sub.EventTitle, 'start', sub.StartDateTime,'end', sub.EndDateTime ) ) AS programs FROM ( SELECT * FROM `gracenote` WHERE `gracenote`.`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) ORDER BY `gracenote`.`StartDateTime` ASC ) AS sub GROUP BY sub.CallSign 
$imagePath=url("/uploads/channellogo/");
$ua = $request->server('HTTP_USER_AGENT');
$iPod    = stripos($ua,"iPod");
$iPhone  = stripos($ua,"iPhone");
$iPad    = stripos($ua,"iPad");
$iPadOS = stripos($ua,"Macintosh");
$appletv = stripos($ua,"appletv");
$Android = stripos($ua,"Android");
if( $iPod || $iPhone||$iPad||$iPadOS ||$appletv&&!$Android) {
    DB::statement(DB::raw('SET SESSION group_concat_max_len = @@max_allowed_packet'));
$record  = Gracenote::select("sub.callSign as CallSign","id","StationName","url",DB::raw("CONCAT('$imagePath/',`image`) logo"))

->selectRaw(" concat( '[', group_concat(json_object( 'title', EventTitle,'start', UNIX_TIMESTAMP(StartDateTime),'end', UNIX_TIMESTAMP(EndDateTime) ) Order By StartDateTime ), ']') programs")
->fromSub(function ($query) {
  $query->select(DB::raw('IFNULL(gracenote.StationName,channels.name) StationName'),DB::raw('IFNULL(channels.callSign,channels.name) callSign'),
  DB::raw('IFNULL(gracenote.EventTitle,channels.name) EventTitle'),DB::raw('IFNULL(gracenote.StartDateTime,DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s")) StartDateTime'),
  DB::raw('IFNULL(gracenote.EndDateTime,DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 3 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")) EndDateTime'),'channels.id',DB::raw('IFNULL(channels.image,"notfound.png") image'),'channels.url')
  ->from('gracenote')
  ->rightJoin('channels', 'gracenote.CallSign', '=', 'channels.callSign')

  ->whereRaw('gracenote.`StartDateTime` BETWEEN DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s") 
  AND DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 3 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")   or `channels`.`callSign` IS NULL')
   ->orderBy('gracenote.StartDateTime', 'asc');
    }, 'sub')
->orderBy('sub.callSign', 'asc')
->groupBy('sub.callSign','StationName','image','url','id');
    } else {
        DB::statement(DB::raw('SET SESSION group_concat_max_len = @@max_allowed_packet'));
$record  = Gracenote::select("sub.callSign as CallSign","id","StationName","dash_url as url",DB::raw("CONCAT('$imagePath/',`image`) logo"))

->selectRaw(" concat( '[', group_concat(json_object( 'title', EventTitle,'start', UNIX_TIMESTAMP(StartDateTime),'end', UNIX_TIMESTAMP(EndDateTime) ) Order By StartDateTime ), ']') programs")
->fromSub(function ($query) {
  $query->select(DB::raw('IFNULL(gracenote.StationName,channels.name) StationName'),DB::raw('IFNULL(channels.callSign,channels.name) callSign'),
  DB::raw('IFNULL(gracenote.EventTitle,channels.name) EventTitle'),DB::raw('IFNULL(gracenote.StartDateTime,DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s")) StartDateTime'),
  DB::raw('IFNULL(gracenote.EndDateTime,DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 3 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")) EndDateTime'),'channels.id',DB::raw('IFNULL(channels.image,"notfound.png") image'),'channels.dash_url')
  ->from('gracenote')
  ->rightJoin('channels', 'gracenote.CallSign', '=', 'channels.callSign')

  ->whereRaw('gracenote.`StartDateTime` BETWEEN DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s") 
  AND DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 3 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")   or `channels`.`callSign` IS NULL')
   ->orderBy('gracenote.StartDateTime', 'asc');
    }, 'sub')
->orderBy('sub.callSign', 'asc')
->groupBy('sub.callSign','StationName','image','dash_url','id');
    }
    

// SELECT CallSign,StationName,
// GROUP_CONCAT(
//   JSON_OBJECT('title', EventTitle,
//                 'start', StartDateTime,'end', EndDateTime 
//   ) SEPARATOR '|'
// ) AS programs
//     FROM gracenote
//     GROUP BY CallSign,StationName
// ->whereRaw('`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)')->orderBy('CallSign', 'asc');
  
// $reco=$record->toSql(); echo $reco;exit; 
$reco=$record->get(); //echo $reco;exit; 
   if ($reco->isEmpty()) {
      $response = [
          'success' => false,
          'data' => 'Empty',
          'message' => 'Channel not found.',
          'response_code'=>404,
      ];
      return response()->json($response, 404);
  }  
      // return response()->json($record->toSql());
      // return json_encode($api);

      $response = [
          'success' => true,
          'date'=>date("Y-m-d"),
          'data' => $reco,
         
          'message' => 'Schedule data for 3 days found',
          'response_code'=>200,
      ];
      // return response()->json($reco);
     
  return response()->json($response, 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
  //  return  response()->json([
  //     'clients' => $this->transformClientCollection($api)
  //  ], 200);
  }
  //channel with 7 day of schdule
  public function frontEpgchannelDaysSchedule(){
    $imagePath=url("/uploads/channellogo/");
    $input=url('livetv/');
    DB::statement(DB::raw('SET SESSION group_concat_max_len = @@max_allowed_packet'));
    $record  = Gracenote::select(["sub.id","sub.callSign","StationName AS name","genres",DB::raw("CONCAT('$imagePath/',`image`) icon")])
    ->selectRaw("CAST( CONCAT('[\"', REPLACE(GROUP_CONCAT(DISTINCT IFNULL(g.name, 'Others')), ',', '\",\"'), '\"]') AS JSON ) genrename")
    ->selectRaw(" concat( '[', group_concat(json_object( 'title', EventTitle,'start', (UNIX_TIMESTAMP(StartDateTime)*1000),'end', (UNIX_TIMESTAMP(EndDateTime)*1000) ) Order By StartDateTime ), ']') events")
    ->leftJoin('genre as g', DB::raw("FIND_IN_SET(g.id, sub.genres)"), DB::raw('>'),DB::raw('0'))
    ->fromSub(function ($query) {
        $query->select(DB::raw('IFNULL(gracenote.StationName,channels.name) StationName'),DB::raw('IFNULL(channels.callSign,channels.name) callSign'),
        DB::raw('IFNULL(gracenote.EventTitle,channels.name) EventTitle'),DB::raw('IFNULL(gracenote.StartDateTime,DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s") ) StartDateTime'),
        DB::raw('IFNULL(gracenote.EndDateTime,DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 3 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")) EndDateTime'),'channels.id','channels.genres',DB::raw('IFNULL(channels.image,"notfound.png") image'),'channels.url')
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

  //channel with 7 day of schdule
  public function oldchannelDaysSchedule(){
    //  SELECT * FROM `gracenote` WHERE `gracenote`.`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) ORDER BY `gracenote`.`StartDateTime` ASC
  //   select GROUP_CONCAT(EventTitle SEPARATOR ', ' ORDER BY StartDateTime ASC) programs,CallSign from gracenote WHERE `gracenote`.`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)  group BY CallSign ORDER by CallSign
  //   SELECT CallSign, JSON_ARRAYAGG( JSON_OBJECT( 'title', EventTitle, 'start', StartDateTime,'end', EndDateTime ) ) AS programs FROM gracenote GROUP BY CallSign
// select count(EventTitle),CallSign from gracenote WHERE `gracenote`.`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) group BY CallSign ORDER by CallSign
// select GROUP_CONCAT(EventTitle),CallSign from gracenote WHERE `gracenote`.`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) group BY CallSign ORDER by CallSign   
//$record  = Gracenote::select('StationName','CallSign','EventTitle','EventDescription','StartDateTime','EndDateTime')->whereRaw('`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)')->orderBy('CallSign', 'asc');
// SELECT CallSign,StationName,
      // JSON_ARRAYAGG(
      //     JSON_OBJECT(
      //         'title', EventTitle,
      //         'start', StartDateTime,'end', EndDateTime )
      // ) AS programs
//     FROM gracenote
//     GROUP BY CallSign,StationName
// $record  = Gracenote::select('CallSign',DB::raw("JSON_ARRAYAGG( JSON_OBJECT( 'title', EventTitle, 'start', StartDateTime,'end', EndDateTime ) ) AS programs"))
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
// SELECT CallSign,StationName,
// GROUP_CONCAT(
//   JSON_OBJECT('title', EventTitle,
//     FROM gracenote
//     GROUP BY CallSign,StationName
// ->whereRaw('`StartDateTime` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)')->orderBy('CallSign', 'asc');
  
$reco=$record->get();   
   if ($reco->isEmpty()) {
      $response = [
          'success' => false,
          'data' => 'Empty',
          'message' => 'Channel not found.',
          'response_code'=>404,
      ];
      return response()->json($response, 404);
  }  
      // return response()->json($record->toSql());
      // return json_encode($api);

      $response = [
          'success' => true,
          'date'=>date("Y-m-d"),
          'data' => $reco,
         
          'message' => 'Schedule data for 3 days found',
          'response_code'=>200,
      ];
      // return response()->json($reco);
     
  return response()->json($response, 200, [], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
  //  return  response()->json([
  //     'clients' => $this->transformClientCollection($api)
  //  ], 200);
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
        public function frontEpgchannelDaysSchedule2(){
            $imagePath=url("/uploads/channellogo/");
            $input=url('livetv/');
            DB::statement(DB::raw('SET SESSION group_concat_max_len = @@max_allowed_packet'));
            $record  = Gracenote::select(["sub.id","sub.callSign","StationName AS name","TimeZoneName","genres",DB::raw("CONCAT('$imagePath/',`image`) icon")])
            ->selectRaw("CAST( CONCAT('[\"', REPLACE(GROUP_CONCAT(DISTINCT IFNULL(g.name, 'Others')), ',', '\",\"'), '\"]') AS JSON ) genrename")
            ->selectRaw(" concat( '[', group_concat(json_object( 'title', EventTitle,'start', StartDateTime,'end', EndDateTime ) Order By StartDateTime ), ']') events")
            ->leftJoin('genre as g', DB::raw("FIND_IN_SET(g.id, sub.genres)"), DB::raw('>'),DB::raw('0'))
            ->fromSub(function ($query) {
                $query->select(DB::raw('IFNULL(gracenote.StationName,channels.name) StationName'),DB::raw('IFNULL(gracenote.TimeZoneName,"US/Pacific") TimeZoneName'),DB::raw('IFNULL(channels.callSign,channels.name) callSign'),
                DB::raw('IFNULL(gracenote.EventTitle,channels.name) EventTitle'),DB::raw('IFNULL(gracenote.StartDateTime,DATE_FORMAT(CONCAT(CURDATE(), " 00:00:00"), "%Y-%m-%d %H:%i:%s") ) StartDateTime'),
                DB::raw('IFNULL(gracenote.EndDateTime,DATE_FORMAT(CONCAT(date_add(CURDATE(), interval 3 day), " 23:59:59"), "%Y-%m-%d %H:%i:%s")) EndDateTime'),'channels.id','channels.genres',DB::raw('IFNULL(channels.image,"notfound.png") image'),'channels.url')
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
             if($address['TimeZoneName']=="Pacific Observing"){
                $address['eventcount']=count($address->events);
                
                foreach($address->events as $element){
                    
                    $address= array_push($element, "manzana", "arándano");
                    
                }
                
               
             }
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
