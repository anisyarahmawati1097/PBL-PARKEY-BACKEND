<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'fullname',        // nama lengkap
        'name',            // username
        'email',
        'tanggal_lahir',
        'phone',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function kendaraans()
    {
        return $this->hasMany(Kendaraan::class);
    }

    public function roles()
    {
        return $this->belongsTo(Roles::class);
    }
}
