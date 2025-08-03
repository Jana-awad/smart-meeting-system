<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
   public function toArray($request): array
{
    return [
        'id' => $this->id,
        'user_id' => $this->user_id,
        'subject' => $this->subject,
        'content' => $this->content,
        'status' => $this->status,
        'created_at' => $this->created_at->toDateTimeString(),
        'updated_at' => $this->updated_at->toDateTimeString(),
    ];
}

}
