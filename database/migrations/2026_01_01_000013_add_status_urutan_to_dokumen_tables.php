<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokumen_akip', function (Blueprint $table) {
            $table->string('status')->default('aktif')->after('file');
            $table->integer('urutan')->default(0)->after('status');
        });

        Schema::table('dokumen_iki', function (Blueprint $table) {
            $table->string('status')->default('aktif')->after('file');
            $table->integer('urutan')->default(0)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('dokumen_akip', function (Blueprint $table) {
            $table->dropColumn(['status', 'urutan']);
        });
        Schema::table('dokumen_iki', function (Blueprint $table) {
            $table->dropColumn(['status', 'urutan']);
        });
    }
};
