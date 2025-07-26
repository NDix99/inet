<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.15);
            border-radius: 5px;
        }
        
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 30px;
            border-bottom: 1px solid #eee;
        }
        
        .company-info h1 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .company-info p {
            color: var(--secondary-color);
            margin-bottom: 3px;
            font-size: 13px;
        }
        
        .invoice-title {
            text-align: right;
        }
        
        .invoice-title h2 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .invoice-title .invoice-number {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .invoice-title .date {
            color: var(--secondary-color);
            font-size: 13px;
            margin-bottom: 3px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            margin-top: 10px;
        }
        
        .status-paid {
            background-color: var(--success-color);
            color: white;
        }
        
        .status-unpaid {
            background-color: var(--warning-color);
            color: #212529;
        }
        
        .status-overdue {
            background-color: var(--danger-color);
            color: white;
        }
        
        .invoice-body {
            padding: 30px;
        }
        
        .customer-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .bill-to, .payment-info {
            width: 48%;
        }
        
        .bill-to h3, .payment-info h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        
        .bill-to p, .payment-info p {
            margin-bottom: 3px;
            font-size: 13px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .items-table th {
            background-color: #f8f9fa;
            color: var(--secondary-color);
            font-weight: 600;
            font-size: 13px;
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        
        .items-table th:last-child,
        .items-table td:last-child {
            text-align: right;
        }
        
        .item-name {
            font-weight: 600;
        }
        
        .item-description {
            color: var(--secondary-color);
            font-size: 12px;
            margin-top: 3px;
        }
        
        .price-breakdown {
            font-size: 12px;
            color: var(--secondary-color);
            margin-top: 3px;
        }
        
        .totals {
            width: 50%;
            margin-left: auto;
        }
        
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .totals-table td {
            padding: 8px 0;
            font-size: 13px;
        }
        
        .totals-table td:last-child {
            text-align: right;
        }
        
        .subtotal-row td {
            padding-top: 0;
        }
        
        .grand-total-row {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 16px;
            border-top: 2px solid #eee;
            padding-top: 10px;
            margin-top: 5px;
        }
        
        .notes {
            margin-top: 30px;
            padding: 15px;
            background-color: #fff8e1;
            border-left: 4px solid var(--warning-color);
            border-radius: 3px;
            font-size: 13px;
        }
        
        .notes-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: #856404;
        }
        
        .invoice-footer {
            text-align: center;
            padding: 20px 30px;
            background-color: #f8f9fa;
            border-top: 1px solid #eee;
            color: var(--secondary-color);
            font-size: 12px;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
        }
        
        .invoice-footer p {
            margin-bottom: 3px;
        }
        
        .print-buttons {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .print-button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            margin: 0 5px;
        }
        
        .print-button:hover {
            background-color: #0056b3;
        }
        
        .close-button {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            margin: 0 5px;
        }
        
        .close-button:hover {
            background-color: #555;
        }
        
        @media print {
            body {
                background-color: white;
                padding: 0;
                margin: 0;
            }
            
            .invoice-container {
                box-shadow: none;
                margin: 0;
                max-width: 100%;
            }
            
            .print-buttons {
                display: none;
            }
            
            .invoice-footer {
                position: fixed;
                bottom: 0;
                width: 100%;
            }
        }
        
        @media (max-width: 768px) {
            .invoice-header, .customer-details {
                flex-direction: column;
            }
            
            .invoice-title {
                text-align: left;
                margin-top: 20px;
            }
            
            .bill-to, .payment-info {
                width: 100%;
                margin-bottom: 20px;
            }
            
            .totals {
                width: 100%;
            }
        }
        body::before {
            content: '';
            position: fixed;
            top: 50%;
            left: 50%;
            width: 400px;
            height: 400px;
            background: url('https://www.dataartasedaya.net.id/assets/images/logov2.png') no-repeat center center;
            background-size: contain;
            opacity: 0.08;
            z-index: 0;
            pointer-events: none;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>
    <div class="print-buttons">
        <button onclick="window.print();" class="print-button">
            <i class="fas fa-print"></i> Cetak Invoice
        </button>
        <button onclick="window.close();" class="close-button">
            <i class="fas fa-times"></i> Tutup
        </button>
    </div>

    <div class="invoice-container">
        <div class="invoice-header">
            <div class="company-info">
                <img src="https://www.dataartasedaya.net.id/assets/images/logov2.png" alt="Logo" style="height:60px; margin-bottom:10px;">
                <h1>INET COMPANY</h1>
                <p>Jl. Internet No. 123, Kota Digital</p>
                <p>Telepon: (021) 123-4567</p>
                <p>Email: info@inetcompany.com</p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <div class="invoice-number">No. {{ $invoice->invoice_number }}</div>
                <div class="date">Tanggal: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</div>
                <div class="date">Jatuh Tempo: {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</div>
                @if ($invoice->status == 'paid')
                    <div class="status-badge status-paid">LUNAS</div>
                @elseif ($invoice->status == 'unpaid')
                    <div class="status-badge status-unpaid">BELUM LUNAS</div>
                @elseif ($invoice->status == 'overdue')
                    <div class="status-badge status-overdue">TERLAMBAT</div>
                @endif
            </div>
        </div>
        
        <div class="invoice-body">
            <div class="customer-details">
                <div class="bill-to">
                    <h3>Tagihan Kepada</h3>
                    <p><strong>{{ $invoice->customer->name }}</strong></p>
                    <p>{{ $invoice->customer->address }}</p>
                    <p>Telepon: {{ $invoice->customer->phone }}</p>
                    <p>Email: {{ $invoice->customer->email }}</p>
                </div>
                
                <div class="payment-info">
                    <h3>Informasi Pembayaran</h3>
                    <p><strong>Metode:</strong> Transfer Bank</p>
                    <p><strong>Bank:</strong> Bank Digital</p>
                    <p><strong>No. Rekening:</strong> 123-456-7890</p>
                    <p><strong>Atas Nama:</strong> INET COMPANY</p>
                </div>
            </div>
            
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Deskripsi</th>
                        <th width="30%">Harga (Termasuk PPN 11%)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="item-name">{{ $invoice->package->name }}</div>
                            <div class="item-description">{{ $invoice->package->description ?? 'Paket Internet/Metro' }}</div>
                            <div class="item-description">Periode: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('F Y') }}</div>
                        </td>
                        <td>
                            <div>Rp {{ number_format($invoice->amount + $invoice->tax_amount, 0, ',', '.') }}</div>
                            <div class="price-breakdown">
                                <div>Harga Dasar: Rp {{ number_format($invoice->amount, 0, ',', '.') }}</div>
                                <div>PPN 11%: Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</div>
                            </div>
                        </td>
                    </tr>
                    @if($invoice->is_printed_superadmin)
                    <tr>
                        <th>Print Status:</th>
                        <td>
                            <span style="color: #28a745; font-weight: bold;">
                                ✓ Tercetak pada {{ $invoice->printed_at_superadmin ? \Carbon\Carbon::parse($invoice->printed_at_superadmin)->format('d/m/Y H:i') : '-' }}
                                <br>
                                Dicetak oleh: {{ optional($invoice->printed_by_superadmin ? \App\Models\User::find($invoice->printed_by_superadmin) : null)->name ?? '-' }}
                            </span>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
            
            <div class="totals">
                <table class="totals-table">
                    <tr class="subtotal-row">
                        <td>Harga (Termasuk PPN 11%):</td>
                        <td>Rp {{ number_format($invoice->amount + $invoice->tax_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Fee Teknisi ({{ $invoice->technician_fee_percentage }}%):</td>
                        <td>Rp {{ number_format($invoice->technician_fee_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="grand-total-row">
                        <td>Total:</td>
                        <td>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
            
            @if ($invoice->notes)
                <div class="notes">
                    <div class="notes-title">Catatan:</div>
                    {{ $invoice->notes }}
                </div>
            @endif
        </div>
        
        <div class="invoice-footer">
            <img src="https://www.dataartasedaya.net.id/assets/images/logov2.png" alt="Logo" style="height:40px; opacity:0.7; margin-bottom:8px;">
            <p>Terima kasih atas kerjasamanya!</p>
            <p>Invoice ini dibuat secara otomatis dan sah tanpa tanda tangan.</p>
            <p>Untuk informasi lebih lanjut, silakan hubungi kami di (021) 123-4567</p>
            <p><small>Catatan: Semua harga yang tercantum sudah termasuk PPN 11%</small></p>
        </div>
    </div>

    <script>
        window.onload = function() {
            // Auto print when page loads if not in frame
            if (window.opener) {
                window.print();
            }
        }
    </script>
</body>
</html> 