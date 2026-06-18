<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class TicketPriority extends Model
{
    protected $fillable = [
        'name',
        'level',
        'sla_hours',
        'color',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sla_hours' => 'integer',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function getDefaultDueAt(): ?Carbon
    {
        if (!$this->sla_hours) {
            return null;
        }

        return now()->addHours($this->sla_hours);
    }
}