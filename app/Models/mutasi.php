<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class mutasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mutasi';

    // public $timestamps = true;

    protected $fillable = [
        'nobukti',
        'id_bahan_baku',
        'quantity',
        'harga',
        'sub_total',
        'jenis_transaksi',
        'status',
    ];

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan_baku');
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'nobukti', 'nobukti');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'nobukti', 'nobukti');
    }

}
