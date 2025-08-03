<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Http\Resources\RoomResource;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::latest()->paginate(10);
        return RoomResource::collection($rooms);
    }

    public function store(StoreRoomRequest $request)
    {
        $room = Room::create($request->validated());
        return new RoomResource($room);
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
