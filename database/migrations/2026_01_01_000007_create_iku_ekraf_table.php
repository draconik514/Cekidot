<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iku_ekraf', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->string('subsektor');
            $table->decimal('nilai', 15, 2)->default(0);
            $table->string('satuan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iku_ekraf');
    }
};
