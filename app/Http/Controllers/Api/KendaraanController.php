<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Endroid\QrCode\QrCode;
use App\Models\Kendaraan;
use App\Models\Parkir;

class KendaraanController extends Controller
{
    // ... kode park() seperti sebelumnya ...
    public function index(Request $request)
{
    $userId = $request->query('user_id');

    if (!$userId) {
        return response()->json([
            "success" => false,
            "message" => "user_id harus dikirim"
        ], 400);
    }

    $kendaraan = Kendaraan::where('users_id', $userId)->get();

    return response()->json([
        "success" => true,
        "data" => $kendaraan
    ], 200);
}


    public function store(Request $request)
    {
        // Validasi input
             $request->validate([
            'plat_nomor' => 'required|unique:kendaraans',
            'jenis' => 'required',
            'merk' => 'required',
            'model' => 'required',
            'warna' => 'required',
            'tahun' => 'required',
            'user_id' => 'required'
        ]);
        // Upload Foto Kendaraan
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            try {
                $fotoPath = $request->file('foto')->store('kendaraan', 'public');
            } catch (\Exception $e) {
                return response()->json([
                    "success" => false,
                    "message" => "Gagal upload foto kendaraan",
                    "error" => $e->getMessage()
                ], 500);
            }
        }

        // Generate QR Code
        $qrPath = null;
        try {
            $imagesDir = public_path('images');
            if (!file_exists($imagesDir)) {
                mkdir($imagesDir, 0755, true);
            }

            $writer = new PngWriter();
            $qr = new QrCode($request->plat_nomor);
            $result = $writer->write($qr);
            $randomKey = strtolower(Str::random(32)) . ".png";
            $qrPath = "images/" . $randomKey;
            $result->saveToFile(public_path($qrPath));
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "QR Code gagal dibuat. Pastikan GD extension aktif.",
                "error" => $e->getMessage()
            ], 500);
        }

        // Simpan kendaraan ke DB
        try {
            $kendaraan = Kendaraan::create([
                'plat_nomor' => $request->plat_nomor,
                'jenis' => $request->jenis,
                'merk' => $request->merk,
                'model' => $request->model,
                'warna' => $request->warna,
                'tahun' => $request->tahun,
                'foto' => $fotoPath,
                'pemilik' => $request->pemilik ?? 'User',
                'qris' => $qrPath,
                'users_id' => $request->user_id // disesuaikan dengan migration
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Gagal menyimpan kendaraan",
                "error" => $e->getMessage()
            ], 500);
        }

        return response()->json([
            "success" => true,
            "message" => "Kendaraan berhasil ditambahkan",
            "data" => $kendaraan
        ], 201);
    }
} // <= pastikan kurung ini ada
