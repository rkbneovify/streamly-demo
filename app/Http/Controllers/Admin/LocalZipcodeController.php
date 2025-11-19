<?php

namespace App\Http\Controllers\Admin;

use App\LocalZipcode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class LocalZipcodeController extends Controller
{
    public function index(Request $request)
    {
        $query = LocalZipcode::query();



        $query->groupBy('zipcode');

        $query->when($request->has('search'), function ($q) use ($request) {
            $search = $request->input('search');
            $q->where('zipcode', 'like', "%$search%")
                ->orWhere('city', 'like', "%$search%")
                ->orWhere('state', 'like', "%$search%");
        });
        $query->when($request->has('status'), function ($q) use ($request) {
            $status = $request->input('status');
            if ($status == '1' || $status == '0') {
                $q->where('status', $status);
            }
        });
        $query->when($request->has('created'), function ($q) use ($request) {
            if ($request->input('created') === 'asc') {
                $q->orderBy('created_at', 'asc');
            } else {
                $q->orderBy('created_at', 'desc');
            }

        });
        $limit = $request->input('limit', 10);
        $zipcodes = $query->paginate($limit);

        if ($request->ajax()) {
            return view('admin.zipcodes.local.index-table', compact('zipcodes'));
        }
        return view('admin.zipcodes.local.index', compact('zipcodes'));
    }


}


