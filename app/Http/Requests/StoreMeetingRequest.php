<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Meeting;

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

    //  Prevent overlapping bookings
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $start = $this->booking_start;
            $end = $this->booking_end;
            $roomId = $this->room_id;

            $overlap = Meeting::where('room_id', $roomId)
                ->where(function ($query) use ($start, $end) {
                    $query->where('booking_start', '<', $end)
                        ->where('booking_end', '>', $start);
                })
                ->exists();


            if ($overlap) {
                $validator->errors()->add('booking_start', 'This time slot is already booked for the selected room.');
            }
        });
    }
}
