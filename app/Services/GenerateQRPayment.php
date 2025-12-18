<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\CoreApi;
use App\Models\Parkir;
use App\Models\Payments;
use Endroid\QrCode\QrCode;
use Illuminate\Support\Str;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Log;

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

        // URL aplikasi
        $url = config('app.url') .'/pay?parkId=' . $data['parkir_id'];

        // Generate QR
        $writer = new PngWriter;
        $qr = new QrCode($url);
        $result = $writer->write($qr);


        $filename = strtolower(Str::random(32)).'.png';
        $path = 'images/pembayaran/' . $filename;
        $result->saveToFile(public_path($path));

        try {
            $response = CoreApi::charge($transaction_data);

            $createPayment = Payments::create([
                "invoice_id" => $response->order_id,
                "payment_string" => $response->actions[0]->url,
                "status" => $response->transaction_status,
                "parkirs_id" => $searchParkir->id,
                "link_payment" => $path
            ])->load("parkirs.kendaraans");

            if (!$createPayment) {
                return ["message" => "Gagal membuat pembayaran."];
            }
            return $createPayment;
        } catch (\Exception $e) {
            Log::info($e);
            return ["message" => "Gagal membuat QRIS by Midtrans.", "hint" => $e->getMessage()];
        }
    }
}
