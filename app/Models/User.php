<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'sellertab',
        'wishlist',
        'preference',
        'language',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'wishlist' => 'array',
        'sellertab' => 'boolean',
    ];
    protected $attributes = [
        'role' => 'customer',
        'sellertab' => false,
        'preference' => 'light',
    ];

    public function owner()
    {
        return $this->hasOne(Owner::class);
    }

    public function sellerRequest()
    {
        return $this->hasOne(SellerRequest::class);
    }
}