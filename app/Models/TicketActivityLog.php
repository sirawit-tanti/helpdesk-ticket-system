<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketActivityLog extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'action',
        'field',
        'old_value',
        'new_value',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}