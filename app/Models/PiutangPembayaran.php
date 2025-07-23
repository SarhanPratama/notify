<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PiutangPembayaran extends Model
{
    use HasFactory;

    protected $table = 'piutang_pembayaran';

    protected $fillable = ['id_piutang', 'id_sumber_dana','tanggal', 'jumlah', 'keterangan'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function piutang()
    {
        return $this->belongsTo(Piutang::class, 'id_piutang');
    }

    public function sumberDana()
    {
        return $this->belongsTo(SumberDana::class, 'id_sumber_dana');
    }
}
