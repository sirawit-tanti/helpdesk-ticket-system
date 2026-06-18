<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model
{
    protected $fillable = [
        'name',
        'color',
        'is_closed',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_closed' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}