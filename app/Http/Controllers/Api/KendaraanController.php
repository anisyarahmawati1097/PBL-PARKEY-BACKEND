<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Parkir;
use App\Services\GenerateQRPayment;
use App\Models\Admin;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KendaraanController extends Controller
{
    /**
     * MASUK / KELUAR PARKIR
     */
       public function park_view(Request $request)
    {
        // Ambil token dari query ?token=xxx
        $token = $request->query('token');

        if (! $token) {
            return response()->json(['message' => 'Token tidak ditemukan'], 400);
        }

        $kendaraan = Kendaraan::where('qr_token', $token)->first();

        if (! $kendaraan) {
            return response()->json(['message' => 'QR tidak valid'], 404);
        }

        return view("parkir", ["token_parkir" => $token, "data" => $kendaraan]);
    }
    // ----
    public function park(Request $request, GenerateQRPayment $generateQRPayment)
    {

        /**
         * ==========================
         * 🚗   MASUK
         * ==========================
         */

        $admin = Admin::where("username", $request->username_admin)->with('lokasi')->first();
        if (!$admin) {
            return response()->json(['message' => 'Username admin tidak ditemukan.'], 404);
        }
        if(!$admin->lokasi){
            return response()->json(['message' => 'Lokasi tidak ada pada admin.'], 404);
        }
        $kendaraan = Kendaraan::where('qr_token', $request->token_parkir)->first();
        if (! $kendaraan) {
            return response()->json(['message' => 'QR tidak valid'], 404);
        }
        $parkirAktif = $kendaraan->parkirs()->whereNull('keluar')->first();
        if (!$parkirAktif) {
            $createParkir = Parkir::create([
                'id_lokasi' => $admin->lokasi->id_lokasi,
                'masuk' => now('Asia/Jakarta'),
                'kendaraans_id' => $kendaraan->id,
                'parkir_id' => strtoupper(Str::random(6)),
            ])->load('kendaraans');

            return response()->json([
                'status' => 'IN',
                'message' => 'Kendaraan berhasil masuk',
                'data' => $createParkir,
            ]);
        }

        /**
         * ==========================
         * 🚘   KELUAR
         * ==========================
         */
        $waktuKeluar = now('Asia/Jakarta');
        $selisih = $parkirAktif->masuk->diffInHours($waktuKeluar);

        $nominal = $kendaraan->jenis === 'motor'
            ? ($selisih <= 2 ? 2000 : 4000 + ($selisih - 2) * 1000)
            : ($selisih <= 2 ? 4000 : 4000 + ($selisih - 2) * 2000);

        $parkirAktif->update([
            'keluar' => $waktuKeluar,
            'harga' => $nominal,
        ]);

        // Generate QR pembayaran
        $invoice = 'PARKEY_' . Str::random(6) . mt_rand(0, 9999);

        $generatePayment = $generateQRPayment->create([
            'parkir_id' => $parkirAktif->parkir_id,
            'invoice_id' => $invoice,
            'nominal' => $nominal,
        ]);

        return response()->json([
            'status' => 'OUT',
            'message' => 'Kendaraan keluar parkir',
            'data' => $generatePayment,
        ]);
    }
    /**
     * SIMPAN KENDARAAN BARU
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
            'user_id' => 'required',
        ]);

        // Upload foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('kendaraan', 'public');
        }

        // Token QR
        $qrToken = Str::random(64);

        // URL aplikasi
        $url = config('app.url') .'/api/park?token='. $qrToken;

        // Generate QR
        $writer = new PngWriter;
        $qr = new QrCode($url);
        $result = $writer->write($qr);


        $filename = strtolower(Str::random(32)).'.png';
        $path = 'images/'.$filename;
        $result->saveToFile(public_path($path));

        // Simpan kendaraan
        $kendaraan = Kendaraan::create([
            'plat_nomor' => $request->plat_nomor,
            'jenis' => $request->jenis,
            'merk' => $request->merk,
            'model' => $request->model,
            'warna' => $request->warna,
            'tahun' => $request->tahun,
            'foto' => $fotoPath,
            'pemilik' => $request->pemilik,
            'qris' => $path,
            'qr_token' => $qrToken,
            'users_id' => $request->user_id,
        ]);

        return response()->json([
            'message' => 'Kendaraan berhasil ditambahkan',
            'data' => $kendaraan,
        ], 201);
    }

    /**
     * LIST KENDARAAN USER
     */
    public function index(Request $request)
    {
        $kendaraan = Kendaraan::where('users_id', $request->query('user_id'))->get();

        return response()->json([
            'success' => true,
            'data' => $kendaraan,
        ]);
    }

    /**
     * HAPUS
     */
    public function destroy($id)
    {
        $kendaraan = Kendaraan::find($id);

        if (! $kendaraan) {
            return response()->json([
                'success' => false,
                'message' => 'Kendaraan tidak ditemukan',
            ], 404);
        }

        $kendaraan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kendaraan berhasil dihapus',
        ]);
    }

    /**
     * SCAN BARCODE: ID|PLAT
     */
    public function scanBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required',
        ]);

        $parts = explode('|', $request->barcode);

        if (count($parts) !== 2) {
            return response()->json([
                'success' => false,
                'message' => 'Format barcode tidak valid.',
            ], 400);
        }

        [$kendaraanId, $platNomor] = $parts;

        $kendaraan = Kendaraan::where('id', $kendaraanId)
            ->where('plat_nomor', $platNomor)
            ->first();

        if (! $kendaraan) {
            return response()->json([
                'success' => false,
                'message' => 'Kendaraan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $kendaraan,
        ]);
    }
}
