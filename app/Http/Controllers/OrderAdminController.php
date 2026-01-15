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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        Log::info('ENTER STORE ORDER PROCEDURE');
        try {
            Log::info('Store Order Trigger');
            $result = $this->orderService->createOrder($request->validated());
            Log::info($result);
            $order = $result['order'];
            $midtrans = $result['midtrans'];

            if ($midtrans && isset($midtrans['snap_token'])) {
                return redirect()->route('admin.orders.payment', ['transaction_id' => $order->transaction_id]);
            }

            return redirect()->route('admin.transactions.index')
                ->with('success', 'Order created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Oops! We couldn't create the order. Please try again.")
                ->withInput();
        }
    }

    public function showPaymentGateway($transaction_id)
    {
        $transaction = Transaction::with('payments')->findOrFail($transaction_id);
        $payment = $transaction->payments()->where('status', 'pending')->latest()->first();

        if (!$payment || !$payment->snap_token) {
            return redirect()->route('admin.transactions.index')->with('error', 'Payment token not found.');
        }

        return view('admin.payment-gateway', [
            'transaction' => $transaction,
            'snap_token' => $payment->snap_token
        ]);
    }

    public function retryPayment(Transaction $order)
    {
        try {
            $result = $this->orderService->retryDigitalPayment($order);
            $updatedOrder = $result['order'];
            $midtrans = $result['midtrans'];

            if ($midtrans && isset($midtrans['snap_token'])) {
                return redirect()->route('admin.orders.payment', ['transaction_id' => $updatedOrder->transaction_id]);
            }

            return redirect()->route('admin.transactions.index')
                ->with('error', 'Failed to initiate payment retry. Please try again.');
        } catch (\Exception $e) {
            Log::error('Retry Payment Controller Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', "Oops! We couldn't retry the payment. " . $e->getMessage());
        }
    }


    public function updateOrder(UpdateOrderRequest $request, Transaction $order)
    {
        Log::info("NYOBA UPDATE DI CONTROLLER ORDER");
        try {
            $result = $this->orderService->updateOrder($order, $request->validated());
            $updatedOrder = $result['order'];
            $midtrans = $result['midtrans'];

            if ($midtrans && isset($midtrans['snap_token'])) {
                 return redirect()->route('admin.orders.payment', ['transaction_id' => $updatedOrder->transaction_id]);
            }

            return redirect()->route('admin.transactions.index')
                ->with('success', 'Order updated successfully!');
        } catch (\Exception $e) {
            Log::error('Update Order Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', "Oops! We couldn't update the order. Please try again.")
                ->withInput();
        }
    }


    public function deleteOrder(Transaction $order)
    {
        try {
            DB::beginTransaction();

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

