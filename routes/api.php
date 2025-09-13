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

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

// Reset password: accepts token, email, password, password_confirmation
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
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

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/admin-only', fn() => response()->json(['message' => 'Welcome, admin']));
});

Route::middleware(['auth:api', 'role:employer'])->group(function () {
    Route::get('/employer-only', fn() => response()->json(['message' => 'Welcome, employee']));
});


// login (returns { user, token })
Route::post('/login', [AuthController::class, 'login']);


// get current user from token
Route::middleware('auth:api')->get('/user', [AuthController::class, 'user']);


// admin-only route (server-side enforcement via role middleware)
Route::middleware(['auth:api', 'role:admin'])->get('/admin-only', function() {
return response()->json(['message' => 'Welcome, admin']);
});