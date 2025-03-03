<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{

    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'kode',
        'nama',
        'stok',
        'harga_modal',
        'harga_jual',
        'deskripsi',
        'foto',
        'id_merek',
        'id_kategori'
    ];

    public function merek() {
        return $this->belongsTo(Merek::class, 'id_merek');
    }

    public function kategori() {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function resep()
    {
        return $this->hasMany(Resep::class, 'id_products');
    }
}
