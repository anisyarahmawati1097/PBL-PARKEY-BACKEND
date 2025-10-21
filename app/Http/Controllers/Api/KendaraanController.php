<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kendaraan;

class KendaraanController extends Controller
{
    public function index()
    {
        return response()->json(Kendaraan::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plat_nomor' => 'required|string',
            'jenis' => 'required|string',
            'pemilik' => 'required|string',
        ]);

        $kendaraan = Kendaraan::create($validated);

        return response()->json($kendaraan, 201);
    }
}
