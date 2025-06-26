<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';

    protected $fillable = [
        'nobukti',
        'total',
        'tanggal',
        // 'status',
        'catatan',
        'id_supplier',
        'id_user'
    ];

    public function mutasi()
    {
        return $this->hasMany(mutasi::class, 'nobukti', 'nobukti');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    public function user() {
        return $this->belongsTo(User::class, 'id_user');
    }
}
