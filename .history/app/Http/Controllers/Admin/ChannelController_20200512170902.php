<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Genre;

use Yajra\Datatables\Datatables;
/**
 * Description of ChannelController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
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
    }    /**
     * Display the specified resource.
     *
     * @param  Show  $request
     * @param  Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $channel = Channel::find($id); 
        return view('admin.channels.show', compact('channel'));

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
        dd($request->genres);
        
        $model=new Channel;
        if ($request->genres!= '') {
            $break_value = implode(",", $request->genres);
            $data['genres'] = $break_value;
            dd($break_value);
        }
        $model->fill($request->all());

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
       // dd($genres);
        return view('admin.channels.edit',compact('channel','genres','channelGenres'));
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
    public function destroy(Destroy $request, Channel $channel)
    {
        if ($channel->delete()) {
                session()->flash('app_message', 'Channel successfully deleted');
            } else {
                session()->flash('app_error', 'Error occurred while deleting Channel');
            }

        return redirect()->back();
    }

    public function channelData()
    {
        return Datatables::of(Channel::with('genre'))
        ->addColumn('action', function($row) {
            return '<a href="/admin/channelstatus/view/'. $row->id .'" class="btn btn-xs btn-primary">View</a> <a href="/admin/channelstatus/edit/'. $row->id .'" class="btn btn-xs btn-info">Edit</a>';
        })
        ->make(true);
    }
}
