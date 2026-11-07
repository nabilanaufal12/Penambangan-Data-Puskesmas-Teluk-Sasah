<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'mape_terbaik' => 'float',
        'mae' => 'float',
        'rmse' => 'float',
        'prediksi_P5' => 'float',
        'safety_stock' => 'float',
        'sisa_stok' => 'float',
        'rekomendasi_total' => 'float',
        'macd_value' => 'float',
        'signal_line' => 'float',
        'histogram' => 'float',
        'is_censored_adjusted' => 'boolean',
    ];
}
