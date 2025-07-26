@extends('adminlte::page')

@section('title', 'Laporan Keuangan Mitra')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-1">
                <i class="fas fa-users mr-2"></i>Laporan Keuangan Mitra
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> Monitoring keuangan mitra dan distribusi fee
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('superadmin.financial.report.print', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-primary" target="_blank">
                <i class="fas fa-print"></i> 
                <span class="d-none d-sm-inline">Cetak Laporan</span>
                <span class="d-inline d-sm-none">Cetak</span>
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
                        <p class="mb-0">Halaman ini berisi laporan keuangan mitra yang dikelola oleh admin. Anda dapat melihat detail fee dan status pembayaran setiap mitra.</p>
            </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body p-3">
                                    <h6 class="card-title"><i class="fas fa-print text-primary mr-2"></i>Pencetakan Laporan</h6>
                                    <p class="card-text small">Laporan yang dicetak berisi informasi keuangan mitra. Pastikan laporan hanya digunakan untuk keperluan internal perusahaan.</p>
                </div>
            </div>
        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body p-3">
                                    <h6 class="card-title"><i class="fas fa-money-bill-wave text-success mr-2"></i>Informasi Fee</h6>
                                    <p class="card-text small">Sebagai superadmin, Anda hanya dapat melihat informasi fee mitra. Untuk mengubah status pembayaran, silakan hubungi admin terkait.</p>
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

    <!-- Filter Form -->
    <div class="card card-outline card-primary mb-4">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-filter mr-1"></i>Filter Laporan
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('superadmin.financial.report') }}" method="GET" class="row">
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

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="payment_status" class="font-weight-bold">
                            <i class="fas fa-money-bill-wave text-primary mr-1"></i>Status Pembayaran
                        </label>
                        <select class="form-control" id="payment_status" name="payment_status">
                            <option value="">Semua Status</option>
                            <option value="paid" {{ $paymentStatus === 'paid' ? 'selected' : '' }}>Lunas</option>
                            <option value="unpaid" {{ $paymentStatus === 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter mr-1"></i> Filter Laporan
                        </button>
                        <button type="button" class="btn btn-secondary ml-2" id="reset-filter">
                            <i class="fas fa-undo mr-1"></i> Reset Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-primary">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Mitra</span>
                    <span class="info-box-number">{{ count($technicianData ?? []) }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-success">
                <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Fee Mitra</span>
                    <span class="info-box-number">Rp {{ number_format($totalFee ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-danger">
                <span class="info-box-icon"><i class="fas fa-building"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Fee PT</span>
                    <span class="info-box-number">Rp {{ number_format($totalPtFee ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-info">
                <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Revenue</span>
                    <span class="info-box-number">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Detail Mitra Table -->
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table mr-1"></i>Detail Laporan Mitra
            </h3>
            <div class="card-tools">
                <span class="badge badge-primary mr-2">
                    <i class="fas fa-list"></i> {{ count($technicianData ?? []) }} Mitra
                </span>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            @if(count($technicianData ?? []) > 0)
                            <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="partners-table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Nama Mitra</th>
                                <th width="10%">Pelanggan</th>
                                <th width="10%">Invoice</th>
                                <th width="15%">Total Revenue</th>
                                <th width="15%">Fee Mitra</th>
                                <th width="15%">Fee PT</th>
                                <th width="10%">Status Cetak</th>
                                <th width="10%">Status Bayar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            @foreach ($technicianData ?? [] as $index => $technician)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-placeholder bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mr-2" style="width: 32px; height: 32px; font-size: 0.875rem;">
                                                {{ substr($technician['name'], 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-weight-bold">{{ $technician['name'] }}</div>
                                                <div class="small text-muted">{{ $technician['email'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $technician['customer_count'] }}</td>
                                    <td class="text-center">{{ $technician['invoice_count'] }}</td>
                                    <td class="text-right">Rp {{ number_format($technician['total_revenue'], 0, ',', '.') }}</td>
                                    <td class="text-right">Rp {{ number_format($technician['fee_amount'], 0, ',', '.') }}</td>
                                    <td class="text-right">Rp {{ number_format($technician['pt_fee'], 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if ($technician['is_printed'])
                                            <span class="badge badge-success"><i class="fas fa-check-circle"></i> Tercetak</span>
                                        @else
                                            <span class="badge badge-secondary"><i class="fas fa-times-circle"></i> Belum</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($technician['is_paid'])
                                            <span class="badge badge-success"><i class="fas fa-check-circle"></i> Lunas</span>
                                        @else
                                            <span class="badge badge-warning"><i class="fas fa-clock"></i> Belum</span>
                                        @endif
                                    </td>
                                        </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-light font-weight-bold">
                                <td colspan="4" class="text-right">Total:</td>
                                <td class="text-right">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($totalFee ?? 0, 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($totalPtFee ?? 0, 0, ',', '.') }}</td>
                                <td colspan="2"></td>
                                        </tr>
                        </tfoot>
                                </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>Tidak ada data mitra yang tersedia untuk periode ini.
                </div>
            @endif
                            </div>
                        </div>

    <!-- Chart Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>Distribusi Fee Mitra
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                        </div>
                        <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="feeChart"></canvas>
                    </div>
            </div>
        </div>
    </div>

        <div class="col-md-6">
            <div class="card card-outline card-success">
        <div class="card-header">
            <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i>Performa Mitra
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="performanceChart"></canvas>
                                        </div>
                                    </div>
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="help-modal" tabindex="-1" role="dialog" aria-labelledby="help-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="help-modal-label">
                        <i class="fas fa-question-circle mr-2"></i>Panduan Laporan Keuangan Mitra
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Informasi Penting:</strong> Halaman ini menampilkan laporan keuangan mitra yang dikelola oleh admin. Sebagai superadmin, Anda hanya dapat melihat informasi ini tanpa mengubahnya.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-filter text-primary mr-2"></i>Filter Laporan</h6>
                            <p class="text-muted">Pilih periode tanggal untuk melihat laporan keuangan mitra dalam rentang waktu tertentu.</p>
                            
                            <h6><i class="fas fa-chart-pie text-info mr-2"></i>Grafik Distribusi</h6>
                            <p class="text-muted">Visualisasi distribusi fee mitra untuk melihat perbandingan antar mitra.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-table text-success mr-2"></i>Tabel Detail</h6>
                            <p class="text-muted">Lihat detail jumlah pelanggan, invoice, revenue, dan fee untuk setiap mitra.</p>
                            
                            <h6><i class="fas fa-print text-warning mr-2"></i>Cetak Laporan</h6>
                            <p class="text-muted">Cetak laporan keuangan mitra dalam format yang profesional untuk dokumentasi.</p>
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

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
    <style>
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
        
        /* Partner Avatar */
        .avatar-placeholder {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }
        
        /* Gap utility */
        .gap-1 {
            gap: 0.25rem;
        }
        
        .gap-2 {
            gap: 0.5rem;
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
                if (localStorage.getItem('superadminFinancialWelcomeModalShown') !== 'true') {
                    $('#welcomeModal').modal('show');
                }
            }
            
            // Tampilkan modal saat halaman dimuat
            checkWelcomeModal();
            
            // Tangani checkbox "Jangan tampilkan lagi"
            $('#dontShowAgain').on('change', function() {
                if ($(this).is(':checked')) {
                    localStorage.setItem('superadminFinancialWelcomeModalShown', 'true');
                } else {
                    localStorage.removeItem('superadminFinancialWelcomeModalShown');
                }
            });
            
            // Reset filter
            $('#reset-filter').on('click', function() {
                window.location.href = '{{ route("superadmin.financial.report") }}';
            });
            
            // DataTable untuk tabel mitra
            $('#partners-table').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: '{{ asset("vendor/datatables/lang/Indonesian.json") }}'
                },
                order: [[0, "asc"]]
            });
            
            // Chart untuk distribusi fee mitra
            if ($('#feeChart').length > 0) {
                const technicianNames = @json($technicianData ? collect($technicianData)->pluck('name') : []);
                const feeAmounts = @json($technicianData ? collect($technicianData)->pluck('fee_amount') : []);
                
                // Jika tidak ada data, tampilkan pesan
                if (technicianNames.length === 0) {
                    $('#feeChart').parent().html('<div class="text-center text-muted p-5"><i class="fas fa-info-circle mr-2"></i>Tidak ada data untuk ditampilkan</div>');
                } else {
                    const ctx = document.getElementById('feeChart').getContext('2d');
                    const feeChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: technicianNames,
                            datasets: [{
                                data: feeAmounts,
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.8)',
                                    'rgba(255, 99, 132, 0.8)',
                                    'rgba(255, 206, 86, 0.8)',
                                    'rgba(75, 192, 192, 0.8)',
                                    'rgba(153, 102, 255, 0.8)',
                                    'rgba(255, 159, 64, 0.8)',
                                    'rgba(199, 199, 199, 0.8)',
                                    'rgba(83, 102, 255, 0.8)',
                                    'rgba(40, 159, 64, 0.8)',
                                    'rgba(210, 199, 199, 0.8)'
                                ],
                                borderColor: [
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)',
                                    'rgba(199, 199, 199, 1)',
                                    'rgba(83, 102, 255, 1)',
                                    'rgba(40, 159, 64, 1)',
                                    'rgba(210, 199, 199, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                    labels: {
                                        boxWidth: 15,
                                        padding: 15
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            if (context.parsed !== null) {
                                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed);
                                            }
                                            return label;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            }
            
            // Chart untuk performa mitra
            if ($('#performanceChart').length > 0) {
                const technicianNames = @json($technicianData ? collect($technicianData)->pluck('name') : []);
                const customerCounts = @json($technicianData ? collect($technicianData)->pluck('customer_count') : []);
                const invoiceCounts = @json($technicianData ? collect($technicianData)->pluck('invoice_count') : []);
                
                // Jika tidak ada data, tampilkan pesan
                if (technicianNames.length === 0) {
                    $('#performanceChart').parent().html('<div class="text-center text-muted p-5"><i class="fas fa-info-circle mr-2"></i>Tidak ada data untuk ditampilkan</div>');
                } else {
                    const ctx = document.getElementById('performanceChart').getContext('2d');
                    const performanceChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: technicianNames,
                            datasets: [
                                {
                                    label: 'Jumlah Pelanggan',
                                    data: customerCounts,
                                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Jumlah Invoice',
                                    data: invoiceCounts,
                                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        }
                    });
                }
            }
            
            // Add hover effects to info boxes
            $('.info-box').hover(
                function() {
                    $(this).addClass('shadow-lg');
                },
                function() {
                    $(this).removeClass('shadow-lg');
                }
            );
        });
    </script>
@stop 