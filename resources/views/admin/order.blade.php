@extends('layouts.admin')

@section('title', 'Create Order - LaundryPRO')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Buat Pesanan Baru</h1>
        <p class="text-gray-600">Pilih paketan laundry untuk dimasukan ke keranjang</p>
    </div>

    <form id="order-form" method="POST" action="{{ route('admin.orders.store') }}">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Services/Packages Section (Left - 2 columns) -->
            <div class="lg:col-span-2">
                <!-- Order Information -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pelanggan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Customer Select with Search -->
                        <div>
                            <label for="customer" class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Pelanggan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="customer-search"
                                    placeholder="Cari kustomer dengan nama atau telepon..." autocomplete="off"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                <input type="hidden" name="customer_id" id="customer-id" required>

                                <!-- Dropdown Results -->
                                <div id="customer-results"
                                    class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden">
                                    <div class="p-2 text-sm text-gray-500 text-center">Type to search...</div>
                                </div>
                            </div>
                            @error('customer_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Parfume Select -->
                        <div>
                            <label for="parfume" class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Parfum <span class="text-red-500">*</span>
                            </label>
                            <select id="parfume" name="parfume_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                <option value="">-- Pilih Parfum --</option>
                                @foreach ($parfumes as $parfume)
                                    <option value="{{ $parfume->parfume_id }}">{{ $parfume->parfume_name }}</option>
                                @endforeach
                            </select>
                            @error('parfume_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date Select -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                                Estimasi Selesai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="date" name="date" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" />
                            @error('date')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="flex items-center">
                                    Metode Pembayaran <span class="text-red-500">*</span>
                                </span>
                            </label>
                            <select id="payment-method" name="payment_method" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Pilih metode pembayaran</option>
                                <option value="cash">Cash</option>
                                <option value="bank">Transfer Bank</option>
                            </select>
                        </div>

                    </div>
                </div>

                @if ($services->isEmpty())
                    <div
                        class="flex flex-col items-center justify-center py-16 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                        <div class="p-4 bg-gray-100 rounded-full mb-4">
                            <i data-feather="archive" class="w-8 h-8"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-700 mb-1">Service Kosong</h2>
                        <p class="text-gray-500 text-sm max-w-md">Belum ada layanan yang ditambahkan. Tambahkan service baru
                            untuk mulai mengelola data laundry.</p>
                        <a href="{{ route('admin.services.index') }}"
                            class="px-4 py-2 mt-3 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition flex items-center space-x-2">
                            <i data-feather="plus" class="w-5 h-5"></i>
                            <span>Tambah Service Baru</span>
                        </a>
                    </div>
                @else
                    <!-- Services Grid -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Pilih Paketan</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="services-grid">
                            @foreach ($services as $service)
                                <div class="service-card border-2 border-gray-200 rounded-xl p-4 hover:border-primary-500 transition cursor-pointer"
                                    data-service-id="{{ $service->service_id }}"
                                    data-service-name="{{ $service->service_name }}" data-price="{{ $service->price }}"
                                    data-unit="{{ $service->unit }}">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center">
                                            <i data-feather="{{ $service->icon_name ?? 'package' }}"
                                                class="text-blue-500"></i>
                                        </div>
                                        <span class="text-sm font-medium text-primary-600">
                                            Rp {{ number_format($service->price, 0, ',', '.') }}/{{ $service->unit }}
                                        </span>
                                    </div>
                                    <h3 class="font-semibold text-gray-800 mb-1">{{ $service->service_name }}</h3>
                                    <p class="text-sm text-gray-500 mb-3">{{ $service->description }}</p>
                                    <div class="flex items-center space-x-2">
                                        <button type="button"
                                            class="minus-btn w-8 h-8 rounded-md bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                                            <i data-feather="minus" class="w-4 h-4"></i>
                                        </button>
                                        <input type="number" step="0.5" value="0" min="0"
                                            class="qty-input w-16 text-center border border-gray-300 rounded-md py-1">
                                        <button type="button"
                                            class="plus-btn w-8 h-8 rounded-md bg-primary-500 hover:bg-primary-600 flex items-center justify-center text-white">
                                            <i data-feather="plus" class="w-4 h-4"></i>
                                        </button>
                                        <span class="text-xs text-gray-500 ml-2">{{ $service->unit }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Cart Section (Right - 1 column) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm p-6 sticky top-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Keranjang</h2>

                    <!-- Cart Items -->
                    <div id="cart-items" class="space-y-3 mb-4 max-h-96 overflow-y-auto">
                        <div class="text-center py-8 text-gray-400">
                            <i data-feather="shopping-cart" class="w-12 h-12 mx-auto mb-2"></i>
                            <p class="text-sm">Keranjang mu kosong</p>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="border-t border-gray-200 pt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium" id="subtotal">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-base font-semibold pt-2 border-t border-gray-200">
                            <span class="text-gray-800">Total</span>
                            <span class="text-primary-600" id="total">Rp 0</span>
                        </div>
                    </div>

                    <!-- Payment status -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="flex items-center">
                                Status Pembayaran <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <select id="payment-status" name="payment-status" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Pilih status pembayaran</option>
                            <option value="paid">Lunas</option>
                            <option value="unpaid">Belum Lunas</option>
                            <option value="downpayment">DP (minimal 50% )</option>
                        </select>
                    </div>

                    <!-- DP Input -->
                    <div class="mt-4" id="dp-input">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah DP (Down Payment) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="downpayment_amount" id="downpayment-amount"
                                placeholder="Masukkan jumlah DP" step="1000"
                                class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary-500">
                        </div>
                        <p class="mt-1.5 text-xs text-gray-500" id="dp-hint">Minimal 50% dari total harga</p>
                    </div>

                    <!-- Hidden inputs for cart items -->
                    <div id="cart-hidden-inputs"></div>

                    <!-- Hidden input for total amount -->
                    <input type="hidden" name="total_amount" id="total-amount-input">

                    <!-- Action Buttons -->
                    <div class="mt-6 space-y-2">
                        <button type="submit" id="submit-btn"
                            class="w-full py-3 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i data-feather="check-circle" class="w-5 h-5"></i>
                            <span>Buat Transaksi</span>
                        </button>
                        <button type="button" id="clear-cart"
                            class="w-full py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition">
                            Bersihkan Keranjang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            feather.replace();

            // ==================== CUSTOMER SEARCH ====================
            const customerSearch = document.getElementById('customer-search');
            const customerResults = document.getElementById('customer-results');
            const customerIdInput = document.getElementById('customer-id');
            let searchTimeout;

            customerSearch.addEventListener('input', function() {
                const query = this.value.trim();
                clearTimeout(searchTimeout);

                if (query.length < 2) {
                    customerResults.classList.add('hidden');
                    customerIdInput.value = '';
                    return;
                }

                searchTimeout = setTimeout(() => {
                    const result = fetch(`/admin/customers/search?q=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(data => {
                            const customers = data.customers;

                            if (!Array.isArray(customers) || customers.length === 0) {
                                customerResults.innerHTML =
                                    '<div class="p-3 text-sm text-gray-500 text-center">No customers found</div>';
                                customerResults.classList.remove('hidden');
                                return;
                            }

                            customerResults.innerHTML = customers.map(customer => `
                                <div class="customer-option p-3 hover:bg-gray-50 cursor-pointer border-b last:border-b-0"
                                    data-id="${customer.customer_id}"
                                    data-name="${customer.name}"
                                    data-phone="${customer.phone}">
                                    <div class="font-medium text-sm text-gray-800">${customer.name}</div>
                                    <div class="text-xs text-gray-500">${customer.phone}</div>
                                </div>
                            `).join('');
                            customerResults.classList.remove('hidden');
                        })

                        .catch(err => {
                            console.error('Search error:', err);
                            customerResults.innerHTML =
                                '<div class="p-3 text-sm text-red-500 text-center">Error loading customers</div>';
                            customerResults.classList.remove('hidden');
                        });
                }, 300);
            });

            customerResults.addEventListener('click', function(e) {
                const option = e.target.closest('.customer-option');
                if (option) {
                    customerIdInput.value = option.dataset.id;
                    customerSearch.value = `${option.dataset.name} - ${option.dataset.phone}`;
                    customerResults.classList.add('hidden');
                }
            });

            document.addEventListener('click', function(e) {
                if (!customerSearch.contains(e.target) && !customerResults.contains(e.target)) {
                    customerResults.classList.add('hidden');
                }
            });

            // ==================== CART MANAGER ====================
            const CartManager = {
                items: {},

                setItem(serviceId, serviceName, price, qty, unit) {
                    qty = parseFloat(qty);

                    if (qty > 0) {
                        if (this.items[serviceId]) {
                            this.items[serviceId].qty = qty;
                        } else {
                            this.items[serviceId] = {
                                id: serviceId,
                                name: serviceName,
                                price: parseFloat(price),
                                qty: qty,
                                unit: unit
                            };
                        }
                        console.log('✅ Item updated:', serviceId, 'qty:', this.items[serviceId].qty);
                    } else {
                        this.removeItem(serviceId);
                    }

                    this.render();
                },

                removeItem(serviceId) {
                    if (this.items[serviceId]) {
                        delete this.items[serviceId];
                        console.log('🗑️ Item removed:', serviceId);

                        const card = document.querySelector(`.service-card[data-service-id="${serviceId}"]`);
                        if (card) {
                            const qtyInput = card.querySelector('.qty-input');
                            if (qtyInput) qtyInput.value = 0;
                        }
                    }
                    this.render();
                },

                clearAll() {
                    this.items = {};
                    document.querySelectorAll('.qty-input').forEach(input => input.value = 0);
                    console.log('🧹 Cart cleared');
                    this.render();
                },

                getSubtotal() {
                    let total = 0;
                    for (const item of Object.values(this.items)) {
                        total += item.price * item.qty;
                    }
                    return total;
                },

                formatRupiah(amount) {
                    return `Rp ${Math.round(amount).toLocaleString('id-ID')}`;
                },

                render() {
                    const cartItemsContainer = document.getElementById('cart-items');
                    const cartHiddenInputs = document.getElementById('cart-hidden-inputs');
                    const subtotal = this.getSubtotal();

                    cartHiddenInputs.innerHTML = '';

                    if (Object.keys(this.items).length === 0) {
                        cartItemsContainer.innerHTML = `
                <div class="text-center py-8 text-gray-400">
                    <i data-feather="shopping-cart" class="w-12 h-12 mx-auto mb-2"></i>
                    <p class="text-sm">Keranjang masih kosong</p>
                </div>`;
                        feather.replace();
                    } else {
                        let itemsHTML = '';
                        let itemIndex = 0;

                        for (const item of Object.values(this.items)) {
                            const itemTotal = item.price * item.qty;

                            itemsHTML += `
                <div class="flex items-start justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-800 text-sm">${item.name}</h4>
                        <p class="text-xs text-gray-500">${item.qty} ${item.unit} × ${this.formatRupiah(item.price)}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-sm text-gray-800">${this.formatRupiah(itemTotal)}</p>
                        <button type="button" onclick="CartManager.removeItem('${item.id}')"
                            class="text-xs text-red-500 hover:text-red-700 mt-1">
                            Remove
                        </button>
                    </div>
                </div>`;

                            cartHiddenInputs.innerHTML += `
                <input type="hidden" name="items[${itemIndex}][service_id]" value="${item.id}">
                <input type="hidden" name="items[${itemIndex}][quantity]" value="${item.qty}">
                <input type="hidden" name="items[${itemIndex}][price]" value="${item.price}">
                `;
                            itemIndex++;
                        }

                        cartItemsContainer.innerHTML = itemsHTML;
                    }

                    document.getElementById('subtotal').textContent = this.formatRupiah(subtotal);
                    document.getElementById('total-amount-input').value = subtotal;
                    this.updateTotalDisplay(subtotal);
                    // cartData.value = JSON.stringify(this.items);

                    console.log('✅ Cart rendered. Items:', Object.keys(this.items).length, 'Subtotal:', this.formatRupiah(
                        subtotal));
                },

                updateTotalDisplay(subtotal) {
                    const totalElement = document.getElementById('total');
                    const dpInputDiv = document.getElementById('dp-input');
                    const dpInput = document.getElementById('downpayment-amount');
                    const dpHint = document.getElementById('dp-hint');
                    const paymentStatus = document.getElementById('payment-status')?.value || '';
                    const minDP = Math.round(subtotal * 0.5);

                    // ✅ Hanya tampil jika status = downpayment
                    if (paymentStatus === 'downpayment') {
                        dpInputDiv.classList.remove('hidden');
                        dpInput.required = true;
                        dpInput.setAttribute('min', minDP);
                        dpInput.setAttribute('max', subtotal);
                        dpHint.textContent =
                            `Minimal ${this.formatRupiah(minDP)} - Maksimal ${this.formatRupiah(subtotal)}`;

                        if (!dpInput.value || dpInput.value === '0') dpInput.value = minDP;

                        const dpAmount = parseFloat(dpInput.value) || 0;
                        const remaining = subtotal - dpAmount;

                        totalElement.innerHTML = `
                <div class="flex flex-col text-right">
                    <span class="text-primary-600 font-semibold">${this.formatRupiah(dpAmount)}</span>
                    <span class="text-xs text-gray-500">Sisa: ${this.formatRupiah(Math.max(remaining, 0))}</span>
                </div>`;
                    } else {
                        dpInputDiv.classList.add('hidden');
                        dpInput.required = false;
                        dpInput.value = '';
                        totalElement.textContent = this.formatRupiah(subtotal);
                    }
                }
            };

            // ✅ Tambahkan event listener supaya update DP ketika status berubah
            document.getElementById('payment-status')?.addEventListener('change', function() {
                const subtotal = CartManager.getSubtotal();
                CartManager.updateTotalDisplay(subtotal);
            });


            // ==================== SERVICE CARD INTERACTIONS ====================
            document.querySelectorAll('.service-card').forEach(card => {
                const minusBtn = card.querySelector('.minus-btn');
                const plusBtn = card.querySelector('.plus-btn');
                const qtyInput = card.querySelector('.qty-input');
                const serviceId = card.dataset.serviceId;
                const serviceName = card.dataset.serviceName;
                const price = parseFloat(card.dataset.price);
                const unit = card.dataset.unit;

                plusBtn.addEventListener('click', () => {
                    const currentVal = parseFloat(qtyInput.value) || 0;
                    const newVal = currentVal + 1;
                    qtyInput.value = newVal;
                    CartManager.setItem(serviceId, serviceName, price, newVal, unit);
                    feather.replace();
                });

                minusBtn.addEventListener('click', () => {
                    const currentVal = parseFloat(qtyInput.value) || 0;
                    if (currentVal > 0) {
                        const newVal = Math.max(0, currentVal - 1);
                        qtyInput.value = newVal;
                        CartManager.setItem(serviceId, serviceName, price, newVal, unit);
                        feather.replace();
                    }
                });

                qtyInput.addEventListener('change', () => {
                    let val = parseFloat(qtyInput.value) || 0;
                    val = Math.max(0, val);
                    qtyInput.value = val;
                    CartManager.setItem(serviceId, serviceName, price, val, unit);
                    feather.replace();
                });

                qtyInput.addEventListener('input', () => {
                    if (parseFloat(qtyInput.value) < 0) {
                        qtyInput.value = 0;
                    }
                });
            });

            // ==================== CLEAR CART BUTTON ====================
            document.getElementById('clear-cart').addEventListener('click', () => {
                if (confirm('Clear all items from cart?')) {
                    CartManager.clearAll();
                }
            });

            // ==================== PAYMENT METHOD CHANGE ====================
            document.getElementById('payment-method').addEventListener('change', () => {
                CartManager.render();
            });

            // ==================== DP INPUT HANDLERS ====================
            const dpInput = document.getElementById('downpayment-amount');

            dpInput.addEventListener('input', function() {
                const dpAmount = parseFloat(this.value) || 0;
                const subtotal = CartManager.getSubtotal();
                const minDP = Math.round(subtotal * 0.5);

                this.classList.remove('border-red-500');

                if (dpAmount < minDP && subtotal > 0 && this.value !== '') {
                    this.classList.add('border-red-500');
                } else if (dpAmount > subtotal && subtotal > 0) {
                    this.classList.add('border-red-500');
                }

                CartManager.render();
            });

            dpInput.addEventListener('blur', function() {
                const dpAmount = parseFloat(this.value) || 0;
                const subtotal = CartManager.getSubtotal();
                const minDP = Math.round(subtotal * 0.5);

                if (subtotal > 0) {
                    if (dpAmount < minDP) {
                        this.value = minDP;
                        alert(`Jumlah DP minimal ${CartManager.formatRupiah(minDP)} (50% dari total)`);
                    } else if (dpAmount > subtotal) {
                        this.value = subtotal;
                        alert(`Jumlah DP tidak boleh melebihi total ${CartManager.formatRupiah(subtotal)}`);
                    }
                }

                this.classList.remove('border-red-500');
                CartManager.render();
            });

            // ==================== FORM VALIDATION ====================
            document.getElementById('order-form').addEventListener('submit', function(e) {
                if (Object.keys(CartManager.items).length === 0) {
                    e.preventDefault();
                    alert('Silakan tambahkan minimal satu service ke cart!');
                    return false;
                }

                const customerId = document.getElementById('customer-id').value;
                if (!customerId) {
                    e.preventDefault();
                    alert('Silakan pilih customer terlebih dahulu!');
                    customerSearch.focus();
                    return false;
                }

                const paymentMethod = document.getElementById('payment-method').value;
                if (!paymentMethod) {
                    e.preventDefault();
                    alert('Silakan pilih metode pembayaran!');
                    return false;
                }

                if (paymentMethod === 'downpayment') {
                    const dpAmount = parseFloat(dpInput.value) || 0;
                    const subtotal = CartManager.getSubtotal();
                    const minDP = Math.round(subtotal * 0.5);

                    if (dpAmount < minDP) {
                        e.preventDefault();
                        alert(`Jumlah DP minimal ${CartManager.formatRupiah(minDP)} (50% dari total)`);
                        dpInput.focus();
                        return false;
                    }

                    if (dpAmount > subtotal) {
                        e.preventDefault();
                        alert(`Jumlah DP tidak boleh melebihi total ${CartManager.formatRupiah(subtotal)}`);
                        dpInput.focus();
                        return false;
                    }
                }

                const submitBtn = document.getElementById('submit-btn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Processing...</span>
            `;

                return true;
            });

            // ==================== INITIALIZE ====================
            CartManager.render();
        </script>
    @endpush
@endsection
