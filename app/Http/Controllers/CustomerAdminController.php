<?php

namespace App\Http\Controllers;

use App\Exports\CustomerExport;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ActivityLogController;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Imports\CustomerImport;

class CustomerAdminController extends Controller
{
    public function customers()
    {
        $customers = Customer::withCount('transactions')
            ->withSum('transactions', 'total')
            ->paginate(10);

        $stats = Customer::getStatistics();

        return view('admin.customers', [
            'customers' => $customers,
            'averageLifetime' => $stats['averageLifetime'],
            'newcustomers' => $stats['newCustomers'],
            'activeCount' => $stats['activeCount']
        ]);
    }

    public function storeCustomer(StoreCustomerRequest $request)
    {
        $data = $request->validated();

        try {
            Customer::create($data);
            ActivityLogController::log('Create', 'Pelanggan Baru Di Tambahkan : ' . $data['name'], auth()->user()->user_id);

            return redirect()->route('admin.customers.index')
                ->with('success', 'Customer added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Oops! We couldn't add the Customer. Please try again.")
                ->withInput();
        }
    }

    public function updateCustomer(UpdateCustomerRequest $request, Customer $customer)
    {
        $data = $request->validated();

        try {
            $customer->update($data);
            ActivityLogController::log('Update', 'Data Pelanggan Di Perbarui : ' . $data['name'], auth()->user()->user_id);

            return redirect()->route('admin.customers.index')
                ->with('success', 'Customer updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Oops! We couldn't update the Customer. Please try again.")
                ->withInput();
        }
    }

    public function deleteCustomer(Customer $customer)
    {
        try {
            $customer->delete();

            ActivityLogController::log('Delete', 'Data Pelanggan Di Hapus : ' . $customer->name, auth()->user()->user_id);
            return redirect()->route('admin.customers.index')
                ->with('success', 'Customer deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Oops! We couldn't delete the Customer. Please try again.");
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json(['customers' => []]);
        }

        $customers = Customer::select('customer_id', 'name', 'phone', 'address', 'created_at', 'email')
            ->withCount('transactions')
            ->withSum('transactions', 'total')
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%")
                    ->orWhere('phone', 'LIKE', "%{$query}%");
            })
            ->orderBy('name')
            ->limit(10)
            ->get();

        return response()->json([
            'customers' => $customers
        ]);
    }

    public function importCustomer(Request $request)
    {
        try {
            Excel::import(new CustomerImport, $request->file('file')->store('temp'));
            ActivityLogController::log('Create', 'Pelanggan Ditambahkan Secara masal ', auth()->user()->user_id);

            return redirect()->route('admin.customers.index')
                ->with('success', 'Customers added successfully!');
        } catch (\Exception $e) {

            return redirect()->back()
                ->with('error', "Oops! We couldn't add the parfume. Please try again.")
                ->withInput();
        }
    }

    public function exportCustomer()
    {
        try {
            return Excel::download(new CustomerExport, 'customer.xlsx');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Oops! We couldn't export the parfume. Please try again.");
        }
    }
}
