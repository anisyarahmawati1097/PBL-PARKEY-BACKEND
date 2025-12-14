<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\CoreApi;
use App\Models\Payments;

class GenerateQRController extends Controller
{
    /**
     * Generate QRIS Payment
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'invoice_id' => 'required|unique:payments,invoice_id',
            'nominal' => 'required|numeric|min:1000',
            'parkirs_id' => 'required|exists:parkirs,id',
        ]);

        // Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = false;

        $transaction_data = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $request->invoice_id,
                'gross_amount' =>100,
            ],
        ];
        try {
            // Generate QRIS via Midtrans
            $response = CoreApi::charge($transaction_data);

            // Simpan payment ke database
            $payment = Payments::create([
                'invoice_id' => $response->order_id,
                'payment_string' =>$response->actions[1]->url?? $response->actions[0]->url?? '',
                'status' => $response->transaction_status,
                'parkirs_id' => $request->parkirs_id,
            ]);

            return response()->json([
                'order_id' => $payment->invoice_id,
                'qr_url' => $payment->payment_string,
                'status' => $payment->status,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat QRIS Midtrans',
                'hint' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Webhook Midtrans
     */
    public function webhook(Request $request)
    {
        $serverKey = env("MIDTRANS_SERVER_KEY");

        // Verifikasi signature
        $verifySign = hash("SHA512",
            $request->order_id . $request->status_code . $request->gross_amount . $serverKey
        );

        if ($request->signature_key === $verifySign) {
            $payment = Payments::where('invoice_id', $request->order_id)->first();

            if (!$payment) {
                return response()->json(['message' => 'Invoice id tidak ditemukan.'], 404);
            }

            // Update status payment
            $payment->update(['status' => $request->transaction_status]);
            return response()->json(['message' => 'Payment berhasil diupdate.'], 200);
        }

        return response()->json(['message' => 'Verifikasi signature salah.'], 500);
    }

    /**
     * Refresh status payment
     */
    public function checkStatus($invoice_id)
    {
        $payment = Payments::where('invoice_id', $invoice_id)->first();
        return response()->json([
            'status' => $payment->status ?? 'not_found'
        ]);
    }
}
