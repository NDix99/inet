@extends('adminlte::page')

@section('title', 'Daftar Pelanggan')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-1">
                <i class="fas fa-users mr-2"></i>Daftar Pelanggan
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> Kelola data pelanggan yang menggunakan layanan internet
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('superadmin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> 
                <span class="d-none d-sm-inline">Kembali ke Dashboard</span>
                <span class="d-inline d-sm-none">Dashboard</span>
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
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Pelanggan</span>
                    <span class="info-box-number">{{ $customers->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-success">
                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pelanggan Aktif</span>
                    <span class="info-box-number">{{ $customers->where('is_active', true)->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-warning">
                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pelanggan Nonaktif</span>
                    <span class="info-box-number">{{ $customers->where('is_active', false)->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-primary">
                <span class="info-box-icon"><i class="fas fa-calendar-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Terdaftar Bulan Ini</span>
                    <span class="info-box-number">{{ $customers->where('created_at', '>=', now()->startOfMonth())->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table mr-1"></i>Data Pelanggan
            </h3>
            <div class="card-tools">
                <span class="badge badge-info mr-2">
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
                <table id="customers-table" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Paket</th>
                            <th>Status</th>
                            <th>Tanggal Tagihan</th>
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>
                                    @if($customer->package)
                                        <span class="badge badge-primary">
                                            <i class="fas fa-box"></i> {{ $customer->package->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">Tidak ada paket</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($customer->is_active)
                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Aktif</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="font-weight-bold text-info">
                                        {{ \Carbon\Carbon::parse($customer->billing_date)->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-user-cog"></i> {{ $customer->creator->name ?? 'Tidak diketahui' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column flex-sm-row gap-1">
                                        <a href="{{ route('superadmin.customers.show', $customer) }}" class="btn btn-sm btn-info" title="Detail Pelanggan">
                                            <i class="fas fa-eye"></i> 
                                            <span class="d-none d-sm-inline">Detail</span>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-warning" title="Edit Pelanggan" data-toggle="modal" data-target="#edit-modal-{{ $customer->id }}">
                                            <i class="fas fa-edit"></i> 
                                            <span class="d-none d-sm-inline">Edit</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" title="Hapus Pelanggan" data-toggle="modal" data-target="#deleteModal{{ $customer->id }}">
                                            <i class="fas fa-trash"></i> 
                                            <span class="d-none d-sm-inline">Hapus</span>
                                        </button>
                                    </div>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="edit-modal-{{ $customer->id }}" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning">
                                                    <h5 class="modal-title text-white" id="edit-modal-label">
                                                        <i class="fas fa-edit mr-2"></i>Edit Pelanggan
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">ID:</label>
                                                                <p class="form-control-static">{{ $customer->id }}</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Nama:</label>
                                                                <p class="form-control-static">{{ $customer->name }}</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Email:</label>
                                                                <p class="form-control-static">{{ $customer->email }}</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Telepon:</label>
                                                                <p class="form-control-static">{{ $customer->phone }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Status:</label>
                                                                <p>
                                                                    @if ($customer->is_active)
                                                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Aktif</span>
                                                                    @else
                                                                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Tidak Aktif</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Paket:</label>
                                                                <p class="form-control-static">
                                                                    @if($customer->package)
                                                                        <span class="badge badge-primary">{{ $customer->package->name }}</span>
                                                                    @else
                                                                        <span class="text-muted">Tidak ada paket</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Tanggal Tagihan:</label>
                                                                <p class="form-control-static">{{ \Carbon\Carbon::parse($customer->billing_date)->format('d/m/Y') }}</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Terdaftar:</label>
                                                                <p class="form-control-static">{{ $customer->created_at->format('d/m/Y H:i') }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                    <a href="{{ route('superadmin.customers.show', $customer) }}" class="btn btn-info">
                                                        <i class="fas fa-eye"></i> Lihat Detail Lengkap
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Modal Konfirmasi Hapus -->
                                    <div class="modal fade" id="deleteModal{{ $customer->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $customer->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger">
                                                    <h5 class="modal-title text-white" id="deleteModalLabel{{ $customer->id }}">
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
                                                    <p class="text-center">Apakah Anda yakin ingin menghapus pelanggan <strong>{{ $customer->name }}</strong>?</p>
                                                    <div class="alert alert-danger">
                                                        <i class="fas fa-exclamation-triangle"></i> 
                                                        <strong>Peringatan:</strong> Semua invoice terkait pelanggan ini juga akan dihapus!
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                        <i class="fas fa-times"></i> Batal
                                                    </button>
                                                    <form action="{{ route('superadmin.customers.destroy', $customer) }}" method="POST" class="d-inline">
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
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="help-modal" tabindex="-1" role="dialog" aria-labelledby="help-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="help-modal-label">
                        <i class="fas fa-question-circle mr-2"></i>Panduan Kelola Pelanggan
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-eye text-info"></i> Melihat Detail</h6>
                            <p class="text-muted">Klik tombol "Detail" untuk melihat informasi lengkap pelanggan termasuk riwayat invoice.</p>
                            
                            <h6><i class="fas fa-edit text-warning"></i> Mengedit Pelanggan</h6>
                            <p class="text-muted">Klik tombol "Edit" untuk melihat informasi pelanggan dan akses ke halaman detail lengkap.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-trash text-danger"></i> Menghapus Pelanggan</h6>
                            <p class="text-muted">Klik tombol "Hapus" untuk menghapus pelanggan. Semua invoice terkait juga akan dihapus.</p>
                            
                            <h6><i class="fas fa-search text-primary"></i> Mencari & Filter</h6>
                            <p class="text-muted">Gunakan fitur pencarian dan filter untuk menemukan pelanggan tertentu dengan cepat.</p>
                        </div>
                    </div>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-lightbulb"></i> 
                        <strong>Tips:</strong> Pelanggan dapat ditambahkan oleh teknisi. Pastikan data pelanggan selalu akurat dan terupdate.
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
            background-color: rgba(23,162,184,0.1);
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
            $('#customers-table').DataTable({
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