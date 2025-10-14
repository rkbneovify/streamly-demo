<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\GracenoteScheduleProgram;
use App\GracenoteSource;
use App\Http\Requests\BugReporting\Store;
use App\Models\Channel;
use App\Models\FavouriteChannel;
use App\Models\Genre;
use App\Models\UserZipcodeRule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class RokuEpgGuideApiController extends Controller
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
            // Define the storage path for the folder
            // $folderPath = storage_path('app/public/channel_roku_images/');
            // Clear the folder before storing the new image
            // $this->clearFolder($folderPath);

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
                $channel->image = $this->getURIFromAsset($channel->assets);
                $channel->roku_image = $channel->image != null ? url('storage/channel_roku_images/resized_image_' . basename($channel->image)) : (env('APP_URL') . '/uploads/channellogo/' . $channel->db_image ?? null);
                $channel->program = $this->getProgramScheduleById($channel->prgSvcId, $day);
                // $channel->program = collect($this->getProgramScheduleById($channel->prgSvcId, $day))
                $channel->program = collect($this->getProgramScheduleById($channel->prgSvcId, $day))
                    ->map(function ($program) {
                        if (isset($program['title']) && strcasecmp(trim($program['title']), 'TV Guide') === 0) {
                            $program['title'] = 'FanDuel';
                            $program['image'] = env('APP_URL') . '/uploads/fandue.png';
                        }
                        return $program;
                    })
                    ->toArray(); // Convert collection back to plain array for JSON



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
                    $channel->image = env('APP_URL') . '/uploads/channellogo/' . $channel->db_image ?? env('APP_URL') . '/uploads/No Image.png';
                }


                if ($channel->roku_image == null) {
                    $channel->roku_image = env('APP_URL') . '/uploads/No Image.png';
                }
                if (
                    trim($channel->stationName) === 'TV Guide' ||
                    trim($channel->callSign) === 'TVGN'
                ) {
                    $channel->stationName = 'FanDuel';
                    $channel->roku_image = env('APP_URL') . '/uploads/fandue.png';
                    $channel->image = env('APP_URL') . '/uploads/fandue.png';
                }

                if ($channel->id == 19) {
                    $channel->stationName = 'Hallmark Family';
                    $channel->image = env('APP_URL') . '/uploads/hall-fam.png';
                    $channel->roku_image = env('APP_URL') . '/uploads/hallm-fam.png';

                }

                unset($channel->db_image);
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
        if (!empty($date)) {
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
    /*
    private function resizeAndStoreImage($imageUrl)
    {
        // The URL of the image you want to resize
        // $imageUrl = 'https://demo.tmsimg.com/assets/s57391_ld_h8_ae.png';

        // Desired width and height
        $targetWidth = 100;
        $targetHeight = 70;

        // Define the storage path for the folder
        $folderPath = storage_path('app/public/channel_roku_images/');

        // Load the image from the URL
        $image = imagecreatefrompng($imageUrl);

        if (!$image) {
            return 'Unable to load image';
        }

        // Create a blank image with the desired dimensions
        $resizedImage = imagecreatetruecolor($targetWidth, $targetHeight);

        // Preserve transparency
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage, true);

        // Get original image dimensions
        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);

        // Resize the original image into the new one
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $originalWidth, $originalHeight);

        // // Define the file name for the new image
        // $filename = 'resized_image_' . time() . '.png';

        // Extract the original filename
        $originalFilename = basename($imageUrl);

        // Define the new file name with the prefix
        $newFilename = 'resized_image_' . $originalFilename;
        // Save the resized image in the folder
        $filePath = $folderPath . $newFilename;
        imagepng($resizedImage, $filePath);

        // Clean up the memory
        imagedestroy($image);
        imagedestroy($resizedImage);

        // Return the image URL as an API response
        $imageUrl = url('storage/channel_roku_images/' . $newFilename);
        return $imageUrl;
        // return response()->json(['image_url' => $imageUrl], 200);
    }

    // Helper function to clear the folder
    private function clearFolder($folderPath)
    {
        // Check if the folder exists
        if (File::exists($folderPath)) {
            // Get all files in the folder and delete them
            $files = File::files($folderPath);
            foreach ($files as $file) {
                File::delete($file);
            }
        } else {
            // If folder doesn't exist, create it
            File::makeDirectory($folderPath, 0755, true);
        }
    } */
}
