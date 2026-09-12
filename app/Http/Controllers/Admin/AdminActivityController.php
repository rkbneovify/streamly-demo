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
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminActivityController extends Controller
{


    public function index(Request $request)
    {

        DB::beginTransaction();
        try {

            ActivityLog::resolveNotification();
            // Step 1: get distinct dates

            $events =  ActivityLog::where('is_admin', 1)->groupBy('event')->pluck('event');
            $data = ActivityLog::where('is_admin', 1)->filterEvent($request->event)->latest()
                ->paginate(10);

            DB::commit();
            if ($request->ajax()) {
                return view('admin.activity.table', compact('data', 'events'));
            }
            return view('admin.activity.index', compact('data', 'events'));
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            // Alert::error("Something went wrong !");
            Alert::error($th->getMessage());
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
            $events =  ActivityLog::where('user_id', $id)->groupBy('event')->pluck('event');
            $data = ActivityLog::where('user_id', $id)->filterEvent($request->event)->latest()
                ->paginate(10);
            // return response()->json($data);
            DB::commit();
            if ($request->ajax()) {
                return view('admin.activity.table-user', compact('user', 'data', 'events'));
            }
            return view('admin.activity.index-user', compact('user', 'data', 'events'));
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Alert::error("Something went wrong !");
            return redirect()->back();
        }
    }

    public function bySession($token = null, Request $request)
    {


        DB::beginTransaction();
        try {

            $activity =   ActivityLog::where('auth_token', $token)->latest()->first();
            if ($activity == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }
            ActivityLog::resolveNotification();
            $user = User::where('id', $activity->user_id)->with('roles')->first();
            if ($user == null) {
                toast('User not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }

            $events =  ActivityLog::where('auth_token', $token)->groupBy('event')->pluck('event');
            $data = ActivityLog::where('auth_token', $token)->filterEvent($request->event)->latest()
                ->paginate(10);
            // return response()->json($events);
            DB::commit();
            if ($request->ajax()) {
                return view('admin.activity.table-user', compact('user', 'data' . 'events'));
            }
            return view('admin.activity.index-user', compact('user', 'data', 'events'));
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            // Alert::error($th->getMessage());
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

            $events =  ActivityLog::whereNull('category')->whereNotNull('passcode')->where('passcode', $passcode)->groupBy('event')->pluck('event');
            $data = ActivityLog::whereNull('category')->whereNotNull('passcode')->where('passcode', $passcode)->latest()
                ->paginate(10);
            // return response()->json(["activity" => $data]);
            DB::commit();
            if ($request->ajax()) {
                return view("admin.activity.sub_user.table", compact('hoauser', 'data', 'events'));
            }
            return view('admin.activity.sub_user.index', compact('hoauser', 'data', 'events'));
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Alert::error("Something went wrong !");
            return redirect()->back();
        }
    }

    public function subUserBySession($token = null, Request $request)
    {


        DB::beginTransaction();
        try {

            $activity =   ActivityLog::where('auth_token', $token)->latest()->first();
            if ($activity == null) {
                toast('Data not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }
            ActivityLog::resolveNotification();
            $hoauser = HoaUser::where('passcode',   $activity->passcode)->first();
            if ($hoauser == null) {
                toast('User not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }
            $events =  ActivityLog::where('auth_token', $token)->groupBy('event')->pluck('event');
            $data = ActivityLog::where('auth_token', $token)
                ->filterEvent($request->event)
                ->latest()
                ->paginate(10);
            // return response()->json(["activity" => $data]);
            DB::commit();
            if ($request->ajax()) {
                return view("admin.activity.sub_user.table", compact('hoauser', 'data', 'events'));
            }
            return view('admin.activity.sub_user.index', compact('hoauser', 'data', 'events'));
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Alert::error("Something went wrong !");
            return redirect()->back();
        }
    }
}
