<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailReminderController extends Controller
{
    public function sendReminder(Transaction $transaction)
    {
        // Log::info($transaction);
        // Contoh data pelanggan dan transaksi
        $customerName = $transaction->customer->name;
        $email = $transaction->customer->email;
        $transactionCode = $transaction->transaction_code;
        $status = match ($transaction->laundry_status) {
            'Pending' => 'Pesanan Diterima',
            'Process' => 'Sedang Diproses',
            'Completed' => 'Selesai Dicuci, Menunggu Diambil',
            'Picked Up' => 'Sudah Diambil, Pesanan Selesai',
        };
        // Dispatch job untuk mengirim email
        SendEmailJob::dispatch($customerName, $email, $transactionCode, $status)->delay(now()->addSeconds(2));
    }

    public function bulkSendEmail(Request $request)
    {
        $transactionIds = $request->transaction_ids;

        foreach ($transactionIds as $index => $id) {
            $transaction = Transaction::find($id);
            if (!$transaction || !$transaction->customer)
                continue;
            $customerName = $transaction->customer->name;
            $email = $transaction->customer->email;
            $transactionCode = $transaction->transaction_code;
            $status = match ($transaction->laundry_status) {
                'Pending' => 'Pesanan Diterima',
                'Process' => 'Sedang Diproses',
                'Completed' => 'Selesai Dicuci, Menunggu Diambil',
                'Picked Up' => 'Sudah Diambil, Pesanan Selesai',
            };

            Log::info("Dispatching email to $email for transaction $transactionCode with status $status");

            SendEmailJob::dispatch($customerName, $email, $transactionCode, $status)
                ->delay(now()->addSeconds($index * 15));
        }

        Log::info('Bulk email dispatch initiated for transactions: ' . implode(', ', $transactionIds));
    }

}
