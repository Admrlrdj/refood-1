<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable; // Penting untuk Auth
use Laravel\Sanctum\HasApiTokens;

class Donor extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'donors';
    protected $primaryKey = '_id';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'type',
        'restaurant_name',
        'password',
        'is_verified',
        'status'
    ];

    protected $hidden = [
        'password', // Agar password tidak terekspos di API
    ];

    // Casts untuk memastikan tipe data benar
    protected $casts = [
        'is_verified' => 'boolean',
    ];
}
