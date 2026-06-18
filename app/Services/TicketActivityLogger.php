<?php

namespace App\Services;

use App\Models\Ticket;

class TicketActivityLogger
{
    public function log(Ticket $ticket, ?int $userId, string $action, ?string $field = null, ?string $oldValue = null, ?string $newValue = null): void
    {
        $ticket->activityLogs()->create([
            'user_id' => $userId,
            'action' => $action,
            'field' => $field,
            'old_value' => $oldValue,
            'new_value' => $newValue,
        ]);
    }

    public function logIfChanged(Ticket $ticket, ?int $userId, ?string $field = null, ?string $oldValue = null, ?string $newValue = null): void
    {
        if ($oldValue === $newValue) {
            return;
        }

        $this->log(
            ticket: $ticket,
            userId: $userId,
            action: 'updated',
            field: $field,
            oldValue: $oldValue,
            newValue: $newValue
        );
    }

    public function created(Ticket $ticket, ?int $userId): void
    {
        $this->log(
            ticket: $ticket,
            userId: $userId,
            action: 'created',
            newValue: 'Ticket created'
        );
    }

    public function commentAdded(Ticket $ticket, ?int $userId): void
    {
        $this->log(
            ticket: $ticket,
            userId: $userId,
            action: 'comment_added',
            newValue: 'Comment added'
        );
    }

    public function internalNoteAdded(Ticket $ticket, ?int $userId): void
    {
        $this->log(
            ticket: $ticket,
            userId: $userId,
            action: 'internal_note_added',
            newValue: 'Internal note added'
        );
    }

    public function resolved(Ticket $ticket, ?int $userId): void
    {
        $this->log(
            ticket: $ticket,
            userId: $userId,
            action: 'resolved',
            newValue: 'Ticket resolved'
        );
    }

    public function closed(Ticket $ticket, ?int $userId): void
    {
        $this->log(
            ticket: $ticket,
            userId: $userId,
            action: 'closed',
            newValue: 'Ticket closed'
        );
    }

    public function reopened(Ticket $ticket, ?int $userId): void
    {
        $this->log(
            ticket: $ticket,
            userId: $userId,
            action: 'reopened',
            newValue: 'Ticket reopened'
        );
    }

    public function attachmentAdded(Ticket $ticket, ?int $userId): void
    {
        $this->log(
            ticket: $ticket,
            userId: $userId,
            action: 'attachment_added',
            newValue: 'Attachment added'
        );
    }

    public function dueDateSet(Ticket $ticket, ?int $userId, ?string $dueAt): void
    {
        $this->log(
            ticket: $ticket,
            userId: $userId,
            action: 'due_date_set',
            field: 'due_at',
            oldValue: null,
            newValue: $dueAt
        );
    }
    
}