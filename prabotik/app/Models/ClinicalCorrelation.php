<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicalCorrelation extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'pearson_r' => 'float',
        'spearman_rho' => 'float',
        'rasio_dosis_per_pasien' => 'float',
        'prediksi_kasus_P5' => 'float',
        'prediksi_kebutuhan_obat_P5' => 'float',
    ];
}
