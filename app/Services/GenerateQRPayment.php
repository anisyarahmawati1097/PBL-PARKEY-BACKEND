<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\CoreApi;
use App\Models\Parkir;
use App\Models\Payments;

class GenerateQRPayment
{
    public function create(array $data)
    {
        $searchParkir = Parkir::where("parkir_id", $data["parkir_id"])->first();
        if (!$searchParkir) {
            return ["message" => "Tidak ada kendaraan yang sedang parkir"];
        }

        Config::$clientKey = env("MIDTRANS_CLIENT_KEY");
        Config::$serverKey = env("MIDTRANS_SERVER_KEY");
        Config::$isProduction = false;

        $transaction_data = [
            'payment_type' => 'gopay',
            'transaction_details' => [
                'order_id'    => $data["invoice_id"],
                'gross_amount'  => $data["nominal"]
            ],
        ];

        try {
            $response = CoreApi::charge($transaction_data);

            $createPayment = Payments::create([
                "invoice_id" => $response->order_id,
                "payment_string" => $response->actions[0]->url,
                "status" => $response->transaction_status,
                "parkirs_id" => $searchParkir->id,
            ])->load("parkirs.kendaraans");


            if (!$createPayment) {
                return ["message" => "Gagal membuat pembayaran."];
            }
            return $createPayment;
        } catch (\Exception $e) {
            return ["message" => "Gagal membuat QRIS by Midtrans.", "hint" => $e->getMessage()];
        }
    }
}
