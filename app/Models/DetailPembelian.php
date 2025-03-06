<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPembelian extends Model
{
    use HasFactory;

    protected $table = 'detail_pembelian';

    protected $fillable = [
        'produk',
        'quantity',
        'harga',
        'total_harga',
        'id_pembelian'
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }
}
