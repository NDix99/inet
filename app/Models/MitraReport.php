<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MitraReport extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'technician_id',
        'periode_awal',
        'periode_akhir',
        'total_fee',
        'total_revenue',
        'total_pt_fee',
        'total_ppn',
        'is_printed',
        'printed_at',
        'printed_by',
        'is_paid',
        'payment_date',
        'payment_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_fee' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'total_pt_fee' => 'decimal:2',
        'total_ppn' => 'decimal:2',
        'periode_awal' => 'date',
        'periode_akhir' => 'date',
        'is_printed' => 'boolean',
        'printed_at' => 'datetime',
        'is_paid' => 'boolean',
        'payment_date' => 'datetime',
    ];

    /**
     * Get the technician that owns the report.
     */
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    /**
     * Get the user that printed the report.
     */
    public function printer()
    {
        return $this->belongsTo(User::class, 'printed_by');
    }
}
