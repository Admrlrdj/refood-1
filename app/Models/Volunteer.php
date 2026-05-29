<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    
    protected $fillable = [
        'name', 'phone', 'email',
        'address', 'vehicle_type', 'vehicle_plate',
    ];

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}
