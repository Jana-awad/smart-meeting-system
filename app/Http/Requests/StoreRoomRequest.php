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
            'created_by' => 'required|exists:users,id',
        ];
    }
}
