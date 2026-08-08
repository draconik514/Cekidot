<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop dan recreate iku_penilaian
        Schema::dropIfExists('iku_penilaian');
        Schema::create('iku_penilaian', function (Blueprint $table) {
            $table->id();
            $table->string('kategori');
            $table->integer('tahun');
            $table->string('nama_kriteria');
            $table->decimal('nilai', 20, 4)->default(0);
            $table->string('link_sumber')->nullable();
            $table->string('file_sumber')->nullable();
            $table->timestamps();
        });

        // Drop dan recreate iku_wisatawan
        Schema::dropIfExists('iku_wisatawan');
        Schema::create('iku_wisatawan', function (Blueprint $table) {
            $table->id();
            $table->string('kategori')->default('Wisatawan');
            $table->string('subkategori');
            $table->integer('tahun');
            $table->string('kabkota');
            $table->decimal('januari', 15, 2)->default(0);
            $table->decimal('februari', 15, 2)->default(0);
            $table->decimal('maret', 15, 2)->default(0);
            $table->decimal('april', 15, 2)->default(0);
            $table->decimal('mei', 15, 2)->default(0);
            $table->decimal('juni', 15, 2)->default(0);
            $table->decimal('juli', 15, 2)->default(0);
            $table->decimal('agustus', 15, 2)->default(0);
            $table->decimal('september', 15, 2)->default(0);
            $table->decimal('oktober', 15, 2)->default(0);
            $table->decimal('november', 15, 2)->default(0);
            $table->decimal('desember', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->timestamps();
        });

        // Drop dan recreate iku_ekraf
        Schema::dropIfExists('iku_ekraf');
        Schema::create('iku_ekraf', function (Blueprint $table) {
            $table->id();
            $table->string('kategori')->default('Ekraf');
            $table->integer('tahun');
            $table->string('sektor');
            $table->decimal('koofisien', 15, 4)->default(0);
            $table->decimal('nilai_bps', 20, 4)->default(0);
            $table->decimal('jumlah_rp', 20, 4)->default(0);
            $table->decimal('hasil_penjumlahan', 20, 4)->default(0);
            $table->timestamps();
        });

        // Drop dan recreate iku_pdrb
        Schema::dropIfExists('iku_pdrb');
        Schema::create('iku_pdrb', function (Blueprint $table) {
            $table->id();
            $table->string('kategori');
            $table->integer('tahun');
            $table->decimal('target', 15, 4)->default(0);
            $table->decimal('realitas', 15, 4)->default(0);
            $table->decimal('capaian', 10, 4)->default(0);
            $table->timestamps();
        });

        // Drop dan recreate iku_infografis
        Schema::dropIfExists('iku_infografis');
        Schema::create('iku_infografis', function (Blueprint $table) {
            $table->id();
            $table->string('kategori');
            $table->string('file_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iku_penilaian');
        Schema::dropIfExists('iku_wisatawan');
        Schema::dropIfExists('iku_ekraf');
        Schema::dropIfExists('iku_pdrb');
        Schema::dropIfExists('iku_infografis');
    }
};
