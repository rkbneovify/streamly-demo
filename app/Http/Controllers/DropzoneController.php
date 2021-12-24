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
//     dump($request);
// dd($image);
foreach ($images as $image) {
    # code...
    $imageName = $image->getClientOriginalName() . '.' . $image->extension();

    $image->move(public_path('images'), $imageName);
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
