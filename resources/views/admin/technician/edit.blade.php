@extends('adminlte::page')

@section('title', 'Edit Teknisi')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-1">
                <i class="fas fa-edit mr-2"></i>Edit Teknisi
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> Perbarui informasi teknisi dan pengaturan akun
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.technicians.index') }}" class="btn btn-secondary">
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
                        <i class="fas fa-edit mr-1"></i>Form Edit Teknisi
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
                    <form action="{{ route('admin.technicians.update', $technician) }}" method="POST" id="technician-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">
                                        <i class="fas fa-user text-primary mr-1"></i>Nama Teknisi <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $technician->name) }}" required placeholder="Masukkan nama lengkap">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <small class="text-muted" id="name-counter">0/100</small>
                                            </span>
                                        </div>
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Nama lengkap teknisi (maksimal 100 karakter)</small>
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
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $technician->email) }}" required placeholder="contoh@email.com">
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Email aktif teknisi untuk login dan komunikasi</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="font-weight-bold">
                                        <i class="fas fa-phone text-primary mr-1"></i>Nomor Telepon
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-phone"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $technician->phone) }}" placeholder="081234567890">
                                    </div>
                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Nomor telepon aktif (format: 081234567890)</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
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
                                            <option value="1" {{ old('is_active', $technician->is_active) ? 'selected' : '' }}>Aktif</option>
                                            <option value="0" {{ old('is_active', $technician->is_active) ? '' : 'selected' }}>Tidak Aktif</option>
                                        </select>
                                    </div>
                                    @error('is_active')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Status aktif teknisi dalam sistem</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="password" class="font-weight-bold">
                                <i class="fas fa-lock text-primary mr-1"></i>Password Baru
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password (minimal 8 karakter)</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="password_confirmation" class="font-weight-bold">
                                <i class="fas fa-lock text-primary mr-1"></i>Konfirmasi Password Baru
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Masukkan ulang password baru untuk konfirmasi</small>
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
                        <i class="fas fa-info-circle mr-1"></i>Informasi Akun
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-info mr-2">
                            <i class="fas fa-user-cog"></i> Akun
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="account-icon bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        <h5 class="mb-1">{{ $technician->name }}</h5>
                        <span class="badge badge-success badge-lg">Role: Teknisi</span>
                    </div>
                    
                    <div class="account-details">
                        <div class="detail-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    <i class="fas fa-shield-alt mr-2"></i>Hak Akses
                                </span>
                                <span class="font-weight-bold text-success">
                                    Terbatas
                                </span>
                            </div>
                        </div>
                        
                        <div class="detail-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    <i class="fas fa-users mr-2"></i>Kelola Pelanggan
                                </span>
                                <span class="font-weight-bold text-success">
                                    <i class="fas fa-check"></i> Ya
                                </span>
                            </div>
                        </div>
                        
                        <div class="detail-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    <i class="fas fa-chart-line mr-2"></i>Laporan Keuangan
                                </span>
                                <span class="font-weight-bold text-success">
                                    <i class="fas fa-check"></i> Ya
                                </span>
                            </div>
                        </div>
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
                            Password kosong = tidak berubah
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Status aktif menentukan akses
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            Email harus unik dalam sistem
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
                                <div class="font-weight-bold">{{ $technician->created_at->format('d/m/Y H:i') }}</div>
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
                                <div class="font-weight-bold">{{ $technician->updated_at->format('d/m/Y H:i') }}</div>
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
                        <i class="fas fa-question-circle mr-2"></i>Panduan Edit Teknisi
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-user text-primary"></i> Data Teknisi</h6>
                            <p class="text-muted">Perbarui informasi teknisi termasuk nama dan kontak.</p>
                            
                            <h6><i class="fas fa-envelope text-success"></i> Email</h6>
                            <p class="text-muted">Email akan digunakan untuk login sistem.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-lock text-warning"></i> Password</h6>
                            <p class="text-muted">Kosongkan jika tidak ingin mengubah password.</p>
                            
                            <h6><i class="fas fa-toggle-on text-info"></i> Status</h6>
                            <p class="text-muted">Aktifkan atau nonaktifkan akses teknisi ke sistem.</p>
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
        
        /* Account Icon */
        .account-icon {
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
            
            // Password toggle
            $('#togglePassword').click(function() {
                var passwordField = $('#password');
                var icon = $(this).find('i');
                
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
            
            $('#togglePasswordConfirmation').click(function() {
                var passwordField = $('#password_confirmation');
                var icon = $(this).find('i');
                
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
            
            // Password confirmation validation
            $('#password_confirmation').on('input', function() {
                var password = $('#password').val();
                var confirmation = $(this).val();
                
                if (confirmation && password !== confirmation) {
                    $(this).addClass('is-invalid');
                    $(this).removeClass('is-valid');
                } else if (confirmation && password === confirmation) {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                } else {
                    $(this).removeClass('is-invalid is-valid');
                }
            });
            
            // Form validation
            $('#technician-form').on('submit', function() {
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
                
                // Password validation (only if password is provided)
                var password = $('#password').val();
                if (password && password.length < 8) {
                    $('#password').addClass('is-invalid');
                    isValid = false;
                }
                
                // Password confirmation validation (only if password is provided)
                var confirmation = $('#password_confirmation').val();
                if (password && password !== confirmation) {
                    $('#password_confirmation').addClass('is-invalid');
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
                $('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
                
                // Reset to original values
                setTimeout(function() {
                    $('#name').trigger('input');
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
        });
    </script>
@stop 