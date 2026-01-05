@forelse ($transactions as $transaction)
    <tr class="transaction-row" data-id="{{ $transaction->transaction_id }}">
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="checkbox" class="checkbox-custom row-checkbox"
                data-id="{{ $transaction->transaction_id }}">
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center gap-2">
                <div class="p-2 bg-indigo-100 rounded-lg">
                    <i data-feather="hash" class="w-4 h-4 text-indigo-600"></i>
                </div>
                <span class="text-sm font-bold text-gray-900">{{ $transaction->transaction_code }}</span>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr($transaction->customer->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $transaction->customer->name)[1] ?? '', 0, 1)) }}
                </div>
                <div>
                    <div class="text-sm font-semibold text-gray-900">{{ $transaction->customer->name }}</div>
                    <div class="text-xs text-gray-500">{{ $transaction->customer->phone }}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">{{ $transaction->user->name }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">
                {{ \Carbon\Carbon::parse($transaction->created_at)->translatedFormat('d M Y') }}
            </div>
            <div class="text-xs text-gray-500">
                {{ \Carbon\Carbon::parse($transaction->created_at)->format('H:i') }} WIB
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            @php
                $paymentBadge = [
                    'Paid' => ['class' => 'badge-paid', 'icon' => 'check', 'label' => 'Lunas'],
                    'Partial' => ['class' => 'badge-dp', 'icon' => 'percent', 'label' => 'DP'],
                    'Unpaid' => ['class' => 'badge-unpaid', 'icon' => 'x', 'label' => 'Belum Lunas'],
                ];
                $badge = $paymentBadge[$transaction->payment_status] ?? $paymentBadge['Unpaid'];
            @endphp
            <span class="{{ $badge['class'] }} inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold text-white shadow-sm">
                <i data-feather="{{ $badge['icon'] }}" class="w-3 h-3"></i>
                {{ $badge['label'] }}
                @if ($transaction->payment_status == 'Partial')
                    ({{ formatRupiahSingkat($transaction->ammount_paid) }})
                @endif
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            @php
                $laundryBadge = [
                    'Pending' => ['class' => 'bg-amber-500', 'icon' => 'loader', 'label' => 'Masuk'],
                    'Process' => ['class' => 'bg-blue-500', 'icon' => 'loader', 'label' => 'Proses'],
                    'Completed' => ['class' => 'bg-green-500', 'icon' => 'check-circle', 'label' => 'Siap Diambil'],
                    'Picked Up' => ['class' => 'bg-purple-500', 'icon' => 'package', 'label' => 'Selesai'],
                ];
                $laundry = $laundryBadge[$transaction->laundry_status] ?? $laundryBadge['Pending'];
            @endphp
            <span class="{{ $laundry['class'] }} inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold text-white shadow-sm">
                <i data-feather="{{ $laundry['icon'] }}" class="w-3 h-3"></i>
                {{ $laundry['label'] }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-bold text-gray-900">Rp {{ formatRupiahSingkat($transaction->total) }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-center">
            <div class="flex items-center justify-center gap-1">
                <button data-view-id="{{ $transaction->transaction_id }}"
                    class="view-btn action-btn w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 flex items-center justify-center text-blue-600 transition-colors"
                    title="Lihat Detail">
                    <i data-feather="eye" class="w-4 h-4"></i>
                </button>
                <button data-edit-id="{{ $transaction->transaction_id }}"
                    class="edit-btn action-btn w-8 h-8 rounded-lg bg-amber-100 hover:bg-amber-200 flex items-center justify-center text-amber-600 transition-colors"
                    title="Edit">
                    <i data-feather="edit-2" class="w-4 h-4"></i>
                </button>
                <button
                    data-url="{{ route('admin.transactions.send-email', $transaction->transaction_id) }}"
                    data-email-id="{{ $transaction->transaction_id }}"
                    data-customer-email="{{ $transaction->customer->email }}"
                    data-customer-name="{{ $transaction->customer->name }}"
                    data-transaction-code="{{ $transaction->transaction_code }}"
                    class="email-btn action-btn w-8 h-8 rounded-lg bg-purple-100 hover:bg-purple-200 flex items-center justify-center text-purple-600 transition-colors"
                    title="Email">
                    <i data-feather="mail" class="w-4 h-4"></i>
                </button>
                <button data-wa-id="{{ $transaction->transaction_id }}"
                    data-customer-phone="{{ $transaction->customer->phone }}"
                    data-customer-name="{{ $transaction->customer->name }}"
                    class="wa-btn action-btn w-8 h-8 rounded-lg bg-green-100 hover:bg-green-200 flex items-center justify-center text-green-600 transition-colors"
                    title="WhatsApp">
                    <i data-feather="message-circle" class="w-4 h-4"></i>
                </button>
                <form action="{{ route('admin.orders.delete', $transaction->transaction_id) }}"
                    method="POST" class="delete-form inline">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                        class="delete-btn action-btn w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 flex items-center justify-center text-red-600 transition-colors"
                        title="Hapus">
                        <i data-feather="trash-2" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9" class="px-6 py-16 text-center">
            <div class="flex flex-col items-center justify-center">
                <div class="p-4 bg-gray-100 rounded-full mb-4">
                    <i data-feather="inbox" class="w-12 h-12 text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-1">Tidak Ada Transaksi</h3>
                <p class="text-sm text-gray-500">Tidak ada data yang sesuai dengan pencarian</p>
            </div>
        </td>
    </tr>
@endforelse
