<?php

namespace App\Http\Controllers\Admin;

use App\ActivityLog;
use App\HoaUser;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class AdminActivityController extends Controller
{


    public function index(Request $request)
    {

        DB::beginTransaction();
        try {

            ActivityLog::resolveNotification();
            // Step 1: get distinct dates
            $data = ActivityLog::where('is_admin', 1)->latest()
                ->paginate(10);

            DB::commit();
            if ($request->ajax()) {
                return view('admin.activity.table', compact('data'));
            }
            return view('admin.activity.index', compact('data'));
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Alert::error("Something went wrong !");
            return redirect()->back();
        }

    }

    public function index2($id, Request $request)
    {

        DB::beginTransaction();
        try {

            ActivityLog::resolveNotification();
            $user = User::where('id', $id)->with('roles')->first();
            if ($user == null) {
                toast('User not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }

            $data = ActivityLog::whereNull('passcode')->where('user_id', $id)->latest()
                ->paginate(10);
            // return response()->json(["activity" => $activities, "paginator" => $paginator]);
            DB::commit();
            if ($request->ajax()) {
                return view('admin.activity.table-user', compact('user', 'data'));
            }
            return view('admin.activity.index-user', compact('user', 'data'));
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Alert::error("Something went wrong !");
            return redirect()->back();
        }

    }

    public function subUserActivity($passcode, Request $request)
    {

        DB::beginTransaction();
        try {

            ActivityLog::resolveNotification();
            $hoauser = HoaUser::where('passcode', $passcode)->first();
            if ($hoauser == null) {
                toast('User not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }

            $data = ActivityLog::whereNull('category')->whereNotNull('passcode')->where('passcode', $passcode)->latest()
                ->paginate(10);
            // return response()->json(["activity" => $data]);
            DB::commit();
            if ($request->ajax()) {
                return view("admin.activity.sub_user.table", compact('hoauser', 'data'));
            }
            return view('admin.activity.sub_user.index', compact('hoauser', 'data'));
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Alert::error("Something went wrong !");
            return redirect()->back();
        }

    }


}
