<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow all users for now
    }

    public function rules(): array
    {
        return [
            'meeting_id' => 'required|exists:meetings,id',
            'user_id' => 'required|exists:users,id',
        ];
    }
}
