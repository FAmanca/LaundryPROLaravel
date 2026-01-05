<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Parfume;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ActivityLogController;

class OrderAdminController extends Controller
{
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

    public function storeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '_token' => 'required|string',
            'customer_id' => 'required|exists:customers,customer_id',
            'parfume_id' => 'required|exists:parfumes,parfume_id',
            'date' => 'required|date',
            'payment_method' => 'required|in:cash,transfer,qris',
            'payment-status' => 'required|in:paid,unpaid,downpayment',
            'downpayment_amount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'total_amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', implode("\n", $validator->errors()->all()));
        }

        try {
            DB::beginTransaction();
            $transaction_code = 'LP' . Carbon::now()->format('m') . strtoupper(uniqid());
            $subtotal = 0;

            $order = new Transaction();
            $order->transaction_code = $transaction_code;
            $order->customer_id = $request->input('customer_id');
            $order->parfume_id = $request->input('parfume_id');
            $order->user_id = auth()->user()->user_id;
            $order->estimated_date = $request->input('date');
            $order->payment_method = $request->input('payment_method');
            $order->payment_status = $request->input('payment-status') == 'downpayment' ? 'Partial' : $request->input('payment-status');

            foreach ($request->input('items') as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $total = $subtotal - ($request->input('discount') ?? 0);
            $downpayment_amount = $request->input('downpayment_amount') ?? 0;
            $remaining_amount = $total - $downpayment_amount;

            $order->subtotal = $subtotal;
            $order->total = $total;
            $order->discount = $request->input('discount') ?? 0;

            $order->remaining_paid = $remaining_amount;

            if ($request->input('payment-status') == 'paid') {
                $order->remaining_paid = 0;
                $order->ammount_paid = $total;
            } else {
                $order->ammount_paid = $downpayment_amount;
            }

            $order->save();

            foreach ($request->input('items') as $item) {
                $detail = $order->details()->create([
                    'transaction_id' => $order->transaction_id,
                    'service_id' => $item['service_id'],
                    'price' => $item['price'],
                    'qty' => $item['quantity'],
                    'total' => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();
            ActivityLogController::log('Create', 'Transaksi Baru Di Tambahkan : ' . $transaction_code, auth()->user()->user_id);

            return redirect()->route('admin.orders.index')
                ->with('success', 'Order created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store Order Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', "Oops! We couldn't create the order. Please try again.")
                ->withInput();
        }
    }

    public function updateOrder(Transaction $order, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|in:Paid,Unpaid,Partial',
            'laundry_status' => 'required|in:Pending,Process,Completed,Picked Up',
            'ammount_paid' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', implode("\n", $validator->errors()->all()));
        }

        try {
            DB::beginTransaction();

            $order->payment_status = $request->input('payment_status');

            if ($order->payment_status === 'Paid') {
                $order->ammount_paid = $order->total;
                $order->remaining_paid = 0;
            } elseif ($order->payment_status === 'Partial') {
                $order->ammount_paid = $request->input('ammount_paid', 0);
                $order->remaining_paid = $order->total - $order->ammount_paid;
            } else {
                $order->ammount_paid = 0;
                $order->remaining_paid = $order->total;
            }

            $order->laundry_status = $request->input('laundry_status');
            $order->note = $request->input('note');

            $order->save();

            DB::commit();
            ActivityLogController::log('Update', 'Transaksi Di Ubah : ' . $order->transaction_code, auth()->user()->user_id);

            return redirect()->route('admin.transactions.index')
                ->with('success', 'Order updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
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
