<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'nama_admin' => 'administrator',
            'username'   => 'admin-gm',
            'password'   => Hash::make('1111'),
            'id_lokasi'  => 1,
        ]);

        Admin::create([
            'nama_admin' => 'administrator2',
            'username'   => 'admin-snl',
            'password'   => Hash::make('2222'),
            'id_lokasi'  => 2,
        ]);
    }
}

