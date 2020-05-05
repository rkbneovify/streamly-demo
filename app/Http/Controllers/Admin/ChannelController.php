<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\ChannelStatus;

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
        $record = Channel::all();
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
        return view('pages.channels.show', [
                'record' =>$channel,
        ]);

    }    /**
     * Show the form for creating a new resource.
     *
     * @param  Create  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Create $request)
    {

        return view('pages.channels.create', [
            'model' => new Channel,

        ]);
    }    /**
     * Store a newly created resource in storage.
     *
     * @param  Store  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        $model=new Channel;
        $model->fill($request->all());

        if ($model->save()) {
            
            session()->flash('app_message', 'Channel saved successfully');
            return redirect()->route('channels.index');
            } else {
                session()->flash('app_message', 'Something is wrong while saving Channel');
            }
        return redirect()->back();
    } /**
     * Show the form for editing the specified resource.
     *
     * @param  Edit  $request
     * @param  Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        return view('pages.channels.edit', [
            'model' => $channel,

            ]);
    }    /**
     * Update a existing resource in storage.
     *
     * @param  Update  $request
     * @param  Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request,Channel $channel)
    {
        $channel->fill($request->all());

        if ($channel->save()) {
            
            session()->flash('app_message', 'Channel successfully updated');
            return redirect()->route('channels.index');
            } else {
                session()->flash('app_error', 'Something is wrong while updating Channel');
            }
        return redirect()->back();
    }    /**
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
}
