<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // kolom status dan urutan sudah ada di migration create_dokumen_akip dan create_dokumen_iki
    }

    public function down(): void
    {
        // nothing to revert
    }
};
