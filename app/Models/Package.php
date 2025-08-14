<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'price',
        'base_price',
        'tax_amount',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'base_price' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Konstanta PPN
     */
    const PPN_RATE = 0.11;

    /**
     * Set harga (price) dan otomatis menghitung base_price dan tax_amount
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value;
        $this->attributes['base_price'] = round($value / (1 + self::PPN_RATE), 2);
        $this->attributes['tax_amount'] = round($value - $this->attributes['base_price'], 2);
    }

    /**
     * Get the customers for the package.
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Get the invoices for the package.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
