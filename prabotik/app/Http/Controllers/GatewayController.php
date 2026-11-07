<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\DiseaseRecord;
use App\Models\ExpiredMedicine;

class GatewayController extends Controller
{
    public function index()
    {
        $stats = [
            'total_obat' => Medicine::count(),
            'total_pasien' => DiseaseRecord::sum('total_pasien'),
            'total_kerugian_ed' => ExpiredMedicine::sum('sub_total_kerugian'),
            'total_usulan_unit' => Medicine::sum('rekomendasi_total'),
            'is_approved' => session('p5_procurement_approved', false),
        ];

        return view('gateway', compact('stats'));
    }
}
