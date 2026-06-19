<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        if (! $request->user()->canManageTickets()) {
            abort(403, 'You are not allowed to access reports.');
        }

        $validated = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $dateFrom = $validated['date_from'] ?? null;
        $dateTo = $validated['date_to'] ?? null;

        $baseQuery = Ticket::query();

        if ($request->user()->isAgent()) {
            $baseQuery->where(function ($query) use ($request) {
                $query->where('assignee_id', $request->user()->id)
                    ->orWhereNull('assignee_id');
            });
        }

        if ($dateFrom) {
            $baseQuery->whereDate('tickets.created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $baseQuery->whereDate('tickets.created_at', '<=', $dateTo);
        }

        $totalTickets = (clone $baseQuery)->count();

        $overdueTickets = (clone $baseQuery)
            ->whereNotNull('due_at')
            ->where('due_at', '<', now())
            ->whereHas('status', function ($query) {
                $query->where('is_closed', false);
            })
            ->count();

        $closedTickets = (clone $baseQuery)
            ->whereHas('status', function ($query) {
                $query->where('is_closed', true);
            })
            ->count();

        $openTickets = $totalTickets - $closedTickets;

        $statusReports = (clone $baseQuery)
            ->join('ticket_statuses', 'tickets.ticket_status_id', '=', 'ticket_statuses.id')
            ->select(
                'ticket_statuses.name',
                'ticket_statuses.color',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('ticket_statuses.name', 'ticket_statuses.color')
            ->orderBy('ticket_statuses.name')
            ->get();

        $priorityReports = (clone $baseQuery)
            ->join('ticket_priorities', 'tickets.ticket_priority_id', '=', 'ticket_priorities.id')
            ->select(
                'ticket_priorities.name',
                'ticket_priorities.color',
                'ticket_priorities.level',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('ticket_priorities.name', 'ticket_priorities.color', 'ticket_priorities.level')
            ->orderBy('ticket_priorities.level')
            ->get();

        $categoryReports = (clone $baseQuery)
            ->join('ticket_categories', 'tickets.ticket_category_id', '=', 'ticket_categories.id')
            ->select(
                'ticket_categories.name',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('ticket_categories.name')
            ->orderByDesc('total')
            ->get();

        return view('reports.index', compact(
            'totalTickets',
            'openTickets',
            'closedTickets',
            'overdueTickets',
            'statusReports',
            'priorityReports',
            'categoryReports',
            'dateFrom',
            'dateTo'
        ));
    }

    public function export(Request $request): StreamedResponse {
        if (! $request->user()->canManageTickets()) {
            abort(403, 'You are not allowed to export reports.');
        }

        $validated = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $dateFrom = $validated['date_from'] ?? null;
        $dateTo = $validated['date_to'] ?? null;

        $baseQuery = Ticket::query();

        if ($request->user()->isAgent()) {
            $baseQuery->where(function ($query) use ($request) {
                $query->where('assignee_id', $request->user()->id)
                    ->orWhereNull('assignee_id');
            });
        }

        if ($dateFrom) {
            $baseQuery->whereDate('tickets.created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $baseQuery->whereDate('tickets.created_at', '<=', $dateTo);
        }

        $tickets = (clone $baseQuery)
            ->with([
                'requester',
                'assignee',
                'department',
                'category',
                'priority',
                'status',
            ])
            ->latest('tickets.created_at')
            ->get();

        $fileName = 'ticket-report-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($tickets, $dateFrom, $dateTo) {
            $handle = fopen('php://output', 'w');

            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['Helpdesk Ticket Report']);

            fputcsv($handle, [
                'Date From',
                $dateFrom ?? 'All',
            ]);

            fputcsv($handle, [
                'Date To',
                $dateTo ?? 'All',
            ]);

            fputcsv($handle, []);

            fputcsv($handle, [
                'Ticket No',
                'Title',
                'Requester',
                'Assignee',
                'Department',
                'Category',
                'Priority',
                'Status',
                'Due Date',
                'Resolved At',
                'Closed At',
                'Created At',
            ]);

            foreach ($tickets as $ticket) {
                fputcsv($handle, [
                    $ticket->ticket_no,
                    $ticket->title,
                    $ticket->requester?->name ?? '-',
                    $ticket->assignee?->name ?? 'Unassigned',
                    $ticket->department?->name ?? '-',
                    $ticket->category?->name ?? '-',
                    $ticket->priority?->name ?? '-',
                    $ticket->status?->name ?? '-',
                    $ticket->due_at?->format('Y-m-d H:i') ?? '-',
                    $ticket->resolved_at?->format('Y-m-d H:i') ?? '-',
                    $ticket->closed_at?->format('Y-m-d H:i') ?? '-',
                    $ticket->created_at?->format('Y-m-d H:i') ?? '-',
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }
}