<?php

namespace App\Http\Controllers\API;

use App\Models\Notification;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;
use App\Http\Resources\NotificationResource;

class NotificationController extends Controller
{
    public function index()
    {
        return NotificationResource::collection(Notification::latest()->paginate(10));
    }

    public function store(StoreNotificationRequest $request)
    {
        $notification = Notification::create($request->validated());
        return new NotificationResource($notification);
    }

    public function show(Notification $notification)
    {
        return new NotificationResource($notification);
    }

    public function update(UpdateNotificationRequest $request, Notification $notification)
    {
        $notification->update($request->validated());
        return new NotificationResource($notification);
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return response()->json(['message' => 'Notification deleted successfully']);
    }
}
