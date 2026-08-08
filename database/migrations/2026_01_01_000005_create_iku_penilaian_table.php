<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iku_penilaian', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->string('indikator');
            $table->decimal('target', 10, 2)->nullable();
            $table->decimal('realisasi', 10, 2)->nullable();
            $table->decimal('capaian', 10, 2)->nullable();
            $table->string('satuan')->nullable();
            $table->string('predikat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iku_penilaian');
    }
};
