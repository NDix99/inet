@extends('adminlte::page')

@section('title', 'Tambah Pelanggan Baru')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Tambah Pelanggan Baru</h1>
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

            <form action="{{ route('technician.customers.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nama Pelanggan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="package_id">Paket Internet <span class="text-danger">*</span></label>
                            <select class="form-control" id="package_id" name="package_id" required>
                                <option value="">-- Pilih Paket --</option>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                        {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="billing_date">Tanggal Tagihan <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="billing_date" name="billing_date" value="{{ old('billing_date') ?? now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Koordinat Lokasi</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="latitude" name="latitude" value="{{ old('latitude') }}" placeholder="Latitude">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="longitude" name="longitude" value="{{ old('longitude') }}" placeholder="Longitude">
                                </div>
                            </div>
                            <small class="form-text text-muted">Opsional - Isi jika ingin menandai lokasi pelanggan di peta</small>
                        </div>
                        <div class="form-group">
                            <button id="get-location" type="button" class="btn btn-info btn-sm">
                                <i class="fas fa-map-marker-alt"></i> Dapatkan Lokasi Saat Ini
                            </button>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
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