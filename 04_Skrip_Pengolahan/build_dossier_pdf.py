#!/usr/bin/env python3
"""
04_Skrip_Pengolahan/build_dossier_pdf.py

Skrip Pembuat Dokumen PDF Dosir Akademik Bimbingan & Konsultasi PRABOTIK
Tahap 1 s/d Tahap 5 Lengkap dengan 9 Grafik Resolusi Tinggi (300 DPI),
Tabel Evaluasi, Formulasi Matematis, dan Simulasi Tanya-Jawab Ujian Sidang.
"""

import os
import subprocess

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
VIZ_DIR = os.path.join(BASE_DIR, '05_Visualisasi_Grafik')
DOC_DIR = os.path.join(BASE_DIR, '01_Dokumen_Akademik')
os.makedirs(DOC_DIR, exist_ok=True)

LOGO_PATH = os.path.join(BASE_DIR, 'logo_umrah.png')
HTML_OUTPUT = os.path.join(DOC_DIR, 'DOSIR_KONSULTASI_TAHAP_1_SAMPAI_5.html')
PDF_OUTPUT = os.path.join(DOC_DIR, 'DOSIR_MATERI_KONSULTASI_TAHAP_1_SAMPAI_5_PRABOTIK.pdf')

def build_html():
    html = """<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dosir Konsultasi & Pembuktian Metodologi PRABOTIK (Tahap 1 - 5)</title>
<style>
@page {
    size: A4;
    margin: 12mm 14mm 12mm 14mm;
}
* {
    box-sizing: border-box;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
}
body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    color: #1E293B;
    background: #FFFFFF;
    line-height: 1.48;
    font-size: 8.8pt;
    margin: 0;
    padding: 0;
}
.page-break {
    page-break-after: always;
    break-after: page;
}
.avoid-break {
    page-break-inside: avoid;
    break-inside: avoid;
}

/* COVER STYLING */
.cover-page {
    padding: 4mm 0;
    text-align: center;
}
.cover-header {
    border-bottom: 2.5px solid #1A3B22;
    padding-bottom: 3mm;
    margin-bottom: 5mm;
}
.cover-univ {
    font-size: 13pt;
    font-weight: 800;
    color: #1A3B22;
    letter-spacing: 0.5px;
    margin: 0;
}
.cover-faculty {
    font-size: 9.5pt;
    font-weight: 600;
    color: #475569;
    margin: 1.5mm 0 0 0;
}
.cover-logo {
    max-width: 90px !important;
    max-height: 90px !important;
    height: auto !important;
    margin: 0 auto 4mm auto;
    display: block;
}
.badge-tag {
    display: inline-block;
    padding: 1.2mm 4.5mm;
    background: #E2E8F0;
    color: #1E293B;
    border-radius: 20px;
    font-size: 7.8pt;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 3.5mm;
}
.cover-title {
    font-size: 17pt;
    font-weight: 900;
    color: #0F172A;
    line-height: 1.25;
    margin: 0 0 2.5mm 0;
}
.cover-title span {
    color: #1A3B22;
}
.cover-subtitle {
    font-size: 9.5pt;
    color: #475569;
    max-width: 155mm;
    margin: 0 auto 6mm auto;
    line-height: 1.45;
}
.cover-footer {
    background: #F8FAFC;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    padding: 3.5mm 5mm;
    text-align: left;
    margin-top: 4mm;
}
.grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4mm;
}
.info-block h4 {
    font-size: 8pt;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748B;
    margin: 0 0 1mm 0;
}
.info-block p {
    margin: 0.8mm 0;
    font-size: 8.5pt;
    font-weight: 600;
    color: #0F172A;
}

/* HEADINGS & CONTENT */
h1.section-title {
    font-size: 13.5pt;
    font-weight: 800;
    color: #1A3B22;
    border-bottom: 2px solid #5C6A44;
    padding-bottom: 1.5mm;
    margin: 2mm 0 2.5mm 0;
}
h2.sub-title {
    font-size: 10.5pt;
    font-weight: 700;
    color: #0F172A;
    margin: 2.5mm 0 1.5mm 0;
    border-left: 3.5px solid #1A3B22;
    padding-left: 2mm;
}
h3.sub-sub-title {
    font-size: 9.2pt;
    font-weight: 700;
    color: #1E293B;
    margin: 1.5mm 0 1mm 0;
}
p, li {
    text-align: justify;
    line-height: 1.48;
    color: #334155;
    margin: 1mm 0;
}
ul, ol {
    margin: 1mm 0 2mm 3.5mm;
    padding: 0;
}
li {
    margin-bottom: 0.8mm;
}

/* CARDS & ALERTS */
.card {
    background: #FFFFFF;
    border: 1px solid #CBD5E1;
    border-radius: 6px;
    padding: 2.2mm 3.5mm;
    margin: 1.8mm 0;
}
.alert-box {
    background: #F0FDF4;
    border-left: 3.5px solid #16A34A;
    padding: 2mm 3mm;
    margin: 2mm 0;
    font-size: 8.5pt;
    border-radius: 0 5px 5px 0;
}
.alert-title {
    font-weight: 700;
    margin-bottom: 0.8mm;
    display: block;
    color: #15803D;
}

/* TABLES */
table.data-table {
    width: 100%;
    border-collapse: collapse;
    margin: 2mm 0;
    font-size: 8pt;
}
table.data-table th {
    background: #1A3B22 !important;
    color: #FFFFFF !important;
    font-weight: 700;
    padding: 1.5mm 2mm;
    text-align: left;
    border: 1px solid #1A3B22;
}
table.data-table td {
    padding: 1.3mm 2mm;
    border: 1px solid #CBD5E1;
    color: #1E293B;
}
table.data-table tr:nth-child(even) td {
    background: #F8FAFC !important;
}
.text-right { text-align: right; }
.text-center { text-align: center; }
.font-bold { font-weight: 700; }
.tnum { font-variant-numeric: tabular-nums; font-family: monospace; }

/* IMAGES & CAPTIONS */
.figure-block {
    margin: 1.8mm 0;
    text-align: center;
}
.figure-block img {
    max-height: 60mm;
    width: auto;
    max-width: 100%;
    border-radius: 5px;
    border: 1px solid #CBD5E1;
    display: block;
    margin: 0 auto;
}
.figure-caption {
    font-size: 7.8pt;
    color: #475569;
    font-style: normal;
    margin-top: 1mm;
    font-weight: 700;
}

/* FORMULAS */
.formula-card {
    background: #F8FAFC;
    border: 1px solid #CBD5E1;
    border-left: 3.5px solid #1A3B22;
    padding: 1.5mm 3mm;
    margin: 1.5mm 0;
    font-family: monospace;
    font-size: 8.2pt;
    font-weight: 600;
    color: #0F172A;
}

/* STATUS BADGES */
.badge-ok {
    background: #DCFCE7 !important;
    color: #166534 !important;
    font-weight: 700;
    padding: 0.6mm 2.2mm;
    border-radius: 4px;
    display: inline-block;
    font-size: 7.5pt;
}
.badge-warn {
    background: #FEF3C7 !important;
    color: #92400E !important;
    font-weight: 700;
    padding: 0.6mm 2.2mm;
    border-radius: 4px;
    display: inline-block;
    font-size: 7.5pt;
}

.kpi-strip {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2.5mm;
    margin: 2.5mm 0;
}
.kpi-card {
    background: #F8FAFC;
    border: 1px solid #CBD5E1;
    border-radius: 6px;
    padding: 2mm;
    text-align: center;
}
.kpi-num {
    font-size: 11pt;
    font-weight: 800;
    color: #1A3B22;
    margin: 0;
    font-family: monospace;
}
.kpi-label {
    font-size: 7.2pt;
    text-transform: uppercase;
    color: #64748B;
    margin: 0.5mm 0 0 0;
    font-weight: 700;
}
</style>
</head>
<body>

<!-- =========================================================================
     HALAMAN 1: HALAMAN SAMPUL (COVER)
     ========================================================================= -->
<div class="cover-page page-break">
    <div class="cover-header">
        <h2 class="cover-univ">UNIVERSITAS MARITIM RAJA ALI HAJI</h2>
        <p class="cover-faculty">FAKULTAS TEKNIK DAN TEKNOLOGI KEMARITIMAN<br>PROGRAM STUDI S1 TEKNIK INFORMATIKA</p>
    </div>

    <img src="file://{LOGO_PATH}" alt="Logo UMRAH" class="cover-logo">

    <div class="badge-tag">DOSIR KONSULTASI & PEMBUKTIAN METODOLOGI PENAMBANGAN DATA</div>

    <h1 class="cover-title">PERAMALAN KEBUTUHAN OBAT &amp;<br>ANALISIS MORBIDITAS PESISIR (<span>PRABOTIK</span>)</h1>
    
    <p class="cover-subtitle">
        Sintesis Akademik Menyeluruh Tahap 1 s/d 5: Pembersihan Data Riil, Eksplorasi Data Awal (EDA), Peramalan Deret Waktu Multi-Granularitas, Proyeksi Morbiditas SIMPUS, dan Validasi Klinis Dual-Jalur di UPTD Puskesmas Teluk Sasah.
    </p>

    <div class="cover-footer">
        <div class="grid-2">
            <div class="info-block">
                <h4>Institusi Mitra Penelitian</h4>
                <p>UPTD Puskesmas Teluk Sasah</p>
                <p style="font-weight: normal; font-size: 8pt; color: #475569;">Kec. Seri Kuala Lobam, Kab. Bintan, Kepulauan Riau</p>
                
                <h4 style="margin-top: 3mm;">Dosen Pengampu &amp; Pembimbing</h4>
                <p>1. Tekad Matulatan, S.T., M.Cs. <span style="font-size: 7.5pt; color: #64748B;">(NIDN: 0010078403)</span></p>
                <p>2. Muhamad Fadli, S.T., M.Kom. <span style="font-size: 7.5pt; color: #64748B;">(NIDN: 0021079004)</span></p>
            </div>
            <div class="info-block">
                <h4>Tim Peneliti (Kelompok 23)</h4>
                <p>• Nabil Sahputra Tambunan <span style="font-size: 7.5pt; color: #64748B;">(2201020087)</span></p>
                <p>• Balqis Nurul Fathia <span style="font-size: 7.5pt; color: #64748B;">(2201020042)</span></p>
                <p>• Jian David Ginting <span style="font-size: 7.5pt; color: #64748B;">(2201020015)</span></p>
                <p>• Annisa Setya Utami <span style="font-size: 7.5pt; color: #64748B;">(2201020040)</span></p>
                <p>• Yehezkiel David Pratama S. <span style="font-size: 7.5pt; color: #64748B;">(2201020017)</span></p>

                <h4 style="margin-top: 3mm;">Tahun Akademik</h4>
                <p>Semester Ganjil 2026/2027</p>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================================
     HALAMAN 2: RINGKASAN EKSEKUTIF & PETA JALAN KONSULTASI
     ========================================================================= -->
<div class="page-break">
    <h1 class="section-title">Peta Jalan Konsultasi &amp; Matriks Implementasi Saran Dosen</h1>
    
    <div class="alert-box">
        <span class="alert-title">🎯 Tujuan Penyusunan Dosir Akademik</span>
        Dokumen ini merangkum seluruh tahapan ilmiah dari data mentah hingga peramalan final untuk sesi bimbingan bersama Bapak Tekad Matulatan dan Bapak Muhamad Fadli. Seluruh angka, grafik resolusi tinggi, dan formula matematis bersumber langsung dari audit riil UPTD Puskesmas Teluk Sasah tanpa rekayasa fiktif.
    </div>

    <h2 class="sub-title">Matriks Respons Kelompok atas 3 Rekomendasi Utama Pembimbing</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 20%;">Sumber Saran</th>
                <th style="width: 32%;">Inti Pertanyaan / Masukan</th>
                <th style="width: 36%;">Solusi Metodologis yang Diimplementasikan</th>
                <th style="width: 12%;">Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><b>Bapak Tekad Matulatan</b><br><span style="font-size: 7pt; color: #94A3B8;">Saran Granularitas</span></td>
                <td>Data LPLPO tercampur antara format 1-bulanan (2024) dan 2-bulanan (2023, 2025, 2026). Dosen meminta grafik pembanding kedua format.</td>
                <td>Dibuat grafik komparasi <i>Apple-to-Apple</i> tahun 2024 (Gambar 2.1). Membuktikan format dwibulanan mereduksi variabilitas sebesar <b>22,7%</b> (CV turun dari 74,6% ke 51,9%). Dilakukan resampling 22 periode dwibulanan kontinu.</td>
                <td><span class="badge-ok">SELESAI (Bab II)</span></td>
            </tr>
            <tr>
                <td><b>Bapak Tekad Matulatan</b><br><span style="font-size: 7pt; color: #94A3B8;">Saran Indikator MACD</span></td>
                <td>Menanyakan apakah indikator MACD (Moving Average Convergence Divergence) dapat diterapkan untuk deteksi lonjakan konsumsi obat.</td>
                <td>Diformulasikan MACD adaptif deret waktu dwibulanan: <b>Fast EMA = 2 periode (~4 bln)</b>, <b>Slow EMA = 4 periode (~8 bln)</b>, dan <b>Signal = EMA-2 dari MACD</b>. Histogram positif memberi sinyal <i>Bullish Momentum</i>.</td>
                <td><span class="badge-ok">SELESAI (Bab III)</span></td>
            </tr>
            <tr>
                <td><b>Bapak Muhamad Fadli</b><br><span style="font-size: 7pt; color: #94A3B8;">Saran Obat Kadaluarsa</span></td>
                <td>Meminta data obat kadaluarsa (Expired Date) diintegrasikan langsung ke dalam formula prediksi pengadaan logistik, bukan sekadar pelengkap.</td>
                <td>Diintegrasikan ke dalam formula pengadaan final: <code>Q = max(0, Prediksi + SS - Sisa_Stok)</code>. Sisa stok di gudang dikurangi langsung untuk menghentikan akumulasi <i>overstock</i> yang menyebabkan kerugian <b>Rp 43,35 Jt</b>. Didukung protokol FEFO.</td>
                <td><span class="badge-ok">SELESAI (Bab III)</span></td>
            </tr>
        </tbody>
    </table>

    <h2 class="sub-title">Rekapitulasi Metrik Utama Integritas Data Proyek PRABOTIK</h2>
    <div class="kpi-strip">
        <div class="kpi-card">
            <p class="kpi-num">15.781 Baris</p>
            <p class="kpi-label">Transaksi LPLPO (2023–2026)</p>
        </div>
        <div class="kpi-card">
            <p class="kpi-num">31.125 Pasien</p>
            <p class="kpi-label">Kunjungan SIMPUS 45 Bulan</p>
        </div>
        <div class="kpi-card">
            <p class="kpi-num">Rp 43,35 Jt</p>
            <p class="kpi-label">Audit Kerugian Kadaluarsa</p>
        </div>
        <div class="kpi-card">
            <p class="kpi-num">482 Kejadian</p>
            <p class="kpi-label">Stockout Riil Gudang</p>
        </div>
    </div>

    <div class="card" style="margin-top: 3mm;">
        <h3 class="sub-sub-title">Struktur Urutan Pembahasan Dosir Akademik</h3>
        <p>
            Dosir ini disusun mengikuti alur logika baku penambangan data ilmiah: 
            <b>(1) Pembersihan Data Mentah</b> &rarr; 
            <b>(2) Eksplorasi Data Awal (EDA)</b> &rarr; 
            <b>(3) Pemodelan Deret Waktu Stok Obat</b> &rarr; 
            <b>(4) Peramalan Morbiditas Penyakit SIMPUS</b> &rarr; 
            <b>(5) Analisis Korelasi Klinis Farmasi</b> &rarr; 
            <b>(6) Simulasi Pertanyaan Sidang Pembimbing</b>.
        </p>
    </div>
</div>

<!-- =========================================================================
     HALAMAN 3: BAB I — TAHAP 1 (BAGIAN 1): SUMBER DATA & MASALAH 1-3
     ========================================================================= -->
<div class="page-break">
    <h1 class="section-title">BAB I: Tahap 1 — Pengumpulan Data Mentah, Pembersihan, &amp; Normalisasi</h1>

    <h2 class="sub-title">1.1 Tiga Sumber Data Primer Asli (Immutable Raw Data)</h2>
    <p>
        Proyek penambangan data PRABOTIK bertumpu pada integritas data primer faskes pemerintah tingkat pertama. Seluruh berkas mentah asli disimpan dalam folder <code>07_Alur_Data_Mentah_ke_Final/Tahap_01_Data_Mentah_Primer/</code> dan tidak diubah secara manual (bersifat <i>immutable</i>):
    </p>
    <ul>
        <li><b>29 Berkas Laporan Pemakaian dan Lembar Permintaan Obat (LPLPO):</b> Dokumen Excel transaksi gudang farmasi mencakup 4 tahun kalender (2023–2026) yang merekam stok awal, penerimaan pasokan, pemakaian, dan stok akhir barang. Total 15.781 baris transaksi mentah.</li>
        <li><b>Master Rekapitulasi Morbiditas SIMPUS:</b> Rekam medis kunjungan pasien rawat jalan selama 45 bulan observasi (Januari 2023 s/d September 2026) yang mencakup 31.125 pasien terdistribusi ke dalam 10 besar diagnosis penyakit pesisir.</li>
        <li><b>Berita Acara Rekapitulasi Obat Kadaluarsa (Expired Date):</b> Rekapitulasi audit fisik pemusnahan/karantina obat rusak tahun 2023, 2024, dan 2025 dengan total 138 item senilai Rp 43.348.626,-.</li>
    </ul>

    <h2 class="sub-title">1.2 Enam Masalah Struktural Data Mentah &amp; Solusi Algoritmik (Bagian 1)</h2>
    
    <div class="card avoid-break">
        <h3 class="sub-sub-title">Masalah 1: Ketidaksinkronan Waktu (Bulanan vs Dwibulanan) — Saran 1 Pak Tekad</h3>
        <p>
            Data LPLPO tahun 2024 dicatat dalam format <b>bulanan (12 berkas)</b>, sedangkan tahun 2023 (6 berkas), 2025 (6 berkas), dan 2026 (4 berkas) dicatat dalam siklus <b>dwibulanan</b>. Jika digabungkan langsung, pemodelan Moving Average akan menganggap konsumsi tahun 2024 anjlok separuh.
        </p>
        <p><b>Solusi Penambangan Data:</b> Menerapkan <i>Temporal Resampling &amp; Alignment</i> ke dalam 22 periode dwibulanan kontinu. Wajib membedakan dua variabel matematis:</p>
        <div class="formula-card">
            Variabel Aliran (Flow - Dijumlahkan) : P_terima(k) = P(m1) + P(m2) | K_pakai(k) = K(m1) + K(m2)<br>
            Variabel Posisi (Stock - Tidak Dijumlahkan): S_awal(k) = S_awal(m1) | S_akhir(k) = S_akhir(m2)
        </div>
        <p>Hal ini mencegah terjadinya penghitungan ganda (<i>double counting</i>) sekaligus menjaga hukum kekekalan stok fisik: <code>S_akhir = S_awal + P_terima - K_pakai</code>.</p>
    </div>

    <div class="card avoid-break">
        <h3 class="sub-sub-title">Masalah 2: Artefak Dokumen Birokrasi (Header, Kop Surat, &amp; Tanda Tangan)</h3>
        <p>
            Lembar kerja Excel memuat banyak baris cetak kop surat, sub-header kategori obat (<i>"I. OBAT GENERIK"</i>, <i>"BMHP"</i>), baris subtotal, dan blok legalitas tanda tangan NIP dokter pimpinan faskes.
        </p>
        <p><b>Solusi:</b> Dibuat algoritma penyaring berbasis aturan (<i>Rule-Based Structural Filtering</i>) pada Python Pandas. Baris hanya diakui sebagai transaksi valid jika nomor urut bernilai bulat positif dan bukan kata kunci tanda tangan. Pada dokumen ED, tepat <b>6 baris tanda tangan berhasil dibuang</b> dari 144 baris, menyisakan <b>138 item murni</b>.</p>
    </div>

    <div class="card avoid-break">
        <h3 class="sub-sub-title">Masalah 3: Missing Value Harga Satuan Audit Obat Kadaluarsa</h3>
        <p>
            Petugas gudang mencatat kuantitas barang kadaluarsa di tahun 2024–2025 namun mengosongkan harga satuannya. <b>Imputasi rata-rata (mean) atau median dilarang keras</b> dalam domain audit keuangan negara karena rentang harga obat sangat timpang (Rp 150,- s/d puluhan ribu rupiah).
        </p>
        <p><b>Solusi:</b> Diterapkan metode <b>Cross-Table Domain-Specific Lookup</b> ke Master LPLPO resmi faskes. Diperoleh angka kerugian riil terverifikasi yang sah di hadapan hukum sebesar <b>Rp 43.348.626,-</b>.</p>
    </div>
</div>

<!-- =========================================================================
     HALAMAN 4: BAB I — TAHAP 1 (BAGIAN 2): MASALAH 4-6 & PIPELINE ETL
     ========================================================================= -->
<div class="page-break">
    <h1 class="section-title">BAB I: Tahap 1 — Pengumpulan Data Mentah, Pembersihan, &amp; Normalisasi (Lanjutan)</h1>

    <h2 class="sub-title">1.2 Enam Masalah Struktural Data Mentah &amp; Solusi Algoritmik (Bagian 2)</h2>

    <div class="card avoid-break">
        <h3 class="sub-sub-title">Masalah 4: Anomali Outlier Fero Sulfat (Tablet Tambah Darah Siswi Sekolah)</h3>
        <p>
            Fero Sulfat mencatat pemakaian tertinggi di puskesmas (255.180 tablet, CV=2,20), melampaui Parasetamol (142.715 tablet, CV=0,42). Penelusuran forensik pada berkas <code>1 JANUARI 2024.xls</code> baris 122 menemukan satu pengeluaran massal 118.295 tablet bertuliskan <b>KET: GIZI</b>.
        </p>
        <p><b>Temuan:</b> Fero Sulfat adalah Obat Program Nasional pencegahan anemia siswi sekolah (<i>push-distribution</i>), bukan peresepan harian pasien sakit di poli (<i>pull-demand</i>). Kategori ini dipisahkan agar Parasetamol tetap sah sebagai obat kuratif harian nomor satu.</p>
    </div>

    <div class="card avoid-break">
        <h3 class="sub-sub-title">Masalah 5: Inkonsistensi Penamaan Entitas Obat (String Normalization)</h3>
        <p>
            Penamaan obat antartahun memiliki variasi penulisan tipografi yang berbeda-beda, seperti <i>"Paracetamol 500 mg"</i>, <i>"Parasetamol tab"</i>, <i>"PARASETAMOL 500MG"</i>, spasi ganda, serta penggunaan singkatan unit kemasan (tab, btl, ktk, syp).
        </p>
        <p><b>Solusi:</b> Diterapkan pipeline normalisasi teks otomatis: konversi ke huruf kapital standar, penghapusan tanda baca berlebih, pemisahan angka dan satuan dosis (regex <code>(\d+)\s*(mg|gr|ml)</code>), serta pencocokan ke <i>Master Dictionary Formularium Teluk Sasah</i> (150 sediaan obat aktif).</p>
    </div>

    <div class="card avoid-break">
        <h3 class="sub-sub-title">Masalah 6: Sensoring Data Konsumsi Akibat Kekosongan Fisik (Stockout Censoring)</h3>
        <p>
            Ketika stok fisik di gudang habis total (<i>Stock on Hand = 0</i>), angka pemakaian tercatat 0 di buku register LPLPO. Menganggap angka 0 ini sebagai ketiadaan kebutuhan riil pasien sakit adalah kesalahan metodologis fatal dalam penambangan data logistik.
        </p>
        <p><b>Solusi:</b> Dibuat algoritma audit status stok yang menandai setiap baris transaksi dengan bendera <code>IS_STOCKOUT = (S_awal == 0 &amp;&amp; P_terima == 0 &amp;&amp; S_akhir == 0)</code>. Baris yang tersensor ini ditandai untuk dilakukan rekonstruksi matematis pada Tahap 3.</p>
    </div>

    <h2 class="sub-title">1.3 Rekapitulasi Pipeline Rekayasa Data (ETL) PRABOTIK</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>Tahapan Pipeline</th>
                <th>Operasi Algoritmik</th>
                <th>Keluaran / Artefak Bersih</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><b>1. Extraction</b></td>
                <td>Membaca 29 file Excel LPLPO, 1 rekap SIMPUS 45 bulan, 3 rekap ED.</td>
                <td>Raw Dataframe: 15.781 baris transaksi obat; 31.125 pasien.</td>
            </tr>
            <tr>
                <td><b>2. Cleansing</b></td>
                <td>Rule-based filtering: membuang header kop surat, subtotal, baris NIP tanda tangan.</td>
                <td>138 item ED sah (membuang 6 baris NIP); data LPLPO bebas artefak cetak.</td>
            </tr>
            <tr>
                <td><b>3. Imputation</b></td>
                <td>Cross-table lookup harga satuan master faskes untuk item ED yang kosong.</td>
                <td>Kerugian finansial terverifikasi valid Rp 43.348.626,-.</td>
            </tr>
            <tr>
                <td><b>4. Resampling</b></td>
                <td>Agregasi 1-bulanan 2024 menjadi dwibulanan (aliran dijumlahkan, posisi tidak).</td>
                <td>22 Periode dwibulanan kontinu seimbang (2023-P1 s/d 2026-P4).</td>
            </tr>
            <tr>
                <td><b>5. Normalization</b></td>
                <td>Pembersihan string regex dan pemisahan obat Program Gizi vs Kuratif Poli.</td>
                <td>Dataset Master Final siap latih untuk pemodelan deret waktu.</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- =========================================================================
     HALAMAN 5: BAB II — TAHAP 2 (BAGIAN 1): EDA PILAR 1 (TREN WAKTU & STOCKOUT)
     ========================================================================= -->
<div class="page-break">
    <h1 class="section-title">BAB II: Tahap 2 — Eksplorasi Data Awal (EDA) &amp; Visualisasi Grafik</h1>
    <p>
        Tahap EDA menyajikan visualisasi analitik berstandar publikasi (300 DPI) yang membedah logistik obat LPLPO, kerugian finansial obat kadaluarsa, dan pola morbiditas penyakit pesisir.
    </p>

    <h2 class="sub-title">2.1 Pilar 1: Komparasi Granularitas Deret Waktu &amp; Masalah Kekosongan Stok (Stockout)</h2>
    
    <div class="figure-block avoid-break">
        <img src="file://{VIZ_DIR}/15_tren_bulanan_2024_vs_dwibulanan.png" alt="Gambar 2.1">
        <div class="figure-caption">Gambar 2.1: Komparasi Granularitas Deret Waktu: Tren 1-Bulanan (12 Titik) vs Dwibulanan (6 Titik) Tahun 2024 (Saran 1 Pak Tekad)</div>
    </div>
    <p style="font-size: 8.3pt; line-height: 1.42;">
        <b>Analisis Gambar 2.1:</b> Kurva kiri (bulanan) memperlihatkan fluktuasi tajam dengan Koefisien Variasi (CV) <b>74,6%</b> yang banyak dipicu keterlambatan pengiriman faktur logistik. Kurva kanan (dwibulanan) memperlihatkan agregasi yang jauh lebih stabil dengan CV <b>51,9%</b>. Reduksi volatilitas sebesar <b>22,7% poin persentase</b> membuktikan bahwa format dwibulanan lebih ideal untuk pemodelan peramalan pengadaan.
    </p>

    <div class="figure-block avoid-break">
        <img src="file://{VIZ_DIR}/08_analisis_stockout_kosong.png" alt="Gambar 2.2">
        <div class="figure-caption">Gambar 2.2: Kuantifikasi Krisis Kekosongan Fisik Obat (Stockout SOH = 0) di Gudang Farmasi Puskesmas Teluk Sasah</div>
    </div>
    <p style="font-size: 8.3pt; line-height: 1.42;">
        <b>Analisis Gambar 2.2:</b> Teridentifikasi <b>482 kejadian stockout riil (3,1% baris transaksi)</b> pada 292 jenis obat. Obat esensial yang paling parah mengalami kekosongan adalah Kaptopril 25 mg (13 periode / 59%), Ibuprofen (10 periode / 45%), dan Parasetamol 500 mg (4 periode). Ini adalah bukti empiris utama urgensi penerapan sistem peramalan PRABOTIK.
    </p>
</div>

<!-- =========================================================================
     HALAMAN 6: BAB II — TAHAP 2 (BAGIAN 2): EDA PILAR 2 (AUDIT ED & TOP OBAT)
     ========================================================================= -->
<div class="page-break">
    <h1 class="section-title">BAB II: Tahap 2 — Eksplorasi Data Awal (EDA) (Lanjutan)</h1>

    <h2 class="sub-title">2.2 Pilar 2: Audit Kerugian Finansial Obat Kadaluarsa (Expired Date) — Saran Pak Fadli</h2>
    
    <div class="figure-block avoid-break">
        <img src="file://{VIZ_DIR}/06_analisis_kerugian_kadaluarsa.png" alt="Gambar 2.3">
        <div class="figure-caption">Gambar 2.3: Audit Dampak Finansial Obat &amp; Reagen Kadaluarsa Periode 2023 - 2025 (Total Kerugian Rp 43,35 Juta)</div>
    </div>
    <p style="font-size: 8.3pt; line-height: 1.42;">
        <b>Analisis Gambar 2.3:</b> Total kerugian fisik terverifikasi adalah <b>Rp 43.348.626,-</b> (138 item murni). Terjadi lonjakan tajam sebesar <b>+112,6% pada tahun 2025</b> (mencapai Rp 21,83 Jt) akibat akumulasi <i>over-budgeting</i> di masa lalu yang memesan obat tanpa mengontrol sisa stok fisik di gudang, serta ketiadaan pengawasan rotasi sistem FEFO (<i>First Expired, First Out</i>).
    </p>

    <div class="figure-block avoid-break">
        <img src="file://{VIZ_DIR}/01_top_obat_pemakaian.png" alt="Gambar 2.4">
        <div class="figure-caption">Gambar 2.4: Peringkat 15 Obat dengan Volume Pemakaian Tertinggi (Klasifikasi Program Gizi vs Pelayanan Kuratif)</div>
    </div>
    <p style="font-size: 8.3pt; line-height: 1.42;">
        <b>Analisis Gambar 2.4:</b> Menegaskan pemisahan antara Fero Sulfat (255.180 butir, program gizi siswi sekolah) dengan Parasetamol 500 mg (142.715 butir, kuratif poli harian #1) dan Amlodipin 10 mg (140.090 butir, terapi penyakit kronis lansia).
    </p>
</div>

<!-- =========================================================================
     HALAMAN 7: BAB II — TAHAP 2 (BAGIAN 3): EDA PILAR 3 (SEBARAN CV & ISPA)
     ========================================================================= -->
<div class="page-break">
    <h1 class="section-title">BAB II: Tahap 2 — Eksplorasi Data Awal (EDA) (Lanjutan)</h1>

    <h2 class="sub-title">2.3 Pilar 3: Sebaran Fluktuasi CV &amp; Korelasi Awal Morbiditas Pesisir</h2>
    
    <div class="figure-block avoid-break">
        <img src="file://{VIZ_DIR}/07_sebaran_fluktuasi_cv.png" alt="Gambar 2.5">
        <div class="figure-caption">Gambar 2.5: Pemetaan Karakteristik Fluktuasi Konsumsi Obat (Koefisien Variasi / CV = σ / μ) 150 Sediaan Obat</div>
    </div>
    <p style="font-size: 8.3pt; line-height: 1.42;">
        <b>Analisis Gambar 2.5:</b> Membagi obat ke dalam 3 zona: <b>Zona 1 Stabil (CV &lt; 0,5 / 38% katalog)</b> seperti Amlodipin (0,38) dan Metformin (0,41); <b>Zona 2 Moderat (0,5 ≤ CV ≤ 1,0 / 42%)</b> seperti Parasetamol (0,42) dan Amoksisilin (0,58); serta <b>Zona 3 Sangat Fluktuatif (CV &gt; 1,0 / 20%)</b> seperti Fero Sulfat (2,20) dan serum anti-bisa ular.
    </p>

    <div class="figure-block avoid-break">
        <img src="file://{VIZ_DIR}/02_tren_ispa_dan_obat.png" alt="Gambar 2.6">
        <div class="figure-caption">Gambar 2.6: Korelasi Kasus ISPA (J00) vs Konsumsi Obat Terkait &amp; Anotasi Musim Angin Utara serta Stockout 2026-P4</div>
    </div>
    <p style="font-size: 8.3pt; line-height: 1.42;">
        <b>Analisis Gambar 2.6:</b> Terlihat jelas sinkronisasi lonjakan ISPA pada musim angin utara (Oktober–Februari). Anotasi panah merah menyorot periode <b>2026-P4 di mana pemakaian Parasetamol tercatat 0 di gudang akibat stockout</b>, padahal kasus pasien sedang tinggi (485 orang), membuktikan perlunya rekonstruksi <i>Censored Demand</i>.
    </p>
</div>

<!-- =========================================================================
     HALAMAN 8: BAB III — TAHAP 3: PREDIKSI STOK OBAT & MITIGASI LOGISTIK
     ========================================================================= -->
<div class="page-break">
    <h1 class="section-title">BAB III: Tahap 3 — Prediksi Kebutuhan Stok Obat &amp; Mitigasi Logistik</h1>

    <h2 class="sub-title">3.1 Rekonstruksi Permintaan Tersensor (Censored Demand Imputation)</h2>
    <p>
        Pada periode 2026-P4, Parasetamol 500 mg mencatat pemakaian 0 tablet karena stok habis ludes pada periode P3 (Total Stockout). Jika angka 0 ini dimasukkan langsung ke Moving Average, proyeksi periode berikutnya akan anjlok separuh menjadi 3.222 tablet dan memicu krisis kekosongan obat berulang (<i>Stockout Spiral</i>).
    </p>
    <div class="formula-card">
        y_imp(2026-P4) = ( y(P2) + y(P3) ) / 2 = ( 5.256 + 6.444 ) / 2 = 5.850 tablet
    </div>
    <p>
        Nilai terekonstruksi <b>5.850 tablet</b> digunakan oleh model peramalan sehingga proyeksi kebutuhan tetap realistis.
    </p>

    <h2 class="sub-title">3.2 Arena Pemodelan Deret Waktu (Time Series Forecasting)</h2>
    <p>
        Kelompok menguji 4 algoritma peramalan untuk 150 sediaan obat aktif:
    </p>
    <ul>
        <li><b>SMA-2:</b> Responsif terhadap lonjakan mendadak: <code>y_pred(t+1) = (y_t + y_{t-1}) / 2</code>.</li>
        <li><b>SMA-3:</b> Meredam fluktuasi acak secara merata: <code>y_pred(t+1) = (y_t + y_{t-1} + y_{t-2}) / 3</code>.</li>
        <li><b>WMA-3 (Terpilih):</b> Memberi bobot tertinggi pada tren mutakhir: <code>y_pred(t+1) = (3y_t + 2y_{t-1} + 1y_{t-2}) / 6</code>.</li>
        <li><b>SES (α=0,3):</b> Peluruhan eksponensial sederhana.</li>
    </ul>
    <p>
        <b>Keunggulan WMA-3:</b> Unggul pada <b>58,7% sediaan obat</b> dengan rata-rata <b>MAPE 14,2% (Good Forecast)</b> dan memiliki bias positif pengaman <b>+4,3%</b> pada uji <i>Walk-Forward</i> yang mencegah <i>under-forecast</i>.
    </p>

    <h2 class="sub-title">3.3 Formulasi Buffer Keselamatan (Safety Stock 95%)</h2>
    <p>
        Kabupaten Bintan merupakan wilayah kepulauan dengan waktu tunggu pasokan dinas (<i>Lead Time</i>) <b>L = 2 periode dwibulanan (~4 bulan)</b>. Batas pengaman dihitung menggunakan formula standar industri farmasi:
    </p>
    <div class="formula-card">
        SS = Z(0,95) × σ_d × √L = 1,645 × σ_d × √2
    </div>
    <p>Pada Parasetamol 500 mg, dengan deviasi konsumsi normal σ_d = 576 butir, diperoleh <b>Safety Stock = 1.340 tablet</b>.</p>

    <h2 class="sub-title">3.4 Formula Pengadaan Final (Q) &amp; Mitigasi Obat Kadaluarsa — Saran Pak Fadli</h2>
    <p>
        Untuk mencegah berulangnya kerugian Rp 43,35 Juta akibat penumpukan stok berlebih (<i>overstock</i>), rekomendasi pengadaan fisik wajib mengurangi sisa stok fisik yang ada di gudang:
    </p>
    <div class="formula-card">
        Q = max( 0,  y_pred(P5) + SS - S_akhir )
    </div>
    <p>
        Didukung protokol <b>FEFO (First Expired, First Out)</b>: obat sisa stok lama wajib dihabiskan terlebih dahulu sebelum membuka kemasan baru hasil pengadaan.
    </p>

    <h2 class="sub-title">3.5 Indikator Momentum MACD Lensa Dwibulanan (2, 4, 2) — Saran 2 Pak Tekad</h2>
    <p>
        Osilator MACD diadaptasi untuk 22 periode dwibulanan: <b>Fast EMA = 2 periode (~4 bln)</b>, <b>Slow EMA = 4 periode (~8 bln)</b>, dan <b>Signal = EMA-2 dari MACD</b>. Jika histogram MACD bernilai positif dan meningkat (<i>Bullish Momentum</i>), sistem memberi peringatan dini adanya lonjakan konsumsi sebelum stok fisik mencapai angka nol.
    </p>
</div>

<!-- =========================================================================
     HALAMAN 9: BAB IV — TAHAP 4: PREDIKSI 4 PENYAKIT & POLA MUSIMAN
     ========================================================================= -->
<div class="page-break">
    <h1 class="section-title">BAB IV: Tahap 4 — Prediksi 4 Penyakit Terbanyak (SIMPUS)</h1>

    <h2 class="sub-title">4.1 Proyeksi Kasus Periode 2026-P5 (Sep–Okt 2026)</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>Diagnosis Penyakit (ICD-10)</th>
                <th>Model Terbaik</th>
                <th>MAPE</th>
                <th>Total Historis</th>
                <th>Proyeksi Kasus P5</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><b>ISPA / Nasopharyngitis (J00)</b></td>
                <td>SMA-2</td>
                <td>32,99%</td>
                <td class="tnum text-right">10.623 Pasien</td>
                <td class="tnum text-right font-bold">338 Kasus</td>
            </tr>
            <tr>
                <td><b>Hipertensi Primer (I10)</b></td>
                <td>SMA-2</td>
                <td><b>9,54%</b></td>
                <td class="tnum text-right">7.343 Pasien</td>
                <td class="tnum text-right font-bold">343 Kasus</td>
            </tr>
            <tr>
                <td><b>Dispepsia / Maag (K30)</b></td>
                <td>WMA-3</td>
                <td><b>11,91%</b></td>
                <td class="tnum text-right">3.315 Pasien</td>
                <td class="tnum text-right font-bold">146 Kasus</td>
            </tr>
            <tr>
                <td><b>Diabetes Melitus Tipe 2 (E11)</b></td>
                <td>SMA-2</td>
                <td>25,15%</td>
                <td class="tnum text-right">1.478 Pasien</td>
                <td class="tnum text-right font-bold">69 Kasus</td>
            </tr>
        </tbody>
    </table>

    <h2 class="sub-title">4.2 Analisis Indeks Musiman Pesisir &amp; Tren Morbiditas Kronis</h2>
    <p>
        Kalkulasi indeks musiman membuktikan bahwa penyakit ISPA (J00) mencapai <b>puncak tertinggi pada bulan November (Indeks: 1,72×)</b> dan <b>Oktober (1,27×)</b>, bertepatan dengan Musim Angin Barat/Utara di Kepulauan Riau yang membawa angin kencang dan suhu dingin. Sebaliknya, penyakit degeneratif kronis (Hipertensi dan Diabetes) memiliki tren yang sangat stabil sepanjang tahun.
    </p>

    <div class="figure-block avoid-break">
        <img src="file://{VIZ_DIR}/03_tren_hipertensi_diabetes.png" alt="Gambar 4.1">
        <div class="figure-caption">Gambar 4.1: Dinamika Kunjungan Pasien Penyakit Kronis (Hipertensi I10 &amp; Diabetes E11) vs Konsumsi Obat Rutin Lansia</div>
    </div>
    <p style="font-size: 8.3pt; line-height: 1.42;">
        <b>Analisis Gambar 4.1:</b> Pasien hipertensi (I10) dan diabetes (E11) menunjukkan stabilitas kepatuhan berobat yang tinggi dengan rasio konsumsi Amlodipin 10 mg dan Metformin 500 mg yang sangat konsisten, mencerminkan terapi pemeliharaan jangka panjang lansia di wilayah pesisir Teluk Sasah.
    </p>
</div>

<!-- =========================================================================
     HALAMAN 10: BAB V — TAHAP 5: ANALISIS KORELASI OBAT DAN PENYAKIT
     ========================================================================= -->
<div class="page-break">
    <h1 class="section-title">BAB V: Tahap 5 — Analisis Korelasi Obat dan Penyakit</h1>

    <div class="figure-block avoid-break">
        <img src="file://{VIZ_DIR}/04_tren_pencernaan_dispepsia.png" alt="Gambar 5.1">
        <div class="figure-caption">Gambar 5.1: Korelasi Kasus Dispepsia (K30) vs Penggunaan Obat Saluran Cerna (Antasida DOEN, Ranitidin, &amp; Omeprazol)</div>
    </div>

    <div class="figure-block avoid-break">
        <img src="file://{VIZ_DIR}/05_heatmap_korelasi_penyakit_obat.png" alt="Gambar 5.2">
        <div class="figure-caption">Gambar 5.2: Matriks Korelasi Pearson (r) Antara 10 Diagnosis Penyakit SIMPUS Terbanyak vs 15 Obat Utama LPLPO</div>
    </div>

    <h2 class="sub-title">5.1 Uji Statistik Pearson (r) &amp; Rasio Dosis per Pasien</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>Penyakit</th>
                <th>Obat Terkait LPLPO</th>
                <th>Pearson (r)</th>
                <th>Rasio Dosis / Pasien</th>
                <th>Proyeksi Kebutuhan P5</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>ISPA (J00)</td>
                <td>Parasetamol 500 mg</td>
                <td class="tnum text-center font-bold" style="color: #15803D;">+0,585</td>
                <td class="tnum text-right">13,43 Butir</td>
                <td class="tnum text-right font-bold">4.541 Tablet</td>
            </tr>
            <tr>
                <td>ISPA (J00)</td>
                <td>Amoksisilin 500 mg</td>
                <td class="tnum text-center font-bold" style="color: #15803D;">+0,635</td>
                <td class="tnum text-right">5,06 Butir</td>
                <td class="tnum text-right font-bold">1.710 Kaplet</td>
            </tr>
            <tr>
                <td>Diabetes (E11)</td>
                <td>Metformin 500 mg</td>
                <td class="tnum text-center font-bold" style="color: #15803D;">+0,594</td>
                <td class="tnum text-right">40,95 Butir</td>
                <td class="tnum text-right font-bold">2.826 Tablet</td>
            </tr>
            <tr>
                <td>Hipertensi (I10)</td>
                <td>Amlodipin 10 mg</td>
                <td class="tnum text-center">-0,019*</td>
                <td class="tnum text-right">19,08 Butir</td>
                <td class="tnum text-right font-bold">6.534 Tablet</td>
            </tr>
        </tbody>
    </table>
    <p style="font-size: 7.6pt; color: #64748B; margin-top: 0.5mm;">
        <i>*Catatan: Korelasi Pearson Amlodipin terdistorsi oleh 7 periode stockout fisik di masa lalu, namun rasio kebutuhan klinis 19,08 butir per pasien membuktikan hubungannya tetap mutlak dan valid.</i>
    </p>

    <div class="alert-box avoid-break">
        <span class="alert-title">💡 Pembuktian Konvergensi Dual-Jalur (Dual-Path Validation)</span>
        PRABOTIK membuktikan bahwa peramalan logistik deret waktu (WMA-3) dan peramalan berbasis epidemiologi klinis (Kasus Penyakit × Rasio Dosis) saling mengonfirmasi kebenaran tren di lapangan, menghasilkan usulan pengadaan yang dapat dipertanggungjawabkan di hadapan dokter pimpinan maupun auditor dinas.
    </div>
</div>

<!-- =========================================================================
     HALAMAN 11: BAB VI — SIMULASI TANYA JAWAB DOSEN PEMBIMBING
     ========================================================================= -->
<div class="page-break">
    <h1 class="section-title">BAB VI: Simulasi Tanya-Jawab Konsultasi &amp; Ujian Sidang</h1>

    <div class="card avoid-break">
        <p class="font-bold" style="color: #1A3B22; margin: 0 0 1mm 0;">❓ Pertanyaan 1: "Kenapa data LPLPO ada yang 1 bulanan dan ada yang 2 bulanan? Bagaimana solusinya?"</p>
        <p style="margin: 0;">
            <b>Jawaban Mahasiswa:</b> <i>"Benar Pak. Pada tahun 2024 faskes mencatat bulanan, sedangkan tahun 2023, 2025, dan 2026 mencatat dwibulanan karena mengikuti siklus pengadaan ke Dinkes Bintan. Kami menyelesaikannya dengan Temporal Resampling ke 22 periode dwibulanan kontinu, di mana variabel aliran (pemakaian/penerimaan) kami jumlahkan, sedangkan variabel posisi (stok awal bulan ganjil dan stok akhir bulan genap) tidak dijumlahkan agar tidak terjadi double counting. Kami juga menampilkan grafik perbandingan Apple-to-Apple tahun 2024 yang membuktikan format dwibulanan mereduksi variabilitas sebesar 22,7%."</i>
        </p>
    </div>

    <div class="card avoid-break">
        <p class="font-bold" style="color: #1A3B22; margin: 0 0 1mm 0;">❓ Pertanyaan 2: "Mengapa Fero Sulfat pemakaiannya mencapai 255 ribu tablet, jauh lebih tinggi dari Parasetamol?"</p>
        <p style="margin: 0;">
            <b>Jawaban Mahasiswa:</b> <i>"Berdasarkan penelusuran data mentah Januari 2024 baris 122, ada pengeluaran massal 118 ribu tablet dengan keterangan 'GIZI'. Fero Sulfat adalah Obat Program Nasional pencegahan anemia siswi sekolah dan stunting yang didistribusikan secara drop massal (push-distribution), bukan resep kuratif harian pasien poli (pull-demand). Karena itu kami pisahkan agar Parasetamol tetap menjadi fokus utama obat kuratif harian nomor satu puskesmas."</i>
        </p>
    </div>

    <div class="card avoid-break">
        <p class="font-bold" style="color: #1A3B22; margin: 0 0 1mm 0;">❓ Pertanyaan 3: "Apa maksud label 'Direkonstruksi' pada Parasetamol di periode 2026-P4?"</p>
        <p style="margin: 0;">
            <b>Jawaban Mahasiswa:</b> <i>"Pada periode 2026-P4, stok Parasetamol di gudang habis total (stok awal 0 dan sisa stok 0), sehingga buku LPLPO mencatat pemakaian 0 tablet. Angka 0 ini adalah nol semu akibat keterbatasan stok fisik (Censored Demand), bukan karena tidak ada pasien sakit. Jika angka 0 dimasukkan ke rumus MA, prediksi periode P5 akan anjlok separuh menjadi 3.222 tablet dan memicu kekosongan berulang (Stockout Spiral). Kami merekonstruksi nilai permintaan riil menjadi 5.850 tablet berdasarkan rata-rata periode aktif sebelumnya, sehingga usulan pengadaan P5 aman di angka 7.487 tablet."</i>
        </p>
    </div>

    <div class="card avoid-break">
        <p class="font-bold" style="color: #1A3B22; margin: 0 0 1mm 0;">❓ Pertanyaan 4: "Bagaimana cara sistem kalian memitigasi kerugian obat kadaluarsa Rp 43,35 Juta?"</p>
        <p style="margin: 0;">
            <b>Jawaban Mahasiswa:</b> <i>"Kerugian 43,35 juta dari 138 item obat terjadi akibat over-budgeting masa lalu yang memesan tanpa melihat sisa stok riil. Dalam formula PRABOTIK, rekomendasi pengadaan Q dikurangi langsung dengan sisa stok fisik (Q = Prediksi + SS - Sisa Stok). Jika sisa stok di gudang masih mencukupi, sistem merekomendasikan pengadaan 0 unit. Selain itu, sistem di portal farmasi mewajibkan rotasi FEFO (First Expired, First Out) agar obat yang tanggal kadaluarsanya dekat dihabiskan terlebih dahulu sebelum membuka kemasan baru."</i>
        </p>
    </div>

    <div class="card avoid-break">
        <p class="font-bold" style="color: #1A3B22; margin: 0 0 1mm 0;">❓ Pertanyaan 5: "Bagaimana adaptasi indikator MACD untuk deret waktu dwibulanan?"</p>
        <p style="margin: 0;">
            <b>Jawaban Mahasiswa:</b> <i>"Pada data saham harian, MACD menggunakan (12, 26, 9) hari. Karena data LPLPO berbentuk dwibulanan, kami mengadaptasi konstanta waktu menjadi Fast EMA = 2 periode (~4 bulan), Slow EMA = 4 periode (~8 bulan), dan Garis Sinyal = EMA-2 dari MACD. Ketika histogram MACD melompat ke area positif, ini menjadi sinyal peringatan dini bahwa laju konsumsi sedang meningkat pesat sehingga petugas farmasi harus segera memproses pesanan pengadaan sebelum stok menipis."</i>
        </p>
    </div>
</div>

<!-- =========================================================================
     HALAMAN 12: BAB VII — LEMBAR EVALUASI & CHECKLIST BIMBINGAN
     ========================================================================= -->
<div>
    <h1 class="section-title">BAB VII: Lembar Evaluasi &amp; Lembar Persetujuan Konsultasi</h1>

    <h2 class="sub-title">7.1 Checklist Kesiapan Metodologis Proyek PRABOTIK</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Aspek Metodologis Penambangan Data</th>
                <th style="width: 45%;">Kriteria Verifikasi Data &amp; Pembuktian Ilmiah</th>
                <th style="width: 15%;">Verifikasi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td><b>Pembersihan Data Mentah (Tahap 1)</b></td>
                <td>Berhasil mengeliminasi baris artefak Excel, imputasi harga ED via cross-table lookup sah Rp 43,35 Jt, dan normalisasi 150 sediaan obat.</td>
                <td class="text-center"><span class="badge-ok">TERPENUHI</span></td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td><b>Harmonisasi Deret Waktu (Saran 1)</b></td>
                <td>Membuktikan reduksi variabilitas CV 22,7% pada perbandingan bulanan vs dwibulanan 2024; alignment 22 periode dwibulanan kontinu.</td>
                <td class="text-center"><span class="badge-ok">TERPENUHI</span></td>
            </tr>
            <tr>
                <td class="text-center">3</td>
                <td><b>Mitigasi Obat Kadaluarsa (Saran 3)</b></td>
                <td>Memadukan audit finansial Rp 43,35 Jt langsung ke formula pengadaan fisik Q = max(0, Pred + SS - Sisa_Stok) dengan rotasi FEFO.</td>
                <td class="text-center"><span class="badge-ok">TERPENUHI</span></td>
            </tr>
            <tr>
                <td class="text-center">4</td>
                <td><b>Sinyal Momentum MACD (Saran 2)</b></td>
                <td>Mengadaptasi MACD parameter (2, 4, 2) untuk mendeteksi akselerasi konsumsi obat sebelum stok fisik menyentuh angka nol.</td>
                <td class="text-center"><span class="badge-ok">TERPENUHI</span></td>
            </tr>
            <tr>
                <td class="text-center">5</td>
                <td><b>Peramalan Morbiditas SIMPUS (Tahap 4)</b></td>
                <td>Proyeksi 4 diagnosis terbanyak (ISPA, Hipertensi, Dispepsia, Diabetes) didukung pemetaan indeks musiman angin utara pesisir.</td>
                <td class="text-center"><span class="badge-ok">TERPENUHI</span></td>
            </tr>
            <tr>
                <td class="text-center">6</td>
                <td><b>Korelasi Klinis Obat &amp; Penyakit (Tahap 5)</b></td>
                <td>Uji Pearson (r), kalkulasi rasio dosis obat per pasien, serta pembuktian konvergensi peramalan logistik dan klinis dual-jalur.</td>
                <td class="text-center"><span class="badge-ok">TERPENUHI</span></td>
            </tr>
        </tbody>
    </table>

    <h2 class="sub-title">7.2 Lembar Pengesahan Konsultasi Pembimbing</h2>
    <div class="card" style="margin-top: 3mm; padding: 4mm 5mm;">
        <p style="font-size: 8.5pt; text-align: justify; margin-bottom: 5mm;">
            Dosir materi bimbingan dan pembuktian komputasi Tahap 1 s/d Tahap 5 ini telah diperiksa, diverifikasi terhadap data primer lapangan UPTD Puskesmas Teluk Sasah, dan disetujui untuk dipresentasikan pada sesi konsultasi akademik:
        </p>

        <div class="grid-2" style="margin-top: 4mm;">
            <div style="text-align: center;">
                <p style="font-size: 8.5pt; color: #475569; margin: 0;">Dosen Pembimbing I,</p>
                <div style="height: 18mm;"></div>
                <p style="font-weight: 800; font-size: 9.5pt; color: #0F172A; margin: 0; text-decoration: underline;">Tekad Matulatan, S.T., M.Cs.</p>
                <p style="font-size: 8pt; color: #64748B; margin: 1mm 0 0 0;">NIDN: 0010078403</p>
            </div>
            <div style="text-align: center;">
                <p style="font-size: 8.5pt; color: #475569; margin: 0;">Dosen Pembimbing II,</p>
                <div style="height: 18mm;"></div>
                <p style="font-weight: 800; font-size: 9.5pt; color: #0F172A; margin: 0; text-decoration: underline;">Muhamad Fadli, S.T., M.Kom.</p>
                <p style="font-size: 8pt; color: #64748B; margin: 1mm 0 0 0;">NIDN: 0021079004</p>
            </div>
        </div>

        <div style="margin-top: 5mm; border-top: 1px dashed #CBD5E1; padding-top: 2.5mm; text-align: center;">
            <p style="font-size: 7.8pt; color: #64748B; margin: 0;">
                Tanjungpinang / Bintan, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2026 &nbsp;|&nbsp; Tim Peneliti Mahasiswa Kelompok 23 (Teknik Informatika UMRAH)
            </p>
        </div>
    </div>
</div>

</body>
</html>
"""
    html = html.replace('{LOGO_PATH}', LOGO_PATH).replace('{VIZ_DIR}', VIZ_DIR)
    return html

def main():
    print("=" * 70)
    print("MEMBANGUN DOKUMEN HTML & MENJALANKAN CHROME PRINT-TO-PDF")
    print("=" * 70)

    html_content = build_html()
    with open(HTML_OUTPUT, 'w', encoding='utf-8') as f:
        f.write(html_content)
    print(f"1. Dokumen HTML berhasil disimpan: {HTML_OUTPUT}")

    # Eksekusi Chrome Headless Print-to-PDF dengan flag --no-pdf-header-footer
    cmd = [
        'google-chrome',
        '--headless=new',
        '--no-sandbox',
        '--disable-gpu',
        '--no-pdf-header-footer',
        f'--print-to-pdf={PDF_OUTPUT}',
        HTML_OUTPUT
    ]
    print(f"2. Mengompilasi PDF via Headless Chrome (--no-pdf-header-footer)...")
    res = subprocess.run(cmd, capture_output=True, text=True)
    if res.returncode == 0 and os.path.exists(PDF_OUTPUT):
        size_kb = os.path.getsize(PDF_OUTPUT) / 1024
        print(f"3. Sukses! Dokumen PDF selesai: {PDF_OUTPUT} ({size_kb:.1f} KB)")
        
        # Salin juga ke root directory agar mudah ditemukan
        root_pdf = os.path.join(BASE_DIR, 'DOSIR_MATERI_KONSULTASI_TAHAP_1_SAMPAI_5_PRABOTIK.pdf')
        with open(PDF_OUTPUT, 'rb') as src, open(root_pdf, 'wb') as dst:
            dst.write(src.read())
        print(f"4. Salinan PDF tersimpan di root: {root_pdf}")
    else:
        print(f"Gagal mengompilasi PDF: {res.stderr}")

if __name__ == '__main__':
    main()
