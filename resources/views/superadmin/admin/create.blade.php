@extends('adminlte::page')

@section('title', 'Tambah Admin')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-1">
                <i class="fas fa-user-plus mr-2"></i>Tambah Admin
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> Buat akun admin baru untuk mengelola sistem
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('superadmin.admins.index') }}" class="btn btn-secondary">
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
        <div class="col-lg-8 col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-1"></i>Form Tambah Admin
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-primary mr-2">
                            <i class="fas fa-asterisk"></i> Wajib Diisi
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
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

                    <form action="{{ route('superadmin.admins.store') }}" method="POST" id="admin-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">
                                        <i class="fas fa-user mr-1"></i>Nama <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="Masukkan nama lengkap" required>
                                    <div class="invalid-feedback" id="name-error">
                                        @error('name') {{ $message }} @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Masukkan nama lengkap admin
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">
                                        <i class="fas fa-envelope mr-1"></i>Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" 
                                           placeholder="contoh@email.com" required>
                                    <div class="invalid-feedback" id="email-error">
                                        @error('email') {{ $message }} @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Email akan digunakan untuk login
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">
                                        <i class="fas fa-lock mr-1"></i>Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" name="password" placeholder="Minimal 8 karakter" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" id="toggle-password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback" id="password-error">
                                        @error('password') {{ $message }} @enderror
                                    </div>
                                    <div class="password-strength mt-2" id="password-strength">
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <small class="text-muted">Kekuatan password: <span id="strength-text">Lemah</span></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">
                                        <i class="fas fa-lock mr-1"></i>Konfirmasi Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" 
                                               id="password_confirmation" name="password_confirmation" 
                                               placeholder="Ulangi password" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" id="toggle-confirm-password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="password-match mt-2" id="password-match">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i> Password harus sama
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone">
                                <i class="fas fa-phone mr-1"></i>Nomor Telepon
                            </label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" 
                                   placeholder="08xxxxxxxxxx">
                            <div class="invalid-feedback" id="phone-error">
                                @error('phone') {{ $message }} @enderror
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Opsional, untuk kontak darurat
                            </small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg" id="submit-btn">
                                <i class="fas fa-save mr-2"></i>Simpan Admin
                            </button>
                            <button type="reset" class="btn btn-secondary btn-lg ml-2">
                                <i class="fas fa-undo mr-2"></i>Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lightbulb mr-1"></i>Tips & Panduan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-check-circle text-success"></i> Password yang Kuat</h6>
                        <ul class="mb-0">
                            <li>Minimal 8 karakter</li>
                            <li>Kombinasi huruf besar & kecil</li>
                            <li>Angka dan simbol</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle text-warning"></i> Penting!</h6>
                        <p class="mb-0">Pastikan email yang digunakan belum terdaftar di sistem. Email ini akan digunakan untuk login admin.</p>
                    </div>
                    
                    <div class="alert alert-success">
                        <h6><i class="fas fa-user-shield text-primary"></i> Hak Akses Admin</h6>
                        <p class="mb-0">Admin yang dibuat akan memiliki akses untuk mengelola teknisi, paket, dan laporan keuangan.</p>
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
                        <i class="fas fa-question-circle mr-2"></i>Panduan Tambah Admin
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-user text-primary"></i> Data Pribadi</h6>
                            <p class="text-muted">Isi nama lengkap dan email yang valid. Email akan digunakan untuk login ke sistem.</p>
                            
                            <h6><i class="fas fa-lock text-warning"></i> Keamanan Password</h6>
                            <p class="text-muted">Buat password yang kuat dengan minimal 8 karakter, kombinasi huruf besar/kecil, angka, dan simbol.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-phone text-success"></i> Kontak</h6>
                            <p class="text-muted">Nomor telepon bersifat opsional, namun disarankan untuk kontak darurat.</p>
                            
                            <h6><i class="fas fa-save text-info"></i> Menyimpan Data</h6>
                            <p class="text-muted">Klik "Simpan Admin" untuk menyimpan data. Pastikan semua field wajib sudah terisi dengan benar.</p>
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

@section('css')
    <style>
        /* Card Improvements */
        .card-outline {
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        
        /* Form Improvements */
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }
        
        /* Password Strength */
        .password-strength .progress-bar {
            transition: all 0.3s ease;
        }
        
        .password-strength.weak .progress-bar {
            background-color: #dc3545;
        }
        
        .password-strength.medium .progress-bar {
            background-color: #ffc107;
        }
        
        .password-strength.strong .progress-bar {
            background-color: #28a745;
        }
        
        /* Button Improvements */
        .btn-lg {
            transition: all 0.3s ease;
        }
        
        .btn-lg:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        /* Alert Improvements */
        .alert {
            border: none;
            border-radius: 8px;
        }
        
        /* Gap utility */
        .gap-2 {
            gap: 0.5rem;
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .btn-lg {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Password toggle functionality
            $('#toggle-password').click(function() {
                const passwordField = $('#password');
                const icon = $(this).find('i');
                
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
            
            $('#toggle-confirm-password').click(function() {
                const passwordField = $('#password_confirmation');
                const icon = $(this).find('i');
                
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
            
            // Password strength checker
            $('#password').on('input', function() {
                const password = $(this).val();
                const strength = checkPasswordStrength(password);
                updatePasswordStrength(strength);
            });
            
            // Password confirmation checker
            $('#password_confirmation').on('input', function() {
                checkPasswordMatch();
            });
            
            // Real-time validation
            $('#name').on('input', function() {
                validateName();
            });
            
            $('#email').on('input', function() {
                validateEmail();
            });
            
            $('#phone').on('input', function() {
                validatePhone();
            });
            
            // Form submission
            $('#admin-form').on('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    showAlert('Mohon perbaiki kesalahan pada form', 'danger');
                }
            });
            
            // Functions
            function checkPasswordStrength(password) {
                let score = 0;
                
                if (password.length >= 8) score++;
                if (/[a-z]/.test(password)) score++;
                if (/[A-Z]/.test(password)) score++;
                if (/[0-9]/.test(password)) score++;
                if (/[^A-Za-z0-9]/.test(password)) score++;
                
                if (score < 3) return 'weak';
                if (score < 5) return 'medium';
                return 'strong';
            }
            
            function updatePasswordStrength(strength) {
                const strengthDiv = $('#password-strength');
                const progressBar = strengthDiv.find('.progress-bar');
                const strengthText = $('#strength-text');
                
                strengthDiv.removeClass('weak medium strong').addClass(strength);
                
                let width, text, color;
                switch(strength) {
                    case 'weak':
                        width = '33%';
                        text = 'Lemah';
                        color = '#dc3545';
                        break;
                    case 'medium':
                        width = '66%';
                        text = 'Sedang';
                        color = '#ffc107';
                        break;
                    case 'strong':
                        width = '100%';
                        text = 'Kuat';
                        color = '#28a745';
                        break;
                }
                
                progressBar.css('width', width).css('background-color', color);
                strengthText.text(text);
            }
            
            function checkPasswordMatch() {
                const password = $('#password').val();
                const confirmPassword = $('#password_confirmation').val();
                const matchDiv = $('#password-match');
                
                if (confirmPassword === '') {
                    matchDiv.find('small').html('<i class="fas fa-info-circle"></i> Password harus sama');
                    return false;
                }
                
                if (password === confirmPassword) {
                    matchDiv.find('small').html('<i class="fas fa-check-circle text-success"></i> Password cocok');
                    return true;
                } else {
                    matchDiv.find('small').html('<i class="fas fa-times-circle text-danger"></i> Password tidak cocok');
                    return false;
                }
            }
            
            function validateName() {
                const name = $('#name').val();
                const nameError = $('#name-error');
                
                if (name.length < 3) {
                    $('#name').addClass('is-invalid');
                    nameError.text('Nama minimal 3 karakter');
                    return false;
                } else {
                    $('#name').removeClass('is-invalid');
                    nameError.text('');
                    return true;
                }
            }
            
            function validateEmail() {
                const email = $('#email').val();
                const emailError = $('#email-error');
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (!emailRegex.test(email)) {
                    $('#email').addClass('is-invalid');
                    emailError.text('Format email tidak valid');
                    return false;
                } else {
                    $('#email').removeClass('is-invalid');
                    emailError.text('');
                    return true;
                }
            }
            
            function validatePhone() {
                const phone = $('#phone').val();
                const phoneError = $('#phone-error');
                
                if (phone && !/^08[0-9]{8,11}$/.test(phone)) {
                    $('#phone').addClass('is-invalid');
                    phoneError.text('Format nomor telepon tidak valid');
                    return false;
                } else {
                    $('#phone').removeClass('is-invalid');
                    phoneError.text('');
                    return true;
                }
            }
            
            function validateForm() {
                const nameValid = validateName();
                const emailValid = validateEmail();
                const phoneValid = validatePhone();
                const passwordValid = $('#password').val().length >= 8;
                const confirmValid = checkPasswordMatch();
                
                return nameValid && emailValid && phoneValid && passwordValid && confirmValid;
            }
            
            function showAlert(message, type) {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <div>${message}</div>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                
                $('.card-body').prepend(alertHtml);
                
                setTimeout(() => {
                    $('.alert').alert('close');
                }, 5000);
            }
        });
    </script>
@stop 