<?php

namespace App\Http\Controllers;
use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class LoginGuideController extends Controller
{   
    public function __construct()
    {
      $this->middleware('auth', ['except' => ['index']]);
    }

    public function index(){

      $slideData = DB::table('sliders')
      ->select()
      ->get();

      $Slidearray =[];
      foreach ($slideData as $slide){
         if($slide->status == 'active'){
         $slides = [
            'sno' => $slide->id,
            'image' => ''.env("APP_URL").'/uploads/screen/'.$slide->image,
            'title' => $slide->text,
            'description' => $slide->description,
         ];
         array_push($Slidearray, $slides);
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
            'count' => count($Slidearray),
            'slide_interval_seconds' => $slideData[0]->slide_interval,
            'slide_interval_milliseconds' => $slideData[0]->slide_interval * 1000,
            'data' => $Slidearray,
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

    public function slideview(){
           $slide = DB::table('sliders')->get();
            //return $slides;
            return view('admin.sliders.slideview', compact('slide'));
    }

    public function slidecreate(){
       return view('admin.sliders.slidecreate');
    }

    public function singleslideview($id){
      $slideUpdate = DB::table('sliders')->where('id', $id)->get();
       //return $slideUpdate;
       return view('admin.sliders.singleslideview', compact('slideUpdate'));
    }

    public function slidedelete($id){

      $deleteSlide= DB::table('sliders')->delete($id); 
      if($deleteSlide){
         return redirect('admin/slide/view')->with('info','Slide deleted sucessfully.');
     }else{
         return redirect('admin/slide/view')->with('info','Slide not found.'); 
     }
    }

    public function slideupdate(Request $request, $id){
      $text=$request['heading'];
      $description=$request['description'];
      $status=$request['status'];
      $slideDelete = DB::table('sliders')
        ->where('id', $id)
        ->update(array(
         'text' => $text,
         'description' => $description,
         'status' => $status
      )); 

      if($slideDelete){
         return redirect('admin/slide/view')->with('info','Slide updated sucessfully.');
     }else{
         return redirect('admin/slide/view')->with('info','Slide not updated.'); 
     }

  }

     /**
     * Store a newly created resource in storage.
     *
     * @param  Store  $request
     * @return \Illuminate\Http\Response
     */

  public function slideadd(Request $request){

   $this->validate($request, [
      'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

   $text=$request['heading'];
   $description=$request['description'];
   $status=$request['status'];
   $image=$request->hasFile('image');

   if ($request->hasFile('image')) {
      $image = $request->file('image');
      $name = $image->getClientoriginalName();
      $destinationPath = public_path('/uploads/screen');
      $imagePath = $destinationPath. "/".  $name;
      $image->move($destinationPath, $name);
    }

   $slideAdd = DB::table('sliders')->insert(
      array(
         'text' => $text,
         'description' => $description,
         'status' => $status,
         'image' => $name
      )
   );

   if($slideAdd){
      return redirect('admin/slide/create')->with('info','Slide added sucessfully.');
  }else{
      return redirect('admin/slide/create')->with('info','Slide not inserted.'); 
  }
  }
}