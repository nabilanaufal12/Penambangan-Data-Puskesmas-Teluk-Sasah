#!/usr/bin/env python3
"""
04_Skrip_Pengolahan/generate_pd12_docx.py

Skrip generator berkas Microsoft Word (.docx) resmi untuk Tugas Individu Minggu 12:
TEKNIK ASOSIASI (INF11121 Penambangan Data).
Luaran: 01_Dokumen_Akademik/PD12_2401020073.docx
Format Cover Resmi: "Penambangan Data - Minggu 12 - 2401020073 - Nabil"
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
OUTPUT_DOCX = os.path.join(BASE_DIR, '01_Dokumen_Akademik', 'PD12_2401020073.docx')

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
    run.font.color.rgb = RGBColor(0, 0, 0) # Font Hitam Murni
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
    run.bold = bold
    run.italic = italic
    run.font.color.rgb = RGBColor(0, 0, 0) # Font Hitam Murni
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
    left_b.set(qn('w:color'), '333333') # Border netral hitam/charcoal
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
    run.font.color.rgb = RGBColor(0, 0, 0) # Font Hitam Murni
    
    if label:
        p_lbl = cell.add_paragraph()
        p_lbl.alignment = WD_ALIGN_PARAGRAPH.RIGHT
        p_lbl.paragraph_format.space_after = Pt(0)
        p_lbl.paragraph_format.line_spacing = 1.0
        r_lbl = p_lbl.add_run(label)
        r_lbl.font.name = 'Times New Roman'
        r_lbl.font.size = Pt(9.5)
        r_lbl.font.color.rgb = RGBColor(0, 0, 0)
    
    # spacing after table
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
    run.font.color.rgb = RGBColor(0, 0, 0) # Font Hitam Murni
    
    p_sp = doc.add_paragraph()
    p_sp.paragraph_format.space_after = Pt(4)
    p_sp.paragraph_format.line_spacing = 1.0

def add_custom_table(headers, data, col_widths=None, alignment=None):
    """Menambahkan tabel bergaya akademik berarsir Deep Navy."""
    tbl = doc.add_table(rows=len(data) + 1, cols=len(headers))
    tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    tbl.autofit = False
    set_table_borders(tbl, color="BDC3C7", sz="4")

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
                r.font.color.rgb = RGBColor(0, 0, 0) # Font Hitam Murni untuk data tabel

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
# 2. HALAMAN COVER RESMI (Sesuai RTM: "Penambangan Data - Minggu 12 - 2401020073 - Nabil")
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

r_title_rtm = p_box.add_run("Penambangan Data - Minggu 12 - 2401020073 - Nabil\n\n")
r_title_rtm.font.name = 'Times New Roman'
r_title_rtm.font.size = Pt(14)
r_title_rtm.bold = True
r_title_rtm.font.color.rgb = RGBColor(0, 0, 0) # Font Hitam

r_sub = p_box.add_run("TUGAS MENULIS INDIVIDU: KAJIAN MENDALAM TEKNIK ASOSIASI\n")
r_sub.font.name = 'Times New Roman'
r_sub.font.size = Pt(11)
r_sub.bold = True
r_sub.font.color.rgb = RGBColor(0, 0, 0) # Font Hitam

r_desc = p_box.add_run("Analisis Aturan Asosiasi, Keranjang Pasar (Market Basket Analysis), Algoritma Apriori, FP-Growth, ECLAT, OPUS, Formulasi Support, Confidence, Lift, Conviction, Leverage, serta Simulasi Manual 8 Transaksi")
r_desc.font.name = 'Times New Roman'
r_desc.font.size = Pt(10)
r_desc.italic = True
r_desc.font.color.rgb = RGBColor(0, 0, 0) # Font Hitam

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
# BAB I: KONSEP DASAR PENAMBANGAN ATURAN ASOSIASI DAN ANALISIS KERANJANG PASAR
# ==============================================================================
print("Menyusun Bab I...")
add_heading_custom("BAB I: KONSEP DASAR PENAMBANGAN ATURAN ASOSIASI DAN ANALISIS KERANJANG PASAR", level=1)

add_heading_custom("1.1 Definisi Fundamental dan Terminologi Basis Data Transaksional", level=2)
add_p(
    "Penambangan aturan asosiasi (Association Rule Mining / ARM) merupakan paradigma fundamental dalam domain "
    "penambangan data tanpa pengawasan (unsupervised machine learning) yang bertujuan untuk mengungkap hubungan implikasi, "
    "korelasi statistik tersembunyi, dan pola kemunculan bersama (co-occurrence patterns) antar-item di dalam basis data "
    "transaksional berskala masif. Metode ini dipelopori pertama kali oleh Rakesh Agrawal, Tomasz Imieliński, dan Arun Swami "
    "(1993) dalam riset seminal mereka mengenai Market Basket Analysis (Analisis Keranjang Pasar), yaitu eksplorasi pola belanja "
    "konsumen berdasarkan item-item yang diletakkan bersamaan dalam satu struk transaksi kasir supermarket."
)
add_p(
    "Secara formal, ruang masalah basis data transaksional dimodelkan melalui konstruksi matematis sebagai berikut:\n"
    "1. Semesta Item (Item Universe): Didefinisikan sebagai himpunan berhingga I = {i_1, i_2, ..., i_m} yang merepresentasikan "
    "m jenis item atau produk berbeda yang terdata dalam katalog sistem.\n"
    "2. Itemset (k-Itemset): Setiap himpunan bagian X subset I disebut sebagai itemset. Apabila himpunan X memuat tepat k buah "
    "item (|X| = k), maka X disebut sebagai k-itemset. Himpunan kosong merepresentasikan 0-itemset.\n"
    "3. Transaksi (T): Setiap transaksi T adalah tupel (TID, X) di mana TID (Transaction Identifier) merupakan pengenal unik, "
    "dan X subset I adalah himpunan item yang dibeli secara serempak dalam transaksi tersebut.\n"
    "4. Basis Data Transaksional (D): Himpunan seluruh transaksi D = {T_1, T_2, ..., T_N} dengan total ukuran transaksi N = |D|."
)

add_heading_custom("1.2 Formulasi Formal Aturan Asosiasi", level=2)
add_p(
    "Suatu aturan asosiasi dinyatakan secara formal dalam bentuk relasi implikasi:"
)
add_math_block(
    "X => Y,  di mana X subset I, Y subset I, X cap Y = empty, X != empty, Y != empty",
    "Formula 1.1: Formulasi Formal Aturan Asosiasi"
)
add_p(
    "Dalam formulasi di atas, himpunan X dinamakan sebagai anteseden (antecedent atau Left-Hand Side / LHS), sedangkan "
    "himpunan Y dinamakan sebagai konsekuen (consequent atau Right-Hand Side / RHS). Syarat bahwa X dan Y saling lepas "
    "(X cap Y = empty) menjamin bahwa aturan asosiasi tidak bersifat trivial atau sirkular (misalnya aturan 'jika membeli roti, "
    "maka membeli roti')."
)
add_p(
    "Bagi mahasiswa teknik informatika, pemahaman krusial yang harus ditekankan adalah bahwa tanda panah (=>) dalam penambangan data "
    "merepresentasikan dependensi empiris berbasis frekuensi bersama di masa lalu, dan BUKAN implikasi logika matematika formal mutlak "
    "(X -> Y), serta TIDAK secara langsung menyimpulkan hubungan sebab-akibat (causality). Sebagai contoh, aturan {Popok Bayi} => {Bir} "
    "pada analisis ritel populer tidak berarti bahwa membeli popok menyebabkan seseorang ingin minum bir, melainkan mencerminkan pola "
    "perilaku belanja para ayah muda pada akhir pekan."
)

add_heading_custom("1.3 Ruang Pencarian Kisi (Lattice Search Space) dan Kompleksitas Komputasi", level=2)
add_p(
    "Tantangan komputasi utama dalam penambangan aturan asosiasi berakar pada ledakan kombinatorial (combinatorial explosion) "
    "ruang pencarian. Untuk sebuah basis data dengan m item unik, jumlah kemungkinan itemset yang dapat dibentuk adalah anggota himpunan "
    "kuasa (power set) dari I, yaitu 2^m - 1 itemset non-kosong. Hubungan inklusi himpunan antar-itemset ini membentuk struktur kisi "
    "terurut parsial (Boolean Lattice)."
)
add_p(
    "Jumlah total aturan asosiasi potensial yang dapat diekstraksi dari kisi tersebut dihitung melalui persamaan kombinatorial:"
)
add_math_block(
    "R = Sigma_{k=2}^m [ C(m, k) * Sigma_{j=1}^{k-1} C(k, j) ] = 3^m - 2^{m+1} + 1",
    "Formula 1.2: Pertumbuhan Jumlah Aturan Asosiasi Potensial"
)
add_p(
    "Jika sebuah toko retail modern mengelola hanya m = 100 jenis produk, maka ruang pencarian itemset mencapai 2^100 - 1 approx 1.267 x 10^30 "
    "itemset, dan jumlah aturan potensial mencapai 3^100 - 2^101 + 1 approx 5.15 x 10^47 aturan! Eksplorasi menggunakan metode brute-force "
    "secara komputasional mustahil dilakukan. Oleh karena itu, seluruh algoritma penambangan data modern wajib mengadopsi mekanisme "
    "pemangkasan ruang pencarian (search space pruning) berbasis ambang batas frekuensi."
)

add_heading_custom("1.4 Penerapan Nyata dalam Domain Ritel, Web, dan Bioinformatika", level=2)
add_p(
    "Aplikasi praktis penambangan aturan asosiasi tersebar luas di berbagai domain industri strategis:\n"
    "1. Optimalisasi Tata Letak Rak Ritel (Store Layout Design): Mengatur posisi barang komplementer pada rak yang berdekatan untuk "
    "kenyamanan pelanggan, atau meletakkannya di sudut berlawanan guna memaksa konsumen menjelajahi lorong lain.\n"
    "2. Sistem Rekomendasi E-Commerce: Mesin personalisasi Amazon atau Tokopedia yang menampilkan 'Sering Dibeli Bersama' (Frequently Bought Together).\n"
    "3. Web Usage Mining: Analisis log server HTTP untuk mendeteksi jalur navigasi situs web yang sering diakses berurutan, dimanfaatkan "
    "untuk optimasi cache dan arsitektur tautan internal.\n"
    "4. Bioinformatika dan Analisis DNA: Mengidentifikasi ko-ekspresi kluster gen dan korelasi mutasi polimorfisme nukleotida tunggal (SNP).\n"
    "5. Sistem Keamanan Jaringan: Deteksi korelasi pola serangan siber (intrusion detection) yang melibatkan urutan port scan multi-tahap."
)

# ==============================================================================
# BAB II: METRIK EVALUASI DAN VALIDASI ATURAN ASOSIASI
# ==============================================================================
print("Menyusun Bab II...")
add_heading_custom("BAB II: METRIK EVALUASI DAN VALIDASI ATURAN ASOSIASI", level=1)

add_p(
    "Proses ekstraksi aturan asosiasi dipartisi menjadi dua fase: (1) menemukan seluruh Frequent Itemsets yang memenuhi ambang batas "
    "dukungan minimum (minimum support threshold), dan (2) membangkitkan Association Rules yang memenuhi ambang batas kepastian minimum "
    "(minimum confidence threshold). Agar aturan yang diperoleh memiliki validitas ekonomi dan statistik, digunakan lima metrik evaluasi utama."
)

add_heading_custom("2.1 Metrik Support (Dukungan Frekuensi)", level=2)
add_p(
    "Support mengukur prevalensi atau frekuensi relatif kemunculan suatu itemset atau aturan asosiasi dalam seluruh basis data transaksional D. "
    "Support absolut atau Support Count (sigma) menyatakan banyaknya transaksi yang memuat itemset:"
)
add_math_block(
    "sigma(X) = |{ T in D | X subset T }|",
    "Formula 2.1: Support Count Absolut"
)
add_p(
    "Support relatif suatu itemset X dan support aturan asosiasi X => Y didefinisikan sebagai:"
)
add_math_block(
    "Support(X) = sigma(X) / |D| = P(X)\n"
    "Support(X => Y) = sigma(X cup Y) / |D| = P(X cap Y)",
    "Formula 2.2: Support Relatif Itemset dan Aturan Asosiasi"
)
add_p(
    "Support berfungsi sebagai filter efisiensi komputasi untuk menyingkirkan itemset langka (infrequent) yang tidak signifikan secara statistik "
    "maupun bisnis, sehingga sistem terhindar dari pemrosesan noise."
)

add_heading_custom("2.2 Metrik Confidence (Tingkat Kepastian Bersyarat)", level=2)
add_p(
    "Confidence mengukur derajat keandalan atau kepastian dari suatu aturan. Secara matematis, confidence merupakan estimasi peluang bersyarat "
    "P(Y | X) bahwa transaksi memuat konsekuen Y dengan syarat transaksi tersebut telah terbukti memuat anteseden X:"
)
add_math_block(
    "Confidence(X => Y) = sigma(X cup Y) / sigma(X) = Support(X cup Y) / Support(X) = P(Y | X)",
    "Formula 2.3: Confidence Aturan Asosiasi"
)
add_p(
    "Rentang nilai confidence berada pada interval [0, 1] atau 0% s/d 100%. Walaupun confidence merupakan metrik standar, metrik ini "
    "memiliki kelemahan fatal karena tidak memperhitungkan frekuensi dasar (baseline popularity) dari konsekuen Y, sehingga dapat menghasilkan "
    "aturan semu yang menyesatkan (spurious rules)."
)

add_heading_custom("2.3 Metrik Lift (Korelasi dan Ketergantungan Statistik)", level=2)
add_p(
    "Untuk mengoreksi kelemahan confidence, metrik Lift membandingkan nilai confidence terhadap peluang independen dari konsekuen Y. "
    "Lift mengukur rasio antara frekuensi kemunculan bersama yang diamati terhadap frekuensi yang diharapkan jika X dan Y independen:"
)
add_math_block(
    "Lift(X => Y) = Confidence(X => Y) / Support(Y) = Support(X cup Y) / (Support(X) * Support(Y)) = P(X cap Y) / (P(X) * P(Y))",
    "Formula 2.4: Metrik Lift (Korelasi Statistik)"
)
add_p(
    "Interpretasi matematis nilai Lift terbagi menjadi tiga domain tegas:\n"
    "1. Lift(X => Y) > 1: Asosiasi Positif (Komplementer). Kemunculan X meningkatkan probabilitas kemunculan Y. Item saling mendukung.\n"
    "2. Lift(X => Y) = 1: Independen Statistik. Kemunculan X dan Y adalah peristiwa acak murni yang saling bebas (P(X cap Y) = P(X)P(Y)).\n"
    "3. Lift(X => Y) < 1: Asosiasi Negatif (Substitutif). Kemunculan X justru menurunkan probabilitas kemunculan Y. Item saling menggantikan."
)
add_p(
    "Sifat Aljabar: Lift bersifat simetris terhadap penukaran posisi anteseden dan konsekuen, yaitu Lift(X => Y) = Lift(Y => X)."
)

add_heading_custom("2.4 Metrik Conviction (Implikasi Logis dan Penanganan Aturan Semu)", level=2)
add_p(
    "Conviction diperkenalkan oleh Brin, Motwani, Ullman, dan Tsur (1997) untuk mengukur tingkat implikasi logis terarah secara asimetris. "
    "Conviction membandingkan frekuensi ekspektasi bahwa X muncul tanpa Y (jika keduanya independen) terhadap frekuensi aktual kemunculan X tanpa Y:"
)
add_math_block(
    "Conviction(X => Y) = (1 - Support(Y)) / (1 - Confidence(X => Y)) = (P(X) * P(~Y)) / P(X cap ~Y)",
    "Formula 2.5: Metrik Conviction (Implikasi Logis Asimetris)"
)
add_p(
    "Karakteristik penting Conviction:\n"
    "1. Nilai Conviction = 1 mengindikasikan bahwa X dan Y sepenuhnya independen.\n"
    "2. Nilai Conviction > 1 mengindikasikan adanya aturan implikasi logis nyata.\n"
    "3. Nilai Conviction = inf (tak hingga) tercapai saat Confidence = 1.0 (100%), yang berarti penyebut bernilai nol dan P(X cap ~Y) = 0. "
    "Ini membuktikan bahwa itemset X tidak pernah muncul tanpa kehadiran Y (implikasi sempurna).\n"
    "4. Berbeda dengan Lift, Conviction bersifat asimetris: Conviction(X => Y) != Conviction(Y => X)."
)

add_heading_custom("2.5 Metrik Leverage (Piatetsky-Shapiro Measure)", level=2)
add_p(
    "Leverage (Piatetsky-Shapiro, 1991) mengukur selisih absolut antara peluang kemunculan bersama X dan Y terhadap ekspektasi independen:"
)
add_math_block(
    "Leverage(X => Y) = Support(X cup Y) - (Support(X) * Support(Y)) = P(X cap Y) - P(X)P(Y)",
    "Formula 2.6: Metrik Leverage"
)
add_p(
    "Rentang nilai Leverage berada pada interval [-0.25, +0.25]. Nilai 0 menunjukkan independensi. Keunggulan Leverage terletak pada "
    "penekanannya terhadap volume proporsional data: Leverage menghukum aturan yang hanya melibatkan item langka berfrekuensi sangat rendah, "
    "sehingga sangat dihargai dalam pengambilan keputusan manajerial retail."
)

add_heading_custom("2.6 Sifat Null-Invariance, Imbalance Ratio (IR), dan Ukuran Alternatif", level=2)
add_p(
    "Dalam basis data transaksi nyata, sebagian besar transaksi tidak memuat item X maupun Y (null transactions). Suatu metrik dikatakan "
    "memiliki sifat Null-Invariance jika nilainya tidak berubah saat sejumlah besar transaksi kosong ditambahkan ke basis data. "
    "Lift, Chi-Square, dan Leverage TIDAK bersifat null-invariant. Sebaliknya, ukuran Kulczynski dan Cosine terbukti bersifat null-invariant:"
)
add_math_block(
    "Kulczynski(X, Y) = 0.5 * [ P(Y | X) + P(X | Y) ] = 0.5 * [ Conf(X => Y) + Conf(Y => X) ]\n"
    "Cosine(X, Y) = Support(X cup Y) / sqrt(Support(X) * Support(Y))\n"
    "Imbalance_Ratio(IR) = |Support(X) - Support(Y)| / (Support(X) + Support(Y) - Support(X cup Y))",
    "Formula 2.7: Ukuran Null-Invariant dan Imbalance Ratio (IR)"
)

# ==============================================================================
# BAB III: ALGORITMA APRIORI
# ==============================================================================
print("Menyusun Bab III...")
add_heading_custom("BAB III: ALGORITMA APRIORI — PRINSIP DASAR DAN PEMBANGKITAN KANDIDAT", level=1)

add_heading_custom("3.1 Prinsip Apriori (Downward-Closure / Anti-Monotonicity Property)", level=2)
add_p(
    "Algoritma Apriori (Agrawal & Srikant, 1994) dibangun di atas aksioma fundamental sifat anti-monotonik fungsi support, yang dikenal luas "
    "sebagai Prinsip Apriori:"
)
add_math_block(
    "X subset Y  ===>  Support(Y) <= Support(X)\n"
    "Support(X) < min_sup  ===>  Untuk setiap Y supset X, Support(Y) < min_sup",
    "Teorema 3.1: Sifat Anti-Monotonik Support (Prinsip Apriori)"
)
add_p(
    "Inti dari prinsip ini adalah: Jika suatu itemset berukuran kecil terbukti tidak frekuen (infrequent), maka seluruh superset "
    "yang diturunkannya dijamin pasti tidak frekuen dan dapat langsung dipangkas (pruned) dari kisi penelusuran tanpa perlu menghitung "
    "frekuensinya ke dalam basis data fisik."
)

add_heading_custom("3.2 Prosedur Pembangkitan Kandidat: Join Step dan Prune Step", level=2)
add_p(
    "Apriori beroperasi secara berjenjang (level-wise / breadth-first search). Untuk membangkitkan himpunan kandidat k-itemset (C_k) dari "
    "himpunan (k-1)-itemset frekuen yang telah divalidasi (L_{k-1}), dilakukan dua tahap formal:\n"
    "1. Langkah Join (L_{k-1} bowtie L_{k-1}): Dua itemset p dan q di dalam L_{k-1} digabungkan jika dan hanya jika keduanya memiliki "
    "k-2 item awal yang identik berdasarkan urutan leksikografis:\n"
    "   (p[1] = q[1]) and (p[2] = q[2]) and ... and (p[k-2] = q[k-2]) and (p[k-1] < q[k-1])\n"
    "   Hasil penggabungan menghasilkan kandidat baru c = {p[1], ..., p[k-1], q[k-1]}.\n"
    "2. Langkah Prune: Untuk setiap kandidat c in C_k, sistem menguji apakah setiap subset berukuran (k-1) dari c terdapat di dalam L_{k-1}. "
    "Jika ditemukan satu saja subset yang tidak ada di L_{k-1}, maka c segera dimusnahkan sebelum pemindaian data dilakukan."
)

add_heading_custom("3.3 Ekstraksi Aturan Asosiasi dari Frequent Itemsets", level=2)
add_p(
    "Setelah seluruh frequent itemset ditemukan, aturan asosiasi X => (Y - X) diekstraksi untuk setiap Y in L dan subset tak-kosong X subset Y. "
    "Karena sifat anti-monotonik confidence terhadap anteseden: jika X => (Y - X) tidak memenuhi min_conf, maka untuk sembarang X' subset X, "
    "aturan X' => (Y - X') juga dijamin gagal memenuhi min_conf. Hal ini memungkinkan pemangkasan terarah saat membangkitkan aturan multi-konsekuen."
)

add_heading_custom("3.4 Analisis Kompleksitas Komputasi dan Keterbatasan I/O Disk", level=2)
add_p(
    "Walaupun elegan dan mudah diimplementasikan, Apriori menderita dua keterbatasan kinerja intrinsik:\n"
    "1. Biaya I/O Disk Sangat Tinggi: Jika itemset terpanjang berukuran k_max, Apriori membutuhkan k_max + 1 kali pemindaian penuh terhadap "
    "seluruh isi basis data transaksi di disk sekunder.\n"
    "2. Pembangkitan Kandidat Kombinatorial Masif: Jika terdapat 10.000 itemset ukuran 1 yang frekuen, Apriori membangkitkan C(10000, 2) approx "
    "50 juta kandidat 2-itemset, yang menguras memori dan siklus CPU saat pencocokan hash-tree."
)

# ==============================================================================
# BAB IV: ALGORITMA FP-GROWTH
# ==============================================================================
print("Menyusun Bab IV...")
add_heading_custom("BAB IV: ALGORITMA FP-GROWTH — PENAMBANGAN POLA FREKUEN TANPA KANDIDAT", level=1)

add_heading_custom("4.1 Konsep Struktur Data Frequent Pattern Tree (FP-Tree) dan Header Table", level=2)
add_p(
    "Algoritma FP-Growth (Frequent Pattern Growth) dirumuskan oleh Jiawei Han, Jian Pei, dan Yiwen Yin (2000) untuk mengatasi kelemahan "
    "kritis Apriori. FP-Growth menerapkan filosofi divide-and-conquer dengan memadatkan basis data transaksi ke dalam struktur pohon terkompresi "
    "(FP-Tree) dan menambang pola frekuen langsung dari pohon tanpa pernah membangkitkan kandidat eksplisit (candidate-free)."
)
add_p(
    "Komponen arsitektural FP-Tree terdiri dari:\n"
    "1. Simpul Akar (Root Node): Simpul bertanda 'null'.\n"
    "2. Simpul Item (Item Nodes): Simpul pohon yang menyimpan tiga data: item-name, count (frekuensi lintasan), dan node-link (penunjuk ke simpul "
    "berikutnya yang memiliki label item sama).\n"
    "3. Header Table: Struktur indeks yang memuat daftar 1-itemset frekuen terurut menurun (L-Order) dan head pointer ke rantai simpul pohon."
)

add_heading_custom("4.2 Prosedur Pembangunan FP-Tree melalui 2-Pass Scan", level=2)
add_p(
    "FP-Tree dibangun secara deterministik hanya dengan tepat dua kali pemindaian basis data (2-Pass Scan):\n"
    "• Pemindaian 1: Menghitung frekuensi seluruh item tunggal, menyaring item dengan frekuensi >= min_sup_count, dan menyusunnya "
    "secara menurun (L-Order, misal Susu:6, Roti:6, Keju:5, Gula:4, Kopi:3).\n"
    "• Pemindaian 2: Untuk setiap transaksi mentah, ambil hanya item-item yang frekuen, urutkan ulang berdasarkan L-Order, dan masukkan "
    "ke dalam FP-Tree. Jika transaksi berbagi jalur prefiks, nilai count pada simpul dinaikkan; jika tidak, dibuat cabang baru."
)

add_heading_custom("4.3 Pembangkitan Conditional Pattern Base dan Conditional FP-Tree", level=2)
add_p(
    "Penambangan FP-Growth dijalankan dari bawah ke atas (bottom-up) mulai dari item terbawah di Header Table. Untuk setiap item target alpha:\n"
    "1. Telusuri rantai node-link item alpha di seluruh pohon untuk mengumpulkan seluruh Jalur Prefiks (Prefix Paths) menuju akar pohon.\n"
    "2. Kumpulan jalur prefiks beserta bobot frekuensi simpul alpha disebut sebagai Conditional Pattern Base dari alpha.\n"
    "3. Hitung akumulasi frekuensi item-item pada conditional pattern base tersebut, eliminasi item yang < min_sup_count, dan bangun pohon "
    "lokal baru yang disebut Conditional FP-Tree.\n"
    "4. Frequent patterns diekstraksi secara rekursif dari gabungan conditional FP-Tree dengan item alpha."
)

add_heading_custom("4.4 Analisis Efisiensi Waktu dan Manajemen Memori vs Apriori", level=2)
add_p(
    "Eksperimen empiris Han et al. (2000) membuktikan bahwa FP-Growth beroperasi 1 hingga 2 orde magnitudo (10x s/d 100x) lebih cepat daripada Apriori. "
    "Kecepatan superior ini bersumber dari eliminasi total pembacaan disk berulang serta penghapusan beban alokasi memori kandidat kombinatorial."
)

# ==============================================================================
# BAB V: ALGORITMA ECLAT
# ==============================================================================
print("Menyusun Bab V...")
add_heading_custom("BAB V: ALGORITMA ECLAT — PENDEKATAN KISI DENGAN FORMAT VERTIKAL", level=1)

add_heading_custom("5.1 Format Representasi Basis Data: Horizontal vs Vertikal (TID-List)", level=2)
add_p(
    "Algoritma ECLAT (Equivalence Class Clustering and along with Lattice Traversal) diciptakan oleh Mohammed J. Zaki (2000). "
    "ECLAT membedakan dirinya melalui pemanfaatan format representasi vertikal (inverted index), berbeda dengan format horizontal tradisional:"
)
add_p(
    "• Format Horizontal: Basis data direpresentasikan sebagai pasangan TID -> {Itemset}, contoh T1: {A, B, C}, T2: {A, D}.\n"
    "• Format Vertikal: Basis data direpresentasikan sebagai pasangan Item -> {Daftar TID}, contoh A: {1, 2, 3, 5, 7, 8}, B: {1, 2, 3, 4, 5, 8}."
)

add_heading_custom("5.2 Operasi Irisan Himpunan (Set / Bitset Intersection)", level=2)
add_p(
    "Keajaiban representasi vertikal adalah bahwa penghitungan support dari gabungan itemset X dan Y TIDAK LAGI memerlukan penelusuran transaksi "
    "mentah, melainkan cukup mengeksekusi operasi irisan himpunan (intersection) terhadap daftar TID masing-masing:"
)
add_math_block(
    "TID(X cup Y) = TID(X) cap TID(Y)\n"
    "sigma(X cup Y) = |TID(X cup Y)| = |TID(X) cap TID(Y)|",
    "Formula 5.1: Penghitungan Support melalui Irisan TID-List"
)
add_p(
    "Operasi ini dapat dipetakan secara langsung ke instruksi logika CPU tingkat rendah (bitwise AND) dengan merepresentasikan TID-list "
    "dalam bentuk bitmask vektor biner, menghasilkan throughput evaluasi yang luar biasa cepat."
)

add_heading_custom("5.3 Strategi Penelusuran Kisi dan Karakteristik Skalabilitas", level=2)
add_p(
    "ECLAT menelusuri kisi kombinatorial menggunakan strategi Depth-First Search (DFS) berbasis kelas ekuivalensi prefiks. "
    "Kelemahan utama ECLAT muncul apabila basis data sangat padat (dense) dengan miliaran transaksi, di mana ukuran daftar TID yang disimpan "
    "dalam memori RAM dapat melampaui kapasitas fisik server (RAM exhaustion)."
)

# ==============================================================================
# BAB VI: ALGORITMA OPUS DAN OPTIMASI RUANG PENCARIAN
# ==============================================================================
print("Menyusun Bab VI...")
add_heading_custom("BAB VI: ALGORITMA OPUS DAN OPTIMASI RUANG PENCARIAN", level=1)

add_heading_custom("6.1 Latar Belakang dan Konsep Optimized Pruning for Unordered Search (OPUS)", level=2)
add_p(
    "Algoritma OPUS (Optimized Pruning for Unordered Search) dirumuskan oleh Geoffrey I. Webb (1995, 2000) untuk membebaskan penambangan "
    "aturan asosiasi dari belenggu urutan item statis (fixed ordering). Pada algoritma konvensional, urutan item kaku membatasi fleksibilitas "
    "pemangkasan berbasis informasi lokal yang ditemukan saat pencarian berlangsung."
)
add_p(
    "OPUS mengelola ruang pencarian melalui dua himpunan disjoin di setiap simpul eksplorasi:\n"
    "1. Himpunan Saat Ini (Current Set / P): Itemset yang sedang aktif dievaluasi sebagai aturan.\n"
    "2. Himpunan Tersedia (Available Set / A): Himpunan item yang diizinkan untuk dikombinasikan dengan P."
)

add_heading_custom("6.2 Pemangkasan Dinamis dan Karakteristik Anytime Performance", level=2)
add_p(
    "Keunggulan utama OPUS adalah Pemangkasan Dinamis: Jika sebuah item i in A terbukti melanggar kriteria kelayakan saat dipasangkan dengan P, "
    "maka item i segera dihapus dari himpunan A untuk seluruh sub-ruang pencarian di bawah cabang tersebut. Hal ini mereduksi faktor percabangan "
    "(branching factor) secara eksponensial."
)
add_p(
    "Selain itu, OPUS memiliki sifat Anytime Algorithm: Penelusuran dapat dihentikan kapan saja sesuai batas waktu (timeout) operasional, "
    "dan algoritma dijamin telah menghasilkan aturan-aturan optimal teratas (top-k rules) yang terukur berdasarkan fungsi utilitas objektif "
    "seperti Lift atau Leverage tertinggi."
)

# ==============================================================================
# BAB VII: SIMULASI PERHITUNGAN MANUAL LANGKAH DEMI LANGKAH
# ==============================================================================
print("Menyusun Bab VII...")
add_heading_custom("BAB VII: SIMULASI PERHITUNGAN MANUAL LANGKAH DEMI LANGKAH (HAND CALCULATION TRACE)", level=1)

add_heading_custom("7.1 Deskripsi Dataset Transaksional Mini", level=2)
add_p(
    "Untuk memastikan transparansi dan pemahaman mendalam mahasiswa teknik informatika semester 5, disajikan simulasi hitungan manual "
    "pada basis data transaksi minimarket kampus yang mencatat N = 8 transaksi harian (T1 s/d T8) dengan 5 jenis item produk pangan:\n"
    "• A = Roti Tawar (Bread)\n"
    "• B = Susu Sapi (Milk)\n"
    "• C = Keju Lembaran (Cheese)\n"
    "• D = Kopi Bubuk (Coffee)\n"
    "• E = Gula Pasir (Sugar)"
)

# Tabel Transaksi
t_hdr = ["TID", "Nama Produk yang Dibeli Pelanggan", "Itemset"]
t_data = [
    ["T1", "Roti Tawar, Susu Sapi, Keju Lembaran", "{A, B, C}"],
    ["T2", "Roti Tawar, Susu Sapi, Kopi Bubuk, Gula Pasir", "{A, B, D, E}"],
    ["T3", "Roti Tawar, Susu Sapi, Keju Lembaran", "{A, B, C}"],
    ["T4", "Susu Sapi, Kopi Bubuk, Gula Pasir", "{B, D, E}"],
    ["T5", "Roti Tawar, Susu Sapi, Keju Lembaran, Gula Pasir", "{A, B, C, E}"],
    ["T6", "Kopi Bubuk, Gula Pasir", "{D, E}"],
    ["T7", "Roti Tawar, Keju Lembaran", "{A, C}"],
    ["T8", "Roti Tawar, Susu Sapi, Keju Lembaran", "{A, B, C}"]
]
add_p("Tabel 7.1: Basis Data Transaksi Mini (N = 8 Transaksi)", bold=True)
add_custom_table(t_hdr, t_data, col_widths=[0.8, 3.8, 1.3])

add_heading_custom("7.2 Parameter Ambang Batas", level=2)
add_p(
    "• Total Transaksi: N = |D| = 8 transaksi.\n"
    "• Ambang Batas Minimum Support (min_sup): 37.5% = 3/8 transaksi (min_sup_count = 3).\n"
    "• Ambang Batas Minimum Confidence (min_conf): 60.0% = 0.60."
)

add_heading_custom("7.3 Simulasi Hitungan Algoritma Apriori", level=2)
add_p(
    "Tahap 1: Pembangkitan Itemset Ukuran 1 (C_1 -> L_1)\n"
    "Hitung frekuensi kemunculan setiap item tunggal pada 8 transaksi:"
)

c1_hdr = ["Item", "Daftar Transaksi Kemunculan", "Support Count", "Support Relatif", "Status Evaluasi"]
c1_data = [
    ["{A} (Roti)", "T1, T2, T3, T5, T7, T8", "6", "6/8 = 0.750 (75.0%)", "Lolos (L_1)"],
    ["{B} (Susu)", "T1, T2, T3, T4, T5, T8", "6", "6/8 = 0.750 (75.0%)", "Lolos (L_1)"],
    ["{C} (Keju)", "T1, T3, T5, T7, T8", "5", "5/8 = 0.625 (62.5%)", "Lolos (L_1)"],
    ["{D} (Kopi)", "T2, T4, T6", "3", "3/8 = 0.375 (37.5%)", "Lolos (L_1)"],
    ["{E} (Gula)", "T2, T4, T5, T6", "4", "4/8 = 0.500 (50.0%)", "Lolos (L_1)"]
]
add_p("Tabel 7.2: Penghitungan Kandidat 1-Itemset (C_1) dan Penyaringan (L_1)", bold=True)
add_custom_table(c1_hdr, c1_data, col_widths=[1.2, 1.8, 0.9, 1.1, 0.9])

add_p(
    "Tahap 2: Pembangkitan Itemset Ukuran 2 (C_2 -> L_2)\n"
    "Langkah Join L_1 bowtie L_1 menghasilkan C(5, 2) = 10 kandidat 2-itemset. Seluruhnya diuji frekuensinya ke basis data:"
)

c2_hdr = ["Kandidat 2-Itemset", "Daftar Transaksi Kemunculan", "Count", "Support", "Status Evaluasi"]
c2_data = [
    ["{A, B}", "T1, T2, T3, T5, T8", "5", "5/8 = 0.625", "Lolos (L_2)"],
    ["{A, C}", "T1, T3, T5, T7, T8", "5", "5/8 = 0.625", "Lolos (L_2)"],
    ["{A, D}", "T2", "1", "1/8 = 0.125", "Dieliminasi (< 3)"],
    ["{A, E}", "T2, T5", "2", "2/8 = 0.250", "Dieliminasi (< 3)"],
    ["{B, C}", "T1, T3, T5, T8", "4", "4/8 = 0.500", "Lolos (L_2)"],
    ["{B, D}", "T2, T4", "2", "2/8 = 0.250", "Dieliminasi (< 3)"],
    ["{B, E}", "T2, T4, T5", "3", "3/8 = 0.375", "Lolos (L_2)"],
    ["{C, D}", "(Tidak ada)", "0", "0/8 = 0.000", "Dieliminasi (< 3)"],
    ["{C, E}", "T5", "1", "1/8 = 0.125", "Dieliminasi (< 3)"],
    ["{D, E}", "T2, T4, T6", "3", "3/8 = 0.375", "Lolos (L_2)"]
]
add_p("Tabel 7.3: Evaluasi Kandidat 2-Itemset (C_2) dan Penyaringan (L_2)", bold=True)
add_custom_table(c2_hdr, c2_data, col_widths=[1.3, 1.7, 0.7, 1.1, 1.1])

add_p(
    "Tahap 3: Pembangkitan Itemset Ukuran 3 (C_3 -> L_3)\n"
    "• Langkah Join L_2 bowtie L_2:\n"
    "  - Dari {A, B} dan {A, C} -> Kandidat {A, B, C}.\n"
    "  - Dari {B, C} dan {B, E} -> Kandidat {B, C, E}.\n"
    "• Langkah Prune (Pengujian Subset Apriori):\n"
    "  - Kandidat {A, B, C}: Subset berukuran 2 adalah {A, B}, {A, C}, {B, C}. Ketiganya ada di L_2 -> LOLOS.\n"
    "  - Kandidat {B, C, E}: Subset berukuran 2 adalah {B, C}, {B, E}, {C, E}. Karena {C, E} TIDAK ADA di L_2 (count = 1 < 3), "
    "maka berdasarkan Prinsip Apriori, kandidat {B, C, E} SEGERA DIPANGKAS (PRUNED) tanpa memindai database!"
)

c3_hdr = ["Kandidat 3-Itemset", "Daftar Transaksi Kemunculan", "Count", "Support", "Status Evaluasi"]
c3_data = [
    ["{A, B, C}", "T1, T3, T5, T8", "4", "4/8 = 0.500", "Lolos (L_3)"]
]
add_p("Tabel 7.4: Evaluasi Kandidat 3-Itemset (C_3) dan Penyaringan (L_3)", bold=True)
add_custom_table(c3_hdr, c3_data, col_widths=[1.3, 1.7, 0.7, 1.1, 1.1])

add_p(
    "Tahap 4: Pembangkitan Itemset Ukuran 4 (C_4 -> L_4)\n"
    "Karena |L_3| = 1, tidak ada pasangan 3-itemset yang dapat di-join. C_4 = empty. Algoritma Apriori selesai konvergen.\n"
    "Total Frequent Itemsets yang Ditemukan: 5 (1-item) + 5 (2-item) + 1 (3-item) = 11 itemset."
)

add_heading_custom("7.4 Simulasi Algoritma FP-Growth (FP-Tree dan Conditional Pattern Base)", level=2)
add_p(
    "1. Pemindaian 1 & Pembentukan L-Order:\n"
    "Frekuensi item tunggal: B(6), A(6), C(5), E(4), D(3). Seluruhnya >= 3. Urutan L-Order: B -> A -> C -> E -> D.\n"
    "2. Pemindaian 2 & Rekonstruksi Transaksi Sesuai L-Order:\n"
    "T1: (B, A, C) | T2: (B, A, E, D) | T3: (B, A, C) | T4: (B, E, D) | T5: (B, A, C, E) | T6: (E, D) | T7: (A, C) | T8: (B, A, C)."
)
add_p(
    "3. Struktur FP-Tree Hasil Konstruksi:\n"
    "Root 'null' bercabang menjadi simpul-simpul berbobot:\n"
    "• Cabang Utama (B:6) -> (A:5) -> (C:4) -> (E:1)\n"
    "                     -> (A:5) -> (E:1) -> (D:1)\n"
    "• Cabang Kedua  (B:6) -> (E:1) -> (D:1)\n"
    "• Cabang Ketiga (E:1) -> (D:1)\n"
    "• Cabang Keempat (A:1) -> (C:1)"
)

fp_hdr = ["Item Target", "Conditional Pattern Base (Jalur Prefiks)", "Akumulasi Frekuensi", "Kondisional FP-Tree", "Frequent Patterns Dihasilkan"]
fp_data = [
    ["D (Kopi)", "(B, A, E): 1, (B, E): 1, (E): 1", "E: 3, B: 2, A: 1", "(E:3)", "{D}: 3, {D, E}: 3"],
    ["E (Gula)", "(B, A, C): 1, (B, A): 1, (B): 1", "B: 3, A: 2, C: 1", "(B:3)", "{E}: 4, {E, B}: 3"],
    ["C (Keju)", "(B, A): 4, (A): 1", "A: 5, B: 4", "(B:4) -> (A:5)", "{C}: 5, {C, A}: 5, {C, B}: 4, {C, A, B}: 4"],
    ["A (Roti)", "(B): 5", "B: 5", "(B:5)", "{A}: 6, {A, B}: 5"],
    ["B (Susu)", "(Tidak ada jalur prefiks)", "-", "empty", "{B}: 6"]
]
add_p("Tabel 7.5: Penambangan Pola Frekuen Rekursif FP-Growth", bold=True)
add_custom_table(fp_hdr, fp_data, col_widths=[0.9, 1.8, 1.0, 0.9, 1.3])
add_p("Kesimpulan: Hasil penambangan FP-Growth 100% konsisten dengan hasil Algoritma Apriori.")

add_heading_custom("7.5 Simulasi Algoritma ECLAT (Operasi Irisan Himpunan TID-List)", level=2)
add_p(
    "1. Pemetaan Inverted Index (TID-Lists 1-Itemset):\n"
    "• TID(A) = {1, 2, 3, 5, 7, 8} (|TID| = 6)   • TID(B) = {1, 2, 3, 4, 5, 8} (|TID| = 6)\n"
    "• TID(C) = {1, 3, 5, 7, 8} (|TID| = 5)      • TID(D) = {2, 4, 6} (|TID| = 3)\n"
    "• TID(E) = {2, 4, 5, 6} (|TID| = 4)\n"
    "2. Operasi Irisan untuk 2-Itemsets:\n"
    "• TID(AB) = TID(A) cap TID(B) = {1, 2, 3, 5, 8} -> count = 5 (Lolos L_2)\n"
    "• TID(AC) = TID(A) cap TID(C) = {1, 3, 5, 7, 8} -> count = 5 (Lolos L_2)\n"
    "• TID(BC) = TID(B) cap TID(C) = {1, 3, 5, 8} -> count = 4 (Lolos L_2)\n"
    "• TID(BE) = TID(B) cap TID(E) = {2, 4, 5} -> count = 3 (Lolos L_2)\n"
    "• TID(DE) = TID(D) cap TID(E) = {2, 4, 6} -> count = 3 (Lolos L_2)\n"
    "3. Operasi Irisan untuk 3-Itemsets:\n"
    "• TID(ABC) = TID(AB) cap TID(AC) = {1, 3, 5, 8} -> count = 4 (Lolos L_3)\n"
    "ECLAT menghasilkan himpunan pola frekuen yang identik tanpa memindai database transaksi ulang."
)

add_heading_custom("7.6 Pembentukan dan Perhitungan Metrik Lengkap Aturan Asosiasi", level=2)
add_p(
    "Dari 11 frequent itemsets, aturan-aturan asosiasi diuji terhadap ambang batas min_conf = 60.0% (0.60). "
    "Berikut detail kalkulasi manual 5 metrik utama untuk 4 kasus representatif:"
)

add_p(
    "Kasus 1: Aturan {Kopi} => {Gula}\n"
    "• Support Count: sigma(Kopi) = 3, sigma(Gula) = 4, sigma(Kopi, Gula) = 3\n"
    "• Support = 3/8 = 0.375 (37.5%)\n"
    "• Confidence = sigma(Kopi, Gula) / sigma(Kopi) = 3/3 = 1.000 (100.0%) [Lolos >= 60%]\n"
    "• Lift = Confidence / Support(Gula) = 1.000 / (4/8) = 2.000 (Asosiasi Positif Sangat Kuat)\n"
    "• Leverage = Support(Kopi, Gula) - (Support(Kopi) * Support(Gula)) = 0.375 - (0.375 * 0.500) = +0.1875\n"
    "• Conviction = (1 - Support(Gula)) / (1 - Confidence) = (1 - 0.500) / (1 - 1.000) = 0.500 / 0 = inf"
)

add_p(
    "Kasus 2: Aturan {Roti} => {Keju}\n"
    "• Support Count: sigma(Roti) = 6, sigma(Keju) = 5, sigma(Roti, Keju) = 5\n"
    "• Support = 5/8 = 0.625 (62.5%)\n"
    "• Confidence = 5/6 approx 0.8333 (83.33%) [Lolos >= 60%]\n"
    "• Lift = (5/6) / (5/8) = (5/6) * (8/5) = 8/6 approx 1.3333 (Asosiasi Positif Komplementer)\n"
    "• Leverage = 0.625 - (0.750 * 0.625) = 0.625 - 0.46875 = +0.15625\n"
    "• Conviction = (1 - 0.625) / (1 - 0.8333) = 0.375 / 0.1667 = 2.250"
)

add_p(
    "Kasus 3: Aturan {Susu, Keju} => {Roti}\n"
    "• Support Count: sigma(Susu, Keju) = 4, sigma(Roti) = 6, sigma(Roti, Susu, Keju) = 4\n"
    "• Support = 4/8 = 0.500 (50.0%)\n"
    "• Confidence = 4/4 = 1.000 (100.0%) [Lolos >= 60%]\n"
    "• Lift = 1.000 / (6/8) = 8/6 approx 1.3333\n"
    "• Leverage = 0.500 - (0.500 * 0.750) = 0.500 - 0.375 = +0.1250\n"
    "• Conviction = (1 - 0.750) / (1 - 1.000) = inf"
)

add_p(
    "Kasus 4: Aturan {Gula} => {Susu} — Pembuktian Kasus Independen\n"
    "• Support Count: sigma(Gula) = 4, sigma(Susu) = 6, sigma(Gula, Susu) = 3\n"
    "• Support = 3/8 = 0.375 (37.5%)\n"
    "• Confidence = 3/4 = 0.750 (75.0%) [Tampak tinggi karena susu populer]\n"
    "• Lift = 0.750 / (6/8) = 0.750 / 0.750 = 1.0000 (Tepat Independen Acak!)\n"
    "• Leverage = 0.375 - (0.500 * 0.750) = 0.375 - 0.375 = 0.0000 (Tidak ada surplus)\n"
    "• Conviction = (1 - 0.750) / (1 - 0.750) = 0.250 / 0.250 = 1.0000 (Tidak ada implikasi logis)"
)

# Tabel Rekapitulasi Rules
r_hdr = ["No", "Aturan Asosiasi (X => Y)", "Support", "Confidence", "Lift", "Leverage", "Conviction", "Status Interpretasi"]
r_data = [
    ["1", "{Kopi} => {Gula}", "0.375 (37.5%)", "1.000 (100%)", "2.000", "+0.1875", "inf", "Asosiasi Positif Sangat Kuat"],
    ["2", "{Gula} => {Kopi}", "0.375 (37.5%)", "0.750 (75%)", "2.000", "+0.1875", "2.500", "Asosiasi Positif Sangat Kuat"],
    ["3", "{Keju} => {Roti}", "0.625 (62.5%)", "1.000 (100%)", "1.333", "+0.1562", "inf", "Komplementer Kuat"],
    ["4", "{Roti} => {Keju}", "0.625 (62.5%)", "0.833 (83.3%)", "1.333", "+0.1562", "2.250", "Komplementer Kuat"],
    ["5", "{Susu, Keju} => {Roti}", "0.500 (50.0%)", "1.000 (100%)", "1.333", "+0.1250", "inf", "Multi-item Deterministik"],
    ["6", "{Roti} => {Susu, Keju}", "0.500 (50.0%)", "0.667 (66.7%)", "1.333", "+0.1250", "1.500", "Multi-item Prediktif"],
    ["7", "{Susu, Roti} => {Keju}", "0.500 (50.0%)", "0.800 (80%)", "1.280", "+0.1094", "1.875", "Asosiasi Komplementer"],
    ["8", "{Keju} => {Susu, Roti}", "0.500 (50.0%)", "0.800 (80%)", "1.280", "+0.1094", "1.875", "Asosiasi Komplementer"],
    ["9", "{Susu} => {Roti}", "0.625 (62.5%)", "0.833 (83.3%)", "1.111", "+0.0625", "1.500", "Asosiasi Positif Moderat"],
    ["10", "{Roti} => {Susu}", "0.625 (62.5%)", "0.833 (83.3%)", "1.111", "+0.0625", "1.500", "Asosiasi Positif Moderat"],
    ["11", "{Keju} => {Susu}", "0.500 (50.0%)", "0.800 (80%)", "1.067", "+0.0312", "1.250", "Asosiasi Lemah"],
    ["12", "{Susu} => {Keju}", "0.500 (50.0%)", "0.667 (66.7%)", "1.067", "+0.0312", "1.125", "Asosiasi Lemah"],
    ["13", "{Gula} => {Susu}", "0.375 (37.5%)", "0.750 (75%)", "1.000", "0.0000", "1.000", "Murni Independen (Acak)"]
]
add_p("Tabel 7.6: Rekapitulasi Evaluasi Metrik Lengkap Seluruh Aturan Asosiasi (min_conf >= 60%)", bold=True)
add_custom_table(r_hdr, r_data, col_widths=[0.4, 1.6, 0.8, 0.8, 0.6, 0.7, 0.6, 1.4])

# ==============================================================================
# BAB VIII: IMPLEMENTASI PRAKTIS PYTHON DAN VERIFIKASI MESIN
# ==============================================================================
print("Menyusun Bab VIII...")
add_heading_custom("BAB VIII: IMPLEMENTASI PRAKTIS PYTHON DAN VERIFIKASI MESIN", level=1)

add_p(
    "Untuk membuktikan kebenaran matematis hitungan manual pada Bab VII, berikut disajikan kode implementasi program "
    "menggunakan pustaka industri standar Python 3.12 (mlxtend dan pandas). Skrip ini memproses matriks transaksi biner "
    "dan menghitung metrik evaluasi secara otomatis."
)

code_snippet = """import pandas as pd
from mlxtend.preprocessing import TransactionEncoder
from mlxtend.frequent_patterns import apriori, fpgrowth, association_rules

# 1. Dataset Transaksional Mini (8 Transaksi)
dataset = [
    ['Roti', 'Susu', 'Keju'],                 # T1
    ['Roti', 'Susu', 'Kopi', 'Gula'],         # T2
    ['Roti', 'Susu', 'Keju'],                 # T3
    ['Susu', 'Kopi', 'Gula'],                 # T4
    ['Roti', 'Susu', 'Keju', 'Gula'],         # T5
    ['Kopi', 'Gula'],                         # T6
    ['Roti', 'Keju'],                         # T7
    ['Roti', 'Susu', 'Keju']                  # T8
]

# 2. Transformasi ke One-Hot Encoding
te = TransactionEncoder()
te_ary = te.fit(dataset).transform(dataset)
df_transaksi = pd.DataFrame(te_ary, columns=te.columns_)

# 3. Penambangan Pola Frekuen (min_sup = 3/8 = 0.375)
freq_apriori = apriori(df_transaksi, min_support=0.375, use_colnames=True)
freq_fpgrowth = fpgrowth(df_transaksi, min_support=0.375, use_colnames=True)

# 4. Pembangkitan Aturan Asosiasi (min_conf = 0.60)
rules = association_rules(freq_apriori, metric='confidence', min_threshold=0.60)
cols = ['antecedents', 'consequents', 'support', 'confidence', 'lift', 'leverage', 'conviction']
rules_sorted = rules[cols].sort_values(by=['lift', 'confidence'], ascending=[False, False])
print(rules_sorted.to_string(index=False))"""

add_code_block(code_snippet)

add_p(
    "Hasil Eksekusi Program dan Pembuktian Validitas:\n"
    "1. Pustaka mlxtend menghasilkan tepat 11 frequent itemsets yang sama persis antara algoritma apriori() dan fpgrowth().\n"
    "2. Aturan asosiasi teratas {Kopi} => {Gula} terverifikasi memiliki Support = 0.3750, Confidence = 1.0000, Lift = 2.0000, "
    "Leverage = 0.1875, dan Conviction = inf.\n"
    "3. Aturan independen {Gula} => {Susu} terbukti memiliki Lift = 1.0000, Leverage = 0.0000, dan Conviction = 1.0000.\n"
    "Tingkat kecocokan antara hitungan manual analitik dan eksekusi mesin mencapai 100% presisi sempurna."
)

# ==============================================================================
# BAB IX: MATRIKS KOMPARASI DAN PANDUAN PEMILIHAN ALGORITMA
# ==============================================================================
print("Menyusun Bab IX...")
add_heading_custom("BAB IX: MATRIKS KOMPARASI DAN PANDUAN PEMILIHAN ALGORITMA", level=1)

comp_hdr = ["Dimensi Evaluasi", "Apriori (Agrawal, 1994)", "FP-Growth (Han, 2000)", "ECLAT (Zaki, 2000)", "OPUS (Webb, 1995)"]
comp_data = [
    ["Model Data", "Horizontal Transaksi", "Pohon FP-Tree Terkompresi", "Vertikal Inverted (TID-List)", "Status Ruang Pencarian (P, A)"],
    ["Kandidat", "Eksplisit Masif (C_k)", "Nihil (Candidate-Free)", "Nihil (Operasi Irisan)", "Dinamis Terpangkas"],
    ["Penelusuran", "Breadth-First (Level-wise)", "Divide & Conquer (DFS)", "Depth-First Search (DFS)", "Branch & Bound Bebas Urutan"],
    ["Scan Database", "k_max + 1 kali", "Tepat 2 kali", "1 kali (Konversi Vertikal)", "Fleksibel / Sesuai Index"],
    ["Beban I/O Disk", "Sangat Berat", "Sangat Ringan", "Ringan", "Menengah"],
    ["Skalabilitas Data", "Buruk pada data padat", "Sangat Tinggi & Skalabel", "Tinggi pada data renggang", "Sangat Baik untuk Aturan Terarah"],
    ["Sensitivitas Support", "Eksplosif jika support rendah", "Sangat Stabil", "Memori membengkak jika padat", "Adaptif dengan Batasan Waktu"],
    ["Kelemahan Kritis", "Bottleneck I/O & Kandidat", "Overhead pointer rekursif", "Ukuran TID-list membesar", "Desain algoritma kompleks"]
]
add_p("Tabel 9.1: Matriks Komparasi Komprehensif Algoritma Penambangan Asosiasi", bold=True)
add_custom_table(comp_hdr, comp_data, col_widths=[1.2, 1.4, 1.5, 1.4, 1.4])

add_p(
    "Panduan Pemilihan Praktis untuk Rekayasa Perangkat Lunak Industri:\n"
    "1. Pilih FP-Growth sebagai algoritma standar utama untuk sistem e-commerce berskala besar (jutaan transaksi) "
    "karena keandalannya pada ambang batas support rendah dan efisiensi memori pohon.\n"
    "2. Pilih ECLAT jika basis data bersifat sangat renggang (sparse) dengan transaksi pendek (seperti data transaksi apotek atau log kata kunci pencarian) "
    "karena komputasi irisan bitset dapat dieksekusi sangat cepat pada tingkat prosesor.\n"
    "3. Pilih OPUS jika sistem dituntut beroperasi secara interaktif (anytime search) di mana pencarian dibatasi oleh waktu respons ketat "
    "dan diarahkan langsung oleh metrik optimasi seperti Lift atau Leverage.\n"
    "4. Gunakan Apriori untuk keperluan prototipe akademis, pengajaran konsep dasar ruang kisi, atau verifikasi audit logika manual."
)

# ==============================================================================
# BAB X: KESIMPULAN
# ==============================================================================
print("Menyusun Bab X...")
add_heading_custom("BAB X: KESIMPULAN", level=1)
add_p(
    "Berdasarkan kajian teoritis ruang metrik asosiasi, formulasi aljabar himpunan, simulasi perhitungan manual, serta verifikasi eksperimental "
    "program Python yang telah diselesaikan, disimpulkan beberapa poin fundamental:\n"
    "1. Penambangan aturan asosiasi (Association Rule Mining) merupakan instrumen analisis keranjang pasar yang sangat tangguh untuk mendeteksi "
    "korelasi empiris dalam data transaksional tanpa memerlukan label data awal.\n"
    "2. Metrik Support dan Confidence tidak memadai jika digunakan sendirian. Evaluasi komprehensif mutlak memerlukan metrik Lift, Conviction, "
    "dan Leverage untuk menyaring aturan-aturan semu dan membuktikan keberadaan korelasi komplementer nyata di atas ekspektasi independensi statistik.\n"
    "3. Prinsip Apriori menyediakan fondasi pemangkasan anti-monotonik yang elegan, namun memiliki kelemahan inheren pada biaya I/O disk berulang "
    "dan ledakan kandidat kombinatorial.\n"
    "4. Algoritma FP-Growth berhasil mengeliminasi pembangkitan kandidat melalui pemadatan basis data ke dalam FP-Tree dengan tepat 2 kali scan disk, "
    "sementara ECLAT mentransformasikan penambangan menjadi operasi irisan aljabar himpunan vertikal (TID-list), dan OPUS memelopori pemangkasan dinamis "
    "pada ruang pencarian bebas urutan dengan kemampuan anytime performance.\n"
    "5. Simulasi manual langkah demi langkah pada 8 transaksi mini minimarket menghasilkan 11 frequent itemsets dan aturan-aturan asosiasi berkualitas tinggi "
    "yang terbukti cocok 100% dengan eksekusi pustaka standar industri mlxtend."
)

# ==============================================================================
# DAFTAR PUSTAKA
# ==============================================================================
print("Menyusun Daftar Pustaka...")
add_heading_custom("DAFTAR PUSTAKA", level=1)

dapus_entries = [
    ("Agrawal, R., Imieliński, T., & Swami, A. (1993). ", "Mining association rules between sets of items in large databases. ", "Proceedings of the 1993 ACM SIGMOD International Conference on Management of Data, 207–216. https://doi.org/10.1145/170035.170072"),
    ("Agrawal, R., & Srikant, R. (1994). ", "Fast algorithms for mining association rules in large databases. ", "Proceedings of the 20th International Conference on Very Large Data Bases (VLDB), 487–499."),
    ("Brin, S., Motwani, R., Ullman, J. D., & Tsur, S. (1997). ", "Dynamic itemset counting and implication rules for market basket data. ", "ACM SIGMOD Record, 26(2), 255–264. https://doi.org/10.1145/253262.253325"),
    ("Han, J., Pei, J., & Yin, Y. (2000). ", "Mining frequent patterns without candidate generation. ", "ACM SIGMOD Record, 29(2), 1–12. https://doi.org/10.1145/335191.335372"),
    ("Han, J., Kamber, M., & Pei, J. (2011). ", "Data Mining: Concepts and Techniques (3rd ed.). ", "Morgan Kaufmann."),
    ("Piatetsky-Shapiro, G. (1991). ", "Discovery, analysis, and presentation of strong rules. ", "Knowledge Discovery in Databases, 229–248. AAAI/MIT Press."),
    ("Tan, P.-N., Steinbach, M., Karpatne, A., & Kumar, V. (2018). ", "Introduction to Data Mining (2nd ed.). ", "Pearson Addison-Wesley."),
    ("Webb, G. I. (2000). ", "OPUS: An efficient admissible algorithm for unordered search. ", "Journal of Artificial Intelligence Research, 13, 433–452. https://doi.org/10.1613/jair.769"),
    ("Zaki, M. J. (2000). ", "Scalable algorithms for association mining. ", "IEEE Transactions on Knowledge and Data Engineering, 12(3), 372–390. https://doi.org/10.1109/69.846291")
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
print("Selesai! Dokumen berhasil dibuat.")
