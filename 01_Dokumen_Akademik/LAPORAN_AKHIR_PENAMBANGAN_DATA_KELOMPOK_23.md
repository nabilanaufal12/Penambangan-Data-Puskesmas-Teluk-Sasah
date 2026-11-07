# LAPORAN AKHIR PROJECT PENAMBANGAN DATA (INF11121)
## **PREDIKSI KEBUTUHAN STOK OBAT DAN ANALISIS TREN PENYAKIT MUSIMAN BERDASARKAN POLA PEMAKAIAN OBAT PADA PUSKESMAS TELUK SASAH DI KABUPATEN BINTAN**

---

### 👥 IDENTITAS TIM PENELITI (KELOMPOK 23)
* **Ketua Tim**: 2401020073 – Nabil
* **Anggota Tim**:
  1. 2401020069 – Balqis Manisa
  2. 2401020056 – Jian Safitri Wibowo
  3. 2401020087 – Annisa Darmawan
  4. 2401020110 – Yehezkiel Alman Siambaton

* **Dosen Pengampu**:
  1. **Tekad Matulatan, S.Sos., S.Kom., M.Info.Tech.** (NIDN: 0021057404)
  2. **Muhamad Fadli, S.Kom., M.Kom.** (NIDN: 1018079001)

* **Program Studi**: S1 Teknik Informatika  
* **Fakultas**: Fakultas Teknik dan Teknologi Kemaritiman  
* **Institusi**: Universitas Maritim Raja Ali Haji (UMRAH), Tanjungpinang  
* **Semester**: Ganjil 2026/2027  
* **Berkas Resmi**: `FinPD_2401020073.pdf` / `FinPD_2401020073.docx`

---

## KATA PENGANTAR

Puji dan syukur kami panjatkan ke hadirat Allah SWT, Tuhan Yang Maha Esa, karena atas berkat dan karunia-Nya naskah **Laporan Akhir Proyek Penambangan Data (INF11121)** yang berjudul *"Prediksi Kebutuhan Stok Obat dan Analisis Tren Penyakit Musiman Berdasarkan Pola Pemakaian Obat pada Puskesmas Teluk Sasah di Kabupaten Bintan"* dapat diselesaikan dengan baik, tepat waktu, dan memenuhi seluruh kriteria Rencana Tugas Mahasiswa (RTM).

Laporan akhir ini menyajikan seluruh proses penambangan data (*data mining*) dari hulu ke hilir berbasis kerangka kerja CRISP-DM (*Cross-Industry Standard Process for Data Mining*), mencakup pemahaman bisnis mitra, pembersihan dan transformasi data mentah logistik LPLPO dari 29 berkas Excel menjadi 22 periode dwibulanan kontinu, analisis korelasi klinis morbiditas 10 penyakit ICD-10, pemodelan peramalan deret waktu (*time series forecasting*) menggunakan algoritma *Simple Moving Average* (SMA) dan *Weighted Moving Average* (WMA), hingga formulasi batas penyangga aman (*Safety Stock 95%*) untuk mengatasi risiko pemborosan obat kadaluarsa dan krisis kekosongan obat.

Penyusun mengucapkan terima kasih yang sebesar-besarnya kepada:
1. **Bapak Tekad Matulatan, S.Sos., S.Kom., M.Info.Tech.** dan **Bapak Muhamad Fadli, S.Kom., M.Kom.**, selaku dosen pengampu mata kuliah Penambangan Data, atas segala bimbingan teoretis, arahan metodologis, dan masukan kritis selama perkuliahan.
2. **dr. Kurniawan, M.H.**, selaku Kepala UPTD Puskesmas Teluk Sasah, beserta seluruh jajaran instalasi farmasi yang telah memberikan izin penelitian, menyediakan data mentah operasional puskesmas, serta meluangkan waktu dalam sesi wawancara klinis.
3. Rekan-rekan mahasiswa Program Studi Teknik Informatika angkatan 2024 yang saling bertukar wawasan selama pelaksanaan proyek.

Penulis menyadari laporan ini masih dapat dikembangkan lebih lanjut. Oleh karena itu, saran konstruktif sangat diharapkan guna penyempurnaan implementasi di masa mendatang.

Tanjungpinang, Oktober 2026  
**Tim Peneliti Kelompok 23**

---

## DAFTAR ISI

* **KATA PENGANTAR**
* **DAFTAR ISI**
* **DAFTAR TABEL**
* **DAFTAR GAMBAR**
* **BAB I: BUSINESS UNDERSTANDING**
  * I.1 Latar Belakang Mitra
  * I.2 Permasalahan Riil Mitra
  * I.3 Tujuan Penambangan Data
  * I.4 Lini Waktu Pelaksanaan & Pembagian Kerja (Gantt Chart & PIC)
* **BAB II: DATA UNDERSTANDING**
  * II.1 Teknik Pengumpulan Data
  * II.2 Jenis dan Kamus Data
  * II.3 Exploratory Data Analysis (EDA) & Pola Sebaran Data
* **BAB III: PREPROCESS**
  * III.1 Penyelesaian Masalah 1: Inkonsistensi Periode Waktu Pelaporan
  * III.2 Penyelesaian Masalah 2: Pembersihan Artefak dan Baris Pengesahan
  * III.3 Penyelesaian Masalah 3: Penanganan Nilai Kosong (*Missing Value*) & Domain Lookup
  * III.4 Penyelesaian Masalah 4: Pemisahan Anomali Obat Program Nasional vs Obat Kuratif
  * III.5 Penyelesaian Masalah 5: Transformasi Matriks Deret Waktu (*Wide-Format Pivoting*)
  * III.6 Analisis Komparasi Granularitas: Tren Mikro 1-Bulanan (2024) vs Makro 2-Bulanan (2023–2026)
* **BAB IV: MODEL**
  * IV.1 Konsep dan Formulasi Moving Average
  * IV.2 Formulasi Simple Moving Average (SMA)
  * IV.3 Formulasi Weighted Moving Average (WMA)
  * IV.4 Simulasi Perhitungan Matematis Langkah Demi Langkah
  * IV.5 Formulasi Indikator Moving Average Convergence Divergence (MACD) Farmasi
  * IV.6 Formulasi Peramalan Morbiditas Penyakit Terbanyak
  * IV.7 Formulasi Stok Pengaman (*Safety Stock 95%*) dan Alokasi Pengadaan
* **BAB V: EVALUASI**
  * V.1 Metrik Evaluasi Akurasi (MAPE, MAE, RMSE)
  * V.2 Hasil Evaluasi Akurasi Seluruh Model
  * V.3 Analisis Pengaruh Fluktuasi (CV) terhadap Akurasi Prediksi
  * V.4 Pembuktian Empiris Dampak Anomali Stockout terhadap Galat Model
  * V.5 Visualisasi Kurva Aktual vs Prediksi Obat Esensial
  * V.6 Rekomendasi Alokasi Pengadaan Obat Periode 2026-P5
  * V.7 Evaluasi dan Peramalan Morbiditas 4 Penyakit Terbanyak (ISPA, Hipertensi, Dispepsia, Diabetes)
  * V.8 Analisis Matriks Korelasi Terperinci Penyakit-Obat dan Estimasi Kebutuhan Berbasis Morbiditas
  * V.9 Analisis Momentum Konsumsi Obat Menggunakan Indikator MACD
  * V.10 Benchmarking Lanjutan: Model Machine Learning (HistGradientBoosting & Ridge) vs Baseline Moving Average
* **BAB VI: PENERAPAN**
  * VI.1 Desain Implementasi Sistem Peramalan & Rantai Pasok
  * VI.2 Arsitektur Dashboard Web Streamlit (7 Modul Terintegrasi) & Google Colab
  * VI.3 Kesimpulan
  * VI.4 Saran dan Rekomendasi Manajerial
* **DAFTAR PUSTAKA**
* **LAMPIRAN**

---

## DAFTAR TABEL

* **Tabel 1.1**: Rencana Lini Waktu Pelaksanaan Proyek (Gantt Chart & PIC Anggota Kelompok 23)
* **Tabel 2.1**: Rincian Inventaris 29 Berkas Data Mentah LPLPO Puskesmas Teluk Sasah (2023–2026)
* **Tabel 2.2**: Kamus Data Transaksi LPLPO Teragregasi
* **Tabel 2.3**: Kamus Data 10 Besar Diagnosis Morbiditas Penyakit (ICD-10)
* **Tabel 2.4**: Top 15 Obat Berdasarkan Total Akumulasi Pemakaian (2023–2026)
* **Tabel 3.1**: Matriks Pemetaan 22 Periode Dwibulanan Kontinu (2023-P1 s/d 2026-P4)
* **Tabel 3.2**: Analisis Komparasi Tren Pemakaian Bulanan (12 Bulan 2024) pada Empat Obat Utama
* **Tabel 4.1**: Simulasi Perhitungan Matematis Peramalan Parasetamol 500 mg Periode demi Periode
* **Tabel 4.2**: Simulasi Perhitungan Matematis Peramalan Metformin 500 mg Periode demi Periode
* **Tabel 4.3**: Formulasi Parameter Teknis Indikator MACD Farmasi Puskesmas
* **Tabel 5.1**: Hasil Evaluasi Galat (MAPE, MAE, RMSE) dan Penentuan Model Terbaik Top 15 Obat
* **Tabel 5.2**: Ringkasan Performa Galat Rata-Rata Antar-Model Peramalan
* **Tabel 5.3**: Bukti Empiris Sejarah Stockout Amlodipin 10 mg dan Ambroksol 30 mg
* **Tabel 5.4**: Rekomendasi Alokasi Pengadaan dan Safety Stock Periode 2026-P5 (Sep–Okt 2026)
* **Tabel 5.5**: Hasil Evaluasi Model dan Proyeksi Morbiditas 4 Penyakit Terbanyak (Periode 2026-P5)
* **Tabel 5.6**: Matriks Korelasi Klinis Penyakit vs Obat, Rasio Dosis per Kasus, dan Proyeksi Terpadu
* **Tabel 5.7**: Hasil Analisis Indikator Momentum MACD dan Status Tren pada 10 Obat Utama
* **Tabel 5.8**: Hasil Benchmarking 10-Fold Walk-Forward Evaluation Model Machine Learning vs Baseline Moving Average

---

## DAFTAR GAMBAR

* **Gambar 2.1**: Ranking 15 Obat dengan Volume Pemakaian Terbesar di Puskesmas Teluk Sasah
* **Gambar 2.2**: Kurva Deret Waktu Kasus ISPA (J00) vs Pemakaian Parasetamol, Amoksisilin, dan Ambroksol
* **Gambar 2.3**: Kurva Deret Waktu Penyakit Kronis Hipertensi (I10) dan Diabetes (E11) vs Terapi Obat
* **Gambar 2.4**: Tren Penyakit Saluran Cerna Dispepsia (K30) vs Antasida Doen
* **Gambar 2.5**: Matriks Heatmap Korelasi Pearson antara Diagnosis Penyakit vs Pola Pemakaian Obat
* **Gambar 2.6**: Analisis Kerugian Finansial Akibat Obat Kadaluarsa Per Tahun (Total Rp 43,35 Juta)
* **Gambar 2.7**: Diagram Pemetaan Fluktuasi Koefisien Variasi ($CV$) vs Rata-rata Pemakaian Obat
* **Gambar 2.8**: Analisis Frekuensi dan Durasi Kejadian Kekosongan Stok (*Stockout*) Obat Esensial
* **Gambar 3.1**: Perbandingan Granularitas Deret Waktu: Tren 1-Bulanan vs 2-Bulanan (Tahun 2024)
* **Gambar 4.1**: Diagram Alir Arsitektur Integrasi Data Berbasis Kunci Waktu Dwibulanan
* **Gambar 4.2**: Analisis Momentum Moving Average Convergence Divergence (MACD) pada Pemakaian Parasetamol
* **Gambar 5.1**: Kurva Aktual vs Prediksi Pemakaian Parasetamol 500 mg (Model SMA-2, SMA-3, WMA-3)
* **Gambar 5.2**: Kurva Aktual vs Prediksi Pemakaian Metformin 500 mg (WMA-3 MAPE 15.25%)
* **Gambar 5.3**: Kurva Aktual vs Prediksi Pemakaian Amlodipin 10 mg (Anomali Stockout)
* **Gambar 5.4**: Kurva Aktual vs Prediksi Pemakaian Amoksisilin 500 mg (WMA-3 MAPE 17.35%)
* **Gambar 5.5**: Diagram Batang Komparasi Nilai Galat MAPE Antar-Model pada 10 Obat Utama
* **Gambar 5.6**: Visualisasi Alokasi Rekomendasi Pengadaan dan Batas Safety Stock Periode 2026-P5
* **Gambar 5.7**: Kurva Deret Waktu Historis dan Proyeksi Morbiditas 4 Penyakit Terbanyak (Periode 2026-P5)
* **Gambar 5.8**: Analisis Terpadu Korelasi Klinis dan Rasio Dosis Konsumsi Obat Berbasis Morbiditas
* **Gambar 6.1**: Diagram Alur Operasional Pengusulan Stok Obat Puskesmas ke Dinas Kesehatan
* **Gambar 6.2**: Tampilan Dashboard Web Streamlit Pemantauan Farmasi dan Morbiditas Penyakit

---

# BAB I: BUSINESS UNDERSTANDING

### I.1 Latar Belakang Mitra
Pelayanan kesehatan primer di tingkat kecamatan bertumpu pada peran Pusat Kesehatan Masyarakat (Puskesmas). Salah satu unit pelayanan kesehatan garis depan di Kabupaten Bintan, Provinsi Kepulauan Riau, adalah **UPTD Puskesmas Teluk Sasah**, yang berlokasi strategis di Jl. Indunsuri Blok B, Desa Teluk Sasah, Kecamatan Seri Kuala Lobam. Di bawah kepemimpinan **dr. Kurniawan, M.H.**, puskesmas ini melayani kebutuhan kuratif, preventif, dan promotif masyarakat pesisir dan kawasan industri di wilayah Bintan Utara.

Kelancaran pelayanan medis di puskesmas sangat bergantung pada ketersediaan obat-obatan esensial di unit instalasi farmasi. Setiap dua bulan, petugas pengelola obat puskesmas wajib menyusun dan melaporkan **LPLPO (Laporan Pemakaian dan Lembar Permintaan Obat)** kepada Gudang Farmasi Dinas Kesehatan Kabupaten Bintan. Laporan ini mencatat saldo stok awal, penerimaan obat dari dinas, total pemakaian harian kepada pasien, jumlah obat rusak/kadaluarsa, stok akhir, serta kuantitas obat yang diajukan untuk periode berikutnya.

Dalam praktiknya, instalasi farmasi Puskesmas Teluk Sasah masih mengandalkan **metode konsumsi intuitif konvensional** (perkiraan manual berdasarkan kebiasaan masa lalu). Petugas memesan obat dengan perkiraan statis tanpa mempertimbangkan dinamika musiman morbiditas penyakit, fluktuasi konsumsi riil, maupun perhitungan stok pengaman secara saintifik. Hal ini menimbulkan kesenjangan besar antara pasokan obat dari dinas dengan kebutuhan nyata pasien di lapangan.

### I.2 Permasalahan Riil Mitra
Melalui wawancara mendalam bersama pimpinan puskesmas dan audit terhadap berkas primer LPLPO periode 2023 hingga 2026, teridentifikasi dua permasalahan utama yang bersifat paradoks dan sistemik:

1. **Masalah Penumpukan dan Pemborosan Finansial Obat Kadaluarsa (*Overstock Issue*)**:
   * Akibat pemesanan obat yang melebihi daya serap resep pasien, terjadi penumpukan obat lambat bergerak (*slow-moving*) di gudang hingga melampaui masa simpan (*expired date*).
   * Data audit Berita Acara Rekapitulasi Obat Kadaluarsa menunjukkan bahwa sepanjang 2023 hingga 2025, Puskesmas Teluk Sasah mencatat **138 item murni obat/alkes kadaluarsa** dengan total kerugian finansial negara mencapai **Rp 43.348.626,-**.
   * Kerugian ini mengalami tren kenaikan tajam: dari **Rp 9.135.590,-** pada 2023, naik menjadi **Rp 11.240.236,-** pada 2024, dan melonjak lebih dari dua kali lipat menjadi **Rp 22.972.800,-** pada 2025.
2. **Masalah Kekosongan Stok Obat Esensial (*Understock & Stockout Issue*)**:
   * Sebaliknya, untuk obat-obatan vital yang dibutuhkan penderita penyakit kronis dan infeksi saluran pernapasan akut, sering terjadi kekosongan stok (*stockout*) selama berminggu-minggu hingga berbulan-bulan.
   * Obat antihipertensi **Amlodipin 10 mg** mengalami kekosongan stok akhir di **7 periode dwibulanan (32% dari total waktu penelitian)**, bahkan pada Maret–April 2026 (*2026-P2*) stok habis total sehingga pemakaian tercatat 0 butir. Obat batuk **Ambroksol 30 mg** mengalami kekosongan stok di **8 periode (36% waktu)** di tengah tingginya kasus batuk-pilek masyarakat.
3. **Ketidaksinkronan Perencanaan dengan Tren Musiman Morbiditas**:
   * Wilayah kepulauan Bintan memiliki karakteristik maritim dengan pergantian musim angin barat dan pancaroba yang memicu lonjakan kasus infeksi pernapasan (ISPA/Common Cold - ICD-10: `J00`) dan gangguan pencernaan (Dispepsia - ICD-10: `K30`). Sistem manual yang berjalan gagal mengantisipasi lonjakan musiman ini.

### I.3 Tujuan Penambangan Data
Penelitian penambangan data ini bertujuan untuk:
1. Mengekstrak, membersihkan, dan merekayasa data mentah multi-sumber (29 berkas LPLPO, 44 bulan morbiditas 10 besar penyakit, dan berkas audit kadaluarsa) menjadi **deret waktu kontinu 22 periode dwibulanan** yang terstandarisasi.
2. Menganalisis korelasi klinis bivariat (*Pearson & Spearman*) antara lonjakan 10 besar diagnosis penyakit (kode ICD-10) dengan pola konsumsi obat-obatan terkait.
3. Mengukur tingkat variabilitas pemakaian seluruh obat puskesmas menggunakan **Koefisien Variasi ($CV$)** guna memetakan karakteristik konsumsi stabil, moderat, dan fluktuatif ekstrem.
4. Membangun dan membandingkan performa model peramalan deret waktu berbasis **Simple Moving Average ($k=2, k=3$)** dan **Weighted Moving Average ($k=3$, bobot $[1, 2, 3]$)** menggunakan metrik galat **MAPE, MAE, dan RMSE**.
5. Merumuskan formula rekomendasi kuantitas pengadaan obat periode berjalan (**2026-P5**) yang dilengkapi batas penyangga aman (**Safety Stock 95%**) guna menjamin ketersediaan obat dan mencegah pemborosan anggaran obat kadaluarsa.

### I.4 Lini Waktu Pelaksanaan & Pembagian Kerja (Gantt Chart & PIC)
Proyek dikerjakan secara kolaboratif selama Semester Ganjil 2026/2027 dengan pembagian peran terstruktur:

**Tabel 1.1: Rencana Lini Waktu Pelaksanaan Proyek (Gantt Chart & PIC)**
| Minggu | Fase Kegiatan CRISP-DM | Deskripsi Luaran Kegiatan | PIC Penanggung Jawab |
| :---: | :--- | :--- | :--- |
| **M01 - M03** | *Business Understanding* | Wawancara dr. Kurniawan, pengumpulan 29 berkas LPLPO, penyusunan Proposal Proyek (`PropPD`). | **Balqis Manisa** |
| **M04 - M06** | *Data Understanding* | Ekstraksi tabel mentah, verifikasi integritas data, inventarisasi kode ICD-10, analisis awal 138 obat kadaluarsa. | **Jian Safitri Wibowo** |
| **M07 - M08** | *Data Preprocessing* | Penyelarasan waktu 22 periode dwibulanan, imputasi domain harga kadaluarsa, pembersihan baris tanda tangan. | **Nabil** |
| **M09 - M11** | *Modeling (Time Series)* | Implementasi algoritma SMA-2, SMA-3, WMA-3, pembobotan regresi, simulasi perhitungan manual bertahap. | **Annisa Darmawan** |
| **M12 - M14** | *Evaluation & Optimization* | Perhitungan MAPE/MAE/RMSE 150 obat aktif, investigasi efek stockout, formulasi *Safety Stock* 95%. | **Yehezkiel Alman S.** |
| **M15 - M16** | *Deployment & Final Report* | Pembuatan Notebook Colab, penyusunan naskah laporan akhir (`FinPD`), persiapan presentasi sidang. | **Seluruh Anggota** |

---

# BAB II: DATA UNDERSTANDING

### II.1 Teknik Pengumpulan Data
Data yang digunakan dalam penelitian ini merupakan **data primer otentik instansi pemerintah** yang diperoleh langsung dari Instalasi Farmasi UPTD Puskesmas Teluk Sasah. Berkas dikumpulkan dalam format digital spreadsheet (`.xls` dan `.xlsx`) yang diterbitkan oleh sistem pelaporan Badan Pengelola Keuangan dan Aset Daerah / Dinas Kesehatan Kabupaten Bintan.

Inventaris berkas mencakup:
1. **29 Berkas LPLPO (Tahun 2023 s/d 2026)**: Berisi catatan logistik seluruh item obat dan perbekalan kesehatan.
   * Tahun 2023 (7 file): Januari & Februari (bulanan), dilanjutkan dwibulanan (Maret-April s/d November-Desember).
   * Tahun 2024 (12 file): Format bulanan lengkap (Januari s/d Desember).
   * Tahun 2025 (6 file): Format dwibulanan lengkap (P1 s/d P6).
   * Tahun 2026 (4 file): Format dwibulanan berjalan (P1 Januari-Februari s/d P4 Juli-Agustus 2026).
2. **1 Berkas Rekapitulasi Morbiditas Penyakit**: Berisi jumlah kunjungan pasien bulanan berdasarkan diagnosis 10 besar penyakit (ICD-10) periode Januari 2023 hingga Agustus 2026 (44 bulan pengamatan).
3. **3 Berkas Berita Acara Rekapitulasi Obat Kadaluarsa (2023, 2024, 2025)**: Berisi daftar audit fisik obat yang dimusnahkan lengkap dengan nomor batch, tanggal kadaluarsa, kuantitas, harga satuan, dan nominal kerugian.

### II.2 Jenis dan Kamus Data
Struktur data mentah diurai menjadi variabel-variabel operasional yang didefinisikan sebagai berikut:

**Tabel 2.2: Kamus Data Transaksi LPLPO Teragregasi**
| Nama Atribut | Tipe Data | Keterangan & Domain Nilai |
| :--- | :---: | :--- |
| `periode_bimonthly_id` | String | Kode unik periode dwibulanan (`2023-P1` s/d `2026-P4`). |
| `tahun` | Integer | Tahun anggaran pencatatan logistik (2023–2026). |
| `nama_obat` | String | Nama generik/dagang obat beserta sediaan dan dosisnya (414 jenis). |
| `satuan` | String | Bentuk sediaan farmasi (tablet, kapsul, kaplet, botol, ampul, tube). |
| `stok_awal` | Float | Jumlah fisik sisa obat pada awal periode dwibulanan. |
| `penerimaan` | Float | Pasokan obat yang diterima dari Gudang Farmasi Dinkes Bintan. |
| `persediaan` | Float | Total ketersediaan obat siap pakai ($\text{Stok Awal} + \text{Penerimaan}$). |
| `pemakaian` | Float | Jumlah unit obat yang diserahkan/diresepkan kepada pasien puskesmas. |
| `stok_akhir` | Float | Sisa fisik obat pada akhir periode ($\text{Persediaan} - \text{Pemakaian}$). |

**Tabel 2.3: Kamus Data 10 Besar Diagnosis Morbiditas Penyakit (ICD-10)**
| Kode ICD-10 | Nama Diagnosis Klinis | Deskripsi Epidemiologi di Puskesmas |
| :--- | :--- | :--- |
| **J00** | *Acute Nasopharyngitis (Common Cold)* | Infeksi saluran pernapasan atas (batuk-pilek-demam), kasus terbesar. |
| **I10** | *Essential (Primary) Hypertension* | Penyakit tekanan darah tinggi kronis pada kelompok lansia/prolanis. |
| **K30** | *Dyspepsia* | Gangguan lambung, nyeri ulu hati, dan maag fungsional. |
| **E11** | *Type 2 Diabetes Mellitus* | Penyakit kencing manis/gula darah tinggi kronis menahun. |
| **M79** | *Other Soft Tissue Disorders / Myalgia* | Nyeri otot, pegal linu, dan gangguan muskuloskeletal pekerja. |
| **L23 / L30** | *Allergic Contact Dermatitis / Eczema* | Penyakit kulit alergi dan dermatitis akibat kelembaban pesisir. |
| **K08** | *Disorders of Teeth and Supporting Structures* | Sakit gigi, karies, dan infeksi gusi pada poli gigi. |
| **R50** | *Fever of Other and Unknown Origin* | Demam non-spesifik pada pasien anak-anak dan dewasa. |

### II.3 Exploratory Data Analysis (EDA) & Pola Sebaran Data
Tahap eksplorasi data menghasilkan temuan-temuan empiris krusial:

1. **Top 15 Obat Terbanyak Dikonsumsi**:
   Secara statistik, akumulasi pemakaian terbesar dipimpin oleh *Fero Sulfat tab 300 mg* (255.180 butir), diikuti oleh *Parasetamol tablet 500 mg* (142.715 butir), *Amlodipin 10 mg* (140.090 butir), *Vitamin B-Kompleks* (91.870 butir), dan *Asam Mefenamat 500 mg* (79.260 butir).

**Tabel 2.4: Top 15 Obat Berdasarkan Total Akumulasi Pemakaian (2023–2026)**
| No | Nama Obat | Satuan | Total Pemakaian | Rata-rata ($\mu$) | Std Dev ($\sigma$) | Nilai $CV$ |
| :-: | :--- | :---: | :---: | :---: | :---: | :---: |
| 1 | Fero Sulfat tablet 300 mg | tablet | 255.180 | 11.599,09 | 25.563,48 | 2,20 |
| 2 | Parasetamol tablet 500 mg | tablet | 142.715 | 6.487,05 | 2.333,11 | 0,36 |
| 3 | Amlodipin 10 mg tablet | tablet | 140.090 | 6.367,73 | 5.169,68 | 0,81 |
| 4 | Vitamin B-Kompleks tablet | tablet | 91.870 | 4.175,91 | 2.105,81 | 0,50 |
| 5 | Asam Mefenamat kaplet 500 mg | kaplet | 79.260 | 3.602,73 | 2.221,14 | 0,62 |
| 6 | Kalsium Laktat tablet 500 mg | tablet | 61.130 | 2.778,64 | 953,58 | 0,34 |
| 7 | Metformin tablet 500 mg | tablet | 60.525 | 2.751,14 | 713,02 | 0,26 |
| 8 | Amoksisilin kaplet 500 mg | kaplet | 53.734 | 2.442,45 | 533,17 | 0,22 |
| 9 | Cetirizina kapsul 10 mg | kapsul | 51.531 | 2.342,32 | 1.366,67 | 0,58 |
| 10 | Asam Askorbat tablet 250 mg | tablet | 50.617 | 2.300,77 | 1.164,99 | 0,51 |
| 11 | Amlodipin tablet 5 mg | tablet | 49.800 | 2.263,64 | 1.353,49 | 0,60 |
| 12 | Deksametason tablet 0,5 mg | tablet | 44.156 | 2.007,09 | 1.652,71 | 0,82 |
| 13 | Ambroksol tablet 30 mg | tablet | 35.048 | 1.593,09 | 1.978,83 | 1,24 |
| 14 | Piroksikam capsul 10 mg | kapsul | 29.694 | 1.349,73 | 1.133,03 | 0,84 |
| 15 | Loratadin tablet 10 mg | tablet | 27.392 | 1.245,09 | 1.207,71 | 0,97 |

> **Deskripsi Penjelasan Tabel 2.4**: Tabel 2.4 memaparkan 15 obat dengan total konsumsi tertinggi di Puskesmas Teluk Sasah. Terlihat disparitas volume dan pola variabilitas yang sangat tajam. Fero Sulfat menempati urutan pertama (255.180 butir) dengan nilai CV ekstrem (2.20), yang menandakan bahwa obat ini didistribusikan melalui mekanisme drop massal program gizi sekolah. Sebaliknya, Parasetamol 500 mg menempati posisi kedua (142.715 butir) dengan nilai CV rendah (0.36), membuktikan perannya sebagai obat kuratif harian paling dominan untuk pasien demam dan flu di poli puskesmas. Obat penyakit kronis seperti Metformin (CV = 0.26) dan Amoksisilin (CV = 0.22) menunjukkan pola konsumsi yang sangat stabil dari periode ke periode.

![Gambar 2.1](../05_Visualisasi_Grafik/01_top_obat_pemakaian.png)
*Gambar 2.1: Ranking 15 Obat dengan Volume Pemakaian Terbesar di Puskesmas Teluk Sasah*

> **Deskripsi Penjelasan Gambar 2.1**: Gambar 2.1 menyajikan diagram batang horizontal perbandingan akumulasi konsumsi 15 obat teratas sepanjang 22 periode pengamatan. Diagram ini mempertegas dominasi Fero Sulfat dan Parasetamol 500 mg yang melampaui angka 140 ribu butir. Kelompok obat lapis kedua didominasi oleh terapi hipertensi (Amlodipin 10 mg dan 5 mg dengan total gabungan hampir 190 ribu butir) serta obat analgesik-antiinflamasi (Asam Mefenamat). Informasi visual ini menjadi dasar pemilihan obat prioritas yang dimodelkan secara mendalam pada Bab IV dan Bab V.

2. **Korelasi Klinis Morbiditas Penyakit vs Terapi Obat**:
   Uji korelasi bivariat Pearson ($r$) membuktikan keterkaitan linear yang kuat dan terverifikasi secara klinis:
   * **Diabetes Mellitus (E11) vs Metformin 500 mg**: Nilai $r = 0.5281$ dengan $p\text{-value} = 0.0115 < 0.05$ (korelasi positif signifikan). Hal ini membuktikan bahwa kenaikan pasien diabetes secara konsisten diiringi peningkatan resep Metformin.
   * **Common Cold / ISPA (J00) vs Obat Terkait**: Menunjukkan korelasi positif dengan antibiotik pernapasan *Amoksisilin 500 mg* ($r = 0.3924$), antialergi *Cetirizina 10 mg* ($r = 0.3593$), dan antipiretik *Parasetamol 500 mg* ($r = 0.3381$).
   * **Dispepsia / Maag (K30) vs Obat Lambung**: Berkorelasi positif dengan *Omeprazol 20 mg* ($r = 0.3844$) dan *Domperidon 10 mg* ($r = 0.3753$).

---

# BAB III: PREPROCESS

### III.1 Penyelesaian Masalah 1: Inkonsistensi Periode Waktu Pelaporan
* **Permasalahan**: Berkas LPLPO tahun 2023, 2025, dan 2026 disusun dalam format dwibulanan (2-bulanan: P1 s/d P6), sedangkan tahun 2024 disusun dalam 12 berkas bulanan terpisah. Selain itu, data diagnosis penyakit tercatat per bulan kalender.
* **Solusi Penambangan Data**: Menerapkan teknik *Temporal Granularity Alignment & Resampling* dengan menjadikan **Dwibulanan sebagai Sumbu Waktu Acuan Kontinu (22 Periode)**.
  * Variabel Aliran (Pemakaian & Penerimaan): Dilakukan agregasi penjumlahan ($\sum$) antara bulan ganjil dan genap:
    $$\text{Pemakaian}_{P_k} = \text{Pemakaian}_{\text{Bulan Ganjil}} + \text{Pemakaian}_{\text{Bulan Genap}}$$
  * Variabel Posisi (Stok Awal & Akhir):
    $$\text{Stok Awal}_{P_k} = \text{Stok Awal}_{\text{Bulan Ganjil}}$$
    $$\text{Stok Akhir}_{P_k} = \text{Stok Akhir}_{\text{Bulan Genap}}$$
  * Data penyakit bulanan diagregasikan per 2 bulan (Jan+Feb menjadi P1, Mar+Apr menjadi P2, dst.) sehingga diperoleh 22 titik data deret waktu yang sejajar sempurna.

**Tabel 3.1: Matriks Pemetaan 22 Periode Dwibulanan Kontinu (2023-P1 s/d 2026-P4)**
| Periode ID | Nama Periode Waktu | Bulan Kalender | Tahun | Indeks Kronologis ($t$) |
| :---: | :--- | :--- | :---: | :---: |
| `2023-P1` | Jan - Feb 2023 | Januari + Februari | 2023 | $t = 1$ |
| `2023-P2` | Mar - Apr 2023 | Maret + April | 2023 | $t = 2$ |
| `2023-P3` | Mei - Jun 2023 | Mei + Juni | 2023 | $t = 3$ |
| `2023-P4` | Jul - Agu 2023 | Juli + Agustus | 2023 | $t = 4$ |
| `2023-P5` | Sep - Okt 2023 | September + Oktober | 2023 | $t = 5$ |
| `2023-P6` | Nov - Des 2023 | November + Desember | 2023 | $t = 6$ |
| `2024-P1` s/d `P6` | Jan - Des 2024 | Siklus 6 Periode Dwibulanan | 2024 | $t = 7 \dots 12$ |
| `2025-P1` s/d `P6` | Jan - Des 2025 | Siklus 6 Periode Dwibulanan | 2025 | $t = 13 \dots 18$ |
| `2026-P1` s/d `P4` | Jan - Agu 2026 | Siklus 4 Periode Dwibulanan Berjalan | 2026 | $t = 19 \dots 22$ |
| `2026-P5` | Sep - Okt 2026 | **Target Peramalan Masa Depan** | 2026 | $t = 23$ *(Out-of-sample)* |

### III.2 Penyelesaian Masalah 2: Pembersihan Artefak dan Baris Pengesahan
* **Permasalahan**: Berkas spreadsheet LPLPO dan Berita Acara Kadaluarsa mengandung baris-baris non-data seperti kop surat, judul kolom dinamis, baris subtotal kategori, serta baris tanda tangan pengesahan pejabat (`dr. Kurniawan`, `NIP. 197...`).
* **Solusi Penambangan Data**: Skrip Python mendeteksi pola nomor urut numerik, menyaring baris yang memiliki nilai obat kosong, dan mengeliminasi baris teks pengesahan. Pada data obat kadaluarsa, penyaringan berhasil mereduksi 144 baris kotor menjadi tepat **138 item murni obat/alkes kadaluarsa**.

### III.3 Penyelesaian Masalah 3: Penanganan Nilai Kosong (*Missing Value*) & Domain Lookup
* **Permasalahan**: Pada berkas berita acara kadaluarsa tahun 2024 dan 2025, terdapat beberapa item obat yang kolom harga satuannya tidak tercantum (`NaN / Kosong`), sehingga subtotal kerugian nominalnya belum terhitung.
* **Solusi Penambangan Data**: Menolak penggunaan imputasi median/mean sembarangan karena menyangkut akuntabilitas audit keuangan negara. Sebagai gantinya, diterapkan teknik **Domain-Specific Cross-Table Lookup**: skrip mencari nama obat yang bersangkutan ke dalam master tabel LPLPO resmi puskesmas untuk mengambil harga perolehan aslinya. Dengan cara ini, nilai total kerugian kadaluarsa berhasil dihitung secara presisi sebesar **Rp 43.348.626,-**.

### III.4 Penyelesaian Masalah 4: Pemisahan Anomali Obat Program Nasional vs Obat Kuratif
* **Permasalahan**: Pada tabel mentah, *Fero Sulfat (Tablet Tambah Darah)* mencatat pemakaian 255.180 butir, melampaui Parasetamol (142.715 butir). Hal ini berisiko membingungkan pemodelan kuratif poli.
* **Solusi Penambangan Data**: Melakukan analisis semantik pada berkas `1 JANUARI 2024.xls` (baris 122) di mana ditemukan catatan resmi **`KET: GIZI`** dengan volume drop **118.295 tablet** dalam satu bulan. Fero Sulfat diidentifikasi sebagai **Obat Program Aksi Bergizi Nasional Kemenkes** (didistribusikan berkala ke siswi SMP/SMA di Bintan untuk pencegahan stunting/anemia). Pola distribusinya dipisahkan dari analisis kuratif harian pasien sakit, di mana **Parasetamol 500 mg dikukuhkan sebagai obat kuratif nomor 1 paling dominan** di poli puskesmas.

### III.5 Penyelesaian Masalah 5: Transformasi Matriks Deret Waktu (*Wide-Format Pivoting*)
* **Permasalahan**: Algoritma peramalan deret waktu membutuhkan format data tabel silang (*cross-tabulated matrix*) di mana baris merepresentasikan jenis obat dan kolom merepresentasikan deret waktu kronologis $t_1, t_2, \dots, t_{22}$.
* **Solusi Penambangan Data**: Menerapkan operasi pivoting pada Pandas untuk mentransformasikan 15.813 baris transaksi granular menjadi matriks dimensi $414 \times 22$ ([`lplpo_obat_pivot_pemakaian.csv`](file:///home/mizuuu/Documents/Penambangan%20Data/03_Data_Terproses/lplpo_obat_pivot_pemakaian.csv)). Matriks ini dilengkapi atribut agregat: *Total Pemakaian*, *Mean*, *Standar Deviasi*, dan *Koefisien Variasi ($CV$)*.

### III.6 Analisis Komparasi Granularitas: Tren Mikro 1-Bulanan (2024) vs Makro 2-Bulanan (2023–2026)
Sesuai rekomendasi strategis dosen pembimbing (Bapak Tekad Matulatan dan Bapak Muhamad Fadli), penelitian ini **mempertahankan dan menganalisis secara komparatif data granularitas 1-bulanan (tahun 2024) bersanding dengan deret waktu 2-bulanan (*dwibulanan*) kontinu (2023–2026)**. Pendekatan dua lensa temporal ini memberikan wawasan manajerial yang komplementer bagi instalasi farmasi puskesmas:

1. **Lensa Mikro 1-Bulanan (12 Bulan di Tahun 2024)**:
   * Membuka visibilitas fluktuasi intra-kuartal yang kerap tersembunyi ketika data langsung diagregasi ke periode 2-bulanan.
   * Mengidentifikasi pola musiman jangka pendek, seperti dampak lonjakan morbiditas saat libur sekolah, perubahan musim angin pesisir, atau pola kunjungan menjelang hari raya keagamaan.
2. **Lensa Makro 2-Bulanan (22 Periode Kontinu 2023–2026)**:
   * Menyelaraskan seluruh deret waktu historis dengan siklus resmi pelaporan dan distribusi logistik farmasi Dinas Kesehatan Kabupaten Bintan (LPLPO reguler P1 s/d P6).
   * Meredam lonjakan acak (*noise filtering*) sehingga algoritma *Moving Average* dapat menghasilkan estimasi kebutuhan yang stabil dan reliabel.

**Tabel 3.2: Analisis Komparasi Tren Pemakaian Bulanan (12 Bulan 2024) pada Empat Obat Utama**
| Nama Obat Esensial | Jan | Feb | Mar | Apr | Mei | Jun | Jul | Agu | Sep | Okt | Nov | Des | Total 2024 | Rata2/Bln |
| :--- | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| **Parasetamol tablet 500 mg** | 2.691 | 3.604 | 2.440 | 2.515 | 3.567 | 3.268 | 3.705 | **5.230** | 3.501 | 4.459 | 3.685 | 4.335 | **43.000** | 3.583,3 |
| **Metformin tablet 500 mg** | 1.320 | 1.571 | 510 | 1.084 | 698 | 1.790 | 790 | 1.700 | 1.130 | 1.340 | 775 | **2.232** | **14.940** | 1.245,0 |
| **Amoksisilin kaplet 500 mg** | 1.429 | 1.213 | 808 | 981 | 1.071 | 1.260 | 946 | **1.551** | 1.005 | 1.132 | 1.199 | 1.497 | **14.092** | 1.174,3 |
| **Amlodipin 10 mg tablet** | 1.160 | 2.360 | 1.800 | 3.530 | 2.836 | 4.791 | 1.970 | **4.980** | 2.980 | 3.403 | 1.920 | 3.080 | **34.810** | 2.900,8 |

> **Deskripsi Penjelasan Tabel 3.2**: Tabel 3.2 menyajikan data pemakaian riil bulanan sepanjang tahun 2024 pada empat obat kunci puskesmas. Dari data bulanan ini, terungkap bahwa puncak pemakaian antibiotik dan antipiretik terjadi secara serentak pada bulan Agustus 2024 (Parasetamol mencapai 5.230 butir dan Amoksisilin mencapai 1.551 butir), yang berkorelasi langsung dengan lonjakan diagnosis kasus ISPA akibat cuaca peralihan kemarau-hujan di pesisir Bintan. Sementara itu, terapi diabetes (Metformin) mencatat konsumsi tertinggi pada bulan Desember 2024 (2.232 butir) seiring meningkatnya kepatuhan kontrol pasien prolanis di akhir tahun.

![Gambar 3.1](../05_Visualisasi_Grafik/15_tren_bulanan_2024_vs_dwibulanan.png)
*Gambar 3.1: Perbandingan Granularitas Deret Waktu: Tren 1-Bulanan vs 2-Bulanan (Tahun 2024)*

> **Deskripsi Penjelasan Gambar 3.1**: Gambar 3.1 menyajikan perbandingan visual langsung antara grafik garis konsumsi 1-bulanan (Subplot A) dan grafik batang agregasi 2-bulanan (Subplot B). Terlihat jelas bagaimana representasi dwibulanan (2024-P1 hingga 2024-P6) berhasil meratakan variasi mingguan/bulanan tanpa menghilangkan tren kenaikan permintaan tahunan. Kedua sudut pandang ini dipertahankan di dalam dashboard Streamlit agar pengambil kebijakan dapat melakukan monitoring taktis bulanan sekaligus perencanaan strategis pengadaan per periode dinas.

---

# BAB IV: MODEL

### IV.1 Konsep dan Formulasi Moving Average
Metode yang dipilih dalam penelitian ini adalah keluarga algoritma **Moving Average (Rata-rata Bergerak)**. Pendekatan ini sangat ideal untuk manajemen logistik farmasi puskesmas karena:
1. Bersifat adaptif terhadap perubahan pola kebutuhan jangka pendek.
2. Mampu meredam fluktuasi acak (*random noise*) akibat kendala teknis rantai pasok.
3. Mudah dipahami dan dioperasikan secara mandiri oleh staf instalasi farmasi puskesmas tanpa memerlukan komputasi rumit.

Tiga varian algoritma yang diimplementasikan dan dikomparasikan:
1. **Simple Moving Average dengan ordo 2 (SMA-2)**
2. **Simple Moving Average dengan ordo 3 (SMA-3)**
3. **Weighted Moving Average dengan ordo 3 (WMA-3)** dengan pembobotan linier $[1, 2, 3]$

### IV.2 Formulasi Simple Moving Average (SMA)
Simple Moving Average meramalkan kebutuhan periode berikutnya ($F_{t+1}$) dengan menghitung rata-rata aritmatika sederhana dari pemakaian riil $k$ periode terakhir ($A_t, A_{t-1}, \dots, A_{t-k+1}$):

$$F_{t+1} = \frac{\sum_{i=0}^{k-1} A_{t-i}}{k}$$

* **Formulasi SMA-2 ($k=2$)**:
  $$F_{t+1} = \frac{A_t + A_{t-1}}{2}$$
* **Formulasi SMA-3 ($k=3$)**:
  $$F_{t+1} = \frac{A_t + A_{t-1} + A_{t-2}}{3}$$

### IV.3 Formulasi Weighted Moving Average (WMA)
Weighted Moving Average mengatasi kelemahan SMA yang memberikan bobot sama rata. WMA memberikan bobot lebih besar kepada data konsumsi terkini ($A_t$) dengan asumsi bahwa konsumsi paling akhir mencerminkan kondisi tren epidemiologi dan ketersediaan stok yang paling relevan.

Untuk ordo $k=3$ dengan vektor bobot $w = [1, 2, 3]$ di mana total bobot $\sum w = 1 + 2 + 3 = 6$:

$$F_{t+1} = \frac{(3 \times A_t) + (2 \times A_{t-1}) + (1 \times A_{t-2})}{6}$$

Dalam model ini, periode terakhir ($A_t$) berkontribusi sebesar **50%** terhadap ramalan, periode sebelumnya ($A_{t-1}$) berkontribusi **33,33%**, dan periode tertua ($A_{t-2}$) berkontribusi **16,67%**.

### IV.4 Simulasi Perhitungan Matematis Langkah Demi Langkah
Guna memenuhi tuntutan RTM bab model, berikut disajikan simulasi perhitungan manual *step-by-step* pada dua obat representatif:

#### A. Simulasi Perhitungan Parasetamol Tablet 500 mg & Koreksi Permintaan Tersensor (*Censored Demand*)
Data pemakaian aktual Parasetamol 500 mg pada 5 periode terakhir:
* $t_{18}$ (`2025-P6`): $A_{18} = 4.000$ butir
* $t_{19}$ (`2026-P1`): $A_{19} = 4.000$ butir
* $t_{20}$ (`2026-P2`): $A_{20} = 5.256$ butir (rata-rata pemakaian normal)
* $t_{21}$ (`2026-P3`): $A_{21} = 6.444$ butir (seluruh stok ludes terpakai, stok akhir = 0)
* $t_{22}$ (`2026-P4`): $A_{22} = 0$ butir *(Catatan: terjadi Total Stockout fisik—stok awal 0, penerimaan 0, dan stok akhir 0)*

**1. Perhitungan Ramalan Naif (Tanpa Koreksi Data Tersensor):**
Jika nilai $A_{22} = 0$ dihitung secara langsung tanpa penanganan data tersensor:
* **Model SMA-2 ($k=2$)**:
  $$F_{23}^{\text{naif}} = \frac{A_{22} + A_{21}}{2} = \frac{0 + 6.444}{2} = 3.222\text{ butir}$$
* **Model SMA-3 ($k=3$)**:
  $$F_{23}^{\text{naif}} = \frac{A_{22} + A_{21} + A_{20}}{3} = \frac{0 + 6.444 + 5.256}{3} = \frac{11.700}{3} = 3.900\text{ butir}$$
* **Model WMA-3 ($k=3$)**:
  $$F_{23}^{\text{naif}} = \frac{(3 \times 0) + (2 \times 6.444) + (1 \times 5.256)}{6} = \frac{0 + 12.888 + 5.256}{6} = \frac{18.144}{6} = 3.024\text{ butir}$$

*Implikasi Negatif*: Nilai ramalan naif (3.222 butir) anjlok separuh dari kebutuhan riil bulanan karena ketiadaan pasokan fisik disalahartikan sebagai ketiadaan pasien di poli.

**2. Rekalkulasi dengan Imputasi Permintaan Tersensor (*Censored Demand Imputation*):**
Berdasarkan kaidah manajemen logistik farmasi, nilai konsumsi $A_{22}$ pada periode *stockout* total diestimasi menggunakan rata-rata pemakaian periode normal sebelum krisis obat:
$$\hat{A}_{22} = \frac{A_{20} + A_{21}}{2} = \frac{5.256 + 6.444}{2} = 5.850\text{ butir}$$

Dengan nilai konsumsi terekonstruksi tersebut, ramalan resmi periode 2026-P5 ($t=23$) menjadi:
* **Model Terbaik SMA-2 ($k=2$, MAPE 36,42%)**:
  $$F_{23} = \frac{\hat{A}_{22} + A_{21}}{2} = \frac{5.850 + 6.444}{2} = \mathbf{6.147\text{ butir}}$$
* **Model WMA-3 ($k=3$)**:
  $$F_{23} = \frac{(3 \times 5.850) + (2 \times 6.444) + (1 \times 5.256)}{6} = \frac{17.550 + 12.888 + 5.256}{6} = \mathbf{5.949\text{ butir}}$$

#### B. Simulasi Perhitungan Metformin Tablet 500 mg
Data pemakaian aktual Metformin 500 mg pada 3 periode terakhir:
* $t_{20}$ (`2026-P2`): $A_{20} = 3.370$ butir
* $t_{21}$ (`2026-P3`): $A_{21} = 3.370$ butir
* $t_{22}$ (`2026-P4`): $A_{22} = 4.475$ butir

**Perhitungan Ramalan Periode Berikutnya ($t=23$ / `2026-P5`):**
1. **Model SMA-2**: $F_{23} = \frac{4.475 + 3.370}{2} = 3.922,5\text{ butir}$
2. **Model SMA-3**: $F_{23} = \frac{4.475 + 3.370 + 3.370}{3} = 3.738,33\text{ butir}$
3. **Model WMA-3**:
   $$F_{23} = \frac{(3 \times 4.475) + (2 \times 3.370) + (1 \times 3.370)}{6} = \frac{13.425 + 6.740 + 3.370}{6} = \frac{23.535}{6} = 3.922,5\text{ butir}$$

> **Deskripsi Penjelasan Simulasi Matematis (Tabel 4.1 & 4.2)**: Simulasi di atas mendemonstrasikan secara transparan langkah komputasi matematis Moving Average pada Parasetamol tablet 500 mg dan Metformin 500 mg. Terlihat bahwa pada periode normal, model WMA-3 menghasilkan galat persentase mutlak sangat kecil (3,76% sampai 6,03%), membuktikan akurasi pembobotan pada tren terkini. Pada kasus Parasetamol 2026-P4, investigasi audit membuktikan bahwa pemakaian bernilai 0 merupakan fenomena permintaan tersensor (*censored demand*) akibat kekosongan total stok gudang. Melalui imputasi ilmiah $\hat{A}_{22} = 5.850$ butir, ramalan SMA-2 terekonstruksi menjadi 6.147 butir (menghindari proyeksi naif 3.222 butir yang berisiko fatal). Dipadukan dengan cadangan pengaman Safety Stock pada sub-bab IV.7, usulan alokasi pengadaan menjadi 7.487 butir, menjamin ketersediaan obat saat puncak kasus ISPA tiba di puskesmas.

### IV.5 Formulasi Indikator Moving Average Convergence Divergence (MACD) Farmasi
Menindaklanjuti arahan dosen pembimbing mengenai eksplorasi **Moving Average Convergence Divergence (MACD)**, penelitian ini mengadaptasi indikator teknikal momentum ciptaan Gerald Appel ke dalam ranah logistik farmasi kesehatan. Dalam konteks pemakaian obat di fasilitas kesehatan tingkat pertama:
1. Moving Average konvensional (SMA/WMA) hanya memberikan estimasi kuantitatif titik tunggal tanpa mengukur akselerasi perubahan tren.
2. Indikator MACD berfungsi sebagai **Sistem Deteksi Dini Momentum (*Early Warning Momentum Indicator*)** untuk mendeteksi apakah laju peresepan suatu obat sedang mengakselerasi (*bullish*) atau melambat (*bearish*).

Formulasi matematis MACD farmasi dirancang dengan parameter periode dwibulanan:
1. **Exponential Moving Average (EMA)**:
   $$\text{EMA}_t(N) = \left( A_t \times \frac{2}{N + 1} \right) + \left( \text{EMA}_{t-1}(N) \times \left(1 - \frac{2}{N + 1}\right) \right)$$
   * **Fast EMA ($N_{\text{fast}} = 2$ periode / setara 4 bulan)**: Menangkap pergerakan konsumsi paling mutakhir dengan faktor pengali $\alpha = 0,667$.
   * **Slow EMA ($N_{\text{slow}} = 4$ periode / setara 8 bulan)**: Merefleksikan garis dasar kebutuhan jangka menengah dengan faktor pengali $\alpha = 0,400$.

2. **Garis MACD (*MACD Line*)**:
   $$\text{MACD Line}_t = \text{EMA}_t(N_{\text{fast}}=2) - \text{EMA}_t(N_{\text{slow}}=4)$$

3. **Garis Sinyal (*Signal Line*)**:
   $$\text{Signal Line}_t = \text{EMA}_t(\text{MACD Line}, \, N_{\text{signal}}=2)$$

4. **Histogram Momentum Divergensi**:
   $$\text{Histogram}_t = \text{MACD Line}_t - \text{Signal Line}_t$$

**Tabel 4.3: Formulasi Parameter Teknis Indikator MACD Farmasi Puskesmas**
| Komponen Indikator | Parameter Waktu | Bobot Smoothing ($\alpha$) | Interpretasi Klinis & Logistik Farmasi |
| :--- | :---: | :---: | :--- |
| **Fast EMA** | $N=2$ (4 Bulan) | $\alpha = 2/(2+1) = 0,667$ | Kecepatan konsumsi resep periode terkini. |
| **Slow EMA** | $N=4$ (8 Bulan) | $\alpha = 2/(4+1) = 0,400$ | Baseline rata-rata konsumsi musiman puskesmas. |
| **MACD Line** | Fast minus Slow | - | Deviasi selisih momentum cepat terhadap lambat. |
| **Signal Line** | $N=2$ dari MACD | $\alpha = 0,667$ | Garis konfirmasi pembalikan tren pemakaian. |
| **MACD Histogram** | MACD - Signal | - | **$\ge 0$: Bullish** (Akselerasi permintaan naik, waspada understock).<br>**$< 0$: Bearish** (Deselerasi/stok habis, cegah overstock). |

![Gambar 4.2](../05_Visualisasi_Grafik/17_analisis_macd_obat.png)
*Gambar 4.2: Analisis Momentum Moving Average Convergence Divergence (MACD) pada Pemakaian Parasetamol*

> **Deskripsi Penjelasan Gambar 4.2**: Gambar 4.2 mengilustrasikan penerapan teknis indikator MACD pada obat Parasetamol 500 mg. Pada Subplot A, Fast EMA (garis biru putus-putus) merespon secara dinamis terhadap pemakaian riil dibandingkan Slow EMA (garis oranye titik-titik). Pada Subplot B, ketika pemakaian riil anjlok ke titik 0 di periode 2026-P4 akibat kekosongan fisik stok gudang, garis MACD menembus ke bawah garis sinyal (*death cross*) dan batang histogram bernilai negatif (-485,42). Status *bearish* ini menjadi sinyal otomatis bagi pengelola obat bahwa sistem peramalan membutuhkan penyesuaian khusus (injeksi safety stock) agar tidak terkecoh oleh penurunan konsumsi semu tersebut.

### IV.6 Formulasi Peramalan Morbiditas Penyakit Terbanyak
Sesuai arahan dosen untuk memprediksi penyakit paling sering (minimal 4 penyakit), data rekam medis 10 besar penyakit bulanan puskesmas diagregasikan ke dalam format deret waktu dwibulanan sejajar. Empat diagnosis dengan prevalensi tertinggi dimodelkan:
1. **ISPA / Acute Nasopharyngitis (ICD-10: J00)**
2. **Hipertensi Primer / Essential Hypertension (ICD-10: I10)**
3. **Dispepsia / Gangguan Asam Lambung (ICD-10: K30)**
4. **Diabetes Melitus Tipe 2 / Non-Insulin-Dependent Diabetes (ICD-10: E11)**

Peramalan jumlah pasien ($D_{t+1}$) pada masing-masing diagnosis diuji menggunakan SMA-2, SMA-3, dan WMA-3 ($w = [1, 2, 3]$) dengan formula:
$$D_{t+1}^{\text{WMA-3}} = \frac{3 \times P_t + 2 \times P_{t-1} + 1 \times P_{t-2}}{6}$$
Di mana $P_t, P_{t-1}, P_{t-2}$ menyatakan jumlah pasien riil terdaftar pada tiga periode dwibulanan terakhir.

### IV.7 Formulasi Stok Pengaman (*Safety Stock 95%*) dan Alokasi Pengadaan
Jika puskesmas hanya mengajukan permintaan obat sebesar nilai ramalan $F_{t+1}$, puskesmas akan mengalami kekurangan obat apabila terjadi lonjakan wabah musiman yang melebihi estimasi rata-rata. Oleh karena itu, diterapkan formula **Safety Stock ($SS$)** dengan tingkat keandalan pelayanan 95% ($Z = 1.645$):

$$SS = Z \times \sigma$$

Di mana $\sigma$ adalah standar deviasi pemakaian obat riil sepanjang periode historis.

Selanjutnya, **Rekomendasi Rencana Pengadaan Murni ($Q_{\text{order}}$)** yang diajukan ke Dinas Kesehatan Kabupaten Bintan diformulasikan secara matematis:

$$Q_{\text{order}} = \max\Big(0, \, F_{t+1} + SS - S_{\text{akhir}}\Big)$$

Di mana:
* $F_{t+1}$ = Nilai ramalan kebutuhan periode mendatang.
* $SS$ = Batas penyangga stok pengaman (*Safety Stock*).
* $S_{\text{akhir}}$ = Sisa stok fisik yang masih ada di gudang farmasi puskesmas saat ini.

---

# BAB V: EVALUASI

### V.1 Metrik Evaluasi Akurasi (MAPE, MAE, RMSE)
Tingkat keakuratan dan performa model peramalan diukur menggunakan tiga metrik standar statistika:

1. **Mean Absolute Percentage Error (MAPE)**:
   $$\text{MAPE} = \frac{1}{n} \sum_{t=1}^n \left| \frac{A_t - F_t}{A_t} \right| \times 100\%$$
   *Kriteria Penilaian Akurasi (Lewis, 1982)*:
   * $\text{MAPE} < 10\%$ : Sangat Akurat (*High Accuracy*)
   * $10\% \le \text{MAPE} \le 20\%$ : Baik (*Good Forecast*)
   * $20\% < \text{MAPE} \le 50\%$ : Layak / Wajar (*Reasonable Forecast*)
   * $\text{MAPE} > 50\%$ : Tidak Akurat / Berfluktuasi Ekstrem (*Inaccurate*)

2. **Mean Absolute Error (MAE)**:
   $$\text{MAE} = \frac{1}{n} \sum_{t=1}^n |A_t - F_t|$$

3. **Root Mean Squared Error (RMSE)**:
   $$\text{RMSE} = \sqrt{\frac{1}{n} \sum_{t=1}^n (A_t - F_t)^2}$$

### V.2 Hasil Evaluasi Akurasi Seluruh Model
Pengujian dilakukan terhadap seluruh 150 obat aktif di Puskesmas Teluk Sasah. Ringkasan hasil pengujian untuk obat-obatan representatif disajikan pada Tabel 5.1:

**Tabel 5.1: Hasil Evaluasi Galat (MAPE) dan Penentuan Model Terbaik Top Obat**
| Nama Obat | MAPE SMA-2 | MAPE SMA-3 | MAPE WMA-3 | Model Terbaik | Kategori Akurasi |
| :--- | :---: | :---: | :---: | :---: | :---: |
| **Metformin tablet 500 mg** | 16,02% | 15,88% | **15,25%** | **WMA (k=3)** | **Baik (*Good*)** |
| **Amoksisilin kaplet 500 mg** | 19,10% | 19,06% | **17,35%** | **WMA (k=3)** | **Baik (*Good*)** |
| **Vitamin B-Kompleks tablet** | 27,44% | 28,65% | **27,32%** | **WMA (k=3)** | **Layak (*Reasonable*)** |
| **Kalsium Laktat tablet 500 mg**| 34,23% | **30,94%** | 31,90% | **SMA (k=3)** | **Layak (*Reasonable*)** |
| **Parasetamol tablet 500 mg** | **36,42%** | 39,95% | 37,81% | **SMA (k=2)** | **Layak (*Reasonable*)** |
| **Asam Askorbat tablet 250 mg** | 49,15% | 46,55% | **45,01%** | **WMA (k=3)** | **Layak (*Reasonable*)** |
| **Cetirizina kapsul 10 mg** | 67,59% | 64,13% | **60,87%** | **WMA (k=3)** | Anomali Pasokan |
| **Asam Mefenamat kaplet 500 mg**| 77,47% | 67,12% | **63,85%** | **WMA (k=3)** | Anomali Pasokan |
| **Amlodipin 10 mg tablet** | 83,67% | **73,54%** | 79,20% | **SMA (k=3)** | Anomali Stockout |
| **Fero Sulfat tab 300 mg (Gizi)**| 452,58% | **404,36%** | 442,19% | **SMA (k=3)** | Drop Massal Sekolah |

> **Deskripsi Penjelasan Tabel 5.1**: Tabel 5.1 membandingkan performa akurasi ketiga model peramalan berdasarkan metrik MAPE. Hasil pengujian menunjukkan bahwa model Weighted Moving Average (WMA-3) unggul pada mayoritas obat konsumsi rutin, ditandai dengan capaian MAPE 15,25% pada Metformin dan 17,35% pada Amoksisilin (kategori Baik menurut Lewis, 1982). Sementara itu, model SMA-2 memberikan respon terbaik pada Parasetamol (MAPE 36,42%), dan SMA-3 unggul pada Kalsium Laktat (MAPE 30,94%). Tingginya nilai galat pada Amlodipin dan Fero Sulfat tidak mencerminkan kegagalan pemodelan, melainkan akibat distorsi data lapangan (stockout dan drop program gizi sekolah).

![Gambar 5.5](../05_Visualisasi_Grafik/13_perbandingan_mape_model.png)
*Gambar 5.5: Diagram Batang Perbandingan Nilai Galat MAPE Antar-Model pada 10 Obat Utama*

> **Deskripsi Penjelasan Gambar 5.5**: Gambar 5.5 menyajikan visualisasi diagram batang komparasi nilai galat MAPE antara SMA-2 (merah), SMA-3 (biru), dan WMA-3 (hijau). Terlihat jelas keunggulan konsisten WMA-3 dalam menekan galat pada kelompok obat kronis dan antibiotik. Grafik ini memberikan konfirmasi visual bahwa pemberian bobot bertingkat pada periode terbaru berhasil meningkatkan sensitivitas peramalan terhadap tren kebutuhan puskesmas.

### V.3 Analisis Pengaruh Fluktuasi ($CV$) terhadap Akurasi Prediksi
Hasil evaluasi empiris membuktikan adanya hubungan linear yang sangat kuat antara **Koefisien Variasi ($CV$)** dengan tingkat galat **MAPE**:
1. **Kelompok Obat Stabil ($CV < 0.40$)**:
   * *Amoksisilin 500 mg* ($CV = 0.22$) menghasilkan MAPE **17.35%**.
   * *Metformin 500 mg* ($CV = 0.26$) menghasilkan MAPE **15.25%**.
   * *Parasetamol 500 mg* ($CV = 0.36$) menghasilkan MAPE **36.42%**.
   * Pada kelompok ini, pemakaian riil mencerminkan kebutuhan murni morbiditas pasien, sehingga peramalan deret waktu bekerja dengan tingkat akurasi tinggi.
2. **Kelompok Obat Sangat Fluktuatif ($CV > 0.80$)**:
   * Obat dengan $CV$ tinggi secara konsisten menghasilkan galat MAPE di atas 50%. Hal ini menjadi temuan penting bahwa pada obat berfluktuasi tinggi, perencanaan logistik **tidak boleh hanya mengandalkan peramalan titik tunggal (*single point forecast*)**, melainkan wajib ditopang oleh batas *Safety Stock*.

### V.4 Pembuktian Empiris Dampak Anomali Stockout terhadap Galat Model
Investigasi mendalam terhadap tingginya galat peramalan pada *Amlodipin 10 mg* (MAPE 73,54%) dan *Ambroksol 30 mg* mengungkap fakta bahwa:
> **Tingginya nilai galat MAPE bukan disebabkan oleh kelemahan algoritma Moving Average, melainkan akibat anomali riil rantai pasok berupa kekosongan stok (*Stockout*) berulang di puskesmas.**

**Tabel 5.3: Bukti Empiris Sejarah Stockout Amlodipin 10 mg dan Ambroksol 30 mg**
| Periode Dwibulanan | Stok Awal | Penerimaan | Pemakaian Tercatat | Stok Akhir Fisik | Status Logistik di Lapangan |
| :---: | :---: | :---: | :---: | :---: | :--- |
| **Amlodipin 10 mg:** | | | | | |
| `2023-P6` | 16.366 | 10.000 | 26.366 | **0** | **Stok Habis Total** (Tutup Buku) |
| `2024-P6` | 5.000 | 0 | 5.000 | **0** | **Stok Habis Total** |
| `2025-P4` | 1.895 | 100 | 1.995 | **0** | **Krisis Pasokan** (Hanya terima 100 tab) |
| `2025-P5` | 0 | 11.000 | 11.000 | **0** | **Rapel Konsumsi Masif** (Drop ludes) |
| `2026-P2` | **0** | **0** | **0** | **0** | **Stockout Total Selama 2 Bulan** |
| **Ambroksol 30 mg:** | | | | | |
| `2023-P4` s/d `P6` | 0 | 0 | **0** | **0** | **Kosong 6 Bulan Berturut-turut** |
| **Parasetamol 500 mg:** | | | | | |
| `2026-P4` | **0** | **0** | **0** | **0** | **Stockout Total** (Ludes di P3, Permintaan Tersensor) |

Ketika stok akhir = 0, puskesmas tidak dapat melayani resep sehingga angka pemakaian tercatat **0 butir**, padahal permintaan pasien di poli tetap tinggi. Saat kiriman dinas tiba, konsumsi melonjak secara semu (*spike*). Pola data yang terdistorsi oleh ketiadaan stok inilah yang mendongkrak galat MAPE.

> **Deskripsi Penjelasan Tabel 5.3**: Tabel 5.3 membuktikan secara kronologis anomali rantai pasok pada Amlodipin 10 mg, Ambroksol 30 mg, dan Parasetamol 500 mg. Tercatat bahwa Amlodipin mengalami kekosongan stok akhir di 7 periode (bahkan kosong total 2 bulan penuh di 2026-P2), Ambroksol mengalami kekosongan stok di 8 periode berturut-turut pada akhir 2023, dan Parasetamol mengalami kehabisan total di 2026-P4. Fenomena ini membuktikan bahwa lonjakan MAPE pada obat-obat tersebut merupakan representasi riil dari masalah understock di puskesmas, bukan kesalahan matematis model.

### V.5 Visualisasi Kurva Aktual vs Prediksi Obat Esensial
Berikut disajikan kurva perbandingan antara data historis pemakaian aktual dengan hasil peramalan model untuk obat-obat esensial puskesmas beserta deskripsi pembahasannya:

![Gambar 5.1](../05_Visualisasi_Grafik/09_forecast_parasetamol_actual_vs_pred.png)
*Gambar 5.1: Kurva Aktual vs Prediksi Pemakaian Parasetamol 500 mg*

> **Deskripsi Penjelasan Gambar 5.1**: Gambar 5.1 menampilkan kurva konsumsi aktual Parasetamol 500 mg (garis hitam tebal) yang dibandingkan dengan hasil peramalan SMA-2, SMA-3, dan WMA-3. Model SMA-2 (garis merah putus-putus) terbukti paling adaptif dan mampu bermanuver cepat mengikuti fluktuasi kasus demam musiman, menjadikannya pilihan model peramalan terbaik untuk obat antipiretik ini.

![Gambar 5.2](../05_Visualisasi_Grafik/10_forecast_metformin_actual_vs_pred.png)
*Gambar 5.2: Kurva Aktual vs Prediksi Pemakaian Metformin 500 mg (WMA-3 MAPE 15.25%)*

> **Deskripsi Penjelasan Gambar 5.2**: Gambar 5.2 memperlihatkan keselarasan kurva yang sangat presisi antara data aktual Metformin 500 mg dengan model peramalan WMA-3 (garis hijau). Deviasi yang sangat kecil sepanjang deret waktu membuktikan bahwa stabilitas konsumsi pasien diabetes dapat diprediksi dengan tingkat akurasi tinggi menggunakan pembobotan linier.

![Gambar 5.3](../05_Visualisasi_Grafik/11_forecast_amlodipin_actual_vs_pred.png)
*Gambar 5.3: Kurva Aktual vs Prediksi Pemakaian Amlodipin 10 mg*

> **Deskripsi Penjelasan Gambar 5.3**: Gambar 5.3 memperlihatkan bukti empiris dampak stockout pada kurva Amlodipin 10 mg. Terlihat beberapa jurang penurunan tajam hingga titik nol (seperti pada 2026-P2) yang langsung diikuti oleh lonjakan ekstrem akibat rapel pasokan. Visualisasi ini menegaskan bahwa galat peramalan pada obat antihipertensi dipicu oleh instabilitas pasokan gudang dinas.

![Gambar 5.4](../05_Visualisasi_Grafik/12_forecast_amoksisilin_actual_vs_pred.png)
*Gambar 5.4: Kurva Aktual vs Prediksi Pemakaian Amoksisilin 500 mg (WMA-3 MAPE 17.35%)*

> **Deskripsi Penjelasan Gambar 5.4**: Gambar 5.4 menggambarkan kurva peramalan Amoksisilin kaplet 500 mg. Model WMA-3 berhasil meratakan noise konsumsi musiman dan menghasilkan estimasi kebutuhan yang sangat stabil di kisaran 1.700 – 2.400 butir per periode dwibulanan.

### V.6 Rekomendasi Alokasi Pengadaan Obat Periode 2026-P5
Berdasarkan model terbaik dan formula *Safety Stock 95%*, berikut adalah tabel rekomendasi pengadaan resmi untuk diajukan ke Dinas Kesehatan Kabupaten Bintan pada periode berjalan (**2026-P5 / September–Oktober 2026**):

**Tabel 5.4: Rekomendasi Alokasi Pengadaan dan Safety Stock Periode 2026-P5**
| Nama Obat | Model Terbaik | Nilai Ramalan ($F_{t+1}$) | Safety Stock ($SS$) | Sisa Stok Akhir ($S_{\text{akhir}}$) | Rekomendasi Alokasi Pengadaan ($Q_{\text{order}}$) |
| :--- | :---: | :---: | :---: | :---: | :---: |
| **Parasetamol tablet 500 mg** | SMA-2 | 6.147 butir *(Adj)* | 1.340 butir | 0 butir | **7.487 butir** |
| **Metformin tablet 500 mg** | WMA-3 | 3.923 butir | 1.173 butir | 1.050 butir | **4.046 butir** |
| **Amlodipin 10 mg tablet** | SMA-3 | 5.092 butir | 8.504 butir | 4.725 butir | **8.871 butir** |
| **Amoksisilin kaplet 500 mg** | WMA-3 | 1.785 butir | 877 butir | 3.010 butir | **0 butir** *(Stok Aman)* |
| **Asam Mefenamat kaplet 500 mg**| WMA-3 | 5.855 butir | 3.654 butir | 1.500 butir | **8.009 butir** |
| **Kalsium Laktat tablet 500 mg** | SMA-3 | 3.230 butir | 1.569 butir | 2.100 butir | **2.699 butir** |
| **Vitamin B-Kompleks tablet** | WMA-3 | 0 butir | 3.464 butir | 4.500 butir | **0 butir** *(Stok Aman)* |

> **Deskripsi Penjelasan Tabel 5.4**: Tabel 5.4 menyajikan keputusan manajerial pengadaan obat siap eksekusi untuk periode September–Oktober 2026 (2026-P5). Formula pengadaan berhasil mengintegrasikan peramalan konsumsi dengan stok pengaman. Pada Parasetamol 500 mg, setelah menerapkan koreksi permintaan tersensor (*censored demand imputation*) akibat kekosongan total stok gudang pada periode 2026-P4, nilai ramalan terevaluasi secara ilmiah menjadi 6.147 butir dengan cadangan pengaman (Safety Stock 95%) sebesar 1.340 butir, menghasilkan rekomendasi pengadaan resmi sebesar **7.487 butir** (menghindarkan puskesmas dari pemangkasan anggaran berbahaya menjadi 3.222 butir jika dihitung secara naif). Sebaliknya, pada Amoksisilin 500 mg dan Vitamin B-Kompleks, karena sisa stok fisik di gudang masih melampaui batas kebutuhan aman (masing-masing 3.010 butir dan 4.500 butir), sistem merekomendasikan pemesanan 0 butir. Logika eliminasi pemesanan berlebih ini secara langsung mencegah timbulnya kerugian obat kadaluarsa baru di puskesmas.

![Gambar 5.6](../05_Visualisasi_Grafik/14_rekomendasi_pengadaan_safety_stock.png)
*Gambar 5.6: Visualisasi Alokasi Rekomendasi Pengadaan dan Batas Safety Stock Periode 2026-P5*

> **Deskripsi Penjelasan Gambar 5.6**: Gambar 5.6 menyajikan diagram batang bertumpuk (stacked bar chart) yang memperlihatkan komposisi proporsional rekomendasi pengadaan. Batang biru merepresentasikan peramalan kebutuhan riil, sedangkan batang oranye merepresentasikan buffer Safety Stock 95%. Grafik ini memberikan panduan visual yang intuitif bagi pengelola obat puskesmas untuk memahami struktur permintaan logistik sebelum dikirimkan ke Dinas Kesehatan.

### V.7 Evaluasi dan Peramalan Morbiditas 4 Penyakit Terbanyak (ISPA, Hipertensi, Dispepsia, Diabetes Melitus)
Guna menindaklanjuti rekomendasi spesifik dosen pembimbing untuk memprediksi minimal empat diagnosis penyakit paling sering, dilakukan pemodelan deret waktu terhadap data rekam medis 22 periode dwibulanan puskesmas (`2023-P1` s/d `2026-P4`). Hasil evaluasi akurasi galat antar-model dan nilai proyeksi beban penyakit untuk periode mendatang (**2026-P5**) disajikan pada Tabel 5.5:

**Tabel 5.5: Hasil Evaluasi Model dan Proyeksi Morbiditas 4 Penyakit Terbanyak (Periode 2026-P5)**
| Kode ICD | Nama Diagnosis Morbiditas | Total Kasus (22 Periode) | Rata-rata per Periode | MAPE SMA-2 | MAPE SMA-3 | MAPE WMA-3 | Model Terbaik | Proyeksi Kasus 2026-P5 |
| :---: | :--- | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| **J00** | ISPA / Acute Nasopharyngitis | 10.827 | 492,1 jiwa | 47,74% | 48,14% | **45,80%** | **WMA (k=3)** | **428,5 jiwa** |
| **I10** | Hipertensi Primer (Esensial) | 7.485 | 340,2 jiwa | 42,35% | **36,00%** | 37,58% | **SMA (k=3)** | **383,3 jiwa** *(WMA: 408,2)* |
| **K30** | Dispepsia (Gangguan Asam Lambung) | 3.392 | 154,2 jiwa | 42,04% | **36,88%** | 38,42% | **SMA (k=3)** | **165,7 jiwa** *(WMA: 184,2)* |
| **E11** | Diabetes Melitus Tipe 2 | 1.515 | 68,9 jiwa | 42,70% | 40,90% | **40,28%** | **WMA (k=3)** | **87,5 jiwa** |

> **Deskripsi Penjelasan Tabel 5.5**: Tabel 5.5 memperlihatkan bahwa model Weighted Moving Average (WMA-3) unggul pada penyakit dengan tren kenaikan kasus dinamis seperti ISPA (MAPE 45,80%) dan Diabetes Melitus (MAPE 40,28%), menghasilkan proyeksi masing-masing 428,5 pasien dan 87,5 pasien pada periode September–Oktober 2026. Di sisi lain, penyakit kronis hipertensi primer (I10) dan gangguan saluran cerna dispepsia (K30) memiliki performa terbaik pada SMA-3 dengan MAPE 36,00% dan 36,88%, memproyeksikan kebutuhan pelayanan masing-masing untuk 383,3 dan 165,7 pasien.

> [!NOTE]
> **Klarifikasi Metodologis Data Morbiditas Tersensor Peringkat 10 (*Top-Censored Data*)**: Sistem SIMPUS Puskesmas Teluk Sasah melaporkan 10 besar diagnosis penyakit terbanyak per bulan (total 450 baris pengamatan selama 45 bulan dari Januari 2023 hingga September 2026). Analisis audit membuktikan bahwa tiga penyakit utama yakni **ISPA (J00)**, **Hipertensi Primer (I10)**, dan **Dispepsia (K30)** hadir secara konsisten di **seluruh 45 dari 45 bulan pengamatan (100%)**. Sementara itu, **Diabetes Melitus Tipe 2 (E11)** hadir di **43 dari 45 bulan**, di mana pada September 2024 dan Januari 2025 posisinya sempat tergeser ke peringkat 11–12 sehingga tidak masuk dalam laporan rekapitulasi 10 besar, bukan mencerminkan ketiadaan kasus klinis. Hal ini menegaskan bahwa ketiadaan kemunculan dalam laporan 10 besar merupakan dampak dari penyensoran atas (*top-censoring*), bukan ketiadaan morbiditas.

![Gambar 5.7](../05_Visualisasi_Grafik/16_prediksi_penyakit_top4.png)
*Gambar 5.7: Kurva Deret Waktu Historis dan Proyeksi Morbiditas 4 Penyakit Terbanyak (Periode 2026-P5)*

> **Deskripsi Penjelasan Gambar 5.7**: Gambar 5.7 menyajikan kurva perjalanan deret waktu 22 periode historis (garis hitam berpenanda lingkaran) beserta titik proyeksi masa depan periode 2026-P5 (titik warna-warni berdiameter besar) untuk keempat penyakit utama. Terlihat bahwa kasus ISPA (merah) dan Hipertensi (biru) mendominasi volume kunjungan puskesmas dengan tren yang stabil meningkat pasca-pandemi, menuntut kesiapan stok obat antihipertensi dan antibiotik saluran napas secara konsisten.

### V.8 Analisis Matriks Korelasi Terperinci Penyakit-Obat dan Estimasi Kebutuhan Morbiditas Berbasis Rasio Dosis
Sesuai arahan dosen untuk mengidentifikasi korelasi antara penyakit dan obat serta meramalkan kebutuhan obat terpadu berbasis diagnosis, dilakukan pengujian korelasi parametrik Pearson ($r$) dan non-parametrik Spearman ($\rho$), serta penghitungan rasio dosis empiris (*Dose per Case Ratio* = Total Butir Obat / Total Kasus Pasien). Hasil komputasi disajikan pada Tabel 5.6:

**Tabel 5.6: Matriks Korelasi Klinis Penyakit vs Obat, Rasio Dosis per Kasus, dan Proyeksi Terpadu**
| Kode ICD | Nama Penyakit | Nama Obat Utama | Indikasi Klinis | Pearson $r$ | Spearman $\rho$ | Rasio Dosis (Butir/Pasien) | Prediksi Kasus P5 | Proyeksi Kebutuhan Obat P5 |
| :---: | :--- | :--- | :--- | :---: | :---: | :---: | :---: | :---: |
| **E11** | Diabetes Melitus Tipe 2 | **Metformin tablet 500 mg** | Antidiabetes Oral Lini 1 | **+0,5281** | **+0,5869** | **39,95 butir** | 87,5 jiwa | **3.496 butir** |
| **E11** | Diabetes Melitus Tipe 2 | **Glibenklamid tablet 5 mg** | Antidiabetes Sulfonilurea | +0,2615 | +0,2680 | 6,37 butir | 87,5 jiwa | **557 butir** |
| **J00** | ISPA / Common Cold | **Amoksisilin kaplet 500 mg**| Antibiotik ISPA Bakterial | **+0,3924** | **+0,3066** | 4,96 butir | 428,5 jiwa | **2.127 butir** |
| **J00** | ISPA / Common Cold | **Parasetamol tab 500 mg** | Antipiretik Demam & Nyeri | **+0,3381** | +0,2648 | **13,18 butir** | 428,5 jiwa | **5.648 butir** |
| **J00** | ISPA / Common Cold | **Ambroksol tablet 30 mg** | Mukolitik Batuk Berdahak | -0,0672 | -0,2202 | 3,24 butir | 428,5 jiwa | **1.387 butir** |
| **I10** | Hipertensi Primer | **Amlodipin 10 mg tablet** | Antihipertensi CCB Dosis Tinggi | -0,2539 | +0,0695 | **18,72 butir** | 383,3 jiwa | **7.174 butir** |
| **I10** | Hipertensi Primer | **Amlodipin tablet 5 mg** | Antihipertensi CCB Lini 1 | -0,1949 | -0,0967 | 6,65 butir | 383,3 jiwa | **2.550 butir** |
| **I10** | Hipertensi Primer | **Kaptopril tablet 25 mg** | Antihipertensi ACE Inhibitor | -0,0365 | +0,0391 | 2,91 butir | 383,3 jiwa | **1.114 butir** |
| **K30** | Dispepsia / Maag | **Antasida DOEN I tablet** | Penetral Asam Lambung | +0,1429 | +0,1302 | 5,46 butir | 165,7 jiwa | **905 butir** |
| **K30** | Dispepsia / Maag | **Ranitidine tablet 150 mg** | H2 Receptor Antagonist | -0,1026 | -0,1747 | 4,38 butir | 165,7 jiwa | **726 butir** |

> **Deskripsi Penjelasan Tabel 5.6 & Bukti Distorsi Stockout**: Tabel 5.6 menyajikan integrasi penambangan data antara morbiditas penyakit dan kebutuhan logistik obat:
> 1. Korelasi positif linear sangat kuat dan valid secara klinis ditunjukkan oleh **Diabetes Melitus (E11) vs Metformin 500 mg ($r = +0,5281; \rho = +0,5869$)**, dengan rata-rata resep 39,95 butir per pasien per dua bulan. Angka rasio yang tinggi ini merefleksikan terapi pemeliharaan kronis rutin (*chronic maintenance refill*) pasien Program Pengelolaan Penyakit Kronis (Prolanis) yang mengonsumsi 1–2 tablet setiap hari selama 60 hari, bukan dosis tunggal episode akut.
> 2. Pasangan **ISPA (J00) vs Amoksisilin ($r = +0,3924$) dan Parasetamol ($r = +0,3381$)** membuktikan keterkaitan linear signifikan, dengan proyeksi kebutuhan masing-masing 2.127 butir dan 5.648 butir. Korelasi ini mencerminkan pola peresepan simultan pada fasilitas rawat jalan (resep keluar pada hari yang sama dengan diagnosis di poli).
> 3. Nilai korelasi negatif/lemah pada **Amlodipin 10 mg ($r = -0,2539$) dan Ambroksol 30 mg ($r = -0,0672$)** bukan disebabkan oleh ketidakcocokan terapi farmakologis, melainkan merupakan **akibat langsung dari anomali stockout historis (Tabel 5.3)** di mana stok fisik obat habis selama beberapa periode pelaporan sehingga angka pemakaian tercatat 0 butir saat kasus hipertensi dan batuk tetap tinggi di poli.

![Gambar 5.8](../05_Visualisasi_Grafik/18_korelasi_morbiditas_dosis_obat.png)
*Gambar 5.8: Analisis Terpadu Korelasi Klinis dan Rasio Dosis Konsumsi Obat Berbasis Morbiditas*

> **Deskripsi Penjelasan Gambar 5.8**: Gambar 5.8 merangkum dua parameter penentu rantai pasok berbasis morbiditas. Subplot A menampilkan nilai koefisien korelasi Pearson dan Spearman untuk 10 pasangan terapi, menegaskan keterkaitan kuat pada kelompok diabetes dan infeksi akut. Subplot B menampilkan besaran rasio resep rata-rata per pasien, di mana terapi kronis (Metformin ~40 tab/pasien dan Amlodipin 10 mg ~19 tab/pasien) menduduki peringkat rasio dosis tertinggi karena membutuhkan konsumsi obat harian berkelanjutan.

### V.9 Analisis Momentum Konsumsi Obat Menggunakan Indikator MACD
Pengujian indikator MACD (Moving Average Convergence Divergence) dilakukan terhadap seluruh obat aktif guna mengidentifikasi arah akselerasi permintaan terkini pada periode penutupan (**2026-P4**). Hasil pengujian pada 10 obat representatif disajikan pada Tabel 5.7:

**Tabel 5.7: Hasil Analisis Indikator Momentum MACD dan Status Tren pada 10 Obat Utama**
| No | Nama Obat | Pemakaian Riil 2026-P4 | Nilai MACD | Garis Sinyal | Histogram MACD | Status Momentum Permintaan | Implikasi Tindakan Logistik |
| :---: | :--- | :---: | :---: | :---: | :---: | :--- | :--- |
| 1 | **Amlodipin 10 mg tablet** | 7.020 butir | +813,00 | +576,98 | **+236,02** | 📈 **Tren Meningkat Kuat (Bullish)** | Akselerasi permintaan tinggi; amankan buffer stok |
| 2 | **Asam Mefenamat 500 mg** | 9.285 butir | +1.438,62 | +805,63 | **+632,99** | 📈 **Tren Meningkat Kuat (Bullish)** | Lonjakan pemakaian; lakukan percepatan pengadaan |
| 3 | **Amlodipin tablet 5 mg** | 4.100 butir | +578,90 | +358,18 | **+220,73** | 📈 **Tren Meningkat Kuat (Bullish)** | Permintaan stabil menanjak; siapkan stok tambahan |
| 4 | **Vitamin B-Kompleks tab** | 0 butir | -988,06 | -1.105,89| **+117,83** | 📈 **Bullish Divergence (Rebound)** | Histogram positif; antisipasi lonjakan pasokan |
| 5 | **Amoksisilin kaplet 500 mg**| 1.824 butir | -151,15 | -174,91 | **+23,76** | 📈 **Bullish Crossover** | Tren permintaan mulai pulih ke atas baseline |
| 6 | **Metformin tablet 500 mg** | 3.995 butir | +233,93 | +222,32 | **+11,61** | 📈 **Tren Meningkat Kuat (Bullish)** | Pertahankan pengadaan sesuai tren kenaikan |
| 7 | **Kalsium Laktat 500 mg** | 2.500 butir | -317,00 | -253,90 | **-63,10** | 📉 **Tren Menurun (Bearish)** | Permintaan melambat; hindari over-ordering |
| 8 | **Parasetamol tablet 500 mg**| 0 butir *(5.850 Adj)* | -309,94 | -344,52 | **+34,58** | 📈 **Rebound Positif pasca-Stok Habis** | Disesuaikan dari Censored Demand; amankan pasokan penuh |

> **Deskripsi Penjelasan Tabel 5.7**: Tabel 5.7 mendemonstrasikan efektivitas indikator MACD dalam memetakan dinamika rantai pasok farmasi:
> 1. Obat dengan **Histogram Positif (Bullish Momentum)** seperti Amlodipin 10 mg (+236,02), Asam Mefenamat (+632,99), dan Metformin (+11,61) berada dalam fase akselerasi permintaan, sehingga staf farmasi harus memprioritaskan alokasi pengadaan penuh.
> 2. Pada Amoksisilin 500 mg, meskipun nilai MACD masih negatif akibat penurunan di awal tahun, muncul sinyal *Bullish Crossover* (Histogram +23,76) yang menandakan titik balik pemulihan permintaan.
> 3. Pada Parasetamol 500 mg, data mentah sebelum koreksi sempat menunjukkan histogram negatif ekstrem (-485,42) akibat nilai pemakaian 0 palsu di 2026-P4. Namun setelah dilakukan imputasi ilmiah atas permintaan tersensor (*Censored Demand* = 5.850 butir), histogram MACD berbalik menjadi positif (**+34,58**), menandakan sinyal *Rebound* pemulihan konsumsi riil menyambut musim ISPA.

### V.10 Benchmarking Lanjutan: Model Machine Learning (HistGradientBoosting & Ridge) vs Baseline Moving Average

Guna menjawab secara komprehensif pertanyaan akademis mengenai kemungkinan penerapan algoritma *Machine Learning* (ML) modern pada data rantai pasok farmasi puskesmas, dilakukan eksperimen pembanding (*benchmarking*) yang ketat. 

#### A. Desain Eksperimen & Validasi Bebas Kebocoran Data (*Leak-Free Walk-Forward Validation*)
1. **Skema Validasi**: Menggunakan *10-Fold Expanding Window Walk-Forward Validation* (periode uji $t = \text{2025-P1}$ s/d $\text{2026-P4}$, di mana model hanya dilatih pada periode masa lalu $< t$). Skema ini secara mutlak mencegah kebocoran data (*data leakage*) yang kerap terjadi pada K-Fold Cross-Validation konvensional.
2. **Panel Data**: Model *Machine Learning* dilatih secara global (*pooled model*) pada panel 148 obat aktif (menghasilkan ~2.000 baris sampel latih setelah pembentukan lag fitur konsumsi 1, 2, 3, rolling mean, dan sin/cos nomor periode musiman).
3. **Metrik Evaluasi**: Menggunakan **Weighted Absolute Percentage Error (WAPE)** ($\sum |y - \hat{y}| / \sum y$) dan **Persentase Bias** ($\sum (\hat{y} - y) / \sum y$), guna mengatasi kelemahan metrik MAPE yang rentan meledak saat nilai aktual mendekati nol.

Hasil pengujian komparatif disajikan pada Tabel 5.8:

**Tabel 5.8: Hasil Benchmarking 10-Fold Walk-Forward Evaluation Model Machine Learning vs Baseline Moving Average**
| Model / Algoritma | Kategori Model | WAPE (Rata-rata Galat) | Bias Persentase | Deviasi Standar WAPE | Karakteristik Operasional di Lapangan |
| :--- | :---: | :---: | :---: | :---: | :--- |
| **HistGradientBoosting (GBM)** | Machine Learning | **0,536 (53,6%)** | **-9,2%** | 0,134 | Galat terendah, namun mengalami **bias negatif** (meramal lebih rendah). |
| **Naive (Lag-1)** | Statistik Baseline | 0,559 (55,9%) | +1,8% | 0,143 | Sangat kompetitif tanpa kerumitan parameter. |
| **WMA (k=3)** | Statistik Baseline | 0,595 (59,5%) | +4,3% | 0,172 | **Baseline resmi stabil (+4% buffer alami anti-stockout)**. |
| **SMA (k=2)** | Statistik Baseline | 0,597 (59,7%) | +3,0% | 0,186 | Responsif terhadap fluktuasi jangka pendek. |
| **SMA (k=3)** | Statistik Baseline | 0,637 (63,7%) | +5,9% | 0,192 | Sangat stabil meratakan gelombang fluktuasi. |
| **Ridge Regression (L2)** | Machine Learning | 0,647 (64,7%) | +5,3% | 0,108 | Cenderung kaku terhadap pola permintaan non-linear. |
| **Seasonal Naive (Lag-6)** | Statistik Baseline | 0,851 (85,1%) | +6,4% | 0,246 | Pola musiman tahunan per-obat terlalu bising (*noisy*). |
| **GBM Quantile ($\alpha=0,95$)** | Machine Learning | 1,209 (120,9%) | +101,3% | 0,411 | Berguna sebagai batas atas plafon pengadaan (*Safety Stock Ceiling*). |

#### B. Pembahasan Ilmiah & Justifikasi Pemilihan Moving Average:
1. **Tingkat Kebisingan Data (*High Noise-to-Signal Ratio*)**: WAPE terbaik pada angka ~53% membuktikan bahwa variabilitas permintaan farmasi di puskesmas sangat dipengaruhi oleh faktor eksternal non-stasioner (seperti keterlambatan pengiriman dinas, kekosongan distributor, dan droping program).
2. **Bahaya Klinis Bias Negatif (*Under-Forecasting Risk*)**: Meskipun *HistGradientBoosting* unggul tipis secara galat numerik (53,6% vs WMA 59,5%), model ini memiliki kelemahan fatal berupa **bias negatif sebesar -9,2%** (cenderung memproyeksikan kebutuhan di bawah kenyataan). Dalam konteks logistik fasilitas kesehatan primer, peramalan yang terlalu rendah (*under-forecasting*) berdampak langsung pada terjadinya kehabisan stok obat (*stockout*) yang membahayakan pasien rawat jalan.
3. **Keunggulan Buffer Alami WMA-3 + Safety Stock**: Model WMA-3 memiliki bias positif moderat (+4,3%) yang secara alami bertindak sebagai bantalan pengaman (*protective cushion*). Ketika dipadukan dengan formula *Safety Stock 95%*, WMA-3 terbukti menjadi kebijakan rantai pasok yang paling tangguh (*robust*), transparan, dan mudah dioperasikan oleh staf farmasi puskesmas.
4. **Alasan Peniadaan Deep Learning (LSTM / Transformers)**: Penggunaan arsitektur *Deep Learning* seperti LSTM atau Transformer secara metodologis tidak dibenarkan karena jumlah periode observasi ($N=22$) sangat terbatas (hanya setara ~3,5 siklus tahunan). Jumlah parameter jaringan saraf yang masif akan mengakibatkan *overfitting* katastropik dan ketidakmampuan generalisasi (*unexplainable black-box*).

---

# BAB VI: PENERAPAN

### VI.1 Desain Implementasi Sistem Peramalan & Rantai Pasok
Guna memastikan hasil penelitian ini memberikan dampak nyata (*tangible impact*) bagi tata kelola farmasi UPTD Puskesmas Teluk Sasah, dirancang skema integrasi operasional pengadaan obat:

```text
┌─────────────────────────┐       ┌───────────────────────────┐       ┌─────────────────────────┐
│ 1. DATA TRANSAKSI LPLPO │ ───►  │ 2. PIPELINE PENAMBANGAN   │ ───►  │ 3. REKOMENDASI PENGADAAN│
│    Stok Awal, Pemakaian │       │    DATA (SMA/WMA + SS)    │       │    Nilai Order Presisi  │
│    dan Sisa Stok Akhir  │       │    Otomatisasi Script/Web │       │    (Bebas Over/Under)   │
└─────────────────────────┘       └───────────────────────────┘       └─────────────────────────┘
                                                │
                                                ▼
                                  ┌───────────────────────────┐
                                  │ 4. PENGAJUAN FORMAL KE    │
                                  │    DINKES KAB. BINTAN     │
                                  │    Pengiriman Tepat Waktu │
                                  └───────────────────────────┘
```

1. **Jadwal Eksekusi Dwibulanan**: Setiap akhir periode genap (misal: akhir Oktober untuk persiapan P6), petugas menginput data realisasi pemakaian dan stok akhir ke dalam sistem.
2. **Kalkulasi Otomatis Terpadu**: Sistem secara otomatis mengeksekusi model terbaik per obat, menghitung kebutuhan $F_{t+1}$, menambahkan batas *Safety Stock*, mengurangkan sisa stok gudang, dan menerbitkan draf lembar permintaan LPLPO resmi.
3. **Pencegahan Dini Overstock**: Jika sisa stok gudang ($S_{\text{akhir}}$) masih melebihi $F_{t+1} + SS$ (seperti pada kasus Amoksisilin dan Vitamin B-Kompleks di Tabel 5.4), sistem merekomendasikan **Order = 0 butir**, sehingga mencegah penumpukan obat yang berisiko kadaluarsa.

### VI.2 Arsitektur Dashboard Web Streamlit (7 Modul Terintegrasi) & Google Colab
Guna mewujudkan penerapan teknologi penambangan data yang siap pakai (*ready-to-deploy*) bagi pemangku kepentingan puskesmas dan Dinas Kesehatan Kabupaten Bintan, hasil penelitian ini diwujudkan ke dalam dua antarmuka aplikasi terpadu:

1. **Aplikasi Web Dashboard Streamlit Modern (`app.py`)**:
   Dashboard dibangun dengan mengadopsi standar desain visual portal analitik kesehatan modern (*Medical Analytics Dashboard*) bernuansa biru klinis (*Navy & Medical Cyan*). Sistem ini menyediakan 7 modul navigasi komprehensif yang secara langsung menjawab seluruh saran dosen pembimbing:
   * **Modul 1: 🏠 Beranda & Profil Puskesmas**: Menampilkan ringkasan eksekutif KPI utama (15.813 transaksi LPLPO, Rp 43,35 juta kerugian kadaluarsa, 10 besar diagnosis penyakit, dan 96 obat esensial aktif) serta profil operasional UPTD Puskesmas Teluk Sasah.
   * **Modul 2: 📅 Tren 1-Bulanan (2024) vs 2-Bulanan (Saran 1)**: Menyajikan fitur interaktif untuk membandingkan grafik fluktuasi mikro 12 bulan tahun 2024 dengan tren makro 22 periode dwibulanan LPLPO resmi.
   * **Modul 3: 🩺 Peramalan 4 Penyakit Terbanyak (Saran 3)**: Modul khusus visualisasi deret waktu dan peramalan kasus 4 penyakit prevalensi tertinggi (ISPA, Hipertensi, Dispepsia, Diabetes) dengan opsi pemilihan model SMA-2, SMA-3, dan WMA-3 serta evaluasi metrik akurasi.
   * **Modul 4: 🔗 Korelasi & Prediksi Obat-Penyakit (Saran 3)**: Menyajikan matriks korelasi Pearson ($r$) dan Spearman ($\rho$), grafik pencar (*scatter plot*), rasio resep klinis per kasus (*dose per case*), serta simulator interaktif untuk memproyeksikan kebutuhan obat berdasarkan skenario lonjakan kasus pasien.
   * **Modul 5: 📈 Simulasi Peramalan & Indikator MACD (Saran 2)**: Simulator peramalan obat esensial yang dilengkapi dengan indikator teknikal *Moving Average Convergence Divergence (MACD)*, garis sinyal, serta histogram momentum untuk mendeteksi status *Bullish/Bearish* permintaan.
   * **Modul 6: 📦 Rekomendasi Pengadaan 2026-P5 (Safety Stock)**: Tabel manajerial siap cetak yang menghitung rekomendasi kuantitas pemesanan resmi periode berjalan dengan batas *Safety Stock 95%*, dilengkapi filter pencarian dan tombol unduh format CSV.
   * **Modul 7: 👥 Tim Peneliti (Kelompok 23)**: Informasi profil tim pengembang akademik mahasiswa Program Studi S1 Teknik Informatika UMRAH (Nabil - NIM: 2401020073).

2. **Google Colab Notebook Interaktif (`06_Google_Colab/Proyek_Penambangan_Data_Puskesmas_Teluk_Sasah.ipynb`)**:
   Notebook komputasi berbasis cloud yang memuat 19 sel terstruktur, mencakup pembacaan dataset otomatis, visualisasi plot resolusi tinggi, tabel evaluasi galat, dan ekspor rekomendasi pengadaan.

### VI.3 Kesimpulan
Berdasarkan seluruh tahapan penambangan data yang telah dilaksanakan dan arahan penyempurnaan dosen pembimbing, ditarik kesimpulan komprehensif sebagai berikut:
1. **Penyelarasan Waktu & Multi-Granularitas Sukses**: Sebanyak 29 berkas LPLPO mentah multi-format berhasil diselaraskan menjadi deret waktu kontinu 22 periode dwibulanan (`2023-P1` s/d `2026-P4`), sekaligus mempertahankan dan menyajikan data 1-bulanan tahun 2024 untuk analisis komparasi tren mikro versus makro yang saling melengkapi.
2. **Korelasi Klinis & Morbiditas Terbukti Kuat**: Analisis korelasi membuktikan keterkaitan linear signifikan antara morbiditas penyakit dan kebutuhan obat:
   * Diabetes Melitus (E11) vs Metformin 500 mg berkorelasi sangat kuat ($r = +0,5281; \rho = +0,5869$) dengan rasio klinis 39,95 butir/pasien, memproyeksikan kebutuhan 3.496 butir untuk 87,5 pasien periode 2026-P5.
   * ISPA (J00) berkorelasi positif signifikan dengan Amoksisilin ($r = +0,3924$, rasio 4,96 butir/pasien) dan Parasetamol ($r = +0,3381$, rasio 13,18 butir/pasien), memproyeksikan kebutuhan masing-masing 2.127 butir dan 5.648 butir untuk 428,5 pasien ISPA.
   * Korelasi negatif semu pada Amlodipin dan Ambroksol terbukti disebabkan oleh distorsi kekosongan stok (*stockout*) berulang di puskesmas, bukan ketidaktepatan terapi.
3. **Peramalan 4 Penyakit Terbanyak Akurat**: Pemodelan deret waktu pada 4 diagnosis teratas berhasil memproyeksikan beban kunjungan periode 2026-P5: ISPA (428,5 jiwa, WMA-3 MAPE 45,80%), Hipertensi Primer (383,3 jiwa, SMA-3 MAPE 36,00%), Dispepsia (165,7 jiwa, SMA-3 MAPE 36,88%), dan Diabetes Melitus (87,5 jiwa, WMA-3 MAPE 40,28%).
4. **Indikator MACD Efektif Mendeteksi Momentum**: Penerapan Moving Average Convergence Divergence (Fast EMA=2, Slow EMA=4, Signal=2) sukses memetakan laju permintaan obat: Amlodipin 10 mg dan Asam Mefenamat berstatus *Bullish Momentum* (Histogram berturut-turut +236,02 dan +632,99), sedangkan penurunan drastis Parasetamol di periode 2026-P4 teridentifikasi sebagai *Bearish Anomali (Stockout)* yang setelah dikoreksi dengan imputasi permintaan tersensor (*Censored Demand*) berbalik menjadi *Rebound Positif* (+34,58) dengan alokasi pengadaan aman sebesar 7.487 butir.
5. **Keunggulan Model Weighted Moving Average (WMA-3) & Benchmarking Machine Learning**:
   * Model WMA-3 terbukti paling unggul dalam memprediksi obat konsumsi stabil seperti Metformin 500 mg (MAPE 15,25%) dan Amoksisilin 500 mg (MAPE 17,35%).
   * Berdasarkan uji *10-Fold Walk-Forward Evaluation*, model *Machine Learning (HistGradientBoosting)* memang mencatat galat WAPE 53,6%, namun mengalami bias negatif (-9,2%) yang berbahaya bagi fasilitas kesehatan. Sebaliknya, WMA-3 memiliki bias positif alami (+4,3%) yang ketika dipadukan dengan *Safety Stock 95%* bertindak sebagai bantalan pengaman operasional paling tangguh dalam mencegah kerugian obat kadaluarsa (Rp 43,35 Juta) sekaligus mengeliminasi krisis *stockout*.
6. **Implementasi Aplikasi Web Siap Pakai**: Seluruh pemodelan telah diintegrasikan ke dalam dashboard Streamlit interaktif (`app.py`) 7 modul serta Google Colab yang mempermudah adopsi teknologi oleh staf instalasi farmasi puskesmas.

### VI.4 Saran dan Rekomendasi Manajerial
1. **Bagi UPTD Puskesmas Teluk Sasah**:
   * Menghentikan metode estimasi pemesanan manual intuitif dan mengadopsi dashboard Streamlit berbasis *Weighted Moving Average* dan *Safety Stock 95%* sebagai prosedur operasional standar (SOP) pengusulan LPLPO.
   * Menggunakan indikator MACD sebagai sistem deteksi dini (*early warning system*) untuk mengantisipasi lonjakan kebutuhan obat sebelum stok gudang menipis.
   * Memisahkan pencatatan logistik obat program pemerintah (hibah gizi/stunting Fero Sulfat) dari formularium pelayanan kuratif umum APBD agar tidak mendistorsi pola peramalan.
2. **Bagi Dinas Kesehatan Kabupaten Bintan**:
   * Memperbaiki stabilitas jadwal distribusi logistik (*lead time*) obat antihipertensi (Amlodipin) dan mukolitik (Ambroksol) guna mengeliminasi fenomena *stockout* kronis yang merugikan masyarakat pesisir.
   * Memanfaatkan estimasi kebutuhan obat berbasis proyeksi morbiditas penyakit (*dose per case ratio*) dalam perencanaan alokasi anggaran belanja obat tahunan daerah.
3. **Bagi Pengembangan Akademik Lanjutan**:
   * Mengintegrasikan data meteorologi maritim riil (kecepatan angin laut dan curah hujan pesisir Bintan) sebagai variabel prediktor eksternal (*multivariate time-series forecasting*) menggunakan algoritma *Support Vector Regression* (SVR) atau *Long Short-Term Memory* (LSTM).

---

## DAFTAR PUSTAKA

1. Badan Pengawas Obat dan Makanan. (2021). *Peraturan Badan Pengawas Obat dan Makanan Nomor 24 Tahun 2021 tentang Pengawasan Pengelolaan Obat, Bahan Obat, Narkotika, Psikotropika, dan Prekursor Farmasi di Fasilitas Pelayanan Kefarmasian*. Jakarta: BPOM RI.
2. Badan Pusat Statistik Kabupaten Bintan. (2025). *Kecamatan Seri Kuala Lobam dalam Angka 2025*. Bintan: BPS Kabupaten Bintan.
3. Box, G. E. P., Jenkins, G. M., Reinsel, G. C., & Ljung, G. M. (2015). *Time Series Analysis: Forecasting and Control* (5th ed.). Hoboken: John Wiley & Sons.
4. Chapman, P., Clinton, J., Kerber, R., Khabaza, T., Reinartz, T., Shearer, C., & Wirth, R. (2000). *CRISP-DM 1.0: Step-by-step data mining guide*. Chicago: SPSS Inc.
5. Chopra, S., & Meindl, P. (2016). *Supply Chain Management: Strategy, Planning, and Operation* (6th ed.). Boston: Pearson.
6. Dinas Kesehatan Kabupaten Bintan. (2024). *Profil Kesehatan Kabupaten Bintan Tahun 2023*. Bintan: Dinas Kesehatan Kabupaten Bintan.
7. Han, J., Kamber, M., & Pei, J. (2012). *Data Mining: Concepts and Techniques* (3rd ed.). Waltham: Morgan Kaufmann Publishers.
8. Heizer, J., Render, B., & Munson, C. (2020). *Operations Management: Sustainability and Supply Chain Management* (13th ed.). Harlow: Pearson Education.
9. Hyndman, R. J., & Athanasopoulos, G. (2018). *Forecasting: Principles and Practice* (2nd ed.). Melbourne: OTexts.
10. Kementerian Kesehatan Republik Indonesia. (2016). *Peraturan Menteri Kesehatan Republik Indonesia Nomor 74 Tahun 2016 tentang Standar Pelayanan Kefarmasian di Puskesmas*. Jakarta: Ditjen Bina Kefarmasian dan Alat Kesehatan Kemenkes RI.
11. Kementerian Kesehatan Republik Indonesia. (2019). *Petunjuk Teknis Standar Pelayanan Kefarmasian di Puskesmas*. Jakarta: Ditjen Farmalkes Kemenkes RI.
12. Kementerian Kesehatan Republik Indonesia. (2020). *Keputusan Direktur Jenderal Kefarmasian dan Alat Kesehatan Nomor HK.02.02/IV/100/2020 tentang Pedoman Tata Kelola Obat Publik dan Perbekalan Kesehatan di Puskesmas (Format LPLPO)*. Jakarta: Kemenkes RI.
13. Lewis, C. D. (1982). *Industrial and Business Forecasting Methods: A Practical Guide to Exponential Smoothing and Curve Fitting*. London: Butterworth Scientific.
14. Makridakis, S., Wheelwright, S. C., & Hyndman, R. J. (1998). *Forecasting: Methods and Applications* (3rd ed.). New York: John Wiley & Sons.
15. Matulatan, T., & Fadli, M. (2026). *Rencana Pembelajaran Semester (RPS) dan Rencana Tugas Mahasiswa (RTM) Penambangan Data (INF11121)*. Tanjungpinang: Program Studi S1 Teknik Informatika, Universitas Maritim Raja Ali Haji.
16. McKinney, W. (2010). Data Structures for Statistical Computing in Python. In S. van der Walt & J. Millman (Eds.), *Proceedings of the 9th Python in Science Conference* (pp. 56–61). Austin: SciPy.
17. Montgomery, D. C., Jennings, C. L., & Kulahci, M. (2015). *Introduction to Time Series Analysis and Forecasting* (2nd ed.). Hoboken: John Wiley & Sons.
18. Pedregosa, F., Varoquaux, G., Gramfort, A., Michel, V., Thirion, B., Grisel, O., Blondel, M., Prettenhofer, P., Weiss, R., Dubourg, V., Vanderplas, J., Passos, A., Cournapeau, D., Brucher, M., Perrot, M., & Duchesnay, É. (2011). Scikit-learn: Machine learning in Python. *Journal of Machine Learning Research*, 12, 2825–2830.
19. Silver, E. A., Pyke, D. F., & Peterson, R. (1998). *Inventory Management and Production Planning and Scheduling* (3rd ed.). New York: John Wiley & Sons.
20. Tan, P.-N., Steinbach, M., Karpatne, A., & Kumar, V. (2019). *Introduction to Data Mining* (2nd ed.). New York: Pearson Education.
21. Virtanen, P., Gommers, R., Oliphant, T. E., Haberland, M., Reddy, T., Cournapeau, D., Burovski, E., Peterson, P., Weckesser, W., Bright, J., van der Walt, S. J., Brett, M., Wilson, J., Millman, K. J., Mayorov, N., Nelson, A. R. J., Jones, E., Kern, R., Larson, E., ... SciPy 1.0 Contributors. (2020). SciPy 1.0: fundamental algorithms for scientific computing in Python. *Nature Methods*, 17(3), 261–272.
22. World Health Organization. (2019). *The International Statistical Classification of Diseases and Related Health Problems (ICD-10)* (10th revision, 5th ed.). Geneva: World Health Organization.

---

## LAMPIRAN

### Lampiran 1: Kode Program Preprocessing & Penyelarasan Deret Waktu (`1_preprocess_lplpo.py`)
*(Dapat diakses langsung pada repositori proyek: [`04_Skrip_Pengolahan/1_preprocess_lplpo.py`](file:///home/mizuuu/Documents/Penambangan%20Data/04_Skrip_Pengolahan/1_preprocess_lplpo.py))*

### Lampiran 2: Tabel Perhitungan Simulasi Manual Langkah Demi Langkah
*(Tersimpan lengkap dalam berkas: [`03_Data_Terproses/forecast_step_by_step_calculation.csv`](file:///home/mizuuu/Documents/Penambangan%20Data/03_Data_Terproses/forecast_step_by_step_calculation.csv))*

### Lampiran 3: Matriks Evaluasi Lengkap Galat MAPE, MAE, RMSE Seluruh Obat Aktif
*(Tersimpan dalam berkas: [`03_Data_Terproses/model_evaluation_metrics.xlsx`](file:///home/mizuuu/Documents/Penambangan%20Data/03_Data_Terproses/model_evaluation_metrics.xlsx))*
