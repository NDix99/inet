@extends('adminlte::page')

@section('title', 'Dashboard Admin')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-1">
                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Admin
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> Selamat datang di panel administrasi INET COMPANY
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#help-modal">
                <i class="fas fa-question-circle"></i> 
                <span class="d-none d-sm-inline">Bantuan</span>
                <span class="d-inline d-sm-none">Help</span>
            </button>
        </div>
    </div>
@stop

@section('content')
    <!-- Info Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-primary">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Pelanggan</span>
                    <span class="info-box-number">{{ $totalCustomers }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-success">
                <span class="info-box-icon"><i class="fas fa-file-invoice"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Invoice</span>
                    <span class="info-box-number">{{ $totalInvoices }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-warning">
                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Belum Lunas</span>
                    <span class="info-box-number">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-danger">
                <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Terlambat</span>
                    <span class="info-box-number">Rp {{ number_format($totalOverdue, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card card-outline card-primary mb-4">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-bolt mr-1"></i>Aksi Cepat
            </h3>
            <div class="card-tools">
                <span class="badge badge-primary mr-2">
                    <i class="fas fa-tools"></i> Tools
                </span>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-primary btn-block btn-lg">
                        <i class="fas fa-users mr-2"></i>
                        <div class="d-flex flex-column">
                            <span class="font-weight-bold">Kelola Pelanggan</span>
                            <small class="opacity-75">Tambah, edit, dan kelola data pelanggan</small>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                    <a href="{{ route('admin.packages.index') }}" class="btn btn-success btn-block btn-lg">
                        <i class="fas fa-box mr-2"></i>
                        <div class="d-flex flex-column">
                            <span class="font-weight-bold">Kelola Paket</span>
                            <small class="opacity-75">Atur paket internet dan metro</small>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-info btn-block btn-lg">
                        <i class="fas fa-file-invoice mr-2"></i>
                        <div class="d-flex flex-column">
                            <span class="font-weight-bold">Kelola Invoice</span>
                            <small class="opacity-75">Buat dan kelola tagihan pelanggan</small>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                    <a href="{{ route('admin.financial.report') }}" class="btn btn-warning btn-block btn-lg">
                        <i class="fas fa-handshake mr-2"></i>
                        <div class="d-flex flex-column">
                            <span class="font-weight-bold">Laporan Mitra</span>
                            <small class="opacity-75">Lihat laporan fee mitra</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i>Statistik Pendapatan Bulanan ({{ date('Y') }})
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-secondary mr-2">
                            <i class="fas fa-chart-line"></i> Analytics
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-plus mr-1"></i>Pelanggan Terbaru
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-info mr-2">
                            <i class="fas fa-users"></i> {{ count($latestCustomers) }}
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @forelse($latestCustomers as $customer)
                            <li class="item">
                                <div class="product-info">
                                    <a href="{{ route('admin.customers.show', $customer) }}" class="product-title">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-placeholder bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mr-2" style="width: 30px; height: 30px; font-size: 0.75rem;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <span>{{ $customer->name }}</span>
                                        </div>
                                        <span class="badge badge-{{ $customer->is_active ? 'success' : 'danger' }} float-right">
                                            <i class="fas fa-{{ $customer->is_active ? 'check-circle' : 'times-circle' }}"></i>
                                            {{ $customer->is_active ? 'Aktif' : 'Non-aktif' }}
                                        </span>
                                    </a>
                                    <span class="product-description">
                                        <i class="fas fa-envelope text-muted mr-1"></i>{{ $customer->email }}
                                        <br>
                                        <i class="fas fa-phone text-muted mr-1"></i>{{ $customer->phone }}
                                    </span>
                                </div>
                            </li>
                        @empty
                            <li class="item">
                                <div class="product-info text-center py-3">
                                    <i class="fas fa-users text-muted" style="font-size: 2rem;"></i>
                                    <p class="text-muted mt-2 mb-0">Belum ada data pelanggan.</p>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye mr-1"></i>Lihat Semua Pelanggan
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-invoice mr-1"></i>Invoice Terbaru
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-warning mr-2">
                            <i class="fas fa-list"></i> {{ count($latestInvoices) }}
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover m-0">
                            <thead>
                                <tr>
                                    <th>No. Invoice</th>
                                    <th>Pelanggan</th>
                                    <th>Tanggal</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestInvoices as $invoice)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.invoices.show', $invoice) }}" class="font-weight-bold text-primary">
                                                <i class="fas fa-file-invoice mr-1"></i>{{ $invoice->invoice_number }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-placeholder bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mr-2" style="width: 25px; height: 25px; font-size: 0.625rem;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <span>{{ $invoice->customer->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="font-weight-bold text-info">
                                                {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="font-weight-bold text-warning">
                                                {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="font-weight-bold text-success">
                                                Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($invoice->status === 'paid')
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle"></i> Lunas
                                                </span>
                                            @elseif ($invoice->status === 'overdue')
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-exclamation-triangle"></i> Terlambat
                                                </span>
                                            @else
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-clock"></i> Belum Lunas
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.invoices.show', $invoice) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.invoices.print', $invoice) }}" class="btn btn-sm btn-primary" target="_blank" title="Cetak Invoice">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-file-invoice text-muted" style="font-size: 2rem;"></i>
                                            <p class="text-muted mt-2 mb-0">Belum ada data invoice.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-eye mr-1"></i>Lihat Semua Invoice
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="help-modal" tabindex="-1" role="dialog" aria-labelledby="help-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="help-modal-label">
                        <i class="fas fa-question-circle mr-2"></i>Panduan Dashboard Admin
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-users text-primary"></i> Kelola Pelanggan</h6>
                            <p class="text-muted">Tambah, edit, dan kelola data pelanggan perusahaan.</p>
                            
                            <h6><i class="fas fa-box text-success"></i> Kelola Paket</h6>
                            <p class="text-muted">Atur paket internet dan metro yang tersedia.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-file-invoice text-info"></i> Kelola Invoice</h6>
                            <p class="text-muted">Buat dan kelola tagihan untuk pelanggan.</p>
                            
                            <h6><i class="fas fa-chart-line text-warning"></i> Laporan Keuangan</h6>
                            <p class="text-muted">Lihat laporan pendapatan dan analisis keuangan.</p>
                        </div>
                    </div>
                    <div class="alert alert-primary mt-3">
                        <i class="fas fa-lightbulb"></i> 
                        <strong>Tips:</strong> Dashboard ini memberikan gambaran lengkap tentang status bisnis perusahaan.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        /* Info Box Improvements */
        .info-box {
            transition: all 0.3s ease;
        }
        
        .info-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        /* Card Improvements */
        .card-outline {
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        
        /* Table Improvements */
        .table-hover tbody tr:hover {
            background-color: rgba(0,123,255,0.1);
        }
        
        /* Button Improvements */
        .btn-lg {
            transition: all 0.3s ease;
            text-align: left;
            padding: 1rem;
        }
        
        .btn-lg:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .btn-sm {
            transition: all 0.2s ease;
        }
        
        .btn-sm:hover {
            transform: translateY(-1px);
        }
        
        /* Modal Improvements */
        .modal-header {
            border-bottom: none;
        }
        
        .modal-footer {
            border-top: none;
        }
        
        /* Avatar Placeholder */
        .avatar-placeholder {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        }
        
        /* Products List Improvements */
        .products-list .item {
            transition: all 0.2s ease;
        }
        
        .products-list .item:hover {
            background-color: rgba(0,123,255,0.05);
        }
        
        /* Gap utility */
        .gap-2 {
            gap: 0.5rem;
        }
        
        /* Responsive improvements */
        .small-box .inner h3 {
            font-size: 1.5rem;
        }
        
        .info-box {
            margin-bottom: 1rem;
        }
        
        .info-box-content {
            padding: 0.5rem;
        }
        
        .info-box-text {
            font-size: 0.875rem;
        }
        
        .info-box-number {
            font-size: 1.25rem;
        }
        
        .progress-description {
            font-size: 0.75rem;
        }
        
        /* Mobile optimizations */
        @media (max-width: 768px) {
            .small-box .inner h3 {
                font-size: 1.25rem;
            }
            
            .info-box-content {
                padding: 0.25rem;
            }
            
            .info-box-text {
                font-size: 0.75rem;
            }
            
            .info-box-number {
                font-size: 1rem;
            }
            
            .progress-description {
                font-size: 0.625rem;
            }
            
            .btn-lg {
                padding: 0.75rem;
            }
            
            .btn-lg .font-weight-bold {
                font-size: 0.875rem;
            }
            
            .btn-lg small {
                font-size: 0.75rem;
            }
        }
        
        /* Table responsive improvements */
        .table-responsive {
            overflow-x: auto;
        }
        
        .table th, .table td {
            white-space: nowrap;
        }
        
        /* Text utilities */
        .text-nowrap {
            white-space: nowrap;
        }
        
        .font-weight-bold {
            font-weight: bold !important;
        }
        
        .mb-0 {
            margin-bottom: 0 !important;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // Data untuk chart
            const chartData = @json($chartData);
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            const values = [];
            
            for (let i = 1; i <= 12; i++) {
                values.push(chartData[i] || 0);
            }
            
            // Buat chart
            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: values,
                        backgroundColor: 'rgba(60, 141, 188, 0.8)',
                        borderColor: 'rgba(60, 141, 188, 1)',
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
                                    return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += 'Rp ' + context.parsed.y.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
            
            // Add hover effects to info boxes
            $('.info-box').hover(
                function() {
                    $(this).addClass('shadow-lg');
                },
                function() {
                    $(this).removeClass('shadow-lg');
                }
            );
            
            // Add loading animation to buttons
            $('.btn').click(function() {
                if (!$(this).hasClass('btn-tool')) {
                    $(this).append('<span class="spinner-border spinner-border-sm ml-1" role="status" aria-hidden="true"></span>');
                    setTimeout(() => {
                        $(this).find('.spinner-border').remove();
                    }, 1000);
                }
            });
        });
    </script>
@stop 