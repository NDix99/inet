@extends('adminlte::page')

@section('title', 'Manajemen Invoice')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-1">
                <i class="fas fa-file-invoice mr-2"></i>Manajemen Invoice
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> Kelola invoice pelanggan dan status pembayaran
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#help-modal">
                <i class="fas fa-question-circle"></i> 
                <span class="d-none d-sm-inline">Bantuan</span>
                <span class="d-inline d-sm-none">Help</span>
            </button>
        </div>
    </div>
@stop

@section('content')
    <!-- Welcome Modal -->
    <div class="modal fade" id="welcomeModal" tabindex="-1" role="dialog" aria-labelledby="welcomeModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="welcomeModalLabel">
                        <i class="fas fa-exclamation-circle mr-2"></i>Perhatian Penting
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle mr-2"></i>Harap Perhatikan!</h5>
                        <p class="mb-0">Halaman ini berisi informasi invoice pelanggan yang memerlukan perhatian khusus dalam pengelolaannya.</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body p-3">
                                    <h6 class="card-title"><i class="fas fa-print text-primary mr-2"></i>Pencetakan Invoice</h6>
                                    <p class="card-text small">Pencetakan invoice akan mengubah status cetak menjadi "Tercetak". Pastikan Anda mencetak invoice hanya saat benar-benar diperlukan.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body p-3">
                                    <h6 class="card-title"><i class="fas fa-edit text-warning mr-2"></i>Status Invoice</h6>
                                    <p class="card-text small">Perubahan status invoice harus sesuai dengan pembayaran yang telah dilakukan. Jangan mengubah status menjadi "Lunas" jika pembayaran belum dilakukan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body p-3">
                                    <h6 class="card-title"><i class="fas fa-undo text-danger mr-2"></i>Reset Status</h6>
                                    <p class="card-text small">Fitur reset status cetak hanya boleh digunakan dalam keadaan tertentu, seperti kesalahan pencetakan atau perubahan data setelah pencetakan.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body p-3">
                                    <h6 class="card-title"><i class="fas fa-info-circle text-info mr-2"></i>Pembuatan Invoice</h6>
                                    <p class="card-text small">Invoice akan dibuat secara otomatis saat teknisi membuat pelanggan baru. Tidak perlu membuat invoice secara manual.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-check mt-3">
                        <input type="checkbox" class="form-check-input" id="dontShowAgain">
                        <label class="form-check-label" for="dontShowAgain">Jangan tampilkan lagi</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info mr-2" data-dismiss="modal" data-toggle="modal" data-target="#help-modal">
                        <i class="fas fa-book mr-1"></i>Lihat Panduan
                    </button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">
                        <i class="fas fa-check mr-1"></i>Saya Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-primary">
                <span class="info-box-icon"><i class="fas fa-file-invoice"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Invoice</span>
                    <span class="info-box-number">{{ $totalInvoices }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-success">
                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Lunas</span>
                    <span class="info-box-number">{{ $paidInvoices }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-warning">
                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Belum Lunas</span>
                    <span class="info-box-number">{{ $unpaidInvoices }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-danger">
                <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Terlambat</span>
                    <span class="info-box-number">{{ $overdueInvoices }}</span>
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

    <!-- Invoice Table -->
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table mr-1"></i>Data Invoice
            </h3>
            <div class="card-tools">
                <span class="badge badge-primary mr-2">
                    <i class="fas fa-list"></i> {{ $totalInvoices }} Invoice
                </span>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div> 
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
                <div class="input-group input-group-sm" style="max-width: 360px;">
                    <input type="text" id="invoice-search-input" class="form-control" placeholder="Cari no invoice, pelanggan, email, status, tanggal..." value="{{ $search ?? '' }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" id="invoice-search-btn" type="button"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="invoices-table" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Jatuh Tempo</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Print</th>
                            <th>Dicetak oleh Teknisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="invoice-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mr-2" style="width: 35px; height: 35px; font-size: 0.875rem;">
                                            <i class="fas fa-file-invoice"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-primary">{{ $invoice->invoice_number }}</div>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="customer-avatar bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mr-2" style="width: 35px; height: 35px; font-size: 0.875rem;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">{{ $invoice->customer->name }}</div>
                                            <small class="text-muted">
                                                <i class="fas fa-envelope"></i> {{ $invoice->customer->email }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar-plus text-muted mr-2"></i>
                                        <span>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar-times text-muted mr-2"></i>
                                        <span>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-money-bill-wave text-success mr-2"></i>
                                        <div>
                                            <span class="font-weight-bold text-success">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                                            <small class="d-block text-muted">
                                                <i class="fas fa-info-circle"></i> Sudah termasuk PPN
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($invoice->status === 'paid')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i> Lunas
                                        </span>
                                    @elseif($invoice->status === 'overdue')
                                        <span class="badge badge-danger">
                                            <i class="fas fa-exclamation-triangle"></i> Terlambat
                                        </span>
                                    @elseif($invoice->status === 'cancelled')
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-times-circle"></i> Dibatalkan
                                        </span>
                                    @else
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock"></i> Belum Lunas
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($invoice->is_printed_admin)
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle text-success mr-2"></i>
                                            <div>
                                                <span class="badge badge-success">Tercetak</span>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $invoice->printed_at ? \Carbon\Carbon::parse($invoice->printed_at)->format('d/m/Y H:i') : '-' }}
                                                </small>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-times-circle text-muted mr-2"></i>
                                            <span class="badge badge-secondary">Belum Tercetak</span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($invoice->is_printed_technician)
                                        {{ optional($invoice->printed_by_technician ? \App\Models\User::find($invoice->printed_by_technician) : null)->name ?? '-' }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailModal{{ $invoice->id }}" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('admin.invoices.print', $invoice) }}" class="btn btn-sm btn-primary" target="_blank" title="Cetak Invoice">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#statusModal{{ $invoice->id }}" title="Update Status">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.invoices.reset.print.status', $invoice) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Reset Status Cetak" onclick="return confirm('Yakin reset status cetak invoice ini?')">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Modal Detail -->
                                    <div class="modal fade" id="detailModal{{ $invoice->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel{{ $invoice->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info">
                                                    <h5 class="modal-title text-white" id="detailModalLabel{{ $invoice->id }}">
                                                        <i class="fas fa-file-invoice mr-2"></i>Detail Invoice
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
                                                                        <i class="fas fa-file-invoice"></i>
                                                                    </div>
                                                                    <div>
                                                                        <small class="text-muted">No. Invoice</small>
                                                                        <div class="font-weight-bold">{{ $invoice->invoice_number }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="info-item mb-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="icon-wrapper bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                                                        <i class="fas fa-user"></i>
                                                                    </div>
                                                                    <div>
                                                                        <small class="text-muted">Pelanggan</small>
                                                                        <div class="font-weight-bold">{{ $invoice->customer->name }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="info-item mb-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="icon-wrapper bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                                                        <i class="fas fa-money-bill-wave"></i>
                                                                    </div>
                                                                    <div>
                                                                        <small class="text-muted">Total</small>
                                                                        <div class="font-weight-bold text-success">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</div>
                                                                        <small class="text-muted"><i class="fas fa-info-circle"></i> Sudah termasuk PPN</small>
                                                                        <small class="d-block text-muted"><i class="fas fa-calculator"></i> Harga Dasar: Rp {{ number_format($invoice->base_price, 0, ',', '.') }}</small>
                                                                        <small class="d-block text-muted"><i class="fas fa-percentage"></i> PPN: Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</small>
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
                                                                            @if($invoice->status === 'paid')
                                                                                <span class="badge badge-success">
                                                                                    <i class="fas fa-check-circle"></i> Lunas
                                                                                </span>
                                                                            @elseif($invoice->status === 'overdue')
                                                                                <span class="badge badge-danger">
                                                                                    <i class="fas fa-exclamation-triangle"></i> Terlambat
                                                                                </span>
                                                                            @elseif($invoice->status === 'cancelled')
                                                                                <span class="badge badge-secondary">
                                                                                    <i class="fas fa-times-circle"></i> Dibatalkan
                                                                                </span>
                                                                            @else
                                                                                <span class="badge badge-warning">
                                                                                    <i class="fas fa-clock"></i> Belum Lunas
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="info-item mb-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="icon-wrapper bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                                                        <i class="fas fa-calendar-plus"></i>
                                                                    </div>
                                                                    <div>
                                                                        <small class="text-muted">Tanggal Invoice</small>
                                                                        <div class="font-weight-bold">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="info-item mb-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="icon-wrapper bg-dark text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                                                        <i class="fas fa-calendar-times"></i>
                                                                    </div>
                                                                    <div>
                                                                        <small class="text-muted">Jatuh Tempo</small>
                                                                        <div class="font-weight-bold">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="info-item mb-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="icon-wrapper {{ $invoice->is_printed_admin ? 'bg-success' : 'bg-secondary' }} text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                                                        <i class="fas fa-print"></i>
                                                                    </div>
                                                                    <div>
                                                                        <small class="text-muted">Status Print</small>
                                                                        <div>
                                                                            @if($invoice->is_printed_admin)
                                                                                <span class="badge badge-success">
                                                                                    <i class="fas fa-check-circle"></i> Tercetak
                                                                                </span>
                                                                                <br>
                                                                                <small class="text-muted">
                                                                                    {{ \Carbon\Carbon::parse($invoice->printed_at)->format('d/m/Y H:i') }}
                                                                                </small>
                                                                            @else
                                                                                <span class="badge badge-secondary">
                                                                                    <i class="fas fa-times-circle"></i> Belum Tercetak
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    @if($invoice->notes)
                                                        <div class="mt-3">
                                                            <h6><i class="fas fa-sticky-note text-muted mr-2"></i>Catatan</h6>
                                                            <div class="alert alert-light">
                                                                {{ $invoice->notes }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                        <i class="fas fa-times"></i> Tutup
                                                    </button>
                                                    <a href="{{ route('admin.invoices.show', $invoice) }}" class="btn btn-info">
                                                        <i class="fas fa-eye"></i> Lihat Detail Lengkap
                                                    </a>
                                                    <a href="{{ route('admin.invoices.print', $invoice) }}" class="btn btn-primary" target="_blank">
                                                        <i class="fas fa-print"></i> Cetak Invoice
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Update Status -->
                                    <div class="modal fade" id="statusModal{{ $invoice->id }}" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel{{ $invoice->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning">
                                                    <h5 class="modal-title text-white" id="statusModalLabel{{ $invoice->id }}">
                                                        <i class="fas fa-edit mr-2"></i>Update Status Invoice
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('admin.invoices.update.status', $invoice) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="alert alert-info">
                                                            <i class="fas fa-info-circle"></i>
                                                            <strong>Invoice:</strong> {{ $invoice->invoice_number }}<br>
                                                            <strong>Pelanggan:</strong> {{ $invoice->customer->name }}<br>
                                                            <strong>Total:</strong> Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                                                            <small class="d-block mt-1"><i class="fas fa-info-circle"></i> Harga sudah termasuk PPN 11%</small>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="status" class="font-weight-bold">
                                                                <i class="fas fa-toggle-on text-primary mr-1"></i>Status Invoice
                                                            </label>
                                                            <select class="form-control" id="status" name="status">
                                                                <option value="paid" {{ $invoice->status === 'paid' ? 'selected' : '' }}>
                                                                    <i class="fas fa-check-circle"></i> Lunas
                                                                </option>
                                                                <option value="unpaid" {{ $invoice->status === 'unpaid' ? 'selected' : '' }}>
                                                                    <i class="fas fa-clock"></i> Belum Lunas
                                                                </option>
                                                                <option value="overdue" {{ $invoice->status === 'overdue' ? 'selected' : '' }}>
                                                                    <i class="fas fa-exclamation-triangle"></i> Terlambat
                                                                </option>
                                                                <option value="cancelled" {{ $invoice->status === 'cancelled' ? 'selected' : '' }}>
                                                                    <i class="fas fa-times-circle"></i> Dibatalkan
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                            <i class="fas fa-times"></i> Batal
                                                        </button>
                                                        <button type="submit" class="btn btn-warning">
                                                            <i class="fas fa-save"></i> Simpan Perubahan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Tambahkan pagination di bawah tabel --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $invoices->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="help-modal" tabindex="-1" role="dialog" aria-labelledby="help-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="help-modal-label">
                        <i class="fas fa-question-circle mr-2"></i>Panduan Kelola Invoice
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Informasi Penting:</strong> Invoice akan dibuat secara otomatis saat teknisi membuat pelanggan baru. Tidak perlu membuat invoice secara manual.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-eye text-info mr-2"></i>Lihat Detail Invoice</h6>
                            <p class="text-muted">Klik tombol <strong><i class="fas fa-eye"></i></strong> untuk melihat informasi lengkap invoice termasuk rincian paket dan pembayaran.</p>
                            
                            <h6><i class="fas fa-print text-primary mr-2"></i>Cetak Invoice</h6>
                            <p class="text-muted">Klik tombol <strong><i class="fas fa-print"></i></strong> untuk mencetak invoice dalam format PDF. Setiap pencetakan akan mengubah status cetak menjadi "Tercetak".</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-edit text-warning mr-2"></i>Update Status Invoice</h6>
                            <p class="text-muted">Klik tombol <strong><i class="fas fa-edit"></i></strong> untuk mengubah status pembayaran invoice (Lunas, Belum Lunas, Terlambat, atau Dibatalkan).</p>
                            
                            <h6><i class="fas fa-undo text-danger mr-2"></i>Reset Status Cetak</h6>
                            <p class="text-muted">Klik tombol <strong><i class="fas fa-undo"></i></strong> untuk mereset status cetak invoice. Gunakan fitur ini hanya jika terjadi kesalahan pencetakan.</p>
                        </div>
                    </div>
                    
                    <div class="card bg-light mt-4">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-exclamation-circle mr-2"></i>Status Invoice</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <span class="badge badge-success mr-2"><i class="fas fa-check-circle"></i> Lunas</span>
                                            Invoice yang sudah dibayar oleh pelanggan
                                        </li>
                                        <li class="mb-2">
                                            <span class="badge badge-warning mr-2"><i class="fas fa-clock"></i> Belum Lunas</span>
                                            Invoice yang belum dibayar tapi belum jatuh tempo
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <span class="badge badge-danger mr-2"><i class="fas fa-exclamation-triangle"></i> Terlambat</span>
                                            Invoice yang belum dibayar dan sudah melewati jatuh tempo
                                        </li>
                                        <li class="mb-2">
                                            <span class="badge badge-secondary mr-2"><i class="fas fa-times-circle"></i> Dibatalkan</span>
                                            Invoice yang dibatalkan karena alasan tertentu
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning mt-4">
                        <i class="fas fa-lightbulb mr-2"></i> 
                        <strong>Tips:</strong> Pastikan status invoice selalu up-to-date untuk kelancaran administrasi keuangan dan pelaporan.
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
        
        /* Welcome Modal */
        #welcomeModal .card {
            height: 100%;
            transition: all 0.3s ease;
        }
        
        #welcomeModal .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        #welcomeModal .card-title {
            font-size: 1rem;
            font-weight: 600;
        }
        
        #welcomeModal .card-text {
            font-size: 0.85rem;
        }
        
        #welcomeModal .form-check {
            margin-left: 5px;
        }
        
        /* Partner Avatar */
        .partner-avatar {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }
        
        /* Customer Icon */
        .customer-icon {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        /* Invoice Icon */
        .invoice-icon {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        }
        
        /* Summary Item */
        .summary-item {
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }
        
        .summary-item:hover {
            background-color: rgba(40,167,69,0.05);
        }
        
        /* Gap utility */
        .gap-1 {
            gap: 0.25rem;
        }
        
        .gap-2 {
            gap: 0.5rem;
        }
        
        /* Responsive improvements */
        .small-box .inner h3 {
            font-size: 1.5rem;
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
            .info-box {
                margin-bottom: 1rem;
            }
            
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
            
            #welcomeModal .card {
                margin-bottom: 1rem;
            }
            
            #welcomeModal .modal-dialog {
                margin: 0.5rem;
            }
            
            #welcomeModal .modal-body {
                padding: 1rem;
            }
            
            #welcomeModal .card-body {
                padding: 0.75rem;
            }
            
            #welcomeModal .card-title {
                font-size: 0.9rem;
            }
            
            #welcomeModal .card-text {
                font-size: 0.8rem;
            }
            
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
            // Fungsi untuk memeriksa apakah modal sudah pernah ditampilkan
            function checkWelcomeModal() {
                if (localStorage.getItem('invoiceWelcomeModalShown') !== 'true') {
                    $('#welcomeModal').modal('show');
                }
            }
            
            // Tampilkan modal saat halaman dimuat
            checkWelcomeModal();
            
            // Tangani checkbox "Jangan tampilkan lagi"
            $('#dontShowAgain').on('change', function() {
                if ($(this).is(':checked')) {
                    localStorage.setItem('invoiceWelcomeModalShown', 'true');
                } else {
                    localStorage.removeItem('invoiceWelcomeModalShown');
                }
            });
            
            $('#invoices-table').DataTable({
                responsive: true,
                autoWidth: false,
                paging: false,        // nonaktifkan pagination DataTables
                info: false,          // nonaktifkan teks "Menampilkan ..."
                lengthChange: false,  // nonaktifkan dropdown jumlah baris
                searching: false,     // gunakan pencarian global server-side
                language: { url: '{{ asset("vendor/datatables/lang/Indonesian.json") }}' },
                order: [[2, "desc"]],
                dom: '<"row"<"col-sm-12"tr>>'            // hilangkan 'f', 'l', 'i', 'p'
                // buttons: [ ... ] // tetap jika ingin export/print
            });
            // Pencarian global invoice
            function navigateWithParams(modifier) {
                var params = new URLSearchParams(window.location.search);
                modifier(params);
                var newUrl = window.location.pathname + (params.toString() ? ('?' + params.toString()) : '');
                window.location.href = newUrl;
            }

            function doInvoiceSearch() {
                var q = $('#invoice-search-input').val().trim();
                navigateWithParams(function(params) {
                    if (q) { params.set('search', q); } else { params.delete('search'); }
                    params.delete('page');
                });
            }
            $('#invoice-search-btn').on('click', doInvoiceSearch);
            $('#invoice-search-input').on('keypress', function(e) { if (e.which === 13) { doInvoiceSearch(); } });
            
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