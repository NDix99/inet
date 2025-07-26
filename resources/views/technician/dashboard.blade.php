@extends('adminlte::page')

@section('title', 'Dashboard Teknisi')

@section('content_header')
    <h1>Dashboard Teknisi</h1>
    <div class="mt-2">
        <a href="{{ route('technician.customers.create') }}" class="btn btn-sm btn-primary mr-2">
            <i class="fas fa-user-plus"></i> Tambah Pelanggan Baru
        </a>
        <a href="{{ route('technician.customers.index') }}" class="btn btn-sm btn-info mr-2">
            <i class="fas fa-users"></i> Lihat Semua Pelanggan
        </a>
        <a href="{{ route('technician.financial.report') }}" class="btn btn-sm btn-success">
            <i class="fas fa-money-bill-wave"></i> Laporan Pendapatan
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalCustomers }}</h3>
                    <p>Total Pelanggan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('technician.customers.index') }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalInvoices }}</h3>
                    <p>Total Invoice</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <a href="{{ route('technician.financial.report') }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Rp {{ number_format($totalFee, 0, ',', '.') }}</h3>
                    <p>Total Pendapatan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <a href="{{ route('technician.financial.report') }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pelanggan Terbaru</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table m-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Paket</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestCustomers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->package->name ?? 'Tidak ada paket' }}</td>
                                <td>
                                    <span class="badge badge-{{ $customer->is_active ? 'success' : 'danger' }}">
                                        {{ $customer->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('technician.customers.show', $customer) }}" class="btn btn-xs btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data pelanggan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-center">
            <a href="{{ route('technician.customers.index') }}" class="uppercase">Lihat Semua Pelanggan</a>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Dashboard Teknisi loaded!');
    </script>
@stop 