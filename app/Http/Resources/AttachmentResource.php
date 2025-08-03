<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'file_name' => $this->file_name,
            'file_path' => $this->file_path,
            'file_type' => $this->file_type,
            'uploaded_by' => $this->uploaded_by,
            'uploader_name' => $this->uploader->name ?? null,
            'minute_of_meeting_id' => $this->minute_of_meeting_id,
            'uploaded_at' => $this->uploaded_at,
            'created_at' => $this->created_at,
        ];
    }
}
