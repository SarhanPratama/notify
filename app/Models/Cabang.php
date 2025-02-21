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
        'nama',
        'alamat',
        'telepon',
        'foto',
    ];

}
