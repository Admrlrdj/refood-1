<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
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
