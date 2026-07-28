<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $table = 'support_tickets';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            $ticket->ticket_number = self::generateTicketNumber();
        });
    }

    public static function generateTicketNumber(): string
    {
        $prefix = 'TCK-';
        $date = now()->format('Ymd');
        $lastTicket = self::whereDate('created_at', now())->latest('id')->first();

        $sequence = $lastTicket ? ((int) substr($lastTicket->ticket_number, -4)) + 1 : 1;

        return $prefix . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
    public function message()
    {
        return  $this->hasMany(SupportTicketMessage::class, 'support_ticket_id', 'id');
    }
}
