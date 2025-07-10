<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class mutasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mutasi';

    public $timestamps = false;

    protected $fillable = [
        'id_bahan_baku',
        'quantity',
        'harga',
        'sub_total',
        'nobukti',
        'jenis_transaksi',
        'status',
        'mutasiable_id',
        'mutasiable_type',
    ];

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan_baku');
    }

        public function mutasiable()
    {
        return $this->morphTo();
    }

}
