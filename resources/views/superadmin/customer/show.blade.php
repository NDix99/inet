@extends('adminlte::page')

@section('title', 'Detail Pelanggan')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-1">
                <i class="fas fa-user mr-2"></i>Detail Pelanggan
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> Informasi lengkap pelanggan {{ $customer->name }}
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('superadmin.customers.index') }}" class="btn btn-secondary">
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
            <div class="info-box bg-gradient-info">
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
        <div class="col-lg-8 col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user mr-1"></i>Informasi Pelanggan
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-info mr-2">
                                    <i class="fas fa-user"></i> Data Pribadi
                                </span>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="avatar-placeholder bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h5 class="mt-2 mb-1">{{ $customer->name }}</h5>
                                <p class="text-muted mb-0">
                                    @if ($customer->is_active)
                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Aktif</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Tidak Aktif</span>
                                    @endif
                                </p>
                            </div>
                            
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">ID Pelanggan:</label>
                                <div class="text-primary font-weight-bold">{{ $customer->id }}</div>
                            </div>
                            
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Email:</label>
                                <div>{{ $customer->email }}</div>
                            </div>
                            
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Telepon:</label>
                                <div>{{ $customer->phone }}</div>
                            </div>
                            
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Alamat:</label>
                                <div>{{ $customer->address }}</div>
                            </div>
                            
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Tanggal Tagihan:</label>
                                <div class="text-info font-weight-bold">
                                    {{ \Carbon\Carbon::parse($customer->billing_date)->format('d/m/Y') }}
                                </div>
                            </div>
                            
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Koordinat:</label>
                                <div>
                                    @if($customer->latitude && $customer->longitude)
                                        <span class="text-muted">{{ $customer->latitude }}, {{ $customer->longitude }}</span>
                                        <a href="https://www.google.com/maps?q={{ $customer->latitude }},{{ $customer->longitude }}" target="_blank" class="btn btn-sm btn-info ml-2">
                                            <i class="fas fa-map-marker-alt"></i> Maps
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Terdaftar:</label>
                                <div>{{ $customer->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                            
                            <div class="info-item mb-3">
                                <label class="font-weight-bold text-muted">Dibuat Oleh:</label>
                                <div>
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-user-cog"></i> {{ $customer->creator->name ?? 'Tidak diketahui' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-box mr-1"></i>Informasi Paket
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-primary mr-2">
                                    <i class="fas fa-box"></i> Layanan
                                </span>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($customer->package)
                                <div class="text-center mb-3">
                                    <div class="avatar-placeholder bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <h5 class="mt-2 mb-1">{{ $customer->package->name }}</h5>
                                    <p class="text-muted mb-0">
                                        @if($customer->package->type == 'internet')
                                            <span class="badge badge-warning"><i class="fas fa-wifi"></i> Internet</span>
                                        @else
                                            <span class="badge badge-info"><i class="fas fa-network-wired"></i> Metro</span>
                                        @endif
                                    </p>
                                </div>
                                
                                <div class="info-item mb-3">
                                    <label class="font-weight-bold text-muted">Nama Paket:</label>
                                    <div class="text-primary font-weight-bold">{{ $customer->package->name }}</div>
                                </div>
                                
                                <div class="info-item mb-3">
                                    <label class="font-weight-bold text-muted">Tipe:</label>
                                    <div>
                                        @if($customer->package->type == 'internet')
                                            <span class="badge badge-warning"><i class="fas fa-wifi"></i> Internet</span>
                                        @else
                                            <span class="badge badge-info"><i class="fas fa-network-wired"></i> Metro</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="info-item mb-3">
                                    <label class="font-weight-bold text-muted">Harga:</label>
                                    <div class="text-success font-weight-bold">
                                        Rp {{ number_format($customer->package->price, 0, ',', '.') }}
                                    </div>
                                </div>
                                
                                <div class="info-item mb-3">
                                    <label class="font-weight-bold text-muted">Deskripsi:</label>
                                    <div>
                                        @if($customer->package->description)
                                            <div class="alert alert-light">
                                                {{ $customer->package->description }}
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="info-item mb-3">
                                    <label class="font-weight-bold text-muted">Status Paket:</label>
                                    <div>
                                        @if ($customer->package->is_active)
                                            <span class="badge badge-success"><i class="fas fa-check-circle"></i> Aktif</span>
                                        @else
                                            <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Tidak Aktif</span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="text-center">
                                    <div class="avatar-placeholder bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                                        <i class="fas fa-times"></i>
                                    </div>
                                    <h5 class="mt-2 mb-1">Tidak Ada Paket</h5>
                                    <p class="text-muted">Pelanggan belum memilih paket layanan.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-12">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>Statistik Invoice
                    </h3>
                </div>
                <div class="card-body">
                    <div class="info-box bg-gradient-success mb-3">
                        <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Lunas</span>
                            <span class="info-box-number">{{ $invoices->where('status', 'paid')->count() }}</span>
                        </div>
                    </div>
                    
                    <div class="info-box bg-gradient-warning mb-3">
                        <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Belum Lunas</span>
                            <span class="info-box-number">{{ $invoices->where('status', 'unpaid')->count() }}</span>
                        </div>
                    </div>
                    
                    <div class="info-box bg-gradient-danger mb-3">
                        <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Terlambat</span>
                            <span class="info-box-number">{{ $invoices->where('status', 'overdue')->count() }}</span>
                        </div>
                    </div>
                    
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Tagihan</span>
                            <span class="info-box-number">Rp {{ number_format($invoices->sum('total_amount'), 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card card-outline card-warning mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lightbulb mr-1"></i>Tips & Panduan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-eye text-info"></i> Melihat Detail</h6>
                        <p class="mb-0">Halaman ini menampilkan informasi lengkap pelanggan dan riwayat invoice.</p>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-map-marker-alt text-warning"></i> Lokasi</h6>
                        <p class="mb-0">Klik tombol "Maps" untuk melihat lokasi pelanggan di Google Maps.</p>
                    </div>
                    
                    <div class="alert alert-success">
                        <h6><i class="fas fa-file-invoice text-success"></i> Invoice</h6>
                        <p class="mb-0">Lihat riwayat invoice pelanggan di tabel di bawah ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card card-outline card-secondary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-file-invoice mr-1"></i>Riwayat Invoice
            </h3>
            <div class="card-tools">
                <span class="badge badge-secondary mr-2">
                    <i class="fas fa-history"></i> Riwayat Pembayaran
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
                                    <span class="font-weight-bold text-primary">{{ $invoice->invoice_number }}</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="font-weight-bold text-info">
                                        {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-weight-bold text-success">
                                        Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    @if($invoice->status === 'paid')
                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Lunas</span>
                                    @elseif($invoice->status === 'overdue')
                                        <span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i> Terlambat</span>
                                    @else
                                        <span class="badge badge-warning"><i class="fas fa-clock"></i> Belum Lunas</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column flex-sm-row gap-1">
                                        <a href="{{ route('superadmin.invoices.show', $invoice) }}" class="btn btn-sm btn-info" title="Detail Invoice">
                                            <i class="fas fa-eye"></i> 
                                            <span class="d-none d-sm-inline">Detail</span>
                                        </a>
                                        <a href="{{ route('superadmin.invoices.print', $invoice) }}" class="btn btn-sm btn-primary" target="_blank" title="Print Invoice">
                                            <i class="fas fa-print"></i> 
                                            <span class="d-none d-sm-inline">Print</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <br>Belum ada invoice untuk pelanggan ini.
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
                <div class="modal-header bg-info">
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
                            <p class="text-muted">Lihat data lengkap pelanggan termasuk kontak, alamat, dan status keaktifan.</p>
                            
                            <h6><i class="fas fa-box text-primary"></i> Informasi Paket</h6>
                            <p class="text-muted">Lihat detail paket yang digunakan pelanggan beserta harga dan deskripsi.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-map-marker-alt text-warning"></i> Lokasi</h6>
                            <p class="text-muted">Klik tombol "Maps" untuk melihat lokasi pelanggan di Google Maps.</p>
                            
                            <h6><i class="fas fa-file-invoice text-success"></i> Riwayat Invoice</h6>
                            <p class="text-muted">Lihat semua invoice pelanggan dan status pembayarannya.</p>
                        </div>
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
            background-color: rgba(108,117,125,0.1);
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
        
        /* Info Item */
        .info-item label {
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }
        
        .info-item div {
            font-size: 1rem;
        }
        
        /* Avatar Placeholder */
        .avatar-placeholder {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        }
        
        .avatar-placeholder.bg-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }
        
        .avatar-placeholder.bg-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
        }
        
        /* Gap utility */
        .gap-1 {
            gap: 0.25rem;
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
            
            .avatar-placeholder {
                width: 60px !important;
                height: 60px !important;
                font-size: 1.5rem !important;
            }
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('#invoices-table').DataTable({
                language: {
                    url: "{{ asset('vendor/datatables/lang/Indonesian.json') }}"
                },
                responsive: true,
                autoWidth: false,
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
        });
    </script>
@stop 