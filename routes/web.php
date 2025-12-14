<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KendaraanController;

Route::get('/pay', [KendaraanController::class, 'pay_view']);
Route::post('/pay', [KendaraanController::class, 'pay']);

Route::get('/', function () {
    return view('welcome');
});


