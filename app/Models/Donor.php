<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Donor extends Model
{
    protected $connection = 'mongodb';
    
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'pic_name',
        'phone', 'email', 'address',
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
