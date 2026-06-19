@extends('layouts.app')

@section('title', $ticket->ticket_no . ' - Helpdesk Ticket System')

@section('content')
<div class="ticket-detail-header mb-4">
    <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                <span class="badge bg-light text-dark border">
                    {{ $ticket->ticket_no }}
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
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white">
        Workflow Actions
    </div>

    <div class="card-body">
        <div class="d-flex flex-wrap gap-2">
            @if((int) $ticket->assignee_id !== (int) auth()->id())
            <form method="POST" action="{{ route('tickets.assign-to-me', $ticket) }}">
                @csrf
                @method('PATCH')

                <button type="submit" class="btn btn-outline-success">
                    Assign to Me
                </button>
            </form>
            @endif

            @if($ticket->assignee_id !== null)
            <form method="POST" action="{{ route('tickets.unassign', $ticket) }}">
                @csrf
                @method('PATCH')

                <button type="submit" class="btn btn-outline-secondary">
                    Unassign
                </button>
            </form>
            @endif

            @if(! $ticket->isClosed())
            <form method="POST" action="{{ route('tickets.resolve', $ticket) }}">
                @csrf
                @method('PATCH')

                <button type="submit" class="btn btn-success">
                    Resolve
                </button>
            </form>

            <form method="POST" action="{{ route('tickets.close', $ticket) }}">
                @csrf
                @method('PATCH')

                <button type="submit" class="btn btn-outline-dark">
                    Close
                </button>
            </form>
            @else
            <form method="POST" action="{{ route('tickets.reopen', $ticket) }}">
                @csrf
                @method('PATCH')

                <button type="submit" class="btn btn-warning">
                    Reopen
                </button>
            </form>
            @endif
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
                    <div class="empty-state-icon">💬</div>
                    <div class="empty-state-title">No comments yet</div>
                    <div class="empty-state-text">
                        Add the first comment to start the conversation.
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                Add Comment
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('tickets.comments.store', $ticket) }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="message" class="form-label">
                            Message
                        </label>

                        <textarea name="message" id="message" rows="4"
                            class="form-control @error('message') is-invalid @enderror" placeholder="Write a comment..."
                            required>{{ old('message') }}</textarea>

                        @error('message')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    @if(auth()->user()->canManageTickets())
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_internal" id="is_internal" value="1" class="form-check-input"
                            {{ old('is_internal') ? 'checked' : '' }}>

                        <label for="is_internal" class="form-check-label">
                            Internal note
                        </label>

                        <div class="form-text">
                            Internal notes are visible only to agents and admins.
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="attachments" class="form-label">
                            Attachments
                        </label>

                        <input type="file" name="attachments[]" id="attachments"
                            class="form-control @error('attachments') is-invalid @enderror @error('attachments.*') is-invalid @enderror"
                            multiple>

                        <div class="form-text">
                            You can upload up to 5 files. Supported: jpg, png, pdf, txt, doc, docx, xls, xlsx.
                        </div>

                        @error('attachments')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                        @error('attachments.*')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Add Comment
                    </button>
                </form>
            </div>
        </div>
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
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    Activity Logs
                </div>

                <span class="badge bg-light text-dark border">
                    {{ $ticket->activityLogs->count() }}
                </span>
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
                    @endphp

                    <div class="activity-item {{ $activityClass }}">
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
                @else
                <div class="empty-state">
                    <div class="empty-state-icon">🕒</div>
                    <div class="empty-state-title">No activity logs yet</div>
                    <div class="empty-state-text">
                        Ticket activity will appear here.
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-3">
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