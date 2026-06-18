@extends('layouts.app')

@section('title', 'Users - Helpdesk Ticket System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Users</h1>
        <p class="text-muted mb-0">
            Manage system users, roles, and departments.
        </p>
    </div>

    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        Create User
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        @if($users->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="fw-semibold">
                            {{ $user->name }}
                        </td>

                        <td>
                            {{ $user->email }}
                        </td>

                        <td>
                            {{ $user->role?->display_name ?? '-' }}
                        </td>

                        <td>
                            {{ $user->department?->name ?? '-' }}
                        </td>

                        <td>
                            @if($user->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>

                        <td>
                            {{ $user->created_at->format('Y-m-d H:i') }}
                        </td>

                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-outline-danger" @disabled(auth()->id()
                                        === $user->id)
                                        >
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

        {{ $users->links() }}
        @else
        <div class="text-center py-5">
            <h2 class="h5">No users found</h2>
            <p class="text-muted mb-3">
                Create your first user to start assigning tickets.
            </p>

            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                Create User
            </a>
        </div>
        @endif
    </div>
</div>
@endsection