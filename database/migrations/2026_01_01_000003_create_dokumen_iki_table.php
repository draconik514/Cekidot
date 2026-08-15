<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_iki', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('file_dokumen')->nullable();
            $table->string('tipe_konten')->default('file');
            $table->string('link_url')->nullable();
            $table->string('file_type')->nullable();
            $table->bigInteger('file_size')->default(0);
            $table->integer('tahun');
            $table->string('divisi')->nullable();
            $table->integer('urutan')->default(0);
            $table->string('status')->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_iki');
    }
};
