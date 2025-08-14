@extends('adminlte::page')

@section('title', 'Manajemen Pelanggan')

@section('content_header')
    <div class="customer-header">
        <h1 class="header-title">Manajemen Pelanggan</h1>
        <div class="header-actions">
            <a href="{{ route('technician.customers.create') }}" class="btn btn-primary btn-add">
                <i class="fas fa-plus"></i>
                <span class="btn-text">Tambah Pelanggan</span>
            </a>
            <div class="import-section">
                <form action="{{ route('technician.customers.import') }}" method="POST" enctype="multipart/form-data" class="import-form">
                    @csrf
                    <div class="file-input-wrapper">
                        <input type="file" name="file" accept=".xlsx,.xls" required class="form-control file-input" id="customer-file">
                        <label for="customer-file" class="file-label">
                            <i class="fas fa-upload"></i>
                            <span class="file-text">Pilih File</span>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-success btn-import">
                        <i class="fas fa-file-excel"></i>
                        <span class="btn-text">Import</span>
                    </button>
                </form>
            </div>
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
        /* Header Styling */
        .customer-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1rem 0;
        }

        .header-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        /* Button Styling */
        .btn-add, .btn-import {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
        }

        .btn-add {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-add:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-import {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }

        .btn-import:hover {
            background: linear-gradient(135deg, #0f8a7d 0%, #2edb6a 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(17, 153, 142, 0.3);
            color: white;
        }

        /* Import Section */
        .import-section {
            display: flex;
            align-items: center;
        }

        .import-form {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .file-input-wrapper {
            position: relative;
            display: inline-block;
        }

        .file-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-label {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .file-label:hover {
            border-color: #667eea;
            background: #f0f2ff;
            color: #667eea;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .customer-header {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }

            .header-title {
                font-size: 1.5rem;
                text-align: center;
            }

            .header-actions {
                flex-direction: column;
                gap: 0.75rem;
            }

            .import-form {
                flex-direction: column;
                width: 100%;
            }

            .file-input-wrapper {
                width: 100%;
            }

            .file-label {
                width: 100%;
                justify-content: center;
            }

            .btn-add, .btn-import {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .header-title {
                font-size: 1.25rem;
            }
        }

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
            // File input change handler
            $('#customer-file').on('change', function() {
                const fileName = this.files[0]?.name || 'Pilih File';
                $('.file-text').text(fileName);
            });

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
