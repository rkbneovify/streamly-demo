<?php

namespace App\Http\Controllers\Api;

use App\GracenoteScheduleProgram;
use App\GracenoteSource;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\FavouriteChannel;
use App\Models\Genre;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NewChannelApiController extends Controller
{
    private $exculdedUsers = ["jmheil@gmail.com", "llipschultz@neovix.com", "brian_marlin@yahoo.com", "qasuresh@yopmail.com", "qajalaj@yopmail.com", "pm13426@gmail.com", "nathan@neovix.com", "willis@neovix.com", "davea@neovix.com"];

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
            $query = Channel::select('channels.id', 'channels.prgSvcId', "channels.$type as url", 'gracenote_sources.stationName', 'gracenote_sources.callSign', 'gracenote_sources.timeZone', 'gracenote_sources.url as channel_url', 'gracenote_sources.assets', 'channels.zip_code')
                ->rightJoin('recent_channel', 'channels.id', '=', 'recent_channel.channels_id')
                ->leftJoin('gracenote_sources', 'gracenote_sources.prgSvcId', '=', 'channels.prgSvcId');

            $query->where('channels.status', '=', '1');
            $query->where('recent_channel.user_id', '=', JWTAuth::user()->id);
            $query->orderBy('stationName', 'asc');
            $record = $query->get()->map(function ($channel) {
                $channel->favourite = FavouriteChannel::where('channels_id', $channel->id)->where('user_id', JWTAuth::user()->id)->exists();
                $channel->image = $this->getURIFromAsset($channel->assets);
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
            $query = Channel::select('channels.id', 'channels.prgSvcId', "channels.$type as url", 'gracenote_sources.stationName', 'gracenote_sources.callSign', 'gracenote_sources.timeZone', 'gracenote_sources.url as channel_url', 'gracenote_sources.assets')
                ->rightJoin('favourite_channel', 'channels.id', '=', 'favourite_channel.channels_id')
                ->leftJoin('gracenote_sources', 'gracenote_sources.prgSvcId', '=', 'channels.prgSvcId');

            $query->where('channels.status', '=', '1');
            $query->where('favourite_channel.user_id', '=', JWTAuth::user()->id);
            $query->orderBy('stationName', 'asc');
            $record = $query->get()->map(function ($channel) {
                $channel->favourite = FavouriteChannel::where('channels_id', $channel->id)->where('user_id', JWTAuth::user()->id)->exists();
                $channel->image = $this->getURIFromAsset($channel->assets);
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
            if (!in_array($user->email, $this->exculdedUsers) && $request->has('zipcode')) {
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
            $query = Channel::select('channels.id', 'channels.prgSvcId', "channels.$type as url", 'gracenote_sources.stationName', 'gracenote_sources.callSign', 'gracenote_sources.timeZone', 'gracenote_sources.url as channel_url', 'gracenote_sources.assets')
                ->selectRaw("CONCAT('[\"', REPLACE(GROUP_CONCAT(IFNULL(genre.name, 'Others')), ',', '\",\"'), '\"]') as genrename")
                ->selectRaw('CAST(IFNULL(genre.name,"Others") AS CHAR) AS genre_name')
                ->leftJoin('channels_genre', 'channels.id', '=', 'channels_genre.channels_id')
                ->leftJoin('genre', 'channels_genre.genre_id', '=', 'genre.id')
                ->leftJoin('gracenote_sources', 'gracenote_sources.prgSvcId', '=', 'channels.prgSvcId');
            if ($validate) {
                if (!empty($zipcode)) {
                    $query->whereRaw("JSON_CONTAINS(channels.zip_code, '\"$zipcode\"') OR JSON_CONTAINS(channels.zip_code, '\"All\"')");
                }
            }
            $query->where('channels.status', '=', '1');
            if (!empty($genre) && $genre != 'Others') {
                $query->where('genre.name', '=', $genre);
            } else if ($genre == 'Others') {
                $query->whereNull('genre.name');
            }
            $query->groupBy('channels.id');
            $query->orderBy('channels.id', 'asc');
            $record = $query->get()->map(function ($channel) {
                $channel->favourite = FavouriteChannel::where('channels_id', $channel->id)->where('user_id', JWTAuth::user()->id)->exists();
                $channel->image = $this->getURIFromAsset($channel->assets);
                $channel->program = $this->getProgramScheduleById($channel->prgSvcId);
                unset($channel->assets);
                return $channel;
            });

            $response = [
                'success' => true,
                'response_code' => 200,
                'message' => 'Epg Guide Retrieved successfully.',
                'data' => $record,

            ];
            return response()->json($response, 200);
        }
    }

    private function getProgramScheduleById($prgSvcId)
    {
        $programs = GracenoteScheduleProgram::where('prgSvcId', $prgSvcId)->select('title', 'description', 'TMSId', 'quals', 'start', 'end', 'assets')->get();
        $programs->map(function ($program) {
            $program->image = $this->getURIFromAssetForProgram($program->assets);
            unset($program->assets);
        });

        return $programs;
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
    public function searchChannelProgram(Request $request)
    {
        $search = $request->input('search');

        // Fetch programs matching the search query
        $programs = GracenoteScheduleProgram::select('prgSvcId', 'title as name', 'description', 'assets')
            ->where('title', 'like', "%{$search}%")
            ->get();

        // Process each program to add image URI and type, and remove assets
        $programs->map(function ($program) {
            $program->image = $this->getURIFromAssetForProgram($program->assets);
            $program->type = 'event';
            $program->callSign = null;
            unset($program->assets);
        });

        // Log the programs fetched for debugging
        // Log::debug('Programs:', $programs->toArray());

        // Fetch channels matching the search query
        $channels = GracenoteSource::select('prgSvcId', 'stationName as name', 'callSign', 'assets')
            ->where('stationName', 'like', "%{$search}%")
            ->get();

        // Process each channel to add image URI and type, and remove assets
        $channels->map(function ($channel) {
            $channel->image = $this->getURIFromAsset(json_encode($channel->assets));
            $channel->type = 'channel';
            $channel->description = null;
            unset($channel->assets);
        });

        // Log the channels fetched for debugging
        // Log::debug('Channels:', $channels->toArray());

        // Merge programs and channels into a single collection
        // $data = $channels->merge($programs);

        // Log the merged data for debugging
        // Log::debug('Merged Data:', $data->toArray());
        $programs = ($programs->unique('name')->toArray());
        $data = collect(array_merge($channels->toArray(), $programs));

        return response()->json($data);
    }

    public function allGenre()
    {
        $genres = Genre::select('name', 'image')->orderBy('position')->get();
        return response()->json($genres);
    }
}
