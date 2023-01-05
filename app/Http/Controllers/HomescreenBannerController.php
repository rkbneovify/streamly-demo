<?php

namespace App\Http\Controllers;
use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class HomescreenBannerController extends Controller
{   
    public function __construct()
    {
      $this->middleware('jwt.verify');
    }

    public function index(){

      $bannerData = DB::table('banner_section')
      ->select()
      ->get();

      $Bannerarray =[];
      foreach ($bannerData as $banner){
         if($banner->status == 'active'){
         $banners = [
            'sno' => $banner->id,
            'title' => $banner->text,
            'description' => $banner->description,
            'image' => ''.env("APP_URL").'/uploads/banner/'.$banner->image,
         ];
         array_push($Bannerarray, $banners);
      }
      }

         // $slide1 = [
         //    'sno' => '1',
         //    'image' => ''.env("APP_URL").'/uploads/screen/onboarding_one.png',
         //    'title' => 'Watch on any device',
         //    'description' => 'Stream on your phone, tablet, laptop and TV without paying more',
         // ];

         // $slide2 = [
         //    'sno' => '2',
         //    'image' => ''.env("APP_URL").'/uploads/screen/boarding_two.png',
         //    'title' => 'Download and go',
         //    'description' => 'Save your data, watch offline on a plane, train and submarine.',
         // ];

         // $slide3 = [
         //    'sno' => '3',
         //    'image' => ''.env("APP_URL").'/uploads/screen/boarding_three.png',
         //    'title' => 'No pesky contracts',
         //    'description' => 'Join today, Cancle anytime.',
         // ];

         $response = [
            'success' => true,
            'count' => count($Bannerarray),
            // 'slide_interval_seconds' => $slideData[0]->slide_interval,
            // 'slide_interval_milliseconds' => $slideData[0]->slide_interval * 1000,
            'data' => $Bannerarray,
            'message' => 'banner section successfully fetched',
            'response_code'=>200,
            ];

            $error = [
               'success' => false,
               'data' => [],
               'response_code'=>420,
               ];

            if($response){
               return response()->json($response, 200);
            }else{
               return response()->json($error, 420);
            }
    }

}