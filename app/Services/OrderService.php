<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ActivityLogController;
use App\Services\MidtransService;

class OrderService
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function createOrder(array $data): array
    {
        try {
            DB::beginTransaction();
            $transaction_code = 'LP' . Carbon::now()->format('m') . strtoupper(uniqid());
            $subtotal = 0;

            $order = new Transaction();
            $order->transaction_code = $transaction_code;
            $order->customer_id = $data['customer_id'];
            $order->parfume_id = $data['parfume_id'];
            $order->user_id = auth()->user()->user_id;
            $order->estimated_date = $data['date'];
            $order->payment_status = $data['payment-status'] == 'downpayment' ? 'Partial' : ($data['payment-status'] == 'paid' ? 'Paid' : 'Unpaid');

            foreach ($data['items'] as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $total = $subtotal - ($data['discount'] ?? 0);

            $order->subtotal = $subtotal;
            $order->total = $total;
            $order->discount = $data['discount'] ?? 0;
            $order->save();

            foreach ($data['items'] as $item) {
                $order->details()->create([
                    'transaction_id' => $order->transaction_id,
                    'service_id' => $item['service_id'],
                    'price' => $item['price'],
                    'qty' => $item['quantity'],
                    'total' => $item['price'] * $item['quantity'],
                ]);
            }

            $paymentAmount = 0;
            if ($order->payment_status == 'Paid') {
                $paymentAmount = $total;
            } elseif ($order->payment_status == 'Partial') {
                $paymentAmount = $data['downpayment_amount'] ?? 0;
            }

            $midtrans = null;
            if ($paymentAmount > 0) {
                $payment = $order->payments()->create([
                    'amount' => $paymentAmount,
                    'payment_method' => $data['payment_method'],
                    'status' => $data['payment_method'] == 'cash' ? 'success' : 'pending',
                ]);

                if ($data['payment_method'] == 'digital') {
                    $midtrans = $this->handleDigitalPayment($order, $payment, $paymentAmount);
                }
            }


            DB::commit();
            ActivityLogController::log('Create', 'Transaksi Baru Di Tambahkan : ' . $transaction_code, auth()->user()->user_id);

            return ['order' => $order, 'midtrans' => $midtrans];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store Order Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateOrder(Transaction $order, array $data): array
    {
        
        Log::info("MENCOBA UPDATE ORDER DI SERVICE");
        try {
            DB::beginTransaction();
            $midtrans = null;

            if (isset($data['amount_paid_now']) && $data['amount_paid_now'] > 0) {
                $payment = $order->payments()->create([
                    'amount' => $data['amount_paid_now'],
                    'payment_method' => $data['payment_method_update'],
                    'status' => $data['payment_method_update'] == 'cash' ? 'success' : 'pending',
                ]);

                if ($data['payment_method_update'] == 'digital') {
                    $midtrans = $this->handleDigitalPayment($order, $payment, $data['amount_paid_now']);
                }
            }

            $order->refresh();

            $totalPaid = $order->amount_paid;
            if ($totalPaid >= $order->total) {
                $order->payment_status = 'Paid';
            } elseif ($totalPaid > 0) {
                $order->payment_status = 'Partial';
            } else {
                $order->payment_status = 'Unpaid';
            }

            $order->laundry_status = $data['laundry_status'] ?? $order->laundry_status;
            $order->note = $data['note'] ?? $order->note;
            Log::info($data['laundry_status']);
            Log::info($order->laundry_status);
            $order->save();
            Log::info("MENGUPDATE LAUNDRY");
            Log::info($order);

            DB::commit();
            ActivityLogController::log('Update', 'Transaksi Di Ubah : ' . $order->transaction_code, auth()->user()->user_id);

            return ['order' => $order, 'midtrans' => $midtrans];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update Order Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function retryDigitalPayment(Transaction $order): array
    {
        try {
            DB::beginTransaction();
            $midtrans = null;

            $remainingAmount = $order->total - $order->amount_paid;
            if ($remainingAmount <= 0) {
                throw new \Exception('This order is already fully paid.');
            }

            $payment = $order->payments()->create([
                'amount' => $remainingAmount,
                'payment_method' => 'digital',
                'status' => 'pending',
            ]);

            $midtrans = $this->handleDigitalPayment($order, $payment, $remainingAmount);

            DB::commit();
            ActivityLogController::log('Update', 'Percobaan Pembayaran Digital Baru : ' . $order->transaction_code, auth()->user()->user_id);

            return ['order' => $order, 'midtrans' => $midtrans];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Retry Digital Payment Error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function handleDigitalPayment(Transaction $order, Payment $payment, int $amount)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $order->transaction_code . '-' . $payment->payment_id,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $order->customer->name,
                'email' => $order->customer->email,
                'phone' => $order->customer->phone,
            ],
            'enabled_payments' => ['gopay', 'shopeepay_qris'],
        ];

        $midtrans = $this->midtransService->createTransaction($params);

        if (isset($midtrans['error'])) {
            throw new \Exception('Midtrans Error: ' . $midtrans['error']);
        }

        $payment->midtrans_order_id = $params['transaction_details']['order_id'];
        $payment->snap_token = $midtrans['snap_token'];
        $payment->save();

        return $midtrans;
    }
}
