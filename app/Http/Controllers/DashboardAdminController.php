<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Exports\ReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\DashboardService;

class DashboardAdminController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function dashboard(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now()->endOfMonth();

        $data = $this->dashboardService->getDashboardData($startDate, $endDate);

        return view('admin.dashboard', array_merge($data, [
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
        ]));
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

