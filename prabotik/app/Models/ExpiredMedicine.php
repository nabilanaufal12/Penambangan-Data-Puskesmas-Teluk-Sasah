<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpiredMedicine extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'tahun' => 'integer',
        'jumlah_kadaluarsa' => 'float',
        'harga_satuan' => 'float',
        'sub_total_kerugian' => 'float',
    ];
}
