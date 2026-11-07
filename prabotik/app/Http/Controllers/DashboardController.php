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
    public function index(Request $request)
    {
        return $this->pharmacyDashboard($request);
    }

    public function pharmacyDashboard(Request $request)
    {
        $stats = [
            'total_obat_aktif' => Medicine::count(),
            'stok_kosong_count' => Medicine::where('sisa_stok', 0)->count(),
            'stok_kritis_count' => Medicine::where('sisa_stok', '>', 0)->whereColumn('sisa_stok', '<=', 'safety_stock')->count(),
            'stok_aman_count' => Medicine::whereColumn('sisa_stok', '>', 'safety_stock')->count(),
            'total_rekomendasi_unit' => Medicine::sum('rekomendasi_total'),
            'bullish_count' => Medicine::where('momentum_status', 'like', '%Bullish%')->orWhere('momentum_status', 'like', '%Rebound%')->count(),
            'total_ed_items' => ExpiredMedicine::count(),
            'total_ed_loss' => ExpiredMedicine::sum('sub_total_kerugian'),
            'total_transaksi' => '15.781',
            'total_periode' => '22 Periode',
        ];

        // Critical Medicines (Stok = 0 atau Stok <= Safety Stock)
        $criticalMedicines = Medicine::where('sisa_stok', 0)
            ->orWhereColumn('sisa_stok', '<=', 'safety_stock')
            ->orderByDesc('rekomendasi_total')
            ->take(8)
            ->get();

        // Top Procurement Order Items P5 (2026)
        $procurementPreview = Medicine::orderByDesc('rekomendasi_total')
            ->take(8)
            ->get();

        // Momentum MACD summary
        $bullishMedicines = Medicine::where('momentum_status', 'like', '%Bullish%')
            ->orWhere('momentum_status', 'like', '%Rebound%')
            ->orderByDesc('rekomendasi_total')
            ->take(6)
            ->get();

        // Recent Expired Medicines
        $recentExpired = ExpiredMedicine::orderByDesc('tahun')
            ->orderByDesc('id')
            ->take(6)
            ->get();

        return view('dashboard.pharmacy', compact(
            'stats', 'criticalMedicines', 'procurementPreview', 'bullishMedicines', 'recentExpired'
        ));
    }

    public function executiveDashboard(Request $request)
    {
        $totalObat = Medicine::count();
        $obatTersedia = Medicine::where('sisa_stok', '>', 0)->count();
        $ketersediaanPct = round(($obatTersedia / max(1, $totalObat)) * 100, 1);

        $totalPasien = DiseaseRecord::sum('total_pasien');
        $totalEdLoss = ExpiredMedicine::sum('sub_total_kerugian');
        $totalOrderUnit = Medicine::sum('rekomendasi_total');
        
        // Estimasi nilai pengadaan P5 (rata-rata harga Rp 1.350 per unit obat)
        $estimasiAnggaran = $totalOrderUnit * 1350;

        $kpis = [
            'ketersediaan_pct' => $ketersediaanPct,
            'obat_tersedia' => $obatTersedia,
            'total_obat' => $totalObat,
            'total_pasien' => $totalPasien,
            'total_ed_loss' => $totalEdLoss,
            'total_order_unit' => $totalOrderUnit,
            'estimasi_anggaran' => $estimasiAnggaran,
            'rasio_peresepan' => '94.6%',
            'periode_aktif' => '2026-P5 (Sep-Okt)',
            'status_disposisi' => 'Menunggu Pengesahan Akhir',
            'kapus_nama' => 'dr. Bambang Utoyo, M.M.',
            'kapus_nip' => 'NIP. 19780512 200604 1 008',
            'pengelola_nama' => 'Apt. Siti Rahmawati, S.Farm',
            'pengelola_sip' => 'SIP. 19890415/SIPA/2018/021',
        ];

        // Top 5 Diseases SIMPUS with percentage
        $topDiseases = DiseaseRecord::select('kode_icd', 'nama_penyakit', DB::raw('sum(total_pasien) as total_pasien'))
            ->groupBy('kode_icd', 'nama_penyakit')
            ->orderByDesc('total_pasien')
            ->take(5)
            ->get()
            ->map(function ($item) use ($totalPasien) {
                $item->persentase = round(($item->total_pasien / max(1, $totalPasien)) * 100, 1);
                return $item;
            });

        // Breakdown ED Loss by Year
        $edLossByYear = ExpiredMedicine::select('tahun', DB::raw('sum(sub_total_kerugian) as total_loss'), DB::raw('count(*) as count'))
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get();

        // Top 6 High-Value Procurement Items for Executive Approval
        $topProcurement = Medicine::orderByDesc('rekomendasi_total')
            ->take(6)
            ->get();

        // Clinical Correlations Top
        $correlations = ClinicalCorrelation::take(5)->get();

        return view('dashboard.executive', compact(
            'kpis', 'topDiseases', 'edLossByYear', 'topProcurement', 'correlations'
        ));
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

    public function inputLplpo(Request $request)
    {
        return $this->dataEntry($request);
    }

    public function dataEntry(Request $request)
    {
        $tab = $request->get('tab', 'lplpo');

        $medicines = Medicine::orderBy('nama_obat')->get();
        $recentDiseases = DiseaseRecord::orderBy('tahun', 'desc')
            ->orderBy('bulan_angka', 'desc')
            ->take(15)
            ->get();
        
        $recentExpired = ExpiredMedicine::orderBy('tahun', 'desc')
            ->orderBy('id', 'desc')
            ->take(15)
            ->get();

        $totalExpiredLoss = ExpiredMedicine::sum('sub_total_kerugian');
        $totalExpiredItems = ExpiredMedicine::count();
        $totalDiseaseRecords = DiseaseRecord::count();

        // Common ICD-10 list for quick select in Puskesmas
        $commonDiseases = [
            ['kode' => 'J00', 'nama' => 'Acute nasopharyngitis [common cold] (ISPA)', 'klaster' => 'Penyakit Sistem Pernapasan'],
            ['kode' => 'I10', 'nama' => 'Essential (primary) hypertension (Hipertensi)', 'klaster' => 'Penyakit Sistem Sirkulasi'],
            ['kode' => 'K30', 'nama' => 'Dyspepsia (Gangguan Asam Lambung / Maag)', 'klaster' => 'Penyakit Sistem Pencernaan'],
            ['kode' => 'E11', 'nama' => 'Non-insulin-dependent diabetes mellitus (Diabetes Tipe 2)', 'klaster' => 'Penyakit Endokrin, Nutrisi & Metabolik'],
            ['kode' => 'M79.1', 'nama' => 'Myalgia (Nyeri Otot)', 'klaster' => 'Penyakit Sistem Muskuloskeletal'],
            ['kode' => 'J02', 'nama' => 'Acute pharyngitis (Radang Tenggorokan)', 'klaster' => 'Penyakit Sistem Pernapasan'],
            ['kode' => 'R50.9', 'nama' => 'Fever, unspecified (Demam)', 'klaster' => 'Gejala, Tanda & Kelainan Klinis'],
            ['kode' => 'A09', 'nama' => 'Infectious gastroenteritis and colitis (Diare Akut)', 'klaster' => 'Penyakit Infeksi & Parasit'],
            ['kode' => 'L20', 'nama' => 'Atopic dermatitis (Alergi / Gatal Kulit)', 'klaster' => 'Penyakit Kulit & Jaringan Subkutan'],
            ['kode' => 'K02', 'nama' => 'Dental caries (Gigi Berlubang)', 'klaster' => 'Penyakit Rongga Mulut & Gigi'],
        ];

        return view('dashboard.data_entry', compact(
            'tab', 'medicines', 'recentDiseases', 'recentExpired', 
            'totalExpiredLoss', 'totalExpiredItems', 'totalDiseaseRecords', 'commonDiseases'
        ));
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
        
        $stokAkhir = (float) $validated['sisa_stok_terbaru'];
        $pemakaian = (float) $validated['pemakaian_terbaru'];
        
        $newPred = round(($med->prediksi_P5 * 0.5) + ($pemakaian * 0.5));
        $safetyStock = $med->safety_stock;
        $order = max(0, ($newPred + $safetyStock) - $stokAkhir);

        $med->update([
            'sisa_stok' => $stokAkhir,
            'prediksi_P5' => $newPred,
            'rekomendasi_total' => $order,
            'keterangan' => $validated['keterangan_tambahan'] ? $validated['keterangan_tambahan'] . ' (Input Mandiri Petugas Farmasi)' : $med->keterangan,
        ]);

        return redirect()->route('data.entry', ['tab' => 'lplpo'])->with('success', "Stok obat '{$med->nama_obat}' berhasil diperbarui! Rekomendasi order baru: " . number_format($order, 0, ',', '.') . " {$med->satuan}.");
    }

    public function storeDisease(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|integer|min:2020|max:2030',
            'bulan_angka' => 'required|integer|min:1|max:12',
            'kode_icd' => 'required|string|max:10',
            'nama_penyakit' => 'required|string|max:255',
            'klaster_icd' => 'nullable|string',
            'pasien_laki' => 'required|numeric|min:0',
            'pasien_perempuan' => 'required|numeric|min:0',
        ]);

        $bulanNamaMap = [
            1 => 'JANUARI', 2 => 'FEBRUARI', 3 => 'MARET', 4 => 'APRIL',
            5 => 'MEI', 6 => 'JUNI', 7 => 'JULI', 8 => 'AGUSTUS',
            9 => 'SEPTEMBER', 10 => 'OKTOBER', 11 => 'NOVEMBER', 12 => 'DESEMBER'
        ];

        $tahun = (int) $validated['tahun'];
        $bulanAngka = (int) $validated['bulan_angka'];
        $bulanNama = $bulanNamaMap[$bulanAngka] ?? 'BULAN';
        $pNum = (int) ceil($bulanAngka / 2);
        $periodeId = "{$tahun}-P{$pNum}";
        
        $laki = (float) $validated['pasien_laki'];
        $perempuan = (float) $validated['pasien_perempuan'];
        $total = $laki + $perempuan;

        DiseaseRecord::create([
            'tahun' => $tahun,
            'bulan_angka' => $bulanAngka,
            'bulan' => $bulanNama,
            'periode_bimonthly_id' => $periodeId,
            'kode_icd' => strtoupper(trim($validated['kode_icd'])),
            'nama_penyakit' => trim($validated['nama_penyakit']),
            'klaster_icd' => $validated['klaster_icd'] ?? 'Penyakit Umum',
            'pasien_laki' => $laki,
            'pasien_perempuan' => $perempuan,
            'total_pasien' => $total,
        ]);

        return redirect()->route('data.entry', ['tab' => 'penyakit'])->with('success', "Rekam morbiditas '{$validated['kode_icd']} - {$validated['nama_penyakit']}' ({$total} pasien, {$bulanNama} {$tahun}) berhasil ditambahkan ke SIMPUS!");
    }

    public function storeExpired(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|integer|min:2020|max:2030',
            'nama_obat' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'jumlah_kadaluarsa' => 'required|numeric|min:0.01',
            'harga_satuan' => 'required|numeric|min:0',
            'sumber_anggaran' => 'nullable|string',
        ]);

        $jumlah = (float) $validated['jumlah_kadaluarsa'];
        $harga = (float) $validated['harga_satuan'];
        $subtotal = $jumlah * $harga;

        ExpiredMedicine::create([
            'tahun' => (int) $validated['tahun'],
            'nama_obat' => strtoupper(trim($validated['nama_obat'])),
            'satuan' => trim($validated['satuan']),
            'harga_satuan' => $harga,
            'jumlah_kadaluarsa' => $jumlah,
            'sub_total_kerugian' => $subtotal,
            'sumber_anggaran' => $validated['sumber_anggaran'] ?? 'APBD / DAK Farmasi Bintan',
        ]);

        return redirect()->route('data.entry', ['tab' => 'kadaluarsa'])->with('success', "Berita Acara obat kadaluarsa '{$validated['nama_obat']}' ({$jumlah} {$validated['satuan']} senilai Rp " . number_format($subtotal, 0, ',', '.') . ") berhasil dicatat dalam audit fisik!");
    }

    public function storeNewMedicine(Request $request)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:255|unique:medicines,nama_obat',
            'satuan' => 'required|string|max:50',
            'prioritas_kategori' => 'required|string',
            'stok_awal' => 'required|numeric|min:0',
            'estimasi_kebutuhan' => 'required|numeric|min:0',
        ]);

        $est = (float) $validated['estimasi_kebutuhan'];
        $stok = (float) $validated['stok_awal'];
        $safetyStock = round($est * 0.25);
        $order = max(0, ($est + $safetyStock) - $stok);

        Medicine::create([
            'nama_obat' => trim($validated['nama_obat']),
            'satuan' => trim($validated['satuan']),
            'model_terbaik' => 'WMA (k=3)',
            'mape_terbaik' => 15.00,
            'mae' => round($est * 0.15),
            'rmse' => round($est * 0.18),
            'prediksi_P5' => $est,
            'safety_stock' => $safetyStock,
            'sisa_stok' => $stok,
            'rekomendasi_total' => $order,
            'macd_value' => 0.0,
            'signal_line' => 0.0,
            'histogram' => 0.0,
            'momentum_status' => '📈 Baru Terdaftar (Stabil)',
            'prioritas_kategori' => $validated['prioritas_kategori'],
            'keterangan' => 'Sediaan Baru Ditambahkan Petugas Farmasi',
        ]);

        return redirect()->route('data.entry', ['tab' => 'obat'])->with('success', "Sediaan obat baru '{$validated['nama_obat']}' berhasil didaftarkan ke katalog aktif dengan estimasi kebutuhan " . number_format($est, 0, ',', '.') . " {$validated['satuan']}!");
    }
}
