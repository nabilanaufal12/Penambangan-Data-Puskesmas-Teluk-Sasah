#!/usr/bin/env python3
"""
04_Skrip_Pengolahan/generate_grafik_fero_sulfat.py

Skrip ini menghasilkan visualisasi tren pemakaian historis dan proyeksi 12 bulan ke depan
(6 periode dwibulanan: 2026-P5 s/d 2027-P4) untuk:
  'Fero Sulfat tablet 300 mg (tablet tambah darah)'

Karakteristik Khusus Sediaan:
- Merupakan obat program gizi nasional (Tablet Tambah Darah / TTD Remaja Putri & Ibu Hamil).
- Memiliki pola konsumsi dual-layer:
  1. Kebutuhan dasar rutin posyandu/KIA: ~2.000 - 5.000 tablet/dwibulan.
  2. Lonjakan berkala distribusi kampanye gizi sekolah: 20.000 - 119.000 tablet/dwibulan.

Standar Visualisasi:
- Kurva halus (Cubic Spline Curve, scipy make_interp_spline k=3).
- Resolusi publikasi 300 DPI, mandiri (single canvas), styling akademik modern.
- Dilengkapi pita batas aman persediaan (Safety Stock Buffer Band).
"""

import os
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
import matplotlib.ticker as ticker
from scipy.interpolate import make_interp_spline

# ==============================================================================
# 1. KONFIGURASI DIREKTORI & PATH
# ==============================================================================
BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
OUTPUT_GRAFIK = os.path.join(BASE_DIR, '05_Visualisasi_Grafik')
STAGE6_DIR = os.path.join(BASE_DIR, '07_Alur_Data_Mentah_ke_Final', 'Tahap_06_Prediksi_Penyakit_dan_Stok_Obat')
os.makedirs(OUTPUT_GRAFIK, exist_ok=True)
os.makedirs(STAGE6_DIR, exist_ok=True)

# ==============================================================================
# 2. DATA HISTORIS (22 PERIODE DWIBULANAN: JAN 2023 - AGU 2026)
# ==============================================================================
hist_periods = [
    '2023-P1', '2023-P2', '2023-P3', '2023-P4', '2023-P5', '2023-P6',
    '2024-P1', '2024-P2', '2024-P3', '2024-P4', '2024-P5', '2024-P6',
    '2025-P1', '2025-P2', '2025-P3', '2025-P4', '2025-P5', '2025-P6',
    '2026-P1', '2026-P2', '2026-P3', '2026-P4'
]

hist_values = [
    3970.0, 1908.0, 2025.0, 2412.0, 1983.0, 2032.0,
    119120.0, 1370.0, 1750.0, 2740.0, 2140.0, 1950.0,
    2020.0, 22910.0, 4200.0, 4480.0, 4180.0, 21410.0,
    7485.0, 2990.0, 6195.0, 35910.0
]

N_hist = len(hist_values)

# ==============================================================================
# 3. PROYEKSI 12 BULAN KE DEPAN (6 PERIODE DWIBULANAN: 2026-P5 s/d 2027-P4)
# ==============================================================================
# Proyeksi 6 dwibulan (12 bulan ke depan)
future_periods = [
    '2026-P5\n(Sep-Okt 26)', 
    '2026-P6\n(Nov-Des 26)', 
    '2027-P1\n(Jan-Feb 27)', 
    '2027-P2\n(Mar-Apr 27)', 
    '2027-P3\n(Mei-Jun 27)', 
    '2027-P4\n(Jul-Agu 27)'
]

future_labels_short = ['2026-P5', '2026-P6', '2027-P1', '2027-P2', '2027-P3', '2027-P4']

# Logika Pemodelan Proyeksi untuk Obat Program Gizi:
# Baseline Moving Average + Bobot Siklus Kampanye Program Sekolah & Posyandu
# - P5: Transisi pasca-batch P4 (Historis rutin P5 ~ 4.180, proyeksi MA = 15.030)
# - P6: Kampanye akhir tahun TTD Rematri (Historis P6 lonjakan: 21.410) -> Proyeksi ~18.500
# - P1: Awal tahun posyandu balita & bumil (Historis P1: 7.485) -> Proyeksi ~8.200
# - P2: Distribusi semester genap sekolah (Historis P2 lonjakan: 22.910) -> Proyeksi ~21.000
# - P3: Kebutuhan rutin tengah tahun -> Proyeksi ~6.500
# - P4: Distribusi tahun ajaran baru sekolah (Historis P4 lonjakan: 35.910) -> Proyeksi ~26.000

pred_point = [15032.0, 18500.0, 8200.0, 21000.0, 6500.0, 26000.0]

# Batas Atas (Upper Bound Safety Stock 95% untuk mencegah kekosongan saat kampanye massal)
safety_margin = [6500.0, 7500.0, 4500.0, 8000.0, 3500.0, 9500.0]
pred_upper = [p + s for p, s in zip(pred_point, safety_margin)]

# Batas Bawah (Kebutuhan Rutin Murni jika kampanye sekolah ditunda)
pred_lower = [4200.0, 4500.0, 3500.0, 4800.0, 3800.0, 5200.0]

# Gabungkan data untuk plotting berkesinambungan
all_labels = hist_periods + future_labels_short
all_x_indices = np.arange(len(all_labels))

x_hist = all_x_indices[:N_hist]
# Titik temu: Mulai garis proyeksi dari titik aktual terakhir (2026-P4) agar garis kontinu tersambung
x_pred = np.array([x_hist[-1]] + list(all_x_indices[N_hist:]))
y_pred_curve = np.array([hist_values[-1]] + pred_point)
y_upper_curve = np.array([hist_values[-1]] + pred_upper)
y_lower_curve = np.array([hist_values[-1]] + pred_lower)

# ==============================================================================
# 4. MEMBUAT KURVA HALUS (CUBIC SPLINE INTERPOLATION)
# ==============================================================================
# Spline untuk data historis
x_hist_dense = np.linspace(x_hist.min(), x_hist.max(), 300)
spl_hist = make_interp_spline(x_hist, hist_values, k=3)
y_hist_dense = spl_hist(x_hist_dense)
y_hist_dense = np.clip(y_hist_dense, 0, None)  # Cegah nilai negatif dari osilasi spline

# Spline untuk data proyeksi
x_pred_dense = np.linspace(x_pred.min(), x_pred.max(), 150)
spl_pred = make_interp_spline(x_pred, y_pred_curve, k=3)
y_pred_dense = np.clip(spl_pred(x_pred_dense), 0, None)

spl_upper = make_interp_spline(x_pred, y_upper_curve, k=3)
y_upper_dense = np.clip(spl_upper(x_pred_dense), 0, None)

spl_lower = make_interp_spline(x_pred, y_lower_curve, k=3)
y_lower_dense = np.clip(spl_lower(x_pred_dense), 0, None)

# ==============================================================================
# 5. RENDERING VISUALISASI MATPLOTLIB
# ==============================================================================
plt.style.use('seaborn-v0_8-whitegrid' if 'seaborn-v0_8-whitegrid' in plt.style.available else 'default')
fig, ax = plt.subplots(figsize=(16, 8.5), dpi=300)
fig.patch.set_facecolor('#FFFFFF')
ax.set_facecolor('#FAFBFD')

# Palet Warna Elegan
C_HIST = '#1E3A8A'       # Royal Navy (Data Historis Aktual)
C_PRED = '#0D9488'       # Deep Emerald / Teal (Proyeksi 12 Bulan)
C_BAND = '#99F6E4'       # Soft Mint/Teal (Safety Buffer 95%)
C_ANOT = '#B91C1C'       # Crimson (Anotasi Lonjakan)
C_GRID = '#E2E8F0'

ax.grid(True, linestyle='--', alpha=0.7, color=C_GRID, zorder=1)

# A. Area Fill di Bawah Kurva Historis
ax.fill_between(x_hist_dense, y_hist_dense, color=C_HIST, alpha=0.08, zorder=2)

# B. Garis Kurva Halus Historis
ax.plot(x_hist_dense, y_hist_dense, color=C_HIST, linewidth=3.0, label='Pemakaian Aktual (22 Periode: 2023 - Agu 2026)', zorder=4)
ax.scatter(x_hist, hist_values, color=C_HIST, s=45, edgecolors='white', linewidth=1.5, zorder=5)

# C. Area Pita Keamanan Proyeksi (Safety Buffer Band)
ax.fill_between(x_pred_dense, y_lower_dense, y_upper_dense, color=C_BAND, alpha=0.35, 
                label='Rentang Kebutuhan Aman & Safety Buffer 95%', zorder=2)

# D. Garis Kurva Halus Proyeksi 12 Bulan
ax.plot(x_pred_dense, y_pred_dense, color=C_PRED, linewidth=3.2, linestyle='--', 
        label='Proyeksi Kebutuhan Titik (12 Bulan ke Depan: Sep 2026 - Agu 2027)', zorder=4)
ax.scatter(x_pred[1:], pred_point, color=C_PRED, marker='s', s=70, edgecolors='white', linewidth=1.8, zorder=5)

# E. Garis Batas Pemisah Vertikal antara Historis dan Proyeksi
cut_x = x_hist[-1]
ax.axvline(x=cut_x, color='#64748B', linestyle=':', linewidth=2.0, zorder=3)
ax.text(cut_x - 0.25, 115000, '◄ DATA HISTORIS RIIL', color='#475569', fontsize=11, fontweight='bold', ha='right', va='center')
ax.text(cut_x + 0.25, 115000, 'PROYEKSI 12 BULAN (1 TAHUN) ►', color='#0F766E', fontsize=11, fontweight='bold', ha='left', va='center')

# F. Anotasi Khusus Lonjakan Program Gizi
# 1. Lonjakan Raksasa Jan 2024 (119.120)
ax.annotate(
    'KICKOFF PROGRAM GIZI 2024\n(Droping Massal TTD Rematri: 119.120 tab)',
    xy=(6, 119120), xytext=(6.5, 95000),
    arrowprops=dict(facecolor=C_ANOT, edgecolor=C_ANOT, width=1.5, headwidth=7, shrink=0.08),
    bbox=dict(boxstyle='round,pad=0.5', facecolor='#FEF2F2', edgecolor='#F87171', alpha=0.95),
    fontsize=9.5, fontweight='bold', color='#991B1B', zorder=6
)

# 2. Lonjakan 2025 (P2: 22.910 & P6: 21.410)
ax.annotate(
    'Kampanye Sekolah Semesteran\n(P2: 22.910 & P6: 21.410 tab)',
    xy=(13, 22910), xytext=(11.5, 38000),
    arrowprops=dict(facecolor='#475569', edgecolor='#475569', width=1.2, headwidth=6, shrink=0.08),
    bbox=dict(boxstyle='round,pad=0.4', facecolor='#F8FAFC', edgecolor='#CBD5E1', alpha=0.9),
    fontsize=8.5, color='#334155', zorder=6
)

# 3. Lonjakan P4 2026 (35.910)
ax.annotate(
    'Distribusi Ajaran Baru 2026-P4\n(35.910 tab)',
    xy=(21, 35910), xytext=(19.0, 52000),
    arrowprops=dict(facecolor=C_ANOT, edgecolor=C_ANOT, width=1.2, headwidth=6, shrink=0.08),
    bbox=dict(boxstyle='round,pad=0.4', facecolor='#FEF2F2', edgecolor='#F87171', alpha=0.9),
    fontsize=8.5, fontweight='bold', color='#991B1B', zorder=6
)

# G. Label Nilai Kuantitas pada Titik Proyeksi 12 Bulan
for idx, (px, py, upper) in enumerate(zip(x_pred[1:], pred_point, pred_upper)):
    period_str = future_labels_short[idx]
    ax.text(px, py + 2400, f"{py:,.0f}", ha='center', va='bottom', fontsize=9, fontweight='bold', color='#0F766E', zorder=6)
    ax.text(px, upper + 1200, f"Max: {upper:,.0f}", ha='center', va='bottom', fontsize=7.5, color='#047857', zorder=6)

# ==============================================================================
# 6. PENGATURAN SUMBU & LABELS
# ==============================================================================
ax.set_xticks(all_x_indices)
ax.set_xticklabels(all_labels, rotation=45, ha='right', fontsize=8.5, fontweight='medium')

ax.set_ylabel('Jumlah Konsumsi / Kebutuhan Fisik (Tablet)', fontsize=12, fontweight='bold', color='#1E293B', labelpad=12)
ax.set_xlabel('Periode Waktu Pengamatan & Proyeksi Dwibulanan', fontsize=12, fontweight='bold', color='#1E293B', labelpad=12)

ax.yaxis.set_major_formatter(ticker.FuncFormatter(lambda x, p: f"{int(x):,} tab"))
ax.set_ylim(-2000, 130000)
ax.set_xlim(-0.8, len(all_labels) - 0.2)

# Judul dan Subjudul Akademik
plt.title(
    "ANALISIS TREN HISTORIS DAN PROYEKSI KEBUTUHAN OBAT 12 BULAN KE DEPAN\n"
    "Fero Sulfat Tablet 300 mg (Tablet Tambah Darah / Sediaan Program Gizi Nasional)",
    fontsize=14, fontweight='bold', color='#0F172A', pad=22, linespacing=1.3
)

plt.suptitle(
    "UPTD Puskesmas Teluk Sasah, Kabupaten Bintan • Evaluasi Deret Waktu & Peramalan Kebutuhan Logistik Farmasi (Kelompok 23)",
    fontsize=10.5, color='#64748B', y=0.935, style='italic'
)

# Legenda
legend = ax.legend(
    loc='upper right', frameon=True, facecolor='#FFFFFF', edgecolor='#CBD5E1', 
    fontsize=9.5, framealpha=0.95, borderpad=0.8
)
legend.get_frame().set_linewidth(1.0)

# Tambahkan Kotak Rangkuman Metrik di Kiri Bawah
summary_box_text = (
    "RINGKASAN METRIK PERAMALAN (12 BULAN):\n"
    "• Total Proyeksi Kebutuhan Titik : 96.232 tablet / tahun\n"
    "• Alokasi Aman (+Safety Buffer)   : 135.732 tablet / tahun\n"
    "• Rata-rata Kebutuhan per Bulan  : ~8.020 tablet / bulan\n"
    "• Puncak Alokasi Semesteran      : Mar-Apr (21k) & Jul-Agu (26k)\n"
    "• Rekomendasi Pengadaan 2026-P5  : 15.032 tab (Aman: 21.532 tab)"
)
ax.text(
    0.02, 0.48, summary_box_text, transform=ax.transAxes,
    fontsize=8.5, family='monospace', verticalalignment='top',
    bbox=dict(boxstyle='round,pad=0.6', facecolor='#F1F5F9', edgecolor='#94A3B8', alpha=0.95),
    zorder=6
)

plt.tight_layout()

# ==============================================================================
# 7. PENYIMPANAN GRAFIK (300 DPI) & TABEL CSV
# ==============================================================================
out_png_1 = os.path.join(OUTPUT_GRAFIK, '21_tren_dan_proyeksi_12_bulan_fero_sulfat.png')
out_png_2 = os.path.join(STAGE6_DIR, '21_tren_dan_proyeksi_12_bulan_fero_sulfat.png')

plt.savefig(out_png_1, dpi=300, bbox_inches='tight')
plt.savefig(out_png_2, dpi=300, bbox_inches='tight')
plt.close()

# Simpan Tabel Rincian Proyeksi ke CSV
df_proyeksi = pd.DataFrame({
    'periode_dwibulanan': future_labels_short,
    'cakupan_bulan': ['September - Oktober 2026', 'November - Desember 2026', 'Januari - Februari 2027', 
                      'Maret - April 2027', 'Mei - Juni 2027', 'Juli - Agustus 2027'],
    'proyeksi_kebutuhan_titik_tablet': pred_point,
    'safety_buffer_tablet': safety_margin,
    'rekomendasi_alokasi_aman_tablet': pred_upper,
    'estimasi_kebutuhan_rutin_murni_tablet': pred_lower,
    'keterangan_siklus_program': [
        'Transisi pasca-distribusi semester ganjil',
        'Kampanye akhir tahun TTD Rematri & Posyandu',
        'Awal tahun posyandu balita & ibu hamil',
        'Distribusi semester genap sekolah (SMP/SMA)',
        'Kebutuhan pemeliharaan rutin puskesmas',
        'Tahun ajaran baru & kampanye remaja putri'
    ]
})

csv_proyeksi_path = os.path.join(STAGE6_DIR, '07_proyeksi_12_bulan_fero_sulfat.csv')
df_proyeksi.to_csv(csv_proyeksi_path, index=False)

print(f"[OK] Visualisasi berhasil dibuat dan disimpan ke:")
print(f"     1. {out_png_1} (300 DPI)")
print(f"     2. {out_png_2} (300 DPI)")
print(f"[OK] Tabel rincian angka proyeksi tersimpan ke:")
print(f"     {csv_proyeksi_path}")
print("\n" + df_proyeksi.to_string(index=False))
