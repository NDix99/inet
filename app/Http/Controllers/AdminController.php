<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Invoice;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Tambahkan middleware role untuk Admin nanti
    }

    /**
     * Menampilkan dashboard Admin.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        // Data untuk dashboard
        $totalCustomers = Customer::count();
        $totalInvoices = Invoice::count();
        $totalRevenue = Invoice::sum('total_amount');
        $totalUnpaid = Invoice::where('status', 'unpaid')->sum('total_amount');
        $totalOverdue = Invoice::where('status', 'overdue')->sum('total_amount');
        $latestCustomers = Customer::latest()->take(5)->get();
        $latestInvoices = Invoice::with('customer')->latest()->take(5)->get();
        
        // Statistik bulanan (pendapatan per bulan untuk tahun ini)
        $monthlyRevenue = Invoice::selectRaw('MONTH(created_at) as month, SUM(total_amount) as revenue')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('revenue', 'month')
            ->toArray();
        
        // Format data untuk chart
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[$i] = $monthlyRevenue[$i] ?? 0;
        }
        
        return view('admin.dashboard', compact(
            'totalCustomers', 
            'totalInvoices', 
            'totalRevenue', 
            'totalUnpaid',
            'totalOverdue',
            'latestCustomers', 
            'latestInvoices',
            'chartData'
        ));
    }

    /**
     * Menampilkan daftar pelanggan.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function customerIndex()
    {
        $customers = Customer::all();
        return view('admin.customer.index', compact('customers'));
    }

    /**
     * Menampilkan form tambah pelanggan.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function customerCreate()
    {
        $packages = Package::all();
        return view('admin.customer.create', compact('packages'));
    }

    /**
     * Menyimpan data pelanggan baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function customerStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'package_id' => 'required|exists:packages,id',
            'billing_date' => 'required|date',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();
        $data['is_active'] = true;

        $customer = Customer::create($data);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail pelanggan.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function customerShow(Customer $customer)
    {
        $invoices = $customer->invoices()->latest()->get();
        return view('admin.customer.show', compact('customer', 'invoices'));
    }

    /**
     * Menampilkan form edit pelanggan.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function customerEdit(Customer $customer)
    {
        $packages = Package::all();
        return view('admin.customer.edit', compact('customer', 'packages'));
    }

    /**
     * Memperbarui data pelanggan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function customerUpdate(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'package_id' => 'required|exists:packages,id',
            'billing_date' => 'required|date',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $customer->update($request->all());

        return redirect()->route('admin.customers.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    /**
     * Menghapus data pelanggan.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function customerDestroy(Customer $customer)
    {
        // Cek apakah pelanggan memiliki invoice
        if ($customer->invoices()->count() > 0) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Pelanggan tidak dapat dihapus karena memiliki invoice.');
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }

    /**
     * Menampilkan daftar paket internet.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function packageIndex()
    {
        $packages = Package::all();
        return view('admin.package.index', compact('packages'));
    }

    /**
     * Menampilkan form edit paket internet (hanya harga).
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function packageEdit(Package $package)
    {
        return view('admin.package.edit', compact('package'));
    }

    /**
     * Memperbarui fee teknisi pada paket internet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function packageUpdatePrice(Request $request, Package $package)
    {
        $request->validate([
            'technician_fee_percentage' => 'required|numeric|min:0|max:100',
        ]);

        // Hitung harga dasar (sebelum PPN)
        $ppnRate = 0.11;
        $priceBeforeTax = round($package->price / (1 + $ppnRate), 2);
        
        // Simpan persentase fee teknisi
        $feePercentage = $request->technician_fee_percentage;
        
        // Hitung jumlah fee teknisi (berdasarkan harga dasar)
        $feeAmount = round(($priceBeforeTax * $feePercentage) / 100, 2);
        
        // Simpan fee teknisi ke database
        $package->update([
            'technician_fee_percentage' => $feePercentage,
            'technician_fee_amount' => $feeAmount
        ]);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Fee teknisi berhasil diperbarui.');
    }

    /**
     * Menampilkan daftar invoice.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function invoiceIndex()
    {
        $invoices = Invoice::with('customer')->latest()->get();
        return view('admin.invoice.index', compact('invoices'));
    }

    /**
     * Menampilkan form pembuatan invoice.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function invoiceCreate()
    {
        $customers = Customer::where('is_active', true)->get();
        return view('admin.invoice.create', compact('customers'));
    }

    /**
     * Menyimpan invoice baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function invoiceStore(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'amount' => 'required|numeric|min:0',
            'tax_percentage' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'technician_fee_percentage' => 'nullable|numeric|min:0',
            'technician_fee_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:paid,unpaid,overdue,cancelled',
            'notes' => 'nullable|string',
        ]);

        // Mendapatkan data pelanggan dan paket
        $customer = Customer::findOrFail($request->customer_id);
        $package = Package::findOrFail($customer->package_id);
        
        // Membuat nomor invoice
        $lastInvoice = Invoice::latest()->first();
        $invoiceNumber = 'INV-' . str_pad(($lastInvoice ? $lastInvoice->id + 1 : 1), 5, '0', STR_PAD_LEFT);
        
        // Menyiapkan data invoice
        $data = $request->all();
        $data['invoice_number'] = $invoiceNumber;
        $data['package_id'] = $customer->package_id;
        $data['created_by'] = Auth::id();
        
        // Menghitung pajak jika belum diisi
        if (!isset($data['tax_percentage']) || $data['tax_percentage'] == 0) {
            $data['tax_percentage'] = 11; // Default PPN 11%
        }
        
        if (!isset($data['tax_amount']) || $data['tax_amount'] == 0) {
            $data['tax_amount'] = $data['amount'] * ($data['tax_percentage'] / 100);
        }
        
        // Mengambil fee teknisi dari paket
        if (!isset($data['technician_fee_percentage']) || $data['technician_fee_percentage'] == 0) {
            $data['technician_fee_percentage'] = $package->technician_fee_percentage ?? 0;
        }
        
        if (!isset($data['technician_fee_amount']) || $data['technician_fee_amount'] == 0) {
            $data['technician_fee_amount'] = $package->technician_fee_amount ?? 0;
        }
        
        // Menghitung total jika belum diisi
        if (!isset($data['total_amount']) || $data['total_amount'] == 0) {
            $data['total_amount'] = $data['amount'] + $data['tax_amount'];
        }
        
        $invoice = Invoice::create($data);

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Invoice berhasil dibuat.');
    }

    /**
     * Menampilkan detail invoice.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function invoiceShow(Invoice $invoice)
    {
        return view('admin.invoice.show', compact('invoice'));
    }

    /**
     * Menampilkan invoice untuk dicetak.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function invoicePrint(Invoice $invoice)
    {
        // Update status cetak admin jika belum tercetak
        if (!$invoice->is_printed_admin) {
            $invoice->update([
                'is_printed_admin' => true,
                'printed_at_admin' => now(),
                'printed_by_admin' => Auth::id(),
            ]);
        }
        return view('admin.invoice.print', compact('invoice'));
    }

    /**
     * Reset status cetak invoice (is_printed, printed_at, printed_by)
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\RedirectResponse
     */
    public function invoiceResetPrintStatus(Invoice $invoice)
    {
        $invoice->update([
            'is_printed' => false,
            'printed_at' => null,
            'printed_by' => null
        ]);
        return redirect()->back()->with('success', 'Status cetak invoice berhasil direset.');
    }

    /**
     * Memperbarui status invoice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function invoiceUpdateStatus(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:paid,unpaid,overdue',
        ]);

        $invoice->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.invoices.index')
            ->with('success', 'Status invoice berhasil diperbarui.');
    }

    /**
     * Menampilkan laporan keuangan untuk Admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function financialReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $paymentStatus = $request->input('payment_status'); // Filter berdasarkan status pembayaran

        // Ambil data teknisi (mitra) dengan total fee dan PPN
        $technicians = User::whereHas('role', function ($query) {
            $query->where('name', 'technician');
        })->with(['customers' => function ($query) use ($startDate, $endDate) {
            $query->whereHas('invoices', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('invoice_date', [$startDate, $endDate]);
            });
        }, 'customers.invoices' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('invoice_date', [$startDate, $endDate]);
        }, 'customers.invoices.package'])->get();

        // Hitung total untuk setiap teknisi
        $technicianData = [];
        $totalFee = 0;
        $totalPPN = 0;
        $totalRevenue = 0;
        $totalPtFee = 0;

        foreach ($technicians as $technician) {
            $technicianFee = 0;
            $technicianPPN = 0;
            $technicianRevenue = 0;
            $technicianPtFee = 0;
            $invoiceCount = 0;
            $feeDetails = [];

            foreach ($technician->customers as $customer) {
                foreach ($customer->invoices as $invoice) {
                    // Ambil data paket
                    $package = $invoice->package;
                    
                    // Harga dasar sebelum PPN menggunakan accessor base_price
                    $basePrice = $invoice->base_price ?? 0;
                    
                    // Hitung fee berdasarkan persentase dari paket
                    $feePercentage = $package ? $package->technician_fee_percentage : 0;
                    $calculatedFee = $basePrice * ($feePercentage / 100);
                    
                    // Hitung fee PT (100% - persentase fee mitra)
                    $ptFeePercentage = 100 - $feePercentage;
                    $ptFeeAmount = $basePrice * ($ptFeePercentage / 100);
                    
                    // Tambahkan ke total
                    $technicianFee += $calculatedFee;
                    $technicianPPN += $invoice->tax_amount ?? 0;
                    $technicianRevenue += $basePrice;
                    $technicianPtFee += $ptFeeAmount; // Tambahkan ke total PT fee
                    $invoiceCount++;
                    
                    // Simpan detail untuk tampilan
                    $feeDetails[] = [
                        'invoice_number' => $invoice->invoice_number,
                        'customer_name' => $customer->name,
                        'package_name' => $package ? $package->name : 'Tidak ada paket',
                        'base_price' => $basePrice,
                        'fee_percentage' => $feePercentage,
                        'fee_amount' => $calculatedFee,
                        'pt_fee_percentage' => $ptFeePercentage,
                        'pt_fee_amount' => $ptFeeAmount
                    ];
                }
            }
            
            // Cek status cetak dan pembayaran dari tabel mitra_reports
            $mitraReport = \App\Models\MitraReport::where('technician_id', $technician->id)
                ->where('periode_awal', $startDate)
                ->where('periode_akhir', $endDate)
                ->first();
            
            $isPrinted = false;
            $printedAt = null;
            $isPaid = false;
            $paymentDate = null;
            $paymentNotes = null;
            
            if ($mitraReport) {
                $isPrinted = $mitraReport->is_printed;
                $printedAt = $mitraReport->printed_at;
                $isPaid = $mitraReport->is_paid;
                $paymentDate = $mitraReport->payment_date;
                $paymentNotes = $mitraReport->payment_notes;
            }

            // Jika ada filter status pembayaran, skip data yang tidak sesuai
            if ($paymentStatus !== null) {
                $filterIsPaid = $paymentStatus === 'paid';
                if ($isPaid !== $filterIsPaid) {
                    continue; // Skip data yang tidak sesuai filter
                }
            }

            $technicianData[] = [
                'technician' => $technician,
                'fee' => $technicianFee,
                'ppn' => $technicianPPN,
                'revenue' => $technicianRevenue,
                'invoice_count' => $invoiceCount,
                'customers_count' => $technician->customers->count(),
                'fee_details' => $feeDetails,
                'avg_fee_percentage' => $technicianRevenue > 0 ? ($technicianFee / $technicianRevenue) * 100 : 0,
                'total_pt_fee' => $technicianPtFee,
                'is_printed' => $isPrinted,
                'printed_at' => $printedAt,
                'is_paid' => $isPaid,
                'payment_date' => $paymentDate,
                'payment_notes' => $paymentNotes
            ];

            $totalFee += $technicianFee;
            $totalPPN += $technicianPPN;
            $totalRevenue += $technicianRevenue;
            $totalPtFee += $technicianPtFee;
        }

        // Ambil data invoice untuk chart
        $invoices = Invoice::whereBetween('invoice_date', [$startDate, $endDate])
            ->with(['customer', 'package'])
            ->get();

        return view('admin.financial.report', compact(
            'technicianData',
            'startDate',
            'endDate',
            'totalFee',
            'totalPPN',
            'totalRevenue',
            'totalPtFee',
            'invoices',
            'paymentStatus'
        ));
    }

    /**
     * Mencetak laporan keuangan untuk Admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function financialReportPrint(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $technicianId = $request->input('technician_id');
        $paymentStatus = $request->input('payment_status'); // Filter berdasarkan status pembayaran

        // Ambil data teknisi (mitra) dengan total fee dan PPN
        $technicians = User::whereHas('role', function ($query) {
            $query->where('name', 'technician');
        })->with(['customers' => function ($query) use ($startDate, $endDate) {
            $query->whereHas('invoices', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('invoice_date', [$startDate, $endDate]);
            });
        }, 'customers.invoices' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('invoice_date', [$startDate, $endDate]);
        }, 'customers.invoices.package'])->get();

        // Jika ada technician_id, filter hanya untuk teknisi tersebut
        if ($technicianId) {
            $technicians = $technicians->where('id', $technicianId);
        }

        // Hitung total untuk setiap teknisi
        $technicianData = [];
        $totalFee = 0;
        $totalPPN = 0;
        $totalRevenue = 0;
        $totalPtFee = 0;

        foreach ($technicians as $technician) {
            $technicianFee = 0;
            $technicianPPN = 0;
            $technicianRevenue = 0;
            $technicianPtFee = 0;
            $invoiceCount = 0;
            $feeDetails = [];

            foreach ($technician->customers as $customer) {
                foreach ($customer->invoices as $invoice) {
                    // Ambil data paket
                    $package = $invoice->package;
                    
                    // Harga dasar sebelum PPN menggunakan accessor base_price
                    $basePrice = $invoice->base_price ?? 0;
                    
                    // Hitung fee berdasarkan persentase dari paket
                    $feePercentage = $package ? $package->technician_fee_percentage : 0;
                    $calculatedFee = $basePrice * ($feePercentage / 100);
                    
                    // Hitung fee PT (100% - persentase fee mitra)
                    $ptFeePercentage = 100 - $feePercentage;
                    $ptFeeAmount = $basePrice * ($ptFeePercentage / 100);
                    
                    // Tambahkan ke total
                    $technicianFee += $calculatedFee;
                    $technicianPPN += $invoice->tax_amount ?? 0;
                    $technicianRevenue += $basePrice;
                    $technicianPtFee += $ptFeeAmount; // Tambahkan ke total PT fee
                    $invoiceCount++;
                    
                    // Simpan detail untuk tampilan
                    $feeDetails[] = [
                        'invoice_number' => $invoice->invoice_number,
                        'customer_name' => $customer->name,
                        'package_name' => $package ? $package->name : 'Tidak ada paket',
                        'base_price' => $basePrice,
                        'fee_percentage' => $feePercentage,
                        'fee_amount' => $calculatedFee,
                        'pt_fee_percentage' => $ptFeePercentage,
                        'pt_fee_amount' => $ptFeeAmount
                    ];
                }
            }
            
            // Cek status cetak dan pembayaran dari tabel mitra_reports
            $mitraReport = \App\Models\MitraReport::where('technician_id', $technician->id)
                ->where('periode_awal', $startDate)
                ->where('periode_akhir', $endDate)
                ->first();
            
            $isPrinted = false;
            $printedAt = null;
            $isPaid = false;
            $paymentDate = null;
            $paymentNotes = null;
            
            if ($mitraReport) {
                $isPrinted = $mitraReport->is_printed;
                $printedAt = $mitraReport->printed_at;
                $isPaid = $mitraReport->is_paid;
                $paymentDate = $mitraReport->payment_date;
                $paymentNotes = $mitraReport->payment_notes;
            }

            // Jika ada filter status pembayaran, skip data yang tidak sesuai
            if ($paymentStatus !== null) {
                $filterIsPaid = $paymentStatus === 'paid';
                if ($isPaid !== $filterIsPaid) {
                    continue; // Skip data yang tidak sesuai filter
                }
            }

            $technicianData[] = [
                'technician' => $technician,
                'fee' => $technicianFee,
                'ppn' => $technicianPPN,
                'revenue' => $technicianRevenue,
                'invoice_count' => $invoiceCount,
                'customers_count' => $technician->customers->count(),
                'fee_details' => $feeDetails,
                'avg_fee_percentage' => $technicianRevenue > 0 ? ($technicianFee / $technicianRevenue) * 100 : 0,
                'total_pt_fee' => $technicianPtFee,
                'is_printed' => $isPrinted,
                'printed_at' => $printedAt,
                'is_paid' => $isPaid,
                'payment_date' => $paymentDate,
                'payment_notes' => $paymentNotes
            ];

            $totalFee += $technicianFee;
            $totalPPN += $technicianPPN;
            $totalRevenue += $technicianRevenue;
            $totalPtFee += $technicianPtFee;
            
            // Update atau buat record di tabel mitra_reports
            if ($technicianId) {
                // Jika mencetak laporan untuk satu mitra saja
                $mitraReport = \App\Models\MitraReport::updateOrCreate(
                    [
                        'technician_id' => $technician->id,
                        'periode_awal' => $startDate,
                        'periode_akhir' => $endDate
                    ],
                    [
                        'total_fee' => $technicianFee,
                        'total_revenue' => $technicianRevenue,
                        'total_pt_fee' => $technicianPtFee,
                        'total_ppn' => $technicianPPN,
                        'is_printed' => true,
                        'printed_at' => now(),
                        'printed_by' => Auth::id()
                    ]
                );
            }
        }
        
        // Jika mencetak laporan untuk semua mitra
        if (!$technicianId && count($technicians) > 0) {
            foreach ($technicians as $technician) {
                $techData = collect($technicianData)->firstWhere('technician.id', $technician->id);
                if ($techData) {
                    \App\Models\MitraReport::updateOrCreate(
                        [
                            'technician_id' => $technician->id,
                            'periode_awal' => $startDate,
                            'periode_akhir' => $endDate
                        ],
                        [
                            'total_fee' => $techData['fee'],
                            'total_revenue' => $techData['revenue'],
                            'total_pt_fee' => $techData['total_pt_fee'],
                            'total_ppn' => $techData['ppn'],
                            'is_printed' => true,
                            'printed_at' => now(),
                            'printed_by' => Auth::id()
                        ]
                    );
                }
            }
        }

        return view('admin.financial.print', compact(
            'technicianData',
            'startDate',
            'endDate',
            'totalFee',
            'totalPPN',
            'totalRevenue',
            'totalPtFee',
            'technicianId',
            'paymentStatus'
        ));
    }

    /**
     * Menampilkan daftar teknisi.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function technicianIndex()
    {
        $technicians = User::whereHas('role', function ($query) {
            $query->where('name', 'technician');
        })->get();

        return view('admin.technician.index', compact('technicians'));
    }

    /**
     * Menampilkan form untuk membuat teknisi baru.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function technicianCreate()
    {
        return view('admin.technician.create');
    }

    /**
     * Menyimpan teknisi baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function technicianStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        $technicianRole = Role::where('name', 'technician')->first();

        if (!$technicianRole) {
            return redirect()->back()->with('error', 'Peran teknisi tidak ditemukan');
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'role_id' => $technicianRole->id,
            'is_active' => true,
        ]);

        return redirect()->route('admin.technicians.index')
            ->with('success', 'Teknisi berhasil ditambahkan');
    }

    /**
     * Menampilkan form untuk mengedit teknisi.
     *
     * @param  \App\Models\User  $technician
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function technicianEdit(User $technician)
    {
        return view('admin.technician.edit', compact('technician'));
    }

    /**
     * Memperbarui data teknisi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $technician
     * @return \Illuminate\Http\Response
     */
    public function technicianUpdate(Request $request, User $technician)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($technician->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $technician->name = $validated['name'];
        $technician->email = $validated['email'];
        $technician->phone = $validated['phone'];
        $technician->is_active = $request->has('is_active');

        if (!empty($validated['password'])) {
            $technician->password = Hash::make($validated['password']);
        }

        $technician->save();

        return redirect()->route('admin.technicians.index')
            ->with('success', 'Teknisi berhasil diperbarui');
    }

    /**
     * Menghapus teknisi.
     *
     * @param  \App\Models\User  $technician
     * @return \Illuminate\Http\Response
     */
    public function technicianDestroy(User $technician)
    {
        // Cek apakah teknisi memiliki pelanggan
        $hasCustomers = Customer::where('created_by', $technician->id)->exists();
        
        if ($hasCustomers) {
            return redirect()->route('admin.technicians.index')
                ->with('error', 'Teknisi tidak dapat dihapus karena memiliki pelanggan terkait.');
        }
        
        $technician->delete();

        return redirect()->route('admin.technicians.index')
            ->with('success', 'Teknisi berhasil dihapus');
    }
} 