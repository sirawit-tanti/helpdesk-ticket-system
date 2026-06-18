@extends('layouts.app')

@section('title', 'Create User - Helpdesk Ticket System')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="h3 mb-1">Create User</h1>
        <p class="text-muted mb-0">
            Add a new system user.
        </p>
    </div>

    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i>
        Back to Users
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
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

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">
                        Email <span class="text-danger">*</span>
                    </label>

                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror" required>

                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="role_id" class="form-label">
                        Role <span class="text-danger">*</span>
                    </label>

                    <select name="role_id" id="role_id" class="form-select @error('role_id') is-invalid @enderror"
                        required>
                        <option value="">Select role</option>

                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" @selected(old('role_id')==$role->id)>
                            {{ $role->display_name }}
                        </option>
                        @endforeach
                    </select>

                    @error('role_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="department_id" class="form-label">
                        Department
                    </label>

                    <select name="department_id" id="department_id"
                        class="form-select @error('department_id') is-invalid @enderror">
                        <option value="">Select department</option>

                        @foreach($departments as $department)
                        <option value="{{ $department->id }}" @selected(old('department_id')==$department->id)>
                            {{ $department->name }}
                        </option>
                        @endforeach
                    </select>

                    @error('department_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">
                        Password <span class="text-danger">*</span>
                    </label>

                    <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror" required>

                    @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label">
                        Confirm Password <span class="text-danger">*</span>
                    </label>

                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                        required>
                </div>
            </div>

            <div class="form-check form-switch mb-4">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input"
                    role="switch" @checked(old('is_active', true))>

                <label for="is_active" class="form-check-label">
                    Active
                </label>
            </div>

            <hr class="mb-4">

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="bi bi-check-lg"></i>
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
