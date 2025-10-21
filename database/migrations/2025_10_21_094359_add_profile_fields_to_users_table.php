<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom baru ke tabel users
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('email');
            $table->string('full_name')->nullable()->after('username');
            $table->date('tanggal_lahir')->nullable()->after('full_name');
            $table->string('phone', 20)->nullable()->after('tanggal_lahir');
        });
    }

    /**
     * Hapus kolom baru jika rollback
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'full_name', 'tanggal_lahir', 'phone']);
        });
    }
};
