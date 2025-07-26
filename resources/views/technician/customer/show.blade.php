@extends('adminlte::page')

@section('title', 'Detail Pelanggan')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detail Pelanggan</h1>
        <a href="{{ route('technician.customers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="mb-3">Informasi Pelanggan</h4>
            <table class="table table-bordered">
                <tr>
                    <th>Nama</th>
                    <td>{{ $customer->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $customer->email }}</td>
                </tr>
                <tr>
                    <th>Telepon</th>
                    <td>{{ $customer->phone }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $customer->address }}</td>
                </tr>
                <tr>
                    <th>Paket</th>
                    <td>{{ $customer->package->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Tanggal Tagihan</th>
                    <td>{{ $customer->billing_date ? \Carbon\Carbon::parse($customer->billing_date)->format('d/m/Y') : '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Riwayat Invoice</h3>
        </div>
        <div class="card-body">
            <table id="invoices-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Tanggal</th>
                        <th>Jatuh Tempo</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Status Cetak</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</td>
                            <td>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'overdue' ? 'danger' : 'warning') }}">
                                    {{ $invoice->status === 'paid' ? 'Lunas' : ($invoice->status === 'overdue' ? 'Terlambat' : 'Belum Lunas') }}
                                </span>
                            </td>
                            <td>
                                @if($invoice->is_printed_technician)
                                    <span class="badge badge-success">Tercetak</span>
                                @else
                                    <span class="badge badge-secondary">Belum Tercetak</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('technician.invoices.print', $invoice->id) }}" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fas fa-print"></i> Print
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada invoice untuk pelanggan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('js')
    <script>
        $(document).ready(function() {
            $('#invoices-table').DataTable({
                language: {
                    url: "{{ asset('vendor/datatables/lang/Indonesian.json') }}"
                },
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script>
@stop 