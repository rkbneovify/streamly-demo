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
    $fromFilename->move(public_path('images'), $toFilename);
    
}
 

     return response()->json(['success' => "Images uploaded"]);
    }

    function fetch()
    {
     $images = \File::allFiles(public_path('images'));
     $output = '<div class="row">';
     foreach($images as $image)
     {
      $output .= '
      <div class="col-md-2" style="margin-bottom:16px;" align="center">
                <img src="'.asset('images/' . $image->getFilename()).'" class="img-thumbnail" width="175" height="175" style="height:175px;" />
                <button type="button" class="btn btn-link remove_image" id="'.$image->getFilename().'">Remove</button>
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
      \File::delete(public_path('images/' . $request->get('name')));
     }
    }
}
