@php
$singleTechnician = null;
if (isset($technicianId) && $technicianId) {
    $singleTechnician = collect($technicianData)->firstWhere('technician.id', $technicianId);
}
@endphp

@if($singleTechnician)
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan Mitra - {{ $singleTechnician['technician']->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 14px;
            line-height: 1.5;
            position: relative;
        }
        .watermark-bg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 120px;
            color: rgba(0,0,0,0.06);
            font-weight: bold;
            z-index: 99;
            pointer-events: none;
            white-space: nowrap;
            user-select: none;
        }
        @media print {
            .watermark-bg {
                display: block !important;
            }
        }
        /* Untuk singleTechnician mode */
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            border: 1px solid #ddd;
            background: white;
            position: relative;
            z-index: 1;
            /* Tidak ada watermark background */
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
        
        .mitra-details {
            margin-bottom: 20px;
        }
        
        .summary-section {
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
            font-weight: bold;
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
            background-color: #f8f9fa;
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
            
            .watermark {
                color: rgba(0, 0, 0, 0.03);
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Cetak Tagihan
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
            Tutup
        </button>
    </div>
   
    <div class="invoice-container">
        
        <div class="invoice-header">
            <div style="display: flex; justify-content: space-between;">
                <div>
                    <img src="https://www.dataartasedaya.net.id/assets/images/logov1.png" alt="Logo Header" style="height:48px; margin-bottom:10px;">
                    <div class="invoice-title">TAGIHAN MITRA</div>
                    <div style="font-size: 16px; color: #6c757d;">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</div>
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
        
        <div style="display: flex; justify-content: space-between;" class="mitra-details">
            <div>
                <h3>Informasi Mitra:</h3>
                <p>
                    <strong>{{ $singleTechnician['technician']->name }}</strong><br>
                    Email: {{ $singleTechnician['technician']->email }}<br>
                    @if($singleTechnician['technician']->phone)
                    Telepon: {{ $singleTechnician['technician']->phone }}<br>
                    @endif
                    Jumlah Invoice: {{ $singleTechnician['invoice_count'] }}
                </p>
            </div>
            
            <div>
                <h3>Detail Periode:</h3>
                <table style="width: auto;">
                    <tr>
                        <th>Periode:</th>
                        <td>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Cetak:</th>
                        <td>{{ now()->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <span style="color: #28a745; font-weight: bold;">
                                ✓ Aktif
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="summary-section">
            <h3>Ringkasan Tagihan</h3>
            <table>
                <thead>
                    <tr>
                        <th>Deskripsi</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total Harga Dasar</td>
                        <td class="text-right">Rp {{ number_format($singleTechnician['revenue'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>PPN (11%)</td>
                        <td class="text-right">Rp {{ number_format($singleTechnician['ppn'], 0, ',', '.') }}</td>
                    </tr>
                    <tr style="background-color: #f8f9fa; font-weight: bold;">
                        <td>Total (Harga Dasar + PPN)</td>
                        <td class="text-right">Rp {{ number_format($singleTechnician['revenue'] + $singleTechnician['ppn'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Fee Teknisi ({{ number_format($singleTechnician['avg_fee_percentage'], 1) }}%)</td>
                        <td class="text-right">Rp {{ number_format($singleTechnician['fee'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Fee PT ({{ number_format(100 - $singleTechnician['avg_fee_percentage'], 1) }}%)</td>
                        <td class="text-right">Rp {{ number_format($singleTechnician['total_pt_fee'], 0, ',', '.') }}</td>
                    </tr>
                    <tr style="background-color: #f8f9fa; font-weight: bold;">
                        <td>Total Fee (Mitra + PT)</td>
                        <td class="text-right">Rp {{ number_format($singleTechnician['fee'] + $singleTechnician['total_pt_fee'], 0, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <th>Total Keseluruhan:</th>
                        <td class="text-right">Rp {{ number_format($singleTechnician['revenue'] + $singleTechnician['ppn'] + $singleTechnician['fee'] + $singleTechnician['total_pt_fee'], 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <!-- Blok catatan dihapus agar tampilan lebih simple -->
        
        <div class="footer">
            <img src="https://www.dataartasedaya.net.id/assets/images/logov2.png" alt="Logo Footer" style="height:32px; opacity:0.7; margin-bottom:8px;">
            <p>Tagihan ini dibuat secara otomatis dan sah tanpa tanda tangan.</p>
            <p>Jika ada pertanyaan tentang tagihan ini, silakan hubungi kami di billing@internetcepat.com atau (021) 123-4567.</p>
            <p>Terima kasih atas kerjasama Anda!</p>
        </div>
    </div>
</body>
</html>
@else
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan Mitra ({{ $startDate }} - {{ $endDate }})</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background-color: white;
        }
        
        .print-container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            padding: 20px;
            /* Tidak ada watermark background */
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            opacity: 0.8;
        }
        
        .company-info {
            margin-bottom: 20px;
            font-size: 11px;
        }
        
        .company-info p {
            margin: 2px 0;
        }
        
        .summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .summary-box {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            background: #f9f9f9;
        }
        
        .summary-box h3 {
            margin: 0 0 5px 0;
            font-size: 10px;
            font-weight: 600;
            color: #666;
        }
        
        .summary-box .amount {
            font-size: 14px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }
        
        .summary-box .icon {
            font-size: 16px;
            margin-bottom: 5px;
            opacity: 0.7;
        }
        
        .content {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }
        
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        
        th {
            background: #f5f5f5;
            color: #333;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .footer p {
            margin: 3px 0;
        }
        
        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin: 0 3px;
            font-size: 12px;
        }
        
        .btn-primary {
            background: #007bff;
            color: white;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .chart-section {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ddd;
            background: #f9f9f9;
        }
        
        .chart-container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .chart-item {
            text-align: center;
            padding: 10px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            min-width: 80px;
        }
        
        .chart-item .percentage {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .chart-item .label {
            font-size: 10px;
            color: #666;
            word-wrap: break-word;
        }
        
        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-printed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-unprinted {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-no-invoice {
            background: #e2e3e5;
            color: #383d41;
        }
        
        .status-paid {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 3px;
        }
        
        .status-unpaid {
            background-color: #fff3cd;
            color: #856404;
            padding: 2px 6px;
            border-radius: 3px;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .print-container {
                padding: 0;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" class="btn btn-primary">Cetak Laporan</button>
        <button onclick="window.close()" class="btn btn-secondary">Tutup</button>
    </div>
   
    <div class="print-container">
        <div class="header">
            <img src="https://www.dataartasedaya.net.id/assets/images/logov1.png" alt="Logo Header" style="height:48px; margin-bottom:10px;">
            <h1>LAPORAN KEUANGAN MITRA</h1>
            <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
            <p>INET COMPANY</p>
        </div>

        <div class="company-info">
            <p><strong>Nama:</strong> INET COMPANY</p>
            <p><strong>Alamat:</strong> Jl. Internet No. 123, Jakarta</p>
            <p><strong>Telepon:</strong> (021) 1234-5678 | <strong>Email:</strong> info@inetcompany.com</p>
        </div>

        <div class="summary">
            <div class="summary-box">
                <div class="icon">🤝</div>
                <h3>Total Mitra</h3>
                <div class="amount">{{ count($technicianData) }}</div>
                <p>Teknisi aktif</p>
            </div>
            <div class="summary-box">
                <div class="icon">💰</div>
                <h3>Total Fee Mitra</h3>
                <div class="amount">Rp {{ number_format($totalFee, 0, ',', '.') }}</div>
                <p>Berdasarkan persentase fee paket</p>
            </div>
            <div class="summary-box">
                <div class="icon">🧾</div>
                <h3>Total PPN</h3>
                <div class="amount">Rp {{ number_format($totalPPN, 0, ',', '.') }}</div>
                <p>PPN dari setiap invoice</p>
            </div>
            <div class="summary-box">
                <div class="icon">📈</div>
                <h3>Total Revenue</h3>
                <div class="amount">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <p>Seluruh periode</p>
            </div>
        </div>

        <div class="content">
            <div class="chart-section">
                <h2 class="section-title">Distribusi Fee Mitra</h2>
                <div class="chart-container">
                    @foreach($technicianData as $data)
                        <div class="chart-item">
                            <div class="percentage" style="color: #007bff;">
                                {{ $data['fee'] > 0 ? number_format(($data['fee'] / $totalFee) * 100, 1) : 0 }}%
                            </div>
                            <div class="label">{{ $data['technician']->name }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <h2 class="section-title">Detail Mitra</h2>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mitra</th>
                        <th>Pelanggan</th>
                        <th>Invoice</th>
                        <th class="text-right">Total Revenue</th>
                        <th class="text-right">Fee Mitra</th>
                        <th class="text-right">Fee PT</th>
                        <th class="text-right">PPN</th>
                        <th class="text-center">Status Cetak</th>
                        <th class="text-center">Status Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($technicianData as $index => $data)
                        @php
                            $allPrinted = true;
                            $hasInvoices = false;
                            foreach ($data['technician']->customers as $customer) {
                                foreach ($customer->invoices as $inv) {
                                    $hasInvoices = true;
                                    if (!$inv->is_printed) { 
                                        $allPrinted = false; 
                                        break 2; 
                                    }
                                }
                            }
                            
                            if (!$hasInvoices) {
                                $printStatus = 'Tidak Ada Invoice';
                                $statusClass = 'status-no-invoice';
                            } elseif ($allPrinted) {
                                $printStatus = 'Tercetak';
                                $statusClass = 'status-printed';
                            } else {
                                $printStatus = 'Belum Dicetak';
                                $statusClass = 'status-unprinted';
                            }
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $data['technician']->name }}</td>
                            <td class="text-center">{{ $data['customers_count'] }}</td>
                            <td class="text-center">{{ $data['invoice_count'] }}</td>
                            <td class="text-right">Rp {{ number_format($data['revenue'], 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($data['fee'], 0, ',', '.') }} ({{ number_format($data['avg_fee_percentage'], 1) }}%)</td>
                            <td class="text-right">Rp {{ number_format($data['total_pt_fee'], 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($data['ppn'], 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($data['invoice_count'] == 0)
                                    <span class="status status-no-invoice">Tidak Ada Invoice</span>
                                @elseif($data['is_printed'])
                                    <span class="status status-printed">Tercetak</span>
                                @else
                                    <span class="status status-unprinted">Belum Dicetak</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($data['invoice_count'] == 0)
                                    <span class="status status-no-invoice">Tidak Ada Invoice</span>
                                @elseif($data['is_paid'])
                                    <span class="status status-paid">Lunas</span>
                                    @if($data['payment_notes'])
                                        <br>
                                        <small>{{ $data['payment_notes'] }}</small>
                                    @endif
                                @else
                                    <span class="status status-unpaid">Belum Lunas</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="footer">
            <img src="https://www.dataartasedaya.net.id/assets/images/logov2.png" alt="Logo Footer" style="height:32px; opacity:0.7; margin-bottom:8px;">
            <p>Laporan ini dibuat secara otomatis oleh sistem INET COMPANY</p>
            <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
@endif 