<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arsip_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidang_id')->constrained('bidang')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('nomor_surat', 100);
            $table->date('tanggal_surat');
            $table->string('perihal', 255);
            $table->enum('jenis_surat', ['masuk', 'keluar', 'internal'])->default('masuk');
            $table->string('file_path', 255);
            $table->string('file_name', 255);
            $table->unsignedBigInteger('file_size')->nullable();
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamp('uploaded_at')->useCurrent();
            $table->text('keterangan')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            $table->index(['bidang_id', 'is_deleted']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsip_surat');
    }
};
