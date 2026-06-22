<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assignee_id' => ['nullable', 'exists:users,id'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'ticket_category_id' => ['required', 'exists:ticket_categories,id'],
            'ticket_priority_id' => ['required', 'exists:ticket_priorities,id'],
            'ticket_status_id' => ['required', 'exists:ticket_statuses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
            'due_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'assignee_id.exists' => 'Selected assignee is invalid.',

            'department_id.required' => 'Please select a department.',
            'department_id.exists' => 'Selected department is invalid.',

            'ticket_category_id.required' => 'Please select a category.',
            'ticket_category_id.exists' => 'Selected category is invalid.',

            'ticket_priority_id.required' => 'Please select a priority.',
            'ticket_priority_id.exists' => 'Selected priority is invalid.',

            'ticket_status_id.required' => 'Please select a status.',
            'ticket_status_id.exists' => 'Selected status is invalid.',

            'title.required' => 'Please enter a ticket title.',
            'title.string' => 'Ticket title must be valid text.',
            'title.max' => 'Ticket title must not exceed 255 characters.',

            'description.required' => 'Please describe the issue.',
            'description.string' => 'Description must be valid text.',

            'due_at.date' => 'Due date must be a valid date and time.',
        ];
    }
}