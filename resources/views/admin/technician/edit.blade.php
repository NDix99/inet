@extends('adminlte::page')

@section('title', 'Edit Teknisi')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-1">
                <i class="fas fa-user-edit text-primary mr-2"></i>Edit Teknisi
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-user-cog"></i> Perbarui data teknisi: {{ $technician->name }}
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.technicians.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> 
                <span class="d-none d-sm-inline">Kembali</span>
                <span class="d-inline d-sm-none">Back</span>
            </a>
        </div>
    </div>
@stop

@section('content')
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

    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-cog mr-1"></i>Informasi Teknisi
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.technicians.update', $technician) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Informasi Teknisi (Editable) -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">
                                        <i class="fas fa-user text-primary mr-1"></i>Nama Teknisi <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $technician->name) }}" 
                                           placeholder="Masukkan nama teknisi" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="font-weight-bold">
                                        <i class="fas fa-envelope text-success mr-1"></i>Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $technician->email) }}" 
                                           placeholder="Masukkan email teknisi" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="font-weight-bold">
                                        <i class="fas fa-phone text-warning mr-1"></i>Telepon
                                    </label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $technician->phone) }}" 
                                           placeholder="08xxxxxxxxxx">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Opsional, untuk kontak darurat
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">
                                        <i class="fas fa-toggle-on text-info mr-1"></i>Status Teknisi
                                    </label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active', $technician->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">
                                            <i class="fas fa-toggle-on mr-1"></i>
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Teknisi yang tidak aktif tidak dapat login ke sistem
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Password (Optional) -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="font-weight-bold">
                                        <i class="fas fa-lock text-warning mr-1"></i>Password Baru
                                    </label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Minimal 8 karakter, kosongkan jika tidak ingin mengubah
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation" class="font-weight-bold">
                                        <i class="fas fa-lock text-warning mr-1"></i>Konfirmasi Password
                                    </label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="Konfirmasi password baru">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Fee Teknisi -->
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
                                        <input type="number" class="form-control @error('technician_fee_percentage') is-invalid @enderror" 
                                               id="technician_fee_percentage" name="technician_fee_percentage" 
                                               value="{{ old('technician_fee_percentage', $technician->technician_fee_percentage ?? 0) }}" 
                                               min="0" max="100" step="1" placeholder="Masukkan persentase fee teknisi" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    @error('technician_fee_percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Persentase fee untuk teknisi (diambil dari harga dasar paket)
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
                                                <input type="text" class="form-control" id="fee-percentage-display" value="{{ old('technician_fee_percentage', $technician->technician_fee_percentage ?? 0) }}" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Contoh Fee (Paket Rp 100.000)</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" class="form-control" id="technician-fee-amount" value="{{ number_format($technician->calculateTechnicianFee(100000)['amount'], 0, ',', '.') }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info mt-3">
                                    <div class="d-flex">
                                        <div class="mr-3">
                                            <i class="fas fa-info-circle fa-2x"></i>
                                        </div>
                                        <div>
                                            <h6>Informasi Perhitungan:</h6>
                                            <ul class="mb-0">
                                                <li>Fee dihitung dari harga dasar paket (sebelum PPN 11%)</li>
                                                <li>Contoh: Paket Rp 100.000 → Harga dasar Rp 90.090 → Fee {{ $technician->technician_fee_percentage ?? 0 }}% = Rp {{ number_format($technician->calculateTechnicianFee(100000)['amount'], 0, ',', '.') }}</li>
                                                <li>Fee akan diterapkan untuk semua paket yang ditangani teknisi ini</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning mt-3">
                            <div class="d-flex">
                                <div class="mr-3">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                                </div>
                                <div>
                                    <h5>Perubahan Sistem Fee:</h5>
                                    <ul class="mb-0">
                                        <li>Fee teknisi sekarang diatur per teknisi, bukan per paket</li>
                                        <li>Setiap teknisi dapat memiliki persentase fee yang berbeda</li>
                                        <li>Fee akan diterapkan secara konsisten untuk semua paket yang ditangani</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save mr-1"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.technicians.index') }}" class="btn btn-secondary btn-lg ml-2">
                                <i class="fas fa-times mr-1"></i>Batal
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
                        <i class="fas fa-info-circle mr-1"></i>Informasi
                    </h3>
                </div>
                <div class="card-body">
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                <i class="fas fa-user-cog"></i>
                            </div>
                            <div>
                                <small class="text-muted">Nama Teknisi</small>
                                <div class="font-weight-bold">{{ $technician->name }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <small class="text-muted">Jumlah Pelanggan</small>
                                <div class="font-weight-bold">{{ $technician->customers()->count() }} pelanggan</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                <i class="fas fa-percentage"></i>
                            </div>
                            <div>
                                <small class="text-muted">Fee Saat Ini</small>
                                <div class="font-weight-bold">
                                    @if($technician->technician_fee_percentage)
                                        {{ $technician->technician_fee_percentage }}%
                                        <div class="small text-muted">
                                            Contoh: Rp {{ number_format($technician->calculateTechnicianFee(100000)['amount'], 0, ',', '.') }}
                                        </div>
                                    @else
                                        <span class="text-muted">Belum diatur</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper bg-info text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div>
                                <small class="text-muted">Total Invoice</small>
                                <div class="font-weight-bold">{{ $technician->invoices()->count() }} invoice</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contoh Perhitungan -->
            <div class="card card-outline card-warning mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calculator mr-1"></i>Contoh Perhitungan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Paket</th>
                                    <th>Fee</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Rp 50.000</td>
                                    <td>Rp {{ number_format($technician->calculateTechnicianFee(50000)['amount'], 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Rp 100.000</td>
                                    <td>Rp {{ number_format($technician->calculateTechnicianFee(100000)['amount'], 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Rp 200.000</td>
                                    <td>Rp {{ number_format($technician->calculateTechnicianFee(200000)['amount'], 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Rp 500.000</td>
                                    <td>Rp {{ number_format($technician->calculateTechnicianFee(500000)['amount'], 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
        
        /* Form improvements */
        .form-control:disabled {
            background-color: #f8f9fa;
            opacity: 1;
        }
        
        /* Alert improvements */
        .alert {
            border: none;
            border-radius: 0.5rem;
        }
        
        /* Button improvements */
        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
        }
        
        /* Table improvements */
        .table-sm th,
        .table-sm td {
            padding: 0.5rem;
            font-size: 0.875rem;
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .btn-lg {
                padding: 0.5rem 1rem;
                font-size: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Update fee calculation when percentage changes
            $('#technician_fee_percentage').on('input', function() {
                const feePercentage = $(this).val() || 0;
                
                // Update display
                $('#fee-percentage-display').val(feePercentage);
                
                // Calculate example fee for Rp 100.000 package
                const packagePrice = 100000;
                const ppnRate = 0.11;
                const priceBeforeTax = Math.round(packagePrice / (1 + ppnRate));
                const feeAmount = Math.round((priceBeforeTax * feePercentage) / 100);
                
                // Update fee amount display
                $('#technician-fee-amount').val(feeAmount.toLocaleString('id-ID'));
                
                // Update calculation info
                updateCalculationInfo(feePercentage, feeAmount);
            });
            
            function updateCalculationInfo(percentage, amount) {
                const packagePrice = 100000;
                const ppnRate = 0.11;
                const priceBeforeTax = Math.round(packagePrice / (1 + ppnRate));
                
                // Update the calculation example in the alert
                const calculationText = `Contoh: Paket Rp 100.000 → Harga dasar Rp ${priceBeforeTax.toLocaleString('id-ID')} → Fee ${percentage}% = Rp ${amount.toLocaleString('id-ID')}`;
                
                // Find and update the calculation text in the alert
                $('.alert-info ul li:nth-child(2)').text(calculationText);
            }
            
            // Add loading animation to submit button
            $('form').on('submit', function() {
                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="fas fa-spinner fa-spin mr-1"></i>Menyimpan...');
            });
            
            // Auto-dismiss alerts
            $('.alert').delay(5000).fadeOut(500);
            
            // Initialize calculation on page load
            $('#technician_fee_percentage').trigger('input');
        });
    </script>
@stop 