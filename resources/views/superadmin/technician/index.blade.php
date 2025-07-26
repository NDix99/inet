@extends('adminlte::page')

@section('title', 'Daftar Teknisi')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-1">
                <i class="fas fa-user-cog mr-2"></i>Daftar Teknisi
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> Kelola teknisi yang bertanggung jawab input data pelanggan dan layanan teknis
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('superadmin.technicians.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> 
                <span class="d-none d-sm-inline">Tambah Teknisi</span>
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
            <div class="info-box bg-gradient-success">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Teknisi</span>
                    <span class="info-box-number">{{ $technicians->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-primary">
                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Teknisi Aktif</span>
                    <span class="info-box-number">{{ $technicians->where('is_active', true)->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-warning">
                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Teknisi Nonaktif</span>
                    <span class="info-box-number">{{ $technicians->where('is_active', false)->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-danger">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Customer</span>
                    <span class="info-box-number">{{ $technicians->sum('customers_count') }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-info">
                <span class="info-box-icon"><i class="fas fa-calendar-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Terdaftar Bulan Ini</span>
                    <span class="info-box-number">{{ $technicians->where('created_at', '>=', now()->startOfMonth())->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-success">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table mr-1"></i>Data Teknisi
            </h3>
            <div class="card-tools">
                <span class="badge badge-success mr-2">
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
                <table class="table table-bordered table-striped table-hover" id="technicians-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Jumlah Customer</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($technicians as $index => $technician)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $technician->name }}</td>
                                <td>{{ $technician->email }}</td>
                                <td>{{ $technician->phone ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        <i class="fas fa-users mr-1"></i> {{ $technician->customers_count }}
                                    </span>
                                </td>
                                <td>
                                    @if ($technician->is_active)
                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Aktif</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column flex-sm-row gap-1">
                                        <a href="{{ route('superadmin.technicians.edit', $technician->id) }}" class="btn btn-sm btn-warning" title="Edit Teknisi">
                                            <i class="fas fa-edit"></i> 
                                            <span class="d-none d-sm-inline">Edit</span>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-info" title="Detail Teknisi" data-toggle="modal" data-target="#detail-modal-{{ $technician->id }}">
                                            <i class="fas fa-eye"></i> 
                                            <span class="d-none d-sm-inline">Detail</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" title="Hapus Teknisi" data-toggle="modal" data-target="#delete-modal-{{ $technician->id }}">
                                            <i class="fas fa-trash"></i> 
                                            <span class="d-none d-sm-inline">Hapus</span>
                                        </button>
                                    </div>

                                    <!-- Modal Detail -->
                                    <div class="modal fade" id="detail-modal-{{ $technician->id }}" tabindex="-1" role="dialog" aria-labelledby="detail-modal-label" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info">
                                                    <h5 class="modal-title text-white" id="detail-modal-label">
                                                        <i class="fas fa-user-cog mr-2"></i>Detail Teknisi
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Nama:</label>
                                                                <p class="form-control-static">{{ $technician->name }}</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Email:</label>
                                                                <p class="form-control-static">{{ $technician->email }}</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Telepon:</label>
                                                                <p class="form-control-static">{{ $technician->phone ?? 'Tidak ada' }}</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Jumlah Customer:</label>
                                                                <p class="form-control-static">
                                                                    <span class="badge badge-info">
                                                                        <i class="fas fa-users mr-1"></i> {{ $technician->customers_count }}
                                                                    </span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Status:</label>
                                                                <p>
                                                                    @if ($technician->is_active)
                                                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Aktif</span>
                                                                    @else
                                                                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Tidak Aktif</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Terdaftar:</label>
                                                                <p class="form-control-static">{{ $technician->created_at->format('d/m/Y H:i') }}</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Terakhir Update:</label>
                                                                <p class="form-control-static">{{ $technician->updated_at->format('d/m/Y H:i') }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                    <a href="{{ route('superadmin.technicians.edit', $technician->id) }}" class="btn btn-warning">
                                                        <i class="fas fa-edit"></i> Edit Teknisi
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Hapus -->
                                    <div class="modal fade" id="delete-modal-{{ $technician->id }}" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger">
                                                    <h5 class="modal-title text-white" id="delete-modal-label">
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
                                                    <p class="text-center">Apakah Anda yakin ingin menghapus teknisi <strong>{{ $technician->name }}</strong>?</p>
                                                    <div class="alert alert-warning">
                                                        <i class="fas fa-info-circle"></i> 
                                                        <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait teknisi ini.
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                        <i class="fas fa-times"></i> Batal
                                                    </button>
                                                    <form action="{{ route('superadmin.technicians.destroy', $technician->id) }}" method="POST" class="d-inline">
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
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white" id="help-modal-label">
                        <i class="fas fa-question-circle mr-2"></i>Panduan Kelola Teknisi
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-plus text-success"></i> Menambah Teknisi</h6>
                            <p class="text-muted">Klik tombol "Tambah Teknisi" untuk membuat akun teknisi baru. Pastikan data yang diisi lengkap dan valid.</p>
                            
                            <h6><i class="fas fa-edit text-warning"></i> Mengedit Teknisi</h6>
                            <p class="text-muted">Klik tombol "Edit" untuk mengubah data teknisi. Password bisa dikosongkan jika tidak ingin mengubah.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-eye text-info"></i> Melihat Detail</h6>
                            <p class="text-muted">Klik tombol "Detail" untuk melihat informasi lengkap teknisi termasuk tanggal registrasi.</p>
                            
                            <h6><i class="fas fa-trash text-danger"></i> Menghapus Teknisi</h6>
                            <p class="text-muted">Klik tombol "Hapus" untuk menghapus teknisi. Tindakan ini tidak dapat dibatalkan.</p>
                        </div>
                    </div>
                    <div class="alert alert-success mt-3">
                        <i class="fas fa-lightbulb"></i> 
                        <strong>Tips:</strong> Teknisi bertanggung jawab untuk input data pelanggan dan memberikan layanan teknis. Pastikan teknisi yang aktif memiliki kemampuan yang memadai.
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
            background-color: rgba(40,167,69,0.1);
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
            $('#technicians-table').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: '{{ asset("vendor/datatables/lang/Indonesian.json") }}'
                },
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