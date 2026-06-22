@extends('layouts.app')

@section('title', '403 Forbidden - Helpdesk Ticket System')

@section('content')
<div class="error-page">
    <div class="error-card">
        <div class="error-icon error-icon-warning">
            <i class="bi bi-shield-lock"></i>
        </div>

        <div class="error-code">
            403
        </div>

        <h1 class="error-title">
            Access Denied
        </h1>

        <p class="error-text">
            You do not have permission to access this page or perform this action.
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