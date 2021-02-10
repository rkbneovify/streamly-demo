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

        // SELECT CallSign FROM gracenotechannels WHERE CallSign LIKE '%bl%' UNION
        //  SELECT StationName FROM gracenotechannels WHERE StationName LIKE '%bl%' ORDER BY 1 LIMIT 10
//         $first = DB::table('gracenotechannels')
//        ->where('StationName', 'LIKE', '%'.$request->q.'%');

// $users = DB::table('gracenotechannels')
// ->where('CallSign', 'LIKE', '%' . $$request->q . '%')
            
//             ->union($first)
//             ->get();
	}
}