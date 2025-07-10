<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaksi';

    protected $fillable = [
        'id_sumber_dana',
        'tanggal',
        'tipe',
        'jumlah',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

        public function SumberDana()
    {
        return $this->belongsTo(SumberDana::class, 'id_sumber_dana');
    }
        public function referenceable()
    {
        return $this->morphTo();
    }
}
