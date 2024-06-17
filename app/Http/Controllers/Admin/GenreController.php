<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

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
        $genres = DB::table('genre')->get();

        return view('admin.viewgenre', compact('genres'));
    }

    public function addgenre()
    {
        return view('admin.addgenre');
    }

    public function creategenre(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'position' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } else {
            $genre = new Genre();
            $genre->name = $request->name;
            $genre->status = 1;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/uploads/genre');
                $image->move($destinationPath, $name);
                $genre->image = '/uploads/genre/' . $name;
            }
            $genre->position = $request->position;

            if ($genre->save()) {
                return redirect('admin/addgenre')->with('info', 'Genre Added Successfully');
            } else {
                return redirect('admin/addgenre')->with('error', 'OOPS! Something Went Wrong.');
            }
        }
    }

    public function deletegenre($id)
    {

        $DeleteGenre = Genre::where('id', $id)->delete();

        if ($DeleteGenre) {
            return redirect('admin/allgenre')->with('info', 'Genre Deleted Successfully');
        } else {
            return redirect('admin/allgenre')->with('error', 'OOPS! Something Went Wrong.');
        }
    }
}
