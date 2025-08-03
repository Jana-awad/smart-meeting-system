<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Http\Requests\StoreMeetingRequest;
use App\Http\Requests\UpdateMeetingRequest;
use App\Http\Resources\MeetingResource;

class MeetingController extends Controller
{
    public function index()
    {
        return MeetingResource::collection(Meeting::with(['room', 'organizer'])->get());
    }

    public function store(StoreMeetingRequest $request)
    {
        $meeting = Meeting::create($request->validated());
        return new MeetingResource($meeting->load(['room', 'organizer']));
    }

    public function show($id)
    {
        $meeting = Meeting::with(['room', 'organizer'])->findOrFail($id);
        return new MeetingResource($meeting);
    }

    public function update(UpdateMeetingRequest $request, $id)
    {
        $meeting = Meeting::findOrFail($id);
        $meeting->update($request->validated());
        return new MeetingResource($meeting->load(['room', 'organizer']));
    }

    public function destroy($id)
    {
        $meeting = Meeting::findOrFail($id);
        $meeting->delete();
        return response()->json(['message' => 'Meeting deleted']);
    }
}
