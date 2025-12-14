<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payments;

class WebhookController extends Controller
{
    public function index(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');

        $verifySign = hash(
            'sha512',
            $request->order_id . $request->status_code . $request->gross_amount . $serverKey
        );

        if ($request->signature_key === $verifySign) {
            $payment = Payments::where('invoice_id', $request->order_id)->first();

            if (!$payment) {
                return response()->json([
                    'message' => 'Invoice id tidak ditemukan.'
                ], 404);
            }

            $payment->update([
                'status' => $request->transaction_status
            ]);

            return response()->json([
                'message' => 'Payment berhasil diupdate.'
            ], 200);
        }

        return response()->json([
            'message' => 'Verifikasi signature salah.'
        ], 500);
    }
}
