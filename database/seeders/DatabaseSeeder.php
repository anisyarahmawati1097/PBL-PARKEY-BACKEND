<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Roles;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed roles
        Roles::create([
            'role_name' => "member"
        ]);

        Roles::create([
            'role_name' => "admin"
        ]);

        // Seed default user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'roles_id' => 1
        ]);

        // 💡 Tambahkan seeder lokasi & admin
        $this->call([
            LokasiSeeder::class,
            AdminSeeder::class,
        ]);
        $this->call(HargaSeeder::class);
        $this->call(SlotSeeder::class);

    }
}
