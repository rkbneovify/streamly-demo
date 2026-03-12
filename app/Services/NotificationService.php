<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class NotificationService
{
    protected $messaging;

    public function __construct()
    {
        $this->messaging = (new Factory)
            ->withServiceAccount(storage_path(env('FIREBASE_CREDENTIALS')))
            ->createMessaging();
    }

    public function send($token, $title, $body)
    {
        try {

            if (empty($token)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Device token is missing'
                ]);
            }

            $message = CloudMessage::withTarget('token', $token)
                ->withNotification(Notification::create($title, $body));

            $this->messaging->send($message);

            return response()->json([
                'status' => true,
                'message' => 'Notification sent successfully'
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function sendMulti($tokens, $title, $body)
    {
        try {

            if (empty($tokens)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Device tokens are missing'
                ]);
            }

            // remove empty + duplicate tokens
            $tokens = array_values(array_unique(array_filter($tokens)));

            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $body));

            $report = $this->messaging->sendMulticast($message, $tokens);

            return response()->json([
                'status' => true,
                'success' => $report->successes()->count(),
                'failed' => $report->failures()->count(),
                'message' => 'Notifications processed'
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}