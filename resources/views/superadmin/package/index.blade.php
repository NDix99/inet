@extends('adminlte::page')

@section('title', 'Manajemen Paket Internet')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-1">
                <i class="fas fa-box mr-2"></i>Manajemen Paket Internet
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> Kelola paket internet dan metro yang tersedia untuk pelanggan
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('superadmin.packages.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> 
                <span class="d-none d-sm-inline">Tambah Paket</span>
                <span class="d-inline d-sm-none">Tambah</span>
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
                <span class="info-box-icon"><i class="fas fa-wifi"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Paket Internet</span>
                    <span class="info-box-number">{{ $packages->where('type', 'internet')->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-info">
                <span class="info-box-icon"><i class="fas fa-network-wired"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Paket Metro</span>
                    <span class="info-box-number">{{ $packages->where('type', 'metro')->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table mr-1"></i>Data Paket Internet
            </h3>
            <div class="card-tools">
                <span class="badge badge-primary mr-2">
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

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="table-responsive">
                <table id="packages-table" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Paket</th>
                            <th>Tipe</th>
                            <th>Harga (Termasuk PPN 11%)</th>
                            <th>Status</th>
                            <th>Deskripsi</th>
                            <th>Jumlah Pelanggan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($packages as $package)
                            <tr>
                                <td>{{ $package->id }}</td>
                                <td>{{ $package->name }}</td>
                                <td>
                                    @if($package->type == 'internet')
                                        <span class="badge badge-warning"><i class="fas fa-wifi"></i> Internet</span>
                                    @else
                                        <span class="badge badge-info"><i class="fas fa-network-wired"></i> Metro</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="font-weight-bold text-primary">
                                        Rp {{ number_format($package->price, 0, ',', '.') }}
                                    </div>
                                    <small class="text-muted">
                                        <div>Harga Dasar: Rp {{ number_format($package->base_price, 0, ',', '.') }}</div>
                                        <div>PPN 11%: Rp {{ number_format($package->tax_amount, 0, ',', '.') }}</div>
                                    </small>
                                </td>
                                <td>
                                    @if ($package->is_active)
                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Aktif</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    @if($package->description)
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $package->description }}">
                                            {{ $package->description }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-users"></i> {{ $package->customers()->count() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column flex-sm-row gap-1">
                                        <a href="{{ route('superadmin.packages.edit', $package) }}" class="btn btn-sm btn-warning" title="Edit Paket">
                                            <i class="fas fa-edit"></i> 
                                            <span class="d-none d-sm-inline">Edit</span>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-info" title="Detail Paket" data-toggle="modal" data-target="#detail-modal-{{ $package->id }}">
                                            <i class="fas fa-eye"></i> 
                                            <span class="d-none d-sm-inline">Detail</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" title="Hapus Paket" data-toggle="modal" data-target="#deleteModal{{ $package->id }}">
                                            <i class="fas fa-trash"></i> 
                                            <span class="d-none d-sm-inline">Hapus</span>
                                        </button>
                                    </div>

                                    <!-- Modal Detail -->
                                    <div class="modal fade" id="detail-modal-{{ $package->id }}" tabindex="-1" role="dialog" aria-labelledby="detail-modal-label" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info">
                                                    <h5 class="modal-title text-white" id="detail-modal-label">
                                                        <i class="fas fa-box mr-2"></i>Detail Paket
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">ID Paket:</label>
                                                                <p class="form-control-static">{{ $package->id }}</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Nama Paket:</label>
                                                                <p class="form-control-static">{{ $package->name }}</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Tipe Paket:</label>
                                                                <p>
                                                                    @if($package->type == 'internet')
                                                                        <span class="badge badge-warning"><i class="fas fa-wifi"></i> Internet</span>
                                                                    @else
                                                                        <span class="badge badge-info"><i class="fas fa-network-wired"></i> Metro</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Harga:</label>
                                                                <p class="form-control-static text-primary font-weight-bold">
                                                                    Rp {{ number_format($package->price, 0, ',', '.') }}
                                                                </p>
                                                                <div class="alert alert-light p-2 mt-1">
                                                                    <div class="d-flex justify-content-between">
                                                                        <span>Harga Dasar:</span>
                                                                        <span>Rp {{ number_format($package->base_price, 0, ',', '.') }}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span>PPN (11%):</span>
                                                                        <span>Rp {{ number_format($package->tax_amount, 0, ',', '.') }}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between font-weight-bold mt-1 pt-1 border-top">
                                                                        <span>Total Harga:</span>
                                                                        <span>Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Status:</label>
                                                                <p>
                                                                    @if ($package->is_active)
                                                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Aktif</span>
                                                                    @else
                                                                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Tidak Aktif</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Jumlah Pelanggan:</label>
                                                                <p class="form-control-static">
                                                                    <span class="badge badge-secondary">
                                                                        <i class="fas fa-users"></i> {{ $package->customers()->count() }}
                                                                    </span>
                                                                </p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Dibuat:</label>
                                                                <p class="form-control-static">{{ $package->created_at->format('d/m/Y H:i') }}</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Terakhir Update:</label>
                                                                <p class="form-control-static">{{ $package->updated_at->format('d/m/Y H:i') }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if($package->description)
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">Deskripsi:</label>
                                                            <div class="alert alert-light">
                                                                {{ $package->description }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                    <a href="{{ route('superadmin.packages.edit', $package) }}" class="btn btn-warning">
                                                        <i class="fas fa-edit"></i> Edit Paket
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Modal Konfirmasi Hapus -->
                                    <div class="modal fade" id="deleteModal{{ $package->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $package->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger">
                                                    <h5 class="modal-title text-white" id="deleteModalLabel{{ $package->id }}">
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
                                                    <p class="text-center">Apakah Anda yakin ingin menghapus paket <strong>{{ $package->name }}</strong>?</p>
                                                    @if ($package->customers()->count() > 0)
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-triangle"></i> 
                                                            <strong>Peringatan:</strong> Paket ini digunakan oleh {{ $package->customers()->count() }} pelanggan. Paket yang digunakan oleh pelanggan tidak dapat dihapus.
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                        <i class="fas fa-times"></i> Batal
                                                    </button>
                                                    <form action="{{ route('superadmin.packages.destroy', $package) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" {{ $package->customers()->count() > 0 ? 'disabled' : '' }}>
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
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="help-modal" tabindex="-1" role="dialog" aria-labelledby="help-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="help-modal-label">
                        <i class="fas fa-question-circle mr-2"></i>Panduan Kelola Paket Internet
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-plus text-primary"></i> Menambah Paket</h6>
                            <p class="text-muted">Klik tombol "Tambah Paket" untuk membuat paket internet atau metro baru. Pastikan harga dan deskripsi sesuai.</p>
                            
                            <h6><i class="fas fa-edit text-warning"></i> Mengedit Paket</h6>
                            <p class="text-muted">Klik tombol "Edit" untuk mengubah data paket. Perubahan harga akan mempengaruhi invoice yang akan datang.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-eye text-info"></i> Melihat Detail</h6>
                            <p class="text-muted">Klik tombol "Detail" untuk melihat informasi lengkap paket termasuk jumlah pelanggan yang menggunakannya.</p>
                            
                            <h6><i class="fas fa-trash text-danger"></i> Menghapus Paket</h6>
                            <p class="text-muted">Klik tombol "Hapus" untuk menghapus paket. Paket yang digunakan pelanggan tidak dapat dihapus.</p>
                        </div>
                    </div>
                    <div class="alert alert-primary mt-3">
                        <i class="fas fa-lightbulb"></i> 
                        <strong>Tips:</strong> Paket internet dan metro memiliki karakteristik berbeda. Pastikan deskripsi paket jelas untuk memudahkan pelanggan memilih.
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
            $('#packages-table').DataTable({
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