<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\CoreApi;
use App\Models\Kendaraan;
use App\Models\Payments;


class GenerateQRController extends Controller
{
    public function webhook(Request $request)
    {
        $serverKey = env("MIDTRANS_SERVER_KEY");

        $verifySign = hash("SHA512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        if ($request->signature_key == $verifySign) {
            $searchPayment =  Payments::where("invoice_id", $request->order_id)->first();

            if (!$searchPayment) {
                return response()->json(["message" => "Invoice id tidak ditemukan."], 404);
            }

            $searchPayment->update(["status" => $request->transaction_status]);
            return response()->json(["message" => "Payment berhasil diupdate."], 200);
        }
        return response()->json(["message" => "Verifikasi signature salah."], 500);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    //  $searchParkir = Parkir::where("parkir_id", $request->parkir_id)->first();
    //     if (!$searchParkir) {
    //         return response()->json(["message" => "Tidak ada kendaraan yang sedang parkir"], 404);
    //     }

    //     Config::$clientKey = env("MIDTRANS_CLIENT_KEY");
    //     Config::$serverKey = env("MIDTRANS_SERVER_KEY");
    //     Config::$isProduction = false;

    //     $transaction_data = [
    //         'payment_type' => 'gopay',
    //         'transaction_details' => [
    //             'order_id'    => $request->invoice_id,
    //             'gross_amount'  => $request->nominal
    //         ],
    //     ];

    //     try {
    //         $response = CoreApi::charge($transaction_data);

    //         $createPayment = Payments::create([
    //             "invoice_id" => $response->order_id,
    //             "payment_string" => $response->actions[0]->url,
    //             "status" => $response->transaction_status,
    //             "parkirs_id" => $searchParkir->id,
    //         ]);

    //         if (!$createPayment) {
    //             return response()->json(["message" => "Gagal membuat pembayaran."], 400);
    //         }
    //         return response()->json($createPayment, 200);
    //     } catch (\Exception $e) {
    //         return response()->json(["message" => "Gagal membuat QRIS by Midtrans.", "hint" => $e->getMessage()], 500);
    //     }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
