<?php

namespace App\Http\Controllers;

use toastr;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Exports\ServiceExport;
use App\Imports\ServiceImport;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ActivityLogController;

class ServicesAdminController extends Controller
{
    public function services()
    {
        $total_services = Service::count();
        $popular_service = Service::withCount('detailTransactions')
            ->orderBy('detail_transactions_count', 'desc')
            ->first();

        $avg_service_price = round(Service::avg('price'));

        $lowest_service = Service::orderBy('price', 'asc')->first();

        $services = Service::withCount('detailTransactions')
            ->paginate(6);

        return view('admin.services', [
            'total_services' => $total_services,
            'avg_service_price' => $avg_service_price,
            'services' => $services,
            'popular_service' => $popular_service,
            'lowest_service' => $lowest_service
        ]);
    }

    public function storeService(Request $request)
    {
        $rules = [
            'service_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|in:kg,pasang,pcs,m²',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', implode("\n", $validator->errors()->all()));
        }

        $data = $request->only(['service_name', 'description', 'price', 'unit', 'icon_name']);
        if ($request->has('icon_name')) {
            $data['icon_name'] = $request->input('icon_name');
        }

        try {
            Service::create($data);
            ActivityLogController::log('Create', 'Layanan Baru Di Tambahkan : ' . $data['service_name'], auth()->user()->user_id);

            return redirect()->route('admin.services.index')
                ->with('success', 'Service added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Oops! We couldn't add the service. Please try again.")
                ->withInput();
        }
    }

    public function updateService(Request $request, Service $service)
    {
        $rules = [
            'service_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|in:kg,pasang,pcs,m²',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', implode("\n", $validator->errors()->all()));
        }

        $data = $request->only(['service_name', 'description', 'price', 'unit', 'icon_name']);
        if ($request->has('icon_name')) {
            $data['icon_name'] = $request->input('icon_name');
        }

        try {
            $service->update($data);
            ActivityLogController::log('Update', 'Layanan Di Ubah : ' . $data['service_name'], auth()->user()->user_id);

            return redirect()->route('admin.services.index')
                ->with('success', 'Service updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Oops! We couldn't change the service. Please try again.")
                ->withInput();
        }
    }

    public function deleteService(Service $service)
    {
        try {
            $service->delete();
            ActivityLogController::log('Delete', 'Layanan Di Hapus : ' . $service->service_name, auth()->user()->user_id);

            return redirect()->route('admin.services.index')
                ->with('success', 'Service deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Oops! We couldn't delete the service. Please try again.")
                ->withInput();
        }
    }

    public function importService(Request $request)
    {
        try {
            Excel::import(new ServiceImport, $request->file('file')->store('temp'));
            ActivityLogController::log('Create', 'Layanan Ditambahkan Secara masal ', auth()->user()->user_id);

            return redirect()->route('admin.services.index')
                ->with('success', 'Service added successfully!');
        } catch (\Exception $e) {
            Log::error('Import Service Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', "Oops! We couldn't add the service. Please try again.")
                ->withInput();
        }
    }

    public function exportService()
    {
        try {
            ActivityLogController::log('Other', 'Layanan Diexport', auth()->user()->user_id);

            return Excel::download(new ServiceExport, 'services.xlsx');
        } catch (\Exception $e) {
            Log::error('export Service Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', "Oops! We couldn't export the services. Please try again.");
        }
    }


}
