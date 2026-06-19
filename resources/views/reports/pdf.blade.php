<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Ticket Report</title>

    <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        color: #111827;
        font-size: 12px;
        line-height: 1.45;
    }

    .header {
        border-bottom: 2px solid #2563eb;
        padding-bottom: 12px;
        margin-bottom: 18px;
    }

    .title {
        font-size: 22px;
        font-weight: bold;
        margin: 0;
        color: #0f172a;
    }

    .subtitle {
        margin-top: 4px;
        color: #64748b;
    }

    .meta {
        margin-top: 10px;
        color: #475569;
        font-size: 11px;
    }

    .summary-table,
    .report-table,
    .ticket-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 18px;
    }

    .summary-table td {
        width: 25%;
        border: 1px solid #e5e7eb;
        padding: 10px;
        vertical-align: top;
    }

    .summary-label {
        color: #64748b;
        font-size: 10px;
        text-transform: uppercase;
    }

    .summary-value {
        margin-top: 4px;
        font-size: 20px;
        font-weight: bold;
        color: #0f172a;
    }

    .summary-danger {
        color: #dc2626;
    }

    .section-title {
        margin: 18px 0 8px;
        font-size: 15px;
        font-weight: bold;
        color: #0f172a;
    }

    .report-table th,
    .report-table td,
    .ticket-table th,
    .ticket-table td {
        border: 1px solid #e5e7eb;
        padding: 7px;
        text-align: left;
    }

    .report-table th,
    .ticket-table th {
        background: #f8fafc;
        color: #334155;
        font-size: 11px;
    }

    .ticket-table {
        font-size: 10px;
    }

    .text-muted {
        color: #64748b;
    }

    .footer {
        margin-top: 20px;
        padding-top: 8px;
        border-top: 1px solid #e5e7eb;
        color: #94a3b8;
        font-size: 10px;
        text-align: right;
    }
    </style>
</head>

<body>
    <div class="header">
        <h1 class="title">Helpdesk Ticket Report</h1>

        <div class="subtitle">
            Ticket analytics and operational overview.
        </div>

        <div class="meta">
            Date From:
            <strong>{{ $dateFrom ?? 'All' }}</strong>
            &nbsp; | &nbsp;
            Date To:
            <strong>{{ $dateTo ?? 'All' }}</strong>
            &nbsp; | &nbsp;
            Generated At:
            <strong>{{ now()->format('Y-m-d H:i') }}</strong>
        </div>
    </div>

    <table class="summary-table">
        <tr>
            <td>
                <div class="summary-label">Total Tickets</div>
                <div class="summary-value">{{ $totalTickets }}</div>
            </td>

            <td>
                <div class="summary-label">Open Tickets</div>
                <div class="summary-value">{{ $openTickets }}</div>
            </td>

            <td>
                <div class="summary-label">Closed Tickets</div>
                <div class="summary-value">{{ $closedTickets }}</div>
            </td>

            <td>
                <div class="summary-label">Overdue Tickets</div>
                <div class="summary-value summary-danger">{{ $overdueTickets }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Tickets by Status</div>

    <table class="report-table">
        <thead>
            <tr>
                <th>Status</th>
                <th>Total</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @forelse($statusReports as $report)
            @php
            $percentage = $totalTickets > 0
            ? round(($report->total / $totalTickets) * 100)
            : 0;
            @endphp

            <tr>
                <td>{{ $report->name }}</td>
                <td>{{ $report->total }}</td>
                <td>{{ $percentage }}%</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-muted">No data available.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Tickets by Priority</div>

    <table class="report-table">
        <thead>
            <tr>
                <th>Priority</th>
                <th>Total</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @forelse($priorityReports as $report)
            @php
            $percentage = $totalTickets > 0
            ? round(($report->total / $totalTickets) * 100)
            : 0;
            @endphp

            <tr>
                <td>{{ $report->name }}</td>
                <td>{{ $report->total }}</td>
                <td>{{ $percentage }}%</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-muted">No data available.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Tickets by Category</div>

    <table class="report-table">
        <thead>
            <tr>
                <th>Category</th>
                <th>Total</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categoryReports as $report)
            @php
            $percentage = $totalTickets > 0
            ? round(($report->total / $totalTickets) * 100)
            : 0;
            @endphp

            <tr>
                <td>{{ $report->name }}</td>
                <td>{{ $report->total }}</td>
                <td>{{ $percentage }}%</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-muted">No data available.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Recent Tickets</div>

    <table class="ticket-table">
        <thead>
            <tr>
                <th>Ticket No</th>
                <th>Title</th>
                <th>Requester</th>
                <th>Assignee</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
            <tr>
                <td>{{ $ticket->ticket_no }}</td>
                <td>{{ $ticket->title }}</td>
                <td>{{ $ticket->requester?->name ?? '-' }}</td>
                <td>{{ $ticket->assignee?->name ?? 'Unassigned' }}</td>
                <td>{{ $ticket->priority?->name ?? '-' }}</td>
                <td>{{ $ticket->status?->name ?? '-' }}</td>
                <td>{{ $ticket->created_at?->format('Y-m-d H:i') ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-muted">No tickets available.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Helpdesk Ticket System
    </div>
</body>

</html>