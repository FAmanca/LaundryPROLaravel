@extends('layouts.admin')

@section('title', 'Payment - LaundryPRO')

@push('styles')
    <script type="text/javascript" src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endpush

@section('content')
<div class="container mx-auto p-8">
    <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg">
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800">Complete Your Payment</h1>
            <p class="text-gray-600">Please complete the payment for order #{{ $transaction->transaction_code }}</p>
        </div>

        <div id="payment-container">
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-500">Customer</p>
                        <p class="font-semibold">{{ $transaction->customer->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Amount to Pay</p>
                        <p class="font-semibold text-2xl text-primary-600">Rp {{ number_format($transaction->payments->where('status', 'pending')->last()->amount ?? $transaction->remaining_amount, 0, ',', '.') }}</p>
                    </div>
                </div>
                <p class="text-center text-gray-600">Your payment window will open automatically...</p>
            </div>
        </div>

        <div id="payment-status" class="p-6 text-center hidden">
            <div id="success-message" class="hidden">
                <i data-feather="check-circle" class="w-16 h-16 text-green-500 mx-auto"></i>
                <h2 class="text-2xl font-bold text-gray-800 mt-4">Payment Successful!</h2>
                <p class="text-gray-600 mt-2">Thank you for your payment. Your order is being processed.</p>
                <a href="{{ route('admin.transactions.index') }}" class="mt-6 inline-block bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded-lg">
                    Back to Transactions
                </a>
            </div>
             <div id="pending-message" class="hidden">
                <i data-feather="loader" class="w-16 h-16 text-yellow-500 mx-auto animate-spin"></i>
                <h2 class="text-2xl font-bold text-gray-800 mt-4">Payment Pending</h2>
                <p class="text-gray-600 mt-2">Your payment is pending. We will update the status once the payment is confirmed.</p>
                 <a href="{{ route('admin.transactions.index') }}" class="mt-6 inline-block bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded-lg">
                    Back to Transactions
                </a>
            </div>
            <div id="error-message" class="hidden">
                 <i data-feather="alert-circle" class="w-16 h-16 text-amber-500 mx-auto"></i>
                <h2 class="text-2xl font-bold text-gray-800 mt-4">Payment Incomplete</h2>
                <p class="text-gray-600 mt-2">The payment window was closed or an error occurred. You can try paying again or return to the transaction list.</p>
                <div class="mt-6 flex items-center justify-center gap-3">
                     <form action="{{ route('admin.transactions.retry', $transaction->transaction_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-primary-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-primary-700 transition">
                            Retry Payment
                        </button>
                    </form>
                    <a href="{{ route('admin.transactions.index') }}" class="bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded-lg">
                        Back to Transactions
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showStatusView(status, result) {
        console.log(status, result);
        const paymentContainer = document.getElementById('payment-container');
        if (paymentContainer) {
            paymentContainer.classList.add('hidden');
        }

        const paymentStatus = document.getElementById('payment-status')
        if(paymentStatus) {
            paymentStatus.classList.remove('hidden');
        }

        const message = document.getElementById(status + '-message');
        if(message) {
            message.classList.remove('hidden');
        }
        feather.replace();
    }

    function pay() {
        snap.pay('{{ $snap_token }}', {
            onSuccess: function(result){
                showStatusView('success', result);
                window.location.href = "{{ route('admin.transactions.index') }}?payment=success";
            },
            onPending: function(result){
                showStatusView('pending', result);
                 window.location.href = "{{ route('admin.transactions.index') }}?payment=pending";
            },
            onError: function(result){
                showStatusView('error', result);
            },
            onClose: function(){
                console.log('customer closed the popup without finishing the payment');
                showStatusView('error', {message: 'Popup closed'});
            }
        });
    }

    document.addEventListener('DOMContentLoaded', (event) => {
        const snapToken = '{{ $snap_token }}';
        if (snapToken) {
            pay();
        } else {
            showStatusView('error', {message: 'No valid payment token found.'});
        }
        feather.replace();
    });
</script>
@endpush
