<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cabang extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'cabang';

    protected $fillable = [
        'kode',
        'nama',
        'alamat',
        'telepon',
        'lokasi',
        'foto',
        // 'id_user'
    ];

        public function Penjualan()
    {
        return $this->hasMany(Penjualan::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_cabang', 'id');
    }

}
