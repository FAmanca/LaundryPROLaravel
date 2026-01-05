@extends('layouts.admin')

@section('title', 'Service Management - LaundryPRO')

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

        /* Stats Card Animations */
        .stats-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }

        .stats-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
        }

        .stats-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .stat-icon {
            transition: transform 0.3s ease;
        }

        /* Service Card Effects */
        .service-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }

        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
        }

        .service-card:hover .service-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .service-icon {
            transition: transform 0.3s ease;
        }

        /* Button Animations */
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

        /* Icon Selection */
        .icon-btn {
            transition: all 0.2s ease;
        }

        .icon-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .icon-btn:active {
            transform: translateY(0);
        }

        /* Badge Pulse */
        .badge-active {
            animation: pulse-subtle 2s ease-in-out infinite;
        }

        @keyframes pulse-subtle {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.8;
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

        /* Stagger animation */
        .stats-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .stats-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .stats-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .stats-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .service-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .service-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .service-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .service-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .service-card:nth-child(5) {
            animation-delay: 0.5s;
        }

        .service-card:nth-child(6) {
            animation-delay: 0.6s;
        }

        /* Input Focus Effect */
        .input-field {
            transition: all 0.3s ease;
        }

        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
        }

        /* Empty State */
        .empty-state {
            animation: fadeInUp 0.8s ease-out;
        }

        /* Scrollbar */
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

        /* Price Badge */
        .price-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
@endpush

@section('content')
    <!-- Header Section -->
    <div class="mb-8 fade-in-up">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Manajemen Layanan</h1>
                <p class="text-gray-600 flex items-center gap-2">
                    <i data-feather="settings" class="w-4 h-4"></i>
                    Kelola layanan laundry beserta harga dan detailnya.
                </p>
            </div>
            <button id="add-service-btn"
                class="btn-primary px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl font-medium hover:from-indigo-700 hover:to-blue-700 shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                <i data-feather="plus-circle" class="w-5 h-5"></i>
                <span>Tambah Layanan</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stats-card fade-in-up border border-gray-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Layanan</p>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $total_services }}</h3>
                    <div
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">
                        <i data-feather="trending-up" class="w-3 h-3"></i>
                        <span>Active</span>
                    </div>
                </div>
                <div
                    class="stat-icon w-14 h-14 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center shadow-sm">
                    <i data-feather="package" class="text-blue-600 w-7 h-7"></i>
                </div>
            </div>
        </div>

        <div class="stats-card fade-in-up border border-gray-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 mb-1">Rata-rata Harga</p>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">
                        Rp {{ number_format($avg_service_price ?? 0, 0, ',', '.') }}
                    </h3>
                    <p class="text-xs text-gray-500">Per layanan</p>
                </div>
                <div
                    class="stat-icon w-14 h-14 rounded-xl bg-gradient-to-br from-green-50 to-emerald-50 flex items-center justify-center shadow-sm">
                    <i data-feather="dollar-sign" class="text-green-600 w-7 h-7"></i>
                </div>
            </div>
        </div>

        <div class="stats-card fade-in-up border border-gray-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 mb-1">Paling Populer</p>

                    @if ($popular_service)
                        <h3 class="text-lg font-bold text-gray-900 mb-2 truncate">
                            {{ $popular_service->service_name }}
                        </h3>
                        <div
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700">
                            <i data-feather="star" class="w-3 h-3"></i>
                            <span>{{ $popular_service->detail_transactions_count }} orders</span>
                        </div>
                    @else
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Belum ada layanan</h3>
                        <div
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">
                            <i data-feather="star" class="w-3 h-3"></i>
                            <span>0 orders</span>
                        </div>
                    @endif
                </div>

                <div
                    class="stat-icon w-14 h-14 rounded-xl bg-gradient-to-br from-purple-50 to-pink-50 flex items-center justify-center shadow-sm">
                    <i data-feather="award" class="text-purple-600 w-7 h-7"></i>
                </div>
            </div>
        </div>

        <div class="stats-card fade-in-up border border-gray-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 mb-1">Harga Paling Murah</p>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Rp
                        {{ number_format($lowest_service ? $lowest_service->price : 0, 0, ',', '.') }}</h3>
                    <div
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700">
                        <i data-feather="dollar-sign" class="w-3 h-3"></i>
                        <span>{{ $lowest_service ? $lowest_service->service_name : "Belum ada" }}</span>
                    </div>
                </div>
                <div
                    class="stat-icon w-14 h-14 rounded-xl bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center shadow-sm">
                    <i data-feather="tag" class="text-red-600 w-7 h-7"></i>
                </div>
            </div>
        </div>


    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden fade-in-up" style="animation-delay: 0.5s;">
        <!-- Card Header -->
        <div class="p-6 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-100 rounded-lg">
                        <i data-feather="grid" class="w-5 h-5 text-indigo-600"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">List Layanan</h2>
                        <p class="text-sm text-gray-500">{{ $total_services }}
                            layanan tersedia</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    {{-- <button
                        class="action-btn px-4 py-2 text-sm bg-white text-gray-700 rounded-lg hover:bg-gray-50 border border-gray-200 shadow-sm flex items-center gap-2">
                        <i data-feather="filter" class="w-4 h-4"></i>
                        <span>Filter</span>
                    </button> --}}
                    <a href="{{ route('admin.services.export') }}"
                        class="action-btn px-4 py-2 text-sm bg-white text-gray-700 rounded-lg hover:bg-gray-50 border border-gray-200 shadow-sm flex items-center gap-2">
                        <i data-feather="download" class="w-4 h-4"></i>
                        <span>Export</span>
                    </a>

                    <form action="{{ route('admin.services.import') }}" method="POST" enctype="multipart/form-data"
                        id="importForm">
                        @csrf
                        <label for="importFile"
                            class="action-btn px-4 py-2 text-sm bg-white text-gray-700 rounded-lg hover:bg-gray-50 border border-gray-200 shadow-sm flex items-center gap-2 cursor-pointer">
                            <i data-feather="upload" class="w-4 h-4"></i>
                            <span>Import</span>
                        </label>

                        <input type="file" id="importFile" name="file" class="hidden" accept=".csv,.xlsx">
                    </form>

                    <a href="{{ asset('templates/service-import-laundry-template.xlsx') }}"
                        class="action-btn px-4 py-2 text-sm bg-white text-gray-700 rounded-lg hover:bg-gray-50 border border-gray-200 shadow-sm flex items-center gap-2">
                        <i data-feather="download" class="w-4 h-4"></i>
                        <span>Download Template</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Services Grid -->
        <div class="p-6">
            @if ($services->isEmpty())
                <div class="empty-state flex flex-col items-center justify-center py-20 text-center">
                    <div class="relative mb-6">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-indigo-200 to-blue-200 rounded-full blur-2xl opacity-50">
                        </div>
                        <div class="relative p-6 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-full">
                            <i data-feather="package" class="w-16 h-16 text-indigo-500"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum ada layanan</h3>
                    <p class="text-gray-600 text-base max-w-md mb-6">
                        Tambahkan layanan laundry pertama Anda untuk mulai mengelola penawaran Anda dan melayani pelanggan
                        dengan lebih baik.
                    </p>
                    <button onclick="document.getElementById('add-service-btn').click()"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl font-medium hover:from-indigo-700 hover:to-blue-700 shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                        <i data-feather="plus-circle" class="w-5 h-5"></i>
                        <span>Tambahkan Layanan Pertama</span>
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($services as $service)
                        <div class="service-card border border-gray-200 rounded-2xl p-6 fade-in-up">
                            <!-- Card Header -->
                            <div class="flex items-start justify-between mb-5">
                                <div
                                    class="service-icon w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-50 to-blue-50 flex items-center justify-center shadow-sm">
                                    <i data-feather="{{ $service->icon_name ?? 'package' }}"
                                        class="text-indigo-600 w-7 h-7"></i>
                                </div>
                                <div class="flex gap-2">
                                    <button id="edit-service-btn-{{ $service->service_id }}"
                                        class="action-btn w-9 h-9 rounded-lg bg-gray-100 hover:bg-indigo-100 flex items-center justify-center text-gray-600 hover:text-indigo-600 transition-colors">
                                        <i data-feather="edit-2" class="w-4 h-4"></i>
                                    </button>
                                    <form action="{{ route('admin.services.delete', $service->service_id) }}"
                                        method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="delete-btn action-btn w-9 h-9 rounded-lg bg-red-50 hover:bg-red-100 flex items-center justify-center text-red-600 transition-colors">
                                            <i data-feather="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="mb-4">
                                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1">
                                    {{ $service->service_name }}
                                </h3>
                                <p class="text-sm text-gray-600 line-clamp-2 min-h-[2.5rem]">
                                    {{ $service->description ?: 'No description available' }}
                                </p>
                            </div>

                            <!-- Price & Stats -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Price</p>
                                    <p
                                        class="price-badge text-white px-3 py-1.5 rounded-lg font-bold text-sm inline-block">
                                        Rp {{ number_format($service->price, 0, ',', '.') }}/{{ $service->unit }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500 mb-1">Orders</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $service->detail_transactions_count }}
                                    </p>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <span
                                    class="badge-active inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 border border-green-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Active
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3">
                    {{ $services->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Modals    (Outside Grid) -->
    @foreach ($services as $service)
        <div id="service-modal-edit-{{ $service->service_id }}"
            class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="sticky top-0 bg-white p-6 border-b border-gray-200 rounded-t-2xl z-10">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-indigo-100 rounded-lg">
                                <i data-feather="edit" class="w-5 h-5 text-indigo-600"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Edit Service</h3>
                                <p class="text-sm text-gray-500">Update service information</p>
                            </div>
                        </div>
                        <button
                            class="close-modal-edit text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <i data-feather="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <form action="{{ route('admin.services.update', $service->service_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Service Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="service_name"
                                        placeholder="e.g., Cuci Kering, Cuci Setrika"
                                        value="{{ $service->service_name }}" required
                                        class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Description
                                    </label>
                                    <textarea name="description" rows="4"
                                        placeholder="Describe the service details, process, or special features..."
                                        class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-all">{{ $service->description }}</textarea>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Price <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span
                                                class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                            <input name="price" type="number" placeholder="0"
                                                value="{{ $service->price }}" required
                                                class="input-field w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Unit <span class="text-red-500">*</span>
                                        </label>
                                        <select name="unit" required
                                            class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                            <option value="kg" {{ $service->unit == 'kg' ? 'selected' : '' }}>kg
                                            </option>
                                            <option value="pasang" {{ $service->unit == 'pasang' ? 'selected' : '' }}>
                                                pasang</option>
                                            <option value="m²" {{ $service->unit == 'm²' ? 'selected' : '' }}>m²
                                            </option>
                                            <option value="pcs" {{ $service->unit == 'pcs' ? 'selected' : '' }}>pcs
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column - Icon Selection -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Select Icon
                                </label>
                                <div class="border-2 border-gray-200 rounded-xl p-4 bg-gray-50">
                                    <div class="grid grid-cols-6 gap-2 mb-3">
                                        @php
                                            $icons = [
                                                'wind',
                                                'zap',
                                                'thermometer',
                                                'zap-off',
                                                'award',
                                                'grid',
                                                'droplet',
                                                'sun',
                                                'star',
                                                'box',
                                                'package',
                                                'shopping-bag',
                                                'gift',
                                                'heart',
                                                'umbrella',
                                                'watch',
                                                'shield',
                                                'truck',
                                            ];
                                        @endphp
                                        @foreach ($icons as $icon)
                                            <button type="button"
                                                class="icon-btn w-12 h-12 rounded-lg bg-white hover:bg-indigo-50 border-2 {{ $service->icon_name == $icon ? 'border-indigo-500 bg-indigo-100' : 'border-transparent' }} flex items-center justify-center transition"
                                                data-icon="{{ $icon }}">
                                                <i data-feather="{{ $icon }}" class="w-5 h-5 text-gray-700"></i>
                                            </button>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="icon_name" class="icon-name-input"
                                        value="{{ $service->icon_name }}">
                                    <div
                                        class="flex items-center gap-2 px-3 py-2 bg-white rounded-lg border border-gray-200">
                                        <i data-feather="info" class="w-4 h-4 text-indigo-600"></i>
                                        <p class="text-xs text-gray-600">
                                            Selected: <span
                                                class="icon-name-display font-semibold text-indigo-600">{{ $service->icon_name }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                            <button type="submit"
                                class="save-edit-btn flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl font-medium hover:from-indigo-700 hover:to-blue-700 shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                                <i data-feather="check" class="w-5 h-5"></i>
                                <span>Update Service</span>
                            </button>
                            <button type="button"
                                class="cancel-modal-edit flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Add Modal -->
    <div id="service-modal"
        class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white p-6 border-b border-gray-200 rounded-t-2xl z-10">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-100 rounded-lg">
                            <i data-feather="plus-circle" class="w-5 h-5 text-indigo-600"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Add New Service</h3>
                            <p class="text-sm text-gray-500">Create a new laundry service</p>
                        </div>
                    </div>
                    <button id="close-modal"
                        class="text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-feather="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form action="{{ route('admin.services.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Service Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="service_name" placeholder="e.g., Cuci Kering, Cuci Setrika"
                                    required
                                    class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea name="description" rows="4"
                                    placeholder="Describe the service details, process, or special features..."
                                    class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-all"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Price <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                        <input name="price" type="number" placeholder="0" required
                                            class="input-field w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Unit <span class="text-red-500">*</span>
                                    </label>
                                    <select name="unit" required
                                        class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                        <option value="kg">kg</option>
                                        <option value="pasang">pasang</option>
                                        <option value="m²">m²</option>
                                        <option value="pcs">pcs</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Icon Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Select Icon
                            </label>
                            <div class="border-2 border-gray-200 rounded-xl p-4 bg-gray-50">
                                <div class="grid grid-cols-6 gap-2 mb-3">
                                    @php
                                        $icons = [
                                            'wind',
                                            'zap',
                                            'thermometer',
                                            'zap-off',
                                            'award',
                                            'grid',
                                            'droplet',
                                            'sun',
                                            'star',
                                            'box',
                                            'package',
                                            'shopping-bag',
                                            'gift',
                                            'heart',
                                            'umbrella',
                                            'watch',
                                            'shield',
                                            'truck',
                                        ];
                                    @endphp
                                    @foreach ($icons as $index => $icon)
                                        <button type="button"
                                            class="icon-btn w-12 h-12 rounded-lg bg-white hover:bg-indigo-50 border-2 {{ $index === 0 ? 'border-indigo-500 bg-indigo-100' : 'border-transparent' }} flex items-center justify-center transition"
                                            data-icon="{{ $icon }}">
                                            <i data-feather="{{ $icon }}" class="w-5 h-5 text-gray-700"></i>
                                        </button>
                                    @endforeach
                                </div>
                                <input type="hidden" name="icon_name" id="icon-name-input" value="wind">
                                <div class="flex items-center gap-2 px-3 py-2 bg-white rounded-lg border border-gray-200">
                                    <i data-feather="info" class="w-4 h-4 text-indigo-600"></i>
                                    <p class="text-xs text-gray-600">
                                        Selected: <span id="icon-name-display"
                                            class="font-semibold text-indigo-600">wind</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                        <button type="submit" id="create_service_btn"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl font-medium hover:from-indigo-700 hover:to-blue-700 shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                            <i data-feather="save" class="w-5 h-5"></i>
                            <span>Save Service</span>
                        </button>
                        <button type="button" id="cancel-modal"
                            class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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

        // ========== ADD SERVICE MODAL ==========
        const modal = document.getElementById('service-modal');
        const addBtn = document.getElementById('add-service-btn');
        const closeBtn = document.getElementById('close-modal');
        const cancelBtn = document.getElementById('cancel-modal');

        addBtn.addEventListener('click', () => openModal(modal));
        closeBtn.addEventListener('click', () => closeModal(modal));
        cancelBtn.addEventListener('click', () => closeModal(modal));

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal(modal);
        });

        // ========== EDIT SERVICE MODALS ==========
        document.querySelectorAll('[id^="edit-service-btn-"]').forEach((editBtn) => {
            const id = editBtn.id.replace('edit-service-btn-', '');
            const modalEdit = document.getElementById(`service-modal-edit-${id}`);

            if (!modalEdit) return;

            const closeBtnEdit = modalEdit.querySelector('.close-modal-edit');
            const cancelBtnEdit = modalEdit.querySelector('.cancel-modal-edit');

            editBtn.addEventListener('click', () => openModal(modalEdit));

            [closeBtnEdit, cancelBtnEdit].forEach((btn) => {
                if (btn) {
                    btn.addEventListener('click', () => closeModal(modalEdit));
                }
            });

            modalEdit.addEventListener('click', (e) => {
                if (e.target === modalEdit) closeModal(modalEdit);
            });

            // Icon selection for edit modal
            const iconButtons = modalEdit.querySelectorAll('.icon-btn');
            const iconNameInput = modalEdit.querySelector('.icon-name-input');
            const iconNameDisplay = modalEdit.querySelector('.icon-name-display');

            iconButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();

                    iconButtons.forEach(b => {
                        b.classList.remove('bg-indigo-100', 'border-indigo-500');
                        b.classList.add('border-transparent', 'bg-white');
                    });

                    btn.classList.remove('border-transparent', 'bg-white');
                    btn.classList.add('bg-indigo-100', 'border-indigo-500');

                    const iconName = btn.dataset.icon;
                    iconNameInput.value = iconName;
                    iconNameDisplay.textContent = iconName;

                    feather.replace();
                });
            });
        });

        // ========== ICON SELECTION FOR ADD MODAL ==========
        const addModalIconButtons = modal.querySelectorAll('.icon-btn');
        const addModalIconInput = document.getElementById('icon-name-input');
        const addModalIconDisplay = document.getElementById('icon-name-display');

        addModalIconButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();

                addModalIconButtons.forEach(b => {
                    b.classList.remove('bg-indigo-100', 'border-indigo-500');
                    b.classList.add('border-transparent', 'bg-white');
                });

                btn.classList.remove('border-transparent', 'bg-white');
                btn.classList.add('bg-indigo-100', 'border-indigo-500');

                const iconName = btn.dataset.icon;
                addModalIconInput.value = iconName;
                addModalIconDisplay.textContent = iconName;

                feather.replace();
            });
        });

        // ========== DELETE CONFIRMATION ==========
        document.querySelectorAll('.delete-btn').forEach((deleteBtn) => {
            deleteBtn.addEventListener('click', (e) => {
                e.preventDefault();

                const form = deleteBtn.closest('.delete-form');

                Swal.fire({
                    title: 'Delete Service?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: 'Cancel',
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
                            title: 'Deleting service...',
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
        const createServiceBtn = document.getElementById('create_service_btn');
        const saveEditBtns = document.querySelectorAll('.save-edit-btn');

        createServiceBtn.addEventListener('click', (e) => {
            const form = e.target.closest('form');
            if (form.checkValidity()) {
                Swal.fire({
                    title: 'Creating service...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
        });

        saveEditBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const form = e.target.closest('form');
                if (form.checkValidity()) {
                    Swal.fire({
                        title: 'Updating service...',
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
            }
        });

        // ========== AUTO-REFRESH FEATHER ICONS ==========
        const observer = new MutationObserver(() => {
            feather.replace();
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        // ========== IMPORT FORM SUBMISSION ==========
        document.getElementById('importFile').addEventListener('change', () => {
            const form = document.getElementById('importForm');
            if (form && form.querySelector('#importFile').files.length > 0) {
                form.submit();
            }
        });
    </script>
@endpush
