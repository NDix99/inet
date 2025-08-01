@extends('adminlte::page')

@section('title', 'Laporan Keuangan Teknisi')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-1">
                <i class="fas fa-wallet mr-2"></i>Laporan Keuangan Teknisi
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> Ringkasan pendapatan, invoice, dan status fee Anda
            </p>
        </div>
        <a href="#" class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak Laporan
        </a>
    </div>
@stop

@section('content')
    <!-- Filter Form -->
    <div class="card card-outline card-primary mb-4">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-filter mr-1"></i>Filter Laporan
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('technician.financial.report') }}" method="GET" class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_date" class="font-weight-bold">
                            <i class="fas fa-calendar-plus text-primary mr-1"></i>Dari Tanggal
                        </label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="end_date" class="font-weight-bold">
                            <i class="fas fa-calendar-times text-primary mr-1"></i>Sampai Tanggal
                        </label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}" required>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('technician.financial.report') }}'">
                        <i class="fas fa-undo mr-1"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-success">
                <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Fee Lunas</span>
                    <span class="info-box-number">Rp {{ number_format($totalFee, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-warning">
                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Fee Tertunda</span>
                    <span class="info-box-number">Rp {{ number_format($pendingFee, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
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
            <div class="info-box bg-gradient-primary">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Pelanggan</span>
                    <span class="info-box-number">{{ $invoices->pluck('customer_id')->unique()->count() }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Revenue Card -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-12 col-sm-12 col-12 mb-3">
            <div class="info-box bg-gradient-info">
                <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Revenue</span>
                    <span class="info-box-number">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-info" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">
                        <i class="fas fa-calendar-alt"></i> Periode {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section dihapus -->
    <!-- Detail Invoice Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detail Invoice</h3>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped" id="invoices-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Invoice</th>
                        <th>Pelanggan</th>
                        <th>Paket</th>
                        <th>Tanggal</th>
                        <th>Fee Teknisi</th>
                        <th>Status</th>
                        <th>Status Cetak</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $index => $invoice)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->customer->name }}</td>
                            <td>{{ $invoice->package->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
                            <td class="text-right">Rp {{ number_format($invoice->technician_fee_amount, 0, ',', '.') }}</td>
                            <td>
                                @if ($invoice->status == 'paid')
                                    <span class="badge badge-success">Lunas</span>
                                @elseif ($invoice->status == 'unpaid')
                                    <span class="badge badge-warning">Belum Lunas</span>
                                @elseif ($invoice->status == 'overdue')
                                    <span class="badge badge-danger">Terlambat</span>
                                @else
                                    <span class="badge badge-secondary">Dibatalkan</span>
                                @endif
                            </td>
                            <td>
                                @if($invoice->is_printed_technician)
                                    <span class="badge badge-success">Tercetak</span>
                                @else
                                    <span class="badge badge-secondary">Belum Tercetak</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('technician.invoices.print', $invoice->id) }}" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fas fa-print"></i> Print
                                </a>
                                <!-- Tombol set status lunas/belum lunas -->
                                <form action="{{ route('technician.invoices.update.status', $invoice->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('PUT')
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Ubah Status
                                        </button>
                                        <div class="dropdown-menu">
                                            <button class="dropdown-item" type="submit" name="status" value="paid">Lunas</button>
                                            <button class="dropdown-item" type="submit" name="status" value="unpaid">Belum Lunas</button>
                                            <button class="dropdown-item" type="submit" name="status" value="overdue">Terlambat</button>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">Total:</th>
                        <th class="text-right">Rp {{ number_format($totalFee + $pendingFee, 0, ',', '.') }}</th>
                        <th colspan="3"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('css')
    <style>
        .info-box {
            transition: all 0.3s ease;
        }
        .info-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        @media (max-width: 991.98px) {
            .info-box {
                margin-bottom: 1rem;
            }
        }
        @media (max-width: 767.98px) {
            .row.mb-4 > [class^='col-'] {
                flex: 0 0 100%;
                max-width: 100%;
            }
            .info-box {
                min-height: 80px;
                padding: 0.5rem 0.75rem;
            }
            .info-box-icon {
                font-size: 1.5rem;
                min-width: 40px;
                min-height: 40px;
            }
            .info-box-content {
                font-size: 0.95rem;
            }
            .btn, .btn-primary, .btn-secondary {
                font-size: 0.95rem;
                padding: 0.4rem 0.8rem;
            }
        }
        @media (max-width: 575.98px) {
            .info-box {
                min-height: 60px;
                padding: 0.25rem 0.5rem;
            }
            .info-box-icon {
                font-size: 1.1rem;
                min-width: 32px;
                min-height: 32px;
            }
            .info-box-content {
                font-size: 0.85rem;
            }
            .btn, .btn-primary, .btn-secondary {
                font-size: 0.85rem;
                padding: 0.3rem 0.6rem;
            }
        }
        .table-responsive {
            overflow-x: auto;
        }
        .table th, .table td {
            white-space: nowrap;
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
                order: [[4, "desc"]],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script>
@stop 