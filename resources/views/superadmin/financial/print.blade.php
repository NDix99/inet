<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Laporan Keuangan Mitra</title>
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
        
        .report-container {
            max-width: 1100px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.15);
            border-radius: 5px;
        }
        
        .report-header {
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
        
        .report-title {
            text-align: right;
        }
        
        .report-title h2 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .report-title .report-period {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--secondary-color);
        }
        
        .report-body {
            padding: 30px;
        }
        
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .summary-card {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .summary-card:hover {
            transform: translateY(-5px);
        }
        
        .summary-card .card-title {
            font-size: 14px;
            color: var(--secondary-color);
            margin-bottom: 5px;
        }
        
        .summary-card .card-value {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .summary-card.total-mitra {
            border-left: 4px solid var(--primary-color);
        }
        
        .summary-card.total-fee {
            border-left: 4px solid var(--success-color);
        }
        
        .summary-card.total-pt {
            border-left: 4px solid var(--danger-color);
        }
        
        .summary-card.total-revenue {
            border-left: 4px solid var(--info-color);
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
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        
        .items-table td {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
            vertical-align: middle;
        }
        
        .items-table th:last-child,
        .items-table td:last-child {
            text-align: right;
        }
        
        .items-table tr:hover {
            background-color: #f8f9fa;
        }
        
        .items-table tfoot {
            font-weight: bold;
        }
        
        .items-table tfoot tr {
            background-color: #f8f9fa;
        }
        
        .items-table tfoot td {
            padding: 12px 15px;
            border-top: 2px solid #eee;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-align: center;
        }
        
        .badge-success {
            background-color: var(--success-color);
            color: white;
        }
        
        .badge-warning {
            background-color: var(--warning-color);
            color: #212529;
        }
        
        .badge-danger {
            background-color: var(--danger-color);
            color: white;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 30px 0 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .technician-name {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .technician-email {
            font-size: 12px;
            color: var(--secondary-color);
        }
        
        .print-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            gap: 10px;
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
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .font-weight-bold {
            font-weight: 700;
        }
        
        .text-success {
            color: var(--success-color);
        }
        
        .text-warning {
            color: var(--warning-color);
        }
        
        .text-danger {
            color: var(--danger-color);
        }
        
        .text-info {
            color: var(--info-color);
        }
        
        .text-primary {
            color: var(--primary-color);
        }
        
        @media print {
            body {
                background-color: white;
                padding: 0;
                margin: 0;
            }
            
            .report-container {
                box-shadow: none;
                margin: 0;
                max-width: 100%;
            }
            
            .print-buttons {
                display: none;
            }
            
            .report-footer {
                position: fixed;
                bottom: 0;
                width: 100%;
            }
            
            .items-table {
                page-break-inside: avoid;
            }
        }
        
        @media (max-width: 768px) {
            .report-header {
                flex-direction: column;
            }
            
            .report-title {
                text-align: left;
                margin-top: 20px;
            }
            
            .summary-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="print-buttons">
        <button onclick="window.print();" class="print-button">
            <i class="fas fa-print"></i> Cetak Laporan
        </button>
        <button onclick="window.close();" class="close-button">
            <i class="fas fa-times"></i> Tutup
        </button>
    </div>

    <div class="report-container">
        <div class="report-header">
            <div class="company-info">
                <h1>INET COMPANY</h1>
                <p>Jl. Internet No. 123, Kota Digital</p>
                <p>Telepon: (021) 123-4567</p>
                <p>Email: info@inetcompany.com</p>
            </div>
            <div class="report-title">
                <h2>LAPORAN KEUANGAN MITRA</h2>
                <div class="report-period">
                    Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                </div>
            </div>
        </div>
        
        <div class="report-body">
            <div class="summary-cards">
                <div class="summary-card total-mitra">
                    <div class="card-title"><i class="fas fa-users"></i> Total Mitra</div>
                    <div class="card-value">{{ count($technicianData ?? []) }}</div>
                </div>
                <div class="summary-card total-fee">
                    <div class="card-title"><i class="fas fa-money-bill-wave"></i> Total Fee Mitra</div>
                    <div class="card-value">Rp {{ number_format($totalFee ?? 0, 0, ',', '.') }}</div>
                </div>
                <div class="summary-card total-pt">
                    <div class="card-title"><i class="fas fa-building"></i> Total Fee PT</div>
                    <div class="card-value">Rp {{ number_format($totalPtFee ?? 0, 0, ',', '.') }}</div>
                </div>
                <div class="summary-card total-revenue">
                    <div class="card-title"><i class="fas fa-chart-line"></i> Total Revenue</div>
                    <div class="card-value">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            
            <!-- Detail Mitra Table -->
            <h3 class="section-title"><i class="fas fa-users"></i> Detail Laporan Mitra</h3>
            @if(count($technicianData ?? []) > 0)
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Mitra</th>
                            <th>Pelanggan</th>
                            <th>Invoice</th>
                            <th>Total Revenue</th>
                            <th>Fee Mitra</th>
                            <th>Fee PT</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($technicianData ?? [] as $index => $technician)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="technician-name">{{ $technician['name'] }}</div>
                                    <div class="technician-email">{{ $technician['email'] }}</div>
                                </td>
                                <td class="text-center">{{ $technician['customer_count'] }}</td>
                                <td class="text-center">{{ $technician['invoice_count'] }}</td>
                                <td class="text-right">Rp {{ number_format($technician['total_revenue'], 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($technician['fee_amount'], 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($technician['pt_fee'], 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if ($technician['is_paid'])
                                        <span class="badge badge-success">Lunas</span>
                                    @else
                                        <span class="badge badge-warning">Belum</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right">Total:</td>
                            <td class="text-right">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($totalFee ?? 0, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($totalPtFee ?? 0, 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            @else
                <div style="padding: 20px; background-color: #d1ecf1; border-radius: 5px; color: #0c5460; margin-bottom: 20px;">
                    <i class="fas fa-info-circle"></i> Tidak ada data mitra yang tersedia untuk periode ini.
                </div>
            @endif
            
            <div class="report-footer">
                <p class="text-center text-muted">
                    <small>Laporan ini dicetak pada {{ now()->format('d/m/Y H:i') }} oleh {{ auth()->user()->name }}</small>
                </p>
            </div>
        </div>
    </div>
</body>
</html> 