<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewStok extends Model
{
    protected $table = 'view_stok';

    protected $fillable = [
        'id_bahan_baku',
        'nama',
        'stok_awal',
        'stok_masuk',
        'stok_keluar',
        'stok_akhir',
        'nama_satuan',
    ];

     public function getStokAkhirAttribute($value)
    {
        return ($value == 0 || $value === null) ? $this->stok_awal : $value;
    }

    public function bahanBaku()
    {
        return $this->belongsTo(bahanBaku::class, 'id_bahan_baku');
    }
}
