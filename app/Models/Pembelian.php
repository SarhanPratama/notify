<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';

    protected $fillable = [
        'total',
        'status',
        'id_supplier'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function detailPembelian()
    {
        return $this->hasMany(DetailPembelian::class);
    }
}
