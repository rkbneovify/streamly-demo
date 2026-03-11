<?php
namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class NotificationService
{


    public function send($token, $title, $body)
    {

        try {

            $firebase = (new Factory)->withServiceAccount(storage_path('app/firebase/streamly-tv-android-firebase-adminsdk-1hfhq-1ba968a112.json'));
            $messaging = $firebase->createMessaging();
            $happy = "eHveuW0sSYCmbV6o99gX5V:APA91bFTNBhJak_yXCfsmB8HA0zRR-HDcXXoom1xhrarf_NxfMJBxo9UkngI7oSdcWCkV8oBe8U40og3p_aZJ_0MfLyRa5GRv2q1TaBvos0-vdFHqBTxlgo";
            $message = CloudMessage::withTarget('token', $happy)
                ->withNotification(
                    Notification::create($title, $body)
                );

            $messaging->send($message);
            return response()->json([
                "message" => "Notification send successfully !"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                // "message" => "Something went wrong !"
                "message" => $th->getMessage()
            ]);
        }



    }


}


