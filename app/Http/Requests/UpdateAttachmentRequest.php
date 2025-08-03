<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttachmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file_name' => 'sometimes|required|string|max:255',
            'file_path' => 'sometimes|required|string|max:500',
            'file_type' => 'sometimes|required|string|max:100',
            'uploaded_by' => 'sometimes|required|exists:users,id',
            'minute_of_meeting_id' => 'nullable|exists:minute_of_meetings,id',
            'uploaded_at' => 'nullable|date',
        ];
    }
}
