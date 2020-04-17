<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Language;
use App\Models\Genre;
use Carbon\Carbon;

/**
 * Description of ChannelController
 *
 * 
 */

class ChannelController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('pages.channel.index', ['records' => Channel::paginate(10)]);
    }    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param  Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Channel $channel)
    {
        return view('pages.channel.show', [
                'record' =>$channel,
        ]);

    }    /**
     * Show the form for creating a new resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$language = Language::all(['id']);
		$genre = Genre::all(['id']);

        return view('pages.channel.create', [
            'model' => new Channel,
			"language" => $language,
			"genre" => $genre,

        ]);
    }    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $model=new Channel;
       // $model->fill($request->all());
        $model->language_id = 1;
        $model->genre_id = 1;
        $model->title = $request->title;
        $model->description = $request->description;
        $model->release_year = $request->release_year;
        $model->length = 2;
        $model->rating = $request->rating;
        $model->stream_url = $request->stream_url;
        $model->status = 1;
        $model->created_on = Carbon::now();
        $model->modified_on = Carbon::now();
        if ($model->save()){
            
            // session()->flash('app_message', 'Channel saved successfully');
            // return redirect()->route('channel.index');
            return response()->json([
                'success' => true,
                'msg'=>'Channel saved successfully',
                'data' => $model
            ]);
            } else {
                // session()->flash('app_message', 'Something is wrong while saving Channel');
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, Channel could not be added.'
                ], 500);
            }
        return redirect()->back();
    } /**
     * Show the form for editing the specified resource.
     *
     * @param  Request  $request
     * @param  Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Channel $channel)
    {
		$language = Language::all(['id']);
		$genre = Genre::all(['id']);

        return view('pages.channel.edit', [
            'model' => $channel,
			"language" => $language,
			"genre" => $genre,

            ]);
    }    /**
     * Update a existing resource in storage.
     *
     * @param  Request  $request
     * @param  Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {    
        

        
            $channel = Channel::find($id);
           // $task = $this->user->tasks()->find($id);
            if(!$channel){
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, channel with id ' . $id . ' cannot be found.'
                ], 400);
            }
            
        
     //  $channel->fill($request->all());
       $channel->language_id = 1;
       
       
       $channel->title = $request['title'];
       $channel->description = $request['description'];
       $channel->release_year = $request['release_year'];
       $channel->rating = $request['rating'];
       $channel->stream_url = $request['stream_url'];
       $channel->status = $request['status'];
       //$channel->length = $request['length'];
       $channel->length = 1;
       $channel->modified_on = Carbon::now();
       if($channel->save())
       {return response()->json([
        'success' => true,
        'msg'=>'Channel successfully updated',
        'data' => $channel
    ]);} else {
              return response()->json([
                    'success' => false,
                    'message' => 'Something is wrong while updating Channel.',
                    'error'=>$e
                ], 500);
    }
       
      
        
            
            // session()->flash('app_message', 'Channel successfully updated');
            // return redirect()->route('channel.index');
           
        
                
                // session()->flash('app_error', 'Something is wrong while updating Channel');
              
            
       // return redirect()->back();
    }    /**
     * Delete a  resource from  storage.
     *
     * @param  Request  $request
     * @param  Channel  $channel
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {    
        $channel = Channel::find($id);
        
         if(!$channel){
             return response()->json([
                 'success' => false,
                 'message' => 'Sorry, channel with id ' . $id . ' cannot be found.'
             ], 400);
         }
         $channel->status = 2;
         if($channel->save())
         {return response()->json([
          'success' => true,
          'msg'=>'Channel successfully deleted',
         // 'data' => $channel
      ]);} else {
                return response()->json([
                      'success' => false,
                      'message' => 'Error occurred while deleting Channel',
                      'error'=>$e
                  ], 500);
      }
        
       // return redirect()->back();
    }
}
