<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discounts extends Model
{
    use HasFactory;

    protected $table = 'discounts';

    protected $fillable = [
        'product_id',
        'minimal_qty',
        'status',
        'tanggal_mulai',
        'tanggal_selesai'
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
