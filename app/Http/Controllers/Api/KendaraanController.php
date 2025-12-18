<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Harga;
use App\Models\Kendaraan;
use App\Models\Parkir;
use App\Services\GenerateQRPayment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class KendaraanController extends Controller
{
    public function pay(Request $request)
    {
        $admin_req = $request->username_admin;
        $admin = Admin::where('username', $admin_req)->first();
        $parkir = Parkir::where('parkir_id', $request->parkId)->with(['payment.parkirs.lokasi', 'admin'])->first();
        if (!$admin) {
            return redirect()->back()->with(['status' => 404, 'message' => 'Mohon konfirmasi oleh Admin yang sama saat Parkir.']);
        }

        return redirect('/pay?parkId='.$request->parkId)->with(['status' => 500, 'message' => '', 'data' => $parkir, "admin" => $admin]);
    }

    public function pay_view(Request $request)
    {
        $parkId = $request->query('parkId');
        if (! $parkId) {
            return response()->json(['message' => 'Park id tidak ada'], 404);
        }

        $parkir = Parkir::where('parkir_id', $parkId)->with(['admin', 'kendaraans', 'lokasi'])->first();

        return view('pay', ['data' => $parkir, 'parkId' => $parkId]);
    }

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

        return view('parkir', ['token_parkir' => $token, 'data' => $kendaraan]);
    }

    // ----
    public function park(Request $request, GenerateQRPayment $generateQRPayment)
    {

        /**
         * ==========================
         * 🚗   MASUK
         * ==========================
         */
        $admin = Admin::where('username', $request->username_admin)->with('lokasi')->first();
        if (! $admin) {
            return response()->json(['message' => 'Username admin tidak ditemukan.'], 404);
        }

        if (! $admin->lokasi) {
            return response()->json(['message' => 'Lokasi tidak ada pada admin.'], 404);
        }

        $kendaraan = Kendaraan::where('qr_token', $request->token_parkir)->first();

        if (! $kendaraan) {
            return response()->json(['message' => 'QR tidak valid'], 404);
        }

        $parkirAktif = $kendaraan->parkirs()->whereNull('keluar')->first();

        if (! $parkirAktif) {
            $createParkir = Parkir::create([
                'id_lokasi' => $admin->lokasi->id_lokasi,
                'masuk' => now('Asia/Jakarta'),
                'kendaraans_id' => $kendaraan->id,
                'parkir_id' => strtoupper(Str::random(6)),
            ])->load('kendaraans');

            Log::info('INI IN : '.$createParkir);

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

        $harga = Harga::where('id_lokasi', $admin->lokasi->id_lokasi)->first();

        $nominal = 0;
        if (strtolower($harga->jenis_kendaraan) == strtolower($kendaraan->jenis)) {
            $nominal = $selisih <= 2 ? $harga->harga : $harga->harga + ($selisih - 2) * $harga->tambahan_per_jam;
        }

        if ($nominal == 0) {
            $nominal = 4000;
        }
        $parkirAktif->update([
            'keluar' => $waktuKeluar,
            'harga' => $nominal,
        ]);
        Log::info('INI OUT : '.$parkirAktif);
        // Generate QR pembayaran
        $invoice = 'PARKEY_'.strtoupper(Str::random(6)).mt_rand(0, 9999);

        $generatePayment = $generateQRPayment->create([
            'parkir_id' => $parkirAktif->parkir_id,
            'invoice_id' => $invoice,
            'nominal' => ceil($nominal),
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
        $url = config('app.url').'/api/park?token='.$qrToken;

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
