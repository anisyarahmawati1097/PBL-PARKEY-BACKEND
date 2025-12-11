<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Harga;

class Kendaraan extends Model
{
    protected $fillable = [
        'plat_nomor',
        'jenis',
        'merk',
        'model',
        'tahun',
        'warna',
        'foto',
        'qris',
        'users_id',
        'pemilik',
        'qr_token' // <--- penting
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function parkirs()
    {
        return $this->hasMany(Parkir::class, 'kendaraans_id');
    }
    public function harga()
    {
        return $this->hasMany(Harga::class, 'jenis_kendaraan', 'jenis');
    }

}
