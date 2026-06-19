@extends('layouts.app')

@section('title', 'Login - Helpdesk Ticket System')

@section('content')
<div class="login-page">
    <div class="login-shell">
        <div class="login-hero">
            <div class="login-brand-badge">
                <span class="login-brand-icon">✓</span>
                Helpdesk Ticket System
            </div>

            <h1 class="login-title">
                Manage support tickets with clarity.
            </h1>

            <p class="login-description">
                Track requests, assign agents, monitor SLA, and review reports from one simple dashboard.
            </p>

            <div class="login-feature-list">
                <div class="login-feature-item">
                    <span>01</span>
                    Ticket workflow and assignment
                </div>

                <div class="login-feature-item">
                    <span>02</span>
                    SLA tracking and overdue visibility
                </div>

                <div class="login-feature-item">
                    <span>03</span>
                    Reports with CSV and PDF export
                </div>
            </div>
        </div>

        <div class="login-card">
            <div class="mb-4">
                <h2 class="login-card-title">
                    Welcome back
                </h2>

                <p class="login-card-text">
                    Sign in to continue to your workspace.
                </p>
            </div>

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">
                        Email Address
                    </label>

                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror" placeholder="admin@example.com"
                        required autofocus>

                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        Password
                    </label>

                    <div class="input-group">
                        <input type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Enter your password" required>

                        <button type="button" class="btn btn-outline-secondary password-toggle-btn"
                            data-password-toggle="password">
                            Show
                        </button>

                        @error('password')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        Sign In
                    </button>
                </div>
            </form>

            <div class="login-demo-box mt-4">
                <div class="text-muted small mb-2">
                    Demo Account
                </div>

                <div class="d-flex justify-content-between gap-3 small">
                    <span class="fw-semibold">admin@example.com</span>
                    <span class="text-muted">password</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection