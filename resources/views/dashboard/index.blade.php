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

    <a href="{{ route('tickets.create') }}" class="btn btn-primary">
        Create Ticket
    </a>
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
@endsection