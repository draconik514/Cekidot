<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('iku_penilaian', function (Blueprint $table) {
            $table->decimal('bobot', 15, 4)->default(0)->after('nilai');
            $table->decimal('target', 15, 4)->default(0)->after('bobot');
            $table->decimal('realisasi', 15, 4)->default(0)->after('target');
        });
    }

    public function down(): void
    {
        Schema::table('iku_penilaian', function (Blueprint $table) {
            $table->dropColumn(['bobot', 'target', 'realisasi']);
        });
    }
};
