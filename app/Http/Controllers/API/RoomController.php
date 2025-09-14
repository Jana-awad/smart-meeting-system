<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Http\Resources\RoomResource;
use Illuminate\Support\Facades\DB;
use App\Models\Feature;
use App\Models\Meeting;
use Illuminate\Http\Request;

class RoomController extends Controller
{
//     public function index(Request $request)
//     {
//         // Get the date from the query, default to today if not provided
//         $date = $request->query('date', now()->toDateString());
//         //capacity filter (optional)
//         $capacityMin = $request->query('capacity_min', 0);
//         $capacityMax = $request->query('capacity_max', PHP_INT_MAX);

//         // Get all rooms with a count of meetings on that date
//         $rooms = Room::withCount(['meetings' => function ($query) use ($date) {
//             $query->whereDate('booking_start', '<=', $date)
//                 ->whereDate('booking_end', '>=', $date);
//         }])
//         ->whereBetween('capacity', [$capacityMin, $capacityMax])
//         ->get();
//      // Optionally filter by equipment (after fetching)
//     if ($request->has('equipment') && $request->equipment !== 'Any') {
//         $equipment = $request->equipment;
//         $rooms = $rooms->filter(function($room) use ($equipment) {
//             return $room->features->contains('name', $equipment);
//         });
//     }
//         // Calculate available capacity for each room
//         foreach ($rooms as $room) {
//             $room->available_capacity = 7 - $room->meetings_count;
//         }

//         // Return as resource collection
//         return RoomResource::collection($rooms);
//     }//old index method

//index 2
// public function index(Request $request,$roomId)
// {
//     $date = $request->query('date', now()->toDateString());

//     // cast to int and give sensible defaults
//     $capacityMin = (int) $request->query('capacity_min', 0);
//     $capacityMax = (int) $request->query('capacity_max', 9999);

//     // eager load features to avoid N+1 when filtering by equipment
//     $roomsQuery = Room::with(['features'])
//         ->whereBetween('capacity', [$capacityMin, $capacityMax]);

//     $rooms = $roomsQuery->get();

//     // filter by equipment (in-memory, features are eager loaded)
//     if ($request->filled('equipment') && $request->equipment !== 'Any') {
//         $equipment = $request->equipment;
//         $rooms = $rooms->filter(function ($room) use ($equipment) {
//             return $room->features->contains('name', $equipment);
//         })->values();
//     }

//     // calculate meetings_count for the requested date and available_capacity
//     foreach ($rooms as $room) {
//         $meetingsCount = $room->meetings()
//             ->whereDate('booking_start', '<=', $date)
//             ->whereDate('booking_end', '>=', $date)
//             ->count();

//         $room->meetings_count = $meetingsCount;
//         $room->available_capacity = max(0, 7 - $meetingsCount);
//     }

//  // Define all possible slots (10:00 to 16:00, 1-hour each)
//     $allSlots = [];
//     for ($hour = 10; $hour < 16; $hour++) {
//         $allSlots[] = sprintf("%02d:00", $hour);
//     }

//     // Get booked meetings for this room and date
//     $bookedSlots = Meeting::where('room_id', $roomId)
//         ->whereDate('booking_start', $date)
//         ->pluck('booking_start')
//         ->map(function($dt) { return \Carbon\Carbon::parse($dt)->format('H:i'); });

//     // Filter out booked slots
//     $availableSlots = array_diff($allSlots, $bookedSlots->toArray());

//     return response()->json(array_values($availableSlots));//added to return available slots

//     return RoomResource::collection($rooms);
// }

// public function index(Request $request)
// {
//     $date = $request->query('date', now()->toDateString());

//     $capacityMin = (int) $request->query('capacity_min', 0);
//     $capacityMax = (int) $request->query('capacity_max', 9999);

//     $roomsQuery = Room::with(['features'])->whereBetween('capacity', [$capacityMin, $capacityMax]);

//     $rooms = $roomsQuery->get();

//     if ($request->filled('equipment') && $request->equipment !== 'Any') {
//         $equipment = $request->equipment;
//         $rooms = $rooms->filter(function ($room) use ($equipment) {
//             return $room->features->contains('name', $equipment);
//         })->values();
//     }

//     $allSlots = [];
//     for ($hour = 10; $hour <= 16; $hour++) {
//         $allSlots[] = sprintf("%02d:00", $hour);
//     }

//     // Add meetings count and available slots per room
//     foreach ($rooms as $room) {
//         $meetingsCount = $room->meetings()
//             ->whereDate('booking_start', '<=', $date)
//             ->whereDate('booking_end', '>=', $date)
//             ->count();

//         $room->meetings_count = $meetingsCount;
//         $room->available_capacity = max(0, 7 - $meetingsCount);

//         // Available slots for this room
//         $bookedSlots = $room->meetings()
//             ->whereDate('booking_start', $date)
//             ->pluck('booking_start')
//             ->map(fn($dt) => \Carbon\Carbon::parse($dt)->format('H:i'));

//         $room->available_slots = array_values(array_diff($allSlots, $bookedSlots->toArray()));
//     }

//     return RoomResource::collection($rooms);
// }/index 3

// public function index(Request $request)
// {
//     $date = $request->query('date', now()->toDateString());

//     $capacityMin = (int) $request->query('capacity_min', 0);
//     $capacityMax = (int) $request->query('capacity_max', 9999);

//     $roomsQuery = Room::with(['features'])->whereBetween('capacity', [$capacityMin, $capacityMax]);


//     $rooms = $roomsQuery->get();

//     if ($request->filled('equipment') && $request->equipment !== 'Any') {
//         $equipment = $request->equipment;
//         $rooms = $rooms->filter(function ($room) use ($equipment) {
//             return $room->features->contains('name', $equipment);
//         })->values();
//     }

//     // Define all possible 1-hour slots
//     $allSlots = [];
//     for ($hour = 10; $hour <= 16; $hour++) {
//         $allSlots[] = sprintf("%02d:00", $hour);
//     }

//     foreach ($rooms as $room) {
//         // Count meetings affecting room availability
//         $meetingsCount = $room->meetings()
//             ->whereDate('booking_start', '<=', $date)
//             ->whereDate('booking_end', '>=', $date)
//             ->count();

//         $room->meetings_count = $meetingsCount;
//         $room->available_capacity = max(0, 7 - $meetingsCount);

//         // Get all bookings for the selected date
//         $bookedMeetings = $room->meetings()
//             ->whereDate('booking_start', $date)
//             ->get()
//             ->map(function ($m) {
//                 return [
//                     'start' => \Carbon\Carbon::parse($m->booking_start),
//                     'end'   => \Carbon\Carbon::parse($m->booking_end),
//                 ];
//             })->toArray();

//         // Filter available slots
//         $availableSlots = [];
//         foreach ($allSlots as $slot) {
//             $slotStart = \Carbon\Carbon::createFromFormat('H:i', $slot);
//             $slotEnd = $slotStart->copy()->addHour();

//             $overlaps = false;
//             foreach ($bookedMeetings as $b) {
//                 if ($slotStart < $b['end'] && $slotEnd > $b['start']) {
//                     $overlaps = true;
//                     break;
//                 }
//             }

//             if (!$overlaps) {
//                 $availableSlots[] = $slot;
//             }
//         }

//         $room->available_slots = $availableSlots;
//     }

//     return RoomResource::collection($rooms);
// }

// public function index(Request $request)
// {
//     $date = $request->query('date', now()->toDateString());
//     $time = $request->query('time'); // optional: "HH:MM" format
//     $capacityMin = (int) $request->query('capacity_min', 0);
//     $capacityMax = (int) $request->query('capacity_max', 9999);

//     // Base query: rooms with features and capacity filter
//     $roomsQuery = Room::with('features')
//         ->whereBetween('capacity', [$capacityMin, $capacityMax]);

//     $rooms = $roomsQuery->get();

//     // Filter by equipment if selected
//     if ($request->filled('equipment') && $request->equipment !== 'Any') {
//         $equipment = $request->equipment;
//         $rooms = $rooms->filter(function ($room) use ($equipment) {
//             return $room->features->contains('name', $equipment);
//         })->values();
//     }

//     // Define all possible 1-hour slots
//     $allSlots = [];
//     for ($hour = 10; $hour <= 16; $hour++) {
//         $allSlots[] = sprintf("%02d:00", $hour);
//     }

//     foreach ($rooms as $room) {
//         // Get all bookings for the selected date
//         $bookedMeetings = $room->meetings()
//             ->whereDate('booking_start', $date)
//             ->get()
//             ->map(function ($m) {
//                 return [
//                     'start' => \Carbon\Carbon::parse($m->booking_start),
//                     'end'   => \Carbon\Carbon::parse($m->booking_end),
//                 ];
//             })->toArray();

//         // Filter available slots
//         $availableSlots = [];
//         foreach ($allSlots as $slot) {
//             $slotStart = \Carbon\Carbon::createFromFormat('H:i', $slot);
//             $slotEnd = $slotStart->copy()->addHour();

//             $overlaps = false;
//             foreach ($bookedMeetings as $b) {
//                 if ($slotStart < $b['end'] && $slotEnd > $b['start']) {
//                     $overlaps = true;
//                     break;
//                 }
//             }

//             if (!$overlaps) {
//                 $availableSlots[] = $slot;
//             }
//         }

//         $room->available_slots = $availableSlots;
//         $room->available_capacity = max(0, 7 - count($bookedMeetings));
//     }

//     // If user selected a specific time, filter rooms by that time
//     // if ($time) {
//     //     $rooms = $rooms->filter(function ($room) use ($time) {
//     //         return in_array($time, $room->available_slots);
//     //     })->values();
//     // }
// if ($time || ($request->filled('feature_id') && $request->feature_id !== 'Any')) {
//     $featureId = (int) $request->feature_id;

//     $rooms = $rooms->filter(function ($room) use ($time, $featureId) {
//         // Check if the selected time is available
//         $timeAvailable = !$time || in_array($time, $room->available_slots);

//         // Check equipment filter if selected
//         $equipmentFilter = true;
//         if ($featureId && $featureId !== 'Any') {
//             $featureIds = $room->features->pluck('id')->toArray();
//             $equipmentFilter = in_array($featureId, $featureIds);
//         }

//         return $timeAvailable && $equipmentFilter;
//     })->values();
// }



//     return RoomResource::collection($rooms);
// }
// Get query parameters
public function index(Request $request)
{
    $date = $request->query('date', now()->toDateString());
    $time = $request->query('time');
    $capacityMin = (int) $request->query('capacity_min', 0);
    $capacityMax = (int) $request->query('capacity_max', 9999);
    $featureId = $request->query('feature_id'); // may be null or string

    // Base query: rooms with features and capacity filter
    $roomsQuery = Room::with('features')
        ->whereBetween('capacity', [$capacityMin, $capacityMax]);

   // Apply feature filter if provided
    if (!empty($featureId) && is_numeric($featureId)) {
        $roomsQuery->whereHas('features', function($q) use ($featureId) {
            $q->where('features.id', $featureId); // prefix table to avoid ambiguity
        });
    }

    $rooms = $roomsQuery->get();

    // Define all possible 1-hour slots
    $allSlots = [];
    for ($hour = 10; $hour <= 16; $hour++) {
        $allSlots[] = sprintf("%02d:00", $hour);
    }

    // Calculate available slots and capacity
    foreach ($rooms as $room) {
        $bookedMeetings = $room->meetings()
            ->whereDate('booking_start', $date)
            ->get()
            ->map(function ($m) {
                return [
                    'start' => \Carbon\Carbon::parse($m->booking_start),
                    'end'   => \Carbon\Carbon::parse($m->booking_end),
                ];
            })->toArray();

        $availableSlots = [];
        foreach ($allSlots as $slot) {
            $slotStart = \Carbon\Carbon::createFromFormat('H:i', $slot);
            $slotEnd = $slotStart->copy()->addHour();
            $overlaps = false;
            foreach ($bookedMeetings as $b) {
                if ($slotStart < $b['end'] && $slotEnd > $b['start']) {
                    $overlaps = true;
                    break;
                }
            }
            if (!$overlaps) $availableSlots[] = $slot;
        }

        $room->available_slots = $availableSlots;
        $room->available_capacity = max(0, 7 - count($bookedMeetings));
    }

    // Filter rooms by selected time if needed
    if ($time) {
        $rooms = $rooms->filter(fn($room) => in_array($time, $room->available_slots))->values();
    }

    return RoomResource::collection($rooms);
}



    public function store(StoreRoomRequest $request)
    {
        // $room = Room::create($request->validated());
        // return new RoomResource($room);

        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $room = Room::create($validated);

            // Get the feature names from the request that are true
            $requestedFeatures = collect($request->features)->filter()->keys();

            // Get the IDs of these features from the database
            $featureIds = Feature::whereIn('name', $requestedFeatures)->pluck('id');

            // Attach the features to the room using the sync() method
            $room->features()->sync($featureIds);

            DB::commit();
            return new RoomResource($room);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create room.'], 500);
        }
    }

    public function show(Room $room)
    {
        return new RoomResource($room);
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        $room->update($request->validated());
        return new RoomResource($room);
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return response()->json(['message' => 'Room deleted successfully.']);
    }
}
