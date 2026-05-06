<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jarak_simpang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ID_Simpang')->nullable();
            $table->string('dari_arah')->nullable();
            $table->string('ke_arah')->nullable();
            $table->decimal('jarak_km', 10, 4)->nullable();
            $table->decimal('lebar_jalan', 10, 2)->nullable();
            $table->string('nama_ruas')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();

            $table->foreign('ID_Simpang')->references('id')->on('simpang')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jarak_simpang');
    }
};
