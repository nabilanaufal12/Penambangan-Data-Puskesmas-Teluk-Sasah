<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expired_medicines', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun')->index();
            $table->string('nama_obat');
            $table->string('satuan')->default('tablet');
            $table->double('jumlah_kadaluarsa')->default(0);
            $table->double('harga_satuan')->default(0);
            $table->double('sub_total_kerugian')->default(0);
            $table->string('sumber_anggaran')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expired_medicines');
    }
};
