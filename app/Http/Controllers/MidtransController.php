<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function notificationHandler(Request $request)
    {
        Log::info('Midtrans notification received.');

        try {
            $serverKey = config('midtrans.server_key');
            $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

            if ($hashed !== $request->signature_key) {
                Log::warning('Midtrans Webhook: Invalid signature.', ['request' => $request->all()]);
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            $orderIdParts = explode('-', $request->order_id);
            $paymentId = end($orderIdParts);

            $payment = Payment::find($paymentId);
            if (!$payment) {
                Log::error('Midtrans Webhook: Payment not found.', ['payment_id' => $paymentId]);
                return response()->json(['message' => 'Payment not found'], 404);
            }

            if ($payment->status === 'success' || $payment->status === 'failed') {
                 Log::info('Midtrans Webhook: Notification for already finalized payment ignored.', ['payment_id' => $paymentId, 'status' => $payment->status]);
                 return response()->json(['message' => 'Notification already processed'], 200);
            }

            $transactionStatus = $request->transaction_status;
            $fraudStatus = $request->fraud_status;

            DB::transaction(function () use ($payment, $transactionStatus, $fraudStatus) {
                if ($transactionStatus == 'capture') {
                    if ($fraudStatus == 'accept') {
                        $payment->status = 'success';
                    }
                } else if ($transactionStatus == 'settlement') {
                    $payment->status = 'success';
                } else if (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                    $payment->status = 'failed';
                } else if ($transactionStatus == 'pending') {
                    $payment->status = 'pending';
                }

                $payment->save();
                Log::info("Midtrans Webhook: Payment status updated.", [
                    'payment_id' => $payment->payment_id,
                    'new_status' => $payment->status
                ]);

                $this->updateTransactionPaymentStatus($payment->transaction);
            });

            return response()->json(['message' => 'Notification processed successfully'], 200);

        } catch (\Exception $e) {
            Log::error('Midtrans Webhook Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'An error occurred.'], 500);
        }
    }

    private function updateTransactionPaymentStatus(Transaction $transaction)
    {
        $transaction->refresh();

        $totalPaid = $transaction->payments()->where('status', 'success')->sum('amount');

        if ($totalPaid >= $transaction->total) {
            $transaction->payment_status = 'Paid';
        } elseif ($totalPaid > 0) {
            $transaction->payment_status = 'Partial';
        } else {
            $transaction->payment_status = 'Unpaid';
        }

        $transaction->save();
        Log::info("Midtrans Webhook: Parent transaction status updated.", [
            'transaction_id' => $transaction->transaction_id,
            'new_status' => $transaction->payment_status
        ]);
    }
}
