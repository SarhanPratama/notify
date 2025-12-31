<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaksi';

    protected $fillable = [
        'nobukti',
        'tanggal',
        'jumlah',
        'posisi_kas',
        'transaksiable_id',
        'transaksiable_type',
        'deskripsi',
        'status',
        'id_kategori_keuangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function transaksiable()
    {
        return $this->morphTo();
    }

    public function kategoriKeuangan()
    {
        return $this->belongsTo(KategoriKeuangan::class, 'id_kategori_keuangan');
    }
}
