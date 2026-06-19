@extends('layouts.app')

@section('title', 'Dashboard - Helpdesk Ticket System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Dashboard</h1>
        <p class="text-muted mb-0">
            Welcome back, {{ auth()->user()->name }}
        </p>
    </div>

    <!-- <a href="{{ route('tickets.create') }}" class="btn btn-primary">
        Create Ticket
    </a> -->
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="h5 mb-1">Quick Actions</h2>
                <p class="text-muted mb-0">
                    Jump to common helpdesk tasks.
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>
                    Create Ticket
                </a>

                <a href="{{ route('tickets.my') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-person-lines-fill me-1"></i>
                    My Tickets
                </a>

                @if(auth()->user()->canManageTickets())
                <a href="{{ route('tickets.assigned-to-me') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-person-check me-1"></i>
                    Assigned to Me
                </a>

                <a href="{{ route('tickets.unassigned') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-inbox me-1"></i>
                    Unassigned
                </a>

                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-bar-chart-line me-1"></i>
                    Reports
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Total Tickets</div>
                <div class="h3 mb-0">{{ $totalTickets }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Open</div>
                <div class="h3 mb-0">{{ $openTickets }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">In Progress</div>
                <div class="h3 mb-0">{{ $inProgressTickets }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Resolved</div>
                <div class="h3 mb-0">{{ $resolvedTickets }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Closed</div>
                <div class="h3 mb-0">{{ $closedTickets }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">High / Critical</div>
                <div class="h3 mb-0">{{ $highPriorityTickets }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Overdue</div>
                <div class="h3 mb-0 text-danger">{{ $overdueTickets }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span>Recent Tickets</span>

        <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-outline-primary">
            View All
        </a>
    </div>

    <div class="card-body">
        @if($recentTickets->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Ticket No.</th>
                        <th>Title</th>
                        <th>Requester</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($recentTickets as $ticket)
                    <tr>
                        <td class="fw-semibold">
                            {{ $ticket->ticket_no }}
                        </td>

                        <td>
                            {{ $ticket->title }}
                        </td>

                        <td>
                            {{ $ticket->requester?->name ?? '-' }}
                        </td>

                        <td>
                            <span class="badge bg-{{ $ticket->priority?->color ?? 'secondary' }}">
                                {{ $ticket->priority?->name ?? '-' }}
                            </span>
                        </td>

                        <td>
                            <span class="badge bg-{{ $ticket->status?->color ?? 'secondary' }}">
                                {{ $ticket->status?->name ?? '-' }}
                            </span>
                        </td>

                        <td>
                            {{ $ticket->created_at->format('Y-m-d H:i') }}
                        </td>

                        <td class="text-end">
                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <h2 class="h5">No tickets yet</h2>
            <p class="text-muted mb-3">
                Create your first ticket to start tracking support requests.
            </p>

            <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                Create Ticket
            </a>
        </div>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div>
            Recent Activity
        </div>

        <span class="badge bg-light text-dark border">
            {{ $recentActivities->count() }}
        </span>
    </div>

    <div class="card-body">
        @if($recentActivities->count())
        <div class="dashboard-activity-list">
            @foreach($recentActivities as $activity)
            @php
            $activityIcon = match ($activity->action) {
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

            $activityText = match ($activity->action) {
            'created' => 'created a ticket',
            'comment_added' => 'added a comment',
            'internal_note_added' => 'added an internal note',
            'resolved' => 'resolved a ticket',
            'closed' => 'closed a ticket',
            'reopened' => 'reopened a ticket',
            'attachment_added' => 'added an attachment',
            'due_date_set' => 'set a due date',
            'updated' => $activity->field
            ? 'updated ' . str_replace('_', ' ', $activity->field)
            : 'updated a ticket',
            default => str_replace('_', ' ', $activity->action),
            };
            @endphp

            <div class="dashboard-activity-item">
                <div class="dashboard-activity-icon">
                    <i class="bi {{ $activityIcon }}"></i>
                </div>

                <div class="flex-grow-1">
                    <div class="dashboard-activity-title">
                        <span class="fw-semibold">
                            {{ $activity->user?->name ?? 'System' }}
                        </span>

                        {{ $activityText }}

                        @if($activity->ticket)
                        <a href="{{ route('tickets.show', $activity->ticket) }}">
                            {{ $activity->ticket->ticket_no }}
                        </a>
                        @endif
                    </div>

                    <div class="dashboard-activity-time">
                        {{ $activity->created_at?->format('Y-m-d H:i') }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-muted mb-0">
            No recent activity.
        </p>
        @endif
    </div>
</div>
@endsection