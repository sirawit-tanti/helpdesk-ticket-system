<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        if (! $request->user()->canManageTickets()) {
            abort(403, 'You are not allowed to access reports.');
        }

        $baseQuery = Ticket::query();

        if ($request->user()->isAgent()) {
            $baseQuery->where(function ($query) use ($request) {
                $query->where('assignee_id', $request->user()->id)
                    ->orWhereNull('assignee_id');
            });
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
            'categoryReports'
        ));
    }
}