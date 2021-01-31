<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Gracenote;
use App\Models\Genre;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
/**
 * Description of DashboardController
 *
 */

class DashboardController extends Controller
{
         
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() 
    {
        $users = User::count();
        return view('admin.dashboard', ['users' => $users]);
    }
    public function testing() {
        $encoded_auth_xml = getKeyOSAuthToken();
        return view('frontend.drm.playlist', compact('encoded_auth_xml'));
    }

}
