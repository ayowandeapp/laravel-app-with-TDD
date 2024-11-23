<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectInvitationcontroller;
use App\Http\Controllers\ProjectTaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth'], function () {

    Route::apiResource('projects', ProjectController::class);
    Route::post("projects/{project}/invite", [ProjectInvitationcontroller::class, 'storeInvitedUser']);
    Route::post("projects/{project}/tasks", [ProjectTaskController::class, 'store']);
    Route::patch('projects/{project}/tasks/{projectTask}', [ProjectTaskController::class, 'update']);
});
