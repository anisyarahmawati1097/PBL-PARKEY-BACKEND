<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WebhookController;


Route::get('/', function () {
    return view('welcome');
});

Route::get("/test", [WebhookController::class, "index"]);
