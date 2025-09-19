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
    public function index()
    {
        $rooms = Room::latest()->paginate(10);
        return RoomResource::collection($rooms);
    }

    public function list(Request $request)
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
            $roomsQuery->whereHas('features', function ($q) use ($featureId) {
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
        return new RoomResource($room->load('features'));
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        //$room->update($request->validated());
        //return new RoomResource($room->load('features'));
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $room->update($validated);

            // FIX: Correctly process the features payload
            if ($request->has('features')) {
                // Get the keys of the features with a true value
                $requestedFeatures = collect($request->features)->filter()->keys();

                // Find the IDs of those features in the database
                $featureIds = Feature::whereIn('name', $requestedFeatures)->pluck('id');

                // Sync the room's features with the new IDs
                $room->features()->sync($featureIds);
            }

            DB::commit();
            return new RoomResource($room->load('features'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update room.'], 500);
        }
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return response()->json(['message' => 'Room deleted successfully.']);
    }
}
