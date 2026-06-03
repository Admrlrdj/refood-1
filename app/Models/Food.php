<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Food extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'foods';

    // Sesuaikan fillable dengan field yang kamu minta
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

    // Pastikan collection_date tersimpan sebagai format Date di MongoDB (bukan string biasa)
    protected $casts = [
        'collection_date' => 'datetime',
    ];
}
