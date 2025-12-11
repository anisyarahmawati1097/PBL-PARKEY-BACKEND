<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    // Laporan harian per lokasi
    public function harianPerLokasi(Request $request)
    {
        $lokasiId = $request->query('lokasi_id');

        if (!$lokasiId) {
            return response()->json([
                'message' => 'Lokasi ID wajib diisi'
            ], 400);
        }

        $laporan = DB::table('parkirs')
            ->join('lokasis', 'parkirs.id_lokasi', '=', 'lokasis.id_lokasi')
            ->select(
                'lokasis.nama_lokasi as lokasi',
                DB::raw('DATE(parkirs.masuk) as tanggal'),
                DB::raw('COUNT(*) as total')
            )
            ->where('parkirs.id_lokasi', $lokasiId)
            ->groupBy('lokasis.nama_lokasi', DB::raw('DATE(parkirs.masuk)'))
            ->orderBy('tanggal', 'desc')
            ->get();

        return response()->json($laporan);
    }
}
