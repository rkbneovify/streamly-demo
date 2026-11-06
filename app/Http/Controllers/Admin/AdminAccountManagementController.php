<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\SessionTable;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAccountManagementController extends Controller
{
    public function usersListByAdmin(Request $request)
    {
        $usersWithRole = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['superadmin', 'admin']);
        })->with('roles')->get(['id', 'email']);

        $creatorId = null;
        if ($request->filled('user_id')) {
            $creatorId = $request->user_id;
        } else {
            $creatorId = $request->user()->id;
        }
        // return response()->json($usersWithRole);
        SessionTable::where('status', 1)->resolveSession();

        $roles = Role::all()->pluck('name', 'id');
        $query = User::query();
        $query->where('creator_id', $creatorId)->with('roles');
        $query->with('zipcodeRule');
        $query->withCount('local');
        $query->withCount('premium');
        $query->withCount([
            'sessions' => function ($q) {
                $q->select(DB::raw('count(distinct device_id)'))
                    ->where('status', 1);
            }
        ]);

        $query->withCount('hoaHouses');
        if (!empty($request->selected)) {
            // Put selected IDs first
            $ids = implode(',', $request->selected);
            $query->orderByRaw("FIELD(id, $ids) DESC");
        }

        if (!empty($request->sort_device)) {

            if ($request->sort_device == 'desc') {
                $query->orderByDesc('sessions_count');
            } elseif ($request->sort_device == 'asc') {
                $query->orderBy('sessions_count');
            }
        }





        $query->withCount('rooms');



        if (!empty($request->role)) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if (!empty($request->mode)) {
            if (strtolower($request->mode) == 'live') {
                $query->where('mode', $request->mode);
            } else {
                $query->whereIn('mode', ['Testing', 'Test', null]);
            }
        }


        if (!empty($request->status)) {
            $status = null;
            if ($request->status == 'active') {
                $status = 1;
            } elseif ($request->status == 'inactive') {
                $status = 0;
            }
            $query->where('is_active', $status);
        }
        if (!empty($request->search)) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($request->daterange)) {
            $query->where(function ($q) use ($request) {
                $dates = explode(" - ", $request->daterange);
                $start = Carbon::createFromFormat('Y-m-d', trim($dates[0]))->startOfDay();
                $end = Carbon::createFromFormat('Y-m-d', trim($dates[1]))->endOfDay();
                $q->whereBetween('created_at', [$start, $end]);
            });
        }


        if ($request->sort_date == 'asc') {

            $query->orderBy('created_at', 'asc');
        } elseif (empty($request->sort_date) && !empty($request->daterange)) {
            $query->orderBy('created_at', 'asc');
            $request->merge(['sort_date' => 'asc']);
        } else {

            $query->latest();
        }

        $query->userType($request->user_type);


        $limit = $request->input('limit', 10);
        $users = $query->paginate($limit);
        // return response()->json($users);

        if (empty($request->daterange)) {
            $dates = [
                'start_date' => User::orderBy('created_at', 'desc')->value('created_at'),
                'end_date' => User::orderBy('created_at', 'desc')->value('created_at'),
                'min_date' => User::orderBy('created_at', 'asc')->value('created_at'),
                'max_date' => User::orderBy('created_at', 'desc')->value('created_at')
            ];
        } else {


            $daterange = explode(' - ', $request->daterange);
            // dd($daterange);
            $dates = [
                'start_date' => $daterange[0],
                'end_date' => $daterange[1],
                'min_date' => User::orderBy('created_at', 'asc')->value('created_at'),
                'max_date' => User::orderBy('created_at', 'desc')->value('created_at')
            ];
        }



        // return response()->json($users);
        if ($request->ajax()) {
            return view('admin.account-managment.users-list-by-admin.table', compact('users', 'roles', 'dates', 'usersWithRole'));
        }
        return view('admin.account-managment.users-list-by-admin.index', compact('users', 'roles', 'dates', 'usersWithRole'));
    }
}
