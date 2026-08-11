<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'admin_divisi', 'anggota'])->default('anggota')->after('email');
            $table->enum('divisi', ['Kepegawaian', 'Program', 'Keuangan', 'Ekraf', 'Destinasi', 'Pemasaran', 'Sdm'])->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'divisi']);
        });
    }
};
