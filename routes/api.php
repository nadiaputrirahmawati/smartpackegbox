<?php

use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/camera/upload', [ActivityLogController::class, 'uploadLog']);

Route::get('/trigger-pusher-notification/{userId}', [NotificationController::class, 'send']);
