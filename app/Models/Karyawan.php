<?php

namespace App\Models;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Karyawan extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'karyawan';

    protected $fillable = [
        'usia',
        'tgl_lahir',
        'telepon',
        'alamat',
        'foto',
        'id_users',
        'id_roles',
        'id_cabang',
        'created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    /**
     * Relasi ke model Cabang
     */
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'id_cabang');
    }

    /**
     * Relasi ke model Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_roles');
    }
}
