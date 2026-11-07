#!/usr/bin/env python3
"""
scripts/3_forecasting_models.py

Pemodelan Prediksi Kebutuhan Stok Obat & Evaluasi Akurasi (BAB IV & BAB V)
Puskesmas Teluk Sasah Periode 2023 - 2026.

Metode yang diimplementasikan:
1. Simple Moving Average (SMA) dengan k=2
2. Simple Moving Average (SMA) dengan k=3
3. Weighted Moving Average (WMA) dengan k=3 (bobot: 1, 2, 3)

Metrik Evaluasi:
1. MAPE (Mean Absolute Percentage Error)
2. MAE (Mean Absolute Error)
3. RMSE (Root Mean Squared Error)

Luaran:
- data_processed/model_evaluation_metrics.csv & .xlsx
- data_processed/forecast_predictions_all.csv & .xlsx
- data_processed/forecast_step_by_step_calculation.csv
- Visualisasi peramalan aktual vs prediksi & perbandingan akurasi model
"""

import os
import re
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt

plt.rcParams['font.sans-serif'] = 'DejaVu Sans'
plt.rcParams['axes.edgecolor'] = '#333333'
plt.rcParams['axes.linewidth'] = 0.8
plt.rcParams['grid.color'] = '#e0e0e0'
plt.rcParams['grid.linestyle'] = '--'
plt.rcParams['grid.alpha'] = 0.7

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DATA_DIR = os.path.join(BASE_DIR, 'data_processed')
VIZ_DIR = os.path.join(BASE_DIR, 'visualizations')
os.makedirs(VIZ_DIR, exist_ok=True)

# 1. LOAD DATASET
df_pivot = pd.read_csv(os.path.join(DATA_DIR, 'lplpo_obat_pivot_pemakaian.csv'))
PERIODS = [c for c in df_pivot.columns if c.startswith('202')]

print(f"Dataset dimuat: {len(df_pivot)} jenis obat, {len(PERIODS)} periode dwibulanan.")

# ==============================================================================
# 2. IMPLEMENTASI ALGORITMA TIME SERIES FORECASTING
# ==============================================================================
def simple_moving_average(series, k):
    """Menghitung Simple Moving Average dengan ukuran jendela k."""
    n = len(series)
    forecast = np.full(n, np.nan)
    for t in range(k, n):
        forecast[t] = np.mean(series[t-k:t])
    return forecast

def weighted_moving_average(series, weights):
    """Menghitung Weighted Moving Average dengan bobot tertentu."""
    k = len(weights)
    weights = np.array(weights, dtype=float)
    w_sum = np.sum(weights)
    n = len(series)
    forecast = np.full(n, np.nan)
    for t in range(k, n):
        window = series[t-k:t]
        forecast[t] = np.sum(window * weights) / w_sum
    return forecast

def calculate_metrics(actual, forecast):
    """Menghitung metrik MAPE, MAE, dan RMSE pada titik-titik yang memiliki nilai prediksi."""
    valid_mask = ~np.isnan(forecast) & (actual > 0)
    if np.sum(valid_mask) == 0:
        return np.nan, np.nan, np.nan

    act = actual[valid_mask]
    pred = forecast[valid_mask]

    mape = np.mean(np.abs((act - pred) / act)) * 100.0
    mae = np.mean(np.abs(act - pred))
    rmse = np.sqrt(np.mean((act - pred)**2))
    return mape, mae, rmse

# ==============================================================================
# 3. EKSEKUSI PEMODELAN PADA SELURUH OBAT
# ==============================================================================
def run_all_models():
    print("Menjalankan peramalan pada seluruh obat...")
    results_metrics = []
    all_forecast_records = []

    for idx, row in df_pivot.iterrows():
        drug_name = row['nama_obat']
        satuan = row['satuan']
        actuals = row[PERIODS].values.astype(float)
        tot_usage = row['total_pemakaian']

        if tot_usage < 100:
            continue

        # Hitung Model 1: SMA (k=2)
        f_sma2 = simple_moving_average(actuals, k=2)
        mape_sma2, mae_sma2, rmse_sma2 = calculate_metrics(actuals, f_sma2)

        # Hitung Model 2: SMA (k=3)
        f_sma3 = simple_moving_average(actuals, k=3)
        mape_sma3, mae_sma3, rmse_sma3 = calculate_metrics(actuals, f_sma3)

        # Hitung Model 3: WMA (k=3, weights=[1, 2, 3])
        weights_3 = [1, 2, 3]
        f_wma3 = weighted_moving_average(actuals, weights_3)
        mape_wma3, mae_wma3, rmse_wma3 = calculate_metrics(actuals, f_wma3)

        # Prediksi Periode Berikutnya (2026-P5 / September - Oktober 2026)
        next_sma2 = np.mean(actuals[-2:])
        next_sma3 = np.mean(actuals[-3:])
        next_wma3 = np.sum(actuals[-3:] * np.array(weights_3)) / np.sum(weights_3)

        # Cari model terbaik berdasarkan MAPE terendah
        models_perf = [('SMA (k=2)', mape_sma2), ('SMA (k=3)', mape_sma3), ('WMA (k=3)', mape_wma3)]
        valid_models = [m for m in models_perf if not np.isnan(m[1])]
        best_model = min(valid_models, key=lambda x: x[1])[0] if valid_models else 'N/A'

        results_metrics.append({
            'nama_obat': drug_name,
            'satuan': satuan,
            'total_pemakaian': tot_usage,
            'mean_pemakaian': row['mean_pemakaian'],
            'cv_pemakaian': row['cv_pemakaian'],
            'mape_sma2': mape_sma2,
            'mae_sma2': mae_sma2,
            'rmse_sma2': rmse_sma2,
            'mape_sma3': mape_sma3,
            'mae_sma3': mae_sma3,
            'rmse_sma3': rmse_sma3,
            'mape_wma3': mape_wma3,
            'mae_wma3': mae_wma3,
            'rmse_wma3': rmse_wma3,
            'best_model': best_model,
            'next_pred_sma2': next_sma2,
            'next_pred_sma3': next_sma3,
            'next_pred_wma3': next_wma3,
            'forecast_rekomendasi_2026_P5': next_wma3 if best_model == 'WMA (k=3)' else (next_sma2 if best_model == 'SMA (k=2)' else next_sma3)
        })

        # Simpan jejak histori peramalan tiap periode
        for t_idx, p in enumerate(PERIODS):
            all_forecast_records.append({
                'nama_obat': drug_name,
                'satuan': satuan,
                'periode_bimonthly_id': p,
                'pemakaian_aktual': actuals[t_idx],
                'prediksi_sma2': f_sma2[t_idx],
                'prediksi_sma3': f_sma3[t_idx],
                'prediksi_wma3': f_wma3[t_idx],
            })

    df_metrics = pd.DataFrame(results_metrics)
    df_metrics.sort_values('total_pemakaian', ascending=False, inplace=True)

    df_forecasts = pd.DataFrame(all_forecast_records)

    # Simpan hasil pemodelan
    df_metrics.to_csv(os.path.join(DATA_DIR, 'model_evaluation_metrics.csv'), index=False)
    df_metrics.to_excel(os.path.join(DATA_DIR, 'model_evaluation_metrics.xlsx'), index=False)
    df_forecasts.to_csv(os.path.join(DATA_DIR, 'forecast_predictions_all.csv'), index=False)
    df_forecasts.to_excel(os.path.join(DATA_DIR, 'forecast_predictions_all.xlsx'), index=False)

    print(f"-> Metrik evaluasi disimpan: {len(df_metrics)} obat aktif")
    return df_metrics, df_forecasts

# ==============================================================================
# 4. TABEL PERHITUNGAN LANGKAH-DEMI-LANGKAH (STEP-BY-STEP UNTUK LAPORAN)
# ==============================================================================
def generate_step_by_step_table():
    print("Membuat tabel simulasi perhitungan langkah demi langkah (BAB IV)...")
    sample_drugs = [
        'Parasetamol tablet 500 mg',
        'Metformin tablet 500 mg',
        'Amoksisilin kaplet 500 mg',
        'Amlodipin 10 mg tablet'
    ]

    calc_rows = []
    for drug in sample_drugs:
        sub = df_pivot[df_pivot['nama_obat'].str.contains(drug, case=False)]
        if sub.empty:
            continue
        vals = sub[PERIODS].sum(axis=0).values

        for t in range(len(PERIODS)):
            p = PERIODS[t]
            act = vals[t]

            # SMA k=3
            if t >= 3:
                a_t1, a_t2, a_t3 = vals[t-1], vals[t-2], vals[t-3]
                f_sma3 = (a_t1 + a_t2 + a_t3) / 3.0
                rumus_sma3 = f"({int(a_t1)} + {int(a_t2)} + {int(a_t3)}) / 3"
                err_sma3 = abs(act - f_sma3)
                ape_sma3 = (err_sma3 / act * 100.0) if act > 0 else 0.0

                # WMA k=3
                f_wma3 = (3*a_t1 + 2*a_t2 + 1*a_t3) / 6.0
                rumus_wma3 = f"(3*{int(a_t1)} + 2*{int(a_t2)} + 1*{int(a_t3)}) / 6"
                err_wma3 = abs(act - f_wma3)
                ape_wma3 = (err_wma3 / act * 100.0) if act > 0 else 0.0
            else:
                f_sma3 = np.nan
                rumus_sma3 = "-"
                err_sma3 = np.nan
                ape_sma3 = np.nan
                f_wma3 = np.nan
                rumus_wma3 = "-"
                err_wma3 = np.nan
                ape_wma3 = np.nan

            calc_rows.append({
                'nama_obat': drug,
                'periode': p,
                'pemakaian_aktual': act,
                'rumus_sma3': rumus_sma3,
                'prediksi_sma3': f_sma3,
                'error_sma3': err_sma3,
                'ape_sma3_persen': ape_sma3,
                'rumus_wma3': rumus_wma3,
                'prediksi_wma3': f_wma3,
                'error_wma3': err_wma3,
                'ape_wma3_persen': ape_wma3
            })

    df_calc = pd.DataFrame(calc_rows)
    path_calc = os.path.join(DATA_DIR, 'forecast_step_by_step_calculation.csv')
    df_calc.to_csv(path_calc, index=False)
    print(f"-> Disimpan: {path_calc}")
    return df_calc

# ==============================================================================
# 5. VISUALISASI AKTUAL VS PREDIKSI (GRAFIK 9 - 14)
# ==============================================================================
def plot_actual_vs_pred(drug_query, drug_title, filename):
    print(f"Menghasilkan visualisasi Aktual vs Prediksi untuk {drug_title}...")
    sub = df_pivot[df_pivot['nama_obat'].str.contains(drug_query, case=False)]
    if sub.empty:
        return
    actuals = sub[PERIODS].sum(axis=0).values

    f_sma2 = simple_moving_average(actuals, k=2)
    f_sma3 = simple_moving_average(actuals, k=3)
    f_wma3 = weighted_moving_average(actuals, [1, 2, 3])

    mape_s2, _, _ = calculate_metrics(actuals, f_sma2)
    mape_s3, _, _ = calculate_metrics(actuals, f_sma3)
    mape_w3, _, _ = calculate_metrics(actuals, f_wma3)

    plt.figure(figsize=(14, 6.5), dpi=300)
    plt.plot(PERIODS, actuals, color='#111111', linewidth=2.8, marker='o', label='Pemakaian Aktual (LPLPO)')
    plt.plot(PERIODS, f_sma2, color='#e65100', linewidth=1.8, linestyle='--', marker='s', label=f'Prediksi SMA (k=2) [MAPE: {mape_s2:.1f}%]')
    plt.plot(PERIODS, f_sma3, color='#0288d1', linewidth=1.8, linestyle='-.', marker='^', label=f'Prediksi SMA (k=3) [MAPE: {mape_s3:.1f}%]')
    plt.plot(PERIODS, f_wma3, color='#2e7d32', linewidth=2.2, linestyle='-', marker='d', label=f'Prediksi WMA (k=3) [MAPE: {mape_w3:.1f}%]')

    # Plot titik proyeksi ke depan (2026-P5)
    next_p = '2026-P5 (Ramalan)'
    next_val_wma = (3*actuals[-1] + 2*actuals[-2] + 1*actuals[-3]) / 6.0
    plt.scatter([PERIODS[-1]], [next_val_wma], color='#c2185b', s=120, zorder=5)
    plt.annotate(f'Ramalan 2026-P5:\n{int(next_val_wma):,} butir',
                 xy=(PERIODS[-1], next_val_wma), xytext=(PERIODS[-3], next_val_wma * 1.08),
                 fontsize=10, fontweight='bold', color='#c2185b',
                 arrowprops=dict(arrowstyle='->', color='#c2185b', lw=1.5))

    plt.title(f'Evaluasi Model Prediksi Pemakaian Obat: {drug_title}\nPerbandingan Nilai Aktual vs Prediksi SMA & WMA (2023 - 2026)', fontsize=13, fontweight='bold', pad=15)
    plt.xlabel('Periode Dwibulanan (2023 - 2026)', fontsize=11, fontweight='bold')
    plt.ylabel('Jumlah Pemakaian Obat (Butir/Unit)', fontsize=11, fontweight='bold')
    plt.xticks(rotation=45)
    plt.legend(loc='upper right', frameon=True, facecolor='white', framealpha=0.9)
    plt.tight_layout()
    path = os.path.join(VIZ_DIR, filename)
    plt.savefig(path)
    plt.close()
    print(f"-> Disimpan: {path}")

def plot_mape_comparison(df_metrics):
    print("Menghasilkan Grafik 13: Perbandingan MAPE antar-Model...")
    top10_drugs = df_metrics.head(10).copy()

    x = np.arange(len(top10_drugs))
    width = 0.25

    plt.figure(figsize=(14, 7), dpi=300)
    b1 = plt.bar(x - width, top10_drugs['mape_sma2'], width, label='SMA (k=2)', color='#e65100', edgecolor='#bf360c')
    b2 = plt.bar(x, top10_drugs['mape_sma3'], width, label='SMA (k=3)', color='#0288d1', edgecolor='#01579b')
    b3 = plt.bar(x + width, top10_drugs['mape_wma3'], width, label='WMA (k=3)', color='#2e7d32', edgecolor='#1b5e20')

    # Garis acuan batas akurasi
    plt.axhline(10, color='#388e3c', linestyle='--', linewidth=1.2, label='Akurasi Sangat Baik (< 10%)')
    plt.axhline(20, color='#fbc02d', linestyle='--', linewidth=1.2, label='Akurasi Baik (< 20%)')
    plt.axhline(50, color='#d32f2f', linestyle='--', linewidth=1.2, label='Akurasi Layak (< 50%)')

    plt.title('Perbandingan Tingkat Kesalahan (MAPE %) antar-Model pada 10 Obat Utama\n(Simple Moving Average vs Weighted Moving Average)', fontsize=13, fontweight='bold', pad=15)
    plt.xlabel('Nama Obat Utama', fontsize=11, fontweight='bold')
    plt.ylabel('Nilai MAPE (%) - Semakin Rendah Semakin Baik', fontsize=11, fontweight='bold')
    plt.xticks(x, [n.split(' ')[0] + '\n' + ' '.join(n.split(' ')[1:3]) for n in top10_drugs['nama_obat']], fontsize=9)
    plt.legend(loc='upper right', frameon=True)
    plt.tight_layout()
    path = os.path.join(VIZ_DIR, '13_perbandingan_mape_model.png')
    plt.savefig(path)
    plt.close()
    print(f"-> Disimpan: {path}")

def plot_safety_stock_recommendation(df_metrics):
    print("Menghasilkan Grafik 14: Rekomendasi Safety Stock & Buffer Pengadaan...")
    top8 = df_metrics.head(8).copy()

    # Hitung Safety Stock (SS) dengan tingkat kepercayaan 95% (Z = 1.65)
    # SS = Z * std_pemakaian
    top8['safety_stock'] = (1.65 * (top8['cv_pemakaian'] * top8['mean_pemakaian'])).round()
    top8['buffer_pengadaan'] = top8['forecast_rekomendasi_2026_P5'] + top8['safety_stock']

    x = np.arange(len(top8))
    width = 0.35

    plt.figure(figsize=(14, 7), dpi=300)
    b1 = plt.bar(x - width/2, top8['forecast_rekomendasi_2026_P5'], width, label='Hasil Prediksi Kebutuhan (Model WMA/SMA)', color='#1976d2', edgecolor='#0d47a1')
    b2 = plt.bar(x + width/2, top8['buffer_pengadaan'], width, label='Rekomendasi Batas Pengadaan Termasuk Safety Stock (95%)', color='#388e3c', edgecolor='#1b5e20')

    for bar in b1:
        h = bar.get_height()
        plt.text(bar.get_x() + bar.get_width()/2, h + 200, f'{int(h):,}', ha='center', va='bottom', fontsize=8.5, fontweight='bold', color='#0d47a1')

    for bar in b2:
        h = bar.get_height()
        plt.text(bar.get_x() + bar.get_width()/2, h + 200, f'{int(h):,}', ha='center', va='bottom', fontsize=8.5, fontweight='bold', color='#1b5e20')

    plt.title('Rekomendasi Perencanaan Permintaan Obat ke Dinas Kesehatan Periode 2026-P5\n(Integrasi Model Prediksi Time Series + Safety Stock Buffer Anti-Stockout)', fontsize=13, fontweight='bold', pad=15)
    plt.xlabel('Nama Obat Utama', fontsize=11, fontweight='bold')
    plt.ylabel('Kuantitas Obat (Butir/Unit)', fontsize=11, fontweight='bold')
    plt.xticks(x, [n[:22] for n in top8['nama_obat']], rotation=25, ha='right', fontsize=9.5)
    plt.legend(loc='upper right', frameon=True)
    plt.tight_layout()
    path = os.path.join(VIZ_DIR, '14_rekomendasi_pengadaan_safety_stock.png')
    plt.savefig(path)
    plt.close()
    print(f"-> Disimpan: {path}")

# ==============================================================================
# MAIN FUNCTION
# ==============================================================================
def main():
    print("="*70)
    print("MEMULAI EKSEKUSI PEMODELAN MOVING AVERAGE & EVALUASI MAPE")
    print("="*70)

    # 1. Jalankan pemodelan untuk seluruh obat
    df_metrics, df_forecasts = run_all_models()

    # 2. Tabel simulasi perhitungan bertahap (untuk BAB IV)
    df_calc = generate_step_by_step_table()

    # 3. Visualisasi Aktual vs Prediksi
    plot_actual_vs_pred('Parasetamol tablet 500', 'Parasetamol 500 mg', '09_forecast_parasetamol_actual_vs_pred.png')
    plot_actual_vs_pred('Metformin tablet 500', 'Metformin 500 mg', '10_forecast_metformin_actual_vs_pred.png')
    plot_actual_vs_pred('Amlodipin 10 mg', 'Amlodipin 10 mg', '11_forecast_amlodipin_actual_vs_pred.png')
    plot_actual_vs_pred('Amoksisilin kaplet 500', 'Amoksisilin 500 mg', '12_forecast_amoksisilin_actual_vs_pred.png')

    # 4. Visualisasi Komparasi MAPE & Rekomendasi Safety Stock
    plot_mape_comparison(df_metrics)
    plot_safety_stock_recommendation(df_metrics)

    print("="*70)
    print("RINGKASAN HASIL EVALUASI PEMODELAN (TOP 10 OBAT):")
    print("="*70)
    summary_cols = ['nama_obat', 'mape_sma2', 'mape_sma3', 'mape_wma3', 'best_model', 'forecast_rekomendasi_2026_P5']
    print(df_metrics[summary_cols].head(10).to_string(index=False))
    print("="*70)
    print("SELURUH PROSES PEMODELAN & EVALUASI SELESAI DENGAN SUKSES!")

if __name__ == '__main__':
    main()
