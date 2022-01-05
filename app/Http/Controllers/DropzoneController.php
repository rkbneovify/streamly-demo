<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DropzoneController extends Controller
{
    function index()
    {
     return view('dropzone');
    }

    function upload(Request $request)
    {
     $images = $request->file('file');
//    dump($images);
   $filenameArr=$request->filenames;
   $customnameArr=$request->description;
   
//    $c = array_combine($filenameArr, $customnameArr);
//    dd($c);

   foreach($images as $key => $fromFilename) {
    $toFilename = $customnameArr[$key].'.'.$fromFilename->extension();
    $fromFilename->move(public_path('/uploads/channellogo'), $toFilename);
    
}
 

     return response()->json(['success' => "Images uploaded"]);
    }

    function fetch()
    {
        // $destinationPath = public_path('/uploads/channellogo');
            // $imagePath = $destinationPath. "/".  $name;
            // $image->move($destinationPath, $name);
            // $model->image = $name;
     $images = \File::files(public_path('/uploads/channellogo'));
     $output = '<div class="row">';
     foreach($images as $image)
     {
      $output .= '
      <div class="col-md-2" style="margin-bottom:16px;" align="center">
                <img src="'.asset('/uploads/channellogo/' . $image->getFilename()).'" class="img-thumbnail" width="175" height="175" style="height:175px;background-color:black;" />
                <button type="button" class="btn btn-link remove_image" id="'.$image->getFilename().'">Remove '.$image->getFilename().'</button>
            </div>
      ';
     }
     $output .= '</div>';
     echo $output;
    }

    function delete(Request $request)
    {
     if($request->get('name'))
     {
      \File::delete(public_path('/uploads/channellogo/' . $request->get('name')));
     }
    }
}
