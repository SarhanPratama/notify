<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailResep extends Model
{

    protected $table = 'detail_resep';
    protected $fillable = ['id_resep', 'id_bahan_baku', 'jumlah'];

    public function resep()
    {
        return $this->belongsTo(Resep::class, 'id_resep');
    }

    // Relasi ke tabel bahan_baku
    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan_baku');
    }
}
