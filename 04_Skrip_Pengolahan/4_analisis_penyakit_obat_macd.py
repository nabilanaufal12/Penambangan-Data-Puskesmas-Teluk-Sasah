#!/usr/bin/env python3
"""
04_Skrip_Pengolahan/4_analisis_penyakit_obat_macd.py

Skrip ini mengimplementasikan rekomendasi dosen pembimbing:
1. Analisis Tren Bulanan (12 Bulan 2024) vs Dwibulanan (22 Periode Kontinu).
2. Analisis Indikator MACD (Moving Average Convergence Divergence) pada pola konsumsi obat.
3. Analisis Korelasi Mendalam antara Penyakit Terbanyak dan Obat Terkait.
4. Peramalan Deret Waktu untuk 4 Penyakit Terbanyak (ISPA J00, Hipertensi I10, Dispepsia K30, Diabetes E11).
5. Model Proyeksi Kebutuhan Obat Berbasis Prediksi Penyakit (Dose/Consumption per Case Ratio).
"""

import os
import numpy as np
import pandas as pd

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DATA_DIR = os.path.join(BASE_DIR, '03_Data_Terproses')

print("=" * 75)
print("MEMULAI PENGOLAHAN DATA SESUAI REKOMENDASI DOSEN PEMBIMBING")
print("=" * 75)

# 1. LOAD DATASET
df_ts = pd.read_csv(os.path.join(DATA_DIR, 'lplpo_bimonthly_timeseries.csv'))
df_pivot = pd.read_csv(os.path.join(DATA_DIR, 'lplpo_obat_pivot_pemakaian.csv'))
df_gran = pd.read_csv(os.path.join(DATA_DIR, 'lplpo_granular_records.csv'))
df_dis = pd.read_csv(os.path.join(DATA_DIR, 'penyakit_clean_records.csv'))

# ==============================================================================
# BAGIAN 1: ANALISIS TREN BULANAN (2024) VS DWIBULANAN
# ==============================================================================
print("\n[1] Mengekstrak Tren Bulanan (12 Bulan 2024) vs Dwibulanan...")

# Filter data tahun 2024 yang memiliki 12 bulan
df_2024_monthly = df_gran[df_gran['tahun'] == 2024].copy()
pivot_2024_monthly = df_2024_monthly.pivot_table(
    index=['nama_obat', 'satuan'],
    columns='periode_bulan_id',
    values='pemakaian',
    aggfunc='sum'
).fillna(0).reset_index()

pivot_2024_monthly.to_csv(os.path.join(DATA_DIR, 'tren_bulanan_2024_obat.csv'), index=False)
print(f" -> Berhasil menyimpan tren bulanan 2024: {len(pivot_2024_monthly)} obat ke 'tren_bulanan_2024_obat.csv'")

# ==============================================================================
# BAGIAN 2: PERAMALAN 4 PENYAKIT TERBANYAK (ISPA, HIPERTENSI, DISPEPSIA, DIABETES)
# ==============================================================================
print("\n[2] Menjalankan Peramalan untuk Minimal 4 Penyakit Terbanyak...")

# Identifikasi 4 Penyakit Terbanyak
top4_icd = [
    ('J00', 'ISPA / Acute Nasopharyngitis'),
    ('I10', 'Hipertensi Primer / Essential Hypertension'),
    ('K30', 'Dispepsia / Dyspepsia (Gangguan Lambung)'),
    ('E11', 'Diabetes Melitus Tipe 2 / Non-Insulin-Dependent Diabetes')
]

# Susun deret waktu 22 periode untuk masing-masing penyakit
disease_pivot = df_dis.pivot_table(
    index=['kode_icd', 'nama_penyakit'],
    columns='periode_bimonthly_id',
    values='total_pasien',
    aggfunc='sum'
).fillna(0)

period_cols = [c for c in df_pivot.columns if c.startswith(('2023-', '2024-', '2025-', '2026-')) and c.endswith(tuple(f'P{i}' for i in range(1, 7)))]

disease_forecast_records = []

for code, label in top4_icd:
    row = df_dis[df_dis['kode_icd'] == code].groupby('periode_bimonthly_id')['total_pasien'].sum()
    series = [row.get(p, 0.0) for p in period_cols]
    
    # 1. Simple Moving Average k=2
    sma2_preds = [None]*len(series)
    for t in range(2, len(series)):
        sma2_preds[t] = np.mean(series[t-2:t])
    next_sma2 = np.mean(series[-2:])
    
    # 2. Simple Moving Average k=3
    sma3_preds = [None]*len(series)
    for t in range(3, len(series)):
        sma3_preds[t] = np.mean(series[t-3:t])
    next_sma3 = np.mean(series[-3:])
    
    # 3. Weighted Moving Average k=3 (Bobot 1, 2, 3)
    w = np.array([1, 2, 3])
    wma3_preds = [None]*len(series)
    for t in range(3, len(series)):
        wma3_preds[t] = np.dot(series[t-3:t], w) / w.sum()
    next_wma3 = np.dot(series[-3:], w) / w.sum()
    
    # Evaluasi metrik untuk WMA-3 (t >= 3)
    act = np.array(series[3:])
    pred_wma = np.array(wma3_preds[3:])
    pred_sma2 = np.array(sma2_preds[3:])
    pred_sma3 = np.array(sma3_preds[3:])
    
    valid = act > 0
    mape_wma = np.mean(np.abs((act[valid] - pred_wma[valid]) / act[valid])) * 100 if np.sum(valid) > 0 else 0
    mae_wma = np.mean(np.abs(act - pred_wma))
    rmse_wma = np.sqrt(np.mean((act - pred_wma)**2))
    
    mape_sma2 = np.mean(np.abs((act[valid] - pred_sma2[valid]) / act[valid])) * 100 if np.sum(valid) > 0 else 0
    mape_sma3 = np.mean(np.abs((act[valid] - pred_sma3[valid]) / act[valid])) * 100 if np.sum(valid) > 0 else 0
    
    # Best Model
    best_m = "WMA (k=3)" if mape_wma <= min(mape_sma2, mape_sma3) else ("SMA (k=2)" if mape_sma2 <= mape_sma3 else "SMA (k=3)")
    best_next = next_wma3 if best_m == "WMA (k=3)" else (next_sma2 if best_m == "SMA (k=2)" else next_sma3)
    
    disease_forecast_records.append({
        'kode_icd': code,
        'nama_penyakit_alias': label,
        'total_kasus_historis': sum(series),
        'mean_kasus_bimonthly': np.mean(series),
        'mape_sma2': round(mape_sma2, 2),
        'mape_sma3': round(mape_sma3, 2),
        'mape_wma3': round(mape_wma, 2),
        'mae_wma3': round(mae_wma, 2),
        'rmse_wma3': round(rmse_wma, 2),
        'best_model': best_m,
        'prediksi_kasus_2026_P5': round(best_next, 1),
        'prediksi_wma3_P5': round(next_wma3, 1),
        'prediksi_sma2_P5': round(next_sma2, 1),
        'prediksi_sma3_P5': round(next_sma3, 1)
    })

df_dis_forecast = pd.DataFrame(disease_forecast_records)
df_dis_forecast.to_csv(os.path.join(DATA_DIR, 'prediksi_penyakit_top4.csv'), index=False)
print("Hasil Peramalan 4 Penyakit Terbanyak:")
print(df_dis_forecast[['kode_icd', 'nama_penyakit_alias', 'mape_wma3', 'best_model', 'prediksi_kasus_2026_P5']])

# ==============================================================================
# BAGIAN 3: ANALISIS KORELASI KLINIS PENYAKIT VS OBAT
# ==============================================================================
print("\n[3] Menghitung Korelasi Penyakit vs Obat Terkait...")

# Pasangan Penyakit - Obat Utama
disease_medicine_pairs = [
    ('J00', 'ISPA / Nasopharyngitis', 'Parasetamol tablet 500 mg', 'Parasetamol.*500', 'Analgesik/Antipiretik Panas & Nyeri'),
    ('J00', 'ISPA / Nasopharyngitis', 'Amoksisilin kaplet 500 mg', 'Amoksisilin.*500', 'Antibiotik Lini Pertama ISPA Bakterial'),
    ('J00', 'ISPA / Nasopharyngitis', 'Ambroksol tablet 30 mg', 'Ambroksol.*30', 'Mukolitik/Ekspektoran Batuk Berdahak'),
    ('I10', 'Hipertensi Primer', 'Amlodipin tablet 5 mg', 'Amlodipin.*5.*mg', 'Antihipertensi CCB Lini Pertama (5mg)'),
    ('I10', 'Hipertensi Primer', 'Amlodipin 10 mg tablet', 'Amlodipin.*10.*mg', 'Antihipertensi CCB Dosis Tinggi (10mg)'),
    ('I10', 'Hipertensi Primer', 'Kaptopril tablet 25 mg', 'Kaptopril.*25', 'Antihipertensi ACE Inhibitor'),
    ('K30', 'Dispepsia / Maag', 'Antasida DOEN I tablet', 'Antasida.*tablet', 'Antasida Penetral Asam Lambung'),
    ('K30', 'Dispepsia / Maag', 'Ranitidine tablet 150 mg', 'Ranitidin.*150', 'H2 Receptor Blocker Penekan Asam'),
    ('E11', 'Diabetes Melitus Tipe 2', 'Metformin tablet 500 mg', 'Metformin.*500', 'Antidiabetes Oral Biguanid Utama'),
    ('E11', 'Diabetes Melitus Tipe 2', 'Glibenklamid tablet 5 mg', 'Glibenklamid.*5', 'Antidiabetes Oral Sulfonilurea')
]

correlation_records = []

for icd, dis_name, med_display, med_pattern, indikasi in disease_medicine_pairs:
    # Seri penyakit
    s_dis = df_dis[df_dis['kode_icd'] == icd].groupby('periode_bimonthly_id')['total_pasien'].sum()
    dis_series = np.array([s_dis.get(p, 0.0) for p in period_cols])
    
    # Seri obat
    row_med = df_pivot[df_pivot['nama_obat'].str.contains(med_pattern, case=False, regex=True, na=False)]
    if not row_med.empty:
        med_actual_name = row_med.iloc[0]['nama_obat']
        med_series = np.array([float(row_med.iloc[0][p]) for p in period_cols])
        
        # Pearson & Spearman
        if np.std(dis_series) > 0 and np.std(med_series) > 0:
            pearson_r = np.corrcoef(dis_series, med_series)[0, 1]
            dis_rank = pd.Series(dis_series).rank()
            med_rank = pd.Series(med_series).rank()
            spearman_rho = np.corrcoef(dis_rank, med_rank)[0, 1]
        else:
            pearson_r, spearman_rho = 0.0, 0.0
            
        # Rasio Konsumsi per Kasus Pasien (Dose Ratio)
        total_p = np.sum(dis_series)
        total_o = np.sum(med_series)
        ratio_dose = total_o / total_p if total_p > 0 else 0
        
        # Prediksi Obat Berdasarkan Proyeksi Kasus Penyakit
        pred_p = df_dis_forecast[df_dis_forecast['kode_icd'] == icd]['prediksi_kasus_2026_P5'].values[0]
        pred_obat_via_penyakit = round(pred_p * ratio_dose, 0)
        
        correlation_records.append({
            'kode_icd': icd,
            'nama_penyakit': dis_name,
            'nama_obat_standar': med_display,
            'nama_obat_lplpo': med_actual_name,
            'indikasi_klinis': indikasi,
            'pearson_r': round(pearson_r, 4),
            'spearman_rho': round(spearman_rho, 4),
            'rasio_dosis_per_pasien': round(ratio_dose, 2),
            'prediksi_kasus_P5': pred_p,
            'prediksi_kebutuhan_obat_P5': pred_obat_via_penyakit
        })

df_corr = pd.DataFrame(correlation_records)
df_corr.to_csv(os.path.join(DATA_DIR, 'korelasi_penyakit_obat_detail.csv'), index=False)
print("Hasil Analisis Korelasi & Prediksi Terpadu Penyakit-Obat:")
print(df_corr[['nama_penyakit', 'nama_obat_standar', 'pearson_r', 'rasio_dosis_per_pasien', 'prediksi_kebutuhan_obat_P5']])

# ==============================================================================
# BAGIAN 4: IMPLEMENTASI INDIKATOR MACD PADA PEMAKAIAN OBAT
# ==============================================================================
print("\n[4] Menghitung Indikator MACD (Moving Average Convergence Divergence)...")

def compute_macd(series, fast_period=2, slow_period=4, signal_period=2):
    """
    Menghitung MACD untuk deret waktu pemakaian obat dwibulanan:
    Karena data memiliki 22 periode (dwibulanan), parameter disesuaikan:
    Fast EMA = 2 periode (4 bulan), Slow EMA = 4 periode (8 bulan), Signal = 2 periode.
    """
    s = pd.Series(series)
    ema_fast = s.ewm(span=fast_period, adjust=False).mean()
    ema_slow = s.ewm(span=slow_period, adjust=False).mean()
    macd_line = ema_fast - ema_slow
    signal_line = macd_line.ewm(span=signal_period, adjust=False).mean()
    macd_hist = macd_line - signal_line
    return ema_fast, ema_slow, macd_line, signal_line, macd_hist

macd_summary = []
for idx, row in df_pivot.iterrows():
    med_name = row['nama_obat']
    series = [float(row[p]) for p in period_cols]
    if sum(series) > 500: # Hanya obat dengan aktivitas signifikan
        _, _, macd_line, signal_line, macd_hist = compute_macd(series)
        last_macd = macd_line.iloc[-1]
        last_sig = signal_line.iloc[-1]
        last_hist = macd_hist.iloc[-1]
        
        # Momentum Status
        if last_hist > 0 and last_macd > last_sig:
            status = "📈 Tren Meningkat Kuat (Bullish Momentum)"
        elif last_hist > 0:
            status = "↗️ Mulai Rebound / Pemulihan"
        elif last_hist < 0 and last_macd < last_sig:
            status = "📉 Tren Menurun (Bearish / Permintaan Melambat)"
        else:
            status = "➡️ Stabil / Netral"
            
        macd_summary.append({
            'nama_obat': med_name,
            'satuan': row['satuan'],
            'total_pemakaian': sum(series),
            'last_pemakaian_P4': series[-1],
            'macd_value': round(last_macd, 2),
            'signal_value': round(last_sig, 2),
            'histogram': round(last_hist, 2),
            'momentum_status': status
        })

df_macd = pd.DataFrame(macd_summary).sort_values(by='total_pemakaian', ascending=False)
df_macd.to_csv(os.path.join(DATA_DIR, 'macd_obat_analysis.csv'), index=False)
print(f" -> Berhasil menghitung MACD untuk {len(df_macd)} obat esensial.")
print("Contoh 5 Obat Teratas berdasarkan MACD:")
print(df_macd[['nama_obat', 'last_pemakaian_P4', 'macd_value', 'histogram', 'momentum_status']].head(5))

print("\n" + "=" * 75)
print("SELURUH PENGOLAHAN DATA SELESAI DENGAN SUKSES!")
print("=" * 75)
