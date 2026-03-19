<?php

namespace App\Http\Controllers\Api;

use App\ActivityLog;
use App\HoaUser;
use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ApiNotificationController extends Controller
{
    protected $notification;
    public function __construct(NotificationService $notification)
    {
        $this->notification = $notification;
    }
    public function send()
    {
        $rahul = "fQFBJGrqaU6bt2x-lJVkDp:APA91bFcw3p9puN2Xz4wFFy7LQ6O0L0aInrHwnzRPaEN2YI3Q_s7nnQTmAiZJFVz9LeOJZKwLKf6jkyup5pqFDvcfgrtAIeEozxRzC_ferxl_1hVxKrn-B0";
        $happy = "eHveuW0sSYCmbV6o99gX5V:APA91bFTNBhJak_yXCfsmB8HA0zRR-HDcXXoom1xhrarf_NxfMJBxo9UkngI7oSdcWCkV8oBe8U40og3p_aZJ_0MfLyRa5GRv2q1TaBvos0-vdFHqBTxlgo";


        return $this->notification->send($rahul, "Testing Android", "This is background notification.");
    }

    public function index(Request $request)
    {


        $query = ActivityLog::query()
            ->where('user_id', auth()->id())
            ->where('category', 'notification')
            ->where('is_user', 1);

        $hoaSubUser = HoaUser::getCurrentSubUserByToken();

        if ($hoaSubUser != null) {
            ActivityLog::resolveNotificationForHoaSub($hoaSubUser->passcode);
            $query->whereNotNull('passcode')
                ->where('passcode', $hoaSubUser->passcode);
        } else {

            ActivityLog::resolveNotificationForAll(auth()->user()->id);
            $query->whereNull('passcode');
        }

        $limit = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $data = $query->latest()->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'response_code' => 200,
            'message' => 'Data fetched successfully !',
            'data' => $data->items(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'per_page' => $data->perPage(),
            'total' => $data->total(),
            'is_more_data' => $page >= $data->lastPage() ? false : true,
        ], 200);
    }
    public function unreadCount()
    {

        $query = ActivityLog::query();
        $query->where('user_id', auth()->user()->id)
            ->where('category', 'notification')
            ->where('is_read', 0)
            ->where('is_user', 1);
        $hoaSubUser = HoaUser::getCurrentSubUserByToken();
        if ($hoaSubUser !== null) {
            $query->whereNotNull('passcode')
                ->where('passcode', $hoaSubUser->passcode);
        } else {
            $query->whereNull('passcode');
        }
        $count = $query->count();

        return response()->json([
            'success' => true,
            'response_code' => 200,
            'message' => 'Data fetched successfully !',
            'count' => $count,

        ], 200);
    }

}
