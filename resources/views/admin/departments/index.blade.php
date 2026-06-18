@extends('layouts.app')

@section('title', 'Departments - Helpdesk Ticket System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Departments</h1>
        <p class="text-muted mb-0">
            Manage company departments used by users and tickets.
        </p>
    </div>

    <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
        Create Department
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        @if($departments->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($departments as $department)
                    <tr>
                        <td class="fw-semibold">
                            {{ $department->name }}
                        </td>

                        <td>
                            {{ $department->description ?? '-' }}
                        </td>

                        <td>
                            @if($department->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>

                        <td>
                            {{ $department->created_at->format('Y-m-d H:i') }}
                        </td>

                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('admin.departments.edit', $department) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('admin.departments.destroy', $department) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this department?');">
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

        {{ $departments->links() }}
        @else
        <div class="text-center py-5">
            <h2 class="h5">No departments found</h2>
            <p class="text-muted mb-3">
                Create your first department to organize users and tickets.
            </p>

            <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
                Create Department
            </a>
        </div>
        @endif
    </div>
</div>
@endsection