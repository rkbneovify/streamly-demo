<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChannelSlider;
use Illuminate\Http\Request;

class ChannelSliderApiController extends Controller
{
    public function getChannelSliders()
    {
        $channel_slides = ChannelSlider::with('channel:id,name,callSign,stationName,url,dash_url,smooth_url,zip_code')
            ->select('channel_id', 'program_name', 'description', 'status', 'image')
            ->get()
            ->map(function ($slide) {
                $slide->image = env('APP_URL') . $slide->image;
                return $slide;
            });
        // $channel_slides = ChannelSlider::query()
        //     ->with(['channel' => function ($query) {
        //         $query->select('id', 'name', 'callSign', 'stationName', 'url', 'dash_url', 'smooth_url', 'zip_code');
        //     }])
        //     ->select('channel_id', 'program_name', 'description', 'status', 'image')
        //     ->get();

        // $channel_slides = ChannelSlider::Join('channels', 'channel_slider.channel_id', '=', 'channels.id')
        //     ->select('channel_slider.channel_id', 'channel_slider.program_name', 'channel_slider.description', 'channel_slider.status', 'channel_slider.image', 'channels.name', 'channels.callSign', 'channels.stationName', 'channels.url', 'channels.dash_url', 'channels.smooth_url', 'channels.zip_code')
        //     ->get();

        //return $slides;
        return response()->json([
            'data' => $channel_slides
        ]);
    }
}
