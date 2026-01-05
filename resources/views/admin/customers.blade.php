@extends('layouts.admin')

@section('title', 'Pelanggan - LaundryPRO')

@push('styles')
    <style>
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

        .customer-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }

        .customer-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
        }

        .stat-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.15);
        }

        .btn-primary {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-primary:hover::before {
            width: 300px;
            height: 300px;
        }

        .action-btn {
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .action-btn:active {
            transform: translateY(0);
        }

        .avatar {
            transition: transform 0.3s ease;
        }

        .customer-card:hover .avatar {
            transform: scale(1.1) rotate(5deg);
        }

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

        .customer-card:nth-child(1) { animation-delay: 0.1s; }
        .customer-card:nth-child(2) { animation-delay: 0.2s; }
        .customer-card:nth-child(3) { animation-delay: 0.3s; }
        .customer-card:nth-child(4) { animation-delay: 0.4s; }
        .customer-card:nth-child(5) { animation-delay: 0.5s; }
        .customer-card:nth-child(6) { animation-delay: 0.6s; }

        .input-field {
            transition: all 0.3s ease;
        }

        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
        }

        .empty-state {
            animation: fadeInUp 0.8s ease-out;
        }

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
    </style>
@endpush

@section('content')
    <div class="mb-8 fade-in-up">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Manajemen Pelanggan</h1>
                <p class="text-gray-600 flex items-center gap-2">
                    <i data-feather="users" class="w-4 h-4"></i>
                    Kelola data pelanggan Anda
                </p>
            </div>
            <button id="add-customer-btn"
                class="btn-primary px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl font-medium hover:from-indigo-700 hover:to-blue-700 shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                <i data-feather="user-plus" class="w-5 h-5"></i>
                <span>Tambah Pelanggan</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 fade-in-up" style="animation-delay: 0.1s;">
        <div class="stat-card bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-500 rounded-xl shadow-lg">
                    <i data-feather="users" class="w-6 h-6 text-white"></i>
                </div>
                <span class="text-sm font-medium text-blue-700 bg-blue-200 px-3 py-1 rounded-full">Total</span>
            </div>
            <h3 class="text-3xl font-bold text-blue-900 mb-1">{{ number_format($customers->total() ?? 0) }}</h3>
            <p class="text-blue-700 text-sm font-medium">Total Pelanggan</p>
        </div>

        <div class="stat-card bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-green-500 rounded-xl shadow-lg">
                    <i data-feather="user-check" class="w-6 h-6 text-white"></i>
                </div>
                <span class="text-sm font-medium text-green-700 bg-green-200 px-3 py-1 rounded-full">Aktif</span>
            </div>
            <h3 class="text-3xl font-bold text-green-900 mb-1">{{ $activeCount }}</h3>
            <p class="text-green-700 text-sm font-medium">Aktif Bulan Ini</p>
        </div>

        <div class="stat-card bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-purple-500 rounded-xl shadow-lg">
                    <i data-feather="user-plus" class="w-6 h-6 text-white"></i>
                </div>
                <span class="text-sm font-medium text-purple-700 bg-purple-200 px-3 py-1 rounded-full">Baru</span>
            </div>
            <h3 class="text-3xl font-bold text-purple-900 mb-1">{{ $newcustomers }}</h3>
            <p class="text-purple-700 text-sm font-medium">Pelanggan Baru</p>
        </div>

        <div class="stat-card bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-amber-500 rounded-xl shadow-lg">
                    <i data-feather="trending-up" class="w-6 h-6 text-white"></i>
                </div>
                <span class="text-sm font-medium text-amber-700 bg-amber-200 px-3 py-1 rounded-full">Rata-rata</span>
            </div>
            <h3 class="text-3xl font-bold text-amber-900 mb-1">{{ formatRupiahSingkat($averageLifetime) }}</h3>
            <p class="text-amber-700 text-sm font-medium">Nilai Lifetime</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden fade-in-up" style="animation-delay: 0.2s;">
        <div class="p-6 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-100 rounded-lg">
                        <i data-feather="users" class="w-5 h-5 text-indigo-600"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Semua Pelanggan</h2>
                        <p class="text-sm text-gray-500">{{ $customers->count() }} pelanggan terdaftar</p>
                    </div>
                </div>
                <div class="flex gap-2 flex-wrap w-full lg:w-auto">
                    <div class="relative flex-1 lg:flex-initial">
                        <i data-feather="search"
                            class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                        <input type="text" id="search-input" placeholder="Cari pelanggan..."
                            class="input-field w-full lg:w-64 pl-10 pr-10 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <button id="clear-search"
                            class="hidden absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i data-feather="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <a href="{{ route('admin.customers.export') }}"
                        class="action-btn px-4 py-2 text-sm bg-white text-gray-700 rounded-lg hover:bg-gray-50 border border-gray-200 shadow-sm flex items-center gap-2">
                        <i data-feather="download" class="w-4 h-4"></i>
                        <span>Export</span>
                    </a>

                    <form action="{{ route('admin.customers.import') }}" method="POST" enctype="multipart/form-data"
                        id="importForm">
                        @csrf
                        <label for="importFile"
                            class="action-btn px-4 py-3 text-sm bg-white text-gray-700 rounded-lg hover:bg-gray-50 border border-gray-200 shadow-sm flex items-center gap-2 cursor-pointer">
                            <i data-feather="upload" class="w-4 h-4"></i>
                            <span>Import</span>
                        </label>
                        <input type="file" id="importFile" name="file" class="hidden" accept=".csv,.xlsx">
                    </form>

                    <a href="{{ asset('templates/customer-import-laundry-template.xlsx') }}"
                        class="action-btn px-4 py-2 text-sm bg-white text-gray-700 rounded-lg hover:bg-gray-50 border border-gray-200 shadow-sm flex items-center gap-2">
                        <i data-feather="download" class="w-4 h-4"></i>
                        <span>Download Template</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div id="loading-state" class="hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @for ($i = 0; $i < 6; $i++)
                        <div class="border-2 border-gray-200 rounded-xl p-6 animate-pulse">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 bg-gray-200 rounded-xl"></div>
                                <div class="flex-1">
                                    <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                                    <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="h-3 bg-gray-200 rounded"></div>
                                <div class="h-3 bg-gray-200 rounded"></div>
                                <div class="h-3 bg-gray-200 rounded w-2/3"></div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <div id="search-results" class="hidden"></div>

            @if ($customers->isEmpty())
                <div class="empty-state flex flex-col items-center justify-center py-20 text-center">
                    <div class="relative mb-6">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-indigo-200 to-blue-200 rounded-full blur-2xl opacity-50">
                        </div>
                        <div class="relative p-6 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-full">
                            <i data-feather="user-x" class="w-16 h-16 text-indigo-500"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum Ada Pelanggan</h3>
                    <p class="text-gray-600 text-base max-w-md mb-6">
                        Mulai tambahkan pelanggan pertama Anda untuk memulai.
                    </p>
                    <button onclick="document.getElementById('add-customer-btn').click()"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl font-medium hover:from-indigo-700 hover:to-blue-700 shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                        <i data-feather="user-plus" class="w-5 h-5"></i>
                        <span>Tambah Pelanggan Pertama</span>
                    </button>
                </div>
            @else
                <div id="customers-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($customers as $customer)
                        <div class="customer-card border border-gray-200 rounded-2xl p-6 fade-in-up"
                            data-name="{{ $customer->name }}" data-email="{{ $customer->email }}"
                            data-phone="{{ $customer->phone }}">
                            <div class="flex items-start justify-between mb-5">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="avatar w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-base shadow-sm">
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $customer->name)[1] ?? '', 0, 1)) }}
                                    </div>
                                    <div>
                                        <h3 class="text-base font-bold text-gray-900">{{ $customer->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $customer->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2 mb-5">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i data-feather="phone" class="w-4 h-4 mr-2 text-gray-400"></i>
                                    <span>{{ $customer->phone }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i data-feather="map-pin" class="w-4 h-4 mr-2 text-gray-400"></i>
                                    <span class="line-clamp-1">{{ $customer->address }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i data-feather="calendar" class="w-4 h-4 mr-2 text-gray-400"></i>
                                    <span>Bergabung: {{ $customer->created_at->format('d M Y') }}</span>
                                </div>
                            </div>

                            <div class="flex justify-between items-center pt-4 border-t border-gray-200 mb-5">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Total Pesanan</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $customer->transactions_count }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500 mb-1">Total Belanja</p>
                                    <p class="text-lg font-bold text-indigo-600">
                                        {{ formatRupiahSingkat($customer->transactions_sum_total ?? 0) }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <button data-customer-id="{{ $customer->customer_id }}"
                                    class="edit-customer-btn action-btn flex-1 px-4 py-2.5 rounded-lg bg-indigo-100 hover:bg-indigo-200 flex items-center justify-center text-indigo-600 transition-colors gap-2">
                                    <i data-feather="edit-2" class="w-4 h-4"></i>
                                    <span class="font-medium">Edit</span>
                                </button>
                                <form method="POST"
                                    action="{{ route('admin.customers.delete', $customer->customer_id) }}"
                                    class="delete-form flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="delete-btn action-btn w-full px-4 py-2.5 rounded-lg bg-red-50 hover:bg-red-100 flex items-center justify-center text-red-600 transition-colors gap-2">
                                        <i data-feather="trash-2" class="w-4 h-4"></i>
                                        <span class="font-medium">Hapus</span>
                                    </button>
                                </form>
                            </div>

                            <div class="pt-4 mt-4 border-t border-gray-200">
                                <p class="text-xs text-gray-500 mb-3 text-center font-medium">Hubungi Pelanggan</p>
                                <div class="flex gap-2">
                                    <a href="mailto:{{ $customer->email }}" target="_blank"
                                        class="action-btn flex-1 px-4 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-700 transition-colors gap-2">
                                        <i data-feather="mail" class="w-4 h-4"></i>
                                        <span class="font-medium">Email</span>
                                    </a>
                                    @php
                                        $phone = $customer->phone;
                                        if (substr($phone, 0, 1) === '0') {
                                            $phone = '62' . substr($phone, 1);
                                        }
                                        $whatsappUrl = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $phone);
                                    @endphp
                                    <a href="{{ $whatsappUrl }}" target="_blank"
                                        class="action-btn flex-1 px-4 py-2.5 rounded-lg bg-green-50 hover:bg-green-100 flex items-center justify-center text-green-700 transition-colors gap-2">
                                        <i data-feather="message-circle" class="w-4 h-4"></i>
                                        <span class="font-medium">WhatsApp</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        @if ($customers->hasPages())
            <div id="pagination-links"
                class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Menampilkan <span class="font-semibold">{{ $customers->firstItem() }}</span>
                    hingga <span class="font-semibold">{{ $customers->lastItem() }}</span>
                    dari <span class="font-semibold">{{ $customers->total() }}</span> pelanggan
                </div>
                {{ $customers->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>

    @if (!$customers->isEmpty())
        @foreach ($customers as $customer)
            <div id="customer-modal-edit-{{ $customer->customer_id }}"
                class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="sticky top-0 bg-white p-6 border-b border-gray-200 rounded-t-2xl z-10">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-indigo-100 rounded-lg">
                                    <i data-feather="edit" class="w-5 h-5 text-indigo-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Edit Pelanggan</h3>
                                    <p class="text-sm text-gray-500">Perbarui informasi pelanggan</p>
                                </div>
                            </div>
                            <button
                                class="close-modal-btn text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-lg transition-colors"
                                data-modal-id="customer-modal-edit-{{ $customer->customer_id }}">
                                <i data-feather="x" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>

                    <div class="p-6">
                        <form method="POST" action="{{ route('admin.customers.update', $customer->customer_id) }}">
                            @csrf
                            @method('PUT')

                            <div class="space-y-5">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Nama Lengkap <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="name" value="{{ $customer->name }}" required
                                            class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" name="email" value="{{ $customer->email }}" required
                                            class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nomor Telepon <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" name="phone" value="{{ $customer->phone }}" required
                                        placeholder="0812-3456-7890"
                                        class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Alamat Lengkap
                                    </label>
                                    <textarea name="address" rows="3" placeholder="Masukkan alamat lengkap..."
                                        class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-all">{{ $customer->address }}</textarea>
                                </div>
                            </div>

                            <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                                <button type="submit"
                                    class="save-edit-btn flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl font-medium hover:from-indigo-700 hover:to-blue-700 shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                                    <i data-feather="check" class="w-5 h-5"></i>
                                    <span>Perbarui Data</span>
                                </button>
                                <button type="button"
                                    class="cancel-modal-btn flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all"
                                    data-modal-id="customer-modal-edit-{{ $customer->customer_id }}">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    <div id="customer-modal-add"
        class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white p-6 border-b border-gray-200 rounded-t-2xl z-10">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-100 rounded-lg">
                            <i data-feather="user-plus" class="w-5 h-5 text-indigo-600"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Tambah Pelanggan Baru</h3>
                            <p class="text-sm text-gray-500">Buat data pelanggan baru</p>
                        </div>
                    </div>
                    <button
                        class="close-modal-btn text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-lg transition-colors"
                        data-modal-id="customer-modal-add">
                        <i data-feather="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('admin.customers.store') }}">
                    @csrf

                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" placeholder="Masukkan nama lengkap" required
                                    class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" placeholder="contoh@email.com" required
                                    class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone" placeholder="0812-3456-7890" required
                                class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Alamat Lengkap
                            </label>
                            <textarea name="address" rows="3" placeholder="Masukkan alamat lengkap pelanggan..."
                                class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-all"></textarea>
                            <p class="mt-2 text-xs text-gray-500">Opsional: Tambahkan alamat untuk pengiriman</p>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                        <button type="submit" id="create-customer-btn"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl font-medium hover:from-indigo-700 hover:to-blue-700 shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                            <i data-feather="save" class="w-5 h-5"></i>
                            <span>Simpan Pelanggan</span>
                        </button>
                        <button type="button"
                            class="cancel-modal-btn flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all"
                            data-modal-id="customer-modal-add">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        class ModalManager {
            constructor() {
                this.init();
            }

            init() {
                document.body.addEventListener('click', (e) => {
                    if (e.target.closest('#add-customer-btn')) {
                        e.preventDefault();
                        this.openModal('customer-modal-add');
                    }

                    const editBtn = e.target.closest('.edit-customer-btn');
                    if (editBtn) {
                        e.preventDefault();
                        const customerId = editBtn.dataset.customerId;
                        if (customerId) {
                            this.openModal(`customer-modal-edit-${customerId}`);
                        }
                    }

                    const closeBtn = e.target.closest('.close-modal-btn');
                    const cancelBtn = e.target.closest('.cancel-modal-btn');
                    const modalOverlay = e.target.closest('.modal-overlay');

                    if (closeBtn || cancelBtn) {
                        e.preventDefault();
                        const modalId = closeBtn ? closeBtn.dataset.modalId : cancelBtn.dataset.modalId;
                        if (modalId) {
                            this.closeModal(modalId);
                        }
                    } else if (modalOverlay && e.target === modalOverlay) {
                        this.closeModal(modalOverlay.id);
                    }
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        const openModal = document.querySelector('.modal-overlay.modal-show');
                        if (openModal) this.closeModal(openModal.id);
                    }
                });
            }

            openModal(modalId) {
                const modal = document.getElementById(modalId);
                if (!modal) return;
                document.body.style.overflow = 'hidden';
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.add('modal-show');
                    feather.replace();
                }, 10);
            }

            closeModal(modalId) {
                const modal = document.getElementById(modalId);
                if (!modal) return;
                modal.classList.remove('modal-show');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                    const form = modal.querySelector('form');
                    if (form) form.reset();
                }, 300);
            }
        }

        class SearchManager {
            constructor(modalManager) {
                this.modalManager = modalManager;
                this.searchInput = document.getElementById('search-input');
                this.clearSearchBtn = document.getElementById('clear-search');
                this.customersGrid = document.getElementById('customers-grid');
                this.searchResultsContainer = document.getElementById('search-results');
                this.loadingState = document.getElementById('loading-state');
                this.paginationLinks = document.getElementById('pagination-links');
                this.abortController = null;
                this.init();
            }

            init() {
                if (!this.searchInput) return;

                let debounceTimer;
                this.searchInput.addEventListener('input', (e) => {
                    const term = e.target.value.trim();
                    this.toggleClearButton(term);
                    clearTimeout(debounceTimer);

                    if (this.abortController) {
                        this.abortController.abort();
                    }

                    if (term.length === 0) {
                        this.resetSearch();
                        return;
                    }

                    if (term.length < 2) {
                        this.showMessage('Lanjutkan...', 'Masukan minimalnya 2 huruf untuk melanjutkan pencarian.');
                        return;
                    }

                    debounceTimer = setTimeout(() => this.performSearch(term), 400);
                });

                this.clearSearchBtn.addEventListener('click', () => {
                    this.searchInput.value = '';
                    this.toggleClearButton('');
                    this.resetSearch();
                });
            }

            toggleClearButton(term) {
                this.clearSearchBtn.classList.toggle('hidden', term.length === 0);
            }

            async performSearch(term) {
                this.showLoading();
                this.abortController = new AbortController();

                try {
                    const response = await fetch(`/admin/customers/search?q=${encodeURIComponent(term)}`, {
                        signal: this.abortController.signal,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                    const data = await response.json();
                    let customers = [];

                    if (Array.isArray(data)) {
                        customers = data;
                    } else if (data && Array.isArray(data.customers)) {
                        customers = data.customers;
                    } else if (data && data.data && Array.isArray(data.data)) {
                        customers = data.data;
                    }

                    const modals = data.modals || '';
                    this.displayResults(customers, term, modals);

                } catch (error) {
                    if (error.name !== 'AbortError') {
                        console.error('Search error:', error);
                        this.showMessage('Terjadi Kesalahan', 'Gagal memuat hasil pencarian. Coba lagi.', 'alert-circle', 'red');
                    }
                } finally {
                    this.hideLoading();
                }
            }

            displayResults(customers, term, modalsHtml) {
                this.hideOriginalGrid();

                if (customers.length === 0) {
                    this.showMessage('Hasil Tidak Ditemukan', `Tidak ada pelanggan untuk kata kunci "<span class="font-semibold">${this.escapeHtml(term)}</span>".`);
                    return;
                }

                const resultsHTML = customers.map(customer => this.buildCustomerCard(customer)).join('');
                this.searchResultsContainer.innerHTML = `
                <div class="mb-4">
                    <p class="text-sm text-gray-600">
                        Menemukan <span class="font-semibold">${customers.length}</span> hasil untuk
                        <span class="font-semibold">"${this.escapeHtml(term)}"</span>
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    ${resultsHTML}
                </div>
            `;

                if (modalsHtml && typeof modalsHtml === 'string') {
                    document.body.insertAdjacentHTML('beforeend', modalsHtml);
                }

                feather.replace();
                this.animateResults();
            }

            buildCustomerCard(customer) {
                const initials = this.getInitials(customer.name);
                const joinDate = new Date(customer.created_at).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });

                let phone = customer.phone;
                if (phone && phone.startsWith('0')) {
                    phone = '62' + phone.substring(1);
                }
                const whatsappUrl = `https://wa.me/${phone.replace(/[^0-9]/g, '')}`;

                return `
                <div class="customer-card border border-gray-200 rounded-2xl p-6 fade-in-up" style="opacity:0; transform:translateY(20px);">
                    <div class="flex items-start justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="avatar w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-base shadow-sm">
                                ${initials}
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900">${this.escapeHtml(customer.name)}</h3>
                                <p class="text-sm text-gray-500">${this.escapeHtml(customer.email)}</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-2 mb-5">
                        <div class="flex items-center text-sm text-gray-600"><i data-feather="phone" class="w-4 h-4 mr-2 text-gray-400"></i><span>${this.escapeHtml(customer.phone)}</span></div>
                        <div class="flex items-center text-sm text-gray-600"><i data-feather="map-pin" class="w-4 h-4 mr-2 text-gray-400"></i><span class="line-clamp-1">${this.escapeHtml(customer.address)}</span></div>
                        <div class="flex items-center text-sm text-gray-600"><i data-feather="calendar" class="w-4 h-4 mr-2 text-gray-400"></i><span>Bergabung: ${joinDate}</span></div>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200 mb-5">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Total Pesanan</p>
                            <p class="text-lg font-bold text-gray-900">${customer.transactions_count || 0}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 mb-1">Total Belanja</p>
                            <p class="text-lg font-bold text-indigo-600">${this.formatRupiahSingkat(customer.transactions_sum_total || 0)}</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button data-customer-id="${customer.customer_id}" class="edit-customer-btn action-btn flex-1 px-4 py-2.5 rounded-lg bg-indigo-100 hover:bg-indigo-200 flex items-center justify-center text-indigo-600"><i data-feather="edit-2" class="w-4 h-4"></i><span class="font-medium">Edit</span></button>
                        <form method="POST" action="/admin/customers/${customer.customer_id}" class="delete-form flex-1">
                            <input type="hidden" name="_token" value="${window.csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" class="delete-btn action-btn w-full px-4 py-2.5 rounded-lg bg-red-50 hover:bg-red-100 flex items-center justify-center text-red-600"><i data-feather="trash-2" class="w-4 h-4"></i><span class="font-medium">Hapus</span></button>
                        </form>
                    </div>
                    <div class="pt-4 mt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500 mb-3 text-center font-medium">Hubungi Pelanggan</p>
                        <div class="flex gap-2">
                            <a href="mailto:${this.escapeHtml(customer.email)}" target="_blank" class="action-btn flex-1 px-4 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-700"><i data-feather="mail" class="w-4 h-4"></i><span class="font-medium">Email</span></a>
                            <a href="${whatsappUrl}" target="_blank" class="action-btn flex-1 px-4 py-2.5 rounded-lg bg-green-50 hover:bg-green-100 flex items-center justify-center text-green-700"><i data-feather="message-circle" class="w-4 h-4"></i><span class="font-medium">WhatsApp</span></a>
                        </div>
                    </div>
                </div>
                `;
            }

            showLoading() {
                this.hideOriginalGrid();
                this.loadingState.classList.remove('hidden');
                this.searchResultsContainer.innerHTML = '';
            }

            hideLoading() {
                this.loadingState.classList.add('hidden');
            }

            showMessage(title, text, icon = 'search', color = 'gray') {
                this.hideOriginalGrid();
                this.searchResultsContainer.innerHTML = `
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="p-4 bg-${color}-100 rounded-full mb-4">
                        <i data-feather="${icon}" class="w-8 h-8 text-${color}-500"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-1">${title}</h2>
                    <p class="text-gray-500 text-sm">${text}</p>
                </div>
            `;
                feather.replace();
            }

            resetSearch() {
                this.customersGrid.classList.remove('hidden');
                this.paginationLinks?.classList.remove('hidden');
                this.searchResultsContainer.classList.add('hidden');
                this.searchResultsContainer.innerHTML = '';
                document.querySelectorAll('.search-modal').forEach(modal => modal.remove());
            }

            hideOriginalGrid() {
                this.customersGrid.classList.add('hidden');
                this.paginationLinks?.classList.add('hidden');
                this.searchResultsContainer.classList.remove('hidden');
            }

            animateResults() {
                this.searchResultsContainer.querySelectorAll('.customer-card').forEach((card, index) => {
                    setTimeout(() => {
                        card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 50);
                });
            }

            getInitials(name) {
                const parts = name.split(' ').filter(Boolean);
                if (parts.length > 1) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
                return name.substring(0, 2).toUpperCase();
            }

            formatRupiahSingkat(number) {
                if (!number || number === 0) return 'Rp0';
                const n = Math.floor(number);
                if (n >= 1000000000) {
                    const val = n / 1000000000;
                    return 'Rp' + (val % 1 === 0 ? val : val.toFixed(1)) + 'M';
                }
                if (n >= 1000000) {
                    const val = n / 1000000;
                    return 'Rp' + (val % 1 === 0 ? val : val.toFixed(1)) + 'Jt';
                }
                if (n >= 1000) {
                    const val = n / 1000;
                    return 'Rp' + (val % 1 === 0 ? val : val.toFixed(1)) + 'K';
                }
                return 'Rp' + n;
            }

            escapeHtml(str) {
                if (!str) return '';
                return str.replace(/[&<>"']/g, (match) => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                }[match]));
            }
        }

        class DeleteManager {
            constructor() {
                document.body.addEventListener('click', (e) => {
                    const deleteBtn = e.target.closest('.delete-btn');
                    if (deleteBtn) {
                        e.preventDefault();
                        const form = deleteBtn.closest('.delete-form');
                        const card = deleteBtn.closest('.customer-card');
                        const customerName = card?.querySelector('h3')?.textContent || 'pelanggan ini';
                        this.confirmDelete(form, customerName);
                    }
                });
            }

            confirmDelete(form, customerName) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        html: `Anda akan menghapus <strong>${this.escapeHtml(customerName)}</strong>. Tindakan ini tidak dapat dibatalkan.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                } else {
                    if (confirm(`Apakah Anda yakin ingin menghapus ${customerName}?`)) {
                        form.submit();
                    }
                }
            }

            escapeHtml(str) {
                const p = document.createElement("p");
                p.textContent = str;
                return p.innerHTML;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const modalManager = new ModalManager();
            new SearchManager(modalManager);
            new DeleteManager();
            feather.replace();
            document.getElementById('importFile')?.addEventListener('change', function() {
                if (this.files.length > 0) {
                    document.getElementById('importForm').submit();
                }
            });
        });
    </script>
@endpush
