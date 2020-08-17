<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FrontendText;
use Illuminate\Http\Request;

class FrontendTextController extends Controller
{

    public function index()
    {  
        //   $frontendjson = FrontendText::where('frontendjson->id', 'like','%1%')->get(); 
        $frontendjson = FrontendText::select('id', 'frontendjson->gender as sex', 'frontendjson->skills as skills')->get();
      
        return view('admin.frontendtext.edit', compact('frontendjson'));
        
    } 
    public function update()
    {  
        # Get the user https://www.qcode.in/use-mysql-json-field-in-laravel/
        // $user = App\User::find(2);
        
        // Update the bio summery
        $user->update(['meta->bio->summery' => 'New sort bio goes here, pretty neat hah!']);
      
        return view('admin.frontendtext.edit', compact('frontendjson'));
        
    } 
    
}
