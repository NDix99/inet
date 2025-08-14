<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    /**
     * Konstruktor
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Middleware untuk memastikan hanya superadmin yang dapat mengakses
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isSuperAdmin()) {
                abort(403, 'Akses tidak diizinkan');
            }
            return $next($request);
        });
    }

    /**
     * Menampilkan dashboard superadmin
     */
    public function dashboard()
    {
        $totalAdmins = User::whereHas('role', function ($query) {
            $query->where('name', 'admin');
        })->count();

        $totalTechnicians = User::whereHas('role', function ($query) {
            $query->where('name', 'technician');
        })->count();

        $totalCustomers = Customer::count();
        $totalInvoices = Invoice::count();
        $totalPaidInvoices = Invoice::where('status', 'paid')->count();
        $totalUnpaidInvoices = Invoice::where('status', 'unpaid')->count();
        $totalOverdueInvoices = Invoice::where('status', 'overdue')->count();
        
        $totalRevenue = Invoice::where('status', 'paid')->sum('total_amount');
        $totalUnpaid = Invoice::where('status', 'unpaid')->sum('total_amount');
        $totalOverdue = Invoice::where('status', 'overdue')->sum('total_amount');
        $totalTax = Invoice::sum('tax_amount');
        
        // Ubah perhitungan total fee teknisi dari users table
        $totalTechnicianFee = User::whereHas('role', function ($query) {
            $query->where('name', 'technician');
        })->sum('technician_fee_amount');

        // Mendapatkan data pelanggan terbaru
        $latestCustomers = Customer::with(['package', 'creator'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Mendapatkan data invoice terbaru
        $latestInvoices = Invoice::with(['customer', 'package'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Menghitung total pendapatan per bulan untuk grafik
        $monthlyRevenue = Invoice::selectRaw('MONTH(invoice_date) as month, YEAR(invoice_date) as year, SUM(total_amount) as total')
            ->where('status', 'paid')
            ->whereYear('invoice_date', now()->year)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $chartData = [
            'labels' => [],
            'data' => []
        ];

        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        foreach ($months as $monthNum => $monthName) {
            $chartData['labels'][] = $monthName;
            $revenue = $monthlyRevenue->firstWhere('month', $monthNum);
            $chartData['data'][] = $revenue ? $revenue->total : 0;
        }

        return view('superadmin.dashboard', compact(
            'totalAdmins', 
            'totalTechnicians',
            'totalCustomers',
            'totalInvoices', 
            'totalPaidInvoices',
            'totalUnpaidInvoices',
            'totalOverdueInvoices',
            'totalRevenue',
            'totalUnpaid',
            'totalOverdue',
            'totalTax',
            'totalTechnicianFee',
            'latestCustomers',
            'latestInvoices',
            'chartData'
        ));
    }

    /**
     * Menampilkan daftar admin
     */
    public function adminIndex()
    {
        $admins = User::whereHas('role', function ($query) {
            $query->where('name', 'admin');
        })->get();

        return view('superadmin.admin.index', compact('admins'));
    }

    /**
     * Menampilkan form untuk membuat admin baru
     */
    public function adminCreate()
    {
        return view('superadmin.admin.create');
    }

    /**
     * Menyimpan admin baru
     */
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        $adminRole = Role::where('name', 'admin')->first();

        if (!$adminRole) {
            return redirect()->back()->with('error', 'Peran admin tidak ditemukan');
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'role_id' => $adminRole->id,
            'is_active' => true,
        ]);

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Admin berhasil ditambahkan');
    }

    /**
     * Menampilkan form untuk mengedit admin
     */
    public function adminEdit(User $admin)
    {
        return view('superadmin.admin.edit', compact('admin'));
    }

    /**
     * Memperbarui data admin
     */
    public function adminUpdate(Request $request, User $admin)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($admin->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->phone = $validated['phone'];
        $admin->is_active = $request->has('is_active');

        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Admin berhasil diperbarui');
    }

    /**
     * Menghapus admin
     */
    public function adminDestroy(User $admin)
    {
        $admin->delete();

        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Admin berhasil dihapus');
    }

    /**
     * Menampilkan daftar teknisi
     */
    public function technicianIndex()
    {
        $technicians = User::whereHas('role', function ($query) {
            $query->where('name', 'technician');
        })->withCount('customers')->get();

        return view('superadmin.technician.index', compact('technicians'));
    }

    /**
     * Menampilkan form untuk membuat teknisi baru
     */
    public function technicianCreate()
    {
        return view('superadmin.technician.create');
    }

    /**
     * Menyimpan teknisi baru
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

        return redirect()->route('superadmin.technicians.index')
            ->with('success', 'Teknisi berhasil ditambahkan');
    }

    /**
     * Menampilkan form untuk mengedit teknisi
     */
    public function technicianEdit(User $technician)
    {
        return view('superadmin.technician.edit', compact('technician'));
    }

    /**
     * Memperbarui data teknisi
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

        return redirect()->route('superadmin.technicians.index')
            ->with('success', 'Teknisi berhasil diperbarui');
    }

    /**
     * Menghapus teknisi
     */
    public function technicianDestroy(User $technician)
    {
        $technician->delete();

        return redirect()->route('superadmin.technicians.index')
            ->with('success', 'Teknisi berhasil dihapus');
    }

    /**
     * Menampilkan daftar semua invoice
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
        
        return view('superadmin.invoice.index', compact(
            'invoices',
            'totalInvoices',
            'paidInvoices',
            'unpaidInvoices',
            'overdueInvoices'
        ));
    }

    /**
     * Menampilkan detail invoice
     */
    public function invoiceShow(Invoice $invoice)
    {
        $invoice->load(['customer', 'package', 'creator']);

        return view('superadmin.invoice.show', compact('invoice'));
    }

    /**
     * Mencetak invoice
     */
    public function invoicePrint(Invoice $invoice)
    {
        // Update status cetak superadmin jika belum tercetak
        if (!$invoice->is_printed_superadmin) {
            $invoice->update([
                'is_printed_superadmin' => true,
                'printed_at_superadmin' => now(),
                'printed_by_superadmin' => Auth::id(),
            ]);
        }
        $invoice->load(['customer', 'package', 'creator']);
        return view('superadmin.invoice.print', compact('invoice'));
    }

    /**
     * Menampilkan laporan keuangan
     */
    public function financialReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $paymentStatus = $request->input('payment_status', '');

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
            $customerCount = 0;
            $customerIds = [];

            foreach ($technician->customers as $customer) {
                if (!in_array($customer->id, $customerIds)) {
                    $customerIds[] = $customer->id;
                    $customerCount++;
                }
                
                foreach ($customer->invoices as $invoice) {
                    // Filter berdasarkan status pembayaran jika ada
                    if ($paymentStatus && $invoice->status !== $paymentStatus) {
                        continue;
                    }
                    
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
                    $technicianRevenue += $basePrice; // Menggunakan base_price sebagai revenue
                    $technicianPtFee += $ptFeeAmount;
                    $invoiceCount++;
                }
            }
            
            // Cek status cetak dan pembayaran dari tabel mitra_reports
            $mitraReport = \App\Models\MitraReport::where('technician_id', $technician->id)
                ->where('periode_awal', $startDate)
                ->where('periode_akhir', $endDate)
                ->first();
            
            $isPrinted = false;
            $isPaid = false;
            
            if ($mitraReport) {
                $isPrinted = $mitraReport->is_printed;
                $isPaid = $mitraReport->is_paid;
            }

            $technicianData[] = [
                'id' => $technician->id,
                'name' => $technician->name,
                'email' => $technician->email,
                'customer_count' => $customerCount,
                'invoice_count' => $invoiceCount,
                'total_revenue' => $technicianRevenue,
                'fee_amount' => $technicianFee,
                'ppn_amount' => $technicianPPN,
                'pt_fee' => $technicianPtFee,
                'is_printed' => $isPrinted,
                'is_paid' => $isPaid,
            ];

            $totalFee += $technicianFee;
            $totalPPN += $technicianPPN;
            $totalRevenue += $technicianRevenue;
            $totalPtFee += $technicianPtFee;
        }
        
        // Urutkan berdasarkan total revenue (tertinggi ke terendah)
        usort($technicianData, function ($a, $b) {
            return $b['total_revenue'] <=> $a['total_revenue'];
        });

        // Ambil data invoice untuk perhitungan total
        $invoices = Invoice::whereBetween('invoice_date', [$startDate, $endDate]);
        
        if ($paymentStatus) {
            $invoices->where('status', $paymentStatus);
        }
        
        $invoices = $invoices->get();

        $totalAmount = $invoices->sum('amount');
        $totalTax = $invoices->sum('tax_amount');
        $totalTechnicianFee = $totalFee; // Menggunakan hasil perhitungan dari loop di atas
        $totalPaid = $invoices->where('status', 'paid')->sum('total_amount');
        $totalUnpaid = $invoices->whereIn('status', ['unpaid', 'overdue'])->sum('total_amount');

        return view('superadmin.financial.report', compact(
            'invoices',
            'startDate',
            'endDate',
            'paymentStatus',
            'totalAmount',
            'totalTax',
            'totalTechnicianFee',
            'totalRevenue',
            'totalPaid',
            'totalUnpaid',
            'totalPtFee',
            'totalPPN',
            'totalFee',
            'technicianData'
        ));
    }

    /**
     * Mencetak laporan keuangan
     */
    public function financialReportPrint(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $paymentStatus = $request->input('payment_status', '');

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
            $customerCount = 0;
            $customerIds = [];

            foreach ($technician->customers as $customer) {
                if (!in_array($customer->id, $customerIds)) {
                    $customerIds[] = $customer->id;
                    $customerCount++;
                }
                
                foreach ($customer->invoices as $invoice) {
                    // Filter berdasarkan status pembayaran jika ada
                    if ($paymentStatus && $invoice->status !== $paymentStatus) {
                        continue;
                    }
                    
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
                    $technicianRevenue += $basePrice; // Menggunakan base_price sebagai revenue
                    $technicianPtFee += $ptFeeAmount;
                    $invoiceCount++;
                }
            }
            
            // Cek status cetak dan pembayaran dari tabel mitra_reports
            $mitraReport = \App\Models\MitraReport::where('technician_id', $technician->id)
                ->where('periode_awal', $startDate)
                ->where('periode_akhir', $endDate)
                ->first();
            
            $isPrinted = false;
            $isPaid = false;
            
            if ($mitraReport) {
                $isPrinted = $mitraReport->is_printed;
                $isPaid = $mitraReport->is_paid;
            }

            $technicianData[] = [
                'id' => $technician->id,
                'name' => $technician->name,
                'email' => $technician->email,
                'customer_count' => $customerCount,
                'invoice_count' => $invoiceCount,
                'total_revenue' => $technicianRevenue,
                'fee_amount' => $technicianFee,
                'ppn_amount' => $technicianPPN,
                'pt_fee' => $technicianPtFee,
                'is_printed' => $isPrinted,
                'is_paid' => $isPaid,
            ];

            $totalFee += $technicianFee;
            $totalPPN += $technicianPPN;
            $totalRevenue += $technicianRevenue;
            $totalPtFee += $technicianPtFee;
        }
        
        // Urutkan berdasarkan total revenue (tertinggi ke terendah)
        usort($technicianData, function ($a, $b) {
            return $b['total_revenue'] <=> $a['total_revenue'];
        });

        // Ambil data invoice untuk perhitungan total
        $invoices = Invoice::whereBetween('invoice_date', [$startDate, $endDate]);
        
        if ($paymentStatus) {
            $invoices->where('status', $paymentStatus);
        }
        
        $invoices = $invoices->get();

        $totalAmount = $invoices->sum('amount');
        $totalTax = $invoices->sum('tax_amount');
        $totalTechnicianFee = $totalFee; // Menggunakan hasil perhitungan dari loop di atas
        $totalPaid = $invoices->where('status', 'paid')->sum('total_amount');
        $totalUnpaid = $invoices->whereIn('status', ['unpaid', 'overdue'])->sum('total_amount');

        return view('superadmin.financial.print', compact(
            'invoices',
            'startDate',
            'endDate',
            'paymentStatus',
            'totalAmount',
            'totalTax',
            'totalTechnicianFee',
            'totalRevenue',
            'totalPaid',
            'totalUnpaid',
            'totalPtFee',
            'totalPPN',
            'totalFee',
            'technicianData'
        ));
    }

    /**
     * Menampilkan daftar pelanggan
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
        
        return view('superadmin.customer.index', compact(
            'customers',
            'totalCustomers',
            'activeCustomers', 
            'inactiveCustomers',
            'newCustomersThisMonth'
        ));
    }

    /**
     * Menampilkan detail pelanggan
     */
    public function customerShow(Customer $customer)
    {
        $invoices = $customer->invoices()->latest()->get();
        return view('superadmin.customer.show', compact('customer', 'invoices'));
    }

    /**
     * Menghapus pelanggan
     */
    public function customerDestroy(Customer $customer)
    {
        // Hapus semua invoice terkait terlebih dahulu
        $customer->invoices()->delete();
        
        // Kemudian hapus pelanggan
        $customer->delete();

        return redirect()->route('superadmin.customers.index')
            ->with('success', 'Pelanggan berhasil dihapus beserta semua invoice terkait');
    }

    /**
     * Menghapus invoice
     */
    public function invoiceDestroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('superadmin.invoices.index')
            ->with('success', 'Invoice berhasil dihapus');
    }

    /**
     * Menampilkan daftar paket internet
     */
    public function packageIndex()
    {
        $packages = Package::all();
        return view('superadmin.package.index', compact('packages'));
    }

    /**
     * Menampilkan form tambah paket internet
     */
    public function packageCreate()
    {
        return view('superadmin.package.create');
    }

    /**
     * Menyimpan data paket internet baru
     */
    public function packageStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['is_active'] = true;

        Package::create($data);

        return redirect()->route('superadmin.packages.index')
            ->with('success', 'Paket internet berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit paket internet
     */
    public function packageEdit(Package $package)
    {
        return view('superadmin.package.edit', compact('package'));
    }

    /**
     * Memperbarui data paket internet
     */
    public function packageUpdate(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $package->update($request->all());

        return redirect()->route('superadmin.packages.index')
            ->with('success', 'Paket internet berhasil diperbarui.');
    }

    /**
     * Menghapus data paket internet
     */
    public function packageDestroy(Package $package)
    {
        // Cek apakah paket digunakan oleh pelanggan
        if ($package->customers()->count() > 0) {
            return redirect()->route('superadmin.packages.index')
                ->with('error', 'Paket tidak dapat dihapus karena digunakan oleh pelanggan.');
        }

        $package->delete();

        return redirect()->route('superadmin.packages.index')
            ->with('success', 'Paket internet berhasil dihapus.');
    }
}
