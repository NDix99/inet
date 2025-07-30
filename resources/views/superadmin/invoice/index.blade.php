@extends('adminlte::page')

@section('title', 'Daftar Invoice')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-1">
                <i class="fas fa-file-invoice mr-2"></i>Daftar Invoice
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> Kelola semua invoice pelanggan dan status pembayaran
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
            <div class="info-box bg-gradient-secondary">
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
                    <span class="info-box-text">Invoice Lunas</span>
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

    <div class="card card-outline card-secondary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table mr-1"></i>Data Invoice
            </h3>
            <div class="card-tools">
                <span class="badge badge-secondary mr-2">
                    <i class="fas fa-search"></i> Cari & Filter
                </span>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="invoices-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Invoice</th>
                            <th>Pelanggan</th>
                            <th>Paket</th>
                            <th>Tanggal</th>
                            <th>Jatuh Tempo</th>
                            <th>Total (Termasuk PPN 11%)</th>
                            <th>Status</th>
                            <th>Dicetak oleh Teknisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $index => $invoice)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="font-weight-bold text-secondary">{{ $invoice->invoice_number }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-placeholder bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mr-2" style="width: 30px; height: 30px; font-size: 0.75rem;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span>{{ $invoice->customer->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($invoice->package)
                                        <span class="badge badge-primary">
                                            <i class="fas fa-box"></i> {{ $invoice->package->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">Tidak ada paket</span>
                                    @endif
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
                                    @if($invoice->is_printed_superadmin)
                                        <span class="badge badge-success">Tercetak</span>
                                    @else
                                        <span class="badge badge-secondary">Belum Tercetak</span>
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
                                    <div class="d-flex flex-column flex-sm-row gap-1">
                                        <a href="{{ route('superadmin.invoices.show', $invoice->id) }}" class="btn btn-sm btn-info" title="Detail Invoice">
                                            <i class="fas fa-eye"></i> 
                                            <span class="d-none d-sm-inline">Detail</span>
                                        </a>
                                        <a href="{{ route('superadmin.invoices.print', $invoice->id) }}" class="btn btn-sm btn-primary" target="_blank" title="Print Invoice">
                                            <i class="fas fa-print"></i> 
                                            <span class="d-none d-sm-inline">Print</span>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-warning" title="Edit Invoice" data-toggle="modal" data-target="#edit-modal-{{ $invoice->id }}">
                                            <i class="fas fa-edit"></i> 
                                            <span class="d-none d-sm-inline">Edit</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" title="Hapus Invoice" data-toggle="modal" data-target="#deleteModal{{ $invoice->id }}">
                                            <i class="fas fa-trash"></i> 
                                            <span class="d-none d-sm-inline">Hapus</span>
                                        </button>
                                    </div>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="edit-modal-{{ $invoice->id }}" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning">
                                                    <h5 class="modal-title text-white" id="edit-modal-label">
                                                        <i class="fas fa-edit mr-2"></i>Edit Invoice
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">No. Invoice:</label>
                                                                <p class="form-control-static">{{ $invoice->invoice_number }}</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Pelanggan:</label>
                                                                <p class="form-control-static">{{ $invoice->customer->name }}</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Paket:</label>
                                                                <p class="form-control-static">
                                                                    @if($invoice->package)
                                                                        <span class="badge badge-primary">{{ $invoice->package->name }}</span>
                                                                    @else
                                                                        <span class="text-muted">Tidak ada paket</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Tanggal Invoice:</label>
                                                                <p class="form-control-static">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Status:</label>
                                                                <p>
                                                                    @if ($invoice->status == 'paid')
                                                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Lunas</span>
                                                                    @elseif ($invoice->status == 'unpaid')
                                                                        <span class="badge badge-warning"><i class="fas fa-clock"></i> Belum Lunas</span>
                                                                    @elseif ($invoice->status == 'overdue')
                                                                        <span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i> Terlambat</span>
                                                                    @else
                                                                        <span class="badge badge-secondary"><i class="fas fa-times-circle"></i> Dibatalkan</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Jatuh Tempo:</label>
                                                                <p class="form-control-static">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Total:</label>
                                                                <p class="form-control-static text-success font-weight-bold">
                                                                    Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                                                                </p>
                                                                <small class="text-muted">
                                                                    <div>Harga Dasar: Rp {{ number_format($invoice->amount, 0, ',', '.') }}</div>
                                                                    <div>PPN 11%: Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</div>
                                                                </small>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Dibuat Oleh:</label>
                                                                <p class="form-control-static">
                                                                    <span class="badge badge-secondary">
                                                                        <i class="fas fa-user-cog"></i> {{ $invoice->creator->name ?? 'Tidak diketahui' }}
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                    <a href="{{ route('superadmin.invoices.show', $invoice->id) }}" class="btn btn-info">
                                                        <i class="fas fa-eye"></i> Lihat Detail Lengkap
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Modal Konfirmasi Hapus -->
                                    <div class="modal fade" id="deleteModal{{ $invoice->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $invoice->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger">
                                                    <h5 class="modal-title text-white" id="deleteModalLabel{{ $invoice->id }}">
                                                        <i class="fas fa-exclamation-triangle mr-2"></i>Konfirmasi Hapus
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center mb-3">
                                                        <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                                                    </div>
                                                    <p class="text-center">Apakah Anda yakin ingin menghapus invoice <strong>{{ $invoice->invoice_number }}</strong>?</p>
                                                    <div class="alert alert-info">
                                                        <h6><i class="fas fa-user text-info"></i> Pelanggan:</h6>
                                                        <p class="mb-1">{{ $invoice->customer->name }}</p>
                                                    </div>
                                                    <div class="alert alert-warning">
                                                        <h6><i class="fas fa-calendar text-warning"></i> Tanggal:</h6>
                                                        <p class="mb-1">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</p>
                                                    </div>
                                                    <div class="alert alert-success">
                                                        <h6><i class="fas fa-money-bill-wave text-success"></i> Total:</h6>
                                                        <p class="mb-1">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</p>
                                                        <small class="text-muted">
                                                            <div>Harga Dasar: Rp {{ number_format($invoice->amount, 0, ',', '.') }}</div>
                                                            <div>PPN 11%: Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</div>
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                        <i class="fas fa-times"></i> Batal
                                                    </button>
                                                    <form action="{{ route('superadmin.invoices.destroy', $invoice) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </form>
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
            {{-- Tambahkan pagination di bawah tabel --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="help-modal" tabindex="-1" role="dialog" aria-labelledby="help-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h5 class="modal-title text-white" id="help-modal-label">
                        <i class="fas fa-question-circle mr-2"></i>Panduan Kelola Invoice
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-eye text-info"></i> Melihat Detail</h6>
                            <p class="text-muted">Klik tombol "Detail" untuk melihat informasi lengkap invoice termasuk rincian pembayaran.</p>
                            
                            <h6><i class="fas fa-print text-primary"></i> Mencetak Invoice</h6>
                            <p class="text-muted">Klik tombol "Print" untuk mencetak invoice dalam format yang siap cetak.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-edit text-warning"></i> Mengedit Invoice</h6>
                            <p class="text-muted">Klik tombol "Edit" untuk melihat informasi invoice dan akses ke halaman detail lengkap.</p>
                            
                            <h6><i class="fas fa-trash text-danger"></i> Menghapus Invoice</h6>
                            <p class="text-muted">Klik tombol "Hapus" untuk menghapus invoice. Pastikan invoice tidak sedang digunakan.</p>
                        </div>
                    </div>
                    <div class="alert alert-secondary mt-3">
                        <i class="fas fa-lightbulb"></i> 
                        <strong>Tips:</strong> Invoice dibuat otomatis berdasarkan paket pelanggan. Pastikan status pembayaran selalu terupdate.
                    </div>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Catatan:</strong> Semua harga yang ditampilkan sudah termasuk PPN 11%.
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
        
        /* Avatar Placeholder */
        .avatar-placeholder {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        }
        
        /* Gap utility */
        .gap-1 {
            gap: 0.25rem;
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .info-box {
                margin-bottom: 1rem;
            }
            
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
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
                order: [[4, "desc"]], // Urutkan berdasarkan tanggal invoice (descending)
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