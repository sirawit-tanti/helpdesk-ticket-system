<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketCommentRequest;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Services\TicketActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TicketCommentController extends Controller
{
    public function store(StoreTicketCommentRequest $request, Ticket $ticket, TicketActivityLogger $activityLogger): RedirectResponse
    {
        $this->authorizeComment($request, $ticket);

        $isInternal = false;

        if ($request->user()->canManageTickets()) {
            $isInternal = $request->boolean('is_internal');
        }

        $comment = $ticket->comments()->create([
            'user_id' => $request->user()->id,
            'message' => $request->input('message'),
            'is_internal' => $isInternal
        ]);

        $hasAttachments = false;

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket-attachments', 'public');

                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'ticket_comment_id' => $comment->id,
                    'uploaded_by' => $request->user()->id,
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);

                $hasAttachments = true;
            }
        }

        if ($hasAttachments) {
            $activityLogger->attachmentAdded($ticket, $request->user()->id);
        }

        if ($isInternal) {
            $activityLogger->internalNoteAdded($ticket, $request->user()->id);
        } else {
            $activityLogger->commentAdded($ticket, $request->user()->id);
        }

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Comment added successfully');
    }

    private function authorizeComment(Request $request, Ticket $ticket): void
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return;
        }

        if ((int) $ticket->requester_id === (int) $user->id) {
            return;
        }

        if ($user->isAgent()) {
            if ($ticket->assignee_id === null || (int) $ticket->assignee_id === (int) $user->id) {
                return;
            }
        }

        abort(403, 'You are not allowed to comment on this ticket.');
    }
}