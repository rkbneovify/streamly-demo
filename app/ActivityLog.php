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


    public function scopeFilterEvent($query, $event)
    {
        if ($event == 'All') {
            return $query;
        } elseif ($event != null) {
            return  $query->where('event', $event);
        }
    }

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
        ActivityLog::whereNull('category')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);
    }

    public static function resolveNotificationForAll($userId)
    {
        ActivityLog::where('user_id', $userId)
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
