@extends('layouts.app')

@section('title', 'Create Department - Helpdesk Ticket System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Create Department</h1>
        <p class="text-muted mb-0">
            Add a new company department.
        </p>
    </div>

    <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">
        Back to Departments
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.departments.store') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">
                    Name <span class="text-danger">*</span>
                </label>

                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="form-control @error('name') is-invalid @enderror" required>

                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">
                    Description
                </label>

                <textarea name="description" id="description" rows="4"
                    class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

                @error('description')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input"
                    @checked(old('is_active', true))>

                <label for="is_active" class="form-check-label">
                    Active
                </label>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn btn-primary">
                    Create Department
                </button>
            </div>
        </form>
    </div>
</div>
@endsection