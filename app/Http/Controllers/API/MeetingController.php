<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Http\Requests\StoreMeetingRequest;
use App\Http\Requests\UpdateMeetingRequest;
use App\Http\Resources\MeetingResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class MeetingController extends Controller
{
    // public function index()
    // {
    //     return MeetingResource::collection(Meeting::with(['room', 'organizer'])->get());
    // }
    // app/Http/Controllers/API/MeetingController.php


    public function index(Request $request)
    {
        $query = Meeting::with(['room', 'organizer']);

        if ($request->has('room_id')) {
            $query->where('room_id', $request->room_id);
        }
        if ($request->has('date')) {
            $date = $request->date;
            $query->whereDate('booking_start', '<=', $date)
                ->whereDate('booking_end', '>=', $date);
        }

        // return plain JSON array (not resource collection wrapper)
        $meetings = $query->get();

        return response()->json($meetings);
    }

    public function store(StoreMeetingRequest $request)
    {
        $meeting = Meeting::create($request->validated());
        // Attach attendees if provided
        if ($request->has('attendees')) {
            $meeting->attendees()->attach($request->attendees);
        }
        return new MeetingResource($meeting->load(['room', 'organizer']));
    }
    //  public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'title' => 'required|string|max:255',
    //         'room_id' => 'required|exists:rooms,id',
    //         'booking_start' => 'required|date',
    //         'booking_end' => 'required|date|after:booking_start',
    //         'organized_by' => auth()->id(),
    //         'description' => 'nullable|string',
    //         'agenda' => 'nullable|string',
    //         'attendees' => 'array',          // 👈 new
    //         'attendees.*' => 'integer|exists:users,id' // 👈 ensure valid user IDs
    //     ]);

    //     $meeting = Meeting::create($validated);

    //     // Save attendees
    //     if (!empty($validated['attendees'])) {
    //         foreach ($validated['attendees'] as $userId) {
    //             $meeting->attendees()->create([
    //                 'user_id' => $userId
    //             ]);
    //         }
    //     }

    //     return new MeetingResource($meeting->load('attendees.user'));
    // }

    // public function show($id)
    // {
    //     $meeting = Meeting::with(['room', 'organizer','attendees.user'])->findOrFail($id);
    //     return new MeetingResource($meeting);
    // }
    public function show($id)
    {
        return Meeting::with('attendees')->findOrFail($id);
    }
    // public function update(UpdateMeetingRequest $request, $id)
    // {
    //     $meeting = Meeting::findOrFail($id);
    //     $meeting->update($request->validated());
    //     return new MeetingResource($meeting->load(['room', 'organizer']));
    // }
    public function update(UpdateMeetingRequest $request, $id)
    {
        $meeting = Meeting::findOrFail($id);

        if ($meeting->status == 'ended') {
            return response()->json(['message' => 'Cannot edit ended meeting'], 400);
        }

        $meeting->update($request->validated());

        //  update attendees

        return new MeetingResource($meeting->load(['room', 'organizer', 'attendees']));
    }



    // public function destroy($id)
    // {
    //     $meeting = Meeting::findOrFail($id);
    //     $meeting->delete();
    //     return response()->json(['message' => 'Meeting deleted']);
    // }
    public function destroy($id)
    {
        $meeting = Meeting::find($id);
        if (!$meeting) {
            return response()->json(['message' => 'Meeting not found'], 404);
        }

        $meeting->delete(); // This will also delete attendees if you set cascade
        return response()->json(['message' => 'Meeting deleted successfully']);
    }

    public function getMeetingsByRoomAndDate(Request $request, $roomId) //new function
    {
        $date = $request->query('date', now()->toDateString());

        $meetings = Meeting::where('room_id', $roomId)
            ->whereDate('booking_start', $date)
            ->get(['id', 'title', 'booking_start', 'booking_end', 'agenda']);

        return response()->json($meetings);
    }

    //////////////////////////////////////////////////////////////////////////
    // getUpcomingMeeting
    //////////////////////////////////////////////////////////////////////////
    // public function getUpcomingMeeting(Request $request)
    // {
    //     $user = Auth::user();
    //     if (!$user) {
    //         return response()->json(['message' => 'Unauthenticated'], 401);
    //     }

    //    $meeting = Meeting::with(['room', 'attendees', 'organizer'])
    //     ->where('organized_by', $user->id) // confirm this is correct column
    //     ->where(function($q){
    //         $q->where('booking_start', '>=', now())
    //           ->orWhere('status', 'active');
    //     })
    //     ->orderBy('booking_start', 'asc')
    //     ->first();


    //     if (!$meeting) {
    //         return response()->json(['message' => 'No upcoming meetings'], 404);
    //     }


    //     return response()->json([
    //         'id' => $meeting->id,
    //         'title' => $meeting->title,
    //         'location' => optional($meeting->room)->name ?? 'Room TBD',
    //         'start' => $meeting->booking_start,
    //         'end' => $meeting->booking_end,
    //         'status' => $meeting->status,
    //         'organizer' => [
    //             'id' => optional($meeting->organizer)->id,
    //             'name' => optional($meeting->organizer)->name,
    //         ],
    //         'attendees' => $meeting->attendees->map(function ($a) {
    //             return [
    //                 'id' => $a->id,
    //                 'name' => $a->name,
    //                 'email' => $a->email,

    //             ];
    //         })->values(),
    //         // simple meeting link
    //         'link' => url("/meet/{$meeting->id}")
    //     ]);
    // }

    //////////////////////////////////////////////////////////////////////////
    // inviteAttendees - accept user ids and emails
    //////////////////////////////////////////////////////////////////////////
    public function inviteAttendees(Request $request, $id)
    {
        $request->validate([
            'attendees' => 'sometimes|array',
            'attendees.*' => 'sometimes|integer|exists:users,id',
            'emails' => 'sometimes|array',
            'emails.*' => 'sometimes|email',
        ]);

        $meeting = Meeting::findOrFail($id);

        if ($meeting->status == 'ended') {
            return response()->json(['message' => 'Cannot invite users to ended meeting'], 400);
        }

        $ids = $request->input('attendees', []);

        // map emails to existing user ids if any
        if ($request->filled('emails')) {
            $emails = $request->input('emails', []);
            $foundIds = User::whereIn('email', $emails)->pluck('id')->toArray();
            $ids = array_merge($ids, $foundIds);
        }

        $ids = array_values(array_unique($ids));

        if (!empty($ids)) {
            $meeting->attendees()->syncWithoutDetaching($ids);
        }

        $meeting->load('attendees');

        // Optionally: send notifications / emails here

        return response()->json([
            'message' => 'Attendees invited',
            'attendees' => $meeting->attendees->map(function ($a) {
                return ['id' => $a->id, 'name' => $a->name, 'email' => $a->email];
            })
        ]);
    }

    //////////////////////////////////////////////////////////////////////////
    // startMeeting (if needed)
    //////////////////////////////////////////////////////////////////////////
    public function startMeeting($id)
    {
        $meeting = Meeting::findOrFail($id);

        if ($meeting->status !== 'scheduled') {
            return response()->json(['message' => 'Meeting cannot be started'], 400);
        }

        $meeting->status = 'active';
        $meeting->save();

        return response()->json(['message' => 'Meeting started', 'meeting' => $meeting]);
    }

    //////////////////////////////////////////////////////////////////////////
    // endMeeting (if needed)
    //////////////////////////////////////////////////////////////////////////
    public function endMeeting($id)
    {
        $meeting = Meeting::findOrFail($id);

        if ($meeting->status !== 'active') {
            return response()->json(['message' => 'Meeting cannot be ended'], 400);
        }

        $meeting->status = 'ended';
        $meeting->save();

        return response()->json(['message' => 'Meeting ended', 'meeting' => $meeting]);
    }

    // start and end meeting functions

    // public function startMeeting($id)
    // {
    //     $meeting = Meeting::findOrFail($id);

    //     if ($meeting->status !== 'scheduled') {
    //         return response()->json(['message' => 'Meeting cannot be started'], 400);
    //     }

    //     $meeting->status = 'active';
    //     $meeting->save();

    //     return response()->json(['message' => 'Meeting started', 'meeting' => $meeting]);
    // }

    // public function endMeeting($id)
    // {
    //     $meeting = Meeting::findOrFail($id);

    //     if ($meeting->status !== 'active') {
    //         return response()->json(['message' => 'Meeting cannot be ended'], 400);
    //     }

    //     $meeting->status = 'ended';
    //     $meeting->save();

    //     return response()->json(['message' => 'Meeting ended', 'meeting' => $meeting]);
    // }
    // public function inviteAttendees(Request $request, $id)
    // {
    //     $request->validate([
    //         'attendees' => 'required|array',
    //         'attendees.*' => 'exists:users,id'
    //     ]);

    //     $meeting = Meeting::findOrFail($id);

    //     if($meeting->status == 'ended') {
    //         return response()->json(['message' => 'Cannot invite users to ended meeting'], 400);
    //     }

    //     $meeting->attendees()->syncWithoutDetaching($request->attendees);

    //     return response()->json(['message' => 'Attendees invited', 'attendees' => $meeting->attendees]);
    // }

    // public function getUpcomingMeeting(Request $request)
    // {
    //     $userId = auth()->id(); // or from token
    //     $meeting = Meeting::where('organized_by', $userId)
    //         ->where('booking_start', '>=', now())
    //         ->orderBy('booking_start', 'asc')
    //         ->first();

    //   if (!$meeting) {
    //     return response()->json(['message' => 'No upcoming meetings'], 404);
    // }

    // return response()->json([
    //     'title' => $meeting->title,
    //     'location' => $meeting->room->name ?? 'No room assigned',
    //     'start' => $meeting->booking_start,
    //     'end' => $meeting->booking_end
    // ]);
    // }
    public function getUpcomingMeeting(Request $request)
    {

        $user = Auth::user(); // returns the User model
        $userId = $user->id;

        $meeting = Meeting::with(['room', 'attendees'])
            ->where('organized_by', $userId)
            ->where('booking_start', '>=', now())
            ->orderBy('booking_start', 'asc')
            ->first();

        if (!$meeting) {
            return response()->json(['message' => 'No upcoming meetings'], 404);
        }

        return response()->json([
            'id' => $meeting->id,
            'title' => $meeting->title,
            'location' => $meeting->room->name ?? 'No room assigned',
            'start' => $meeting->booking_start,
            'end' => $meeting->booking_end,
            'status' => $meeting->status,
            'attendees' => $meeting->attendees->map(function ($a) {
                return ['id' => $a->id, 'name' => $a->name, 'email' => $a->email];
            }),
            'link' => url("/meet/{$meeting->id}")
        ]);
    }



    // public function getUpcomingMeeting(Request $request)
    // {
    //     // Get currently authenticated user
    //     $user = Auth::user();
    //     if (!$user) {
    //         return response()->json(['message' => 'Unauthenticated'], 401);
    //     }

    //     // Find the next upcoming meeting organized by this user
    //     $meeting = Meeting::with('room')
    //         ->where('organized_by', $user->id)
    //         ->where('booking_start', '>=', now()) // future meetings
    //         ->orderBy('booking_start', 'asc')
    //         ->first();

    //     // If no meeting is found
    //     if (!$meeting) {
    //         return response()->json(['message' => 'No upcoming meetings'], 200);
    //     }

    //     // Return meeting details
    //     return response()->json([
    //         'id'       => $meeting->id,
    //         'title'    => $meeting->title,
    //         'location' => $meeting->room->name ?? 'No room assigned',
    //         'start'    => $meeting->booking_start->format('Y-m-d H:i:s'),
    //         'end'      => $meeting->booking_end->format('Y-m-d H:i:s')
    //     ]);
    // }
    // }



}
