<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Laporan Transaksi</h1>
    <table>
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Pelanggan</th>
                <th>Layanan</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Kasir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->transaction_code }}</td>
                <td>{{ $transaction->customer->name }}</td>
                <td>
                    @foreach($transaction->details as $detail)
                        {{ $detail->service->service_name }} ({{ $detail->qty }} kg)
                    @endforeach
                </td>
                <td>{{ $transaction->created_at->format('d M Y') }}</td>
                <td>{{ $transaction->total }}</td>
                <td>{{ $transaction->payment_status }}</td>
                <td>{{ $transaction->user->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
