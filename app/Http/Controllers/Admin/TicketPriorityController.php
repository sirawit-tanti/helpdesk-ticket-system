<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketPriority;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketPriorityController extends Controller
{
    public function index(): View
    {
        $priorities = TicketPriority::orderBy('level')
            ->paginate(10);

        return view('admin.priorities.index', compact('priorities'));
    }

    public function create(): View
    {
        return view('admin.priorities.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:ticket_priorities,name'],
            'level' => ['required', 'integer', 'min:1', 'max:99'],
            'sla_hours' => ['nullable', 'integer', 'min:1', 'max:9999'],
            'color' => ['required', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        TicketPriority::create([
            'name' => $validated['name'],
            'level' => $validated['level'],
            'sla_hours' => $validated['sla_hours'] ?? null,
            'color' => $validated['color'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.priorities.index')
            ->with('success', 'Priority created successfully.');
    }

    public function edit(TicketPriority $priority): View
    {
        return view('admin.priorities.edit', compact('priority'));
    }

    public function update(Request $request, TicketPriority $priority): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:ticket_priorities,name,' . $priority->id],
            'level' => ['required', 'integer', 'min:1', 'max:99'],
            'sla_hours' => ['nullable', 'integer', 'min:1', 'max:9999'],
            'color' => ['required', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $priority->update([
            'name' => $validated['name'],
            'level' => $validated['level'],
            'sla_hours' => $validated['sla_hours'] ?? null,
            'color' => $validated['color'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.priorities.index')
            ->with('success', 'Priority updated successfully.');
    }

    public function destroy(TicketPriority $priority): RedirectResponse
    {
        if ($priority->tickets()->exists()) {
            return redirect()
                ->route('admin.priorities.index')
                ->with('error', 'This priority is currently used by tickets and cannot be deleted.');
        }

        $priority->delete();

        return redirect()
            ->route('admin.priorities.index')
            ->with('success', 'Priority deleted successfully.');
    }
}