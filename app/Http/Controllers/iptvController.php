<?php

namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class iptvController extends Controller

    {
        public function index() 
        {
           //return 'iptv';
           return view('iptv');
        }

        public function submitInfo(Request $request)
        {
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'dma' => 'string|max:255',
            'company_name' => 'string|max:255',
            'subscribers' => 'integer',
        ]);

        // Process the form data here, e.g., save it to a database
        $saveData = DB::table('iptv')->insert(
            array(
               'name' => $request->name,
               'email' => $request->email,
               'phone' => $request->phone,
               'dma' => $request->dma,
               'company_name' => $request->company_name,
               'subscribers' => $request->subscribers
            )
         );

        // Redirect back to the form with a success message
        if($saveData){
            return redirect('managed-iptv')->with('success', 'Information submitted successfully!');
        }else{
            return redirect('managed-iptv')->with('error', 'OOPS! Technical Error');
        }
        }

    }