<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengirim');
            $table->string('email');
            $table->string('no_hp')->nullable();
            $table->string('instansi')->nullable();
            $table->string('perihal');
            $table->text('isi');
            $table->string('file')->nullable();
            $table->string('status')->default('belum_dibaca');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_masuk');
    }
};
