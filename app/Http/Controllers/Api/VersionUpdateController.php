<?php

namespace App\Http\Controllers\Api;

use App\AndroidVersion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VersionUpdateController extends Controller
{
    public function checkUpdate(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'version' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'validation_error',
                'message' => $validator->errors()
            ], 400);
        } else {
            $currentVersion = $request->version;  // e.g., "1.2.5"
            $latestVersion = AndroidVersion::latest('created_at')->first()->version;
            $apkUrl = url('/storage/apks/latest.apk'); // Adjust the storage path

            // Compare versions
            if (version_compare($currentVersion, $latestVersion, '<')) {
                return response()->json([
                    'update_available' => true,
                    'download_url' => $apkUrl,
                    'version' => $latestVersion,
                ]);
            }

            return response()->json([
                'update_available' => false,
            ]);
        }
    }
}
