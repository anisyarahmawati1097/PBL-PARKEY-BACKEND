<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slot;
use App\Models\Parkir;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    // GET: Semua slot + relasi lokasi
    public function status(Request $request)
{
    $lokasiId = $request->lokasi_id;

    // Ambil semua slot untuk lokasi
    $slots = Slot::where('id_lokasi', $lokasiId)->get();

    // Jika tidak ada slot → total = 0
    $totalSlot = $slots->sum('slot') ?? 0;

    // Hitung jumlah mobil yang sedang parkir
    $occupied = Parkir::where('id_lokasi', $lokasiId)
        ->whereNull('keluar')
        ->count();

    // available = total - occupied (jangan negatif)
    $available = max($totalSlot - $occupied, 0);

    return response()->json([
        'total'     => $totalSlot,
        'occupied'  => $occupied,
        'available' => $available,
    ], 200);
}

}
