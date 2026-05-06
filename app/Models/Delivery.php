<?php
// app/Models/Delivery.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'food_id', 'donor_id', 'receiver_id', 'volunteer_id',
        'status', 'pickup_time', 'eta_minutes', 'is_expiring', 'note', 'lat', 'lng',
    ];

    protected $casts = [
        'pickup_time' => 'datetime',
        'is_expiring' => 'boolean',
    ];

    public function food()      { return $this->belongsTo(Food::class); }
    public function donor()     { return $this->belongsTo(Donor::class); }
    public function receiver()  { return $this->belongsTo(Receiver::class); }
    public function volunteer() { return $this->belongsTo(Volunteer::class); }
}
