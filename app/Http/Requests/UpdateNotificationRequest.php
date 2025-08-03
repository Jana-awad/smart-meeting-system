<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
{
    return [
        'user_id' => 'sometimes|exists:users,id',
        'subject' => 'sometimes|string|max:255',
        'content' => 'sometimes|string',
        'status' => 'sometimes|in:unread,read,archived',
    ];
}

}
