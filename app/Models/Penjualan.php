<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $fillable = [
        'nobukti',
        'total',
        'tanggal',
        // 'status',
        'catatan',
        'id_cabang',
        // 'id_user'
    ];

    public function mutasi()
    {
        return $this->hasMany(mutasi::class, 'nobukti', 'nobukti');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'id_cabang');
    }
}
