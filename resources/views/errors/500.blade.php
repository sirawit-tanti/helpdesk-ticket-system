@extends('layouts.app')

@section('title', '500 Server Error - Helpdesk Ticket System')

@section('content')
<div class="error-page">
    <div class="error-card">
        <div class="error-icon error-icon-danger">
            <i class="bi bi-exclamation-triangle"></i>
        </div>

        <div class="error-code">
            500
        </div>

        <h1 class="error-title">
            Something Went Wrong
        </h1>

        <p class="error-text">
            An unexpected error occurred. Please try again later or contact the administrator.
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