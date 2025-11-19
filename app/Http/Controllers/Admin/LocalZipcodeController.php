<?php

namespace App\Http\Controllers\Admin;

use App\LocalZipcode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

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

    public function zipcodeWiseChannel($zipcode, Request $request)
    {
        $query = LocalZipcode::query();
        $query->with('channel');
        $query->where('zipcode', $zipcode);
        $query->when($request->has('search'), function ($q) use ($request) {
            $search = $request->input('search');
            $q->whereHas('channel', function ($channelQuery) use ($search) {
                $channelQuery->where('id', 'like', "%$search%")
                    ->orWhere('name', 'like', "%$search%")
                    ->orWhere('stationName', 'like', "%$search%")
                    ->orWhere('callSign', 'like', "%$search%");
            });
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
            return view('admin.zipcodes.local.channel.index-table', compact('zipcodes'));
        }
        return view("admin.zipcodes.local.channel.index", compact('zipcodes'));
    }

    public function changeStatus($id)
    {



        DB::beginTransaction();
        try {
            $localZipcode = LocalZipcode::find($id);

            if ($localZipcode == null) {
                toast('Zipcode not found', 'error')
                    ->autoClose(3000)
                    ->timerProgressBar();

                return redirect()->back();
            }



            if ($localZipcode->status == 1) {
                $channel = Channel::where('id', $localZipcode->channel_id)
                    ->whereJsonContains('zip_code', $localZipcode->zipcode)
                    ->first();

                if ($channel == null) {
                    toast('Channel not found', 'error')
                        ->autoClose(3000)
                        ->timerProgressBar();

                    return redirect()->back();
                }
                $channelNewZipcodes = array_filter($channel->zip_code, function ($item) use ($localZipcode) {
                    return $item !== strval($localZipcode->zipcode);
                });
                $channelNewZipcodes = array_values($channelNewZipcodes);
                $channel->zip_code = $channelNewZipcodes;
                $channel->save();
            } else {
                $channel = Channel::where('id', $localZipcode->channel_id)
                    ->whereJsonDoesntContain('zip_code', $localZipcode->zipcode)
                    ->first();

                if ($channel == null) {
                    toast('Channel not found', 'error')
                        ->autoClose(3000)
                        ->timerProgressBar();

                    return redirect()->back();
                }
                $channelZipcodes = $channel->zip_code;
                $channelZipcodes[] = strval($localZipcode->zipcode);
                $channel->zip_code = $channelZipcodes;
                $channel->save();
            }





            $localZipcode->status == 0 ? $localZipcode->status = 1 : $localZipcode->status = 0;
            $localZipcode->save();









            DB::commit();
            $message = $localZipcode->status == 1 ? 'activated' : 'deactivated';
            toast("Zipcode {$message} successfully", 'success')

                ->autoClose(3000)
                ->timerProgressBar();

            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error('Error', 'Something went wrong ! Please try again .' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function delete($id)
    {



        DB::beginTransaction();
        try {
            $localZipcode = LocalZipcode::find($id);

            if ($localZipcode == null) {
                toast('Zipcode not found', 'error')
                    ->autoClose(3000)
                    ->timerProgressBar();

                return redirect()->back();
            }



            $channel = Channel::where('id', $localZipcode->channel_id)
                ->whereJsonContains('zip_code', $localZipcode->zipcode)
                ->first();

            if ($channel == null) {
                toast('Channel not found', 'error')
                    ->autoClose(3000)
                    ->timerProgressBar();

                return redirect()->back();
            }
            $channelNewZipcodes = array_filter($channel->zip_code, function ($item) use ($localZipcode) {
                return $item !== strval($localZipcode->zipcode);
            });
            $channelNewZipcodes = array_values($channelNewZipcodes);
            $channel->zip_code = $channelNewZipcodes;
            $channel->save();








            $localZipcode->delete();
            DB::commit();

            toast("Zipcode delete successfully !", 'success')

                ->autoClose(3000)
                ->timerProgressBar();

            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error('Error', 'Something went wrong ! Please try again .');
            return redirect()->back();
        }
    }


}


