<?php

namespace App\Http\Controllers;

use toastr;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Exports\ServiceExport;
use App\Imports\ServiceImport;
use Flasher\Prime\FlasherInterface;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ActivityLogController;

class ServicesAdminController extends Controller
{
    public function services()
    {
        $stats = Service::getStatistics();
        $services = Service::withCount('detailTransactions')
            ->paginate(6);

        return view('admin.services', array_merge($stats, ['services' => $services]));
    }

    public function storeService(StoreServiceRequest $request)
    {
        $data = $request->validated();

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

    public function updateService(UpdateServiceRequest $request, Service $service)
    {
        $data = $request->validated();

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
