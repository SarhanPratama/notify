<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VSaldoAkhir extends Model
{
    protected $table = 'vsaldoakhir';

    protected $fillable = [
        'nama',
        'stok_awal',
        'saldoakhir',
        'totalmasuk',
        'totalkeluar',
        'nama_satuan',
    ];
}
