<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'min:2', 'max:5000'],
            'is_internal' => ['nullable', 'boolean'],

            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => [
                'file',
                'max:5120',
                'mimes:jpg,jpeg,png,pdf,txt,log,doc,docx,xls,xlsx',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'Please enter a reply or internal note.',
            'message.string' => 'Message must be valid text.',
            'message.max' => 'Message must not exceed 5,000 characters.',

            'is_internal.boolean' => 'Internal note option is invalid.',

            'attachments.array' => 'Attachments must be uploaded as a valid file list.',
            'attachments.*.file' => 'Each attachment must be a valid file.',
            'attachments.*.max' => 'Each attachment must not exceed 10 MB.',
        ];
    }
}