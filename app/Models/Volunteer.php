<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone', 'email',
        'address', 'vehicle_type', 'vehicle_plate',
    ];

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}
