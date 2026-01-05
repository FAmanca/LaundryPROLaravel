<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - LaundryPRO</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1f2937;
            background: #f9fafb;
            padding: 20px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
        }

        /* Header Section */
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 20px;
        }

        .company-info h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .company-info p {
            font-size: 14px;
            opacity: 0.95;
            line-height: 1.6;
        }

        .invoice-meta {
            text-align: right;
        }

        .invoice-number {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 4px;
        }

        .invoice-date {
            font-size: 18px;
            font-weight: 600;
        }

        /* Info Section */
        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            padding: 40px;
            border-bottom: 2px solid #f3f4f6;
        }

        .info-box {
            background: #f9fafb;
            padding: 24px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }

        .info-box h3 {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            margin-bottom: 16px;
            font-weight: 600;
        }

        .customer-name {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .info-label {
            color: #6b7280;
            min-width: 100px;
            font-weight: 500;
        }

        .info-value {
            color: #111827;
            font-weight: 400;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .status-lunas {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .laundry-badge {
            background: #ddd6fe;
            color: #5b21b6;
        }

        /* Parfum Section */
        .parfum-section {
            padding: 0 40px 20px;
        }

        .parfum-box {
            background: #fef3c7;
            border: 2px dashed #fbbf24;
            border-radius: 8px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .parfum-icon {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .parfum-text {
            font-size: 14px;
            color: #92400e;
        }

        .parfum-name {
            font-weight: 700;
            font-size: 16px;
        }

        /* Order Details */
        .order-details {
            padding: 40px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-icon {
            width: 24px;
            height: 24px;
            background: #667eea;
            color: white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .order-item {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 12px;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .item-name {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
        }

        .item-price {
            font-size: 18px;
            font-weight: 700;
            color: #667eea;
        }

        .item-details {
            display: flex;
            gap: 20px;
            font-size: 13px;
            color: #6b7280;
        }

        .item-detail {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Summary Section */
        .summary-section {
            padding: 0 40px 40px;
        }

        .summary-box {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
        }

        .summary-title {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid #d1d5db;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .summary-label {
            color: #6b7280;
            font-weight: 500;
        }

        .summary-value {
            color: #111827;
            font-weight: 600;
        }

        .summary-total {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 2px solid #d1d5db;
        }

        .summary-total .summary-label {
            color: #111827;
            font-size: 16px;
            font-weight: 700;
        }

        .summary-total .summary-value {
            color: #667eea;
            font-size: 24px;
            font-weight: 700;
        }

        .payment-method {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #d1d5db;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .payment-badge {
            background: #667eea;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
        }

        /* Footer */
        .invoice-footer {
            background: #f9fafb;
            padding: 30px 40px;
            text-align: center;
            border-top: 2px solid #e5e7eb;
        }

        .footer-text {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .footer-highlight {
            color: #667eea;
            font-weight: 600;
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .invoice-container {
                box-shadow: none;
                border-radius: 0;
            }
        }

        @media (max-width: 640px) {
            .info-section {
                grid-template-columns: 1fr;
                gap: 20px;
                padding: 30px 20px;
            }

            .header-content {
                flex-direction: column;
            }

            .invoice-meta {
                text-align: left;
            }

            .invoice-header,
            .order-details,
            .summary-section,
            .parfum-section,
            .invoice-footer {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="header-content">
                <div class="company-info">
                    <h1>LaundryPRO</h1>
                    <p>Jl. Contoh No. 123, Jakarta Selatan<br>
                    Telp: (021) 1234-5678<br>
                    Email: info@laundrypro.com</p>
                </div>
                <div class="invoice-meta">
                    <div class="invoice-number">INVOICE</div>
                    <div class="invoice-date">LP116911SABCS96SF</div>
                </div>
            </div>
        </div>

        <!-- Customer & Transaction Info -->
        <div class="info-section">
            <!-- Customer Info -->
            <div class="info-box">
                <h3>📋 Informasi Pelanggan</h3>
                <div class="customer-name">Sigit Batagor</div>
                <div class="info-row">
                    <span class="info-label">ID Pelanggan:</span>
                    <span class="info-value">083158020884</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Lokasi:</span>
                    <span class="info-value">Jl Situi</span>
                </div>
            </div>

            <!-- Transaction Info -->
            <div class="info-box">
                <h3>📄 Informasi Transaksi</h3>
                <div class="info-row">
                    <span class="info-label">Kode Transaksi:</span>
                    <span class="info-value"><strong>LP116911SABCS96SF</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal:</span>
                    <span class="info-value">10 Nov 2025, 10:23 WIB</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kasir:</span>
                    <span class="info-value"><span class="status-badge status-pending">Asep Rendang</span></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status Bayar:</span>
                    <span class="info-value"><span class="status-badge status-lunas">✓ Lunas</span></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status Laundry:</span>
                    <span class="info-value"><span class="status-badge laundry-badge">🔄 Dicuci</span></span>
                </div>
            </div>
        </div>

        <!-- Parfum Section -->
        <div class="parfum-section">
            <div class="parfum-box">
                <div class="parfum-icon">🌸</div>
                <div>
                    <div class="parfum-text">Parfum Dipilih:</div>
                    <div class="parfum-name">Ocean Breeze</div>
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="order-details">
            <div class="section-title">
                <span class="section-icon">📦</span>
                Detail Pesanan
            </div>

            <div class="order-item">
                <div class="item-header">
                    <div class="item-name">Cuci Kering</div>
                    <div class="item-price">Rp 30.000</div>
                </div>
                <div class="item-details">
                    <div class="item-detail">
                        <span>⚖️</span>
                        <span>3 kg × Rp 10.000</span>
                    </div>
                    <div class="item-detail">
                        <span>⏱️</span>
                        <span>Estimasi: 3 hari</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="summary-section">
            <div class="summary-box">
                <div class="summary-title">💰 Ringkasan Pembayaran</div>

                <div class="summary-row">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value">Rp 30.000</span>
                </div>

                <div class="summary-row">
                    <span class="summary-label">Diskon</span>
                    <span class="summary-value" style="color: #dc2626;">- Rp 0</span>
                </div>

                <div class="summary-row summary-total">
                    <span class="summary-label">Total Pembayaran</span>
                    <span class="summary-value">Rp 30.000</span>
                </div>

                <div class="payment-method">
                    <span class="summary-label">Metode Pembayaran:</span>
                    <span class="payment-badge">💵 Cash</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <p class="footer-text">
                <strong>Terima kasih atas kepercayaan Anda!</strong><br>
                Untuk informasi lebih lanjut, hubungi kami di <span class="footer-highlight">info@laundrypro.com</span>
            </p>
            <p class="footer-text" style="margin-top: 16px; font-size: 11px; color: #9ca3af;">
                Invoice ini dibuat secara otomatis oleh sistem LaundryPRO<br>
                Dokumen sah tanpa tanda tangan dan stempel
            </p>
        </div>
    </div>
</body>
</html>
