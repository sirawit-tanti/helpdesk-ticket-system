<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_no',
        'requester_id',
        'assignee_id',
        'department_id',
        'ticket_category_id',
        'ticket_priority_id',
        'ticket_status_id',
        'title',
        'description',
        'due_at',
        'resolved_at',
        'closed_at'
    ];

    protected $casts = [
        'due_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime'
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'ticket_category_id');
    }

    public function priority()
    {
        return $this->belongsTo(TicketPriority::class, 'ticket_priority_id');
    }

    public function status()
    {
        return $this->belongsTo(TicketStatus::class, 'ticket_status_id');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(TicketActivityLog::class);
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }
    
    public function isClosed(): bool
    {
        return (bool) $this->status?->is_closed;
    }

    public function isOverdue(): bool
    {
        return $this->due_at !== null
            && ! $this->isClosed()
            && $this->due_at->isPast();
    }

    public function isDueSoon(): bool
    {
        return $this->due_at !== null
            && ! $this->isClosed()
            && ! $this->isOverdue()
            && $this->due_at->diffInHours(now()) <= 24;
    }

    public function getDueStatusLabelAttribute(): string
    {
        if ($this->due_at === null) {
            return 'No due date';
        }

        if ($this->isClosed()) {
            return 'Completed';
        }

        if ($this->isOverdue()) {
            return 'Overdue';
        }

        if ($this->isDueSoon()) {
            return 'Due Soon';
        }

        return 'On Track';
    }

    public function getDueStatusColorAttribute(): string
    {
        if ($this->due_at === null) {
            return 'secondary';
        }

        if ($this->isClosed()) {
            return 'success';
        }

        if ($this->isOverdue()) {
            return 'danger';
        }

        if ($this->isDueSoon()) {
            return 'warning';
        }

        return 'primary';
    }
}