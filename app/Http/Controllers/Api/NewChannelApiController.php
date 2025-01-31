<?php

namespace App\Http\Controllers\Api;

use App\GracenoteScheduleProgram;
use App\GracenoteSource;
use App\Http\Controllers\Controller;
use App\Http\Requests\BugReporting\Store;
use App\Models\Channel;
use App\Models\FavouriteChannel;
use App\Models\Genre;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class NewChannelApiController extends Controller
{
    private $exculdedUsers = ["jmheil@gmail.com", "llipschultz@neovix.com", "brian_marlin@yahoo.com", "qajalaj@yopmail.com", "pm13426@gmail.com", "nathan@neovix.com", "willis@neovix.com", "davea@neovix.com", "willthiswork2023@outlook.com"];

    private function getURIFromAsset($assets)
    {
        $uri = NULL;
        // Initialize URI variables
        $uri_16_9 = null;
        $uri_4_3 = null;
        // Decode JSON to associative array
        $data = json_decode($assets, true);
        if (!empty($data)) {

            // Iterate through the assets
            foreach ($data['asset'] as $asset) {
                if (($asset['category'] === 'Parent Source Logo - dark' || $asset['category'] === 'Source Logo - dark' || $asset['category'] === 'Iconic')) {
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
            $query = Channel::select('channels.id',  'channels.image as db_image', 'channels.prgSvcId', "channels.$type as url", 'gracenote_sources.stationName', 'gracenote_sources.callSign', 'gracenote_sources.timeZone', 'gracenote_sources.url as channel_url', 'gracenote_sources.assets', 'channels.zip_code')
                ->rightJoin('recent_channel', 'channels.id', '=', 'recent_channel.channels_id')
                ->leftJoin('gracenote_sources', 'gracenote_sources.prgSvcId', '=', 'channels.prgSvcId');

            $query->where('channels.status', '=', '1');
            $query->where('recent_channel.user_id', '=', JWTAuth::user()->id);
            $query->orderBy('stationName', 'asc');
            $record = $query->distinct()->get()->map(function ($channel) {
                $channel->favourite = FavouriteChannel::where('channels_id', $channel->id)->where('user_id', JWTAuth::user()->id)->exists();
                $channel->image = $this->getURIFromAsset($channel->assets);
                $channel->roku_image = $channel->image != null ? url('storage/channel_roku_images/resized_image_' . basename($channel->image)) : env('APP_URL') . '/uploads/channellogo/' . $channel->db_image;
                if ($channel->image == null) {
                    $channel->image = env('APP_URL') . '/uploads/channellogo/' . $channel->db_image ?? env('APP_URL') . '/uploads/No Image.png';
                }
                if ($channel->roku_image == null) {
                    $channel->roku_image = env('APP_URL') . '/uploads/No Image.png';
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
            $query = Channel::select('channels.id', 'channels.image as db_image', 'channels.prgSvcId', "channels.$type as url", 'gracenote_sources.stationName', 'gracenote_sources.callSign', 'gracenote_sources.timeZone', 'gracenote_sources.url as channel_url', 'gracenote_sources.assets')
                ->rightJoin('favourite_channel', 'channels.id', '=', 'favourite_channel.channels_id')
                ->leftJoin('gracenote_sources', 'gracenote_sources.prgSvcId', '=', 'channels.prgSvcId');

            $query->where('channels.status', '=', '1');
            $query->where('favourite_channel.user_id', '=', JWTAuth::user()->id);
            $query->orderBy('stationName', 'asc');
            $record = $query->get()->map(function ($channel) {
                $channel->favourite = FavouriteChannel::where('channels_id', $channel->id)->where('user_id', JWTAuth::user()->id)->exists();
                $channel->image = $this->getURIFromAsset($channel->assets) ?? env('APP_URL') . '/uploads/channellogo/' . $channel->db_image;
                if ($channel->image == null) {
                    $channel->image = env('APP_URL') . '/uploads/channellogo/' . $channel->db_image ?? env('APP_URL') . '/uploads/No Image.png';
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
        $validator = Validator::make($request->all(), [
            'typeurl' => 'required',
            'day' => 'nullable',
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
            $hoursoffset = $request->get('hoursoffset');
            $pattern = '/^(-?\d{2}:\d{2})$/';

            if (preg_match($pattern, $hoursoffset, $matches)) {
                // String matches the format, and $matches[1] contains the matched value
                $hoursofset = $matches[1];
            } else {
                // String does not match the format
                $hoursofset = '00:00';
            }
            $day = $request->day;
            $validate = 0;
            if (!in_array($user->email, $this->exculdedUsers) && $request->has('zipcode')) {
                $validate = 1;
            }
            if ($user->email == "streamlymtairy@gmail.com") {
                $validate = 2;
                $zipcode = "18344";
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
            $query->where('channels.status', '=', '1');
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
                $channel->favourite = FavouriteChannel::where('channels_id', $channel->id)->where('user_id', JWTAuth::user()->id)->exists();
                $channel->image = $this->getURIFromAsset($channel->assets) ?? env('APP_URL') . '/uploads/channellogo/' . $channel->db_image;
                $channel->program = $this->getProgramScheduleById($channel->prgSvcId, $day);
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
                unset($channel->db_image);
                return $channel;
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

    private function getProgramScheduleById($prgSvcId, $day)
    {
        $gmdate_string = gmdate('Y-m-d H:i');
        $current_timestamp = strtotime($gmdate_string . ' GMT');
        $query = GracenoteScheduleProgram::where('prgSvcId', $prgSvcId)->select('title', 'description', 'TMSId', 'quals', 'start', 'end', 'assets');
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
            unset($program->assets);
        });

        return $programs;
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
            if (!in_array($user->email, $this->exculdedUsers) && $request->has('zipcode')) {
                $validate = 1;
            }
            if ($user->email == "streamlymtairy@gmail.com") {
                $validate = 2;
                $zipcode = "18344";
            }
            // Fetch programs matching the search query
            $pquery = GracenoteScheduleProgram::select('channels.id', 'gracenote_schedule_program.prgSvcId', 'title as name', 'description', 'assets', 'TMSId')
                ->join('channels', 'gracenote_schedule_program.prgSvcId', '=', 'channels.prgSvcId')
                ->where('title', 'like', "%{$search}%");
            if ($validate == 1) {
                if (!empty($zipcode)) {
                    $pquery->where(function ($q) use ($zipcode) {
                        $q->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"')")
                            ->orWhereRaw("JSON_CONTAINS(channels.zip_code, '\"All\"')");
                    });
                }
            } elseif ($validate == 2) {
                if (!empty($zipcode)) {
                    $pquery->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"')");
                }
            }
            $pquery->distinct('gracenote_schedule_program.TMSId');
            $programs = $pquery->get();
            // Process each program to add image URI and type, and remove assets
            $programs->map(function ($program) {
                $program->image = $this->getURIFromAssetForProgram($program->assets);
                $program->roku_image = $program->image;
                $program->type = 'event';
                $program->callSign = null;
                $program->url = null;
                unset($program->assets);
            });

            // Fetch channels matching the search query
            $query = GracenoteSource::select('channels.id', 'channels.image as db_image', 'gracenote_sources.prgSvcId', 'gracenote_sources.stationName as name', 'gracenote_sources.callSign', 'assets',  "channels.$type as url",)
                ->join('channels', 'gracenote_sources.prgSvcId', '=', 'channels.prgSvcId')
                ->where('gracenote_sources.stationName', 'like', "%{$search}%");
            if ($validate == 1) {
                if (!empty($zipcode)) {
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
            $channels = $query->get();

            // Process each channel to add image URI and type, and remove assets
            $channels->map(function ($channel) {
                $channel->image = $this->getURIFromAsset(json_encode($channel->assets));
                $channel->roku_image = $channel->image != null ? url('storage/channel_roku_images/resized_image_' . basename($channel->image)) : env('APP_URL') . '/uploads/channellogo/' . $channel->db_image;
                if ($channel->image == null) {
                    $channel->image = env('APP_URL') . '/uploads/channellogo/' . $channel->db_image ?? env('APP_URL') . '/uploads/No Image.png';
                }
                if ($channel->roku_image == null) {
                    $channel->roku_image = env('APP_URL') . '/uploads/No Image.png';
                }
                unset($channel->assets);
                $channel->type = 'channel';
                $channel->description = null;
                $channel->TMSId = null;
                unset($channel->assets);
            });

            // $programs = ($programs->unique('name')->toArray());
            $data = collect(array_merge($channels->toArray(), $programs->toArray()));

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
        $genres = Genre::select('name', 'image')->orderBy('position')->get();

        $genres->map(function ($genre) {
            $genre->image = env('APP_URL') . $genre->image;
        });
        $response = [
            'success' => true,
            'response_code' => 200,
            'message' => 'All genres Retrieved successfully.',
            'data' => $genres
        ];
        return response()->json($response);
    }

    public function programDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TMSId' => 'required',
            'channel_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'validation_error',
                'message' => $validator->errors()
            ], 400);
        } else {
            $TMSId = $request->TMSId;
            $program = GracenoteScheduleProgram::where('TMSId', $TMSId)->select('channels.id as channel_id', 'gracenote_schedule_program.prgSvcId', 'gracenote_sources.stationName', 'channels.url', 'dash_url', 'smooth_url', 'title', 'description', 'duration', 'quals', 'gracenote_schedule_program.assets')
                ->selectRaw("CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(genre.name, 'Others')), ',', '\",\"'), '\"]') as genre")
                ->leftJoin('channels', 'gracenote_schedule_program.prgSvcId', '=', 'channels.prgSvcId')
                ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
                ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
                ->leftJoin('gracenote_sources', 'gracenote_schedule_program.prgSvcId', '=', 'gracenote_sources.prgSvcId')
                ->where('channels.id', $request->channel_id)
                ->groupBy('gracenote_schedule_program.id')
                ->first();

            if (!empty($program)) {
                $program_details = GracenoteScheduleProgram::select('date', 'time', 'duration', 'start')
                    ->where('title', 'like', "%{$program->title}%")
                    ->where('gracenote_schedule_program.prgSvcId', $program->prgSvcId)
                    ->orderBy('start')
                    ->get();
                $data = [
                    'channel_id' => $program->channel_id,
                    'stationName' => $program->stationName,
                    'url' => $program->url,
                    'dash_url' => $program->dash_url,
                    'smooth_url' => $program->smooth_url,
                    "title" => $program->title,
                    "description" => $program->description,
                    "duration" => $program->duration,
                    "quals" => $program->quals,
                    "genre" =>  json_decode($program->genre),
                    "image" => $this->getURIFromAssetForProgram($program->assets),
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
            if (!in_array($user->email, $this->exculdedUsers) && $request->has('zipcode')) {
                $validate = 1;
            }
            if ($user->email == "streamlymtairy@gmail.com") {
                $validate = 2;
                $zipcode = "18344";
            }
            $query = Channel::where('channels.id', $channel_id)->select('channels.id', 'channels.image as db_image', 'channels.prgSvcId', 'channels.url', 'dash_url', 'smooth_url', 'gracenote_sources.stationName', 'gracenote_sources.callSign', 'gracenote_sources.assets')
                ->selectRaw("CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(genre.name, 'Others')), ',', '\",\"'), '\"]') as genrename")
                ->selectRaw("CONCAT('[\"', REPLACE(GROUP_CONCAT(genre.id), ',', '\",\"'), '\"]') as genre_ids")
                ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
                ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
                ->leftJoin('gracenote_sources', 'gracenote_sources.prgSvcId', '=', 'channels.prgSvcId');
            $query->groupBy('channels.id');
            $record = $query->get();

            $genre_ids = json_decode($record[0]->genre_ids);
            $query1 = Channel::select('channels.id', 'channels.image as db_image', 'channels.prgSvcId', 'channels.url', 'dash_url', 'smooth_url', 'gracenote_sources.stationName', 'gracenote_sources.callSign', 'gracenote_sources.assets')
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
            $query1->where('channels.status', '=', '1');
            $query1->where('channels.id', '!=', $channel_id);
            if (!empty($genre_ids)) {
                $query1->whereIn('channels_genre.genre_id', $genre_ids);
            } else {
                $query1->whereNull('channels_genre.genre_id');
            }
            $query1->groupBy('channels.id');
            $recommended_channels = $query1->get()->unique()->map(function ($channel) {
                $channel->image = $this->getURIFromAsset($channel->assets) ?? env('APP_URL') . '/uploads/channellogo/' . $channel->db_image;
                unset($channel->db_image);
                unset($channel->assets);
                return $channel;
            });
            $record->map(function ($channel) {
                $channel->image = $this->getURIFromAsset($channel->assets) ?? env('APP_URL') . '/uploads/channellogo/' . $channel->db_image;
                $channel->genre_ids = json_decode($channel->genre_ids);
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
                $data = [
                    "title" => $program->title,
                    "description" => $program->description,
                    "duration" => $program->duration,
                    "quals" => $program->quals,
                    "genre" =>  json_decode($program->genre),
                    "image" => $this->getURIFromAssetForProgram($program->assets),
                    "up_next" => $up_next
                ];
            } else {
                $data = NULL;
            }
            $PrevNext = $channel_id;
            $all = DB::table('channels')->where('status', '=', 1)->orderBy('id', 'desc')->select('id')->first();
            $nextChannel = $PrevNext;
            if ($PrevNext == 1) {
                $PrevNext = 2;
                $nextChannel = 1;
            }
            if ($PrevNext == $all->id) {
                $nextChannel = $all->id - 1;
            }

            $prev = DB::table('channels')->where('id', '<', $PrevNext)->where('status', '1')->max('id');
            $prevChannel = DB::table('channels')->where('id', $prev)->where('status', '1')->select('id', 'name',  'callSign', 'channels.url', 'dash_url', 'smooth_url')->first();
            $next = DB::table('channels')->where('id', '>', $nextChannel)->where('status', '1')->min('id');
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
                $data = [
                    "title" => $program->title,
                    "description" => $program->description,
                    "duration" => $program->duration,
                    "quals" => $program->quals,
                    "genre" =>  json_decode($program->genre),
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
