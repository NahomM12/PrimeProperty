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
        'seller_tab',
        'wishlist',
        'preference',
        'language', 
        'mode', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'wishlist' => 'array',
        //'sellertab' => '',
    ];
    protected $attributes = [
        'role' => 'customer',
        'seller_tab' => 'inactive',
        'preference' => 'light',
    ];
 //create relationship
public function properties()
{
    return $this->hasMany(Property::class, 'property_views');
}
public function isAdmin()
{
    return $this->admin()->exists();
}

public function isManager()
{
    return $this->manager()->exists() && $this->manager->status === 'active';
}

public function isSeller()
{
    return $this->mode === 'seller' && $this->seller_tab === 'active';
}

public function isCustomer()
{
    return $this->mode === 'customer';
}
public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

