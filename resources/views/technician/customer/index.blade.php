@extends('adminlte::page')

@section('title', 'Manajemen Pelanggan')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Manajemen Pelanggan</h1>
        <a href="{{ route('technician.customers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Pelanggan
        </a>
        <form action="{{ route('technician.customers.import') }}" method="POST" enctype="multipart/form-data" style="display:inline;">
            @csrf
            <input type="file" name="file" accept=".xlsx,.xls" required>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Import Excel
            </button>
        </form>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <table id="customers-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Paket</th>
                        <th>Status</th>
                        <th>Tanggal Tagihan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
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
                            <td>{{ \Carbon\Carbon::parse($customer->billing_date)->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('technician.customers.show', $customer) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('technician.customers.edit', $customer) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
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
            $('#customers-table').DataTable({
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
