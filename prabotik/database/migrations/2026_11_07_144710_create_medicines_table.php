<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('nama_obat')->index();
            $table->string('satuan')->default('tablet');
            $table->string('model_terbaik')->nullable();
            $table->double('mape_terbaik')->nullable();
            $table->double('mae')->nullable();
            $table->double('rmse')->nullable();
            $table->double('prediksi_P5')->default(0);
            $table->double('safety_stock')->default(0);
            $table->double('sisa_stok')->default(0);
            $table->double('rekomendasi_total')->default(0);
            $table->double('macd_value')->nullable();
            $table->double('signal_line')->nullable();
            $table->double('histogram')->nullable();
            $table->string('momentum_status')->nullable();
            $table->string('prioritas_kategori')->default('Prioritas 2 (Esensial / Stabil)');
            $table->boolean('is_censored_adjusted')->default(false);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
