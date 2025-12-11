<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slots', function (Blueprint $table) {
            $table->id('id_slot');

            $table->unsignedBigInteger('id_lokasi');
            $table->foreign('id_lokasi')
                  ->references('id_lokasi')
                  ->on('lokasis')
                  ->onDelete('cascade');
            $table->integer('slot'); // jumlah slot tersedia
            $table->enum('status', ['available', 'terisi', 'penuh'])->default('available');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slots');
    }
};
