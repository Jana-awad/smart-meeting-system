<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'rolename' => $this->rolename,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
