<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'package_id',
        'invoice_date',
        'due_date',
        'amount',
        'tax_percentage',
        'tax_amount',
        //'technician_fee_percentage',
        //'technician_fee_amount',
        'total_amount',
        'status',
        'notes',
        'created_by',
        'is_printed',
        'printed_at',
        'printed_by',
        'is_printed_admin',
        'printed_at_admin',
        'printed_by_admin',
        'is_printed_technician',
        'printed_at_technician',
        'printed_by_technician',
        'is_printed_superadmin',
        'printed_at_superadmin',
        'printed_by_superadmin',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'technician_fee_percentage' => 'decimal:2',
        'technician_fee_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'invoice_date' => 'date',
        'due_date' => 'date',
        'is_printed' => 'boolean',
        'printed_at' => 'datetime',
        'is_printed_admin' => 'boolean',
        'printed_at_admin' => 'datetime',
        'is_printed_technician' => 'boolean',
        'printed_at_technician' => 'datetime',
        'is_printed_superadmin' => 'boolean',
        'printed_at_superadmin' => 'datetime',
    ];

    /**
     * Konstanta PPN
     */
    const PPN_RATE = 0.11;
    
    /**
     * Mendapatkan harga dasar sebelum PPN
     */
    public function getBasePriceAttribute()
    {
        // Amount pada invoice adalah harga termasuk PPN, hitung harga dasar dengan membagi dengan (1 + PPN)
        return round($this->amount / (1 + self::PPN_RATE), 2);
    }

    /**
     * Get the customer that owns the invoice.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the package that owns the invoice.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the user that created the invoice.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user that printed the invoice.
     */
    public function printer()
    {
        return $this->belongsTo(User::class, 'printed_by');
    }
}
