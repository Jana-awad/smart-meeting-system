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
