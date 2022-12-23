<?php

namespace App\Http\Controllers;


class LoginGuideController extends Controller
{   
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['index']]);
    }

    public function index(){

         $slide1 = [
            'sno' => '1',
            'image' => ''.env("APP_URL").'/uploads/screen/onboarding_one.png',
            'title' => 'Watch on any device',
            'description' => 'Stream on your phone, tablet, laptop and TV without paying more',
         ];

         $slide2 = [
            'sno' => '2',
            'image' => ''.env("APP_URL").'/uploads/screen/boarding_two.png',
            'title' => 'Download and go',
            'description' => 'Save your data, watch offline on a plane, train and submarine.',
         ];

         $slide3 = [
            'sno' => '3',
            'image' => ''.env("APP_URL").'/uploads/screen/boarding_three.png',
            'title' => 'No pesky contracts',
            'description' => 'Join today, Cancle anytime.',
         ];

         $response = [
            'success' => true,
            'count' => 3,
            'data' => [
                $slide1, $slide2, $slide3
            ],
            'response_code'=>200,
            ];
    
            return response()->json($response, 200);
    }
}