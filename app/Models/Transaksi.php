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
        // 'id_bahan_baku',
        // 'id_sumber_dana',
        'tanggal',
        'tipe',
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

        public function SumberDana()
    {
        return $this->belongsTo(SumberDana::class, 'id_sumber_dana');
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'nobukti', 'nobukti');
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'nobukti', 'nobukti');
    }

    public function piutang()
    {
        return $this->belongsTo(Piutang::class, 'nobukti', 'nobukti');
    }
}
