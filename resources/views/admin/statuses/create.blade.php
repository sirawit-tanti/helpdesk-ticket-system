@extends('layouts.app')

@section('title', 'Create Status - Helpdesk Ticket System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Create Status</h1>
        <p class="text-muted mb-0">
            Add a new ticket workflow status.
        </p>
    </div>

    <a href="{{ route('admin.statuses.index') }}" class="btn btn-outline-secondary">
        Back to Statuses
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.statuses.store') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">
                    Name <span class="text-danger">*</span>
                </label>

                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="form-control @error('name') is-invalid @enderror" placeholder="Example: Waiting for User"
                    required>

                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="sort_order" class="form-label">
                    Sort Order <span class="text-danger">*</span>
                </label>

                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}"
                    class="form-control @error('sort_order') is-invalid @enderror" min="0" max="999" required>

                <div class="form-text">
                    Lower number appears earlier in dropdowns.
                </div>

                @error('sort_order')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="color" class="form-label">
                    Bootstrap Badge Color <span class="text-danger">*</span>
                </label>

                <select name="color" id="color" class="form-select @error('color') is-invalid @enderror" required>
                    <option value="">Select color</option>

                    @foreach(['secondary', 'primary', 'success', 'danger', 'warning', 'info', 'dark'] as $color)
                    <option value="{{ $color }}" @selected(old('color')===$color)>
                        {{ ucfirst($color) }}
                    </option>
                    @endforeach
                </select>

                @error('color')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_closed" id="is_closed" value="1" class="form-check-input"
                    @checked(old('is_closed'))>

                <label for="is_closed" class="form-check-label">
                    Closed status
                </label>

                <div class="form-text">
                    Closed statuses are treated as completed or ended workflow states.
                </div>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input"
                    @checked(old('is_active', true))>

                <label for="is_active" class="form-check-label">
                    Active
                </label>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.statuses.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn btn-primary">
                    Create Status
                </button>
            </div>
        </form>
    </div>
</div>
@endsection