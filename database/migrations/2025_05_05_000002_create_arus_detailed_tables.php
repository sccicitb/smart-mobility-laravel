<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $directions = ['barat', 'timur', 'utara', 'selatan'];

        foreach ($directions as $dir) {
            Schema::create("arus_lalu_lintas_{$dir}_detailed", function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ID_Simpang')->nullable();
                $table->string('Tipe_Pendekat')->nullable();
                $table->string('Arah')->nullable();
                $table->integer('SM')->default(0);
                $table->integer('MP')->default(0);
                $table->integer('AUP')->default(0);
                $table->integer('TR')->default(0);
                $table->integer('BS')->default(0);
                $table->integer('TS')->default(0);
                $table->integer('TB')->default(0);
                $table->integer('BB')->default(0);
                $table->integer('Gandeng')->default(0);
                $table->integer('KTB')->default(0);
                $table->timestamp('Waktu')->nullable();
                $table->timestamps();

                $table->foreign('ID_Simpang')->references('id')->on('simpang')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        foreach (['barat', 'timur', 'utara', 'selatan'] as $dir) {
            Schema::dropIfExists("arus_lalu_lintas_{$dir}_detailed");
        }
    }
};
