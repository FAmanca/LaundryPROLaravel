@extends('layouts.admin')

@section('title', 'Transaksi - LaundryPRO')

@push('styles')
    <style>
        /* Modal Animation */
        .modal-overlay {
            transition: opacity 0.3s ease-in-out;
            opacity: 0;
        }

        .modal-overlay.modal-show {
            opacity: 1;
        }

        .modal-content {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s ease-out;
            transform: scale(0.9) translateY(-30px);
            opacity: 0;
        }

        .modal-overlay.modal-show .modal-content {
            transform: scale(1) translateY(0);
            opacity: 1;
        }

        /* Table Row Hover */
        .transaction-row {
            transition: all 0.2s ease;
        }

        .transaction-row:hover {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            transform: translateX(4px);
        }

        /* Checkbox Styling */
        .checkbox-custom {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #4f46e5;
        }

        /* Button Animations */
        .action-btn {
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .action-btn:active {
            transform: translateY(0);
        }

        /* Badge Animations */
        .badge-paid {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            animation: pulse-subtle 2s ease-in-out infinite;
        }

        .badge-dp {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            animation: pulse-subtle 2s ease-in-out infinite;
        }

        .badge-unpaid {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .badge-process {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            animation: pulse-subtle 2s ease-in-out infinite;
        }

        .badge-ready {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .badge-done {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }

        @keyframes pulse-subtle {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.85;
            }
        }

        /* Fade In Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        /* Input Focus Effect */
        .input-field {
            transition: all 0.3s ease;
        }

        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
        }

        /* Scrollbar Styling */
        .modal-content::-webkit-scrollbar {
            width: 8px;
        }

        .modal-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .modal-content::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .modal-content::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Stats Card Hover */
        .stat-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.15);
        }

        /* Dropdown Animation */
        .dropdown-menu {
            transition: all 0.2s ease-in-out;
            transform-origin: top;
        }

        .dropdown-menu.hidden {
            transform: scaleY(0);
            opacity: 0;
        }

        .dropdown-menu:not(.hidden) {
            transform: scaleY(1);
            opacity: 1;
        }

        /* Bulk Actions Bar */
        .bulk-actions-bar {
            transition: all 0.3s ease;
            transform: translateY(-100%);
            opacity: 0;
        }

        .bulk-actions-bar.show {
            transform: translateY(0);
            opacity: 1;
        }
    </style>
@endpush

@section('content')
    <!-- Header Section -->
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

    <!-- Stats Cards -->
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

    <!-- Main Content Card -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden fade-in-up" style="animation-delay: 0.2s;">
        <!-- Bulk Actions Bar -->
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

        <!-- Card Header with Search & Filter -->
        <div class="p-6 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Search Bar -->
                <div class="flex-1">
                    <div class="relative">
                        <i data-feather="search"
                            class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                        <input type="text" id="search-transaction"
                            placeholder="Cari kode transaksi atau nama pelanggan..."
                            class="input-field w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>
                </div>

                <!-- Filter Buttons -->
                <div class="flex gap-2 flex-wrap">
                    <!-- Status Bayar Filter -->
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

                    <!-- Status Laundry Filter -->
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

                    <!-- Export Button -->
                    <button
                        class="action-btn px-4 py-3 bg-white text-gray-700 rounded-xl hover:bg-gray-50 border border-gray-200 shadow-sm flex items-center gap-2">
                        <i data-feather="download" class="w-4 h-4"></i>
                        <span class="hidden sm:inline">Export</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
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

        <!-- Pagination -->
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

    <!-- Detail & Edit Modals -->
    @foreach ($transactions as $transaction)
        <!-- Detail Modal -->
        <div id="detail-modal-{{ $transaction->transaction_id }}"
            class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
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

                <!-- Modal Body -->
                <div class="p-6">
                    <!-- Customer & Transaction Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Customer Info -->
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

                        <!-- Transaction Info -->
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

                    <!-- Parfum Info -->
                    <div class="bg-gray-50 rounded-xl p-4 mb-6">
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <i data-feather="droplet" class="w-4 h-4 text-indigo-600"></i>
                            <span class="font-semibold">Parfum:</span>
                            <span>{{ $transaction->parfume->parfume_name }}</span>
                        </div>
                    </div>

                    <!-- Order Items -->
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

                    <!-- Payment Summary -->
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
                            @if ($transaction->payment_status == 'Partial')
                                <div class="pt-3 border-t border-indigo-200 flex justify-between text-gray-700">
                                    <span>Sudah Dibayar (DP)</span>
                                    <span class="font-semibold text-green-600">Rp
                                        {{ number_format($transaction->ammount_paid, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-gray-700">
                                    <span>Sisa Pembayaran</span>
                                    <span class="font-semibold text-red-600">Rp
                                        {{ number_format($transaction->total - $transaction->ammount_paid, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="pt-3 border-t border-indigo-200 flex justify-between text-gray-700">
                                <span>Metode Pembayaran</span>
                                <span class="font-semibold">{{ ucwords($transaction->payment_method) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
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

                <!-- Modal Footer -->
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

        <!-- Edit Modal -->
        <div id="edit-modal-{{ $transaction->transaction_id }}"
            class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
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

                <!-- Modal Body -->
                <div class="p-6">
                    <form action="{{ route('admin.orders.update', $transaction->transaction_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-5">
                            <!-- Status Pembayaran -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Status Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <select name="payment_status" required
                                    class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                    <option value="Unpaid"
                                        {{ $transaction->payment_status == 'Unpaid' ? 'selected' : '' }}>Belum Lunas
                                    </option>
                                    <option value="Partial"
                                        {{ $transaction->payment_status == 'Partial' ? 'selected' : '' }}>DP (Sebagian)
                                    </option>
                                    <option value="Paid" {{ $transaction->payment_status == 'Paid' ? 'selected' : '' }}>
                                        Lunas</option>
                                </select>
                            </div>

                            <!-- Jumlah Dibayar (untuk DP) -->
                            <div id="amount-paid-field-{{ $transaction->transaction_id }}"
                                class="{{ $transaction->payment_status == 'Partial' ? '' : 'hidden' }}">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Jumlah Dibayar (DP)
                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                    <input type="number" name="ammount_paid" value="{{ $transaction->ammount_paid }}"
                                        placeholder="0"
                                        class="input-field w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Total: Rp
                                    {{ number_format($transaction->total, 0, ',', '.') }}</p>
                            </div>

                            <!-- Status Laundry -->
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

                            <!-- Catatan -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Catatan
                                </label>
                                <textarea name="note" rows="3" placeholder="Tambahkan catatan untuk transaksi ini..."
                                    class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-all">{{ $transaction->notes }}</textarea>
                            </div>
                        </div>

                        <!-- Modal Footer -->
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
    <script>
        // Initialize Feather Icons
        feather.replace();

        // ========== MODAL HELPER FUNCTIONS ==========
        function openModal(modalElement) {
            modalElement.classList.remove('hidden');
            setTimeout(() => {
                modalElement.classList.add('modal-show');
                feather.replace();
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalElement) {
            modalElement.classList.remove('modal-show');
            setTimeout(() => {
                modalElement.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        // ========== VIEW DETAIL MODAL ==========
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.viewId;
                const modal = document.getElementById(`detail-modal-${id}`);
                if (modal) openModal(modal);
            });
        });

        // ========== EDIT MODAL ==========
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.editId;
                const modal = document.getElementById(`edit-modal-${id}`);
                if (modal) openModal(modal);
            });
        });

        // Close modals
        document.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                const modal = btn.closest('.modal-overlay');
                if (modal) closeModal(modal);
            });
        });

        // Close on backdrop click
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal(modal);
            });
        });

        // ========== PAYMENT STATUS CHANGE ==========
        document.querySelectorAll('select[name="payment_status"]').forEach(select => {
            select.addEventListener('change', function() {
                const modal = this.closest('.modal-overlay');
                const transactionId = modal.id.replace('edit-modal-', '');
                const amountField = document.getElementById(`amount-paid-field-${transactionId}`);

                if (this.value === 'Partial') {
                    amountField?.classList.remove('hidden');
                } else {
                    amountField?.classList.add('hidden');
                }
            });
        });

        // ========== CHECKBOX SELECTION ==========
        const selectAllCheckbox = document.getElementById('select-all');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const bulkActionsBar = document.getElementById('bulk-actions-bar');
        const selectedCountSpan = document.getElementById('selected-count');
        const bulkClearBtn = document.getElementById('bulk-clear-btn');

        function updateBulkActions() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            const count = checkedBoxes.length;

            if (count > 0) {
                bulkActionsBar.classList.add('show');
                selectedCountSpan.textContent = count;
            } else {
                bulkActionsBar.classList.remove('show');
            }
        }

        selectAllCheckbox?.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });

        rowCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                updateBulkActions();

                const allChecked = Array.from(rowCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(rowCheckboxes).some(cb => cb.checked);

                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = someChecked && !allChecked;
                }
            });
        });

        bulkClearBtn?.addEventListener('click', () => {
            rowCheckboxes.forEach(checkbox => checkbox.checked = false);
            if (selectAllCheckbox) selectAllCheckbox.checked = false;
            updateBulkActions();
        });

        // ========== EMAIL REMINDER ==========
        document.querySelectorAll('.email-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const customerName = btn.dataset.customerName;
                const customerEmail = btn.dataset.customerEmail;
                const transactionId = btn.dataset.emailId;
                const url = btn.dataset.url;
                const transactionCode = btn.dataset.transactionCode;


                Swal.fire({
                    title: 'Kirim Email Reminder?',
                    html: `
                        <div class="text-sm text-gray-700 leading-relaxed">
                            Email akan dikirim ke:
                            <div class="mt-3 p-3 rounded-lg bg-purple-50 text-left">
                                <p class="font-semibold text-gray-800">${customerEmail}</p>
                                <p class="text-gray-500 text-xs mt-1">Kode Transaksi: ${transactionCode}</p>
                            </div>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#8b5cf6',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i data-feather="mail" class="w-4 h-4 inline mr-1"></i> Kirim Email',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6',
                        cancelButton: 'rounded-xl px-6'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Swal.fire({
                        //     title: 'Mengirim email...',
                        //     allowOutsideClick: false,
                        //     showConfirmButton: false,
                        //     didOpen: () => Swal.showLoading()
                        // });

                        fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    email: customerEmail,
                                    name: customerName
                                })
                            })
                            // .then(res => res.json())
                            .then(data => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Email Terkirim!',
                                    text: `Reminder berhasil dikirim ke ${customerName}`,
                                    timer: 1500,
                                    showConfirmButton: false,
                                    customClass: {
                                        popup: 'rounded-2xl'
                                    }
                                });
                            })
                            .catch(err => {
                                console.error(err);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Mengirim Email!',
                                    text: 'Terjadi kesalahan saat mengirim email.',
                                    customClass: {
                                        popup: 'rounded-2xl'
                                    }
                                });
                            });
                    }
                });
            });
        });


        // ========== WHATSAPP REMINDER ==========
        document.querySelectorAll('.wa-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const customerName = btn.dataset.customerName;
                const customerPhone = btn.dataset.customerPhone;

                Swal.fire({
                    title: 'Kirim WhatsApp Reminder?',
                    html: `Pesan akan dikirim ke:<br><strong>${customerName}</strong><br>${customerPhone}`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i data-feather="message-circle" class="w-4 h-4 inline mr-1"></i> Kirim WhatsApp',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6',
                        cancelButton: 'rounded-xl px-6'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Mengirim pesan...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => Swal.showLoading()
                        });

                        setTimeout(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Pesan Terkirim!',
                                text: `Reminder berhasil dikirim ke ${customerName}`,
                                timer: 3000,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'rounded-2xl'
                                }
                            });
                        }, 1500);
                    }
                });
            });
        });

        // ========== BULK EMAIL ==========
        document.getElementById('bulk-email-btn')?.addEventListener('click', () => {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            const count = checkedBoxes.length;

            if (count === 0) {
                Swal.fire('Pilih minimal 1 pelanggan!', '', 'warning');
                return;
            }

            const transactionIds = Array.from(checkedBoxes).map(cb => cb.dataset.id);
            const url = document.getElementById('bulk-email-btn').dataset.url;

            Swal.fire({
                title: 'Kirim Email Massal?',
                text: `Email reminder akan dikirim ke ${count} pelanggan`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#8b5cf6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Kirim Email',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6',
                    cancelButton: 'rounded-xl px-6'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengirim email...',
                        text: `Mengirim ke ${count} penerima`,
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => Swal.showLoading()
                    });

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify({
                                transaction_ids: transactionIds
                            })
                        })
                        // .then(res => res.json())
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Semua Email Terkirim!',
                                text: `${count} email reminder berhasil dikirim`,
                                timer: 2000,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'rounded-2xl'
                                }
                            });

                            // Clear selection
                            bulkClearBtn.click();
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Mengirim Email!',
                                text: 'Terjadi kesalahan saat mengirim email.',
                                customClass: {
                                    popup: 'rounded-2xl'
                                }
                            });
                        });
                }
            });
        });

        // ========== BULK WHATSAPP ==========
        document.getElementById('bulk-whatsapp-btn')?.addEventListener('click', () => {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            const count = checkedBoxes.length;

            Swal.fire({
                title: 'Kirim WhatsApp Massal?',
                text: `Pesan reminder akan dikirim ke ${count} pelanggan`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Kirim WhatsApp',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6',
                    cancelButton: 'rounded-xl px-6'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengirim pesan...',
                        text: `Mengirim ke ${count} penerima`,
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => Swal.showLoading()
                    });

                    setTimeout(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Semua Pesan Terkirim!',
                            text: `${count} pesan WhatsApp berhasil dikirim`,
                            timer: 3000,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'rounded-2xl'
                            }
                        });

                        // Clear selection
                        bulkClearBtn.click();
                    }, 2000);
                }
            });
        });

        // ========== DROPDOWN FILTERS ==========
        const filterBayarBtn = document.getElementById('filter-bayar-btn');
        const dropdownBayar = document.getElementById('dropdown-bayar');
        const filterLaundryBtn = document.getElementById('filter-laundry-btn');
        const dropdownLaundry = document.getElementById('dropdown-laundry');

        filterBayarBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownBayar.classList.toggle('hidden');
            dropdownLaundry.classList.add('hidden');
            feather.replace();
        });

        filterLaundryBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownLaundry.classList.toggle('hidden');
            dropdownBayar.classList.add('hidden');
            feather.replace();
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', () => {
            dropdownBayar?.classList.add('hidden');
            dropdownLaundry?.classList.add('hidden');
        });

        // Handle dropdown item clicks
        dropdownBayar?.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                console.log('Filter Status Bayar:', btn.textContent.trim());
                dropdownBayar.classList.add('hidden');
                // Add your filter logic here
            });
        });

        dropdownLaundry?.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                console.log('Filter Status Laundry:', btn.textContent.trim());
                dropdownLaundry.classList.add('hidden');
                // Add your filter logic here
            });
        });

        // ========== SEARCH FUNCTIONALITY ==========
        const searchInput = document.getElementById('search-transaction');
        let searchTimeout = null;

        searchInput?.addEventListener('input', (e) => {
            const searchTerm = e.target.value.trim();

            clearTimeout(searchTimeout);

            if (searchTerm.length < 2) {
                document.getElementById('transactions-table-body').innerHTML = '';
                return;
            }

            searchTimeout = setTimeout(async () => {
                try {
                    console.log('Searching for:', searchTerm);
                    const params = new URLSearchParams({
                        q: searchTerm
                    });
                    const response = await fetch(`/admin/transactions/search?${params}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) throw new Error('Gagal memuat hasil pencarian');

                    const html = await response.text();
                    document.getElementById('transactions-table-body').innerHTML = html;
                    feather.replace();
                } catch (error) {
                    console.error(error);
                }
            }, 400);
        });


        // ========== DELETE CONFIRMATION ==========
        document.querySelectorAll('.delete-btn').forEach((deleteBtn) => {
            deleteBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const form = deleteBtn.closest('.delete-form');

                Swal.fire({
                    title: 'Hapus Transaksi?',
                    text: "Data transaksi akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6',
                        cancelButton: 'rounded-xl px-6'
                    },
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown animate__faster'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp animate__faster'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menghapus transaksi...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        form.submit();
                    }
                });
            });
        });

        // ========== FORM SUBMIT LOADING ==========
        document.querySelectorAll('.save-edit-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const form = e.target.closest('form');
                if (form.checkValidity()) {
                    Swal.fire({
                        title: 'Memperbarui transaksi...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }
            });
        });

        // ========== KEYBOARD SHORTCUTS ==========
        document.addEventListener('keydown', (e) => {
            // ESC key to close modals
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay.modal-show').forEach(modal => {
                    closeModal(modal);
                });
                dropdownBayar?.classList.add('hidden');
                dropdownLaundry?.classList.add('hidden');
            }

            // CTRL/CMD + K to focus search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                searchInput?.focus();
            }
        });

        // ========== AJAX SEARCH & FILTER SYSTEM ==========
        class TransactionFilter {
            constructor() {
                this.searchInput = document.getElementById('search-transaction');
                this.filterBayar = document.getElementById('dropdown-bayar');
                this.filterLaundry = document.getElementById('dropdown-laundry');
                this.tbody = document.querySelector('tbody');
                this.paginationContainer = document.querySelector('.pagination');
                this.searchTimeout = null;
                this.currentFilters = {
                    search: '',
                    payment_status: '',
                    laundry_status: ''
                };
                this.checkedIds = new Set();
                this.init();
            }

            init() {
                this.setupSearch();
                this.setupFilters();
                this.setupPagination();
                this.restoreCheckboxes();
            }

            // Save checkbox state
            saveCheckboxState() {
                this.checkedIds.clear();
                document.querySelectorAll('.row-checkbox:checked').forEach(cb => {
                    this.checkedIds.add(cb.dataset.id);
                });
            }

            // Restore checkbox state
            restoreCheckboxes() {
                this.checkedIds.forEach(id => {
                    const checkbox = document.querySelector(`.row-checkbox[data-id="${id}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
                updateBulkActions();
            }

            setupSearch() {
                if (!this.searchInput) return;

                this.searchInput.addEventListener('input', (e) => {
                    clearTimeout(this.searchTimeout);
                    this.currentFilters.search = e.target.value.trim();

                    this.searchTimeout = setTimeout(() => {
                        this.loadData();
                    }, 400);
                });
            }

            setupFilters() {
                // Payment Status Filter
                this.filterBayar?.querySelectorAll('button').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const text = btn.textContent.trim().toLowerCase();

                        if (text.includes('semua')) {
                            this.currentFilters.payment_status = '';
                        } else if (text.includes('lunas')) {
                            this.currentFilters.payment_status = 'Paid';
                        } else if (text.includes('dp')) {
                            this.currentFilters.payment_status = 'Partial';
                        } else if (text.includes('belum')) {
                            this.currentFilters.payment_status = 'Unpaid';
                        }

                        this.loadData();
                        this.filterBayar.classList.add('hidden');
                    });
                });

                // Laundry Status Filter
                this.filterLaundry?.querySelectorAll('button').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const text = btn.textContent.trim().toLowerCase();

                        if (text.includes('semua')) {
                            this.currentFilters.laundry_status = '';
                        } else if (text.includes('masuk')) {
                            this.currentFilters.laundry_status = 'Pending';
                        } else if (text.includes('proses')) {
                            this.currentFilters.laundry_status = 'Process';
                        } else if (text.includes('siap')) {
                            this.currentFilters.laundry_status = 'Completed';
                        } else if (text.includes('selesai')) {
                            this.currentFilters.laundry_status = 'Picked Up';
                        }

                        this.loadData();
                        this.filterLaundry.classList.add('hidden');
                    });
                });
            }

            setupPagination() {
                // Intercept pagination clicks
                document.addEventListener('click', (e) => {
                    const paginationLink = e.target.closest('.pagination a');
                    if (paginationLink && !paginationLink.classList.contains('disabled')) {
                        e.preventDefault();
                        const url = new URL(paginationLink.href);
                        const page = url.searchParams.get('page');
                        if (page) {
                            this.loadData(page);
                        }
                    }
                });
            }

            async loadData(page = 1) {
                this.saveCheckboxState();

                // Show loading
                this.showLoading();

                try {
                    const params = new URLSearchParams({
                        page: page,
                        ...this.currentFilters
                    });

                    // Remove empty params
                    for (let [key, value] of [...params.entries()]) {
                        if (!value) params.delete(key);
                    }

                    const response = await fetch(`/admin/transactions?${params}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    });

                    if (!response.ok) throw new Error('Failed to load data');

                    const html = await response.text();
                    this.tbody.innerHTML = html;

                    // Update pagination
                    this.updatePagination(response.url);

                    // Restore checkboxes
                    this.restoreCheckboxes();

                    // Reinitialize icons and event listeners
                    feather.replace();
                    this.reinitializeEventListeners();

                } catch (error) {
                    console.error('Error loading data:', error);
                    this.showError();
                }
            }

            updatePagination(url) {
                // You can fetch and update pagination separately if needed
                // For now, we'll keep the existing pagination
            }

            showLoading() {
                this.tbody.innerHTML = `
            <tr>
                <td colspan="9" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mb-4"></div>
                        <p class="text-sm text-gray-500">Memuat data...</p>
                    </div>
                </td>
            </tr>
        `;
            }

            showError() {
                this.tbody.innerHTML = `
            <tr>
                <td colspan="9" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="p-4 bg-red-100 rounded-full mb-4">
                            <i data-feather="alert-circle" class="w-12 h-12 text-red-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-1">Terjadi Kesalahan</h3>
                        <p class="text-sm text-gray-500">Gagal memuat data. Silakan coba lagi.</p>
                    </div>
                </td>
            </tr>
        `;
                feather.replace();
            }

            reinitializeEventListeners() {
                // Reinitialize all button event listeners
                // View buttons
                document.querySelectorAll('.view-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.dataset.viewId;
                        const modal = document.getElementById(`detail-modal-${id}`);
                        if (modal) openModal(modal);
                    });
                });

                // Edit buttons
                document.querySelectorAll('.edit-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.dataset.editId;
                        const modal = document.getElementById(`edit-modal-${id}`);
                        if (modal) openModal(modal);
                    });
                });

                // Email buttons
                document.querySelectorAll('.email-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        // Your email logic here
                    });
                });

                // WhatsApp buttons
                document.querySelectorAll('.wa-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        // Your WhatsApp logic here
                    });
                });

                // Delete buttons
                document.querySelectorAll('.delete-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        // Your delete logic here
                    });
                });

                // Checkboxes
                document.querySelectorAll('.row-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', () => {
                        updateBulkActions();

                        const allChecked = Array.from(document.querySelectorAll('.row-checkbox')).every(
                            cb => cb.checked);
                        const someChecked = Array.from(document.querySelectorAll('.row-checkbox')).some(
                            cb => cb.checked);

                        if (selectAllCheckbox) {
                            selectAllCheckbox.checked = allChecked;
                            selectAllCheckbox.indeterminate = someChecked && !allChecked;
                        }
                    });
                });
            }
        }

        // Initialize the filter system
        const transactionFilter = new TransactionFilter();

        // ========== AUTO-REFRESH FEATHER ICONS ==========
        const observer = new MutationObserver(() => {
            feather.replace();
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        // Initial icon replacement
        feather.replace();
    </script>
@endpush
