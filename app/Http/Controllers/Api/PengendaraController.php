<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kendaraan;

class PengendaraController extends Controller
{
    public function index()
    {
        return User::all();
    }

    // GET /pengendara/{id}/kendaraan
    public function getKendaraan($id)
    {
        return Kendaraan::where('users_id', $id)->get();
    }
}
