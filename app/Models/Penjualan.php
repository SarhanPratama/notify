<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $fillable = [
        'nobukti',
        'total',
        'tanggal',
        'status',
        'metode_pembayaran',
        'catatan',
        'id_outlet',
        // 'id_user'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $prefix = 'PO-' . date('Ymd');

            $last = Penjualan::where('nobukti', 'like', $prefix . '%')
                ->orderByDesc('nobukti')
                ->first();

            $nextNumber = 1;

            if ($last) {
                $lastNumber = (int)substr($last->nobukti, -4);
                $nextNumber = $lastNumber + 1;
            }

            $model->nobukti = $prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        });
    }

    protected function catatan(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ?? 'Tidak ada catatan',
        );
    }
    public function mutasi()
    {
        return $this->morphMany(mutasi::class, 'mutasiable');
    }

    public function transaksi()
    {
        return $this->morphOne(Transaksi::class, 'referenceable');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'id_outlet');
    }

    public function piutang()
    {
        return $this->hasOne(Piutang::class, 'nobukti', 'nobukti');
    }
}
