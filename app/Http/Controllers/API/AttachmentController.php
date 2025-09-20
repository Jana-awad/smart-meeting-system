<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Http\Requests\StoreAttachmentRequest;
use App\Http\Requests\UpdateAttachmentRequest;
use App\Http\Resources\AttachmentResource;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function index()
    {
        return AttachmentResource::collection(
            Attachment::with(['uploader', 'minuteOfMeeting'])->get()
        );
    }

    // public function store(StoreAttachmentRequest $request)
    // {
    //     $attachment = Attachment::create($request->validated());
    //     return new AttachmentResource($attachment->load(['uploader', 'minuteOfMeeting']));
    // }
    public function store(Request $request)
{
    $request->validate([
        'file' => 'required|file|max:51200', // max 50MB for example
        'minute_id' => 'nullable|exists:minute_of_meetings,id',
        'meeting_id' => 'nullable|exists:meetings,id',
    ]);

    $file = $request->file('file');
    $path = $file->store('attachments', 'public'); // adjust driver
    $attachment = \App\Models\Attachment::create([
        'meeting_id' => $request->input('meeting_id'),
        'minute_id' => $request->input('minute_id'),
        'uploaded_by' => auth()->id(),
        'filename' => $file->getClientOriginalName(),
        'path' => $path,
        'mime' => $file->getClientMimeType(),
        'size' => $file->getSize()
    ]);

    return response()->json($attachment, 201);
}

    public function show($id)
    {
        $attachment = Attachment::with(['uploader', 'minuteOfMeeting'])->findOrFail($id);
        return new AttachmentResource($attachment);
    }

    public function update(UpdateAttachmentRequest $request, $id)
    {
        $attachment = Attachment::findOrFail($id);
        $attachment->update($request->validated());
        return new AttachmentResource($attachment->load(['uploader', 'minuteOfMeeting']));
    }

    public function destroy($id)
    {
        $attachment = Attachment::findOrFail($id);
        $attachment->delete();
        return response()->json(['message' => 'Attachment deleted']);
    }
}
