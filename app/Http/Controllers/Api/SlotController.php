<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slot;
use App\Models\Parkir;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    // GET: Semua slot + relasi lokasi
    public function index()
    {
        return response()->json(
            Slot::with('lokasi')->get(),
            200
        );
    }

    // GET: slot berdasarkan lokasi
    public function getByLokasi($lokasiId)
    {
        $slots = Slot::where('id_lokasi', $lokasiId)->get();

        return response()->json($slots, 200);
    }

    // POST: Tambah slot
    public function store(Request $request)
    {
        $request->validate([
            'id_lokasi' => 'required|integer',
            'slot' => 'required|integer'
        ]);

        $slot = Slot::create($request->all());

        return response()->json($slot, 201);
    }

    // PUT: Update slot
    public function update(Request $request, $id_slot)
    {
        $slot = Slot::findOrFail($id_slot);
        $slot->update($request->all());

        return response()->json($slot, 200);
    }

    // DELETE: hapus slot
    public function destroy($id_slot)
    {
        Slot::destroy($id_slot);

        return response()->json(['message' => 'Slot dihapus'], 200);
    }

    // GET Status Slot (total, occupied, available)
    public function status(Request $request)
    {
        $lokasiId = $request->lokasi_id;

        // Ambil data slot berdasarkan lokasi
        $slotData = Slot::where('id_lokasi', $lokasiId)->first();

        if (!$slotData) {
            return response()->json(['error' => 'Data slot tidak ditemukan'], 404);
        }

        $totalSlot = $slotData->slot;

        // Hitung parkir ongoing (belum keluar)
        $occupied = Parkir::where('id_lokasi', $lokasiId)
            ->whereNull('keluar')
            ->count();

        return response()->json([
            'total'     => $totalSlot,
            'occupied'  => $occupied,
            'available' => $totalSlot - $occupied,
        ], 200);
    }
}
