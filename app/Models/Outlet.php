<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Outlet extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'outlet';

    protected $fillable = [
        'kode',
        'nama',
        'penanggung_jawab',
        'alamat',
        'telepon',
        'lokasi',
        'foto',
        'barcode_token',
        'barcode_generated_at',
        'barcode_active'
        // 'id_user'
    ];

    protected $dates = [
        'barcode_generated_at'
    ];

        public function Penjualan()
    {
        return $this->hasMany(Penjualan::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_cabang', 'id');
    }
    protected $casts = [
    'barcode_generated_at' => 'datetime',
];

    /**
     * Generate unique barcode token for this outlet
     */
    public function generateBarcodeToken()
    {
        $this->barcode_token = 'OUTLET_' . strtoupper(uniqid()) . '_' . $this->id;
        $this->barcode_generated_at = now();
        $this->barcode_active = true;
        $this->save();
        return $this->barcode_token;
    }

    /**
     * Get barcode URL attribute
     */
    public function getBarcodeUrlAttribute()
    {
        if (!$this->barcode_token) {
            return null;
        }
        return url('/outlet/' . $this->barcode_token . '/order');
    }

    /**
     * Regenerate barcode token
     */
    public function regenerateBarcodeToken()
    {
        return $this->generateBarcodeToken();
    }

    /**
     * Deactivate barcode
     */
    public function deactivateBarcode()
    {
        $this->barcode_active = false;
        $this->save();
    }

}
