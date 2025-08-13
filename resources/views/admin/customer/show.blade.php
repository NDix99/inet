@extends('adminlte::page')

@section('title', 'Detail Pelanggan')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-1">
                <i class="fas fa-user mr-2"></i>Detail Pelanggan
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> Informasi lengkap pelanggan dan riwayat invoice
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> 
                <span class="d-none d-sm-inline">Edit</span>
                <span class="d-inline d-sm-none">Edit</span>
            </a>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> 
                <span class="d-none d-sm-inline">Kembali</span>
                <span class="d-inline d-sm-none">Back</span>
            </a>
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
                <span class="info-box-icon"><i class="fas fa-file-invoice"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Invoice</span>
                    <span class="info-box-number">{{ $invoices->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-success">
                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Invoice Lunas</span>
                    <span class="info-box-number">{{ $invoices->where('status', 'paid')->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-warning">
                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Belum Lunas</span>
                    <span class="info-box-number">{{ $invoices->where('status', 'unpaid')->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-danger">
                <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Terlambat</span>
                    <span class="info-box-number">{{ $invoices->where('status', 'overdue')->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user mr-1"></i>Informasi Pelanggan
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-info mr-2">
                            <i class="fas fa-id-card"></i> Data
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12 text-center mb-3">
                            <div class="avatar-placeholder bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                                <i class="fas fa-user"></i>
                            </div>
                            <h4 class="mt-2 mb-1">{{ $customer->name }}</h4>
                            <span class="badge badge-{{ $customer->is_active ? 'success' : 'danger' }} badge-lg">
                                <i class="fas fa-{{ $customer->is_active ? 'check-circle' : 'times-circle' }}"></i>
                                {{ $customer->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                <i class="fas fa-hashtag"></i>
                            </div>
                            <div>
                                <small class="text-muted">ID Pelanggan</small>
                                <div class="font-weight-bold">{{ $customer->id }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <small class="text-muted">Email</small>
                                <div class="font-weight-bold">{{ $customer->email }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <small class="text-muted">Telepon</small>
                                <div class="font-weight-bold">{{ $customer->phone }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper bg-info text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <small class="text-muted">Alamat</small>
                                <div class="font-weight-bold">{{ $customer->address }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <small class="text-muted">Tanggal Tagihan</small>
                                <div class="font-weight-bold">{{ \Carbon\Carbon::parse($customer->billing_date)->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    @if($customer->latitude && $customer->longitude)
                        <div class="info-item mb-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper bg-danger text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Koordinat</small>
                                    <div class="font-weight-bold">{{ $customer->latitude }}, {{ $customer->longitude }}</div>
                                    <a href="https://www.google.com/maps?q={{ $customer->latitude }},{{ $customer->longitude }}" target="_blank" class="btn btn-sm btn-info mt-1">
                                        <i class="fas fa-external-link-alt"></i> Lihat di Google Maps
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper bg-dark text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <div>
                                <small class="text-muted">Tanggal Registrasi</small>
                                <div class="font-weight-bold">{{ $customer->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-box mr-1"></i>Informasi Paket
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-success mr-2">
                            <i class="fas fa-package"></i> Paket
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if ($customer->package)
                        <div class="text-center mb-4">
                            <div class="package-icon bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                <i class="fas fa-box"></i>
                            </div>
                            <h4 class="mb-1">{{ $customer->package->name }}</h4>
                            <span class="badge badge-primary badge-lg">{{ $customer->package->type }}</span>
                        </div>
                        
                        <div class="package-details">
                            <div class="detail-item mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">
                                        <i class="fas fa-tag mr-2"></i>Harga Paket
                                    </span>
                                    <span class="font-weight-bold text-success">
                                        Rp {{ number_format($customer->package->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="detail-item mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">
                                        <i class="fas fa-info-circle mr-2"></i>Deskripsi
                                    </span>
                                    <span class="font-weight-bold">
                                        {{ $customer->package->description ?? 'Tidak ada deskripsi' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-muted">Tidak ada paket yang dipilih</h5>
                            <p class="text-muted">Pelanggan belum memilih paket internet</p>
                            <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Pilih Paket
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-file-invoice mr-1"></i>Riwayat Invoice
            </h3>
            <div class="card-tools">
                <span class="badge badge-warning mr-2">
                    <i class="fas fa-history"></i> {{ $invoices->count() }} Invoice
                </span>
                
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="invoices-table" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Tanggal</th>
                            <th>Jatuh Tempo</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($invoices as $invoice)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.invoices.show', $invoice) }}" class="font-weight-bold text-primary">
                                        <i class="fas fa-file-invoice mr-1"></i>INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}
                                    </a>
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
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-file-invoice text-muted" style="font-size: 2rem;"></i>
                                    <p class="text-muted mt-2 mb-0">Belum ada invoice untuk pelanggan ini.</p>
                                    <a href="{{ route('admin.invoices.create') }}?customer_id={{ $customer->id }}" class="btn btn-primary btn-sm mt-2">
                                        <i class="fas fa-plus"></i> Buat Invoice Pertama
                                    </a>
                                </td>
                            </tr>
                        @endforelse
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
                        <i class="fas fa-question-circle mr-2"></i>Panduan Detail Pelanggan
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-user text-info"></i> Informasi Pelanggan</h6>
                            <p class="text-muted">Lihat data lengkap pelanggan termasuk kontak dan alamat.</p>
                            
                            <h6><i class="fas fa-box text-success"></i> Informasi Paket</h6>
                            <p class="text-muted">Lihat detail paket internet yang dipilih pelanggan.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-file-invoice text-warning"></i> Riwayat Invoice</h6>
                            <p class="text-muted">Lihat semua invoice yang telah dibuat untuk pelanggan ini.</p>
                            
                            <h6><i class="fas fa-edit text-warning"></i> Edit Pelanggan</h6>
                            <p class="text-muted">Ubah informasi pelanggan atau pilih paket yang berbeda.</p>
                        </div>
                    </div>
                    <div class="alert alert-primary mt-3">
                        <i class="fas fa-lightbulb"></i> 
                        <strong>Tips:</strong> Pastikan data pelanggan selalu akurat dan up-to-date.
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
        
        /* Avatar Placeholder */
        .avatar-placeholder {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        }
        
        /* Icon Wrapper */
        .icon-wrapper {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }
        
        /* Package Icon */
        .package-icon {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
        
        /* Detail Item */
        .detail-item {
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }
        
        .detail-item:hover {
            background-color: rgba(40,167,69,0.05);
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
            $('#invoices-table').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: '{{ asset("vendor/datatables/lang/Indonesian.json") }}'
                },
                order: [[1, "desc"]], // Urutkan berdasarkan tanggal invoice (descending)
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