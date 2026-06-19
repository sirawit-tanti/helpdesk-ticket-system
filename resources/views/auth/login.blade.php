@extends('layouts.app')

@section('title', 'Login - Helpdesk Ticket System')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h1 class="h4 mb-1 text-center">Helpdesk Ticket System</h1>
                <p class="text-muted text-center mb-4">
                    Sign in to continue
                </p>

                @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>

                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" required autofocus>

                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>

                        <div class="input-group">
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror" required>

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

                    <div class="form-check mb-3">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input" value="1">

                        <label for="remember" class="form-check-label">
                            Remember me
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Login
                    </button>
                </form>

                <div class="mt-4 small text-muted text-center">
                    Demo: admin@example.com / password
                </div>
            </div>
        </div>
    </div>
</div>
@endsection