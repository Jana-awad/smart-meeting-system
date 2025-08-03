<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_id'       => 'required|exists:rooms,id',
            'organized_by'  => 'required|exists:users,id',
            'booking_start' => 'required|date',
            'booking_end'   => 'required|date|after:booking_start',
            'title'         => 'nullable|string|max:255',
            'status'        => 'in:scheduled,cancelled,completed',
            'agenda'        => 'nullable|string',
        ];
    }
}
