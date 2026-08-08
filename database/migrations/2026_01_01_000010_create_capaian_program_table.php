<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('capaian_program', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->string('program');
            $table->string('kegiatan')->nullable();
            $table->decimal('pagu', 15, 2)->default(0);
            $table->decimal('realisasi', 15, 2)->default(0);
            $table->decimal('persentase', 8, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('capaian_program');
    }
};
