<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'meeting_id' => 'sometimes|required|exists:meetings,id',
            'user_id' => 'sometimes|required|exists:users,id',
        ];
    }
}
