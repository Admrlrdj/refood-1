<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Food;

class Donor extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'donors';
    protected $primaryKey = '_id';

    protected $fillable = [
        'name',
        'username',
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

    public function foods()
    {
        // Karena di dokumen desain database Anda donor_id adalah FK di collection foods
        return $this->hasMany(Food::class, 'donor_id', '_id');
    }
}
