@extends('layouts.app')
@section('content')
    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes popIn {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        .animate-fade-in {
            animation: fadeIn 1s ease-out;
        }

        .animate-slide-up {
            animation: slideUp 1s ease-out 0.2s both;
        }

        .animate-pop-in {
            animation: popIn 0.4s ease-out;
        }

        .animate-spin-slow {
            animation: spin 1.5s linear infinite;
        }

        .bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s infinite ease-in-out;
        }

        .bubble-1 {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
        }

        .bubble-2 {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 15%;
        }

        .bubble-3 {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
        }

        .bubble-4 {
            width: 100px;
            height: 100px;
            top: 30%;
            right: 30%;
        }
    </style>
    <!-- Hero Section -->
    <div class="relative min-h-screen flex flex-col justify-center items-center px-4 py-16 overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center"
            style="background-image: url('https://images.unsplash.com/photo-1582735689369-4fe89db7114c?q=80&w=2070');">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/90 via-cyan-500/85 to-blue-600/90"></div>
        </div>

        <div class="absolute inset-0 opacity-20">
            <div class="bubble bubble-1"></div>
            <div class="bubble bubble-2"></div>
            <div class="bubble bubble-3"></div>
            <div class="bubble bubble-4"></div>
        </div>

        <div class="relative z-10 max-w-5xl mx-auto text-center text-white">
            <div class="flex items-center justify-center mb-8 animate-fade-in">
                <div class="bg-white bg-opacity-20 p-4 rounded-2xl backdrop-blur-lg shadow-2xl">
                    <i data-feather="droplet" class="w-16 h-16 text-white"></i>
                </div>
                <h1 class="text-5xl md:text-7xl font-extrabold ml-4 tracking-tight">LaundryPRO</h1>
            </div>

            <p class="text-2xl md:text-3xl mb-12 font-medium animate-slide-up">
                Your clothes deserve the royal treatment! <span class="inline-block animate-bounce">👑</span>
            </p>

            <div
                class="bg-white rounded-3xl p-8 md:p-10 shadow-2xl transform hover:scale-105 transition-all duration-300 animate-slide-up">
                <div class="flex items-center justify-center mb-4">
                    <i data-feather="package" class="w-8 h-8 text-blue-600 mr-3"></i>
                    <h2 class="text-gray-800 text-3xl font-bold">Lacak Laundry Anda</h2>
                </div>
                <p class="text-gray-600 mb-8 text-lg">Pantau status cucian Anda secara real-time</p>

                <div class="flex flex-col md:flex-row gap-3 max-w-2xl mx-auto">
                    <div class="relative flex-grow">
                        <i data-feather="hash"
                            class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                        <input id="tracking-code" type="text" placeholder="Masukkan kode laundry (LP12345)"
                            name="kode_laundry"
                            class="w-full pl-12 pr-4 py-4 rounded-xl border-2 border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 text-lg shadow-sm">
                    </div>
                    <button id="track-btn"
                        class="bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-bold px-8 py-4 rounded-xl transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <i data-feather="search" class="mr-2 w-5 h-5"></i> Lacak Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Loading -->
    <div id="tracking-modal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50">
        <div
            class="bg-white rounded-2xl shadow-2xl p-8 w-96 text-center animate-pop-in transform transition-all duration-500">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 border-4 border-blue-500 border-t-transparent rounded-full animate-spin-slow mx-auto">
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Mencari Data Laundry...</h3>
            <p class="text-gray-600 mb-6">Mohon tunggu sebentar, kami sedang memproses pencarian Anda.</p>
            <button id="close-modal"
                class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-semibold px-6 py-2 rounded-lg hover:opacity-90 transition">Tutup</button>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('tracking-modal');
            const trackBtn = document.getElementById('track-btn');
            const closeModal = document.getElementById('close-modal');

            trackBtn.addEventListener('click', async () => {
                const code = document.getElementById('tracking-code').value.trim();

                if (!code) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Masukkan kode laundry terlebih dahulu!',
                        confirmButtonColor: '#2563eb'
                    });
                    return;
                }

                modal.classList.remove('hidden');

                try {
                    const result = await search({
                        code
                    });

                    setTimeout(() => {
                        modal.classList.add('hidden');

                        if (result && result.laundry_status) {
                            Swal.fire({
                                title: 'Status Laundry Ditemukan!',
                                html: `
            <div class="text-left">
                <p><b>Kode:</b> ${result.transaction_code}</p>
                <p><b>Nama Pelanggan:</b> ${result.customer?.name ?? '-'}</p>
                <p><b>Status:</b> <span class="text-blue-600 font-semibold">${result.laundry_status}</span></p>
                <p><b>Estimasi Selesai:</b> ${result.estimated_date ?? '-'}</p>
                <p><b>Status Pembayaran:</b> ${result.payment_status}</p>
                <p><b>Total:</b> Rp ${result.total.toLocaleString('id-ID')}</p>
            </div>
        `,
                                icon: 'success',
                                confirmButtonColor: '#2563eb',
                                confirmButtonText: 'Oke'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Tidak Ditemukan!',
                                text: 'Kode laundry tidak terdaftar.',
                                confirmButtonColor: '#2563eb'
                            });
                        }

                    }, 1500);
                } catch (error) {
                    modal.classList.add('hidden');
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal memuat data dari server.',
                        confirmButtonColor: '#2563eb'
                    });
                    console.error('Error:', error);
                }
            });

            closeModal.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });

        async function search(params) {
            try {
                console.log('params:', params);
                console.log('query string:', new URLSearchParams(params).toString());

                const response = await fetch('/search?' + new URLSearchParams(params));
                if (!response.ok) throw new Error('Network response was not ok');
                const data = await response.json();
                console.log(data);

                return data;
            } catch (error) {
                console.error('Fetch error:', error);
                throw error;
            }
        }
    </script>
@endsection
