<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Exports\ReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
// use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DetailTransaction;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ActivityLogController;

class DashboardAdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now()->endOfMonth();

        $transactionsQuery = Transaction::whereBetween('created_at', [$startDate, $endDate]);

        $totalCustomers = Customer::whereBetween('created_at', [$startDate, $endDate])->count();
        $completedTransactions = (clone $transactionsQuery)->where('payment_status', 'Paid')->where('laundry_status', 'Picked Up')->count();
        $monthlyIncome = (clone $transactionsQuery)->sum('total');
        $pendingOrders = (clone $transactionsQuery)->whereIn('laundry_status', ['Pending', 'Process'])->count();

        $transactions = (clone $transactionsQuery)->with('customer', 'user', 'details.service')->latest()->get();

        $revenueTrend = (clone $transactionsQuery)
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
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

        return view('admin.dashboard', [
            'totalCustomers' => $totalCustomers,
            'completedTransactions' => $completedTransactions,
            'monthlyIncome' => $monthlyIncome,
            'pendingOrders' => $pendingOrders,
            'activitiesdata' => $activitiesdata,
            'popularServiceChartData' => $popularServiceChartData,
            'transactions' => $transactions,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
            'revenueTrend' => $revenueTrend,
        ]);
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now()->endOfMonth();

        $transactions = Transaction::with('customer', 'user', 'details.service')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        return Excel::download(new ReportExport($transactions), 'laporan-transaksi.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay()
            : now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::createFromFormat('Y-m-d', $request->end_date)->endOfDay()
            : now()->endOfMonth();

        $transactions = Transaction::with('customer', 'user', 'details.service')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        Log::info('' . $startDate . '' . $endDate . '');
        Log::info($transactions);

        $pdf = Pdf::loadView('admin.exports.report_pdf', compact('transactions'));
        return $pdf->download('laporan-transaksi.pdf');
    }
}
