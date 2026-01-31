<?php

namespace App\Enums;

final class ActivityEventType
{
    public const CREATED = 'created';
    public const UPDATED = 'updated';
    public const DELETED = 'deleted';
    public const REMOVED = 'removed';
    public const LOGIN = 'login';
    public const LOGOUT = 'logout';
    public const STATUSDISABLE = 'disable';
    public const STATUSENABLE = 'enable';
}
