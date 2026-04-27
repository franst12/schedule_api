<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JadwalKuliahController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::apiResource('matakuliah', MataKuliahController::class);
    Route::get('/jadwal/hari-ini', [JadwalKuliahController::class, 'jadwalHariIni']);
    Route::apiResource('jadwal', JadwalKuliahController::class);
    Route::apiResource('assignments', AssignmentController::class);
    Route::patch('assignments/{id}/finish', [AssignmentController::class, 'markAsFinished']);
    Route::apiResource('notes', NoteController::class);

    // Route Fitur 8: Profil
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
});