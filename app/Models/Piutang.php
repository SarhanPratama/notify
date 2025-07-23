<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Piutang extends Model
{
        use HasFactory;

    protected $table = 'piutang';

    protected $fillable = ['nobukti', 'jumlah_piutang', 'jatuh_tempo', 'status'];

    protected $casts = [
        'jatuh_tempo' => 'date',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'nobukti', 'nobukti');
    }

    public function pembayaran()
{
    return $this->hasMany(PiutangPembayaran::class, 'id_piutang');
}

}
