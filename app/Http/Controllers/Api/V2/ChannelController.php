<?php

namespace App\Http\Controllers\Api\v2;


use App\GracenoteScheduleProgram;
use App\GracenoteSource;
use App\Http\Controllers\Controller;
use App\Http\Requests\BugReporting\Store;
use App\Models\Channel;
use App\Models\FavouriteChannel;
use App\Models\Genre;
use App\Models\UserZipcode;
use App\Models\UserZipcodeRule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ChannelController extends Controller
{
    private $exculdedUsers = ["jmheil@gmail.com", "llipschultz@neovix.com", "brian_marlin@yahoo.com", "qajalaj@yopmail.com", "pm13426@gmail.com", "nathan@neovix.com", "willis@neovix.com", "davea@neovix.com", "willthiswork2023@outlook.com"];


    private function getURIFromAsset($assets)
    {
        $uri = null;
        $ratio_used = null;

        $uri_16_9 = null;
        $uri_4_3 = null;

        $data = json_decode($assets, true);
        if (!empty($data) && isset($data['asset'])) {
            foreach ($data['asset'] as $asset) {
                if (
                    ($asset['category'] === 'Parent Source Logo - dark' ||
                        $asset['category'] === 'Source Logo - dark' ||
                        $asset['category'] === 'Iconic')
                ) {
                    if ($asset['ratio'] === '16:9') {
                        $uri_16_9 = "https://demo.tmsimg.com/" . $asset['URI'] . "?w=3840&h=2160";
                        $ratio_used = '16:9';
                        break;
                    } elseif ($asset['ratio'] === '4:3') {
                        $uri_4_3 = "https://demo.tmsimg.com/" . $asset['URI'] . "?w=3840&h=2160";
                        $ratio_used = '4:3';
                    }
                }
            }

            if ($uri_16_9) {
                $uri = $uri_16_9;
                $ratio_used = '16:9';
            } elseif ($uri_4_3) {
                $uri = $uri_4_3;
                $ratio_used = '4:3';
            }
        }


        return [
            'uri' => $uri,
            'ratio' => $ratio_used
        ];
    }



    private function getURIFromAssetForProgram($data)
    {
        $uri = NULL;
        // Initialize URI variables
        $uri_16_9 = null;
        $uri_4_3 = null;
        if (!empty($data) && isset($data['asset'])) {
            // Iterate through the assets
            foreach ($data['asset'] as $asset) {
                if (isset($asset['ratio']) && isset($asset['URI'])) {
                    if ($asset['category'] == "Iconic" && $asset['ratio'] == '3:4') {
                        $uri_4_3 = "https://demo.tmsimg.com/" . $asset['URI'];
                        break;
                    } elseif ($asset['category'] == "VOD Art" && $asset['ratio'] == '3:4') {
                        $uri_4_3 = "https://demo.tmsimg.com/" . $asset['URI'];
                        break;
                    } elseif ($asset['category'] == "Poster Art" && $asset['ratio'] == '3:4') {
                        $uri_4_3 = "https://demo.tmsimg.com/" . $asset['URI'];
                        break;
                    } elseif ($asset['category'] == "Key Art" && $asset['ratio'] == '3:4') {
                        $uri_4_3 = "https://demo.tmsimg.com/" . $asset['URI'];
                        break;
                    } elseif ($asset['ratio'] == '16:9') {
                        $uri_16_9 = "https://demo.tmsimg.com/" . $asset['URI'];
                    }
                }
            }

            // Output the URI
            if ($uri_4_3) {
                $uri = $uri_4_3;
            } elseif ($uri_16_9) {
                $uri = $uri_16_9;
            }
        }
        return $uri;
    }

    public function recentChannels(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'typeurl' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'validation_error',
                'message' => $validator->errors()
            ], 400);
        } else {
            $urlType = $request->get('typeurl');
            if ($urlType == 'manifest') {
                $type = 'smooth_url';
            } else if ($urlType == 'm3u8') {
                $type = 'url';
            } else if ($urlType == "mpd") {
                $type = 'dash_url';
            } else {
                $type = 'dash_url';
            }
            $query = Channel::select('channels.id', 'channels.image as db_image', 'channels.image as local_image', 'channels.is_logo_from_gracenote', 'channels.prgSvcId', "channels.$type as url", 'gracenote_sources.stationName', 'gracenote_sources.callSign', 'gracenote_sources.timeZone', 'gracenote_sources.url as channel_url', 'gracenote_sources.assets', 'channels.zip_code')
                ->rightJoin('recent_channel', 'channels.id', '=', 'recent_channel.channels_id')
                ->leftJoin('gracenote_sources', 'gracenote_sources.prgSvcId', '=', 'channels.prgSvcId');

            $query->where('channels.status', '=', '1');
            $query->where('recent_channel.user_id', '=', JWTAuth::user()->id);
            $query->orderBy('stationName', 'asc');
            $record = $query->distinct()->get()->map(function ($channel) {
                $channel->favourite = FavouriteChannel::where('channels_id', $channel->id)->where('user_id', JWTAuth::user()->id)->exists();
                $assetData = $this->getURIFromAsset($channel->assets);
                $channel->image = $assetData['uri'];
                $channel->roku_image = $channel->image != null ? url('storage/channel_roku_images/resized_image_' . basename($channel->image)) : env('APP_URL') . '/uploads/channellogo/' . $channel->db_image;
                if ($channel->image == null) {
                    $channel->image = env('APP_URL') . '/uploads/channellogo/' . $channel->db_image ?? env('APP_URL') . '/uploads/No Image.png';
                }
                if ($channel->roku_image == null) {
                    $channel->roku_image = env('APP_URL') . '/uploads/No Image.png';
                }
                if ($channel->id == 6) {
                    $channel->stationName = 'Trinity Broadcasting Network';
                    // $channel->image = env('APP_URL') . '/uploads/tbn-6.png';
                    // $channel->roku_image = env('APP_URL') . '/uploads/tbn-6.png';
                }
                if ($channel->id == 11) {
                    $channel->stationName = 'FanDuel';
                    // $channel->image = env('APP_URL') . '/uploads/fandue.png';
                    // $channel->roku_image = env('APP_URL') . '/uploads/fandue.png';
                }
                if ($channel->id == 19) {
                    $channel->stationName = 'Hallmark Family';
                    // $channel->image = env('APP_URL') . '/uploads/hallm-fam-19.png';
                    // $channel->roku_image = env('APP_URL') . '/uploads/hallm-fam-19.png';
                }
                if ($channel->id == 45) {
                    $channel->stationName = 'Fuse Music';
                    // $channel->image = env('APP_URL') . '/uploads/fuse-mu-45.png';
                    // $channel->roku_image = env('APP_URL') . '/uploads/fuse-mu-45.png';
                }




                // if ($channel->id == 134) {
                //     $channel->image = env('APP_URL') . '/uploads/ocbs-134.png';
                //     $channel->roku_image = env('APP_URL') . '/uploads/ocbs-134.png';
                // }
                // if ($channel->id == 135) {
                //     $channel->image = env('APP_URL') . '/uploads/WCAU-135.png';
                //     $channel->roku_image = env('APP_URL') . '/uploads/WCAU-135.png';
                // }
                // if ($channel->id == 136) {
                //     $channel->image = env('APP_URL') . '/uploads/fox29-136.png';
                //     $channel->roku_image = env('APP_URL') . '/uploads/fox29-136.png';
                // }
                // if ($channel->id == 137) {
                //     $channel->image = env('APP_URL') . '/uploads/abc7-137.png';
                //     $channel->roku_image = env('APP_URL') . '/uploads/abc7-137.png';
                // }
                // if ($channel->id == 138) {
                //     $channel->image = env('APP_URL') . '/uploads/ocbs-138.png';
                //     $channel->roku_image = env('APP_URL') . '/uploads/ocbs-138.png';
                // }
                // if ($channel->id == 139) {
                //     $channel->image = env('APP_URL') . '/uploads/fox-139.png';
                //     $channel->roku_image = env('APP_URL') . '/uploads/fox-139.png';
                // }
                // if ($channel->id == 140) {
                //     $channel->image = env('APP_URL') . '/uploads/4ny-140.png';
                //     $channel->roku_image = env('APP_URL') . '/uploads/4ny-140.png';
                // }

                if ($channel->is_logo_from_gracenote == 0) {
                    $channel->image = url('uploads/channellogo') . '/' . $channel->local_image;
                    $channel->roku_image = url('uploads/channellogo') . '/' . $channel->local_image;
                }

                unset($channel->db_image);
                unset($channel->assets);
                return $channel;
            });

            $response = [
                'success' => true,
                'response_code' => 200,
                'message' => 'Recent Channel Retrieved successfully.',
                'data' => $record,

            ];
            return response()->json($response, 200);
        }
    }
    public function favouriteChannels(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'typeurl' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'validation_error',
                'message' => $validator->errors()
            ], 400);
        } else {
            $urlType = $request->get('typeurl');
            if ($urlType == 'manifest') {
                $type = 'smooth_url';
            } else if ($urlType == 'm3u8') {
                $type = 'url';
            } else if ($urlType == "mpd") {
                $type = 'dash_url';
            } else {
                $type = 'dash_url';
            }
            $query = Channel::select('channels.id', 'channels.image as db_image', 'channels.image as local_image', 'channels.is_logo_from_gracenote', 'channels.prgSvcId', "channels.$type as url", 'gracenote_sources.stationName', 'gracenote_sources.callSign', 'gracenote_sources.timeZone', 'gracenote_sources.url as channel_url', 'gracenote_sources.assets')
                ->rightJoin('favourite_channel', 'channels.id', '=', 'favourite_channel.channels_id')
                ->leftJoin('gracenote_sources', 'gracenote_sources.prgSvcId', '=', 'channels.prgSvcId');

            $query->where('channels.status', '=', '1');
            $query->where('favourite_channel.user_id', '=', JWTAuth::user()->id);
            $query->orderBy('stationName', 'asc');
            $record = $query->get()->map(function ($channel) {
                $channel->favourite = FavouriteChannel::where('channels_id', $channel->id)->where('user_id', JWTAuth::user()->id)->exists();
                $assetData = $this->getURIFromAsset($channel->assets) ?? env('APP_URL') . '/uploads/channellogo/' . $channel->db_image;
                $channel->image = $assetData['uri'];
                if ($channel->image == null) {
                    $channel->image = env('APP_URL') . '/uploads/channellogo/' . $channel->db_image ?? env('APP_URL') . '/uploads/No Image.png';
                }
                if ($channel->id == 6) {
                    $channel->stationName = 'Trinity Broadcasting Network';
                    // $channel->image = env('APP_URL') . '/uploads/tbn-6.png';
                    // $channel->roku_image = env('APP_URL') . '/uploads/tbn-6.png';
                }
                if ($channel->id == 11) {
                    $channel->stationName = 'FanDuel';
                    // $channel->image = env('APP_URL') . '/uploads/fandue.png';
                    // $channel->roku_image = env('APP_URL') . '/uploads/fandue.png';

                }
                if ($channel->id == 19) {
                    $channel->stationName = 'Hallmark Family';
                    // $channel->image = env('APP_URL') . '/uploads/hallm-fam-19.png';
                    // $channel->roku_image = env('APP_URL') . '/uploads/hallm-fam-19.png';

                }
                if ($channel->id == 45) {
                    $channel->stationName = 'Fuse Music';
                    // $channel->image = env('APP_URL') . '/uploads/fuse-mu-45.png';
                    // $channel->roku_image = env('APP_URL') . '/uploads/fuse-mu-45.png';
                }
                // if ($channel->id == 134) {
                //     $channel->image = env('APP_URL') . '/uploads/ocbs-134.png';
                //     $channel->roku_image = env('APP_URL') . '/uploads/ocbs-134.png';
                // }
                // if ($channel->id == 135) {
                //     $channel->image = env('APP_URL') . '/uploads/WCAU-135.png';
                //     $channel->roku_image = env('APP_URL') . '/uploads/WCAU-135.png';
                // }
                // if ($channel->id == 136) {
                //     $channel->image = env('APP_URL') . '/uploads/fox29-136.png';
                //     $channel->roku_image = env('APP_URL') . '/uploads/fox29-136.png';
                // }
                // if ($channel->id == 137) {
                //     $channel->image = env('APP_URL') . '/uploads/abc7-137.png';
                //     $channel->roku_image = env('APP_URL') . '/uploads/abc7-137.png';
                // }
                // if ($channel->id == 138) {
                //     $channel->image = env('APP_URL') . '/uploads/ocbs-138.png';
                //     $channel->roku_image = env('APP_URL') . '/uploads/ocbs-138.png';
                // }
                // if ($channel->id == 139) {
                //     $channel->image = env('APP_URL') . '/uploads/fox-139.png';
                //     $channel->roku_image = env('APP_URL') . '/uploads/fox-139.png';
                // }
                // if ($channel->id == 140) {
                //     $channel->image = env('APP_URL') . '/uploads/4ny-140.png';
                //     $channel->roku_image = env('APP_URL') . '/uploads/4ny-140.png';
                // }

                if ($channel->is_logo_from_gracenote == 0) {
                    $channel->image = url('uploads/channellogo') . '/' . $channel->local_image;
                    $channel->roku_image = url('uploads/channellogo') . '/' . $channel->local_image;
                }

                unset($channel->db_image);
                unset($channel->assets);
                return $channel;
            });

            $response = [
                'success' => true,
                'response_code' => 200,
                'message' => 'Favourite Channel Retrieved successfully.',
                'data' => $record,

            ];
            return response()->json($response, 200);
        }
    }



    public function epgGuide(Request $request)
    {

        // abort(500);
        $validator = Validator::make($request->all(), [
            'typeurl' => 'required',
            'day' => 'nullable',
            'zipcode' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'validation_error',
                'message' => $validator->errors()
            ], 400);
        } else {
            $user = JWTAuth::user();
            $urlType = $request->get('typeurl');
            $zipcode = $request->input('zipcode');
            $genre = $request->get('genre');
            $hoursoffset = $request->get('hoursoffset');
            $pattern = '/^(-?\d{2}:\d{2})$/';

            // Insert zipcode only if it doesn't already exist for this user
            if ($zipcode && $user) {
                UserZipcode::firstOrCreate([
                    'user_id' => $user->id,
                    'zipcode' => $zipcode
                ]);
            }

            if (preg_match($pattern, $hoursoffset, $matches)) {
                // String matches the format, and $matches[1] contains the matched value
                $hoursofset = $matches[1];
            } else {
                // String does not match the format
                $hoursofset = '00:00';
            }
            $day = $request->day;
            $validate = 0;

            // Check DB for email-specific zipcode rules
            $zipcodeRule = UserZipcodeRule::where('email', $user->email)->first();
            if ($zipcodeRule) {
                $validate = $zipcodeRule->validate;
                $zipcode = $zipcodeRule->zipcode;
            } elseif (!in_array($user->email, $this->exculdedUsers) && $request->has('zipcode')) {
                $validate = 1;
            }

            if ($urlType == 'manifest') {
                $type = 'smooth_url';
            } else if ($urlType == 'm3u8') {
                $type = 'url';
            } else if ($urlType == "mpd") {
                $type = 'dash_url';
            } else {
                $type = 'dash_url';
            }
            $query = Channel::with([
                'zipcode',
                'premium',
                'phxLocal'
            ])
                ->select('channels.id', 'channels.image as db_image', 'channels.type', 'channels.status', 'channels.image as local_image', 'channels.is_logo_from_gracenote', 'channels.prgSvcId', "channels.$type as url", 'channels.stationName', 'gracenote_sources.callSign', 'gracenote_sources.timeZone', 'gracenote_sources.url as channel_url', 'gracenote_sources.assets')
                ->selectRaw("CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(genre.name, 'Others')), ',', '\",\"'), '\"]') as genrename")
                ->selectRaw('CAST(IFNULL(genre.name,"Others") AS CHAR) AS genre_name')
                ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
                ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
                ->leftJoin('gracenote_sources', 'gracenote_sources.prgSvcId', '=', 'channels.prgSvcId');




            // $query->where('channels.status', 1);
            // // return response()->json([$query->where('channels.status', 1)->toSql(), $query->getBindings()]);

            // $query->where(function ($q) use ($zipcode) {

            //     $q->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"')")

            //         ->orWhereRaw("JSON_CONTAINS(channels.zip_code, '\"All\"')");
            // });
            // ->orWhereRaw("JSON_CONTAINS(channels.zip_code, '\"All\"')")
            // $query->orWhereRaw("JSON_CONTAINS(channels.zip_code, '\"All\"')")

            if ($validate == 1) {
                if (!empty($zipcode)) {


                    $query->localZipcode($zipcode)
                        ->premiumStatus($user)
                        ->phxLocalStatus($user)
                        ->orwhere('channels.type', 'all')
                        ->where('channels.status', 1)
                        ->orwhereNull('channels.type')
                        ->where('channels.status', 1);
                }
            } elseif ($validate == 2) {
                if (!empty($zipcode)) {

                    $query->localZipcode($zipcode)
                        ->premiumStatus($user)
                        ->phxLocalStatus($user)
                        ->where('channels.status', 1);
                }
            }

            // $query->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"')");
            // $query->where('channels.status', 1)->where(function ($q) use ($user) {
            //     if ($user->access_premium_channel == 1) {
            //         $q->whereIn('channels.type', ['all', 'local', 'premium'])
            //             ->orWhereNull('channels.type');
            //     } else {
            //         $q->whereIn('channels.type', ['all', 'local'])
            //             ->orWhereNull('channels.type');
            //     }

            // });


            // $query->premium($user);

            $query->where('channels.status', 1);





            if (!empty($genre) && $genre != 'Others') {
                $query->where('genre.name', '=', $genre);
            } else if ($genre == 'Others') {
                $query->whereNull('genre.name');
            }
            $query->groupBy('channels.id');
            if ($user->email == "streamlymtairy@gmail.com") {
                $query->orderBy('channels.id', 'desc');
            } else {
                $query->orderBy('channels.id', 'asc');
            }
            $record = $query->get()->map(function ($channel) use ($day) {
                $channel->favourite = FavouriteChannel::where('channels_id', $channel->id)
                    ->where('user_id', JWTAuth::user()->id)
                    ->exists();

                $assetData = $this->getURIFromAsset($channel->assets);

                $channel->image = $assetData['uri'] ?? env('APP_URL') . '/uploads/channellogo/' . $channel->db_image;
                $channel->ratio = $assetData['ratio'] ?? 'NA';

                $channel->program = $this->getProgramScheduleById($channel->prgSvcId, $day)->map(function ($item) use ($channel) {
                    // station name inside program
                    $item->stationName = $channel->stationName;
                    return $item;
                });



                unset($channel->assets);

                if ($channel->stationName == null) {
                    $channel->stationName = "NA";
                }
                if ($channel->callSign == null) {
                    $channel->callSign = "NA";
                }
                if ($channel->timeZone == null) {
                    $channel->timeZone = "NA";
                }
                if ($channel->channel_url == null) {
                    $channel->channel_url = "NA";
                }
                if ($channel->prgSvcId == null) {
                    $channel->prgSvcId = "NA";
                }
                if ($channel->image == null) {
                    $channel->image = env('APP_URL') . '/uploads/No Image.png';
                }




                if ($channel->id == 11) {
                    $channel->stationName = 'FanDuel';
                    // $channel->image = env('APP_URL') . '/uploads/fandue.png';
                }
                if ($channel->id == 6) {
                    $channel->stationName = 'Trinity Broadcasting Network';
                    // $channel->image = env('APP_URL') . '/uploads/tbn-6.png';
                }
                if ($channel->id == 19) {
                    $channel->stationName = 'Hallmark Family';
                    // $channel->image = env('APP_URL') . '/uploads/hallm-fam-19.png';
                }
                if ($channel->id == 45) {
                    $channel->stationName = 'Fuse Music';
                    // $channel->image = env('APP_URL') . '/uploads/fuse-mu-45.png';
                }

                // if ($channel->id == 134) {
                //     $channel->image = env('APP_URL') . '/uploads/ocbs-134.png';
                // }
                // if ($channel->id == 135) {
                //     $channel->image = env('APP_URL') . '/uploads/WCAU-135.png';
                // }
                // if ($channel->id == 136) {

                //     $channel->image = env('APP_URL') . '/uploads/fox29-136.png';
                // }
                // if ($channel->id == 137) {
                //     $channel->image = env('APP_URL') . '/uploads/abc7-137.png';
                // }
                // if ($channel->id == 138) {
                //     $channel->image = env('APP_URL') . '/uploads/ocbs-138.png';
                // }
                // if ($channel->id == 139) {
                //     $channel->image = env('APP_URL') . '/uploads/fox-139.png';
                // }
                // if ($channel->id == 140) {
                //     $channel->image = env('APP_URL') . '/uploads/4ny-140.png';
                // }

                if ($channel->is_logo_from_gracenote == 0) {
                    $channel->image = url('/uploads/channellogo') . '/' . $channel->local_image;
                }


                // just for additional data hiding
                unset($channel->zipcode);
                unset($channel->primium);
                unset($channel->is_logo_from_gracenote);
                unset($channel->local_image);
                unset($channel->db_image);
                $channel->program->makeHidden([
                    'description',
                    'quals',
                    'time',
                    'image',
                    'stationName',
                ]);
                $channel->makeHidden([
                    'type',
                    'status',
                    'prgSvcId',
                    'channel_url',
                    'genrename',
                    'genre_name',
                    'ratio',
                    'premium',
                    'stationName',
                    'east_coast',
                ]);


                return $channel;
            });


            // here just hidding relation data
            $record = $record->map(function ($item) {
                $data = $item instanceof \Illuminate\Database\Eloquent\Model
                    ? $item->toArray()
                    : (array) $item;

                unset($data['phx_local']);

                return $data;
            });

            $response = [];
            $response['success'] = true;
            $response['response_code'] = 200;
            $response['message'] = 'Epg Guide Retrieved successfully.';
            if ($day == 'tomorrow') {
                $gmdate = gmdate('Y-m-d', strtotime('+1 day'));
                $start_time = GracenoteScheduleProgram::select('start')->where('date', $gmdate)->orderBy('start', 'asc')->first();
                // var_dump($start_time);
                $response['start'] = $start_time->start;
            }
            $response['data'] = $record;
            return response()->json($response, 200);
        }
    }

    public function epgGuideWithPagination(Request $request)
    {

        // abort(500);
        $validator = Validator::make($request->all(), [
            'typeurl' => 'required',
            'day' => 'nullable',
            'zipcode' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'validation_error',
                'message' => $validator->errors()
            ], 400);
        } else {
            $user = JWTAuth::user();
            $urlType = $request->get('typeurl');
            $zipcode = $request->input('zipcode');
            $genre = $request->get('genre');
            $hoursoffset = $request->get('hoursoffset');
            $pattern = '/^(-?\d{2}:\d{2})$/';

            $limit = $request->input('limit', 10);
            $page = $request->input('page', 1);

            // Insert zipcode only if it doesn't already exist for this user
            if ($zipcode && $user) {
                UserZipcode::firstOrCreate([
                    'user_id' => $user->id,
                    'zipcode' => $zipcode
                ]);
            }

            if (preg_match($pattern, $hoursoffset, $matches)) {
                // String matches the format, and $matches[1] contains the matched value
                $hoursofset = $matches[1];
            } else {
                // String does not match the format
                $hoursofset = '00:00';
            }
            $day = $request->day;
            $validate = 0;

            // Check DB for email-specific zipcode rules
            $zipcodeRule = UserZipcodeRule::where('email', $user->email)->first();
            if ($zipcodeRule) {
                $validate = $zipcodeRule->validate;
                $zipcode = $zipcodeRule->zipcode;
            } elseif (!in_array($user->email, $this->exculdedUsers) && $request->has('zipcode')) {
                $validate = 1;
            }

            if ($urlType == 'manifest') {
                $type = 'smooth_url';
            } else if ($urlType == 'm3u8') {
                $type = 'url';
            } else if ($urlType == "mpd") {
                $type = 'dash_url';
            } else {
                $type = 'dash_url';
            }
            // cache key
            $cacheKey = 'epg_guide_' . md5(json_encode([
                'user'       => $user->id,
                'zipcode'    => $zipcode,
                'genre'      => $genre,
                'day'        => $day,
                'page'       => $page,
                'limit'      => $limit,
                'typeurl'    => $urlType,
            ]));

            $query = Channel::with([
                'zipcode',
                'premium',
                'phxLocal',
                'eastCoast'
            ])
                ->select('channels.id', 'channels.image as db_image', 'channels.type', 'channels.status', 'channels.image as local_image', 'channels.is_logo_from_gracenote', 'channels.prgSvcId', "channels.$type as url", 'channels.stationName', 'gracenote_sources.callSign', 'gracenote_sources.timeZone', 'gracenote_sources.url as channel_url', 'gracenote_sources.assets')
                ->selectRaw("CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(genre.name, 'Others')), ',', '\",\"'), '\"]') as genrename")
                ->selectRaw('CAST(IFNULL(genre.name,"Others") AS CHAR) AS genre_name')
                ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
                ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
                ->leftJoin('gracenote_sources', 'gracenote_sources.prgSvcId', '=', 'channels.prgSvcId');






            if ($validate == 1) {
                if (!empty($zipcode)) {


                    $query->localZipcode($zipcode)
                        ->premiumStatus($user)
                        ->phxLocalStatus($user)
                        ->eastCoastStatus($user)
                        ->orwhere('channels.type', 'all')
                        ->where('channels.status', 1)
                        ->orwhereNull('channels.type')
                        ->where('channels.status', 1);
                }
            } elseif ($validate == 2) {
                if (!empty($zipcode)) {

                    $query->localZipcode($zipcode)
                        ->premiumStatus($user)
                        ->phxLocalStatus($user)
                        ->eastCoastStatus($user)
                        ->where('channels.status', 1);
                }
            }



            $query->where('channels.status', 1);





            if (!empty($genre) && $genre != 'Others') {
                $query->where('genre.name', '=', $genre);
            } else if ($genre == 'Others') {
                $query->whereNull('genre.name');
            }
            $query->groupBy('channels.id');
            if ($user->email == "streamlymtairy@gmail.com") {
                $query->orderBy('channels.id', 'desc');
            } else {
                $query->orderBy('channels.id', 'asc');
            }



            $channelsx = Cache::remember($cacheKey, now()->addMinutes(5), function () use (
                $query,
                $limit,
                $page,
                $day,
                $user
            ) {
                Log::info('CACHE MISS');
                $channels =  $query->paginate($limit, ['*'], 'page', $page);

                $channels->setCollection($channels->getCollection()->transform(function ($channel) use ($day) {
                    $channel->favourite = FavouriteChannel::where('channels_id', $channel->id)
                        ->where('user_id', JWTAuth::user()->id)
                        ->exists();

                    $assetData = $this->getURIFromAsset($channel->assets);

                    $channel->image = $assetData['uri'] ?? env('APP_URL') . '/uploads/channellogo/' . $channel->db_image;
                    $channel->ratio = $assetData['ratio'] ?? 'NA';

                    $channel->program = $this->getProgramScheduleByIdWithCache($channel->prgSvcId, $day)->map(function ($item) use ($channel) {
                        // station name inside program
                        $item->stationName = $channel->stationName;
                        return $item;
                    });
                    unset($channel->assets);

                    if ($channel->stationName == null) {
                        $channel->stationName = "NA";
                    }
                    if ($channel->callSign == null) {
                        $channel->callSign = "NA";
                    }
                    if ($channel->timeZone == null) {
                        $channel->timeZone = "NA";
                    }
                    if ($channel->channel_url == null) {
                        $channel->channel_url = "NA";
                    }
                    if ($channel->prgSvcId == null) {
                        $channel->prgSvcId = "NA";
                    }
                    if ($channel->image == null) {
                        $channel->image = env('APP_URL') . '/uploads/No Image.png';
                    }




                    if ($channel->id == 11) {
                        $channel->stationName = 'FanDuel';
                    }
                    if ($channel->id == 6) {
                        $channel->stationName = 'Trinity Broadcasting Network';
                    }
                    if ($channel->id == 19) {
                        $channel->stationName = 'Hallmark Family';
                    }
                    if ($channel->id == 45) {
                        $channel->stationName = 'Fuse Music';
                    }



                    if ($channel->is_logo_from_gracenote == 0) {
                        $channel->image = url('/uploads/channellogo') . '/' . $channel->local_image;
                    }


                    // just for additional data hiding
                    unset($channel->zipcode);
                    unset($channel->primium);
                    unset($channel->is_logo_from_gracenote);
                    unset($channel->local_image);
                    unset($channel->db_image);


                    return $channel;
                }));

                return $channels;
            });



            $response = [];
            $response['success'] = true;
            $response['response_code'] = 200;
            $response['message'] = 'Epg Guide Retrieved successfully.';
            if ($day == 'tomorrow') {
                $gmdate = gmdate('Y-m-d', strtotime('+1 day'));
                $start_time = GracenoteScheduleProgram::select('start')->where('date', $gmdate)->orderBy('start', 'asc')->first();
                // var_dump($start_time);
                $response['start'] = $start_time->start;
            }
            $response['data'] = $channelsx->items();
            $response['total'] = $channelsx->total();
            $response['last_page'] = $channelsx->lastPage();
            $response['current_page'] = $channelsx->currentPage();
            $response['from'] = $channelsx->firstItem();
            $response['to'] = $channelsx->lastItem();
            $response['limit'] = $channelsx->perPage();
            $response['has_more'] = $channelsx->hasMorePages();
            // Log::info('Response Size: ' . round(strlen(json_encode($response)) / 1024 / 1024, 2) . ' MB');
            return response()->json($response, 200);
        }
    }

    public function epgGuideWithPaginationAndKeyRemove(Request $request)
    {

        // abort(500);
        $validator = Validator::make($request->all(), [
            'typeurl' => 'required',
            'day' => 'nullable',
            'zipcode' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'validation_error',
                'message' => $validator->errors()
            ], 400);
        } else {
            $user = JWTAuth::user();
            $urlType = $request->get('typeurl');
            $zipcode = $request->input('zipcode');
            $genre = $request->get('genre');
            $hoursoffset = $request->get('hoursoffset');
            $pattern = '/^(-?\d{2}:\d{2})$/';

            $limit = $request->input('limit', 10);
            $page = $request->input('page', 1);

            // Insert zipcode only if it doesn't already exist for this user
            if ($zipcode && $user) {
                UserZipcode::firstOrCreate([
                    'user_id' => $user->id,
                    'zipcode' => $zipcode
                ]);
            }

            if (preg_match($pattern, $hoursoffset, $matches)) {
                // String matches the format, and $matches[1] contains the matched value
                $hoursofset = $matches[1];
            } else {
                // String does not match the format
                $hoursofset = '00:00';
            }
            $day = $request->day;
            $validate = 0;

            // Check DB for email-specific zipcode rules
            $zipcodeRule = UserZipcodeRule::where('email', $user->email)->first();
            if ($zipcodeRule) {
                $validate = $zipcodeRule->validate;
                $zipcode = $zipcodeRule->zipcode;
            } elseif (!in_array($user->email, $this->exculdedUsers) && $request->has('zipcode')) {
                $validate = 1;
            }

            if ($urlType == 'manifest') {
                $type = 'smooth_url';
            } else if ($urlType == 'm3u8') {
                $type = 'url';
            } else if ($urlType == "mpd") {
                $type = 'dash_url';
            } else {
                $type = 'dash_url';
            }
            // cache key
            $cacheKey = 'epg_guide_' . md5(json_encode([
                'user'       => $user->id,
                'zipcode'    => $zipcode,
                'genre'      => $genre,
                'day'        => $day,
                'page'       => $page,
                'limit'      => $limit,
                'typeurl'    => $urlType,
            ]));

            $query = Channel::with([
                'zipcode',
                'premium',
                'phxLocal',
                'eastCoast'
            ])
                ->select('channels.id', 'channels.image as db_image', 'channels.type', 'channels.status', 'channels.image as local_image', 'channels.is_logo_from_gracenote', 'channels.prgSvcId', "channels.$type as url", 'channels.stationName', 'gracenote_sources.callSign', 'gracenote_sources.timeZone', 'gracenote_sources.url as channel_url', 'gracenote_sources.assets')
                ->selectRaw("CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(genre.name, 'Others')), ',', '\",\"'), '\"]') as genrename")
                ->selectRaw('CAST(IFNULL(genre.name,"Others") AS CHAR) AS genre_name')
                ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
                ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
                ->leftJoin('gracenote_sources', 'gracenote_sources.prgSvcId', '=', 'channels.prgSvcId');






            if ($validate == 1) {
                if (!empty($zipcode)) {


                    $query->localZipcode($zipcode)
                        ->premiumStatus($user)
                        ->phxLocalStatus($user)
                        ->eastCoastStatus($user)
                        ->orwhere('channels.type', 'all')
                        ->where('channels.status', 1)
                        ->orwhereNull('channels.type')
                        ->where('channels.status', 1);
                }
            } elseif ($validate == 2) {
                if (!empty($zipcode)) {

                    $query->localZipcode($zipcode)
                        ->premiumStatus($user)
                        ->phxLocalStatus($user)
                        ->eastCoastStatus($user)
                        ->where('channels.status', 1);
                }
            }



            $query->where('channels.status', 1);





            if (!empty($genre) && $genre != 'Others') {
                $query->where('genre.name', '=', $genre);
            } else if ($genre == 'Others') {
                $query->whereNull('genre.name');
            }
            $query->groupBy('channels.id');
            if ($user->email == "streamlymtairy@gmail.com") {
                $query->orderBy('channels.id', 'desc');
            } else {
                $query->orderBy('channels.id', 'asc');
            }



            $channelsx = Cache::remember($cacheKey, now()->addMinutes(5), function () use (
                $query,
                $limit,
                $page,
                $day,
                $user
            ) {
                Log::info('CACHE MISS');
                $channels =  $query->paginate($limit, ['*'], 'page', $page);

                $channels->setCollection($channels->getCollection()->transform(function ($channel) use ($day) {
                    $channel->favourite = FavouriteChannel::where('channels_id', $channel->id)
                        ->where('user_id', JWTAuth::user()->id)
                        ->exists();

                    $assetData = $this->getURIFromAsset($channel->assets);

                    $channel->image = $assetData['uri'] ?? env('APP_URL') . '/uploads/channellogo/' . $channel->db_image;
                    $channel->ratio = $assetData['ratio'] ?? 'NA';

                    $channel->program = $this->getProgramScheduleByIdWithCache($channel->prgSvcId, $day)->map(function ($item) use ($channel) {
                        // station name inside program
                        // $item->stationName = $channel->stationName;
                        return $item;
                    });
                    unset($channel->assets);

                    if ($channel->stationName == null) {
                        $channel->stationName = "NA";
                    }
                    if ($channel->callSign == null) {
                        $channel->callSign = "NA";
                    }
                    if ($channel->timeZone == null) {
                        $channel->timeZone = "NA";
                    }
                    if ($channel->channel_url == null) {
                        $channel->channel_url = "NA";
                    }
                    if ($channel->prgSvcId == null) {
                        $channel->prgSvcId = "NA";
                    }
                    if ($channel->image == null) {
                        $channel->image = env('APP_URL') . '/uploads/No Image.png';
                    }




                    if ($channel->id == 11) {
                        $channel->stationName = 'FanDuel';
                    }
                    if ($channel->id == 6) {
                        $channel->stationName = 'Trinity Broadcasting Network';
                    }
                    if ($channel->id == 19) {
                        $channel->stationName = 'Hallmark Family';
                    }
                    if ($channel->id == 45) {
                        $channel->stationName = 'Fuse Music';
                    }



                    if ($channel->is_logo_from_gracenote == 0) {
                        $channel->image = url('/uploads/channellogo') . '/' . $channel->local_image;
                    }


                    // just for additional data hiding
                    unset($channel->zipcode);
                    unset($channel->primium);
                    unset($channel->is_logo_from_gracenote);
                    unset($channel->local_image);
                    unset($channel->db_image);



                    $channel->program->makeHidden([
                        'description',
                        'quals',
                        'time',
                        'image',

                    ]);
                    $channel->makeHidden([
                        'type',
                        'status',
                        'prgSvcId',
                        'channel_url',
                        'genrename',
                        'genre_name',
                        'ratio',
                        'premium',
                        'east_coast',
                    ]);


                    // here just hidding relation data
                    $channel = $channel instanceof \Illuminate\Database\Eloquent\Model
                        ? $channel->toArray()
                        : (array) $channel;

                    unset($channel['phx_local']);
                    // here just hidding relation data
                    $channel = $channel instanceof \Illuminate\Database\Eloquent\Model
                        ? $channel->toArray()
                        : (array) $channel;

                    unset($channel['east_coast']);

                    return $channel;
                }));






                return $channels;
            });




            $response = [];
            $response['success'] = true;
            $response['response_code'] = 200;
            $response['message'] = 'Epg Guide Retrieved successfully.';
            if ($day == 'tomorrow') {
                $gmdate = gmdate('Y-m-d', strtotime('+1 day'));
                $start_time = GracenoteScheduleProgram::select('start')->where('date', $gmdate)->orderBy('start', 'asc')->first();
                // var_dump($start_time);
                $response['start'] = $start_time->start;
            }
            $response['data'] = $channelsx->items();
            $response['total'] = $channelsx->total();
            $response['last_page'] = $channelsx->lastPage();
            $response['current_page'] = $channelsx->currentPage();
            $response['from'] = $channelsx->firstItem();
            $response['to'] = $channelsx->lastItem();
            $response['limit'] = $channelsx->perPage();
            $response['has_more'] = $channelsx->hasMorePages();
            // Log::info('Response Size: ' . round(strlen(json_encode($response)) / 1024 / 1024, 2) . ' MB');
            return response()->json($response, 200);
        }
    }


    private function getProgramScheduleById($prgSvcId, $day)
    {
        $gmdate_string = gmdate('Y-m-d H:i');
        $current_timestamp = strtotime($gmdate_string . ' GMT');
        $query = GracenoteScheduleProgram::where('prgSvcId', $prgSvcId)->select('title', 'description', 'TMSId', 'quals', 'time', 'start', 'end', 'assets');
        if ($day == 'today') {
            $gmdate = gmdate('Y-m-d');
            $query->where('end', '>=', $current_timestamp);
        } else if ($day == 'tomorrow') {
            $gmdate = gmdate('Y-m-d', strtotime('+1 day'));
        }
        if (!empty($day)) {
            $query->where('date', $gmdate);
        } else {
            $query->where('end', '>=', $current_timestamp);
        }
        $programs = $query->get();
        $programs->map(function ($program) {
            $program->image = $this->getURIFromAssetForProgram($program->assets);

            if (strcasecmp($program->title, 'TV Guide') === 0) {
                $program->title = 'FanDuel';
            }

            unset($program->assets);
        });



        return $programs;
    }

    private function getProgramScheduleByIdWithCache($prgSvcId, $day)
    {
        $cacheKey = "program_schedule_{$prgSvcId}_{$day}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($prgSvcId, $day) {
            $gmdate_string = gmdate('Y-m-d H:i');
            $current_timestamp = strtotime($gmdate_string . ' GMT');
            $query = GracenoteScheduleProgram::where('prgSvcId', $prgSvcId)->select('title', 'description', 'TMSId', 'quals', 'time', 'start', 'end', 'assets');
            if ($day == 'today') {
                $gmdate = gmdate('Y-m-d');
                $query->where('end', '>=', $current_timestamp);
            } else if ($day == 'tomorrow') {
                $gmdate = gmdate('Y-m-d', strtotime('+1 day'));
            }
            if (!empty($day)) {
                $query->where('date', $gmdate);
            } else {
                $query->where('end', '>=', $current_timestamp);
            }
            $programs = $query->get();
            $programs->map(function ($program) {
                $program->image = $this->getURIFromAssetForProgram($program->assets);

                if (strcasecmp($program->title, 'TV Guide') === 0) {
                    $program->title = 'FanDuel';
                }

                unset($program->assets);
            });



            return $programs;
        });
    }

    public function searchChannelProgram(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'required',
            'typeurl' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'validation_error',
                'message' => $validator->errors()
            ], 400);
        } else {
            $user = JWTAuth::user();
            $zipcode = $request->get('zipcode');
            $search = $request->input('search');
            $urlType = $request->typeurl;
            if ($urlType == 'manifest') {
                $type = 'smooth_url';
            } else if ($urlType == 'm3u8') {
                $type = 'url';
            } else if ($urlType == "mpd") {
                $type = 'dash_url';
            } else {
                $type = 'dash_url';
            }
            $validate = 0;

            // Check DB for email-specific zipcode rules
            $zipcodeRule = UserZipcodeRule::where('email', $user->email)->first();
            if ($zipcodeRule) {
                $validate = $zipcodeRule->validate;
                $zipcode = $zipcodeRule->zipcode;
            } elseif (!in_array($user->email, $this->exculdedUsers) && $request->has('zipcode')) {
                $validate = 1;
            }

            // Fetch programs matching the search query
            $pquery = GracenoteScheduleProgram::with([
                'channel',
                'channel.zipcode',
                'channel.premium'
            ])->select('channels.id', 'gracenote_schedule_program.prgSvcId', 'title as name', 'start', 'end', 'description', 'assets', 'TMSId', "channels.$type as url")
                ->join('channels', 'gracenote_schedule_program.prgSvcId', '=', 'channels.prgSvcId')
                ->where('title', 'like', "%{$search}%")
                ->whereHas('channel', function ($q) use ($zipcode, $user, $validate) {

                    if ($validate == 1) {
                        if (!empty($zipcode)) {


                            $q->localZipcode($zipcode)
                                ->premiumStatus($user)
                                ->orwhere('channels.type', 'all')
                                ->where('channels.status', 1)
                                ->orwhereNull('channels.type')
                                ->where('channels.status', 1);
                        }
                    } elseif ($validate == 2) {
                        if (!empty($zipcode)) {

                            $q->localZipcode($zipcode)
                                ->premiumStatus($user)
                                ->where('channels.status', 1);
                        }
                    }
                });


            $pquery->distinct('gracenote_schedule_program.TMSId');
            $pquery->where('channels.status', '=', '1');

            $programs = $pquery->get();
            // Process each program to add image URI and type, and remove assets
            $filteredPrograms = collect($programs)
                ->map(function ($program) {
                    $program->image = $this->getURIFromAssetForProgram($program->assets);
                    $program->roku_image = $program->image;
                    $program->type = 'event';
                    $program->callSign = null;
                    // $program->url = null;
                    // Convert start and end timestamps to Carbon instances
                    $startTime = Carbon::parse($program->start, 'UTC');
                    $endTime = Carbon::parse($program->end, 'UTC');

                    // Get the current timestamp in the UTC timezone
                    $currentTimestamp = Carbon::now('UTC');
                    // Determine if play_icon should be true or false
                    $program->play_icon = $currentTimestamp->between($startTime, $endTime) ?? false;
                    unset($program->assets);
                    unset($program->start);
                    unset($program->end);
                    return $program;
                })->groupBy('TMSId') // Group by TMSId to filter duplicates
                ->map(function ($group) {
                    // Sort so the one with play_icon = true comes first
                    return $group->sortByDesc('play_icon')->first();
                })
                ->values();;
            // Sorting in ascending order by 'price'
            $sortedPrograms = $filteredPrograms->sortByDesc('play_icon');

            // Convert back to indexed array (optional)
            $sortedPrograms = $sortedPrograms->values();
            // Fetch channels matching the search query

            $query = GracenoteSource::with([
                'channel',
                'channels.zipcode',
                'channels.premium'
            ])->select('channels.id', 'channels.type', 'channels.status', 'channels.image as db_image', 'channels.image as local_image', 'channels.is_logo_from_gracenote', 'gracenote_sources.prgSvcId', 'gracenote_sources.stationName as name', 'gracenote_sources.callSign', 'assets', "channels.$type as url",)
                ->join('channels', 'gracenote_sources.prgSvcId', '=', 'channels.prgSvcId')
                ->where('gracenote_sources.stationName', 'like', "%{$search}%")

                //     if ($validate == 1) {
                //     if (!empty($zipcode)) {
                //         $query->where(function ($q) use ($zipcode) {
                //             $q->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"')")
                //                 ->orWhereRaw("JSON_CONTAINS(channels.zip_code, '\"All\"')");
                //         });
                //     }
                // } elseif ($validate == 2) {
                //     if (!empty($zipcode)) {
                //         $query->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"')");
                //     }
                // }

                // $query->where(function ($q) {
                //     $q->whereIn('channels.type', ['all', 'local'])
                //         ->orWhereNull('channels.type');
                // });

                // if ($user->access_premium_channel == 1) {
                //     $query->orwhere('channels.type', 'premium');

                // }

                // $query->where(function ($q) use ($user) {
                //     if ($user->access_premium_channel == 1) {
                //         $q->whereIn('channels.type', ['all', 'local', 'premium'])
                //             ->orWhereNull('channels.type');
                //     } else {
                //         $q->whereIn('channels.type', ['all', 'local'])
                //             ->orWhereNull('channels.type');
                //     }

                // });
                ->whereHas('channel', function ($q) use ($zipcode, $user, $validate) {

                    if ($validate == 1) {
                        if (!empty($zipcode)) {


                            $q->localZipcode($zipcode)
                                ->premiumStatus($user)
                                ->orwhere('channels.type', 'all')
                                ->where('channels.status', 1)
                                ->orwhereNull('channels.type')
                                ->where('channels.status', 1);
                        }
                    } elseif ($validate == 2) {
                        if (!empty($zipcode)) {

                            $q->localZipcode($zipcode)
                                ->premiumStatus($user)
                                ->where('channels.status', 1);
                        }
                    }
                });

            $query->where('channels.status', '=', 1);


            $channels = $query->get();

            // Process each channel to add image URI and type, and remove assets
            $channels->map(function ($channel) {
                $assetData = $this->getURIFromAsset(json_encode($channel->assets));
                $channel->image = $assetData['uri'];
                $channel->roku_image = $channel->image != null ? url('storage/channel_roku_images/resized_image_' . basename($channel->image)) : env('APP_URL') . '/uploads/channellogo/' . $channel->db_image;
                if ($channel->image == null) {
                    $channel->image = env('APP_URL') . '/uploads/channellogo/' . $channel->db_image ?? env('APP_URL') . '/uploads/No Image.png';
                }
                if ($channel->roku_image == null) {
                    $channel->roku_image = env('APP_URL') . '/uploads/No Image.png';
                }

                if ($channel->id == 11) {
                    $channel->name = 'FanDuel';
                    // $channel->image = env('APP_URL') . '/uploads/fandue.png';
                    // $channel->roku_image = env('APP_URL') . '/uploads/fandue.png';
                }

                if ($channel->id == 6) {
                    $channel->stationName = 'Trinity Broadcasting Network';
                    // $channel->image = env('APP_URL') . '/uploads/tbn-6.png';
                    // $channel->roku_image = env('APP_URL') . '/uploads/tbn-6.png';

                }
                if ($channel->id == 19) {
                    $channel->stationName = 'Hallmark Family';
                    // $channel->image = env('APP_URL') . '/uploads/hallm-fam-19.png';
                    // $channel->roku_image = env('APP_URL') . '/uploads/hallm-fam-19.png';

                }
                if ($channel->id == 45) {
                    $channel->stationName = 'Fuse Music';
                    // $channel->image = env('APP_URL') . '/uploads/fuse-mu-45.png';
                    // $channel->roku_image = env('APP_URL') . '/uploads/fuse-mu-45.png';

                }
                // if ($channel->id == 134) {
                //     $channel->image = env('APP_URL') . '/uploads/ocbs-134.png';
                //     $channel->roku_image = env('APP_URL') . '/uploads/ocbs-134.png';
                // }
                // if ($channel->id == 135) {
                //     $channel->image = env('APP_URL') . '/uploads/WCAU-135.png';
                //     $channel->roku_image = env('APP_URL') . '/uploads/WCAU-135.png';
                // }
                // if ($channel->id == 136) {
                //     $channel->image = env('APP_URL') . '/uploads/fox29-136.png';
                //     $channel->roku_image = env('APP_URL') . '/uploads/fox29-136.png';
                // }
                // if ($channel->id == 137) {
                //     $channel->image = env('APP_URL') . '/uploads/abc7-137.png';
                //     $channel->roku_image = env('APP_URL') . '/uploads/abc7-137.png';
                // }
                // if ($channel->id == 138) {
                //     $channel->image = env('APP_URL') . '/uploads/ocbs-138.png';
                //     $channel->roku_image = env('APP_URL') . '/uploads/ocbs-138.png';
                // }
                // if ($channel->id == 139) {
                //     $channel->image = env('APP_URL') . '/uploads/fox-139.png';
                //     $channel->roku_image = env('APP_URL') . '/uploads/fox-139.png';
                // }
                // if ($channel->id == 140) {
                //     $channel->image = env('APP_URL') . '/uploads/4ny-140.png';
                //     $channel->roku_image = env('APP_URL') . '/uploads/4ny-140.png';
                // }

                if ($channel->is_logo_from_gracenote == 0) {
                    $channel->image = url('uploads/channellogo') . '/' . $channel->local_image;
                    $channel->roku_image = url('uploads/channellogo') . '/' . $channel->local_image;
                }

                unset($channel->assets);
                $channel->type = 'channel';
                $channel->description = null;
                $channel->TMSId = null;


                $channel->play_icon = true;
                unset($channel->assets);
            });

            // $programs = ($programs->unique('name')->toArray());
            $data = collect(array_merge($channels->toArray(), $sortedPrograms->toArray()));

            $response = [
                'success' => true,
                'response_code' => 200,
                'message' => 'Search Results Retrieved successfully.',
                'data' => $data
            ];
            return response()->json($response);
        }
    }

    // public function searchChannelProgram(Request $request)
    // {
    //     $search = $request->input('search');

    //     // Fetch programs matching the search query
    //     $programs = GracenoteScheduleProgram::select('prgSvcId', 'title as name', 'description', 'assets')
    //         ->where('title', 'like', "%{$search}%")
    //         ->get();

    //     // Process each program to add image URI and type, and remove assets
    //     $programs->map(function ($program) {
    //         $program->image = $this->getURIFromAssetForProgram($program->assets);
    //         $program->type = 'event';
    //         $program->callSign = Null;
    //         unset($program->assets);
    //     });

    //     // Fetch channels matching the search query
    //     $channels = GracenoteSource::select('prgSvcId', 'stationName as name', 'callSign', 'assets')
    //         ->where('stationName', 'like', "%{$search}%")
    //         ->get();

    //     // Process each channel to add image URI and type, and remove assets
    //     $channels->map(function ($channel) {
    //         $channel->image = $this->getURIFromAsset(json_encode($channel->assets));
    //         $channel->type = 'channel';
    //         $channel->description = NULL;
    //         unset($channel->assets);
    //     });

    //     // Merge programs and channels into a single collection
    //     $data = $channels->merge($programs);

    //     return response()->json($data);
    // }

    public function allGenre()
    {
        $response = Cache::remember('all_genres', now()->addMinutes(5), function () {
            $genres = Genre::select('name', 'image')->orderBy('position')->get();

            $genres->map(function ($genre) {
                $genre->image = env('APP_URL') . $genre->image;
            });
            return [
                'success' => true,
                'response_code' => 200,
                'message' => 'All genres Retrieved successfully.',
                'data' => $genres
            ];
        });
        return response()->json($response);
    }

    public function programDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TMSId' => 'required',
            'channel_id' => 'required',
            'timezone' => 'nullable'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'validation_error',
                'message' => $validator->errors()
            ], 400);
        } else {
            $TMSId = $request->TMSId;
            $timezone = $request->timezone ?? 'UTC'; // Default to UTC if no timezone is provided
            $programs = GracenoteScheduleProgram::where('TMSId', $TMSId)->select('channels.id as channel_id', 'gracenote_schedule_program.prgSvcId', 'gracenote_sources.stationName', 'channels.url', 'dash_url', 'smooth_url', 'title', 'description', 'duration', 'quals', 'gracenote_schedule_program.assets', 'gracenote_schedule_program.start', 'gracenote_schedule_program.end')
                ->selectRaw("CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(genre.name, 'Others')), ',', '\",\"'), '\"]') as genre")
                ->leftJoin('channels', 'gracenote_schedule_program.prgSvcId', '=', 'channels.prgSvcId')
                ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
                ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
                ->leftJoin('gracenote_sources', 'gracenote_schedule_program.prgSvcId', '=', 'gracenote_sources.prgSvcId')
                ->where('channels.id', $request->channel_id)
                ->groupBy('gracenote_schedule_program.id')
                ->get();

            if (!empty($programs)) {
                foreach ($programs as $program) {
                    // Convert start and end timestamps to Carbon instances
                    $startTime = Carbon::parse($program->start, 'UTC')->setTimezone($timezone);
                    $endTime = Carbon::parse($program->end, 'UTC')->setTimezone($timezone);

                    // Get the current timestamp in the requested timezone
                    $currentTimestamp = Carbon::now($timezone);
                    // Determine if play_icon should be true or false
                    $program_play_icon = $currentTimestamp->between($startTime, $endTime);
                    if ($program_play_icon == true) {
                        break;
                    } else {
                        continue;
                    }
                }
                $program_details = GracenoteScheduleProgram::select('date', 'time', 'duration', 'start')
                    ->where('title', 'like', "%{$program->title}%")
                    ->where('gracenote_schedule_program.prgSvcId', $programs[0]->prgSvcId)
                    ->orderBy('start')
                    ->get();

                $stationName = $programs[0]->stationName;
                $channelId = $programs[0]->channel_id;
                $image = $this->getURIFromAssetForProgram($program->assets);

                // Replace logic for TV Guide / TVGN
                // if (trim($stationName) === 'TV Guide' || trim($programs[0]->callSign ?? '') === 'TVGN') {
                //     $stationName = 'FanDuel';
                //     $image = env('APP_URL') . '/uploads/fanduel.png'; // your custom FanDuel logo
                // }

                if ($channelId == 11) {
                    $stationName = 'FanDuel';
                    // $image = env('APP_URL') . '/uploads/fandue.png'; // your custom FanDuel logo
                    $programs[0]->title = 'FanDuel'; // Replace title also
                }

                if ($channelId == 6) {
                    $stationName = 'Trinity Broadcasting Network';
                    // $image = env('APP_URL') . '/uploads/tbn-6.png'; // your custom FanDuel logo

                }



                $data = [
                    'channel_id' => $programs[0]->channel_id,
                    'play_icon' => $program_play_icon,
                    // 'stationName' => $programs[0]->stationName,
                    'stationName' => $stationName,
                    'url' => $programs[0]->url,
                    'dash_url' => $programs[0]->dash_url,
                    'smooth_url' => $programs[0]->smooth_url,
                    "title" => $programs[0]->title,
                    "description" => $programs[0]->description,
                    "duration" => $programs[0]->duration,
                    "quals" => $programs[0]->quals,
                    "genre" => json_decode($programs[0]->genre),
                    "image" => $image,
                    // "image" => $this->getURIFromAssetForProgram($program->assets),
                    "schedule" => $program_details
                ];
                unset($program->assets);
            } else {
                $data = [];
            }
            $response = [
                'success' => true,
                'response_code' => 200,
                'message' => 'Program Details Retrieved successfully.',
                'data' => $data
            ];

            return response()->json($response);
        }
    }


    public function playerDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'channel_id' => 'required',
            'current_datetime' => 'required|date|date_format:Y-m-d H:i:s'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'validation_error',
                'message' => $validator->errors()
            ], 400);
        } else {
            $channel_id = $request->channel_id;
            $zipcode = $request->get('zipcode');
            $current_datetime = $request->current_datetime;
            $validate = 0;
            $user = JWTAuth::user();

            // Check DB for email-specific zipcode rules
            $zipcodeRule = UserZipcodeRule::where('email', $user->email)->first();
            if ($zipcodeRule) {
                $validate = $zipcodeRule->validate;
                $zipcode = $zipcodeRule->zipcode;
            } elseif (!in_array($user->email, $this->exculdedUsers) && $request->has('zipcode')) {
                $validate = 1;
            }

            $query = Channel::where('channels.id', $channel_id)->select('channels.id', 'channels.type', 'channels.image as db_image', 'channels.image as local_image', 'channels.is_logo_from_gracenote', 'channels.prgSvcId', 'channels.url', 'dash_url', 'smooth_url', 'gracenote_sources.stationName', 'gracenote_sources.callSign', 'gracenote_sources.assets')
                ->selectRaw("CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(genre.name, 'Others')), ',', '\",\"'), '\"]') as genrename")
                ->selectRaw("CONCAT('[\"', REPLACE(GROUP_CONCAT(genre.id), ',', '\",\"'), '\"]') as genre_ids")
                ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
                ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
                ->leftJoin('gracenote_sources', 'gracenote_sources.prgSvcId', '=', 'channels.prgSvcId');
            $query->groupBy('channels.id');
            $record = $query->get();

            $genre_ids = json_decode($record[0]->genre_ids);
            $query1 = Channel::select('channels.id', 'channels.type', 'channels.status', 'channels.image as db_image', 'channels.image as local_image', 'channels.is_logo_from_gracenote', 'channels.prgSvcId', 'channels.url', 'dash_url', 'smooth_url', 'gracenote_sources.stationName', 'gracenote_sources.callSign', 'gracenote_sources.assets')
                ->selectRaw("CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(genre.name, 'Others')), ',', '\",\"'), '\"]') as genrename")
                ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
                ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
                ->leftJoin('gracenote_sources', 'gracenote_sources.prgSvcId', '=', 'channels.prgSvcId');
            if ($validate == 1) {
                if (!empty($zipcode)) {
                    // $query1->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"') OR JSON_CONTAINS(channels.zip_code, '\"All\"')");
                    $query1->where(function ($q) use ($zipcode) {
                        $q->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"')")
                            ->orWhereRaw("JSON_CONTAINS(channels.zip_code, '\"All\"')");
                    });
                }
            } elseif ($validate == 2) {
                if (!empty($zipcode)) {
                    $query1->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"')");
                }
            }

            if ($user->access_premium_channel == 0) {
                $query->where('channels.type', '!=', 'premium')
                    ->orWhere('channels.type', null);
            }

            $query1->where('channels.status', '=', 1);
            $query1->where('channels.id', '!=', $channel_id);
            if (!empty($genre_ids)) {
                $query1->whereIn('channels_genre.genre_id', $genre_ids);
            } else {
                $query1->whereNull('channels_genre.genre_id');
            }
            $query1->groupBy('channels.id');
            $recommended_channels = $query1->get()->unique()->map(function ($channel) {
                $assetData = $this->getURIFromAsset($channel->assets) ?? env('APP_URL') . '/uploads/channellogo/' . $channel->db_image;
                $channel->image = $assetData['uri'];
                if ($channel->id == 11) {
                    $channel->stationName = 'FanDuel';
                    // $channel->image = env('APP_URL') . '/uploads/fandue.png'; // your custom FanDuel logo

                }
                if ($channel->id == 6) {
                    $channel->stationName = 'Trinity Broadcasting Network';
                    // $channel->image = env('APP_URL') . '/uploads/tbn-6.png';

                }
                if ($channel->id == 19) {
                    $channel->stationName = 'Hallmark Family';
                    // $channel->image = env('APP_URL') . '/uploads/hallm-fam-19.png';

                }
                if ($channel->id == 45) {
                    $channel->stationName = 'Fuse Music';
                    // $channel->image = env('APP_URL') . '/uploads/fuse-mu-45.png';

                }
                // if ($channel->id == 134) {
                //     $channel->image = env('APP_URL') . '/uploads/ocbs-134.png';
                // }
                // if ($channel->id == 135) {
                //     $channel->image = env('APP_URL') . '/uploads/WCAU-135.png';
                // }
                // if ($channel->id == 136) {
                //     $channel->image = env('APP_URL') . '/uploads/fox29-136.png';
                // }
                // if ($channel->id == 137) {
                //     $channel->image = env('APP_URL') . '/uploads/abc7-137.png';
                // }
                // if ($channel->id == 138) {
                //     $channel->image = env('APP_URL') . '/uploads/ocbs-138.png';
                // }
                // if ($channel->id == 139) {
                //     $channel->image = env('APP_URL') . '/uploads/fox-139.png';
                // }
                // if ($channel->id == 140) {
                //     $channel->image = env('APP_URL') . '/uploads/4ny-140.png';
                // }

                if ($channel->is_logo_from_gracenote == 0) {
                    $channel->image = url('uploads/channellogo') . '/' . $channel->local_image;
                }

                unset($channel->db_image);
                unset($channel->assets);
                return $channel;
            });
            $record->map(function ($channel) {
                $assetData = $this->getURIFromAsset($channel->assets) ?? env('APP_URL') . '/uploads/channellogo/' . $channel->db_image;
                $channel->image = $assetData['uri'];
                $channel->genre_ids = json_decode($channel->genre_ids);

                if ($channel->id == 11) {
                    $channel->stationName = 'FanDuel';
                    // $channel->image = env('APP_URL') . '/uploads/fandue.png'; // your custom FanDuel logo

                }
                if ($channel->id == 6) {
                    $channel->stationName = 'Trinity Broadcasting Network';
                    // $channel->image = env('APP_URL') . '/uploads/tbn-6.png';

                }
                if ($channel->id == 19) {
                    $channel->stationName = 'Hallmark Family';
                    // $channel->image = env('APP_URL') . '/uploads/hallm-fam-19.png';

                }
                if ($channel->id == 45) {
                    $channel->stationName = 'Fuse Music';
                    // $channel->image = env('APP_URL') . '/uploads/fuse-mu-45.png';

                }

                // if ($channel->id == 134) {
                //     $channel->image = env('APP_URL') . '/uploads/ocbs-134.png';
                // }
                // if ($channel->id == 135) {
                //     $channel->image = env('APP_URL') . '/uploads/WCAU-135.png';
                // }
                // if ($channel->id == 136) {
                //     $channel->image = env('APP_URL') . '/uploads/fox29-136.png';
                // }
                // if ($channel->id == 137) {
                //     $channel->image = env('APP_URL') . '/uploads/abc7-137.png';
                // }
                // if ($channel->id == 138) {
                //     $channel->image = env('APP_URL') . '/uploads/ocbs-138.png';
                // }
                // if ($channel->id == 139) {
                //     $channel->image = env('APP_URL') . '/uploads/fox-139.png';
                // }
                // if ($channel->id == 140) {
                //     $channel->image = env('APP_URL') . '/uploads/4ny-140.png';
                // }

                if ($channel->is_logo_from_gracenote == 0) {
                    $channel->image = url('uploads/channellogo') . '/' . $channel->local_image;
                }

                unset($channel->db_image);
                unset($channel->assets);
                unset($channel->genre_ids);
                return $channel;
            });

            $current_datetimestamp = Carbon::parse($current_datetime, 'UTC')->timestamp;

            $program = GracenoteScheduleProgram::select('title', 'description', 'duration', 'quals', 'assets', 'date', 'time')
                ->selectRaw("CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(genre.name, 'Others')), ',', '\",\"'), '\"]') as genre")
                ->leftJoin('channels', 'gracenote_schedule_program.prgSvcId', '=', 'channels.prgSvcId')
                ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
                ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
                ->where('start', '<=', $current_datetimestamp)
                ->where('end', '>=', $current_datetimestamp)
                ->where('gracenote_schedule_program.prgSvcId', $record[0]->prgSvcId)
                // WHERE `start` <= 1719313930 AND `end` >= 1719313930
                ->groupBy('gracenote_schedule_program.id')
                ->first();

            if (!empty($program)) {
                $up_next = GracenoteScheduleProgram::select('title', 'description', 'time', 'duration')
                    ->where('date', $program->date)
                    ->where('time', '>', $program->time)
                    ->where('gracenote_schedule_program.prgSvcId', $record[0]->prgSvcId)
                    ->orderBy('start')
                    ->get();

                if (strtolower(trim($program->title)) === 'tv guide') {
                    $data = [
                        "title" => "FanDuel",
                        "description" => "FanDuel",
                        "duration" => $program->duration,
                        "quals" => $program->quals,
                        "genre" => json_decode($program->genre),
                        "image" => env('APP_URL') . '/uploads/fandue.png', // your custom FanDuel logo
                        "up_next" => $up_next
                    ];
                } else {
                    $data = [
                        "title" => $program->title,
                        "description" => $program->description,
                        "duration" => $program->duration,
                        "quals" => $program->quals,
                        "genre" => json_decode($program->genre),
                        "image" => $this->getURIFromAssetForProgram($program->assets),
                        "up_next" => $up_next
                    ];
                }
            } else {
                $data = NULL;
            }
            // Previous and Next Channel Logic with ZIP Code Filtering
            $PrevNext = $channel_id;

            $all_channel_query = DB::table('channels')
                ->where('status', '=', 1);
            if ($validate == 1) {
                if (!empty($zipcode)) {
                    $all_channel_query->where(function ($q) use ($zipcode) {
                        $q->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"')")
                            ->orWhereRaw("JSON_CONTAINS(channels.zip_code, '\"All\"')");
                    });
                }
            } elseif ($validate == 2) {
                if (!empty($zipcode)) {
                    $all_channel_query->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"')");
                }
            }
            $all_channel_query->orderBy('id', 'desc');
            $all_channel_query->select('id');
            $all = $all_channel_query->first();

            $nextChannel = $PrevNext;

            if ($PrevNext == 1) {
                $PrevNext = 2;
                $nextChannel = 1;
            }
            if ($PrevNext == $all->id) {
                $nextChannel = $all->id - 1;
            }

            $prevChannel_query = DB::table('channels')
                ->where('id', '<', $PrevNext)
                ->where('status', '1');
            if ($validate == 1) {
                if (!empty($zipcode)) {
                    $prevChannel_query->where(function ($q) use ($zipcode) {
                        $q->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"')")
                            ->orWhereRaw("JSON_CONTAINS(channels.zip_code, '\"All\"')");
                    });
                }
            } elseif ($validate == 2) {
                if (!empty($zipcode)) {
                    $prevChannel_query->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"')");
                }
            }
            $prev = $prevChannel_query->max('id');
            $prevChannel = DB::table('channels')->where('id', $prev)->where('status', '1')->select('id', 'name', 'callSign', 'channels.url', 'dash_url', 'smooth_url')->first();
            $nextChannel_query = DB::table('channels')
                ->where('id', '>', $nextChannel)
                ->where('status', '1');
            if ($validate == 1) {
                if (!empty($zipcode)) {
                    $nextChannel_query->where(function ($q) use ($zipcode) {
                        $q->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"')")
                            ->orWhereRaw("JSON_CONTAINS(channels.zip_code, '\"All\"')");
                    });
                }
            } elseif ($validate == 2) {
                if (!empty($zipcode)) {
                    $nextChannel_query->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"')");
                }
            }
            $next = $nextChannel_query->min('id');
            $nextChannel = DB::table('channels')->where('id', $next)->where('status', '1')->select('id', 'name', 'callSign', 'channels.url', 'dash_url', 'smooth_url')->first();
            $response = [
                'success' => true,
                'response_code' => 200,
                'message' => 'Player Details Retrieved successfully.',
                'data' => [
                    "channel" => $record[0],
                    "recommended_channels" => $recommended_channels,
                    "program_details" => $data,
                    "prevChannel" => $prevChannel,
                    "nextChannel" => $nextChannel
                ],

            ];
            return response()->json($response, 200);
        }
    }

    public function ChannelProgramDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'channel_id' => 'required',
            'current_datetime' => 'required|date|date_format:Y-m-d H:i:s'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'validation_error',
                'message' => $validator->errors()
            ], 400);
        } else {
            $channel_id = $request->channel_id;
            $current_datetime = $request->current_datetime;
            $query = Channel::where('channels.id', $channel_id)->select('channels.id', 'channels.image as db_image', 'channels.prgSvcId', 'gracenote_sources.stationName', 'gracenote_sources.callSign', 'gracenote_sources.assets')
                ->selectRaw("CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(genre.name, 'Others')), ',', '\",\"'), '\"]') as genrename")
                ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
                ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
                ->leftJoin('gracenote_sources', 'gracenote_sources.prgSvcId', '=', 'channels.prgSvcId');
            $query->groupBy('channels.id');
            $record = $query->get();

            $record->map(function ($channel) {
                $channel->image = $this->getURIFromAsset($channel->assets) ?? env('APP_URL') . '/uploads/channellogo/' . $channel->db_image;
                unset($channel->db_image);
                unset($channel->assets);
                return $channel;
            });

            $current_datetimestamp = Carbon::parse($current_datetime, 'UTC')->timestamp;

            $program = GracenoteScheduleProgram::select('title', 'description', 'duration', 'quals', 'assets', 'date', 'time')
                ->selectRaw("CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(genre.name, 'Others')), ',', '\",\"'), '\"]') as genre")
                ->leftJoin('channels', 'gracenote_schedule_program.prgSvcId', '=', 'channels.prgSvcId')
                ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
                ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
                ->where('start', '<=', $current_datetimestamp)
                ->where('end', '>=', $current_datetimestamp)
                ->where('gracenote_schedule_program.prgSvcId', $record[0]->prgSvcId)
                // WHERE `start` <= 1719313930 AND `end` >= 1719313930
                ->groupBy('gracenote_schedule_program.id')
                ->first();
            if (!empty($program)) {

                $title = trim($program->title);
                if ($title === 'TV Guide' || $title === 'TVGN') {
                    $title = 'FanDuel';
                }
                $data = [
                    "title" => $title,
                    "description" => $program->description,
                    "duration" => $program->duration,
                    "quals" => $program->quals,
                    "genre" => json_decode($program->genre),
                    "image" => $this->getURIFromAssetForProgram($program->assets),
                ];
            } else {
                $data = NULL;
            }
            $response = [
                'success' => true,
                'response_code' => 200,
                'message' => 'Channel Program Details Retrieved successfully.',
                'data' => [
                    "channel" => $record[0],
                    "program_details" => $data
                ],

            ];
            return response()->json($response, 200);
        }
    }
    // Function to resize an image to 100x100 pixels
    /* public function resizeImage($url = 'https://demo.tmsimg.com/assets/s57391_ld_h8_ae.png', $width = 100, $height = 100)
    {
        // $imageUrl = 'https://demo.tmsimg.com/assets/s57391_ld_h8_ae.png';

        // Load the image from the URL
        $image = imagecreatefrompng($url);

        // Create a blank image with the desired dimensions
        $resizedImage = imagecreatetruecolor($width, $height);

        // Preserve transparency
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage, true);

        // Get original image dimensions
        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);

        // Resize the original image into the new one
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);

        // Output the resized image to the browser
        header('Content-Type: image/png');
        imagepng($resizedImage);

        // Clean up memory
        imagedestroy($image);
        imagedestroy($resizedImage);
    } */
}
