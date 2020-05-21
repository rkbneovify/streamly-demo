<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Gracenote;
use App\Models\Genre;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
/**
 * Description of ChannelController
 *
 */

class ChannelController extends Controller
{
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
    
    public function channeljson() 
    {   $extrafield='genre_name';
        $forwhen=true;
        $genres = Genre::pluck('name', 'id'); 
        $extrafield='genre_name';
        //Option 1            
        $record = DB::table('channels')
        ->join('gracenotechannels', 'gracenotechannels.CallSign', '=', 'channels.callSign')
        
       
        ->select('channels.id','gracenotechannels.StationName', 'channels.callSign','channels.genres', 'channels.url')
        ->when(true, function($query) use ($genres,$extrafield){
           $genreInNum=$query->pluck('genres');
           $genres = Genre::pluck('name', 'id'); 
          // print_r($genreInNum);exit;
          if(!is_null($genreInNum)){
            $arrVal=explode(',',$genreInNum);}
            
            $options = '';
            foreach ($genres as $id => $genre) {
                if(in_array($id, $arrVal)){
                                 
                $options .= ''.$genre.' ';
                $query->addSelect(DB::raw("' . $options . ' as $extrafield"));
            } 
                
                
            }
            
    
            
     
         
        })
        
        ->orderBy('channels.callSign', 'asc');
        // SELECT name FROM genre WHERE id IN (1,2,3)
        // SELECT group_concat(concat_ws(',', name)) AS genresss FROM genre WHERE id IN (1,2,3)
        // SELECT genres FROM `channels`
    //     $s=$record->pluck('genres');
    //     $genres = Genre::pluck('name', 'id'); 
    //     //$arrVal=explode(',',$s[]);
    //     foreach($s as $player)
    //     {
    //         echo $player;
    //     }
    //    // print_r($s);
    //     exit;
    //     $options = '';
    //     foreach ($genres as $id => $genre) {
    //         if(in_array($id, $arrVal)){
                             
    //         $options .= ''.$genre.' ';
    //     } else{
    //         $options .= '';}
            
    //     }
      $pagechannel=$record->paginate(10);
        
      return response()->json(array(
        'message' => $pagechannel, 
        'success' => true,
        //'author'=>$author,
        //'genre'=>$genre,
     ));
        return response()->json($record->toSql());
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

  $channel = Channel::where('name', 'LIKE', "%{$search}%")->Paginate(16);
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
        
        
        $input = $request->only('name', 'genre_id', 'url','status');
        $model=new Channel;
        if ($request->genres!= '') {
            $break_value = implode(",", $request->genres);
            $input['genres'] = $break_value;
        
        }
        
        $model->fill($input);

        if ($model->save()) {
            
            return redirect('admin/channelstatus')->with('info','Channel Added Successfully');
        
            } else {
                return redirect('admin/channelstatus')->with('info','Some error occured');
        
            }
    } /**
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
            'genre_id'=>'required'
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
        
        $search = '2020-05-14';
        $callsign = request()->get('search');
         $record  = Gracenote::where([
    ['CallSign', '=', $callsign],
    ['StartDateTime', 'LIKE', "%{$search}%"],
     ])->get();
         
        return response()->json($record);
        // SELECT * FROM `gracenote` WHERE `CallSign`= 'COMEDYP' AND `StartDateTime` LIKE '%2020-05-14%' ORDER BY `StartDateTime` ASC
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
      //  >rawColumns(['fullName', 'action'])
        ->make(true);
    }
}
