<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gracenote;
use App\Models\Gracenotechannel;
use App\User;

class SearchController extends Controller
{
	//
	public function searchUsers(Request $request)
	{
        // return Gracenote::where('StationName', 'LIKE', '%'.$request->q.'%')
        // ->orWhere('EventTitle', 'like', '%' . $$request->q . '%')
        // ->get();
        return Gracenotechannel::where('StationName', 'LIKE', '%'.$request->q.'%')
        //  ->orWhere('CallSign', 'LIKE', '%' . $$request->q . '%')
        ->get();
	}
}