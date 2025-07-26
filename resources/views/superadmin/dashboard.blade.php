@extends('adminlte::page')

@section('title', 'Dashboard Superadmin')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <h1 class="mb-2 mb-md-0">
            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Superadmin
        </h1>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('superadmin.admins.index') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-user-shield"></i> 
                <span class="d-none d-sm-inline">Kelola Admin</span>
                <span class="d-inline d-sm-none">Admin</span>
            </a>
            <a href="{{ route('superadmin.technicians.index') }}" class="btn btn-sm btn-success">
                <i class="fas fa-user-cog"></i> 
                <span class="d-none d-sm-inline">Kelola Teknisi</span>
                <span class="d-inline d-sm-none">Teknisi</span>
            </a>
            <a href="{{ route('superadmin.invoices.index') }}" class="btn btn-sm btn-info">
                <i class="fas fa-file-invoice"></i> 
                <span class="d-none d-sm-inline">Kelola Invoice</span>
                <span class="d-inline d-sm-none">Invoice</span>
            </a>
            <a href="{{ route('superadmin.financial.report') }}" class="btn btn-sm btn-warning">
                <i class="fas fa-chart-line"></i> 
                <span class="d-none d-sm-inline">Laporan Keuangan</span>
                <span class="d-inline d-sm-none">Laporan</span>
            </a>
        </div>
    </div>
@stop

@section('content')
    <!-- Welcome Banner & Quick Tips -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-lightbulb fa-2x text-warning"></i>
                    </div>
                    <div class="flex-grow-1 ml-3">
                        <h5 class="alert-heading mb-1">
                            <i class="fas fa-star text-warning"></i> Selamat Datang di Dashboard Superadmin!
                        </h5>
                        <p class="mb-2">Kelola sistem INET dengan mudah. Berikut adalah ringkasan aktivitas dan statistik penting:</p>
                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-2">
                                <small><i class="fas fa-users text-primary"></i> <strong>{{ $totalCustomers }}</strong> Pelanggan Aktif</small>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <small><i class="fas fa-file-invoice text-success"></i> <strong>{{ $totalInvoices }}</strong> Invoice Dibuat</small>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <small><i class="fas fa-user-shield text-info"></i> <strong>{{ $totalAdmins }}</strong> Admin Terdaftar</small>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-2">
                                <small><i class="fas fa-user-cog text-warning"></i> <strong>{{ $totalTechnicians }}</strong> Teknisi Aktif</small>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Tips -->
    <div class="row mb-4">
        <div class="col-lg-8 col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-rocket mr-1"></i>Quick Actions & Tips
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="quick-action-item p-3 border rounded bg-light">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user-plus text-primary fa-lg mr-2"></i>
                                    <h6 class="mb-0">Tambah Admin Baru</h6>
                                </div>
                                <p class="text-muted small mb-2">Buat akun admin baru untuk mengelola sistem</p>
                                <a href="{{ route('superadmin.admins.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Admin
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="quick-action-item p-3 border rounded bg-light">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user-cog text-success fa-lg mr-2"></i>
                                    <h6 class="mb-0">Kelola Teknisi</h6>
                                </div>
                                <p class="text-muted small mb-2">Tambah atau edit teknisi untuk layanan pelanggan</p>
                                <a href="{{ route('superadmin.technicians.create') }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-plus"></i> Tambah Teknisi
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="quick-action-item p-3 border rounded bg-light">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-boxes text-info fa-lg mr-2"></i>
                                    <h6 class="mb-0">Kelola Paket Internet</h6>
                                </div>
                                <p class="text-muted small mb-2">Buat atau edit paket internet yang tersedia</p>
                                <a href="{{ route('superadmin.packages.create') }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-plus"></i> Tambah Paket
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="quick-action-item p-3 border rounded bg-light">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-chart-line text-warning fa-lg mr-2"></i>
                                    <h6 class="mb-0">Laporan Keuangan</h6>
                                </div>
                                <p class="text-muted small mb-2">Lihat laporan keuangan lengkap dan analisis</p>
                                <a href="{{ route('superadmin.financial.report') }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-chart-bar"></i> Lihat Laporan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-12">
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-question-circle mr-1"></i>Panduan Cepat
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle text-info mr-2"></i>
                                <div>
                                    <h6 class="mb-1">Info Box</h6>
                                    <small class="text-muted">Klik untuk melihat detail lengkap</small>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-chart-bar text-success mr-2"></i>
                                <div>
                                    <h6 class="mb-1">Grafik Interaktif</h6>
                                    <small class="text-muted">Hover untuk melihat detail nilai</small>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-table text-warning mr-2"></i>
                                <div>
                                    <h6 class="mb-1">Tabel Responsif</h6>
                                    <small class="text-muted">Scroll horizontal di mobile</small>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-print text-danger mr-2"></i>
                                <div>
                                    <h6 class="mb-1">Cetak Laporan</h6>
                                    <small class="text-muted">Klik tombol cetak untuk print</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info boxes -->
    <div class="row">
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-3">
            <div class="small-box bg-gradient-primary position-relative">
                <div class="inner">
                    <h3>{{ $totalAdmins }}</h3>
                    <p>Admin</p>
                    <div class="small text-white-50">
                        <i class="fas fa-user-shield"></i> Pengelola sistem
                    </div>
                </div>
                <div class="icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <a href="{{ route('superadmin.admins.index') }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
                <div class="tooltip-info">
                    <i class="fas fa-info-circle"></i>
                    <span class="tooltip-text">Admin bertanggung jawab mengelola teknisi, paket, dan laporan keuangan</span>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-3">
            <div class="small-box bg-gradient-success position-relative">
                <div class="inner">
                    <h3>{{ $totalTechnicians }}</h3>
                    <p>Teknisi</p>
                    <div class="small text-white-50">
                        <i class="fas fa-user-cog"></i> Layanan pelanggan
                    </div>
                </div>
                <div class="icon">
                    <i class="fas fa-user-cog"></i>
                </div>
                <a href="{{ route('superadmin.technicians.index') }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
                <div class="tooltip-info">
                    <i class="fas fa-info-circle"></i>
                    <span class="tooltip-text">Teknisi menangani input data pelanggan dan layanan teknis</span>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-3">
            <div class="small-box bg-gradient-info position-relative">
                <div class="inner">
                    <h3>{{ $totalCustomers }}</h3>
                    <p>Pelanggan</p>
                    <div class="small text-white-50">
                        <i class="fas fa-users"></i> Pengguna layanan
                    </div>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('superadmin.customers.index') }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
                <div class="tooltip-info">
                    <i class="fas fa-info-circle"></i>
                    <span class="tooltip-text">Pelanggan yang menggunakan layanan internet/metro</span>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-3">
            <div class="small-box bg-gradient-warning position-relative">
                <div class="inner">
                    <h3>{{ $totalInvoices }}</h3>
                    <p>Invoice</p>
                    <div class="small text-white-50">
                        <i class="fas fa-file-invoice"></i> Tagihan dibuat
                    </div>
                </div>
                <div class="icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <a href="{{ route('superadmin.invoices.index') }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
                <div class="tooltip-info">
                    <i class="fas fa-info-circle"></i>
                    <span class="tooltip-text">Total invoice yang telah dibuat untuk pelanggan</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Grafik Pendapatan Bulanan -->
        <div class="col-lg-8 col-md-12 mb-4">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Pendapatan Bulanan {{ now()->year }}
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-info mr-2">
                            <i class="fas fa-mouse-pointer"></i> Hover untuk detail
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> 
                            Grafik menunjukkan tren pendapatan bulanan. Hover pada bar untuk melihat detail nilai.
                        </small>
                    </div>
                </div>
            </div>
            
            <!-- Pelanggan Terbaru -->
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-1"></i>
                        Pelanggan Terbaru
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-info mr-2">
                            <i class="fas fa-mobile-alt"></i> Responsif
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="d-none d-md-table-cell">Nama</th>
                                    <th class="d-none d-lg-table-cell">Paket</th>
                                    <th class="d-none d-xl-table-cell">Dibuat Oleh</th>
                                    <th class="d-none d-md-table-cell">Tanggal Daftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestCustomers as $customer)
                                    <tr>
                                        <td class="d-none d-md-table-cell">{{ $customer->name }}</td>
                                        <td class="d-none d-lg-table-cell">{{ $customer->package->name ?? 'Tidak ada paket' }}</td>
                                        <td class="d-none d-xl-table-cell">{{ $customer->creator->name ?? 'Tidak diketahui' }}</td>
                                        <td class="d-none d-md-table-cell">{{ $customer->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="d-flex flex-column d-md-block">
                                                <div class="d-md-none mb-1">
                                                    <strong>{{ $customer->name }}</strong><br>
                                                    <small class="text-muted">{{ $customer->package->name ?? 'Tidak ada paket' }}</small>
                                                </div>
                                                <a href="{{ route('superadmin.customers.show', $customer) }}" class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye"></i> 
                                                    <span class="d-none d-sm-inline">Detail</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada pelanggan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('superadmin.customers.index') }}" class="text-primary">
                        Lihat Semua Pelanggan <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-12">
            <!-- Status Invoice -->
            <div class="card card-success card-outline mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-invoice mr-1"></i>
                        Status Invoice
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-success mr-2">
                            <i class="fas fa-chart-pie"></i> Real-time
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="d-flex flex-column flex-sm-row">
                        <div class="info-box bg-success flex-fill m-0 border-0">
                            <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Lunas</span>
                                <span class="info-box-number">{{ $totalPaidInvoices }}</span>
                            </div>
                        </div>
                        <div class="info-box bg-warning flex-fill m-0 border-0">
                            <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Belum Lunas</span>
                                <span class="info-box-number">{{ $totalUnpaidInvoices }}</span>
                            </div>
                        </div>
                        <div class="info-box bg-danger flex-fill m-0 border-0">
                            <span class="info-box-icon"><i class="fas fa-exclamation-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Terlambat</span>
                                <span class="info-box-number">{{ $totalOverdueInvoices }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Status invoice berdasarkan pembayaran pelanggan
                    </small>
                </div>
            </div>
            
            <!-- Ringkasan Keuangan -->
            <div class="card card-warning card-outline mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-money-bill-wave mr-1"></i>
                        Ringkasan Keuangan
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-warning mr-2">
                            <i class="fas fa-calculator"></i> Auto-calc
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <tr>
                                <th style="width: 60%">Total Pendapatan (Lunas)</th>
                                <td class="text-right text-nowrap">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Total Belum Lunas</th>
                                <td class="text-right text-nowrap">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Total Terlambat</th>
                                <td class="text-right text-nowrap">Rp {{ number_format($totalOverdue, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Total PPN</th>
                                <td class="text-right text-nowrap">Rp {{ number_format($totalTax, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Total Fee Teknisi</th>
                                <td class="text-right text-nowrap">Rp {{ number_format($totalTechnicianFee, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="bg-light">
                                <th>Total Pendapatan Global</th>
                                <td class="text-right font-weight-bold text-nowrap">Rp {{ number_format($totalRevenue + $totalUnpaid + $totalOverdue, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('superadmin.financial.report') }}" class="text-warning">
                        Lihat Laporan Lengkap <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <!-- Invoice Terbaru -->
            <div class="card card-danger card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-invoice-dollar mr-1"></i>
                        Invoice Terbaru
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-danger mr-2">
                            <i class="fas fa-clock"></i> Latest
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($latestInvoices as $invoice)
                            <li class="list-group-item">
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
                                    <div class="mb-2 mb-sm-0">
                                        <span class="badge badge-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'overdue' ? 'danger' : 'warning') }} mr-2">
                                            {{ $invoice->status == 'paid' ? 'Lunas' : ($invoice->status == 'overdue' ? 'Terlambat' : 'Belum Lunas') }}
                                        </span>
                                        <strong class="d-block d-sm-inline">{{ $invoice->invoice_number }}</strong>
                                        <div class="text-muted small">{{ $invoice->customer->name }}</div>
                                    </div>
                                    <div class="text-right text-sm-left">
                                        <div class="font-weight-bold">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</div>
                                        <small class="text-muted">{{ $invoice->invoice_date->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center">Belum ada invoice</li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('superadmin.invoices.index') }}" class="text-danger">
                        Lihat Semua Invoice <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href='{{ asset("vendor/adminlte/dist/css/adminlte.min.css") }}'>
    <style>
        /* Responsive improvements */
        .small-box .icon {
            color: rgba(0, 0, 0, 0.15);
            z-index: 0;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(to right, #007bff, #0056b3) !important;
        }
        .bg-gradient-success {
            background: linear-gradient(to right, #28a745, #1e7e34) !important;
        }
        .bg-gradient-info {
            background: linear-gradient(to right, #17a2b8, #117a8b) !important;
        }
        .bg-gradient-warning {
            background: linear-gradient(to right, #ffc107, #d39e00) !important;
        }
        
        .card-outline {
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        
        /* Quick Action Items */
        .quick-action-item {
            transition: all 0.3s ease;
            border: 1px solid #dee2e6 !important;
        }
        
        .quick-action-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-color: #007bff !important;
        }
        
        /* Tooltip Styles */
        .tooltip-info {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }
        
        .tooltip-info i {
            color: rgba(255,255,255,0.8);
            cursor: help;
            font-size: 14px;
        }
        
        .tooltip-info .tooltip-text {
            visibility: hidden;
            width: 200px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            right: 0;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 12px;
            line-height: 1.4;
        }
        
        .tooltip-info:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
        
        /* Alert Improvements */
        .alert-info {
            background: linear-gradient(135deg,rgb(48, 95, 197) 0%,rgb(39, 173, 194) 100%);
            border: 1px solidrgb(59, 61, 61);
        }
        
        .alert-info .fas {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        /* Badge Improvements */
        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        /* List Group Improvements */
        .list-group-item {
            border-left: none;
            border-right: none;
            transition: background-color 0.2s ease;
        }
        
        .list-group-item:hover {
            background-color: #f8f9fa;
        }
        
        /* Responsive table improvements */
        .table-responsive {
            overflow-x: auto;
        }
        
        .table th, .table td {
            white-space: nowrap;
        }
        
        /* Mobile optimizations */
        @media (max-width: 768px) {
            .small-box {
                margin-bottom: 1rem;
            }
            
            .info-box {
                margin-bottom: 0.5rem;
            }
            
            .card-title {
                font-size: 1rem;
            }
            
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
            
            .table th, .table td {
                padding: 0.5rem;
                font-size: 0.875rem;
            }
            
            .tooltip-info {
                display: none;
            }
        }
        
        /* Gap utility for flexbox */
        .gap-2 {
            gap: 0.5rem;
        }
        
        /* Text utilities */
        .text-nowrap {
            white-space: nowrap;
        }
        
        .text-sm-left {
            text-align: left;
        }
        
        @media (min-width: 576px) {
            .text-sm-left {
                text-align: right;
            }
        }
        
        /* Position utilities */
        .position-relative {
            position: relative !important;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Grafik Pendapatan Bulanan
            const ctx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($chartData['labels']),
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: @json($chartData['data']),
                        backgroundColor: 'rgba(0, 123, 255, 0.5)',
                        borderColor: 'rgba(0, 123, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Pendapatan: Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                }
                            }
                        }
                    }
                }
            });
            
            // Animasi pada card saat hover (hanya di desktop)
            if (window.innerWidth > 768) {
                document.querySelectorAll('.card').forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-5px)';
                        this.style.transition = 'transform 0.3s ease';
                    });
                    
                    card.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0)';
                    });
                });
            }
            
            // Auto-hide welcome alert after 10 seconds
            setTimeout(function() {
                const alert = document.querySelector('.alert-info');
                if (alert) {
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 500);
                }
            }, 10000);
            
            // Add click to dismiss functionality for welcome alert
            document.querySelector('.alert-info .close').addEventListener('click', function() {
                const alert = this.closest('.alert');
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 500);
            });
        });
    </script>
@stop 