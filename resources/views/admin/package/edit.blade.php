@extends('adminlte::page')

@section('title', 'Pengaturan Fee Teknisi - ' . $package->name)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>
            <i class="fas fa-percentage text-warning"></i>
            Pengaturan Fee Teknisi - {{ $package->name }}
        </h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.packages.index') }}"><i class="fas fa-boxes"></i> Paket Internet</a></li>
            <li class="breadcrumb-item active"><i class="fas fa-percentage"></i> Pengaturan Fee Teknisi</li>
        </ol>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-1"></i>Form Edit Harga Paket
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-warning mr-2">
                            <i class="fas fa-money-bill"></i> Edit Harga
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <div>
                                    <strong>Error:</strong> Terdapat kesalahan pada form
                                    <ul class="mb-0 mt-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('admin.packages.update.price', $package) }}" method="POST" id="price-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">
                                        <i class="fas fa-box text-primary mr-1"></i>Nama Paket
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-box"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="name" value="{{ $package->name }}" readonly disabled>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-lock"></i> Nama paket tidak dapat diubah oleh Admin
                                    </small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type" class="font-weight-bold">
                                        <i class="fas fa-tag text-primary mr-1"></i>Tipe Paket
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-tag"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="type" value="{{ ucfirst($package->type) }}" readonly disabled>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-lock"></i> Tipe paket tidak dapat diubah oleh Admin
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="price" class="font-weight-bold">
                                        <i class="fas fa-money-bill-wave text-primary mr-1"></i>Harga Paket (Termasuk PPN 11%)
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-money-bill-wave"></i>
                                            </span>
                                        </div>
                                        <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $package->price) }}" readonly disabled>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-lock"></i> Harga paket tidak dapat diubah oleh Admin
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Perhitungan PPN Otomatis -->
                        <div class="card bg-light mb-3">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-calculator mr-1"></i>Perhitungan PPN</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Harga Dasar</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" class="form-control" id="price-before-tax" value="{{ number_format($package->base_price, 0, ',', '.') }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>PPN (11%)</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" class="form-control" id="tax-amount" value="{{ number_format($package->tax_amount, 0, ',', '.') }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Total Harga</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" class="form-control" id="total-price" value="{{ number_format($package->price, 0, ',', '.') }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Fee Teknisi (Baru) -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="technician_fee_percentage" class="font-weight-bold">
                                        <i class="fas fa-percentage text-warning mr-1"></i>Persentase Fee Teknisi <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-percentage"></i>
                                            </span>
                                        </div>
                                        <input type="number" class="form-control" id="technician_fee_percentage" name="technician_fee_percentage" value="{{ old('technician_fee_percentage', $package->technician_fee_percentage ?? 0) }}" min="0" max="100" step="1" placeholder="Masukkan persentase fee teknisi" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Persentase fee untuk teknisi (diambil dari harga dasar)
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card bg-light mb-3">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0"><i class="fas fa-calculator mr-1"></i>Perhitungan Fee Teknisi</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Persentase</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="fee-percentage-display" value="{{ old('technician_fee_percentage', $package->technician_fee_percentage ?? 0) }}" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jumlah Fee Teknisi</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" class="form-control" id="technician-fee-amount" value="{{ number_format($package->technician_fee_amount ?? 0, 0, ',', '.') }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description" class="font-weight-bold">
                                        <i class="fas fa-align-left text-primary mr-1"></i>Deskripsi Paket
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-align-left"></i>
                                            </span>
                                        </div>
                                        <textarea class="form-control" id="description" rows="3" readonly disabled>{{ $package->description }}</textarea>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-lock"></i> Deskripsi paket tidak dapat diubah oleh Admin
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-3">
                            <div class="d-flex">
                                <div class="mr-3">
                                    <i class="fas fa-info-circle fa-2x"></i>
                                </div>
                                <div>
                                    <h5>Informasi Penting:</h5>
                                    <ul class="mb-0">
                                        <li>Admin hanya dapat mengubah fee teknisi, tidak dapat mengubah harga paket</li>
                                        <li>Fee teknisi diambil dari harga dasar (harga sebelum PPN)</li>
                                        <li>Fee teknisi akan disimpan sebagai referensi untuk invoice berikutnya</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="fas fa-save mr-2"></i>Simpan Fee Teknisi
                            </button>
                            <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary btn-lg ml-2">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-1"></i>Informasi Paket
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-info mr-2">
                            <i class="fas fa-box"></i> Info
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="package-icon bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                            <i class="fas fa-box"></i>
                        </div>
                        <h5 class="mb-1">{{ $package->name }}</h5>
                        <span class="badge badge-{{ $package->type == 'internet' ? 'info' : 'warning' }} badge-lg">
                            <i class="fas fa-{{ $package->type == 'internet' ? 'wifi' : 'broadcast-tower' }}"></i> {{ ucfirst($package->type) }}
                        </span>
                    </div>
                    
                    <div class="package-details">
                        <div class="detail-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    <i class="fas fa-money-bill-wave mr-2"></i>Harga Saat Ini
                                </span>
                                <span class="font-weight-bold text-success">
                                    Rp {{ number_format($package->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="detail-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    <i class="fas fa-users mr-2"></i>Jumlah Pelanggan
                                </span>
                                <span class="font-weight-bold text-primary">
                                    {{ $package->customers()->count() }} pelanggan
                                </span>
                            </div>
                        </div>
                        
                        <div class="detail-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    <i class="fas fa-toggle-on mr-2"></i>Status
                                </span>
                                <span>
                                    @if($package->is_active)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i> Aktif
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times-circle"></i> Tidak Aktif
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lightbulb mr-1"></i>Tips
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-warning mr-2">
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
                            Pastikan harga sesuai kebijakan perusahaan
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Harga minimal Rp 0
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Perubahan harga akan mempengaruhi pelanggan
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Hanya Admin yang dapat mengubah harga
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-1"></i>Riwayat Perubahan
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
                    <div class="info-item mb-2">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 30px; height: 30px; font-size: 0.75rem;">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <div>
                                <small class="text-muted">Dibuat</small>
                                <div class="font-weight-bold">{{ $package->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-item mb-2">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 30px; height: 30px; font-size: 0.75rem;">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div>
                                <small class="text-muted">Terakhir Diperbarui</small>
                                <div class="font-weight-bold">{{ $package->updated_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
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
                        <i class="fas fa-question-circle mr-2"></i>Panduan Edit Harga Paket
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-money-bill-wave text-warning"></i> Edit Harga</h6>
                            <p class="text-muted">Masukkan harga baru untuk paket internet ini.</p>
                            
                            <h6><i class="fas fa-lock text-danger"></i> Field Terkunci</h6>
                            <p class="text-muted">Nama, tipe, dan deskripsi paket tidak dapat diubah oleh Admin.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-users text-success"></i> Dampak Perubahan</h6>
                            <p class="text-muted">Perubahan harga akan mempengaruhi pelanggan yang menggunakan paket ini.</p>
                            
                            <h6><i class="fas fa-check-circle text-info"></i> Validasi</h6>
                            <p class="text-muted">Harga minimal Rp 0 dan harus berupa angka.</p>
                        </div>
                    </div>
                    <div class="alert alert-primary mt-3">
                        <i class="fas fa-lightbulb"></i> 
                        <strong>Tips:</strong> Pastikan harga yang diubah sesuai dengan kebijakan perusahaan dan tidak merugikan pelanggan.
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
        
        /* Form Improvements */
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }
        
        .input-group-text {
            background-color: #f8f9fa;
            border-color: #ced4da;
        }
        
        /* Button Improvements */
        .btn-lg {
            transition: all 0.3s ease;
        }
        
        .btn-lg:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        /* Modal Improvements */
        .modal-header {
            border-bottom: none;
        }
        
        .modal-footer {
            border-top: none;
        }
        
        /* Package Icon */
        .package-icon {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        }
        
        /* Detail Item */
        .detail-item {
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }
        
        .detail-item:hover {
            background-color: rgba(40,167,69,0.05);
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
        
        /* Gap utility */
        .gap-2 {
            gap: 0.5rem;
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .btn-lg {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
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
    <!-- JavaScript khusus untuk halaman ini -->
    <script>
        $(document).ready(function() {
            // Format angka dengan pemisah ribuan
            function numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
            
            // Ambil harga dasar (sebelum PPN) dari input
            const priceBeforeTax = parseFloat('{{ $package->base_price }}');
            
            // Fungsi untuk menghitung fee teknisi
            function updateFeeCalculation() {
                const feePercentage = $('#technician_fee_percentage').val() || 0;
                
                // Tampilkan persentase
                $('#fee-percentage-display').val(feePercentage);
                
                // Hitung jumlah fee berdasarkan persentase dari harga dasar
                const feeAmount = Math.round((priceBeforeTax * feePercentage) / 100);
                
                // Tampilkan jumlah fee
                $('#technician-fee-amount').val(numberWithCommas(feeAmount));
            }
            
            // Jalankan perhitungan saat halaman dimuat
            updateFeeCalculation();
            
            // Update perhitungan fee saat persentase berubah
            $('#technician_fee_percentage').on('input', function() {
                updateFeeCalculation();
            });
        });
    </script>
@stop 