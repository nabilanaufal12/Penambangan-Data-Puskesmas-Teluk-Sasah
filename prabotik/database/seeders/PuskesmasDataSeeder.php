<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;
use App\Models\DiseaseRecord;
use App\Models\ClinicalCorrelation;
use App\Models\MlBenchmark;
use App\Models\ExpiredMedicine;
use Illuminate\Support\Facades\DB;

class PuskesmasDataSeeder extends Seeder
{
    public function run(): void
    {
        $baseDir = dirname(__DIR__, 3) . '/07_Alur_Data_Mentah_ke_Final';

        // 1. Seed ML Benchmarks
        $mlFile = $baseDir . '/Tahap_04_Pemodelan_MA_dan_Indikator_MACD/04_benchmarking_machine_learning_vs_baseline.csv';
        if (file_exists($mlFile)) {
            $handle = fopen($mlFile, 'r');
            $header = fgetcsv($handle);
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) >= 6) {
                    MlBenchmark::create([
                        'model_name' => trim($row[0]),
                        'kategori' => trim($row[1]),
                        'wape' => (float) $row[2],
                        'bias_percentage' => trim($row[3]),
                        'stabilitas_sd' => (float) $row[4],
                        'interpretasi' => trim($row[5]),
                    ]);
                }
            }
            fclose($handle);
        }

        // 2. Seed Clinical Correlations
        $corrFile = $baseDir . '/Tahap_05_Korelasi_Obat_dan_Penyakit/01_korelasi_penyakit_dan_obat_detail.csv';
        if (file_exists($corrFile)) {
            $handle = fopen($corrFile, 'r');
            $header = fgetcsv($handle);
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) >= 10) {
                    ClinicalCorrelation::create([
                        'kode_icd' => trim($row[0]),
                        'nama_penyakit' => trim($row[1]),
                        'nama_obat_standar' => trim($row[2]),
                        'nama_obat_lplpo' => trim($row[3]),
                        'indikasi_klinis' => trim($row[4]),
                        'pearson_r' => (float) $row[5],
                        'spearman_rho' => (float) $row[6],
                        'rasio_dosis_per_pasien' => (float) $row[7],
                        'prediksi_kasus_P5' => (float) $row[8],
                        'prediksi_kebutuhan_obat_P5' => (float) $row[9],
                    ]);
                }
            }
            fclose($handle);
        }

        // 3. Seed Disease Records (450 rows)
        $diseaseFile = $baseDir . '/Tahap_02_Pembersihan_dan_Standardisasi/02_penyakit_terstandarisasi_icd10.csv';
        if (file_exists($diseaseFile)) {
            $handle = fopen($diseaseFile, 'r');
            $header = fgetcsv($handle);
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) >= 10) {
                    DiseaseRecord::create([
                        'tahun' => (int) $row[0],
                        'bulan_angka' => (int) $row[1],
                        'bulan' => trim($row[2]),
                        'periode_bimonthly_id' => trim($row[3]),
                        'kode_icd' => trim($row[4]),
                        'nama_penyakit' => trim($row[5]),
                        'klaster_icd' => trim($row[6]),
                        'pasien_laki' => (float) $row[7],
                        'pasien_perempuan' => (float) $row[8],
                        'total_pasien' => (float) $row[9],
                    ]);
                }
            }
            fclose($handle);
        }

        // 4. Seed Expired Medicines (138 rows)
        $expiredFile = $baseDir . '/Tahap_02_Pembersihan_dan_Standardisasi/03_audit_obat_kadaluarsa_bersih.csv';
        if (file_exists($expiredFile)) {
            $handle = fopen($expiredFile, 'r');
            $header = fgetcsv($handle);
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) >= 10) {
                    ExpiredMedicine::create([
                        'tahun' => (int) $row[0],
                        'nama_obat' => trim($row[2]),
                        'satuan' => trim($row[3]),
                        'harga_satuan' => (float) $row[4],
                        'jumlah_kadaluarsa' => (float) $row[8],
                        'sub_total_kerugian' => (float) $row[9],
                        'sumber_anggaran' => 'APBD / DAK Farmasi',
                    ]);
                }
            }
            fclose($handle);
        }

        // 5. Seed Medicines (150 active medicines)
        // Load MACD lookup map
        $macdMap = [];
        $macdFile = $baseDir . '/Tahap_04_Pemodelan_MA_dan_Indikator_MACD/02_analisis_indikator_macd_obat.csv';
        if (file_exists($macdFile)) {
            $handle = fopen($macdFile, 'r');
            $header = fgetcsv($handle);
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) >= 8) {
                    $macdMap[trim($row[0])] = [
                        'macd' => (float) $row[4],
                        'signal' => (float) $row[5],
                        'hist' => (float) $row[6],
                        'status' => trim($row[7]),
                    ];
                }
            }
            fclose($handle);
        }

        // Load Priority Recommendations lookup map
        $recMap = [];
        $recFile = $baseDir . '/Tahap_07_Output_Final_dan_Rekomendasi_Pengadaan/01_rekomendasi_prioritas_pengadaan_2026_P5.csv';
        if (file_exists($recFile)) {
            $handle = fopen($recFile, 'r');
            $header = fgetcsv($handle);
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) >= 11) {
                    $recMap[trim($row[0])] = [
                        'safety_stock' => (float) $row[5],
                        'rekomendasi_total' => (float) $row[6],
                        'prioritas_kategori' => trim($row[10]),
                    ];
                }
            }
            fclose($handle);
        }

        // Load Moving Average Evaluations
        $maFile = $baseDir . '/Tahap_04_Pemodelan_MA_dan_Indikator_MACD/01_evaluasi_model_moving_average.csv';
        if (file_exists($maFile)) {
            $handle = fopen($maFile, 'r');
            $header = fgetcsv($handle);
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) >= 19) {
                    $nama = trim($row[0]);
                    $satuan = trim($row[1]);
                    $bestModel = trim($row[14]);
                    
                    // Determine mape based on best model
                    $mape = 0.0;
                    $mae = 0.0;
                    $rmse = 0.0;
                    if (str_contains($bestModel, 'SMA-2') || str_contains($bestModel, 'k=2')) {
                        $mape = (float) $row[5];
                        $mae = (float) $row[6];
                        $rmse = (float) $row[7];
                    } elseif (str_contains($bestModel, 'SMA (k=3)') || str_contains($bestModel, 'SMA-3')) {
                        $mape = (float) $row[8];
                        $mae = (float) $row[9];
                        $rmse = (float) $row[10];
                    } else {
                        $mape = (float) $row[11];
                        $mae = (float) $row[12];
                        $rmse = (float) $row[13];
                    }

                    $predP5 = (float) $row[18];
                    $macdInfo = $macdMap[$nama] ?? ['macd' => null, 'signal' => null, 'hist' => null, 'status' => 'Stabil'];
                    $recInfo = $recMap[$nama] ?? [
                        'safety_stock' => round($predP5 * 0.25),
                        'rekomendasi_total' => round($predP5 * 1.25),
                        'prioritas_kategori' => 'Prioritas 3 (Penunjang / Non-Esensial)',
                    ];

                    $isCensored = ($nama === 'Parasetamol tablet 500 mg');

                    Medicine::create([
                        'nama_obat' => $nama,
                        'satuan' => $satuan,
                        'model_terbaik' => $bestModel,
                        'mape_terbaik' => $mape,
                        'mae' => $mae,
                        'rmse' => $rmse,
                        'prediksi_P5' => $predP5,
                        'safety_stock' => $recInfo['safety_stock'],
                        'sisa_stok' => ($nama === 'Parasetamol tablet 500 mg') ? 0 : round($predP5 * 0.1),
                        'rekomendasi_total' => $recInfo['rekomendasi_total'],
                        'macd_value' => $macdInfo['macd'],
                        'signal_line' => $macdInfo['signal'],
                        'histogram' => $macdInfo['hist'],
                        'momentum_status' => $macdInfo['status'],
                        'prioritas_kategori' => $recInfo['prioritas_kategori'],
                        'is_censored_adjusted' => $isCensored,
                        'keterangan' => $isCensored ? 'Disesuaikan dari Permintaan Tersensor Stockout P4' : null,
                    ]);
                }
            }
            fclose($handle);
        }
    }
}
