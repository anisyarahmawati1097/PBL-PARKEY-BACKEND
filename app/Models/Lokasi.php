<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $table = 'lokasis';

    // ✅ PRIMARY KEY SESUAI DATABASE
    protected $primaryKey = 'id_lokasi';

    public $incrementing = true;
    protected $keyType = 'int';

    // ✅ SESUAI NAMA KOLOM DI DATABASE
    protected $fillable = [
        'nama_lokasi',
        'alamat_lokasi',
    ];

    // ✅ RELASI DIPERBAIKI (id → id_lokasi)

    public function admin()
{
    return $this->hasMany(Admin::class, 'id_lokasi', 'id_lokasi');
}

}
