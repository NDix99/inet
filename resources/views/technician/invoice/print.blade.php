<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 14px;
            line-height: 1.5;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            border: 1px solid #ddd;
        }
        .invoice-header {
            border-bottom: 2px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .company-details {
            text-align: right;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        .invoice-details {
            margin-bottom: 20px;
        }
        .client-details, .payment-details {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        .total-section {
            margin-top: 30px;
        }
        .total-row {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #ddd;
        }
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        .status-unpaid {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-overdue {
            background-color: #f8d7da;
            color: #721c24;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
        .notes {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #6c757d;
        }
        @media print {
            body {
                padding: 0;
            }
            .invoice-container {
                border: none;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div style="display: flex; justify-content: space-between;">
                <div>
                    <img src="https://www.dataartasedaya.net.id/assets/images/logov1.png" alt="Logo" style="height:60px; margin-bottom:10px;">
                    <div class="invoice-title">INVOICE (Teknisi)</div>
                    <div>#{{ $invoice->invoice_number }}</div>
                </div>
                <div class="company-details">
                    <h2>PT Internet Cepat</h2>
                    <p>
                        Jl. Raya Internet No. 123<br>
                        Jakarta, Indonesia 12345<br>
                        Telp: (021) 123-4567<br>
                        Email: info@internetcepat.com
                    </p>
                </div>
            </div>
        </div>
        <div style="display: flex; justify-content: space-between;" class="invoice-details">
            <div class="client-details">
                <h3>Ditagihkan kepada:</h3>
                <p>
                    <strong>{{ $invoice->customer->name }}</strong><br>
                    {{ $invoice->customer->address }}<br>
                    Email: {{ $invoice->customer->email }}<br>
                    Telepon: {{ $invoice->customer->phone }}
                </p>
            </div>
            <div class="payment-details">
                <h3>Detail Pembayaran:</h3>
                <table style="width: auto;">
                    <tr>
                        <th>No. Invoice:</th>
                        <td>INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal:</th>
                        <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Jatuh Tempo:</th>
                        <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <span class="status {{ $invoice->status === 'paid' ? 'status-paid' : ($invoice->status === 'overdue' ? 'status-overdue' : ($invoice->status === 'cancelled' ? 'status-cancelled' : 'status-unpaid')) }}">
                                {{ $invoice->status === 'paid' ? 'Lunas' : ($invoice->status === 'overdue' ? 'Terlambat' : ($invoice->status === 'cancelled' ? 'Dibatalkan' : 'Belum Lunas')) }}
                            </span>
                        </td>
                    </tr>
                    @if($invoice->is_printed_technician)
                    <tr>
                        <th>Print Status:</th>
                        <td>
                            <span style="color: #28a745; font-weight: bold;">
                                ✓ Tercetak pada {{ $invoice->printed_at_technician ? \Carbon\Carbon::parse($invoice->printed_at_technician)->format('d/m/Y H:i') : '-' }}
                                <br>
                                Dicetak oleh: {{ optional($invoice->printed_by_technician ? \App\Models\User::find($invoice->printed_by_technician) : null)->name ?? '-' }}
                            </span>
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Paket</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Layanan Internet Bulanan</td>
                    <td>{{ $invoice->customer->package->name ?? 'Tidak ada paket' }}</td>
                    <td class="text-right">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        <div style="display: flex; justify-content: space-between;" class="total-section">
            <div style="width: 50%;">
                @if($invoice->notes)
                <div class="notes">
                    <strong>Catatan:</strong><br>
                    {{ $invoice->notes }}
                </div>
                @endif
                <div style="margin-top: 30px;">
                    <h3>Metode Pembayaran:</h3>
                    <p>
                        <strong>Transfer Bank</strong><br>
                        Bank Mandiri<br>
                        No. Rekening: 123-456-789-0<br>
                        Atas Nama: PT Internet Cepat
                    </p>
                </div>
            </div>
            <div style="width: 40%;">
                <table>

                    <tr>
                        <th>Harga Dasar:</th>
                        <td class="text-right">Rp {{ number_format($invoice->package->base_price ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>PPN (11%):</th>
                        <td class="text-right">Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Total:</th>
                        <td class="text-right">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right; font-style: italic; font-size: 12px; color: #6c757d; border-bottom: none;">
                            *Sudah termasuk PPN
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="footer">
            <img src="https://www.dataartasedaya.net.id/assets/images/logov2.png" alt="Logo" style="height:40px; opacity:0.7; margin-bottom:8px;">
            <p>Invoice ini dibuat secara otomatis dan sah tanpa tanda tangan.</p>
            <p>Jika ada pertanyaan tentang invoice ini, silakan hubungi kami di billing@internetcepat.com atau (021) 123-4567.</p>
            <p>Terima kasih atas kepercayaan Anda menggunakan layanan kami!</p>
        </div>
    </div>
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Cetak Invoice
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
            Tutup
        </button>
    </div>
</body>
</html> 