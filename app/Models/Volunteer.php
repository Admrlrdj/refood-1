<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens; // <--- Wajib untuk Login Sanctum

class Volunteer extends Model
{
    use HasFactory, HasApiTokens;

    protected $connection = 'mongodb';
    protected $collection = 'volunteers';

    protected $fillable = [
        'name',
        'username',
        'phone',
        'email',
        'password',
        'address',
        'vehicle_type',
        'vehicle_plate',
        'verification_status',
        'is_online',
        'is_verified',
        'status'
    ];

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}
