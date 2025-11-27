<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('kendaraans', function (Blueprint $table) {
        $table->id();
        $table->string('plat_nomor');
        $table->string('jenis'); // Mobil/Motor/Truk/Pickup
        $table->string('merk');
        $table->string('model');
        $table->string('warna');
        $table->string('tahun');
        $table->string('foto')->nullable(); // path upload foto
        $table->string('pemilik')->nullable(); // kalau memang butuh
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraans');
    }
};
