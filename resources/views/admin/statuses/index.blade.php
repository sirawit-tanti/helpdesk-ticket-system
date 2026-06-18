@extends('layouts.app')

@section('title', 'Statuses - Helpdesk Ticket System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Statuses</h1>
        <p class="text-muted mb-0">
            Manage ticket workflow statuses.
        </p>
    </div>

    <a href="{{ route('admin.statuses.create') }}" class="btn btn-primary">
        Create Status
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        @if($statuses->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Sort</th>
                        <th>Name</th>
                        <th>Color</th>
                        <th>Preview</th>
                        <th>Closed?</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($statuses as $status)
                    <tr>
                        <td>
                            {{ $status->sort_order }}
                        </td>

                        <td class="fw-semibold">
                            {{ $status->name }}
                        </td>

                        <td>
                            <code>{{ $status->color }}</code>
                        </td>

                        <td>
                            <span class="badge bg-{{ $status->color }}">
                                {{ $status->name }}
                            </span>
                        </td>

                        <td>
                            @if($status->is_closed)
                            <span class="badge bg-success">Yes</span>
                            @else
                            <span class="badge bg-secondary">No</span>
                            @endif
                        </td>

                        <td>
                            @if($status->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>

                        <td>
                            {{ $status->created_at->format('Y-m-d H:i') }}
                        </td>

                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('admin.statuses.edit', $status) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('admin.statuses.destroy', $status) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this status?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $statuses->links() }}
        @else
        <div class="text-center py-5">
            <h2 class="h5">No statuses found</h2>
            <p class="text-muted mb-3">
                Create your first status to define the ticket workflow.
            </p>

            <a href="{{ route('admin.statuses.create') }}" class="btn btn-primary">
                Create Status
            </a>
        </div>
        @endif
    </div>
</div>
@endsection