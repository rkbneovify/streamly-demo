<?php

namespace App\Http\Controllers\Admin;

use App\Exports\HoaSubUserExport;
use App\HoaUser;
use App\HotelRoomTable;
use App\Http\Controllers\Controller;
use App\SessionTable;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class AdminHoaController extends Controller
{
    public function index(Request $request, $id)
    {
        try {
            $user = User::find($id);



            $query = HoaUser::query();

            $query->where('user_id', $id)
                ->withCount('session');

            if ($request->device == 'desc') {
                $query->orderByDesc('session_count');
            } elseif ($request->device == 'asc') {
                $query->orderBy('session_count');

            }
            $query->when($request->has('search'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orwhere('email', 'like', '%' . $request->search . '%')
                    ->orwhere('passcode', 'like', '%' . $request->search . '%');
            });
            $query->when($request->has('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            });


            if ($request->created == null) {
                $query->latest();
            } else {
                $query->when($request->has('created'), function ($q) use ($request) {

                    if ($request->created == 'asc') {
                        $q->orderBy('created_at');
                    } else {
                        $q->latest();
                    }

                });
            }
            $limit = $request->input('limit', 10);
            $houseList = $query->paginate($limit);

            if ($request->ajax()) {
                return view('admin.hoa.sub-user.index-table', compact('user', 'houseList'));
            }

            return view('admin.hoa.sub-user.index', compact('user', 'houseList'));
        } catch (\Throwable $th) {
            //throw $th;
            Alert::error('error', 'Something went wrong ! try again later.')
                ->timerProgressBar()
                ->autoClose(3000);
            // Alert::error('Oops !', $th->getMessage());
            return redirect()->back();
        }
    }

    public function sessionByPasscode(Request $request, $passcode)
    {
        try {
            $user = HoaUser::where('passcode', $passcode)->first();
            $query = SessionTable::query();
            $query->with([
                'pdInfoPasscode',
                'subSessionPasscode'
            ]);
            $query->selectRaw("*, 
        SUBSTRING_INDEX(mac_address, '#~', 1) as device_name,
        JSON_UNQUOTE(JSON_EXTRACT(device, '$.useragent')) as useragent")
                ->where('passcode', $passcode)
                ->where('status', 1);


            if (!empty($request->search) && strtolower($request->search) != 'web') {


                $query->whereHas('pdInfoPasscode', function ($query) use ($request) {
                    $query->where('personal_devicename', 'like', '%' . $request->search . '%')
                        ->orWhere('platform', 'like', '%' . $request->search . '%')
                        ->orWhere('device_model', 'like', '%' . $request->search . '%')
                        ->orWhere('build_version', 'like', '%' . $request->search . '%')
                        ->orWhere('zipcode', 'like', '%' . $request->search . '%');

                });



                $query->orderBy('created_on', 'desc');
                $allSession = $query->get();
            } elseif (!empty($request->search) && strtolower($request->search) == 'web') {
                $query->orderBy('created_on', 'desc');
                $allSession = $query->get();
                if (!empty($request->search) && strtolower($request->search) == 'web') {
                    $allSession = $allSession->filter(function ($item) {
                        return strtolower($item->device_type) === 'web';
                    })->values();
                }
            } else {
                $query->orderBy('created_on', 'desc');
                $allSession = $query->get();
            }




            if ($allSession->count() <= 0) {
                toast('No active device.', 'error')
                    ->timerProgressBar()
                    ->autoClose(3000);
                return redirect()->back();
            }

            // return response()->json($allSession);

            if ($request->ajax()) {
                return view('admin.hoa.sub-user.devices.table', compact('allSession', 'user'));
            }




            // return response()->json(['success' => true, "datalist" => $allSession]);
            return view('admin.hoa.sub-user.devices.index', compact('allSession', 'user'));
        } catch (\Throwable $th) {
            // throw $th;
            toast('Something went wrong ! try again later.', 'error')
                ->timerProgressBar()
                ->autoClose(3000);
            // Alert::error('Oops !', $th->getMessage());
            return redirect()->back();
        }


    }

    public function houseListOrdering($id)
    {
        DB::beginTransaction();
        try {
            $houseList = HoaUser::where('user_id', $id)->get();
            foreach ($houseList as $key => $item) {
                $item->ordering = $key + 1;
                $item->save();
            }
            DB::commit();
            toast('Ordering successfully !', 'success')
                ->timerProgressBar()
                ->autoClose(3000);
            return redirect()->back();
        } catch (\Throwable $th) {
            // toast('Something went wrong ! try again later.', 'error')
            //     ->timerProgressBar()
            //     ->autoClose(3000);
            Alert::error('Oops !', $th->getMessage());
            return redirect()->back();
        }
    }

    public function exportIntoExcel(Request $request, $id)
    {

        $filename = 'hoa_house_list_' . now()->format('d-m-Y_H-i-s') . '.xlsx';
        return Excel::download(new HoaSubUserExport($request, $id), $filename);
    }
}
