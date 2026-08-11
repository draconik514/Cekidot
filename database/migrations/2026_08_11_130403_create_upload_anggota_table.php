<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upload_anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('folder_id')->constrained('folder_dokumen');
            $table->string('judul');
            $table->string('file_name');
            $table->string('file_type')->nullable();
            $table->bigInteger('file_size')->default(0);
            $table->text('keterangan')->nullable();
            $table->integer('tahun');
            $table->tinyInteger('bulan');
            $table->date('tanggal_upload');
            $table->string('status')->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upload_anggota');
    }
};
