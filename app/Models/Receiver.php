<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laravel\Sanctum\HasApiTokens;

class Receiver extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable, HasApiTokens;

    protected $connection = 'mongodb';

    // Saya tambahkan 'username' dan 'password' agar bisa digunakan untuk Login
    protected $fillable = [
        'name',
        'username',
        'type',
        'pic_name',
        'phone',
        'email',
        'address',
        'password',
        'capacity_people',
        'need_level',
    ];

    // Sembunyikan password saat data dipanggil
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function foods()
    {
        return $this->hasMany(Food::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}
