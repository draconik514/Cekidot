<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['slider', 'users', 'dokumen_akip', 'dokumen_iki', 'iku_penilaian'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && ! Schema::hasColumn($table, 'updated_at')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->timestamp('updated_at')->nullable();
                });
            }
        }

        if (Schema::hasTable('surat_masuk')) {
            if (! Schema::hasColumn('surat_masuk', 'created_at')) {
                Schema::table('surat_masuk', function (Blueprint $table) {
                    $table->timestamp('created_at')->nullable();
                });
            }
            if (! Schema::hasColumn('surat_masuk', 'updated_at')) {
                Schema::table('surat_masuk', function (Blueprint $table) {
                    $table->timestamp('updated_at')->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        $tables = ['slider', 'users', 'dokumen_akip', 'dokumen_iki', 'iku_penilaian'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'updated_at')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumn('updated_at');
                });
            }
        }

        if (Schema::hasTable('surat_masuk')) {
            foreach (['created_at', 'updated_at'] as $column) {
                if (Schema::hasColumn('surat_masuk', $column)) {
                    Schema::table('surat_masuk', function (Blueprint $table) use ($column) {
                        $table->dropColumn($column);
                    });
                }
            }
        }
    }
};
