<?php

namespace App\Http\Controllers;

use App\Models\Parfume;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

class OrderAdminController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function order()
    {
        $services = Service::all();
        $customers = Customer::all();
        $parfumes = Parfume::all();
        return view('admin.order', [
            'services' => $services,
            'customers' => $customers,
            'parfumes' => $parfumes
        ]);
    }

    public function storeOrder(StoreOrderRequest $request)
    {
        try {
            $this->orderService->createOrder($request->validated());

            return redirect()->route('admin.orders.index')
                ->with('success', 'Order created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Oops! We couldn't create the order. Please try again.")
                ->withInput();
        }
    }

    public function updateOrder(UpdateOrderRequest $request, Transaction $order)
    {
        try {
            $this->orderService->updateOrder($order, $request->validated());

            return redirect()->route('admin.transactions.index')
                ->with('success', 'Order updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Oops! We couldn't update the order. Please try again.")
                ->withInput();
        }
    }


    public function deleteOrder(Transaction $order)
    {
        try {
            DB::beginTransaction();

            // $order->details()->delete();

            $order->delete();

            DB::commit();
            ActivityLogController::log('Delete', 'Transaksi Di Hapus : ' . $order->transaction_code, auth()->user()->user_id);

            return redirect()->route('admin.transactions.index')
                ->with('success', 'Order deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete Order Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', "Oops! We couldn't delete the order. Please try again.");
        }
    }
}

