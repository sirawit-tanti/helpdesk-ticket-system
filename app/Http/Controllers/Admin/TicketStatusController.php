<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketStatusController extends Controller
{
    public function index(): View
    {
        $statuses = TicketStatus::orderBy('sort_order')
            ->paginate(10);

        return view('admin.statuses.index', compact('statuses'));
    }

    public function create(): View
    {
        return view('admin.statuses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:ticket_statuses,name'],
            'color' => ['required', 'string', 'max:50'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999'],
            'is_closed' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        TicketStatus::create([
            'name' => $validated['name'],
            'color' => $validated['color'],
            'sort_order' => $validated['sort_order'],
            'is_closed' => $request->boolean('is_closed'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.statuses.index')
            ->with('success', 'Status created successfully.');
    }

    public function edit(TicketStatus $status): View
    {
        return view('admin.statuses.edit', compact('status'));
    }

    public function update(Request $request, TicketStatus $status): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:ticket_statuses,name,' . $status->id],
            'color' => ['required', 'string', 'max:50'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999'],
            'is_closed' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $status->update([
            'name' => $validated['name'],
            'color' => $validated['color'],
            'sort_order' => $validated['sort_order'],
            'is_closed' => $request->boolean('is_closed'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.statuses.index')
            ->with('success', 'Status updated successfully.');
    }

    public function destroy(TicketStatus $status): RedirectResponse
    {
        if ($status->tickets()->exists()) {
            return redirect()
                ->route('admin.statuses.index')
                ->with('error', 'This status is currently used by tickets and cannot be deleted.');
        }

        $status->delete();

        return redirect()
            ->route('admin.statuses.index')
            ->with('success', 'Status deleted successfully.');
    }
}