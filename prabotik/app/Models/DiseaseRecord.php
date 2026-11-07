<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiseaseRecord extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'tahun' => 'integer',
        'bulan_angka' => 'integer',
        'pasien_laki' => 'float',
        'pasien_perempuan' => 'float',
        'total_pasien' => 'float',
    ];
}
