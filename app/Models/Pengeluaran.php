<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengeluaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengeluaran';

    protected $fillable = [
        'nobukti',
        'tanggal',
        'jumlah',
        'deskripsi',
        'id_kategori_keuangan',
        'status',
        'posisi_kas',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function kategoriKeuangan()
    {
        return $this->belongsTo(KategoriKeuangan::class, 'id_kategori_keuangan');
    }

    public function sumberDana()
    {
        return $this->belongsTo(SumberDana::class, 'id_sumber_dana');
    }

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'nobukti', 'nobukti');
    }
}
