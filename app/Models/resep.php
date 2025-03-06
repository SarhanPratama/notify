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
        'instruksi',
    ];

    public function produk()
    {
        return $this->belongsTo(products::class, 'id_products');
    }

    public function bahanBaku()
    {
        return $this->belongsTo(bahanBaku::class, 'id_bahan_baku');
    }

    public function detailResep()
    {
        return $this->hasMany(DetailResep::class, 'id_resep');
    }
}
