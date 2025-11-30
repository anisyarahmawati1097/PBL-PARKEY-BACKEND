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

    public function park(Request $request, GenerateQRPayment $generateQRPayment){
        $request->validate(['plat_nomor' => 'required', 'type' => 'required']);
        if (!$request->has("plat_nomor")) {
            return response()->json(["message" => "Parameter untuk plat nomor dibutuhkan."], 309);
        }
        $searchKendaraan = Kendaraan::where("plat_nomor", $request->plat_nomor)->first();
        if ($request->get("type") == 'in') {
            $createParkir = Parkir::create([
                "masuk" => now('Asia/Jakarta'),
                "kendaraans_id" => $searchKendaraan->id,
                "parkir_id" => strtoupper(Str::random(6))
            ])->load("kendaraans");

            return $createParkir;
        }

        if ($request->get("type") == 'out') {
            $updateParkir = $searchKendaraan->parkirs()->latest()->first();
            if (!$updateParkir) {
                return response()->json(["message" => "Kendaraan tidak sedang parkir."], 404);
            }

            $waktu_keluar = now('Asia/Jakarta');
            $selisih = $updateParkir->masuk->diffInHours($waktu_keluar);
            $nominal = 0;
            if (strtolower($searchKendaraan->jenis) == "motor") {
                if ($selisih <= 2) {
                    // harga 2 jam pertama untuk kendaraan roda 2.
                    $nominal = 2000;
                } else {
                    // total jam parkir dikurangi 2 jam pertama
                    $next_time = round($selisih - 2);
                    $nominal = 1000 * $next_time + 4000;
                }
            } else {
                if ($selisih <= 2) {
                    // harga 2 jam pertama untuk kendaraan roda 4.
                    $nominal = 4000;
                } else {
                    // total jam parkir dikurangi 2 jam pertama
                    $next_time = round($selisih - 2);
                    $nominal = 2000 * $next_time + 4000;
                }
            }
            $updateParkir->update(["keluar" => $waktu_keluar, "harga" => $nominal]);


            $invoice = "PARKEY_TEST_" . Str::random(6) . mt_rand(0, 9999);
            $generatePayment = $generateQRPayment->create([
                "parkir_id" => $updateParkir->parkir_id,
                "invoice_id" => $invoice,
                "nominal" => $nominal
            ]);

            return response()->json($generatePayment);
        }
        return response()->json(["message" => "Parameter type tidak dikenali."], 404);
    }
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
            'user_id' => 'required'
        ]);

        // Upload foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('kendaraan', 'public');
        }
        // Buat qr untuk masing masing kendaraan

        $writer = new PngWriter();
        $qr = new QrCode($request->plat_nomor);
        $result = $writer->write($qr);
        $randomkey = strtolower(Str::random(32)) . ".png";
        $path = "images/" . $randomkey;
        $result->saveToFile(public_path($path));
        //

        $kendaraan = Kendaraan::create([
            'plat_nomor' => $request->plat_nomor,
            'jenis' => $request->jenis,
            'merk' => $request->merk,
            'model' => $request->model,
            'warna' => $request->warna,
            'tahun' => $request->tahun,
             'foto' => $fotoPath,
            'pemilik' => $request->pemilik,
            'qris' =>  $path,
            'users_id' => $request->user_id
        ]);

        return response()->json([
            "message" => "Kendaraan berhasil ditambahkan",
            "data" => $kendaraan
        ], 201);

    }

    /**
     * Ambil semua kendaraan
     */
    public function index(Request $request)
{
    $user_id = $request->query('user_id');

    $kendaraan = Kendaraan::where('users_id', $user_id)->get();

    return response()->json([
        'success' => true,
        'data' => $kendaraan
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
