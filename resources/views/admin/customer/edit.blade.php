@extends('adminlte::page')

@section('title', 'Edit Pelanggan')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-1">
                <i class="fas fa-edit mr-2"></i>Edit Pelanggan
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> Perbarui informasi pelanggan dan paket internet
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> 
                <span class="d-none d-sm-inline">Kembali</span>
                <span class="d-inline d-sm-none">Back</span>
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
    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-1"></i>Form Edit Pelanggan
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-warning mr-2">
                            <i class="fas fa-user-edit"></i> Edit
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.customers.update', $customer) }}" method="POST" id="customer-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">
                                        <i class="fas fa-user text-primary mr-1"></i>Nama Pelanggan <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $customer->name) }}" required placeholder="Masukkan nama lengkap">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <small class="text-muted" id="name-counter">0/100</small>
                                            </span>
                                        </div>
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Nama lengkap pelanggan (maksimal 100 karakter)</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="font-weight-bold">
                                        <i class="fas fa-envelope text-primary mr-1"></i>Email <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                        </div>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $customer->email) }}" required placeholder="contoh@email.com">
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Email aktif pelanggan untuk komunikasi</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="font-weight-bold">
                                        <i class="fas fa-phone text-primary mr-1"></i>Nomor Telepon <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-phone"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" required placeholder="081234567890">
                                    </div>
                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Nomor telepon aktif (format: 081234567890)</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="billing_date" class="font-weight-bold">
                                        <i class="fas fa-calendar-alt text-primary mr-1"></i>Tanggal Tagihan <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="date" class="form-control @error('billing_date') is-invalid @enderror" id="billing_date" name="billing_date" value="{{ old('billing_date', $customer->billing_date->format('Y-m-d')) }}" required>
                                    </div>
                                    @error('billing_date')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Tanggal jatuh tempo tagihan bulanan</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="address" class="font-weight-bold">
                                <i class="fas fa-map-marker-alt text-primary mr-1"></i>Alamat <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                </div>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required placeholder="Masukkan alamat lengkap">{{ old('address', $customer->address) }}</textarea>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <small class="text-muted" id="address-counter">0/500</small>
                                    </span>
                                </div>
                            </div>
                            @error('address')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Alamat lengkap pelanggan (maksimal 500 karakter)</small>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="latitude" class="font-weight-bold">
                                        <i class="fas fa-map-marker-alt text-primary mr-1"></i>Latitude
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $customer->latitude) }}" placeholder="-6.2088">
                                    </div>
                                    @error('latitude')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Koordinat latitude (opsional)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="longitude" class="font-weight-bold">
                                        <i class="fas fa-map-marker-alt text-primary mr-1"></i>Longitude
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $customer->longitude) }}" placeholder="106.8456">
                                    </div>
                                    @error('longitude')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Koordinat longitude (opsional)</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="package_id" class="font-weight-bold">
                                <i class="fas fa-box text-primary mr-1"></i>Paket Internet <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-box"></i>
                                    </span>
                                </div>
                                <select class="form-control @error('package_id') is-invalid @enderror" id="package_id" name="package_id" required>
                                    <option value="">-- Pilih Paket --</option>
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id }}" {{ old('package_id', $customer->package_id) == $package->id ? 'selected' : '' }} data-price="{{ $package->price }}" data-type="{{ $package->type }}">
                                            {{ $package->name }} - {{ $package->type }} - Rp {{ number_format($package->price, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('package_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Pilih paket internet yang sesuai dengan kebutuhan pelanggan</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="is_active" class="font-weight-bold">
                                <i class="fas fa-toggle-on text-primary mr-1"></i>Status <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-toggle-on"></i>
                                    </span>
                                </div>
                                <select class="form-control @error('is_active') is-invalid @enderror" id="is_active" name="is_active" required>
                                    <option value="1" {{ old('is_active', $customer->is_active) ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('is_active', $customer->is_active) ? '' : 'selected' }}>Tidak Aktif</option>
                                </select>
                            </div>
                            @error('is_active')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Status aktif pelanggan dalam sistem</small>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                            <button type="reset" class="btn btn-secondary btn-lg ml-2">
                                <i class="fas fa-undo mr-2"></i>Reset Form
                            </button>
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
                            <i class="fas fa-box"></i> Detail
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="package-info" class="text-center py-4" style="display: none;">
                        <i class="fas fa-box text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-muted">Pilih Paket</h5>
                        <p class="text-muted">Pilih paket internet untuk melihat detail</p>
                    </div>
                    
                    <div id="package-details">
                        @if($customer->package)
                            <div class="text-center mb-3">
                                <div class="package-icon bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px; font-size: 1.25rem;">
                                    <i class="fas fa-box"></i>
                                </div>
                                <h5 id="package-name" class="mb-1">{{ $customer->package->name }}</h5>
                                <span id="package-type" class="badge badge-primary">{{ $customer->package->type }}</span>
                            </div>
                            
                            <div class="package-details">
                                <div class="detail-item mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">
                                            <i class="fas fa-tag mr-1"></i>Harga
                                        </span>
                                        <span id="package-price" class="font-weight-bold text-success">Rp {{ number_format($customer->package->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-muted">Tidak ada paket</h5>
                                <p class="text-muted">Pelanggan belum memilih paket internet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lightbulb mr-1"></i>Tips
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-success mr-2">
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
                            Perbarui data yang berubah saja
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Pastikan status pelanggan sesuai
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Koordinat GPS membantu tracking
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Tanggal tagihan mempengaruhi billing
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
                                <div class="font-weight-bold">{{ $customer->created_at->format('d/m/Y H:i') }}</div>
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
                                <div class="font-weight-bold">{{ $customer->updated_at->format('d/m/Y H:i') }}</div>
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
                        <i class="fas fa-question-circle mr-2"></i>Panduan Edit Pelanggan
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-user text-primary"></i> Data Pelanggan</h6>
                            <p class="text-muted">Perbarui informasi pelanggan termasuk nama, email, dan telepon.</p>
                            
                            <h6><i class="fas fa-map-marker-alt text-warning"></i> Alamat & Koordinat</h6>
                            <p class="text-muted">Update alamat lengkap dan koordinat GPS untuk tracking lokasi.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-box text-success"></i> Paket Internet</h6>
                            <p class="text-muted">Ubah paket internet sesuai dengan kebutuhan pelanggan.</p>
                            
                            <h6><i class="fas fa-toggle-on text-info"></i> Status Pelanggan</h6>
                            <p class="text-muted">Aktifkan atau nonaktifkan status pelanggan dalam sistem.</p>
                        </div>
                    </div>
                    <div class="alert alert-primary mt-3">
                        <i class="fas fa-lightbulb"></i> 
                        <strong>Tips:</strong> Pastikan semua perubahan data akurat dan valid.
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
        
        /* Character counter */
        .input-group-append .input-group-text {
            background-color: #e9ecef;
            border-color: #ced4da;
            font-size: 0.75rem;
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
    <script>
        $(document).ready(function() {
            // Character counter for name
            $('#name').on('input', function() {
                var length = $(this).val().length;
                $('#name-counter').text(length + '/100');
                
                if (length > 80) {
                    $('#name-counter').removeClass('text-muted').addClass('text-warning');
                } else {
                    $('#name-counter').removeClass('text-warning').addClass('text-muted');
                }
            });
            
            // Character counter for address
            $('#address').on('input', function() {
                var length = $(this).val().length;
                $('#address-counter').text(length + '/500');
                
                if (length > 400) {
                    $('#address-counter').removeClass('text-muted').addClass('text-warning');
                } else {
                    $('#address-counter').removeClass('text-warning').addClass('text-muted');
                }
            });
            
            // Package selection handler
            $('#package_id').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var packageName = selectedOption.text();
                var packagePrice = selectedOption.data('price');
                var packageType = selectedOption.data('type');
                
                if (packageName && packageName !== '-- Pilih Paket --') {
                    $('#package-info').hide();
                    $('#package-details').show();
                    $('#package-name').text(packageName.split(' - ')[0]);
                    $('#package-type').text(packageType);
                    $('#package-price').text('Rp ' + packagePrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
                } else {
                    $('#package-info').show();
                    $('#package-details').hide();
                }
            });
            
            // Form validation
            $('#customer-form').on('submit', function() {
                var isValid = true;
                
                // Check required fields
                $(this).find('[required]').each(function() {
                    if (!$(this).val()) {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                
                // Email validation
                var email = $('#email').val();
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email && !emailRegex.test(email)) {
                    $('#email').addClass('is-invalid');
                    isValid = false;
                }
                
                // Phone validation
                var phone = $('#phone').val();
                var phoneRegex = /^[0-9]{10,13}$/;
                if (phone && !phoneRegex.test(phone)) {
                    $('#phone').addClass('is-invalid');
                    isValid = false;
                }
                
                if (!isValid) {
                    $('html, body').animate({
                        scrollTop: $('.is-invalid:first').offset().top - 100
                    }, 500);
                    return false;
                }
                
                // Show loading state
                $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...');
            });
            
            // Reset form
            $('button[type="reset"]').on('click', function() {
                $('#package-info').hide();
                $('#package-details').show();
                $('.is-invalid').removeClass('is-invalid');
                
                // Reset to original values
                setTimeout(function() {
                    $('#name').trigger('input');
                    $('#address').trigger('input');
                }, 100);
            });
            
            // Add loading animation to buttons
            $('.btn').click(function() {
                if (!$(this).hasClass('btn-tool') && !$(this).attr('type')) {
                    $(this).append('<span class="spinner-border spinner-border-sm ml-1" role="status" aria-hidden="true"></span>');
                    setTimeout(() => {
                        $(this).find('.spinner-border').remove();
                    }, 1000);
                }
            });
            
            // Initialize counters
            $('#name').trigger('input');
            $('#address').trigger('input');
        });
    </script>
@stop 