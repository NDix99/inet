@extends('adminlte::page')

@section('title', 'Manajemen Pelanggan')

@section('content_header')
    <div class="d-flex justify-content-between flex-wrap">
        <h1 class="mb-2">Manajemen Pelanggan</h1>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('technician.customers.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> <span class="d-none d-sm-inline">Tambah Pelanggan</span>
            </a>
            <form action="{{ route('technician.customers.import') }}" method="POST" enctype="multipart/form-data" class="d-flex">
                @csrf
                <input type="file" name="file" accept=".xlsx,.xls" required class="form-control form-control-sm">
                <button type="submit" class="btn btn-success btn-sm ms-2">
                    <i class="fas fa-file-excel"></i> <span class="d-none d-sm-inline">Import</span>
                </button>
            </form>
        </div>
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

            <div class="table-responsive">
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
                            <th width="150">Aksi</th>
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
                                    <div class="action-buttons">
                                        <a href="{{ route('technician.customers.show', $customer) }}" 
                                           class="btn btn-sm btn-info action-btn">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('technician.customers.edit', $customer) }}" 
                                           class="btn btn-sm btn-warning action-btn">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger action-btn delete-customer" 
                                                data-customer-id="{{ $customer->id }}" 
                                                data-customer-name="{{ $customer->name }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        /* Responsive table */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        /* Action buttons styling */
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        .action-btn {
            min-width: 35px;
            min-height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.375rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        
        /* Mobile optimization */
        @media (max-width: 768px) {
            .table {
                font-size: 14px;
            }
            
            .table th,
            .table td {
                padding: 8px 6px;
                white-space: nowrap;
            }
            
            .action-buttons {
                gap: 3px;
            }
            
            .action-btn {
                min-width: 40px;
                min-height: 40px;
                font-size: 14px;
            }
            
            /* Make table scrollable on mobile */
            .table-responsive {
                border: 0;
            }
            
            .table {
                margin-bottom: 0;
            }
        }
        
        /* Hover effects */
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        /* SweetAlert customization */
        .swal2-popup {
            border-radius: 12px;
        }
        
        .swal2-title {
            color: #2c3e50;
        }
        
        .swal2-html-container {
            margin: 1rem 0;
        }
    </style>
@stop

@section('js')
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#customers-table').DataTable({
                language: {
                    url: "{{ asset('vendor/datatables/lang/Indonesian.json') }}"
                },
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                // Mobile optimization
                scrollX: true,
                autoWidth: false,
                columnDefs: [
                    {
                        targets: -1, // Last column (Actions)
                        orderable: false,
                        searchable: false,
                        width: '150px'
                    }
                ]
            });

            // Delete customer function
            function deleteCustomer(customerId, customerName) {
                return Swal.fire({
                    title: 'Hapus Pelanggan?',
                    html: `
                        <div class="text-center">
                            <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                            <p><strong class="text-danger">"${customerName}"</strong></p>
                            <p>Akan dihapus secara permanen!</p>
                        </div>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'swal2-custom',
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            }

            // Event listener untuk delete
            $(document).on('click', '.delete-customer', function(e) {
                e.preventDefault();
                
                const customerId = $(this).data('customer-id');
                const customerName = $(this).data('customer-name');
                
                deleteCustomer(customerId, customerName).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Menghapus...',
                            html: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit delete form
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `{{ url('technician/customers') }}/${customerId}`;
                        
                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';
                        
                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'DELETE';
                        
                        form.appendChild(csrfToken);
                        form.appendChild(methodField);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });

            // Mobile touch optimization
            if ('ontouchstart' in window) {
                $('.action-btn').on('touchstart', function() {
                    $(this).addClass('active');
                }).on('touchend touchcancel', function() {
                    $(this).removeClass('active');
                });
            }
        });
    </script>
@stop
