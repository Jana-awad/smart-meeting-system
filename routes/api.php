<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\MeetingController;
use App\Http\Controllers\API\AttachmentController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\AttendeeController;
use App\Http\Controllers\API\MinuteOfMeetingController;
use App\Http\Controllers\API\FeatureController;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\RoleController;

/*
|--------------------------------------------------------------------------
| Public Routes (No authentication needed)
|--------------------------------------------------------------------------
*/

//Route::post('register', [AuthController::class, 'register']);
//Route::post('login', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'login'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login');


/*
|--------------------------------------------------------------------------
| Protected Routes (Require JWT token)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:api'])->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('roles', RoleController::class);
    Route::apiResource('rooms', RoomController::class);
    Route::apiResource('features', FeatureController::class);
    Route::apiResource('minutes', MinuteOfMeetingController::class);
    Route::apiResource('attendees', AttendeeController::class);
    Route::apiResource('notifications', NotificationController::class);
    Route::apiResource('attachments', AttachmentController::class);
    Route::apiResource('meetings', MeetingController::class);
    Route::apiResource('users', UserController::class);
});
