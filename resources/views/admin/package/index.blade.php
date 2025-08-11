@extends('adminlte::page')

@section('title', 'Pengaturan Fee Teknisi')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-1">
                <i class="fas fa-percentage text-warning mr-2"></i>Pengaturan Fee Teknisi
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> Atur fee teknisi untuk setiap paket internet
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
                <span class="info-box-icon"><i class="fas fa-box"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Paket</span>
                    <span class="info-box-number">{{ $packages->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-success">
                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Paket Aktif</span>
                    <span class="info-box-number">{{ $packages->where('is_active', true)->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-warning">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Pelanggan</span>
                    <span class="info-box-number">{{ $packages->sum(function($package) { return $package->customers()->count(); }) }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-info">
                <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Rata-rata Harga</span>
                    <span class="info-box-number">Rp {{ number_format($packages->avg('price'), 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Alerts -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Package Table -->
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table mr-1"></i>Data Paket Internet
            </h3>
            <div class="card-tools">
                <span class="badge badge-primary mr-2">
                    <i class="fas fa-money-bill"></i> Hanya Edit Harga
                </span>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    <div>
                        <strong>Informasi:</strong> Sebagai Admin, Anda hanya dapat mengatur persentase fee teknisi. Untuk menambah, mengubah harga, atau menghapus paket, silakan hubungi Superadmin.
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="table-responsive">
                <table id="packages-table" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Paket</th>
                            <th>Tipe</th>
                            <th>Harga (Termasuk PPN 11%)</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($packages as $package)
                            <tr>
                                <td>
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-hashtag"></i> {{ $package->id }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="package-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mr-2" style="width: 35px; height: 35px; font-size: 0.875rem;">
                                            <i class="fas fa-box"></i>
                                        </div>
                                        <div>
                                            <span class="font-weight-bold">{{ $package->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ ucfirst($package->type) }}</td>
                                <td class="text-nowrap">
                                    <div class="font-weight-bold text-success">
                                        Rp {{ number_format($package->price, 0, ',', '.') }}
                                    </div>
                                    <div class="text-muted small">
                                        <div>Harga Dasar: Rp {{ number_format($package->base_price, 0, ',', '.') }}</div>
                                        <div>PPN 11%: Rp {{ number_format($package->tax_amount, 0, ',', '.') }}</div>
                                    </div>
                                </td>
                                <td class="text-nowrap">
                                    @if($package->technician_fee_percentage)
                                        <div class="font-weight-bold text-warning">
                                            {{ $package->technician_fee_percentage }}%
                                        </div>
                                        <div class="text-muted small">
                                            Rp {{ number_format($package->technician_fee_amount, 0, ',', '.') }}
                                        </div>
                                    @else
                                        <span class="text-muted">Belum diatur</span>
                                    @endif
                                </td>
                                <td>
                                    @if($package->is_active)
                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Aktif</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#detail-modal-{{ $package->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                        
                                        <!-- Modal Detail -->
                                        <div class="modal fade" id="detail-modal-{{ $package->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel{{ $package->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info">
                                                        <h5 class="modal-title text-white" id="detailModalLabel{{ $package->id }}">
                                                            <i class="fas fa-box mr-2"></i>Detail Paket
                                                        </h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="info-item mb-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="icon-wrapper bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                                                            <i class="fas fa-box"></i>
                                                                        </div>
                                                                        <div>
                                                                            <small class="text-muted">Nama Paket</small>
                                                                            <div class="font-weight-bold">{{ $package->name }}</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="info-item mb-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="icon-wrapper bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                                                            <i class="fas fa-tag"></i>
                                                                        </div>
                                                                        <div>
                                                                            <small class="text-muted">Tipe Paket</small>
                                                                            <div class="font-weight-bold">{{ ucfirst($package->type) }}</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="info-item mb-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="icon-wrapper bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                                                            <i class="fas fa-money-bill-wave"></i>
                                                                        </div>
                                                                        <div>
                                                                            <small class="text-muted">Harga (Termasuk PPN 11%)</small>
                                                                            <div class="font-weight-bold text-success">Rp {{ number_format($package->price, 0, ',', '.') }}</div>
                                                                            <div class="small text-muted mt-1">
                                                                                <div>Harga Dasar: Rp {{ number_format($package->base_price, 0, ',', '.') }}</div>
                                                                                <div>PPN 11%: Rp {{ number_format($package->tax_amount, 0, ',', '.') }}</div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="info-item mb-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="icon-wrapper bg-info text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                                                            <i class="fas fa-toggle-on"></i>
                                                                        </div>
                                                                        <div>
                                                                            <small class="text-muted">Status</small>
                                                                            <div>
                                                                                @if($package->is_active)
                                                                                    <span class="badge badge-success">
                                                                                        <i class="fas fa-check-circle"></i> Aktif
                                                                                    </span>
                                                                                @else
                                                                                    <span class="badge badge-danger">
                                                                                        <i class="fas fa-times-circle"></i> Tidak Aktif
                                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="info-item mb-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="icon-wrapper bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                                                            <i class="fas fa-users"></i>
                                                                        </div>
                                                                        <div>
                                                                            <small class="text-muted">Jumlah Pelanggan</small>
                                                                            <div class="font-weight-bold">{{ $package->customers()->count() }} pelanggan</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="info-item mb-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="icon-wrapper bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                                                            <i class="fas fa-percentage"></i>
                                                                        </div>
                                                                        <div>
                                                                            <small class="text-muted"></small>
                                                                            <div class="font-weight-bold">
                                                                                @if($package->technician_fee_percentage)
                                                                                    {{ $package->technician_fee_percentage }}%
                                                                                    <div class="small text-muted">
                                                                                        Rp {{ number_format($package->technician_fee_amount, 0, ',', '.') }}
                                                                                    </div>
                                                                                @else
                                                                                    
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        @if($package->description)
                                                            <div class="mt-3">
                                                                <h6><i class="fas fa-align-left text-muted mr-2"></i>Deskripsi</h6>
                                                                <div class="alert alert-light">
                                                                    {{ $package->description }}
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                            <i class="fas fa-times"></i> Tutup
                                                        </button>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="help-modal" tabindex="-1" role="dialog" aria-labelledby="help-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="help-modal-label">
                        <i class="fas fa-question-circle mr-2"></i>Panduan Kelola Paket
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-eye text-info"></i> Lihat Detail</h6>
                            <p class="text-muted">Klik ikon mata untuk melihat informasi lengkap paket.</p>
                            
                            <h6><i class="fas fa-money-bill text-warning"></i> Edit Harga</h6>
                            <p class="text-muted">Klik ikon uang untuk mengubah harga paket.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-info-circle text-primary"></i> Batasan Admin</h6>
                            <p class="text-muted">Admin hanya dapat mengubah harga paket, tidak dapat menambah atau menghapus paket.</p>
                            
                            <h6><i class="fas fa-users text-success"></i> Statistik</h6>
                            <p class="text-muted">Lihat jumlah pelanggan yang menggunakan setiap paket.</p>
                        </div>
                    </div>
                    <div class="alert alert-primary mt-3">
                        <i class="fas fa-lightbulb"></i> 
                        <strong>Tips:</strong> Pastikan harga yang diubah sesuai dengan kebijakan perusahaan.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

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
        
        /* Package Icon */
        .package-icon {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }
        
        /* Icon Wrapper */
        .icon-wrapper {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }
        
        /* Info Item */
        .info-item {
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }
        
        .info-item:hover {
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
    <script>
        $(document).ready(function() {
            $('#packages-table').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: '{{ asset("vendor/datatables/lang/Indonesian.json") }}'
                },
                order: [[1, "asc"]], // Urutkan berdasarkan nama paket (ascending)
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="fas fa-copy"></i> Salin',
                        className: 'btn btn-sm btn-secondary'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-sm btn-success'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-sm btn-danger'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-sm btn-info'
                    }
                ]
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
            
            // Auto-dismiss alerts
            $('.alert').delay(5000).fadeOut(500);
            
            // Responsive button text
            function updateButtonText() {
                if (window.innerWidth < 576) {
                    $('.dt-buttons .btn').each(function() {
                        var $btn = $(this);
                        var text = $btn.text();
                        if (text.includes('Salin')) {
                            $btn.html('<i class="fas fa-copy"></i>');
                        } else if (text.includes('Excel')) {
                            $btn.html('<i class="fas fa-file-excel"></i>');
                        } else if (text.includes('PDF')) {
                            $btn.html('<i class="fas fa-file-pdf"></i>');
                        } else if (text.includes('Print')) {
                            $btn.html('<i class="fas fa-print"></i>');
                        }
                    });
                }
            }
            
            // Update button text on load and resize
            updateButtonText();
            $(window).resize(updateButtonText);
        });
    </script>
@stop 