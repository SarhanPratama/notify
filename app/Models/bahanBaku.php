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
        'stok',
        'stok_minimum',
        'harga',
        'id_satuan',
    ];

    public function satuan() {
        return $this->belongsTo(Satuan::class, 'id_satuan');
    }

    public function resep()
    {
        return $this->hasMany(Resep::class, 'id_bahan_baku');
    }
}
