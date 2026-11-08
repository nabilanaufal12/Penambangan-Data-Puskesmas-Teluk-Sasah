<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * DataMiningController — Portal /lab (Sains Data & Laboratorium CRISP-DM)
 * Kelompok 23, Mata Kuliah Penambangan Data INF11121, UMRAH
 * UPTD Puskesmas Teluk Sasah, Kab. Bintan
 */
class DataMiningController extends Controller
{
    /**
     * Membaca berkas CSV pivot dwibulanan mentah (22 periode 2023-P1 s.d. 2026-P4)
     * Mengembalikan kamus berindeks nama obat huruf kecil
     */
    private function loadPivotCsv(): array
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }

        $path = base_path('../07_Alur_Data_Mentah_ke_Final/Tahap_03_Tren_1Bulanan_dan_2Bulanan/03_lplpo_obat_pivot_matriks.csv');
        $cache = [];

        if (file_exists($path) && ($f = fopen($path, 'r')) !== false) {
            $header = fgetcsv($f);
            while (($row = fgetcsv($f)) !== false) {
                if (count($row) === count($header)) {
                    $key = strtolower(trim($row[0]));
                    $cache[$key] = array_combine($header, $row);
                }
            }
            fclose($f);
        }

        return $cache;
    }

    /**
     * TAB 1 — Pipeline CRISP-DM & EDA
     */
    public function pipelineEda()
    {
        // 1. Ringkasan Eksekutif KPI
        $totalMedicines = DB::table('medicines')->count();
        $totalVisits    = DB::table('disease_records')->sum('total_pasien');
        $totalEdLoss    = DB::table('expired_medicines')->sum('sub_total_kerugian');
        $totalEdItems   = DB::table('expired_medicines')->count();
        $censored       = DB::table('medicines')->where('is_censored_adjusted', 1)->count();
        $stockout       = DB::table('medicines')->where('sisa_stok', 0)->count();
        $avgAccuracy    = DB::table('medicines')->avg('evaluasi_akurasi');

        // 2. Distribusi Model Terbaik
        $modelDist = DB::table('medicines')
            ->select('model_terbaik', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('model_terbaik')
            ->orderByDesc('jumlah')
            ->get();

        // 3. Distribusi Prioritas ABC-VEN
        $prioritasDist = DB::table('medicines')
            ->select('prioritas_kategori', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('prioritas_kategori')
            ->orderBy('prioritas_kategori')
            ->get();

        // 4. Top 10 Penyakit SIMPUS
        $topDiseases = DB::table('disease_records')
            ->select('kode_icd', 'nama_penyakit', 'klaster_icd',
                DB::raw('SUM(pasien_laki) as total_laki'),
                DB::raw('SUM(pasien_perempuan) as total_perempuan'),
                DB::raw('SUM(total_pasien) as grand_total'))
            ->groupBy('kode_icd', 'nama_penyakit', 'klaster_icd')
            ->orderByDesc('grand_total')
            ->limit(10)
            ->get();

        // 5. Audit Kerugian ED per Sumber Dana
        $edBySumber = DB::table('expired_medicines')
            ->select('sumber_anggaran', DB::raw('SUM(sub_total_kerugian) as total'), DB::raw('COUNT(*) as items'))
            ->groupBy('sumber_anggaran')
            ->get();

        // 6. Profil Statistik 150 Sediaan Obat (Diperkaya Data CSV)
        $pivotCsv = $this->loadPivotCsv();
        $dbMeds   = DB::table('medicines')->orderBy('nama_obat')->get();
        $medicinesProfile = [];

        foreach ($dbMeds as $m) {
            $key = strtolower(trim($m->nama_obat));
            $csv = $pivotCsv[$key] ?? null;

            $mean     = $csv ? (float) $csv['mean_pemakaian'] : (float) $m->pemakaian;
            $std      = $csv ? (float) $csv['std_pemakaian']  : (float) ($m->safety_stock / 1.645);
            $cv       = $csv ? ((float) $csv['cv_pemakaian'] * 100) : ($mean > 0 ? ($std / $mean * 100) : 0);
            $minVal   = $csv ? (float) $csv['min_pemakaian'] : 0;
            $maxVal   = $csv ? (float) $csv['max_pemakaian'] : (float) $m->pemakaian;
            $actCount = $csv ? (int) $csv['periode_aktif_count'] : 22;
            $stockoutRate = round(((22 - $actCount) / 22) * 100, 1);

            $medicinesProfile[] = (object) [
                'id'                 => $m->id,
                'nama_obat'          => $m->nama_obat,
                'satuan'             => $m->satuan,
                'prioritas'          => $m->prioritas_kategori,
                'mean'               => round($mean, 1),
                'std'                => round($std, 1),
                'cv'                 => round($cv, 1),
                'min'                => round($minVal),
                'max'                => round($maxVal),
                'periode_aktif'      => $actCount,
                'stockout_rate'      => $stockoutRate,
                'model_terbaik'      => $m->model_terbaik,
                'evaluasi_akurasi'   => round((float) $m->evaluasi_akurasi, 1),
                'mape'               => round((float) $m->mape_terbaik, 1),
                'prediksi_P5'        => round((float) $m->prediksi_P5),
                'safety_stock'       => round((float) $m->safety_stock),
                'rekomendasi_total'  => round((float) $m->rekomendasi_total),
                'is_censored'        => (bool) $m->is_censored_adjusted,
                'sisa_stok'          => (float) $m->sisa_stok,
            ];
        }

        // Daftar obat ringkas untuk autocomplete Drug Switcher
        $allMedicines = DB::table('medicines')
            ->select('id', 'nama_obat', 'satuan', 'model_terbaik', 'evaluasi_akurasi', 'momentum_status', 'prioritas_kategori')
            ->orderBy('nama_obat')
            ->get();

        return view('lab.eda', compact(
            'totalMedicines', 'totalVisits', 'totalEdLoss', 'totalEdItems',
            'censored', 'stockout', 'avgAccuracy', 'modelDist', 'prioritasDist',
            'topDiseases', 'edBySumber', 'medicinesProfile', 'allMedicines'
        ));
    }

    /**
     * TAB 2 — Pra-Proses & Imputasi Censored Demand
     */
    public function preprocessing()
    {
        $censoredMeds = DB::table('medicines')
            ->where('is_censored_adjusted', 1)
            ->select('id', 'nama_obat', 'satuan', 'prediksi_P5', 'safety_stock', 'sisa_stok', 'rekomendasi_total', 'evaluasi_akurasi', 'mape_terbaik', 'pernah_kosong')
            ->orderBy('nama_obat')
            ->get();

        $stockoutMeds = DB::table('medicines')
            ->where('sisa_stok', 0)
            ->select('id', 'nama_obat', 'satuan', 'prediksi_P5', 'safety_stock', 'rekomendasi_total', 'momentum_status', 'evaluasi_akurasi')
            ->orderBy('nama_obat')
            ->get();

        $ssStats = DB::table('medicines')->selectRaw('
            AVG(safety_stock) as avg_ss,
            MIN(safety_stock) as min_ss,
            MAX(safety_stock) as max_ss,
            SUM(safety_stock) as total_ss,
            AVG(sisa_stok) as avg_sisa,
            SUM(CASE WHEN sisa_stok = 0 THEN 1 ELSE 0 END) as kosong_count,
            SUM(CASE WHEN is_censored_adjusted = 1 THEN 1 ELSE 0 END) as censored_count
        ')->first();

        $beforeAfter = DB::table('medicines')
            ->select('id', 'nama_obat', 'satuan', 'pemakaian', 'prediksi_P5', 'safety_stock',
                     'sisa_stok', 'is_censored_adjusted', 'pernah_kosong', 'evaluasi_akurasi', 'mape_terbaik')
            ->orderByDesc('is_censored_adjusted')
            ->orderBy('nama_obat')
            ->limit(30)
            ->get();

        $topSS = DB::table('medicines')
            ->select('nama_obat', 'satuan', 'safety_stock', 'prediksi_P5', 'sisa_stok', 'rekomendasi_total')
            ->orderByDesc('safety_stock')
            ->limit(10)
            ->get();

        $allMedicines = DB::table('medicines')
            ->select('id', 'nama_obat', 'satuan', 'model_terbaik', 'evaluasi_akurasi', 'momentum_status', 'prioritas_kategori')
            ->orderBy('nama_obat')
            ->get();

        return view('lab.preprocessing', compact(
            'censoredMeds', 'stockoutMeds', 'ssStats', 'beforeAfter', 'topSS', 'allMedicines'
        ));
    }

    /**
     * TAB 3 — Arena Model & MACD
     */
    public function modelingArena()
    {
        $benchmarks = DB::table('ml_benchmarks')->orderBy('wape')->get();

        $bullishMeds = DB::table('medicines')
            ->where('histogram', '>', 0)
            ->select('id', 'nama_obat', 'satuan', 'macd_value', 'signal_line', 'histogram', 'momentum_status', 'prediksi_P5', 'model_terbaik', 'evaluasi_akurasi')
            ->orderByDesc('histogram')
            ->limit(20)
            ->get();

        $bearishMeds = DB::table('medicines')
            ->where('histogram', '<', 0)
            ->select('id', 'nama_obat', 'satuan', 'macd_value', 'signal_line', 'histogram', 'momentum_status', 'prediksi_P5', 'model_terbaik', 'evaluasi_akurasi')
            ->orderBy('histogram')
            ->limit(20)
            ->get();

        $modelDist = DB::table('medicines')
            ->select('model_terbaik', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('model_terbaik')
            ->orderByDesc('jumlah')
            ->get();

        $macdStats = DB::table('medicines')->selectRaw('
            SUM(CASE WHEN histogram > 0 THEN 1 ELSE 0 END) as bullish_count,
            SUM(CASE WHEN histogram < 0 THEN 1 ELSE 0 END) as bearish_count,
            SUM(CASE WHEN histogram = 0 THEN 1 ELSE 0 END) as neutral_count,
            AVG(evaluasi_akurasi) as avg_accuracy,
            MIN(mape_terbaik) as min_mape,
            MAX(evaluasi_akurasi) as max_accuracy
        ')->first();

        $topAccuracy = DB::table('medicines')
            ->select('nama_obat', 'satuan', 'model_terbaik', 'evaluasi_akurasi', 'mape_terbaik', 'prediksi_P5', 'momentum_status')
            ->orderByDesc('evaluasi_akurasi')
            ->limit(15)
            ->get();

        $allMedicines = DB::table('medicines')
            ->select('id', 'nama_obat', 'satuan', 'model_terbaik', 'evaluasi_akurasi', 'macd_value', 'signal_line', 'histogram', 'momentum_status')
            ->orderBy('nama_obat')
            ->get();

        return view('lab.modeling', compact(
            'benchmarks', 'bullishMeds', 'bearishMeds', 'modelDist',
            'macdStats', 'topAccuracy', 'allMedicines'
        ));
    }

    /**
     * TAB 4 — Korelasi ICD-10 & Evaluasi Model
     */
    public function correlationEvaluation()
    {
        $correlations = DB::table('clinical_correlations')->orderByDesc('pearson_r')->get();
        $benchmarks   = DB::table('ml_benchmarks')->orderBy('wape')->get();

        $accuracyDist = DB::table('medicines')->selectRaw('
            SUM(CASE WHEN evaluasi_akurasi >= 90 THEN 1 ELSE 0 END) as tier_excellent,
            SUM(CASE WHEN evaluasi_akurasi >= 70 AND evaluasi_akurasi < 90 THEN 1 ELSE 0 END) as tier_good,
            SUM(CASE WHEN evaluasi_akurasi >= 50 AND evaluasi_akurasi < 70 THEN 1 ELSE 0 END) as tier_fair,
            SUM(CASE WHEN evaluasi_akurasi < 50 THEN 1 ELSE 0 END) as tier_poor,
            AVG(evaluasi_akurasi) as avg_acc,
            MIN(evaluasi_akurasi) as min_acc,
            MAX(evaluasi_akurasi) as max_acc
        ')->first();

        $accuracyBins = DB::table('medicines')
            ->selectRaw('
                CASE
                    WHEN evaluasi_akurasi >= 90 THEN "90-100%"
                    WHEN evaluasi_akurasi >= 80 THEN "80-90%"
                    WHEN evaluasi_akurasi >= 70 THEN "70-80%"
                    WHEN evaluasi_akurasi >= 60 THEN "60-70%"
                    WHEN evaluasi_akurasi >= 50 THEN "50-60%"
                    WHEN evaluasi_akurasi >= 40 THEN "40-50%"
                    WHEN evaluasi_akurasi >= 30 THEN "30-40%"
                    WHEN evaluasi_akurasi >= 20 THEN "20-30%"
                    WHEN evaluasi_akurasi >= 10 THEN "10-20%"
                    ELSE "0-10%"
                END as bin_label,
                COUNT(*) as count
            ')
            ->groupBy('bin_label')
            ->orderBy('bin_label')
            ->get();

        $topDiseasesPred = DB::table('clinical_correlations')
            ->select('kode_icd', 'nama_penyakit', 'prediksi_kasus_P5', 'prediksi_kebutuhan_obat_P5', 'nama_obat_standar', 'pearson_r', 'spearman_rho', 'rasio_dosis_per_pasien')
            ->orderByDesc('prediksi_kasus_P5')
            ->get();

        $allMedicines = DB::table('medicines')
            ->select('id', 'nama_obat', 'satuan')
            ->orderBy('nama_obat')
            ->get();

        return view('lab.evaluation', compact(
            'correlations', 'benchmarks', 'accuracyDist', 'accuracyBins',
            'topDiseasesPred', 'allMedicines'
        ));
    }

    /**
     * Endpoint JSON untuk Instant Drug Switcher & Kalkulasi Multi-Model
     * Menggunakan data deret waktu riil dari CSV jika tersedia
     */
    public function inspectDrugJson(Request $request, int $drugId)
    {
        $drug = DB::table('medicines')->find($drugId);
        if (!$drug) {
            return response()->json(['error' => 'Obat tidak ditemukan'], 404);
        }

        $lens = $request->query('lens', 'bimonthly'); // 'bimonthly' (22P) atau 'monthly' (36B/12B)

        // 1. Ambil deret waktu riil dari CSV pivot
        $pivotCsv = $this->loadPivotCsv();
        $key      = strtolower(trim($drug->nama_obat));
        $csvRow   = $pivotCsv[$key] ?? null;

        $periodLabels = [];
        $rawY         = [];
        $imputedY     = [];
        $stockouts    = [];

        if ($csvRow) {
            // 22 Periode Dwibulanan: 2023-P1 s.d. 2026-P4
            $periodKeys = [
                '2023-P1', '2023-P2', '2023-P3', '2023-P4', '2023-P5', '2023-P6',
                '2024-P1', '2024-P2', '2024-P3', '2024-P4', '2024-P5', '2024-P6',
                '2025-P1', '2025-P2', '2025-P3', '2025-P4', '2025-P5', '2025-P6',
                '2026-P1', '2026-P2', '2026-P3', '2026-P4'
            ];

            foreach ($periodKeys as $idx => $pk) {
                $val = isset($csvRow[$pk]) ? (float) $csvRow[$pk] : 0.0;
                $periodLabels[] = $pk;
                $rawY[] = $val;

                // Deteksi Censored Demand (Stok Kosong pada 2026-P4 atau pemakaian 0 padahal riwayat aktif)
                $isStockout = ($val == 0.0 && $idx >= 2 && $drug->is_censored_adjusted);
                $stockouts[] = $isStockout;

                if ($isStockout) {
                    $imputed = round(($rawY[$idx - 1] + $rawY[$idx - 2]) / 2, 1);
                    $imputedY[] = $imputed;
                } else {
                    $imputedY[] = $val;
                }
            }
        } else {
            // Fallback sintetik jika nama obat tidak ditemukan di CSV
            $base = max(10, (float) $drug->pemakaian);
            for ($i = 1; $i <= 22; $i++) {
                $pk = 'P-' . (22 - $i);
                $periodLabels[] = $pk;
                $val = max(0, round($base + sin($i) * ($base * 0.2)));
                $rawY[] = $val;
                $imputedY[] = $val;
                $stockouts[] = false;
            }
        }

        // 2. Komputasi Deret Waktu: WMA-3 (w=[3,2,1]), SMA-3, SES (alpha=0.3), EMA
        $n = count($imputedY);
        $wma3 = array_fill(0, $n, null);
        $sma3 = array_fill(0, $n, null);
        $ses  = array_fill(0, $n, null);
        $residuals  = [];
        $traceTable = [];

        // SES: S_t = 0.3 * y_t + 0.7 * S_{t-1}
        if ($n > 0) {
            $ses[0] = $imputedY[0];
            for ($t = 1; $t < $n; $t++) {
                $ses[$t] = round(0.3 * $imputedY[$t - 1] + 0.7 * $ses[$t - 1], 1);
            }
        }

        // WMA-3 & SMA-3 mulai t=3
        for ($t = 3; $t < $n; $t++) {
            $y_t1 = $imputedY[$t - 1]; // Bobot 3 (terbaru)
            $y_t2 = $imputedY[$t - 2]; // Bobot 2
            $y_t3 = $imputedY[$t - 3]; // Bobot 1

            $predWma = round((3 * $y_t1 + 2 * $y_t2 + 1 * $y_t3) / 6, 1);
            $predSma = round(($y_t1 + $y_t2 + $y_t3) / 3, 1);

            $wma3[$t] = $predWma;
            $sma3[$t] = $predSma;

            $err    = round($imputedY[$t] - $predWma, 1);
            $denom  = max($imputedY[$t], $predWma, 1);
            $symAcc = round(max(0, 100 - (abs($err) / $denom) * 100), 1);

            $residuals[] = $err;

            $traceTable[] = [
                'periode'  => $periodLabels[$t],
                'window'   => "{$y_t3}, {$y_t2}, {$y_t1}",
                'formula'  => "(3×{$y_t1} + 2×{$y_t2} + 1×{$y_t3}) / 6",
                'actual'   => $imputedY[$t],
                'wma3'     => $predWma,
                'sma3'     => $predSma,
                'ses'      => $ses[$t],
                'residual' => $err,
                'accuracy' => $symAcc
            ];
        }

        // Proyeksi masa depan P5 (t=22)
        $last1 = $imputedY[$n - 1];
        $last2 = $imputedY[$n - 2];
        $last3 = $imputedY[$n - 3];
        $nextWma3 = round((3 * $last1 + 2 * $last2 + 1 * $last3) / 6);
        $nextSma3 = round(($last1 + $last2 + $last3) / 3);
        $nextSes  = round(0.3 * $last1 + 0.7 * $ses[$n - 1]);

        $extendedLabels = array_merge($periodLabels, ['2026-P5 (Prediksi)']);
        $extendedRawY   = array_merge($rawY, [null]);
        $extendedWma3   = array_merge($wma3, [$nextWma3]);
        $extendedSma3   = array_merge($sma3, [$nextSma3]);
        $extendedSes    = array_merge($ses,  [$nextSes]);

        // 3. Komputasi MACD Sesuai Lensa
        [$fastSpan, $slowSpan, $sigSpan] = ($lens === 'monthly') ? [12, 26, 9] : [2, 4, 2];
        $emaFast   = $this->calculateEMA($imputedY, $fastSpan);
        $emaSlow   = $this->calculateEMA($imputedY, $slowSpan);
        $macdLine  = [];
        for ($i = 0; $i < $n; $i++) {
            $macdLine[$i] = round($emaFast[$i] - $emaSlow[$i], 2);
        }
        $signalLine = $this->calculateEMA($macdLine, $sigSpan);
        $histogram  = [];
        for ($i = 0; $i < $n; $i++) {
            $histogram[$i] = round($macdLine[$i] - $signalLine[$i], 2);
        }

        // 4. Safety Stock & Evaluasi
        $ssVal = (float) $drug->safety_stock;
        $ssLine = array_fill(0, count($extendedLabels), round($ssVal));

        // Metrik evaluasi 3 model
        $metricsWma3 = $this->evaluateMetrics($imputedY, $wma3);
        $metricsSma3 = $this->evaluateMetrics($imputedY, $sma3);
        $metricsSes  = $this->evaluateMetrics($imputedY, $ses);

        return response()->json([
            'id'               => $drug->id,
            'nama_obat'        => $drug->nama_obat,
            'satuan'           => $drug->satuan,
            'model_terbaik'    => $drug->model_terbaik,
            'evaluasi_akurasi' => round((float) $drug->evaluasi_akurasi, 1),
            'mape'             => round((float) $drug->mape_terbaik, 1),
            'prediksi_P5'      => round((float) $drug->prediksi_P5),
            'safety_stock'     => round($ssVal),
            'sisa_stok'        => (float) $drug->sisa_stok,
            'rekomendasi'      => round((float) $drug->rekomendasi_total),
            'macd_value'       => round((float) $drug->macd_value, 2),
            'signal_line'      => round((float) $drug->signal_line, 2),
            'histogram'        => round((float) $drug->histogram, 2),
            'momentum_status'  => $drug->momentum_status,
            'prioritas'        => $drug->prioritas_kategori,
            'is_censored'      => (bool) $drug->is_censored_adjusted,
            'pernah_kosong'    => (bool) $drug->pernah_kosong,
            'lens'             => $lens,
            'ss_formula'       => [
                'z'      => 1.645,
                'sigma'  => round($ssVal / (1.645 * sqrt(2)), 1),
                'L'      => 2,
                'result' => round($ssVal),
            ],
            'chart' => [
                'labels'    => $extendedLabels,
                'raw_y'     => $extendedRawY,
                'imputed_y' => array_merge($imputedY, [null]),
                'wma3'      => $extendedWma3,
                'sma3'      => $extendedSma3,
                'ses'       => $extendedSes,
                'ss_line'   => $ssLine,
                'stockouts' => $stockouts,
                'macd'      => [
                    'labels'    => $periodLabels,
                    'fast_ema'  => $emaFast,
                    'slow_ema'  => $emaSlow,
                    'macd_line' => $macdLine,
                    'signal'    => $signalLine,
                    'histogram' => $histogram,
                    'params'    => "({$fastSpan}, {$slowSpan}, {$sigSpan})"
                ]
            ],
            'residuals'  => $residuals,
            'trace'      => array_reverse($traceTable), // Terbaru di atas
            'metrics'    => [
                'wma3' => $metricsWma3,
                'sma3' => $metricsSma3,
                'ses'  => $metricsSes,
            ]
        ]);
    }

    /**
     * Ekspor CSV Bersih — 150 Sediaan Obat
     */
    public function exportCsv(): StreamedResponse
    {
        $pivotCsv = $this->loadPivotCsv();
        $dbMeds   = DB::table('medicines')->orderBy('id')->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="PRABOTIK_150_Obat_CRISP_DM_' . date('Ymd_His') . '.csv"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        return response()->stream(function () use ($dbMeds, $pivotCsv) {
            $f = fopen('php://output', 'w');
            // BOM UTF-8 untuk Excel
            fputs($f, "\xEF\xBB\xBF");

            fputcsv($f, [
                'ID', 'Nama Obat', 'Satuan', 'Kategori Prioritas',
                'Mean Pemakaian (mu)', 'Std Deviasi (sigma)', 'Koefisien Variasi CV (%)',
                'Min Pemakaian', 'Max Pemakaian', 'Periode Aktif (dari 22)', 'Rasio Stockout (%)',
                'Model Terbaik', 'Akurasi Simetris (%)', 'MAPE (%)', 'MAE', 'RMSE',
                'Stok Awal', 'Penerimaan', 'Persediaan', 'Pemakaian Riil', 'Sisa Stok Gudang',
                'Prediksi P5', 'Safety Stock (SS)', 'Rekomendasi Total (Q)',
                'MACD Value', 'Signal Line', 'MACD Histogram', 'Status Momentum',
                'Censored Demand Disesuaikan'
            ]);

            foreach ($dbMeds as $m) {
                $key = strtolower(trim($m->nama_obat));
                $csv = $pivotCsv[$key] ?? null;

                $mean     = $csv ? (float) $csv['mean_pemakaian'] : (float) $m->pemakaian;
                $std      = $csv ? (float) $csv['std_pemakaian']  : (float) ($m->safety_stock / 1.645);
                $cv       = $csv ? ((float) $csv['cv_pemakaian'] * 100) : ($mean > 0 ? ($std / $mean * 100) : 0);
                $minVal   = $csv ? (float) $csv['min_pemakaian'] : 0;
                $maxVal   = $csv ? (float) $csv['max_pemakaian'] : (float) $m->pemakaian;
                $actCount = $csv ? (int) $csv['periode_aktif_count'] : 22;
                $stockoutRate = round(((22 - $actCount) / 22) * 100, 1);

                fputcsv($f, [
                    $m->id,
                    $m->nama_obat,
                    $m->satuan,
                    $m->prioritas_kategori,
                    round($mean, 2),
                    round($std, 2),
                    round($cv, 2),
                    round($minVal),
                    round($maxVal),
                    $actCount,
                    $stockoutRate,
                    $m->model_terbaik,
                    round((float) $m->evaluasi_akurasi, 2),
                    round((float) $m->mape_terbaik, 2),
                    round((float) $m->mae, 2),
                    round((float) $m->rmse, 2),
                    $m->stok_awal,
                    $m->penerimaan,
                    $m->persediaan,
                    $m->pemakaian,
                    $m->sisa_stok,
                    round((float) $m->prediksi_P5),
                    round((float) $m->safety_stock),
                    round((float) $m->rekomendasi_total),
                    $m->macd_value,
                    $m->signal_line,
                    $m->histogram,
                    $m->momentum_status,
                    $m->is_censored_adjusted ? 'Ya' : 'Tidak'
                ]);
            }
            fclose($f);
        }, 200, $headers);
    }

    // =========================================================================
    // Pembantu Algoritmik Deret Waktu
    // =========================================================================
    private function calculateEMA(array $series, int $span): array
    {
        $alpha = 2 / ($span + 1);
        $ema = [];
        foreach ($series as $i => $val) {
            $ema[$i] = ($i === 0) ? $val : round($alpha * $val + (1 - $alpha) * $ema[$i - 1], 2);
        }
        return $ema;
    }

    private function evaluateMetrics(array $actual, array $pred): array
    {
        $sumAbsErr = 0; $sumActual = 0; $sumSqErr = 0; $sumSymAcc = 0; $count = 0;
        foreach ($actual as $t => $y) {
            if (!isset($pred[$t]) || $pred[$t] === null) continue;
            $err = abs($y - $pred[$t]);
            $sumAbsErr += $err;
            $sumActual += $y;
            $sumSqErr  += pow($y - $pred[$t], 2);
            $sumSymAcc += max(0, 100 - ($err / max($y, $pred[$t], 1)) * 100);
            $count++;
        }
        if ($count === 0) return ['wape' => 0, 'rmse' => 0, 'mae' => 0, 'accuracy' => 0];
        return [
            'wape'     => round(($sumAbsErr / max($sumActual, 1)) * 100, 2),
            'rmse'     => round(sqrt($sumSqErr / $count), 2),
            'mae'      => round($sumAbsErr / $count, 2),
            'accuracy' => round($sumSymAcc / $count, 1)
        ];
    }
}
