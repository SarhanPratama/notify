<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewStok extends Model
{
    protected $table = 'view_stok';

    protected $fillable = [
        'nama',
        'stok_awal',
        'stok_masuk',
        'stok_keluar',
        'saldo_akhir',
        'nama_satuan',
    ];

     public function getStokAkhirAttribute($value)
    {
        return ($value == 0 || $value === null) ? $this->stok_awal : $value;
    }

    public function bahanBaku()
    {
        return $this->belongsTo(bahanBaku::class, 'id');
    }
}
