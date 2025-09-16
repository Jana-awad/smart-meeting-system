<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

// class RoomResource extends JsonResource
// {
//     public function toArray($request): array
//     {
//         return [
//             'id' => $this->id,
//             'name' => $this->name,
//             'location' => $this->location,
//             'capacity' => $this->capacity,
//             'max_meetings_capacity' => '7', // original max capacity
//             'meetings_count' => $this->meetings_count ?? 0, // number of meetings on the date
//             'available_capacity' => $this->available_capacity ?? $this->capacity, // available slots
//              // Add available slots here
//             'available_slots' => $this->available_slots ?? [],
//             'created_by' => $this->created_by,
//             'created_at' => $this->created_at,
//             'updated_at' => $this->updated_at,
//             'features' => $this->features->map(function($feature) {
//             return [
//                 'id' => $feature->id,
//                 'name' => $feature->name,

//             ];
//     }),
//         ];
//     }
// }

class RoomResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => $this->location,
            'capacity' => $this->capacity,
            'max_meetings_capacity' => 7, // as integer
            'meetings_count' => $this->meetings_count ?? 0,
            'available_capacity' => $this->available_capacity ?? $this->capacity,
            'available_slots' => $this->available_slots ?? [],
            'features' => FeatureResource::collection($this->whenLoaded('features')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'features' => $this->whenLoaded('features', function () {
                return $this->features->map(function ($feature) {
                    return [
                        'id' => $feature->id,
                        'name' => $feature->name,
                    ];
                });
            }, []), // default to empty array if not loaded
        ];
    }
}

