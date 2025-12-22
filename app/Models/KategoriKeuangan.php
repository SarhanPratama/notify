<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriKeuangan extends Model
{
    protected $table = 'kategori_keuangan';
    protected $fillable = ['nama', 'jenis'];

    public function pemasukan()
    {
        return $this->hasMany(Pemasukan::class, 'id_kategori_keuangan');
    }

    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'id_kategori_keuangan');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_kategori_keuangan');
    }
}
