@extends('layouts.app')

@section('title', 'Edit Priority - Helpdesk Ticket System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Edit Priority</h1>
        <p class="text-muted mb-0">
            Update priority details.
        </p>
    </div>

    <a href="{{ route('admin.priorities.index') }}" class="btn btn-outline-secondary">
        Back to Priorities
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.priorities.update', $priority) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">
                    Name <span class="text-danger">*</span>
                </label>

                <input type="text" name="name" id="name" value="{{ old('name', $priority->name) }}"
                    class="form-control @error('name') is-invalid @enderror" required>

                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="level" class="form-label">
                    Level <span class="text-danger">*</span>
                </label>

                <input type="number" name="level" id="level" value="{{ old('level', $priority->level) }}"
                    class="form-control @error('level') is-invalid @enderror" min="1" max="99" required>

                <div class="form-text">
                    Higher level means higher urgency.
                </div>

                @error('level')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="sla_hours" class="form-label">
                    SLA Hours
                </label>

                <input type="number" name="sla_hours" id="sla_hours"
                    value="{{ old('sla_hours', $priority->sla_hours) }}"
                    class="form-control @error('sla_hours') is-invalid @enderror" min="1" max="9999"
                    placeholder="Example: 24">

                <div class="form-text">
                    Leave empty if this priority should not automatically set a due date.
                </div>

                @error('sla_hours')
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
                    <option value="{{ $color }}" @selected(old('color', $priority->color) === $color)
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
                <span class="badge bg-{{ old('color', $priority->color) }}">
                    {{ old('name', $priority->name) }}
                </span>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input"
                    @checked(old('is_active', $priority->is_active))
                >

                <label for="is_active" class="form-check-label">
                    Active
                </label>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.priorities.index') }}" class="btn btn-outline-secondary">
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