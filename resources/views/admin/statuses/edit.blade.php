@extends('layouts.app')

@section('title', 'Edit Status - Helpdesk Ticket System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Edit Status</h1>
        <p class="text-muted mb-0">
            Update workflow status details.
        </p>
    </div>

    <a href="{{ route('admin.statuses.index') }}" class="btn btn-outline-secondary">
        Back to Statuses
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.statuses.update', $status) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">
                    Name <span class="text-danger">*</span>
                </label>

                <input type="text" name="name" id="name" value="{{ old('name', $status->name) }}"
                    class="form-control @error('name') is-invalid @enderror" required>

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

                <input type="number" name="sort_order" id="sort_order"
                    value="{{ old('sort_order', $status->sort_order) }}"
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
                    @foreach(['secondary', 'primary', 'success', 'danger', 'warning', 'info', 'dark'] as $color)
                    <option value="{{ $color }}" @selected(old('color', $status->color) === $color)
                        >
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

            <div class="mb-3">
                <div class="form-label">Preview</div>
                <span class="badge bg-{{ old('color', $status->color) }}">
                    {{ old('name', $status->name) }}
                </span>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_closed" id="is_closed" value="1" class="form-check-input"
                    @checked(old('is_closed', $status->is_closed))
                >

                <label for="is_closed" class="form-check-label">
                    Closed status
                </label>

                <div class="form-text">
                    Closed statuses are treated as completed or ended workflow states.
                </div>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input"
                    @checked(old('is_active', $status->is_active))
                >

                <label for="is_active" class="form-check-label">
                    Active
                </label>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.statuses.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn btn-primary">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection