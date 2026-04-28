<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TaskController;

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('courses', CourseController::class);
    Route::get('/schedule/today', [ScheduleController::class, 'scheduleToday']);
    Route::apiResource('schedule', ScheduleController::class);
    Route::apiResource('tasks', TaskController::class);
    Route::patch('tasks/{id}/finish', [TaskController::class, 'markAsFinished']);
    Route::apiResource('notes', NoteController::class);

    // Route Fitur 8: Profil
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
});