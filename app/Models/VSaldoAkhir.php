<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VSaldoAkhir extends Model
{
    protected $table = 'vsaldoakhir2';

    protected $fillable = [
        'nama',
        'stok_awal',
        'saldoakhir',
        'masuk',
        'keluar',
        'nama_satuan',
    ];
}
