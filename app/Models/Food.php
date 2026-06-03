<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Food extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'foods';

    protected $fillable = [
        'name',
        'category',
        'portion',
        'donor_id',
        'receiver_id',
        'status',
        'collection_date',
        'note',
        'photo_url'
    ];

    protected $casts = [
        'collection_date' => 'datetime',
    ];

    /**
     * Relasi ke model Donor
     */
    public function donor()
    {
        return $this->belongsTo(Donor::class, 'donor_id');
    }

    /**
     * Relasi ke model Receiver
     */
    public function receiver()
    {
        return $this->belongsTo(Receiver::class, 'receiver_id');
    }
}
