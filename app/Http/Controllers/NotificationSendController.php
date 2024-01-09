<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Models\User;
use Auth;

class NotificationSendController extends Controller
{
    public function updateDeviceToken(Request $request)
    {
        Auth::user()->device_token =  $request->token;

        Auth::user()->save();

        return response()->json(['Token successfully stored.']);
    }

    public function sendNotification(Request $request)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $FcmToken = '';
            
        $serverKey = 'AAAAmqDjrzk:APA91bEHyc7jm5DnxX_6IID989zA-xA08b0Wu5LPhlVhI7Z9dTl3zWgsxNW4Pr2_xoPwI-weBpeCr0nAtR1-mcF9cbrI2nK4TvecP8CHt76tODkeeWRqGQt0koOB0dW135aKABCSptqu';
    
        $data = [
            "registration_ids" => array('enzY50uXwypEzjo3zZpQvO:APA91bEfg7GdOn10GSp8yW_AuU--C4Ly5LtWIs098gcvS5BufrwD_G1XDRE6CtiqK2huCAcUd0EcJXltvRnL7GT1bWAV1SHMungWAOLgVoFNuUZMZPb2-YlczC3S2IxjPRDAxJs8icf3'),
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,  
            ]
        ];
        $encodedData = json_encode($data);
    
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }        
        // Close connection
        curl_close($ch);
        // FCM response
        dd($result);
    }
}