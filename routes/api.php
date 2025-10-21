<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KendaraanController;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/kendaraan', [KendaraanController::class, 'index']);
Route::post('/kendaraan', [KendaraanController::class, 'store']);

Route::post('/daftar', [AuthController::class, 'daftar']);
Route::post('/masuk', [AuthController::class, 'masuk']);
Route::middleware('auth:sanctum')->post('/keluar', [AuthController::class, 'keluar']);
