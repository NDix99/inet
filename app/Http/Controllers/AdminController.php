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
        // Data untuk pagination
        $customers = Customer::with(['package', 'creator'])->latest()->paginate(20);
        
        // Data untuk statistik (total keseluruhan)
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('is_active', true)->count();
        $inactiveCustomers = Customer::where('is_active', false)->count();
        $newCustomersThisMonth = Customer::where('created_at', '>=', now()->startOfMonth())->count();
        
        // Data untuk filter teknisi (semua teknisi di database)
        $allTechnicians = User::whereHas('role', function ($query) {
            $query->where('name', 'technician');
        })->get();
        
        return view('admin.customer.index', compact(
            'customers',
            'totalCustomers',
            'activeCustomers', 
            'inactiveCustomers',
            'newCustomersThisMonth',
            'allTechnicians'
        ));
    }

    /**
     * Menampilkan form pembuatan pelanggan.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function customerCreate()
    {
        $packages = Package::where('is_active', true)->get();
        $technicians = User::whereHas('role', function ($query) {
            $query->where('name', 'technician');
        })->where('is_active', true)->get();
        
        return view('admin.customer.create', compact('packages', 'technicians'));
    }

    /**
     * Menyimpan pelanggan baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function customerStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'package_id' => 'required|exists:packages,id',
            'billing_date' => 'required|date',
            'created_by' => 'nullable|exists:users,id',
        ]);

        Customer::create($request->all());

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
        return view('admin.customer.show', compact('customer'));
    }

    /**
     * Menampilkan form edit pelanggan.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function customerEdit(Customer $customer)
    {
        $packages = Package::where('is_active', true)->get();
        $technicians = User::whereHas('role', function ($query) {
            $query->where('name', 'technician');
        })->where('is_active', true)->get();
        
        return view('admin.customer.edit', compact('customer', 'packages', 'technicians'));
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
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'package_id' => 'required|exists:packages,id',
            'billing_date' => 'required|date',
            'created_by' => 'nullable|exists:users,id',
        ]);

        $customer->update($request->all());

        return redirect()->route('admin.customers.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    /**
     * Menghapus pelanggan.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function customerDestroy(Customer $customer)
    {
        // Cek apakah pelanggan memiliki invoice
        if ($customer->invoices()->exists()) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Pelanggan tidak dapat dihapus karena memiliki invoice terkait.');
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

        // Simpan persentase fee teknisi
        $feePercentage = $request->technician_fee_percentage;
        
        // Hitung jumlah fee teknisi (berdasarkan harga dasar)
        $feeAmount = round(($package->base_price * $feePercentage) / 100, 2);
        
        // Simpan fee teknisi ke database
        $package->update([
            'technician_fee_percentage' => $feePercentage,
            'technician_fee_amount' => $feeAmount
        ]);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Fee teknisi berhasil diperbarui.');
    }

    /**
     * Menampilkan daftar teknisi.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function technicianIndex()
    {
        $baseQuery = User::whereHas('role', function ($query) {
            $query->where('name', 'technician');
        });

        $technicians = (clone $baseQuery)->paginate(20);

        $totalTechnicians    = (clone $baseQuery)->count();
        $activeTechnicians   = (clone $baseQuery)->where('is_active', true)->count();
        $inactiveTechnicians = (clone $baseQuery)->where('is_active', false)->count();
        $registeredThisMonth = (clone $baseQuery)->where('created_at', '>=', now()->startOfMonth())->count();

        return view('admin.technician.index', compact(
            'technicians',
            'totalTechnicians',
            'activeTechnicians',
            'inactiveTechnicians',
            'registeredThisMonth'
        ));
    }

    /**
     * Menampilkan form pembuatan teknisi.
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
     * Menampilkan form edit teknisi untuk pengaturan fee.
     *
     * @param  \App\Models\User  $technician
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function technicianEdit(User $technician)
    {
        // Pastikan user adalah teknisi
        if (!$technician->isTechnician()) {
            return redirect()->route('admin.technicians.index')
                ->with('error', 'User yang dipilih bukan teknisi.');
        }
        
        return view('admin.technician.edit', compact('technician'));
    }

    /**
     * Update data teknisi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $technician
     * @return \Illuminate\Http\Response
     */
    public function technicianUpdate(Request $request, User $technician)
    {
        // Pastikan user adalah teknisi
        if (!$technician->isTechnician()) {
            return redirect()->route('admin.technicians.index')
                ->with('error', 'User yang dipilih bukan teknisi.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                \Illuminate\Validation\Rule::unique('users')->ignore($technician->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'technician_fee_percentage' => 'required|numeric|min:0|max:100',
        ]);

        // Update data teknisi
        $technician->name = $validated['name'];
        $technician->email = $validated['email'];
        $technician->phone = $validated['phone'];
        $technician->is_active = $request->has('is_active');
        $technician->technician_fee_percentage = $validated['technician_fee_percentage'];

        // Update password jika diisi
        if (!empty($validated['password'])) {
            $technician->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        // Hitung contoh fee untuk paket Rp 100.000 (harga dasar sekitar Rp 90.090)
        $examplePackagePrice = 100000;
        $exampleFee = $technician->calculateTechnicianFee($examplePackagePrice);
        $technician->technician_fee_amount = $exampleFee['amount'];

        $technician->save();

        return redirect()->route('admin.technicians.index')
            ->with('success', 'Data teknisi berhasil diperbarui.');
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

    /**
     * Menampilkan daftar invoice.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function invoiceIndex()
    {
        // Data untuk pagination
        $invoices = Invoice::with('customer')->latest()->paginate(20);
        
        // Data untuk statistik (total keseluruhan)
        $totalInvoices = Invoice::count();
        $paidInvoices = Invoice::where('status', 'paid')->count();
        $unpaidInvoices = Invoice::where('status', 'unpaid')->count();
        $overdueInvoices = Invoice::where('status', 'overdue')->count();
        
        return view('admin.invoice.index', compact(
            'invoices',
            'totalInvoices',
            'paidInvoices',
            'unpaidInvoices',
            'overdueInvoices'
        ));
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
        
        // Mendapatkan teknisi yang membuat invoice
        $technician = Auth::user();
        
        // Membuat nomor invoice
        $lastInvoice = Invoice::latest()->first();
        $invoiceNumber = 'INV-' . str_pad(($lastInvoice ? $lastInvoice->id + 1 : 1), 5, '0', STR_PAD_LEFT);
        
        // Menyiapkan data invoice
        $data = $request->all();
        $data['invoice_number'] = $invoiceNumber;
        $data['package_id'] = $customer->package_id;
        $data['created_by'] = Auth::id();
        
        // Hitung fee teknisi berdasarkan harga dasar paket
        $basePrice = $package->base_price; // Harga dasar (sebelum PPN)
        $feeAmount = round(($basePrice * $technician->technician_fee_percentage) / 100, 2);
        
        $data['technician_fee_percentage'] = $technician->technician_fee_percentage;
        $data['technician_fee_amount'] = $feeAmount;
        
        // Buat invoice
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
     * Menampilkan invoice untuk print.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function invoicePrint(Invoice $invoice)
    {
        return view('admin.invoice.print', compact('invoice'));
    }

    /**
     * Reset status print invoice.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function invoiceResetPrintStatus(Invoice $invoice)
    {
        $invoice->update(['print_status' => false]);
        
        return redirect()->back()
            ->with('success', 'Status print invoice berhasil direset.');
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
            'status' => 'required|in:paid,unpaid,overdue,cancelled',
        ]);

        $invoice->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Status invoice berhasil diperbarui.');
    }

    /**
     * Menampilkan laporan keuangan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function financialReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());
        $paymentStatus = $request->get('payment_status', '');
        
        // Query untuk mendapatkan data invoice dalam rentang tanggal
        $invoices = Invoice::whereBetween('invoice_date', [$startDate, $endDate])
            ->with(['customer', 'package']);
        
        // Filter berdasarkan status pembayaran jika ada
        if ($paymentStatus) {
            $invoices = $invoices->where('status', $paymentStatus);
        }
        
        $invoices = $invoices->get();
        
        // Hitung total pendapatan
        $totalRevenue = $invoices->sum('total_amount');
        $totalPaid = $invoices->where('status', 'paid')->sum('total_amount');
        $totalUnpaid = $invoices->where('status', 'unpaid')->sum('total_amount');
        $totalOverdue = $invoices->where('status', 'overdue')->sum('total_amount');
        
        // Hitung total fee teknisi
        $totalTechnicianFee = $invoices->sum('technician_fee_amount');
        
        // Data untuk chart
        $dailyRevenue = $invoices->groupBy(function($invoice) {
            return $invoice->invoice_date->format('Y-m-d');
        })->map(function($group) {
            return $group->sum('total_amount');
        });
        
        // Data untuk tabel
        $invoiceData = $invoices->map(function($invoice) {
            return [
                'invoice_number' => $invoice->invoice_number,
                'customer_name' => $invoice->customer->name,
                'package_name' => $invoice->package->name,
                'amount' => $invoice->amount,
                'tax_amount' => $invoice->tax_amount,
                'technician_fee_amount' => $invoice->technician_fee_amount,
                'total_amount' => $invoice->total_amount,
                'status' => $invoice->status,
                'invoice_date' => $invoice->invoice_date,
                'due_date' => $invoice->due_date,
            ];
        });
        
        // Data untuk mitra (teknisi) - menggunakan struktur yang sesuai dengan view
        $technicianData = User::whereHas('role', function($query) {
            $query->where('name', 'technician');
        })->where('is_active', true)->get()->map(function($technician) use ($invoices, $startDate, $endDate) {
            $technicianInvoices = $invoices->where('created_by', $technician->id);
            $customers = $technician->customers;
            
            // Hitung revenue dan fee
            $revenue = $technicianInvoices->sum('total_amount');
            $fee = $technicianInvoices->sum('technician_fee_amount');
            $ppn = $technicianInvoices->sum('tax_amount');
            
            // Hitung rata-rata fee percentage berdasarkan fee yang sudah disimpan di invoice
            $totalBasePrice = $technicianInvoices->sum(function($invoice) {
                return $invoice->package->base_price; // Gunakan harga dasar dari paket
            });
            
            // Jika fee masih 0, coba hitung ulang berdasarkan persentase teknisi
            if ($fee == 0 && $technician->technician_fee_percentage > 0) {
                $fee = $technicianInvoices->sum(function($invoice) use ($technician) {
                    $basePrice = $invoice->package->base_price; // Harga dasar
                    return round(($basePrice * $technician->technician_fee_percentage) / 100, 2);
                });
            }
            
            $avgFeePercentage = $totalBasePrice > 0 ? ($fee / $totalBasePrice) * 100 : $technician->technician_fee_percentage;
            
            // Hitung fee PT
            $totalPtFee = $revenue - $fee;
            
            // Detail fee untuk modal
            $feeDetails = $technicianInvoices->map(function($invoice) use ($technician) {
                $basePrice = $invoice->package->base_price; // Harga dasar dari paket
                
                // Jika fee masih 0, hitung ulang
                $feeAmount = $invoice->technician_fee_amount;
                if ($feeAmount == 0 && $technician->technician_fee_percentage > 0) {
                    $feeAmount = round(($basePrice * $technician->technician_fee_percentage) / 100, 2);
                }
                
                $feePercentage = $basePrice > 0 ? ($feeAmount / $basePrice) * 100 : $technician->technician_fee_percentage;
                
                return [
                    'invoice_number' => $invoice->invoice_number,
                    'base_price' => $basePrice,
                    'fee_percentage' => $feePercentage,
                    'fee_amount' => $feeAmount,
                    'pt_fee_amount' => $basePrice - $feeAmount,
                ];
            });
            
            // Cek status print dan payment
            $isPrinted = $technicianInvoices->every(function($invoice) {
                return $invoice->is_printed;
            });
            $isPaid = $technicianInvoices->every(function($invoice) {
                return $invoice->status === 'paid';
            });
            
            return [
                'technician' => $technician,
                'customers_count' => $customers->count(),
                'invoice_count' => $technicianInvoices->count(),
                'revenue' => $revenue,
                'fee' => $fee,
                'ppn' => $ppn,
                'avg_fee_percentage' => $avgFeePercentage,
                'total_pt_fee' => $totalPtFee,
                'fee_details' => $feeDetails,
                'is_printed' => $isPrinted,
                'is_paid' => $isPaid,
                'printed_at' => $isPrinted ? $technicianInvoices->max('printed_at') : null,
                'payment_date' => $isPaid ? $technicianInvoices->max('paid_at') : null,
                'payment_notes' => $technicianInvoices->first()->payment_notes ?? null,
            ];
        });
        
        // Hitung total fee untuk mitra dan PT
        $totalFee = $technicianData->sum('fee');
        $totalPtFee = $totalRevenue - $totalFee;
        $totalTax = $invoices->sum('tax_amount');
        $totalPPN = $totalTax; // PPN sama dengan tax
        
        return view('admin.financial.report', compact(
            'invoices',
            'totalRevenue',
            'totalPaid',
            'totalUnpaid',
            'totalOverdue',
            'totalTechnicianFee',
            'dailyRevenue',
            'invoiceData',
            'startDate',
            'endDate',
            'paymentStatus',
            'technicianData',
            'totalFee',
            'totalPtFee',
            'totalTax',
            'totalPPN'
        ));
    }

    /**
     * Menampilkan laporan keuangan untuk print.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function financialReportPrint(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());
        $paymentStatus = $request->get('payment_status', '');
        $technicianId = $request->get('technician_id');
        
        // Query untuk mendapatkan data invoice dalam rentang tanggal
        $invoices = Invoice::whereBetween('invoice_date', [$startDate, $endDate])
            ->with(['customer', 'package']);
        
        // Filter berdasarkan status pembayaran jika ada
        if ($paymentStatus) {
            $invoices = $invoices->where('status', $paymentStatus);
        }
        
        // Filter berdasarkan teknisi jika ada
        if ($technicianId) {
            $invoices = $invoices->where('created_by', $technicianId);
        }
        
        $invoices = $invoices->get();
        
        // Hitung total pendapatan
        $totalRevenue = $invoices->sum('total_amount');
        $totalPaid = $invoices->where('status', 'paid')->sum('total_amount');
        $totalUnpaid = $invoices->where('status', 'unpaid')->sum('total_amount');
        $totalOverdue = $invoices->where('status', 'overdue')->sum('total_amount');
        
        // Hitung total fee teknisi
        $totalTechnicianFee = $invoices->sum('technician_fee_amount');
        
        // Data untuk chart
        $dailyRevenue = $invoices->groupBy(function($invoice) {
            return $invoice->invoice_date->format('Y-m-d');
        })->map(function($group) {
            return $group->sum('total_amount');
        });
        
        // Data untuk tabel
        $invoiceData = $invoices->map(function($invoice) {
            return [
                'invoice_number' => $invoice->invoice_number,
                'customer_name' => $invoice->customer->name,
                'package_name' => $invoice->package->name,
                'amount' => $invoice->amount,
                'tax_amount' => $invoice->tax_amount,
                'technician_fee_amount' => $invoice->technician_fee_amount,
                'total_amount' => $invoice->total_amount,
                'status' => $invoice->status,
                'invoice_date' => $invoice->invoice_date,
                'due_date' => $invoice->due_date,
            ];
        });
        
        // Data untuk mitra (teknisi) - menggunakan struktur yang sesuai dengan view
        $technicianData = User::whereHas('role', function($query) {
            $query->where('name', 'technician');
        })->where('is_active', true)->get()->map(function($technician) use ($invoices, $startDate, $endDate) {
            $technicianInvoices = $invoices->where('created_by', $technician->id);
            $customers = $technician->customers;
            
            // Hitung revenue dan fee
            $revenue = $technicianInvoices->sum('total_amount');
            $fee = $technicianInvoices->sum('technician_fee_amount');
            $ppn = $technicianInvoices->sum('tax_amount');
            
            // Hitung rata-rata fee percentage berdasarkan fee yang sudah disimpan di invoice
            $totalBasePrice = $technicianInvoices->sum(function($invoice) {
                return $invoice->total_amount - $invoice->tax_amount;
            });
            
            // Jika fee masih 0, coba hitung ulang berdasarkan persentase teknisi
            if ($fee == 0 && $technician->technician_fee_percentage > 0) {
                $fee = $technicianInvoices->sum(function($invoice) use ($technician) {
                    $feeCalculation = $technician->calculateTechnicianFee($invoice->total_amount);
                    return $feeCalculation['amount'];
                });
            }
            
            $avgFeePercentage = $totalBasePrice > 0 ? ($fee / $totalBasePrice) * 100 : $technician->technician_fee_percentage;
            
            // Hitung fee PT
            $totalPtFee = $revenue - $fee;
            
            // Detail fee untuk modal
            $feeDetails = $technicianInvoices->map(function($invoice) use ($technician) {
                $basePrice = $invoice->total_amount - $invoice->tax_amount;
                
                // Jika fee masih 0, hitung ulang
                $feeAmount = $invoice->technician_fee_amount;
                if ($feeAmount == 0 && $technician->technician_fee_percentage > 0) {
                    $feeCalculation = $technician->calculateTechnicianFee($invoice->total_amount);
                    $feeAmount = $feeCalculation['amount'];
                }
                
                $feePercentage = $basePrice > 0 ? ($feeAmount / $basePrice) * 100 : $technician->technician_fee_percentage;
                
                return [
                    'invoice_number' => $invoice->invoice_number,
                    'base_price' => $basePrice,
                    'fee_percentage' => $feePercentage,
                    'fee_amount' => $feeAmount,
                    'pt_fee_amount' => $basePrice - $feeAmount,
                ];
            });
            
            // Cek status print dan payment
            $isPrinted = $technicianInvoices->every(function($invoice) {
                return $invoice->is_printed;
            });
            $isPaid = $technicianInvoices->every(function($invoice) {
                return $invoice->status === 'paid';
            });
            
            return [
                'technician' => $technician,
                'customers_count' => $customers->count(),
                'invoice_count' => $technicianInvoices->count(),
                'revenue' => $revenue,
                'fee' => $fee,
                'ppn' => $ppn,
                'avg_fee_percentage' => $avgFeePercentage,
                'total_pt_fee' => $totalPtFee,
                'fee_details' => $feeDetails,
                'is_printed' => $isPrinted,
                'is_paid' => $isPaid,
                'printed_at' => $isPrinted ? $technicianInvoices->max('printed_at') : null,
                'payment_date' => $isPaid ? $technicianInvoices->max('paid_at') : null,
                'payment_notes' => $technicianInvoices->first()->payment_notes ?? null,
            ];
        });
        
        // Hitung total fee untuk mitra dan PT
        $totalFee = $technicianData->sum('fee');
        $totalPtFee = $totalRevenue - $totalFee;
        $totalTax = $invoices->sum('tax_amount');
        $totalPPN = $totalTax; // PPN sama dengan tax
        
        return view('admin.financial.print', compact(
            'invoices',
            'totalRevenue',
            'totalPaid',
            'totalUnpaid',
            'totalOverdue',
            'totalTechnicianFee',
            'dailyRevenue',
            'invoiceData',
            'startDate',
            'endDate',
            'paymentStatus',
            'technicianData',
            'totalFee',
            'totalPtFee',
            'totalTax',
            'totalPPN'
        ));
    }
} 