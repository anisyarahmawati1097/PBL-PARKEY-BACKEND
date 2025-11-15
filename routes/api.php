<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KendaraanController;
use App\Http\Controllers\Api\AuthController;

// ================================
// ROUTE USER TEROTENTIKASI
// ================================
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ================================
// ROUTE KENDARAAN
// ================================
Route::get('/kendaraan', [KendaraanController::class, 'index']);
Route::post('/kendaraan', [KendaraanController::class, 'store']);

// ================================
// AUTH ROUTES
// ================================
Route::post('/daftar', [AuthController::class, 'daftar']);
Route::post('/masuk', [AuthController::class, 'masuk']);
Route::middleware('auth:sanctum')->post('/keluar', [AuthController::class, 'keluar']);
