@extends('layouts.app')

@section('title', $ticket->ticket_no . ' - Helpdesk Ticket System')

@section('content')
<div class="print-only print-header">
    <div class="print-title">
        Helpdesk Ticket System
    </div>

    <div class="print-meta">
        Printed at {{ now()->format('Y-m-d H:i') }}
    </div>
</div>
<div class="ticket-detail-header mb-4">
    <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                <span class="badge bg-light text-dark border">
                    {{ $ticket->ticket_no }}

                    <button type="button" class="btn btn-sm btn-link p-0 ms-1 ticket-copy-btn"
                        data-copy-text="{{ $ticket->ticket_no }}" data-copy-label="Ticket number copied"
                        title="Copy ticket number">
                        <i class="bi bi-copy"></i>
                    </button>
                </span>

                <span class="badge bg-{{ $ticket->status?->color ?? 'secondary' }}">
                    {{ $ticket->status?->name ?? '-' }}
                </span>

                <span class="badge bg-{{ $ticket->priority?->color ?? 'secondary' }}">
                    {{ $ticket->priority?->name ?? '-' }}
                </span>

                @if($ticket->due_at)
                <span class="badge bg-{{ $ticket->due_status_color }}">
                    {{ $ticket->due_status_label }}
                </span>
                @endif
            </div>

            <h1 class="h3 mb-2">
                {{ $ticket->title }}
            </h1>

            <p class="text-muted mb-0">
                Created by
                <span class="fw-semibold">{{ $ticket->requester?->name ?? '-' }}</span>
                on
                <span class="fw-semibold">{{ $ticket->created_at?->format('Y-m-d H:i') }}</span>
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <button type="button" class="btn btn-outline-secondary"
                data-copy-text="{{ route('tickets.show', $ticket) }}" data-copy-label="Ticket link copied">
                <i class="bi bi-link-45deg me-1"></i>
                Copy Link
            </button>

            <button type="button" class="btn btn-outline-secondary no-print" onclick="window.print()">
                <i class="bi bi-printer me-1"></i>
                Print
            </button>

            <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">
                Back
            </a>

            @if(auth()->user()->canManageTickets())
            <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-primary">
                Edit Ticket
            </a>
            @endif
        </div>
    </div>
</div>

@if(auth()->user()->canManageTickets())
<div class="card border-0 shadow-sm mb-4 no-print">
    <div class="card-header bg-white">
        Workflow Actions
    </div>

    <div class="card-body">
        <div class="d-flex flex-wrap gap-2">
            @if((int) $ticket->assignee_id !== (int) auth()->id())
            <form method="POST" action="{{ route('tickets.assign-to-me', $ticket) }}">
                @csrf
                @method('PATCH')

                <button type="button" class="btn btn-outline-success" data-confirm-action
                    data-confirm-title="Assign Ticket"
                    data-confirm-message="Are you sure you want to assign this ticket to yourself?"
                    data-confirm-button="Yes, Assign" data-confirm-class="btn-primary">
                    <i class="bi bi-person-check me-1"></i>
                    Assign to Me
                </button>
            </form>
            @endif

            @if($ticket->assignee_id !== null)
            <form method="POST" action="{{ route('tickets.unassign', $ticket) }}">
                @csrf
                @method('PATCH')

                <button type="button" class="btn btn-outline-secondary" data-confirm-action
                    data-confirm-title="Unassign Ticket"
                    data-confirm-message="Are you sure you want to remove the current assignee from this ticket?"
                    data-confirm-button="Yes, Unassign" data-confirm-class="btn-secondary">
                    <i class="bi bi-person-dash me-1"></i>
                    Unassign
                </button>
            </form>
            @endif

            @if(! $ticket->isClosed())
            <form method="POST" action="{{ route('tickets.resolve', $ticket) }}">
                @csrf
                @method('PATCH')

                <button type="button" class="btn btn-success" data-confirm-action data-confirm-title="Resolve Ticket"
                    data-confirm-message="Are you sure you want to mark this ticket to resolved?"
                    data-confirm-button="Yes, Resolve" data-confirm-class="btn-success">
                    <i class="bi bi-check-circle me-1"></i>
                    Resolve
                </button>
            </form>

            <form method="POST" action="{{ route('tickets.close', $ticket) }}">
                @csrf
                @method('PATCH')

                <button type="button" class="btn btn-outline-dark" data-confirm-action data-confirm-title="Close Ticket"
                    data-confirm-message="Are you sure you want to close this ticket? Closed tickets are considered completed."
                    data-confirm-button="Yes, Close" data-confirm-class="btn-dark">
                    <i class="bi bi-lock me-1"></i>
                    Close
                </button>
            </form>
            @else
            <form method="POST" action="{{ route('tickets.reopen', $ticket) }}">
                @csrf
                @method('PATCH')

                <button type="button" class="btn btn-warning" data-confirm-action data-confirm-title="Reopen Ticket"
                    data-confirm-message="Are you sure you want to reopen this ticket?"
                    data-confirm-button="Yes, Reopen" data-confirm-class="btn-warning">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                    Reopen
                </button>
            </form>
            @endif
            <form method="POST" action="{{ route('tickets.assign', $ticket) }}" class="ticket-assign-form">
                @csrf
                @method('PATCH')

                <div class="input-group">
                    <select name="assignee_id" class="form-select" required>
                        <option value="">Assign to...</option>

                        @foreach($agents as $agent)
                        <option value="{{ $agent->id }}" @selected((int) $ticket->assignee_id === (int) $agent->id)>
                            {{ $agent->name }}
                        </option>
                        @endforeach
                    </select>

                    <button type="button" class="btn btn-outline-primary" data-confirm-action
                        data-confirm-title="Assign Ticket"
                        data-confirm-message="Are you sure you want to assign this ticket to the selected user?"
                        data-confirm-button="Yes, Assign" data-confirm-class="btn-primary">
                        <i class="bi bi-person-plus me-1"></i>
                        Assign
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white">
                Ticket Details
            </div>

            <div class="card-body">
                <h2 class="h5 mb-3">{{ $ticket->title }}</h2>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        Description
                    </div>

                    <div class="card-body">
                        <div class="ticket-description">
                            {!! nl2br(e($ticket->description)) !!}
                        </div>
                    </div>
                </div>

                <div class="text-muted small">
                    Created at {{ $ticket->created_at->format('Y-m-d H:i') }}
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    Comments
                </div>

                <span class="badge bg-light text-dark border">
                    {{ $ticket->comments->count() }}
                </span>
            </div>

            <div class="card-body">
                @if($ticket->comments->count())
                <div class="comment-timeline">
                    @foreach($ticket->comments as $comment)
                    <div class="comment-item {{ $comment->is_internal ? 'comment-internal' : '' }}">
                        <div class="comment-avatar">
                            {{ strtoupper(substr($comment->user?->name ?? 'U', 0, 1)) }}
                        </div>

                        <div class="comment-content">
                            <div class="comment-bubble">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                    <div>
                                        <div class="comment-author">
                                            {{ $comment->user?->name ?? 'Unknown User' }}
                                        </div>

                                        <div class="comment-time">
                                            {{ $comment->created_at?->format('Y-m-d H:i') }}
                                        </div>
                                    </div>

                                    @if($comment->is_internal)
                                    <span class="badge bg-warning text-dark">
                                        Internal Note
                                    </span>
                                    @else
                                    <span class="badge bg-light text-dark border">
                                        Comment
                                    </span>
                                    @endif
                                </div>

                                <div class="comment-message">
                                    {!! nl2br(e($comment->message)) !!}
                                </div>

                                @if($comment->attachments->count())
                                <div class="comment-attachments">
                                    @foreach($comment->attachments as $attachment)
                                    <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank"
                                        class="comment-attachment-link">
                                        <span class="comment-attachment-icon">
                                            <i class="bi bi-paperclip"></i>
                                        </span>

                                        <span>
                                            {{ $attachment->original_name }}
                                        </span>

                                        <span class="text-muted">
                                            ({{ $attachment->formatted_file_size }})
                                        </span>
                                    </a>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <div class="empty-state-title">No comments yet</div>
                    <div class="empty-state-text">
                        Add the first comment to start the conversation.
                    </div>
                </div>
                @endif
            </div>
        </div>


        <form method="POST" action="{{ route('tickets.comments.store', $ticket) }}" enctype="multipart/form-data"
            data-loading-form>
            @csrf

            <div class="card border-0 shadow-sm mt-4 no-print">
                <div class="card-body p-0">
                    <div class="comment-composer">
                        <div class="comment-composer-header">
                            <div>
                                <h5 class="comment-composer-title">
                                    Add Reply
                                </h5>

                                <p class="comment-composer-text">
                                    Share an update or add more details to this ticket.
                                </p>
                            </div>

                            @if(auth()->user()->canManageTickets())
                            <div class="form-check form-switch comment-type-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_internal"
                                    name="is_internal" value="1" data-comment-type-toggle>

                                <label class="form-check-label" for="is_internal">
                                    Internal Note
                                </label>
                            </div>
                            @endif
                        </div>

                        @if(auth()->user()->canManageTickets())
                        <div class="comment-internal-alert d-none" data-internal-note-alert>
                            <i class="bi bi-shield-lock me-1"></i>
                            This note is internal and will only be visible to admins and agents.
                        </div>
                        @endif

                        <textarea name="message" rows="4"
                            class="form-control comment-composer-input @error('message') is-invalid @enderror"
                            placeholder="Write your reply..." data-comment-input
                            required>{{ old('message') }}</textarea>

                        @error('message')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                        <div class="comment-attach-box mt-4">
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                <div>
                                    <div class="comment-attach-title">
                                        <i class="bi bi-paperclip me-1"></i>
                                        Attach files
                                    </div>

                                    <div class="comment-attach-text">
                                        Optional screenshots or related documents for this reply.
                                    </div>
                                </div>

                                <span class="badge bg-light text-dark border" id="selectedFileCount">
                                    0 file(s)
                                </span>
                            </div>

                            <div class="comment-file-dropzone">
                                <div class="comment-file-icon">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                </div>

                                <div class="comment-file-title" id="selectedFileTitle">
                                    No files selected
                                </div>

                                <div class="comment-file-text">
                                    Choose files to attach with your reply.
                                </div>

                                <label for="attachments" class="btn btn-sm btn-outline-primary mt-3">
                                    <i class="bi bi-folder2-open me-1"></i>
                                    Choose Files
                                </label>

                                <input type="file" name="attachments[]" id="attachments"
                                    class="visually-hidden @error('attachments.*') is-invalid @enderror" multiple>
                            </div>

                            <div class="selected-file-list d-none" id="selectedFileList"></div>

                            @error('attachments.*')
                            <div class="invalid-feedback d-block mt-2">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="comment-composer-footer">
                            <div class="comment-composer-hint" data-comment-hint>
                                Public replies are visible to the requester and support team.
                            </div>

                            <button type="submit" class="btn btn-primary" data-comment-submit>
                                <i class="bi bi-send me-1"></i>
                                Send Reply
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                Ticket Information
            </div>

            <div class="card-body">
                <div class="ticket-info-grid">
                    <div class="ticket-info-item">
                        <div class="ticket-info-label">Requester</div>
                        <div class="ticket-info-value">{{ $ticket->requester?->name ?? '-' }}</div>
                    </div>

                    <div class="ticket-info-item">
                        <div class="ticket-info-label">Assignee</div>
                        <div class="ticket-info-value">
                            @if($ticket->assignee)
                            {{ $ticket->assignee->name }}
                            @else
                            <span class="badge bg-light text-dark border">Unassigned</span>
                            @endif
                        </div>
                    </div>

                    <div class="ticket-info-item">
                        <div class="ticket-info-label">Department</div>
                        <div class="ticket-info-value">{{ $ticket->department?->name ?? '-' }}</div>
                    </div>

                    <div class="ticket-info-item">
                        <div class="ticket-info-label">Category</div>
                        <div class="ticket-info-value">{{ $ticket->category?->name ?? '-' }}</div>
                    </div>

                    <div class="ticket-info-item">
                        <div class="ticket-info-label">Priority</div>
                        <div class="ticket-info-value">
                            <span class="badge bg-{{ $ticket->priority?->color ?? 'secondary' }}">
                                {{ $ticket->priority?->name ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="ticket-info-item">
                        <div class="ticket-info-label">Status</div>
                        <div class="ticket-info-value">
                            <span class="badge bg-{{ $ticket->status?->color ?? 'secondary' }}">
                                {{ $ticket->status?->name ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="ticket-info-item">
                        <div class="ticket-info-label">Due Date</div>
                        <div class="ticket-info-value">
                            @if($ticket->due_at)
                            {{ $ticket->due_at->format('Y-m-d H:i') }}
                            <span class="badge bg-{{ $ticket->due_status_color }} ms-1">
                                {{ $ticket->due_status_label }}
                            </span>
                            @else
                            <span class="text-muted">No due date</span>
                            @endif
                        </div>
                    </div>

                    <div class="ticket-info-item">
                        <div class="ticket-info-label">Created At</div>
                        <div class="ticket-info-value">
                            {{ $ticket->created_at?->format('Y-m-d H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 pb-0">
                <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                    <div>
                        <h5 class="mb-1">
                            <i class="bi bi-clock-history me-1"></i>
                            Activity Logs
                        </h5>

                        <div class="text-muted small">
                            Track important changes and updates for this ticket.
                        </div>
                    </div>

                    <div class="activity-header-actions">
                        <span class="badge bg-light text-dark border">
                            {{ $ticket->activityLogs->count() }}
                        </span>

                        @if($ticket->activityLogs->count())
                        <div class="activity-filter-group" role="group" aria-label="Activity filters">
                            <button type="button" class="activity-filter-btn active" data-activity-filter="all">
                                All
                            </button>

                            <button type="button" class="activity-filter-btn" data-activity-filter="comment">
                                Comments
                            </button>

                            <button type="button" class="activity-filter-btn" data-activity-filter="attachment">
                                Attachments
                            </button>

                            <button type="button" class="activity-filter-btn" data-activity-filter="status">
                                Status
                            </button>

                            <button type="button" class="activity-filter-btn" data-activity-filter="assignment">
                                Assignment
                            </button>

                            <button type="button" class="activity-filter-btn" data-activity-filter="other">
                                Other
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if($ticket->activityLogs->count())
                <div class="activity-timeline">
                    @foreach($ticket->activityLogs as $log)
                    @php
                    $activityIcon = match ($log->action) {
                    'created' => 'bi-plus-lg',
                    'comment_added' => 'bi-chat-dots',
                    'internal_note_added' => 'bi-journal-text',
                    'resolved' => 'bi-check-lg',
                    'closed' => 'bi-lock',
                    'reopened' => 'bi-arrow-clockwise',
                    'attachment_added' => 'bi-paperclip',
                    'due_date_set' => 'bi-clock',
                    'updated' => 'bi-arrow-left-right',
                    default => 'bi-circle-fill',
                    };

                    $activityClass = match ($log->action) {
                    'created' => 'activity-created',
                    'resolved' => 'activity-success',
                    'closed' => 'activity-closed',
                    'reopened' => 'activity-warning',
                    'internal_note_added' => 'activity-warning',
                    'attachment_added' => 'activity-info',
                    'due_date_set' => 'activity-info',
                    'updated' => 'activity-updated',
                    default => 'activity-default',
                    };

                    $activityType = match (true) {
                    in_array($log->action, ['comment_added', 'internal_note_added'], true) => 'comment',
                    $log->action === 'attachment_added' => 'attachment',
                    in_array($log->action, ['resolved', 'closed', 'reopened'], true) => 'status',
                    $log->action === 'updated' && in_array($log->field, ['status', 'ticket_status_id', 'ticket_status'],
                    true) => 'status',
                    $log->action === 'updated' && in_array($log->field, ['assignee', 'assignee_id'], true) =>
                    'assignment',
                    default => 'other',
                    };
                    @endphp

                    <div class="activity-item {{ $activityClass }}" data-activity-item
                        data-activity-type="{{ $activityType }}">
                        <div class="activity-icon">
                            <i class="bi {{ $activityIcon }}"></i>
                        </div>

                        <div class="activity-content">
                            <div class="activity-card">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-1">
                                    <div class="activity-title">
                                        @if($log->action === 'created')
                                        Created this ticket
                                        @elseif($log->action === 'comment_added')
                                        Added a comment
                                        @elseif($log->action === 'internal_note_added')
                                        Added an internal note
                                        @elseif($log->action === 'resolved')
                                        Resolved this ticket
                                        @elseif($log->action === 'closed')
                                        Closed this ticket
                                        @elseif($log->action === 'reopened')
                                        Reopened this ticket
                                        @elseif($log->action === 'attachment_added')
                                        Added an attachment
                                        @elseif($log->action === 'due_date_set')
                                        Set due date
                                        @elseif($log->action === 'updated' && $log->field)
                                        Updated {{ ucwords(str_replace('_', ' ', $log->field)) }}
                                        @elseif($log->action === 'updated')
                                        Updated this ticket
                                        @else
                                        {{ ucwords(str_replace('_', ' ', $log->action)) }}
                                        @endif
                                    </div>

                                    <div class="activity-time">
                                        {{ $log->created_at?->format('Y-m-d H:i') }}
                                    </div>
                                </div>

                                <div class="activity-meta">
                                    By
                                    <span class="fw-semibold">
                                        {{ $log->user?->name ?? 'System' }}
                                    </span>
                                </div>

                                @if($log->action === 'updated' && $log->field)
                                <div class="activity-change mt-2">
                                    <span class="activity-old">
                                        {{ $log->old_value ?? '-' }}
                                    </span>

                                    <span class="activity-arrow">
                                        →
                                    </span>

                                    <span class="activity-new">
                                        {{ $log->new_value ?? '-' }}
                                    </span>
                                </div>
                                @elseif($log->action === 'due_date_set')
                                <div class="activity-change mt-2">
                                    <span class="activity-new">
                                        {{ $log->new_value ?? '-' }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="activity-filter-empty d-none" data-activity-empty>
                    <div class="attachment-empty-icon">
                        <i class="bi bi-funnel"></i>
                    </div>

                    <div class="fw-bold">
                        No activity found
                    </div>

                    <div class="text-muted small">
                        Try choosing another activity filter.
                    </div>
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <div class="empty-state-title">
                        No activity logs yet
                    </div>

                    <div class="empty-state-text">
                        Ticket activity will appear here.
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-3 no-print">
            <div class="card-header bg-white">
                Attachments
            </div>

            <div class="card-body">
                @if($ticket->attachments->count())
                <div class="d-flex flex-column gap-2">
                    @foreach($ticket->attachments as $attachment)
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                        <div>
                            <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank"
                                class="fw-semibold text-decoration-none">
                                {{ $attachment->original_name }}
                            </a>

                            <div class="text-muted small">
                                Uploaded by {{ $attachment->uploader?->name ?? 'System' }}
                                · {{ $attachment->formatted_file_size }}
                                · {{ $attachment->created_at->format('Y-m-d H:i') }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted mb-0">
                    No attachments yet.
                </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection