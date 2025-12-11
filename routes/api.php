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
use App\Http\Controllers\Api\SlotController;
use App\Http\Controllers\Api\ParkirController;
use App\Http\Controllers\Api\LaporanController;

// ROUTE USER TEROTENTIKASI
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ROUTE KENDARAAN
// Route::middleware('auth:sanctum')->post('/kendaraan/store', [KendaraanController::class, 'store']);
Route::post('/kendaraan', [KendaraanController::class, 'store']);
// Route::post('/kendaraan/store', [KendaraanController::class, 'store']);

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
//Route::post('/admin/create', [AdminController::class, 'store']);
//Route::get('/admin/list', [AdminController::class, 'index']);

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

Route::get('/kendaraan-masuk', [ParkirController::class, 'kendaraanMasuk']);

// ROUTE QR SCAN (masuk parkir via QR HP)
// Route::get('/park', [KendaraanController::class, 'park']);
Route::get('/park', [KendaraanController::class, 'park_view']);
Route::post('/park', [KendaraanController::class, 'park']);


Route::get('/parkir/masuk', [ParkirController::class, 'kendaraanMasuk']);
Route::get('/lokasi/{id}/pengendara', [ParkirController::class, 'getPengendaraByLokasi']);
Route::get('/parkir/aktivitas', [ParkirController::class, 'aktivitas']);
Route::middleware('auth:sanctum')->get('/aktivitas-pengendara', [ParkirController::class, 'aktivitasPengendara']);
Route::middleware('auth:sanctum')->group(function () {
Route::get('/parkir/aktivitas/riwayat', [ParkirController::class, 'riwayat']);
});
Route::middleware('auth:sanctum')->get('/riwayat-pengendara', [ParkirController::class, 'riwayatPengendara']);


Route::get('/slots', [SlotController::class, 'index']);
Route::get('/slots/lokasi/{id}', [SlotController::class, 'getByLokasi']);
Route::post('/slots/status', [SlotController::class, 'status']);
Route::post('/slots', [SlotController::class, 'store']);
Route::put('/slots/{id}', [SlotController::class, 'update']);
Route::delete('/slots/{id}', [SlotController::class, 'destroy']);

Route::get('/laporan/harian-lokasi', [LaporanController::class, 'harianPerLokasi']);






