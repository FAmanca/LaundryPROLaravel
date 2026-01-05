<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LaundryPRO Admin')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar.collapsed .nav-text {
            display: none;
        }

        .sidebar.collapsed .logo-text {
            display: none;
        }

        .sidebar.collapsed .nav-item {
            justify-content: center;
        }

        .sidebar.collapsed .user-info {
            display: none;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Component -->
        <aside class="sidebar bg-white shadow-md w-64 flex flex-col">
            <!-- Logo -->
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg bg-primary-500 flex items-center justify-center">
                        <i data-feather="droplet" class="text-white"></i>
                    </div>
                    <span class="logo-text text-xl font-bold text-primary-700">LaundryPRO</span>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 overflow-y-auto px-2 py-4">
                <div class="space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard.index') }}"
                        class="nav-item flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i data-feather="home" class="w-5 h-5"></i>
                        <span class="nav-text ml-3 font-medium">Dashboard</span>
                    </a>

                    <!-- Pelanggan -->
                    <a href="{{ route('admin.customers.index') }}"
                        class="nav-item flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.customers.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i data-feather="users" class="w-5 h-5"></i>
                        <span class="nav-text ml-3 font-medium">Pelanggan</span>
                    </a>

                    <!-- Dropdown: Transaksi -->
                    <div class="dropdown">
                        <button class="nav-item flex items-center justify-between w-full px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 focus:outline-none {{ request()->routeIs('admin.transactions.*') || request()->routeIs('admin.orders.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-100' }}"
                            onclick="toggleDropdown('transaksiDropdown')">
                            <div class="flex items-center">
                                <i data-feather="clipboard" class="w-5 h-5"></i>
                                <span class="nav-text ml-3 font-medium">Transaksi</span>
                            </div>
                            <i data-feather="chevron-down" class="w-4 h-4 ml-auto"></i>
                        </button>

                        <div id="transaksiDropdown" class="hidden pl-11 mt-1 space-y-1">
                            <a href="{{ route('admin.orders.index') }}"
                                class="flex items-center px-3 py-2 rounded-md text-sm {{ request()->routeIs('admin.orders.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-100' }}">
                                <i data-feather="plus-circle" class="w-4 h-4 mr-2"></i>
                                Tambah Pesanan
                            </a>
                            <a href="{{ route('admin.transactions.index') }}"
                                class="flex items-center px-3 py-2 rounded-md text-sm {{ request()->routeIs('admin.transactions.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-100' }}">
                                <i data-feather="file-text" class="w-4 h-4 mr-2"></i>
                                Data Transaksi
                            </a>
                        </div>
                    </div>

                    <!-- Dropdown: Inventaris -->
                    <div class="dropdown">
                        <button class="nav-item flex items-center justify-between w-full px-4 py-3 rounded-lg text-gray-600 hover:bg-gray-100 focus:outline-none {{ request()->routeIs('admin.services.*') || request()->routeIs('admin.parfumes.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-100' }}"
                            onclick="toggleDropdown('inventarisDropdown')">
                            <div class="flex items-center ">
                                <i data-feather="database" class="w-5 h-5"></i>
                                <span class="nav-text ml-3 font-medium">Inventaris</span>
                            </div>
                            <i data-feather="chevron-down" class="w-4 h-4 ml-auto"></i>
                        </button>

                        <div id="inventarisDropdown" class="hidden pl-11 mt-1 space-y-1">
                            <a href="{{ route('admin.services.index') }}"
                                class="flex items-center px-3 py-2 rounded-md text-sm {{ request()->routeIs('admin.services.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-100' }}">
                                <i data-feather="package" class="w-4 h-4 mr-2"></i>
                                Layanan
                            </a>
                            <a href="{{ route('admin.parfumes.index') }}"
                                class="flex items-center px-3 py-2 rounded-md text-sm {{ request()->routeIs('admin.parfumes.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-100' }}">
                                <i data-feather="droplet" class="w-4 h-4 mr-2"></i>
                                Parfum
                            </a>
                        </div>
                    </div>

                    <!-- Lainnya -->
                    {{-- <a href="#"
                        class="nav-item flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('reports.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i data-feather="bar-chart-2" class="w-5 h-5"></i>
                        <span class="nav-text ml-3 font-medium">Reports</span>
                    </a> --}}

                    <a href="#"
                        class="nav-item flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('settings.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i data-feather="settings" class="w-5 h-5"></i>
                        <span class="nav-text ml-3 font-medium">Settings</span>
                    </a>
                </div>
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                        <i data-feather="user" class="text-gray-600"></i>
                    </div>
                    <div class="user-info">
                        <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'Admin User' }}</div>
                        <div class="text-xs text-gray-500">{{ auth()->user()->email ?? 'admin@laundrypro.com' }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 overflow-auto">
            <!-- Top Navigation Bar -->
            <header class="bg-white shadow-sm">
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button id="sidebar-toggle" class="text-gray-500 focus:outline-none">
                            <i data-feather="menu" class="w-5 h-5"></i>
                        </button>

                        {{-- <div class="relative">
                            <input type="text" placeholder="Search..."
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 w-64">
                            <i data-feather="search" class="absolute left-3 top-2.5 text-gray-400 w-5 h-5"></i>
                        </div> --}}
                    </div>

                    <div class="flex items-center space-x-4">
                        <button class="text-gray-500 relative">
                            <i data-feather="bell" class="w-5 h-5"></i>
                            @if (isset($notificationCount) && $notificationCount > 0)
                                <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
                            @endif
                        </button>

                        <div class="w-px h-6 bg-gray-200"></div>

                        <button class="text-gray-500">
                            <i data-feather="help-circle" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Initialize Feather Icons
        feather.replace();

        // Sidebar Toggle
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        });

        // Dropdown Toggle
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            dropdown.classList.toggle('hidden');
        }
    </script>
    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Notifikasi -->
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif


    @stack('scripts')
</body>

</html>
