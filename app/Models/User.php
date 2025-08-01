<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];
    
    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Memeriksa apakah pengguna adalah superadmin
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->role && $this->role->name === 'superadmin';
    }

    /**
     * Memeriksa apakah pengguna adalah admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role && $this->role->name === 'admin';
    }

    /**
     * Memeriksa apakah pengguna adalah teknisi
     *
     * @return bool
     */
    public function isTechnician()
    {
        return $this->role && $this->role->name === 'technician';
    }

    /**
     * Get the customers created by the user.
     */
    public function customers()
    {
        return $this->hasMany(Customer::class, 'created_by');
    }

    /**
     * Get the invoices created by the user.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'created_by');
    }
}
