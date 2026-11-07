#!/usr/bin/env python3
"""
04_Skrip_Pengolahan/generate_pd13_docx.py

Skrip generator berkas Microsoft Word (.docx) resmi untuk Tugas Individu Minggu 13:
TEKNIK PENGUKURAN (INF11121 Penambangan Data).
Luaran: 01_Dokumen_Akademik/PD13_2401020073.docx
Format Cover Resmi: "Penambangan Data - Minggu 13 - 2401020073 - Nabil"
Standar: Akademik Informatika Semester 5, Font Times New Roman 12 pt, Spasi 1.5,
Margin 4-3-3-3 cm, logo resmi UMRAH, tabel berarsir Deep Navy, blok formula matematika,
simulasi manual bertahap, dan DAPUS terstandarisasi APA 7th Edition.
"""

import os
import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import OxmlElement
from docx.oxml.ns import qn

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
LOGO_PATH = os.path.join(BASE_DIR, 'logo_umrah.png')
OUTPUT_DOCX = os.path.join(BASE_DIR, '01_Dokumen_Akademik', 'PD13_2401020073.docx')

doc = docx.Document()

# ==============================================================================
# 1. KONFIGURASI HALAMAN & MARGIN (Left 4cm, Top 3cm, Right 3cm, Bottom 3cm)
# ==============================================================================
for section in doc.sections:
    section.top_margin = Inches(1.18)     # 3.0 cm
    section.bottom_margin = Inches(1.18)  # 3.0 cm
    section.left_margin = Inches(1.57)    # 4.0 cm (standar penjilidan skripsi)
    section.right_margin = Inches(1.18)   # 3.0 cm
    section.page_width = Inches(8.27)     # A4
    section.page_height = Inches(11.69)   # A4

# Penomoran Halaman di Footer
section = doc.sections[0]
section.different_first_page_header_footer = True
footer = section.footer
p_ft = footer.paragraphs[0]
p_ft.alignment = WD_ALIGN_PARAGRAPH.RIGHT
r_ft = p_ft.add_run("Halaman ")
r_ft.font.name = 'Times New Roman'
r_ft.font.size = Pt(10)
r_ft.font.color.rgb = RGBColor(0, 0, 0)

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

# Default Style Configuration
style_normal = doc.styles['Normal']
style_normal.font.name = 'Times New Roman'
style_normal.font.size = Pt(12)
style_normal.font.color.rgb = RGBColor(0, 0, 0)
style_normal.paragraph_format.line_spacing = 1.5
style_normal.paragraph_format.space_after = Pt(6)

def set_cell_background(cell, fill_hex):
    """Memberi latar belakang warna sel tabel."""
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
    """Mengatur border tipis elegan pada tabel Word."""
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

def add_heading_custom(text, level=1, space_before=14, space_after=6):
    p = doc.add_paragraph()
    p.paragraph_format.space_before = Pt(space_before)
    p.paragraph_format.space_after = Pt(space_after)
    p.paragraph_format.keep_with_next = True
    run = p.add_run(text)
    run.font.name = 'Times New Roman'
    run.bold = True
    run.font.color.rgb = RGBColor(0, 0, 0)
    if level == 1:
        run.font.size = Pt(14)
        p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    elif level == 2:
        run.font.size = Pt(12.5)
        p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    else:
        run.font.size = Pt(12)
        p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    return p

def add_p(text, bold=False, italic=False, space_after=6, align=WD_ALIGN_PARAGRAPH.JUSTIFY):
    p = doc.add_paragraph()
    p.alignment = align
    p.paragraph_format.line_spacing = 1.5
    p.paragraph_format.space_after = Pt(space_after)
    run = p.add_run(text)
    run.font.name = 'Times New Roman'
    run.font.size = Pt(12)
    run.font.color.rgb = RGBColor(0, 0, 0)
    run.bold = bold
    run.italic = italic
    return p

def add_math_block(formula_text, label=""):
    """Menambahkan blok rumus matematika terindentasi dan terformat rapi."""
    tbl = doc.add_table(rows=1, cols=1)
    tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    tbl.autofit = False
    tbl.rows[0].cells[0].width = Inches(5.9)
    cell = tbl.rows[0].cells[0]
    set_cell_background(cell, "F8F9FA")
    set_cell_margins(cell, top=80, bottom=80, left=140, right=140)
    
    tcPr = cell._element.get_or_add_tcPr()
    tcBorders = OxmlElement('w:tcBorders')
    left_b = OxmlElement('w:left')
    left_b.set(qn('w:val'), 'single')
    left_b.set(qn('w:sz'), '18') # 2.25pt tebal
    left_b.set(qn('w:space'), '0')
    left_b.set(qn('w:color'), '333333') # Neutral charcoal
    tcBorders.append(left_b)
    for b_name in ['top', 'bottom', 'right']:
        b = OxmlElement(f'w:{b_name}')
        b.set(qn('w:val'), 'none')
        tcBorders.append(b)
    tcPr.append(tcBorders)

    p = cell.paragraphs[0]
    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p.paragraph_format.space_after = Pt(2)
    p.paragraph_format.line_spacing = 1.15
    run = p.add_run(formula_text)
    run.font.name = 'Times New Roman'
    run.font.size = Pt(11)
    run.italic = True
    run.font.color.rgb = RGBColor(0, 0, 0)
    
    if label:
        p_lbl = cell.add_paragraph()
        p_lbl.alignment = WD_ALIGN_PARAGRAPH.RIGHT
        p_lbl.paragraph_format.space_after = Pt(0)
        p_lbl.paragraph_format.line_spacing = 1.0
        r_lbl = p_lbl.add_run(label)
        r_lbl.font.name = 'Times New Roman'
        r_lbl.font.size = Pt(9.5)
        r_lbl.font.color.rgb = RGBColor(0, 0, 0)
    
    p_sp = doc.add_paragraph()
    p_sp.paragraph_format.space_after = Pt(4)
    p_sp.paragraph_format.line_spacing = 1.0

def add_code_block(code_text):
    """Menambahkan blok kode program dengan latar belakang abu dan border kiri hitam."""
    tbl = doc.add_table(rows=1, cols=1)
    tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    tbl.autofit = False
    tbl.rows[0].cells[0].width = Inches(5.9)
    cell = tbl.rows[0].cells[0]
    set_cell_background(cell, "F4F6F7")
    set_cell_margins(cell, top=100, bottom=100, left=140, right=140)
    
    tcPr = cell._element.get_or_add_tcPr()
    tcBorders = OxmlElement('w:tcBorders')
    left_b = OxmlElement('w:left')
    left_b.set(qn('w:val'), 'single')
    left_b.set(qn('w:sz'), '18')
    left_b.set(qn('w:space'), '0')
    left_b.set(qn('w:color'), '333333') # Border netral hitam
    tcBorders.append(left_b)
    for b_name in ['top', 'bottom', 'right']:
        b = OxmlElement(f'w:{b_name}')
        b.set(qn('w:val'), 'none')
        tcBorders.append(b)
    tcPr.append(tcBorders)

    p = cell.paragraphs[0]
    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p.paragraph_format.space_after = Pt(2)
    p.paragraph_format.line_spacing = 1.15
    run = p.add_run(code_text)
    run.font.name = 'Consolas'
    run.font.size = Pt(9.5)
    run.font.color.rgb = RGBColor(0, 0, 0)
    
    p_sp = doc.add_paragraph()
    p_sp.paragraph_format.space_after = Pt(4)
    p_sp.paragraph_format.line_spacing = 1.0

def add_custom_table(headers, data, col_widths=None, alignment=None):
    """Menambahkan tabel bergaya akademik berarsir Deep Navy."""
    tbl = doc.add_table(rows=len(data) + 1, cols=len(headers))
    tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    tbl.autofit = False
    set_table_borders(tbl)

    # Header Row
    hdr_cells = tbl.rows[0].cells
    for i, h_text in enumerate(headers):
        hdr_cells[i].text = h_text
        set_cell_background(hdr_cells[i], "1F4E79") # Navy
        set_cell_margins(hdr_cells[i], top=120, bottom=120, left=120, right=120)
        hdr_cells[i].vertical_alignment = WD_ALIGN_VERTICAL.CENTER
        p = hdr_cells[i].paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        p.paragraph_format.space_after = Pt(0)
        p.paragraph_format.line_spacing = 1.15
        for r in p.runs:
            r.font.name = 'Times New Roman'
            r.font.size = Pt(10)
            r.font.bold = True
            r.font.color.rgb = RGBColor(255, 255, 255)

    # Data Rows
    for r_idx, row_data in enumerate(data):
        row_cells = tbl.rows[r_idx + 1].cells
        bg_color = "F2F5F8" if r_idx % 2 == 1 else "FFFFFF"
        for c_idx, val in enumerate(row_data):
            row_cells[c_idx].text = str(val)
            set_cell_background(row_cells[c_idx], bg_color)
            set_cell_margins(row_cells[c_idx], top=90, bottom=90, left=110, right=110)
            row_cells[c_idx].vertical_alignment = WD_ALIGN_VERTICAL.CENTER
            p = row_cells[c_idx].paragraphs[0]
            if alignment and c_idx < len(alignment):
                p.alignment = alignment[c_idx]
            else:
                p.alignment = WD_ALIGN_PARAGRAPH.CENTER if c_idx == 0 else WD_ALIGN_PARAGRAPH.LEFT
            p.paragraph_format.space_after = Pt(0)
            p.paragraph_format.line_spacing = 1.15
            for r in p.runs:
                r.font.name = 'Times New Roman'
                r.font.size = Pt(9.5)
                r.font.color.rgb = RGBColor(0, 0, 0)

    # Column Widths
    if col_widths:
        for row in tbl.rows:
            for idx, w in enumerate(col_widths):
                if idx < len(row.cells):
                    row.cells[idx].width = Inches(w)

    p_sp = doc.add_paragraph()
    p_sp.paragraph_format.space_after = Pt(4)
    p_sp.paragraph_format.line_spacing = 1.0
    return tbl

print("Menyusun Halaman Sampul (Cover)...")
# ==============================================================================
# 2. HALAMAN SAMPUL (COVER) RESMI
# ==============================================================================
p_cov_hdr = doc.add_paragraph()
p_cov_hdr.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_cov_hdr.paragraph_format.space_before = Pt(8)
p_cov_hdr.paragraph_format.space_after = Pt(4)

r_kemen = p_cov_hdr.add_run("KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI\n")
r_kemen.font.name = 'Times New Roman'
r_kemen.font.size = Pt(11)
r_kemen.bold = True
r_kemen.font.color.rgb = RGBColor(0, 0, 0)

r_univ = p_cov_hdr.add_run("UNIVERSITAS MARITIM RAJA ALI HAJI\n")
r_univ.font.name = 'Times New Roman'
r_univ.font.size = Pt(13)
r_univ.bold = True
r_univ.font.color.rgb = RGBColor(0, 0, 0)

r_fak = p_cov_hdr.add_run("FAKULTAS TEKNIK DAN TEKNOLOGI KEMARITIMAN\nPROGRAM STUDI S1 TEKNIK INFORMATIKA\n")
r_fak.font.name = 'Times New Roman'
r_fak.font.size = Pt(10.5)
r_fak.bold = True
r_fak.font.color.rgb = RGBColor(0, 0, 0)

# LOGO UNIVERSITAS MARITIM RAJA ALI HAJI (UMRAH)
if os.path.exists(LOGO_PATH):
    p_logo = doc.add_paragraph()
    p_logo.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_logo.paragraph_format.space_before = Pt(6)
    p_logo.paragraph_format.space_after = Pt(8)
    r_logo = p_logo.add_run()
    r_logo.add_picture(LOGO_PATH, width=Inches(1.85))

# KOTAK JUDUL UTAMA COVER
tbl_box = doc.add_table(rows=1, cols=1)
tbl_box.alignment = WD_TABLE_ALIGNMENT.CENTER
tbl_box.rows[0].cells[0].width = Inches(5.9)
c_box = tbl_box.rows[0].cells[0]
set_cell_background(c_box, "F0F4F8")
set_cell_margins(c_box, top=130, bottom=130, left=140, right=140)

tcPr = c_box._element.get_or_add_tcPr()
tcBorders = OxmlElement('w:tcBorders')
for b_name in ['top', 'left', 'bottom', 'right']:
    b = OxmlElement(f'w:{b_name}')
    b.set(qn('w:val'), 'single')
    b.set(qn('w:sz'), '12') # 1.5 pt
    b.set(qn('w:space'), '0')
    b.set(qn('w:color'), '1F4E79')
    tcBorders.append(b)
tcPr.append(tcBorders)

p_box = c_box.paragraphs[0]
p_box.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_box.paragraph_format.line_spacing = 1.2
p_box.paragraph_format.space_after = Pt(2)

# Persis sesuai aturan RTM
r_rtm = p_box.add_run("COVER HALAMAN DEPAN (FORMAT RESMI RTM):\n")
r_rtm.font.name = 'Times New Roman'
r_rtm.font.size = Pt(9.5)
r_rtm.font.color.rgb = RGBColor(0, 0, 0)
r_rtm.bold = True

r_title_rtm = p_box.add_run("Penambangan Data - Minggu 13 - 2401020073 - Nabil\n\n")
r_title_rtm.font.name = 'Times New Roman'
r_title_rtm.font.size = Pt(14)
r_title_rtm.bold = True
r_title_rtm.font.color.rgb = RGBColor(0, 0, 0)

r_sub = p_box.add_run("TUGAS MENULIS INDIVIDU: KAJIAN MENDALAM TEKNIK PENGUKURAN\n")
r_sub.font.name = 'Times New Roman'
r_sub.font.size = Pt(11)
r_sub.bold = True
r_sub.font.color.rgb = RGBColor(0, 0, 0)

r_desc = p_box.add_run("Evaluasi Kinerja Model, Generalization Error, Dekomposisi Bias-Variance, Metode Resampling (Holdout, K-Fold, Bootstrap .632), Matriks Konfusi, Kurva ROC/AUC, Metrik Regresi & Kluster, serta Simulasi Manual")
r_desc.font.name = 'Times New Roman'
r_desc.font.size = Pt(10)
r_desc.italic = True
r_desc.font.color.rgb = RGBColor(0, 0, 0)

p_space = doc.add_paragraph()
p_space.paragraph_format.space_before = Pt(8)

# DATA MAHASISWA & DOSEN
p_meta = doc.add_paragraph()
p_meta.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_meta.paragraph_format.line_spacing = 1.2

r_mhs = p_meta.add_run("Disusun Oleh:\n")
r_mhs.font.name = 'Times New Roman'
r_mhs.font.size = Pt(10.5)
r_mhs.font.color.rgb = RGBColor(0, 0, 0)

r_mhs_name = p_meta.add_run("NABIL\nNIM: 2401020073\n\n")
r_mhs_name.font.name = 'Times New Roman'
r_mhs_name.font.size = Pt(12)
r_mhs_name.bold = True
r_mhs_name.font.color.rgb = RGBColor(0, 0, 0)

r_dsn = p_meta.add_run("Dosen Pengampu Mata Kuliah:\n")
r_dsn.font.name = 'Times New Roman'
r_dsn.font.size = Pt(10.5)
r_dsn.font.color.rgb = RGBColor(0, 0, 0)

r_dsn_names = p_meta.add_run("1. Tekad Matulatan, S.T., M.Kom.\n2. Muhamad Fadli, S.T., M.Kom.\n\n")
r_dsn_names.font.name = 'Times New Roman'
r_dsn_names.font.size = Pt(11)
r_dsn_names.bold = True
r_dsn_names.font.color.rgb = RGBColor(0, 0, 0)

r_loc = p_meta.add_run("TANJUNGPINANG, KEPULAUAN RIAU\n2026")
r_loc.font.name = 'Times New Roman'
r_loc.font.size = Pt(10.5)
r_loc.bold = True
r_loc.font.color.rgb = RGBColor(0, 0, 0)

doc.add_page_break()

# ==============================================================================
# BAB I: KONSEP DASAR EVALUASI MODEL DAN GENERALIZATION ERROR
# ==============================================================================
print("Menyusun Bab I...")
add_heading_custom("BAB I: KONSEP DASAR EVALUASI MODEL DAN GENERALIZATION ERROR", level=1)

add_heading_custom("1.1 Urgensi Evaluasi Kinerja Model dalam Data Science Lifecycle", level=2)
add_p(
    "Dalam kerangka kerja standar industri penambangan data seperti CRISP-DM (Cross-Industry Standard Process for Data Mining), "
    "tahap Evaluasi (Evaluation) memegang peranan krusial sebagai gerbang kualitas akhir sebelum model dilepas ke lingkungan produksi. "
    "Membangun model yang memiliki akurasi sempurna pada data latih adalah hal yang sangat mudah dicapai; namun, tantangan sejati seorang "
    "ilmuwan data dan insinyur informatika adalah memastikan bahwa model tersebut memiliki daya generalisasi yang tangguh saat menghadapi "
    "data baru di dunia nyata yang belum pernah dilihat sebelumnya (unseen data)."
)
add_p(
    "Evaluasi performa bukan sekadar mencari nilai akurasi agregat semata, melainkan proses diagnostik mendalam untuk menguji apakah "
    "model mempelajari pola sinyal inheren yang benar atau hanya menghafal derau acak (noise), bagaimana model menyeimbangkan risiko "
    "antara kesalahan alarm palsu dan kesalahan terlewat, serta seberapa stabil performa model jika terjadi pergeseran distribusi data."
)

add_heading_custom("1.2 Training Error, Validation Error, Test Error, dan Pencegahan Data Leakage", level=2)
add_p(
    "Untuk mengukur generalisasi secara obyektif, korpus data wajib dipartisi ke dalam tiga partisi disjoin:\n"
    "1. Training Error (E_train): Galat empiris yang dihitung langsung pada data latih yang digunakan mengoptimasi parameter model. "
    "E_train yang rendah tidak menjamin model berkualitas baik karena dapat merefleksikan penghafalan data mentah.\n"
    "2. Validation Error (E_val): Galat yang dievaluasi pada subset independen selama proses eksperimen untuk memandu pemilihan arsitektur, "
    "penalaan hiperparameter (hyperparameter tuning), dan kriteria penghentian dini (early stopping).\n"
    "3. Test Error (E_test): Galat yang diuji pada himpunan data final yang diisolasi secara mutlak dan hanya dievaluasi satu kali pada akhir siklus."
)
add_p(
    "Pencegahan Kebocoran Data (Data Leakage): Mahasiswa informatika wajib mematuhi kaidah bahwa seluruh transformasi data "
    "(seperti normalisasi Min-Max, standarisasi Z-Score, imputasi nilai kosong, atau rekayasa fitur) harus dihitung parameternya HANYA "
    "menggunakan data latih, dan kemudian diaplikasikan secara pasif ke data uji. Melakukan penskalaan pada seluruh dataset sebelum pemisahan "
    "merupakan pelanggaran fatal data leakage yang membuat metrik evaluasi tampak optimis palsu."
)

add_heading_custom("1.3 Fenomena Overfitting vs Underfitting dan Kurva Pembelajaran", level=2)
add_p(
    "Hubungan antara kapasitas representasi model dan galat menghasilkan dua patologi pembelajaran mesin:\n"
    "• Underfitting (Bias Tinggi): Model terlalu sederhana (misalnya menerapkan regresi linier pada pola data kuadratik). "
    "Model gagal menangkap pola fundamental data, ditandai oleh E_train dan E_val yang sama-sama tinggi.\n"
    "• Overfitting (Varians Tinggi): Model terlalu kompleks (misalnya Decision Tree yang ditumbuhkan hingga kedalaman tak hingga). "
    "Model menghafal derau dan pencilan data latih, ditandai oleh jurang pemisah lebar di mana E_train mendekati nol namun E_val melonjak tinggi.\n"
    "Titik optimal tercapai pada saat E_val mencapai nilai minimum global sebelum kurva mulai berbalik naik."
)

add_heading_custom("1.4 Teorema Dekomposisi Bias-Variance Tradeoff secara Matematis", level=2)
add_p(
    "Secara analitik, untuk masalah regresi dengan fungsi target y = f(x) + epsilon (di mana ekspektasi epsilon = 0 dan varians = sigma^2), "
    "ekspektasi galat kuadrat rata-rata dari estimator dapat diuraikan secara elegan menjadi tiga komponen aditif:"
)
add_math_block(
    "E[(y - f_hat(x))^2] = (E[f_hat(x)] - f(x))^2 + E[(f_hat(x) - E[f_hat(x)])^2] + sigma^2\n"
    "                    = [ Bias(f_hat(x)) ]^2 + [ Varians(f_hat(x)) ] + [ Irreducible Error ]",
    "Formula 1.1: Dekomposisi Bias-Variance Tradeoff"
)
add_p(
    "Bias kuadrat merefleksikan penyederhanaan asumsi model, Varians merefleksikan sensitivitas model terhadap pergantian sampel data latih, "
    "dan Irreducible Error (sigma^2) adalah batas bawah acak teoretis dari derau data yang tidak dapat dieliminasi oleh model apa pun. "
    "Desain model selalu menuntut kompromi (tradeoff) optimal antara Bias dan Varians."
)

# ==============================================================================
# BAB II: STRATEGI PARTISI DAN METODE RESAMPLING DATA
# ==============================================================================
print("Menyusun Bab II...")
add_heading_custom("BAB II: STRATEGI PARTISI DAN METODE RESAMPLING DATA", level=1)

add_heading_custom("2.1 Metode Penahanan (Holdout Method)", level=2)
add_p(
    "Metode Holdout membagi dataset secara acak menjadi data latih (70-80%) dan data uji (20-30%). "
    "Meskipun efisien secara komputasional karena pelatihan hanya berjalan satu kali, metode ini memiliki varians estimasi yang sangat tinggi "
    "karena sangat rentan terhadap komposisi pembagian sampel acak."
)

add_heading_custom("2.2 Validasi Silang K-Lipat (K-Fold Cross-Validation) dan Stratified K-Fold", level=2)
add_p(
    "Untuk mereduksi varians estimasi, K-Fold Cross-Validation membagi dataset menjadi K lipatan berukuran sama. "
    "Algoritma dieksekusi K kali; pada setiap iterasi ke-k, lipatan k menjadi data uji dan K-1 lipatan lainnya menjadi data latih. "
    "Estimasi performa akhir dihitung dari rata-rata dan standar deviasi ke-K pengujian:"
)
add_math_block(
    "E_bar = (1 / K) * Sigma_{k=1}^K E_k,   sigma_E = sqrt( (1 / (K-1)) * Sigma_{k=1}^K (E_k - E_bar)^2 )",
    "Formula 2.1: Estimasi Performa K-Fold Cross-Validation"
)
add_p(
    "Nilai standar industri adalah K = 5 atau K = 10 (Kohavi, 1995). Pada dataset dengan kelas tidak seimbang (imbalanced), "
    "teknik Stratified K-Fold mutlak wajib digunakan untuk memaksakan agar proporsi kelas pada setiap lipatan sama persis dengan proporsi data induk."
)

add_heading_custom("2.3 Leave-One-Out Cross-Validation (LOOCV)", level=2)
add_p(
    "LOOCV adalah bentuk ekstrem K-Fold di mana K = N (jumlah seluruh data). Pada setiap iterasi, tepat 1 sampel menjadi data uji dan N-1 menjadi latih. "
    "LOOCV menghasilkan estimator performa yang hampir tanpa bias (unbiased), namun menuntut beban komputasi masif O(N) dan memiliki varians estimasi "
    "yang relatif tinggi karena ke-N model yang dilatih memiliki sampel data latih yang hampir identik."
)

add_heading_custom("2.4 Metode Bootstrap Resampling dan Estimator .632", level=2)
add_p(
    "Metode Bootstrap (Efron, 1979) melakukan penarikan sampel berukuran N dengan pengembalian (sampling with replacement). "
    "Peluang sebuah sampel tidak pernah terpilih sama sekali setelah N kali penarikan independen dirumuskan melalui limit kalkulus:"
)
add_math_block(
    "lim_{N -> inf} (1 - 1/N)^N = 1 / e approx 0.3678  (36.8%)",
    "Formula 2.2: Teorema Peluang Sampel Out-of-Bag (OOB)"
)
add_p(
    "Artinya, himpunan latih bootstrap rata-rata memuat 63.2% sampel unik, sementara 36.8% sisanya bertindak sebagai himpunan uji Out-of-Bag (OOB). "
    "Efron merumuskan Estimator .632 Bootstrap untuk mengombinasikan galat latih optimis dan galat OOB pesimis:"
)
add_math_block(
    "E_{.632} = 0.368 * E_train + 0.632 * E_OOB",
    "Formula 2.3: Estimator .632 Bootstrap"
)

# ==============================================================================
# BAB III: METRIK EVALUASI MODEL KLASIFIKASI
# ==============================================================================
print("Menyusun Bab III...")
add_heading_custom("BAB III: METRIK EVALUASI MODEL KLASIFIKASI", level=1)

add_heading_custom("3.1 Struktur Matriks Konfusi (Confusion Matrix)", level=2)
add_p(
    "Matriks konfusi menyajikan pemetaan komparatif antara label kelas aktual dan label prediksi model pada kasus klasifikasi biner:"
)

# Tabel Matriks Konfusi
cm_hdr = ["Kondisi Aktual \\ Hasil Prediksi", "Model Memprediksi Positif (1)", "Model Memprediksi Negatif (0)", "Total Aktual"]
cm_data = [
    ["Aktual Positif (1)", "True Positive (TP)\n(Benar Terdeteksi)", "False Negative (FN)\n(Galat Tipe II / Terlewat)", "P = TP + FN"],
    ["Aktual Negatif (0)", "False Positive (FP)\n(Galat Tipe I / Alarm Palsu)", "True Negative (TN)\n(Benar Ditolak)", "N = FP + TN"],
    ["Total Prediksi", "TP + FP", "FN + TN", "Total = TP + TN + FP + FN"]
]
add_p("Tabel 3.1: Struktur Matriks Konfusi Klasifikasi Biner", bold=True)
add_custom_table(cm_hdr, cm_data, col_widths=[1.5, 1.8, 1.8, 1.1])

add_heading_custom("3.2 Akurasi, Error Rate, dan Paradoks Akurasi", level=2)
add_p(
    "Akurasi mengukur proporsi prediksi yang benar secara keseluruhan, sedangkan Error Rate adalah komplemennya:"
)
add_math_block(
    "Accuracy = (TP + TN) / (TP + TN + FP + FN)\n"
    "Error Rate = 1 - Accuracy = (FP + FN) / (TP + TN + FP + FN)",
    "Formula 3.1: Akurasi dan Error Rate"
)
add_p(
    "Paradoks Akurasi (The Accuracy Paradox): Pada dataset yang sangat timpang (misal 99% transaksi legal vs 1% penipuan), "
    "model bodoh yang selalu memprediksi semua transaksi sebagai 'Legal' akan memperoleh akurasi 99.0%! Namun model tersebut tidak memiliki "
    "kegunaan praktis karena gagal mendeteksi satu pun penipuan. Akurasi sangat berbahaya jika dijadikan ukuran tunggal."
)

add_heading_custom("3.3 Presisi, Recall (Sensitivitas), dan Spesifisitas", level=2)
add_p(
    "• Presisi (Positive Predictive Value): Mengukur kemurnian prediksi positif model. Dari seluruh sampel yang diprediksi positif, "
    "berapa persen yang benar-benar positif? Berfokus meminimalkan False Positive (alarm palsu, misal filter spam email).\n"
    "• Recall / Sensitivitas (True Positive Rate): Mengukur daya tangkap model. Dari seluruh sampel yang faktanya positif, berapa persen "
    "yang berhasil dijaring model? Berfokus meminimalkan False Negative (pasien kanker terlewat divonis sehat).\n"
    "• Spesifisitas (True Negative Rate): Kemampuan model mengenali sampel negatif sejati (TN / (TN + FP))."
)
add_math_block(
    "Precision = TP / (TP + FP)\n"
    "Recall = TP / (TP + FN)\n"
    "Specificity = TN / (TN + FP),   FPR = 1 - Specificity = FP / (TN + FP)",
    "Formula 3.2: Presisi, Recall, Spesifisitas, dan FPR"
)

add_heading_custom("3.4 F-Score (Harmonic Mean) dan Generalisasi F-Beta", level=2)
add_p(
    "F1-Score adalah rata-rata harmonik antara Presisi dan Recall. Rata-rata harmonik memberikan penalti berat jika salah satu metrik bernilai sangat rendah:"
)
add_math_block(
    "F1-Score = 2 * (Precision * Recall) / (Precision + Recall) = (2 * TP) / (2 * TP + FP + FN)\n"
    "F_beta = (1 + beta^2) * (Precision * Recall) / ((beta^2 * Precision) + Recall)",
    "Formula 3.3: F1-Score dan Generalisasi F-Beta"
)
add_p(
    "Jika beta = 2 (F2-Score), sistem memberi bobot 2x lebih besar pada Recall dibanding Presisi (diagnosis medis). "
    "Jika beta = 0.5 (F0.5-Score), sistem memberi bobot 2x lebih besar pada Presisi dibanding Recall."
)

add_heading_custom("3.5 Matthews Correlation Coefficient (MCC) dan Cohen's Kappa", level=2)
add_p(
    "MCC (Matthews, 1975) dianggap sebagai metrik evaluasi biner paling obyektif dan seimbang karena melibatkan seluruh empat sel matriks konfusi:"
)
add_math_block(
    "MCC = ( (TP * TN) - (FP * FN) ) / sqrt( (TP + FP)(TP + FN)(TN + FP)(TN + FN) )",
    "Formula 3.4: Matthews Correlation Coefficient (MCC)"
)
add_p(
    "Rentang nilai MCC adalah [-1.0, +1.0]. Nilai +1.0 menunjukkan prediksi sempurna, 0.0 setara tebakan acak, dan -1.0 kontradiksi sempurna."
)

add_heading_custom("3.6 Agregasi Multikelas: Macro, Micro, dan Weighted Average", level=2)
add_p(
    "Untuk klasifikasi multikelas (C > 2):\n"
    "1. Macro-Average: Merata-ratakan skor tiap kelas secara setara tanpa bobot. Sangat baik untuk mengevaluasi kelas minoritas.\n"
    "2. Micro-Average: Mengakumulasikan total global TP, FP, FN lalu menghitung metrik. Didominasi oleh kelas mayoritas.\n"
    "3. Weighted-Average: Merata-ratakan metrik tiap kelas dengan bobot sebanding dengan jumlah sampel aktual (support) masing-masing."
)

# ==============================================================================
# BAB IV: ANALISIS KURVA ROC DAN PRECISION-RECALL (PR)
# ==============================================================================
print("Menyusun Bab IV...")
add_heading_custom("BAB IV: ANALISIS KURVA ROC DAN PRECISION-RECALL (PR)", level=1)

add_heading_custom("4.1 Teori Pemindaian Ambang Batas Probabilitas Klasifikasi", level=2)
add_p(
    "Model klasifikasi modern mengeluarkan probabilitas kontinu p_hat in [0, 1]. Penetapan label biner bergantung pada ambang batas keputusan "
    "(classification threshold theta). Mengubah theta dari 1.0 ke 0.0 akan menggeser trade-off antara TPR dan FPR secara kontinu."
)

add_heading_custom("4.2 Kurva Receiver Operating Characteristic (ROC)", level=2)
add_p(
    "Kurva ROC memetakan False Positive Rate (FPR) pada sumbu X versus True Positive Rate (TPR / Recall) pada sumbu Y untuk seluruh kemungkinan theta in [0, 1]. "
    "Titik (0, 0) mewakili theta = 1.0, titik (1, 1) mewakili theta = 0.0, garis diagonal y = x mewakili tebakan acak murni, "
    "dan titik ideal (0, 1) pada sudut kiri atas merepresentasikan pengklasifikasi sempurna (100% sensitivitas tanpa alarm palsu)."
)

add_heading_custom("4.3 Area Under the ROC Curve (AUC-ROC) dan Interpretasi Probabilistik", level=2)
add_p(
    "AUC mengukur integral luasan di bawah kurva ROC pada rentang [0, 1]. Secara probabilistik, nilai AUC sama dengan peluang bahwa model "
    "akan memberikan skor probabilitas lebih tinggi kepada sampel positif acak dibanding sampel negatif acak:\n"
    "AUC = P( p_hat(x^+) > p_hat(x^-) ).\n"
    "Kategori Penilaian AUC: 0.90-1.00 (Luar Biasa / Excellent), 0.80-0.90 (Baik / Good), 0.70-0.80 (Cukup / Fair), "
    "0.60-0.70 (Buruk / Poor), dan <= 0.50 (Gagal / Acak)."
)

add_heading_custom("4.4 Kurva Precision-Recall (PR Curve) untuk Data Sangat Tidak Seimbang", level=2)
add_p(
    "Pada kasus ketidakseimbangan kelas ekstrem (misal 1:1000 pada deteksi fraud), kurva ROC memberikan ilusi optimis palsu karena penyebut FPR "
    "adalah total negatif (TN + FP) yang sangat besar. Kurva PR (Recall pada sumbu X vs Precision pada sumbu Y) lebih direkomendasikan "
    "karena tidak melibatkan TN, sehingga fokus secara murni mengevaluasi kualitas pendeteksian pada kelas minoritas."
)

# ==============================================================================
# BAB V: METRIK EVALUASI MODEL PREDIKSI DAN REGRESI
# ==============================================================================
print("Menyusun Bab V...")
add_heading_custom("BAB V: METRIK EVALUASI MODEL PREDIKSI DAN REGRESI", level=1)

add_heading_custom("5.1 Karakteristik Galat Regresi dan Deret Waktu", level=2)
add_p(
    "Pada penambangan data berbasis regresi dan peramalan deret waktu (seperti proyek prediksi stok obat Puskesmas Teluk Sasah), "
    "keluaran model adalah nilai numerik kontinu. Galat prediksi individu didefinisikan sebagai e_i = y_i - y_hat_i."
)

add_heading_custom("5.2 Metrik Skala Dependen: MAE, MAD, MSE, dan RMSE", level=2)
add_p(
    "Metrik skala dependen memiliki satuan fisik yang terkait langsung dengan data asli:"
)
add_math_block(
    "MAE = (1 / n) * Sigma_{i=1}^n |y_i - y_hat_i|\n"
    "MSE = (1 / n) * Sigma_{i=1}^n (y_i - y_hat_i)^2\n"
    "RMSE = sqrt(MSE) = sqrt( (1 / n) * Sigma_{i=1}^n (y_i - y_hat_i)^2 )",
    "Formula 5.1: Formulasi MAE, MSE, dan RMSE"
)
add_p(
    "MAE bersifat linier dan lebih tahan terhadap outlier. Sebaliknya, MSE dan RMSE memberikan penalti kuadratik yang sangat berat "
    "terhadap kesalahan berukuran besar. Selisih yang lebar antara RMSE dan MAE mengindikasikan keberadaan kesalahan-kesalahan ekstrem pada data uji."
)

add_heading_custom("5.3 Metrik Persentase dan Relatif: MAPE, sMAPE, dan MASE", level=2)
add_p(
    "Metrik persentase membebaskan evaluasi dari satuan unit data sehingga memungkinkan komparasi performa lintas produk:"
)
add_math_block(
    "MAPE = (100% / n) * Sigma_{i=1}^n |(y_i - y_hat_i) / y_i|\n"
    "sMAPE = (100% / n) * Sigma_{i=1}^n [ (2 * |y_i - y_hat_i|) / (|y_i| + |y_hat_i|) ]\n"
    "MASE = MAE / [ (1 / (n-1)) * Sigma_{i=2}^n |y_i - y_{i-1}| ]",
    "Formula 5.2: Formulasi MAPE, sMAPE, dan MASE"
)
add_p(
    "sMAPE mengatasi asimetri MAPE dan pembagian dengan nol. MASE (Hyndman & Koehler, 2006) membandingkan akurasi model terhadap metode peramalan naif; "
    "nilai MASE < 1 membuktikan bahwa model bekerja lebih unggul dibanding peramalan naif."
)

add_heading_custom("5.4 Koefisien Determinasi (R-Squared) dan Adjusted R-Squared", level=2)
add_p(
    "R-Squared mengukur proporsi variabilitas nilai target aktual yang berhasil dijelaskan oleh model penambangan data:"
)
add_math_block(
    "R^2 = 1 - (SS_res / SS_tot) = 1 - [ Sigma (y_i - y_hat_i)^2 / Sigma (y_i - y_bar)^2 ]\n"
    "R_adj^2 = 1 - [ ((1 - R^2)(n - 1)) / (n - p - 1) ]",
    "Formula 5.3: Koefisien Determinasi R^2 dan Adjusted R^2"
)

# ==============================================================================
# BAB VI: PENGAYAAN EVALUASI MODEL KLUSTER (UNSUPERVISED METRICS)
# ==============================================================================
print("Menyusun Bab VI...")
add_heading_custom("BAB VI: PENGAYAAN EVALUASI MODEL KLUSTER (UNSUPERVISED METRICS)", level=1)

add_p(
    "Sebagai kesinambungan kurikulum (Sub-CPMK0416 dan Sub-CPMK0413), evaluasi kluster menguji validitas pengelompokan tanpa tersedianya label kebenaran dasar:\n"
    "1. Silhouette Coefficient (Rousseeuw, 1987): Mengukur rasio separasi antar-kluster b(i) terhadap kohesi internal a(i). Rentang [-1, +1]. Nilai > 0.70 menunjukkan kluster sangat solid.\n"
    "2. Davies-Bouldin Index (DBI): Mengukur rata-rata rasio kesamaan antar-kluster. Nilai mendekati nol menunjukkan kluster terbaik.\n"
    "3. Calinski-Harabasz Index (CH): Rasio variansi antar-kluster (SSB) terhadap dalam-kluster (SSW) yang disesuaikan derajat kebebasan. Nilai semakin tinggi semakin optimal.\n"
    "4. Dunn Index: Rasio jarak minimum antar-sampel beda kluster terhadap diameter maksimum intra-kluster."
)

# ==============================================================================
# BAB VII: SIMULASI PERHITUNGAN MANUAL LANGKAH DEMI LANGKAH
# ==============================================================================
print("Menyusun Bab VII...")
add_heading_custom("BAB VII: SIMULASI PERHITUNGAN MANUAL LANGKAH DEMI LANGKAH (HAND CALCULATION TRACE)", level=1)

add_heading_custom("7.1 Evaluasi Matriks Konfusi Klasifikasi Biner (100 Sampel Medis)", level=2)
add_p(
    "Sebuah model diagnostik diuji pada 100 pasien rekam medis (40 Pasien Sakit Positif, 60 Pasien Sehat Negatif). "
    "Hasil inferensi model menghasilkan:\n"
    "• True Positive (TP) = 32   • False Positive (FP) = 6\n"
    "• False Negative (FN) = 8   • True Negative (TN) = 54"
)

t71_hdr = ["Kondisi Aktual \\ Prediksi", "Prediksi Sakit (1)", "Prediksi Sehat (0)", "Total Aktual"]
t71_data = [
    ["Aktual Sakit (1)", "TP = 32", "FN = 8", "P = 40"],
    ["Aktual Sehat (0)", "FP = 6", "TN = 54", "N = 60"],
    ["Total Prediksi", "38", "62", "N_total = 100"]
]
add_p("Tabel 7.1: Matriks Konfusi Pengujian Klinis 100 Sampel", bold=True)
add_custom_table(t71_hdr, t71_data, col_widths=[1.5, 1.8, 1.8, 1.1])

add_p(
    "Langkah Perhitungan Manual Analitik:\n"
    "1. Akurasi = (32 + 54) / 100 = 86 / 100 = 0.8600 (86.00%)\n"
    "2. Error Rate = 1 - 0.8600 = 0.1400 (14.00%)\n"
    "3. Presisi = 32 / (32 + 6) = 32 / 38 approx 0.8421 (84.21%)\n"
    "4. Recall (Sensitivitas) = 32 / (32 + 8) = 32 / 40 = 0.8000 (80.00%)\n"
    "5. Spesifisitas = 54 / (54 + 6) = 54 / 60 = 0.9000 (90.00%)\n"
    "6. False Positive Rate (FPR) = 1 - 0.9000 = 0.1000 (10.00%)\n"
    "7. F1-Score = 2 * (0.8421 * 0.8000) / (0.8421 + 0.8000) = 1.347368 / 1.642105 approx 0.8205 (82.05%)\n"
    "8. MCC = ((32 * 54) - (6 * 8)) / sqrt((38)(40)(60)(62)) = 1680 / sqrt(5.654.400) = 1680 / 2377.898 approx +0.7065"
)

add_heading_custom("7.2 Pemindaian Ambang Batas Probabilitas, Plot Kurva ROC, dan Integrasi Trapesium AUC", level=2)
add_p(
    "Diberikan 10 sampel uji terurut berdasarkan probabilitas prediksi model (5 Positif, 5 Negatif). "
    "Ambang batas theta dipindai untuk mencatat koordinat (FPR, TPR):"
)

roc_hdr = ["ID", "Label (y)", "Prob (p_hat)", "Ambang (theta)", "TP Kum", "FP Kum", "TPR (TP/5)", "FPR (FP/5)", "Titik Koordinat ROC"]
roc_data = [
    ["-", "-", "-", "> 0.95", "0", "0", "0.00", "0.00", "(0.0, 0.0)"],
    ["S1", "1", "0.95", ">= 0.95", "1", "0", "0.20", "0.00", "(0.0, 0.2)"],
    ["S2", "1", "0.88", ">= 0.88", "2", "0", "0.40", "0.00", "(0.0, 0.4)"],
    ["S3", "0", "0.75", ">= 0.75", "2", "1", "0.40", "0.20", "(0.2, 0.4)"],
    ["S4", "1", "0.70", ">= 0.70", "3", "1", "0.60", "0.20", "(0.2, 0.6)"],
    ["S5", "1", "0.65", ">= 0.65", "4", "1", "0.80", "0.20", "(0.2, 0.8)"],
    ["S6", "0", "0.55", ">= 0.55", "4", "2", "0.80", "0.40", "(0.4, 0.8)"],
    ["S7", "0", "0.40", ">= 0.40", "4", "3", "0.80", "0.60", "(0.6, 0.8)"],
    ["S8", "1", "0.35", ">= 0.35", "5", "3", "1.00", "0.60", "(0.6, 1.0)"],
    ["S9", "0", "0.20", ">= 0.20", "5", "4", "1.00", "0.80", "(0.8, 1.0)"],
    ["S10", "0", "0.10", ">= 0.10", "5", "5", "1.00", "1.00", "(1.0, 1.0)"]
]
add_p("Tabel 7.2: Lembar Kerja Pemindaian Ambang Batas Probabilitas Pembentukan Kurva ROC", bold=True)
add_custom_table(roc_hdr, roc_data, col_widths=[0.4, 0.6, 0.7, 0.8, 0.6, 0.6, 0.7, 0.7, 1.0])

add_p(
    "Integrasi Luas Area di Bawah Kurva (AUC) Menggunakan Aturan Trapesium:\n"
    "• Luas 1 (FPR 0.0 -> 0.2): Lebar 0.2 * Tinggi 0.4 = 0.0800\n"
    "• Luas 2 (FPR 0.2 -> 0.6): Lebar 0.4 * Tinggi 0.8 = 0.3200\n"
    "• Luas 3 (FPR 0.6 -> 1.0): Lebar 0.4 * Tinggi 1.0 = 0.4000\n"
    "Total Luas AUC = 0.0800 + 0.3200 + 0.4000 = 0.8000 (80.00%).\n"
    "Kesimpulan: Model memiliki kemampuan diskriminasi yang Baik (Good Discrimination)."
)

add_heading_custom("7.3 Simulasi Partisi Stratified 5-Fold Cross-Validation", level=2)
add_p(
    "Dataset 100 sampel dipartisi menjadi K = 5 lipatan (masing-masing 20 sampel: 8 Positif, 12 Negatif). "
    "Hasil evaluasi pada ke-5 lipatan validasi dicatat sebagai berikut:"
)

cv_hdr = ["Iterasi (k)", "Alokasi Data Latih", "Alokasi Data Validasi", "Akurasi Validasi", "F1-Score Validasi"]
cv_data = [
    ["Fold 1", "Lipatan 2, 3, 4, 5 (80 sampel)", "Lipatan 1 (20 sampel)", "0.8500 (17/20)", "0.8000"],
    ["Fold 2", "Lipatan 1, 3, 4, 5 (80 sampel)", "Lipatan 2 (20 sampel)", "0.9000 (18/20)", "0.8750"],
    ["Fold 3", "Lipatan 1, 2, 4, 5 (80 sampel)", "Lipatan 3 (20 sampel)", "0.8000 (16/20)", "0.7500"],
    ["Fold 4", "Lipatan 1, 2, 3, 5 (80 sampel)", "Lipatan 4 (20 sampel)", "0.8500 (17/20)", "0.8235"],
    ["Fold 5", "Lipatan 1, 2, 3, 4 (80 sampel)", "Lipatan 5 (20 sampel)", "0.9000 (18/20)", "0.8571"]
]
add_p("Tabel 7.3: Simulasi Evaluasi Stratified 5-Fold Cross-Validation", bold=True)
add_custom_table(cv_hdr, cv_data, col_widths=[0.8, 2.0, 1.6, 1.2, 1.1])

add_p(
    "Statistik Agregat K-Fold:\n"
    "• Rata-rata Akurasi: (0.85 + 0.90 + 0.80 + 0.85 + 0.90) / 5 = 4.30 / 5 = 0.8600 (86.00%)\n"
    "• Standar Deviasi Akurasi: sqrt(0.0070 / 4) approx 0.0418 (+- 4.18%)\n"
    "• Rata-rata F1-Score: 4.1056 / 5 = 0.8211 (82.11%)\n"
    "Laporan akhir kinerja model dituliskan: Akurasi = 86.00% +- 4.18%."
)

add_heading_custom("7.4 Evaluasi Model Prediksi Deret Waktu", level=2)
add_p(
    "Pengujian galat peramalan permintaan obat 6 periode berturut-turut (n = 6):"
)

reg_hdr = ["Periode", "Aktual (y)", "Prediksi (y_hat)", "Galat (e)", "|e|", "e^2", "Persen Galat", "Pembagi sMAPE"]
reg_data = [
    ["P1", "100", "110", "-10", "10", "100", "10.000%", "210"],
    ["P2", "120", "115", "+5", "5", "25", "4.167%", "235"],
    ["P3", "150", "140", "+10", "10", "100", "6.667%", "290"],
    ["P4", "130", "145", "-15", "15", "225", "11.538%", "275"],
    ["P5", "180", "170", "+10", "10", "100", "5.556%", "350"],
    ["P6", "200", "210", "-10", "10", "100", "5.000%", "410"],
    ["Total", "880", "890", "0", "60", "650", "42.928%", "1770"]
]
add_p("Tabel 7.4: Lembar Kerja Evaluasi Galat Prediksi Numerik", bold=True)
add_custom_table(reg_hdr, reg_data, col_widths=[0.7, 0.7, 0.9, 0.7, 0.5, 0.6, 1.0, 1.0])

add_p(
    "Hasil Perhitungan Metrik Regresi Manual:\n"
    "• Rata-rata Aktual y_bar = 880 / 6 approx 146.6667\n"
    "• MAE = 60 / 6 = 10.0000\n"
    "• MSE = 650 / 6 = 108.3333\n"
    "• RMSE = sqrt(108.3333) approx 10.4083\n"
    "• MAPE = 42.928% / 6 approx 7.1546%\n"
    "• sMAPE = (100% / 6) * [ 20/210 + 10/235 + 20/290 + 30/275 + 20/350 + 20/410 ] approx 7.0295%\n"
    "• SS_tot = Sigma (y - y_bar)^2 = 7133.3333,   SS_res = Sigma e^2 = 650.0000\n"
    "• R^2 = 1 - (650.0000 / 7133.3333) = 1 - 0.09112 = 0.9089 (90.89%)"
)

# ==============================================================================
# BAB VIII: IMPLEMENTASI PRAKTIS PYTHON DAN VERIFIKASI MESIN
# ==============================================================================
print("Menyusun Bab VIII...")
add_heading_custom("BAB VIII: IMPLEMENTASI PRAKTIS PYTHON DAN VERIFIKASI MESIN", level=1)

add_p(
    "Berikut kode sumber implementasi Python 3.12 berbasis Scikit-Learn yang memvalidasi seluruh perhitungan manual Bab VII secara otomatis:"
)

code_snippet = """import numpy as np
import pandas as pd
from sklearn.metrics import (
    confusion_matrix, accuracy_score, precision_score, 
    recall_score, f1_score, matthews_corrcoef,
    roc_curve, auc, mean_absolute_error, mean_squared_error, r2_score
)

# 1. Verifikasi Matriks Konfusi (100 Sampel Medis)
y_true_cls = np.array([1]*32 + [0]*6 + [1]*8 + [0]*54)
y_pred_cls = np.array([1]*32 + [1]*6 + [0]*8 + [0]*54)

print("Accuracy :", accuracy_score(y_true_cls, y_pred_cls))      # 0.8600
print("Precision:", precision_score(y_true_cls, y_pred_cls))     # 0.8421
print("Recall   :", recall_score(y_true_cls, y_pred_cls))        # 0.8000
print("F1-Score :", f1_score(y_true_cls, y_pred_cls))            # 0.8205
print("MCC      :", matthews_corrcoef(y_true_cls, y_pred_cls))   # 0.7065

# 2. Verifikasi Kurva ROC dan AUC (10 Sampel)
y_true_roc = np.array([1, 1, 0, 1, 1, 0, 0, 1, 0, 0])
y_score_roc = np.array([0.95, 0.88, 0.75, 0.70, 0.65, 0.55, 0.40, 0.35, 0.20, 0.10])
fpr, tpr, thresholds = roc_curve(y_true_roc, y_score_roc)
print("AUC Score:", auc(fpr, tpr))                               # 0.8000

# 3. Verifikasi Metrik Regresi (6 Periode Deret Waktu)
y_true_reg = np.array([100.0, 120.0, 150.0, 130.0, 180.0, 200.0])
y_pred_reg = np.array([110.0, 115.0, 140.0, 145.0, 170.0, 210.0])
print("MAE  :", mean_absolute_error(y_true_reg, y_pred_reg))     # 10.0000
print("MSE  :", mean_squared_error(y_true_reg, y_pred_reg))      # 108.3333
print("RMSE :", np.sqrt(mean_squared_error(y_true_reg, y_pred_reg))) # 10.4083
print("MAPE :", np.mean(np.abs((y_true_reg - y_pred_reg)/y_true_reg))*100) # 7.1546%
print("R^2  :", r2_score(y_true_reg, y_pred_reg))                # 0.9089"""

add_code_block(code_snippet)

add_p(
    "Hasil eksekusi program mesin menunjukkan keselarasan sempurna (100% exact match) terhadap seluruh hasil hitungan analitik manual Bab VII."
)

# ==============================================================================
# BAB IX: PANDUAN STRATEGIS PEMILIHAN METRIK DALAM REKAYASA SISTEM
# ==============================================================================
print("Menyusun Bab IX...")
add_heading_custom("BAB IX: PANDUAN STRATEGIS PEMILIHAN METRIK DALAM REKAYASA SISTEM", level=1)

sel_hdr = ["Domain Masalah", "Karakteristik Data", "Metrik Utama Wajib", "Rasionalisasi Kritis"]
sel_data = [
    ["Diagnosis Medis", "Positif Sangat Kritis & Sedikit", "Recall, F2-Score, PR-AUC", "Menghindari Negatif Palsu (pasien kanker terlewat berisiko kematian fatal)."],
    ["Deteksi Fraud", "Imbalanced Ekstrem (1:1000)", "PR-AUC, F1-Score, MCC", "Akurasi menyesatkan (Accuracy Paradox); fokus murni pada kelas minoritas."],
    ["Filter Spam Email", "Volume Masif, Pengguna Peka", "Precision, F0.5-Score", "Menghindari Positif Palsu (email tagihan penting jangan sampai masuk spam)."],
    ["Sistem Biometrik", "Verifikasi Keamanan Tinggi", "FPR (FAR), FNR (FRR), EER", "Keseimbangan False Acceptance Rate dan False Rejection Rate."],
    ["Prediksi Permintaan Obat", "Deret Waktu Stok Ritel", "MAE, MAPE, RMSE, sMAPE", "MAE memberikan unit fisik obat, RMSE menghukum kesalahan stok ekstrem."],
    ["Evaluasi Kluster", "Unsupervised (Tanpa Label)", "Silhouette, DBI, Calinski-H.", "Menilai kohesi internal dan separasi geometri kluster."]
]
add_p("Tabel 9.1: Matriks Rekomendasi Pemilihan Metrik Evaluasi Sistem", bold=True)
add_custom_table(sel_hdr, sel_data, col_widths=[1.3, 1.4, 1.5, 2.0])

add_p(
    "Peringatan Rekayasa: Hindari jebakan Goodhart's Law (Metric Gaming) di mana analis mengorbankan kualitas holistik sistem hanya demi mengejar "
    "satu angka metrik tunggal. Selalu gunakan kombinasi metrik berimbang dan isolasi data uji secara steril."
)

# ==============================================================================
# BAB X: KESIMPULAN
# ==============================================================================
print("Menyusun Bab X...")
add_heading_custom("BAB X: KESIMPULAN", level=1)
add_p(
    "Berdasarkan pembahasan komprehensif, formulasi matematika, simulasi hitungan bertahap, dan verifikasi eksperimental yang telah disusun, diperoleh simpulan akhir:\n"
    "1. Tahap evaluasi model (Model Evaluation) merupakan pilar penjamin validitas dalam siklus penambangan data yang menjembatani eksperimen algoritmik menuju aplikasi riil yang bernilai guna dan aman.\n"
    "2. Estimasi galat harus membedakan secara tegas antara Training Error, Validation Error, dan Test Error untuk mengidentifikasi patologi Underfitting vs Overfitting serta menyeimbangkan dekomposisi Bias-Variance Tradeoff.\n"
    "3. Metode partisi Stratified K-Fold Cross-Validation merupakan standar emas untuk dataset berukuran sedang, sementara metode Bootstrap .632 menawarkan dasar probabilitas matematis yang kuat untuk dataset berukuran terbatas.\n"
    "4. Akurasi adalah metrik yang tidak memadai pada data tidak seimbang (class-imbalanced data). Metrik Presisi, Recall, F1-Score, dan MCC menyajikan diagnostik menyeluruh terhadap alokasi risiko kesalahan Galat Tipe I versus Galat Tipe II.\n"
    "5. Kurva ROC dan metrik AUC-ROC menyediakan evaluasi agregat yang bebas ambang batas (threshold-independent), yang mencerminkan kemampuan diskriminasi intrinsik model.\n"
    "6. Pada tugas regresi dan prediksi deret waktu, metrik MAE, RMSE, MAPE, dan R^2 mengukur akurasi peramalan baik dalam dimensi absolut, kuadratik, maupun persentase proporsional variabilitas.\n"
    "7. Validasi silang dan perhitungan manual 100 sampel klasifikasi, kurva ROC 10 titik, serta 6 periode deret waktu terbukti selaras 100% presisi dengan pustaka industri Python scikit-learn."
)

# ==============================================================================
# DAFTAR PUSTAKA
# ==============================================================================
print("Menyusun Daftar Pustaka...")
add_heading_custom("DAFTAR PUSTAKA", level=1)

dapus_entries = [
    ("Chicco, D., & Jurman, G. (2020). ", "The advantages of the Matthews correlation coefficient (MCC) over F1 score and accuracy in binary classification evaluation. ", "BMC Genomics, 21(1), 1–13. https://doi.org/10.1186/s12864-019-6413-7"),
    ("Efron, B. (1979). ", "Bootstrap methods: Another look at the jackknife. ", "The Annals of Statistics, 7(1), 1–26."),
    ("Fawcett, T. (2006). ", "An introduction to ROC analysis. ", "Pattern Recognition Letters, 27(8), 861–874. https://doi.org/10.1016/j.patrec.2005.10.010"),
    ("Han, J., Kamber, M., & Pei, J. (2011). ", "Data Mining: Concepts and Techniques (3rd ed.). ", "Morgan Kaufmann."),
    ("Hastie, T., Tibshirani, R., & Friedman, J. (2009). ", "The Elements of Statistical Learning: Data Mining, Inference, and Prediction (2nd ed.). ", "Springer."),
    ("Hyndman, R. J., & Koehler, A. B. (2006). ", "Another look at measures of forecast accuracy. ", "International Journal of Forecasting, 22(4), 679–688. https://doi.org/10.1016/j.ijforecast.2006.03.001"),
    ("Kohavi, R. (1995). ", "A study of cross-validation and bootstrap for accuracy estimation and model selection. ", "Proceedings of the 14th International Joint Conference on Artificial Intelligence (IJCAI), 2, 1137–1145."),
    ("Matthews, B. W. (1975). ", "Comparison of the predicted and observed secondary structure of T4 phage lysozyme. ", "Biochimica et Biophysica Acta (BBA) - Protein Structure, 405(2), 442–451. https://doi.org/10.1016/0005-2795(75)90109-9"),
    ("Powers, D. M. (2011). ", "Evaluation: From precision, recall and F-measure to ROC, informedness, markedness and correlation. ", "Journal of Machine Learning Technologies, 2(1), 37–63."),
    ("Rousseeuw, P. J. (1987). ", "Silhouettes: A graphical aid to the interpretation and validation of cluster analysis. ", "Journal of Computational and Applied Mathematics, 20, 53–65. https://doi.org/10.1016/0377-0427(87)90125-7"),
    ("Tan, P.-N., Steinbach, M., Karpatne, A., & Kumar, V. (2018). ", "Introduction to Data Mining (2nd ed.). ", "Pearson Addison-Wesley.")
]

for author_year, title, pub in dapus_entries:
    p_dp = doc.add_paragraph()
    p_dp.paragraph_format.left_indent = Inches(0.5)
    p_dp.paragraph_format.first_line_indent = Inches(-0.5)
    p_dp.paragraph_format.space_after = Pt(6)
    p_dp.paragraph_format.line_spacing = 1.15
    p_dp.alignment = WD_ALIGN_PARAGRAPH.LEFT
    
    r1 = p_dp.add_run(author_year)
    r1.font.name = 'Times New Roman'
    r1.font.size = Pt(11)
    
    r2 = p_dp.add_run(title)
    r2.font.name = 'Times New Roman'
    r2.font.size = Pt(11)
    r2.italic = True
    
    r3 = p_dp.add_run(pub)
    r3.font.name = 'Times New Roman'
    r3.font.size = Pt(11)

print(f"Menyimpan dokumen Word ke: {OUTPUT_DOCX}...")
doc.save(OUTPUT_DOCX)
print("Selesai! Dokumen PD13 berhasil dibuat.")
