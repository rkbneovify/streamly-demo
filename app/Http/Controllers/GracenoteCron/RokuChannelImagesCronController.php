<?php

namespace App\Http\Controllers\GracenoteCron;

use App\GracenoteSource;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class RokuChannelImagesCronController extends Controller
{
    private function getURIFromAsset($data)
    {
        $uri = NULL;
        // Initialize URI variables
        $uri_16_9 = null;
        $uri_4_3 = null;
        // Decode JSON to associative array
        // $data = json_decode($assets, true);
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

    public function RokuChannelImagesCron()
    {
        // $query = Channel::select('gracenote_sources.assets')
        //     ->leftJoin('gracenote_sources', 'gracenote_sources.prgSvcId', '=', 'channels.prgSvcId');


        $folderPath = storage_path('app/public/channel_roku_images/');
        $this->clearFolder($folderPath);
        $query = GracenoteSource::select('assets');

        // return $query->get();
        $record = $query->get()->map(function ($channel) {
            // return $channel->assets;
            $channel->image = $this->getURIFromAsset($channel->assets);
            $channel->roku_image = $channel->image != null ? $this->resizeAndStoreImage($channel->image) : null;
            unset($channel->assets);
            return $channel;
        });

        $response = [
            'success' => true,
            'response_code' => 200,
            'message' => 'Channel Images optimised for Roku successfully.',
            'data' => $record,

        ];
        return response()->json($response, 200);
    }

    public function RokuChannelImagesCronNew()
    {
        // $query = Channel::select('gracenote_sources.assets')
        //     ->leftJoin('gracenote_sources', 'gracenote_sources.prgSvcId', '=', 'channels.prgSvcId');


        try {
            $folderPath = storage_path('app/public/channel_roku_images/');
            $this->clearFolder($folderPath);
            $query = GracenoteSource::select('assets');

            // return $query->get();
            $record = $query->get()->map(function ($channel) {
                // return $channel->assets;
                $channel->image = $this->getURIFromAsset($channel->assets);
                $channel->roku_image = $channel->image != null ? $this->resizeAndStoreImage($channel->image) : null;
                unset($channel->assets);
                return $channel;
            });

            $response = [
                'success' => true,
                'response_code' => 200,
                'message' => 'Channel Images optimised for Roku successfully.',
                'data' => $record,

            ];
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            $response['exception'] = class_basename($th);
            $response['file'] = $th->getFile();
            $response['line'] = $th->getLine();
            return response()->json($response, 500);
        }
    }

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
    }
}
