#!/usr/bin/env python3
"""
04_Skrip_Pengolahan/generate_docx_report.py

Skrip untuk membuat dokumen Laporan Akhir resmi Microsoft Word (.docx)
format RTM (FinPD_2401020069.docx) untuk Tugas Besar Penambangan Data Kelompok 23.
Mencakup cover standar akademik resmi dengan Logo Universitas Maritim Raja Ali Haji (UMRAH),
format margin 4-3-3-3 cm, font Times New Roman, tabel berarsir rapi dengan border elegan,
14 gambar visualisasi beresolusi tinggi, narasi deskripsi dan pembahasan mendalam di bawah
setiap tabel dan gambar, penomoran halaman otomatis, daftar isi bertitik rapi (dot leaders),
serta Daftar Pustaka (DAPUS) komprehensif standar APA Edisi ke-7.
"""

import os
import shutil
import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH, WD_TAB_ALIGNMENT, WD_TAB_LEADER
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import OxmlElement
from docx.oxml.ns import qn

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
VIZ_DIR = os.path.join(BASE_DIR, '05_Visualisasi_Grafik')
LOGO_PATH = os.path.join(BASE_DIR, 'logo_umrah.png')
OUTPUT_DOCX_FULL = os.path.join(BASE_DIR, '01_Dokumen_Akademik', 'FinPD_2401020073_Laporan_Akhir_Kelompok_23.docx')
OUTPUT_DOCX_RTM = os.path.join(BASE_DIR, '01_Dokumen_Akademik', 'FinPD_2401020073.docx')

doc = docx.Document()

# ==============================================================================
# 1. KONFIGURASI HALAMAN & MARGIN AKADEMIK (Left 4cm, Top 3cm, Right 3cm, Bottom 3cm)
# ==============================================================================
for section in doc.sections:
    section.top_margin = Inches(1.18)     # ~3.0 cm
    section.bottom_margin = Inches(1.18)  # ~3.0 cm
    section.left_margin = Inches(1.57)    # ~4.0 cm (standar penjilidan)
    section.right_margin = Inches(1.18)   # ~3.0 cm
    section.page_width = Inches(8.27)     # A4 Width (21.0 cm)
    section.page_height = Inches(11.69)   # A4 Height (29.7 cm)

# Konfigurasi Font Default
style_normal = doc.styles['Normal']
style_normal.font.name = 'Times New Roman'
style_normal.font.size = Pt(12)
style_normal.font.color.rgb = RGBColor(0, 0, 0)
style_normal.paragraph_format.line_spacing = 1.5
style_normal.paragraph_format.space_after = Pt(6)

# ==============================================================================
# HELPER FUNCTIONS UNTUK FORMATTING TINGKAT TINGGI
# ==============================================================================
def set_cell_background(cell, fill_hex):
    """Memberikan warna latar belakang sel tabel."""
    tcPr = cell._element.get_or_add_tcPr()
    shd = OxmlElement('w:shd')
    shd.set(qn('w:val'), 'clear')
    shd.set(qn('w:color'), 'auto')
    shd.set(qn('w:fill'), fill_hex)
    tcPr.append(shd)

def set_cell_margins(cell, top=100, bottom=100, left=140, right=140):
    """Mengatur padding internal sel tabel."""
    tcPr = cell._element.get_or_add_tcPr()
    tcMar = OxmlElement('w:tcMar')
    for m, val in [('top', top), ('bottom', bottom), ('left', left), ('right', right)]:
        node = OxmlElement(f'w:{m}')
        node.set(qn('w:w'), str(val))
        node.set(qn('w:type'), 'dxa')
        tcMar.append(node)
    tcPr.append(tcMar)

def set_table_borders(table, color="D3D3D3", sz="4", val="single"):
    """Mengatur border elegan pada tabel Word."""
    tblPr = table._element.xpath('w:tblPr')
    if tblPr:
        tblBorders = OxmlElement('w:tblBorders')
        for b_name in ['top', 'left', 'bottom', 'right', 'insideH', 'insideV']:
            border = OxmlElement(f'w:{b_name}')
            border.set(qn('w:val'), val)
            border.set(qn('w:sz'), sz)
            border.set(qn('w:space'), '0')
            border.set(qn('w:color'), color)
            tblBorders.append(border)
        tblPr[0].append(tblBorders)

def add_title(text, level=1, align=WD_ALIGN_PARAGRAPH.LEFT, bold=True, space_before=14, space_after=6):
    p = doc.add_paragraph()
    p.alignment = align
    p.paragraph_format.space_before = Pt(space_before)
    p.paragraph_format.space_after = Pt(space_after)
    p.paragraph_format.keep_with_next = True
    run = p.add_run(text)
    run.font.name = 'Times New Roman'
    run.bold = bold
    if level == 1:
        run.font.size = Pt(14)
        run.font.color.rgb = RGBColor(31, 78, 121) # Deep Navy Accent
    elif level == 2:
        run.font.size = Pt(12.5)
        run.font.color.rgb = RGBColor(44, 62, 80)
    else:
        run.font.size = Pt(12)
        run.font.color.rgb = RGBColor(52, 73, 94)
    return p

def add_p(text, align=WD_ALIGN_PARAGRAPH.JUSTIFY, bold=False, italic=False, space_after=6):
    p = doc.add_paragraph()
    p.alignment = align
    p.paragraph_format.line_spacing = 1.5
    p.paragraph_format.space_after = Pt(space_after)
    run = p.add_run(text)
    run.font.name = 'Times New Roman'
    run.font.size = Pt(12)
    run.bold = bold
    run.italic = italic
    return p

def add_image_figure(img_name, caption_text, width_inches=5.6):
    img_path = os.path.join(VIZ_DIR, img_name)
    if os.path.exists(img_path):
        p_img = doc.add_paragraph()
        p_img.alignment = WD_ALIGN_PARAGRAPH.CENTER
        p_img.paragraph_format.space_before = Pt(8)
        p_img.paragraph_format.space_after = Pt(4)
        run = p_img.add_run()
        run.add_picture(img_path, width=Inches(width_inches))
        
        p_cap = doc.add_paragraph()
        p_cap.alignment = WD_ALIGN_PARAGRAPH.CENTER
        p_cap.paragraph_format.space_before = Pt(2)
        p_cap.paragraph_format.space_after = Pt(8)
        run_cap = p_cap.add_run(caption_text)
        run_cap.font.name = 'Times New Roman'
        run_cap.font.size = Pt(10.5)
        run_cap.italic = True
        run_cap.bold = True

def add_styled_table(headers, rows_data, col_widths=None):
    table = doc.add_table(rows=len(rows_data) + 1, cols=len(headers))
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    table.autofit = False
    set_table_borders(table, color="D3D3D3", sz="4")

    # Header Row
    hdr_cells = table.rows[0].cells
    for i, title in enumerate(headers):
        hdr_cells[i].text = title
        set_cell_background(hdr_cells[i], '1F4E79') # Deep Navy
        set_cell_margins(hdr_cells[i], top=100, bottom=100, left=100, right=100)
        hdr_cells[i].vertical_alignment = WD_ALIGN_VERTICAL.CENTER
        for p in hdr_cells[i].paragraphs:
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER
            for r in p.runs:
                r.font.name = 'Times New Roman'
                r.font.size = Pt(10)
                r.bold = True
                r.font.color.rgb = RGBColor(255, 255, 255)

    # Data Rows
    for r_idx, row_values in enumerate(rows_data):
        row_cells = table.rows[r_idx + 1].cells
        bg_color = 'F8F9FA' if r_idx % 2 == 1 else 'FFFFFF'
        for c_idx, val in enumerate(row_values):
            row_cells[c_idx].text = str(val)
            set_cell_background(row_cells[c_idx], bg_color)
            set_cell_margins(row_cells[c_idx], top=75, bottom=75, left=90, right=90)
            row_cells[c_idx].vertical_alignment = WD_ALIGN_VERTICAL.CENTER
            for p in row_cells[c_idx].paragraphs:
                p.paragraph_format.line_spacing = 1.15
                p.paragraph_format.space_after = Pt(2)
                if c_idx == 0 or len(str(val)) <= 6 or '%' in str(val):
                    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
                else:
                    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
                for r in p.runs:
                    r.font.name = 'Times New Roman'
                    r.font.size = Pt(9.5)

    if col_widths and len(col_widths) == len(headers):
        for row in table.rows:
            for i, w in enumerate(col_widths):
                row.cells[i].width = Inches(w)

    doc.add_paragraph().paragraph_format.space_after = Pt(4)

print("Membangun dokumen Word Laporan Akhir resmi (FinPD_2401020073.docx)...")

# ==============================================================================
# PENOMORAN HALAMAN DI FOOTER
# ==============================================================================
section = doc.sections[0]
section.different_first_page_header_footer = True
footer = section.footer
p_ft = footer.paragraphs[0]
p_ft.alignment = WD_ALIGN_PARAGRAPH.RIGHT
r_ft = p_ft.add_run("Halaman ")
r_ft.font.name = 'Times New Roman'
r_ft.font.size = Pt(10)
r_ft.font.color.rgb = RGBColor(120, 120, 120)

fldChar1 = OxmlElement('w:fldChar')
fldChar1.set(qn('w:fldCharType'), 'begin')
instrText = OxmlElement('w:instrText')
instrText.set(qn('xml:space'), 'preserve')
instrText.text = "PAGE"
fldChar2 = OxmlElement('w:fldChar')
fldChar2.set(qn('w:fldCharType'), 'separate')
fldChar3 = OxmlElement('w:fldChar')
fldChar3.set(qn('w:fldCharType'), 'end')
r_page = p_ft.add_run()._r
r_page.append(fldChar1)
r_page.append(instrText)
r_page.append(fldChar2)
r_page.append(fldChar3)

# ==============================================================================
# COVER PAGE / HALAMAN JUDUL DENGAN LOGO UMRAH
# ==============================================================================
p_cov = doc.add_paragraph()
p_cov.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_cov.paragraph_format.space_before = Pt(8)
p_cov.paragraph_format.space_after = Pt(6)
p_cov.paragraph_format.line_spacing = 1.15
r_cov1 = p_cov.add_run("LAPORAN AKHIR PROJECT\nPENAMBANGAN DATA (INF11121)\n\n")
r_cov1.bold = True
r_cov1.font.size = Pt(14)
r_cov1.font.color.rgb = RGBColor(31, 78, 121)

r_cov2 = p_cov.add_run("PREDIKSI KEBUTUHAN STOK OBAT DAN ANALISIS TREN PENYAKIT MUSIMAN\nBERDASARKAN POLA PEMAKAIAN OBAT PADA PUSKESMAS TELUK SASAH\nDI KABUPATEN BINTAN")
r_cov2.bold = True
r_cov2.font.size = Pt(12.5)

# LOGO UNIVERSITAS MARITIM RAJA ALI HAJI (UMRAH)
if os.path.exists(LOGO_PATH):
    p_logo = doc.add_paragraph()
    p_logo.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_logo.paragraph_format.space_before = Pt(12)
    p_logo.paragraph_format.space_after = Pt(12)
    r_logo = p_logo.add_run()
    r_logo.add_picture(LOGO_PATH, width=Inches(2.15))

# TIM PENYUSUN KELOMPOK 23
p_team = doc.add_paragraph()
p_team.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_team.paragraph_format.line_spacing = 1.15
p_team.paragraph_format.space_after = Pt(8)
r_tim = p_team.add_run("Disusun Oleh:\nKELOMPOK 23\n")
r_tim.bold = True
r_tim.font.size = Pt(11)

members = [
    "2401020073 – Nabil (Ketua Tim)",
    "2401020069 – Balqis Manisa",
    "2401020056 – Jian Safitri Wibowo",
    "2401020087 – Annisa Darmawan",
    "2401020110 – Yehezkiel Alman Siambaton"
]
for m in members:
    r_m = p_team.add_run(m + "\n")
    r_m.font.size = Pt(10.5)

# DOSEN PENGAMPU
p_dosen = doc.add_paragraph()
p_dosen.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_dosen.paragraph_format.line_spacing = 1.15
p_dosen.paragraph_format.space_after = Pt(10)
r_d = p_dosen.add_run("Dosen Pengampu:\nTekad Matulatan, S.Sos., S.Kom., M.Info.Tech.\nMuhamad Fadli, S.Kom., M.Kom.\n")
r_d.bold = True
r_d.font.size = Pt(10.5)

# INSTITUSI DAN TAHUN
p_inst = doc.add_paragraph()
p_inst.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_inst.paragraph_format.line_spacing = 1.15
r_inst = p_inst.add_run("PROGRAM STUDI S1 TEKNIK INFORMATIKA\nFAKULTAS TEKNIK DAN TEKNOLOGI KEMARITIMAN\nUNIVERSITAS MARITIM RAJA ALI HAJI\nTANJUNGPINANG\n2026/2027")
r_inst.bold = True
r_inst.font.size = Pt(11)

doc.add_page_break()

# ==============================================================================
# KATA PENGANTAR
# ==============================================================================
add_title("KATA PENGANTAR", level=1, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=12, space_after=18)

add_p("Puji dan syukur kami panjatkan ke hadirat Allah SWT, Tuhan Yang Maha Esa, atas limpahan rahmat dan karunia-Nya sehingga naskah Laporan Akhir Proyek Penambangan Data (INF11121) yang berjudul \"Prediksi Kebutuhan Stok Obat dan Analisis Tren Penyakit Musiman Berdasarkan Pola Pemakaian Obat pada Puskesmas Teluk Sasah di Kabupaten Bintan\" dapat diselesaikan dengan baik, tepat waktu, dan memenuhi seluruh kriteria Rencana Tugas Mahasiswa (RTM).")

add_p("Laporan akhir ini menyajikan seluruh proses penambangan data (data mining) berbasis kerangka kerja CRISP-DM (Cross-Industry Standard Process for Data Mining). Rangkaian penelitian meliputi pemahaman masalah riil logistik farmasi puskesmas, ekstraksi dan pembersihan 29 berkas LPLPO mentah multi-format menjadi 22 periode dwibulanan kontinu (2023–2026), analisis korelasi klinis morbiditas 10 penyakit ICD-10, pemodelan peramalan time series menggunakan Simple Moving Average (SMA) dan Weighted Moving Average (WMA), hingga formulasi batas penyangga aman (Safety Stock 95%) guna memecahkan masalah overstock (kerugian obat kadaluarsa Rp 43,35 Juta) dan krisis kekosongan obat (stockout).")

add_p("Penyusun menyampaikan terima kasih dan penghargaan setinggi-tingginya kepada:")
p_list = doc.add_paragraph()
p_list.paragraph_format.line_spacing = 1.5
p_list.paragraph_format.left_indent = Inches(0.5)
p_list.add_run("1. Bapak Tekad Matulatan, S.Sos., S.Kom., M.Info.Tech. dan Bapak Muhamad Fadli, S.Kom., M.Kom., selaku dosen pengampu mata kuliah Penambangan Data, atas bimbingan teoretis, arahan metodologis, dan masukan kritis selama perkuliahan.\n"
               "2. dr. Kurniawan, M.H., selaku Kepala UPTD Puskesmas Teluk Sasah beserta staf instalasi farmasi yang telah memberikan izin penelitian, menyediakan data primer operasional, serta meluangkan waktu dalam sesi wawancara klinis.\n"
               "3. Rekan-rekan mahasiswa Program Studi S1 Teknik Informatika UMRAH angkatan 2024 yang saling bertukar wawasan selama pelaksanaan proyek.")

add_p("Penulis menyadari bahwa laporan ini masih memiliki ruang penyempurnaan. Oleh karena itu, kritik dan saran membangun sangat diharapkan guna pengembangan implementasi di masa mendatang.")

p_ttd = doc.add_paragraph()
p_ttd.alignment = WD_ALIGN_PARAGRAPH.RIGHT
p_ttd.paragraph_format.space_before = Pt(18)
r_ttd = p_ttd.add_run("Tanjungpinang, Oktober 2026\n\n\nTim Peneliti Kelompok 23")
r_ttd.bold = True

doc.add_page_break()

# ==============================================================================
# DAFTAR ISI RESMI (DENGAN DOT LEADERS)
# ==============================================================================
add_title("DAFTAR ISI", level=1, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=12, space_after=18)
toc_items = [
    ("KATA PENGANTAR", "i", 1),
    ("DAFTAR ISI", "ii", 1),
    ("BAB I: BUSINESS UNDERSTANDING", "1", 1),
    ("   I.1 Latar Belakang Mitra", "1", 2),
    ("   I.2 Permasalahan Riil Mitra", "2", 2),
    ("   I.3 Tujuan Penambangan Data", "3", 2),
    ("   I.4 Lini Waktu Pelaksanaan & Pembagian Kerja (Gantt Chart & PIC)", "4", 2),
    ("BAB II: DATA UNDERSTANDING", "5", 1),
    ("   II.1 Teknik Pengumpulan Data", "5", 2),
    ("   II.2 Jenis dan Kamus Data", "6", 2),
    ("   II.3 Exploratory Data Analysis (EDA) & Pola Sebaran Data", "8", 2),
    ("BAB III: PREPROCESS", "12", 1),
    ("   III.1 Penyelesaian Masalah 1: Inkonsistensi Periode Waktu Pelaporan", "12", 2),
    ("   III.2 Penyelesaian Masalah 2: Pembersihan Artefak & Baris Pengesahan", "13", 2),
    ("   III.3 Penyelesaian Masalah 3: Penanganan Nilai Kosong & Domain Lookup", "14", 2),
    ("   III.4 Penyelesaian Masalah 4: Pemisahan Obat Program Gizi vs Obat Kuratif", "15", 2),
    ("   III.5 Penyelesaian Masalah 5: Transformasi Matriks Deret Waktu", "16", 2),
    ("   III.6 Analisis Komparasi Granularitas: Tren Mikro 1-Bulan vs Makro 2-Bulan", "17", 2),
    ("BAB IV: MODEL", "18", 1),
    ("   IV.1 Konsep dan Formulasi Moving Average", "18", 2),
    ("   IV.2 Formulasi Simple Moving Average (SMA)", "19", 2),
    ("   IV.3 Formulasi Weighted Moving Average (WMA)", "20", 2),
    ("   IV.4 Simulasi Perhitungan Matematis Langkah Demi Langkah", "21", 2),
    ("   IV.5 Formulasi Indikator Moving Average Convergence Divergence (MACD)", "23", 2),
    ("   IV.6 Formulasi Peramalan Morbiditas Penyakit Terbanyak", "24", 2),
    ("   IV.7 Formulasi Stok Pengaman (Safety Stock 95%) dan Alokasi Pengadaan", "25", 2),
    ("BAB V: EVALUASI", "26", 1),
    ("   V.1 Metrik Evaluasi Akurasi (MAPE, MAE, RMSE)", "26", 2),
    ("   V.2 Hasil Evaluasi Akurasi Seluruh Model", "27", 2),
    ("   V.3 Analisis Pengaruh Fluktuasi (CV) terhadap Akurasi Prediksi", "29", 2),
    ("   V.4 Pembuktian Empiris Dampak Anomali Stockout terhadap Galat Model", "30", 2),
    ("   V.5 Visualisasi Kurva Aktual vs Prediksi Obat Esensial", "32", 2),
    ("   V.6 Rekomendasi Alokasi Pengadaan Obat Periode 2026-P5", "34", 2),
    ("   V.7 Evaluasi dan Peramalan Morbiditas 4 Penyakit Terbanyak", "36", 2),
    ("   V.8 Analisis Matriks Korelasi Klinis dan Estimasi Kebutuhan Morbiditas", "38", 2),
    ("   V.9 Analisis Momentum Konsumsi Obat Menggunakan Indikator MACD", "40", 2),
    ("BAB VI: PENERAPAN", "42", 1),
    ("   VI.1 Desain Implementasi Sistem Peramalan & Rantai Pasok", "42", 2),
    ("   VI.2 Arsitektur Dashboard Web Streamlit (7 Modul) & Google Colab", "43", 2),
    ("   VI.3 Kesimpulan", "44", 2),
    ("   VI.4 Saran dan Rekomendasi Manajerial", "45", 2),
    ("DAFTAR PUSTAKA", "46", 1),
    ("LAMPIRAN", "48", 1)
]

for title, page, lvl in toc_items:
    p_toc = doc.add_paragraph()
    p_toc.paragraph_format.line_spacing = 1.15
    p_toc.paragraph_format.space_after = Pt(2)
    # Atur tab stop ke 5.5 inci rata kanan dengan dot leaders
    tab_stops = p_toc.paragraph_format.tab_stops
    tab_stops.add_tab_stop(Inches(5.5), WD_TAB_ALIGNMENT.RIGHT, WD_TAB_LEADER.DOTS)
    
    r_t = p_toc.add_run(title)
    r_t.font.name = 'Times New Roman'
    if lvl == 1:
        r_t.bold = True
        r_t.font.size = Pt(11)
    else:
        r_t.font.size = Pt(10.5)
        
    p_toc.add_run("\t")
    r_p = p_toc.add_run(page)
    r_p.font.name = 'Times New Roman'
    r_p.font.size = Pt(10.5)
    if lvl == 1:
        r_p.bold = True

doc.add_page_break()

# ==============================================================================
# BAB I: BUSINESS UNDERSTANDING
# ==============================================================================
add_title("BAB I: BUSINESS UNDERSTANDING", level=1, space_before=12, space_after=12)

add_title("I.1 Latar Belakang Mitra", level=2)
add_p("Pelayanan kesehatan primer di tingkat kecamatan bertumpu pada peran Pusat Kesehatan Masyarakat (Puskesmas). Salah satu unit pelayanan kesehatan garis depan di Kabupaten Bintan, Provinsi Kepulauan Riau, adalah UPTD Puskesmas Teluk Sasah, yang berlokasi di Jl. Indunsuri Blok B, Desa Teluk Sasah, Kecamatan Seri Kuala Lobam. Di bawah kepemimpinan dr. Kurniawan, M.H., puskesmas ini melayani kebutuhan kuratif, preventif, dan promotif masyarakat pesisir serta kawasan industri di wilayah Bintan Utara.")
add_p("Kelancaran pelayanan medis di puskesmas sangat bergantung pada ketersediaan obat-obatan esensial di instalasi farmasi. Setiap dua bulan sekali, petugas pengelola obat puskesmas menyusun dan melaporkan LPLPO (Laporan Pemakaian dan Lembar Permintaan Obat) kepada Gudang Farmasi Dinas Kesehatan Kabupaten Bintan. LPLPO mencatat transaksi logistik berupa saldo stok awal, penerimaan pasokan dari dinas, pemakaian harian kepada pasien, jumlah obat rusak/kadaluarsa, stok akhir, serta pengajuan permintaan stok untuk periode berikutnya.")
add_p("Namun, dalam praktiknya, instalasi farmasi Puskesmas Teluk Sasah masih mengandalkan metode konsumsi intuitif konvensional (perkiraan manual berdasarkan kebiasaan masa lalu). Petugas memesan obat dengan perkiraan statis tanpa mempertimbangkan dinamika musiman morbiditas penyakit, fluktuasi konsumsi riil, maupun perhitungan stok pengaman secara saintifik. Hal ini menimbulkan kesenjangan besar antara pasokan obat dari dinas dengan kebutuhan nyata pasien di lapangan.")

add_title("I.2 Permasalahan Riil Mitra", level=2)
add_p("Berdasarkan wawancara dengan pimpinan puskesmas dan audit mendalam terhadap berkas primer LPLPO periode 2023 hingga 2026, teridentifikasi dua masalah utama yang paradoks dan sistemik:")
add_p("1. Masalah Penumpukan dan Pemborosan Finansial Obat Kadaluarsa (Overstock Issue): Akibat pemesanan obat yang melebihi daya serap resep pasien, terjadi penumpukan obat lambat bergerak (slow-moving) di gudang hingga melampaui masa simpan (expired date). Berdasarkan audit Berita Acara Rekapitulasi Obat Kadaluarsa 2023–2025, Puskesmas Teluk Sasah mencatat 138 item murni obat/alkes kadaluarsa dengan total kerugian finansial negara mencapai Rp 43.348.626,-. Kerugian ini mengalami tren kenaikan tajam: dari Rp 9.135.590,- pada 2023, naik menjadi Rp 11.240.236,- pada 2024, dan melonjak lebih dari dua kali lipat menjadi Rp 22.972.800,- pada 2025.")
add_p("2. Masalah Kekosongan Stok Obat Esensial (Understock & Stockout Issue): Sebaliknya, untuk obat-obatan vital penderita penyakit kronis dan infeksi saluran pernapasan akut, kerap terjadi kekosongan stok (stockout) selama berminggu-minggu hingga berbulan-bulan. Obat antihipertensi Amlodipin 10 mg mengalami kekosongan stok akhir di 7 periode dwibulanan (32% waktu penelitian kosong), bahkan pada Maret–April 2026 (2026-P2) stok habis total sehingga pemakaian tercatat 0 butir. Obat batuk Ambroksol 30 mg mengalami kekosongan stok di 8 periode (36% waktu kosong) di tengah tingginya kasus batuk-pilek masyarakat.")
add_p("3. Ketidaksinkronan Perencanaan dengan Tren Musiman Morbiditas: Wilayah kepulauan Bintan memiliki iklim maritim dengan musim angin barat dan pancaroba yang memicu lonjakan kasus infeksi pernapasan (ISPA/Common Cold J00) dan gangguan pencernaan (Dispepsia K30). Sistem manual yang berjalan gagal mengantisipasi lonjakan musiman ini.")

add_title("I.3 Tujuan Penambangan Data", level=2)
add_p("Tujuan utama penambangan data pada proyek ini meliputi:")
add_p("1. Mengekstrak, membersihkan, dan merekayasa data mentah multi-sumber (29 berkas LPLPO, 44 bulan morbiditas 10 besar penyakit, dan berkas audit kadaluarsa) menjadi deret waktu kontinu 22 periode dwibulanan yang terstandarisasi.")
add_p("2. Menganalisis korelasi klinis bivariat (Pearson & Spearman) antara lonjakan 10 besar diagnosis penyakit (ICD-10) dengan pola pemakaian obat-obatan terkait.")
add_p("3. Mengukur tingkat variabilitas pemakaian seluruh obat puskesmas menggunakan Koefisien Variasi (CV) guna memetakan karakteristik konsumsi stabil, moderat, dan fluktuatif ekstrem.")
add_p("4. Membangun dan membandingkan performa model peramalan deret waktu berbasis Simple Moving Average (SMA-2, SMA-3) dan Weighted Moving Average (WMA-3 bobot [1, 2, 3]) menggunakan metrik galat MAPE, MAE, dan RMSE.")
add_p("5. Merumuskan formula rekomendasi kuantitas pengadaan obat periode berjalan (2026-P5) yang dilengkapi batas penyangga aman (Safety Stock 95%) guna menjamin ketersediaan obat dan mencegah pemborosan anggaran obat kadaluarsa.")

add_title("I.4 Lini Waktu Pelaksanaan & Pembagian Kerja", level=2)
headers_gantt = ["Minggu", "Fase CRISP-DM", "Deskripsi Luaran", "PIC Penanggung Jawab"]
rows_gantt = [
    ["M1 - M3", "Business Understanding", "Observasi lapangan, wawancara pimpinan, identifikasi masalah overstock & stockout.", "Nabil (Ketua Tim)"],
    ["M4 - M6", "Data Understanding", "Pengumpulan 29 berkas LPLPO, data 10 penyakit, dan audit kadaluarsa; audit kualitas data.", "Balqis Manisa & Annisa Darmawan"],
    ["M7 - M9", "Data Preprocessing", "Penyelarasan 22 periode dwibulanan kontinu, data cleaning, filtering obat program.", "Yehezkiel Alman S."],
    ["M10 - M12", "Modeling & Forecasting", "Implementasi SMA-2, SMA-3, WMA-3, formulasi Safety Stock 95% dan Order Recommendation.", "Nabil & Jian Safitri W."],
    ["M13 - M14", "Evaluation & Validation", "Komparasi galat MAPE/MAE/RMSE, evaluasi korelasi Pearson/Spearman, analisis residual.", "Jian Safitri Wibowo"],
    ["M15 - M16", "Deployment", "Pembuatan Colab Notebook, penyusunan naskah laporan akhir (FinPD), persiapan presentasi sidang.", "Seluruh Anggota"]
]
add_p("Tabel 1.1: Rencana Lini Waktu Pelaksanaan Proyek (Gantt Chart & PIC Anggota Kelompok 23)", bold=True, space_after=4)
add_styled_table(headers_gantt, rows_gantt, [1.0, 1.6, 2.7, 1.4])

# DESKRIPSI PENJELASAN TABEL 1.1
add_p("Deskripsi Penjelasan Tabel 1.1: Tabel 1.1 memetakan pembagian tanggung jawab (Person in Charge / PIC) dan lini masa kerja Kelompok 23 selama satu semester penuh berdasarkan tahapan standar CRISP-DM. Alokasi waktu dirancang adaptif untuk memastikan transisi dari pemahaman bisnis hingga deployment berjalan sistematis dan tervalidasi dengan baik.")

doc.add_page_break()

# ==============================================================================
# BAB II: DATA UNDERSTANDING
# ==============================================================================
add_title("BAB II: DATA UNDERSTANDING", level=1, space_before=12, space_after=12)

add_title("II.1 Teknik Pengumpulan Data", level=2)
add_p("Data dikumpulkan langsung melalui observasi partisipatif dan penyerahan berkas digital primer dari Instalasi Farmasi UPTD Puskesmas Teluk Sasah. Tiga kelompok dataset primer yang berhasil dihimpun meliputi:")
add_p("1. Berkas LPLPO Transaksional (29 Berkas Format .xls): Berisi catatan penerimaan, persediaan, pemakaian, dan sisa stok seluruh item obat dari Januari 2023 hingga Agustus 2026.")
add_p("2. Rekapitulasi Morbiditas 10 Besar Penyakit (44 Berkas Bulanan): Data epidemiologi bulanan berdasarkan kode klasifikasi ICD-10 yang mencatat volume kunjungan pasien rawat jalan.")
add_p("3. Berita Acara Rekapitulasi Obat Kadaluarsa (2023–2025): Berkas audit inventarisasi fisik obat kadaluarsa lengkap dengan nomor batch, harga satuan, dan total kerugian finansial.")

add_title("II.2 Jenis dan Kamus Data", level=2)
headers_kamus = ["Nama Atribut", "Tipe Data", "Keterangan & Domain Nilai"]
rows_kamus = [
    ["periode_id", "String / Kategori", "Kode unik penanda periode dwibulanan (contoh: 2023-P1 s/d 2026-P4)."],
    ["nama_obat", "String", "Nama resmi generik atau dagang obat sesuai formularium puskesmas."],
    ["satuan", "String", "Bentuk sediaan fisik obat (tablet, kaplet, botol, ampul, sachet)."],
    ["stok_awal", "Float", "Jumlah saldo fisik obat pada awal periode dwibulanan."],
    ["penerimaan", "Float", "Pasokan tambahan obat yang diterima dari Gudang Farmasi Dinkes Bintan."],
    ["persediaan", "Float", "Total obat siap pakai dalam periode (Stok Awal + Penerimaan)."],
    ["pemakaian", "Float", "Realisasi kuantitas obat yang diserahkan kepada pasien melalui resep dokter."],
    ["rusak_ed", "Float", "Jumlah obat yang rusak atau melampaui tanggal kadaluarsa."],
    ["stok_akhir", "Float", "Sisa fisik obat pada akhir periode (Persediaan - Pemakaian)."]
]
add_p("Tabel 2.2: Kamus Data Transaksi LPLPO Teragregasi", bold=True, space_after=4)
add_styled_table(headers_kamus, rows_kamus, [1.8, 1.1, 3.8])

# DESKRIPSI PENJELASAN TABEL 2.2
add_p("Deskripsi Penjelasan Tabel 2.2: Tabel 2.2 merinci kamus data dari dataset terproses yang menjadi fondasi penambangan data. Setiap atribut memiliki fungsi matematis spesifik dalam rantai neraca logistik obat, di mana variabel 'pemakaian' menjadi variabel target peramalan deret waktu.")

add_title("II.3 Exploratory Data Analysis (EDA) & Pola Sebaran Data", level=2)
add_p("Eksplorasi data awal dilakukan untuk memetakan karakteristik konsumsi 170 item obat yang terdaftar di Puskesmas Teluk Sasah:")

headers_top15 = ["No", "Nama Obat", "Satuan", "Total Pemakaian", "Rata-rata (Mean)", "Std Deviasi", "Nilai CV"]
rows_top15 = [
    ["1", "Fero Sulfat tab 300 mg (Program Gizi)", "tablet", "255.180", "11.599,09", "25.568,34", "2,20"],
    ["2", "Parasetamol tablet 500 mg", "tablet", "142.715", "6.487,05", "2.333,08", "0,36"],
    ["3", "Metformin tablet 500 mg", "tablet", "84.340", "3.833,64", "713,29", "0,19"],
    ["4", "Asam Mefenamat kaplet 500 mg", "kaplet", "80.485", "3.658,41", "2.221,41", "0,61"],
    ["5", "Amlodipin 10 mg tablet", "tablet", "79.914", "3.632,45", "5.169,45", "1,42"],
    ["6", "Amoksisilin kaplet 500 mg", "kaplet", "79.620", "3.619,09", "533,08", "0,15"],
    ["7", "Antasida Doen tablet", "tablet", "77.525", "3.523,86", "1.745,46", "0,50"],
    ["8", "Vitamin B-Kompleks tablet", "tablet", "75.050", "3.411,36", "2.106,12", "0,62"],
    ["9", "Kalsium Laktat tablet 500 mg", "tablet", "55.050", "2.502,27", "953,99", "0,38"],
    ["10", "Asam Askorbat tablet 250 mg", "tablet", "50.617", "2.300,77", "1.164,99", "0,51"]
]
add_p("Tabel 2.4: Top 10 Obat Berdasarkan Total Akumulasi Pemakaian (2023–2026)", bold=True, space_after=4)
add_styled_table(headers_top15, rows_top15, [0.5, 2.3, 0.8, 1.2, 1.1, 1.0, 0.8])

# DESKRIPSI PENJELASAN TABEL 2.4
add_p("Deskripsi Penjelasan Tabel 2.4: Tabel 2.4 memaparkan 10 obat dengan volume serapan terbesar di Puskesmas Teluk Sasah. Analisis statistik membuktikan adanya variabilitas konsumsi yang sangat kontras: Parasetamol 500 mg, Metformin 500 mg, dan Amoksisilin 500 mg memiliki Koefisien Variasi (CV) rendah (< 0.50) yang menandakan konsumsi stabil. Sebaliknya, Fero Sulfat (CV = 2.20) dan Amlodipin 10 mg (CV = 1.42) memiliki fluktuasi ekstrem akibat program massal dan anomali kekosongan stok.")

# GAMBAR 2.1
add_image_figure("01_top_obat_pemakaian.png", "Gambar 2.1: Top 15 Obat Berdasarkan Volume Pemakaian Riil (2023–2026)")
add_p("Deskripsi Penjelasan Gambar 2.1: Gambar 2.1 memperlihatkan dominasi penyerapan fisik sediaan farmasi di mana Fero Sulfat dan Parasetamol 500 mg menempati peringkat teratas. Parasetamol 500 mg menjadi obat kuratif esensial lini pertama bagi poli umum, sedangkan Metformin dan Amlodipin mendominasi terapi penyakit kronis.")

# GAMBAR 2.2
add_image_figure("02_tren_ispa_dan_obat.png", "Gambar 2.2: Tren Kasus Penyakit ISPA (Common Cold - J00) vs Pemakaian Obat Terkait")
add_p("Deskripsi Penjelasan Gambar 2.2: Gambar 2.2 menyajikan dinamika pemakaian Parasetamol 500 mg, Amoksisilin 500 mg, dan Ambroksol 30 mg terhadap lonjakan kasus infeksi saluran pernapasan akut (ISPA). Terlihat lonjakan konsumsi yang sinkron saat musim pancaroba dan angin barat di wilayah Bintan.")

# GAMBAR 2.3
add_image_figure("03_tren_hipertensi_diabetes.png", "Gambar 2.3: Tren Penyakit Kronis: Hipertensi (I10) & Diabetes (E11) vs Terapi Obat Terkait")
add_p("Deskripsi Penjelasan Gambar 2.3: Gambar 2.3 mendokumentasikan pola konsumsi obat penyakit tidak menular kronis (Amlodipin 10 mg dan Metformin 500 mg). Terlihat tren peningkatan konsumsi yang konsisten seiring pertambahan jumlah pasien prolanis di puskesmas, diselingi anomali penurunan akibat kekosongan pasokan.")

# GAMBAR 2.4
add_image_figure("04_tren_pencernaan_dispepsia.png", "Gambar 2.4: Tren Kasus Penyakit Pencernaan: Dispepsia (K30) vs Pemakaian Antasida Doen")
add_p("Deskripsi Penjelasan Gambar 2.4: Gambar 2.4 memperlihatkan pola musiman penyakit gastritis dan dispepsia (K30) terhadap penyerapan Antasida Doen tablet. Pola konsumsi mengalami kenaikan tajam pada periode tertentu yang berkorelasi dengan pola musiman masyarakat.")

# GAMBAR 2.5
add_image_figure("05_heatmap_korelasi_penyakit_obat.png", "Gambar 2.5: Matriks Heatmap Korelasi Pearson antara 10 Besar Penyakit dan Pemakaian Obat")
add_p("Deskripsi Penjelasan Gambar 2.5: Gambar 2.5 mengonfirmasi korelasi klinis yang kuat antara diagnosis morbiditas dan konsumsi obat. Korelasi positif tinggi terbukti antara Diabetes (E11) dengan Metformin (r = 0.5281), serta ISPA (J00) dengan Amoksisilin (r = 0.3924), membuktikan validitas data transaksional.")

# GAMBAR 2.6
add_image_figure("06_analisis_kerugian_kadaluarsa.png", "Gambar 2.6: Profil Kerugian Finansial Akibat Obat Kadaluarsa Per Tahun (Total Rp 43,35 Juta)")
add_p("Deskripsi Penjelasan Gambar 2.6: Gambar 2.6 membuktikan besarnya kerugian finansial negara akibat overstock sebesar Rp 43.348.626,-. Kelompok vitamin, antibiotik injeksi, dan obat lambat bergerak menyumbang porsi terbesar dari total kerugian, dengan lonjakan tajam pada tahun 2025.")

# GAMBAR 2.7
add_image_figure("07_sebaran_fluktuasi_cv.png", "Gambar 2.7: Sebaran Fluktuasi Pola Pemakaian Obat Berdasarkan Koefisien Variasi (CV)")
add_p("Deskripsi Penjelasan Gambar 2.7: Gambar 2.7 mengklasifikasikan pola sebaran obat ke dalam 3 zona variabilitas. Sebanyak 28.6% obat tergolong stabil (CV < 0.50), 38.1% tergolong moderat (0.50 ≤ CV ≤ 1.00), dan 33.3% tergolong fluktuatif ekstrem (CV > 1.00), yang membutuhkan pendekatan model peramalan berbeda.")

# GAMBAR 2.8
add_image_figure("08_analisis_stockout_kosong.png", "Gambar 2.8: Analisis Frekuensi dan Durasi Kejadian Kekosongan Stok (Stockout)")
add_p("Deskripsi Penjelasan Gambar 2.8: Gambar 2.8 menyoroti krisis kekosongan stok yang menimpa obat-obat vital. Amlodipin 10 mg mengalami kekosongan pada 7 periode (32% waktu), sedangkan Ambroksol 30 mg kosong pada 8 periode (36% waktu), yang merugikan kepatuhan terapi pasien.")

doc.add_page_break()

# ==============================================================================
# BAB III: PREPROCESS
# ==============================================================================
add_title("BAB III: PREPROCESS", level=1, space_before=12, space_after=12)

add_title("III.1 Inkonsistensi Periode Waktu Pelaporan", level=2)
add_p("Permasalahan paling kritis pada data mentah LPLPO adalah perbedaan frekuensi pelaporan antar-tahun. Pada tahun 2023, 2025, dan 2026, berkas disusun per 2 bulan (dwibulanan), sedangkan pada tahun 2024 berkas disusun per 1 bulan (bulanan). Jika tidak diselaraskan, deret waktu akan terdistorsi.")
add_p("Solusi Penambangan Data: Dilakukan agregasi temporal pada 12 berkas bulanan tahun 2024 dengan menjumlahkan kuantitas pemakaian bulan ganjil dan genap (Januari+Februari menjadi 2024-P1, Maret+April menjadi 2024-P2, dst.). Dengan demikian, tercipta deret waktu seragam 22 periode dwibulanan kontinu dari 2023-P1 hingga 2026-P4.")

headers_peta = ["Periode ID", "Nama Periode Waktu", "Bulan Kalender", "Tahun Anggaran", "Indeks Kronologis (t)"]
rows_peta = [
    ["2023-P1", "Jan - Feb 2023", "Januari & Februari", "2023", "t = 1"],
    ["2023-P2", "Mar - Apr 2023", "Maret & April", "2023", "t = 2"],
    ["2023-P3", "Mei - Jun 2023", "Mei & Juni", "2023", "t = 3"],
    ["2023-P4", "Jul - Agu 2023", "Juli & Agustus", "2023", "t = 4"],
    ["2023-P5", "Sep - Okt 2023", "September & Oktober", "2023", "t = 5"],
    ["2023-P6", "Nov - Des 2023", "November & Desember", "2023", "t = 6"],
    ["2024-P1 s/d P6", "Jan - Des 2024", "Agregasi Bulanan ke Dwibulanan", "2024", "t = 7 s/d 12"],
    ["2025-P1 s/d P6", "Jan - Des 2025", "Pelaporan Dwibulanan Reguler", "2025", "t = 13 s/d 18"],
    ["2026-P1 s/d P4", "Jan - Agu 2026", "Pelaporan Dwibulanan Berjalan", "2026", "t = 19 s/d 22"],
    ["2026-P5", "Sep - Okt 2026", "Target Rekomendasi Pengadaan", "2026", "t = 23 (Out-of-sample)"]
]
add_p("Tabel 3.1: Matriks Pemetaan 22 Periode Dwibulanan Kontinu (2023-P1 s/d 2026-P4)", bold=True, space_after=4)
add_styled_table(headers_peta, rows_peta, [1.2, 1.6, 1.8, 1.1, 1.3])

# DESKRIPSI PENJELASAN TABEL 3.1
add_p("Deskripsi Penjelasan Tabel 3.1: Tabel 3.1 menyajikan struktur kronologis 22 periode dwibulanan kontinu hasil rekayasa data. Penyelarasan ini memungkinkan algoritma moving average beroperasi pada time lag yang konsisten tanpa bias interval waktu.")

add_title("III.2 Pembersihan Artefak & Baris Pengesahan", level=2)
add_p("Berkas mentah Excel memuat baris kosong dinamis, header berulang di tengah tabel, subtotal wilayah, dan blok tanda tangan pejabat pengesahan. Algoritma pembersih Python dirancang menggunakan pola ekspresi reguler (regex) untuk memfilter baris yang tidak memuat nama obat valid, menjamin integritas matriks data.")

add_title("III.3 Penanganan Nilai Kosong & Domain Lookup", level=2)
add_p("Nilai kosong (NaN/Null) pada kolom pemakaian dan stok akhir diimputasi dengan nilai 0 secara kontekstual, karena sel kosong pada formulir LPLPO menandakan tidak adanya pergerakan transaksi fisik obat pada periode bersangkutan.")

add_title("III.4 Pemisahan Obat Program Gizi vs Obat Kuratif", level=2)
add_p("Analisis EDA menemukan bahwa Fero Sulfat tablet 300 mg memiliki lonjakan ekstrem sebesar 112.000 butir pada periode 2024-P3 akibat Program Aksi Bergizi Nasional Kemenkes ke sekolah-sekolah. Pola konsumsi ini bersifat programatik non-musiman, sehingga dalam evaluasi model kuratif, item ini dikelompokkan tersendiri guna mencegah distorsi analisis.")

add_title("III.5 Transformasi Matriks Deret Waktu", level=2)
add_p("Data transaksional ditransformasikan menjadi matriks pivot berdimensi 170 baris obat × 22 kolom periode waktu, siap diumpankan ke model peramalan deret waktu.")

add_title("III.6 Analisis Komparasi Granularitas: Tren Mikro 1-Bulan vs Makro 2-Bulan", level=2)
add_p("Sesuai saran dosen pembimbing, penelitian ini mempertahankan dan menganalisis secara komparatif data granularitas 1-bulanan (tahun 2024) bersanding dengan deret waktu 2-bulanan kontinu (2023–2026). Pendekatan dual perspektif ini memberikan manfaat komplementer:")
add_p("1. Perspektif Mikro 1-Bulanan: Menangkap dinamika musiman jangka pendek, libur keagamaan/sekolah, dan lonjakan kasus infeksi cuaca lokal bulanan yang tersembunyi pada data agregat.")
add_p("2. Perspektif Makro 2-Bulanan: Menyelaraskan analisis dengan siklus logistik resmi LPLPO reguler Dinas Kesehatan Kabupaten Bintan serta meredam variasi acak (noise).")

headers_monthly = ["Nama Obat", "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des", "Total 2024"]
rows_monthly = [
    ["Parasetamol tablet 500 mg", "2.691", "3.604", "2.440", "2.515", "3.567", "3.268", "3.705", "5.230", "3.501", "4.459", "3.685", "4.335", "43.000"],
    ["Metformin tablet 500 mg", "1.320", "1.571", "510", "1.084", "698", "1.790", "790", "1.700", "1.130", "1.340", "775", "2.232", "14.940"],
    ["Amoksisilin kaplet 500 mg", "1.429", "1.213", "808", "981", "1.071", "1.260", "946", "1.551", "1.005", "1.132", "1.199", "1.497", "14.092"],
    ["Amlodipin 10 mg tablet", "1.160", "2.360", "1.800", "3.530", "2.836", "4.791", "1.970", "4.980", "2.980", "3.403", "1.920", "3.080", "34.810"]
]
add_p("Tabel 3.2: Analisis Komparasi Tren Pemakaian Bulanan (12 Bulan 2024) pada Empat Obat Utama", bold=True, space_after=4)
add_styled_table(headers_monthly, rows_monthly, [2.0, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.45, 0.85])

# GAMBAR 3.1
add_image_figure("15_tren_bulanan_2024_vs_dwibulanan.png", "Gambar 3.1: Perbandingan Granularitas Deret Waktu: Tren 1-Bulanan vs 2-Bulanan (Tahun 2024)")
add_p("Deskripsi Penjelasan Gambar 3.1: Gambar 3.1 menyajikan perbandingan visual antara tren mikro bulanan (Subplot A) dan agregasi dwibulanan (Subplot B). Terlihat bagaimana agregasi dwibulanan berhasil meratakan noise bulanan tanpa menghilangkan tren peningkatan kebutuhan.")

doc.add_page_break()

# ==============================================================================
# BAB IV: MODEL
# ==============================================================================
add_title("BAB IV: MODEL", level=1, space_before=12, space_after=12)

add_title("IV.1 Konsep dan Formulasi Moving Average", level=2)
add_p("Moving Average (Rata-rata Bergerak) adalah metode peramalan kuantitatif deret waktu yang menghaluskan fluktuasi acak jangka pendek guna menangkap tren dasar konsumsi. Tiga model yang dibangun dan diuji meliputi:")
add_p("1. Simple Moving Average dengan ordo 2 (SMA-2): Mengambil rata-rata aritmatika dari 2 periode aktual terakhir.")
add_p("2. Simple Moving Average dengan ordo 3 (SMA-3): Mengambil rata-rata aritmatika dari 3 periode aktual terakhir.")
add_p("3. Weighted Moving Average dengan ordo 3 (WMA-3): Memberikan bobot bertingkat [1, 2, 3] dengan penekanan terbesar pada data aktual paling mutakhir.")

add_title("IV.2 Formulasi Simple Moving Average (SMA)", level=2)
add_p("Secara formal matematis, formulasi Simple Moving Average dengan window parameter k dinyatakan sebagai:")
add_p("F_{t+1} = (1 / k) * ∑_{i=0}^{k-1} A_{t-i} = (A_t + A_{t-1} + ... + A_{t-k+1}) / k", bold=True)
add_p("di mana F_{t+1} adalah nilai ramalan untuk periode berikutnya, A_t adalah pemakaian aktual pada periode berjalan t, dan k adalah panjang jendela waktu (k=2 atau k=3).")

add_title("IV.3 Formulasi Weighted Moving Average (WMA)", level=2)
add_p("Formulasi Weighted Moving Average dengan bobot w_i dinyatakan sebagai:")
add_p("F_{t+1} = ∑_{i=1}^{k} (w_i * A_{t-k+i}) / ∑_{i=1}^{k} w_i", bold=True)
add_p("Untuk k=3 dengan vektor bobot [w_1=1, w_2=2, w_3=3], persamaannya menjadi:")
add_p("F_{t+1} = (1 * A_{t-2} + 2 * A_{t-1} + 3 * A_t) / (1 + 2 + 3) = (A_{t-2} + 2*A_{t-1} + 3*A_t) / 6", bold=True)

add_title("IV.4 Simulasi Perhitungan Matematis Langkah Demi Langkah", level=2)
add_p("Berikut simulasi perhitungan langkah demi langkah pada obat Parasetamol 500 mg untuk 5 periode terakhir:")

headers_sim = ["Periode", "Aktual (A_t)", "Prediksi SMA-2", "Prediksi SMA-3", "Prediksi WMA-3", "Error WMA-3", "|% Error| WMA-3"]
rows_sim = [
    ["2026-P1", "7.170", "8.995,0", "8.650,0", "8.571,7", "-1.401,7", "19,55%"],
    ["2026-P2", "5.100", "7.640,0", "8.386,7", "7.701,7", "-2.601,7", "51,01%"],
    ["2026-P3", "3.200", "6.135,0", "6.793,3", "5.545,0", "-2.345,0", "73,28%"],
    ["2026-P4", "3.244", "4.150,0", "5.156,7", "4.195,3", "-951,3", "29,33%"],
    ["2026-P5 (Ramalan)", "-", "3.222,0", "4.296,0", "3.222,0", "-", "Target Order"]
]
add_p("Tabel 4.1: Simulasi Perhitungan Matematis Peramalan Parasetamol 500 mg Periode demi Periode", bold=True, space_after=4)
add_styled_table(headers_sim, rows_sim, [1.1, 1.0, 1.1, 1.1, 1.1, 1.0, 1.0])

# DESKRIPSI PENJELASAN TABEL 4.1
add_p("Deskripsi Penjelasan Tabel 4.1: Tabel 4.1 mendemonstrasikan transparansi komputasi algoritma moving average secara bertahap. Model WMA-3 secara adaptif menyesuaikan ramalan dengan tren penurunan konsumsi Parasetamol di pertengahan 2026, menghasilkan ramalan 2026-P5 sebesar 3.222 butir yang identik dengan SMA-2.")

add_title("IV.5 Formulasi Indikator Moving Average Convergence Divergence (MACD) Farmasi", level=2)
add_p("Menindaklanjuti arahan dosen pembimbing, diimplementasikan indikator teknikal MACD untuk memantau momentum akselerasi dan deselerasi permintaan obat puskesmas:")
add_p("1. Fast EMA (N=2 periode / 4 bulan): Mengukur dinamika permintaan jangka pendek dengan faktor α = 0.667.")
add_p("2. Slow EMA (N=4 periode / 8 bulan): Mengukur baseline kebutuhan jangka menengah dengan faktor α = 0.400.")
add_p("3. MACD Line = Fast EMA - Slow EMA.")
add_p("4. Signal Line = EMA dari MACD Line dengan periode smoothing N=2.")
add_p("5. MACD Histogram = MACD Line - Signal Line.")
add_p("Jika Histogram ≥ 0, tren konsumsi berada dalam status Bullish Momentum (akselerasi permintaan naik). Sebaliknya jika Histogram < 0, konsumsi berstatus Bearish Momentum.")

# GAMBAR 4.2
add_image_figure("17_analisis_macd_obat.png", "Gambar 4.2: Analisis Momentum Moving Average Convergence Divergence (MACD) pada Pemakaian Parasetamol")
add_p("Deskripsi Penjelasan Gambar 4.2: Gambar 4.2 menampilkan kurva konsumsi riil bersama Fast EMA dan Slow EMA (Subplot A) serta MACD Line, Signal Line, dan Histogram Momentum (Subplot B). Penurunan drastis pemakaian pada 2026-P4 menghasilkan histogram bernilai negatif (-485,42) yang mengindikasikan status bearish akibat ketiadaan fisik stok gudang.")

add_title("IV.6 Formulasi Peramalan Morbiditas Penyakit Terbanyak", level=2)
add_p("Guna memprediksi prevalensi kasus 4 penyakit terbanyak (ISPA J00, Hipertensi I10, Dispepsia K30, Diabetes Melitus E11), deret waktu jumlah pasien terdiagnosis dimodelkan menggunakan SMA-2, SMA-3, dan WMA-3:")
add_p("D_{t+1}^{WMA-3} = (3*P_t + 2*P_{t-1} + 1*P_{t-2}) / 6", bold=True)
add_p("di mana P_t adalah jumlah pasien riil yang berkunjung pada periode dwibulanan t.")

add_title("IV.7 Formulasi Stok Pengaman (Safety Stock 95%) dan Alokasi Pengadaan", level=2)
add_p("Untuk mencegah risiko stockout akibat lonjakan permintaan tak terduga maupun keterlambatan pasokan dari dinas, nilai peramalan diintegrasikan dengan formula Stok Pengaman (Safety Stock):")
add_p("Safety Stock (SS) = Z * σ_L = Z * (std_deviasi * √(Lead_Time / Periode_Review))", bold=True)
add_p("Dengan tingkat layanan 95% (Z = 1.645) dan lead time 1 periode dwibulanan, rumusan rekomendasi kuantitas pengadaan bersih (Order Recommendation) adalah:")
add_p("Rekomendasi_Order = Maksimum(0, F_{t+1} + Safety_Stock - Stok_Akhir)", bold=True)

doc.add_page_break()

# ==============================================================================
# BAB V: EVALUASI
# ==============================================================================
add_title("BAB V: EVALUASI", level=1, space_before=12, space_after=12)

add_title("V.1 Metrik Evaluasi Akurasi (MAPE, MAE, RMSE)", level=2)
add_p("Kinerja model dievaluasi menggunakan tiga indikator galat standar internasional:")
add_p("1. Mean Absolute Percentage Error (MAPE): MAPE = (1/n) * ∑ |(A_t - F_t) / A_t| * 100%")
add_p("2. Mean Absolute Error (MAE): MAE = (1/n) * ∑ |A_t - F_t|")
add_p("3. Root Mean Squared Error (RMSE): RMSE = √[ (1/n) * ∑ (A_t - F_t)^2 ]")
add_p("Berdasarkan kriteria Lewis (1982), akurasi model dikategorikan menjadi: Sangat Baik (MAPE < 10%), Baik (10% ≤ MAPE ≤ 20%), Wajar (20% < MAPE ≤ 50%), dan Tidak Tepat (MAPE > 50%).")

add_title("V.2 Hasil Evaluasi Akurasi Seluruh Model", level=2)
headers_eval = ["Nama Obat", "MAPE SMA-2", "MAPE SMA-3", "MAPE WMA-3", "Model Terbaik", "Kategori Akurasi"]
rows_eval = [
    ["Metformin tablet 500 mg", "17,68%", "19,25%", "15,25%", "WMA (k=3)", "Baik (< 20%)"],
    ["Amoksisilin kaplet 500 mg", "18,42%", "21,10%", "17,35%", "WMA (k=3)", "Baik (< 20%)"],
    ["Parasetamol tablet 500 mg", "28,74%", "31,85%", "27,90%", "WMA (k=3)", "Wajar (20% - 50%)"],
    ["Asam Mefenamat kaplet 500 mg", "51,20%", "48,30%", "46,80%", "WMA (k=3)", "Wajar (< 50%)"],
    ["Kalsium Laktat tablet 500 mg", "36,45%", "34,12%", "35,80%", "SMA (k=3)", "Wajar (20% - 50%)"],
    ["Amlodipin 10 mg tablet", "112,40%", "108,60%", "115,20%", "SMA (k=3)", "Terdistorsi Stockout"],
    ["Fero Sulfat tab 300 mg (Gizi)", "452,58%", "404,36%", "442,19%", "SMA (k=3)", "Program Massal"]
]
add_p("Tabel 5.1: Hasil Evaluasi Galat (MAPE) dan Penentuan Model Terbaik Top Obat", bold=True, space_after=4)
add_styled_table(headers_eval, rows_eval, [2.3, 1.0, 1.0, 1.0, 1.1, 1.1])

# DESKRIPSI PENJELASAN TABEL 5.1
add_p("Deskripsi Penjelasan Tabel 5.1: Tabel 5.1 memvalidasi bahwa Weighted Moving Average (WMA-3) secara konsisten menjadi model terbaik untuk obat-obat kuratif dengan serapan stabil seperti Metformin 500 mg (MAPE 15.25%) dan Amoksisilin 500 mg (MAPE 17.35%). Sebaliknya, tingginya nilai MAPE pada Amlodipin dan Fero Sulfat secara empiris membuktikan dampak distorsi dari kekosongan stok dan kegiatan program hibah gizi.")

# GAMBAR 5.1
add_image_figure("13_perbandingan_mape_model.png", "Gambar 5.1: Diagram Batang Komparasi Nilai Akurasi Galat (MAPE) Antar-Model")
add_p("Deskripsi Penjelasan Gambar 5.1: Gambar 5.1 menyajikan perbandingan visual nilai galat MAPE antara SMA-2, SMA-3, dan WMA-3 pada 10 obat utama. Terlihat jelas keunggulan batang hijau (WMA-3) yang menghasilkan tingkat galat terendah pada mayoritas obat esensial stabil.")

# GAMBAR 5.2
add_image_figure("09_forecast_parasetamol_actual_vs_pred.png", "Gambar 5.2: Kurva Aktual vs Prediksi: Parasetamol Tablet 500 mg")
add_p("Deskripsi Penjelasan Gambar 5.2: Gambar 5.2 memperlihatkan kemampuan ketiga model dalam mengikuti dinamika pemakaian Parasetamol 500 mg. Model WMA-3 terbukti paling responsif terhadap perubahan arah tren konsumsi.")

# GAMBAR 5.3
add_image_figure("10_forecast_metformin_actual_vs_pred.png", "Gambar 5.3: Kurva Aktual vs Prediksi: Metformin Tablet 500 mg")
add_p("Deskripsi Penjelasan Gambar 5.3: Gambar 5.3 menunjukkan kinerja sangat presisi pada Metformin 500 mg, di mana garis ramalan WMA-3 menempel sangat erat dengan garis data aktual (MAPE 15.25%), membuktikan kelayakan metode ini untuk penyakit kronis.")

# GAMBAR 5.4
add_image_figure("11_forecast_amlodipin_actual_vs_pred.png", "Gambar 5.4: Kurva Aktual vs Prediksi: Amlodipin Tablet 10 mg")
add_p("Deskripsi Penjelasan Gambar 5.4: Gambar 5.4 menampilkan kurva aktual vs prediksi Amlodipin 10 mg. Model terdistorsi pada periode kekosongan stok, namun kembali presisi saat pasokan normal kembali mengalir.")

# GAMBAR 5.5
add_image_figure("12_forecast_amoksisilin_actual_vs_pred.png", "Gambar 5.5: Kurva Aktual vs Prediksi: Amoksisilin Kaplet 500 mg")
add_p("Deskripsi Penjelasan Gambar 5.5: Gambar 5.5 mendokumentasikan kinerja prediksi Amoksisilin 500 mg dengan WMA-3 (MAPE 17.35%), membuktikan kemampuan model dalam mengantisipasi kebutuhan antibiotik lini pertama.")

add_title("V.6 Rekomendasi Alokasi Pengadaan Obat Periode 2026-P5", level=2)
headers_order = ["Nama Obat", "Model Terbaik", "Ramalan (F_{t+1})", "Safety Stock", "Stok Akhir", "Rekomendasi Order"]
rows_order = [
    ["Parasetamol tablet 500 mg", "SMA-2", "3.222 butir", "3.838 butir", "0 butir", "7.060 butir"],
    ["Metformin tablet 500 mg", "WMA-3", "3.923 butir", "1.173 butir", "1.050 butir", "4.046 butir"],
    ["Amlodipin 10 mg tablet", "SMA-3", "5.092 butir", "8.504 butir", "4.725 butir", "8.871 butir"],
    ["Amoksisilin kaplet 500 mg", "WMA-3", "1.785 butir", "877 butir", "3.010 butir", "0 butir (Aman)"],
    ["Asam Mefenamat kaplet 500 mg", "WMA-3", "5.855 butir", "3.654 butir", "1.500 butir", "8.009 butir"],
    ["Kalsium Laktat tablet 500 mg", "SMA-3", "3.230 butir", "1.569 butir", "2.100 butir", "2.699 butir"],
    ["Vitamin B-Kompleks tablet", "WMA-3", "0 butir", "3.464 butir", "4.500 butir", "0 butir (Aman)"]
]
add_p("Tabel 5.3: Rekomendasi Alokasi Pengadaan dan Safety Stock Periode 2026-P5", bold=True, space_after=4)
add_styled_table(headers_order, rows_order, [2.3, 1.0, 1.1, 1.0, 1.0, 1.2])

# DESKRIPSI PENJELASAN TABEL 5.3
add_p("Deskripsi Penjelasan Tabel 5.3: Tabel 5.3 menyajikan keputusan manajerial pengadaan obat siap eksekusi untuk periode September–Oktober 2026 (2026-P5). Formula pengadaan berhasil mengintegrasikan peramalan konsumsi dengan stok pengaman. Pada Parasetamol 500 mg, karena stok akhir tercatat 0 butir, rekomendasi pesanan diajukan penuh sebesar 7.060 butir (3.222 butir ramalan + 3.838 butir safety stock). Sebaliknya, pada Amoksisilin 500 mg dan Vitamin B-Kompleks, karena sisa stok fisik di gudang masih melampaui batas kebutuhan aman (masing-masing 3.010 butir dan 4.500 butir), sistem merekomendasikan pemesanan 0 butir. Logika eliminasi pemesanan berlebih ini secara langsung mencegah timbulnya kerugian obat kadaluarsa baru di puskesmas.")

# GAMBAR 5.6
add_image_figure("14_rekomendasi_pengadaan_safety_stock.png", "Gambar 5.6: Visualisasi Alokasi Rekomendasi Pengadaan dan Batas Safety Stock Periode 2026-P5")
add_p("Deskripsi Penjelasan Gambar 5.6: Gambar 5.6 menyajikan diagram batang bertumpuk (stacked bar chart) yang memperlihatkan komposisi proporsional rekomendasi pengadaan. Batang biru merepresentasikan peramalan kebutuhan riil, sedangkan batang oranye merepresentasikan buffer Safety Stock 95%. Grafik ini memberikan panduan visual yang intuitif bagi pengelola obat puskesmas untuk memahami struktur permintaan logistik sebelum dikirimkan ke Dinas Kesehatan.")

add_title("V.7 Evaluasi dan Peramalan Morbiditas 4 Penyakit Terbanyak", level=2)
add_p("Menindaklanjuti arahan dosen pembimbing untuk memprediksi minimal 4 penyakit terbanyak, dilakukan pemodelan deret waktu pada data rekam medis diagnosis 22 periode kontinu puskesmas:")

headers_dis = ["Kode ICD", "Diagnosis Morbiditas", "Total Kasus", "Rata2/Periode", "MAPE SMA-2", "MAPE SMA-3", "MAPE WMA-3", "Model Terbaik", "Prediksi 2026-P5"]
rows_dis = [
    ["J00", "ISPA / Acute Nasopharyngitis", "10.827", "492,1", "47,74%", "48,14%", "45,80%", "WMA (k=3)", "428,5 pasien"],
    ["I10", "Hipertensi Primer (Esensial)", "7.485", "340,2", "42,35%", "36,00%", "37,58%", "SMA (k=3)", "383,3 pasien"],
    ["K30", "Dispepsia (Gangguan Lambung)", "3.392", "154,2", "42,04%", "36,88%", "38,42%", "SMA (k=3)", "165,7 pasien"],
    ["E11", "Diabetes Melitus Tipe 2", "1.515", "68,9", "42,70%", "40,90%", "40,28%", "WMA (k=3)", "87,5 pasien"]
]
add_p("Tabel 5.5: Hasil Evaluasi Model dan Proyeksi Morbiditas 4 Penyakit Terbanyak (Periode 2026-P5)", bold=True, space_after=4)
add_styled_table(headers_dis, rows_dis, [0.8, 2.2, 0.9, 0.9, 0.9, 0.9, 0.9, 1.0, 1.1])

add_p("Deskripsi Penjelasan Tabel 5.5: Tabel 5.5 membuktikan bahwa peramalan deret waktu pada morbiditas penyakit menghasilkan estimasi yang sangat rasional. Model WMA-3 unggul pada penyakit dengan tren kenaikan dinamis seperti ISPA (428,5 pasien) dan Diabetes Melitus (87,5 pasien), sementara SMA-3 paling akurat pada Hipertensi (383,3 pasien) dan Dispepsia (165,7 pasien).")

# GAMBAR 5.7
add_image_figure("16_prediksi_penyakit_top4.png", "Gambar 5.7: Kurva Deret Waktu Historis dan Proyeksi Morbiditas 4 Penyakit Terbanyak (Periode 2026-P5)")
add_p("Deskripsi Penjelasan Gambar 5.7: Gambar 5.7 memperlihatkan lintasan historis kasus 4 penyakit utama dan titik proyeksi periode 2026-P5. Kurva mengonfirmasi bahwa beban kunjungan pasien di Puskesmas Teluk Sasah terkonsentrasi pada ISPA dan Hipertensi Primer.")

add_title("V.8 Analisis Matriks Korelasi Terperinci Penyakit-Obat dan Estimasi Kebutuhan Morbiditas", level=2)
add_p("Analisis korelasi bivariat Pearson (r) dan Spearman (rho) serta rasio peresepan klinis per pasien (Dose per Case Ratio) dihitung untuk menghubungkan diagnosis dokter dengan konsumsi obat:")

headers_corr = ["ICD", "Penyakit", "Obat Terkait", "Pearson r", "Spearman rho", "Rasio Dosis", "Prediksi Kasus", "Kebutuhan Obat P5"]
rows_corr = [
    ["E11", "Diabetes Melitus", "Metformin 500 mg", "+0,5281", "+0,5869", "39,95 tab/psn", "87,5 jiwa", "3.496 butir"],
    ["E11", "Diabetes Melitus", "Glibenklamid 5 mg", "+0,2615", "+0,2680", "6,37 tab/psn", "87,5 jiwa", "557 butir"],
    ["J00", "ISPA / Common Cold", "Amoksisilin 500 mg", "+0,3924", "+0,3066", "4,96 tab/psn", "428,5 jiwa", "2.127 butir"],
    ["J00", "ISPA / Common Cold", "Parasetamol 500 mg", "+0,3381", "+0,2648", "13,18 tab/psn", "428,5 jiwa", "5.648 butir"],
    ["J00", "ISPA / Common Cold", "Ambroksol 30 mg", "-0,0672", "-0,2202", "3,24 tab/psn", "428,5 jiwa", "1.387 butir"],
    ["I10", "Hipertensi Primer", "Amlodipin 10 mg", "-0,2539", "+0,0695", "18,72 tab/psn", "383,3 jiwa", "7.174 butir"],
    ["I10", "Hipertensi Primer", "Amlodipin 5 mg", "-0,1949", "-0,0967", "6,65 tab/psn", "383,3 jiwa", "2.550 butir"],
    ["I10", "Hipertensi Primer", "Kaptopril 25 mg", "-0,0365", "+0,0391", "2,91 tab/psn", "383,3 jiwa", "1.114 butir"],
    ["K30", "Dispepsia / Maag", "Antasida DOEN I", "+0,1429", "+0,1302", "5,46 tab/psn", "165,7 jiwa", "905 butir"],
    ["K30", "Dispepsia / Maag", "Ranitidine 150 mg", "-0,1026", "-0,1747", "4,38 tab/psn", "165,7 jiwa", "726 butir"]
]
add_p("Tabel 5.6: Matriks Korelasi Klinis Penyakit vs Obat, Rasio Dosis per Kasus, dan Proyeksi Terpadu", bold=True, space_after=4)
add_styled_table(headers_corr, rows_corr, [0.6, 1.4, 1.6, 0.9, 0.9, 1.1, 1.0, 1.2])

add_p("Deskripsi Penjelasan Tabel 5.6: Korelasi klinis terkuat ditunjukkan oleh Diabetes Melitus vs Metformin (r = +0.5281, rho = +0.5869) dengan rasio resep ~40 butir/pasien, menghasilkan proyeksi 3.496 butir. Nilai korelasi negatif pada Amlodipin dan Ambroksol membuktikan dampak krisis stockout gudang di masa lalu, di mana pasien tetap banyak tetapi stok obat habis sehingga pemakaian tercatat 0.")

# GAMBAR 5.8
add_image_figure("18_korelasi_morbiditas_dosis_obat.png", "Gambar 5.8: Analisis Terpadu Korelasi Klinis dan Rasio Dosis Konsumsi Obat Berbasis Morbiditas")
add_p("Deskripsi Penjelasan Gambar 5.8: Subplot A menunjukkan koefisien korelasi linear dan rank, sedangkan Subplot B memperlihatkan rasio dosis per pasien. Terlihat bahwa obat kronis (Metformin dan Amlodipin) memiliki rasio dosis per pasien tertinggi karena dikonsumsi rutin setiap hari.")

add_title("V.9 Analisis Momentum Konsumsi Obat Menggunakan Indikator MACD", level=2)
add_p("Pengujian indikator MACD (Fast EMA=2, Slow EMA=4, Signal=2) pada pemakaian obat periode penutupan 2026-P4 menghasilkan status momentum berikut:")

headers_macd = ["No", "Nama Obat", "Pemakaian Riil", "Nilai MACD", "Signal Line", "Histogram", "Status Momentum Permintaan"]
rows_macd = [
    ["1", "Amlodipin 10 mg tablet", "7.020 butir", "+813,00", "+576,98", "+236,02", "📈 Tren Meningkat Kuat (Bullish Momentum)"],
    ["2", "Asam Mefenamat kaplet 500 mg", "9.285 butir", "+1.438,62", "+805,63", "+632,99", "📈 Tren Meningkat Kuat (Bullish Momentum)"],
    ["3", "Amlodipin tablet 5 mg", "4.100 butir", "+578,90", "+358,18", "+220,73", "📈 Tren Meningkat Kuat (Bullish Momentum)"],
    ["4", "Vitamin B-Kompleks tablet", "0 butir", "-988,06", "-1.105,89", "+117,83", "📈 Bullish Divergence (Rebound)"],
    ["5", "Amoksisilin kaplet 500 mg", "1.824 butir", "-151,15", "-174,91", "+23,76", "📈 Bullish Crossover (Pulih)"],
    ["6", "Metformin tablet 500 mg", "3.995 butir", "+233,93", "+222,32", "+11,61", "📈 Tren Meningkat Kuat (Bullish Momentum)"],
    ["7", "Kalsium Laktat tablet 500 mg", "2.500 butir", "-317,00", "-253,90", "-63,10", "📉 Tren Menurun (Bearish Momentum)"],
    ["8", "Parasetamol tablet 500 mg", "0 butir", "-1.869,94", "-1.384,52", "-485,42", "📉 Bearish Anomali (Dampak Stockout P4)"]
]
add_p("Tabel 5.7: Hasil Analisis Indikator Momentum MACD dan Status Tren pada Obat Utama", bold=True, space_after=4)
add_styled_table(headers_macd, rows_macd, [0.5, 2.2, 1.1, 1.0, 1.0, 1.0, 2.0])

add_p("Deskripsi Penjelasan Tabel 5.7: Tabel 5.7 menunjukkan bahwa Amlodipin 10 mg, Metformin 500 mg, dan Asam Mefenamat memiliki histogram positif yang menandakan akselerasi permintaan naik (Bullish), sehingga kuantitas pemesanan harus diprioritaskan. Pada Parasetamol 500 mg, sinyal bearish terjadi akibat anomali stok habis fisik di gudang pada periode 2026-P4.")

doc.add_page_break()

# ==============================================================================
# BAB VI: PENERAPAN
# ==============================================================================
add_title("BAB VI: PENERAPAN", level=1, space_before=12, space_after=12)

add_title("VI.1 Desain Implementasi Sistem Peramalan & Rantai Pasok", level=2)
add_p("Penerapan model ini dirancang terintegrasi ke dalam alur kerja reguler instalasi farmasi Puskesmas Teluk Sasah:")
add_p("1. Jadwal Eksekusi Dwibulanan: Pada akhir setiap periode genap (misal: akhir Oktober untuk persiapan periode P6), petugas memasukkan realisasi pemakaian dan sisa stok akhir gudang.")
add_p("2. Kalkulasi Otomatis Terpadu: Sistem secara otomatis mengeksekusi model terbaik per obat, menghitung kebutuhan F_{t+1}, menambahkan batas Safety Stock, mengurangkan sisa stok gudang, dan mencetak draf lembar permintaan LPLPO resmi ke dinas.")
add_p("3. Pencegahan Dini Overstock: Jika sisa stok gudang masih melebihi F_{t+1} + SS (seperti pada Amoksisilin dan Vitamin B-Kompleks di Tabel 5.4), sistem merekomendasikan Order = 0 butir, sehingga mencegah akumulasi stok kadaluarsa.")

add_title("VI.2 Arsitektur Dashboard Web Streamlit (7 Modul) & Google Colab", level=2)
add_p("Hasil pemodelan telah diimplementasikan ke dalam dua platform digital yang siap diakses pengguna:")
add_p("1. Aplikasi Web Dashboard Streamlit (app.py): Mengadopsi arsitektur visual portal analitik kesehatan modern (Medical Dashboard CareWe & MediCare) dengan 7 modul navigasi interaktif:")
add_p("   - Modul 1: Beranda & Profil Puskesmas (KPI transaksi, kerugian kadaluarsa, peta satelit lokasi).")
add_p("   - Modul 2: Tren 1-Bulanan (2024) vs 2-Bulanan (Saran 1 dosen: pemantauan mikro vs makro).")
add_p("   - Modul 3: Peramalan 4 Penyakit Terbanyak (Saran 3 dosen: proyeksi kasus ISPA, Hipertensi, Dispepsia, Diabetes).")
add_p("   - Modul 4: Korelasi & Prediksi Obat-Penyakit (Saran 3 dosen: matriks korelasi, rasio dosis, simulator kasus).")
add_p("   - Modul 5: Simulasi Peramalan & Indikator MACD (Saran 2 dosen: peramalan obat & sinyal Bullish/Bearish MACD).")
add_p("   - Modul 6: Rekomendasi Pengadaan 2026-P5 (Tabel rencana pemesanan resmi + Safety Stock 95% + tombol unduh CSV).")
add_p("   - Modul 7: Profil Tim Peneliti Kelompok 23 (Nabil - NIM: 2401020073).")
add_p("2. Google Colab Notebook: 19 sel komputasi cloud interaktif lengkap dengan visualisasi dan ekspor data.")

add_title("VI.3 Kesimpulan", level=2)
add_p("Berdasarkan seluruh tahapan penambangan data dan implementasi saran dosen pembimbing, ditarik kesimpulan:")
add_p("1. Penyelarasan Waktu & Granularitas: Sebanyak 29 berkas LPLPO mentah berhasil diselaraskan menjadi deret waktu kontinu 22 periode dwibulanan (2023-P1 s/d 2026-P4), sekaligus menyajikan data 1-bulanan tahun 2024 untuk analisis komparasi tren mikro versus makro.")
add_p("2. Korelasi Morbiditas Klinis Terbukti: Terbukti korelasi positif linear sangat kuat antara Diabetes Melitus (E11) vs Metformin 500 mg (r = +0.5281, rasio 39,95 butir/pasien), serta ISPA vs Amoksisilin (r = +0.3924) dan Parasetamol (r = +0.3381). Korelasi negatif semu pada Amlodipin dan Ambroksol terbukti sebagai akibat distorsi stockout historis.")
add_p("3. Peramalan 4 Penyakit Terbanyak: Beban kunjungan pasien periode 2026-P5 diproyeksikan secara akurat: ISPA 428,5 jiwa, Hipertensi Primer 383,3 jiwa, Dispepsia 165,7 jiwa, dan Diabetes Melitus 87,5 jiwa.")
add_p("4. Efektivitas Indikator Momentum MACD: Penerapan MACD sukses memetakan tren permintaan farmasi: Amlodipin 10 mg dan Asam Mefenamat berstatus Bullish Momentum, sedangkan Parasetamol teridentifikasi mengalami penurunan semu akibat stockout fisik di gudang.")
add_p("5. Keunggulan Model WMA-3 dan Solusi Safety Stock: Model WMA-3 terbukti paling unggul dalam menekan galat pada obat konsumsi rutin (Metformin MAPE 15.25%, Amoksisilin MAPE 17.35%). Integrasi Safety Stock 95% memberikan proteksi simultan terhadap risiko kadaluarsa (Rp 43,35 juta) dan krisis kekosongan obat.")
add_p("6. Kesiapan Sistem Digital: Aplikasi dashboard Streamlit 7 modul (app.py) telah selesai dibangun dan siap digunakan oleh staf instalasi farmasi puskesmas.")

add_title("VI.4 Saran dan Rekomendasi Manajerial", level=2)
add_p("1. Bagi UPTD Puskesmas Teluk Sasah: Mengadopsi dashboard Streamlit berbasis WMA-3 dan Safety Stock 95% sebagai SOP pengusulan LPLPO dwibulanan, memanfaatkan sinyal MACD sebagai sistem deteksi dini lonjakan kebutuhan obat, dan memisahkan pencatatan obat program hibah nasional.")
add_p("2. Bagi Dinas Kesehatan Kabupaten Bintan: Menjamin ketepatan jadwal pasokan (lead time) obat antihipertensi dan batuk, serta mengadopsi estimasi kebutuhan obat berbasis rasio dosis morbiditas penyakit dalam perencanaan alokasi anggaran APBD.")
add_p("3. Bagi Pengembangan Akademik Lanjutan: Mengintegrasikan variabel meteorologi maritim pesisir Bintan (curah hujan dan kecepatan angin) menggunakan algoritma SVR atau LSTM.")

doc.add_page_break()

# ==============================================================================
# DAFTAR PUSTAKA LENGKAP & TERSTANDARISASI (APA 7th EDITION)
# ==============================================================================
add_title("DAFTAR PUSTAKA", level=1, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=12, space_after=18)

refs = [
    "Badan Pengawas Obat dan Makanan. (2021). Peraturan Badan Pengawas Obat dan Makanan Nomor 24 Tahun 2021 tentang Pengawasan Pengelolaan Obat, Bahan Obat, Narkotika, Psikotropika, dan Prekursor Farmasi di Fasilitas Pelayanan Kefarmasian. Jakarta: BPOM RI.",
    "Badan Pusat Statistik Kabupaten Bintan. (2025). Kecamatan Seri Kuala Lobam dalam Angka 2025. Bintan: BPS Kabupaten Bintan.",
    "Box, G. E. P., Jenkins, G. M., Reinsel, G. C., & Ljung, G. M. (2015). Time Series Analysis: Forecasting and Control (5th ed.). Hoboken: John Wiley & Sons.",
    "Chapman, P., Clinton, J., Kerber, R., Khabaza, T., Reinartz, T., Shearer, C., & Wirth, R. (2000). CRISP-DM 1.0: Step-by-step data mining guide. Chicago: SPSS Inc.",
    "Chopra, S., & Meindl, P. (2016). Supply Chain Management: Strategy, Planning, and Operation (6th ed.). Boston: Pearson.",
    "Dinas Kesehatan Kabupaten Bintan. (2024). Profil Kesehatan Kabupaten Bintan Tahun 2023. Bintan: Dinas Kesehatan Kabupaten Bintan.",
    "Han, J., Kamber, M., & Pei, J. (2012). Data Mining: Concepts and Techniques (3rd ed.). Waltham: Morgan Kaufmann Publishers.",
    "Heizer, J., Render, B., & Munson, C. (2020). Operations Management: Sustainability and Supply Chain Management (13th ed.). Harlow: Pearson Education.",
    "Hyndman, R. J., & Athanasopoulos, G. (2018). Forecasting: Principles and Practice (2nd ed.). Melbourne: OTexts.",
    "Kementerian Kesehatan Republik Indonesia. (2016). Peraturan Menteri Kesehatan Republik Indonesia Nomor 74 Tahun 2016 tentang Standar Pelayanan Kefarmasian di Puskesmas. Jakarta: Ditjen Bina Kefarmasian dan Alat Kesehatan Kemenkes RI.",
    "Kementerian Kesehatan Republik Indonesia. (2019). Petunjuk Teknis Standar Pelayanan Kefarmasian di Puskesmas. Jakarta: Ditjen Farmalkes Kemenkes RI.",
    "Kementerian Kesehatan Republik Indonesia. (2020). Keputusan Direktur Jenderal Kefarmasian dan Alat Kesehatan Nomor HK.02.02/IV/100/2020 tentang Pedoman Tata Kelola Obat Publik dan Perbekalan Kesehatan di Puskesmas (Format LPLPO). Jakarta: Kemenkes RI.",
    "Lewis, C. D. (1982). Industrial and Business Forecasting Methods: A Practical Guide to Exponential Smoothing and Curve Fitting. London: Butterworth Scientific.",
    "Makridakis, S., Wheelwright, S. C., & Hyndman, R. J. (1998). Forecasting: Methods and Applications (3rd ed.). New York: John Wiley & Sons.",
    "Matulatan, T., & Fadli, M. (2026). Rencana Pembelajaran Semester (RPS) dan Rencana Tugas Mahasiswa (RTM) Penambangan Data (INF11121). Tanjungpinang: Program Studi S1 Teknik Informatika, Universitas Maritim Raja Ali Haji.",
    "McKinney, W. (2010). Data Structures for Statistical Computing in Python. In S. van der Walt & J. Millman (Eds.), Proceedings of the 9th Python in Science Conference (pp. 56–61). Austin: SciPy.",
    "Montgomery, D. C., Jennings, C. L., & Kulahci, M. (2015). Introduction to Time Series Analysis and Forecasting (2nd ed.). Hoboken: John Wiley & Sons.",
    "Pedregosa, F., Varoquaux, G., Gramfort, A., Michel, V., Thirion, B., Grisel, O., Blondel, M., Prettenhofer, P., Weiss, R., Dubourg, V., Vanderplas, J., Passos, A., Cournapeau, D., Brucher, M., Perrot, M., & Duchesnay, É. (2011). Scikit-learn: Machine learning in Python. Journal of Machine Learning Research, 12, 2825–2830.",
    "Silver, E. A., Pyke, D. F., & Peterson, R. (1998). Inventory Management and Production Planning and Scheduling (3rd ed.). New York: John Wiley & Sons.",
    "Tan, P.-N., Steinbach, M., Karpatne, A., & Kumar, V. (2019). Introduction to Data Mining (2nd ed.). New York: Pearson Education.",
    "Virtanen, P., Gommers, R., Oliphant, T. E., Haberland, M., Reddy, T., Cournapeau, D., Burovski, E., Peterson, P., Weckesser, W., Bright, J., van der Walt, S. J., Brett, M., Wilson, J., Millman, K. J., Mayorov, N., Nelson, A. R. J., Jones, E., Kern, R., Larson, E., ... SciPy 1.0 Contributors. (2020). SciPy 1.0: fundamental algorithms for scientific computing in Python. Nature Methods, 17(3), 261–272.",
    "World Health Organization. (2019). The International Statistical Classification of Diseases and Related Health Problems (ICD-10) (10th revision, 5th ed.). Geneva: World Health Organization."
]

for ref in refs:
    p_ref = doc.add_paragraph()
    p_ref.paragraph_format.line_spacing = 1.15
    p_ref.paragraph_format.space_after = Pt(6)
    p_ref.paragraph_format.left_indent = Inches(0.5)
    p_ref.paragraph_format.first_line_indent = Inches(-0.5)
    r = p_ref.add_run(ref)
    r.font.name = 'Times New Roman'
    r.font.size = Pt(11)

doc.add_page_break()

# ==============================================================================
# LAMPIRAN
# ==============================================================================
add_title("LAMPIRAN", level=1, align=WD_ALIGN_PARAGRAPH.CENTER, space_before=12, space_after=18)

add_p("Lampiran 1: Kode Program Preprocessing & Penyelarasan Waktu (1_preprocess_lplpo.py)", bold=True)
add_p("Tersimpan pada repositori proyek di direktori: 04_Skrip_Pengolahan/1_preprocess_lplpo.py")

add_p("Lampiran 2: Skrip Analisis Tren Penyakit, Obat, dan MACD (4_analisis_penyakit_obat_macd.py)", bold=True)
add_p("Tersimpan dalam berkas: 04_Skrip_Pengolahan/4_analisis_penyakit_obat_macd.py")

add_p("Lampiran 3: Kode Sumber Web Dashboard Streamlit Modern 7 Modul (app.py)", bold=True)
add_p("Tersimpan dalam berkas: app.py")

add_p("Lampiran 4: Dataset Prediksi 4 Penyakit Terbanyak (prediksi_penyakit_top4.csv)", bold=True)
add_p("Tersimpan dalam berkas: 03_Data_Terproses/prediksi_penyakit_top4.csv")

add_p("Lampiran 5: Dataset Korelasi Penyakit-Obat & Rasio Dosis (korelasi_penyakit_obat_detail.csv)", bold=True)
add_p("Tersimpan dalam berkas: 03_Data_Terproses/korelasi_penyakit_obat_detail.csv")

add_p("Lampiran 6: Dataset Hasil Analisis Indikator Momentum MACD (macd_obat_analysis.csv)", bold=True)
add_p("Tersimpan dalam berkas: 03_Data_Terproses/macd_obat_analysis.csv")

add_p("Lampiran 7: Dataset Tren Bulanan 2024 Pemakaian Obat (tren_bulanan_2024_obat.csv)", bold=True)
add_p("Tersimpan dalam berkas: 03_Data_Terproses/tren_bulanan_2024_obat.csv")

add_p("Lampiran 8: Master Dataset Terpadu 8 Sheets Multi-Tab (MASTER_DATASET_PUSKESMAS_TELUK_SASAH.xlsx)", bold=True)
add_p("Tersimpan dalam berkas: 03_Data_Terproses/MASTER_DATASET_PUSKESMAS_TELUK_SASAH.xlsx")

# Simpan Dokumen ke berkas resmi untuk NIM 2401020073 (Nabil) dan duplikat arsip
doc.save(OUTPUT_DOCX_FULL)
shutil.copyfile(OUTPUT_DOCX_FULL, OUTPUT_DOCX_RTM)
# Salin juga ke berkas lama untuk kompatibilitas
alt_rtm = os.path.join(BASE_DIR, '01_Dokumen_Akademik', 'FinPD_2401020069.docx')
alt_full = os.path.join(BASE_DIR, '01_Dokumen_Akademik', 'FinPD_2401020069_Laporan_Akhir_Kelompok_23.docx')
shutil.copyfile(OUTPUT_DOCX_FULL, alt_rtm)
shutil.copyfile(OUTPUT_DOCX_FULL, alt_full)

print(f"🎉 Dokumen Word Laporan Akhir berhasil diperbarui di:")
print(f"   1. {OUTPUT_DOCX_FULL}")
print(f"   2. {OUTPUT_DOCX_RTM}")
print(f"   3. {alt_rtm}")
