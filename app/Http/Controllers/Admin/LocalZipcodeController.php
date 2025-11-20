<?php

namespace App\Http\Controllers\Admin;

use App\LocalZipcode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Zipcode;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Adapter\Local;
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


        $localChannels = Channel::whereJsonDoesntContain('zip_code', "All")
            ->whereJsonDoesntContain('zip_code', strval($zipcode))
            ->pluck('id')
            ->toArray();


        if ($request->ajax()) {
            return view('admin.zipcodes.local.channel.index-table', compact('zipcodes', 'localChannels', 'zipcode'));
        }
        return view("admin.zipcodes.local.channel.index", compact('zipcodes', 'localChannels', 'zipcode'));
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


    public function statusDisableForAllLocalChannel($zipcode)
    {
        $localZipcode = LocalZipcode::where('zipcode', $zipcode)->get();
        if ($localZipcode == null) {
            toast('Zipcode not found', 'error')
                ->autoClose(3000)
                ->timerProgressBar();

            return redirect()->back();
        }

        $channelIds = $localZipcode->pluck('channel_id')->toArray();
        Channel::whereIn('id', $channelIds)
            ->whereJsonContains('zip_code', strval($zipcode))
            ->each(function ($channel) use ($zipcode) {
                $channelNewZipcodes = array_filter($channel->zip_code, function ($item) use ($zipcode) {
                    return $item !== strval($zipcode);
                });

                $channel->zip_code = array_values($channelNewZipcodes);
                $channel->save();
            });

        LocalZipcode::where('zipcode', $zipcode)
            ->update(['status' => 0]);

        toast("Zipcode disable successfully for all local channels", 'success')
            ->autoClose(3000)
            ->timerProgressBar();

        return redirect()->back();









    }

    public function statusEnableForAllLocalChannel($zipcode)
    {
        $localZipcode = LocalZipcode::where('zipcode', $zipcode)->get();
        if ($localZipcode == null) {
            toast('Zipcode not found', 'error')
                ->autoClose(3000)
                ->timerProgressBar();

            return redirect()->back();
        }

        $channelIds = $localZipcode->pluck('channel_id')->toArray();
        Channel::whereIn('id', $channelIds)
            ->whereJsonContains('zip_code', strval($zipcode))
            ->each(function ($channel) use ($zipcode) {
                $channel->zip_code = strval($zipcode);
                $channel->save();

            });

        LocalZipcode::where('zipcode', $zipcode)
            ->update(['status' => 1]);

        toast("Zipcode disable successfully for all local channels", 'success')
            ->autoClose(3000)
            ->timerProgressBar();

        return redirect()->back();









    }


    public function addZipcodeToChannel($zipcode, $channelId)
    {
        DB::beginTransaction();
        try {
            $localZipcode = LocalZipcode::where('zipcode', $zipcode)
                ->where('channel_id', $channelId)
                ->first();
            if ($localZipcode != null) {
                $message = $localZipcode->status == 1 ? 'active' : 'inactive';
                toast('Zipcode already added to this channel. ' . 'But zipcode ' . $message, 'info')
                    ->autoClose(3000)
                    ->timerProgressBar();
                return redirect()->back();
            }
            $channel = Channel::where('id', $channelId)
                ->first();


            if ($channel == null) {
                toast('Channel not found', 'error')
                    ->autoClose(3000)
                    ->timerProgressBar();

                return redirect()->back();
            }
            $channelZipcodes = $channel->zip_code;
            if (in_array(strval($zipcode), $channelZipcodes)) {
                toast('Zipcode already added to this channel', 'info')
                    ->autoClose(3000)
                    ->timerProgressBar();
                return redirect()->back();
            }
            if (!in_array(strval($zipcode), $channelZipcodes)) {
                $channelZipcodes[] = strval($zipcode);
                $channel->zip_code = $channelZipcodes;
                $channel->save();

            }
            $allowzipcode = Zipcode::where('zipcode', $zipcode)
                ->where('status', 1)
                ->first();
            if ($allowzipcode == null) {
                toast('Zipcode not found or inactive', 'error')
                    ->autoClose(3000)
                    ->timerProgressBar();
                return redirect()->back();
            }
            $localZipcode = new LocalZipcode();
            $localZipcode->channel_id = $channelId;
            $localZipcode->zipcode = $allowzipcode->zipcode;
            $localZipcode->city = $allowzipcode->city;
            $localZipcode->state = $allowzipcode->state;
            $localZipcode->state_abbr = $allowzipcode->state_abbr;
            $localZipcode->county_area = $allowzipcode->county_area;
            $localZipcode->code = $allowzipcode->code;
            $localZipcode->latitude = $allowzipcode->latitude;
            $localZipcode->longitude = $allowzipcode->longitude;
            $localZipcode->status = $allowzipcode->status;
            $localZipcode->save();
            DB::commit();
            toast("Zipcode added to channel successfully !", 'success')

                ->autoClose(3000)
                ->timerProgressBar();
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error('Error', 'Something went wrong ! Please try again .');
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


