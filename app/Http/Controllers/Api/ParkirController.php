<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Slot;
use App\Models\Harga;
use App\Models\Parkir;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


use App\Http\Controllers\Controller;

class ParkirController extends Controller
{

    public function pembayaran(Request $request,$id) {
        $parkir_id = $request->query('parkir_id');
        $parkir = Parkir::where('parkir_id', $id)->with('payment')->first();
        if(!$parkir){
            return response()->json(['message' => "Parkir tidak ditemukan."],404);
        }
        Log::info("PEMBAYARAN PARKIR : " . $parkir);
        return response()->json(['message' => "Parkir ditemukan", 'data' => $parkir], 200);
    }
    // ================= CARI PARKIR DENGAN PARKIR ID ============
    public function parkir_plat(Request $request)
    {
        $id = $request->query('parkir_id');

        if (!$id) {
            return response()->json([
                "status"  => "error",
                "message" => "Parkir id tidak dikirim"
            ], 400);
        }

        $parkir = Parkir::where('parkir_id', $id)
                        ->with('payments')
                        ->first();

        if (!$parkir) {
            return response()->json([
                "status"  => "error",
                "message" => "Parkir tidak ada"
            ], 404);
        }

        return response()->json([
            "status" => "success",
            "data"   => $parkir
        ], 200);
    }

    // ============== KENDARAAN MASUK / KELUAR ==============
    public function kendaraanMasuk(Request $request)
    {
        $plat     = $request->query('plat');
        $lokasiId = $request->query('lokasi_id');

        if (!$plat) {
            return response()->json([
                "status"  => "error",
                "message" => "Plat nomor tidak dikirim"
            ], 400);
        }

        $kendaraan = Kendaraan::where('plat_nomor', $plat)->first();

        if (!$kendaraan) {
            return response()->json([
                "status"  => "error",
                "message" => "Kendaraan tidak terdaftar"
            ], 404);
        }

        // Cek apakah kendaraan masih parkir
        $parkir = Parkir::where('kendaraans_id', $kendaraan->id)
                        ->whereNull('keluar')
                        ->first();

        if (!$parkir) {
            $slot = Slot::where('id_lokasi', $lokasiId)->first();

            if (!$slot || $slot->slot <= 0) {
                return response()->json([
                    "status"  => "error",
                    "message" => "Slot parkir penuh"
                ], 400);
            }

            $slot->slot -= 1;
            $slot->status = ($slot->slot == 0) ? 'penuh' : 'available';
            $slot->save();

            // =================== MASUK ===================
            $parkir = Parkir::create([
                "parkir_id"     => "PRK" . time(),
                "kendaraans_id" => $kendaraan->id,
                "masuk"         => Carbon::now(),
                "id_lokasi"     => $lokasiId,
                "id_slot"       => $slot->id_slot,
            ]);

            return response()->json([
                "status"  => "masuk",
                "message" => "Kendaraan masuk",
                "data"    => $parkir
            ], 200);
        }

        // =================== KELUAR ===================
        $masuk     = Carbon::parse($parkir->masuk);
        $keluar    = Carbon::now();
        $totalJam  = ceil($masuk->floatDiffInHours($keluar));

        // Ambil harga dari tabel harga sesuai lokasi dan jenis kendaraan
        $hargaData = Harga::where('id_lokasi', $parkir->id_lokasi)
                          ->where('jenis_kendaraan', $kendaraan->jenis)
                          ->first();

        if (!$hargaData) {
            return response()->json([
                "status"  => "error",
                "message" => "Harga untuk kendaraan ini belum diatur"
            ], 500);
        }

        // Hitung total biaya
        $totalBiaya = ($totalJam <= 2)
            ? $hargaData->harga
            : $hargaData->harga + (($totalJam - 2) * $hargaData->tambahan_per_jam);

        // Update keluar + total_harga di DB
        $parkir->update([
            "keluar"      => $keluar,
            "total_harga" => $totalBiaya
        ]);

        Slot::where('id_slot', $parkir->id_slot)
            ->update(['status' => 'available']);

        $parkir->load(['lokasi', 'kendaraans']);

        return response()->json([
            "status"  => "keluar",
            "message" => "Kendaraan keluar",
            "data"    => [
                "masuk"       => $masuk,
                "keluar"      => $keluar,
                "durasi_jam"  => $totalJam,
                "harga"       => $totalBiaya
            ]
        ], 200);
    }

    // ============== DAFTAR PENGENDARA DI LOKASI ==============
    public function getPengendaraByLokasi($id)
    {
        $data = Parkir::with('kendaraans')
            ->where('id_lokasi', $id)
            ->whereNull('keluar')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $data
        ]);
    }

    // ============== AKTIVITAS PENGENDARA (FILTERNYA BASED ON USER) ==============
    public function aktivitasPengendara(Request $request)
    {
        $user = $request->user();

        $kendaraan = Kendaraan::where('users_id', $user->id)
                              ->with('parkirs')
                              ->get();

        if ($kendaraan->isEmpty()) {
            return response()->json([
                "status"  => "empty",
                "message" => "Pengendara belum mendaftarkan kendaraan"
            ], 200);
        }

        return response()->json([
            "status" => "success",
            "data"   => $kendaraan
        ], 200);
    }

    // ============== SEMUA AKTIVITAS PARKIR ==============
    public function aktivitas()
    {
        $data = Parkir::with('kendaraans')
                      ->orderBy('id', 'desc')
                      ->get();

        return response()->json([
            "status"  => "success",
            "message" => "Daftar aktivitas parkir",
            "data"    => $data
        ], 200);
    }

    // ============== RIWAYAT PER USER ==============
    public function riwayat(Request $request)
    {
        $user = $request->user();

        $kendaraanId = Kendaraan::where('users_id', $user->id)->pluck("id");

        if (!$kendaraanId) {
            return response()->json([
                "status"  => "empty",
                "message" => "Pengendara belum mendaftarkan kendaraan"
            ], 200);
        }

        $riwayat = Parkir::with(['lokasi', 'kendaraans'])
            ->whereIn('kendaraans_id', $kendaraanId)
            ->whereNotNull('keluar')
            ->orderBy('id', 'desc')
            ->get();

        $riwayat = $riwayat->map(function ($parkir) {
            $masuk = $parkir->masuk;
            $keluar = $parkir->keluar;
            $totalJam = ceil($masuk->floatDiffInHours($keluar));

            $hargaData = $parkir->kendaraans->harga()
                             ->where('id_lokasi', $parkir->id_lokasi)
                             ->first();

            $totalBiaya = 0;
            if ($hargaData) {
                $totalBiaya = ($totalJam <= 2)
                    ? $hargaData->harga
                    : $hargaData->harga + (($totalJam - 2) * $hargaData->tambahan_per_jam);
            }

            $parkir->total_harga   = $totalBiaya;
            $parkir->durasi_jam    = $totalJam;
            $parkir->id_lokasi_data = $parkir->id_lokasi;

            return $parkir;
        });

        return response()->json([
            "status" => "success",
            "data"   => $riwayat
        ], 200);
    }
}
