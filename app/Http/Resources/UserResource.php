<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'email'           => $this->email,
            'email_verified'  => $this->email_verified_at ? true : false,
            'created_at'      => $this->created_at,
            'password'       => $this->when($request->user() && $request->user()->isAdmin(), function () {
                return $this->password; // Only return password if the user is an admin
            }),
        ];
    }
}
