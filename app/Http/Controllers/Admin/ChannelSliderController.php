<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\ChannelSlider;
use Illuminate\Support\Facades\Validator;
// use Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ChannelSliderController extends Controller
{
    public function channelSlideCreate()
    {
        $channels = Channel::select(
            'channels.id',
            'channels.url',
            'channels.dash_url',
            'channels.smooth_url',
            DB::raw('IFNULL(gracenotechannels.StationName,channels.name) StationName'),
            DB::raw('IFNULL(gracenotechannels.CallSign,channels.name) CallSign'),
        )
            ->leftJoin('gracenotechannels', 'gracenotechannels.CallSign', '=', 'channels.callSign')
            ->where('channels.status', '=', '1')
            ->orderBy('CallSign', 'asc')->get();

        $channels = $channels->unique();
        return view('admin.channel_sliders.create-channel-slider', compact('channels'));
    }

    public function storeChannelSlide(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'channel' => 'required|exists:channels,id',
            'program_name' => 'required',
            'description' => 'required',
            'status' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } else {

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                // $name = $image->getClientoriginalName();
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/uploads/channel_slider');
                $imagePath = $destinationPath . "/" .  $name;
                $image->move($destinationPath, $name);
            }

            $slideAdd = new ChannelSlider();
            $slideAdd->channel_id = $request['channel'];
            $slideAdd->program_name = $request->program_name;
            $slideAdd->description =  $request['description'];
            $slideAdd->status = $request['status'];
            $slideAdd->image = '/uploads/channel_slider/' . $name;
            if ($request->hasFile('background_image')) {
                $background_image = $request->file('background_image');
                $name = time() . '.' . $background_image->getClientOriginalExtension();
                $destinationPath = public_path('/uploads/channel_slider');
                $imagePath = $destinationPath . "/" .  $name;
                $background_image->move($destinationPath, $name);
                $slideAdd->background_image = '/uploads/channel_slider/' . $name;
            }
            if ($slideAdd->save()) {
                return redirect('admin/channel-slider-create')->with('info', 'Slide added sucessfully.');
            } else {
                return redirect('admin/channel-slider-create')->with('info', 'Slide not inserted.');
            }
        }
    }

    public function allChannelSliders()
    {
        $channel_slides = ChannelSlider::all();
        //return $slides;
        return view('admin.channel_sliders.all-channel-sliders', compact('channel_slides'));
    }

    public function channelSlideDelete($id)
    {
        $slideUpdate = ChannelSlider::findOrFail($id);
        $oldImagePath = public_path($slideUpdate->image);
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }
        $deleteSlide = $slideUpdate->delete();
        if ($deleteSlide) {
            return redirect('admin/all-channel-sliders')->with('info', 'Slide deleted sucessfully.');
        } else {
            return redirect('admin/all-channel-sliders')->with('info', 'Slide not found.');
        }
    }

    public function channelSlideEdit($id)
    {
        $slideUpdate = ChannelSlider::findOrFail($id);
        // DB::table('channel_slider')->where('id', $id)->get();
        //return $slideUpdate;
        $channels = Channel::select(
            'channels.id',
            'channels.url',
            'channels.dash_url',
            'channels.smooth_url',
            DB::raw('IFNULL(gracenotechannels.StationName,channels.name) StationName'),
            DB::raw('IFNULL(gracenotechannels.CallSign,channels.name) CallSign'),
        )
            ->leftJoin('gracenotechannels', 'gracenotechannels.CallSign', '=', 'channels.callSign')
            ->where('channels.status', '=', '1')
            ->orderBy('CallSign', 'asc')->get();

        $channels = $channels->unique();
        return view('admin.channel_sliders.channel-slider-edit', compact('slideUpdate', 'channels'));
    }

    public function channelSlideUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'channel' => 'required|exists:channels,id',
            'program_name' => 'required',
            'description' => 'required',
            'status' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } else {

            $slideUpdate = ChannelSlider::find($id);

            if ($request->hasFile('image')) {
                // Remove previous image if it exists
                if ($slideUpdate->image) {
                    $oldImagePath = public_path($slideUpdate->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                // Upload new image
                $image = $request->file('image');
                $name = time() . '.' . $image->getClientOriginalExtension();                $destinationPath = public_path('/uploads/channel_slider');
                $imagePath = '/uploads/channel_slider/' . $name;
                $image->move($destinationPath, $name);

                $slideUpdate->image = $imagePath;
            }
            if ($request->hasFile('background_image')) {
                // Remove previous background_image if it exists
                if ($slideUpdate->background_image) {
                    $oldImagePath = public_path($slideUpdate->background_image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                // Upload new image
                $background_image = $request->file('background_image');
                $name = time() . '.' .$background_image->getClientOriginalExtension();
                $destinationPath = public_path('/uploads/channel_slider');
                $imagePath = '/uploads/channel_slider/' . $name;
                $background_image->move($destinationPath, $name);

                $slideUpdate->background_image = $imagePath;
            }

            $slideUpdate->channel_id = $request['channel'];
            $slideUpdate->program_name = $request->program_name;
            $slideUpdate->description =  $request['description'];
            $slideUpdate->status = $request['status'];

            if ($slideUpdate->save()) {
                return redirect('admin/all-channel-sliders')->with('info', 'Slide updated sucessfully.');
            } else {
                return redirect('admin/all-channel-sliders')->with('info', 'Slide not updated.');
            }
        }
    }
}
