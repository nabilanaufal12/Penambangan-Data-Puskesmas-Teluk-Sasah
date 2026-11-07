<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disease_records', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun')->index();
            $table->integer('bulan_angka');
            $table->string('bulan');
            $table->string('periode_bimonthly_id')->index();
            $table->string('kode_icd', 10)->index();
            $table->string('nama_penyakit');
            $table->string('klaster_icd')->nullable();
            $table->double('pasien_laki')->default(0);
            $table->double('pasien_perempuan')->default(0);
            $table->double('total_pasien')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disease_records');
    }
};
