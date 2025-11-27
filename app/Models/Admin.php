<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'admins'; // sesuai migration

    protected $primaryKey = 'id_admin'; // sesuai migration

    public $incrementing = true; // id auto increment
    protected $keyType = 'int';

    protected $fillable = [
        'username',
        'nama_admin',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
