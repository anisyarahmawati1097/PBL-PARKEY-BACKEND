<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parkirs', function (Blueprint $table) {
            $table->id();
            $table->string('parkir_id');

            $table->integer('harga')->default(0);

            // Relasi ke lokasis
            $table->unsignedBigInteger('id_lokasi');
            $table->foreign('id_lokasi')
                  ->references('id_lokasi')
                  ->on('lokasis')
                  ->onDelete('cascade');

            // Relasi ke slots
            $table->unsignedBigInteger('id_slot')->nullable();
            $table->foreign('id_slot')
                  ->references('id_slot')
                  ->on('slots')
                  ->onDelete('set null');

            // Timestamps parkir
            $table->timestamp('masuk')->nullable();
            $table->timestamp('keluar')->nullable();

            // Relasi ke kendaraans
            $table->unsignedBigInteger('kendaraans_id');
            $table->foreign('kendaraans_id')
                  ->references('id')
                  ->on('kendaraans')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parkirs');
    }
};
