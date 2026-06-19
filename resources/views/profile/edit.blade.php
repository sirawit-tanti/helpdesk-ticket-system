@extends('layouts.app')

@section('title', 'Profile - Helpdesk Ticket System')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="h3 mb-1">Profile</h1>
        <p class="text-muted mb-0">
            Manage your account information and password.
        </p>
    </div>

    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
        Back to Dashboard
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
                Account Information
            </div>

            <div class="card-body">
                <div class="profile-avatar-block mb-4">
                    <div class="profile-avatar profile-avatar-{{ $user->role?->name ?? 'user' }}">
                        {{ $user->initial }}
                    </div>

                    <div>
                        <div class="profile-avatar-name">
                            {{ $user->name }}
                        </div>

                        <div class="profile-avatar-email">
                            {{ $user->email }}
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="text-muted small">Role</div>
                    <div>
                        <span class="badge bg-{{ $user->role_color }}">
                            {{ $user->role?->display_name ?? '-' }}
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="text-muted small">Department</div>
                    <div class="fw-semibold">
                        {{ $user->department?->name ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-muted small">Status</div>

                    @if($user->is_active)
                    <span class="badge bg-success">Active</span>
                    @else
                    <span class="badge bg-secondary">Inactive</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                Edit Profile
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}" data-loading-form>
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="name" class="form-label">
                            Name
                        </label>

                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            class="form-control @error('name') is-invalid @enderror" required>

                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                Change Password
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">
                            Current Password
                        </label>

                        <div class="input-group">
                            <input type="password" name="current_password" id="current_password"
                                class="form-control @error('current_password', 'passwordUpdate') is-invalid @enderror"
                                required>

                            <button type="button" class="btn btn-outline-secondary password-toggle-btn"
                                data-password-toggle="current_password">
                                Show
                            </button>

                            @error('current_password', 'passwordUpdate')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            New Password
                        </label>

                        <div class="input-group">
                            <input type="password" name="password" id="password"
                                class="form-control @error('password', 'passwordUpdate') is-invalid @enderror" required>

                            <button type="button" class="btn btn-outline-secondary password-toggle-btn"
                                data-password-toggle="password">
                                Show
                            </button>

                            @error('password', 'passwordUpdate')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">
                            Confirm New Password
                        </label>

                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" required>

                            <button type="button" class="btn btn-outline-secondary password-toggle-btn"
                                data-password-toggle="password_confirmation">
                                Show
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning">
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection