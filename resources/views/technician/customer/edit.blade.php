@extends('adminlte::page')

@section('title', 'Edit Pelanggan')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Edit Pelanggan</h1>
        <a href="{{ route('technician.customers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('technician.customers.update', $customer) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nama Pelanggan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $customer->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $customer->email) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address', $customer->address) }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="package_id">Paket Internet <span class="text-danger">*</span></label>
                            <select class="form-control" id="package_id" name="package_id" required>
                                <option value="">-- Pilih Paket --</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" {{ (old('package_id', $customer->package_id) == $package->id) ? 'selected' : '' }}>
                                        {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="billing_date">Tanggal Tagihan <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="billing_date" name="billing_date" value="{{ old('billing_date', $customer->billing_date->format('Y-m-d')) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Koordinat Lokasi</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="latitude" name="latitude" value="{{ old('latitude', $customer->latitude) }}" placeholder="Latitude">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="longitude" name="longitude" value="{{ old('longitude', $customer->longitude) }}" placeholder="Longitude">
                                </div>
                            </div>
                            <small class="form-text text-muted">Opsional - Isi jika ingin menandai lokasi pelanggan di peta</small>
                        </div>
                        <div class="form-group">
                            <button id="get-location" type="button" class="btn btn-info btn-sm">
                                <i class="fas fa-map-marker-alt"></i> Dapatkan Lokasi Saat Ini
                            </button>
                            @if($customer->latitude && $customer->longitude)
                                <a href="https://www.google.com/maps?q={{ $customer->latitude }},{{ $customer->longitude }}" target="_blank" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-map"></i> Lihat di Google Maps
                                </a>
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ old('is_active', $customer->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Pelanggan Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tombol untuk mendapatkan lokasi saat ini
            document.getElementById('get-location').addEventListener('click', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        document.getElementById('latitude').value = position.coords.latitude;
                        document.getElementById('longitude').value = position.coords.longitude;
                    }, function(error) {
                        alert('Error mendapatkan lokasi: ' + error.message);
                    });
                } else {
                    alert('Geolocation tidak didukung oleh browser ini.');
                }
            });
        });
    </script>
@stop 