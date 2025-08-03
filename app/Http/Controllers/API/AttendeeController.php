<?php

namespace App\Http\Controllers\API;

use App\Models\Attendee;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendeeRequest;
use App\Http\Requests\UpdateAttendeeRequest;
use App\Http\Resources\AttendeeResource;

class AttendeeController extends Controller
{
    public function index()
    {
        return AttendeeResource::collection(Attendee::latest()->paginate(10));
    }

    public function store(StoreAttendeeRequest $request)
    {
        $attendee = Attendee::create($request->validated());
        return new AttendeeResource($attendee);
    }

    public function show(Attendee $attendee)
    {
        return new AttendeeResource($attendee);
    }

    public function update(UpdateAttendeeRequest $request, Attendee $attendee)
    {
        $attendee->update($request->validated());
        return new AttendeeResource($attendee);
    }

    public function destroy(Attendee $attendee)
    {
        $attendee->delete();
        return response()->json(['message' => 'Attendee deleted successfully']);
    }
}
