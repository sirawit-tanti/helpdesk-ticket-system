<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\User;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use App\Services\TicketActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Ticket::with([
            'requester',
            'assignee',
            'department',
            'category',
            'priority',
            'status',
        ])->latest();

        if ($user->isRequester()) {
            $query->where('requester_id', $user->id);
        }

        if ($user->isAgent()) {
            $query->where(function ($q) use ($user) {
                $q->where('assignee_id', $user->id)
                    ->orWhereNull('assignee_id');
            });
        }

        return $this->getTicketListView(
            request: $request,
            query: $query,
            pageTitle: 'Tickets',
            pageDescription: 'View and manage support tickets.'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        $categories = TicketCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        $priorities = TicketPriority::where('is_active', true)
            ->orderBy('level')
            ->get();

        return view('tickets.create', compact(
            'departments',
            'categories',
            'priorities'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request, TicketActivityLogger $activityLogger): RedirectResponse
    {
        $openStatus = TicketStatus::where('name', 'Open')->firstOrFail();

        $priority = TicketPriority::findOrFail($request->input('ticket_priority_id'));

        $dueAt = $request->input('due_at') ?: $priority->getDefaultDueAt();

        $ticket = Ticket::create([
            'ticket_no' => $this->generateTicketNo(),
            'requester_id' => $request->user()->id,
            'assignee_id' => null,
            'department_id' => $request->input('department_id'),
            'ticket_category_id' => $request->input('ticket_category_id'),
            'ticket_priority_id' => $request->input('ticket_priority_id'),
            'ticket_status_id' => $openStatus->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'due_at' => $dueAt,
        ]);

        $activityLogger->created($ticket, $request->user()->id);

        if ($ticket->due_at) {
            $activityLogger->dueDateSet(
                ticket: $ticket,
                userId: $request->user()->id,
                dueAt: $ticket->due_at->format('Y-m-d H:i')
            );
        }

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Ticket $ticket): View
    {
        $this->authorizeView($request, $ticket);

        $canViewInternalComments = in_array($request->user()->role?->name, ['admin', 'agent'], true);

        $ticket->load([
            'requester',
            'assignee',
            'department',
            'category',
            'priority',
            'status',
            'comments' => function ($query) use ($canViewInternalComments) {
                $query->with(['user', 'attachments'])
                    ->when(! $canViewInternalComments, function ($query) {
                        $query->where('is_internal', false);
                    })
                    ->oldest();
            },
            'activityLogs' => function ($query) {
                $query->with('user')->latest();
            },
            'attachments' => function ($query) {
                $query->with('uploader')->latest();
            }
        ]);

        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Ticket $ticket): View
    {
        $this->authorizeView($request, $ticket);

        $ticket->load([
            'requester',
            'assignee',
            'department',
            'category',
            'priority',
            'status'
        ]);

        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        $categories = TicketCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        $priorities = TicketPriority::where('is_active', true)
            ->orderBy('level')
            ->get();

        $statuses = TicketStatus::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $agents = User::whereHas('role', function ($query) {
                $query->whereIn('name', ['admin', 'agent']);
            })
            ->orderBy('name')
            ->get();

        return view('tickets.edit', compact(
            'ticket',
            'departments',
            'categories',
            'priorities',
            'statuses',
            'agents'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket, TicketActivityLogger $activityLogger): RedirectResponse
    {
        $this->authorizeManage($request, $ticket);

        $ticket->load([
            'assignee',
            'department',
            'category',
            'priority',
            'status',
        ]);

        $oldAssignee = $ticket->assignee?->name ?? 'Unassigned';
        $oldDepartment = $ticket->department?->name ?? 'Unassigned';
        $oldCategory = $ticket->category?->name;
        $oldPriority = $ticket->priority?->name;
        $oldStatus = $ticket->status?->name;
        $oldTitle = $ticket->title;
        $oldDueAt = $ticket->due_at?->format('Y-m-d H:i');

        $newStatus = TicketStatus::findOrFail($request->input('ticket_status_id'));

        $resolvedAt = $ticket->resolved_at;
        $closedAt = $ticket->closed_at;

        if (! $newStatus->is_closed) {
            $resolvedAt = null;
            $closedAt = null;
        }

        if ($newStatus->name === 'Resolved') {
            $resolvedAt = $resolvedAt ?? now();
            $closedAt = null;
        }

        if ($newStatus->name === 'Closed') {
            $resolvedAt = $resolvedAt ?? now();
            $closedAt = $closedAt ?? now();
        }

        if ($newStatus->name === 'Cancelled') {
            $closedAt = $closedAt ?? now();
        }

        $ticket->update([
            'assignee_id' => $request->input('assignee_id'),
            'department_id' => $request->input('department_id'),
            'ticket_category_id' => $request->input('ticket_category_id'),
            'ticket_priority_id' => $request->input('ticket_priority_id'),
            'ticket_status_id' => $newStatus->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'due_at' => $request->input('due_at'),
            'resolved_at' => $resolvedAt,
            'closed_at' => $closedAt,
        ]);
        
        $ticket->load([
            'assignee',
            'department',
            'category',
            'priority',
            'status',
        ]);

        $activityLogger->logIfChanged(
            ticket: $ticket,
            userId: $request->user()->id,
            field: 'title',
            oldValue: $oldTitle,
            newValue: $ticket->title
        );

        $activityLogger->logIfChanged(
            ticket: $ticket,
            userId: $request->user()->id,
            field: 'assignee',
            oldValue: $oldAssignee,
            newValue: $ticket->assignee?->name ?? 'Unassigned'
        );

        $activityLogger->logIfChanged(
            ticket: $ticket,
            userId: $request->user()->id,
            field: 'department',
            oldValue: $oldDepartment,
            newValue: $ticket->department?->name ?? 'Unassigned'
        );

        $activityLogger->logIfChanged(
            ticket: $ticket,
            userId: $request->user()->id,
            field: 'category',
            oldValue: $oldCategory,
            newValue: $ticket->category?->name
        );

        $activityLogger->logIfChanged(
            ticket: $ticket,
            userId: $request->user()->id,
            field: 'priority',
            oldValue: $oldPriority,
            newValue: $ticket->priority?->name
        );

        $activityLogger->logIfChanged(
            ticket: $ticket,
            userId: $request->user()->id,
            field: 'status',
            oldValue: $oldStatus,
            newValue: $ticket->status?->name
        );

        $activityLogger->logIfChanged(
            ticket: $ticket,
            userId: $request->user()->id,
            field: 'due_at',
            oldValue: $oldDueAt,
            newValue: $ticket->due_at?->format('Y-m-d H:i')
        );

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function authorizeManage(Request $request, Ticket $ticket): void
    {
        if ($request->user()->canManageTickets()) {
            return;
        }

        abort(403, 'You are not allowed to manage this ticket.');
    }

    private function generateTicketNo(): string
    {
        $prefix = 'TCK-' . now()->format('Ymd') . '-';

        $latestTicket = Ticket::where('ticket_no', 'like', $prefix . '%')
            ->orderByDesc('ticket_no')
            ->first();

        $nextNumber = 1;

        if ($latestTicket) {
            $lastNumber = (int) substr($latestTicket->ticket_no, -4);
            $nextNumber = $lastNumber + 1;
        }

        return $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }

    private function authorizeView(Request $request, Ticket $ticket): void
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return;
        }

        if ((int) $ticket->requester_id === (int) $user->id) {
            return;
        }

        if ($user->isAgent()) {
            if ($ticket->assignee_id === null || (int) $ticket->assignee_id === (int) $user->id) {
                return;
            }
        }

        abort(403, 'You are not allowed to view this ticket.');
    }

    public function resolve(Request $request, Ticket $ticket, TicketActivityLogger $activityLogger): RedirectResponse 
    {
        $this->authorizeManage($request, $ticket);

        $resolvedStatus = TicketStatus::where('name', 'Resolved')->first();

        if (! $resolvedStatus) {
            return redirect()
                ->route('tickets.show', $ticket)
                ->with('error', 'Resolved status was not found.');
        }

        if ($ticket->status?->name === 'Resolved') {
            return redirect()
                ->route('tickets.show', $ticket)
                ->with('error', 'This ticket is already resolved.');
        }

        $oldStatus = $ticket->status?->name;

        $ticket->update([
            'ticket_status_id' => $resolvedStatus->id,
            'resolved_at' => now(),
            'closed_at' => null,
        ]);

        $activityLogger->logIfChanged(
            ticket: $ticket,
            userId: $request->user()->id,
            field: 'status',
            oldValue: $oldStatus,
            newValue: 'Resolved'
        );

        $activityLogger->resolved($ticket, $request->user()->id);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket resolved successfully.');
    }

    public function close(Request $request, Ticket $ticket, TicketActivityLogger $activityLogger): RedirectResponse
    {
        $this->authorizeManage($request, $ticket);

        $closedStatus = TicketStatus::where('name', 'Closed')->first();

        if (! $closedStatus) {
            return redirect()
                ->route('tickets.show', $ticket)
                ->with('error', 'Closed status was not found.');
        }

        if ($ticket->status?->name === 'Closed') {
            return redirect()
                ->route('tickets.show', $ticket)
                ->with('error', 'This ticket is already closed.');
        }

        $oldStatus = $ticket->status?->name;

        $ticket->update([
            'ticket_status_id' => $closedStatus->id,
            'resolved_at' => $ticket->resolved_at ?? now(),
            'closed_at' => now(),
        ]);

        $activityLogger->logIfChanged(
            ticket: $ticket,
            userId: $request->user()->id,
            field: 'status',
            oldValue: $oldStatus,
            newValue: 'Closed'
        );

        $activityLogger->closed($ticket, $request->user()->id);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket closed successfully.');
    }

    public function reopen(Request $request, Ticket $ticket, TicketActivityLogger $activityLogger): RedirectResponse
    {
        $this->authorizeManage($request, $ticket);

        $openStatus = TicketStatus::where('name', 'Open')->first();

        if (! $openStatus) {
            return redirect()
                ->route('tickets.show', $ticket)
                ->with('error', 'Open status was not found.');
        }

        if (! $ticket->status?->is_closed) {
            return redirect()
                ->route('tickets.show', $ticket)
                ->with('error', 'Only closed tickets can be reopened.');
        }

        $oldStatus = $ticket->status?->name;

        $ticket->update([
            'ticket_status_id' => $openStatus->id,
            'resolved_at' => null,
            'closed_at' => null,
        ]);

        $activityLogger->logIfChanged(
            ticket: $ticket,
            userId: $request->user()->id,
            field: 'status',
            oldValue: $oldStatus,
            newValue: 'Open'
        );

        $activityLogger->reopened($ticket, $request->user()->id);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket reopened successfully.');
    }

    public function assignToMe(Request $request, Ticket $ticket, TicketActivityLogger $activityLogger): RedirectResponse
    {
        $this->authorizeManage($request, $ticket);

        $user = $request->user();

        $ticket->load('assignee');

        $oldAssignee = $ticket->assignee?->name ?? 'Unassigned';

        if ((int) $ticket->assignee_id === (int) $user->id) {
            return redirect()
                ->route('tickets.show', $ticket)
                ->with('error', 'This ticket is already assigned to you.');
        }

        $ticket->update([
            'assignee_id' => $user->id,
        ]);

        $activityLogger->logIfChanged(
            ticket: $ticket,
            userId: $user->id,
            field: 'assignee',
            oldValue: $oldAssignee,
            newValue: $user->name
        );

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket assigned to you successfully.');
    }

    public function unassign(Request $request, Ticket $ticket, TicketActivityLogger $activityLogger): RedirectResponse
    {
        $this->authorizeManage($request, $ticket);

        $ticket->load('assignee');

        if ($ticket->assignee_id === null) {
            return redirect()
                ->route('tickets.show', $ticket)
                ->with('error', 'This ticket is already unassigned.');
        }

        $oldAssignee = $ticket->assignee?->name ?? 'Unassigned';

        $ticket->update([
            'assignee_id' => null,
        ]);

        $activityLogger->logIfChanged(
            ticket: $ticket,
            userId: $request->user()->id,
            field: 'assignee',
            oldValue: $oldAssignee,
            newValue: 'Unassigned'
        );

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket unassigned successfully.');
    }

    private function getTicketListView(Request $request, $query, string $pageTitle, string $pageDescription): View
    {
        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($q) use ($search) {
                $q->where('ticket_no', 'ilike', "%{$search}%")
                    ->orWhere('title', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('status_id')) {
            $query->where('ticket_status_id', $request->input('status_id'));
        }

        if ($request->filled('priority_id')) {
            $query->where('ticket_priority_id', $request->input('priority_id'));
        }

        if ($request->filled('category_id')) {
            $query->where('ticket_category_id', $request->input('category_id'));
        }

        if ($request->boolean('overdue')) {
            $query->whereNotNull('due_at')
                ->where('due_at', '<', now())
                ->whereHas('status', function ($q) {
                    $q->where('is_closed', false);
                });
        }

        $tickets = $query->latest()
            ->paginate(10)
            ->withQueryString();

        $statuses = TicketStatus::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $priorities = TicketPriority::where('is_active', true)
            ->orderBy('level')
            ->get();

        $categories = TicketCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('tickets.index', compact(
            'tickets',
            'statuses',
            'priorities',
            'categories',
            'pageTitle',
            'pageDescription'
        ));
    }

    public function myTickets(Request $request): View
    {
        $query = Ticket::with([
            'requester',
            'assignee',
            'department',
            'category',
            'priority',
            'status',
        ])->where('requester_id', $request->user()->id);

        return $this->getTicketListView(
            request: $request,
            query: $query,
            pageTitle: 'My Tickets',
            pageDescription: 'Tickets that you have submitted.'
        );
    }

    public function assignedToMe(Request $request): View
    {
        if (! $request->user()->canManageTickets()) {
            abort(403, 'You are not allowed to access assigned tickets.');
        }

        $query = Ticket::with([
            'requester',
            'assignee',
            'department',
            'category',
            'priority',
            'status',
        ])->where('assignee_id', $request->user()->id);

        return $this->getTicketListView(
            request: $request,
            query: $query,
            pageTitle: 'Assigned to Me',
            pageDescription: 'Tickets currently assigned to you.'
        );
    }

    public function unassigned(Request $request): View
    {
        if (! $request->user()->canManageTickets()) {
            abort(403, 'You are not allowed to access unassigned tickets.');
        }

        $query = Ticket::with([
            'requester',
            'assignee',
            'department',
            'category',
            'priority',
            'status',
        ])->whereNull('assignee_id');

        return $this->getTicketListView(
            request: $request,
            query: $query,
            pageTitle: 'Unassigned Queue',
            pageDescription: 'Tickets that have not been assigned to any agent yet.'
        );
    }

    public function bulkAction(Request $request, TicketActivityLogger $activityLogger): RedirectResponse
    {
        if (! $request->user()->canManageTickets()) {
            abort(403, 'You are not allowed to manage tickets.');
        }

        $validated = $request->validate([
            'ticket_ids' => ['required', 'array', 'min:1'],
            'ticket_ids.*' => ['integer', 'exists:tickets,id'],
            'action' => ['required', 'string', 'in:assign_to_me,close'],
        ]);

        $user = $request->user();

        $tickets = Ticket::with(['assignee', 'status'])
            ->whereIn('id', $validated['ticket_ids'])
            ->get();

        if ($tickets->isEmpty()) {
            return redirect()
                ->back()
                ->with('error', 'No tickets selected.');
        }

        if ($validated['action'] === 'assign_to_me') {
            foreach ($tickets as $ticket) {
                $oldAssignee = $ticket->assignee?->name ?? 'Unassigned';

                if ((int) $ticket->assignee_id === (int) $user->id) {
                    continue;
                }

                $ticket->update([
                    'assignee_id' => $user->id,
                ]);

                $activityLogger->logIfChanged(
                    ticket: $ticket,
                    userId: $user->id,
                    field: 'assignee',
                    oldValue: $oldAssignee,
                    newValue: $user->name
                );
            }

            return redirect()
                ->back()
                ->with('success', 'Selected tickets assigned to you successfully.');
        }

        if ($validated['action'] === 'close') {
            $closedStatus = TicketStatus::where('name', 'Closed')->firstOrFail();

            foreach ($tickets as $ticket) {
                $oldStatus = $ticket->status?->name ?? '-';

                if ((int) $ticket->ticket_status_id === (int) $closedStatus->id) {
                    continue;
                }

                $ticket->update([
                    'ticket_status_id' => $closedStatus->id,
                    'resolved_at' => $ticket->resolved_at ?? now(),
                    'closed_at' => now(),
                ]);

                $activityLogger->logIfChanged(
                    ticket: $ticket,
                    userId: $user->id,
                    field: 'status',
                    oldValue: $oldStatus,
                    newValue: 'Closed'
                );

                $activityLogger->closed($ticket, $user->id);
            }

            return redirect()
                ->back()
                ->with('success', 'Selected tickets closed successfully.');
        }

        return redirect()
            ->back()
            ->with('error', 'Invalid bulk action.');
    }
}