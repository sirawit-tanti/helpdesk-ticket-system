<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketActivityLog;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $baseQuery = Ticket::query();

        if ($user->isRequester()) {
            $baseQuery->where('requester_id', $user->id);
        }

        if ($user->isAgent()) {
            $baseQuery->where(function ($query) use ($user) {
                $query->where('assignee_id', $user->id)
                    ->orWhereNull('assignee_id');
            });
        }

        $statusCounts = (clone $baseQuery)
            ->join('ticket_statuses', 'tickets.ticket_status_id', '=', 'ticket_statuses.id')
            ->select('ticket_statuses.name', DB::raw('COUNT(*) as total'))
            ->groupBy('ticket_statuses.name')
            ->pluck('total', 'ticket_statuses.name');

        $highPriorityTickets = (clone $baseQuery)
            ->join('ticket_priorities', 'tickets.ticket_priority_id', '=', 'ticket_priorities.id')
            ->whereIn('ticket_priorities.name', ['High', 'Critical'])
            ->count();

        $recentTickets = (clone $baseQuery)
            ->with([
                'requester',
                'priority',
                'status',
            ])
            ->latest('tickets.created_at')
            ->limit(5)
            ->get();

        $totalTickets = $statusCounts->sum();

        $openTickets = $statusCounts->get('Open', 0);
        $inProgressTickets = $statusCounts->get('In Progress', 0);
        $resolvedTickets = $statusCounts->get('Resolved', 0);
        $closedTickets = $statusCounts->get('Closed', 0);

        $overdueTickets = (clone $baseQuery)
            ->whereNotNull('due_at')
            ->where('due_at', '<', now())
            ->whereHas('status', function ($query) {
                $query->where('is_closed', false);
            })
            ->count();

        $recentActivitiesQuery = TicketActivityLog::with(['user', 'ticket'])
            ->latest()
            ->limit(8);

        if ($user->isRequester()) {
            $recentActivitiesQuery->whereHas('ticket', function ($query) use ($user) {
                $query->where('requester_id', $user->id);
            });
        }

        if ($user->isAgent()) {
            $recentActivitiesQuery->whereHas('ticket', function ($query) use ($user) {
                $query->where(function ($query) use ($user) {
                    $query->where('assignee_id', $user->id)
                        ->orWhereNull('assignee_id');
                });
            });
        }

        $recentActivities = $recentActivitiesQuery->get();

        $openStatus = TicketStatus::where('name', 'Open')->first();
        $inProgressStatus = TicketStatus::where('name', 'In Progress')->first();
        $resolvedStatus = TicketStatus::where('name', 'Resolved')->first();
        $closedStatus = TicketStatus::where('name', 'Closed')->first();
        $highPriority = TicketPriority::where('name', 'High')->first();
        $criticalPriority = TicketPriority::where('name', 'Critical')->first();

        $dashboardLinks = [
            'total' => route('tickets.index'),

            'open' => $openStatus
                ? route('tickets.index', ['status_id' => $openStatus->id])
                : route('tickets.index'),

            'in_progress' => $inProgressStatus
                ? route('tickets.index', ['status_id' => $inProgressStatus->id])
                : route('tickets.index'),

            'resolved' => $resolvedStatus
                ? route('tickets.index', ['status_id' => $resolvedStatus->id])
                : route('tickets.index'),

            'closed' => $closedStatus
                ? route('tickets.index', ['status_id' => $closedStatus->id])
                : route('tickets.index'),

            'high_critical' => route('tickets.index', ['priority_group' => 'high_critical']),

            'overdue' => route('tickets.index', ['overdue' => 1]),
        ];

        return view('dashboard.index', compact(
            'totalTickets',
            'openTickets',
            'inProgressTickets',
            'resolvedTickets',
            'closedTickets',
            'highPriorityTickets',
            'recentTickets',
            'overdueTickets',
            'recentActivities',
            'dashboardLinks'
        ));
    }

    private function countByStatus($query, string $statusName): int
    {
        $status = TicketStatus::where('name', $statusName)->first();

        if (! $status) {
            return 0;
        }

        return (clone $query)
            ->where('ticket_status_id', $status->id)
            ->count();
    }
}