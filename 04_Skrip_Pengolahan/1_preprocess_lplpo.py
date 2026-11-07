#!/usr/bin/env python3
"""
scripts/1_preprocess_lplpo.py

Skrip Pemrosesan dan Agregasi Data LPLPO (Laporan Pemakaian dan Lembar Permintaan Obat)
Puskesmas Teluk Sasah Periode 2023 - 2026.

Output:
1. data_processed/lplpo_granular_records.csv : Data mentah hasil ekstraksi dari 29 berkas LPLPO
2. data_processed/lplpo_bimonthly_timeseries.csv : Data time-series teragregasi 22 periode dwibulanan (2023-P1 s/d 2026-P4)
3. data_processed/lplpo_bimonthly_timeseries.xlsx : Format Excel untuk pelaporan
4. data_processed/lplpo_obat_pivot_pemakaian.csv : Matriks pivot pemakaian obat per periode (22 periode)
5. data_processed/lplpo_obat_pivot_pemakaian.xlsx : Format Excel pivot dengan indikator statistik
6. data_processed/penyakit_bimonthly.csv : Data diagnosis penyakit teragregasi ke 22 periode yang sama
7. data_processed/obat_kadaluarsa.csv : Data rekapitulasi obat kadaluarsa (2023 - 2025)
"""

import os
import re
import io
import zipfile
import datetime
import numpy as np
import pandas as pd
import xlrd
import openpyxl

# ==============================================================================
# 1. KONFIGURASI DAN PEMETAAN BERKAS LPLPO
# ==============================================================================
BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
ZIP_LPLPO = os.path.join(BASE_DIR, 'Data Puskesmas Teluk Sasah - Kelompok 23', 'LPLPO TEKSAS 23-26.zip')
EXCEL_PENYAKIT = os.path.join(BASE_DIR, 'Data Puskesmas Teluk Sasah - Kelompok 23', 'DATA PENYAKIT UPTD. PUSKESMAS TELUK SASAH.xlsx')
ZIP_KADALUARSA = os.path.join(BASE_DIR, 'Data Puskesmas Teluk Sasah - Kelompok 23', 'relplpo2023.zip')
OUTPUT_DIR = os.path.join(BASE_DIR, 'data_processed')

os.makedirs(OUTPUT_DIR, exist_ok=True)

# Pemetaan seluruh 29 berkas ke metadata periode dan dwibulanan (P1 - P6)
FILE_CONFIGS = [
    # 2023
    ('2023/Januari 2023.xls', 2023, '2023-01', 'Januari', '2023-P1', 'Jan - Feb 2023', 1, 1),
    ('2023/Februari 2023.xls', 2023, '2023-02', 'Februari', '2023-P1', 'Jan - Feb 2023', 1, 2),
    ('2023/Maret - April 2023.xls', 2023, '2023-03_04', 'Maret - April', '2023-P2', 'Mar - Apr 2023', 2, 0),
    ('2023/Mei - Juni 2023.xls', 2023, '2023-05_06', 'Mei - Juni', '2023-P3', 'Mei - Jun 2023', 3, 0),
    ('2023/Juli - Agustus 2023.xls', 2023, '2023-07_08', 'Juli - Agustus', '2023-P4', 'Jul - Agu 2023', 4, 0),
    ('2023/September -  Oktober 2023.xls', 2023, '2023-09_10', 'September - Oktober', '2023-P5', 'Sep - Okt 2023', 5, 0),
    ('2023/November - Desember 2023.xls', 2023, '2023-11_12', 'November - Desember', '2023-P6', 'Nov - Des 2023', 6, 0),

    # 2024 (12 Bulan granular dipetakan ke 6 Dwibulanan)
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

# Standarisasi Penamaan Obat Khusus (Mengatasi Fragmentasi & Casing)
STANDAR_NAMA_OBAT = {
    'Zinc sirup 20mg/5ml @60 ml': 'Zinc sirup 20 mg/5 ml @ 60 ml',
    'Asetil Sisteina 200 mg kapsul': 'Asetil sisteina 200 mg kapsul',
    'Urine bag': 'Urine Bag',
    'Larutan Ress echer 100 ml': 'Larutan Ress Echer 100 ml',
    'FDC Dewasa TLE': 'FDC Dewasa (TLE)',
    'Rapid Test IgG/IgM-Ns1 (Dengue Combo)': 'Rapid Test IgG/IgM-NS1 (Dengue Combo)',
    'Stick asam urat Accupro': 'Stick asam urat (Accupro)',
    'Stick cholestrol Accupro': 'Stick cholestrol (Accupro)',
    'Stick gula darah Accupro': 'Stick gula darah (Accupro)',
    'Stick Hemoglobin Accupro': 'Stick Hemoglobin (Accupro)',
    'Blood Lancet serenity': 'Blood Lancet Serenity',
}

STANDAR_SATUAN = {
    'capsul': 'kapsul',
    'saset': 'sachet',
    'ktk': 'kotak',
    'suppos': 'supp',
    'tab': 'tablet',
    'pieces': 'pcs',
    'fls': 'botol',
    'vial': 'ampul',  # atau tetap terstandarisasi
}

# ==============================================================================
# 2. FUNGSI PEMBERSIHAN DATA
# ==============================================================================
def clean_num(val):
    if val is None or val == '':
        return 0.0
    try:
        if isinstance(val, (int, float)):
            return float(val) if not np.isnan(val) else 0.0
        s = str(val).strip().replace(',', '.')
        s = re.sub(r'[^\d.-]', '', s)
        return float(s) if s else 0.0
    except:
        return 0.0

def clean_str(val):
    if val is None:
        return ''
    s = str(val).strip()
    return re.sub(r'\s+', ' ', s)

def get_sheet_cells(sheet, is_xlsx):
    if is_xlsx:
        return [[sheet.cell(r, c).value for c in range(1, sheet.max_column+1)] for r in range(1, sheet.max_row+1)]
    return [[sheet.cell_value(r, c) for c in range(sheet.ncols)] for r in range(sheet.nrows)]

# ==============================================================================
# 3. PROSES EKSTRAKSI DATA GRANULAR LPLPO
# ==============================================================================
def extract_lplpo_granular():
    print("Mengekstraksi berkas LPLPO...")
    all_rows = []

    with zipfile.ZipFile(ZIP_LPLPO) as z:
        for f, year, month_id, month_label, bim_id, bim_label, bim_idx, sub_month in FILE_CONFIGS:
            is_xlsx = f.endswith('.xlsx')
            if is_xlsx:
                wb = openpyxl.load_workbook(io.BytesIO(z.read(f)), data_only=True)
                sheets_data = [('Sheet1', get_sheet_cells(wb.active, True))]
            else:
                wb = xlrd.open_workbook(file_contents=z.read(f))
                if 'PISAH' in f:
                    sheets_data = [
                        (s, get_sheet_cells(wb.sheet_by_name(s), False))
                        for s in ['OBAT APBD', 'BMHP APBD', 'OBAT HIBAH', 'BMHP HIBAH']
                    ]
                elif 'MARET - APRIL 2026' in f:
                    sheets_data = [('lplpo th 2026', get_sheet_cells(wb.sheet_by_name('lplpo th 2026'), False))]
                else:
                    sheets_data = [(wb.sheet_names()[0], get_sheet_cells(wb.sheet_by_index(0), False))]

            for sname, srows in sheets_data:
                # Deteksi header row
                hrow = -1
                for r_idx, row in enumerate(srows[:10]):
                    txt_row = [str(c).upper().strip() for c in row if c is not None]
                    if any('NAMA OBAT' in c or 'NAMA BARANG' in c for c in txt_row):
                        hrow = r_idx
                        break

                if hrow == -1:
                    print(f"Warning: Header tidak ditemukan di {f} [{sname}]")
                    continue

                # Kategori awal berdasarkan nama sheet bila dipisah
                if 'BMHP' in sname.upper():
                    curr_cat = 'BMHP'
                else:
                    curr_cat = 'OBAT'

                # Parsing baris demi baris
                for row in srows[hrow+1:]:
                    if len(row) < 3:
                        continue
                    c0 = clean_str(row[0])
                    c1 = clean_str(row[1])

                    if not c1:
                        continue
                    c1_upper = c1.upper()

                    # Hentikan bila mencapai footer dokumen
                    if any(k in c1_upper for k in ['JUMLAH TOTAL', 'JUMLAH KUNJUNGAN', 'MENYETUJUI', 'KEPALA DINAS', 'KEPALA BALAI', 'KABUPATEN BINTAN']):
                        break

                    # Deteksi pergantian seksi kategori
                    if 'BAHAN HABIS PAKAI' in c1_upper or 'BMHP' in c1_upper:
                        curr_cat = 'BMHP'
                        continue
                    if 'PERBEKALAN KESEHATAN GIGI' in c1_upper:
                        curr_cat = 'KESEHATAN GIGI'
                        continue
                    if 'BAHAN LABORATORIUM' in c1_upper or 'REAGEN' in c1_upper:
                        curr_cat = 'LABORATORIUM'
                        continue

                    # Abaikan bila c1 hanya berisi karakter tanda baca atau garis tanda tangan
                    if set(c1) <= {'.', '-', '_', ' '}:
                        continue

                    # Ambil nilai satuan
                    satuan = clean_str(row[2]).lower()
                    if satuan in STANDAR_SATUAN:
                        satuan = STANDAR_SATUAN[satuan]

                    # Abaikan bila satuan terlihat seperti teks penutup / tanda tangan
                    if any(t in satuan for t in ['bintan', 'menyerahkan', 'kepala', 'menyetujui', '(', ')']):
                        continue

                    # Standarisasi nama obat
                    nama_obat = STANDAR_NAMA_OBAT.get(c1, c1)

                    harga = clean_num(row[3]) if len(row) > 3 else 0.0
                    stok_awal = clean_num(row[4]) if len(row) > 4 else 0.0
                    penerimaan = clean_num(row[5]) if len(row) > 5 else 0.0
                    persediaan = clean_num(row[6]) if len(row) > 6 else 0.0
                    pemakaian = clean_num(row[7]) if len(row) > 7 else 0.0
                    stok_akhir = clean_num(row[8]) if len(row) > 8 else 0.0

                    # Flagging integritas stok fisik & pencilan program gizi
                    calc_persediaan = stok_awal + penerimaan
                    calc_stok_akhir = persediaan - pemakaian
                    flag_stok_discrepancy = bool(
                        abs(persediaan - calc_persediaan) > 0.5 or 
                        abs(stok_akhir - calc_stok_akhir) > 0.5
                    )
                    flag_program_gizi = bool(
                        any(k in nama_obat.upper() for k in ['FERO SULFAT', 'VITAMIN A', 'ZINC']) and 
                        (pemakaian > 10000 or penerimaan > 10000)
                    )

                    all_rows.append({
                        'file_sumber': f,
                        'sheet_sumber': sname,
                        'tahun': year,
                        'periode_bulan_id': month_id,
                        'periode_bulan_nama': month_label,
                        'periode_bimonthly_id': bim_id,
                        'periode_bimonthly_nama': bim_label,
                        'bimonthly_index': bim_idx,
                        'sub_month_index': sub_month,  # 1 (bulan ke-1), 2 (bulan ke-2), 0 (sudah dwibulanan)
                        'kategori': curr_cat,
                        'nama_obat': nama_obat,
                        'satuan': satuan if satuan else 'pcs',
                        'harga_satuan': harga,
                        'stok_awal': stok_awal,
                        'penerimaan': penerimaan,
                        'persediaan': persediaan,
                        'pemakaian': pemakaian,
                        'stok_akhir': stok_akhir,
                        'flag_stok_discrepancy': flag_stok_discrepancy,
                        'flag_program_gizi': flag_program_gizi,
                    })

    df = pd.DataFrame(all_rows)
    print(f"Total baris data granular berhasil diekstrak: {len(df):,}")
    return df

# ==============================================================================
# 4. AGREGASI TIME-SERIES DWIBULANAN (22 PERIODE KONTINU)
# ==============================================================================
def aggregate_bimonthly(df_granular):
    print("Mengagregasikan data ke 22 periode dwibulanan kontinu...")

    # Untuk agregasi dwibulanan:
    # - Pemakaian: dijumlahkan (sum)
    # - Penerimaan: dijumlahkan (sum)
    # - Stok Awal: diambil stok awal bulan pertama pada periode tersebut
    # - Stok Akhir: diambil stok akhir bulan kedua pada periode tersebut
    # - Harga: rata-rata atau harga terbaru

    records = []
    grouped = df_granular.groupby(['bimonthly_index', 'periode_bimonthly_id', 'periode_bimonthly_nama', 'tahun', 'kategori', 'nama_obat', 'satuan'])

    for (b_idx, b_id, b_name, year, cat, name, unit), group in grouped:
        # Jika berasal dari 2 bulan terpisah (sub_month 1 dan 2)
        if len(group) > 1 and group['sub_month_index'].nunique() > 1:
            sorted_g = group.sort_values('sub_month_index')
            stok_awal = sorted_g.iloc[0]['stok_awal']
            stok_akhir = sorted_g.iloc[-1]['stok_akhir']
        else:
            stok_awal = group.iloc[0]['stok_awal']
            stok_akhir = group.iloc[-1]['stok_akhir']

        total_pemakaian = group['pemakaian'].sum()
        total_penerimaan = group['penerimaan'].sum()
        persediaan = stok_awal + total_penerimaan
        harga_satuan = group['harga_satuan'].mean()

        records.append({
            'bimonthly_index': b_idx,
            'periode_bimonthly_id': b_id,
            'periode_bimonthly_nama': b_name,
            'tahun': year,
            'kategori': cat,
            'nama_obat': name,
            'satuan': unit,
            'harga_satuan': harga_satuan,
            'stok_awal': stok_awal,
            'penerimaan': total_penerimaan,
            'persediaan': persediaan,
            'pemakaian': total_pemakaian,
            'stok_akhir': stok_akhir,
        })

    df_agg = pd.DataFrame(records)
    df_agg.sort_values(['kategori', 'nama_obat', 'bimonthly_index'], inplace=True)
    print(f"Total baris agregasi dwibulanan: {len(df_agg):,}")
    return df_agg

# ==============================================================================
# 5. PIVOT TABLE PEMAKAIAN OBAT KHUSUS UNTUK TIME-SERIES FORECASTING
# ==============================================================================
def create_obat_pivot(df_agg):
    print("Membuat matriks pivot pemakaian obat untuk pemodelan...")
    df_obat = df_agg[df_agg['kategori'] == 'OBAT'].copy()

    # Buat pivot tabel: baris = nama_obat, kolom = periode_bimonthly_id, values = pemakaian
    pivot = df_obat.pivot_table(
        index=['nama_obat', 'satuan'],
        columns='periode_bimonthly_id',
        values='pemakaian',
        aggfunc='sum',
        fill_value=0.0
    )

    # Pastikan urutan kolom sesuai urutan 22 periode kronologis
    ordered_cols = [cfg[4] for cfg in FILE_CONFIGS]
    unique_ordered_cols = []
    for c in ordered_cols:
        if c not in unique_ordered_cols:
            unique_ordered_cols.append(c)

    # Reindex kolom
    existing_cols = [c for c in unique_ordered_cols if c in pivot.columns]
    pivot = pivot[existing_cols]

    # Hitung metrik statistik deskriptif untuk setiap obat
    pivot['total_pemakaian'] = pivot[existing_cols].sum(axis=1)
    pivot['mean_pemakaian'] = pivot[existing_cols].mean(axis=1)
    pivot['std_pemakaian'] = pivot[existing_cols].std(axis=1)
    pivot['min_pemakaian'] = pivot[existing_cols].min(axis=1)
    pivot['max_pemakaian'] = pivot[existing_cols].max(axis=1)
    # Koefisien variasi (CV) = std / mean (mengukur derajat fluktuasi / musiman)
    pivot['cv_pemakaian'] = np.where(pivot['mean_pemakaian'] > 0, pivot['std_pemakaian'] / pivot['mean_pemakaian'], 0.0)
    pivot['periode_aktif_count'] = (pivot[existing_cols] > 0).sum(axis=1)

    pivot.sort_values('total_pemakaian', ascending=False, inplace=True)
    return pivot.reset_index()

# ==============================================================================
# 6. EKSTRAKSI DAN AGREGASI DATA PENYAKIT (DIAGNOSIS)
# ==============================================================================
def extract_penyakit():
    print("Mengekstraksi dan menyelaraskan data diagnosis penyakit...")
    wb = openpyxl.load_workbook(EXCEL_PENYAKIT, data_only=True)

    sheet_year_map = {
        'PERBULAN.2023': 2023,
        'PERBULAN.2024': 2024,
        'PERBULAN 2025': 2025,
        'PERBULAN 2026': 2026
    }

    month_num_to_name = {
        1: 'JANUARI', 2: 'FEBRUARI', 3: 'MARET', 4: 'APRIL',
        5: 'MEI', 6: 'JUNI', 7: 'JULI', 8: 'AGUSTUS',
        9: 'SEPTEMBER', 10: 'OKTOBER', 11: 'NOVEMBER', 12: 'DESEMBER'
    }

    month_str_patterns = [
        ('JANUARI', 1), ('FEBERUARI', 2), ('FEBRUARI', 2), ('MARET', 3),
        ('APRIL', 4), ('MEI', 5), ('JUNI', 6), ('JULI', 7),
        ('AGUSTUS', 8), ('SEPTEMBER', 9), ('OKTOBER', 10),
        ('NOVEMBER', 11), ('DESEMBER', 12)
    ]

    def get_icd_cluster(code, name):
        c = str(code).upper().strip()
        n = str(name).upper().strip()
        
        # 1. Pernapasan / ISPA (J00 - J99)
        if c.startswith('J'):
            return 'Penyakit Sistem Pernapasan (ISPA, Faringitis, Tonsilitis)'
        # 2. Kardiovaskular / Hipertensi (I00 - I99)
        elif c.startswith('I'):
            return 'Penyakit Sistem Sirkulasi (Hipertensi Esensial & Jantung)'
        # 3. Penyakit Gigi & Mulut (K00 - K14)
        elif c.startswith(('K00', 'K01', 'K02', 'K03', 'K04', 'K05', 'K06', 'K07', 'K08')):
            return 'Penyakit Gigi, Mulut & Pulpa (Pulpitis, Abses, Erupsi Gigi)'
        # 4. Saluran Cerna / Dispepsia (K20 - K93)
        elif c.startswith('K'):
            return 'Penyakit Saluran Pencernaan (Dispepsia & Gastritis)'
        # 5. Diabetes Melitus (E10 - E14)
        elif c.startswith(('E10', 'E11', 'E12', 'E13', 'E14')):
            return 'Penyakit Endokrin & Metabolik (Diabetes Melitus Tipe 1 & 2)'
        # 6. Asam Urat & Gangguan Purin (E79)
        elif c.startswith('E79'):
            return 'Gangguan Metabolisme Asam Urat (Hiperurisemia)'
        elif c.startswith('E'):
            return 'Penyakit Endokrin & Nutrisi Lainnya'
        # 7. Diare & Gastroenteritis (A09)
        elif c.startswith('A09'):
            return 'Penyakit Infeksi Usus (Diare & Gastroenteritis)'
        # 8. Infeksi Virus Cacar & Gondongan (B01, B26)
        elif c.startswith('B01'):
            return 'Infeksi Virus Varisela (Cacar Air)'
        elif c.startswith('B26'):
            return 'Infeksi Virus Parotitis (Gondongan)'
        elif c.startswith(('A', 'B')):
            return 'Penyakit Infeksi & Parasit Lainnya'
        # 9. Muskuloskeletal & Nyeri Otot (M00 - M99)
        elif c.startswith('M10'):
            return 'Penyakit Sendi & Artritis (Gout / Pirai)'
        elif c.startswith(('M79', 'M54')):
            return 'Penyakit Muskuloskeletal & Jaringan Lunak (Myalgia & Low Back Pain)'
        elif c.startswith('M'):
            return 'Penyakit Sistem Otot & Rangka Lainnya'
        # 10. Kulit & Dermatitis (L00 - L99)
        elif c.startswith(('L20', 'L30')):
            return 'Penyakit Kulit Alergi & Dermatitis (Eksim)'
        elif c.startswith('L01'):
            return 'Infeksi Bakteri Kulit (Impetigo)'
        elif c.startswith('L50'):
            return 'Penyakit Kulit Alergi (Urtikaria / Biduran)'
        elif c.startswith('L'):
            return 'Penyakit Kulit & Jaringan Subkutan Lainnya'
        # 11. Saraf & Sakit Kepala (G44)
        elif c.startswith('G44'):
            return 'Gangguan Sistem Saraf (Cephalgia / Sakit Kepala)'
        # 12. Telinga & Vertigo (H81)
        elif c.startswith('H81'):
            return 'Gangguan Telinga & Keseimbangan (Vertigo / BPPV)'
        # 13. Mata & Konjungtivitis (H10)
        elif c.startswith('H10'):
            return 'Penyakit Mata & Adneksa (Konjungtivitis)'
        # 14. Kehamilan & Anemia (O99)
        elif c.startswith('O99'):
            return 'Kondisi Maternal Terkait Kehamilan (Anemia Gravidarum)'
        # 15. Cedera & Trauma (T14)
        elif c.startswith('T'):
            return 'Cedera, Keracunan & Trauma Eksternal'
        else:
            return 'Diagnosis & Gejala Klinis Lainnya'

    records = []

    for sname, year in sheet_year_map.items():
        if sname not in wb.sheetnames:
            continue
        sheet = wb[sname]

        curr_month_num = None
        curr_month_name = None

        for r in range(1, sheet.max_row + 1):
            c0 = sheet.cell(r, 1).value
            c1 = sheet.cell(r, 2).value

            # Deteksi Header Bulan (Tangani datetime, typo Feberuari, dan spasi ganda)
            if str(c0).strip() == 'Bulan':
                if isinstance(c1, datetime.datetime):
                    curr_month_num = c1.month
                    curr_month_name = month_num_to_name.get(curr_month_num, f'BULAN-{curr_month_num}')
                else:
                    txt = str(c1).upper().strip()
                    for pattern, m_idx in month_str_patterns:
                        if pattern in txt:
                            curr_month_num = m_idx
                            curr_month_name = month_num_to_name[m_idx]
                            break

            # Periksa apakah baris diagnosis (c0 adalah nomor urut atau c1 adalah kode ICD)
            c0_str = str(c0).strip() if c0 is not None else ''
            c1_str = str(c1).strip() if c1 is not None else ''
            c2_str = str(sheet.cell(r, 3).value).strip() if sheet.cell(r, 3).value is not None else ''

            if c0_str.isdigit() and c1_str and c1_str not in ['Kode Diagnosa', 'Diagnosa']:
                kode_icd = c1_str
                nama_penyakit = clean_str(c2_str)
                pasien_l = clean_num(sheet.cell(r, 4).value)
                pasien_p = clean_num(sheet.cell(r, 5).value)
                total_pasien = clean_num(sheet.cell(r, 6).value)

                if total_pasien == 0.0 and (pasien_l > 0 or pasien_p > 0):
                    total_pasien = pasien_l + pasien_p

                # Hitung dwibulanan (P1 - P6)
                bim_num = (curr_month_num + 1) // 2 if curr_month_num else 1
                bim_id = f"{year}-P{bim_num}"

                records.append({
                    'tahun': year,
                    'bulan_angka': curr_month_num,
                    'bulan': curr_month_name,
                    'periode_bimonthly_id': bim_id,
                    'kode_icd': kode_icd,
                    'nama_penyakit': nama_penyakit,
                    'klaster_icd': get_icd_cluster(kode_icd, nama_penyakit),
                    'pasien_laki': pasien_l,
                    'pasien_perempuan': pasien_p,
                    'total_pasien': total_pasien
                })

    df_p = pd.DataFrame(records)
    print(f"Total baris data diagnosis penyakit: {len(df_p):,}")
    return df_p

# ==============================================================================
# 7. EKSTRAKSI DATA OBAT KADALUARSA
# ==============================================================================
def extract_obat_kadaluarsa():
    print("Mengekstraksi data rekapitulasi obat kadaluarsa...")
    records = []

    def convert_excel_date(v):
        if v is None or v == '':
            return ''
        try:
            val = float(v)
            if val > 1000:
                # Excel base date is 1899-12-30
                d = datetime.datetime(1899, 12, 30) + datetime.timedelta(days=val)
                return d.strftime('%Y-%m')
            return str(int(val))
        except:
            return str(v).strip()

    with zipfile.ZipFile(ZIP_KADALUARSA) as z:
        for f in z.namelist():
            if not f.endswith('.xls'):
                continue
            # ekstrak tahun dari nama file
            year_match = re.search(r'20\d\d', f)
            year = int(year_match.group(0)) if year_match else 2024

            wb = xlrd.open_workbook(file_contents=z.read(f))
            sheet = wb.sheet_by_index(0)

            # Cari baris header
            hrow = -1
            for r in range(min(10, sheet.nrows)):
                row_txt = [str(sheet.cell_value(r, c)).upper() for c in range(sheet.ncols)]
                if any('NAMA OBAT' in c for c in row_txt):
                    hrow = r
                    break

            if hrow == -1:
                continue

            for r in range(hrow + 1, sheet.nrows):
                c0 = str(sheet.cell_value(r, 0)).strip()
                c1 = clean_str(sheet.cell_value(r, 1))

                # Hapus baris tanda tangan, NIP, pejabat, dan header subtotal
                if not c1 or any(k in c1.upper() for k in [
                    'JUMLAH', 'TOTAL', 'MENGETAHUI', 'KEPALA', 'KURNIAWAN', 'NIP', 'PENGELOLA'
                ]):
                    continue

                satuan = clean_str(sheet.cell_value(r, 2)).lower()
                if satuan in STANDAR_SATUAN:
                    satuan = STANDAR_SATUAN[satuan]

                harga = clean_num(sheet.cell_value(r, 3))
                no_batch = clean_str(sheet.cell_value(r, 4))
                exp_date_raw = sheet.cell_value(r, 5)
                jumlah = clean_num(sheet.cell_value(r, 6))
                sub_total = clean_num(sheet.cell_value(r, 7))

                # Hapus baris artefak tanda tangan (jumlah == 0 dan harga == 0)
                if jumlah <= 0 and sub_total <= 0:
                    continue
                if not satuan and harga <= 0:
                    continue

                exp_date_clean = convert_excel_date(exp_date_raw)

                records.append({
                    'tahun_lapor': year,
                    'file_sumber': f,
                    'nama_barang': c1,
                    'satuan': satuan if satuan else 'pcs',
                    'harga_satuan': harga,
                    'no_batch': no_batch if no_batch else '-',
                    'tgl_kadaluarsa_raw': str(exp_date_raw),
                    'tgl_kadaluarsa_standar': exp_date_clean,
                    'jumlah_kadaluarsa': jumlah,
                    'sub_total_kerugian': sub_total if sub_total > 0 else (jumlah * harga)
                })

    df_k = pd.DataFrame(records)
    print(f"Total baris data obat kadaluarsa: {len(df_k):,}")
    return df_k

# ==============================================================================
# 8. MAIN EXECUTION PIPELINE
# ==============================================================================
def main():
    print("=" * 70)
    print("MEMULAI PIPELINE PENGOLAHAN & AGREGASI DATA LPLPO PUSKESMAS TELUK SASAH")
    print("=" * 70)

    # 1. Ekstraksi Data Granular LPLPO
    df_granular = extract_lplpo_granular()
    path_granular_csv = os.path.join(OUTPUT_DIR, 'lplpo_granular_records.csv')
    df_granular.to_csv(path_granular_csv, index=False)
    print(f"-> Disimpan: {path_granular_csv}")

    # 2. Agregasi Dwibulanan (22 Periode)
    df_bimonthly = aggregate_bimonthly(df_granular)
    path_bim_csv = os.path.join(OUTPUT_DIR, 'lplpo_bimonthly_timeseries.csv')
    path_bim_xlsx = os.path.join(OUTPUT_DIR, 'lplpo_bimonthly_timeseries.xlsx')
    df_bimonthly.to_csv(path_bim_csv, index=False)
    df_bimonthly.to_excel(path_bim_xlsx, index=False)
    print(f"-> Disimpan: {path_bim_csv}")
    print(f"-> Disimpan: {path_bim_xlsx}")

    # 3. Pivot Matrix Pemakaian Obat (Khusus Analisis & Model)
    df_pivot = create_obat_pivot(df_bimonthly)
    path_pivot_csv = os.path.join(OUTPUT_DIR, 'lplpo_obat_pivot_pemakaian.csv')
    path_pivot_xlsx = os.path.join(OUTPUT_DIR, 'lplpo_obat_pivot_pemakaian.xlsx')
    df_pivot.to_csv(path_pivot_csv, index=False)
    df_pivot.to_excel(path_pivot_xlsx, index=False)
    print(f"-> Disimpan: {path_pivot_csv}")
    print(f"-> Disimpan: {path_pivot_xlsx}")

    # 4. Ekstraksi & Agregasi Data Penyakit
    df_penyakit = extract_penyakit()
    path_penyakit_csv = os.path.join(OUTPUT_DIR, 'penyakit_clean_records.csv')
    df_penyakit.to_csv(path_penyakit_csv, index=False)
    print(f"-> Disimpan: {path_penyakit_csv}")

    # 5. Ekstraksi Data Obat Kadaluarsa
    df_kadaluarsa = extract_obat_kadaluarsa()
    path_kadaluarsa_csv = os.path.join(OUTPUT_DIR, 'obat_kadaluarsa_clean.csv')
    df_kadaluarsa.to_csv(path_kadaluarsa_csv, index=False)
    print(f"-> Disimpan: {path_kadaluarsa_csv}")

    print("=" * 70)
    print("RINGKASAN HASIL PENGOLAHAN:")
    print("=" * 70)
    print(f"1. Total Rekaman Mentah Granular : {len(df_granular):,} baris")
    print(f"2. Total Rekaman Dwibulanan      : {len(df_bimonthly):,} baris")
    print(f"3. Jumlah Item Obat Unik         : {len(df_pivot):,} item obat")
    print(f"4. Jumlah Periode Dwibulanan     : 22 periode (2023-P1 s/d 2026-P4)")
    print(f"5. Total Rekaman Diagnosis       : {len(df_penyakit):,} rekaman")
    print(f"6. Total Rekaman Kadaluarsa      : {len(df_kadaluarsa):,} rekaman")
    print("\nTop 10 Obat Pemakaian Terbanyak:")
    print(df_pivot[['nama_obat', 'satuan', 'total_pemakaian', 'mean_pemakaian', 'cv_pemakaian']].head(10).to_string(index=False))
    print("=" * 70)
    print("PIPELINE PENGOLAHAN SELESAI DENGAN SUKSES!")

if __name__ == '__main__':
    main()
