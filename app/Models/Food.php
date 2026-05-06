<?php
// app/Models/Food.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;
    protected $table = 'foods';
    protected $fillable = [
        'name', 'category', 'portion',
        'donor_id', 'receiver_id',
        'status', 'collection_date',
        'photo', 'note',
    ];

    protected $casts = [
        'collection_date' => 'datetime',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function receiver()
    {
        return $this->belongsTo(Receiver::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}
