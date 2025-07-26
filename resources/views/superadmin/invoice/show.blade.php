@extends('adminlte::page')

@section('title', 'Detail Invoice')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-1">
                <i class="fas fa-file-invoice mr-2"></i>Detail Invoice
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> Informasi lengkap invoice {{ $invoice->invoice_number }}
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('superadmin.invoices.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> 
                <span class="d-none d-sm-inline">Kembali</span>
                <span class="d-inline d-sm-none">Back</span>
            </a>
            <a href="{{ route('superadmin.invoices.print', $invoice->id) }}" class="btn btn-primary" target="_blank">
                <i class="fas fa-print"></i> 
                <span class="d-none d-sm-inline">Cetak</span>
                <span class="d-inline d-sm-none">Print</span>
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
            <div class="info-box bg-gradient-secondary">
                <span class="info-box-icon"><i class="fas fa-file-invoice"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">No. Invoice</span>
                    <span class="info-box-number">{{ $invoice->invoice_number }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-success">
                <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Tagihan</span>
                    <span class="info-box-number">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-warning">
                <span class="info-box-icon"><i class="fas fa-calendar-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Jatuh Tempo</span>
                    <span class="info-box-number">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-info">
                <span class="info-box-icon"><i class="fas fa-user"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pelanggan</span>
                    <span class="info-box-number">{{ $invoice->customer->name }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-invoice mr-1"></i>Invoice #{{ $invoice->invoice_number }}
                        @if ($invoice->status == 'paid')
                            <span class="badge badge-success ml-2"><i class="fas fa-check-circle"></i> Lunas</span>
                        @elseif ($invoice->status == 'unpaid')
                            <span class="badge badge-warning ml-2"><i class="fas fa-clock"></i> Belum Lunas</span>
                        @elseif ($invoice->status == 'overdue')
                            <span class="badge badge-danger ml-2"><i class="fas fa-exclamation-triangle"></i> Terlambat</span>
                        @else
                            <span class="badge badge-secondary ml-2"><i class="fas fa-times-circle"></i> Dibatalkan</span>
                        @endif
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-secondary mr-2">
                            <i class="fas fa-receipt"></i> Detail Tagihan
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-user mr-1"></i>Detail Pelanggan
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <div class="avatar-placeholder bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <h6 class="mt-2 mb-1">{{ $invoice->customer->name }}</h6>
                                    </div>
                                    
                                    <div class="info-item mb-2">
                                        <label class="font-weight-bold text-muted">Alamat:</label>
                                        <div>{{ $invoice->customer->address }}</div>
                                    </div>
                                    
                                    <div class="info-item mb-2">
                                        <label class="font-weight-bold text-muted">Telepon:</label>
                                        <div>{{ $invoice->customer->phone }}</div>
                                    </div>
                                    
                                    <div class="info-item mb-2">
                                        <label class="font-weight-bold text-muted">Email:</label>
                                        <div>{{ $invoice->customer->email }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-calendar mr-1"></i>Detail Invoice
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="info-item mb-2">
                                        <label class="font-weight-bold text-muted">Tanggal Invoice:</label>
                                        <div class="text-info font-weight-bold">
                                            {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    
                                    <div class="info-item mb-2">
                                        <label class="font-weight-bold text-muted">Jatuh Tempo:</label>
                                        <div class="text-warning font-weight-bold">
                                            {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    
                                    <div class="info-item mb-2">
                                        <label class="font-weight-bold text-muted">Status:</label>
                                        <div>
                                            @if ($invoice->status == 'paid')
                                                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Lunas</span>
                                            @elseif ($invoice->status == 'unpaid')
                                                <span class="badge badge-warning"><i class="fas fa-clock"></i> Belum Lunas</span>
                                            @elseif ($invoice->status == 'overdue')
                                                <span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i> Terlambat</span>
                                            @else
                                                <span class="badge badge-secondary"><i class="fas fa-times-circle"></i> Dibatalkan</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="info-item mb-2">
                                        <label class="font-weight-bold text-muted">Dibuat Oleh:</label>
                                        <div>
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-user-cog"></i> {{ $invoice->creator->name ?? 'Tidak diketahui' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-receipt mr-1"></i>Detail Tagihan
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Paket</th>
                                                    <th>Deskripsi</th>
                                                    <th class="text-right">Harga (Termasuk PPN 11%)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <span class="badge badge-primary">
                                                            <i class="fas fa-box"></i> {{ $invoice->package->name }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $invoice->package->description ?? 'Paket Internet/Metro' }}</td>
                                                    <td class="text-right font-weight-bold text-success">
                                                        Rp {{ number_format($invoice->amount + $invoice->tax_amount, 0, ',', '.') }}
                                                        <div class="small text-muted">
                                                            <div>Harga Dasar: Rp {{ number_format($invoice->amount, 0, ',', '.') }}</div>
                                                            <div>PPN 11%: Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot class="bg-light">
                                                <tr>
                                                    <th colspan="2" class="text-right">Harga (Termasuk PPN 11%)</th>
                                                    <th class="text-right font-weight-bold text-success">
                                                        Rp {{ number_format($invoice->amount + $invoice->tax_amount, 0, ',', '.') }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th colspan="2" class="text-right">Fee Teknisi ({{ $invoice->technician_fee_percentage }}%)</th>
                                                    <th class="text-right font-weight-bold text-warning">
                                                        Rp {{ number_format($invoice->technician_fee_amount, 0, ',', '.') }}
                                                    </th>
                                                </tr>
                                                <tr class="bg-success text-white">
                                                    <th colspan="2" class="text-right">Total</th>
                                                    <th class="text-right font-weight-bold">
                                                        Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($invoice->notes)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card card-outline card-warning">
                                    <div class="card-header">
                                        <h5 class="card-title">
                                            <i class="fas fa-sticky-note mr-1"></i>Catatan
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-light">
                                            <i class="fas fa-quote-left mr-2"></i>
                                            {{ $invoice->notes }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>Ringkasan Tagihan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="info-box bg-gradient-success mb-3">
                        <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Harga (Termasuk PPN 11%)</span>
                            <span class="info-box-number">Rp {{ number_format($invoice->amount + $invoice->tax_amount, 0, ',', '.') }}</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                            <span class="progress-description">
                                Harga Dasar: Rp {{ number_format($invoice->amount, 0, ',', '.') }} + PPN: Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="info-box bg-gradient-warning mb-3">
                        <span class="info-box-icon"><i class="fas fa-user-cog"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Fee Teknisi ({{ $invoice->technician_fee_percentage }}%)</span>
                            <span class="info-box-number">Rp {{ number_format($invoice->technician_fee_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <div class="info-box bg-gradient-primary">
                        <span class="info-box-icon"><i class="fas fa-calculator"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total</span>
                            <span class="info-box-number">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
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
                        <p class="mb-0">Halaman ini menampilkan informasi lengkap invoice dan rincian pembayaran.</p>
                    </div>
                    
                    <div class="alert alert-primary">
                        <h6><i class="fas fa-print text-primary"></i> Mencetak</h6>
                        <p class="mb-0">Klik tombol "Cetak" untuk mencetak invoice dalam format yang siap cetak.</p>
                    </div>
                    
                    <div class="alert alert-success">
                        <h6><i class="fas fa-money-bill-wave text-success"></i> Pembayaran</h6>
                        <p class="mb-0">Pastikan status pembayaran selalu terupdate sesuai dengan pembayaran pelanggan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="help-modal" tabindex="-1" role="dialog" aria-labelledby="help-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h5 class="modal-title text-white" id="help-modal-label">
                        <i class="fas fa-question-circle mr-2"></i>Panduan Detail Invoice
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-user text-info"></i> Informasi Pelanggan</h6>
                            <p class="text-muted">Lihat data lengkap pelanggan yang ditagih termasuk kontak dan alamat.</p>
                            
                            <h6><i class="fas fa-calendar text-primary"></i> Detail Invoice</h6>
                            <p class="text-muted">Lihat tanggal invoice, jatuh tempo, status pembayaran, dan pembuat invoice.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-receipt text-success"></i> Rincian Tagihan</h6>
                            <p class="text-muted">Lihat detail paket, subtotal, PPN, fee teknisi, dan total tagihan.</p>
                            
                            <h6><i class="fas fa-print text-warning"></i> Mencetak</h6>
                            <p class="text-muted">Cetak invoice dalam format yang profesional dan siap diserahkan ke pelanggan.</p>
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
        
        /* Gap utility */
        .gap-2 {
            gap: 0.5rem;
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
            
            .avatar-placeholder {
                width: 50px !important;
                height: 50px !important;
                font-size: 1.25rem !important;
            }
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
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