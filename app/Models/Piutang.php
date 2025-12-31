<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Piutang extends Model

{
    use HasFactory;

    protected $table = 'piutang';

    protected $fillable = ['nobukti', 'jumlah_piutang', 'sisa_piutang', 'jatuh_tempo', 'status'];

    protected $casts = [
        'tanggal' => 'date',
        'jatuh_tempo' => 'date',
    ];

    public function transaksi()
    {
        return $this->morphMany(Transaksi::class, 'transaksiable');
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'nobukti', 'nobukti');
    }

    public function pembayaran()
    {
        return $this->hasMany(PiutangPembayaran::class, 'nobukti', 'nobukti');
    }

    // Accessor untuk menghitung sisa piutang
    // public function getSisaPiutangAttribute()
    // {
    //     $totalDibayar = $this->pembayaran()->sum('jumlah');
    //     return $this->jumlah_piutang - $totalDibayar;
    // }

    // // Method untuk cek apakah piutang sudah lunas
    // public function isLunas()
    // {
    //     return $this->sisa_piutang <= 0;
    // }

}
