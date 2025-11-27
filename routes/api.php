<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KendaraanController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Admin\AdminAuthController;

// ROUTE USER TEROTENTIKASI
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ROUTE KENDARAAN
Route::post('/kendaraan/store', [KendaraanController::class, 'store']);
Route::get('/kendaraan', [KendaraanController::class, 'index']);
Route::delete('/kendaraan/{id}', [KendaraanController::class, 'destroy']);

// AUTH ROUTES
Route::post('/daftar', [AuthController::class, 'daftar']);
Route::post('/masuk', [AuthController::class, 'masuk']);
Route::middleware('auth:sanctum')->post('/keluar', [AuthController::class, 'keluar']);
Route::middleware('auth:sanctum')->post('/update-profile', [AuthController::class, 'update']);
Route::post('/lupa-password', [AuthController::class, 'lupaPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);


// public routes
Route::post('/admin/login', [AdminAuthController::class, 'login']);

//protected routes
Route::middleware('auth:sanctum')->group(function() {
});
