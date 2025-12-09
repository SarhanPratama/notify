<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewSaldo extends Model
{
    protected $table = 'view_saldo_dana';

    protected $fillable = [
        'id_sumber_dana',
        'saldo_awal',
        'total_pemasukan',
        'total_pengeluaran',
        'saldo_current',
    ];

        public function sumberDana()
    {
        return $this->belongsTo(SumberDana::class, 'id_sumber_dana', 'id');
    }
}
