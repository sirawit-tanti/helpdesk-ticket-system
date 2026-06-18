@extends('layouts.app')

@section('title', 'Tickets - Helpdesk Ticket System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Tickets</h1>
        <p class="text-muted mb-0">
            Manage and track support requests.
        </p>
    </div>

    <a href="{{ route('tickets.create') }}" class="btn btn-primary">
        Create Ticket
    </a>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('tickets.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search</label>

                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Search ticket no. or title">
                </div>

                <div class="col-md-2">
                    <label for="status_id" class="form-label">Status</label>

                    <select name="status_id" id="status_id" class="form-select">
                        <option value="">All Statuses</option>

                        @foreach($statuses as $status)
                        <option value="{{ $status->id }}" @selected(request('status_id')==$status->id)
                            >
                            {{ $status->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="priority_id" class="form-label">Priority</label>

                    <select name="priority_id" id="priority_id" class="form-select">
                        <option value="">All Priorities</option>

                        @foreach($priorities as $priority)
                        <option value="{{ $priority->id }}" @selected(request('priority_id')==$priority->id)
                            >
                            {{ $priority->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="category_id" class="form-label">Category</label>

                    <select name="category_id" id="category_id" class="form-select">
                        <option value="">All Categories</option>

                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id')==$category->id)
                            >
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="overdue" id="overdue" value="1" class="form-check-input"
                            @checked(request()->boolean('overdue'))
                        >

                        <label for="overdue" class="form-check-label">
                            Overdue only
                        </label>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            Filter
                        </button>

                        <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">
                            Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@if(request()->hasAny(['search', 'status_id', 'priority_id', 'category_id', 'overdue']))
<div class="alert alert-info">
    Showing filtered results.
</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        @if($tickets->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Ticket No.</th>
                        <th>Title</th>
                        <th>Requester</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th>Created</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
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
                            {{ $ticket->category?->name ?? '-' }}
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
                            @if($ticket->due_at)
                            <div>
                                {{ $ticket->due_at->format('Y-m-d H:i') }}
                            </div>

                            <span class="badge bg-{{ $ticket->due_status_color }}">
                                {{ $ticket->due_status_label }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>

                        <td>
                            {{ $ticket->created_at->format('Y-m-d H:i') }}
                        </td>

                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>

                                @if(auth()->user()->canManageTickets())
                                <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-sm btn-outline-secondary">
                                    Edit
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $tickets->links() }}
        @else
        <div class="text-center py-5">
            <h2 class="h5">No tickets found</h2>
            <p class="text-muted mb-3">
                Create your first support ticket to get started.
            </p>

            <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                Create Ticket
            </a>
        </div>
        @endif
    </div>
</div>
@endsection