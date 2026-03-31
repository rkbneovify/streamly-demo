<?php

namespace App\Traits;

use App\ActivityLog;
use App\Models\PersonalDevice;
use App\User;
use Illuminate\Support\Facades\Auth;


trait ActivityLogger
{
    protected ActivityLog $activity;

    public function activity($userId = null)
    {
        $this->activity = new ActivityLog();
        $this->activity->user_id = $userId ?? Auth::id();
        $this->activity->user_ip_address = request()->ip();
        $this->activity->is_user = 1;


        return $this;
    }

    public function effectedUser(int $userId)
    {
        $this->activity->effected_user_id = $userId;
        return $this;
    }
    public function notification()
    {
        $this->activity->category = "notification";
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
            $this->activity->description = $description . ' - ' . Auth::user()->email . ' | ' . Auth::user()->roles[0]->name . ' | ' . Auth::user()->mode;
            return $this;
        } elseif ($this->activity->user_id != null) {
            $user = User::where('id', $this->activity->user_id)->first();
            $this->activity->description = $description . ' - ' . $user->email . ' | ' . $user->roles[0]->name;
            return $this;
        }
        $this->activity->description = $description;
        return $this;
    }

    public function descriptionSimple(string $description)
    {

        $this->activity->description = $description;
        return $this;
    }

    public function descriptionDeviceRemove($currentSession = null, $session = null)
    {
        if (!$currentSession || !$session) {
            return $this;
        }

        $removedBy = PersonalDevice::where('device_id', $currentSession->device_id)->latest()->first();
        $device = PersonalDevice::where('device_id', $session->device_id)->latest()->first();

        $description = "Hoa sub user device removed <br>";

        ;
        // Who removed
        if ($removedBy) {
            $description .= "Device removed by: " . explode('#~', $currentSession->mac_address)[0] . "<br>";

            if ($currentSession->device_type == 'non-web') {
                $description .= "Device Name: {$removedBy->personal_devicename}<br>";
            }
        }

        // Removed device details
        if ($device) {
            $description .= "Removed Device Details:<br>";
            $description .= "Device: " . explode('#~', $session->mac_address)[0] . "<br>";
            if ($session->device_type == 'non-web') {
                $description .= "Device Name: {$device->personal_devicename}<br>";
            }
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

    public function passcode($passcode)
    {
        $this->activity->passcode = $passcode;
        return $this;
    }
    public function isUser()
    {
        $this->activity->is_user = 1;
        return $this;
    }


    public function isHoa()
    {
        $this->activity->is_hoa = 1;
        return $this;
    }
    public function isAdmin()
    {
        $this->activity->is_admin = 1;
        return $this;
    }

    public function save()
    {
        $this->activity->save();
        return $this->activity;
    }
}
