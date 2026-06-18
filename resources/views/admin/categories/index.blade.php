@extends('layouts.app')

@section('title', 'Categories - Helpdesk Ticket System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Categories</h1>
        <p class="text-muted mb-0">
            Manage ticket categories used by support requests.
        </p>
    </div>

    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        Create Category
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        @if($categories->count())
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
                    @foreach($categories as $category)
                    <tr>
                        <td class="fw-semibold">
                            {{ $category->name }}
                        </td>

                        <td>
                            {{ $category->description ?? '-' }}
                        </td>

                        <td>
                            @if($category->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>

                        <td>
                            {{ $category->created_at->format('Y-m-d H:i') }}
                        </td>

                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this category?');">
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

        {{ $categories->links() }}
        @else
        <div class="text-center py-5">
            <h2 class="h5">No categories found</h2>
            <p class="text-muted mb-3">
                Create your first category to classify support tickets.
            </p>

            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                Create Category
            </a>
        </div>
        @endif
    </div>
</div>
@endsection