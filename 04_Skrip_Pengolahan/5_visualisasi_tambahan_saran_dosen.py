#!/usr/bin/env python3
"""
04_Skrip_Pengolahan/5_visualisasi_tambahan_saran_dosen.py

Skrip ini menghasilkan 4 visualisasi tambahan beresolusi tinggi (300 DPI)
untuk mendukung implementasi rekomendasi dosen pembimbing:
1. 15_tren_bulanan_2024_vs_dwibulanan.png (Saran 1: Tren Mikro Bulanan vs Makro Dwibulanan)
2. 16_prediksi_penyakit_top4.png (Saran 3: Peramalan Kasus 4 Penyakit Terbanyak 2026-P5)
3. 17_analisis_macd_obat.png (Saran 2: Analisis Indikator Momentum MACD Konsumsi Obat)
4. 18_korelasi_morbiditas_dosis_obat.png (Saran 3: Rasio Dosis Klinis & Estimasi Morbiditas)
"""

import os
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DATA_DIR = os.path.join(BASE_DIR, '03_Data_Terproses')
VIZ_DIR = os.path.join(BASE_DIR, '05_Visualisasi_Grafik')
os.makedirs(VIZ_DIR, exist_ok=True)

# Styling visualisasi akademik standar IEEE / Scopus
plt.rcParams['font.sans-serif'] = 'DejaVu Sans'
plt.rcParams['axes.edgecolor'] = '#333333'
plt.rcParams['axes.linewidth'] = 0.9
plt.rcParams['grid.color'] = '#E2E8F0'
plt.rcParams['grid.linestyle'] = '--'
plt.rcParams['grid.alpha'] = 0.8

print("=" * 70)
print("MEMBUAT VISUALISASI GRAFIK TAMBAHAN REKOMENDASI DOSEN")
print("=" * 70)

# ==============================================================================
# 1. GAMBAR 15: TREN BULANAN 2024 VS DWIBULANAN (SARAN 1)
# ==============================================================================
print("\n[1] Membuat Gambar 15: Tren Bulanan 2024 vs Dwibulanan...")
df_monthly_2024 = pd.read_csv(os.path.join(DATA_DIR, 'tren_bulanan_2024_obat.csv'))
df_pivot = pd.read_csv(os.path.join(DATA_DIR, 'lplpo_obat_pivot_pemakaian.csv'))

# Ambil Parasetamol 500 mg dan Metformin 500 mg
pct_monthly = df_monthly_2024[df_monthly_2024['nama_obat'].str.contains('Parasetamol.*500', case=False, na=False)]
met_monthly = df_monthly_2024[df_monthly_2024['nama_obat'].str.contains('Metformin.*500', case=False, na=False)]

month_cols = [f'2024-{str(m).zfill(2)}' for m in range(1, 13)]
month_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']

fig, (ax1, ax2) = plt.subplots(1, 2, figsize=(14, 5.5), dpi=300)

if not pct_monthly.empty and not met_monthly.empty:
    pct_vals = pct_monthly[month_cols].values[0]
    met_vals = met_monthly[month_cols].values[0]
    
    # Subplot 1: Tren Bulanan 2024 (Mikro)
    ax1.plot(month_labels, pct_vals, marker='o', linewidth=2.2, color='#1E40AF', label='Parasetamol 500 mg')
    ax1.plot(month_labels, met_vals, marker='s', linewidth=2.2, color='#0D9488', label='Metformin 500 mg')
    ax1.set_title("A. Tren Mikro Bulanan (Tahun 2024 / 12 Bulan)", fontsize=12, fontweight='bold', pad=12, color='#1E293B')
    ax1.set_xlabel("Bulan Kalender 2024", fontsize=10, fontweight='bold')
    ax1.set_ylabel("Pemakaian Riil (Butir)", fontsize=10, fontweight='bold')
    ax1.grid(True)
    ax1.legend(frameon=True, facecolor='#F8FAFC')
    
    # Anotasi titik lonjakan
    max_pct_idx = np.argmax(pct_vals)
    ax1.annotate(f"Puncak: {int(pct_vals[max_pct_idx]):,} tab", 
                 (month_labels[max_pct_idx], pct_vals[max_pct_idx]),
                 textcoords="offset points", xytext=(-20, 12),
                 fontweight='bold', color='#1E40AF',
                 arrowprops=dict(arrowstyle="->", color='#1E40AF'))

# Subplot 2: Agregasi Dwibulanan 2024 (P1 s/d P6)
pct_bimonthly = [pct_vals[i] + pct_vals[i+1] for i in range(0, 12, 2)]
met_bimonthly = [met_vals[i] + met_vals[i+1] for i in range(0, 12, 2)]
bi_labels = ['2024-P1\n(Jan-Feb)', '2024-P2\n(Mar-Apr)', '2024-P3\n(Mei-Jun)', 
             '2024-P4\n(Jul-Agu)', '2024-P5\n(Sep-Okt)', '2024-P6\n(Nov-Des)']

x = np.arange(len(bi_labels))
width = 0.35

ax2.bar(x - width/2, pct_bimonthly, width, label='Parasetamol 500 mg', color='#3B82F6', edgecolor='#1D4ED8')
ax2.bar(x + width/2, met_bimonthly, width, label='Metformin 500 mg', color='#14B8A6', edgecolor='#0F766E')
ax2.set_title("B. Tren Makro Dwibulanan Terpadu (2024-P1 s/d P6)", fontsize=12, fontweight='bold', pad=12, color='#1E293B')
ax2.set_xlabel("Periode LPLPO Dwibulanan Resmi", fontsize=10, fontweight='bold')
ax2.set_ylabel("Total Pemakaian (Butir)", fontsize=10, fontweight='bold')
ax2.set_xticks(x)
ax2.set_xticklabels(bi_labels, fontsize=9)
ax2.grid(True, axis='y')
ax2.legend(frameon=True, facecolor='#F8FAFC')

plt.suptitle("Perbandingan Granularitas Deret Waktu: Tren 1-Bulanan vs 2-Bulanan (Tahun 2024)", 
             fontsize=14, fontweight='bold', y=0.98, color='#0F172A')
plt.tight_layout()
fig_path_15 = os.path.join(VIZ_DIR, '15_tren_bulanan_2024_vs_dwibulanan.png')
plt.savefig(fig_path_15, bbox_inches='tight')
plt.close()
print(f" -> Gambar 15 berhasil disimpan: {fig_path_15}")

# ==============================================================================
# 2. GAMBAR 16: PREDIKSI 4 PENYAKIT TERBANYAK (SARAN 3)
# ==============================================================================
print("\n[2] Membuat Gambar 16: Prediksi 4 Penyakit Terbanyak...")
df_pred_penyakit = pd.read_csv(os.path.join(DATA_DIR, 'prediksi_penyakit_top4.csv'))
df_clean_penyakit = pd.read_csv(os.path.join(DATA_DIR, 'penyakit_clean_records.csv'))

period_cols = [c for c in df_pivot.columns if c.startswith(('2023-', '2024-', '2025-', '2026-')) and c.endswith(tuple(f'P{i}' for i in range(1, 7)))]

fig, axes = plt.subplots(2, 2, figsize=(15, 10), dpi=300)
axes = axes.flatten()

colors = ['#EF4444', '#3B82F6', '#F59E0B', '#10B981']

for idx, row in df_pred_penyakit.iterrows():
    ax = axes[idx]
    code = row['kode_icd']
    name = row['nama_penyakit_alias']
    pred_val = row['prediksi_kasus_2026_P5']
    mape = row['mape_wma3']
    
    s_dis = df_clean_penyakit[df_clean_penyakit['kode_icd'] == code].groupby('periode_bimonthly_id')['total_pasien'].sum()
    y_vals = [s_dis.get(p, 0.0) for p in period_cols]
    
    # Plot historis
    ax.plot(range(len(period_cols)), y_vals, marker='o', color='#475569', linewidth=2, label='Data Aktual Historis')
    
    # Plot titik prediksi masa depan
    future_x = len(period_cols)
    ax.plot([future_x - 1, future_x], [y_vals[-1], pred_val], '--', color=colors[idx], linewidth=2.2)
    ax.scatter([future_x], [pred_val], color=colors[idx], s=120, zorder=5, label=f'Prediksi 2026-P5: {pred_val:.1f} Kasus')
    
    ax.set_title(f"{name} (MAPE: {mape:.2f}%)", fontsize=11, fontweight='bold', color='#1E293B')
    ax.set_xlabel("Periode Kronologis (2023-P1 s/d 2026-P5)", fontsize=9)
    ax.set_ylabel("Jumlah Pasien (Jiwa)", fontsize=9)
    
    # Set x-ticks hanya untuk beberapa periode agar rapi
    tick_pos = [0, 5, 11, 17, 21, 22]
    tick_lbl = ['23-P1', '23-P6', '24-P6', '25-P6', '26-P4', '26-P5\n(Target)']
    ax.set_xticks(tick_pos)
    ax.set_xticklabels(tick_lbl, fontsize=8)
    ax.grid(True)
    ax.legend(loc='upper right', fontsize=8.5, frameon=True, facecolor='#F8FAFC')

plt.suptitle("Kurva Deret Waktu Historis dan Proyeksi Morbiditas 4 Penyakit Terbanyak (Periode 2026-P5)", 
             fontsize=14, fontweight='bold', y=0.99, color='#0F172A')
plt.tight_layout()
fig_path_16 = os.path.join(VIZ_DIR, '16_prediksi_penyakit_top4.png')
plt.savefig(fig_path_16, bbox_inches='tight')
plt.close()
print(f" -> Gambar 16 berhasil disimpan: {fig_path_16}")

# ==============================================================================
# 3. GAMBAR 17: ANALISIS INDIKATOR MACD OBAT (SARAN 2)
# ==============================================================================
print("\n[3] Membuat Gambar 17: Analisis Indikator MACD Obat...")
# Ambil contoh Parasetamol 500 mg dan Amlodipin 10 mg
fig, (ax1, ax2) = plt.subplots(2, 1, figsize=(14, 8), dpi=300, sharex=True)

# Hitung EMA Fast (k=2) dan EMA Slow (k=4) untuk Parasetamol 500 mg
pct_series = pd.Series([float(df_pivot[df_pivot['nama_obat'].str.contains('Parasetamol.*500', case=False, na=False)][p].values[0]) for p in period_cols])
ema_fast = pct_series.ewm(span=2, adjust=False).mean()
ema_slow = pct_series.ewm(span=4, adjust=False).mean()
macd_line = ema_fast - ema_slow
signal_line = macd_line.ewm(span=2, adjust=False).mean()
histogram = macd_line - signal_line

x_indices = range(len(period_cols))

# Subplot 1: Konsumsi Aktual & EMA
ax1.plot(x_indices, pct_series, label='Konsumsi Aktual Parasetamol 500 mg', color='#0F172A', linewidth=2, marker='o')
ax1.plot(x_indices, ema_fast, label='Fast EMA (span=2)', color='#2563EB', linestyle='--', linewidth=1.8)
ax1.plot(x_indices, ema_slow, label='Slow EMA (span=4)', color='#EA580C', linestyle=':', linewidth=1.8)
ax1.set_title("A. Kurva Pemakaian Riil dan Garis Tren Eksponensial (Fast & Slow EMA)", fontsize=11, fontweight='bold', color='#1E293B')
ax1.set_ylabel("Jumlah Butir", fontsize=10, fontweight='bold')
ax1.grid(True)
ax1.legend(loc='upper right', frameon=True, facecolor='#F8FAFC')

# Subplot 2: MACD Line, Signal Line, & Histogram
ax2.plot(x_indices, macd_line, label='MACD Line (Fast - Slow)', color='#2563EB', linewidth=2)
ax2.plot(x_indices, signal_line, label='Signal Line (EMA MACD)', color='#DC2626', linewidth=1.8, linestyle='--')

# Histogram warna hijau untuk positif (Bullish), merah untuk negatif (Bearish)
hist_colors = ['#10B981' if val >= 0 else '#EF4444' for val in histogram]
ax2.bar(x_indices, histogram, color=hist_colors, alpha=0.6, width=0.6, label='MACD Histogram (Momentum Divergence)')
ax2.axhline(0, color='#64748B', linewidth=1)
ax2.set_title("B. Indikator MACD dan Histogram Momentum Pemakaian Parasetamol 500 mg", fontsize=11, fontweight='bold', color='#1E293B')
ax2.set_xlabel("Periode Waktu Dwibulanan (2023-P1 s/d 2026-P4)", fontsize=10, fontweight='bold')
ax2.set_ylabel("Nilai Indikator", fontsize=10, fontweight='bold')
ax2.set_xticks(range(len(period_cols)))
ax2.set_xticklabels([p.replace('20', '') for p in period_cols], rotation=45, fontsize=8)
ax2.grid(True)
ax2.legend(loc='lower left', frameon=True, facecolor='#F8FAFC')

plt.suptitle("Analisis Momentum Moving Average Convergence Divergence (MACD) pada Pemakaian Parasetamol", 
             fontsize=14, fontweight='bold', y=0.98, color='#0F172A')
plt.tight_layout()
fig_path_17 = os.path.join(VIZ_DIR, '17_analisis_macd_obat.png')
plt.savefig(fig_path_17, bbox_inches='tight')
plt.close()
print(f" -> Gambar 17 berhasil disimpan: {fig_path_17}")

# ==============================================================================
# 4. GAMBAR 18: KORELASI MORBIDITAS & RASIO DOSIS KLINIS (SARAN 3)
# ==============================================================================
print("\n[4] Membuat Gambar 18: Korelasi Morbiditas & Rasio Dosis Klinis...")
df_korelasi_detail = pd.read_csv(os.path.join(DATA_DIR, 'korelasi_penyakit_obat_detail.csv'))

fig, (ax1, ax2) = plt.subplots(1, 2, figsize=(15, 6), dpi=300)

# Subplot 1: Nilai Korelasi Pearson vs Spearman
y_pos = np.arange(len(df_korelasi_detail))
bar_height = 0.35

ax1.barh(y_pos - bar_height/2, df_korelasi_detail['pearson_r'], bar_height, label='Pearson r (Linear)', color='#3B82F6', edgecolor='#1D4ED8')
ax1.barh(y_pos + bar_height/2, df_korelasi_detail['spearman_rho'], bar_height, label='Spearman rho (Rank)', color='#10B981', edgecolor='#047857')

labels = [f"{r['kode_icd']} vs {r['nama_obat_standar'].split()[0]}" for _, r in df_korelasi_detail.iterrows()]
ax1.set_yticks(y_pos)
ax1.set_yticklabels(labels, fontsize=9)
ax1.set_xlabel("Koefisien Korelasi", fontsize=10, fontweight='bold')
ax1.set_title("A. Derajat Korelasi Penyakit vs Obat Terkait", fontsize=12, fontweight='bold', pad=12, color='#1E293B')
ax1.axvline(0, color='#64748B', linewidth=0.8)
ax1.grid(True, axis='x')
ax1.legend(loc='lower right', frameon=True, facecolor='#F8FAFC')

# Subplot 2: Rasio Dosis per Pasien (Butir per Jiwa)
ax2.barh(y_pos, df_korelasi_detail['rasio_dosis_per_pasien'], color='#F59E0B', edgecolor='#B45309', height=0.6)
ax2.set_yticks(y_pos)
ax2.set_yticklabels([''] * len(labels)) # Sembunyikan label y agar rapi
ax2.set_xlabel("Rasio Dosis Rata-rata (Butir / Pasien)", fontsize=10, fontweight='bold')
ax2.set_title("B. Rasio Resep Klinis per Kasus Terdiagnosis", fontsize=12, fontweight='bold', pad=12, color='#1E293B')
ax2.grid(True, axis='x')

# Tambahkan label angka di samping batang
for i, v in enumerate(df_korelasi_detail['rasio_dosis_per_pasien']):
    ax2.text(v + 0.5, i, f"{v:.1f} tab/psn", va='center', fontsize=8.5, fontweight='bold', color='#78350F')

plt.suptitle("Analisis Terpadu Korelasi Klinis dan Rasio Dosis Konsumsi Obat Berbasis Morbiditas", 
             fontsize=14, fontweight='bold', y=0.98, color='#0F172A')
plt.tight_layout()
fig_path_18 = os.path.join(VIZ_DIR, '18_korelasi_morbiditas_dosis_obat.png')
plt.savefig(fig_path_18, bbox_inches='tight')
plt.close()
print(f" -> Gambar 18 berhasil disimpan: {fig_path_18}")

print("\n" + "=" * 70)
print("SELURUH 4 VISUALISASI TAMBAHAN SELESAI DIBUAT DENGAN SUKSES!")
print("=" * 70)
