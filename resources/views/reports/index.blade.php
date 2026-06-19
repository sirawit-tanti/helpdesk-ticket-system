@extends('layouts.app')

@section('title', 'Reports - Helpdesk Ticket System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Reports</h1>
        <p class="text-muted mb-0">
            Ticket analytics and operational overview.
        </p>
    </div>

    <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">
        Back to Tickets
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Total Tickets</div>
                <div class="h3 mb-0">{{ $totalTickets }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Open Tickets</div>
                <div class="h3 mb-0">{{ $openTickets }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Closed Tickets</div>
                <div class="h3 mb-0">{{ $closedTickets }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Overdue Tickets</div>
                <div class="h3 mb-0 text-danger">{{ $overdueTickets }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
                Tickets by Status
            </div>

            <div class="card-body">
                @if($statusReports->count())
                <div class="d-flex flex-column gap-3">
                    @foreach($statusReports as $report)
                    @php
                    $percentage = $totalTickets > 0
                    ? round(($report->total / $totalTickets) * 100)
                    : 0;
                    @endphp

                    <div>
                        <div class="d-flex justify-content-between mb-1">
                            <div>
                                <span class="badge bg-{{ $report->color }}">
                                    {{ $report->name }}
                                </span>
                            </div>

                            <div class="fw-semibold">
                                {{ $report->total }}
                            </div>
                        </div>

                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-{{ $report->color }}" style="width: {{ $percentage }}%;"></div>
                        </div>

                        <div class="text-muted small mt-1">
                            {{ $percentage }}%
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted mb-0">No data available.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
                Tickets by Priority
            </div>

            <div class="card-body">
                @if($priorityReports->count())
                <div class="d-flex flex-column gap-3">
                    @foreach($priorityReports as $report)
                    @php
                    $percentage = $totalTickets > 0
                    ? round(($report->total / $totalTickets) * 100)
                    : 0;
                    @endphp

                    <div>
                        <div class="d-flex justify-content-between mb-1">
                            <div>
                                <span class="badge bg-{{ $report->color }}">
                                    {{ $report->name }}
                                </span>
                            </div>

                            <div class="fw-semibold">
                                {{ $report->total }}
                            </div>
                        </div>

                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-{{ $report->color }}" style="width: {{ $percentage }}%;"></div>
                        </div>

                        <div class="text-muted small mt-1">
                            {{ $percentage }}%
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted mb-0">No data available.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
                Tickets by Category
            </div>

            <div class="card-body">
                @if($categoryReports->count())
                <div class="d-flex flex-column gap-3">
                    @foreach($categoryReports as $report)
                    @php
                    $percentage = $totalTickets > 0
                    ? round(($report->total / $totalTickets) * 100)
                    : 0;
                    @endphp

                    <div>
                        <div class="d-flex justify-content-between mb-1">
                            <div class="fw-semibold">
                                {{ $report->name }}
                            </div>

                            <div class="fw-semibold">
                                {{ $report->total }}
                            </div>
                        </div>

                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" style="width: {{ $percentage }}%;"></div>
                        </div>

                        <div class="text-muted small mt-1">
                            {{ $percentage }}%
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted mb-0">No data available.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection