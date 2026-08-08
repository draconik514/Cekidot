<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::drop('capaian_program');

        Schema::create('capaian_program', function (Blueprint $table) {
            $table->id();
            $table->string('program')->nullable();
            $table->string('sasaran')->nullable();
            $table->text('indikator')->nullable();
            $table->decimal('target', 15, 4)->default(0);
            $table->decimal('realisasi', 15, 4)->default(0);
            $table->decimal('capaian', 15, 4)->default(0);
            $table->string('frekwensi')->nullable();
            $table->string('sumber_data')->nullable();
            $table->string('file_sumber')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->string('tahun', 10)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::drop('capaian_program');
    }
};
