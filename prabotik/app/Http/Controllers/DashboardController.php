<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\DiseaseRecord;
use App\Models\ClinicalCorrelation;
use App\Models\MlBenchmark;
use App\Models\ExpiredMedicine;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_transaksi' => '15.781',
            'total_pasien' => '31.125',
            'total_periode' => '22',
            'total_obat_aktif' => Medicine::count(),
            'total_kerugian_ed' => 'Rp 43,35 Jt',
            'obat_vital_count' => Medicine::where('prioritas_kategori', 'like', '%Vital%')->count(),
            'bullish_count' => Medicine::where('momentum_status', 'like', '%Bullish%')->orWhere('momentum_status', 'like', '%Rebound%')->count(),
        ];

        // Top Priority Medicines for Procurement
        $vitalMedicines = Medicine::where('prioritas_kategori', 'like', '%Vital%')
            ->orderByRaw("CASE WHEN nama_obat LIKE '%Parasetamol%' THEN 1 WHEN nama_obat LIKE '%Metformin%' THEN 2 WHEN nama_obat LIKE '%Amlodipin 10%' THEN 3 ELSE 4 END")
            ->take(8)
            ->get();

        // Top 4 Diseases (ISPA, Hipertensi, Dispepsia, Diabetes)
        $topDiseases = [
            ['kode' => 'J00', 'nama' => 'ISPA / Acute Nasopharyngitis', 'model' => 'WMA (k=3)', 'mape' => '45.80%', 'prediksi' => 428.5, 'color' => '#EF4444'],
            ['kode' => 'I10', 'nama' => 'Hipertensi Primer (Esensial)', 'model' => 'SMA (k=3)', 'mape' => '36.00%', 'prediksi' => 383.3, 'color' => '#3B82F6'],
            ['kode' => 'K30', 'nama' => 'Dispepsia (Asam Lambung)', 'model' => 'SMA (k=3)', 'mape' => '36.88%', 'prediksi' => 165.7, 'color' => '#10B981'],
            ['kode' => 'E11', 'nama' => 'Diabetes Melitus Tipe 2', 'model' => 'WMA (k=3)', 'mape' => '40.28%', 'prediksi' => 87.5, 'color' => '#F59E0B'],
        ];

        // ML Benchmarking Data
        $mlBenchmarks = MlBenchmark::all();

        // Clinical Correlations Top
        $correlations = ClinicalCorrelation::take(6)->get();

        // Expired summary
        $expiredTotal = ExpiredMedicine::sum('sub_total_kerugian');
        $expiredCount = ExpiredMedicine::count();

        return view('dashboard.index', compact('stats', 'vitalMedicines', 'topDiseases', 'mlBenchmarks', 'correlations', 'expiredTotal', 'expiredCount'));
    }

    public function medicines(Request $request)
    {
        $query = Medicine::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('nama_obat', 'like', "%{$q}%");
        }

        if ($request->filled('prioritas')) {
            $query->where('prioritas_kategori', 'like', "%{$request->prioritas}%");
        }

        if ($request->filled('momentum')) {
            $query->where('momentum_status', 'like', "%{$request->momentum}%");
        }

        $medicines = $query->orderBy('rekomendasi_total', 'desc')->paginate(20)->withQueryString();
        $totalCount = Medicine::count();

        return view('dashboard.medicines', compact('medicines', 'totalCount'));
    }

    public function procurement(Request $request)
    {
        $query = Medicine::query();

        if ($request->filled('prioritas')) {
            $query->where('prioritas_kategori', 'like', "%{$request->prioritas}%");
        }

        if ($request->filled('q')) {
            $query->where('nama_obat', 'like', "%{$request->q}%");
        }

        $medicines = $query->orderByRaw("
            CASE 
                WHEN prioritas_kategori LIKE '%Prioritas 1%' THEN 1 
                WHEN prioritas_kategori LIKE '%Prioritas 2%' THEN 2 
                ELSE 3 
            END, rekomendasi_total DESC
        ")->paginate(25)->withQueryString();

        $paracetamol = Medicine::where('nama_obat', 'like', '%Parasetamol 500%')->first();

        return view('dashboard.procurement', compact('medicines', 'paracetamol'));
    }

    public function diseases()
    {
        $topCodes = ['J00', 'I10', 'K30', 'E11'];
        
        $diseaseSeries = [];
        foreach ($topCodes as $code) {
            $records = DiseaseRecord::where('kode_icd', $code)
                ->orderBy('tahun')
                ->orderBy('bulan_angka')
                ->get(['tahun', 'bulan', 'periode_bimonthly_id', 'total_pasien']);
            
            $diseaseSeries[$code] = $records;
        }

        $correlations = ClinicalCorrelation::all();

        return view('dashboard.diseases', compact('diseaseSeries', 'correlations'));
    }

    public function macd()
    {
        $medicines = Medicine::whereNotNull('macd_value')
            ->orderBy('histogram', 'desc')
            ->get();

        $bullishCount = Medicine::where('histogram', '>=', 0)->count();
        $bearishCount = Medicine::where('histogram', '<', 0)->count();

        return view('dashboard.macd', compact('medicines', 'bullishCount', 'bearishCount'));
    }

    public function correlation()
    {
        $correlations = ClinicalCorrelation::all();
        return view('dashboard.correlation', compact('correlations'));
    }

    public function mlBenchmark()
    {
        $benchmarks = MlBenchmark::all();
        return view('dashboard.ml_benchmark', compact('benchmarks'));
    }

    public function stockAudit()
    {
        $expiredItems = ExpiredMedicine::orderBy('sub_total_kerugian', 'desc')->paginate(30);
        $totalLoss = ExpiredMedicine::sum('sub_total_kerugian');
        $totalItems = ExpiredMedicine::count();

        return view('dashboard.stock_audit', compact('expiredItems', 'totalLoss', 'totalItems'));
    }

    public function about()
    {
        return view('dashboard.about');
    }

    public function printLplpo(Request $request)
    {
        $query = Medicine::query();
        
        // Default show priority 1 & 2 medicines for official LPLPO request sheet, or all
        if ($request->get('scope') === 'all') {
            $medicines = $query->orderBy('prioritas_kategori')->orderBy('rekomendasi_total', 'desc')->get();
        } else {
            $medicines = $query->where('prioritas_kategori', 'like', '%Prioritas 1%')
                ->orWhere('prioritas_kategori', 'like', '%Prioritas 2%')
                ->orderByRaw("CASE WHEN prioritas_kategori LIKE '%Prioritas 1%' THEN 1 ELSE 2 END, rekomendasi_total DESC")
                ->get();
        }

        $paracetamol = Medicine::where('nama_obat', 'like', '%Parasetamol 500%')->first();
        $totalAlokasi = $medicines->sum('rekomendasi_total');

        return view('dashboard.print_lplpo', compact('medicines', 'paracetamol', 'totalAlokasi'));
    }

    public function inputLplpo()
    {
        $medicines = Medicine::orderBy('nama_obat')->get(['id', 'nama_obat', 'satuan', 'sisa_stok', 'prediksi_P5', 'safety_stock', 'rekomendasi_total']);
        return view('dashboard.input_lplpo', compact('medicines'));
    }

    public function storeLplpo(Request $request)
    {
        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'pemakaian_terbaru' => 'required|numeric|min:0',
            'sisa_stok_terbaru' => 'required|numeric|min:0',
            'keterangan_tambahan' => 'nullable|string',
        ]);

        $med = Medicine::findOrFail($validated['medicine_id']);
        
        // Recalculate based on WMA / moving average
        $stokAkhir = (float) $validated['sisa_stok_terbaru'];
        $pemakaian = (float) $validated['pemakaian_terbaru'];
        
        // Update stock & re-evaluate order recommendation
        $newPred = round(($med->prediksi_P5 * 0.5) + ($pemakaian * 0.5));
        $safetyStock = $med->safety_stock;
        $order = max(0, ($newPred + $safetyStock) - $stokAkhir);

        $med->update([
            'sisa_stok' => $stokAkhir,
            'prediksi_P5' => $newPred,
            'rekomendasi_total' => $order,
            'keterangan' => $validated['keterangan_tambahan'] ? $validated['keterangan_tambahan'] . ' (Input Mandiri Petugas Farmasi)' : $med->keterangan,
        ]);

        return redirect()->route('lplpo.input')->with('success', "Stok obat '{$med->nama_obat}' berhasil diperbarui! Rekomendasi order baru: " . number_format($order, 0, ',', '.') . " {$med->satuan}.");
    }
}
