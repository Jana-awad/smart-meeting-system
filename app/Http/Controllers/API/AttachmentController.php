<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Http\Requests\StoreAttachmentRequest;
use App\Http\Requests\UpdateAttachmentRequest;
use App\Http\Resources\AttachmentResource;

class AttachmentController extends Controller
{
    public function index()
    {
        return AttachmentResource::collection(
            Attachment::with(['uploader', 'minuteOfMeeting'])->get()
        );
    }

    public function store(StoreAttachmentRequest $request)
    {
        $attachment = Attachment::create($request->validated());
        return new AttachmentResource($attachment->load(['uploader', 'minuteOfMeeting']));
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
