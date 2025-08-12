@extends('adminlte::page')

@section('title', 'Daftar Pelanggan')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-1">
                <i class="fas fa-users mr-2"></i>Daftar Pelanggan
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> Kelola data pelanggan dan informasi paket internet
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.customers.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i>
                <span class="d-none d-sm-inline">Tambah Pelanggan</span>
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
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Pelanggan</span>
                    <span class="info-box-number">{{ $totalCustomers }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-success">
                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pelanggan Aktif</span>
                    <span class="info-box-number">{{ $activeCustomers }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-warning">
                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pelanggan Non-Aktif</span>
                    <span class="info-box-number">{{ $inactiveCustomers }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-info">
                <span class="info-box-icon"><i class="fas fa-user-plus"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Baru Bulan Ini</span>
                    <span class="info-box-number">{{ $newCustomersThisMonth }}</span>
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

    <!-- Customer Table -->
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table mr-1"></i>Data Pelanggan
            </h3>
            <div class="card-tools">
                <span class="badge badge-primary mr-2">
                    <i class="fas fa-list"></i> {{ $totalCustomers }} Pelanggan
                </span>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        
        <div class="card-body">
            <!-- Filter dan Export Section -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <label for="teknisi-filter" class="mb-0 mr-2">Filter Teknisi:</label>
                        <select id="teknisi-filter" class="form-control form-control-sm w-auto">
                            <option value="">Semua</option>
                            @foreach($allTechnicians as $technician)
                                <option value="{{ $technician->name }}">{{ $technician->name }}</option>
                            @endforeach
                        </select>
                        
                        <label for="status-filter" class="mb-0 mr-2 ml-3">Filter Status:</label>
                        <select id="status-filter" class="form-control form-control-sm w-auto">
                            <option value="">Semua</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 d-flex justify-content-end">
                    <button type="button" class="btn btn-success btn-sm" id="export-excel-btn">
                        <i class="fas fa-file-excel mr-1"></i>
                        Export Excel
                    </button>
                </div>
            </div>

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
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                            <th class="d-none">Teknisi Filter</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-hashtag"></i> {{ $customer->id }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-placeholder bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mr-2" style="width: 35px; height: 35px; font-size: 0.875rem;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-primary">{{ $customer->name }}</div>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-alt"></i> {{ $customer->created_at->format('d/m/Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-envelope text-muted mr-2"></i>
                                        <span>{{ $customer->email }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-phone text-muted mr-2"></i>
                                        <span>{{ $customer->phone }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($customer->package)
                                        <span class="badge badge-primary">
                                            <i class="fas fa-box"></i> {{ $customer->package->name }}
                                        </span>
                                        <br>
                                        <small class="text-muted">Rp {{ number_format($customer->package->price, 0, ',', '.') }}</small>
                                    @else
                                        <span class="badge badge-warning">
                                            <i class="fas fa-exclamation-triangle"></i> Tidak ada paket
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($customer->is_active)
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
                                    @if($customer->creator)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-placeholder bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mr-2" style="width: 25px; height: 25px; font-size: 0.625rem;">
                                                <i class="fas fa-user-cog"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold teknisi-name">{{ $customer->creator->name }}</div>
                                                <small class="text-muted">{{ ucfirst($customer->creator->role->name ?? 'unknown') }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-question-circle"></i> Tidak diketahui
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal{{ $customer->id }}" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Modal Konfirmasi Hapus -->
                                    <div class="modal fade" id="deleteModal{{ $customer->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $customer->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $customer->id }}">
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
                                                    <p>Apakah Anda yakin ingin menghapus pelanggan <strong>{{ $customer->name }}</strong>?</p>
                                                    <ul class="text-muted">
                                                        <li>Semua data pelanggan akan dihapus permanen</li>
                                                        <li>Riwayat invoice akan tetap tersimpan</li>
                                                        <li>Tindakan ini tidak dapat dibatalkan</li>
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                        <i class="fas fa-times"></i> Batal
                                                    </button>
                                                    <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline">
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
                                <td class="d-none">{{ $customer->creator->name ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Tambahkan pagination di bawah tabel --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $customers->links() }}
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="help-modal" tabindex="-1" role="dialog" aria-labelledby="help-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
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
                            <h6><i class="fas fa-plus text-success"></i> Tambah Pelanggan</h6>
                            <p class="text-muted">Klik tombol "Tambah Pelanggan" untuk menambahkan pelanggan baru ke sistem.</p>

                            <h6><i class="fas fa-eye text-info"></i> Lihat Detail</h6>
                            <p class="text-muted">Klik ikon mata untuk melihat informasi lengkap pelanggan dan riwayat invoice.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-edit text-warning"></i> Edit Pelanggan</h6>
                            <p class="text-muted">Klik ikon edit untuk mengubah informasi pelanggan dan paket yang dipilih.</p>

                            <h6><i class="fas fa-trash text-danger"></i> Hapus Pelanggan</h6>
                            <p class="text-muted">Klik ikon hapus untuk menghapus pelanggan dari sistem (permanen).</p>
                        </div>
                    </div>
                    <div class="alert alert-primary mt-3">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Tips:</strong> Pastikan data pelanggan selalu akurat dan up-to-date untuk kelancaran operasional.
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

        /* Avatar Placeholder */
        .avatar-placeholder {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
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

        /* Table responsive improvements - PERBAIKAN UTAMA */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Hapus white-space nowrap untuk mobile */
        @media (max-width: 768px) {
            .table th, .table td {
                white-space: normal;
                min-width: 120px;
            }
            
            /* Kolom tertentu bisa lebih kecil */
            .table th:first-child, .table td:first-child {
                min-width: 80px;
            }
            
            .table th:last-child, .table td:last-child {
                min-width: 100px;
            }
            
            /* Kolom aksi lebih kecil di mobile */
            .btn-group .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }

        /* Desktop tetap menggunakan nowrap */
        @media (min-width: 769px) {
            .table th, .table td {
                white-space: nowrap;
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

        /* Mobile-specific table improvements */
        @media (max-width: 576px) {
            .table-responsive {
                border: none;
            }
            
            .table {
                font-size: 0.875rem;
            }
            
            .badge {
                font-size: 0.75rem;
            }
            
            .btn-sm {
                padding: 0.25rem 0.4rem;
                font-size: 0.7rem;
            }
            
            /* Sembunyikan beberapa elemen di mobile */
            .d-mobile-none {
                display: none !important;
            }
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            var table = $('#customers-table').DataTable({
                responsive: true,
                autoWidth: false,
                scrollX: true,
                scrollCollapse: true,
                paging: false,         // nonaktifkan pagination DataTables
                info: false,           // nonaktifkan teks "Menampilkan ..."
                lengthChange: false,   // nonaktifkan dropdown jumlah baris
                language: { url: '{{ asset("vendor/datatables/lang/Indonesian.json") }}' },
                order: [[1, "asc"]],
                dom: '<"row"<"col-sm-12 col-md-6"f>>' +   // hilangkan 'l'
                     '<"row"<"col-sm-12"tr>>'             // hilangkan 'i' & 'p'
                // buttons: [ ... ] // tetap jika diperlukan
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

            // Filter berdasarkan teknisi
            $('#teknisi-filter').on('change', function() {
                var val = $(this).val();
                if (val === "") {
                    table.column(8).search('').draw(); // Kolom ke-9 (kolom tersembunyi)
                } else {
                    table.column(8).search('^' + val + '$', true, false).draw();
                }
            });

            // Filter berdasarkan status
            $('#status-filter').on('change', function() {
                var val = $(this).val();
                if (val === "") {
                    table.column(5).search('').draw(); // Kolom Status (index 5)
                } else if (val === "active") {
                    table.column(5).search('Aktif').draw();
                } else if (val === "inactive") {
                    table.column(5).search('Tidak Aktif').draw();
                }
            });

            // Mobile-specific improvements
            function handleMobileView() {
                if (window.innerWidth <= 768) {
                    // Enable horizontal scroll for table
                    $('.table-responsive').css('overflow-x', 'auto');
                    
                    // Adjust table column widths for mobile
                    $('.table th, .table td').css('min-width', '120px');
                    $('.table th:first-child, .table td:first-child').css('min-width', '80px');
                    $('.table th:last-child, .table td:last-child').css('min-width', '100px');
                }
            }

            // Call on load and resize
            handleMobileView();
            $(window).resize(handleMobileView);

            // Handle export button
            $('#export-excel-btn').on('click', function() {
                var technicianFilter = $('#teknisi-filter').val();
                var statusFilter = $('#status-filter').val();

                var url = '{{ route("admin.customers.export") }}';
                var params = {
                    technician: technicianFilter,
                    status: statusFilter
                };

                window.location.href = url + '?' + $.param(params);
            });

            // Export Excel functionality
            $('#export-excel-btn').on('click', function() {
                var teknisiFilter = $('#teknisi-filter').val();
                var statusFilter = $('#status-filter').val();
                
                // Show loading state
                var $btn = $(this);
                var originalText = $btn.html();
                $btn.html('<i class="fas fa-spinner fa-spin mr-1"></i>Exporting...');
                $btn.prop('disabled', true);
                
                // Build export URL with filters
                var exportUrl = '{{ route("admin.customers.export") }}?';
                var params = [];
                
                if (teknisiFilter) {
                    params.push('technician=' + encodeURIComponent(teknisiFilter));
                }
                
                if (statusFilter) {
                    params.push('status=' + encodeURIComponent(statusFilter));
                }
                
                exportUrl += params.join('&');
                
                // Create temporary form to submit export request
                var $form = $('<form>', {
                    'method': 'POST',
                    'action': exportUrl
                });
                
                $form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': '{{ csrf_token() }}'
                }));
                
                $('body').append($form);
                $form.submit();
                $form.remove();
                
                // Reset button state after a delay
                setTimeout(function() {
                    $btn.html(originalText);
                    $btn.prop('disabled', false);
                }, 2000);
            });
        });
    </script>
@stop
