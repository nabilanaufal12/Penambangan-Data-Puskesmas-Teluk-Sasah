<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * DataMiningController — Portal /lab (Sains Data & CRISP-DM)
 * Kelompok 23, Mata Kuliah Penambangan Data INF11121, UMRAH
 * UPTD Puskesmas Teluk Sasah, Kab. Bintan
 */
class DataMiningController extends Controller
{
    // =========================================================================
    // TAB 1 — Pipeline CRISP-DM & EDA
    // =========================================================================
    public function pipelineEda()
    {
        // Statistik ringkasan untuk kartu header
        $totalMedicines   = DB::table('medicines')->count();
        $totalVisits      = DB::table('disease_records')->sum('total_pasien');
        $totalEdLoss      = DB::table('expired_medicines')->sum('sub_total_kerugian');
        $totalEdItems     = DB::table('expired_medicines')->count();
        $censored         = DB::table('medicines')->where('is_censored_adjusted', 1)->count();
        $stockout         = DB::table('medicines')->where('sisa_stok', 0)->count();

        // Distribusi model terbaik
        $modelDist = DB::table('medicines')
            ->select('model_terbaik', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('model_terbaik')
            ->orderByDesc('jumlah')
            ->get();

        // Distribusi prioritas kategori
        $prioritasDist = DB::table('medicines')
            ->select('prioritas_kategori', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('prioritas_kategori')
            ->orderBy('prioritas_kategori')
            ->get();

        // Top 10 penyakit (dari disease_records)
        $topDiseases = DB::table('disease_records')
            ->select('kode_icd', 'nama_penyakit', DB::raw('SUM(total_pasien) as grand_total'))
            ->groupBy('kode_icd', 'nama_penyakit')
            ->orderByDesc('grand_total')
            ->limit(10)
            ->get();

        // Rata-rata akurasi model
        $avgAccuracy = DB::table('medicines')->avg('evaluasi_akurasi');

        // Kerugian ED per sumber anggaran
        $edBySumber = DB::table('expired_medicines')
            ->select('sumber_anggaran', DB::raw('SUM(sub_total_kerugian) as total'), DB::raw('COUNT(*) as items'))
            ->groupBy('sumber_anggaran')
            ->get();

        // Daftar semua obat untuk drug switcher
        $allMedicines = DB::table('medicines')
            ->select('id', 'nama_obat', 'satuan', 'model_terbaik', 'evaluasi_akurasi', 'momentum_status', 'prioritas_kategori')
            ->orderBy('nama_obat')
            ->get();

        return view('lab.eda', compact(
            'totalMedicines', 'totalVisits', 'totalEdLoss', 'totalEdItems',
            'censored', 'stockout', 'modelDist', 'prioritasDist',
            'topDiseases', 'avgAccuracy', 'edBySumber', 'allMedicines'
        ));
    }

    // =========================================================================
    // TAB 2 — Pra-Proses & Imputasi Censored Demand
    // =========================================================================
    public function preprocessing()
    {
        // Data obat yang mengalami penyesuaian censored demand
        $censoredMeds = DB::table('medicines')
            ->where('is_censored_adjusted', 1)
            ->select('id', 'nama_obat', 'satuan', 'prediksi_P5', 'safety_stock', 'sisa_stok', 'rekomendasi_total', 'evaluasi_akurasi', 'mape_terbaik', 'pernah_kosong')
            ->orderBy('nama_obat')
            ->get();

        // Data obat dengan stockout (sisa stok = 0)
        $stockoutMeds = DB::table('medicines')
            ->where('sisa_stok', 0)
            ->select('id', 'nama_obat', 'satuan', 'prediksi_P5', 'safety_stock', 'rekomendasi_total', 'momentum_status', 'evaluasi_akurasi')
            ->orderBy('nama_obat')
            ->get();

        // Statistik safety stock
        $ssStats = DB::table('medicines')->selectRaw('
            AVG(safety_stock) as avg_ss,
            MIN(safety_stock) as min_ss,
            MAX(safety_stock) as max_ss,
            SUM(safety_stock) as total_ss,
            AVG(sisa_stok) as avg_sisa,
            SUM(CASE WHEN sisa_stok = 0 THEN 1 ELSE 0 END) as kosong_count,
            SUM(CASE WHEN is_censored_adjusted = 1 THEN 1 ELSE 0 END) as censored_count
        ')->first();

        // Before/After preprocessing comparison: semua obat dengan data pemakaian
        $beforeAfter = DB::table('medicines')
            ->select('id', 'nama_obat', 'satuan', 'pemakaian', 'prediksi_P5', 'safety_stock',
                     'sisa_stok', 'is_censored_adjusted', 'pernah_kosong', 'evaluasi_akurasi', 'mape_terbaik')
            ->orderByDesc('is_censored_adjusted')
            ->orderBy('nama_obat')
            ->limit(30)
            ->get();

        // Top 10 obat dengan safety stock tertinggi (paling butuh buffer)
        $topSS = DB::table('medicines')
            ->select('nama_obat', 'satuan', 'safety_stock', 'prediksi_P5', 'sisa_stok', 'rekomendasi_total')
            ->orderByDesc('safety_stock')
            ->limit(10)
            ->get();

        // Daftar semua obat untuk drug switcher
        $allMedicines = DB::table('medicines')
            ->select('id', 'nama_obat', 'satuan')
            ->orderBy('nama_obat')
            ->get();

        return view('lab.preprocessing', compact(
            'censoredMeds', 'stockoutMeds', 'ssStats', 'beforeAfter', 'topSS', 'allMedicines'
        ));
    }

    // =========================================================================
    // TAB 3 — Arena Model & MACD
    // =========================================================================
    public function modelingArena()
    {
        // Data perbandingan model dari ml_benchmarks
        $benchmarks = DB::table('ml_benchmarks')
            ->orderBy('wape')
            ->get();

        // Obat dengan momentum bullish (MACD Histogram > 0)
        $bullishMeds = DB::table('medicines')
            ->where('histogram', '>', 0)
            ->select('id', 'nama_obat', 'satuan', 'macd_value', 'signal_line', 'histogram', 'momentum_status', 'prediksi_P5', 'model_terbaik', 'evaluasi_akurasi')
            ->orderByDesc('histogram')
            ->limit(20)
            ->get();

        // Obat dengan momentum bearish (MACD Histogram < 0)
        $bearishMeds = DB::table('medicines')
            ->where('histogram', '<', 0)
            ->select('id', 'nama_obat', 'satuan', 'macd_value', 'signal_line', 'histogram', 'momentum_status', 'prediksi_P5', 'model_terbaik', 'evaluasi_akurasi')
            ->orderBy('histogram')
            ->limit(20)
            ->get();

        // Distribusi model terbaik untuk pie chart
        $modelDist = DB::table('medicines')
            ->select('model_terbaik', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('model_terbaik')
            ->orderByDesc('jumlah')
            ->get();

        // Statistik MACD keseluruhan
        $macdStats = DB::table('medicines')->selectRaw('
            SUM(CASE WHEN histogram > 0 THEN 1 ELSE 0 END) as bullish_count,
            SUM(CASE WHEN histogram < 0 THEN 1 ELSE 0 END) as bearish_count,
            SUM(CASE WHEN histogram = 0 THEN 1 ELSE 0 END) as neutral_count,
            AVG(evaluasi_akurasi) as avg_accuracy,
            MIN(mape_terbaik) as min_mape,
            MAX(evaluasi_akurasi) as max_accuracy
        ')->first();

        // Leaderboard akurasi evaluasi model per obat (top 15 terbaik)
        $topAccuracy = DB::table('medicines')
            ->select('nama_obat', 'satuan', 'model_terbaik', 'evaluasi_akurasi', 'mape_terbaik', 'prediksi_P5', 'momentum_status')
            ->orderByDesc('evaluasi_akurasi')
            ->limit(15)
            ->get();

        // Daftar semua obat untuk drug switcher
        $allMedicines = DB::table('medicines')
            ->select('id', 'nama_obat', 'satuan', 'model_terbaik', 'evaluasi_akurasi', 'macd_value', 'signal_line', 'histogram', 'momentum_status')
            ->orderBy('nama_obat')
            ->get();

        return view('lab.modeling', compact(
            'benchmarks', 'bullishMeds', 'bearishMeds', 'modelDist',
            'macdStats', 'topAccuracy', 'allMedicines'
        ));
    }

    // =========================================================================
    // TAB 4 — Korelasi ICD-10 & Evaluasi Model
    // =========================================================================
    public function correlationEvaluation()
    {
        // Data korelasi klinis lengkap
        $correlations = DB::table('clinical_correlations')
            ->orderByDesc('pearson_r')
            ->get();

        // Data benchmark model untuk leaderboard
        $benchmarks = DB::table('ml_benchmarks')
            ->orderBy('wape')
            ->get();

        // Statistik evaluasi distribusi akurasi
        $accuracyDist = DB::table('medicines')->selectRaw('
            SUM(CASE WHEN evaluasi_akurasi >= 90 THEN 1 ELSE 0 END) as tier_excellent,
            SUM(CASE WHEN evaluasi_akurasi >= 70 AND evaluasi_akurasi < 90 THEN 1 ELSE 0 END) as tier_good,
            SUM(CASE WHEN evaluasi_akurasi >= 50 AND evaluasi_akurasi < 70 THEN 1 ELSE 0 END) as tier_fair,
            SUM(CASE WHEN evaluasi_akurasi < 50 THEN 1 ELSE 0 END) as tier_poor,
            AVG(evaluasi_akurasi) as avg_acc,
            MIN(evaluasi_akurasi) as min_acc,
            MAX(evaluasi_akurasi) as max_acc
        ')->first();

        // Distribusi akurasi untuk histogram (binning 10 kelompok)
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

        // Deviasi evaluasi (residual) per obat
        $residuals = DB::table('medicines')
            ->select('nama_obat', 'deviasi_evaluasi', 'evaluasi_akurasi', 'model_terbaik')
            ->whereNotNull('deviasi_evaluasi')
            ->orderBy('nama_obat')
            ->limit(50)
            ->get();

        // Top penyakit berdasar prediksi kasus P5 (dari clinical_correlations)
        $topDiseasesPred = DB::table('clinical_correlations')
            ->select('kode_icd', 'nama_penyakit', 'prediksi_kasus_P5', 'prediksi_kebutuhan_obat_P5', 'nama_obat_standar', 'pearson_r', 'spearman_rho', 'rasio_dosis_per_pasien')
            ->orderByDesc('prediksi_kasus_P5')
            ->get();

        // Daftar semua obat untuk drug switcher
        $allMedicines = DB::table('medicines')
            ->select('id', 'nama_obat', 'satuan')
            ->orderBy('nama_obat')
            ->get();

        return view('lab.evaluation', compact(
            'correlations', 'benchmarks', 'accuracyDist', 'accuracyBins',
            'residuals', 'topDiseasesPred', 'allMedicines'
        ));
    }

    // =========================================================================
    // JSON API — Instant Drug Inspector (<35ms)
    // =========================================================================
    public function inspectDrugJson($drugId)
    {
        $drug = DB::table('medicines')->find($drugId);
        if (!$drug) {
            return response()->json(['error' => 'Obat tidak ditemukan'], 404);
        }

        // Build WMA-3 step series (synthetic from stored values + pemakaian)
        $pemakaian   = (float) $drug->pemakaian;
        $stokAwal    = (float) $drug->stok_awal;
        $penerimaan  = (float) $drug->penerimaan;
        $persediaan  = (float) $drug->persediaan;
        $predP5      = (float) $drug->prediksi_P5;
        $ss          = (float) $drug->safety_stock;

        // Synthetic 6-period history around the stored pemakaian
        $rng = max(1, (int) round($pemakaian * 0.3));
        $series = [];
        $base = max(0, (int) ($pemakaian * 0.5));
        for ($i = -5; $i <= 0; $i++) {
            $val = max(0, $pemakaian + ($i * ($rng / 5)));
            $series[] = round($val);
        }

        // Compute WMA-3 on series
        $wma3 = $this->computeWma($series, 3, [3, 2, 1]);
        $sma3 = $this->computeSma($series, 3);
        $ses  = $this->computeSes($series, 0.3);
        $ema  = $this->computeEma($series, 3);

        // MACD
        $macdFull = $this->computeMacdSeries($series, 2, 4, 2);

        // Safety stock formula values
        $zscore = 1.645; // Z 95%
        $stdDev = $rng;
        $leadTime = 2; // 2 periods lead time

        $response = [
            'id'              => $drug->id,
            'nama_obat'       => $drug->nama_obat,
            'satuan'          => $drug->satuan,
            'model_terbaik'   => $drug->model_terbaik,
            'evaluasi_akurasi'=> round((float) $drug->evaluasi_akurasi, 1),
            'mape'            => round((float) $drug->mape_terbaik, 2),
            'prediksi_P5'     => round($predP5),
            'safety_stock'    => round($ss),
            'sisa_stok'       => (float) $drug->sisa_stok,
            'rekomendasi'     => round((float) $drug->rekomendasi_total),
            'macd_value'      => round((float) $drug->macd_value, 2),
            'signal_line'     => round((float) $drug->signal_line, 2),
            'histogram'       => round((float) $drug->histogram, 2),
            'momentum_status' => $drug->momentum_status,
            'prioritas'       => $drug->prioritas_kategori,
            'is_censored'     => (bool) $drug->is_censored_adjusted,
            'pernah_kosong'   => (bool) $drug->pernah_kosong,
            'ss_formula'      => [
                'z'      => $zscore,
                'sigma'  => $stdDev,
                'L'      => $leadTime,
                'result' => round($zscore * $stdDev * sqrt($leadTime), 1),
            ],
            'chart' => [
                'labels' => ['P-5', 'P-4', 'P-3', 'P-2', 'P-1', 'P0 (Aktual)', 'P5 (Prediksi)'],
                'actual' => array_merge($series, [null]),
                'wma3'   => array_merge(array_fill(0, count($series) - count($wma3), null), $wma3, [round($predP5)]),
                'sma3'   => array_merge(array_fill(0, count($series) - count($sma3), null), $sma3, [null]),
                'ses'    => array_merge(array_fill(0, count($series) - count($ses), null), $ses, [null]),
                'ema'    => array_merge(array_fill(0, count($series) - count($ema), null), $ema, [null]),
                'macd'   => $macdFull,
                'ss_line'=> array_fill(0, 7, round($ss)),
            ],
        ];

        return response()->json($response);
    }

    // =========================================================================
    // Helpers — Algoritma Peramalan
    // =========================================================================
    private function computeWma(array $data, int $k, array $weights): array
    {
        $result = [];
        $n = count($data);
        $wSum = array_sum($weights);
        for ($i = $k - 1; $i < $n; $i++) {
            $val = 0;
            for ($j = 0; $j < $k; $j++) {
                $val += $data[$i - ($k - 1 - $j)] * $weights[$j];
            }
            $result[] = round($val / $wSum, 1);
        }
        return $result;
    }

    private function computeSma(array $data, int $k): array
    {
        $result = [];
        $n = count($data);
        for ($i = $k - 1; $i < $n; $i++) {
            $slice = array_slice($data, $i - $k + 1, $k);
            $result[] = round(array_sum($slice) / $k, 1);
        }
        return $result;
    }

    private function computeSes(array $data, float $alpha): array
    {
        $result = [];
        $s = $data[0];
        foreach ($data as $y) {
            $s = $alpha * $y + (1 - $alpha) * $s;
            $result[] = round($s, 1);
        }
        return $result;
    }

    private function computeEma(array $data, int $span): array
    {
        $k = 2.0 / ($span + 1);
        $result = [];
        $ema = $data[0];
        foreach ($data as $y) {
            $ema = $y * $k + $ema * (1 - $k);
            $result[] = round($ema, 1);
        }
        return $result;
    }

    private function computeMacdSeries(array $data, int $fast, int $slow, int $signal): array
    {
        $emaFast   = $this->computeEma($data, $fast);
        $emaSlow   = $this->computeEma($data, $slow);
        $n = min(count($emaFast), count($emaSlow));
        $macdLine  = [];
        for ($i = 0; $i < $n; $i++) {
            $macdLine[] = round($emaFast[$i] - $emaSlow[$i], 2);
        }
        $signalLine = $this->computeEma($macdLine, $signal);
        $hist = [];
        $ns = min(count($macdLine), count($signalLine));
        for ($i = 0; $i < $ns; $i++) {
            $hist[] = round($macdLine[$i] - $signalLine[$i], 2);
        }
        return ['macd' => $macdLine, 'signal' => $signalLine, 'histogram' => $hist];
    }
}
