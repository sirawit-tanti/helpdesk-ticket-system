@extends('layouts.app')

@section('title', $ticket->ticket_no . ' - Helpdesk Ticket System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">{{ $ticket->ticket_no }}</h1>
        <p class="text-muted mb-0">
            {{ $ticket->title }}
        </p>
    </div>

    <div class="d-flex gap-2">
        @if(auth()->user()->canManageTickets())
        <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-primary">
            Edit Ticket
        </a>
        @endif

        <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">
            Back to Tickets
        </a>
    </div>
</div>

@if(auth()->user()->canManageTickets())
<div class="d-flex flex-wrap gap-2">
    @if(! $ticket->status?->is_closed && $ticket->status?->name !== 'Resolved')
    <form method="POST" action="{{ route('tickets.resolve', $ticket) }}">
        @csrf
        @method('PATCH')

        <button type="submit" class="btn btn-success"
            onclick="return confirm('Are you sure you want to resolve this ticket?');">
            Resolve Ticket
        </button>
    </form>
    @endif

    @if($ticket->status?->name !== 'Closed')
    <form method="POST" action="{{ route('tickets.close', $ticket) }}">
        @csrf
        @method('PATCH')

        <button type="submit" class="btn btn-dark"
            onclick="return confirm('Are you sure you want to close this ticket?');">
            Close Ticket
        </button>
    </form>
    @endif

    @if($ticket->status?->is_closed)
    <form method="POST" action="{{ route('tickets.reopen', $ticket) }}">
        @csrf
        @method('PATCH')

        <button type="submit" class="btn btn-outline-warning"
            onclick="return confirm('Are you sure you want to reopen this ticket?');">
            Reopen Ticket
        </button>
    </form>
    @endif
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

                <div class="mb-4">
                    {!! nl2br(e($ticket->description)) !!}
                </div>

                <div class="text-muted small">
                    Created at {{ $ticket->created_at->format('Y-m-d H:i') }}
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white">
                Comments
            </div>

            <div class="card-body">
                @if($ticket->comments->count())
                <div class="d-flex flex-column gap-3 mb-4">
                    @foreach($ticket->comments as $comment)
                    <div class="border rounded p-3 {{ $comment->is_internal ? 'bg-warning-subtle' : 'bg-light' }}">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="fw-semibold">
                                    {{ $comment->user?->name ?? 'Deleted User' }}

                                    @if($comment->is_internal)
                                    <span class="badge bg-warning text-dark ms-2">
                                        Internal Note
                                    </span>
                                    @endif
                                </div>

                                <div class="text-muted small">
                                    {{ $comment->created_at->format('Y-m-d H:i') }}
                                </div>
                            </div>
                        </div>

                        <div>
                            {!! nl2br(e($comment->message)) !!}
                        </div>
                    </div>
                    @if($comment->attachments->count())
                    <div class="mt-2">
                        <div class="small text-muted mb-1">
                            Attachments
                        </div>

                        <div class="d-flex flex-column gap-1">
                            @foreach($comment->attachments as $attachment)
                            <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank"
                                class="small text-decoration-none">
                                {{ $attachment->original_name }}
                                <span class="text-muted">
                                    ({{ $attachment->formatted_file_size }})
                                </span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
                @else
                <p class="text-muted">
                    No comments yet.
                </p>
                @endif

                <form method="POST" action="{{ route('tickets.comments.store', $ticket) }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="message" class="form-label">
                            Add Comment
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
                            @checked(old('is_internal'))>

                        <label for="is_internal" class="form-check-label">
                            Internal note
                        </label>

                        <div class="form-text">
                            Internal notes are visible only to administrators and support agents.
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
                            You can upload up to 5 files. Allowed types: jpg, png, pdf, txt, log, doc, docx, xls, xlsx.
                            Max 5 MB each.
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
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            Add Comment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                Information
            </div>

            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted small">Status</div>
                    <span class="badge bg-{{ $ticket->status?->color ?? 'secondary' }}">
                        {{ $ticket->status?->name ?? '-' }}
                    </span>
                </div>

                <div class="mb-3">
                    <div class="text-muted small">Priority</div>
                    <span class="badge bg-{{ $ticket->priority?->color ?? 'secondary' }}">
                        {{ $ticket->priority?->name ?? '-' }}
                    </span>
                </div>

                <div class="mb-3">
                    <div class="text-muted small">Category</div>
                    <div>{{ $ticket->category?->name ?? '-' }}</div>
                </div>

                <div class="mb-3">
                    <div class="text-muted small">Department</div>
                    <div>{{ $ticket->department?->name ?? '-' }}</div>
                </div>

                <div class="mb-3">
                    <div class="text-muted small">Requester</div>
                    <div>{{ $ticket->requester?->name ?? '-' }}</div>
                </div>

                <div class="mb-3">
                    <div class="text-muted small">Assignee</div>
                    <div>{{ $ticket->assignee?->name ?? 'Unassigned' }}</div>
                </div>

                <div class="mb-3">
                    <div class="text-muted small">Due Date</div>

                    @if($ticket->due_at)
                    <div>
                        {{ $ticket->due_at->format('Y-m-d H:i') }}
                    </div>

                    <span class="badge bg-{{ $ticket->due_status_color }}">
                        {{ $ticket->due_status_label }}
                    </span>
                    @else
                    <div>-</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-white">
                Activity Log
            </div>

            <div class="card-body">
                @if($ticket->activityLogs->count())
                <div class="d-flex flex-column gap-3">
                    @foreach($ticket->activityLogs as $log)
                    <div class="border-start border-3 ps-3">
                        <div class="small fw-semibold">
                            {{ $log->user?->name ?? 'System' }}
                        </div>

                        <div class="small">
                            @if($log->action === 'created')
                            Created this ticket.

                            @elseif($log->action === 'comment_added')
                            Added a comment.

                            @elseif($log->action === 'internal_note_added')
                            Added an internal note.

                            @elseif($log->action === 'resolved')
                            Resolved this ticket.

                            @elseif($log->action === 'closed')
                            Closed this ticket.

                            @elseif($log->action === 'reopened')
                            Reopened this ticket.

                            @elseif($log->action === 'due_date_set')
                            Set Due Date to
                            <span class="fw-semibold">{{ $log->new_value ?? '-' }}</span>.

                            @elseif(in_array($log->action, ['updated', 'update'], true) && $log->field)
                            Changed {{ ucwords(str_replace('_', ' ', $log->field)) }}
                            from
                            <span class="fw-semibold">{{ $log->old_value ?? '-' }}</span>
                            to
                            <span class="fw-semibold">{{ $log->new_value ?? '-' }}</span>.

                            @elseif($log->action === 'attachment_added')
                            Added an attachment.

                            @elseif($log->action === 'updated')
                            Updated this ticket.

                            @else
                            {{ ucwords(str_replace('_', ' ', $log->action)) }}.
                            @endif
                        </div>

                        <div class="text-muted small">
                            {{ $log->created_at->format('Y-m-d H:i') }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted mb-0">
                    No activity yet.
                </p>
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