#!/usr/bin/env python3
"""
scripts/2_eda_visual_analysis.py

Skrip Eksplorasi Data & Analisis Visual (EDA) serta Korelasi Tren Penyakit vs Pemakaian Obat
Puskesmas Teluk Sasah Periode 2023 - 2026.

Menghasilkan 8 visualisasi beresolusi tinggi (300 DPI) untuk BAB II & BAB V Laporan Akhir:
1. visualizations/01_top_obat_pemakaian.png
2. visualizations/02_tren_ispa_dan_obat.png
3. visualizations/03_tren_hipertensi_diabetes.png
4. visualizations/04_tren_pencernaan_dispepsia.png
5. visualizations/05_heatmap_korelasi_penyakit_obat.png
6. visualizations/06_analisis_kerugian_kadaluarsa.png
7. visualizations/07_sebaran_fluktuasi_cv.png
8. visualizations/08_analisis_stockout_kosong.png
"""

import os
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns
from scipy.stats import pearsonr, spearmanr

# Set tema visualisasi profesional untuk publikasi akademik
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
print("Memuat dataset terproses...")
df_bimonthly = pd.read_csv(os.path.join(DATA_DIR, 'lplpo_bimonthly_timeseries.csv'))
df_pivot = pd.read_csv(os.path.join(DATA_DIR, 'lplpo_obat_pivot_pemakaian.csv'))
df_penyakit = pd.read_csv(os.path.join(DATA_DIR, 'penyakit_clean_records.csv'))
df_kadaluarsa = pd.read_csv(os.path.join(DATA_DIR, 'obat_kadaluarsa_clean.csv'))

# Urutan 22 periode kronologis
PERIODS = [c for c in df_pivot.columns if c.startswith('202')]

print(f"Dataset dimuat: {len(df_bimonthly)} baris dwibulanan, {len(df_pivot)} jenis obat, {len(PERIODS)} periode.")

# ==============================================================================
# VISUALISASI 1: TOP 15 OBAT DENGAN PEMAKAIAN TERTINGGI (2023 - 2026)
# ==============================================================================
def plot_top_15_obat():
    print("Menghasilkan Grafik 1: Top 15 Obat...")
    top15 = df_pivot.head(15).copy()
    top15.sort_values('total_pemakaian', ascending=True, inplace=True)

    plt.figure(figsize=(12, 8), dpi=300)
    bars = plt.barh(top15['nama_obat'], top15['total_pemakaian'], color='#1f77b4', edgecolor='#0d47a1', height=0.65)

    # Beri label angka di ujung bar
    for bar in bars:
        w = bar.get_width()
        plt.text(w + 2000, bar.get_y() + bar.get_height()/2, f'{int(w):,} unit',
                 va='center', ha='left', fontsize=9.5, fontweight='bold', color='#1a237e')

    plt.title('Top 15 Obat dengan Pemakaian Tertinggi di Puskesmas Teluk Sasah\n(Periode 2023 - 2026)', fontsize=14, fontweight='bold', pad=15)
    plt.xlabel('Total Pemakaian (Tablet / Kaplet / Kapsul / Botol)', fontsize=11, fontweight='bold')
    plt.xlim(0, max(top15['total_pemakaian']) * 1.15)
    plt.tight_layout()
    path = os.path.join(VIZ_DIR, '01_top_obat_pemakaian.png')
    plt.savefig(path)
    plt.close()
    print(f"-> Disimpan: {path}")

# ==============================================================================
# VISUALISASI 2: TREN PENYAKIT ISPA / COMMON COLD (J00) VS OBAT TERKAIT
# ==============================================================================
def plot_tren_ispa_dan_obat():
    print("Menghasilkan Grafik 2: Tren ISPA vs Obat Terkait...")
    p_j00 = df_penyakit[df_penyakit['kode_icd'] == 'J00'].groupby('periode_bimonthly_id')['total_pasien'].sum().reindex(PERIODS).fillna(0)

    # Obat terkait ISPA / Batuk / Flu
    m_pct = df_pivot[df_pivot['nama_obat'].str.contains('Parasetamol tablet 500', case=False)][PERIODS].sum(axis=0).values
    m_amb = df_pivot[df_pivot['nama_obat'].str.contains('Ambroksol tablet 30', case=False)][PERIODS].sum(axis=0).values
    m_amx = df_pivot[df_pivot['nama_obat'].str.contains('Amoksisilin kaplet 500', case=False)][PERIODS].sum(axis=0).values
    m_cet = df_pivot[df_pivot['nama_obat'].str.contains('Cetirizina', case=False)][PERIODS].sum(axis=0).values

    fig, ax1 = plt.subplots(figsize=(14, 7), dpi=300)

    color_j00 = '#d32f2f'
    ax1.set_xlabel('Periode Dwibulanan (2023 - 2026)', fontsize=11, fontweight='bold', labelpad=10)
    ax1.set_ylabel('Jumlah Pasien Kasus J00 (Acute Nasopharyngitis)', color=color_j00, fontsize=11, fontweight='bold')
    l0 = ax1.plot(PERIODS, p_j00, color=color_j00, linewidth=2.8, marker='o', label='Kasus J00 [Common Cold]')
    ax1.tick_params(axis='y', labelcolor=color_j00)
    ax1.tick_params(axis='x', rotation=45)

    ax2 = ax1.twinx()
    ax2.set_ylabel('Pemakaian Obat (Unit/Butir)', color='#1565c0', fontsize=11, fontweight='bold')
    l1 = ax2.plot(PERIODS, m_pct, color='#0288d1', linewidth=2.0, linestyle='--', marker='s', label='Parasetamol 500 mg')
    l2 = ax2.plot(PERIODS, m_amx, color='#388e3c', linewidth=2.0, linestyle='-.', marker='^', label='Amoksisilin 500 mg')
    l3 = ax2.plot(PERIODS, m_cet, color='#f57c00', linewidth=1.8, linestyle=':', marker='d', label='Cetirizina 10 mg')
    l4 = ax2.plot(PERIODS, m_amb, color='#7b1fa2', linewidth=1.8, linestyle='-', marker='v', label='Ambroksol 30 mg')
    ax2.tick_params(axis='y', labelcolor='#1565c0')
    ax2.grid(False)

    lines = l0 + l1 + l2 + l3 + l4
    labels = [l.get_label() for l in lines]
    ax1.legend(lines, labels, loc='upper right', frameon=True, facecolor='white', framealpha=0.9)

    plt.title('Korelasi Tren Penyakit Common Cold (J00) dengan Pola Pemakaian Obat Terkait\n(Puskesmas Teluk Sasah 2023 - 2026)', fontsize=13, fontweight='bold', pad=15)
    plt.tight_layout()
    path = os.path.join(VIZ_DIR, '02_tren_ispa_dan_obat.png')
    plt.savefig(path)
    plt.close()
    print(f"-> Disimpan: {path}")

# ==============================================================================
# VISUALISASI 3: TREN PENYAKIT KRONIS (HIPERTENSI & DIABETES) VS OBAT RUTIN
# ==============================================================================
def plot_tren_kronis():
    print("Menghasilkan Grafik 3: Tren Hipertensi & Diabetes...")
    p_i10 = df_penyakit[df_penyakit['kode_icd'] == 'I10'].groupby('periode_bimonthly_id')['total_pasien'].sum().reindex(PERIODS).fillna(0)
    p_e11 = df_penyakit[df_penyakit['kode_icd'] == 'E11'].groupby('periode_bimonthly_id')['total_pasien'].sum().reindex(PERIODS).fillna(0)

    m_amlo10 = df_pivot[df_pivot['nama_obat'].str.contains('Amlodipin 10 mg', case=False)][PERIODS].sum(axis=0).values
    m_met = df_pivot[df_pivot['nama_obat'].str.contains('Metformin tablet 500', case=False)][PERIODS].sum(axis=0).values

    fig, (ax1, ax2) = plt.subplots(2, 1, figsize=(14, 10), dpi=300, sharex=True)

    # Subplot 1: Hipertensi
    color_i10 = '#c2185b'
    ax1.set_ylabel('Pasien Hipertensi (I10)', color=color_i10, fontsize=10, fontweight='bold')
    l0 = ax1.plot(PERIODS, p_i10, color=color_i10, linewidth=2.4, marker='o', label='Kasus I10 [Hipertensi Primer]')
    ax1.tick_params(axis='y', labelcolor=color_i10)

    ax1_twin = ax1.twinx()
    ax1_twin.set_ylabel('Amlodipin 10 mg (Tablet)', color='#0277bd', fontsize=10, fontweight='bold')
    l1 = ax1_twin.plot(PERIODS, m_amlo10, color='#0277bd', linewidth=2.0, linestyle='--', marker='s', label='Pemakaian Amlodipin 10 mg')
    ax1_twin.tick_params(axis='y', labelcolor='#0277bd')
    ax1_twin.grid(False)

    lines1 = l0 + l1
    ax1.legend(lines1, [l.get_label() for l in lines1], loc='upper left')
    ax1.set_title('(A) Kasus Hipertensi Primer (I10) vs Konsumsi Amlodipin 10 mg', fontsize=12, fontweight='bold')

    # Subplot 2: Diabetes
    color_e11 = '#e65100'
    ax2.set_ylabel('Pasien Diabetes (E11)', color=color_e11, fontsize=10, fontweight='bold')
    l2 = ax2.plot(PERIODS, p_e11, color=color_e11, linewidth=2.4, marker='o', label='Kasus E11 [Diabetes Melitus]')
    ax2.tick_params(axis='y', labelcolor=color_e11)

    ax2_twin = ax2.twinx()
    ax2_twin.set_ylabel('Metformin 500 mg (Tablet)', color='#2e7d32', fontsize=10, fontweight='bold')
    l3 = ax2_twin.plot(PERIODS, m_met, color='#2e7d32', linewidth=2.0, linestyle='--', marker='^', label='Pemakaian Metformin 500 mg')
    ax2_twin.tick_params(axis='y', labelcolor='#2e7d32')
    ax2_twin.grid(False)

    lines2 = l2 + l3
    ax2.legend(lines2, [l.get_label() for l in lines2], loc='upper left')
    ax2.set_title('(B) Kasus Diabetes Melitus (E11) vs Konsumsi Metformin 500 mg (r = 0.528, p < 0.05)', fontsize=12, fontweight='bold')

    plt.xticks(rotation=45)
    plt.xlabel('Periode Dwibulanan (2023 - 2026)', fontsize=11, fontweight='bold')
    plt.tight_layout()
    path = os.path.join(VIZ_DIR, '03_tren_hipertensi_diabetes.png')
    plt.savefig(path)
    plt.close()
    print(f"-> Disimpan: {path}")

# ==============================================================================
# VISUALISASI 4: TREN PENYAKIT PENCERNAAN / DISPEPSIA (K30) VS OBAT LAMBUNG
# ==============================================================================
def plot_tren_pencernaan():
    print("Menghasilkan Grafik 4: Tren Pencernaan vs Obat Lambung...")
    p_k30 = df_penyakit[df_penyakit['kode_icd'] == 'K30'].groupby('periode_bimonthly_id')['total_pasien'].sum().reindex(PERIODS).fillna(0)

    m_ome = df_pivot[df_pivot['nama_obat'].str.contains('Omeprazol', case=False)][PERIODS].sum(axis=0).values
    m_ant = df_pivot[df_pivot['nama_obat'].str.contains('Antasida DOEN', case=False)][PERIODS].sum(axis=0).values
    m_dom = df_pivot[df_pivot['nama_obat'].str.contains('Domperidon', case=False)][PERIODS].sum(axis=0).values

    fig, ax1 = plt.subplots(figsize=(14, 7), dpi=300)

    color_k30 = '#6a1b9a'
    ax1.set_xlabel('Periode Dwibulanan (2023 - 2026)', fontsize=11, fontweight='bold', labelpad=10)
    ax1.set_ylabel('Jumlah Pasien Dispepsia (K30)', color=color_k30, fontsize=11, fontweight='bold')
    l0 = ax1.plot(PERIODS, p_k30, color=color_k30, linewidth=2.8, marker='o', label='Kasus K30 [Dyspepsia]')
    ax1.tick_params(axis='y', labelcolor=color_k30)
    ax1.tick_params(axis='x', rotation=45)

    ax2 = ax1.twinx()
    ax2.set_ylabel('Pemakaian Obat Saluran Cerna (Unit)', color='#00695c', fontsize=11, fontweight='bold')
    l1 = ax2.plot(PERIODS, m_ome, color='#00897b', linewidth=2.0, linestyle='--', marker='s', label='Omeprazol 20 mg (r = 0.384)')
    l2 = ax2.plot(PERIODS, m_ant, color='#fb8c00', linewidth=2.0, linestyle='-.', marker='^', label='Antasida DOEN')
    l3 = ax2.plot(PERIODS, m_dom, color='#3949ab', linewidth=1.8, linestyle=':', marker='d', label='Domperidon 10 mg (r = 0.375)')
    ax2.tick_params(axis='y', labelcolor='#00695c')
    ax2.grid(False)

    lines = l0 + l1 + l2 + l3
    labels = [l.get_label() for l in lines]
    ax1.legend(lines, labels, loc='upper right', frameon=True, facecolor='white', framealpha=0.9)

    plt.title('Korelasi Tren Penyakit Lambung / Dispepsia (K30) dengan Pemakaian Obat Terkait\n(Puskesmas Teluk Sasah 2023 - 2026)', fontsize=13, fontweight='bold', pad=15)
    plt.tight_layout()
    path = os.path.join(VIZ_DIR, '04_tren_pencernaan_dispepsia.png')
    plt.savefig(path)
    plt.close()
    print(f"-> Disimpan: {path}")

# ==============================================================================
# VISUALISASI 5: HEATMAP KORELASI PENYAKIT UTAMA VS OBAT UTAMA
# ==============================================================================
def plot_heatmap_korelasi():
    print("Menghasilkan Grafik 5: Heatmap Korelasi...")
    # 6 Penyakit dominan
    disease_codes = ['J00', 'I10', 'K30', 'E11', 'A09', 'T14']
    disease_labels = ['J00 (Common Cold)', 'I10 (Hipertensi)', 'K30 (Dispepsia)', 'E11 (Diabetes)', 'A09 (Diare)', 'T14 (Luka/Cedera)']

    # 10 Obat dominan
    drug_names = [
        'Parasetamol tablet 500 mg',
        'Amoksisilin kaplet 500 mg',
        'Ambroksol tablet 30 mg',
        'Cetirizina kapsul 10 mg',
        'Amlodipin 10 mg tablet',
        'Kaptopril tablet 25 mg',
        'Metformin tablet 500 mg',
        'Omeprazol capsul 20 mg',
        'Domperidon tablet 10 mg',
        'Asam Mefenamat kaplet 500 mg',
    ]
    drug_short = [
        'Parasetamol 500mg',
        'Amoksisilin 500mg',
        'Ambroksol 30mg',
        'Cetirizina 10mg',
        'Amlodipin 10mg',
        'Kaptopril 25mg',
        'Metformin 500mg',
        'Omeprazol 20mg',
        'Domperidon 10mg',
        'Asam Mefenamat 500mg',
    ]

    p_df = df_penyakit.groupby(['periode_bimonthly_id', 'kode_icd'])['total_pasien'].sum().unstack(fill_value=0.0).reindex(PERIODS)
    m_df = df_bimonthly[df_bimonthly['kategori'] == 'OBAT'].groupby(['periode_bimonthly_id', 'nama_obat'])['pemakaian'].sum().unstack(fill_value=0.0).reindex(PERIODS)

    corr_matrix = np.zeros((len(disease_codes), len(drug_names)))

    for i, code in enumerate(disease_codes):
        for j, drug in enumerate(drug_names):
            if code in p_df.columns and drug in m_df.columns:
                r, _ = pearsonr(p_df[code], m_df[drug])
                corr_matrix[i, j] = r

    df_corr = pd.DataFrame(corr_matrix, index=disease_labels, columns=drug_short)

    plt.figure(figsize=(13, 7), dpi=300)
    sns.heatmap(df_corr, annot=True, fmt='.2f', cmap='coolwarm', vmin=-0.6, vmax=0.8,
                cbar_kws={'label': 'Koefisien Korelasi Pearson (r)'}, linewidths=0.8, linecolor='white')
    plt.title('Matriks Korelasi Pearson antara Diagnosis Penyakit dan Pemakaian Obat Terkait\n(Puskesmas Teluk Sasah 2023 - 2026)', fontsize=13, fontweight='bold', pad=15)
    plt.xticks(rotation=45, ha='right', fontsize=9.5)
    plt.yticks(fontsize=9.5)
    plt.tight_layout()
    path = os.path.join(VIZ_DIR, '05_heatmap_korelasi_penyakit_obat.png')
    plt.savefig(path)
    plt.close()
    print(f"-> Disimpan: {path}")

# ==============================================================================
# VISUALISASI 6: ANALISIS KERUGIAN OBAT & ALKES KADALUARSA
# ==============================================================================
def plot_analisis_kadaluarsa():
    print("Menghasilkan Grafik 6: Analisis Obat Kadaluarsa...")
    annual_loss = df_kadaluarsa.groupby('tahun_lapor')['sub_total_kerugian'].agg(['sum', 'count']).reset_index()

    fig, (ax1, ax2) = plt.subplots(1, 2, figsize=(15, 6), dpi=300)

    # Panel 1: Bar chart kerugian per tahun
    colors = ['#1976d2', '#388e3c', '#d32f2f']
    bars = ax1.bar(annual_loss['tahun_lapor'].astype(str), annual_loss['sum'] / 1e6, color=colors, edgecolor='#333333', width=0.55)
    for bar in bars:
        h = bar.get_height()
        ax1.text(bar.get_x() + bar.get_width()/2, h + 0.5, f'Rp {h:.2f} Juta', ha='center', va='bottom', fontweight='bold', fontsize=10)

    ax1.set_title('(A) Estimasi Kerugian Finansial Akibat Kadaluarsa\n(Total: Rp 43.35 Juta)', fontsize=12, fontweight='bold')
    ax1.set_ylabel('Nilai Kerugian (Juta Rupiah)', fontsize=10, fontweight='bold')
    ax1.set_xlabel('Tahun Pelaporan', fontsize=10, fontweight='bold')
    ax1.set_ylim(0, 26)

    # Panel 2: Top 8 item dengan kerugian terbesar
    top8_loss = df_kadaluarsa.groupby('nama_barang')['sub_total_kerugian'].sum().sort_values(ascending=True).tail(8)
    bars2 = ax2.barh([n[:25] for n in top8_loss.index], top8_loss.values / 1e6, color='#ff7043', edgecolor='#bf360c', height=0.6)
    for bar in bars2:
        w = bar.get_width()
        ax2.text(w + 0.1, bar.get_y() + bar.get_height()/2, f'Rp {w:.2f} Jt', va='center', ha='left', fontsize=9, fontweight='bold')

    ax2.set_title('(B) Top 8 Item Obat/Alkes dengan Kerugian Terbesar', fontsize=12, fontweight='bold')
    ax2.set_xlabel('Total Nilai Kerugian (Juta Rupiah)', fontsize=10, fontweight='bold')
    ax2.set_xlim(0, max(top8_loss.values / 1e6) * 1.25)

    plt.suptitle('Analisis Bukti Empiris Masalah Overstock & Pemborosan Obat Kadaluarsa di Puskesmas Teluk Sasah', fontsize=13, fontweight='bold', y=1.03)
    plt.tight_layout()
    path = os.path.join(VIZ_DIR, '06_analisis_kerugian_kadaluarsa.png')
    plt.savefig(path)
    plt.close()
    print(f"-> Disimpan: {path}")

# ==============================================================================
# VISUALISASI 7: SEBARAN FLUKTUASI (CV) VS TINGKAT PEMAKAIAN OBAT
# ==============================================================================
def plot_sebaran_cv():
    print("Menghasilkan Grafik 7: Sebaran Fluktuasi CV...")
    df_valid = df_pivot[(df_pivot['total_pemakaian'] >= 500) & (df_pivot['cv_pemakaian'] > 0)].copy()

    plt.figure(figsize=(13, 7), dpi=300)
    scatter = plt.scatter(df_valid['mean_pemakaian'], df_valid['cv_pemakaian'],
                          s=np.log1p(df_valid['total_pemakaian'])*15,
                          c=df_valid['cv_pemakaian'], cmap='viridis', alpha=0.75, edgecolors='k', linewidth=0.5)

    # Tambahkan garis batas kuadran
    plt.axhline(0.4, color='#2e7d32', linestyle='--', linewidth=1.2, label='Batas Stabil (CV < 0.4)')
    plt.axhline(0.8, color='#c62828', linestyle='--', linewidth=1.2, label='Batas Fluktuasi Tinggi (CV > 0.8)')

    # Anotasi obat-obat penting
    highlight_drugs = [
        'Parasetamol tablet 500 mg',
        'Metformin tablet 500 mg',
        'Amoksisilin kaplet 500 mg',
        'Cetirizina kapsul 10 mg',
        'Amlodipin 10 mg tablet',
        'Ambroksol tablet 30 mg',
        'Asam Mefenamat kaplet 500 mg',
        'Kalsium Laktat tablet 500 mg'
    ]

    for drug in highlight_drugs:
        sub = df_valid[df_valid['nama_obat'] == drug]
        if not sub.empty:
            x = sub['mean_pemakaian'].values[0]
            y = sub['cv_pemakaian'].values[0]
            plt.annotate(drug.split(' ')[0] + ' (' + drug.split(' ')[-2] + ' ' + drug.split(' ')[-1] + ')',
                         xy=(x, y), xytext=(x * 1.08, y + 0.05),
                         fontsize=9, fontweight='bold',
                         arrowprops=dict(arrowstyle='->', color='#333333', lw=1.0))

    plt.xscale('log')
    plt.title('Pemetaan Karakteristik Fluktuasi Pemakaian Obat Puskesmas Teluk Sasah\n(Koefisien Variasi [CV] vs Rata-rata Pemakaian per Periode)', fontsize=13, fontweight='bold', pad=15)
    plt.xlabel('Rata-rata Pemakaian per Periode (Skala Logaritmik - Butir)', fontsize=11, fontweight='bold')
    plt.ylabel('Koefisien Variasi (CV = Std / Mean)', fontsize=11, fontweight='bold')
    plt.colorbar(scatter, label='Nilai Koefisien Variasi (CV)')
    plt.legend(loc='upper right', frameon=True)
    plt.tight_layout()
    path = os.path.join(VIZ_DIR, '07_sebaran_fluktuasi_cv.png')
    plt.savefig(path)
    plt.close()
    print(f"-> Disimpan: {path}")

# ==============================================================================
# VISUALISASI 8: FREKUENSI KEKOSONGAN STOK (STOCKOUT) PADA OBAT ESENSIAL
# ==============================================================================
def plot_analisis_stockout():
    print("Menghasilkan Grafik 8: Frekuensi Stockout Obat Esensial...")
    top_drugs = [
        'Kaptopril tablet 25 mg',
        'Ibuprofen tablet 400 mg',
        'Metilprednisolon tablet 4 mg',
        'Ambroksol tablet 30 mg',
        'Piroksikam  capsul 10 mg',
        'Amlodipin 10 mg tablet',
        'Deksametason tablet 0,5 mg',
        'Amlodipin tablet 5 mg',
        'Cetirizina kapsul 10 mg',
        'Parasetamol tablet 500 mg',
        'Vitamin B-Kompleks tablet',
        'Asam Mefenamat kaplet 500 mg',
    ]

    stockout_counts = []
    df_obat = df_bimonthly[df_bimonthly['kategori'] == 'OBAT']

    for d in top_drugs:
        sub = df_obat[df_obat['nama_obat'] == d]
        cnt = (sub['stok_akhir'] == 0).sum()
        stockout_counts.append({'nama_obat': d, 'stockout_count': cnt})

    df_stock = pd.DataFrame(stockout_counts).sort_values('stockout_count', ascending=True)

    plt.figure(figsize=(12, 7), dpi=300)
    bars = plt.barh([d[:35] for d in df_stock['nama_obat']], df_stock['stockout_count'],
                    color='#d32f2f', edgecolor='#b71c1c', height=0.6)

    for bar in bars:
        w = bar.get_width()
        pct = (w / 22) * 100
        plt.text(w + 0.2, bar.get_y() + bar.get_height()/2, f'{int(w)} periode ({pct:.0f}%)',
                 va='center', ha='left', fontsize=9.5, fontweight='bold', color='#b71c1c')

    plt.title('Bukti Empiris Masalah Stockout (Kekosongan Obat pada Akhir Periode)\nTotal 22 Periode Dwibulanan (2023 - 2026)', fontsize=13, fontweight='bold', pad=15)
    plt.xlabel('Jumlah Periode Dwibulanan Mengalami Stok Akhir = 0 (dari 22 Periode)', fontsize=11, fontweight='bold')
    plt.xlim(0, 16)
    plt.tight_layout()
    path = os.path.join(VIZ_DIR, '08_analisis_stockout_kosong.png')
    plt.savefig(path)
    plt.close()
    print(f"-> Disimpan: {path}")

# ==============================================================================
# MAIN RUNNER
# ==============================================================================
def main():
    print("="*70)
    print("MENJALANKAN ANALISIS EKSPLORASI DATA & GENERASI GRAFIK VISUALISASI")
    print("="*70)
    plot_top_15_obat()
    plot_tren_ispa_dan_obat()
    plot_tren_kronis()
    plot_tren_pencernaan()
    plot_heatmap_korelasi()
    plot_analisis_kadaluarsa()
    plot_sebaran_cv()
    plot_analisis_stockout()
    print("="*70)
    print("SELURUH 8 VISUALISASI TELAH BERHASIL DIHASILKAN DI FOLDER 'visualizations/'!")
    print("="*70)

if __name__ == '__main__':
    main()
