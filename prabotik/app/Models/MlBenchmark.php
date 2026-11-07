<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MlBenchmark extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'wape' => 'float',
        'stabilitas_sd' => 'float',
    ];
}
