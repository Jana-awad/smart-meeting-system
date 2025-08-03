<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_id'       => 'sometimes|required|exists:rooms,id',
            'organized_by'  => 'sometimes|required|exists:users,id',
            'booking_start' => 'sometimes|required|date',
            'booking_end'   => 'sometimes|required|date|after:booking_start',
            'title'         => 'nullable|string|max:255',
            'status'        => 'in:scheduled,cancelled,completed',
            'agenda'        => 'nullable|string',
        ];
    }
}
