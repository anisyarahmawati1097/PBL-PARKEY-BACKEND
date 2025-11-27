<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Admin::create([
        'username' => 'admin',
        'nama_admin' => 'administrator',
        'password' => Hash::make('1234'),
       ]); 
    }
}
