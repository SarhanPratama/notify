<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class cash_flow extends Model
{
    use HasFactory;

    protected $table = 'cash_flow';

    protected $fillable = [
        'tanggal',
        'keterangan',
        'nominal',
        'jenis_transaksi',
        'sumber_dana',
        'keperluan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];
}
