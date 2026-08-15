<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('folder_dokumen', function (Blueprint $table) {
            $table->foreignId('bidang_id')->nullable()->after('divisi')->constrained('bidang')->cascadeOnUpdate()->nullOnDelete();
        });

        Schema::table('dokumen_iki', function (Blueprint $table) {
            $table->foreignId('bidang_id')->nullable()->after('tahun')->constrained('bidang')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('folder_dokumen', function (Blueprint $table) {
            $table->dropConstrainedForeignId('bidang_id');
        });

        Schema::table('dokumen_iki', function (Blueprint $table) {
            $table->dropConstrainedForeignId('bidang_id');
        });
    }
};
