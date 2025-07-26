@extends('adminlte::page')

@section('title', 'Tambah Paket Internet')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-1">
                <i class="fas fa-plus-circle mr-2"></i>Tambah Paket Internet
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> Buat paket internet atau metro baru untuk pelanggan
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('superadmin.packages.index') }}" class="btn btn-secondary">
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
                        <i class="fas fa-edit mr-1"></i>Form Tambah Paket
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
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <div>
                                    <strong>Terjadi kesalahan:</strong>
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

                    <form action="{{ route('superadmin.packages.store') }}" method="POST" id="package-form">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">
                                        <i class="fas fa-box mr-1"></i>Nama Paket <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="Contoh: Internet 50 Mbps" required>
                                    <div class="invalid-feedback" id="name-error">
                                        @error('name') {{ $message }} @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Masukkan nama paket yang jelas dan mudah dipahami
                                    </small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="type">
                                        <i class="fas fa-tag mr-1"></i>Tipe Paket <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">-- Pilih Tipe --</option>
                                        <option value="internet" {{ old('type') == 'internet' ? 'selected' : '' }}>
                                            <i class="fas fa-wifi"></i> Internet
                                        </option>
                                        <option value="metro" {{ old('type') == 'metro' ? 'selected' : '' }}>
                                            <i class="fas fa-network-wired"></i> Metro
                                        </option>
                                    </select>
                                    <div class="invalid-feedback" id="type-error">
                                        @error('type') {{ $message }} @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Pilih tipe paket sesuai layanan yang ditawarkan
                                    </small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="price">
                                        <i class="fas fa-money-bill-wave mr-1"></i>Harga (Rp) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                               id="price" name="price" value="{{ old('price') }}" 
                                               min="0" placeholder="250000" required>
                                    </div>
                                    <div class="invalid-feedback" id="price-error">
                                        @error('price') {{ $message }} @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Masukkan harga dalam rupiah tanpa tanda koma
                                    </small>
                                    <div class="alert alert-info mt-2 p-2">
                                        <i class="fas fa-exclamation-circle"></i> Harga yang diinput sudah termasuk PPN 11%
                                    </div>
                                    
                                    <div class="card mt-2 border-primary">
                                        <div class="card-header bg-primary text-white py-1">
                                            <i class="fas fa-calculator"></i> Perhitungan Otomatis
                                        </div>
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between">
                                                <span>Harga Dasar:</span>
                                                <span id="price-before-tax">Rp 0</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>PPN (11%):</span>
                                                <span id="tax-amount">Rp 0</span>
                                            </div>
                                            <div class="d-flex justify-content-between font-weight-bold mt-1 pt-1 border-top">
                                                <span>Total Harga:</span>
                                                <span id="total-price">Rp 0</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" id="base_price" name="base_price" value="{{ old('base_price') }}">
                                    <input type="hidden" id="tax_amount" name="tax_amount" value="{{ old('tax_amount') }}">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">
                                        <i class="fas fa-align-left mr-1"></i>Deskripsi
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="5" 
                                              placeholder="Masukkan deskripsi detail tentang paket ini...">{{ old('description') }}</textarea>
                                    <div class="invalid-feedback" id="description-error">
                                        @error('description') {{ $message }} @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Opsional, jelaskan fitur dan keunggulan paket
                                    </small>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-keyboard"></i> Karakter tersisa: <span id="char-count">0</span>/500
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg" id="submit-btn">
                                <i class="fas fa-save mr-2"></i>Simpan Paket
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
                    <div class="alert alert-primary">
                        <h6><i class="fas fa-box text-primary"></i> Nama Paket</h6>
                        <p class="mb-0">Gunakan nama yang jelas dan informatif, misalnya "Internet 50 Mbps" atau "Metro Fiber 100 Mbps".</p>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-tag text-warning"></i> Tipe Paket</h6>
                        <ul class="mb-0">
                            <li><strong>Internet:</strong> Layanan internet untuk rumah/toko</li>
                            <li><strong>Metro:</strong> Layanan internet untuk bisnis/enterprise</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-success">
                        <h6><i class="fas fa-money-bill-wave text-success"></i> Harga</h6>
                        <p class="mb-0">Pastikan harga sesuai dengan kualitas layanan dan kompetitif di pasaran.</p>
                    </div>
                    
                    <div class="alert alert-info">
                        <h6><i class="fas fa-align-left text-info"></i> Deskripsi</h6>
                        <p class="mb-0">Jelaskan fitur, keunggulan, dan syarat paket untuk memudahkan pelanggan memilih.</p>
                    </div>
                </div>
            </div>
            
            <div class="card card-outline card-success mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i>Statistik Paket
                    </h3>
                </div>
                <div class="card-body">
                    <div class="info-box bg-gradient-primary mb-3">
                        <span class="info-box-icon"><i class="fas fa-box"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Paket</span>
                            <span class="info-box-number">{{ \App\Models\Package::count() }}</span>
                        </div>
                    </div>
                    
                    <div class="info-box bg-gradient-success mb-3">
                        <span class="info-box-icon"><i class="fas fa-wifi"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Paket Internet</span>
                            <span class="info-box-number">{{ \App\Models\Package::where('type', 'internet')->count() }}</span>
                        </div>
                    </div>
                    
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas fa-network-wired"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Paket Metro</span>
                            <span class="info-box-number">{{ \App\Models\Package::where('type', 'metro')->count() }}</span>
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
                        <i class="fas fa-question-circle mr-2"></i>Panduan Tambah Paket
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-box text-primary"></i> Nama Paket</h6>
                            <p class="text-muted">Buat nama yang jelas dan informatif. Contoh: "Internet 50 Mbps Unlimited" atau "Metro Fiber 100 Mbps".</p>
                            
                            <h6><i class="fas fa-tag text-warning"></i> Tipe Paket</h6>
                            <p class="text-muted">Pilih antara Internet (untuk rumah/toko) atau Metro (untuk bisnis/enterprise) sesuai target pelanggan.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-money-bill-wave text-success"></i> Harga</h6>
                            <p class="text-muted">Masukkan harga dalam rupiah tanpa tanda koma. Pastikan harga kompetitif dan sesuai kualitas layanan.</p>
                            
                            <h6><i class="fas fa-align-left text-info"></i> Deskripsi</h6>
                            <p class="text-muted">Jelaskan fitur, keunggulan, dan syarat paket untuk membantu pelanggan memahami layanan yang ditawarkan.</p>
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
        
        /* Info Box Improvements */
        .info-box {
            transition: all 0.3s ease;
        }
        
        .info-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
            
            .info-box {
                margin-bottom: 1rem;
            }
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Character counter for description
            $('#description').on('input', function() {
                const maxLength = 500;
                const currentLength = $(this).val().length;
                const remaining = maxLength - currentLength;
                
                $('#char-count').text(currentLength);
                
                if (currentLength > maxLength) {
                    $(this).val($(this).val().substring(0, maxLength));
                    $('#char-count').text(maxLength);
                }
                
                if (currentLength > maxLength * 0.9) {
                    $('#char-count').addClass('text-danger');
                } else {
                    $('#char-count').removeClass('text-danger');
                }
            });
            
            // Real-time validation
            $('#name').on('input', function() {
                validateName();
            });
            
            $('#type').on('change', function() {
                validateType();
            });
            
            $('#price').on('input', function() {
                validatePrice();
                updatePriceCalculations();
            });
            
            // Form submission
            $('#package-form').on('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    showAlert('Mohon perbaiki kesalahan pada form', 'danger');
                }
            });
            
            // Functions
            function validateName() {
                const name = $('#name').val();
                const nameError = $('#name-error');
                
                if (name.length < 3) {
                    $('#name').addClass('is-invalid');
                    nameError.text('Nama paket minimal 3 karakter');
                    return false;
                } else {
                    $('#name').removeClass('is-invalid');
                    nameError.text('');
                    return true;
                }
            }
            
            function validateType() {
                const type = $('#type').val();
                const typeError = $('#type-error');
                
                if (!type) {
                    $('#type').addClass('is-invalid');
                    typeError.text('Pilih tipe paket');
                    return false;
                } else {
                    $('#type').removeClass('is-invalid');
                    typeError.text('');
                    return true;
                }
            }
            
            function validatePrice() {
                const price = $('#price').val();
                const priceError = $('#price-error');
                
                if (!price || price <= 0) {
                    $('#price').addClass('is-invalid');
                    priceError.text('Harga harus lebih dari 0');
                    return false;
                } else {
                    $('#price').removeClass('is-invalid');
                    priceError.text('');
                    return true;
                }
            }
            
            function validateForm() {
                const nameValid = validateName();
                const typeValid = validateType();
                const priceValid = validatePrice();
                
                return nameValid && typeValid && priceValid;
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
            
            // Add hover effects to info boxes
            $('.info-box').hover(
                function() {
                    $(this).addClass('shadow-lg');
                },
                function() {
                    $(this).removeClass('shadow-lg');
                }
            );
            
            // Fungsi untuk menghitung harga dasar, PPN, dan total harga
            function updatePriceCalculations() {
                const price = parseFloat($('#price').val()) || 0;
                const ppnRate = 0.11; // PPN 11%
                const basePrice = price / (1 + ppnRate);
                const taxAmount = price - basePrice;
                
                $('#price-before-tax').text('Rp ' + numberWithCommas(Math.round(basePrice)));
                $('#tax-amount').text('Rp ' + numberWithCommas(Math.round(taxAmount)));
                $('#total-price').text('Rp ' + numberWithCommas(Math.round(price)));
                
                // Update hidden fields
                $('#base_price').val(Math.round(basePrice));
                $('#tax_amount').val(Math.round(taxAmount));
            }
            
            // Fungsi untuk memformat angka dengan pemisah ribuan
            function numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
            
            // Panggil fungsi saat halaman dimuat
            updatePriceCalculations();
        });
    </script>
@stop 