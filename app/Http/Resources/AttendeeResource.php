<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendeeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'meeting_id' => $this->meeting_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
