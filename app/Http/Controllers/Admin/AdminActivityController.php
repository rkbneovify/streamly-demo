<?php

namespace App\Http\Controllers\Admin;

use App\ActivityLog;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class AdminActivityController extends Controller
{


    public function index($id, Request $request)
    {
        DB::beginTransaction();
        try {
            ActivityLog::where('is_read', 0)->update([
                "is_read" => 1
            ]);

            $user = User::where('id', $id)->with('roles')->first();
            if ($user == null) {
                toast('User not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }
            $activities = ActivityLog::where('user_id', $user->id)
                ->with('effectedUser')
                ->latest()
                ->get()->groupBy(function ($log) {
                    return $log->created_at->format('d/m/Y');
                });
            // return response()->json($activities);

            DB::commit();
            if ($request->ajax()) {
                return view('admin.activity.table', compact('user', 'activities'));
            }
            return view('admin.activity.index', compact('user', 'activities'));
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

            $perPage = 1; // number of dates per page
            $page = request()->get('page', 1);

            ActivityLog::where('is_read', 0)->update([
                "is_read" => 1
            ]);
            $user = User::where('id', $id)->with('roles')->first();
            if ($user == null) {
                toast('User not found !', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }

            // Step 1: get distinct dates
            $dates = ActivityLog::where('user_id', $id)->selectRaw('DATE(created_at) as date')
                ->groupBy('date')
                ->orderByDesc('date')
                ->pluck('date');

            // Step 2: paginate dates manually
            $paginatedDates = $dates
                ->slice(($page - 1) * $perPage, $perPage)
                ->values();

            // Step 3: get logs for those dates
            $activities = ActivityLog::where('user_id', $id)->with('user')
                ->whereIn(DB::raw('DATE(created_at)'), $paginatedDates)
                ->orderByDesc('created_at')
                ->get()->groupBy(function ($log) {
                    return $log->created_at->format('d/m/Y');
                });

            // Step 4: paginator
            $paginator = new LengthAwarePaginator(
                $paginatedDates,
                $dates->count(),
                $perPage,
                $page,
                ['path' => request()->url()]
            );

            // return response()->json(["activity" => $activities, "paginator" => $paginator]);
            DB::commit();
            if ($request->ajax()) {
                return view('admin.activity.table', compact('user', 'activities', 'paginator'));
            }
            return view('admin.activity.index', compact('user', 'activities', 'paginator'));
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            Alert::error("Something went wrong !");
            return redirect()->back();
        }

    }


}
