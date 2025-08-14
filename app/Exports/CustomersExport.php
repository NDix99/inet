<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class CustomersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $technicianFilter;
    protected $statusFilter;
    protected $search;

    public function __construct($technicianFilter = null, $statusFilter = null, $search = null)
    {
        $this->technicianFilter = $technicianFilter;
        $this->statusFilter = $statusFilter;
        $this->search = $search;
    }

    public function collection()
    {
        $query = Customer::with(['package', 'creator']);

        // Filter berdasarkan teknisi
        if ($this->technicianFilter) {
            $query->whereHas('creator', function ($q) {
                $q->where('name', $this->technicianFilter);
            });
        }

        // Filter berdasarkan status
        if ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        // Pencarian global
        if (!empty($this->search)) {
            $search = $this->search;
            $query->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhereHas('package', function ($pq) use ($search) {
                        $pq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('creator', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Pelanggan',
            'Email',
            'Telepon',
            'Alamat',
            'Paket Internet',
            'Harga Paket',
            'Status',
            'Tanggal Billing',
            'Dibuat Oleh (Teknisi)',
            'Tanggal Dibuat',
            'Terakhir Diupdate'
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->id,
            $customer->name,
            $customer->email,
            $customer->phone,
            $customer->address ?? '-',
            $customer->package ? $customer->package->name : 'Tidak ada paket',
            $customer->package ? 'Rp ' . number_format($customer->package->price, 0, ',', '.') : '-',
            $customer->is_active ? 'Aktif' : 'Tidak Aktif',
            $customer->billing_date ? \Carbon\Carbon::parse($customer->billing_date)->format('d/m/Y') : '-',
            $customer->creator ? $customer->creator->name : 'Tidak diketahui',
            $customer->created_at ? $customer->created_at->format('d/m/Y H:i') : '-',
            $customer->updated_at ? $customer->updated_at->format('d/m/Y H:i') : '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header styling
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2E86AB'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Border untuk semua data
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:L' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Alternating row colors - PERBAIKAN UTAMA
        for ($row = 2; $row <= $lastRow; $row++) {
            $color = ($row % 2 == 0) ? 'F8F9FA' : 'FFFFFF';
            $sheet->getStyle('A' . $row . ':L' . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->setStartColor(new \PhpOffice\PhpSpreadsheet\Style\Color($color));
        }

        // Auto-filter
        $sheet->setAutoFilter('A1:L1');

        return $sheet;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 25,  // Nama
            'C' => 30,  // Email
            'D' => 15,  // Telepon
            'E' => 40,  // Alamat
            'F' => 25,  // Paket
            'G' => 20,  // Harga
            'H' => 15,  // Status
            'I' => 20,  // Billing Date
            'J' => 25,  // Teknisi
            'K' => 20,  // Created
            'L' => 20,  // Updated
        ];
    }

    public function title(): string
    {
        $title = 'Daftar Pelanggan';
        
        if ($this->technicianFilter) {
            $title .= ' - Teknisi: ' . $this->technicianFilter;
        }
        
        if ($this->statusFilter) {
            $title .= ' - Status: ' . ($this->statusFilter === 'active' ? 'Aktif' : 'Tidak Aktif');
        }
        
        return $title;
    }
}
