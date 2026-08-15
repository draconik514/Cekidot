<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('bidang_id')->nullable()->after('divisi')->constrained('bidang')->cascadeOnUpdate()->nullOnDelete();
            $table->boolean('is_active')->default(true)->after('bidang_id');
            $table->enum('role', ['super_admin', 'admin_divisi', 'admin_bidang', 'anggota'])->default('anggota')->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'admin_divisi', 'anggota'])->default('anggota')->change();
            $table->dropConstrainedForeignId('bidang_id');
            $table->dropColumn('is_active');
        });
    }
};
