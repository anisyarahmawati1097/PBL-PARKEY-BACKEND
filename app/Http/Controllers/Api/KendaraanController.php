<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kendaraan;

class KendaraanController extends Controller
{
    /**
     * Simpan kendaraan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required',
            'jenis' => 'required',
            'merk' => 'required',
            'model' => 'required',
            'warna' => 'required',
            'tahun' => 'required',
        ]);

        // Upload foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('kendaraan', 'public');
        }

        $kendaraan = Kendaraan::create([
            'plat_nomor' => $request->plat_nomor,
            'jenis' => $request->jenis,
            'merk' => $request->merk,
            'model' => $request->model,
            'warna' => $request->warna,
            'tahun' => $request->tahun,
            'foto' => $fotoPath,
            'pemilik' => $request->pemilik
        ]);

        return response()->json([
            "message" => "Kendaraan berhasil ditambahkan",
            "data" => $kendaraan
        ], 201);

    }

    /**
     * Ambil semua kendaraan
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Kendaraan::all()
        ]);
    }

    /**
     * Hapus kendaraan
     */
    public function destroy($id)
    {
        $kendaraan = Kendaraan::find($id);

        if (!$kendaraan) {
            return response()->json([
                'success' => false,
                'message' => 'Kendaraan tidak ditemukan'
            ], 404);
        }

        $kendaraan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kendaraan berhasil dihapus'
        ]);
    }
}
