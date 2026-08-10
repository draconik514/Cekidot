<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iku_pdrb', function (Blueprint $table) {
            $table->id();
            $table->string('kategori');
            $table->year('tahun');
            $table->decimal('target', 20, 6)->default(0);
            $table->decimal('realitas', 20, 6)->default(0);
            $table->decimal('capaian', 10, 4)->default(0);
            $table->timestamps();
            
            $table->index(['kategori', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iku_pdrb');
    }
};
