@extends('layouts.app')

@section('title', '404 Not Found - Helpdesk Ticket System')

@section('content')
<div class="error-page">
    <div class="error-card">
        <div class="error-icon error-icon-info">
            <i class="bi bi-compass"></i>
        </div>

        <div class="error-code">
            404
        </div>

        <h1 class="error-title">
            Page Not Found
        </h1>

        <p class="error-text">
            The page you are looking for does not exist or may have been moved.
        </p>

        <div class="error-actions">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                <i class="bi bi-speedometer2 me-1"></i>
                Back to Dashboard
            </a>

            <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-ticket-perforated me-1"></i>
                View Tickets
            </a>
        </div>
    </div>
</div>
@endsection