@extends('layouts.admin')

@section('title', 'Transaksi - LaundryPRO')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/transactions.css') }}">
@endpush

@section('content')
    <div class="mb-8 fade-in-up">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Manajemen Transaksi</h1>
                <p class="text-gray-600 flex items-center gap-2">
                    <i data-feather="shopping-bag" class="w-4 h-4"></i>
                    Kelola semua transaksi laundry
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 fade-in-up" style="animation-delay: 0.1s;">
        <div class="stat-card bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-500 rounded-xl shadow-lg">
                    <i data-feather="shopping-cart" class="w-6 h-6 text-white"></i>
                </div>
                <span class="text-sm font-medium text-blue-700 bg-blue-200 px-3 py-1 rounded-full">Total</span>
            </div>
            <h3 class="text-3xl font-bold text-blue-900 mb-1">{{ $total_transactions }}</h3>
            <p class="text-blue-700 text-sm font-medium">Total Transaksi</p>
        </div>

        <div class="stat-card bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-amber-500 rounded-xl shadow-lg">
                    <i data-feather="clock" class="w-6 h-6 text-white"></i>
                </div>
                <span class="text-sm font-medium text-amber-700 bg-amber-200 px-3 py-1 rounded-full">Proses</span>
            </div>
            <h3 class="text-3xl font-bold text-amber-900 mb-1">{{ $processing_transactions }}</h3>
            <p class="text-amber-700 text-sm font-medium">Sedang Diproses</p>
        </div>

        <div class="stat-card bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-green-500 rounded-xl shadow-lg">
                    <i data-feather="check-circle" class="w-6 h-6 text-white"></i>
                </div>
                <span class="text-sm font-medium text-green-700 bg-green-200 px-3 py-1 rounded-full">Lunas</span>
            </div>
            <h3 class="text-3xl font-bold text-green-900 mb-1">{{ $paid_transactions }}</h3>
            <p class="text-green-700 text-sm font-medium">Sudah Dibayar</p>
        </div>

        <div class="stat-card bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-purple-500 rounded-xl shadow-lg">
                    <i data-feather="dollar-sign" class="w-6 h-6 text-white"></i>
                </div>
                <span class="text-sm font-medium text-purple-700 bg-purple-200 px-3 py-1 rounded-full">Hari Ini</span>
            </div>
            <h3 class="text-3xl font-bold text-purple-900 mb-1">Rp {{ formatRupiahSingkat($today_total_income) }}</h3>
            <p class="text-purple-700 text-sm font-medium">Total Pendapatan</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden fade-in-up" style="animation-delay: 0.2s;">
        <div id="bulk-actions-bar" class="bulk-actions-bar bg-indigo-600 px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-white font-medium">
                    <span id="selected-count">0</span> transaksi dipilih
                </span>
            </div>
            <div class="flex items-center gap-2">
                <button id="bulk-email-btn" data-url ="{{ route('admin.transactions.sendBulk-email') }}"
                    class="action-btn px-4 py-2 bg-white text-indigo-600 rounded-lg font-medium hover:bg-gray-100 transition-colors flex items-center gap-2">
                    <i data-feather="mail" class="w-4 h-4"></i>
                    <span>Kirim Email</span>
                </button>
                <button id="bulk-whatsapp-btn"
                    class="action-btn px-4 py-2 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors flex items-center gap-2">
                    <i data-feather="message-circle" class="w-4 h-4"></i>
                    <span>Kirim WhatsApp</span>
                </button>
                <button id="bulk-clear-btn"
                    class="action-btn px-4 py-2 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors">
                    <i data-feather="x" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <div class="p-6 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
            <div class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <i data-feather="search"
                            class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                        <input type="text" id="search-transaction"
                            placeholder="Cari kode transaksi atau nama pelanggan..."
                            class="input-field w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>
                </div>

                <div class="flex gap-2 flex-wrap">
                    <div class="relative">
                        <button id="filter-bayar-btn"
                            class="action-btn px-4 py-3 bg-white text-gray-700 rounded-xl hover:bg-gray-50 border border-gray-200 shadow-sm flex items-center gap-2">
                            <i data-feather="credit-card" class="w-4 h-4"></i>
                            <span>Status Bayar</span>
                            <i data-feather="chevron-down" class="w-4 h-4"></i>
                        </button>
                        <div id="dropdown-bayar"
                            class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-10">
                            <button
                                class="w-full px-4 py-2 text-left hover:bg-gray-50 text-sm text-gray-700 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                Semua
                            </button>
                            <button
                                class="w-full px-4 py-2 text-left hover:bg-gray-50 text-sm text-green-600 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                Lunas
                            </button>
                            <button
                                class="w-full px-4 py-2 text-left hover:bg-gray-50 text-sm text-amber-600 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                DP
                            </button>
                            <button
                                class="w-full px-4 py-2 text-left hover:bg-gray-50 text-sm text-red-600 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                Belum Lunas
                            </button>
                        </div>
                    </div>

                    <div class="relative">
                        <button id="filter-laundry-btn"
                            class="action-btn px-4 py-3 bg-white text-gray-700 rounded-xl hover:bg-gray-50 border border-gray-200 shadow-sm flex items-center gap-2">
                            <i data-feather="loader" class="w-4 h-4"></i>
                            <span>Status Laundry</span>
                            <i data-feather="chevron-down" class="w-4 h-4"></i>
                        </button>
                        <div id="dropdown-laundry"
                            class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-10">
                            <button
                                class="w-full px-4 py-2 text-left hover:bg-gray-50 text-sm text-gray-700 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                Semua
                            </button>
                            <button
                                class="w-full px-4 py-2 text-left hover:bg-gray-50 text-sm text-amber-600 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                Proses
                            </button>
                            <button
                                class="w-full px-4 py-2 text-left hover:bg-gray-50 text-sm text-blue-600 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                Siap Diambil
                            </button>
                            <button
                                class="w-full px-4 py-2 text-left hover:bg-gray-50 text-sm text-purple-600 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                                Selesai
                            </button>
                        </div>
                    </div>

                    <button
                        class="action-btn px-4 py-3 bg-white text-gray-700 rounded-xl hover:bg-gray-50 border border-gray-200 shadow-sm flex items-center gap-2">
                        <i data-feather="download" class="w-4 h-4"></i>
                        <span class="hidden sm:inline">Export</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <input type="checkbox" id="select-all" class="checkbox-custom">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Pelanggan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kasir
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status
                            Bayar</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status
                            Laundry</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @include('admin.partials.transaction-rows')
                </tbody>
            </table>
        </div>

        @if ($transactions->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Menampilkan <span class="font-semibold">{{ $transactions->firstItem() }}</span> hingga
                    <span class="font-semibold">{{ $transactions->lastItem() }}</span> dari
                    <span class="font-semibold">{{ $transactions->total() }}</span> transaksi
                </div>
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

    @foreach ($transactions as $transaction)
        <div id="detail-modal-{{ $transaction->transaction_id }}"
            class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white p-6 border-b border-gray-200 rounded-t-2xl z-10">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-indigo-100 rounded-lg">
                                <i data-feather="file-text" class="w-5 h-5 text-indigo-600"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Detail Transaksi</h3>
                                <p class="text-sm text-gray-500">{{ $transaction->transaction_code }}</p>
                            </div>
                        </div>
                        <button
                            class="close-modal text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <i data-feather="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-5">
                            <h4 class="text-sm font-semibold text-blue-900 mb-4 flex items-center gap-2">
                                <i data-feather="user" class="w-4 h-4"></i>
                                Informasi Pelanggan
                            </h4>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($transaction->customer->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $transaction->customer->name)[1] ?? '', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $transaction->customer->name }}</div>
                                        <div class="text-sm text-gray-600">{{ $transaction->customer->phone }}</div>
                                    </div>
                                </div>
                                <div class="pt-3 border-t border-blue-200 text-sm text-gray-600">
                                    <i data-feather="map-pin" class="w-4 h-4 inline mr-2"></i>
                                    {{ $transaction->customer->address }}
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-5">
                            <h4 class="text-sm font-semibold text-purple-900 mb-4 flex items-center gap-2">
                                <i data-feather="info" class="w-4 h-4"></i>
                                Informasi Transaksi
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Kode Transaksi</span>
                                    <span class="font-semibold text-gray-900">{{ $transaction->transaction_code }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tanggal</span>
                                    <span
                                        class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($transaction->created_at)->translatedFormat('d M Y, H:i') }}
                                        WIB</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Kasir</span>
                                    <span class="font-semibold text-gray-900">{{ $transaction->user->name }}</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-purple-200">
                                    <span class="text-gray-600">Status Bayar</span>
                                    @php
                                        $paymentBadge = [
                                            'Paid' => [
                                                'class' => 'bg-green-500',
                                                'icon' => 'check',
                                                'label' => 'Lunas',
                                            ],
                                            'Partial' => [
                                                'class' => 'bg-amber-500',
                                                'icon' => 'percent',
                                                'label' => 'DP',
                                            ],
                                            'Unpaid' => [
                                                'class' => 'bg-red-500',
                                                'icon' => 'x',
                                                'label' => 'Belum Lunas',
                                            ],
                                        ];
                                        $badge = $paymentBadge[$transaction->payment_status] ?? $paymentBadge['Unpaid'];
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold {{ $badge['class'] }} text-white">
                                        <i data-feather="{{ $badge['icon'] }}" class="w-3 h-3"></i>
                                        {{ $badge['label'] }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status Laundry</span>
                                    @php
                                        $laundryBadge = [
                                            'Pending' => [
                                                'class' => 'bg-amber-500',
                                                'icon' => 'loader',
                                                'label' => 'Masuk',
                                            ],
                                            'Process' => [
                                                'class' => 'bg-blue-500',
                                                'icon' => 'loader',
                                                'label' => 'Proses',
                                            ],
                                            'Completed' => [
                                                'class' => 'bg-green-500',
                                                'icon' => 'check-circle',
                                                'label' => 'Siap Diambil',
                                            ],
                                            'Picked Up' => [
                                                'class' => 'bg-purple-500',
                                                'icon' => 'package',
                                                'label' => 'Selesai',
                                            ],
                                        ];
                                        $laundry =
                                            $laundryBadge[$transaction->laundry_status] ?? $laundryBadge['process'];
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold {{ $laundry['class'] }} text-white">
                                        <i data-feather="{{ $laundry['icon'] }}" class="w-3 h-3"></i>
                                        {{ $laundry['label'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 mb-6">
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <i data-feather="droplet" class="w-4 h-4 text-indigo-600"></i>
                            <span class="font-semibold">Parfum:</span>
                            <span>{{ $transaction->parfume->parfume_name }}</span>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-5 mb-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i data-feather="shopping-bag" class="w-4 h-4"></i>
                            Detail Pesanan
                        </h4>
                        <div class="space-y-3">
                            @foreach ($transaction->details as $detail)
                                <div class="bg-white rounded-lg p-4 border border-gray-200">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $detail->service->service_name }}
                                            </div>
                                            <div class="text-sm text-gray-600">{{ $detail->qty }}
                                                {{ $detail->service->unit }} × Rp
                                                {{ number_format($detail->price, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-bold text-gray-900">Rp
                                                {{ number_format($detail->total, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-500 flex items-center gap-4">
                                        <span class="flex items-center gap-1">
                                            <i data-feather="calendar" class="w-3 h-3"></i>
                                            Estimasi: 3 hari
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-200 rounded-xl p-5">
                        <h4 class="text-sm font-semibold text-indigo-900 mb-4 flex items-center gap-2">
                            <i data-feather="credit-card" class="w-4 h-4"></i>
                            Ringkasan Pembayaran
                        </h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between text-gray-700">
                                <span>Subtotal</span>
                                <span class="font-semibold">Rp
                                    {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-700">
                                <span>Diskon</span>
                                <span class="font-semibold text-green-600">- Rp
                                    {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                            </div>
                            <div class="pt-3 border-t-2 border-indigo-300 flex justify-between items-center">
                                <span class="font-bold text-indigo-900 text-base">Total Pembayaran</span>
                                <span class="font-bold text-indigo-900 text-xl">Rp
                                    {{ number_format($transaction->total, 0, ',', '.') }}</span>
                            </div>
                             <div class="pt-3 border-t border-indigo-200 flex justify-between text-gray-700">
                                <span>Sudah Dibayar</span>
                                <span class="font-semibold text-green-600">Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-700">
                                <span>Sisa Pembayaran</span>
                                <span class="font-semibold text-red-600">Rp {{ number_format($transaction->remaining_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="pt-3 border-t border-indigo-200 flex justify-between text-gray-700">
                                <span>Metode Pembayaran</span>
                                 <span class="font-semibold">
                                    @foreach($transaction->payments->unique('payment_method') as $payment)
                                        {{ ucwords($payment->payment_method) }}{{ !$loop->last ? ',' : '' }}
                                    @endforeach
                                </span>
                            </div>
                        </div>
                    </div>

                    @if ($transaction->note)
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 mt-6">
                            <h4 class="text-sm font-semibold text-amber-900 mb-2 flex items-center gap-2">
                                <i data-feather="message-square" class="w-4 h-4"></i>
                                Catatan
                            </h4>
                            <p class="text-sm text-gray-700">{{ $transaction->note }}</p>
                        </div>
                    @endif
                </div>

                <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-2xl flex gap-3">
                    <button
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl font-medium hover:from-indigo-700 hover:to-blue-700 shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                        <i data-feather="printer" class="w-5 h-5"></i>
                        <span>Cetak Invoice</span>
                    </button>
                    <button
                        class="close-modal px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <div id="edit-modal-{{ $transaction->transaction_id }}"
            class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white p-6 border-b border-gray-200 rounded-t-2xl z-10">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-indigo-100 rounded-lg">
                                <i data-feather="edit" class="w-5 h-5 text-indigo-600"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Edit Transaksi</h3>
                                <p class="text-sm text-gray-500">{{ $transaction->transaction_code }}</p>
                            </div>
                        </div>
                        <button
                            class="close-modal text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <i data-feather="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <form action="{{ route('admin.orders.update', $transaction->transaction_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <h4 class="font-semibold text-gray-800 mb-3">Ringkasan Pembayaran</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Total Tagihan</span>
                                        <span class="font-bold text-gray-900">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Sudah Dibayar</span>
                                        <span class="font-semibold text-green-600">Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between pt-2 border-t">
                                        <span class="font-bold text-gray-900">Sisa Tagihan</span>
                                        <span class="font-bold text-red-600">Rp {{ number_format($transaction->remaining_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- @if($transaction->payment_status != 'Paid')
                            <div id="new-payment-section-{{ $transaction->transaction_id }}">
                                <h4 class="font-semibold text-gray-800 mb-3">Lakukan Pembayaran Baru</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                     <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Jumlah Pembayaran Baru
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                            <input type="number" name="amount_paid_now" value="0" placeholder="0" min="0" max="{{ $transaction->remaining_amount }}"
                                                class="input-field w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                           Metode Pembayaran
                                        </label>
                                        <select name="payment_method_update"
                                            class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                            <option value="cash">Cash</option>
                                            <option value="digital">Digital</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endif --}}

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Status Laundry <span class="text-red-500">*</span>
                                </label>
                                <select name="laundry_status" required
                                    class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                    <option value="Pending"
                                        {{ $transaction->laundry_status == 'Pending' ? 'selected' : '' }}>Masuk</option>
                                    <option value="Process"
                                        {{ $transaction->laundry_status == 'Process' ? 'selected' : '' }}>Proses</option>
                                    <option value="Completed"
                                        {{ $transaction->laundry_status == 'Completed' ? 'selected' : '' }}>Siap Diambil
                                    </option>
                                    <option value="Picked Up"
                                        {{ $transaction->laundry_status == 'Picked Up' ? 'selected' : '' }}>Selesai
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Catatan
                                </label>
                                <textarea name="note" rows="3" placeholder="Tambahkan catatan untuk transaksi ini..."
                                    class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-all">{{ $transaction->note }}</textarea>
                            </div>
                        </div>

                        <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                            <button type="submit"
                                class="save-edit-btn flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl font-medium hover:from-indigo-700 hover:to-blue-700 shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                                <i data-feather="check" class="w-5 h-5"></i>
                                <span>Perbarui Transaksi</span>
                            </button>
                            <button type="button"
                                class="close-modal flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/transactions.js') }}"></script>
@endpush
