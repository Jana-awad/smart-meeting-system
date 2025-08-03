<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
 //use App\Http\Controllers\UserController;
use App\Http\Controllers\API\MeetingController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AttachmentController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\AttendeeController;
use App\Http\Controllers\API\MinuteOfMeetingController;
use App\Http\Controllers\API\FeatureController;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\RoleController;

Route::apiResource('roles', RoleController::class);

Route::apiResource('rooms', RoomController::class);

Route::apiResource('features', FeatureController::class);

Route::apiResource('minutes', MinuteOfMeetingController::class);

Route::apiResource('attendees', AttendeeController::class);

Route::apiResource('notifications', NotificationController::class);

Route::apiResource('attachments', AttachmentController::class);
Route::apiResource('meetings', MeetingController::class);
Route::apiResource('users', UserController::class);

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
   // return $request->user();
//});

Route::resource('users',UserController::class );
