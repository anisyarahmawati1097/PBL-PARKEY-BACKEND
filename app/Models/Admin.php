<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'admins';
    protected $primaryKey = 'id_admin';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'username',
        'nama_admin',
        'password',
        'lokasi_id', // sudah sesuai
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi ke Lokasi (BENAR)

    public function lokasi()
{
    return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
}

}
