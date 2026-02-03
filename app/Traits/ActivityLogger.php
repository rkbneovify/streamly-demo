<?php

namespace App\Traits;

use App\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

trait ActivityLogger
{
    protected ActivityLog $activity;

    public function activity($userId = null)
    {
        $this->activity = new ActivityLog();
        $this->activity->user_id = $userId ?? Auth::id();
        $this->activity->user_ip_address = request()->ip();

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
        if (Auth::check()) {
            $this->activity->description = $description . ' by - ' . Auth::user()->email . ' | ' . Auth::user()->roles[0]->name;
            return $this;
        }
        $this->activity->description = $description;
        return $this;
    }



    public function properties(array $properties)
    {
        $existingProperties = $this->activity->properties ?? [];

        $this->activity->properties = array_merge(
            $existingProperties,
            $properties
        );

        return $this;
    }

    public function oldData($properties)
    {
        $existingProperties = $this->activity->properties ?? [];

        $this->activity->properties = array_merge(
            $existingProperties,
            ["old_data" => $properties]
        );

        return $this;
    }

    public function newData($properties)
    {
        $existingProperties = $this->activity->properties ?? [];

        $this->activity->properties = array_merge(
            $existingProperties,
            ["new_data" => $properties]
        );

        return $this;
    }



    public function save()
    {
        $this->activity->save();
        return $this->activity;
    }
}
