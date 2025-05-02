<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bahanBaku extends Model
{
    use HasFactory;

    protected $table = 'bahan_baku';

    protected $fillable = [
        'nama',
        'stok_awal',
        // 'stok_akhir',
        'stok_minimum',
        'harga',
        'foto',
        'id_satuan',
        'id_kategori'
    ];

    public function satuan() {
        return $this->belongsTo(Satuan::class, 'id_satuan');
    }

    public function kategori() {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    // public function resep()
    // {
    //     return $this->hasMany(Resep::class, 'id_bahan_baku');
    // }
}
