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
        //'wishlist',
        'preference',
        'language', //eng
        'mode', // seller or customer default customer update migration
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
//create relationship
    
}