@extends('layouts.app')

@section('title', 'Create Ticket - Helpdesk Ticket System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Create Ticket</h1>
        <p class="text-muted mb-0">
            Submit a new support request.
        </p>
    </div>

    <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">
        Back to Tickets
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('tickets.store') }}" data-loading-form>
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>

                <input type="text" name="title" id="title" value="{{ old('title') }}"
                    class="form-control @error('title') is-invalid @enderror"
                    placeholder="Example: Cannot access company email" required>

                @error('title')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="ticket_category_id" class="form-label">
                        Category <span class="text-danger">*</span>
                    </label>

                    <select name="ticket_category_id" id="ticket_category_id"
                        class="form-select @error('ticket_category_id') is-invalid @enderror" required>
                        <option value="">Select category</option>

                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('ticket_category_id')==$category->id)
                            >
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>

                    @error('ticket_category_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="ticket_priority_id" class="form-label">
                        Priority <span class="text-danger">*</span>
                    </label>

                    <select name="ticket_priority_id" id="ticket_priority_id"
                        class="form-select @error('ticket_priority_id') is-invalid @enderror" required>
                        <option value="">Select priority</option>

                        @foreach($priorities as $priority)
                        <option value="{{ $priority->id }}" @selected(old('ticket_priority_id')==$priority->id)
                            >
                            {{ $priority->name }}
                        </option>
                        @endforeach
                    </select>

                    @error('ticket_priority_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="department_id" class="form-label">
                        Department
                    </label>

                    <select name="department_id" id="department_id"
                        class="form-select @error('department_id') is-invalid @enderror">
                        <option value="">Select department</option>

                        @foreach($departments as $department)
                        <option value="{{ $department->id }}" @selected(old('department_id')==$department->id)
                            >
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

            <div class="mb-3">
                <label for="description" class="form-label">
                    Description <span class="text-danger">*</span>
                </label>

                <textarea name="description" id="description" rows="6"
                    class="form-control @error('description') is-invalid @enderror"
                    placeholder="Please describe the issue, steps to reproduce, and any error messages."
                    required>{{ old('description') }}</textarea>

                @error('description')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="due_at" class="form-label">
                    Due Date
                </label>

                <input type="datetime-local" name="due_at" id="due_at" value="{{ old('due_at') }}"
                    class="form-control @error('due_at') is-invalid @enderror">

                <div class="form-text">
                    Leave empty to automatically set the due date using the selected priority SLA.
                </div>

                @error('due_at')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn btn-primary">
                    Submit Ticket
                </button>
            </div>
        </form>
    </div>
</div>
@endsection