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
        Schema::create('harga', function (Blueprint $table) {
            $table->id('id_harga');

            // Foreign key ke tabel lokasi
            $table->unsignedBigInteger('id_lokasi');
            $table->foreign('id_lokasi')
                ->references('id_lokasi')
                ->on('lokasis')
                ->onDelete('cascade');

            // Semua jenis kendaraan lengkap
            $table->enum('jenis_kendaraan', [
                'motor',
                'mobil',
                'pickup',
                'truck',
                'bus'
            ]);

            // Harga 2 jam pertama
            $table->integer('harga');

            // Tambahan per jam
            $table->integer('tambahan_per_jam');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga');
    }
};
