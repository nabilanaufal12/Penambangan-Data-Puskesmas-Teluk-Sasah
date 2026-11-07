<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinical_correlations', function (Blueprint $table) {
            $table->id();
            $table->string('kode_icd', 10)->index();
            $table->string('nama_penyakit');
            $table->string('nama_obat_standar');
            $table->string('nama_obat_lplpo')->nullable();
            $table->string('indikasi_klinis')->nullable();
            $table->double('pearson_r')->nullable();
            $table->double('spearman_rho')->nullable();
            $table->double('rasio_dosis_per_pasien')->default(0);
            $table->double('prediksi_kasus_P5')->default(0);
            $table->double('prediksi_kebutuhan_obat_P5')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinical_correlations');
    }
};
