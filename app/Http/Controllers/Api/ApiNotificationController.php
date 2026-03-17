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
        $rahul = "dcWCq8y4iEesqdAhoI1G9D:APA91bEyOWn_OT2b0GHU2MRrp-r_daKUqOUR_mpg4J3p1i8hDSPxVafbNemZgpBT9viIl-Au3u_f5i-s9WxiVwyz5pvrz6ajyYR8p4YZM1-VrG_ZinHOL9c";
        $happy = "eHveuW0sSYCmbV6o99gX5V:APA91bFTNBhJak_yXCfsmB8HA0zRR-HDcXXoom1xhrarf_NxfMJBxo9UkngI7oSdcWCkV8oBe8U40og3p_aZJ_0MfLyRa5GRv2q1TaBvos0-vdFHqBTxlgo";

        $staging = "fz7jygh1AUa9rmNy8_nHEL:APA91bHuwCVSUxq60sKzeOqYYU8dqjBqrpXIyuOQQy72RQZaUJnF-xEPxocj6rCugNy24m1XeUj0RkXL5I19txIUlm_w-LjBdLIBb-eEHI1jT2w0_ze_4gI";
        return $this->notification->send($staging, "Testing Android", "This is background notification.");
    }

    public function index(Request $request)
    {


        $query = ActivityLog::query();
        $query->where('user_id', auth()->user()->id)
            ->where('category', 'notification')
            ->where('is_user', 1);
        $hoaSubUser = HoaUser::getCurrentSubUserByToken();
        if ($hoaSubUser != null) {
            $query->where('passcode', $hoaSubUser->passcode);
        }

        $limit = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $query->latest();
        $data = $query->paginate($limit, ['*'], 'page', $page);
        // ActivityLog::resolveNotificationForHoa(auth()->user()->id);
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
        if ($hoaSubUser != null) {
            $query->where('passcode', $hoaSubUser->passcode);
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
