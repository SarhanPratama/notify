<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriKeuangan extends Model
{
    protected $table = 'kategori_keuangan';
    protected $fillable = ['nama', 'jenis'];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_kategori_keuangan');
    }
}
