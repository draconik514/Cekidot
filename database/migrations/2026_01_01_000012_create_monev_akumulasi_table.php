<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monev_akumulasi', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->string('program');
            $table->decimal('target_akhir', 8, 2)->default(0);
            $table->decimal('realisasi_akhir', 8, 2)->default(0);
            $table->decimal('persentase', 8, 2)->default(0);
            $table->string('predikat')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monev_akumulasi');
    }
};
