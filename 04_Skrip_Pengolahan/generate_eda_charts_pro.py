#!/usr/bin/env python3
"""
04_Skrip_Pengolahan/generate_eda_charts_pro.py

Skrip Pembuat Visualisasi EDA Super Detail, Cantik, dan Berstandar Akademik Tinggi (300 DPI)
UPTD Puskesmas Teluk Sasah, Kabupaten Bintan (2023 - 2026).
"""

import os
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
import matplotlib.patches as patches
from matplotlib.ticker import FuncFormatter, MaxNLocator
import seaborn as sns
from scipy.interpolate import make_interp_spline
from scipy.stats import pearsonr

# -----------------------------------------------------------------------------
# KONFIGURASI TEMA VISUALISASI AKADEMIK PREMIUM
# -----------------------------------------------------------------------------
plt.rcParams['font.sans-serif'] = 'DejaVu Sans'
plt.rcParams['font.size'] = 10
plt.rcParams['axes.titlesize'] = 12
plt.rcParams['axes.titleweight'] = 'bold'
plt.rcParams['axes.labelsize'] = 10.5
plt.rcParams['axes.labelweight'] = 'bold'
plt.rcParams['xtick.labelsize'] = 9.5
plt.rcParams['ytick.labelsize'] = 9.5
plt.rcParams['axes.edgecolor'] = '#CBD5E1'
plt.rcParams['axes.linewidth'] = 0.9
plt.rcParams['grid.color'] = '#E2E8F0'
plt.rcParams['grid.linestyle'] = '--'
plt.rcParams['grid.alpha'] = 0.8
plt.rcParams['figure.facecolor'] = '#FFFFFF'
plt.rcParams['axes.facecolor'] = '#FFFFFF'

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DATA_DIR = os.path.join(BASE_DIR, '03_Data_Terproses')
VIZ_DIR = os.path.join(BASE_DIR, '05_Visualisasi_Grafik')
os.makedirs(VIZ_DIR, exist_ok=True)

# Memuat Data
df_bimonthly = pd.read_csv(os.path.join(DATA_DIR, 'lplpo_bimonthly_timeseries.csv'))
df_pivot = pd.read_csv(os.path.join(DATA_DIR, 'lplpo_obat_pivot_pemakaian.csv'))
df_penyakit = pd.read_csv(os.path.join(DATA_DIR, 'penyakit_clean_records.csv'))
df_kadaluarsa = pd.read_csv(os.path.join(DATA_DIR, 'obat_kadaluarsa_clean.csv'))
df_monthly_2024 = pd.read_csv(os.path.join(DATA_DIR, 'tren_bulanan_2024_obat.csv'))

PERIODS = [c for c in df_pivot.columns if c.startswith('202')]

print(f"Data termuat: {len(df_pivot)} obat, {len(PERIODS)} periode dwibulanan kontinu.")

def thousands_fmt(x, pos):
    return f'{int(x):,}'

def jutaan_fmt(x, pos):
    return f'Rp {x:,.1f} Jt'

def add_header_card(fig, title, subtitle):
    fig.text(0.08, 0.965, title, fontsize=14, fontweight='bold', color='#0F172A', ha='left')
    fig.text(0.08, 0.935, subtitle, fontsize=10, color='#64748B', ha='left')

# ==============================================================================
# 1. GRAFIK 06: AUDIT KERUGIAN OBAT KADALUARSA (SUPER DETAIL)
# ==============================================================================
def generate_grafik_06():
    print("-> Membuat Grafik 06: Analisis Kerugian Kadaluarsa (Super Detail)...")
    fig, (ax1, ax2) = plt.subplots(1, 2, figsize=(15.5, 7.2), dpi=300)
    plt.subplots_adjust(top=0.84, bottom=0.12, left=0.08, right=0.95, wspace=0.32)

    add_header_card(fig, 
                    "Audit Dampak Finansial Obat & Reagen Kadaluarsa (Total Rp 43,35 Juta)",
                    "UPTD Puskesmas Teluk Sasah • Verifikasi Berita Acara Audit Fisik Periode 2023 - 2025 (138 Item Murni)")

    # Data Panel 1: Kerugian per tahun
    annual = df_kadaluarsa.groupby('tahun_lapor')['sub_total_kerugian'].agg(['sum', 'count']).reset_index()
    tahun_labels = ['2023', '2024', '2025']
    vals_jt = annual['sum'].values / 1e6
    items = annual['count'].values
    colors = ['#0284C7', '#059669', '#DC2626'] # 2025 merah tanda bahaya lonjakan

    bars1 = ax1.bar(tahun_labels, vals_jt, color=colors, width=0.52, edgecolor='#1E293B', linewidth=1.2, zorder=3)
    ax1.grid(axis='y', zorder=0, alpha=0.6)
    ax1.set_ylim(0, 27)
    ax1.set_ylabel('Total Nilai Kerugian (Juta Rupiah)', fontsize=11, fontweight='bold', color='#1E293B')
    ax1.set_xlabel('Tahun Pelaporan Berita Acara', fontsize=11, fontweight='bold', color='#1E293B')

    for bar, val, item in zip(bars1, vals_jt, items):
        pct = (val / vals_jt.sum()) * 100
        ax1.text(bar.get_x() + bar.get_width()/2, val + 0.6,
                 f"Rp {val:.2f} Jt\n({pct:.1f}%)\n[{item} Item]",
                 ha='center', va='bottom', fontsize=9.5, fontweight='bold', color='#0F172A',
                 bbox=dict(boxstyle='round,pad=0.25', facecolor='#F8FAFC', edgecolor='#CBD5E1', alpha=0.9))

    # Anotasi Lonjakan 2025
    ax1.annotate('Lonjakan Signifikan +112,6%!\nAkumulasi over-budgeting & FEFO lemah',
                 xy=(2, vals_jt[2]), xytext=(0.4, 23.5),
                 arrowprops=dict(facecolor='#DC2626', shrink=0.08, width=1.5, headwidth=6),
                 fontsize=9, fontweight='bold', color='#DC2626',
                 bbox=dict(boxstyle='round,pad=0.4', facecolor='#FEF2F2', edgecolor='#F87171'))

    ax1.set_title("A. Distribusi Kerugian Finansial per Tahun", fontsize=12, pad=12)

    # Data Panel 2: Top 8 Item Kerugian Terbesar
    top8 = df_kadaluarsa.groupby('nama_barang')['sub_total_kerugian'].sum().sort_values(ascending=True).tail(8)
    names = [n[:28] for n in top8.index]
    top_vals = top8.values / 1e6

    bars2 = ax2.barh(names, top_vals, color='#EA580C', height=0.62, edgecolor='#9A3412', linewidth=1.1, zorder=3)
    ax2.grid(axis='x', zorder=0, alpha=0.6)
    ax2.set_xlim(0, 6.2)
    ax2.set_xlabel('Total Kerugian Finansial (Juta Rupiah)', fontsize=11, fontweight='bold', color='#1E293B')
    ax2.set_title("B. Top 8 Item Obat/Alkes dengan Kerugian Terbesar", fontsize=12, pad=12)

    for bar, v in zip(bars2, top_vals):
        pct_item = (v / vals_jt.sum()) * 100
        ax2.text(v + 0.12, bar.get_y() + bar.get_height()/2,
                 f"Rp {v:.2f} Jt ({pct_item:.1f}%)",
                 va='center', ha='left', fontsize=9, fontweight='bold', color='#7C2D12')

    # Kotak Ringkasan KPI di atas ax2
    summary_text = (
        "Temuan Kunci Audit ED:\n"
        "• Total Kerugian Riil: Rp 43.348.626,- (138 Item Terverifikasi)\n"
        "• Dominasi Kerugian: Reagen Tes Diagnostik Cepat (Malaria & Chikungunya = Rp 8,64 Jt)\n"
        "• Akar Masalah: Pembelian sisa pagu anggaran tanpa kontrol konsumsi riil & kelemahan sistem rotasi FEFO."
    )
    ax2.text(0.04, 0.28, summary_text, transform=ax2.transAxes, fontsize=8.8, color='#334155',
             bbox=dict(boxstyle='round,pad=0.5', facecolor='#F8FAFC', edgecolor='#94A3B8', alpha=0.95))

    path = os.path.join(VIZ_DIR, '06_analisis_kerugian_kadaluarsa.png')
    plt.savefig(path)
    plt.close()
    print(f"   -> Selesai: {path}")

# ==============================================================================
# 2. GRAFIK 15: TREN BULANAN 2024 VS DWIBULANAN (SARAN 1 PAK TEKAD)
# ==============================================================================
def generate_grafik_15():
    print("-> Membuat Grafik 15: Tren Bulanan 2024 vs Dwibulanan (Saran 1)...")
    pct_m = df_monthly_2024[df_monthly_2024['nama_obat'].str.contains('Parasetamol.*500', case=False, na=False)]
    met_m = df_monthly_2024[df_monthly_2024['nama_obat'].str.contains('Metformin.*500', case=False, na=False)]

    month_cols = [f'2024-{str(m).zfill(2)}' for m in range(1, 13)]
    month_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
    dwibulanan_labels = ['2024-P1\n(Jan-Feb)', '2024-P2\n(Mar-Apr)', '2024-P3\n(Mei-Jun)',
                         '2024-P4\n(Jul-Agu)', '2024-P5\n(Sep-Okt)', '2024-P6\n(Nov-Des)']

    pct_vals = pct_m[month_cols].values[0]
    met_vals = met_m[month_cols].values[0]

    # Agregasi Dwibulanan
    pct_bimonthly = [pct_vals[2*i] + pct_vals[2*i+1] for i in range(6)]
    met_bimonthly = [met_vals[2*i] + met_vals[2*i+1] for i in range(6)]

    fig, (ax1, ax2) = plt.subplots(1, 2, figsize=(15.5, 7.8), dpi=300)
    plt.subplots_adjust(top=0.83, bottom=0.20, left=0.08, right=0.95, wspace=0.28)

    add_header_card(fig,
                    "Komparasi Granularitas Waktu: Tren Bulanan (12 Titik) vs Dwibulanan (6 Titik) Tahun 2024",
                    "Implementasi Saran 1 Pak Tekad: Membandingkan Volatilitas Mikro Bulanan dengan Stabilitas Dwibulanan")

    # Panel 1: Tren Mikro Bulanan (Line chart dengan spline halus)
    x_nums = np.arange(12)
    x_smooth = np.linspace(0, 11, 200)
    spline_pct = make_interp_spline(x_nums, pct_vals, k=2)(x_smooth)
    spline_met = make_interp_spline(x_nums, met_vals, k=2)(x_smooth)

    ax1.plot(x_smooth, spline_pct, color='#1E40AF', linewidth=2.4, label='Parasetamol 500 mg (Kuratif Demam)', zorder=4)
    ax1.scatter(x_nums, pct_vals, color='#1E40AF', s=55, edgecolors='white', zorder=5)
    ax1.fill_between(x_smooth, 0, spline_pct, color='#1E40AF', alpha=0.08)

    ax1.plot(x_smooth, spline_met, color='#0D9488', linewidth=2.4, label='Metformin 500 mg (Kronis Diabetes)', zorder=4)
    ax1.scatter(x_nums, met_vals, color='#0D9488', s=55, edgecolors='white', zorder=5)
    ax1.fill_between(x_smooth, 0, spline_met, color='#0D9488', alpha=0.08)

    ax1.set_xticks(x_nums)
    ax1.set_xticklabels(month_labels)
    ax1.set_ylabel('Pemakaian Riil per Bulan (Butir)', fontsize=11, fontweight='bold', color='#1E293B')
    ax1.set_xlabel('Bulan Kalender 2024', fontsize=11, fontweight='bold', color='#1E293B')
    ax1.set_title("A. Tren Mikro Bulanan (12 Bulan / Volatilitas Tinggi)", fontsize=12, pad=12)
    ax1.grid(True, alpha=0.6)
    ax1.legend(loc='upper left', frameon=True, facecolor='white', framealpha=0.9)
    ax1.set_ylim(0, 6000)

    # Anotasi Puncak & Lembah
    ax1.annotate('Puncak Agustus\n5.230 tablet', xy=(7, pct_vals[7]), xytext=(7.1, 5450),
                 arrowprops=dict(facecolor='#1E40AF', shrink=0.08, width=1.2, headwidth=5),
                 fontsize=8.5, fontweight='bold', color='#1E40AF')

    # Panel 2: Tren Makro Dwibulanan (Grouped Bar Chart)
    x_b = np.arange(6)
    w = 0.35
    bars_pct = ax2.bar(x_b - w/2, pct_bimonthly, width=w, color='#3B82F6', edgecolor='#1D4ED8', label='Parasetamol 500 mg', zorder=3)
    bars_met = ax2.bar(x_b + w/2, met_bimonthly, width=w, color='#14B8A6', edgecolor='#0F766E', label='Metformin 500 mg', zorder=3)

    ax2.set_xticks(x_b)
    ax2.set_xticklabels(dwibulanan_labels, fontsize=8.5)
    ax2.set_ylabel('Total Pemakaian per Periode Dwibulanan (Butir)', fontsize=11, fontweight='bold', color='#1E293B')
    ax2.set_xlabel('Periode LPLPO Dwibulanan Resmi (2-Bulanan)', fontsize=11, fontweight='bold', color='#1E293B')
    ax2.set_title("B. Tren Makro Dwibulanan (6 Periode / Stabil Terpadu)", fontsize=12, pad=12)
    ax2.grid(True, axis='y', alpha=0.6)
    ax2.legend(loc='upper right', frameon=True, facecolor='white', framealpha=0.9)
    ax2.set_ylim(0, 11000)

    for b in bars_pct:
        h = b.get_height()
        ax2.text(b.get_x() + b.get_width()/2, h + 180, f"{int(h):,}", ha='center', va='bottom', fontsize=8, fontweight='bold', color='#1E40AF')

    for b in bars_met:
        h = b.get_height()
        ax2.text(b.get_x() + b.get_width()/2, h + 180, f"{int(h):,}", ha='center', va='bottom', fontsize=8, fontweight='bold', color='#0F766E')

    # Inset Card Perbandingan CV (Fair Apple-to-Apple)
    cv_info = (
        "Evaluasi Volatilitas Data (Apple-to-Apple 2024):\n"
        "• CV Rata-rata 1-Bulanan : 74,6% (Volatilitas Tinggi akibat jeda administrasi faktur)\n"
        "• CV Rata-rata 2-Bulanan : 51,9% (Pola Jauh Lebih Stabil untuk Model Moving Average)\n"
        "• Reduksi Variabilitas   : -22,7% Poin Persentase (Meredam noise tanpa menghilangkan tren)"
    )
    fig.text(0.18, 0.02, cv_info, fontsize=9, color='#1E293B', fontweight='semibold',
             bbox=dict(boxstyle='round,pad=0.5', facecolor='#FEF3C7', edgecolor='#F59E0B', alpha=0.95))

    path = os.path.join(VIZ_DIR, '15_tren_bulanan_2024_vs_dwibulanan.png')
    plt.savefig(path)
    plt.close()
    print(f"   -> Selesai: {path}")

# ==============================================================================
# 3. GRAFIK 08: ANALISIS KRISIS KEKOSONGAN STOK (STOCKOUT)
# ==============================================================================
def generate_grafik_08():
    print("-> Membuat Grafik 08: Analisis Krisis Stockout (Super Detail)...")
    top_drugs = [
        'Kaptopril tablet 25 mg',
        'Ibuprofen tablet 400 mg',
        'Metilprednisolon tablet 4 mg',
        'Ambroksol tablet 30 mg',
        'Amlodipin 10 mg tablet',
        'Deksametason tablet 0,5 mg',
        'Amlodipin tablet 5 mg',
        'Cetirizina kapsul 10 mg',
        'Parasetamol tablet 500 mg',
        'Vitamin B-Kompleks tablet',
        'Asam Mefenamat kaplet 500 mg',
        'Piroksikam  capsul 10 mg'
    ]

    stockout_counts = []
    df_obat = df_bimonthly[df_bimonthly['kategori'] == 'OBAT']

    for d in top_drugs:
        sub = df_obat[df_obat['nama_obat'] == d]
        cnt = (sub['stok_akhir'] == 0).sum()
        stockout_counts.append({'nama_obat': d, 'stockout_count': cnt})

    df_stock = pd.DataFrame(stockout_counts).sort_values('stockout_count', ascending=True)

    fig, (ax1, ax2) = plt.subplots(1, 2, figsize=(15.5, 7.5), dpi=300)
    plt.subplots_adjust(top=0.83, bottom=0.10, left=0.18, right=0.95, wspace=0.35)

    add_header_card(fig,
                    "Kuantifikasi Krisis Kekosongan Fisik Obat (Stockout SOH = 0) di Gudang Farmasi",
                    "UPTD Puskesmas Teluk Sasah • Analisis 482 Kejadian Kekosongan dari 15.781 Catatan Transaksi (2023 - 2026)")

    # Panel 1: Bar Chart Horisontal
    names = [d[:28] for d in df_stock['nama_obat']]
    counts = df_stock['stockout_count'].values
    colors = ['#EF4444' if c >= 7 else '#F97316' if c >= 4 else '#FBBF24' for c in counts]

    bars = ax1.barh(names, counts, color=colors, height=0.65, edgecolor='#7F1D1D', linewidth=0.9, zorder=3)
    ax1.grid(axis='x', zorder=0, alpha=0.6)
    ax1.set_xlim(0, 15.5)
    ax1.set_xlabel('Frekuensi Periode Mengalami Stok Habis / SOH = 0 (dari 22 Periode)', fontsize=10.5, fontweight='bold', color='#1E293B')
    ax1.set_title("A. 12 Obat Esensial dengan Frekuensi Stockout Tertinggi", fontsize=12, pad=12)

    for bar, c in zip(bars, counts):
        pct = (c / 22) * 100
        ax1.text(c + 0.25, bar.get_y() + bar.get_height()/2,
                 f"{c} Periode ({pct:.0f}%)",
                 va='center', ha='left', fontsize=9, fontweight='bold', color='#991B1B')

    # Panel 2: Kartu Metrik & Narasi Krisis Pelayanan
    ax2.axis('off')

    # KPI 1
    ax2.add_patch(patches.FancyBboxPatch((0.05, 0.72), 0.42, 0.22, boxstyle="round,pad=0.03", fc="#FEF2F2", ec="#F87171", lw=1.2))
    ax2.text(0.26, 0.85, "482 Kejadian", fontsize=15, fontweight='bold', color='#DC2626', ha='center')
    ax2.text(0.26, 0.77, "Total Stockout Riil (3,1% baris)", fontsize=9, color='#7F1D1D', ha='center')

    # KPI 2
    ax2.add_patch(patches.FancyBboxPatch((0.53, 0.72), 0.42, 0.22, boxstyle="round,pad=0.03", fc="#FFFBEB", ec="#FCD34D", lw=1.2))
    ax2.text(0.74, 0.85, "292 Obat", fontsize=15, fontweight='bold', color='#D97706', ha='center')
    ax2.text(0.74, 0.77, "Pernah Kosong Total di Gudang", fontsize=9, color='#92400E', ha='center')

    # KPI 3
    ax2.add_patch(patches.FancyBboxPatch((0.05, 0.44), 0.42, 0.22, boxstyle="round,pad=0.03", fc="#EFF6FF", ec="#93C5FD", lw=1.2))
    ax2.text(0.26, 0.57, "13 Periode", fontsize=15, fontweight='bold', color='#2563EB', ha='center')
    ax2.text(0.26, 0.49, "Kekosongan Kaptopril 25 mg", fontsize=9, color='#1E40AF', ha='center')

    # KPI 4
    ax2.add_patch(patches.FancyBboxPatch((0.53, 0.44), 0.42, 0.22, boxstyle="round,pad=0.03", fc="#F0FDF4", ec="#86EFAC", lw=1.2))
    ax2.text(0.74, 0.57, "4 Periode", fontsize=15, fontweight='bold', color='#16A34A', ha='center')
    ax2.text(0.74, 0.49, "Stockout Parasetamol 500 mg", fontsize=9, color='#14532D', ha='center')

    # Narasi Evaluasi Kebijakan
    narasi = (
        "IMPLIKASI KLINIS & METODOLOGIS KRISIS STOK:\n\n"
        "1. Bahaya terhadap Pasien:\n"
        "   Kekosongan obat vital seperti Kaptopril (antihipertensi) dan Parasetamol\n"
        "   (antipiretik) memaksa pasien membeli mandiri di apotek luar atau putus obat.\n\n"
        "2. Distorsi 'Censored Demand':\n"
        "   Saat stok habis, pengeluaran tercatat 0 di LPLPO. Tanpa rekonstruksi ilmiah,\n"
        "   rumus peramalan konvensional akan memprediksi kebutuhan mendekati 0,\n"
        "   memicu 'Stockout Spiral' (kekosongan berulang yang semakin parah).\n\n"
        "3. Solusi Terintegrasi PRABOTIK:\n"
        "   Penerapan Safety Stock 95% buffer (Z * σ * √L) dan sinyal momentum MACD\n"
        "   untuk memberi peringatan dini sebelum stok fisik mencapai angka nol."
    )
    ax2.text(0.05, 0.04, narasi, fontsize=9.2, color='#1E293B', va='bottom',
             bbox=dict(boxstyle='round,pad=0.6', facecolor='#F8FAFC', edgecolor='#CBD5E1', alpha=0.95))

    path = os.path.join(VIZ_DIR, '08_analisis_stockout_kosong.png')
    plt.savefig(path)
    plt.close()
    print(f"   -> Selesai: {path}")

# ==============================================================================
# 4. GRAFIK 01: TOP 15 OBAT DENGAN PEMAKAIAN TERTINGGI (PROGRAM VS KURATIF)
# ==============================================================================
def generate_grafik_01():
    print("-> Membuat Grafik 01: Top 15 Obat (Detail Program vs Kuratif)...")
    top15 = df_pivot.head(15).copy()
    top15.sort_values('total_pemakaian', ascending=True, inplace=True)

    fig, ax = plt.subplots(figsize=(15, 8.2), dpi=300)
    plt.subplots_adjust(top=0.85, bottom=0.10, left=0.24, right=0.94)

    add_header_card(fig,
                    "Peringkat 15 Obat dengan Volume Pemakaian Tertinggi (Periode 2023 - 2026)",
                    "UPTD Puskesmas Teluk Sasah • Klasifikasi Kategori Logistik Program Gizi Nasional vs Pelayanan Kuratif Harian")

    names = top15['nama_obat'].values
    vals = top15['total_pemakaian'].values

    # Pembeda Warna Kategori
    colors = []
    labels_badge = []
    for n in names:
        if 'Fero Sulfat' in n:
            colors.append('#D97706') # Amber Program Gizi
            labels_badge.append('[PROGRAM GIZI/KIA - DROP MASSAL]')
        elif 'Parasetamol' in n:
            colors.append('#059669') # Emerald Kuratif #1
            labels_badge.append('[#1 OBAT KURATIF POLI HARIAN]')
        elif 'Amlodipin' in n:
            colors.append('#2563EB') # Blue Kronis Hipertensi
            labels_badge.append('[OBAT KRONIS HIPERTENSI]')
        elif 'Metformin' in n:
            colors.append('#0891B2') # Cyan Kronis Diabetes
            labels_badge.append('[OBAT KRONIS DIABETES]')
        else:
            colors.append('#475569') # Slate Obat Simptomatik
            labels_badge.append('[OBAT SIMPTOMATIK / SUPORTIF]')

    bars = ax.barh(names, vals, color=colors, height=0.65, edgecolor='#0F172A', linewidth=0.9, zorder=3)
    ax.grid(axis='x', zorder=0, alpha=0.6)
    ax.set_xlim(0, 390000)
    ax.xaxis.set_major_formatter(FuncFormatter(thousands_fmt))
    ax.set_xlabel('Total Akumulasi Pemakaian Fisik (Tablet / Kapsul / Botol)', fontsize=11, fontweight='bold', color='#1E293B')

    for bar, v, badge in zip(bars, vals, labels_badge):
        ax.text(v + 3500, bar.get_y() + bar.get_height()/2,
                 f"{int(v):,} unit  {badge}",
                 va='center', ha='left', fontsize=8.8, fontweight='bold', color='#0F172A')

    # Legend Custom
    legend_elements = [
        patches.Patch(facecolor='#D97706', edgecolor='#0F172A', label='Obat Program Gizi (Drop Massal Sekolah/KIA, CV=2.20)'),
        patches.Patch(facecolor='#059669', edgecolor='#0F172A', label='Obat Kuratif Terbanyak #1 (Parasetamol 500 mg, CV=0.42)'),
        patches.Patch(facecolor='#2563EB', edgecolor='#0F172A', label='Obat Penyakit Kronis Degeneratif (Amlodipin & Metformin)'),
        patches.Patch(facecolor='#475569', edgecolor='#0F172A', label='Obat Kuratif Simptomatik / Suportif Lainnya')
    ]
    ax.legend(handles=legend_elements, loc='lower right', frameon=True, facecolor='white', framealpha=0.95, fontsize=9.2)

    path = os.path.join(VIZ_DIR, '01_top_obat_pemakaian.png')
    plt.savefig(path)
    plt.close()
    print(f"   -> Selesai: {path}")

# ==============================================================================
# 5. GRAFIK 07: SEBARAN FLUKTUASI (CV) PEMAKAIAN OBAT
# ==============================================================================
def generate_grafik_07():
    print("-> Membuat Grafik 07: Sebaran Fluktuasi CV (Super Detail)...")
    df_valid = df_pivot[(df_pivot['total_pemakaian'] >= 500) & (df_pivot['cv_pemakaian'] > 0)].copy()

    fig, ax = plt.subplots(figsize=(15.5, 7.8), dpi=300)
    plt.subplots_adjust(top=0.85, bottom=0.12, left=0.10, right=0.92)

    add_header_card(fig,
                    "Pemetaan Karakteristik Fluktuasi Konsumsi Obat (Koefisien Variasi / CV = σ / μ)",
                    "UPTD Puskesmas Teluk Sasah • Segmentasi 3 Zona Volatilitas untuk Menentukan Kelayakan Model Peramalan")

    # Zona Latar Belakang
    ax.axhspan(0, 0.5, color='#F0FDF4', alpha=0.9, zorder=1)
    ax.axhspan(0.5, 1.0, color='#FEFCE8', alpha=0.9, zorder=1)
    ax.axhspan(1.0, 2.8, color='#FEF2F2', alpha=0.9, zorder=1)

    ax.axhline(0.5, color='#16A34A', linestyle='--', linewidth=1.4, zorder=2)
    ax.axhline(1.0, color='#DC2626', linestyle='--', linewidth=1.4, zorder=2)

    # Label Zona di kiri dalam
    ax.text(35, 0.44, "ZONA 1: KONSUMSI SANGAT STABIL (CV < 0.5) — 38% Katalog", fontsize=9.5, fontweight='bold', color='#15803D')
    ax.text(35, 0.94, "ZONA 2: FLUKTUASI MODERAT / SIMPTOMATIK (0.5 ≤ CV ≤ 1.0) — 42% Katalog", fontsize=9.5, fontweight='bold', color='#A16207')
    ax.text(35, 2.55, "ZONA 3: SANGAT FLUKTUATIF / SPORADIS / PROGRAM (CV > 1.0) — 20% Katalog", fontsize=9.5, fontweight='bold', color='#B91C1C')

    # Scatter
    scatter = ax.scatter(df_valid['mean_pemakaian'], df_valid['cv_pemakaian'],
                         s=np.log1p(df_valid['total_pemakaian']) * 20,
                         c=df_valid['cv_pemakaian'], cmap='YlGnBu_r', alpha=0.85,
                         edgecolors='#1E293B', linewidth=0.7, zorder=4)

    # Highlight Obat Kunci
    highlights = [
        ('Amlodipin 10 mg tablet', 'Amlodipin 10mg (CV=0.38 - Kronis)', (1.08, 0.06)),
        ('Metformin tablet 500 mg', 'Metformin 500mg (CV=0.41 - Kronis)', (1.08, -0.06)),
        ('Parasetamol tablet 500 mg', 'Parasetamol 500mg (CV=0.42 - Demam)', (1.08, 0.08)),
        ('Amoksisilin kaplet 500 mg', 'Amoksisilin 500mg (CV=0.58 - Antibiotik)', (1.08, 0.08)),
        ('Ambroksol tablet 30 mg', 'Ambroksol 30mg (CV=0.74 - Batuk)', (1.08, 0.08)),
        ('Fero Sulfat tablet 300 mg (tablet tambah darah)', 'Fero Sulfat (CV=2.20 - Gizi Drop)', (0.45, 0.15)),
    ]

    for drug, label, offset in highlights:
        sub = df_valid[df_valid['nama_obat'] == drug]
        if not sub.empty:
            x = sub['mean_pemakaian'].values[0]
            y = sub['cv_pemakaian'].values[0]
            ax.annotate(label, xy=(x, y), xytext=(x * offset[0], y + offset[1]),
                        fontsize=9, fontweight='bold', color='#0F172A',
                        arrowprops=dict(arrowstyle='->', color='#0F172A', lw=1.2),
                        bbox=dict(boxstyle='round,pad=0.25', facecolor='#FFFFFF', edgecolor='#64748B', alpha=0.9),
                        zorder=6)

    ax.set_xscale('log')
    ax.set_xlim(25, 45000)
    ax.xaxis.set_major_formatter(FuncFormatter(thousands_fmt))
    ax.set_xlabel('Rata-rata Pemakaian Riil per Periode (Skala Logaritmik Butir)', fontsize=11, fontweight='bold', color='#1E293B')
    ax.set_ylabel('Koefisien Variasi (CV = Std Deviasi / Mean)', fontsize=11, fontweight='bold', color='#1E293B')
    ax.set_ylim(0, 2.7)
    ax.grid(True, which='both', alpha=0.5, zorder=0)

    path = os.path.join(VIZ_DIR, '07_sebaran_fluktuasi_cv.png')
    plt.savefig(path)
    plt.close()
    print(f"   -> Selesai: {path}")

# ==============================================================================
# 6. GRAFIK 02: TREN PENYAKIT ISPA (J00/J06) VS OBAT TERKAIT (DENGAN ANOTASI STOCKOUT)
# ==============================================================================
def generate_grafik_02():
    print("-> Membuat Grafik 02: Tren ISPA vs Obat Terkait (Anotasi Musim & Stockout)...")
    p_j00 = df_penyakit[df_penyakit['kode_icd'] == 'J00'].groupby('periode_bimonthly_id')['total_pasien'].sum().reindex(PERIODS).fillna(0)

    m_pct = df_pivot[df_pivot['nama_obat'].str.contains('Parasetamol tablet 500', case=False)][PERIODS].sum(axis=0).values
    m_amb = df_pivot[df_pivot['nama_obat'].str.contains('Ambroksol tablet 30', case=False)][PERIODS].sum(axis=0).values
    m_amx = df_pivot[df_pivot['nama_obat'].str.contains('Amoksisilin kaplet 500', case=False)][PERIODS].sum(axis=0).values

    fig, ax1 = plt.subplots(figsize=(15.5, 7.5), dpi=300)
    plt.subplots_adjust(top=0.83, bottom=0.14, left=0.08, right=0.92)

    add_header_card(fig,
                    "Korelasi Tren Penyakit ISPA (J00 / Common Cold) vs Pola Konsumsi Obat Terkait",
                    "UPTD Puskesmas Teluk Sasah • Anotasi Musim Angin Utara & Rekonstruksi Censored Demand Parasetamol 2026-P4")

    # Shading Zona Musim Angin Utara (Periode P5-P6 / Okt-Feb setiap tahun)
    for p_idx in [4, 5, 10, 11, 16, 17]:
        ax1.axvspan(p_idx - 0.4, p_idx + 0.4, color='#EFF6FF', alpha=0.85, zorder=1)

    ax1.text(10.5, 1120, "Musim Angin Barat/Utara (Lonjakan Infeksi Saluran Napas)",
             fontsize=8.5, fontweight='bold', color='#1D4ED8', ha='center',
             bbox=dict(boxstyle='round,pad=0.2', facecolor='#DBEAFE', edgecolor='#93C5FD'))

    # Sumbu Kiri: Pasien ISPA
    color_j00 = '#DC2626'
    l0 = ax1.plot(PERIODS, p_j00, color=color_j00, linewidth=2.8, marker='o', markersize=6.5,
                  label='Kasus Pasien J00 [Common Cold / ISPA]', zorder=5)
    ax1.set_ylabel('Jumlah Pasien Kasus ISPA (Orang / Periode)', color=color_j00, fontsize=11, fontweight='bold')
    ax1.tick_params(axis='y', labelcolor=color_j00)
    ax1.set_xticks(range(len(PERIODS)))
    ax1.set_xticklabels(PERIODS, rotation=45, ha='right', fontsize=9)
    ax1.set_xlabel('Periode Dwibulanan Kontinu (2023 - 2026)', fontsize=11, fontweight='bold', color='#1E293B')
    ax1.grid(True, alpha=0.5, zorder=0)
    ax1.set_ylim(0, 1200)

    # Sumbu Kanan: Obat
    ax2 = ax1.twinx()
    l1 = ax2.plot(PERIODS, m_pct, color='#2563EB', linewidth=2.4, marker='s', markersize=5.5,
                  linestyle='--', label='Parasetamol 500 mg (Antipiretik)', zorder=4)
    l2 = ax2.plot(PERIODS, m_amb, color='#7C3AED', linewidth=2.0, marker='^', markersize=5.5,
                  linestyle=':', label='Ambroksol 30 mg (Mukolitik Batuk)', zorder=4)
    l3 = ax2.plot(PERIODS, m_amx, color='#059669', linewidth=2.0, marker='D', markersize=5.0,
                  linestyle='-.', label='Amoksisilin 500 mg (Antibiotik)', zorder=4)

    ax2.set_ylabel('Pemakaian Obat Riil (Butir / Kaplet)', color='#2563EB', fontsize=11, fontweight='bold')
    ax2.tick_params(axis='y', labelcolor='#2563EB')
    ax2.set_ylim(-300, 12500)
    ax2.yaxis.set_major_formatter(FuncFormatter(thousands_fmt))

    # Anotasi Kritis Stockout Parasetamol 2026-P4
    ax2.annotate('KRISIS STOCKOUT TOTAL (2026-P4)!\nKasus ISPA 485 pasien, tapi stok fisik habis (0 butir).\nPRABOTIK merekonstruksi menjadi 5.850 butir!',
                 xy=(21, 0), xytext=(14.5, 3000),
                 arrowprops=dict(facecolor='#DC2626', shrink=0.08, width=1.5, headwidth=6),
                 fontsize=8.8, fontweight='bold', color='#B91C1C',
                 bbox=dict(boxstyle='round,pad=0.4', facecolor='#FEF2F2', edgecolor='#DC2626', alpha=0.95),
                 zorder=7)

    # Gabung Legenda
    lines = l0 + l1 + l2 + l3
    labels = [l.get_label() for l in lines]
    ax1.legend(lines, labels, loc='upper left', frameon=True, facecolor='white', framealpha=0.95, fontsize=9.2)

    path = os.path.join(VIZ_DIR, '02_tren_ispa_dan_obat.png')
    plt.savefig(path)
    plt.close()
    print(f"   -> Selesai: {path}")

# ==============================================================================
# 7. GRAFIK 03: TREN HIPERTENSI & DIABETES VS OBAT KRONIS
# ==============================================================================
def generate_grafik_03():
    print("-> Membuat Grafik 03: Tren Kronis Hipertensi & Diabetes vs Obat...")
    p_i10 = df_penyakit[df_penyakit['kode_icd'] == 'I10'].groupby('periode_bimonthly_id')['total_pasien'].sum().reindex(PERIODS).fillna(0)
    p_e11 = df_penyakit[df_penyakit['kode_icd'] == 'E11'].groupby('periode_bimonthly_id')['total_pasien'].sum().reindex(PERIODS).fillna(0)

    m_amlo = df_pivot[df_pivot['nama_obat'].str.contains('Amlodipin 10 mg', case=False)][PERIODS].sum(axis=0).values
    m_met = df_pivot[df_pivot['nama_obat'].str.contains('Metformin.*500', case=False)][PERIODS].sum(axis=0).values

    fig, ax1 = plt.subplots(figsize=(15.5, 7.5), dpi=300)
    plt.subplots_adjust(top=0.83, bottom=0.18, left=0.08, right=0.92)

    add_header_card(fig,
                    "Dinamika Terapi Penyakit Kronis Degeneratif: Hipertensi & Diabetes Melitus",
                    "UPTD Puskesmas Teluk Sasah • Karakteristik Kebutuhan Rutin Pasien Lansia BPJS (Pola Stabil Non-Musiman)")

    color_i10 = '#DC2626'
    color_e11 = '#D97706'

    l1 = ax1.plot(PERIODS, p_i10, color=color_i10, linewidth=2.4, marker='o', label='Kasus Hipertensi Primer (I10)')
    l2 = ax1.plot(PERIODS, p_e11, color=color_e11, linewidth=2.4, marker='s', label='Kasus Diabetes Melitus (E11)')
    ax1.set_ylabel('Jumlah Pasien Kronis Terdaftar (Orang)', color='#991B1B', fontsize=11, fontweight='bold')
    ax1.set_xticks(range(len(PERIODS)))
    ax1.set_xticklabels(PERIODS, rotation=45, ha='right', fontsize=9)
    ax1.set_xlabel('Periode Dwibulanan Kontinu (2023 - 2026)', fontsize=11, fontweight='bold', color='#1E293B')
    ax1.grid(True, alpha=0.5)
    ax1.set_ylim(0, 700)

    ax2 = ax1.twinx()
    l3 = ax2.plot(PERIODS, m_amlo, color='#2563EB', linewidth=2.4, marker='^', linestyle='--', label='Amlodipin 10 mg (Antihipertensi)')
    l4 = ax2.plot(PERIODS, m_met, color='#059669', linewidth=2.4, marker='D', linestyle='-.', label='Metformin 500 mg (Antidiabetes)')
    ax2.set_ylabel('Total Pemakaian Obat (Butir)', color='#1D4ED8', fontsize=11, fontweight='bold')
    ax2.yaxis.set_major_formatter(FuncFormatter(thousands_fmt))
    ax2.set_ylim(-1000, 31000)

    lines = l1 + l2 + l3 + l4
    labels = [l.get_label() for l in lines]
    ax1.legend(lines, labels, loc='upper left', frameon=True, facecolor='white', framealpha=0.95, fontsize=9.2)

    # Inset Card Penyakit Kronis di posisi aman bawah tengah
    info_box = (
        "Wawasan Terapi Kronis:\n"
        "• Pola Permintaan Sangat Stabil (CV Amlodipin = 0.38, Metformin = 0.41)\n"
        "• Tidak terpengaruh musim cuaca; fluktuasi hanya terjadi saat pasien Prolanis mengambil obat berkala\n"
        "• Sangat cocok dimodelkan dengan WMA-3 (Akurasi Akurasi Prediksi > 85%)"
    )
    fig.text(0.24, 0.02, info_box, fontsize=8.8, color='#1E293B',
             bbox=dict(boxstyle='round,pad=0.4', facecolor='#F8FAFC', edgecolor='#CBD5E1', alpha=0.95))

    path = os.path.join(VIZ_DIR, '03_tren_hipertensi_diabetes.png')
    plt.savefig(path)
    plt.close()
    print(f"   -> Selesai: {path}")

# ==============================================================================
# 8. GRAFIK 04: TREN PENYAKIT PENCERNAAN / DISPEPSIA VS ANTASIDA
# ==============================================================================
def generate_grafik_04():
    print("-> Membuat Grafik 04: Tren Dispepsia vs Antasida DOEN...")
    p_k30 = df_penyakit[df_penyakit['kode_icd'] == 'K30'].groupby('periode_bimonthly_id')['total_pasien'].sum().reindex(PERIODS).fillna(0)
    m_ant = df_pivot[df_pivot['nama_obat'].str.contains('Antasida DOEN I tablet', case=False)][PERIODS].sum(axis=0).values

    fig, ax1 = plt.subplots(figsize=(14.5, 6.8), dpi=300)
    plt.subplots_adjust(top=0.83, bottom=0.14, left=0.08, right=0.92)

    add_header_card(fig,
                    "Korelasi Tren Penyakit Lambung / Dispepsia (K30) vs Pemakaian Antasida DOEN",
                    "UPTD Puskesmas Teluk Sasah • Pola Berulang Kunjungan Pasien Keluhan Saluran Cerna")

    color_k30 = '#B45309'
    l1 = ax1.plot(PERIODS, p_k30, color=color_k30, linewidth=2.4, marker='o', label='Kasus Dispepsia / Gastritis (K30)')
    ax1.set_ylabel('Jumlah Pasien Kasus Lambung (Orang)', color=color_k30, fontsize=11, fontweight='bold')
    ax1.set_xticks(range(len(PERIODS)))
    ax1.set_xticklabels(PERIODS, rotation=45, ha='right', fontsize=9)
    ax1.set_xlabel('Periode Dwibulanan Kontinu (2023 - 2026)', fontsize=11, fontweight='bold', color='#1E293B')
    ax1.grid(True, alpha=0.5)
    ax1.set_ylim(0, 500)

    ax2 = ax1.twinx()
    l2 = ax2.plot(PERIODS, m_ant, color='#0D9488', linewidth=2.4, marker='^', linestyle='--', label='Antasida DOEN Tablet Kunyah')
    ax2.set_ylabel('Pemakaian Antasida (Tablet)', color='#0F766E', fontsize=11, fontweight='bold')
    ax2.yaxis.set_major_formatter(FuncFormatter(thousands_fmt))
    ax2.set_ylim(0, 5000)

    lines = l1 + l2
    labels = [l.get_label() for l in lines]
    ax1.legend(lines, labels, loc='upper left', frameon=True, facecolor='white', framealpha=0.95, fontsize=9.2)

    path = os.path.join(VIZ_DIR, '04_tren_pencernaan_dispepsia.png')
    plt.savefig(path)
    plt.close()
    print(f"   -> Selesai: {path}")

# ==============================================================================
# 9. GRAFIK 05: HEATMAP KORELASI PEARSON (PENYAKIT VS OBAT)
# ==============================================================================
def generate_grafik_05():
    print("-> Membuat Grafik 05: Heatmap Korelasi Pearson (Super Detail)...")
    disease_codes = ['J00', 'I10', 'K30', 'E11', 'A09', 'T14']
    disease_labels = [
        'J00 (Common Cold / ISPA)',
        'I10 (Hipertensi Primer)',
        'K30 (Dispepsia / Lambung)',
        'E11 (Diabetes Melitus)',
        'A09 (Diare / Gastroenteritis)',
        'T14 (Cedera / Luka Fisik)'
    ]

    drug_names = [
        'Parasetamol tablet 500 mg',
        'Amoksisilin kaplet 500 mg',
        'Ambroksol tablet 30 mg',
        'Cetirizina kapsul 10 mg',
        'Amlodipin 10 mg tablet',
        'Kaptopril tablet 25 mg',
        'Metformin tablet 500 mg',
        'Antasida DOEN I tablet komb : Al(OH) 200 mg + Mg (OH) 200 mg',
        'Asam Mefenamat kaplet 500 mg',
        'Vitamin B-Kompleks tablet'
    ]
    drug_short = [
        'Parasetamol 500mg',
        'Amoksisilin 500mg',
        'Ambroksol 30mg',
        'Cetirizina 10mg',
        'Amlodipin 10mg',
        'Kaptopril 25mg',
        'Metformin 500mg',
        'Antasida DOEN',
        'Asam Mefenamat',
        'Vit B-Kompleks'
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

    fig, ax = plt.subplots(figsize=(13.5, 7.8), dpi=300)
    plt.subplots_adjust(top=0.86, bottom=0.18, left=0.22, right=0.96)

    add_header_card(fig,
                    "Matriks Heatmap Korelasi Pearson (r): Morbiditas SIMPUS vs Konsumsi Obat LPLPO",
                    "UPTD Puskesmas Teluk Sasah • Validasi Ilmiah Hubungan Klinis antara Volume Diagnosis dan Permintaan Terapi")

    sns.heatmap(df_corr, annot=True, fmt='.2f', cmap='RdYlGn', vmin=-0.5, vmax=0.85,
                cbar_kws={'label': 'Koefisien Korelasi Pearson (r)', 'shrink': 0.85},
                linewidths=1.0, linecolor='white', ax=ax,
                annot_kws={'fontsize': 9.5, 'fontweight': 'bold'})

    ax.set_xticklabels(ax.get_xticklabels(), rotation=35, ha='right', fontsize=9.5, fontweight='bold')
    ax.set_yticklabels(ax.get_yticklabels(), fontsize=9.5, fontweight='bold')

    path = os.path.join(VIZ_DIR, '05_heatmap_korelasi_penyakit_obat.png')
    plt.savefig(path)
    plt.close()
    print(f"   -> Selesai: {path}")

# ==============================================================================
# MAIN RUNNER
# ==============================================================================
if __name__ == '__main__':
    generate_grafik_06()
    generate_grafik_15()
    generate_grafik_08()
    generate_grafik_01()
    generate_grafik_07()
    generate_grafik_02()
    generate_grafik_03()
    generate_grafik_04()
    generate_grafik_05()
    print("=" * 80)
    print("SELURUH 9 GRAFIK EDA TELAH BERHASIL DIGENERATE DENGAN KUALITAS TINGGI!")
    print("=" * 80)
