@extends('adminlte::page')

@section('title', 'Edit Paket Internet')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-1">
                <i class="fas fa-edit mr-2"></i>Edit Paket Internet
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> Perbarui data paket {{ $package->name }}
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
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-1"></i>Form Edit Paket
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-warning mr-2">
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

                    <form action="{{ route('superadmin.packages.update', $package) }}" method="POST" id="package-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">
                                        <i class="fas fa-box mr-1"></i>Nama Paket <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $package->name) }}" 
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
                                        <option value="internet" {{ old('type', $package->type) == 'internet' ? 'selected' : '' }}>
                                            <i class="fas fa-wifi"></i> Internet
                                        </option>
                                        <option value="metro" {{ old('type', $package->type) == 'metro' ? 'selected' : '' }}>
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
                                               id="price" name="price" value="{{ old('price', $package->price) }}" 
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
                                    
                                    <div class="card mt-2 border-warning">
                                        <div class="card-header bg-warning text-white py-1">
                                            <i class="fas fa-calculator"></i> Perhitungan Otomatis
                                        </div>
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between">
                                                <span>Harga Dasar:</span>
                                                <span id="price-before-tax">Rp {{ number_format($package->base_price, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>PPN (11%):</span>
                                                <span id="tax-amount">Rp {{ number_format($package->tax_amount, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between font-weight-bold mt-1 pt-1 border-top">
                                                <span>Total Harga:</span>
                                                <span id="total-price">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" id="base_price" name="base_price" value="{{ old('base_price', $package->base_price) }}">
                                    <input type="hidden" id="tax_amount" name="tax_amount" value="{{ old('tax_amount', $package->tax_amount) }}">
                                </div>
                                
                                <div class="form-group">
                                    <label for="is_active">
                                        <i class="fas fa-toggle-on mr-1"></i>Status <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('is_active') is-invalid @enderror" id="is_active" name="is_active" required>
                                        <option value="1" {{ old('is_active', $package->is_active) ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ old('is_active', $package->is_active) ? '' : 'selected' }}>Tidak Aktif</option>
                                    </select>
                                    <div class="invalid-feedback" id="is_active-error">
                                        @error('is_active') {{ $message }} @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Paket yang tidak aktif tidak akan muncul di pilihan pelanggan
                                    </small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">
                                        <i class="fas fa-align-left mr-1"></i>Deskripsi
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="5" 
                                              placeholder="Masukkan deskripsi detail tentang paket ini...">{{ old('description', $package->description) }}</textarea>
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
                            <button type="submit" class="btn btn-warning btn-lg" id="submit-btn">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
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
                        <i class="fas fa-box mr-1"></i>Info Paket
                    </h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-placeholder bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                            <i class="fas fa-box"></i>
                        </div>
                        <h5 class="mt-2 mb-1">{{ $package->name }}</h5>
                        <p class="text-muted mb-0">
                            @if($package->type == 'internet')
                                <span class="badge badge-warning"><i class="fas fa-wifi"></i> Internet</span>
                            @else
                                <span class="badge badge-info"><i class="fas fa-network-wired"></i> Metro</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="info-item mb-3">
                        <label class="font-weight-bold text-muted">Harga:</label>
                        <div class="text-primary font-weight-bold">
                            Rp {{ number_format($package->price, 0, ',', '.') }}
                        </div>
                        <div class="alert alert-light p-2 mt-1">
                            <div class="d-flex justify-content-between">
                                <span>Harga Dasar:</span>
                                <span>Rp {{ number_format($package->base_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>PPN (11%):</span>
                                <span>Rp {{ number_format($package->tax_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between font-weight-bold mt-1 pt-1 border-top">
                                <span>Total Harga:</span>
                                <span>Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-item mb-3">
                        <label class="font-weight-bold text-muted">Status:</label>
                        <div>
                            @if ($package->is_active)
                                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Aktif</span>
                            @else
                                <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Tidak Aktif</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="info-item mb-3">
                        <label class="font-weight-bold text-muted">Jumlah Pelanggan:</label>
                        <div>
                            <span class="badge badge-secondary">
                                <i class="fas fa-users"></i> {{ $package->customers()->count() }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="info-item mb-3">
                        <label class="font-weight-bold text-muted">Dibuat:</label>
                        <div>{{ $package->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    
                    <div class="info-item mb-3">
                        <label class="font-weight-bold text-muted">Terakhir Update:</label>
                        <div>{{ $package->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
            
            <div class="card card-outline card-warning mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lightbulb mr-1"></i>Tips & Panduan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle text-warning"></i> Perubahan Harga</h6>
                        <p class="mb-0">Perubahan harga akan mempengaruhi invoice yang akan datang. Pastikan perubahan sudah disetujui.</p>
                    </div>
                    
                    <div class="alert alert-info">
                        <h6><i class="fas fa-toggle-on text-success"></i> Status Paket</h6>
                        <p class="mb-0">Nonaktifkan paket jika tidak ingin pelanggan baru mendaftar menggunakan paket ini.</p>
                    </div>
                    
                    <div class="alert alert-success">
                        <h6><i class="fas fa-save text-primary"></i> Menyimpan</h6>
                        <p class="mb-0">Klik "Simpan Perubahan" untuk mengupdate data paket.</p>
                    </div>
                </div>
            </div>
            
            @if ($package->customers()->count() > 0)
                <div class="card card-outline card-danger mt-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Peringatan
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-users text-danger"></i> Paket Digunakan</h6>
                            <p class="mb-0">Paket ini digunakan oleh {{ $package->customers()->count() }} pelanggan. Perubahan harga akan mempengaruhi invoice yang akan datang.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="help-modal" tabindex="-1" role="dialog" aria-labelledby="help-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-white" id="help-modal-label">
                        <i class="fas fa-question-circle mr-2"></i>Panduan Edit Paket
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-box text-primary"></i> Data Paket</h6>
                            <p class="text-muted">Edit nama, tipe, dan deskripsi paket sesuai kebutuhan. Pastikan informasi tetap akurat.</p>
                            
                            <h6><i class="fas fa-money-bill-wave text-success"></i> Harga</h6>
                            <p class="text-muted">Perubahan harga akan mempengaruhi invoice yang akan datang. Pastikan perubahan sudah disetujui.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-toggle-on text-info"></i> Status</h6>
                            <p class="text-muted">Aktifkan/nonaktifkan paket. Paket yang tidak aktif tidak akan muncul di pilihan pelanggan baru.</p>
                            
                            <h6><i class="fas fa-save text-warning"></i> Menyimpan</h6>
                            <p class="text-muted">Klik "Simpan Perubahan" untuk mengupdate data paket. Pastikan semua data sudah benar.</p>
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
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255,193,7,0.25);
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
        
        /* Info Item */
        .info-item label {
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }
        
        .info-item div {
            font-size: 1rem;
        }
        
        /* Avatar Placeholder */
        .avatar-placeholder {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
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
            
            .avatar-placeholder {
                width: 60px !important;
                height: 60px !important;
                font-size: 1.5rem !important;
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
            
            // Initialize character count on page load
            $('#char-count').text($('#description').val().length);
            
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
            
            $('#is_active').on('change', function() {
                validateStatus();
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
            
            function validateStatus() {
                const status = $('#is_active').val();
                const statusError = $('#is_active-error');
                
                if (status === '') {
                    $('#is_active').addClass('is-invalid');
                    statusError.text('Pilih status paket');
                    return false;
                } else {
                    $('#is_active').removeClass('is-invalid');
                    statusError.text('');
                    return true;
                }
            }
            
            function validateForm() {
                const nameValid = validateName();
                const typeValid = validateType();
                const priceValid = validatePrice();
                const statusValid = validateStatus();
                
                return nameValid && typeValid && priceValid && statusValid;
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

            function numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Initial call to update calculations on page load
            updatePriceCalculations();
        });
    </script>
@stop 