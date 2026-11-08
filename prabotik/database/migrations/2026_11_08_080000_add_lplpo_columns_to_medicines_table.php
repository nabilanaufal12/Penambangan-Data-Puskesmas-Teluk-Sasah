<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->double('stok_awal')->default(0)->after('satuan');
            $table->double('penerimaan')->default(0)->after('stok_awal');
            $table->double('persediaan')->default(0)->after('penerimaan');
            $table->double('pemakaian')->default(0)->after('persediaan');
            $table->boolean('pernah_kosong')->default(false)->after('is_censored_adjusted');
            $table->double('evaluasi_akurasi')->nullable()->after('rmse');
            $table->double('deviasi_evaluasi')->nullable()->after('evaluasi_akurasi');
            $table->string('evaluasi_catatan')->nullable()->after('deviasi_evaluasi');
        });
    }

    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumn([
                'stok_awal',
                'penerimaan',
                'persediaan',
                'pemakaian',
                'pernah_kosong',
                'evaluasi_akurasi',
                'deviasi_evaluasi',
                'evaluasi_catatan',
            ]);
        });
    }
};
