@extends('layouts.admin')

@section('title', 'Parfume Management - LaundryPRO')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/parfume.css') }}">
@endpush

@section('content')
    <!-- Header Section -->
    <div class="mb-8 fade-in-up">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Manajemen Parfum</h1>
                <p class="text-gray-600 flex items-center gap-2">
                    <i data-feather="package" class="w-4 h-4"></i>
                    Kelola berbagai parfum untuk Laundry anda
                </p>
            </div>
            <button id="add-parfume-btn"
                class="btn-primary px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl font-medium hover:from-indigo-700 hover:to-blue-700 shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                <i data-feather="plus-circle" class="w-5 h-5"></i>
                <span>Tambah Parfum</span>
            </button>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden fade-in-up" style="animation-delay: 0.1s;">
        <!-- Card Header -->
        <div class="p-6 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-100 rounded-lg">
                        <i data-feather="droplet" class="w-5 h-5 text-indigo-600"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Koleksi Parfum</h2>
                        <p class="text-sm text-gray-500">{{ $parfumes->count() }} parfum tersedia</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    {{-- <button
                        class="action-btn px-4 py-2 text-sm bg-white text-gray-700 rounded-lg hover:bg-gray-50 border border-gray-200 shadow-sm flex items-center gap-2">
                        <i data-feather="filter" class="w-4 h-4"></i>
                        <span>Filter</span>
                    </button> --}}
                    <a href="{{ route('admin.parfumes.export') }}"
                        class="action-btn px-4 py-2 text-sm bg-white text-gray-700 rounded-lg hover:bg-gray-50 border border-gray-200 shadow-sm flex items-center gap-2">
                        <i data-feather="download" class="w-4 h-4"></i>
                        <span>Export</span>
                    </a>

                    <form action="{{ route('admin.parfumes.import') }}" method="POST" enctype="multipart/form-data"
                        id="importForm">
                        @csrf
                        <label for="importFile"
                            class="action-btn px-4 py-2 text-sm bg-white text-gray-700 rounded-lg hover:bg-gray-50 border border-gray-200 shadow-sm flex items-center gap-2 cursor-pointer">
                            <i data-feather="upload" class="w-4 h-4"></i>
                            <span>Import</span>
                        </label>

                        <input type="file" id="importFile" name="file" class="hidden" accept=".csv,.xlsx">
                    </form>

                    <a href="{{ asset('templates/parfume-import-laundry-template.xlsx') }}"
                        class="action-btn px-4 py-2 text-sm bg-white text-gray-700 rounded-lg hover:bg-gray-50 border border-gray-200 shadow-sm flex items-center gap-2">
                        <i data-feather="download" class="w-4 h-4"></i>
                        <span>Download Template</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Parfumes Grid -->
        <div class="p-6">
            @if ($parfumes->isEmpty())
                <div class="empty-state flex flex-col items-center justify-center py-20 text-center">
                    <div class="relative mb-6">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-indigo-200 to-blue-200 rounded-full blur-2xl opacity-50">
                        </div>
                        <div class="relative p-6 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-full">
                            <i data-feather="droplet" class="w-16 h-16 text-indigo-500"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum ada parfum</h3>
                    <p class="text-gray-600 text-base max-w-md mb-6">
                        Tambahkan parfum pertama Anda untuk mulai mengelola koleksi parfum laundry Anda.
                    </p>
                    <button onclick="document.getElementById('add-parfume-btn').click()"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl font-medium hover:from-indigo-700 hover:to-blue-700 shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                        <i data-feather="plus-circle" class="w-5 h-5"></i>
                        <span>Tambahkan parfum pertama anda</span>
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($parfumes as $parfume)
                        <div class="parfume-card border border-gray-200 rounded-2xl p-6 fade-in-up">
                            <!-- Card Header -->
                            <div class="flex items-start justify-between mb-5">
                                <div
                                    class="parfume-icon w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-50 to-blue-50 flex items-center justify-center shadow-sm">
                                    <i data-feather="{{ $parfume->icon_name ?? 'droplet' }}"
                                        class="text-indigo-600 w-7 h-7"></i>
                                </div>
                                <div class="flex gap-2">
                                    <button id="edit-parfume-btn-{{ $parfume->parfume_id }}"
                                        class="action-btn w-9 h-9 rounded-lg bg-gray-100 hover:bg-indigo-100 flex items-center justify-center text-gray-600 hover:text-indigo-600 transition-colors">
                                        <i data-feather="edit-2" class="w-4 h-4"></i>
                                    </button>
                                    <form action="{{ route('admin.parfumes.delete', $parfume->parfume_id) }}" method="POST"
                                        class="delete-form">
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
                                    {{ $parfume->parfume_name }}
                                </h3>
                                <p class="text-sm text-gray-600 line-clamp-2 min-h-[2.5rem]">
                                    {{ $parfume->description ?: 'Tidak ada deskripsi' }}
                                </p>
                            </div>

                            <!-- Card Footer -->
                            <div class="pt-4 border-t border-gray-100">
                                <span
                                    class="badge-active inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 border border-green-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Active
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $parfumes->links('pagination::tailwind') }}
            @endif
        </div>
    </div>

    <!-- Edit Modals (Outside Grid) -->
    @if (!$parfumes->isEmpty())
        @foreach ($parfumes as $parfume)
            <div id="parfume-modal-edit-{{ $parfume->parfume_id }}"
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
                                    <h3 class="text-xl font-bold text-gray-900">Edit Parfume</h3>
                                    <p class="text-sm text-gray-500">Update parfume information</p>
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
                        <form action="{{ route('admin.parfumes.update', $parfume->parfume_id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="space-y-5">
                                <!-- Parfume Name -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Parfume Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="parfume_name"
                                        placeholder="e.g., Cold Lavender, Fresh Linen"
                                        value="{{ $parfume->parfume_name }}" required
                                        class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                </div>

                                <!-- Description -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Description
                                    </label>
                                    <textarea name="description" rows="4"
                                        placeholder="Describe the parfume scent, characteristics, or best use cases..."
                                        class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-all">{{ $parfume->description }}</textarea>
                                    <p class="mt-2 text-xs text-gray-500">Optional: Add details about the fragrance profile
                                    </p>
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                                <button type="submit"
                                    class="save-edit-btn flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl font-medium hover:from-indigo-700 hover:to-blue-700 shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                                    <i data-feather="check" class="w-5 h-5"></i>
                                    <span>Update Parfume</span>
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
    @endif

    <!-- Add Modal -->
    <div id="parfume-modal"
        class="modal-overlay hidden fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="modal-content bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white p-6 border-b border-gray-200 rounded-t-2xl z-10">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-100 rounded-lg">
                            <i data-feather="plus-circle" class="w-5 h-5 text-indigo-600"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Add New Parfume</h3>
                            <p class="text-sm text-gray-500">Create a new parfume entry</p>
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
                <form action="{{ route('admin.parfumes.store') }}" method="POST">
                    @csrf

                    <div class="space-y-5">
                        <!-- Parfume Name -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Parfume Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="parfume_name"
                                placeholder="e.g., Cold Lavender, Fresh Linen, Ocean Breeze" required
                                class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" rows="4"
                                placeholder="Describe the parfume scent, characteristics, or best use cases..."
                                class="input-field w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-all"></textarea>
                            <p class="mt-2 text-xs text-gray-500">Optional: Add details about the fragrance profile</p>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                        <button type="submit" id="create_parfume_btn"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl font-medium hover:from-indigo-700 hover:to-blue-700 shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                            <i data-feather="save" class="w-5 h-5"></i>
                            <span>Save Parfume</span>
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
    <script src="{{ asset('assets/admin/js/parfume.js') }}"></script>
@endpush
