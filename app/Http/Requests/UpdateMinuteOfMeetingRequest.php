<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMinuteOfMeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'meeting_id' => 'sometimes|required|exists:meetings,id',
            'room_id' => 'sometimes|required|exists:rooms,id',
            'created_by' => 'sometimes|required|exists:users,id',
            'assigned_to' => 'sometimes|required|exists:users,id',
            'content' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'in:draft,published,completed',
            'issues' => 'nullable|string',
            'deadline' => 'nullable|date',
        ];
    }
}
