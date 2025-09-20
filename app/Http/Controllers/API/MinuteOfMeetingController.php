<?php

namespace App\Http\Controllers\API;

use App\Models\MinuteOfMeeting;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMinuteOfMeetingRequest;
use App\Http\Requests\UpdateMinuteOfMeetingRequest;
use App\Http\Resources\MinuteOfMeetingResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Attachment;
use Illuminate\Support\Facades\DB;

class MinuteOfMeetingController extends Controller
{
    // public function index()
    // {
    //     return MinuteOfMeetingResource::collection(MinuteOfMeeting::latest()->paginate(10));
    // }
    public function index(Request $request)
    {
        // optionally filter by meeting_id
        $query = MinuteOfMeeting::with(['meeting', 'creator', 'assignee', 'attachments']);
        if ($request->has('meeting_id')) {
            $query->where('meeting_id', $request->meeting_id);
        }
        return MinuteOfMeetingResource::collection($query->orderByDesc('created_at')->get());
    }
    // public function store(StoreMinuteOfMeetingRequest $request)
    // {
    //     $minute = MinuteOfMeeting::create($request->validated());
    //     return new MinuteOfMeetingResource($minute);
    // }


    // public function store(Request $request)
    // {
    //     $v = Validator::make($request->all(), [
    //         'meeting_id' => 'required|exists:meetings,id',
    //         'content' => 'nullable|string',
    //         'description' => 'nullable|string',
    //         'status' => 'nullable|string',
    //         'issues' => 'nullable|string',
    //         'deadline' => 'nullable|date',
    //         'assigned_to' => 'nullable|integer|exists:users,id'
    //     ]);
    //     if ($v->fails()) return response()->json(['errors' => $v->errors()], 422);

    //     $data = $v->validated();
    //     $data['created_by'] = Auth::id();

    //     $mom = MinuteOfMeeting::create($data);

    //     return new MinuteOfMeetingResource($mom);
    // }
    // public function store(Request $request)
    // {
    //     $v = Validator::make($request->all(), [
    //         'meeting_id'   => 'required|exists:meetings,id',
    //         'room_id'      => 'required|exists:rooms,id',
    //         'content'      => 'nullable|string',
    //         'created_by'   => 'nullable|exists:users,id',
    //         'action_items'                 => 'required|array|min:1',
    //         'action_items.*.description'  => 'required|string',
    //         'action_items.*.assigned_to'  => 'nullable|exists:users,id',
    //         'action_items.*.status'       => 'required|in:pending,in_progress,completed',
    //         'action_items.*.deadline'     => 'required|date',
    //     ]);

    //     if ($v->fails()) {
    //         return response()->json(['errors' => $v->errors()], 422);
    //     }

    //     $data = $v->validated();

    //     DB::beginTransaction();

    //     try {
    //         $minutes = MinuteOfMeeting::create([
    //             'meeting_id' => $data['meeting_id'],
    //             'room_id'    => $data['room_id'],
    //             'content'    => $data['content'] ?? null,
    //             'created_by' => Auth::id(),
    //         ]);

    //         foreach ($data['action_items'] as $item) {
    //             $minutes->actionItems()->create([
    //                 'description' => $item['description'],
    //                 'assigned_to' => $item['assigned_to'] ?? null,
    //                 'status'      => $item['status'],
    //                 'deadline'    => $item['deadline'],
    //             ]);
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'message' => 'Minutes of meeting created successfully',
    //             'data' => $minutes->load('actionItems'),
    //         ], 201);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'error' => 'Failed to save minutes of meeting',
    //             'exception' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'id'           => 'nullable|exists:minute_of_meetings,id',
            'meeting_id'   => 'required|exists:meetings,id',
            'room_id'      => 'required|exists:rooms,id',
            'content'      => 'nullable|string',
            'created_by'   => 'nullable|exists:users,id',

            'action_items'                 => 'required|array|min:1',
            'action_items.*.description'  => 'required|string',
            'action_items.*.assigned_to'  => 'nullable|exists:users,id',
            'action_items.*.status'       => 'required|in:pending,in_progress,completed',
            'action_items.*.deadline'     => 'required|date',
        ]);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        $data = $v->validated();

        DB::beginTransaction();

        try {
            // ✅ Check if we're updating or creating
            if (isset($data['id'])) {
                // 🔁 Update existing
                $minutes = MinuteOfMeeting::findOrFail($data['id']);

                $minutes->update([
                    'meeting_id' => $data['meeting_id'],
                    'room_id'    => $data['room_id'],
                    'content'    => $data['content'] ?? null,
                    'created_by' => Auth::id(), // optionally allow override with $data['created_by']
                ]);

                // 🔄 Delete existing action items and replace them
                $minutes->actionItems()->delete();
            } else {
                // ➕ Create new
                $minutes = MinuteOfMeeting::create([
                    'meeting_id' => $data['meeting_id'],
                    'room_id'    => $data['room_id'],
                    'content'    => $data['content'] ?? null,
                    'created_by' => Auth::id(),
                ]);
            }

            // 🧾 Save action items
            foreach ($data['action_items'] as $item) {
                $minutes->actionItems()->create([
                    'description' => $item['description'],
                    'assigned_to' => $item['assigned_to'] ?? null,
                    'status'      => $item['status'],
                    'deadline'    => $item['deadline'],
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => isset($data['id']) ? 'Minutes of meeting updated successfully' : 'Minutes of meeting created successfully',
                'data' => $minutes->load('actionItems'),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to save minutes of meeting',
                'exception' => $e->getMessage()
            ], 500);
        }
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

    public function getByMeeting($meetingId)
    {
        $minutes = MinuteOfMeeting::where('meeting_id', $meetingId)
            ->with(['creator', 'assignee', 'attachments'])
            ->orderByDesc('created_at')
            ->get();

        return MinuteOfMeetingResource::collection($minutes);
    }
    public function uploadAttachment(Request $request, MinuteOfMeeting $mom)
    {
        $file = $request->file('file');
        $path = $file->store('attachments', 'public');
        $request->validate([
            'file' => 'required|file|mimes:pdf,docx,xlsx,jpg,png|max:10240', // max 10MB
        ]);

        $attachment = Attachment::create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getClientMimeType(),
            'uploaded_by' => auth()->id(),
            'minute_of_meeting_id' => $mom->id,
            'uploaded_at' => now(),
        ]);

        return response()->json($attachment, 201);
    }
}
