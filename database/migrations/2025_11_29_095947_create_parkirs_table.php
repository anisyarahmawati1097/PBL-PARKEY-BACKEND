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
        Schema::create('parkirs', function (Blueprint $table) {
            $table->id();
            $table->string('parkir_id');
            $table->timestamp('masuk');
            $table->timestamp('keluar')->nullable();
            $table->integer('harga')->default(0);

            $table->unsignedBigInteger('kendaraans_id');
            $table->foreign('kendaraans_id')->references('id')->on('kendaraans')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parkirs');
    }
};
