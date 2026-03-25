<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $casts = [
        'properties' => 'array',
        'is_user' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function effectedUser()
    {
        return $this->belongsTo(User::class, 'effected_user_id', 'id');
    }

    public static function resolveNotification()
    {
        $latest = ActivityLog::where('category', 'activity')->where('is_read', 0)
            ->orderByDesc('id') // or created_at
            ->first();

        if ($latest) {
            ActivityLog::where('category', 'activity')
                ->where('is_read', 0)
                ->where('id', '!=', $latest->id)
                ->update(['is_read' => 1]);
        }
    }

    public static function resolveNotificationForAll($userId)
    {
        ActivityLog::where('user_id', $userId)
            ->whereNull('passcode')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);
    }

    public static function resolveNotificationForHoaSub($passcode)
    {
        ActivityLog::where('passcode', $passcode)
            ->whereNotNull('passcode')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);
    }

}
