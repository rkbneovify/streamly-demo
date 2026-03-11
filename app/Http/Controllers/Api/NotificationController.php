<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Services\NotificationService;

class NotificationController extends Controller
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
        return $this->notification->send($rahul, "Testing Android", "This is background notification.");
    }
}
