<?php

namespace App\Services;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ActivityLogController;

class OrderService
{
    public function createOrder(array $data): Transaction
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
            $order->payment_method = $data['payment_method'];
            $order->payment_status = $data['payment-status'] == 'downpayment' ? 'Partial' : $data['payment-status'];

            foreach ($data['items'] as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $total = $subtotal - ($data['discount'] ?? 0);
            $downpayment_amount = $data['downpayment_amount'] ?? 0;
            $remaining_amount = $total - $downpayment_amount;

            $order->subtotal = $subtotal;
            $order->total = $total;
            $order->discount = $data['discount'] ?? 0;

            $order->remaining_paid = $remaining_amount;

            if ($data['payment-status'] == 'paid') {
                $order->remaining_paid = 0;
                $order->ammount_paid = $total;
            } else {
                $order->ammount_paid = $downpayment_amount;
            }

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

            DB::commit();
            ActivityLogController::log('Create', 'Transaksi Baru Di Tambahkan : ' . $transaction_code, auth()->user()->user_id);

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store Order Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateOrder(Transaction $order, array $data): Transaction
    {
        try {
            DB::beginTransaction();

            $order->payment_status = $data['payment_status'];

            if ($order->payment_status === 'Paid') {
                $order->ammount_paid = $order->total;
                $order->remaining_paid = 0;
            } elseif ($order->payment_status === 'Partial') {
                $order->ammount_paid = $data['ammount_paid'] ?? 0;
                $order->remaining_paid = $order->total - $order->ammount_paid;
            } else {
                $order->ammount_paid = 0;
                $order->remaining_paid = $order->total;
            }

            $order->laundry_status = $data['laundry_status'];
            $order->note = $data['note'];

            $order->save();

            DB::commit();
            ActivityLogController::log('Update', 'Transaksi Di Ubah : ' . $order->transaction_code, auth()->user()->user_id);

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update Order Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
