<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('folder_dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('deskripsi')->nullable();
            $table->enum('divisi', ['Kepegawaian', 'Program', 'Keuangan', 'Ekraf', 'Destinasi', 'Pemasaran', 'Sdm', 'Semua'])->default('Semua');
            $table->string('status')->default('aktif');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('folder_dokumen');
    }
};
