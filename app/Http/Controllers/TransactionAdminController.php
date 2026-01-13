<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionAdminController extends Controller
{
    public function transactions(Request $request)
    {
        $query = Transaction::with(['customer', 'parfume', 'user', 'details.service', 'payments']);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_code', 'LIKE', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('phone', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($request->has('payment_status') && !empty($request->payment_status)) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('laundry_status') && !empty($request->laundry_status)) {
            $query->where('laundry_status', $request->laundry_status);
        }

        $transactions = $query->orderByDesc('created_at')->paginate(15);
        $transactions->appends($request->all());

        $total_transactions = Transaction::count();
        $processing_transactions = Transaction::where('laundry_status', 'Process')->count();
        $paid_transactions = Transaction::where('payment_status', 'Paid')->count();
        $today_total_income = Payment::where('status', 'success')->whereDate('created_at', Carbon::today())->sum('amount');

        if ($request->ajax()) {
            return view('admin.partials.transaction-rows', compact('transactions'))->render();
        }

        return view('admin.transactions', [
            'transactions' => $transactions,
            'total_transactions' => $total_transactions,
            'processing_transactions' => $processing_transactions,
            'paid_transactions' => $paid_transactions,
            'today_total_income' => $today_total_income
        ]);
    }
}
