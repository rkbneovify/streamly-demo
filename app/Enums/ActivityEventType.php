<?php

namespace App\Enums;

final class ActivityEventType
{
    public const CREATED = 'created';
    public const UPDATED = 'updated';
    public const DELETED = 'deleted';
    public const REMOVED = 'removed';
    public const LOGIN = 'login';
    public const REGISTER = 'register';
    public const LOGOUT = 'logout';
    public const STATUSDISABLE = 'disable';
    public const STATUSENABLE = 'enable';
    public const CANCELED = 'canceled';
    public const TRIALEND = 'trial_end';
    public const MailResend = 'mail resend';
    public const NewPlanAdded = 'plan added';
    public const Verification = 'verification';
    public const Regenerate = 'regenerate';
    public const Forgot = 'forgot';
    public const AccountActive = 'account active';
    public const Change = 'change';
    public const Subscription = 'subscription';

    public const Failed = 'failed';
    public const Resume = 'resume';
    public const Terminated = 'terminated';
}
