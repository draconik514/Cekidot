<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iku_wisatawan', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->string('jenis');
            $table->bigInteger('jumlah')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iku_wisatawan');
    }
};
