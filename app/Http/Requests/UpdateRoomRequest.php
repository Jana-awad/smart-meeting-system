<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:rooms,name,' . $this->room->id,
            'location' => 'nullable|string',
            'capacity' => 'required|integer',
            'features' => 'nullable|array',
            
        ];
    }
}
