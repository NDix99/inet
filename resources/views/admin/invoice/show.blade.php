@extends('adminlte::page')

@section('title', 'Detail Invoice')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-1">
                <i class="fas fa-file-invoice mr-2"></i>Detail Invoice
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> Informasi lengkap invoice dan status pembayaran
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.invoices.print', $invoice) }}" class="btn btn-primary" target="_blank">
                <i class="fas fa-print"></i> 
                <span class="d-none d-sm-inline">Cetak Invoice</span>
                <span class="d-inline d-sm-none">Print</span>
            </a>
            <form action="{{ route('admin.invoices.reset.print.status', $invoice) }}" method="POST" style="display:inline;">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin reset status cetak invoice ini?')">
                    <i class="fas fa-undo"></i> Reset Status Cetak
                </button>
            </form>
            <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
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
    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-invoice mr-1"></i>Invoice #{{ $invoice->invoice_number }}
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'overdue' ? 'danger' : 'warning') }} mr-2">
                            <i class="fas fa-{{ $invoice->status === 'paid' ? 'check-circle' : ($invoice->status === 'overdue' ? 'exclamation-triangle' : 'clock') }}"></i> 
                            {{ $invoice->status === 'paid' ? 'Lunas' : ($invoice->status === 'overdue' ? 'Terlambat' : 'Belum Lunas') }}
                        </span>
                        @if($invoice->is_printed)
                            <span class="badge badge-success">Tercetak</span>
                            <br>
                            <small class="text-muted">
                                {{ $invoice->printed_at ? \Carbon\Carbon::parse($invoice->printed_at)->format('d/m/Y H:i') : '-' }}
                            </small>
                        @else
                            <span class="badge badge-secondary">Belum Dicetak</span>
                        @endif
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-section mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-user mr-2"></i>Detail Pelanggan
                                </h5>
                                <div class="customer-info">
                                    <div class="info-item mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-wrapper bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 35px; height: 35px; font-size: 0.875rem;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold">{{ $invoice->customer->name }}</div>
                                                <small class="text-muted">
                                                    <i class="fas fa-envelope"></i> {{ $invoice->customer->email }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-wrapper bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 35px; height: 35px; font-size: 0.875rem;">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold">Alamat</div>
                                                <small class="text-muted">{{ $invoice->customer->address ?? 'Tidak ada alamat' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-wrapper bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 35px; height: 35px; font-size: 0.875rem;">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold">Telepon</div>
                                                <small class="text-muted">{{ $invoice->customer->phone ?? 'Tidak ada telepon' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-section mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-calendar-alt mr-2"></i>Informasi Invoice
                                </h5>
                                <div class="invoice-info">
                                    <div class="info-item mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-wrapper bg-info text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 35px; height: 35px; font-size: 0.875rem;">
                                                <i class="fas fa-calendar-plus"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold">Tanggal Invoice</div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-wrapper bg-danger text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 35px; height: 35px; font-size: 0.875rem;">
                                                <i class="fas fa-calendar-times"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold">Jatuh Tempo</div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-wrapper bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 35px; height: 35px; font-size: 0.875rem;">
                                                <i class="fas fa-toggle-on"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold">Status</div>
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
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Package Details -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-box mr-1"></i>Detail Paket
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="bg-success text-white">
                                                <tr>
                                                    <th><i class="fas fa-tag mr-1"></i>Paket</th>
                                                    <th><i class="fas fa-tachometer-alt mr-1"></i>Kecepatan</th>
                                                    <th><i class="fas fa-align-left mr-1"></i>Deskripsi</th>
                                                    <th class="text-right"><i class="fas fa-money-bill-wave mr-1"></i>Harga</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="package-icon bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mr-2" style="width: 30px; height: 30px; font-size: 0.75rem;">
                                                                <i class="fas fa-box"></i>
                                                            </div>
                                                            <span class="font-weight-bold">{{ $invoice->customer->package->name ?? 'Tidak ada paket' }}</span>
                                                        </div>
                                                    </td>
                                                    <td>{{ $invoice->customer->package->speed ?? '-' }}</td>
                                                    <td>{{ $invoice->customer->package->description ?? '-' }}</td>
                                                    <td class="text-right font-weight-bold text-success">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notes and Summary -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card card-outline card-warning">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-sticky-note mr-1"></i>Catatan
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="notes-content">
                                        @if($invoice->notes)
                                            <p class="mb-0">{{ $invoice->notes }}</p>
                                        @else
                                            <p class="text-muted mb-0">
                                                <i class="fas fa-info-circle"></i> Tidak ada catatan
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-calculator mr-1"></i>Ringkasan
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-striped mb-0">
                                        <tr>
                                            <th>Total:</th>
                                            <td class="text-right font-weight-bold text-success">
                                                Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Harga Dasar:</th>
                                            <td class="text-right">
                                                Rp {{ number_format($invoice->base_price, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>PPN (11%):</th>
                                            <td class="text-right">
                                                Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-right text-muted small">
                                                <i class="fas fa-info-circle"></i> Sudah termasuk PPN
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#statusModal">
                        <i class="fas fa-edit mr-2"></i>Update Status
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>Statistik Invoice
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-success mr-2">
                            <i class="fas fa-chart"></i> Stats
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="invoice-icon bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <h5 class="mb-1">{{ $invoice->invoice_number }}</h5>
                        <span class="badge badge-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'overdue' ? 'danger' : 'warning') }} badge-lg">
                            @if($invoice->status === 'paid')
                                <i class="fas fa-check-circle"></i> Lunas
                            @elseif($invoice->status === 'overdue')
                                <i class="fas fa-exclamation-triangle"></i> Terlambat
                            @elseif($invoice->status === 'cancelled')
                                <i class="fas fa-times-circle"></i> Dibatalkan
                            @else
                                <i class="fas fa-clock"></i> Belum Lunas
                            @endif
                        </span>
                    </div>
                    
                    <div class="invoice-stats">
                        <div class="stat-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    <i class="fas fa-money-bill-wave mr-2"></i>Total Amount
                                </span>
                                <span class="font-weight-bold text-success">
                                    Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="stat-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    <i class="fas fa-percentage mr-2"></i>Tax Rate
                                </span>
                                <span class="font-weight-bold text-warning">
                                    {{ number_format($invoice->tax_percentage, 1) }}%
                                </span>
                            </div>
                        </div>
                        
                        <div class="stat-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    <i class="fas fa-calendar-alt mr-2"></i>Days Until Due
                                </span>
                                <span class="font-weight-bold text-info">
                                    {{ \Carbon\Carbon::now()->diffInDays($invoice->due_date, false) }} hari
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lightbulb mr-1"></i>Tips
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-info mr-2">
                            <i class="fas fa-tips"></i> Tips
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Cetak invoice untuk pelanggan
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Update status sesuai pembayaran
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Monitor jatuh tempo pembayaran
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Simpan catatan penting
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-1"></i>Riwayat
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-primary mr-2">
                            <i class="fas fa-clock"></i> History
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="history-item mb-2">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 30px; height: 30px; font-size: 0.75rem;">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <div>
                                <small class="text-muted">Dibuat</small>
                                <div class="font-weight-bold">{{ $invoice->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="history-item mb-2">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 30px; height: 30px; font-size: 0.75rem;">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div>
                                <small class="text-muted">Terakhir Diperbarui</small>
                                <div class="font-weight-bold">{{ $invoice->updated_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Update Status -->
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-white" id="statusModalLabel">
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

    <!-- Help Modal -->
    <div class="modal fade" id="help-modal" tabindex="-1" role="dialog" aria-labelledby="help-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
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
                            <h6><i class="fas fa-user text-primary"></i> Detail Pelanggan</h6>
                            <p class="text-muted">Informasi lengkap pelanggan yang terkait dengan invoice ini.</p>
                            
                            <h6><i class="fas fa-box text-success"></i> Detail Paket</h6>
                            <p class="text-muted">Informasi paket yang digunakan pelanggan.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-print text-info"></i> Cetak Invoice</h6>
                            <p class="text-muted">Cetak invoice dalam format PDF untuk pelanggan.</p>
                            
                            <h6><i class="fas fa-edit text-warning"></i> Update Status</h6>
                            <p class="text-muted">Ubah status pembayaran invoice sesuai kondisi.</p>
                        </div>
                    </div>
                    <div class="alert alert-primary mt-3">
                        <i class="fas fa-lightbulb"></i> 
                        <strong>Tips:</strong> Pastikan status invoice selalu up-to-date untuk kelancaran administrasi keuangan.
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
        /* Card Improvements */
        .card-outline {
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        
        /* Info Section */
        .info-section {
            padding: 1rem;
            border-radius: 0.375rem;
            background-color: #f8f9fa;
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
        
        /* Icon Wrapper */
        .icon-wrapper {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }
        
        /* Invoice Icon */
        .invoice-icon {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        /* Package Icon */
        .package-icon {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        /* Stat Item */
        .stat-item {
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }
        
        .stat-item:hover {
            background-color: rgba(40,167,69,0.05);
        }
        
        /* History Item */
        .history-item {
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }
        
        .history-item:hover {
            background-color: rgba(0,123,255,0.05);
        }
        
        /* Modal Improvements */
        .modal-header {
            border-bottom: none;
        }
        
        .modal-footer {
            border-top: none;
        }
        
        /* Gap utility */
        .gap-2 {
            gap: 0.5rem;
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .info-section {
                padding: 0.5rem;
            }
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
        });
    </script>
@stop 