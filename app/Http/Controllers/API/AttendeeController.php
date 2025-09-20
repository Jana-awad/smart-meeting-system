<?php

namespace App\Http\Controllers\API;

use App\Models\Attendee;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendeeRequest;
use App\Http\Requests\UpdateAttendeeRequest;
use App\Http\Resources\AttendeeResource;
use App\Models\Meeting;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    public function index()
    {
        return AttendeeResource::collection(Attendee::latest()->paginate(10));
    }

    // public function store(StoreAttendeeRequest $request)
    // {
    //     $attendee = Attendee::create($request->validated());
    //     return new AttendeeResource($attendee);
    // }
public function store(Request $request, $id)
{
    $meeting = Meeting::findOrFail($id);
    foreach ($request->attendees as $attendee) {
        $meeting->attendees()->create($attendee);
    }
    return response()->json(['message' => 'Attendees added successfully'], 200);
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
    //  public function getByMeeting($meetingId)
    // {
    //     $meeting = Meeting::with('attendees')->find($meetingId);

    //     if (!$meeting) {
    //         return response()->json(['message' => 'Meeting not found'], 404);
    //     }

    //     return response()->json($meeting->attendees);
    // }
    public function getByMeeting($meetingId)
{
    $meeting = Meeting::with('attendees')->find($meetingId);

    if (!$meeting) {
        return response()->json(['message' => 'Meeting not found'], 404);
    }

    $attendees = $meeting->attendees->map(function ($user) {
        return [
            'id' => $user->id,

            'name' => $user->name ?? 'Unknown',
            'email' => $user->email ?? null,
        ];
    });

    return response()->json($attendees);
}

    public function addAttendees(Request $request, $meetingId)
    {
        $meeting = Meeting::findOrFail($meetingId);

            $request->validate([
                'attendees' => 'required|array',
                'attendees.*.name' => 'required|string|max:255',
                'attendees.*.email' => 'required|email|max:255',
            ]);
        foreach ($request->attendees as $attendeeData) {
            $meeting->attendees()->create($attendeeData);
        }

        return response()->json(['message' => 'Attendees added successfully'], 200);
    }

}
