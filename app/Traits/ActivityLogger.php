<?php

namespace App\Traits;

use App\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

trait ActivityLogger
{
    protected ActivityLog $activity;

    public function activity()
    {
        $this->activity = new ActivityLog();
        $this->activity->user_id = Auth::id();

        return $this;
    }

    public function effectedUser(int $userId)
    {
        $this->activity->effected_user_id = $userId;
        return $this;
    }

    public function event(string $event)
    {
        $this->activity->event = $event;
        return $this;
    }

    public function eventType(string $eventType)
    {
        $this->activity->event_type = $eventType;
        return $this;
    }



    public function description(string $description)
    {
        $this->activity->description = $description . ' by - ' . Auth::user()->roles[0]->name . ' | ' . Auth::user()->email;
        return $this;
    }



    public function properties(array $properties)
    {
        $this->activity->properties = $properties;
        return $this;
    }



    public function save()
    {
        $this->activity->save();
        return $this->activity;
    }
}
