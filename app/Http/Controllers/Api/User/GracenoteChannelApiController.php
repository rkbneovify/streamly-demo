<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Channel;
use App\Models\FavouriteChannel;
use App\Models\UserZipcodeRule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class GracenoteChannelApiController extends Controller
{
    private $exculdedUsers = ["jmheil@gmail.com", "llipschultz@neovix.com", "brian_marlin@yahoo.com", "qajalaj@yopmail.com", "pm13426@gmail.com", "nathan@neovix.com", "willis@neovix.com", "davea@neovix.com", "willthiswork2023@outlook.com"];

    public function channelList(Request $request)
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
            $user = JWTAuth::user();
            $urlType = $request->get('typeurl');
            $zipcode = $request->get('zipcode');
            $validate = 0;

            // Check DB for email-specific zipcode rules
            $zipcodeRule = UserZipcodeRule::where('email', $user->email)->first();
            if ($zipcodeRule) {
                $validate = $zipcodeRule->validate;
                $zipcode = $zipcodeRule->zipcode;
            } elseif (!in_array($user->email, $this->exculdedUsers) && $request->has('zipcode')) {
                $validate = 1;
            }

            $imagePath = url("/uploads/channellogo/");

            if ($urlType == 'manifest') {
                $type = 'smooth_url';
            } else if ($urlType == 'm3u8') {
                $type = 'url';
            } else if ($urlType == "mpd") {
                $type = 'dash_url';
            } else {
                $type = 'dash_url';
            }
            $query = Channel::select('channels.id', 'channels.image as db_image', 'channels.prgSvcId', "channels.$type as url", 'gracenote_sources.stationName', 'gracenote_sources.callSign', 'gracenote_sources.timeZone', 'gracenote_sources.url as channel_url', 'gracenote_sources.assets')
                // ->selectRaw("CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(genre.name, 'Others')), ',', '\",\"'), '\"]') as genrename")
                ->selectRaw('CAST(IFNULL(genre.name,"Others") AS CHAR) AS genre_name')
                ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
                ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
                ->leftJoin('gracenote_sources', 'gracenote_sources.prgSvcId', '=', 'channels.prgSvcId');
            if ($validate == 1) {
                if (!empty($zipcode)) {
                    // $query->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"') OR JSON_CONTAINS(channels.zip_code, '\"All\"')");
                    $query->where(function ($q) use ($zipcode) {
                        $q->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"')")
                            ->orWhereRaw("JSON_CONTAINS(channels.zip_code, '\"All\"')");
                    });
                }
            } elseif ($validate == 2) {
                if (!empty($zipcode)) {
                    $query->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"')");
                }
            }
            $query->where('channels.status', '=', '1');
            $query->orderBy('genre_name', 'asc');
            $record = $query->get()->map(function ($channel) {
                $channel->favourite = FavouriteChannel::where('channels_id', $channel->id)->where('user_id', JWTAuth::user()->id)->exists();
                $channel->image = $this->getURIFromAsset($channel->assets);
                $channel->roku_image = $channel->image != null ? url('storage/channel_roku_images/resized_image_' . basename($channel->image)) : env('APP_URL') . '/uploads/channellogo/' . $channel->db_image;
                if ($channel->image == null) {
                    $channel->image = env('APP_URL') . '/uploads/channellogo/' . $channel->db_image ?? env('APP_URL') . '/uploads/No Image.png';
                }
                if ($channel->roku_image == null) {
                    $channel->roku_image = env('APP_URL') . '/uploads/No Image.png';
                }
                if ($channel->id == 11) {
                    $channel->stationName = 'FanDuel';
                    $channel->image = env('APP_URL') . '/uploads/fandue.png';
                    $channel->roku_image = env('APP_URL') . '/uploads/fandue.png';

                }
                unset($channel->db_image);
                unset($channel->assets);
                return $channel;
            });

            $data = $record->groupBy('genre_name');
            foreach ($data as $genre => $channels) {
                $genreArray = [
                    "genre_name" => $genre,
                    "channels" => $channels
                ];
                $result[] = $genreArray;
            }
            $response = [
                'success' => true,
                'response_code' => 200,
                'message' => 'Channel List Retrieved successfully.',
                'data' => $result,

            ];
            return response()->json($response, 200);
        }
    }

    private function getURIFromAsset($assets)
    {
        $uri = NULL;
        // Initialize URI variables
        $uri_16_9 = null;
        $uri_4_3 = null;
        // Decode JSON to associative array
        $data = json_decode($assets, true);
        if (!empty($data)) {
            // foreach ($data['asset'] as $asset) {
            //     if (($asset['category'] === 'Parent Source Logo - dark' || $asset['category'] === 'Source Logo - dark') && $asset['ratio'] === '16:9') {
            //         $uri = "https://demo.tmsimg.com/" . $asset['URI'];
            //         break;
            //     }
            // }

            // Iterate through the assets
            foreach ($data['asset'] as $asset) {
                if (($asset['category'] === 'Parent Source Logo - dark' || $asset['category'] === 'Source Logo - dark')) {
                    if ($asset['ratio'] === '16:9') {
                        $uri_16_9 = "https://demo.tmsimg.com/" . $asset['URI'];
                        break; // Found the preferred ratio, no need to continue
                    } elseif ($asset['ratio'] === '4:3') {
                        $uri_4_3 = "https://demo.tmsimg.com/" . $asset['URI'];
                    }
                }
            }

            // Output the URI
            if ($uri_16_9) {
                $uri = $uri_16_9;
            } elseif ($uri_4_3) {
                $uri = $uri_4_3;
            }
        }
        return $uri;
    }

    public function allChannelList(Request $request)
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
            $user = JWTAuth::user();
            $urlType = $request->get('typeurl');
            $zipcode = $request->get('zipcode');
            $genre = $request->get('genre');
            $validate = 0;

            // Check DB for email-specific zipcode rules
            $zipcodeRule = UserZipcodeRule::where('email', $user->email)->first();
            if ($zipcodeRule) {
                $validate = $zipcodeRule->validate;
                $zipcode = $zipcodeRule->zipcode;
            } elseif (!in_array($user->email, $this->exculdedUsers) && $request->has('zipcode')) {
                $validate = 1;
            }

            $imagePath = url("/uploads/channellogo/");

            if ($urlType == 'manifest') {
                $type = 'smooth_url';
            } else if ($urlType == 'm3u8') {
                $type = 'url';
            } else if ($urlType == "mpd") {
                $type = 'dash_url';
            } else {
                $type = 'dash_url';
            }
            $query = Channel::select('channels.id', 'channels.image as db_image', 'channels.prgSvcId', "channels.$type as url", 'gracenote_sources.stationName', 'gracenote_sources.callSign', 'gracenote_sources.timeZone', 'gracenote_sources.url as channel_url', 'gracenote_sources.assets')
                ->selectRaw("CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(genre.name, 'Others')), ',', '\",\"'), '\"]') as genrename")
                ->selectRaw('CAST(IFNULL(genre.name,"Others") AS CHAR) AS genre_name')
                ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
                ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
                ->leftJoin('gracenote_sources', 'gracenote_sources.prgSvcId', '=', 'channels.prgSvcId');
            if ($validate == 1) {
                if (!empty($zipcode)) {
                    // $query->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"') OR JSON_CONTAINS(channels.zip_code, '\"All\"')");
                    $query->where(function ($q) use ($zipcode) {
                        $q->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"')")
                            ->orWhereRaw("JSON_CONTAINS(channels.zip_code, '\"All\"')");
                    });
                }
            } elseif ($validate == 2) {
                if (!empty($zipcode)) {
                    $query->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"')");
                }
            }
            if (!empty($genre) && $genre != 'Others') {
                $query->where('genre.name', '=', $genre);
            } else if ($genre == 'Others') {
                $query->whereNull('genre.name');
            }
            $query->where('channels.status', '=', '1');
            $query->groupBy('channels.id');
            $query->orderBy('channels.id', 'asc');
            $record = $query->get()->map(function ($channel) {
                $channel->favourite = FavouriteChannel::where('channels_id', $channel->id)->where('user_id', JWTAuth::user()->id)->exists();
                $channel->image = $this->getURIFromAsset($channel->assets);
                $channel->roku_image = $channel->image != null ? url('storage/channel_roku_images/resized_image_' . basename($channel->image)) : env('APP_URL') . '/uploads/channellogo/' . $channel->db_image;
                if ($channel->image == null) {
                    $channel->image = env('APP_URL') . '/uploads/channellogo/' . $channel->db_image ?? env('APP_URL') . '/uploads/No Image.png';
                }

                if (
                    trim($channel->stationName) === 'TV Guide' ||
                    trim($channel->callSign) === 'TVGN'
                ) {
                    $channel->stationName = 'FanDuel';
                    $channel->image = env('APP_URL') . '/uploads/fandue.png';
                    $channel->roku_image = env('APP_URL') . '/uploads/fandue.png';
                }

                if ($channel->roku_image == null) {
                    $channel->roku_image = env('APP_URL') . '/uploads/No Image.png';
                }
                unset($channel->db_image);
                unset($channel->assets);
                return $channel;
            });

            // $data = $record->groupBy('genre_name');
            // foreach ($data as $genre => $channels) {
            //     $genreArray = [
            //         "genre_name" => $genre,
            //         "channels" => $channels
            //     ];
            //     $result[] = $genreArray;
            // }
            $response = [
                'success' => true,
                'response_code' => 200,
                'message' => 'Channel List Retrieved successfully.',
                'data' => $record,

            ];
            return response()->json($response, 200);
        }
    }

    public function channelListBeforeLogin(Request $request)
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

            $query = Channel::select('channels.id', 'channels.image as db_image', 'gracenote_sources.stationName', 'gracenote_sources.assets')
                // ->selectRaw("CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(genre.name, 'Others')), ',', '\",\"'), '\"]') as genrename")
                ->selectRaw('CAST(IFNULL(genre.name,"Others") AS CHAR) AS genre_name')
                ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
                ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
                ->leftJoin('gracenote_sources', 'gracenote_sources.prgSvcId', '=', 'channels.prgSvcId');
            $query->where('channels.status', '=', '1');
            $query->orderBy('genre_name', 'asc');
            $record = $query->get()->map(function ($channel) {
                $channel->image = $this->getURIFromAsset($channel->assets) ?? env('APP_URL') . '/uploads/channellogo/' . $channel->db_image;
                unset($channel->db_image);
                unset($channel->assets);
                return $channel;
            });

            $data = $record->groupBy('genre_name');
            foreach ($data as $genre => $channels) {
                $genreArray = [
                    "genre_name" => $genre,
                    "channels" => $channels
                ];
                $result[] = $genreArray;
            }
            $response = [
                'success' => true,
                'response_code' => 200,
                'message' => 'Channel List Retrieved successfully.',
                'data' => $result,

            ];
            return response()->json($response, 200);
        }
    }
}
