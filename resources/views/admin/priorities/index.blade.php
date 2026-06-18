@extends('layouts.app')

@section('title', 'Priorities - Helpdesk Ticket System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Priorities</h1>
        <p class="text-muted mb-0">
            Manage ticket priority levels and badge colors.
        </p>
    </div>

    <a href="{{ route('admin.priorities.create') }}" class="btn btn-primary">
        Create Priority
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        @if($priorities->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Level</th>
                        <th>SLA</th>
                        <th>Color</th>
                        <th>Preview</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($priorities as $priority)
                    <tr>
                        <td class="fw-semibold">
                            {{ $priority->name }}
                        </td>

                        <td>
                            {{ $priority->level }}
                        </td>

                        <td>
                            @if($priority->sla_hours)
                            {{ $priority->sla_hours }} hours
                            @else
                            <span class="text-muted">No SLA</span>
                            @endif
                        </td>

                        <td>
                            <code>{{ $priority->color }}</code>
                        </td>

                        <td>
                            <span class="badge bg-{{ $priority->color }}">
                                {{ $priority->name }}
                            </span>
                        </td>

                        <td>
                            @if($priority->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>

                        <td>
                            {{ $priority->created_at->format('Y-m-d H:i') }}
                        </td>

                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('admin.priorities.edit', $priority) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('admin.priorities.destroy', $priority) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this priority?');">
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

        {{ $priorities->links() }}
        @else
        <div class="text-center py-5">
            <h2 class="h5">No priorities found</h2>
            <p class="text-muted mb-3">
                Create your first priority to classify ticket urgency.
            </p>

            <a href="{{ route('admin.priorities.create') }}" class="btn btn-primary">
                Create Priority
            </a>
        </div>
        @endif
    </div>
</div>
@endsection