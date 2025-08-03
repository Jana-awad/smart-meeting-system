<?php

namespace App\Http\Controllers\API;

use App\Models\MinuteOfMeeting;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMinuteOfMeetingRequest;
use App\Http\Requests\UpdateMinuteOfMeetingRequest;
use App\Http\Resources\MinuteOfMeetingResource;

class MinuteOfMeetingController extends Controller
{
    public function index()
    {
        return MinuteOfMeetingResource::collection(MinuteOfMeeting::latest()->paginate(10));
    }

    public function store(StoreMinuteOfMeetingRequest $request)
    {
        $minute = MinuteOfMeeting::create($request->validated());
        return new MinuteOfMeetingResource($minute);
    }

    public function show(MinuteOfMeeting $minuteOfMeeting)
    {
        return new MinuteOfMeetingResource($minuteOfMeeting);
    }

    public function update(UpdateMinuteOfMeetingRequest $request, MinuteOfMeeting $minuteOfMeeting)
    {
        $minuteOfMeeting->update($request->validated());
        return new MinuteOfMeetingResource($minuteOfMeeting);
    }

    public function destroy(MinuteOfMeeting $minuteOfMeeting)
    {
        $minuteOfMeeting->delete();
        return response()->json(['message' => 'Minute of Meeting deleted']);
    }
}
