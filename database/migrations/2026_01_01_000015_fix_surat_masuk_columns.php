<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->string('asal_instansi')->nullable()->after('instansi');
            $table->timestamp('tanggal_masuk')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->dropColumn(['asal_instansi', 'tanggal_masuk']);
        });
    }
};
