<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MinuteOfMeetingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'meeting_id' => $this->meeting_id,
            'room_id' => $this->room_id,
            'created_by' => $this->created_by,
            'assigned_to' => $this->assigned_to,
            'content' => $this->content,
            'description' => $this->description,
            'status' => $this->status,
            'issues' => $this->issues,
            'deadline' => $this->deadline,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
