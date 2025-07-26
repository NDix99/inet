@extends('adminlte::page')

@section('title', 'Laporan Keuangan Mitra')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-1">
                <i class="fas fa-handshake mr-2"></i>Laporan Keuangan Mitra
            </h1>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> Analisis keuangan mitra (teknisi) dan distribusi fee
            </p>
        </div>
    </div>
@stop

@section('content')
    <!-- Welcome Modal -->
    <div class="modal fade" id="welcomeModal" tabindex="-1" role="dialog" aria-labelledby="welcomeModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="welcomeModalLabel">
                        <i class="fas fa-exclamation-circle mr-2"></i>Perhatian Penting
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle mr-2"></i>Harap Perhatikan!</h5>
                        <p class="mb-0">Halaman ini berisi informasi keuangan yang sensitif dan memerlukan perhatian khusus dalam penggunaannya.</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body p-3">
                                    <h6 class="card-title"><i class="fas fa-print text-primary mr-2"></i>Pencetakan Laporan</h6>
                                    <p class="card-text small">Pencetakan laporan akan mengubah status cetak menjadi "Tercetak". Pastikan Anda mencetak laporan hanya saat benar-benar diperlukan.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body p-3">
                                    <h6 class="card-title"><i class="fas fa-money-bill-wave text-success mr-2"></i>Status Pembayaran</h6>
                                    <p class="card-text small">Perubahan status pembayaran harus sesuai dengan pembayaran yang telah dilakukan. Jangan mengubah status menjadi "Lunas" jika pembayaran belum dilakukan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body p-3">
                                    <h6 class="card-title"><i class="fas fa-undo text-danger mr-2"></i>Reset Status</h6>
                                    <p class="card-text small">Fitur reset status cetak hanya boleh digunakan dalam keadaan tertentu, seperti kesalahan pencetakan atau perubahan data setelah pencetakan.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body p-3">
                                    <h6 class="card-title"><i class="fas fa-book text-info mr-2"></i>Panduan Lengkap</h6>
                                    <p class="card-text small">Klik tombol <strong>"Panduan Penggunaan"</strong> di bagian filter untuk melihat panduan lengkap tentang penggunaan halaman ini.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-check mt-3">
                        <input type="checkbox" class="form-check-input" id="dontShowAgain">
                        <label class="form-check-label" for="dontShowAgain">Jangan tampilkan lagi</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info mr-2" data-dismiss="modal" data-toggle="modal" data-target="#help-modal">
                        <i class="fas fa-book mr-1"></i>Lihat Panduan
                    </button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">
                        <i class="fas fa-check mr-1"></i>Saya Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card card-outline card-primary mb-4">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-filter mr-1"></i>Filter Laporan
            </h3>
            <div class="card-tools">
                <span class="badge badge-primary mr-2">
                    <i class="fas fa-calendar"></i> Periode
                </span>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.financial.report') }}" method="GET" id="filter-form">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="start_date" class="font-weight-bold">
                                <i class="fas fa-calendar-plus text-primary mr-1"></i>Dari Tanggal
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar-plus"></i>
                                    </span>
                                </div>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}" required>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Tanggal awal periode laporan
                            </small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="end_date" class="font-weight-bold">
                                <i class="fas fa-calendar-times text-primary mr-1"></i>Sampai Tanggal
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar-times"></i>
                                    </span>
                                </div>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}" required>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Tanggal akhir periode laporan
                            </small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="payment_status" class="font-weight-bold">
                                <i class="fas fa-money-bill-wave text-primary mr-1"></i>Status Pembayaran
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </span>
                                </div>
                                <select class="form-control" id="payment_status" name="payment_status">
                                    <option value="">Semua Status</option>
                                    <option value="paid" {{ $paymentStatus === 'paid' ? 'selected' : '' }}>Lunas</option>
                                    <option value="unpaid" {{ $paymentStatus === 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                                </select>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Filter berdasarkan status pembayaran
                            </small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="d-flex flex-wrap">
                                <button type="submit" class="btn btn-primary mb-2 mr-2">
                                    <i class="fas fa-filter mr-2"></i>Filter Laporan
                                </button>
                                <button type="button" class="btn btn-secondary mb-2 mr-2" id="reset-filter">
                                    <i class="fas fa-undo mr-1"></i> Reset Filter
                                </button>
                                <a href="{{ route('admin.financial.report.print', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-success mb-2 mr-2" target="_blank">
                                    <i class="fas fa-print mr-1"></i> Cetak Laporan
                                </a>
                                <button type="button" class="btn btn-info mb-2" data-toggle="modal" data-target="#help-modal">
                                    <i class="fas fa-question-circle mr-1"></i> Panduan Penggunaan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-primary">
                <span class="info-box-icon"><i class="fas fa-handshake"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Mitra</span>
                    <span class="info-box-number">{{ count($technicianData) }}</span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">
                        <i class="fas fa-users"></i> Teknisi aktif
                    </span>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-success">
                <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Fee Mitra</span>
                    <span class="info-box-number">Rp {{ number_format($totalFee, 0, ',', '.') }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">
                        <i class="fas fa-percentage"></i> Fee untuk mitra
                    </span>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-danger">
                <span class="info-box-icon"><i class="fas fa-building"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Fee PT</span>
                    <span class="info-box-number">Rp {{ number_format($totalPtFee, 0, ',', '.') }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-danger" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">
                        <i class="fas fa-percentage"></i> Fee untuk perusahaan
                    </span>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-6 col-sm-6 col-12 mb-3">
            <div class="info-box bg-gradient-warning">
                <span class="info-box-icon"><i class="fas fa-receipt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total PPN</span>
                    <span class="info-box-number">Rp {{ number_format($totalPPN, 0, ',', '.') }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">
                        <i class="fas fa-percentage"></i> PPN (11%)
                    </span>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-12 col-sm-12 col-12 mb-3">
            <div class="info-box bg-gradient-info">
                <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Revenue</span>
                    <span class="info-box-number">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-info" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">
                        <i class="fas fa-calendar-alt"></i> Periode {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>Distribusi Fee Mitra
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-info mr-2">
                            <i class="fas fa-chart"></i> Chart
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="feeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i>Ringkasan Mitra
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-success mr-2">
                            <i class="fas fa-calculator"></i> Summary
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="partner-summary">
                        <div class="summary-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    <i class="fas fa-users mr-2"></i>Total Mitra
                                </span>
                                <span class="font-weight-bold text-primary">
                                    {{ count($technicianData) }} mitra
                                </span>
                            </div>
                        </div>
                        
                        <div class="summary-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    <i class="fas fa-file-invoice mr-2"></i>Total Invoice
                                </span>
                                <span class="font-weight-bold text-success">
                                    {{ collect($technicianData)->sum('invoice_count') }} invoice
                                </span>
                            </div>
                        </div>
                        
                        <div class="summary-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    <i class="fas fa-calendar-alt mr-2"></i>Periode Laporan
                                </span>
                                <span class="font-weight-bold text-info">
                                    {{ \Carbon\Carbon::parse($startDate)->diffInDays($endDate) + 1 }} hari
                                </span>
                            </div>
                        </div>
                        
                        <div class="summary-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    <i class="fas fa-money-bill-wave mr-2"></i>Rata-rata Fee per Mitra
                                </span>
                                <span class="font-weight-bold text-warning">
                                    Rp {{ count($technicianData) > 0 ? number_format($totalFee / count($technicianData), 0, ',', '.') : 0 }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Mitra Table -->
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table mr-1"></i>Detail Mitra
            </h3>
            <div class="card-tools">
                <span class="badge badge-primary mr-2">
                    <i class="fas fa-list"></i> {{ count($technicianData) }} Mitra
                </span>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="partners-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Mitra</th>
                            <th>Pelanggan</th>
                            <th>Invoice</th>
                            <th>Total Revenue</th>
                            <th>Fee Mitra</th>
                            <th>Fee PT</th>
                            <th>PPN</th>
                            <th>Status Cetak</th>
                            <th>Status Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($technicianData as $index => $data)
                            @php
                                $allPrinted = true;
                                foreach ($data['technician']->customers as $customer) {
                                    foreach ($customer->invoices as $inv) {
                                        if (!$inv->is_printed) { $allPrinted = false; break 2; }
                                    }
                                }
                            @endphp
                            <tr>
                                <td>
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-hashtag"></i> {{ $index + 1 }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="partner-avatar bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mr-2" style="width: 35px; height: 35px; font-size: 0.875rem;">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-primary">{{ $data['technician']->name }}</div>
                                            <small class="text-muted">
                                                <i class="fas fa-envelope"></i> {{ $data['technician']->email }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="customer-icon bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mr-2" style="width: 30px; height: 30px; font-size: 0.75rem;">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <span class="font-weight-bold">{{ $data['customers_count'] }} pelanggan</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="invoice-icon bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mr-2" style="width: 30px; height: 30px; font-size: 0.75rem;">
                                            <i class="fas fa-file-invoice"></i>
                                        </div>
                                        <span class="font-weight-bold">{{ $data['invoice_count'] }} invoice</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-money-bill-wave text-success mr-2"></i>
                                        <div>
                                        <span class="font-weight-bold text-success">Rp {{ number_format($data['revenue'], 0, ',', '.') }}</span>
                                            <div class="small text-muted">Harga dasar (sebelum PPN)</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-handshake text-primary mr-2"></i>
                                        <div>
                                        <span class="font-weight-bold text-primary">Rp {{ number_format($data['fee'], 0, ',', '.') }}</span>
                                            <div class="small text-muted">
                                                <i class="fas fa-percentage"></i> {{ number_format($data['avg_fee_percentage'], 1) }}% dari harga dasar
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-primary mt-1" data-toggle="modal" data-target="#feeMitraDetailModal{{ $data['technician']->id }}">
                                                <i class="fas fa-handshake mr-1"></i> Detail Fee Mitra
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Modal Detail Perhitungan Fee Mitra -->
                                    <div class="modal fade" id="feeMitraDetailModal{{ $data['technician']->id }}" tabindex="-1" role="dialog" aria-labelledby="feeMitraDetailModalLabel{{ $data['technician']->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-gradient-primary text-white py-3 align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center mr-3" style="width:48px;height:48px;font-size:2rem;">
                                                            <i class="fas fa-handshake"></i>
                                                        </div>
                                                        <div>
                                                            <h5 class="modal-title mb-0 font-weight-bold" id="feeMitraDetailModalLabel{{ $data['technician']->id }}">
                                                                Fee Mitra: {{ $data['technician']->name }}
                                                            </h5>
                                                            <small class="text-white-50">{{ $data['technician']->email }}</small>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="close text-white ml-2" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6 mb-2 mb-md-0">
                                                            <div class="bg-light rounded p-3 h-100 d-flex flex-column justify-content-center">
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <span class="badge badge-primary p-2 mr-2" style="font-size:1.2rem;"><i class="fas fa-coins"></i></span>
                                                                    <span class="h4 mb-0 font-weight-bold text-primary">Rp {{ number_format($data['fee'], 0, ',', '.') }}</span>
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="badge badge-info mr-2"><i class="fas fa-percentage"></i> {{ number_format($data['avg_fee_percentage'], 1) }}%</span>
                                                                    <span class="text-muted">dari harga dasar</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <!-- Bagian rumus dihapus agar tampilan lebih profesional -->
                                                        </div>
                                                    </div>
                                                    <h6 class="border-bottom pb-2 mb-3 font-weight-bold"><i class="fas fa-table mr-1"></i> Detail Per Invoice</h6>
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-striped table-hover mb-0">
                                                            <thead class="thead-light sticky-top">
                                                                <tr>
                                                                    <th>Invoice</th>
                                                                    <th>Harga Dasar</th>
                                                                    <th>Fee %</th>
                                                                    <th>Fee Mitra</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($data['fee_details'] as $detail)
                                                                <tr>
                                                                    <td>{{ $detail['invoice_number'] }}</td>
                                                                    <td>Rp {{ number_format($detail['base_price'], 0, ',', '.') }}</td>
                                                                    <td><span class="badge badge-info">{{ number_format($detail['fee_percentage'], 1) }}%</span></td>
                                                                    <td class="text-primary font-weight-bold">Rp {{ number_format($detail['fee_amount'], 0, ',', '.') }}</td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr class="font-weight-bold bg-light">
                                                                    <td>Total:</td>
                                                                    <td>Rp {{ number_format($data['revenue'], 0, ',', '.') }}</td>
                                                                    <td></td>
                                                                    <td class="text-primary">Rp {{ number_format($data['fee'], 0, ',', '.') }}</td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="modal-footer py-2">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-building text-danger mr-2"></i>
                                        <div>
                                            <span class="font-weight-bold text-danger">Rp {{ number_format($data['total_pt_fee'], 0, ',', '.') }}</span>
                                            <div class="small text-muted">
                                                <i class="fas fa-percentage"></i> {{ number_format(100 - $data['avg_fee_percentage'], 1) }}% dari harga dasar
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger mt-1" data-toggle="modal" data-target="#feePTDetailModal{{ $data['technician']->id }}">
                                                <i class="fas fa-building mr-1"></i> Detail Fee PT
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Modal Detail Perhitungan Fee PT -->
                                    <div class="modal fade" id="feePTDetailModal{{ $data['technician']->id }}" tabindex="-1" role="dialog" aria-labelledby="feePTDetailModalLabel{{ $data['technician']->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-gradient-danger text-white py-3 align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <div class="rounded-circle bg-white text-danger d-flex align-items-center justify-content-center mr-3" style="width:48px;height:48px;font-size:2rem;">
                                                            <i class="fas fa-building"></i>
                                                        </div>
                                                        <div>
                                                            <h5 class="modal-title mb-0 font-weight-bold" id="feePTDetailModalLabel{{ $data['technician']->id }}">
                                                                Fee PT: {{ $data['technician']->name }}
                                                            </h5>
                                                            <small class="text-white-50">{{ $data['technician']->email }}</small>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="close text-white ml-2" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6 mb-2 mb-md-0">
                                                            <div class="bg-light rounded p-3 h-100 d-flex flex-column justify-content-center">
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <span class="badge badge-danger p-2 mr-2" style="font-size:1.2rem;"><i class="fas fa-building"></i></span>
                                                                    <span class="h4 mb-0 font-weight-bold text-danger">Rp {{ number_format($data['total_pt_fee'], 0, ',', '.') }}</span>
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="badge badge-warning mr-2"><i class="fas fa-percentage"></i> {{ number_format(100 - $data['avg_fee_percentage'], 1) }}%</span>
                                                                    <span class="text-muted">dari harga dasar</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <!-- Bagian rumus dihapus agar tampilan lebih profesional -->
                                                        </div>
                                                    </div>
                                                    <h6 class="border-bottom pb-2 mb-3 font-weight-bold"><i class="fas fa-table mr-1"></i> Detail Per Invoice</h6>
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-striped table-hover mb-0">
                                                            <thead class="thead-light sticky-top">
                                                                <tr>
                                                                    <th>Invoice</th>
                                                                    <th>Harga Dasar</th>
                                                                    <th>Fee %</th>
                                                                    <th>Fee PT</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($data['fee_details'] as $detail)
                                                                <tr>
                                                                    <td>{{ $detail['invoice_number'] }}</td>
                                                                    <td>Rp {{ number_format($detail['base_price'], 0, ',', '.') }}</td>
                                                                    <td><span class="badge badge-warning">{{ number_format(100 - $detail['fee_percentage'], 1) }}%</span></td>
                                                                    <td class="text-danger font-weight-bold">Rp {{ number_format($detail['pt_fee_amount'], 0, ',', '.') }}</td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr class="font-weight-bold bg-light">
                                                                    <td>Total:</td>
                                                                    <td>Rp {{ number_format($data['revenue'], 0, ',', '.') }}</td>
                                                                    <td></td>
                                                                    <td class="text-danger">Rp {{ number_format($data['total_pt_fee'], 0, ',', '.') }}</td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="modal-footer py-2">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-receipt text-warning mr-2"></i>
                                        <span class="font-weight-bold text-warning">Rp {{ number_format($data['ppn'], 0, ',', '.') }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($data['invoice_count'] == 0)
                                        <span class="badge badge-secondary">Tidak Ada Invoice</span>
                                    @elseif($data['is_printed'])
                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Tercetak</span>
                                        @if($data['printed_at'])
                                            <br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($data['printed_at'])->format('d/m/Y H:i') }}
                                            </small>
                                        @endif
                                    @else
                                        <span class="badge badge-secondary"><i class="fas fa-times-circle"></i> Belum Dicetak</span>
                                    @endif
                                </td>
                                <td>
                                    @if($data['invoice_count'] == 0)
                                        <span class="badge badge-secondary">Tidak Ada Invoice</span>
                                    @elseif($data['is_paid'])
                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Lunas</span>
                                        @if($data['payment_date'])
                                            <br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($data['payment_date'])->format('d/m/Y H:i') }}
                                            </small>
                                        @endif
                                        @if($data['payment_notes'])
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-sticky-note"></i> {{ $data['payment_notes'] }}
                                            </small>
                                        @endif
                                    @else
                                        <span class="badge badge-warning"><i class="fas fa-times-circle"></i> Belum Lunas</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailModal{{ $data['technician']->id }}" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('admin.financial.report.print', ['start_date' => $startDate, 'end_date' => $endDate, 'technician_id' => $data['technician']->id]) }}" class="btn btn-sm btn-primary" target="_blank" title="Cetak Laporan Mitra">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>

                                    <!-- Modal Detail Mitra -->
                                    <div class="modal fade" id="detailModal{{ $data['technician']->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel{{ $data['technician']->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info">
                                                    <h5 class="modal-title text-white" id="detailModalLabel{{ $data['technician']->id }}">
                                                        <i class="fas fa-user-tie mr-2"></i>Detail Mitra
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="info-item mb-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="icon-wrapper bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                                                        <i class="fas fa-user-tie"></i>
                                                                    </div>
                                                                    <div>
                                                                        <small class="text-muted">Nama Mitra</small>
                                                                        <div class="font-weight-bold">{{ $data['technician']->name }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="info-item mb-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="icon-wrapper bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                                                        <i class="fas fa-users"></i>
                                                                    </div>
                                                                    <div>
                                                                        <small class="text-muted">Total Pelanggan</small>
                                                                        <div class="font-weight-bold">{{ $data['customers_count'] }} pelanggan</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="info-item mb-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="icon-wrapper bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                                                        <i class="fas fa-file-invoice"></i>
                                                                    </div>
                                                                    <div>
                                                                        <small class="text-muted">Total Invoice</small>
                                                                        <div class="font-weight-bold">{{ $data['invoice_count'] }} invoice</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="info-item mb-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="icon-wrapper bg-info text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                                                        <i class="fas fa-money-bill-wave"></i>
                                                                    </div>
                                                                    <div>
                                                                        <small class="text-muted">Total Revenue</small>
                                                                        <div class="font-weight-bold text-success">Rp {{ number_format($data['revenue'], 0, ',', '.') }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="info-item mb-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="icon-wrapper bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                                                        <i class="fas fa-handshake"></i>
                                                                    </div>
                                                                    <div>
                                                                                                                                        <small class="text-muted">Fee Mitra</small>
                                                                        <div class="font-weight-bold text-primary">Rp {{ number_format($data['fee'], 0, ',', '.') }}</div>
                                                                <div class="small text-muted">{{ number_format($data['avg_fee_percentage'], 1) }}% dari harga dasar</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="info-item mb-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="icon-wrapper bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                                                        <i class="fas fa-receipt"></i>
                                                                    </div>
                                                                    <div>
                                                                                                                                        <small class="text-muted">PPN</small>
                                                                        <div class="font-weight-bold text-warning">Rp {{ number_format($data['ppn'], 0, ',', '.') }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                        <i class="fas fa-times"></i> Tutup
                                                    </button>
                                                    <a href="{{ route('admin.financial.report.print', ['start_date' => $startDate, 'end_date' => $endDate, 'technician_id' => $data['technician']->id]) }}" class="btn btn-primary" target="_blank">
                                                        <i class="fas fa-print"></i> Cetak Laporan Mitra
                                                    </a>
                                                    @if($data['is_printed'])
                                                        <form action="{{ route('admin.financial.report.reset.print.status') }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <input type="hidden" name="technician_id" value="{{ $data['technician']->id }}">
                                                            <input type="hidden" name="periode_awal" value="{{ $startDate }}">
                                                            <input type="hidden" name="periode_akhir" value="{{ $endDate }}">
                                                            <button type="submit" class="btn btn-danger" onclick="return confirm('PERHATIAN! Anda akan mereset status cetak laporan mitra ini.\n\nTindakan ini sebaiknya hanya dilakukan jika:\n- Terjadi kesalahan pada pencetakan sebelumnya\n- Ada perubahan data setelah pencetakan\n\nApakah Anda yakin ingin melanjutkan?')">
                                                                <i class="fas fa-undo"></i> Reset Status Cetak
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <!-- Tombol Update Status Pembayaran -->
                                                    <button type="button" class="btn {{ $data['is_paid'] ? 'btn-warning' : 'btn-success' }}" data-toggle="modal" data-target="#paymentModal{{ $data['technician']->id }}">
                                                        <i class="fas fa-money-bill-wave"></i> {{ $data['is_paid'] ? 'Ubah Status Bayar' : 'Set Lunas' }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-light">
                            <th colspan="4" class="text-right font-weight-bold">
                                <i class="fas fa-calculator mr-2"></i>Total:
                            </th>
                            <th class="text-right font-weight-bold text-success">
                                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                            </th>
                            <th class="text-right font-weight-bold text-primary">
                                Rp {{ number_format($totalFee, 0, ',', '.') }}
                            </th>
                            <th class="text-right font-weight-bold text-danger">
                                Rp {{ number_format($totalPtFee, 0, ',', '.') }}
                            </th>
                            <th class="text-right font-weight-bold text-warning">
                                Rp {{ number_format($totalPPN, 0, ',', '.') }}
                            </th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="help-modal" tabindex="-1" role="dialog" aria-labelledby="help-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="help-modal-label">
                        <i class="fas fa-question-circle mr-2"></i>Panduan Laporan Keuangan Mitra
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-filter text-primary"></i> Filter Periode</h6>
                            <p class="text-muted">Pilih periode laporan untuk melihat data keuangan mitra.</p>
                            
                            <h6><i class="fas fa-chart-pie text-info"></i> Grafik Fee</h6>
                            <p class="text-muted">Visualisasi distribusi fee mitra berdasarkan revenue.</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-print text-success"></i> Cetak Laporan</h6>
                            <p class="text-muted">Cetak laporan keuangan mitra dalam format PDF.</p>
                            
                            <h6><i class="fas fa-table text-warning"></i> Detail Mitra</h6>
                            <p class="text-muted">Lihat detail semua mitra dan perhitungan fee mereka.</p>
                        </div>
                    </div>
                    <div class="alert alert-primary mt-3">
                        <i class="fas fa-lightbulb"></i> 
                        <strong>Tips:</strong> Revenue adalah total harga dasar (sebelum pajak) dari pelanggan teknisi/mitra. Fee mitra dihitung berdasarkan persentase fee yang telah diatur di setiap paket. Fee PT adalah komplemen dari fee mitra (100% - persentase fee mitra). PPN adalah total PPN dari setiap pelanggan teknisi/mitra.
                    </div>
                    
                    <div class="card bg-light mt-3">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-calculator mr-2"></i>Cara Perhitungan Fee Mitra</h6>
                        </div>
                        <div class="card-body">
                            <ol>
                                <li>Setiap paket internet memiliki persentase fee yang dapat diatur di menu <strong>Pengaturan Fee Teknisi</strong></li>
                                <li>Fee Mitra dihitung dengan rumus: <code>Fee Mitra = Harga Dasar × Persentase Fee</code></li>
                                <li>Fee PT dihitung dengan rumus: <code>Fee PT = Harga Dasar × (100% - Persentase Fee)</code></li>
                                <li>Harga dasar adalah harga paket sebelum PPN (harga bersih)</li>
                                <li>Sistem secara otomatis menghitung harga dasar dengan rumus: <code>Harga Dasar = Harga Total / (1 + Tarif PPN)</code></li>
                                <li>Contoh: Jika harga total Rp 110.000 dengan PPN 11%, maka:
                                    <ul>
                                        <li>Harga dasar = Rp 110.000 / 1,11 = <strong>Rp 99.099</strong></li>
                                        <li>Jika persentase fee mitra 25%, maka:
                                            <ul>
                                                <li>Fee Mitra = Rp 99.099 × 25% = <strong>Rp 24.775</strong></li>
                                                <li>Fee PT = Rp 99.099 × 75% = <strong>Rp 74.324</strong></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li>Total fee adalah jumlah dari semua fee invoice pelanggan mitra</li>
                                <li>Untuk melihat detail perhitungan, klik tombol <strong>Detail Perhitungan</strong> di kolom Fee Mitra</li>
                            </ol>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Perhatian:</strong> Pastikan persentase fee sudah diatur dengan benar di setiap paket internet untuk hasil perhitungan yang akurat.
                            </div>
                        </div>
                    </div>
                    
                    <div class="card bg-light mt-3">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="fas fa-print mr-2"></i>Panduan Pencetakan Laporan</h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Penting:</strong> Pencetakan laporan akan mengubah status cetak menjadi "Tercetak" dan mencatat waktu pencetakan. Pastikan Anda mencetak laporan hanya saat benar-benar diperlukan.
                            </div>
                            
                            <h6><i class="fas fa-check-circle text-success mr-1"></i>Cara Mencetak Laporan:</h6>
                            <ol>
                                <li>Klik tombol <strong><i class="fas fa-print"></i> Cetak Semua</strong> untuk mencetak laporan semua mitra</li>
                                <li>Atau, klik tombol <strong><i class="fas fa-print"></i></strong> pada baris mitra tertentu untuk mencetak laporan mitra tersebut</li>
                                <li>Atau, klik tombol <strong><i class="fas fa-eye"></i> Detail</strong> lalu klik <strong><i class="fas fa-print"></i> Cetak Laporan Mitra</strong> di modal detail</li>
                                <li>Setelah mencetak, status cetak akan berubah menjadi "Tercetak" dengan tanggal dan waktu pencetakan</li>
                            </ol>
                            
                            <h6><i class="fas fa-exclamation-triangle text-danger mr-1"></i>Peringatan:</h6>
                            <ul>
                                <li>Jangan mencetak laporan yang sama berulang kali tanpa alasan yang jelas</li>
                                <li>Setiap pencetakan akan tercatat dalam sistem dan dapat dilihat oleh superadmin</li>
                                <li>Jika terjadi kesalahan pencetakan, gunakan fitur "Reset Status Cetak" dengan bijak</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="card bg-light mt-3">
                        <div class="card-header bg-warning text-white">
                            <h6 class="mb-0"><i class="fas fa-money-bill-wave mr-2"></i>Panduan Update Status Pembayaran</h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Penting:</strong> Status pembayaran menandakan apakah fee mitra sudah dibayarkan atau belum. Pastikan Anda mengubah status pembayaran hanya saat pembayaran benar-benar telah dilakukan.
                            </div>
                            
                            <h6><i class="fas fa-check-circle text-success mr-1"></i>Cara Update Status Pembayaran:</h6>
                            <ol>
                                <li>Klik tombol <strong><i class="fas fa-eye"></i> Detail</strong> pada baris mitra tertentu</li>
                                <li>Pada modal detail, klik tombol <strong><i class="fas fa-money-bill-wave"></i> Set Lunas</strong> atau <strong><i class="fas fa-money-bill-wave"></i> Ubah Status Bayar</strong></li>
                                <li>Pilih status pembayaran (Lunas/Belum Lunas) pada dropdown yang tersedia</li>
                                <li>Tambahkan catatan pembayaran jika diperlukan (misalnya: "Dibayar via transfer BCA")</li>
                                <li>Klik tombol <strong>Set Lunas</strong> atau <strong>Update Status</strong> untuk menyimpan perubahan</li>
                            </ol>
                            
                            <h6><i class="fas fa-exclamation-triangle text-danger mr-1"></i>Peringatan:</h6>
                            <ul>
                                <li>Jangan mengubah status menjadi "Lunas" jika pembayaran belum benar-benar dilakukan</li>
                                <li>Setiap perubahan status pembayaran akan tercatat dalam sistem dan dapat dilihat oleh superadmin</li>
                                <li>Selalu tambahkan catatan pembayaran yang jelas untuk memudahkan pelacakan</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="card bg-light mt-3">
                        <div class="card-header bg-danger text-white">
                            <h6 class="mb-0"><i class="fas fa-undo mr-2"></i>Panduan Reset Status Cetak</h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Perhatian:</strong> Fitur reset status cetak hanya boleh digunakan dalam keadaan tertentu, seperti kesalahan pencetakan atau perubahan data setelah pencetakan.
                            </div>
                            
                            <h6><i class="fas fa-check-circle text-success mr-1"></i>Cara Reset Status Cetak:</h6>
                            <ol>
                                <li>Klik tombol <strong><i class="fas fa-eye"></i> Detail</strong> pada baris mitra tertentu</li>
                                <li>Pada modal detail, klik tombol <strong><i class="fas fa-undo"></i> Reset Status Cetak</strong></li>
                                <li>Konfirmasi tindakan reset saat dialog konfirmasi muncul</li>
                                <li>Setelah reset, status cetak akan kembali menjadi "Belum Dicetak"</li>
                            </ol>
                            
                            <h6><i class="fas fa-exclamation-triangle text-danger mr-1"></i>Peringatan:</h6>
                            <ul>
                                <li>Fitur ini hanya boleh digunakan dalam keadaan yang benar-benar diperlukan</li>
                                <li>Setiap tindakan reset akan tercatat dalam sistem dan dapat dilihat oleh superadmin</li>
                                <li>Penggunaan fitur ini secara berlebihan dapat menimbulkan ketidakkonsistenan data</li>
                                <li>Jika ragu, konsultasikan dengan supervisor atau superadmin sebelum melakukan reset</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Update Status Pembayaran -->
    @foreach($technicianData as $data)
    <div class="modal fade" id="paymentModal{{ $data['technician']->id }}" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel{{ $data['technician']->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header {{ $data['is_paid'] ? 'bg-warning' : 'bg-success' }} text-white">
                    <h5 class="modal-title" id="paymentModalLabel{{ $data['technician']->id }}">
                        <i class="fas fa-money-bill-wave mr-2"></i>{{ $data['is_paid'] ? 'Ubah Status Pembayaran' : 'Set Status Lunas' }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.financial.report.update.payment.status') }}" method="POST">
                    @csrf
                    <input type="hidden" name="technician_id" value="{{ $data['technician']->id }}">
                    <input type="hidden" name="periode_awal" value="{{ $startDate }}">
                    <input type="hidden" name="periode_akhir" value="{{ $endDate }}">
                    
                    <div class="modal-body">
                        <div class="alert {{ $data['is_paid'] ? 'alert-warning' : 'alert-info' }}">
                            <i class="fas fa-info-circle mr-1"></i>
                            <strong>Mitra:</strong> {{ $data['technician']->name }}<br>
                            <strong>Periode:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}<br>
                            <strong>Total Fee:</strong> Rp {{ number_format($data['fee'], 0, ',', '.') }}
                        </div>
                        
                        <div class="form-group">
                            <label for="is_paid{{ $data['technician']->id }}">
                                <i class="fas fa-toggle-on mr-1"></i>Status Pembayaran
                            </label>
                            <select class="form-control" id="is_paid{{ $data['technician']->id }}" name="is_paid">
                                <option value="1" {{ $data['is_paid'] ? 'selected' : '' }}>Lunas</option>
                                <option value="0" {{ !$data['is_paid'] ? 'selected' : '' }}>Belum Lunas</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="payment_notes{{ $data['technician']->id }}">
                                <i class="fas fa-sticky-note mr-1"></i>Catatan Pembayaran
                            </label>
                            <textarea class="form-control" id="payment_notes{{ $data['technician']->id }}" name="payment_notes" rows="3" placeholder="Contoh: Dibayar via transfer BCA">{{ $data['payment_notes'] }}</textarea>
                            <small class="form-text text-muted">Opsional: Tambahkan catatan tentang pembayaran ini</small>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn {{ $data['is_paid'] ? 'btn-warning' : 'btn-success' }}" onclick="return confirm('Anda akan {{ $data['is_paid'] ? 'mengubah' : 'mengatur' }} status pembayaran mitra {{ $data['technician']->name }}.\n\nPastikan tindakan ini sudah benar dan sesuai dengan pembayaran yang telah dilakukan.\n\nApakah Anda yakin ingin melanjutkan?')">
                            <i class="fas fa-save mr-1"></i>{{ $data['is_paid'] ? 'Update Status' : 'Set Lunas' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.Chartjs', true)

@section('css')
    <style>
        /* Info Box Improvements */
        .info-box {
            transition: all 0.3s ease;
        }
        
        .info-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        /* Card Improvements */
        .card-outline {
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        
        /* Table Improvements */
        .table-hover tbody tr:hover {
            background-color: rgba(0,123,255,0.1);
        }
        
        /* Button Improvements */
        .btn-sm {
            transition: all 0.2s ease;
        }
        
        .btn-sm:hover {
            transform: translateY(-1px);
        }
        
        /* Modal Improvements */
        .modal-header {
            border-bottom: none;
        }
        
        .modal-footer {
            border-top: none;
        }
        
        /* Welcome Modal */
        #welcomeModal .card {
            height: 100%;
            transition: all 0.3s ease;
        }
        
        #welcomeModal .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        #welcomeModal .card-title {
            font-size: 1rem;
            font-weight: 600;
        }
        
        #welcomeModal .card-text {
            font-size: 0.85rem;
        }
        
        #welcomeModal .form-check {
            margin-left: 5px;
        }
        
        /* Partner Avatar */
        .partner-avatar {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }
        
        /* Customer Icon */
        .customer-icon {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        /* Invoice Icon */
        .invoice-icon {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        }
        
        /* Summary Item */
        .summary-item {
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }
        
        .summary-item:hover {
            background-color: rgba(40,167,69,0.05);
        }
        
        /* Gap utility */
        .gap-1 {
            gap: 0.25rem;
        }
        
        .gap-2 {
            gap: 0.5rem;
        }
        
        /* Responsive improvements */
        .small-box .inner h3 {
            font-size: 1.5rem;
        }
        
        .info-box-content {
            padding: 0.5rem;
        }
        
        .info-box-text {
            font-size: 0.875rem;
        }
        
        .info-box-number {
            font-size: 1.25rem;
        }
        
        .progress-description {
            font-size: 0.75rem;
        }
        
        /* Mobile optimizations */
        @media (max-width: 768px) {
            .info-box {
                margin-bottom: 1rem;
            }
            
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
            
            #welcomeModal .card {
                margin-bottom: 1rem;
            }
            
            #welcomeModal .modal-dialog {
                margin: 0.5rem;
            }
            
            #welcomeModal .modal-body {
                padding: 1rem;
            }
            
            #welcomeModal .card-body {
                padding: 0.75rem;
            }
            
            #welcomeModal .card-title {
                font-size: 0.9rem;
            }
            
            #welcomeModal .card-text {
                font-size: 0.8rem;
            }
            
            .small-box .inner h3 {
                font-size: 1.25rem;
            }
            
            .info-box-content {
                padding: 0.25rem;
            }
            
            .info-box-text {
                font-size: 0.75rem;
            }
            
            .info-box-number {
                font-size: 1rem;
            }
            
            .progress-description {
                font-size: 0.625rem;
            }
        }
        
        /* Table responsive improvements */
        .table-responsive {
            overflow-x: auto;
        }
        
        .table th, .table td {
            white-space: nowrap;
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
            // Fungsi untuk memeriksa apakah modal sudah pernah ditampilkan
            function checkWelcomeModal() {
                if (localStorage.getItem('financialWelcomeModalShown') !== 'true') {
                    $('#welcomeModal').modal('show');
                }
            }
            
            // Tampilkan modal saat halaman dimuat
            checkWelcomeModal();
            
            // Tangani checkbox "Jangan tampilkan lagi"
            $('#dontShowAgain').on('change', function() {
                if ($(this).is(':checked')) {
                    localStorage.setItem('financialWelcomeModalShown', 'true');
                } else {
                    localStorage.removeItem('financialWelcomeModalShown');
                }
            });
            
            // DataTables initialization
            $('#partners-table').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: '{{ asset("vendor/datatables/lang/Indonesian.json") }}'
                },
                order: [[4, "desc"]], // Urutkan berdasarkan total revenue (descending)
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="fas fa-copy"></i> Salin',
                        className: 'btn btn-sm btn-secondary'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-sm btn-success'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-sm btn-danger'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-sm btn-info'
                    }
                ]
            });
            
            // Chart.js initialization
            var ctx = document.getElementById('feeChart').getContext('2d');
            var feeChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: [
                        @foreach($technicianData as $data)
                            '{{ $data["technician"]->name }}',
                        @endforeach
                    ],
                    datasets: [{
                        data: [
                            @foreach($technicianData as $data)
                                {{ $data['fee'] }},
                            @endforeach
                        ],
                        backgroundColor: [
                            '#007bff',
                            '#28a745',
                            '#ffc107',
                            '#dc3545',
                            '#6c757d',
                            '#17a2b8',
                            '#fd7e14',
                            '#6f42c1'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var label = context.label || '';
                                    var value = context.parsed;
                                    var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    var percentage = ((value / total) * 100).toFixed(1);
                                    return label + ': Rp ' + new Intl.NumberFormat('id-ID').format(value) + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
            
            // Add hover effects to info boxes
            $('.info-box').hover(
                function() {
                    $(this).addClass('shadow-lg');
                },
                function() {
                    $(this).removeClass('shadow-lg');
                }
            );
            
            // Add loading animation to buttons
            $('.btn').click(function() {
                if (!$(this).hasClass('btn-tool')) {
                    $(this).append('<span class="spinner-border spinner-border-sm ml-1" role="status" aria-hidden="true"></span>');
                    setTimeout(() => {
                        $(this).find('.spinner-border').remove();
                    }, 1000);
                }
            });
            
            // Reset filter
            $('#reset-filter').click(function() {
                var today = new Date();
                var firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                var lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                
                $('#start_date').val(firstDay.toISOString().split('T')[0]);
                $('#end_date').val(lastDay.toISOString().split('T')[0]);
                
                $('#filter-form').submit();
            });
            
            // Date validation
            $('#end_date').on('change', function() {
                var startDate = new Date($('#start_date').val());
                var endDate = new Date($(this).val());
                
                if (endDate < startDate) {
                    alert('Tanggal akhir tidak boleh lebih kecil dari tanggal awal!');
                    $(this).val($('#start_date').val());
                }
            });
            
            // Responsive button text
            function updateButtonText() {
                if (window.innerWidth < 576) {
                    $('.dt-buttons .btn').each(function() {
                        var $btn = $(this);
                        var text = $btn.text();
                        if (text.includes('Salin')) {
                            $btn.html('<i class="fas fa-copy"></i>');
                        } else if (text.includes('Excel')) {
                            $btn.html('<i class="fas fa-file-excel"></i>');
                        } else if (text.includes('PDF')) {
                            $btn.html('<i class="fas fa-file-pdf"></i>');
                        } else if (text.includes('Print')) {
                            $btn.html('<i class="fas fa-print"></i>');
                        }
                    });
                }
            }
            
            // Update button text on load and resize
            updateButtonText();
            $(window).resize(updateButtonText);
        });
    </script>
@stop 