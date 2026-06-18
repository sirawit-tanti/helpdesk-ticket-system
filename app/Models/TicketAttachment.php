<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    protected $fillable = [
        'ticket_id',
        'ticket_comment_id',
        'uploaded_by',
        'original_name',
        'file_path',
        'mime_type',
        'file_size',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function comment()
    {
        return $this->belongsTo(TicketComment::class, 'ticket_comment_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFormattedFileSizeAttribute(): string
    {
        if ($this->file_size >= 1024 * 1024) {
            return round($this->file_size / 1024 / 1024, 2) . ' MB';
        }

        if ($this->file_size >= 1024) {
            return round($this->file_size / 1024, 2) . ' KB';
        }

        return $this->file_size . ' B';
    }
}