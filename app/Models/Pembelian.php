<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembelian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pembelian';

    protected $fillable = [
        'nobukti',
        'total',
        'tanggal',
        'status',
        'catatan',
        'id_supplier',
        // 'id_user'
    ];

      protected function catatan(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ?? 'Tidak ada catatan',
        );
    }
    public function mutasi()
    {
        return $this->morphMany(mutasi::class, 'mutasiable');
    }

    public function transaksi()
    {
        return $this->morphMany(Transaksi::class, 'referenceable');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
