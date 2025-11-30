<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parkirs()
    {
        return $this->hasMany(Parkir::class, 'kendaraans_id');
    }
}
