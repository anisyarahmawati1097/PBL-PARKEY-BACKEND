<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string("role_name");
            $table->boolean("status")->default(true);
            $table->timestamps();
        });

        // Tambahkan data default agar FK tidak error
        DB::table('roles')->insert([
            ['id' => 1, 'role_name' => 'admin', 'status' => true],
            ['id' => 2, 'role_name' => 'user', 'status' => true],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
