<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;

class LokasiController extends Controller
{
    public function index()
    {
        return Lokasi::all();
    }

    public function slotSummary()
    {
        return Lokasi::select('nama', 'total_slot', 'occupied_slot')->get();
    }

    public function harga()
{
    return $this->hasMany(Harga::class, 'id_lokasi');
}

}
