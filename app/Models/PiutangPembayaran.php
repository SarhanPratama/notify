<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PiutangPembayaran extends Model
{
    use HasFactory;

    protected $table = 'piutang_pembayaran';

    protected $fillable = ['nobukti', 'id_sumber_dana','tanggal', 'jumlah', 'keterangan'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function piutang()
    {
        return $this->belongsTo(Piutang::class, 'nobukti', 'nobukti');
    }

        public function transaksi()
    {
        return $this->morphMany(Transaksi::class, 'transaksiable');
    }

}
