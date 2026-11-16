<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SupportTicketMessage extends Model
{
    protected $table = 'support_ticket_messages';

    public function files()
    {
        return  $this->hasMany(File::class, 'support_ticket_message_id', 'id');
    }
    public function user()
    {
        return  $this->hasOne(User::class, 'id', 'sender_id');
    }
}
