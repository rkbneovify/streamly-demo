<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $table = 'support_tickets';

    public function message()
    {
        return  $this->hasMany(SupportTicketMessage::class, 'support_ticket_id', 'id');
    }
}
