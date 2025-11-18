<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewSaldo extends Model
{
    protected $table = 'view_saldo_dana';

    protected $fillable = [
        'nama',
        'saldo_awal',
        'total_pemasukan',
        'total_pengeluaran',
        'saldo_current',
    ];
}
