<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class mutasi extends Model
{
    use HasFactory;

    protected $table = 'mutasi';

    protected $fillable = [
        'id_bahan_baku',
        'quantity',
        'harga',
        'sub_total',
        'nobukti',
        'jenis_transaksi',
        'status'
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'nobukti', 'nobukti');
    }

    public function bahanBaku()
{
    return $this->belongsTo(bahanBaku::class, 'id_bahan_baku');
}

}
