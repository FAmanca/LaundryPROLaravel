@component('mail::message')
# Halo, {{ $customerName }}!

Pesanan laundry Anda dengan kode **{{ $transactionCode }}** saat ini berstatus:

@component('mail::panel')
**{{ strtoupper($status) }}**
@endcomponent

Terima kasih sudah menggunakan layanan **LaundryPRO**.
Kami akan mengabari lagi saat status cucian Anda berubah.

Salam hangat,
**Tim LaundryPRO**
@endcomponent
