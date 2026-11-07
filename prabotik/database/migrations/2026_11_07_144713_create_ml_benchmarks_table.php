<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ml_benchmarks', function (Blueprint $table) {
            $table->id();
            $table->string('model_name');
            $table->string('kategori');
            $table->double('wape');
            $table->string('bias_percentage');
            $table->double('stabilitas_sd')->nullable();
            $table->string('interpretasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ml_benchmarks');
    }
};
