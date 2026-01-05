@extends('layouts.admin')

@section('title', 'Dashboard - LaundryPRO')

@push('styles')
    <style>
        .stat-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .stat-icon {
            transition: transform 0.3s ease;
        }

        .chart-container {
            position: relative;
            height: 300px;
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

        .activity-item:hover {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            transform: translateX(4px);
        }

        .quick-action-btn {
            transition: all 0.3s ease;
        }

        .quick-action-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush

@section('content')
    <div class="space-y-8">
        <!-- Header with Filters -->
        <div class="bg-white p-6 rounded-2xl shadow-sm">
            <form action="{{ route('admin.dashboard.index') }}" method="GET">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-4">
                        <div>
                            <label for="start_date" class="text-sm font-medium text-gray-700">
                                Tanggal Mulai
                            </label>
                            <input type="date" name="start_date" id="start_date"
                                value="{{ $startDate }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">

                        </div>

                        <div>
                            <label for="end_date" class="text-sm font-medium text-gray-700">
                                Tanggal Akhir
                            </label>
                            <input type="date" name="end_date" id="end_date"
                                value="{{ $endDate }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">

                        </div>

                        <button type="submit"
                            class="self-end px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Filter
                        </button>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.dashboard.export.excel', request()->query()) }}"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Export Excel
                        </a>

                        <a href="{{ route('admin.dashboard.export.pdf', request()->query()) }}"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Export PDF
                        </a>
                    </div>
                </div>
            </form>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            <div class="stat-card bg-white p-6 rounded-2xl shadow-sm fade-in-up">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm text-gray-500 font-medium">Total Pelanggan</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalCustomers }}</p>
                    </div>
                    <div class="stat-icon bg-indigo-100 text-indigo-600 p-3 rounded-xl">
                        <i data-feather="users" class="w-6 h-6"></i>
                    </div>
                </div>
            </div>
            <div class="stat-card bg-white p-6 rounded-2xl shadow-sm fade-in-up">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm text-gray-500 font-medium">Transaksi Selesai</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $completedTransactions }}</p>
                    </div>
                    <div class="stat-icon bg-green-100 text-green-600 p-3 rounded-xl">
                        <i data-feather="check-circle" class="w-6 h-6"></i>
                    </div>
                </div>
            </div>
            <div class="stat-card bg-white p-6 rounded-2xl shadow-sm fade-in-up">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm text-gray-500 font-medium">Total Pendapatan</h3>
                        <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="stat-icon bg-yellow-100 text-yellow-600 p-3 rounded-xl">
                        <i data-feather="dollar-sign" class="w-6 h-6"></i>
                    </div>
                </div>
            </div>
            <div class="stat-card bg-white p-6 rounded-2xl shadow-sm fade-in-up">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm text-gray-500 font-medium">Pending Order</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $pendingOrders }}</p>
                    </div>
                    <div class="stat-icon bg-red-100 text-red-600 p-3 rounded-xl">
                        <i data-feather="clock" class="w-6 h-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm xl:col-span-2 fade-in-up">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Grafik Pendapatan</h2>
                </div>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm fade-in-up">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Layanan Populer</h2>
                </div>
                <div class="chart-container">
                    <canvas id="servicesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 bg-gray-50 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Transaksi Terbaru</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Total</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status Bayar</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Kasir</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($transactions->take(10) as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $transaction->transaction_code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->customer->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp
                                    {{ number_format($transaction->total, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($transaction->payment_status == 'Paid')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Lunas</span>
                                    @elseif($transaction->payment_status == 'Partial')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">DP</span>
                                    @else
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Belum
                                            Bayar</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->user->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data transaksi
                                    untuk periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm fade-in-up xl:col-span-2">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Aktivitas Terbaru</h2>
                <ul class="divide-y divide-gray-100">
                    @foreach ($activitiesdata as $activity)
                        @php
                            $actionColor = match ($activity->action) {
                                'create' => 'text-green-600',
                                'update' => 'text-blue-600',
                                'delete' => 'text-red-600',
                                default => 'text-gray-600',
                            };
                        @endphp
                        <li class="activity-item py-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        <span class="font-semibold">{{ $activity->user?->name ?? 'System' }}</span>
                                        <span
                                            class="{{ $actionColor }} font-medium">[{{ ucfirst($activity->action) }}]</span>
                                    </p>
                                    <p class="text-sm text-gray-800">{{ $activity->description }}</p>
                                </div>
                                <p class="text-xs text-gray-400 whitespace-nowrap">
                                    {{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm fade-in-up">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
                <div class="flex flex-col space-y-3">
                    <a href="{{ route('admin.orders.index') }}"
                        class="quick-action-btn bg-indigo-600 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2"><i
                            data-feather="plus-circle" class="w-4 h-4"></i><span>Transaksi Baru</span></a>
                    <a href="{{ route('admin.customers.index') }}"
                        class="quick-action-btn bg-green-600 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2"><i
                            data-feather="users" class="w-4 h-4"></i><span>Data Pelanggan</span></a>
                    <a href="{{ route('admin.services.index') }}"
                        class="quick-action-btn bg-blue-500 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2"><i
                            data-feather="package" class="w-4 h-4"></i><span>Layanan</span></a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        feather.replace();
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($revenueTrend->keys()) !!},
                    datasets: [{
                        label: 'Pendapatan',
                        data: {!! json_encode($revenueTrend->values()) !!},
                        borderColor: 'rgb(99, 102, 241)',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(99, 102, 241)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            borderRadius: 8,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(1) + ' jt';
                                    } else if (value >= 1000) {
                                        return 'Rp ' + (value / 1000).toFixed(0) + ' rb';
                                    } else {
                                        return 'Rp ' + value;
                                    }
                                },
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }
        const servicesCtx = document.getElementById('servicesChart');
        if (servicesCtx) {
            new Chart(servicesCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($popularServiceChartData->pluck('service_name')) !!},
                    datasets: [{
                        data: {!! json_encode($popularServiceChartData->pluck('detail_transactions_count')) !!},
                        backgroundColor: ['rgb(99, 102, 241)', 'rgb(16, 185, 129)', 'rgb(245, 158, 11)',
                            'rgb(139, 92, 246)', 'rgb(239, 68, 68)'
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 11
                                },
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 10,
                            borderRadius: 8,
                            titleFont: {
                                size: 13,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 12
                            },
                            callbacks: {
                                label: function(context) {
                                    const dataset = context.dataset;
                                    const total = dataset.data.reduce((a, b) => a + b, 0);
                                    const value = context.parsed;
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
@endpush
