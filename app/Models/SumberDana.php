<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SumberDana extends Model
{
      use HasFactory, Notifiable;

    protected $table = 'sumber_dana';

    protected $fillable = [
        'nama',
        'saldo_awal'
    ];

        public function Transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}
