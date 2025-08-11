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
            <a href="{{ route('admin.technicians.create') }}" class="btn btn-success">
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
                    <span class="info-box-number">{{ $totalTechnicians }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-primary">
                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Teknisi Aktif</span>
                    <span class="info-box-number">{{ $activeTechnicians }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-warning">
                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Teknisi Nonaktif</span>
                    <span class="info-box-number">{{ $inactiveTechnicians }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-info">
                <span class="info-box-icon"><i class="fas fa-calendar-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Terdaftar Bulan Ini</span>
                    <span class="info-box-number">{{ $registeredThisMonth }}</span>
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

    <!-- Technician Table -->
    <div class="card card-outline card-success">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table mr-1"></i>Data Teknisi
            </h3>
            <div class="card-tools">
                <span class="badge badge-success mr-2">
                    <i class="fas fa-list"></i> {{ $totalTechnicians }} Teknisi
                </span>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="technicians-table" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Status</th>
                            <th>Terdaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($technicians as $technician)
                            <tr>
                                <td>
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-hashtag"></i> {{ $technician->id }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-placeholder bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mr-2" style="width: 35px; height: 35px; font-size: 0.875rem;">
                                            <i class="fas fa-user-cog"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-success">{{ $technician->name }}</div>
                                            <small class="text-muted">
                                                <i class="fas fa-user-tag"></i> Teknisi
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-envelope text-muted mr-2"></i>
                                        <span>{{ $technician->email }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-phone text-muted mr-2"></i>
                                        <span>{{ $technician->phone ?? 'Tidak ada' }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($technician->is_active)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i> Aktif
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times-circle"></i> Tidak Aktif
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar-alt text-muted mr-2"></i>
                                        <span>{{ $technician->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailModal{{ $technician->id }}" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('admin.technicians.edit', $technician) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal{{ $technician->id }}" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Modal Detail -->
                                    <div class="modal fade" id="detailModal{{ $technician->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel{{ $technician->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info">
                                                    <h5 class="modal-title text-white" id="detailModalLabel{{ $technician->id }}">
                                                        <i class="fas fa-user-cog mr-2"></i>Detail Teknisi
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
                                                                        <i class="fas fa-user"></i>
                                                                    </div>
                                                                    <div>
                                                                        <small class="text-muted">Nama Lengkap</small>
                                                                        <div class="font-weight-bold">{{ $technician->name }}</div>
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
                                                                        <div class="font-weight-bold">{{ $technician->email }}</div>
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
                                                                        <div class="font-weight-bold">{{ $technician->phone ?? 'Tidak ada' }}</div>
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
                                                                            @if($technician->is_active)
                                                                                <span class="badge badge-success">
                                                                                    <i class="fas fa-check-circle"></i> Aktif
                                                                                </span>
                                                                            @else
                                                                                <span class="badge badge-danger">
                                                                                    <i class="fas fa-times-circle"></i> Tidak Aktif
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
                                                                        <small class="text-muted">Terdaftar</small>
                                                                        <div class="font-weight-bold">{{ $technician->created_at->format('d/m/Y H:i') }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="info-item mb-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="icon-wrapper bg-dark text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                                                        <i class="fas fa-edit"></i>
                                                                    </div>
                                                                    <div>
                                                                        <small class="text-muted">Terakhir Update</small>
                                                                        <div class="font-weight-bold">{{ $technician->updated_at->format('d/m/Y H:i') }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                        <i class="fas fa-times"></i> Tutup
                                                    </button>
                                                    <a href="{{ route('admin.technicians.edit', $technician) }}" class="btn btn-warning">
                                                        <i class="fas fa-edit"></i> Edit Teknisi
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Konfirmasi Hapus -->
                                    <div class="modal fade" id="deleteModal{{ $technician->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $technician->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger">
                                                    <h5 class="modal-title text-white" id="deleteModalLabel{{ $technician->id }}">
                                                        <i class="fas fa-exclamation-triangle mr-2"></i>Konfirmasi Hapus
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="alert alert-warning">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                        <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!
                                                    </div>
                                                    <p>Apakah Anda yakin ingin menghapus teknisi <strong>{{ $technician->name }}</strong>?</p>
                                                    <ul class="text-muted">
                                                        <li>Akun teknisi akan dihapus permanen</li>
                                                        <li>Data pelanggan yang dibuat teknisi tetap tersimpan</li>
                                                        <li>Tindakan ini tidak dapat dibatalkan</li>
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                        <i class="fas fa-times"></i> Batal
                                                    </button>
                                                    <form action="{{ route('admin.technicians.destroy', $technician) }}" method="POST" class="d-inline">
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

            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">
                    Menampilkan {{ $technicians->firstItem() ?? 0 }}–{{ $technicians->lastItem() ?? 0 }} dari {{ $technicians->total() }} teknisi
                </small>
                {{ $technicians->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="help-modal" tabindex="-1" role="dialog" aria-labelledby="help-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
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
                            <h6><i class="fas fa-plus text-success"></i> Tambah Teknisi</h6>
                            <p class="text-muted">Klik tombol "Tambah Teknisi" untuk menambahkan teknisi baru ke sistem.</p>
                            
                            <h6><i class="fas fa-eye text-info"></i> Lihat Detail</h6>
                            <p class="text-muted">Klik ikon mata untuk melihat informasi lengkap teknisi.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-edit text-warning"></i> Edit Teknisi</h6>
                            <p class="text-muted">Klik ikon edit untuk mengubah informasi teknisi.</p>
                            
                            <h6><i class="fas fa-trash text-danger"></i> Hapus Teknisi</h6>
                            <p class="text-muted">Klik ikon hapus untuk menghapus teknisi dari sistem (permanen).</p>
                        </div>
                    </div>
                    <div class="alert alert-primary mt-3">
                        <i class="fas fa-lightbulb"></i> 
                        <strong>Tips:</strong> Pastikan data teknisi selalu akurat dan up-to-date untuk kelancaran operasional.
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
        
        /* Avatar Placeholder */
        .avatar-placeholder {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        /* Icon Wrapper */
        .icon-wrapper {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
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
            $('#technicians-table').DataTable({
                responsive: true,
                autoWidth: false,
                paging: false,          // matikan pagination DataTables
                info: false,            // matikan text "Menampilkan x sampai y"
                lengthChange: false,    // matikan dropdown jumlah baris
                language: { url: '{{ asset("vendor/datatables/lang/Indonesian.json") }}' },
                order: [[1, "asc"]],
                dom: '<"row"<"col-sm-12 col-md-6"f>>' +   // hilangkan 'l'
                     '<"row"<"col-sm-12"tr>>'             // hilangkan 'i' dan 'p'
                // Jika tombol export dipakai, tambahkan 'B' ke dom dan pastikan assets Buttons ter-load.
                // buttons: [ ... ] // opsional
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