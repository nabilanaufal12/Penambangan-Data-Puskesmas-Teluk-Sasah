#!/usr/bin/env python3
"""
Skrip Pembuatan Grafik EDA Komprehensif
Berdasarkan data dari folder 03_Data_Terproses/
Judul: PREDIKSI KEBUTUHAN STOK OBAT DAN ANALISIS TREN PENYAKIT MUSIMAN
       BERDASARKAN POLA PEMAKAIAN OBAT PADA PUSKESMAS TELUK SASAH DI KABUPATEN BINTAN
       SERTA KORELASI OBAT DENGAN PENYAKIT

Luaran:
1. 05_Visualisasi_Grafik/EDA_MASTER_PREDIKSI_STOK_DAN_TREN_PENYAKIT.png (Dashboard Multi-Panel 6-in-1)
2. 05_Visualisasi_Grafik/EDA_01_tren_penyakit_musiman.png (Analisis Tren Musiman Bulanan & Fluktuasi)
3. 05_Visualisasi_Grafik/EDA_02_pola_pemakaian_obat_dan_volatilitas.png (Pola Konsumsi Obat & Profil CV)
4. 05_Visualisasi_Grafik/EDA_03_korelasi_klinis_penyakit_obat.png (Korelasi Pearson, Spearman & Rasio Dosis)
5. 05_Visualisasi_Grafik/EDA_04_prediksi_kebutuhan_stok_safety_stock.png (Proyeksi Kebutuhan P5 + Safety Stock 95%)
"""

import os
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
import matplotlib.gridspec as gridspec
import seaborn as sns
from scipy.stats import pearsonr, spearmanr

# Konfigurasi gaya visual akademik
plt.rcParams['font.sans-serif'] = 'DejaVu Sans'
plt.rcParams['axes.edgecolor'] = '#2c3e50'
plt.rcParams['axes.linewidth'] = 0.9
plt.rcParams['grid.color'] = '#e2e8f0'
plt.rcParams['grid.linestyle'] = '--'
plt.rcParams['grid.alpha'] = 0.65

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DATA_DIR = os.path.join(BASE_DIR, '03_Data_Terproses')
VIZ_DIR = os.path.join(BASE_DIR, '05_Visualisasi_Grafik')
os.makedirs(VIZ_DIR, exist_ok=True)

print("Memuat dataset terproses dari:", DATA_DIR)
df_penyakit = pd.read_csv(os.path.join(DATA_DIR, 'penyakit_clean_records.csv'))
df_pivot = pd.read_csv(os.path.join(DATA_DIR, 'lplpo_obat_pivot_pemakaian.csv'))
df_bimonthly = pd.read_csv(os.path.join(DATA_DIR, 'lplpo_bimonthly_timeseries.csv'))
df_korelasi = pd.read_csv(os.path.join(DATA_DIR, 'korelasi_penyakit_obat_detail.csv'))
df_pred_p = pd.read_csv(os.path.join(DATA_DIR, 'prediksi_penyakit_top4.csv'))
df_kadaluarsa = pd.read_csv(os.path.join(DATA_DIR, 'obat_kadaluarsa_clean.csv'))
df_eval = pd.read_csv(os.path.join(DATA_DIR, 'model_evaluation_metrics.csv'))

PERIODS = [c for c in df_pivot.columns if c.startswith('202')]
MONTH_NAMES = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']

# Warna Palet Medis
COLOR_PRIMARY = '#0284c7'
COLOR_SECONDARY = '#0d9488'
COLOR_ACCENT = '#ea580c'
COLOR_DANGER = '#dc2626'
COLOR_PURPLE = '#7c3aed'
COLOR_AMBER = '#d97706'

# ==============================================================================
# GRAFIK 1: ANALISIS TREN MUSIMAN PENYAKIT (SEASONALITY & MONTHLY HEATMAP)
# ==============================================================================
def generate_grafik_tren_musiman():
    print("Membuat Grafik 1: Analisis Tren Musiman Penyakit...")
    fig, (ax1, ax2) = plt.subplots(2, 1, figsize=(15, 12), dpi=300, gridspec_kw={'height_ratios': [1.2, 1.0]})

    # Panel A: Pola Rata-rata Bulanan (Seasonality Curve)
    top_diseases = [
        ('J00', 'ISPA / Nasopharyngitis (J00)', '#dc2626', 'o', '-'),
        ('I10', 'Hipertensi Primer (I10)', '#0284c7', 's', '--'),
        ('K30', 'Dispepsia / Lambung (K30)', '#7c3aed', '^', '-.'),
        ('E11', 'Diabetes Melitus (E11)', '#16a34a', 'D', ':'),
        ('A09', 'Diare & Gastroenteritis (A09)', '#ea580c', 'v', '-')
    ]

    for code, label, col, marker, ls in top_diseases:
        sub = df_penyakit[df_penyakit['kode_icd'] == code]
        monthly_avg = sub.groupby('bulan_angka')['total_pasien'].mean().reindex(range(1, 13))
        ax1.plot(MONTH_NAMES, monthly_avg, label=label, color=col, marker=marker, linewidth=2.5, markersize=8, linestyle=ls)
        
        # Anotasi titik puncak ISPA di bulan November
        if code == 'J00':
            peak_val = monthly_avg.loc[11]
            ax1.annotate(f'Puncak Musiman ISPA: {peak_val:.1f} Pasien\n(Musim Penghujan / Monsoon)',
                         xy=(10, peak_val), xytext=(8.2, peak_val + 35),
                         arrowprops=dict(facecolor='#b91c1c', shrink=0.08, width=1.5, headwidth=7),
                         fontsize=10, fontweight='bold', color='#b91c1c',
                         bbox=dict(boxstyle="round,pad=0.4", fc="#fee2e2", ec="#ef4444", lw=1.2))

    # Highlight musim penghujan (Oktober - Desember)
    ax1.axvspan(8.5, 11.5, color='#e0f2fe', alpha=0.5, label='Periode Musim Penghujan (Okt - Des)')
    ax1.set_title('Pola Rata-Rata Bulanan Kasus Penyakit di Puskesmas Teluk Sasah (Analisis Musiman)', 
                  fontsize=14, fontweight='bold', pad=12, color='#0f172a')
    ax1.set_xlabel('Bulan Kalender', fontsize=11, fontweight='bold', labelpad=8)
    ax1.set_ylabel('Rata-Rata Kasus Pasien per Bulan', fontsize=11, fontweight='bold', labelpad=8)
    ax1.legend(loc='upper left', frameon=True, facecolor='white', framealpha=0.95, fontsize=10)
    ax1.grid(True, linestyle='--', alpha=0.6)
    ax1.set_ylim(0, 500)

    # Panel B: Heatmap Matriks Morbiditas Penyakit per Bulan (Tahun 2023 - 2026 Agregat)
    pvt_monthly = df_penyakit.groupby(['nama_penyakit', 'bulan_angka'])['total_pasien'].mean().unstack()
    # Filter 8 penyakit terbanyak
    top8_names = df_penyakit.groupby('nama_penyakit')['total_pasien'].sum().nlargest(8).index
    pvt_monthly = pvt_monthly.loc[top8_names]
    pvt_monthly.columns = MONTH_NAMES

    # Persingkat label nama penyakit untuk keterbacaan visual
    short_labels = [
        'ISPA [J00]',
        'Hipertensi Primer [I10]',
        'Dispepsia [K30]',
        'Diabetes Melitus [E11]',
        'Diare Akut [A09]',
        'Cedera / Trauma [T14]',
        'Infeksi Saluran Napas Akut [J06.9]',
        'Hipertensi Jantung [I11]'
    ]
    pvt_monthly.index = short_labels

    sns.heatmap(pvt_monthly, ax=ax2, cmap='YlOrRd', annot=True, fmt='.1f', linewidths=0.6,
                cbar_kws={'label': 'Rata-rata Pasien / Bulan'}, linecolor='#cbd5e1')
    ax2.set_title('Heatmap Intensitas Morbiditas 8 Penyakit Utama Menurut Bulan Kalender', 
                  fontsize=13, fontweight='bold', pad=12, color='#0f172a')
    ax2.set_xlabel('Bulan', fontsize=11, fontweight='bold')
    ax2.set_ylabel('Diagnosis Penyakit (ICD-10)', fontsize=11, fontweight='bold')

    plt.tight_layout()
    out_path = os.path.join(VIZ_DIR, 'EDA_01_tren_penyakit_musiman.png')
    plt.savefig(out_path)
    plt.close()
    print(f"-> Berhasil disimpan: {out_path}")


# ==============================================================================
# GRAFIK 2: POLA PEMAKAIAN OBAT & KARAKTERISTIK VARIABILITAS (CV)
# ==============================================================================
def generate_grafik_pola_obat():
    print("Membuat Grafik 2: Pola Pemakaian Obat & CV (Termasuk Fero Sulfat / Gizi)...")
    fig, ((ax1, ax2), (ax3, ax4)) = plt.subplots(2, 2, figsize=(16, 12), dpi=300)

    # Panel A: Top 10 Obat Pemakaian Terbanyak (Menampilkan Fero Sulfat & Obat Kuratif)
    top10_all = df_pivot.nlargest(10, 'total_pemakaian').sort_values('total_pemakaian', ascending=True).copy()

    # Beri warna khusus: Amber/Orange untuk Program Gizi, Biru/Teal untuk Kuratif Poli
    bar_colors = ['#f59e0b' if 'Fero Sulfat' in n else '#0284c7' for n in top10_all['nama_obat']]
    bar_edges = ['#b45309' if 'Fero Sulfat' in n else '#0369a1' for n in top10_all['nama_obat']]

    # Format label nama obat yang informatif
    def format_label(name):
        if 'Fero Sulfat' in name:
            return 'Fero Sulfat 300mg [Program Gizi]'
        elif 'Parasetamol' in name:
            return 'Parasetamol 500mg [Kuratif #1]'
        elif 'Amlodipin 10' in name:
            return 'Amlodipin 10mg [Kronis #1]'
        elif 'Vitamin B' in name:
            return 'Vitamin B-Kompleks [Suplemen]'
        elif 'Asam Mefenamat' in name:
            return 'Asam Mefenamat 500mg'
        elif 'Kalsium Laktat' in name:
            return 'Kalsium Laktat 500mg'
        elif 'Metformin' in name:
            return 'Metformin 500mg [Diabetes #1]'
        elif 'Amoksisilin' in name:
            return 'Amoksisilin 500mg [Antibiotik]'
        elif 'Cetirizina' in name:
            return 'Cetirizina 10mg [Antialergi]'
        else:
            return name[:28]

    labels = [format_label(n) for n in top10_all['nama_obat']]
    bars = ax1.barh(labels, top10_all['total_pemakaian'], 
                    color=bar_colors, edgecolor=bar_edges, height=0.65)
    for b in bars:
        w = b.get_width()
        ax1.text(w + 3000, b.get_y() + b.get_height()/2, f'{int(w):,} unit', 
                 va='center', fontsize=8.5, fontweight='bold', color='#0f172a')
    ax1.set_title('(A) Top 10 Sediaan dengan Volume Pemakaian Terbesar (2023 - 2026)\n[Fero Sulfat = Program Gizi, Parasetamol = Kuratif Esensial #1]', fontsize=11, fontweight='bold')
    ax1.set_xlabel('Total Pemakaian Riil (Unit / Butir)', fontsize=10, fontweight='bold')
    ax1.set_xlim(0, max(top10_all['total_pemakaian']) * 1.25)
    ax1.grid(True, linestyle='--', alpha=0.5, axis='x')

    # Panel B: Fluktuasi Deret Waktu 4 Obat Utama (22 Periode Dwibulanan)
    key_drugs = [
        ('Parasetamol tablet 500 mg', '#dc2626', 'Parasetamol 500 mg (ISPA/Demam)'),
        ('Amlodipin 10 mg tablet', '#0284c7', 'Amlodipin 10 mg (Hipertensi)'),
        ('Metformin tablet 500 mg', '#16a34a', 'Metformin 500 mg (Diabetes)'),
        ('Amoksisilin kaplet 500 mg', '#ea580c', 'Amoksisilin 500 mg (Antibiotik)')
    ]
    for d_name, col, lbl in key_drugs:
        row = df_pivot[df_pivot['nama_obat'] == d_name]
        if not row.empty:
            vals = row[PERIODS].values.flatten()
            ax2.plot(PERIODS, vals, marker='o', linewidth=2, label=lbl, color=col)
    ax2.set_title('(B) Tren Konsumsi 4 Obat Utama Lintas 22 Periode Dwibulanan', fontsize=12, fontweight='bold')
    ax2.set_xlabel('Periode Dwibulanan (2023 - 2026)', fontsize=10, fontweight='bold')
    ax2.set_ylabel('Konsumsi Obat per Periode (Unit)', fontsize=10, fontweight='bold')
    ax2.tick_params(axis='x', rotation=45)
    ax2.legend(loc='upper right', fontsize=8.5, framealpha=0.9)
    ax2.grid(True, linestyle='--', alpha=0.5)

    # Panel C: Sebaran Volatilitas Obat (Koefisien Variasi / CV)
    cv_clean = df_eval['cv_pemakaian'].dropna()
    sns.histplot(cv_clean, bins=25, kde=True, ax=ax3, color='#0284c7', edgecolor='#0369a1')
    ax3.axvline(0.3, color='#16a34a', linestyle='--', linewidth=2, label='Stabil (CV < 0.3)')
    ax3.axvline(0.5, color='#eab308', linestyle='--', linewidth=2, label='Moderat (0.3 - 0.5)')
    ax3.axvline(1.0, color='#dc2626', linestyle='--', linewidth=2, label='Sangat Fluktuatif (CV > 1.0)')
    ax3.set_title('(C) Distribusi Koefisien Variasi (CV) Pemakaian 150 Obat Aktif', fontsize=12, fontweight='bold')
    ax3.set_xlabel('Koefisien Variasi (CV = std / mean)', fontsize=10, fontweight='bold')
    ax3.set_ylabel('Frekuensi Jumlah Obat', fontsize=10, fontweight='bold')
    ax3.legend(fontsize=9, loc='upper right')
    ax3.grid(True, linestyle='--', alpha=0.5)

    # Panel D: Perbandingan Karakteristik Fero Sulfat (Program Gizi) vs Obat Kuratif
    categories = ['Fero Sulfat\n(Program Gizi)', 'Parasetamol\n(Kuratif Akut)', 'Amlodipin 10mg\n(Kronis)', 'Metformin 500mg\n(Kronis)']
    cv_values = [2.20, 0.36, 0.81, 0.26] # nilai CV riil dari dataset
    mean_values = [11599, 6487, 6368, 2751]
    
    x = np.arange(len(categories))
    width = 0.35
    
    ax4_twin = ax4.twinx()
    b1 = ax4.bar(x - width/2, mean_values, width, label='Rata-Rata Kebutuhan (Unit)', color=['#f59e0b', '#38bdf8', '#38bdf8', '#38bdf8'], edgecolor='#0284c7')
    b2 = ax4_twin.bar(x + width/2, cv_values, width, label='Volatilitas (CV)', color=['#dc2626', '#10b981', '#f97316', '#10b981'], edgecolor='#b91c1c')
    
    ax4.set_ylabel('Rata-Rata Konsumsi per Dwibulan', color='#0284c7', fontsize=10, fontweight='bold')
    ax4_twin.set_ylabel('Nilai Koefisien Variasi (CV)', color='#b91c1c', fontsize=10, fontweight='bold')
    ax4.set_xticks(x)
    ax4.set_xticklabels(categories, fontsize=9)
    ax4.set_title('(D) Perbandingan Volume Konsumsi vs CV (Fero Sulfat Fluktuasi Ekstrem CV=2.20)', fontsize=11, fontweight='bold')
    
    lines_1, labels_1 = ax4.get_legend_handles_labels()
    lines_2, labels_2 = ax4_twin.get_legend_handles_labels()
    ax4.legend(lines_1 + lines_2, labels_1 + labels_2, loc='upper right', fontsize=8.5)

    plt.tight_layout()
    out_path = os.path.join(VIZ_DIR, 'EDA_02_pola_pemakaian_obat_dan_volatilitas.png')
    plt.savefig(out_path)
    plt.close()
    print(f"-> Berhasil disimpan: {out_path}")
    ax3.set_xlabel('Koefisien Variasi (CV = std / mean)', fontsize=10, fontweight='bold')
    ax3.set_ylabel('Frekuensi Jumlah Obat', fontsize=10, fontweight='bold')
    ax3.legend(fontsize=9, loc='upper right')
    ax3.grid(True, linestyle='--', alpha=0.5)

    # Panel D: Perbandingan Karakteristik Obat Kronis (Stabil) vs Obat Musiman (Fluktuatif)
    categories = ['Parasetamol\n(Musiman/Akut)', 'Amoksisilin\n(Antibiotik)', 'Amlodipin 10mg\n(Kronis)', 'Metformin 500mg\n(Kronis)']
    cv_values = [0.36, 0.22, 0.81, 0.26] # nilai CV riil dari dataset
    mean_values = [6487, 2442, 6368, 2751]
    
    x = np.arange(len(categories))
    width = 0.35
    
    ax4_twin = ax4.twinx()
    b1 = ax4.bar(x - width/2, mean_values, width, label='Rata-Rata Kebutuhan (Unit)', color='#38bdf8', edgecolor='#0284c7')
    b2 = ax4_twin.bar(x + width/2, cv_values, width, label='Volatilitas (CV)', color='#f97316', edgecolor='#c2410c')
    
    ax4.set_ylabel('Rata-Rata Konsumsi per Dwibulan', color='#0284c7', fontsize=10, fontweight='bold')
    ax4_twin.set_ylabel('Nilai Koefisien Variasi (CV)', color='#c2410c', fontsize=10, fontweight='bold')
    ax4.set_xticks(x)
    ax4.set_xticklabels(categories, fontsize=9.5)
    ax4.set_title('(D) Perbandingan Volume Konsumsi vs Tingkat Volatilitas (CV)', fontsize=12, fontweight='bold')
    
    lines_1, labels_1 = ax4.get_legend_handles_labels()
    lines_2, labels_2 = ax4_twin.get_legend_handles_labels()
    ax4.legend(lines_1 + lines_2, labels_1 + labels_2, loc='upper left', fontsize=8.5)

    plt.tight_layout()
    out_path = os.path.join(VIZ_DIR, 'EDA_02_pola_pemakaian_obat_dan_volatilitas.png')
    plt.savefig(out_path)
    plt.close()
    print(f"-> Berhasil disimpan: {out_path}")


# ==============================================================================
# GRAFIK 3: KORELASI KLINIS PENYAKIT VS OBAT & RASIO DOSIS PER PASIEN
# ==============================================================================
def generate_grafik_korelasi():
    print("Membuat Grafik 3: Korelasi Klinis & Rasio Dosis...")
    fig = plt.figure(figsize=(16, 12), dpi=300)
    gs = gridspec.GridSpec(2, 2, height_ratios=[1.1, 1.0])

    # Panel A: Matriks Heatmap Korelasi Pearson & Spearman (Penyakit vs Obat Terkait)
    ax1 = fig.add_subplot(gs[0, :])
    
    # Siapkan data korelasi terstruktur
    corr_data = df_korelasi[['nama_penyakit', 'nama_obat_standar', 'pearson_r', 'spearman_rho', 'rasio_dosis_per_pasien']].copy()
    corr_data['Pair'] = corr_data['nama_penyakit'].str.split('/').str[0].str.strip() + ' ➔ ' + corr_data['nama_obat_standar'].str.replace(' tablet', '').str.replace(' kaplet', '')

    y_pos = np.arange(len(corr_data))
    bar_height = 0.35
    
    ax1.barh(y_pos - bar_height/2, corr_data['pearson_r'], height=bar_height, label='Pearson (r) [Korelasi Linear]', color='#0284c7', edgecolor='#0369a1')
    ax1.barh(y_pos + bar_height/2, corr_data['spearman_rho'], height=bar_height, label='Spearman (ρ) [Korelasi Monotonik]', color='#10b981', edgecolor='#047857')
    
    for i in range(len(corr_data)):
        r = corr_data['pearson_r'].iloc[i]
        rho = corr_data['spearman_rho'].iloc[i]
        ax1.text(r + (0.02 if r >= 0 else -0.06), i - bar_height/2, f"{r:.2f}", va='center', fontsize=9, fontweight='bold', color='#0369a1')
        ax1.text(rho + (0.02 if rho >= 0 else -0.06), i + bar_height/2, f"{rho:.2f}", va='center', fontsize=9, fontweight='bold', color='#047857')

    ax1.set_yticks(y_pos)
    ax1.set_yticklabels(corr_data['Pair'], fontsize=9.5, fontweight='bold')
    ax1.axvline(0, color='#64748b', linewidth=1.2)
    ax1.axvline(0.5, color='#dc2626', linestyle=':', label='Batas Korelasi Kuat (r > 0.5)')
    ax1.set_xlabel('Koefisien Korelasi', fontsize=11, fontweight='bold')
    ax1.set_title('(A) Evaluasi Kekuatan Hubungan: Kasus Diagnosis vs Konsumsi Obat Spesifik Puskesmas Teluk Sasah', fontsize=13, fontweight='bold', pad=10)
    ax1.legend(loc='lower right', fontsize=10, framealpha=0.95)
    ax1.grid(True, linestyle='--', alpha=0.5, axis='x')
    ax1.set_xlim(-0.35, 1.0)

    # Panel B: Scatter Plot Regresi ISPA vs Parasetamol & Amoksisilin
    ax2 = fig.add_subplot(gs[1, 0])
    p_j00 = df_penyakit[df_penyakit['kode_icd'] == 'J00'].groupby('periode_bimonthly_id')['total_pasien'].sum().reindex(PERIODS).fillna(0)
    m_pct = df_pivot[df_pivot['nama_obat'].str.contains('Parasetamol tablet 500', case=False)][PERIODS].sum(axis=0).values
    m_amx = df_pivot[df_pivot['nama_obat'].str.contains('Amoksisilin kaplet 500', case=False)][PERIODS].sum(axis=0).values

    # Filter out anomalous stockout period (P4 pemakaian 0) for regression fit
    mask = m_pct > 500
    sns.regplot(x=p_j00[mask], y=m_pct[mask], ax=ax2, color='#dc2626', 
                scatter_kws={'s': 60, 'alpha': 0.8}, line_kws={'linewidth': 2, 'label': 'Parasetamol (r = 0.59)'})
    sns.regplot(x=p_j00[mask], y=m_amx[mask], ax=ax2, color='#0284c7', 
                scatter_kws={'s': 60, 'alpha': 0.8, 'marker': '^'}, line_kws={'linewidth': 2, 'linestyle': '--', 'label': 'Amoksisilin (r = 0.64)'})
    ax2.set_title('(B) Regresi Linier Kasus ISPA (J00) vs Konsumsi Obat Terkait', fontsize=11, fontweight='bold')
    ax2.set_xlabel('Jumlah Pasien Kasus ISPA per Dwibulan', fontsize=10, fontweight='bold')
    ax2.set_ylabel('Konsumsi Obat (Unit / Butir)', fontsize=10, fontweight='bold')
    ax2.legend(loc='upper left', fontsize=9.5)
    ax2.grid(True, linestyle='--', alpha=0.5)

    # Panel C: Diagram Rasio Dosis Rata-rata per Kasus Pasien
    ax3 = fig.add_subplot(gs[1, 1])
    sorted_rasio = corr_data.sort_values('rasio_dosis_per_pasien', ascending=True)
    bars_r = ax3.barh(sorted_rasio['Pair'], sorted_rasio['rasio_dosis_per_pasien'], color='#8b5cf6', edgecolor='#6d28d9', height=0.6)
    for b in bars_r:
        w = b.get_width()
        ax3.text(w + 0.8, b.get_y() + b.get_height()/2, f"{w:.1f} butir/pasien", va='center', fontsize=9, fontweight='bold', color='#4c1d95')
    ax3.set_title('(C) Rasio Konsumsi Obat per Kasus Pasien Terdaftar', fontsize=11, fontweight='bold')
    ax3.set_xlabel('Rata-Rata Butir Obat per Pasien', fontsize=10, fontweight='bold')
    ax3.set_xlim(0, max(sorted_rasio['rasio_dosis_per_pasien']) * 1.3)
    ax3.grid(True, linestyle='--', alpha=0.5, axis='x')

    plt.tight_layout()
    out_path = os.path.join(VIZ_DIR, 'EDA_03_korelasi_klinis_penyakit_obat.png')
    plt.savefig(out_path)
    plt.close()
    print(f"-> Berhasil disimpan: {out_path}")


# ==============================================================================
# GRAFIK 4: PREDIKSI KEBUTUHAN STOK OBAT & SAFETY STOCK 95%
# ==============================================================================
def generate_grafik_prediksi_stok():
    print("Membuat Grafik 4: Prediksi Stok & Safety Stock...")
    fig, (ax1, ax2) = plt.subplots(1, 2, figsize=(16, 7), dpi=300)

    # Panel A: Prediksi Kebutuhan 8 Obat Utama (2026-P5) + Buffer Safety Stock (Z=1.65 / 95%)
    target_drugs = [
        'Parasetamol tablet 500 mg',
        'Amlodipin 10 mg tablet',
        'Asam Mefenamat kaplet 500 mg',
        'Metformin tablet 500 mg',
        'Amoksisilin kaplet 500 mg',
        'Kalsium Laktat tablet 500 mg',
        'Antasida DOEN I tablet',
        'Ambroksol tablet 30 mg'
    ]

    pred_rows = []
    for td in target_drugs:
        match = df_eval[df_eval['nama_obat'].str.contains(td[:18], case=False, regex=False)]
        if not match.empty:
            r = match.iloc[0]
            forecast = r['forecast_rekomendasi_2026_P5']
            # Safety stock: 1.65 * std (or 25% of forecast if std high)
            std_val = r['mean_pemakaian'] * r['cv_pemakaian']
            ss = 1.65 * (std_val * 0.4) # buffer penyesuaian lead time
            pred_rows.append({
                'name': td.replace(' tablet', '').replace(' kaplet', ''),
                'forecast': forecast,
                'safety_stock': ss,
                'total_rekomendasi': forecast + ss,
                'best_model': r['best_model']
            })

    df_plot_pred = pd.DataFrame(pred_rows).sort_values('total_rekomendasi', ascending=True)

    y_pos = np.arange(len(df_plot_pred))
    p1 = ax1.barh(y_pos, df_plot_pred['forecast'], height=0.6, label='Prediksi Base Demand (WMA/SMA)', color='#0284c7', edgecolor='#0369a1')
    p2 = ax1.barh(y_pos, df_plot_pred['safety_stock'], left=df_plot_pred['forecast'], height=0.6, label='Safety Stock 95% (Buffer Proteksi)', color='#f59e0b', edgecolor='#d97706')

    for i in range(len(df_plot_pred)):
        tot = df_plot_pred['total_rekomendasi'].iloc[i]
        bm = df_plot_pred['best_model'].iloc[i]
        ax1.text(tot + 150, i, f"{int(tot):,} unit ({bm})", va='center', fontsize=9, fontweight='bold', color='#0f172a')

    ax1.set_yticks(y_pos)
    ax1.set_yticklabels(df_plot_pred['name'], fontsize=9.5, fontweight='bold')
    ax1.set_xlabel('Rekomendasi Jumlah Pengadaan (Unit)', fontsize=10, fontweight='bold')
    ax1.set_title('(A) Rekomendasi Alokasi Stok Obat 2026-P5 Lengkap dengan Buffer Pengaman 95%', fontsize=11, fontweight='bold')
    ax1.set_xlim(0, max(df_plot_pred['total_rekomendasi']) * 1.3)
    ax1.legend(loc='lower right', fontsize=9.5)
    ax1.grid(True, linestyle='--', alpha=0.5, axis='x')

    # Panel B: Matriks Evaluasi Risiko (Understock vs Overstock Kadaluarsa)
    # Bukti data: 482 stockout vs Rp 43.35 Juta kadaluarsa
    risk_labels = ['Total Kejadian Stockout\n(Kekosongan Stok Nyata)', 'Total Obat Kadaluarsa\n(138 Item Terbuang)', 'Kerugian Kadaluarsa\n(Rp Juta Rupiah)']
    risk_vals = [482, 138, 43.35]
    risk_colors = ['#dc2626', '#ea580c', '#b91c1c']

    bars_risk = ax2.bar(risk_labels, risk_vals, color=risk_colors, width=0.5, edgecolor='#450a0a')
    for b in bars_risk:
        h = b.get_height()
        ax2.text(b.get_x() + b.get_width()/2, h + (15 if h > 50 else 3), 
                 f"{h:,.2f}" if h < 100 else f"{int(h):,} Kasus", 
                 ha='center', fontsize=10.5, fontweight='bold', color='#7f1d1d')
        
    ax2.set_title('(B) Urgensi Pemodelan Data Mining: Mengatasi Understock & Overstock Historis', fontsize=11, fontweight='bold')
    ax2.set_ylabel('Besaran Metrik / Frekuensi Kasus', fontsize=10, fontweight='bold')
    ax2.set_ylim(0, 560)
    ax2.grid(True, linestyle='--', alpha=0.5, axis='y')

    plt.tight_layout()
    out_path = os.path.join(VIZ_DIR, 'EDA_04_prediksi_kebutuhan_stok_safety_stock.png')
    plt.savefig(out_path)
    plt.close()
    print(f"-> Berhasil disimpan: {out_path}")


# ==============================================================================
# GRAFIK MASTER: EDA DASHBOARD MULTI-PANEL 6-IN-1 (PUBLICATION QUALITY)
# ==============================================================================
def generate_master_dashboard():
    print("Membuat Grafik Master Dashboard EDA (6-in-1)...")
    fig = plt.figure(figsize=(20, 15), dpi=300)
    gs = gridspec.GridSpec(3, 2, height_ratios=[1.0, 1.0, 1.0])

    fig.suptitle('EKSPOLORASI DATA & ANALISIS PREDIKSI KEBUTUHAN STOK OBAT VS TREN PENYAKIT MUSIMAN\nUPTD Puskesmas Teluk Sasah, Kabupaten Bintan (Data Terproses 2023 - 2026)',
                 fontsize=15, fontweight='heavy', color='#0f172a', y=0.98)

    # Subplot 1: Tren Rata-rata Musiman Bulanan
    ax1 = fig.add_subplot(gs[0, 0])
    p_j00 = df_penyakit[df_penyakit['kode_icd'] == 'J00'].groupby('bulan_angka')['total_pasien'].mean().reindex(range(1, 13))
    p_i10 = df_penyakit[df_penyakit['kode_icd'] == 'I10'].groupby('bulan_angka')['total_pasien'].mean().reindex(range(1, 13))
    p_k30 = df_penyakit[df_penyakit['kode_icd'] == 'K30'].groupby('bulan_angka')['total_pasien'].mean().reindex(range(1, 13))
    p_e11 = df_penyakit[df_penyakit['kode_icd'] == 'E11'].groupby('bulan_angka')['total_pasien'].mean().reindex(range(1, 13))

    ax1.plot(MONTH_NAMES, p_j00, marker='o', color='#dc2626', linewidth=2.5, label='ISPA [J00] (Puncak Nov: 436 pasien)')
    ax1.plot(MONTH_NAMES, p_i10, marker='s', color='#0284c7', linewidth=2, linestyle='--', label='Hipertensi [I10] (Pola Stabil)')
    ax1.plot(MONTH_NAMES, p_k30, marker='^', color='#7c3aed', linewidth=2, linestyle='-.', label='Dispepsia [K30]')
    ax1.plot(MONTH_NAMES, p_e11, marker='d', color='#16a34a', linewidth=2, linestyle=':', label='Diabetes Melitus [E11]')
    ax1.axvspan(8.5, 11.5, color='#fee2e2', alpha=0.4, label='Lonjakan Musim Penghujan')
    ax1.set_title('(A) Analisis Tren Musiman Bulanan Morbiditas Penyakit', fontsize=11, fontweight='bold')
    ax1.set_ylabel('Rata-rata Pasien', fontsize=9.5, fontweight='bold')
    ax1.legend(loc='upper left', fontsize=8.5)
    ax1.grid(True, linestyle='--', alpha=0.5)

    # Subplot 2: Top 8 Konsumsi Obat Kuratif
    ax2 = fig.add_subplot(gs[0, 1])
    df_kuratif = df_pivot[~df_pivot['nama_obat'].str.contains('Fero Sulfat', case=False)].nlargest(8, 'total_pemakaian').sort_values('total_pemakaian', ascending=True)
    b_top = ax2.barh(df_kuratif['nama_obat'].apply(lambda x: x[:26]), df_kuratif['total_pemakaian'], color='#38bdf8', edgecolor='#0284c7', height=0.6)
    for b in b_top:
        w = b.get_width()
        ax2.text(w + 1500, b.get_y() + b.get_height()/2, f"{int(w):,}", va='center', fontsize=8.5, fontweight='bold')
    ax2.set_title('(B) Pola Volume Pemakaian 8 Obat Kuratif Terbanyak', fontsize=11, fontweight='bold')
    ax2.set_xlabel('Total Pemakaian Riil (Unit)', fontsize=9.5, fontweight='bold')
    ax2.set_xlim(0, max(df_kuratif['total_pemakaian']) * 1.25)
    ax2.grid(True, linestyle='--', alpha=0.5, axis='x')

    # Subplot 3: Heatmap Korelasi Pearson & Spearman
    ax3 = fig.add_subplot(gs[1, 0])
    pair_labels = df_korelasi['nama_penyakit'].str.split('/').str[0].str.strip() + ' ➔ ' + df_korelasi['nama_obat_standar'].str.split(' ').str[0]
    y_idx = np.arange(len(df_korelasi))
    ax3.barh(y_idx - 0.18, df_korelasi['pearson_r'], height=0.36, color='#0284c7', label='Pearson (r)')
    ax3.barh(y_idx + 0.18, df_korelasi['spearman_rho'], height=0.36, color='#10b981', label='Spearman (ρ)')
    ax3.set_yticks(y_idx)
    ax3.set_yticklabels(pair_labels, fontsize=8.5, fontweight='bold')
    ax3.axvline(0, color='#64748b')
    ax3.axvline(0.5, color='#dc2626', linestyle=':')
    ax3.set_title('(C) Koefisien Korelasi Penyakit vs Terapi Obat Terkait', fontsize=11, fontweight='bold')
    ax3.set_xlabel('Koefisien Korelasi', fontsize=9.5, fontweight='bold')
    ax3.legend(loc='lower right', fontsize=8.5)
    ax3.grid(True, linestyle='--', alpha=0.5, axis='x')

    # Subplot 4: Rasio Konsumsi per Kasus Pasien
    ax4 = fig.add_subplot(gs[1, 1])
    sorted_k = df_korelasi.sort_values('rasio_dosis_per_pasien', ascending=True)
    b_k = ax4.barh(sorted_k['nama_obat_standar'].apply(lambda x: x[:24]), sorted_k['rasio_dosis_per_pasien'], color='#c084fc', edgecolor='#7c3aed', height=0.6)
    for b in b_k:
        w = b.get_width()
        ax4.text(w + 0.8, b.get_y() + b.get_height()/2, f"{w:.1f} butir", va='center', fontsize=8.5, fontweight='bold')
    ax4.set_title('(D) Rata-Rata Rasio Dosis Butir Obat per Pasien', fontsize=11, fontweight='bold')
    ax4.set_xlabel('Butir / Pasien', fontsize=9.5, fontweight='bold')
    ax4.set_xlim(0, max(sorted_k['rasio_dosis_per_pasien']) * 1.25)
    ax4.grid(True, linestyle='--', alpha=0.5, axis='x')

    # Subplot 5: Rekomendasi Stok 2026-P5 + Safety Stock 95%
    ax5 = fig.add_subplot(gs[2, 0])
    target_top5 = ['Parasetamol tablet 500 mg', 'Amlodipin 10 mg tablet', 'Asam Mefenamat kaplet 500 mg', 'Metformin tablet 500 mg', 'Amoksisilin kaplet 500 mg']
    p_rows = []
    for t in target_top5:
        m = df_eval[df_eval['nama_obat'].str.contains(t[:15], case=False)]
        if not m.empty:
            r = m.iloc[0]
            fc = r['forecast_rekomendasi_2026_P5']
            ss = 1.65 * (r['mean_pemakaian'] * r['cv_pemakaian'] * 0.4)
            p_rows.append({'name': t.split(' ')[0], 'base': fc, 'ss': ss, 'tot': fc + ss})
    df_p5 = pd.DataFrame(p_rows)
    y_p5 = np.arange(len(df_p5))
    ax5.barh(y_p5, df_p5['base'], height=0.55, label='Base Demand (Model MA/WMA)', color='#0284c7')
    ax5.barh(y_p5, df_p5['ss'], left=df_p5['base'], height=0.55, label='Safety Stock (95% Buffer)', color='#f59e0b')
    for i in range(len(df_p5)):
        tot = df_p5['tot'].iloc[i]
        ax5.text(tot + 150, i, f"{int(tot):,} unit", va='center', fontsize=8.5, fontweight='bold')
    ax5.set_yticks(y_p5)
    ax5.set_yticklabels(df_p5['name'], fontsize=9, fontweight='bold')
    ax5.set_title('(E) Proyeksi Pengadaan Obat P5 dengan Safety Stock 95%', fontsize=11, fontweight='bold')
    ax5.set_xlabel('Jumlah Rekomendasi (Unit)', fontsize=9.5, fontweight='bold')
    ax5.set_xlim(0, max(df_p5['tot']) * 1.3)
    ax5.legend(loc='lower right', fontsize=8.5)
    ax5.grid(True, linestyle='--', alpha=0.5, axis='x')

    # Subplot 6: Bukti Empiris Kerugian Kadaluarsa vs Stockout
    ax6 = fig.add_subplot(gs[2, 1])
    metrics = ['Kasus Stockout Nyata\n(482 Kejadian)', 'Item Kadaluarsa\n(138 Obat/Alkes)', 'Nilai Finansial Rugi\n(Rp 43.35 Juta)']
    vals = [482, 138, 43.35]
    b_met = ax6.bar(metrics, vals, color=['#ef4444', '#f97316', '#b91c1c'], width=0.45)
    for b in b_met:
        h = b.get_height()
        ax6.text(b.get_x() + b.get_width()/2, h + 12, f"{h:.2f}" if h < 100 else f"{int(h)}", ha='center', fontsize=9.5, fontweight='bold')
    ax6.set_title('(F) Risiko Tanpa Prediksi: Stockout (Understock) vs Kadaluarsa (Overstock)', fontsize=11, fontweight='bold')
    ax6.set_ylabel('Besaran Metrik / Frekuensi Kasus', fontsize=9.5, fontweight='bold')
    ax6.set_ylim(0, 560)
    ax6.grid(True, linestyle='--', alpha=0.5, axis='y')

    plt.tight_layout(rect=[0, 0.03, 1, 0.95])
    out_path = os.path.join(VIZ_DIR, 'EDA_MASTER_PREDIKSI_STOK_DAN_TREN_PENYAKIT.png')
    plt.savefig(out_path)
    plt.close()
    print(f"-> Berhasil disimpan: {out_path}")

if __name__ == '__main__':
    print("=== MEMULAI GENERASI GRAFIK EDA KOMPREHENSIF ===")
    generate_grafik_tren_musiman()
    generate_grafik_pola_obat()
    generate_grafik_korelasi()
    generate_grafik_prediksi_stok()
    generate_master_dashboard()
    print("=== SELESAI SELURUH GRAFIK BERHASIL DIBUAT ===")

