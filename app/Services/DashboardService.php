<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\Payment;
use Carbon\Carbon;
use App\Http\Controllers\ActivityLogController;

class DashboardService
{
    public function getDashboardData(Carbon $startDate, Carbon $endDate): array
    {
        $transactionsQuery = Transaction::whereBetween('created_at', [$startDate, $endDate]);
        $paymentsQuery = Payment::where('status', 'success')->whereBetween('created_at', [$startDate, $endDate]);

        $totalCustomers = Customer::whereBetween('created_at', [$startDate, $endDate])->count();
        $completedTransactions = (clone $transactionsQuery)->where('payment_status', 'Paid')->where('laundry_status', 'Picked Up')->count();
        $monthlyIncome = (clone $paymentsQuery)->sum('amount');
        $pendingOrders = (clone $transactionsQuery)->whereIn('laundry_status', ['Pending', 'Process'])->count();

        $transactions = (clone $transactionsQuery)->with('customer', 'user', 'details.service')->latest()->get();

        $revenueTrend = (clone $paymentsQuery)
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $popularServiceChartData = Service::withCount(['detailTransactions' => function ($query) use ($startDate, $endDate) {
            $query->whereHas('transaction', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });
        }])
            ->orderBy('detail_transactions_count', 'desc')
            ->take(5)
            ->get();

        $activitiesdata = ActivityLogController::getNewestActivities();

        return [
            'totalCustomers' => $totalCustomers,
            'completedTransactions' => $completedTransactions,
            'monthlyIncome' => $monthlyIncome,
            'pendingOrders' => $pendingOrders,
            'activitiesdata' => $activitiesdata,
            'popularServiceChartData' => $popularServiceChartData,
            'transactions' => $transactions,
            'revenueTrend' => $revenueTrend,
        ];
    }
}
