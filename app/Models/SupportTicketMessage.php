<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SupportTicketMessage extends Model
{
    protected $table = 'support_ticket_messages';

    public function files()
    {
        return  $this->hasMany(File::class, 'support_ticket_message_id', 'id');
    }
}
