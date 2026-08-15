<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('folder_dokumen', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('deskripsi')->constrained('folder_dokumen')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('folder_dokumen', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_id');
        });
    }
};
