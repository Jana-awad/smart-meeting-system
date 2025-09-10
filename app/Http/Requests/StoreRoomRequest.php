<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:rooms,name',
            'location' => 'nullable|string',
            'capacity'=>'required|integer',
            'features' => 'required|array', // <-- Add this line to validate features
            'features.*' => 'boolean',
        ];
    }
}
