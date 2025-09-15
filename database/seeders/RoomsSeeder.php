<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Feature;


class RoomsSeeder extends Seeder
{
    public function run(): void
    {
        // Define rooms with their features
        $rooms = [
            [
                'name' => 'Conference Room A',
                'location' => 'First Floor',
                'capacity' => 8,
                'features' => ['projector', 'whiteboard', 'wifi']
            ],
            [
                'name' => 'Boardroom B',
                'location' => 'Second Floor',
                'capacity' => 12,
                'features' => ['conference', 'wifi']
            ],
            [
                'name' => 'Creative Space C',
                'location' => 'Third Floor',
                'capacity' => 6,
                'features' => ['whiteboard', 'wifi']
            ],
            [
                'name' => 'Huddle Room D',
                'location' => 'Fourth Floor',
                'capacity' => 4,
                'features' => ['wifi']
            ],
        ];

        foreach ($rooms as $roomData) {
            // Create room
            $room = Room::updateOrCreate(
                ['name' => $roomData['name']],
                [
                    'location' => $roomData['location'],
                    'capacity' => $roomData['capacity'],
                ]
            );

            // Attach features
            if (!empty($roomData['features'])) {
                $featureIds = Feature::whereIn('name', $roomData['features'])->pluck('id')->toArray();
                if(!empty($featureIds)){
                $room->features()->sync($featureIds);
            }
        }
        }
    }
}

// class RoomsSeeder extends Seeder
// {
//     public function run(): void
//     {
//         Room::create([
//             'name' => 'Conference Room A',
//             'location' => 'First Floor',
//             'capacity' => 8,
//           //  'created_by' => 1, // admin user id
//         ]);

//         Room::create([
//             'name' => 'Boardroom B',
//             'location' => 'Second Floor',
//             'capacity' => 12,
//           //  'created_by' => 1,
//         ]);

//         Room::create([
//             'name' => 'Creative Space C',
//             'location' => 'Third Floor',
//             'capacity' => 6,
//           //  'created_by' => 1,
//         ]);

//         Room::create([
//             'name' => 'Huddle Room D',
//             'location' => 'Fourth Floor',
//             'capacity' => 4,
//           //  'created_by' => 1,
//         ]);
//     }
// }
