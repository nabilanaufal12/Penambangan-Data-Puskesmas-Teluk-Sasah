#!/usr/bin/env python3
"""
scripts/build_colab_notebook.py

Skrip untuk membuat Google Colab Notebook (.ipynb) interaktif dan terstruktur
untuk Tugas Besar Penambangan Data Kelompok 23 (Puskesmas Teluk Sasah).
"""

import json
import os

OUTPUT_NOTEBOOK = os.path.join(os.path.dirname(os.path.dirname(os.path.abspath(__file__))),
                               '06_Google_Colab',
                               'Proyek_Penambangan_Data_Puskesmas_Teluk_Sasah.ipynb')

cells = []

def add_md(text):
    cells.append({
        "cell_type": "markdown",
        "metadata": {},
        "source": [line + "\n" for line in text.strip().split("\n")]
    })

def add_code(text):
    cells.append({
        "cell_type": "code",
        "execution_count": None,
        "metadata": {},
        "outputs": [],
        "source": [line + "\n" for line in text.strip().split("\n")]
    })

# ==============================================================================
# CELL 1: HEADER & IDENTITAS PROYEK
# ==============================================================================
add_md("""# 🏥 PROYEK PENAMBANGAN DATA (INF11121)
## **Prediksi Kebutuhan Stok Obat dan Analisis Tren Penyakit Musiman Berdasarkan Pola Pemakaian Obat pada Puskesmas Teluk Sasah di Kabupaten Bintan**

---
### 👥 **Identitas Tim (Kelompok 23)**:
* **Ketua**: 2401020069 – Balqis Manisa
* **Anggota**:
  * 2401020056 – Jian Safitri Wibowo
  * 2401020073 – Nabil
  * 2401020087 – Annisa Darmawan
  * 2401020110 – Yehezkiel Alman Siambaton
* **Dosen Pengampu**: Tekad Matulatan, S.Sos., S.Kom., M.Info.Tech. & Muhamad Fadli, S.Kom., M.Kom.
* **Program Studi**: S1 Teknik Informatika, Fakultas Teknik dan Teknologi Kemaritiman, Universitas Maritim Raja Ali Haji (UMRAH)
* **Semester**: Ganjil 2026/2027

---
### 📌 **Ringkasan Alur Pengerjaan pada Notebook Ini**:
1. **Langkah 1: Setup & Persiapan Berkas Data**: Upload dataset riil puskesmas ke Google Colab.
2. **Langkah 2: Preprocessing & Penyelarasan Waktu (Script 1)**: Mengekstrak 29 berkas LPLPO mentah (2023–2026) menjadi 22 periode dwibulanan kontinu.
3. **Langkah 3: Exploratory Data Analysis & Analisis Korelasi Klinis (Script 2)**: Menemukan obat dominan, korelasi dengan diagnosis penyakit (ISPA, Hipertensi, Diabetes, Dispepsia), serta membuktikan masalah riil *overstock* (kadaluarsa Rp 43 Juta) dan *stockout*.
4. **Langkah 4: Pemodelan Time Series Forecasting (Script 3)**: Menerapkan algoritma *Simple Moving Average* (SMA-2, SMA-3) dan *Weighted Moving Average* (WMA-3) lengkap dengan detail perhitungan matematis bertahap.
5. **Langkah 5: Evaluasi Akurasi (MAPE) & Rekomendasi Pengadaan**: Mengukur galat prediksi dan menghitung *Safety Stock* (95%) untuk perencanaan permintaan obat ke Dinas Kesehatan.""")

# ==============================================================================
# CELL 2: PANDUAN UPLOAD DATA DI GOOGLE COLAB
# ==============================================================================
add_md("""---
## 🛠️ Langkah 1: Setup Lingkungan & Upload Data ke Colab

### **Cara Menyiapkan File Data di Google Colab**:
Anda memerlukan 3 berkas data asli:
1. `LPLPO TEKSAS 23-26.zip` (Berisi 29 berkas laporan pemakaian obat 2023–2026)
2. `DATA PENYAKIT UPTD. PUSKESMAS TELUK SASAH.xlsx` (Data 10 besar penyakit bulanan)
3. `relplpo2023.zip` (Laporan obat kadaluarsa 2023–2025)

> 💡 **Petunjuk Upload**:
> * **Opsi A (Upload Langsung)**: Klik ikon folder (📁 *Files*) di bilah sisi kiri Google Colab, lalu tarik (*drag & drop*) ketiga berkas di atas langsung ke area file `/content`.
> * **Opsi B (Google Drive)**: Simpan folder dataset di Google Drive Anda, lalu jalankan perintah `drive.mount('/content/drive')` di bawah ini.""")

# ==============================================================================
# CELL 3: INSTALASI DEPENDENCIES
# ==============================================================================
add_code("""# 1. Instalasi pustaka pendukung yang diperlukan di lingkungan Google Colab
!pip install --quiet openpyxl xlrd scipy seaborn matplotlib pandas numpy

import os, sys, glob, io, re, zipfile
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns
from scipy.stats import pearsonr, spearmanr
import xlrd, openpyxl

print("✅ Seluruh pustaka berhasil dimuat dengan sukses!")""")

# ==============================================================================
# CELL 4: DETEKSI FILE OTOMATIS
# ==============================================================================
add_code("""# 2. Fungsi otomatis mendeteksi lokasi berkas data (di folder lokal colab maupun drive)
def find_data_file(pattern):
    # Cari di direktori kerja saat ini dan subfolder
    candidates = glob.glob(f"**/{pattern}", recursive=True) + glob.glob(f"/content/**/{pattern}", recursive=True)
    if candidates:
        return os.path.abspath(candidates[0])
    return None

zip_lplpo_path = find_data_file("*LPLPO*.zip")
excel_penyakit_path = find_data_file("*PENYAKIT*.xlsx")
zip_kadaluarsa_path = find_data_file("*relplpo*.zip")

print("Status Penemuan Berkas:")
print(f"📦 Berkas LPLPO Zip       : {'✅ ' + zip_lplpo_path if zip_lplpo_path else '❌ Belum diupload (Unggah LPLPO TEKSAS 23-26.zip)'}")
print(f"📊 Berkas Penyakit Excel  : {'✅ ' + excel_penyakit_path if excel_penyakit_path else '❌ Belum diupload (Unggah DATA PENYAKIT UPTD. PUSKESMAS TELUK SASAH.xlsx)'}")
print(f"⚠️ Berkas Kadaluarsa Zip  : {'✅ ' + zip_kadaluarsa_path if zip_kadaluarsa_path else '❌ Belum diupload (Unggah relplpo2023.zip)'}")

if not all([zip_lplpo_path, excel_penyakit_path, zip_kadaluarsa_path]):
    print("\\n⚠️ PERINGATAN: Pastikan Anda telah mengupload ketiga berkas ke Google Colab!")
else:
    print("\\n🎉 Semua berkas data telah siap untuk diproses!")""")

# ==============================================================================
# CELL 5: PREPROCESSING SCRIPT 1
# ==============================================================================
add_md("""---
## 🧹 Langkah 2: Preprocessing & Penyelarasan Waktu (Script 1)

### **Latar Belakang Masalah Data Mentah**:
* Data LPLPO tahun 2024 dicatat secara **bulanan** (12 berkas).
* Data LPLPO tahun 2023, 2025, dan 2026 dicatat secara **dwibulanan (2-bulanan)**.
* **Solusi Penambangan Data**: Kita menyelaraskan seluruh transaksi farmasi menjadi **22 Periode Dwibulanan Kontinu (Kronologis)**:
  $$\\text{2023-P1} \\longrightarrow \\text{2026-P4} \\quad (\\text{Total 3 Tahun 8 Bulan Tanpa Jeda})$$
* **Variabel Target**: $\\text{Pemakaian} = \\sum \\text{pemakaian obat pasien selama 2 bulan}$.

Mari kita jalankan proses ekstraksi, pembersihan inkonsistensi string/satuan, dan agregasi deret waktu.""")

add_code("""# 3. Ekstraksi dan Pembersihan Data Granular LPLPO (Script 1)
OUTPUT_DIR = "data_processed"
os.makedirs(OUTPUT_DIR, exist_ok=True)

FILE_CONFIGS = [
    # 2023
    ('2023/Januari 2023.xls', 2023, '2023-01', 'Januari', '2023-P1', 'Jan - Feb 2023', 1, 1),
    ('2023/Februari 2023.xls', 2023, '2023-02', 'Februari', '2023-P1', 'Jan - Feb 2023', 1, 2),
    ('2023/Maret - April 2023.xls', 2023, '2023-03_04', 'Maret - April', '2023-P2', 'Mar - Apr 2023', 2, 0),
    ('2023/Mei - Juni 2023.xls', 2023, '2023-05_06', 'Mei - Juni', '2023-P3', 'Mei - Jun 2023', 3, 0),
    ('2023/Juli - Agustus 2023.xls', 2023, '2023-07_08', 'Juli - Agustus', '2023-P4', 'Jul - Agu 2023', 4, 0),
    ('2023/September -  Oktober 2023.xls', 2023, '2023-09_10', 'September - Oktober', '2023-P5', 'Sep - Okt 2023', 5, 0),
    ('2023/November - Desember 2023.xls', 2023, '2023-11_12', 'November - Desember', '2023-P6', 'Nov - Des 2023', 6, 0),
    # 2024
    ('2024/1 JANUARI 2024.xls', 2024, '2024-01', 'Januari', '2024-P1', 'Jan - Feb 2024', 7, 1),
    ('2024/2 FEBRUARI 2024.xls', 2024, '2024-02', 'Februari', '2024-P1', 'Jan - Feb 2024', 7, 2),
    ('2024/3 MARET 2024.xls', 2024, '2024-03', 'Maret', '2024-P2', 'Mar - Apr 2024', 8, 1),
    ('2024/4 APRIL 2024.xls', 2024, '2024-04', 'April', '2024-P2', 'Mar - Apr 2024', 8, 2),
    ('2024/5 MEI 2024.xls', 2024, '2024-05', 'Mei', '2024-P3', 'Mei - Jun 2024', 9, 1),
    ('2024/6 JUNII 2024.xls', 2024, '2024-06', 'Juni', '2024-P3', 'Mei - Jun 2024', 9, 2),
    ('2024/7 JULI 2024.xls', 2024, '2024-07', 'Juli', '2024-P4', 'Jul - Agu 2024', 10, 1),
    ('2024/8 FIX AGUSTUS 2024.xlsx', 2024, '2024-08', 'Agustus', '2024-P4', 'Jul - Agu 2024', 10, 2),
    ('2024/9 SEPTEMBER 2024.xlsx', 2024, '2024-09', 'September', '2024-P5', 'Sep - Okt 2024', 11, 1),
    ('2024/10 OKTOBER 2024.xlsx', 2024, '2024-10', 'Oktober', '2024-P5', 'Sep - Okt 2024', 11, 2),
    ('2024/11 NOVEMBER 2024.xlsx', 2024, '2024-11', 'November', '2024-P6', 'Nov - Des 2024', 12, 1),
    ('2024/12 LPLPO DESEMBER 2024.xlsx', 2024, '2024-12', 'Desember', '2024-P6', 'Nov - Des 2024', 12, 2),
    # 2025
    ('2025/1 LPLPO JAN-FEB 2025.xls', 2025, '2025-01_02', 'Januari - Februari', '2025-P1', 'Jan - Feb 2025', 13, 0),
    ('2025/2 LPLPO MAR-APR 2025.xls', 2025, '2025-03_04', 'Maret - April', '2025-P2', 'Mar - Apr 2025', 14, 0),
    ('2025/3 MEI-JUN 2025.xls', 2025, '2025-05_06', 'Mei - Juni', '2025-P3', 'Mei - Jun 2025', 15, 0),
    ('2025/4 JULI - AGUSTUS 2025.xls', 2025, '2025-07_08', 'Juli - Agustus', '2025-P4', 'Jul - Agu 2025', 16, 0),
    ('2025/5 SEP - OKT 2025.xls', 2025, '2025-09_10', 'September - Oktober', '2025-P5', 'Sep - Okt 2025', 17, 0),
    ('2025/6 NOV - DES 25 Revisi.xls', 2025, '2025-11_12', 'November - Desember', '2025-P6', 'Nov - Des 2025', 18, 0),
    # 2026
    ('2026/1 JAN - FEB 2026.xls', 2026, '2026-01_02', 'Januari - Februari', '2026-P1', 'Jan - Feb 2026', 19, 0),
    ('2026/MARET - APRIL 2026.xls', 2026, '2026-03_04', 'Maret - April', '2026-P2', 'Mar - Apr 2026', 20, 0),
    ('2026/MEI - JUNI 2026 - PISAH.xls', 2026, '2026-05_06', 'Mei - Juni', '2026-P3', 'Mei - Jun 2026', 21, 0),
    ('2026/JULI - AGUSTUS 2026.xls', 2026, '2026-07_08', 'Juli - Agustus', '2026-P4', 'Jul - Agu 2026', 22, 0),
]

STANDAR_NAMA_OBAT = {
    'Zinc sirup 20mg/5ml @60 ml': 'Zinc sirup 20 mg/5 ml @ 60 ml',
    'Asetil Sisteina 200 mg kapsul': 'Asetil sisteina 200 mg kapsul',
}

STANDAR_SATUAN = {'capsul': 'kapsul', 'saset': 'sachet', 'ktk': 'kotak', 'suppos': 'supp'}

def clean_num(val):
    if val is None or val == '': return 0.0
    try:
        if isinstance(val, (int, float)): return float(val) if not np.isnan(val) else 0.0
        s = str(val).strip().replace(',', '.')
        s = re.sub(r'[^\\d.-]', '', s)
        return float(s) if s else 0.0
    except: return 0.0

def clean_str(val):
    if val is None: return ''
    return re.sub(r'\\s+', ' ', str(val)).strip()

def get_sheet_cells(sheet, is_xlsx):
    if is_xlsx:
        return [[sheet.cell(r, c).value for c in range(1, sheet.max_column+1)] for r in range(1, sheet.max_row+1)]
    return [[sheet.cell_value(r, c) for c in range(sheet.ncols)] for r in range(sheet.nrows)]

print("Memulai parsing berkas LPLPO...")
all_rows = []
with zipfile.ZipFile(zip_lplpo_path) as z:
    for f, year, month_id, month_label, bim_id, bim_label, bim_idx, sub_month in FILE_CONFIGS:
        is_xlsx = f.endswith('.xlsx')
        if is_xlsx:
            wb = openpyxl.load_workbook(io.BytesIO(z.read(f)), data_only=True)
            sheets_data = [('Sheet1', get_sheet_cells(wb.active, True))]
        else:
            wb = xlrd.open_workbook(file_contents=z.read(f))
            if 'PISAH' in f:
                sheets_data = [(s, get_sheet_cells(wb.sheet_by_name(s), False)) for s in ['OBAT APBD', 'BMHP APBD', 'OBAT HIBAH', 'BMHP HIBAH']]
            elif 'MARET - APRIL 2026' in f:
                sheets_data = [('lplpo th 2026', get_sheet_cells(wb.sheet_by_name('lplpo th 2026'), False))]
            else:
                sheets_data = [(wb.sheet_names()[0], get_sheet_cells(wb.sheet_by_index(0), False))]

        for sname, srows in sheets_data:
            hrow = -1
            for r_idx, row in enumerate(srows[:10]):
                txt_row = [str(c).upper().strip() for c in row if c is not None]
                if any('NAMA OBAT' in c or 'NAMA BARANG' in c for c in txt_row):
                    hrow = r_idx; break
            if hrow == -1: continue

            curr_cat = 'BMHP' if 'BMHP' in sname.upper() else 'OBAT'
            for row in srows[hrow+1:]:
                if len(row) < 3: continue
                c1 = clean_str(row[1])
                if not c1: continue
                c1_upper = c1.upper()
                if any(k in c1_upper for k in ['JUMLAH TOTAL', 'JUMLAH KUNJUNGAN', 'MENYETUJUI', 'KEPALA DINAS', 'KEPALA BALAI', 'KABUPATEN BINTAN']): break
                if 'BAHAN HABIS PAKAI' in c1_upper or 'BMHP' in c1_upper: curr_cat = 'BMHP'; continue
                if 'PERBEKALAN KESEHATAN GIGI' in c1_upper: curr_cat = 'KESEHATAN GIGI'; continue
                if 'BAHAN LABORATORIUM' in c1_upper or 'REAGEN' in c1_upper: curr_cat = 'LABORATORIUM'; continue
                if set(c1) <= {'.', '-', '_', ' '}: continue

                satuan = clean_str(row[2]).lower()
                satuan = STANDAR_SATUAN.get(satuan, satuan)
                if any(t in satuan for t in ['bintan', 'menyerahkan', 'kepala', 'menyetujui', '(', ')']): continue
                nama_obat = STANDAR_NAMA_OBAT.get(c1, c1)

                all_rows.append({
                    'tahun': year, 'periode_bimonthly_id': bim_id, 'periode_bimonthly_nama': bim_label,
                    'bimonthly_index': bim_idx, 'sub_month_index': sub_month, 'kategori': curr_cat,
                    'nama_obat': nama_obat, 'satuan': satuan,
                    'harga_satuan': clean_num(row[3]) if len(row) > 3 else 0.0,
                    'stok_awal': clean_num(row[4]) if len(row) > 4 else 0.0,
                    'penerimaan': clean_num(row[5]) if len(row) > 5 else 0.0,
                    'persediaan': clean_num(row[6]) if len(row) > 6 else 0.0,
                    'pemakaian': clean_num(row[7]) if len(row) > 7 else 0.0,
                    'stok_akhir': clean_num(row[8]) if len(row) > 8 else 0.0,
                })

df_granular = pd.DataFrame(all_rows)
print(f"✅ Ekstraksi granular berhasil: {len(df_granular):,} rekaman.")""")

# ==============================================================================
# CELL 6: AGREGASI KE PIVOT DAN TIME SERIES
# ==============================================================================
add_code("""# 4. Agregasi ke 22 Periode Dwibulanan Kontinu & Matriks Pivot Khusus Obat
# Agregasi
records_agg = []
for (b_idx, b_id, b_name, year, cat, name, unit), group in df_granular.groupby(['bimonthly_index', 'periode_bimonthly_id', 'periode_bimonthly_nama', 'tahun', 'kategori', 'nama_obat', 'satuan']):
    if len(group) > 1 and group['sub_month_index'].nunique() > 1:
        sg = group.sort_values('sub_month_index')
        stok_awal = sg.iloc[0]['stok_awal']
        stok_akhir = sg.iloc[-1]['stok_akhir']
    else:
        stok_awal = group.iloc[0]['stok_awal']
        stok_akhir = group.iloc[-1]['stok_akhir']
    records_agg.append({
        'bimonthly_index': b_idx, 'periode_bimonthly_id': b_id, 'periode_bimonthly_nama': b_name,
        'tahun': year, 'kategori': cat, 'nama_obat': name, 'satuan': unit,
        'harga_satuan': group['harga_satuan'].mean(), 'stok_awal': stok_awal,
        'penerimaan': group['penerimaan'].sum(), 'persediaan': stok_awal + group['penerimaan'].sum(),
        'pemakaian': group['pemakaian'].sum(), 'stok_akhir': stok_akhir,
    })
df_bimonthly = pd.DataFrame(records_agg)

# Buat Pivot Table Pemakaian Obat
df_obat = df_bimonthly[df_bimonthly['kategori'] == 'OBAT'].copy()
pivot = df_obat.pivot_table(index=['nama_obat', 'satuan'], columns='periode_bimonthly_id', values='pemakaian', aggfunc='sum', fill_value=0.0)

ordered_cols = [cfg[4] for cfg in FILE_CONFIGS]
unique_ordered_cols = [c for i, c in enumerate(ordered_cols) if c not in ordered_cols[:i]]
existing_cols = [c for c in unique_ordered_cols if c in pivot.columns]
pivot = pivot[existing_cols]

pivot['total_pemakaian'] = pivot[existing_cols].sum(axis=1)
pivot['mean_pemakaian'] = pivot[existing_cols].mean(axis=1)
pivot['std_pemakaian'] = pivot[existing_cols].std(axis=1)
pivot['cv_pemakaian'] = np.where(pivot['mean_pemakaian'] > 0, pivot['std_pemakaian'] / pivot['mean_pemakaian'], 0.0)
pivot.sort_values('total_pemakaian', ascending=False, inplace=True)
df_pivot = pivot.reset_index()

# Ekstraksi Data Penyakit & Kadaluarsa
wb_p = openpyxl.load_workbook(excel_penyakit_path, data_only=True)
p_records = []
month_code_map = {'JANUARI': 'P1', 'FEBRUARI': 'P1', 'MARET': 'P2', 'APRIL': 'P2', 'MEI': 'P3', 'JUNI': 'P3', 'JULI': 'P4', 'AGUSTUS': 'P4', 'SEPTEMBER': 'P5', 'OKTOBER': 'P5', 'NOVEMBER': 'P6', 'DESEMBER': 'P6'}
for sname in ['PERBULAN.2023', 'PERBULAN.2024', 'PERBULAN 2025', 'PERBULAN 2026']:
    if sname not in wb_p.sheetnames: continue
    sheet = wb_p[sname]
    yr = int(re.search(r'20\d\d', sname).group(0))
    c_m, c_b = None, None
    for r in range(1, sheet.max_row+1):
        c1_v = str(sheet.cell(r, 1).value or '').strip()
        c2_v = str(sheet.cell(r, 2).value or '').strip()
        txt = (c1_v + " " + c2_v).upper()
        for m_name, p_code in month_code_map.items():
            if m_name in txt: c_m, c_b = m_name, f"{yr}-{p_code}"; break
        if c1_v.isdigit() and c2_v and c2_v not in ['Kode Diagnosa', 'Diagnosa']:
            tot = clean_num(sheet.cell(r, 6).value)
            if tot == 0: tot = clean_num(sheet.cell(r, 4).value) + clean_num(sheet.cell(r, 5).value)
            p_records.append({'tahun': yr, 'periode_bimonthly_id': c_b, 'kode_icd': c2_v, 'nama_penyakit': clean_str(sheet.cell(r, 3).value), 'total_pasien': tot})
df_penyakit = pd.DataFrame(p_records)

# Kadaluarsa
k_records = []
with zipfile.ZipFile(zip_kadaluarsa_path) as z:
    for kf in z.namelist():
        if not kf.endswith('.xls'): continue
        yr = int(re.search(r'20\d\d', kf).group(0)) if re.search(r'20\d\d', kf) else 2024
        wb_k = xlrd.open_workbook(file_contents=z.read(kf))
        s_k = wb_k.sheet_by_index(0)
        h_idx = -1
        for r in range(min(10, s_k.nrows)):
            if any('NAMA OBAT' in str(s_k.cell_value(r, c)).upper() for c in range(s_k.ncols)): h_idx = r; break
        for r in range(h_idx+1, s_k.nrows):
            nm = clean_str(s_k.cell_value(r, 1))
            if not nm or any(k in nm.upper() for k in ['JUMLAH', 'TOTAL', 'MENGETAHUI', 'KEPALA']): continue
            jml = clean_num(s_k.cell_value(r, 6))
            prc = clean_num(s_k.cell_value(r, 3))
            sub = clean_num(s_k.cell_value(r, 7))
            k_records.append({'tahun': yr, 'nama_barang': nm, 'jumlah': jml, 'kerugian': sub if sub > 0 else jml*prc})
df_kadaluarsa = pd.DataFrame(k_records)

print(f"✅ Agregasi selesai: {len(df_pivot)} jenis obat terdata sepanjang 22 periode.")
print(f"✅ Data diagnosis penyakit: {len(df_penyakit)} rekaman.")
print(f"✅ Data barang kadaluarsa: {len(df_kadaluarsa)} rekaman (Total kerugian: Rp {df_kadaluarsa['kerugian'].sum():,.0f}).")
df_pivot[['nama_obat', 'satuan', 'total_pemakaian', 'mean_pemakaian', 'cv_pemakaian']].head(10)""")

# ==============================================================================
# CELL 7: EDA SCRIPT 2 (VISUALISASI INLINE)
# ==============================================================================
add_md("""---
## 📊 Langkah 3: Exploratory Data Analysis & Analisis Korelasi (Script 2)

Di tahap ini, kita menghasilkan bukti statistik dan visualisasi untuk **BAB I (Latar Belakang/Permasalahan)** dan **BAB II (Data Understanding)**:
1. **Top 15 Obat Terbanyak**: Mengetahui konsumsi utama puskesmas.
2. **Korelasi Kasus ISPA (J00) vs Obat Terkait**: Parasetamol, Cetirizine, Amoksisilin, Ambroksol.
3. **Penyakit Kronis**: Diabetes (E11) vs Metformin ($r = 0.528, p < 0.05$) & Hipertensi (I10) vs Amlodipin.
4. **Bukti Riil Masalah Mitra**: Kerugian obat kadaluarsa (**Rp 43,35 Juta**) dan frekuensi kekosongan stok (*stockout*).""")

add_code("""# 5. Visualisasi 1: Top 15 Obat dengan Pemakaian Tertinggi
top15 = df_pivot.head(15).sort_values('total_pemakaian', ascending=True)

plt.figure(figsize=(12, 7))
bars = plt.barh(top15['nama_obat'], top15['total_pemakaian'], color='#1f77b4', edgecolor='#0d47a1', height=0.65)
for bar in bars:
    w = bar.get_width()
    plt.text(w + 2000, bar.get_y() + bar.get_height()/2, f'{int(w):,} unit', va='center', ha='left', fontsize=9, fontweight='bold', color='#0d47a1')

plt.title('Top 15 Obat dengan Pemakaian Tertinggi di Puskesmas Teluk Sasah (2023 - 2026)', fontsize=13, fontweight='bold', pad=12)
plt.xlabel('Total Pemakaian (Tablet / Kaplet / Kapsul / Botol)', fontweight='bold')
plt.xlim(0, max(top15['total_pemakaian']) * 1.15)
plt.tight_layout()
plt.show()""")

add_code("""# 6. Visualisasi 2: Tren Kasus Penyakit ISPA / Common Cold (J00) vs Konsumsi Obat Terkait
p_j00 = df_penyakit[df_penyakit['kode_icd'] == 'J00'].groupby('periode_bimonthly_id')['total_pasien'].sum().reindex(existing_cols).fillna(0)

m_pct = df_pivot[df_pivot['nama_obat'].str.contains('Parasetamol tablet 500', case=False)][existing_cols].sum(axis=0).values
m_amx = df_pivot[df_pivot['nama_obat'].str.contains('Amoksisilin kaplet 500', case=False)][existing_cols].sum(axis=0).values
m_cet = df_pivot[df_pivot['nama_obat'].str.contains('Cetirizina', case=False)][existing_cols].sum(axis=0).values
m_amb = df_pivot[df_pivot['nama_obat'].str.contains('Ambroksol tablet 30', case=False)][existing_cols].sum(axis=0).values

fig, ax1 = plt.subplots(figsize=(14, 6))
color_j00 = '#d32f2f'
ax1.set_xlabel('Periode Dwibulanan (2023 - 2026)', fontweight='bold')
ax1.set_ylabel('Jumlah Pasien J00 (Common Cold)', color=color_j00, fontweight='bold')
l0 = ax1.plot(existing_cols, p_j00, color=color_j00, linewidth=2.8, marker='o', label='Kasus J00 [Common Cold]')
ax1.tick_params(axis='y', labelcolor=color_j00)
ax1.tick_params(axis='x', rotation=45)

ax2 = ax1.twinx()
ax2.set_ylabel('Pemakaian Obat (Unit/Butir)', color='#1565c0', fontweight='bold')
l1 = ax2.plot(existing_cols, m_pct, color='#0288d1', linewidth=2.0, linestyle='--', marker='s', label='Parasetamol 500 mg')
l2 = ax2.plot(existing_cols, m_amx, color='#388e3c', linewidth=2.0, linestyle='-.', marker='^', label='Amoksisilin 500 mg')
l3 = ax2.plot(existing_cols, m_cet, color='#f57c00', linewidth=1.8, linestyle=':', marker='d', label='Cetirizina 10 mg')
l4 = ax2.plot(existing_cols, m_amb, color='#7b1fa2', linewidth=1.8, linestyle='-', marker='v', label='Ambroksol 30 mg')
ax2.tick_params(axis='y', labelcolor='#1565c0')
ax2.grid(False)

lines = l0 + l1 + l2 + l3 + l4
ax1.legend(lines, [l.get_label() for l in lines], loc='upper right', frameon=True)
plt.title('Korelasi Tren Penyakit Common Cold (J00) dengan Pemakaian Obat Terkait (2023 - 2026)', fontsize=13, fontweight='bold', pad=12)
plt.tight_layout()
plt.show()""")

add_code("""# 7. Visualisasi 3: Bukti Empiris Masalah Mitra (Kadaluarsa Rp 43 Juta & Stockout)
fig, (ax1, ax2) = plt.subplots(1, 2, figsize=(15, 5))

# Panel 1: Kerugian Kadaluarsa
loss_yr = df_kadaluarsa.groupby('tahun')['kerugian'].sum() / 1e6
bars = ax1.bar(loss_yr.index.astype(str), loss_yr.values, color=['#1976d2', '#388e3c', '#d32f2f'], edgecolor='k', width=0.5)
for bar in bars:
    h = bar.get_height()
    ax1.text(bar.get_x() + bar.get_width()/2, h + 0.4, f'Rp {h:.2f} Juta', ha='center', fontweight='bold')
ax1.set_title('(A) Kerugian Finansial Akibat Obat Kadaluarsa\\n(Total: Rp 43.35 Juta)', fontweight='bold')
ax1.set_ylabel('Nilai Kerugian (Juta Rupiah)', fontweight='bold')
ax1.set_ylim(0, 26)

# Panel 2: Frekuensi Stockout
top_drugs_stockout = ['Kaptopril tablet 25 mg', 'Ibuprofen tablet 400 mg', 'Ambroksol tablet 30 mg', 'Amlodipin 10 mg tablet', 'Cetirizina kapsul 10 mg', 'Parasetamol tablet 500 mg']
so_counts = [((df_bimonthly[df_bimonthly['nama_obat']==d]['stok_akhir'] == 0).sum()) for d in top_drugs_stockout]
bars2 = ax2.barh([d.split(' ')[0] + ' ' + d.split(' ')[-2] for d in top_drugs_stockout], so_counts, color='#d32f2f', edgecolor='k', height=0.6)
for bar in bars2:
    w = bar.get_width()
    ax2.text(w + 0.2, bar.get_y() + bar.get_height()/2, f'{int(w)} periode ({(w/22)*100:.0f}%)', va='center', fontweight='bold', color='#b71c1c')
ax2.set_title('(B) Frekuensi Terjadinya Stockout (Stok=0)\\n(dari 22 Periode Dwibulanan)', fontweight='bold')
ax2.set_xlabel('Jumlah Periode Kosong', fontweight='bold')
ax2.set_xlim(0, 16)

plt.tight_layout()
plt.show()""")

# ==============================================================================
# CELL 8: PEMODELAN MOVING AVERAGE SCRIPT 3
# ==============================================================================
add_md("""---
## 📈 Langkah 4: Pemodelan Prediksi Kebutuhan Stok (Script 3)

### **Metode Peramalan (BAB IV)**:
1. **Simple Moving Average ($k=2$, SMA-2)**:
   $$F_{t+1} = \\frac{A_t + A_{t-1}}{2}$$
2. **Simple Moving Average ($k=3$, SMA-3)**:
   $$F_{t+1} = \\frac{A_t + A_{t-1} + A_{t-2}}{3}$$
3. **Weighted Moving Average ($k=3$, WMA-3)** dengan bobot linier $[1, 2, 3]$:
   $$F_{t+1} = \\frac{3 A_t + 2 A_{t-1} + 1 A_{t-2}}{6}$$

### **Metrik Evaluasi Galat (BAB V)**:
$$\\text{MAPE} = \\frac{1}{n} \\sum_{t=1}^n \\left| \\frac{A_t - F_t}{A_t} \\right| \\times 100\\%$$

Mari kita eksekusi pemodelan pada seluruh obat dan membuat tabel perhitungan matematis bertahap.""")

add_code("""# 8. Implementasi Algoritma Moving Average & Perhitungan Evaluasi MAPE
def run_forecast_model(actuals):
    n = len(actuals)
    f_sma2 = np.full(n, np.nan)
    f_sma3 = np.full(n, np.nan)
    f_wma3 = np.full(n, np.nan)
    
    for t in range(2, n):
        f_sma2[t] = np.mean(actuals[t-2:t])
    for t in range(3, n):
        f_sma3[t] = np.mean(actuals[t-3:t])
        f_wma3[t] = (3*actuals[t-1] + 2*actuals[t-2] + 1*actuals[t-3]) / 6.0
        
    def calc_mape(pred):
        mask = ~np.isnan(pred) & (actuals > 0)
        return np.mean(np.abs((actuals[mask] - pred[mask]) / actuals[mask])) * 100.0 if np.sum(mask) > 0 else np.nan

    mape_s2 = calc_mape(f_sma2)
    mape_s3 = calc_mape(f_sma3)
    mape_w3 = calc_mape(f_wma3)
    
    # Ramalan periode berikutnya (2026-P5)
    next_s2 = np.mean(actuals[-2:])
    next_s3 = np.mean(actuals[-3:])
    next_w3 = (3*actuals[-1] + 2*actuals[-2] + 1*actuals[-3]) / 6.0
    
    return f_sma2, f_sma3, f_wma3, mape_s2, mape_s3, mape_w3, next_s2, next_s3, next_w3

# Evaluasi pada 10 Obat Utama
metrics_summary = []
for idx, row in df_pivot.head(10).iterrows():
    acts = row[existing_cols].values.astype(float)
    _, _, _, m_s2, m_s3, m_w3, n_s2, n_s3, n_w3 = run_forecast_model(acts)
    best_m = 'WMA (k=3)' if (m_w3 <= m_s2 and m_w3 <= m_s3) else ('SMA (k=2)' if m_s2 <= m_s3 else 'SMA (k=3)')
    rec_val = n_w3 if best_m == 'WMA (k=3)' else (n_s2 if best_m == 'SMA (k=2)' else n_s3)
    metrics_summary.append({
        'Nama Obat': row['nama_obat'], 'Total Pemakaian': int(row['total_pemakaian']),
        'MAPE SMA-2 (%)': round(m_s2, 2), 'MAPE SMA-3 (%)': round(m_s3, 2), 'MAPE WMA-3 (%)': round(m_w3, 2),
        'Model Terbaik': best_m, 'Ramalan 2026-P5': int(round(rec_val))
    })

df_eval_summary = pd.DataFrame(metrics_summary)
print("=== TABEL HASIL EVALUASI AKURASI MODEL (TOP 10 OBAT) ===")
display(df_eval_summary)""")

add_code("""# 9. Tabel Simulasi Perhitungan Matematis Bertahap (Step-by-Step BAB IV)
sample_drug = 'Metformin tablet 500 mg'
acts_sample = df_pivot[df_pivot['nama_obat'].str.contains('Metformin', case=False)][existing_cols].sum(axis=0).values

step_rows = []
for t in range(len(existing_cols)):
    p = existing_cols[t]
    act = acts_sample[t]
    if t >= 3:
        a1, a2, a3 = acts_sample[t-1], acts_sample[t-2], acts_sample[t-3]
        f_s3 = (a1 + a2 + a3) / 3.0
        f_w3 = (3*a1 + 2*a2 + 1*a3) / 6.0
        err_w3 = abs(act - f_w3)
        ape_w3 = (err_w3 / act * 100.0) if act > 0 else 0.0
        step_rows.append({
            'Periode': p, 'Aktual (At)': int(act),
            'Rumus WMA (k=3)': f"(3*{int(a1)} + 2*{int(a2)} + 1*{int(a3)}) / 6",
            'Prediksi WMA': round(f_w3, 2), 'Error |At - Ft|': round(err_w3, 2), 'Galat APE (%)': f"{ape_w3:.2f}%"
        })

print(f"=== DETAIL SIMULASI PERHITUNGAN LANGKAH-DEMI-LANGKAH: {sample_drug} ===")
display(pd.DataFrame(step_rows).head(8))""")

add_code("""# 10. Visualisasi Kurva Aktual vs Prediksi & Titik Proyeksi (Parasetamol & Metformin)
fig, (ax1, ax2) = plt.subplots(2, 1, figsize=(14, 10))

for ax, d_name, d_title in zip([ax1, ax2], ['Parasetamol tablet 500', 'Metformin tablet 500'], ['Parasetamol 500 mg', 'Metformin 500 mg']):
    acts = df_pivot[df_pivot['nama_obat'].str.contains(d_name, case=False)][existing_cols].sum(axis=0).values
    f_s2, f_s3, f_w3, m_s2, m_s3, m_w3, n_s2, n_s3, n_w3 = run_forecast_model(acts)
    
    ax.plot(existing_cols, acts, color='black', linewidth=2.5, marker='o', label='Aktual (LPLPO)')
    ax.plot(existing_cols, f_s2, color='#e65100', linestyle='--', marker='s', label=f'SMA (k=2) [MAPE: {m_s2:.1f}%]')
    ax.plot(existing_cols, f_w3, color='#2e7d32', linewidth=2.0, marker='d', label=f'WMA (k=3) [MAPE: {m_w3:.1f}%]')
    
    # Titik Proyeksi
    ax.scatter([existing_cols[-1]], [n_w3], color='#c2185b', s=100, zorder=5)
    ax.annotate(f'Ramalan 2026-P5: {int(n_w3):,} butir', xy=(existing_cols[-1], n_w3),
                xytext=(existing_cols[-4], n_w3 * 1.1), fontweight='bold', color='#c2185b',
                arrowprops=dict(arrowstyle='->', color='#c2185b', lw=1.5))
    ax.set_title(f'Hasil Peramalan Aktual vs Prediksi Model: {d_title}', fontweight='bold')
    ax.set_ylabel('Pemakaian (Butir)', fontweight='bold')
    ax.tick_params(axis='x', rotation=45)
    ax.legend(loc='upper right', frameon=True)

plt.tight_layout()
plt.show()""")

# ==============================================================================
# CELL 9: REKOMENDASI PENGADAAN & SAFETY STOCK (BAB VI)
# ==============================================================================
add_md("""---
## 📦 Langkah 5: Desain Penerapan Manajerial & Safety Stock (BAB VI)

Untuk mengatasi permasalahan utama mitra (kehabisan obat di satu sisi dan obat kedaluwarsa di sisi lain), model peramalan diintegrasikan dengan formula inventori farmasi:
1. **Safety Stock ($SS$) Tingkat Layanan 95%**:
   $$SS = 1.65 \\times \\sigma_{\\text{pemakaian}}$$
2. **Rencana Permintaan Obat ke Dinas Kesehatan**:
   $$\\text{Rekomendasi Pengadaan} = F_{t+1} + SS - \\text{Stok Akhir}$$""")

add_code("""# 11. Tabel Rekomendasi Permintaan Obat Periode Berikutnya (2026-P5) Dilengkapi Safety Stock
rec_table = []
for idx, row in df_pivot.head(8).iterrows():
    acts = row[existing_cols].values.astype(float)
    _, _, _, _, _, _, _, _, n_w3 = run_forecast_model(acts)
    ss = round(1.65 * (row['cv_pemakaian'] * row['mean_pemakaian']))
    stok_akhir_skrg = df_bimonthly[(df_bimonthly['nama_obat']==row['nama_obat']) & (df_bimonthly['periode_bimonthly_id']=='2026-P4')]['stok_akhir'].values
    stok_ak = stok_akhir_skrg[0] if len(stok_akhir_skrg) > 0 else 0.0
    req_order = max(0, int(round(n_w3 + ss - stok_ak)))
    
    rec_table.append({
        'Nama Obat': row['nama_obat'], 'Satuan': row['satuan'],
        'Ramalan Kebutuhan (Ft+1)': int(round(n_w3)),
        'Safety Stock (95%)': int(ss),
        'Stok Akhir Riil (2026-P4)': int(stok_ak),
        'Rekomendasi Permintaan ke Dinkes': req_order
    })

print("=== REKOMENDASI PERENCANAAN PENGADAAN OBAT PUSKESMAS TELUK SASAH (2026-P5) ===")
display(pd.DataFrame(rec_table))""")

# ==============================================================================
# CELL 10: DOWNLOAD FILE OUTPUT
# ==============================================================================
add_md("""---
## 💾 Langkah 6: Download Berkas Hasil Pemrosesan dari Colab

Jalankan blok di bawah ini jika Anda ingin mengunduh seluruh file olahan (`.csv` dan grafik) langsung ke komputer/laptop Anda.""")

add_code("""# 12. Simpan hasil akhir ke CSV dan opsi download file di Google Colab
df_pivot.to_csv("hasil_pivot_pemakaian_obat.csv", index=False)
df_eval_summary.to_csv("hasil_evaluasi_model_mape.csv", index=False)

try:
    from google.colab import files
    print("Mempersiapkan download file hasil olahan...")
    # files.download('hasil_pivot_pemakaian_obat.csv')
    # files.download('hasil_evaluasi_model_mape.csv')
    print("✅ Berkas siap diunduh lewat menu files di bilah kiri!")
except ImportError:
    print("✅ Berkas hasil tersimpan lokal di direktori kerja.")""")

# Tulis file .ipynb
notebook_dict = {
    "cells": cells,
    "metadata": {
        "colab": {
            "name": "Proyek_Penambangan_Data_Puskesmas_Teluk_Sasah.ipynb",
            "provenance": []
        },
        "kernelspec": {
            "display_name": "Python 3",
            "name": "python3"
        },
        "language_info": {
            "name": "python"
        }
    },
    "nbformat": 4,
    "nbformat_minor": 2
}

with open(OUTPUT_NOTEBOOK, 'w', encoding='utf-8') as f:
    json.dump(notebook_dict, f, indent=2, ensure_ascii=False)

print(f"🎉 Google Colab Notebook berhasil dibuat di: {OUTPUT_NOTEBOOK}")
print(f"Total cells: {len(cells)} (Markdown: {sum(1 for c in cells if c['cell_type']=='markdown')}, Code: {sum(1 for c in cells if c['cell_type']=='code')})")
