<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttachmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file_name' => 'required|string|max:255',
            'file_path' => 'required|string|max:500',
            'file_type' => 'required|string|max:100',
            'uploaded_by' => 'required|exists:users,id',
            'minute_of_meeting_id' => 'nullable|exists:minute_of_meetings,id',
            'uploaded_at' => 'nullable|date',
        ];
    }
}
