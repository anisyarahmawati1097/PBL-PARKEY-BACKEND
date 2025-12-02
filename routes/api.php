<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KendaraanController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Api\PengendaraController;
use App\Models\User;
use App\Models\Pengendara;
use App\Models\Kendaraan;

// ROUTE USER TEROTENTIKASI
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ROUTE KENDARAAN
Route::middleware('auth:sanctum')->post('/kendaraan/store', [KendaraanController::class, 'store']);
Route::get('/kendaraan', [KendaraanController::class, 'index']);
// Route::delete('/kendaraan/{id}', [KendaraanController::class, 'destroy']);
// Route::post('/kendaraan/park', [KendaraanController::class, "park"]);

// ROUTE PENGENDARA
Route::get('/pengendara', [PengendaraController::class, 'index']);
Route::get('/pengendara/{id}/kendaraan', [PengendaraController::class, 'getKendaraan']);

// AUTH ROUTES (USER)
Route::post('/daftar', [AuthController::class, 'daftar']);
Route::post('/masuk', [AuthController::class, 'masuk']);
Route::post('/lupa-password', [AuthController::class, 'lupaPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::middleware('auth:sanctum')->post('/keluar', [AuthController::class, 'keluar']);
Route::middleware('auth:sanctum')->post('/update-profile', [AuthController::class, 'update']);

// ADMIN LOGIN
Route::post('/admin/login', [AdminAuthController::class, 'login']);

// USERS LIST TESTING
Route::get('/users', function () {
    return User::all();
});

// ==== DASHBOARD API SEDERHANA TANPA CONTROLLER ==== //
Route::get('/dashboard/stats', function () {
    return response()->json([
        "pengendara" => User::count(),   // sebelumnya Pengendara::count()
        "kendaraan"  => Kendaraan::count(),
        "laporan"    => 0, // Belum ada laporan
    ]);
});


// SLOT PARKIR DUMMY
Route::get('/dashboard/slot-summary', function () {
    return [
        [
            "nama_lokasi" => "Grand Mall",
            "total" => 12,
            "occupied" => 4,
        ],
        [
            "nama_lokasi" => "SNL Food",
            "total" => 8,
            "occupied" => 3,
        ],
    ];
});
