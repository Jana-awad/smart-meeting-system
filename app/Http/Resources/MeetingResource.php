<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'room_id'        => $this->room_id,
            'room_name'      => $this->room->name ?? null,
            'organized_by'   => $this->organized_by,
            'organizer_name' => $this->organizer->name ?? null,
            'booking_start'  => $this->booking_start,
            'booking_end'    => $this->booking_end,
            'title'          => $this->title,
            'status'         => $this->status,
            'agenda'         => $this->agenda,
            'attendees' => AttendeeResource::collection($this->whenLoaded('attendees')),//add attendees
            'created_at'     => $this->created_at,
        ];
    }
}
