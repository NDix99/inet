@php
$singleTechnician = null;
if (isset($technicianId) && $technicianId) {
    $singleTechnician = collect($technicianData)->firstWhere('technician.id', $technicianId);
}
@endphp

@if($singleTechnician && isset($singleTechnician['technician']))
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan Mitra - {{ $singleTechnician['technician']->name ?? 'N/A' }}</title>
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
            color: #265bed;
        }
        
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #265bed;
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
                    <h2>PT. DATA ARTA SEDAYA</h2>
                    <p>Jl. Ngawi - madiun, Ngawi, Prayungan, Cangakan,</p>
                    <p>Kec. Kasreman, Kabupaten Ngawi, Jawa Timur 63281</p>
				    <p>Telepon:  0857-3514-6195</p>
                    <p>Email: admin@dasnet.my.id</p>
                </div>
            </div>
        </div>
        
        <div style="display: flex; justify-content: space-between;" class="mitra-details">
            <div>
                <h3>Informasi Mitra:</h3>
                <p>
                    <strong>{{ $singleTechnician['technician']->name ?? 'N/A' }}</strong><br>
                    Email: {{ $singleTechnician['technician']->email ?? 'N/A' }}<br>
                    @if(isset($singleTechnician['technician']->phone) && $singleTechnician['technician']->phone)
                    Telepon: {{ $singleTechnician['technician']->phone }}<br>
                    @endif
                    Jumlah Invoice: {{ $singleTechnician['invoice_count'] ?? 0 }}
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
                        <td class="text-right">Rp {{ number_format($singleTechnician['revenue'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>PPN (11%)</td>
                        <td class="text-right">Rp {{ number_format($singleTechnician['ppn'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="background-color: #f8f9fa; font-weight: bold;">
                        <td>Total (Harga Dasar + PPN)</td>
                        <td class="text-right">Rp {{ number_format(($singleTechnician['revenue'] ?? 0) + ($singleTechnician['ppn'] ?? 0), 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Fee Teknisi ({{ number_format($singleTechnician['avg_fee_percentage'] ?? 0, 1) }}%)</td>
                        <td class="text-right">Rp {{ number_format($singleTechnician['fee'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Fee PT ({{ number_format(100 - ($singleTechnician['avg_fee_percentage'] ?? 0), 1) }}%)</td>
                        <td class="text-right">Rp {{ number_format($singleTechnician['total_pt_fee'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="background-color: #f8f9fa; font-weight: bold;">
                        <td>Total Fee (Mitra + PT)</td>
                        <td class="text-right">Rp {{ number_format(($singleTechnician['fee'] ?? 0) + ($singleTechnician['total_pt_fee'] ?? 0), 0, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <th>Total Di Bayar Ke PT ({{ number_format(100 - ($singleTechnician['avg_fee_percentage'] ?? 0), 1) }}% + PPN 11% ):</th>
                        <td class="text-right">Rp {{ number_format(($singleTechnician['total_pt_fee'] ?? 0) + ($singleTechnician['ppn'] ?? 0), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <!-- Blok catatan dihapus agar tampilan lebih simple -->
        
        <div class="footer">
            <img src="https://www.dataartasedaya.net.id/assets/images/logov2.png" alt="Logo Footer" style="height:32px; opacity:0.7; margin-bottom:8px;">
            <p>Tagihan ini dibuat secara otomatis dan sah tanpa tanda tangan.</p>
            <p>Jika ada pertanyaan tentang tagihan ini, silakan hubungi kami di admin@dasnet.my.id atau 0857-3514-6195.</p>
            <p>Terima kasih atas kerjasama Anda!</p>
        </div>
    </div>
</body>
</html>
@else
<div style="text-align: center; padding: 50px; font-family: Arial, sans-serif;">
    <h2>Data Tidak Ditemukan</h2>
    <p>Data teknisi tidak ditemukan atau tidak valid.</p>
    <p>Technician ID: {{ $technicianId ?? 'Tidak ada' }}</p>
    <p>Jumlah data teknisi: {{ count($technicianData ?? []) }}</p>
    <button onclick="window.close()" style="padding: 10px 20px; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">
        Tutup
    </button>
</div>
@endif 