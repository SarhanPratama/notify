<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class resep extends Model
{
    protected $table = 'resep';

    protected $fillable = [
        'jumlah',
        'id_products',
        'id_bahan_baku',
    ];

    public function produk()
    {
        return $this->belongsTo(products::class, 'id_products');
    }

    /**
     * Relasi dengan model BahanBaku.
     */
    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan_baku');
    }
}
