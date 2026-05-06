<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ID_Simpang')->nullable();
            $table->string('tipe_pendekat')->nullable();
            $table->string('dari_arah')->nullable();
            $table->string('ke_arah')->nullable();
            $table->integer('SM')->default(0);
            $table->integer('MP')->default(0);
            $table->integer('AUP')->default(0);
            $table->integer('TR')->default(0);
            $table->integer('BS')->default(0);
            $table->integer('TS')->default(0);
            $table->integer('TB')->default(0);
            $table->integer('BB')->default(0);
            $table->integer('GANDENG')->default(0);
            $table->integer('KTB')->default(0);
            $table->timestamp('waktu')->nullable();
            $table->timestamps();

            $table->foreign('ID_Simpang')->references('id')->on('simpang')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arus');
    }
};
