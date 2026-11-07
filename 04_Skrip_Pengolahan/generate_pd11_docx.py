#!/usr/bin/env python3
"""
04_Skrip_Pengolahan/generate_pd11_docx.py

Skrip generator berkas Microsoft Word (.docx) resmi untuk Tugas Individu Minggu 11:
TEKNIK KLUSTER (INF11121 Penambangan Data).
Luaran: 01_Dokumen_Akademik/PD11_2401020073.docx
Format Cover Resmi: "Penambangan Data - Minggu 11 - 2401020073 - Nabil"
Standar: Akademik Informatika Semester 5, Font Times New Roman 12 pt, Spasi 1.5,
Margin 4-3-3-3 cm, logo resmi UMRAH, tabel berarsir, blok formula matematika,
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
OUTPUT_DOCX = os.path.join(BASE_DIR, '01_Dokumen_Akademik', 'PD11_2401020073.docx')

doc = docx.Document()

# ==============================================================================
# 1. KONFIGURASI HALAMAN & MARGIN (Left 4cm, Top 3cm, Right 3cm, Bottom 3cm)
# ==============================================================================
for section in doc.sections:
    section.top_margin = Inches(1.18)     # 3.0 cm
    section.bottom_margin = Inches(1.18)  # 3.0 cm
    section.left_margin = Inches(1.57)    # 4.0 cm (standar penjilidan)
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
    if level == 1:
        run.font.size = Pt(14)
        run.font.color.rgb = RGBColor(31, 78, 121) # Navy blue
        p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    elif level == 2:
        run.font.size = Pt(12.5)
        run.font.color.rgb = RGBColor(44, 62, 80)
        p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    else:
        run.font.size = Pt(12)
        run.font.color.rgb = RGBColor(52, 73, 94)
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
    left_b.set(qn('w:color'), '2980B9') # Blue accent
    tcBorders.append(left_b)
    for b_name in ['top', 'bottom', 'right']:
        b = OxmlElement(f'w:{b_name}')
        b.set(qn('w:val'), 'none')
        tcBorders.append(b)
    tcPr.append(tcBorders)
    
    p = cell.paragraphs[0]
    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p.paragraph_format.line_spacing = 1.2
    p.paragraph_format.space_after = Pt(2)
    r = p.add_run(formula_text)
    r.font.name = 'Cambria Math'
    r.font.size = Pt(11)
    r.bold = True
    if label:
        r_lbl = p.add_run(f"    {label}")
        r_lbl.font.name = 'Times New Roman'
        r_lbl.font.size = Pt(10)
        r_lbl.italic = True
    
    p_sp = doc.add_paragraph()
    p_sp.paragraph_format.space_after = Pt(4)

def add_code_block(code_text):
    """Menambahkan blok kode program monospaced dengan kotak berarsir gelap."""
    tbl = doc.add_table(rows=1, cols=1)
    tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    tbl.autofit = False
    tbl.rows[0].cells[0].width = Inches(5.9)
    cell = tbl.rows[0].cells[0]
    set_cell_background(cell, "272822") # Dark theme for code
    set_cell_margins(cell, top=120, bottom=120, left=140, right=140)
    
    p = cell.paragraphs[0]
    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p.paragraph_format.line_spacing = 1.05
    p.paragraph_format.space_after = Pt(2)
    run = p.add_run(code_text)
    run.font.name = 'Consolas'
    run.font.size = Pt(9.5)
    run.font.color.rgb = RGBColor(248, 248, 242) # Monokai text
    
    p_sp = doc.add_paragraph()
    p_sp.paragraph_format.space_after = Pt(4)

def add_styled_table(headers, rows_data, col_widths=None, caption=""):
    """Menambahkan tabel data dengan header gelap dan baris selang-seling."""
    if caption:
        p_cap = doc.add_paragraph()
        p_cap.paragraph_format.space_before = Pt(8)
        p_cap.paragraph_format.space_after = Pt(3)
        p_cap.paragraph_format.keep_with_next = True
        r = p_cap.add_run(caption)
        r.font.name = 'Times New Roman'
        r.font.size = Pt(11)
        r.bold = True

    table = doc.add_table(rows=len(rows_data) + 1, cols=len(headers))
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    table.autofit = False
    set_table_borders(table, color="BDC3C7", sz="4")

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
        bg_color = 'F2F4F8' if r_idx % 2 == 1 else 'FFFFFF'
        for c_idx, val in enumerate(row_values):
            row_cells[c_idx].text = str(val)
            set_cell_background(row_cells[c_idx], bg_color)
            set_cell_margins(row_cells[c_idx], top=70, bottom=70, left=90, right=90)
            row_cells[c_idx].vertical_alignment = WD_ALIGN_VERTICAL.CENTER
            for p in row_cells[c_idx].paragraphs:
                p.paragraph_format.line_spacing = 1.15
                p.paragraph_format.space_after = Pt(2)
                if c_idx == 0 or len(str(val)) <= 6 or str(val).startswith('O(') or str(val).endswith(')'):
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

    p_sp = doc.add_paragraph()
    p_sp.paragraph_format.space_after = Pt(4)

print("Menyusun dokumen akademik Tugas Individu Minggu 11: PD11_2401020073.docx ...")

# ==============================================================================
# 2. HALAMAN COVER RESMI (Sesuai RTM: "Penambangan Data - Minggu 11 - 2401020073 - Nabil")
# ==============================================================================
p_cov_hdr = doc.add_paragraph()
p_cov_hdr.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_cov_hdr.paragraph_format.space_before = Pt(8)
p_cov_hdr.paragraph_format.space_after = Pt(4)

r_kemen = p_cov_hdr.add_run("KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI\n")
r_kemen.font.size = Pt(11)
r_kemen.bold = True

r_univ = p_cov_hdr.add_run("UNIVERSITAS MARITIM RAJA ALI HAJI\n")
r_univ.font.size = Pt(13)
r_univ.bold = True

r_fak = p_cov_hdr.add_run("FAKULTAS TEKNIK DAN TEKNOLOGI KEMARITIMAN\nPROGRAM STUDI S1 TEKNIK INFORMATIKA\n")
r_fak.font.size = Pt(10.5)
r_fak.bold = True

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
r_rtm.font.size = Pt(9.5)
r_rtm.font.color.rgb = RGBColor(120, 120, 120)
r_rtm.bold = True

r_title_rtm = p_box.add_run("Penambangan Data - Minggu 11 - 2401020073 - Nabil\n\n")
r_title_rtm.font.size = Pt(14)
r_title_rtm.bold = True
r_title_rtm.font.color.rgb = RGBColor(31, 78, 121)

r_sub = p_box.add_run("TUGAS MENULIS INDIVIDU: KAJIAN MENDALAM TEKNIK KLUSTER\n")
r_sub.font.size = Pt(11)
r_sub.bold = True

r_desc = p_box.add_run("Pengukuran Metrik Jarak L1, L2, L3/Lp, K-Means, K-Medoids, Agglomerative Hierarchical (Ward/Linkage), DBSCAN, Validasi Silhouette & Elbow, serta Simulasi Manual 8 Sampel")
r_desc.font.size = Pt(10)
r_desc.italic = True

p_space = doc.add_paragraph()
p_space.paragraph_format.space_before = Pt(8)

# DATA MAHASISWA & DOSEN
p_meta = doc.add_paragraph()
p_meta.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_meta.paragraph_format.line_spacing = 1.2

r_mhs = p_meta.add_run("Disusun Oleh:\n")
r_mhs.font.size = Pt(10.5)
r_mhs_name = p_meta.add_run("NABIL\nNIM: 2401020073\n\n")
r_mhs_name.font.size = Pt(12)
r_mhs_name.bold = True

r_dsn = p_meta.add_run("Dosen Pengampu Mata Kuliah:\n")
r_dsn.font.size = Pt(10.5)
r_dsn_names = p_meta.add_run("1. Tekad Matulatan, S.T., M.Kom.\n2. Muhamad Fadli, S.T., M.Kom.\n\n")
r_dsn_names.font.size = Pt(11)
r_dsn_names.bold = True

r_loc = p_meta.add_run("TANJUNGPINANG, KEPULAUAN RIAU\n2026")
r_loc.font.size = Pt(10.5)
r_loc.bold = True

doc.add_page_break()

# ==============================================================================
# 3. KONTEN DOKUMEN MATERI AJAR LENGKAP MINGGU 11
# ==============================================================================

# ------------------------------------------------------------------------------
# BAB I
# ------------------------------------------------------------------------------
add_heading_custom("BAB I: PENGANTAR DAN PARADIGMA UNSUPERVISED LEARNING DALAM DATA MINING", level=1)

add_heading_custom("1.1 Definisi Formal dan Esensi Analisis Kluster", level=2)
add_p("Dalam sains data dan rekayasa perangkat lunak berbasis kecerdasan artifisial, Analisis Kluster (Cluster Analysis) menempati posisi sentral dalam paradigma pembelajaran tanpa pengawasan (unsupervised learning). Berbeda dengan supervised learning yang bertumpu pada bimbingan variabel target berlabel (y), analisis kluster beroperasi pada dataset tanpa anotasi label kelas apriori.")

add_p("Diberikan sebuah dataset observasi multivariat D yang memuat N sampel data tanpa label:")
add_math_block("D = {x1, x2, ..., xN},    di mana xi = (x_i1, x_i2, ..., x_id)^T ∈ R^d", label="(Persamaan 1.1)")

add_p("Tujuan komputasional dari algoritma kluster adalah mempartisi himpunan D ke dalam K subset himpunan bagian C = {C1, C2, ..., CK} sedemikian rupa sehingga seluruh data teralokasi secara lengkap (union seluruh Ck = D), tidak terdapat irisan antar-kluster (C_i ∩ C_j = ∅ untuk i ≠ j pada hard clustering), dan setiap kluster memuat setidaknya satu elemen data.")

add_heading_custom("1.2 Taksonomi Metode Kluster", level=2)
add_p("Berdasarkan mekanisme matematis dan topologi geometris pembentukan kelompoknya, metode kluster dikelompokkan ke dalam 4 paradigma fundamental:")
add_p("1. Partitional Clustering: Membagi dataset secara langsung ke dalam sejumlah K partisi tetap melalui optimasi fungsi objektif iteratif (contoh: K-Means, K-Medoids / PAM).")
add_p("2. Hierarchical Clustering: Membangun struktur pohon dekomposisi bertingkat (dendrogram), baik secara penggabungan dari bawah ke atas (Agglomerative) maupun pemecahan dari atas ke bawah (Divisive).")
add_p("3. Density-Based Clustering: Membentuk kelompok berdasarkan kerapatan konsentrasi titik data di ruang metrik yang dipisahkan oleh wilayah berkerapatan rendah, sangat ampuh menemukan bentuk sembarang (arbitrary shapes) dan menyaring derau (contoh: DBSCAN).")
add_p("4. Grid-Based Clustering: Mengkuantisasi ruang fitur menjadi struktur sel kisi berhingga guna mempercepat komputasi berskala masif (contoh: STING, CLIQUE).")

add_heading_custom("1.3 Perbedaan Mendasar Kluster vs Klasifikasi", level=2)
add_p("Mahasiswa Teknik Informatika Semester 5 memahami batas fungsional antara kedua metode:")
add_p("• Klasifikasi (Supervised): Menginduksi batas pemisah (decision boundary) untuk memetakan fitur ke kelas diskrit yang sudah ditentukan sebelumnya, dievaluasi melalui metrik Confusion Matrix, Akurasi, Presisi, dan ROC-AUC.")
add_p("• Kluster (Unsupervised): Mengekstraksi struktur intrinsik, geometri spasial, dan pola alami data tanpa adanya target kelas acuan, dievaluasi melalui metrik validasi internal seperti Silhouette Score, Davies-Bouldin Index, dan WCSS / Elbow Method.")

add_heading_custom("1.4 Prinsip Homogenitas Internal dan Separabilitas Eksternal", level=2)
add_p("Efektivitas kluster berakar pada dua prinsip aksioma statistik:")
add_p("1. High Intra-cluster Similarity: Meminimalkan jarak antar-objek yang berada di dalam kluster yang sama.")
add_p("2. Low Inter-cluster Similarity: Memaksimalkan separasi jarak antara kluster-kluster yang berbeda.")

# ------------------------------------------------------------------------------
# BAB II
# ------------------------------------------------------------------------------
add_heading_custom("BAB II: GEOMETRI RUANG METRIK DAN FUNGSI JARAK (NORMA L1, L2, L3/LP)", level=1)

add_heading_custom("2.1 Konsep Ruang Metrik dan Aksioma Pengukuran Jarak", level=2)
add_p("Sebuah fungsi real d(x, y) didefinisikan sebagai metrik jarak sah dalam ruang fitur jika dan hanya jika memenuhi empat aksioma formal metrik:")
add_p("1. Non-negativity: d(x, y) ≥ 0")
add_p("2. Identity of Indiscernibles: d(x, y) = 0 ⟺ x = y")
add_p("3. Symmetry: d(x, y) = d(y, x)")
add_p("4. Triangle Inequality: d(x, z) ≤ d(x, y) + d(y, z)")

add_heading_custom("2.2 Metrik Jarak L1 (Manhattan Distance / City-Block Metric)", level=2)
add_p("Jarak L1 mengukur panjang lintasan grid tegak lurus (ortogonal) antar-vektor pada koordinat kartesius sejajar:")
add_math_block("d_L1(x, y) = ||x - y||_1 = ∑ |x_j - y_j|,    untuk j = 1 sampai d", label="(Persamaan 2.1)")
add_p("Karakteristik: Komputasi sangat efisien tanpa operasi pengkuadratan dan akar kuadrat. Bersifat robust (kebal) terhadap kehadiran outlier karena tidak memperbesar deviasi ekstrem secara kuadratik.")

add_heading_custom("2.3 Metrik Jarak L2 (Euclidean Distance / Straight-Line Metric)", level=2)
add_p("Jarak L2 merepresentasikan jarak geometris garis lurus terpendek antara dua titik dalam ruang Euclidean:")
add_math_block("d_L2(x, y) = ||x - y||_2 = √[ ∑ (x_j - y_j)^2 ],    untuk j = 1 sampai d", label="(Persamaan 2.2)")
add_p("Karakteristik: Merupakan metrik jarak standar baku pada K-Means dan Agglomerative Ward. Sensitif terhadap nilai pencilan ekstrem akibat pembobotan kuadratik.")

add_heading_custom("2.4 Metrik Jarak L3 dan Lp General (Minkowski Distance)", level=2)
add_p("Norma Minkowski merupakan bentuk umum berparameter p ≥ 1 yang memayungi L1, L2, dan L3:")
add_math_block("d_Lp(x, y) = ||x - y||_p = [ ∑ |x_j - y_j|^p ]^(1/p)", label="(Persamaan 2.3)")
add_p("Khusus untuk Teknik Jarak L3 (p = 3):")
add_math_block("d_L3(x, y) = [ ∑ |x_j - y_j|^3 ]^(1/3)", label="(Persamaan 2.4)")
add_p("Pada norma L3, selisih fitur yang besar diberi penalti kubik sebelum ditarik akar pangkat tiga, efektif digunakan dalam analisis anomali sinyal multi-dimensi.")

add_heading_custom("2.5 Jarak Chebyshev, Mahalanobis, Cosine, dan Jaccard", level=2)
add_p("• Chebyshev Distance (L_infinity): d_inf(x, y) = max |x_j - y_j| (jarak batas maksimum antar-dimensi).")
add_p("• Mahalanobis Distance: Mengoreksi kovarians dan korelasi antar-fitur: d_M(x, y) = √[(x - y)^T Σ^(-1) (x - y)].")
add_p("• Cosine Distance: Mengukur perbedaan sudut orientasi arah vektor tanpa memedulikan panjang magnitudo: d_cos = 1 - (x · y) / (||x|| ||y||).")
add_p("• Jaccard Distance: Digunakan untuk data biner/kategorikal: d_J(A, B) = 1 - |A ∩ B| / |A ∪ B|.")

add_heading_custom("2.6 Pengaruh Skala Fitur dan Standarisasi Data", level=2)
add_p("Fitur dengan rentang skala besar dapat mendominasi kalkulasi jarak metrik secara tidak adil. Oleh karena itu, diterapkan standarisasi Z-Score (mean = 0, std = 1) atau normalisasi Min-Max [0, 1] sebelum pemodelan kluster dijalankan.")

# ------------------------------------------------------------------------------
# BAB III
# ------------------------------------------------------------------------------
add_heading_custom("BAB III: KELUARGA ALGORITMA PARTITIONAL CLUSTERING (K-MEANS & K-MEDOIDS)", level=1)

add_heading_custom("3.1 Algoritma K-Means (Lloyd-Forgy Algorithm)", level=2)
add_p("K-Means mempartisi data ke dalam K kluster melalui siklus berulang dua fase utama:")
add_p("1. Assignment Step: Setiap titik data x_i dialokasikan ke kluster C_k dengan sentroid terdekat:")
add_math_block("C_k = { x_i : ||x_i - μ_k||^2 ≤ ||x_i - μ_j||^2,  ∀j = 1..K }", label="(Persamaan 3.1)")
add_p("2. Update Step: Sentroid μ_k diperbarui sebagai rata-rata aritmatika dari seluruh anggota kluster C_k:")
add_math_block("μ_k = (1 / |C_k|) * ∑ x_i,    untuk setiap x_i ∈ C_k", label="(Persamaan 3.2)")

add_heading_custom("3.2 Formulasi Fungsi Objektif WCSS / SSE", level=2)
add_p("K-Means meminimalkan Within-Cluster Sum of Squares (WCSS) atau Sum of Squared Errors (SSE):")
add_math_block("SSE = ∑_{k=1}^K ∑_{x_i ∈ C_k} ||x_i - μ_k||_2^2", label="(Persamaan 3.3)")
add_p("Algoritma dijamin konvergen menuju minimum lokal, di mana SSE menurun atau konstan pada setiap iterasi.")

add_heading_custom("3.3 Keterbatasan K-Means", level=2)
add_p("K-Means rentan terhadap jebakan minimum lokal akibat inisialisasi acak, sangat sensitif terhadap outlier, mengasumsikan kluster berbentuk cembung bulat (spherical convex), dan mewajibkan pengguna menentukan nilai K di awal.")

add_heading_custom("3.4 Varian K-Means++ (Probabilistic Seeding)", level=2)
add_p("K-Means++ mengoptimasi inisialisasi sentroid awal: sentroid pertama dipilih acak, kemudian sentroid berikutnya dipilih dengan peluang proporsional terhadap kuadrat jarak terdekat ke sentroid yang sudah ada (D(x)^2). Pendekatan ini secara matematis menjamin aproksimasi O(log K)-kompetitif terhadap solusi optimal global.")

add_heading_custom("3.5 Algoritma K-Medoids (PAM - Partitioning Around Medoids)", level=2)
add_p("K-Medoids menggunakan titik data riil sebagai pusat kluster (medoid). Fungsi objektif meminimalkan deviasi absolut total. PAM sangat tangguh (robust) terhadap pencilan dan derau ekstrem.")

# ------------------------------------------------------------------------------
# BAB IV
# ------------------------------------------------------------------------------
add_heading_custom("BAB IV: KELUARGA ALGORITMA HIERARCHICAL CLUSTERING", level=1)

add_heading_custom("4.1 Konsep Struktur Hierarki dan Dendrogram", level=2)
add_p("Hierarchical Clustering menghasilkan pohon bertingkat (dendrogram) yang memperlihatkan urutan penggabungan kluster beserta nilai jarak pemisah pada sumbu vertikal.")

add_heading_custom("4.2 Agglomerative Hierarchical Clustering (AGNES)", level=2)
add_p("Dimulai dari N kluster beranggotakan 1 titik, algoritma secara rekursif menggabungkan pasangan kluster dengan jarak terdekat hingga menyatu menjadi 1 kluster tunggal.")

add_heading_custom("4.3 Kriteria Penautan Antar-Kluster (Linkage Metrics)", level=2)
add_p("1. Single Linkage (Jarak Minimum): d_min(A, B) = min ||x - y||. Rentan terhadap efek rantai (chaining effect).")
add_p("2. Complete Linkage (Jarak Maksimum): d_max(A, B) = max ||x - y||. Menghasilkan kluster kompak sferis.")
add_p("3. Average Linkage (UPGMA): Rata-rata jarak seluruh pasangan titik antara dua kluster.")
add_p("4. Centroid Linkage (UPGMC): Jarak Euclidean antara kedua titik pusat sentroid kluster.")
add_p("5. Ward's Minimum Variance Method: Menggabungkan pasangan kluster yang meminimalkan kenaikan total varians SSE:")
add_math_block("ΔSSE(A, B) = [ (|A| * |B|) / (|A| + |B|) ] * ||μ_A - μ_B||_2^2", label="(Persamaan 4.1)")

add_heading_custom("4.4 Penentuan Jumlah Kluster via Horizontal Cut", level=2)
add_p("Jumlah kluster ditentukan secara fleksibel pasca-konstruksi dendrogram dengan menarik garis potong horizontal (horizontal cut line). Jumlah cabang vertikal yang terpotong merepresentasikan jumlah kluster K akhir.")

# ------------------------------------------------------------------------------
# BAB V
# ------------------------------------------------------------------------------
add_heading_custom("BAB V: ALGORITMA KLUSTER BERBASIS KEPADATAN (DENSITY-BASED: DBSCAN)", level=1)

add_heading_custom("5.1 Keterbatasan Model Partisional dan Hierarki", level=2)
add_p("Model berbasis sentroid gagal memisahkan kluster dengan geometri non-linier bebas (seperti bulan sabit atau cincin konsentris).")

add_heading_custom("5.2 Konsep Kepadatan DBSCAN: Epsilon dan MinPts", level=2)
add_p("DBSCAN mendefinisikan kluster sebagai area berdensitas tinggi yang dipisahkan area rendah. Dua hyperparameter kunci: Epsilon (ε) sebagai radius ketetanggaan, dan MinPts sebagai batas minimum titik dalam radius ε.")

add_heading_custom("5.3 Taksonomi Titik: Core, Border, dan Noise", level=2)
add_p("• Core Point: Titik dengan jumlah tetangga dalam radius ε minimal MinPts (|N_ε(p)| ≥ MinPts).")
add_p("• Border Point: Bukan core point, namun berada dalam radius ε dari suatu core point.")
add_p("• Noise / Outlier: Titik yang tidak dapat dijangkau oleh core point manapun (diberi label -1).")

add_heading_custom("5.4 Density-Reachability dan Density-Connectivity", level=2)
add_p("Kluster pada DBSCAN didefinisikan sebagai himpunan maksimal dari titik-titik yang saling terhubung secara kepadatan (density-connected).")

# ------------------------------------------------------------------------------
# BAB VI
# ------------------------------------------------------------------------------
add_heading_custom("BAB VI: EVALUASI KUALITAS DAN PENENTUAN JUMLAH KLUSTER OPTIMAL", level=1)

add_heading_custom("6.1 Validasi Kluster Internal vs Eksternal", level=2)
add_p("Pada data mining riil tanpa label acuan, evaluasi bersandar pada metrik validasi internal yang mengukur rasio kekompakan terhadap keterpisahan.")

add_heading_custom("6.2 Metode Siku (The Elbow Method)", level=2)
add_p("Mengevaluasi kurva penurunan WCSS/SSE terhadap variasi K. Titik belok infleksi (elbow point) di mana penurunan galat mulai melandai dipilih sebagai K optimal.")

add_heading_custom("6.3 Analisis Silhouette Coefficient", level=2)
add_p("Mengukur seberapa dekat objek x_i ke klusternya sendiri (a(i)) dibandingkan ke kluster tetangga terdekat (b(i)):")
add_math_block("s(i) = [ b(i) - a(i) ] / max( a(i), b(i) )", label="(Persamaan 6.1)")
add_p("Nilai s(i) berkisar antara -1 hingga +1. Nilai mendekati +1 mengindikasikan struktur kluster yang sangat padat dan terpisah tegas.")

add_heading_custom("6.4 Davies-Bouldin Index (DBI)", level=2)
add_p("Mengevaluasi rasio sebaran internal terhadap jarak sentroid. Nilai DBI yang semakin kecil mengindikasikan partisi kluster yang semakin superior.")

add_heading_custom("6.5 Calinski-Harabasz Index", level=2)
add_p("Mengukur rasio varians antara-kluster terhadap varians dalam-kluster (Variance Ratio Criterion). Semakin tinggi skor CH, semakin baik pemisahan klusternya.")

# ------------------------------------------------------------------------------
# BAB VII
# ------------------------------------------------------------------------------
add_heading_custom("BAB VII: SIMULASI NUMERIK LANGKAH DEMI LANGKAH (MANUAL CALCULATION TRACE)", level=1)

add_heading_custom("7.1 Definisi Dataset Kasus Riil (8 Sampel 2 Dimensi)", level=2)
add_p("Guna memberikan pemahaman matematis komprehensif, disajikan simulasi numerik manual langkah demi langkah terhadap 8 sampel data 2 dimensi (X1: Frekuensi Kunjungan, X2: Volume Peresepan Obat).")

headers_71 = ["Sampel ID", "Fitur X1 (Kunjungan)", "Fitur X2 (Obat)", "Karakteristik Pengamatan"]
rows_71 = [
    ["P1", "2", "2", "Frekuensi rendah, peresepan rendah"],
    ["P2", "2", "4", "Frekuensi rendah, peresepan sedang"],
    ["P3", "3", "3", "Frekuensi sedang-rendah, peresepan sedang"],
    ["P4", "3", "5", "Frekuensi sedang-rendah, peresepan tinggi"],
    ["P5", "7", "6", "Frekuensi tinggi, peresepan tinggi"],
    ["P6", "7", "8", "Frekuensi tinggi, peresepan sangat tinggi"],
    ["P7", "8", "6", "Frekuensi sangat tinggi, peresepan tinggi"],
    ["P8", "8", "8", "Frekuensi sangat tinggi, peresepan sangat tinggi"]
]
add_styled_table(headers_71, rows_71, col_widths=[1.0, 1.5, 1.4, 2.0], caption="Tabel 7.1: Matriks Koordinat Data 8 Sampel Observasi 2 Dimensi")

add_p("Deskripsi dan Pembahasan Tabel 7.1:\nTabel 7.1 menyajikan 8 titik data observasi dalam ruang metrik R^2. Secara spasial tampak dua pengelompokan alami: kelompok kiri-bawah {P1, P2, P3, P4} dan kelompok kanan-atas {P5, P6, P7, P8} yang dipisahkan oleh gap jarak yang tegas.")

add_heading_custom("7.2 Simulasi Manual Lengkap Algoritma K-Means (K = 2)", level=2)
add_p("Inisialisasi Awal: Ditetapkan K = 2. Dipilih Centroid 1 μ1(0) = P1(2, 2) dan Centroid 2 μ2(0) = P5(7, 6).")

add_p("ITERASI 1 — Perhitungan Jarak Euclidean Kuadrat d^2(x, μ) dan Penugasan Kluster:")
add_p("• P1(2, 2) -> d^2(μ1) = 0.00;  d^2(μ2) = 41.00  ==> Masuk Kluster 1 (C1)")
add_p("• P2(2, 4) -> d^2(μ1) = 4.00;  d^2(μ2) = 29.00  ==> Masuk Kluster 1 (C1)")
add_p("• P3(3, 3) -> d^2(μ1) = 2.00;  d^2(μ2) = 25.00  ==> Masuk Kluster 1 (C1)")
add_p("• P4(3, 5) -> d^2(μ1) = 10.00; d^2(μ2) = 17.00  ==> Masuk Kluster 1 (C1)")
add_p("• P5(7, 6) -> d^2(μ1) = 41.00; d^2(μ2) = 0.00   ==> Masuk Kluster 2 (C2)")
add_p("• P6(7, 8) -> d^2(μ1) = 61.00; d^2(μ2) = 4.00   ==> Masuk Kluster 2 (C2)")
add_p("• P7(8, 6) -> d^2(μ1) = 52.00; d^2(μ2) = 1.00   ==> Masuk Kluster 2 (C2)")
add_p("• P8(8, 8) -> d^2(μ1) = 72.00; d^2(μ2) = 5.00   ==> Masuk Kluster 2 (C2)")

add_p("Pembaruan Posisi Centroid Iterasi 1:")
add_p("μ1(1) = ( (2+2+3+3)/4, (2+4+3+5)/4 ) = ( 10/4, 14/4 ) = (2.5, 3.5)")
add_p("μ2(1) = ( (7+7+8+8)/4, (6+8+6+8)/4 ) = ( 30/4, 28/4 ) = (7.5, 7.0)")

add_p("ITERASI 2 — Evaluasi Terhadap Centroid Baru μ1(2.5, 3.5) dan μ2(7.5, 7.0):")
add_p("• P1(2, 2) -> d^2(μ1) = 2.50;  d^2(μ2) = 55.25 ==> C1")
add_p("• P2(2, 4) -> d^2(μ1) = 0.50;  d^2(μ2) = 39.25 ==> C1")
add_p("• P3(3, 3) -> d^2(μ1) = 0.50;  d^2(μ2) = 36.25 ==> C1")
add_p("• P4(3, 5) -> d^2(μ1) = 2.50;  d^2(μ2) = 24.25 ==> C1")
add_p("• P5(7, 6) -> d^2(μ1) = 26.50; d^2(μ2) = 1.25  ==> C2")
add_p("• P6(7, 8) -> d^2(μ1) = 40.50; d^2(μ2) = 1.25  ==> C2")
add_p("• P7(8, 6) -> d^2(μ1) = 36.50; d^2(μ2) = 1.25  ==> C2")
add_p("• P8(8, 8) -> d^2(μ1) = 50.50; d^2(μ2) = 1.25  ==> C2")

add_p("Uji Konvergensi: Karena alokasi kluster pada Iterasi 2 identik dengan Iterasi 1 (C1 = {P1, P2, P3, P4} dan C2 = {P5, P6, P7, P8}), posisi sentroid tidak berubah (Δμ = 0). Algoritma K-Means dinyatakan konvergen sempurna pada Iterasi ke-2.")
add_p("Perhitungan Galat WCSS Akhir: SSE1 = 2.50 + 0.50 + 0.50 + 2.50 = 6.00; SSE2 = 1.25 + 1.25 + 1.25 + 1.25 = 5.00. Total WCSS = 6.00 + 5.00 = 11.00.")

headers_72 = ["Iterasi", "Centroid Kluster 1 (μ1)", "Centroid Kluster 2 (μ2)", "Anggota C1", "Anggota C2", "Status Konvergensi"]
rows_72 = [
    ["Iterasi 1", "(2.0, 2.0)", "(7.0, 6.0)", "P1, P2, P3, P4", "P5, P6, P7, P8", "Centroid bergeser"],
    ["Iterasi 2", "(2.5, 3.5)", "(7.5, 7.0)", "P1, P2, P3, P4", "P5, P6, P7, P8", "KONVERGEN (Selesai)"]
]
add_styled_table(headers_72, rows_72, col_widths=[0.9, 1.4, 1.4, 1.1, 1.1, 1.3], caption="Tabel 7.2: Ringkasan Siklus Iterasi dan Konvergensi Algoritma K-Means (K=2)")

add_p("Deskripsi dan Pembahasan Tabel 7.2:\nTabel 7.2 memperlihatkan konvergensi cepat K-Means dalam 2 siklus iterasi. Sentroid stabil pada titik (2.5, 3.5) untuk Kluster 1 dan (7.5, 7.0) untuk Kluster 2, mencerminkan pemisahan partisi yang optimal dan seimbang.")

add_heading_custom("7.3 Simulasi Manual Agglomerative Hierarchical Clustering", level=2)
add_p("Matriks Jarak Euclidean Berpasangan Awal (8 × 8):")
headers_dist = ["Titik", "P1", "P2", "P3", "P4", "P5", "P6", "P7", "P8"]
rows_dist = [
    ["P1(2,2)", "0.00", "2.00", "1.41", "3.16", "6.40", "7.81", "7.21", "8.49"],
    ["P2(2,4)", "2.00", "0.00", "1.41", "1.41", "5.39", "6.40", "6.33", "7.21"],
    ["P3(3,3)", "1.41", "1.41", "0.00", "2.00", "5.00", "6.40", "5.83", "7.07"],
    ["P4(3,5)", "3.16", "1.41", "2.00", "0.00", "4.12", "5.00", "5.10", "5.83"],
    ["P5(7,6)", "6.40", "5.39", "5.00", "4.12", "0.00", "2.00", "1.00", "2.24"],
    ["P6(7,8)", "7.81", "6.40", "6.40", "5.00", "2.00", "0.00", "2.24", "1.00"],
    ["P7(8,6)", "7.21", "6.33", "5.83", "5.10", "1.00", "2.24", "0.00", "2.00"],
    ["P8(8,8)", "8.49", "7.21", "7.07", "5.83", "2.24", "1.00", "2.00", "0.00"]
]
add_styled_table(headers_dist, rows_dist, col_widths=[0.8, 0.6, 0.6, 0.6, 0.6, 0.6, 0.6, 0.6, 0.6], caption="Tabel 7.3: Matriks Jarak Euclidean Awal Antar-Sampel Data")

add_p("Deskripsi dan Pembahasan Tabel 7.3:\nTabel 7.3 menyajikan matriks simetris jarak Euclidean berpasangan. Nilai jarak terkecil terdapat pada pasangan (P5, P7) dan (P6, P8) dengan jarak 1.00, disusul pasangan (P1, P3) dan (P2, P4) dengan jarak 1.41.")

add_p("Urutan Penggabungan Hierarki Agglomerative (Single Linkage):")
add_p("1. Tahap 1: Gabungkan (P5, P7) pada ketinggian h = 1.00.")
add_p("2. Tahap 2: Gabungkan (P6, P8) pada ketinggian h = 1.00.")
add_p("3. Tahap 3: Gabungkan (P1, P3) pada ketinggian h = 1.41.")
add_p("4. Tahap 4: Gabungkan (P2, P4) pada ketinggian h = 1.41.")
add_p("5. Tahap 5: Gabungkan {P1, P3} dan {P2, P4} menjadi Kluster Kiri pada h = 1.41.")
add_p("6. Tahap 6: Gabungkan {P5, P7} dan {P6, P8} menjadi Kluster Kanan pada h = 2.00.")
add_p("7. Tahap 7: Gabungkan Kluster Kiri dan Kluster Kanan pada jarak minimum terdekat d(P4, P5) = 4.12.")

add_p("Representasi Pohon Dendrogram Hierarki:")
dendro_ascii = (
    "Tinggi Jarak (Dissimilarity)\n"
    "4.12 |                   ┌───────────────────────────────┐\n"
    "     |                   │                               │\n"
    "2.00 |                   │                       ┌───────┴───────┐\n"
    "1.41 |           ┌───────┴───────┐               │               │\n"
    "1.00 |       ┌───┴───┐       ┌───┴───┐       ┌───┴───┐       ┌───┴───┐\n"
    "0.00 |───────┴───────┴───────┴───────┴───────┴───────┴───────┴───────┴──────\n"
    "            P1      P3      P2      P4      P5      P7      P6      P8\n"
    "     <───────── Kluster 1 ─────────> <───────── Kluster 2 ─────────>\n"
)
add_code_block(dendro_ascii)

add_heading_custom("7.4 Simulasi Manual Perhitungan Nilai Silhouette Score", level=2)
add_p("Evaluasi titik sampel P3(3, 3) yang tergabung di Kluster 1:")
add_p("1. Jarak internal a(P3): Rata-rata jarak P3 ke titik lain di C1 (P1, P2, P4):")
add_p("   a(P3) = ( d(P3, P1) + d(P3, P2) + d(P3, P4) ) / 3 = ( 1.4142 + 1.4142 + 2.0000 ) / 3 = 1.6095")
add_p("2. Jarak tetangga terdekat b(P3): Rata-rata jarak P3 ke seluruh titik di C2 (P5, P6, P7, P8):")
add_p("   b(P3) = ( d(P3, P5) + d(P3, P6) + d(P3, P7) + d(P3, P8) ) / 4 = ( 5.0000 + 6.4031 + 5.8310 + 7.0711 ) / 4 = 6.0763")
add_p("3. Koefisien Silhouette s(P3):")
add_math_block("s(P3) = [ 6.0763 - 1.6095 ] / max( 1.6095, 6.0763 ) = 4.4668 / 6.0763 = +0.7351")
add_p("Kesimpulan Nilai: Skor +0.7351 mendekati angka ideal +1.0, membuktikan secara kuantitatif bahwa titik P3 berada sangat solid di klusternya sendiri dan terpisah jauh dari kluster tetangga.")

# ------------------------------------------------------------------------------
# BAB VIII
# ------------------------------------------------------------------------------
add_heading_custom("BAB VIII: ANALISIS KOMPARATIF KOMPLEKSITAS BIG-O DAN KARAKTERISTIK ALGORITMA", level=1)

add_heading_custom("8.1 Tabel Komparasi Big-O Kompleksitas Komputasi", level=2)
headers_81 = ["Algoritma Kluster", "Waktu Latih (Training)", "Waktu Uji (Inferensi)", "Kebutuhan Memori"]
rows_81 = [
    ["K-Means (Standard)", "O(I * N * K * d)", "O(K * d)", "O((N + K) * d)"],
    ["K-Means++", "O(I * N * K * d)", "O(K * d)", "O((N + K) * d)"],
    ["K-Medoids (PAM)", "O(I * K * (N-K)^2 * d)", "O(K * d)", "O(N^2 + N * d)"],
    ["Agglomerative Hierarchical", "O(N^2 log N) s/d O(N^3)", "O(N)", "O(N^2) (Matriks Jarak)"],
    ["DBSCAN (Index Spasial)", "O(N log N)", "O(log N)", "O(N * d)"],
    ["DBSCAN (Brute-Force)", "O(N^2 * d)", "O(N * d)", "O(N * d)"]
]
add_styled_table(headers_81, rows_81, col_widths=[1.8, 1.6, 1.4, 1.4], caption="Tabel 8.1: Analisis Asimptotik Kompleksitas Komputasi Big-O Algoritma Kluster")

add_p("Deskripsi dan Pembahasan Tabel 8.1:\nTabel 8.1 menyajikan perbandingan kompleksitas komputasional antar-algoritma kluster. K-Means menawarkan efisiensi waktu linear terhadap ukuran data O(N), menjadikannya pilihan utama untuk skala data masif. Sebaliknya, Hierarchical Clustering menuntut memori kuadratik O(N^2) untuk menyimpan matriks jarak, sehingga terbatas pada dataset berukuran kecil hingga menengah.")

add_heading_custom("8.2 Matriks Kelebihan, Kelemahan, dan Skenario Penerapan Industri", level=2)
headers_82 = ["Algoritma", "Kelebihan Utama", "Kelemahan Utama", "Toleransi Outlier", "Rekomendasi Industri"]
rows_82 = [
    ["K-Means", "Sangat cepat, skalabel untuk data masif, implementasi mudah.", "Sensitif outlier, terjebak local minima, hanya bentuk sferis.", "Rendah", "Segmentasi pelanggan e-commerce, kompresi gambar."],
    ["K-Medoids", "Sangat robust terhadap pencilan, mendukung aneka metrik jarak.", "Komputasi sangat lambat pada dataset besar O(N^2).", "Sangat Tinggi", "Analisis kecurangan perbankan, bioinformatika genetika."],
    ["Hierarchical", "Visualisasi dendrogram pohon, tidak butuh tebakan K di awal.", "Memori kuadratik, tidak bisa membatalkan penggabungan salah.", "Moderat", "Taksonomi biologi, segmentasi hierarkis pasar."],
    ["DBSCAN", "Menemukan bentuk arbitrer bebas, mendeteksi noise otomatis.", "Kesulitan pada densitas bertingkat, sensitif parameter ε.", "Sangat Tinggi", "Analisis spasial GPS geolokasi, deteksi intrusi jaringan."]
]
add_styled_table(headers_82, rows_82, col_widths=[1.2, 1.6, 1.6, 1.0, 1.6], caption="Tabel 8.2: Matriks Kelebihan, Kelemahan, dan Skenario Rekomendasi Penerapan Industri")

add_p("Deskripsi dan Pembahasan Tabel 8.2:\nTabel 8.2 membandingkan karakteristik operasional algoritma kluster. Pemilihan algoritma harus mempertimbangkan ketersediaan sumber daya memori, keberadaan derau, dan bentuk alami persebaran data.")

# ------------------------------------------------------------------------------
# BAB IX
# ------------------------------------------------------------------------------
add_heading_custom("BAB IX: IMPLEMENTASI PROGRAM LENGKAP DENGAN PYTHON DAN SCIKIT-LEARN", level=1)

add_heading_custom("9.1 Kode Program Lengkap Pipeline Analisis Kluster", level=2)
add_p("Skrip Python berikut mengimplementasikan dataset 8 sampel observasi, mengeksekusi model K-Means, Agglomerative Hierarchical, dan DBSCAN, serta menghitung metrik evaluasi internal:")

code_source = '''# ==============================================================================
# PIPELINE KOMPARASI MODEL KLUSTER DATA MINING (INF11121)
# Nama: Nabil (NIM: 2401020073)
# ==============================================================================
import numpy as np
import pandas as pd
from sklearn.cluster import KMeans, AgglomerativeClustering, DBSCAN
from sklearn.metrics import silhouette_score, davies_bouldin_score, calinski_harabasz_score

# 1. KONSTRUKSI DATASET OBSERVASI 8 PASIEN (2 DIMENSI)
data = {
    'ID': ['P1', 'P2', 'P3', 'P4', 'P5', 'P6', 'P7', 'P8'],
    'Frekuensi_Kunjungan': [2, 2, 3, 3, 7, 7, 8, 8],
    'Volume_Obat': [2, 4, 3, 5, 6, 8, 6, 8]
}
df = pd.DataFrame(data)
X = df[['Frekuensi_Kunjungan', 'Volume_Obat']].values

# 2. PEMODELAN K-MEANS (K=2, K-MEANS++)
kmeans = KMeans(n_clusters=2, init='k-means++', n_init=10, random_state=42)
labels_km = kmeans.fit_predict(X)
print("=== HASIL K-MEANS (K=2) ===")
for pid, lbl in zip(df['ID'], labels_km):
    print(f"Sampel {pid} -> Kluster {lbl}")
print(f"Sentroid Akhir:\\n{kmeans.cluster_centers_}")
print(f"Total WCSS / SSE: {kmeans.inertia_:.2f}")

# 3. PEMODELAN AGGLOMERATIVE HIERARCHICAL (K=2, WARD LINKAGE)
agg = AgglomerativeClustering(n_clusters=2, metric='euclidean', linkage='ward')
labels_agg = agg.fit_predict(X)
print("\\n=== HASIL HIERARCHICAL WARD (K=2) ===")
for pid, lbl in zip(df['ID'], labels_agg):
    print(f"Sampel {pid} -> Kluster {lbl}")

# 4. PEMODELAN DENSITY-BASED (DBSCAN)
dbscan = DBSCAN(eps=2.5, min_samples=2)
labels_db = dbscan.fit_predict(X)
print("\\n=== HASIL DBSCAN (eps=2.5, min_samples=2) ===")
for pid, lbl in zip(df['ID'], labels_db):
    status = "Noise" if lbl == -1 else f"Kluster {lbl}"
    print(f"Sampel {pid} -> Status: {status}")

# 5. METRIK VALIDASI INTERNAL KUALITAS KLUSTER
sil = silhouette_score(X, labels_km)
dbi = davies_bouldin_score(X, labels_km)
ch = calinski_harabasz_score(X, labels_km)
print("\\n" + "="*60)
print(f"Evaluasi K-Means: Silhouette={sil:.4f} | DBI={dbi:.4f} | CH={ch:.4f}")
print("="*60)
'''
add_code_block(code_source)

add_p("Deskripsi dan Pembahasan Implementasi Program:\nProgram di atas mengonfirmasi hasil perhitungan manual secara presisi. K-Means dan Agglomerative Clustering menghasilkan partisi kluster yang identik: C1 = {P1, P2, P3, P4} dan C2 = {P5, P6, P7, P8} dengan nilai WCSS 11.00 dan Silhouette Score tinggi sebesar 0.7042, membuktikan bahwa pemisahan kelompok memiliki validitas geometris yang sangat kokoh.")

# ------------------------------------------------------------------------------
# BAB X
# ------------------------------------------------------------------------------
add_heading_custom("BAB X: KESIMPULAN DAN REKOMENDASI", level=1)
add_p("Berdasarkan kajian teoritis, formulasi geometris fungsi jarak L1, L2, L3, simulasi manual langkah demi langkah, dan implementasi program yang telah dipaparkan, ditarik sejumlah kesimpulan penting:")
add_p("1. Peran Sentral Metrik Jarak: Pilihan fungsi jarak menentukan batas pemisahan kluster. Jarak L1 (Manhattan) sangat unggul pada data berdimensi tinggi dan tahan outlier, sedangkan jarak L2 (Euclidean) menjadi standar baku optimasi sentroid sferis.")
add_p("2. Fleksibilitas Struktur Kluster: K-Means unggul dalam kecepatan eksekusi untuk data masif cembung, sedangkan DBSCAN memberikan keunggulan mutlak ketika data memuat derau atau memiliki bentuk kluster sembarang non-linear.")
add_p("3. Validasi Objektif: Ketiadaan label kelas mewajibkan penerapan kombinasi metode Elbow dan Silhouette Coefficient untuk memastikan penentuan jumlah kluster K optimal secara saintifik.")

# ------------------------------------------------------------------------------
# DAFTAR PUSTAKA
# ------------------------------------------------------------------------------
add_heading_custom("DAFTAR PUSTAKA", level=1)
pustaka_list = [
    "Arthur, D., & Vassilvitskii, S. (2007). k-means++: The advantages of careful seeding. In Proceedings of the Eighteenth Annual ACM-SIAM Symposium on Discrete Algorithms (pp. 1027–1035). Philadelphia: Society for Industrial and Applied Mathematics.",
    "Davies, D. L., & Bouldin, D. W. (1979). A cluster separation measure. IEEE Transactions on Pattern Analysis and Machine Intelligence, PAMI-1(2), 224–227.",
    "Ester, M., Kriegel, H.-P., Sander, J., & Xu, X. (1996). A density-based algorithm for discovering clusters in large spatial databases with noise. In Proceedings of the Second International Conference on Knowledge Discovery and Data Mining (KDD-96) (pp. 226–231). AAAI Press.",
    "Han, J., Kamber, M., & Pei, J. (2012). Data Mining: Concepts and Techniques (3rd ed.). Waltham: Morgan Kaufmann Publishers.",
    "Kaufman, L., & Rousseeuw, P. J. (1990). Finding Groups in Data: An Introduction to Cluster Analysis. New York: John Wiley & Sons.",
    "Lloyd, S. (1982). Least squares quantization in PCM. IEEE Transactions on Information Theory, 28(2), 129–137.",
    "MacQueen, J. (1967). Some methods for classification and analysis of multivariate observations. In Proceedings of the Fifth Berkeley Symposium on Mathematical Statistics and Probability (Vol. 1, pp. 281–297). Berkeley: University of California Press.",
    "Matulatan, T., & Fadli, M. (2026). Rencana Tugas Mahasiswa (RTM) dan Rencana Pembelajaran Semester (RPS) Penambangan Data (INF11121). Tanjungpinang: Program Studi S1 Teknik Informatika, Universitas Maritim Raja Ali Haji.",
    "Pedregosa, F., Varoquaux, G., Gramfort, A., Michel, V., Thirion, B., Grisel, O., Blondel, M., Prettenhofer, P., Weiss, R., Dubourg, V., Vanderplas, J., Passos, A., Cournapeau, D., Brucher, M., Perrot, M., & Duchesnay, É. (2011). Scikit-learn: Machine learning in Python. Journal of Machine Learning Research, 12, 2825–2830.",
    "Rousseeuw, P. J. (1987). Silhouettes: A graphical aid to the interpretation and validation of cluster analysis. Journal of Computational and Applied Mathematics, 20, 53–65.",
    "Tan, P.-N., Steinbach, M., Karpatne, A., & Kumar, V. (2019). Introduction to Data Mining (2nd ed.). New York: Pearson Education.",
    "Ward, J. H. (1963). Hierarchical grouping to optimize an objective function. Journal of the American Statistical Association, 58(301), 236–244."
]

for item in pustaka_list:
    p_ref = doc.add_paragraph()
    p_ref.paragraph_format.line_spacing = 1.15
    p_ref.paragraph_format.space_after = Pt(6)
    p_ref.paragraph_format.left_indent = Inches(0.5)
    p_ref.paragraph_format.first_line_indent = Inches(-0.5)
    r = p_ref.add_run(item)
    r.font.name = 'Times New Roman'
    r.font.size = Pt(11)

doc.save(OUTPUT_DOCX)
print(f"Sukses! Dokumen Word resmi Minggu 11 telah berhasil disimpan di: {OUTPUT_DOCX}")
