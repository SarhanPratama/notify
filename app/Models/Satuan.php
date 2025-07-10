<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Satuan extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'satuan';

    protected $fillable = [
        'nama',
    ];

    public function bahanBaku() {
        return $this->hasMany(BahanBaku::class, 'id_satuan');
    }

}
