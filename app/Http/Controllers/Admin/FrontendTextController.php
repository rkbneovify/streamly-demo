<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FrontendText;
use Illuminate\Http\Request;

class FrontendTextController extends Controller
{

    public function index()
    {   echo $frontendjson = FrontendText::where('frontendjson->country', 'India')->get(); exit;
        return view('admin.frontendtext.edit', compact('frontendjson'));
        
    } 
    
}
