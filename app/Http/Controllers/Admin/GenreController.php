<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Support\Facades\DB;

//use App\Traits\ImageUpload;programs
/**
 * Description of ChannelController
 *
 */

class GenreController extends Controller
{    //use ImageUpload; //Using our created Trait to access inside trait method
       /**
     * Display a listing of the resource.
     *
     * @param  Index  $request
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    { 
        $users = DB::table('genre')->get();

        return view('admin.viewgenre', compact('users'));
    }

    public function addgenre(){
        return view('admin.addgenre');
    }

    public function creategenre(Request $request){

        if($request){
            $add_genre = DB::table('genre')->insert(
                array(
                   'name' => $request->name,
                   'status' => '1',
                   'created_on' => date("Y/m/d h:i:s")
                )
             );

            return redirect('admin/addgenre')->with('info', 'Genre Added Successfully');
            
        }else{
            return redirect('admin/addgenre')->with('error', 'OOPS! Something Went Wrong.');
        }
    }

    public function deletegenre($id){

        $DeleteGenre = Genre::where('id', $id)->delete();

        if($DeleteGenre){
            return redirect('admin/allgenre')->with('info', 'Genre Deleted Successfully');
        }else{
        return redirect('admin/allgenre')->with('error', 'OOPS! Something Went Wrong.');
        }
    }
}