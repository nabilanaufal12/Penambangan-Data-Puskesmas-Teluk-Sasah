# HALAMAN JUDUL & IDENTITAS TIM

### LAPORAN AKHIR PROYEK MATA KULIAH PENAMBANGAN DATA (INF11121 – 5 SKS)
#### SEMESTER GANJIL 2026/2027

## PREDIKSI KEBUTUHAN STOK OBAT DAN ANALISIS TREN MORBIDITAS MENGGUNAKAN MOVING AVERAGE DAN MACD DI PUSKESMAS TELUK SASAH

**Disusun Oleh — Kelompok 23:**  
* **Nabil (Ketua Tim – NIM: 2401020073)**  
* **Balqis Manisa (NIM: 2401020069)**  
* **Jian Safitri Wibowo (NIM: 2401020056)**  
* **Annisa Darmawan (NIM: 2401020087)**  
* **Yehezkiel Alman Siambaton (NIM: 2401020110)**  

**Dosen Pengampu:**  
1. Bapak Tekad Matulatan, S.Sos., S.Kom., M.Info.Tech. (NIDN: 0021057404)  
2. Bapak Muhamad Fadli, S.Kom., M.Kom. (NIDN: 1018079001)  

**PROGRAM STUDI S1 TEKNIK INFORMATIKA**  
**FAKULTAS TEKNIK DAN TEKNOLOGI KEMARITIMAN**  
**UNIVERSITAS MARITIM RAJA ALI HAJI (UMRAH)**  
**TANJUNGPINANG**  
**2026**  

---

# KATA PENGANTAR

Puji syukur kehadirat Tuhan Yang Maha Esa atas segala limpahan rahmat, hidayah, dan karunia-Nya, sehingga tim penulis dari Kelompok 23 dapat menyelesaikan Laporan Akhir Proyek Mata Kuliah Penambangan Data (INF11121) ini dengan baik. Laporan ini disusun untuk memenuhi tugas besar semester ganjil tahun akademik 2026/2027 pada Program Studi S1 Teknik Informatika, Fakultas Teknik dan Teknologi Kemaritiman, Universitas Maritim Raja Ali Haji (UMRAH).

Penyusunan proyek bertajuk **"Prediksi Kebutuhan Stok Obat dan Analisis Tren Morbiditas Menggunakan Moving Average dan MACD di Puskesmas Teluk Sasah"** ini tidak lepas dari bimbingan, dorongan, serta bantuan dari berbagai pihak. Oleh karena itu, pada kesempatan ini tim penulis menyampaikan ucapan terima kasih yang sebesar-besarnya kepada:

1. **Bapak Tekad Matulatan, S.Sos., S.Kom., M.Info.Tech.** dan **Bapak Muhamad Fadli, S.Kom., M.Kom.**, selaku dosen pengampu mata kuliah Penambangan Data, yang telah memberikan arahan metodologis, bimbingan kerangka kerja CRISP-DM, serta pengujian algoritma *Moving Average* dan MACD dengan penuh kesabaran dan dedikasi.
2. **dr. Kurniawan, M.H.**, selaku Kepala UPTD Puskesmas Teluk Sasah, beserta **Ibu Apt. Siti Rahmawati, S.Farm.**, selaku Penanggung Jawab Farmasi, atas keterbukaan, izin penelitian, serta dukungan penuh dalam penyediaan 29 berkas LPLPO historis dan rekapitulasi morbiditas SIMPUS.
3. Orang tua serta keluarga tercinta atas segala doa, restu, dan dukungan moril maupun materil yang senantiasa menyertai langkah studi kami.
4. Rekan-rekan mahasiswa Program Studi S1 Teknik Informatika UMRAH Angkatan 2024 yang telah memberikan masukan, diskusi konstruktif, serta semangat selama proses penyelesaian proyek ini.

Penulis menyadari sepenuhnya bahwa laporan akhir ini masih memiliki keterbatasan, baik dari segi kedalaman teknis maupun cakupan implementasi. Oleh karena itu, segala kritik, saran, dan masukan yang membangun dari pembaca sangat kami harapkan demi kesempurnaan karya-karya ilmiah di masa mendatang. Semoga sistem PRABOTIK dan laporan ini dapat memberikan kontribusi nyata bagi efisiensi logistik kesehatan di UPTD Puskesmas Teluk Sasah dan dunia akademik.

Tanjungpinang, Oktober 2026  

**Tim Penulis (Kelompok 23)**

---

# DAFTAR ISI

* **HALAMAN JUDUL** .................................................................................... i
* **KATA PENGANTAR** ................................................................................ ii
* **DAFTAR ISI** ............................................................................................... iii
* **DAFTAR TABEL** ........................................................................................ v
* **DAFTAR GAMBAR** ................................................................................... vi
* **BAB I: BUSINESS UNDERSTANDING** .................................................... 1
  * I.1 Latar Belakang Mitra .............................................................................. 1
  * I.2 Permasalahan Riil Mitra (Tiga Paradoks Sistemik) .............................. 1
  * I.3 Tujuan Penambangan Data .................................................................... 2
  * I.4 Lini Waktu Pelaksanaan & Pembagian Kerja (Gantt Chart & PIC) ...... 3
* **BAB II: DATA UNDERSTANDING** ......................................................... 4
  * II.1 Teknik Pengumpulan Data .................................................................... 4
  * II.2 Jenis dan Kamus Data .......................................................................... 4
  * II.3 Exploratory Data Analysis (EDA) & Pola Sebaran Data .................... 5
* **BAB III: PREPROCESS (DATA PREPARATION)** .................................. 8
  * III.1 Penyelesaian Masalah 1: Inkonsistensi Periode Waktu Pelaporan .... 8
  * III.2 Penyelesaian Masalah 2: Pembersihan Artefak Header dan Footer .... 9
  * III.3 Penyelesaian Masalah 3: Penanganan *Missing Value* Harga ............ 10
  * III.4 Penyelesaian Masalah 4: Pemisahan Anomali Obat Program Gizi ..... 11
  * III.5 Penyelesaian Masalah 5: Rekonstruksi *Censored Demand* Parasetamol 12
  * III.6 Penyelesaian Masalah 6: Transformasi Matriks Deret Waktu & Lensa .. 13
* **BAB IV: MODELING** ................................................................................ 15
  * IV.1 Konsep dan Formulasi Deret Waktu Moving Average ........................ 15
  * IV.2 Simulasi Perhitungan Matematis Langkah Demi Langkah ................ 16
  * IV.3 Formulasi Indikator Momentum MACD Dwibulanan (2, 4, 2) .............. 18
  * IV.4 Formulasi Integrasi Morbiditas SIMPUS ($R_{i,j}$) .................................. 19
  * IV.5 Formulasi Batas Persediaan Pengaman (Safety Stock & Ceiling) ........ 20
* **BAB V: EVALUATION** .............................................................................. 22
  * V.1 Metrik Evaluasi Akurasi Deret Waktu .................................................. 22
  * V.2 Hasil Evaluasi 10-Fold Walk-Forward Validation (149 Obat Aktif) ... 22
  * V.3 Evaluasi Rekonstruksi Permintaan Tersensor (Parasetamol P4) .......... 24
  * V.4 Evaluasi Korelasi Klinis Morbiditas SIMPUS ....................................... 24
  * V.5 Rekomendasi Alokasi Pengadaan P5 (September–Oktober 2026) ....... 25
* **BAB VI: PENERAPAN (DEPLOYMENT)** .............................................. 26
  * VI.1 Desain Implementasi Sistem PRABOTIK ......................................... 26
  * VI.2 Alur Kerja Arsitektur *Dual-Portal* (Farmasi vs Kepala) .................... 27
  * VI.3 Kesimpulan Akhir (Sintesis 6 Fase CRISP-DM) ................................ 29
  * VI.4 Saran Pengembangan & Rekomendasi Manajerial ........................... 30
* **LAMPIRAN: Daftar Artefak Pendukung & Dokumentasi Sistem** ............ 32
* **DAFTAR PUSTAKA** .................................................................................. 34

---

# DAFTAR TABEL

* **Tabel 1.1** Lini Waktu Pelaksanaan Proyek Penambangan Data Kelompok 23 ..... 3
* **Tabel II.1** Kamus Data Atribut Transaksi LPLPO ........................................... 4
* **Tabel II.2** Kamus Data Morbiditas SIMPUS (10 Besar Penyakit ICD-10) ........ 5
* **Tabel II.3** Statistik Deskriptif dan Koefisien Variasi Top 15 Obat di Puskesmas .. 6
* **Tabel III.1** Pemetaan Standardisasi 22 Periode Dwibulanan Kontinu ............... 9
* **Tabel III.2** Hasil Pembersihan Baris Artefak pada Dokumen Berita Acara ED ... 10
* **Tabel III.3** Pemisahan Karakteristik Obat Program vs Obat Kuratif Poli ........ 11
* **Tabel III.4** Dampak Rekonstruksi *Censored Demand* Parasetamol 500 mg ..... 13
* **Tabel III.5** Spesifikasi Matriks Keluaran Tahap *Preprocess* .............................. 14
* **Tabel IV.1** Jejak Perhitungan Deret Waktu Parasetamol 500 mg ....................... 17
* **Tabel IV.2** Jejak Perhitungan Deret Waktu Metformin 500 mg ........................... 18
* **Tabel IV.3** Matriks Klasifikasi Status Sinyal Momentum MACD (2, 4, 2) .......... 19
* **Tabel V.1** Hasil Benchmarking 6 Model pada 10-Fold Walk-Forward Validation .. 23
* **Tabel V.2** Komparasi Evaluasi Perencanaan Parasetamol 500 mg .................... 24
* **Tabel V.3** Hasil Evaluasi Korelasi Statistik dan Rasio Dosis per Kasus ................ 25
* **Tabel V.4** Rekomendasi Pengadaan Obat Prioritas Periode 2026-P5 ................. 26
* **Tabel VI.1** Pemetaan Integrasi Modul Analitik CRISP-DM ke Kontroler Laravel .. 27
* **Tabel VI.2** Matriks Perbandingan Fungsional Arsitektur *Dual-Portal* ............... 28
* **Tabel VI.3** Daftar Artefak Dokumentasi Visual dan Berkas Pendukung ........... 31

---

# DAFTAR GAMBAR

* **Gambar 2.1** Alur Transformasi Data Mentah LPLPO Menjadi Matriks Waktu ... 8
* **Gambar 3.1** Grafik Perbandingan Tren *Unadjusted* vs Imputasi Parasetamol ... 12
* **Gambar 4.1** Skema Alir Perhitungan Indikator Momentum MACD (2, 4, 2) ...... 18
* **Gambar 5.1** Diagram Sebaran WAPE dan Bias pada 10-Fold Walk-Forward ....... 23
* **Gambar VI.1** Arsitektur Perangkat Lunak *Dual-Portal* PRABOTIK (Laravel 11) .. 26

---

---

# BAB I: BUSINESS UNDERSTANDING

## I.1 Latar Belakang Mitra

UPTD Puskesmas Teluk Sasah merupakan fasilitas kesehatan tingkat pertama (FKTP) milik pemerintah daerah yang terletak di Kecamatan Seri Kuala Lobam, Kabupaten Bintan, Provinsi Kepulauan Riau. Dengan wilayah kerja yang meliputi kawasan padat penduduk serta kawasan industri strategis—termasuk Desa Teluk Sasah, Kelurahan Teluk Lobam, dan Kelurahan Tanjung Permai—Puskesmas Teluk Sasah memegang tanggung jawab besar dalam menyelenggarakan pelayanan kesehatan dasar secara komprehensif, promotif, preventif, kuratif, dan rehabilitatif.

Dalam aspek operasional logistik farmasi, Puskesmas Teluk Sasah mengandalkan mekanisme pengelolaan persediaan berkala menggunakan instrumen Laporan Pemakaian dan Lembar Permintaan Obat (LPLPO). Setiap periode dwibulanan (bimonthly), pengelola gudang farmasi menyusun rekapitulasi stok awal, penerimaan, pemakaian, dan sisa stok fisik yang kemudian diajukan ke Gudang Farmasi Dinas Kesehatan Kabupaten Bintan sebagai dasar alokasi distribusi suplai obat berikutnya. Kendati demikian, siklus perencanaan konvensional ini selama bertahun-tahun sangat bergantung pada estimasi manual berbasis kebiasaan historis (*heuristic-based planning*) tanpa didukung oleh pemodelan analitik data yang objektif dan saintifik.

## I.2 Permasalahan Riil Mitra (Tiga Paradoks Sistemik)

Berdasarkan audit data historis tata kelola logistik dan rekam medis di UPTD Puskesmas Teluk Sasah, teridentifikasi tiga paradoks sistemik utama yang mendasari urgensi penelitian penambangan data ini:

1. **Paradoks 1 (*Overstock* & Kerugian Akibat Obat Kadaluarsa/ED):**  
   Perencanaan manual yang tidak akurat memicu fenomena *over-budgeting* pengadaan obat. Berdasarkan Berita Acara Audit historis, tercatat akumulasi kerugian fisik obat dan alat kesehatan kadaluarsa (*Expired Date*) mencapai **Rp 43.348.626,-** yang bersumber dari anggaran APBD dan DAK. Tren kerugian ini menunjukkan eskalasi yang mengkhawatirkan, yakni meningkat dari Rp 9,1 juta pada tahun 2023 menjadi Rp 22,9 juta pada tahun 2025, akibat lemahnya pemantauan rotasi berbasis prinsip *First Expired First Out* (FEFO).
2. **Paradoks 2 (*Understock* & Krisis *Stockout* Obat Esensial):**  
   Di sisi lain, Puskesmas kerap menghadapi kekosongan stok (*stockout*) pada obat-obat vital dan esensial saat dibutuhkan oleh pasien. Berdasarkan analisis deret waktu, item vital seperti **Amlodipin 10 mg** mengalami kekosongan pada 7 dari 22 periode dwibulanan (32% total waktu pengamatan), sementara mukolitik **Ambroksol 30 mg** mengalami kekosongan hingga 8 periode dwibulanan (36% waktu pengamatan), yang secara langsung menurunkan mutu layanan klinis.
3. **Paradoks 3 (Distorsi *Censored Demand* & Fenomena Musiman):**  
   Wilayah pesisir Bintan sangat dipengaruhi oleh perubahan cuaca ekstrem dan musim angin utara, di mana kasus Infeksi Saluran Pernapasan Akut (ISPA, ICD-10: J00) melonjak drastis hingga mendominasi 34,8% dari total morbiditas. Namun, lonjakan kebutuhan ini gagal diantisipasi oleh sistem manual karena pada periode sebelumnya, ketika stok obat habis total (*total stockout*), pencatatan pemakaian pada LPLPO bernilai nol. Angka nol tersebut mendistorsi perhitungan rata-rata periode berikutnya menjadi anjlok ke bawah, sehingga Puskesmas kembali terjebak dalam siklus kekosongan berulang.

## I.3 Tujuan Penambangan Data

Berdasarkan rumusan masalah di atas, penelitian penambangan data ini menetapkan lima tujuan konkret yang diselaraskan dengan tahapan CRISP-DM:

1. Melakukan ekstraksi, pembersihan, dan standardisasi data transaksi LPLPO menjadi matriks deret waktu kontinu sepanjang **22 periode dwibulanan** (tahun 2023–2026).
2. Menerapkan algoritma imputasi *Censored Demand Adjustment* guna merekonstruksi nilai permintaan riil pada periode *stockout* (studi kasus Parasetamol 500 mg).
3. Menguji dan mengevaluasi kinerja peramalan melalui skema *10-Fold Expanding Window Walk-Forward Validation* untuk membandingkan model *Weighted Moving Average* ($\text{WMA-3}$), *Simple Moving Average* ($\text{SMA-2}$), dan *HistGradientBoosting* berdasarkan metrik WAPE dan Bias.
4. Menerapkan adaptasi skala waktu indikator momentum **MACD Dwibulanan (2, 4, 2)** untuk mendeteksi sinyal percepatan lonjakan kebutuhan obat (*Bullish/Bearish*).
5. Menganalisis korelasi statistik bivariat (Pearson $r$ & Spearman $\rho$) antara 10 penyakit terbesar SIMPUS terhadap kelas terapi terkait serta memformalkan **Rasio Dosis per Kasus** ($R_{i,j}$) untuk menghasilkan rekomendasi Rencana Pengadaan P5 yang dilengkapi penyangga *Safety Stock* 95%.

## I.4 Lini Waktu Pelaksanaan & Pembagian Kerja (Gantt Chart & PIC)

Pelaksanaan proyek penambangan data ini dibagi ke dalam 16 minggu pengerjaan yang mencakup seluruh fase standar industri CRISP-DM. Pembagian tugas dan penanggung jawab (*Person in Charge* / PIC) di dalam Kelompok 23 dirinci pada Tabel 1.1.

**Tabel 1.1** Lini Waktu Pelaksanaan Proyek Penambangan Data Kelompok 23

| No | Fase CRISP-DM | Durasi Minggu | Deskripsi Kegiatan Utama | Penanggung Jawab (PIC) |
| --- | --- | --- | --- | --- |
| 1 | **Business Understanding** | M01 – M02 | Identifikasi mitra, perumusan 3 paradoks, dan penyusunan proposal proyek. | Nabil (Ketua) & Annisa Darmawan |
| 2 | **Data Understanding** | M03 – M05 | Pengumpulan 29 file LPLPO, rekap SIMPUS 44 bulan, dan berita acara obat ED. | Balqis Manisa & Jian Safitri W. |
| 3 | **Data Preparation** | M06 – M08 | *Data cleaning*, agregasi dwibulanan, dan imputasi *Censored Demand*. | Nabil & Yehezkiel Alman S. |
| 4 | **Modeling** | M09 – M11 | Pemodelan $\text{WMA-3}$, $\text{SMA-2}$, adaptasi MACD (2, 4, 2), & matriks korelasi SIMPUS. | Nabil & Balqis Manisa |
| 5 | **Evaluation** | M12 – M13 | *10-Fold Walk-Forward Validation*, analisis WAPE, MAPE, dan audit bias model. | Annisa Darmawan & Jian Safitri W. |
| 6 | **Deployment** | M14 – M16 | Pengembangan aplikasi *Dual-Portal* Laravel 11 & SQLite, serta penyusunan laporan akhir. | Seluruh Anggota Kelompok 23 |

---

# BAB II: DATA UNDERSTANDING

## II.1 Teknik Pengumpulan Data

Seluruh data yang digunakan dalam proyek penambangan data ini bersumber dari data primer operasional rekam medis dan logistik UPTD Puskesmas Teluk Sasah yang dikumpulkan melalui koordinasi bersama penanggung jawab gudang farmasi. Sumber data tersebut dikelompokkan menjadi tiga kategori utama:

1. **Data Transaksi Logistik LPLPO:** Sebanyak **29 berkas lembar kerja digital (Excel)** yang merekam rekapitulasi bulanan persediaan farmasi dari tahun 2023 hingga 2026.
2. **Data Epidemiologi SIMPUS:** Berkas rekapitulasi morbiditas bulanan yang mencakup 10 besar kelompok diagnosis penyakit berdasarkan klasifikasi ICD-10 selama rentang 44 bulan pengamatan.
3. **Data Audit Kerugian Farmasi:** Berkas Berita Acara Pemeriksaan dan Pemusnahan Obat Rusak/Kadaluarsa tahun 2023, 2024, dan 2025 untuk validasi nilai kerugian finansial senilai Rp 43,34 juta.

## II.2 Jenis dan Kamus Data

Data mentah yang terkumpul kemudian ditransformasikan ke dalam struktur relasional yang terstandarisasi. Tabel II.1 menyajikan kamus data untuk transaksi LPLPO, sedangkan Tabel II.2 menyajikan kamus data untuk morbiditas SIMPUS.

**Tabel II.1** Kamus Data Atribut Transaksi LPLPO

| Nama Kolom / Atribut | Tipe Data | Deskripsi / Keterangan |
| --- | --- | --- |
| `periode_bimonthly_id` | `VARCHAR(10)` | Identifikasi periode dwibulanan (Contoh: `2026-P1` untuk Januari–Februari). |
| `nama_obat` | `VARCHAR(100)` | Nama resmi item obat sesuai Formularium Nasional / LPLPO. |
| `satuan` | `VARCHAR(20)` | Satuan kemasan terkecil (Contoh: `Tablet`, `Kaplet`, `Botol`, `Tube`). |
| `stok_awal` | `INTEGER` | Jumlah stok fisik sisa periode sebelumnya di gudang farmasi. |
| `penerimaan` | `INTEGER` | Jumlah suplai obat yang diterima dari Gudang Farmasi Dinas Kesehatan. |
| `persediaan` | `INTEGER` | Akumulasi stok (`stok_awal` + `penerimaan`) yang siap didistribusikan. |
| `pemakaian` | `INTEGER` | Jumlah riil obat yang dikeluarkan untuk melayani pasien di seluruh poli. |
| `stok_akhir` | `INTEGER` | Sisa stok fisik akhir periode (`persediaan` - `pemakaian`). |

**Tabel II.2** Kamus Data Morbiditas SIMPUS (10 Besar Penyakit ICD-10)

| Kode ICD-10 | Kategori Diagnosis Penyakit | Deskripsi Klinis Utama |
| --- | --- | --- |
| **J00** | ISPA / Nasopharyngitis Akut | Infeksi Saluran Pernapasan Akut Atas (Beban tertinggi: 34,8%). |
| **I10** | Essential (Primary) Hypertension | Hipertensi Primer / Tekanan Darah Tinggi (Beban kronis: 24,0%). |
| **K30** | Dyspepsia | Gangguan pencernaan / Maag fungsional. |
| **E11** | Type 2 Diabetes Mellitus | Diabetes Melitus Tipe 2 (Keterikatan tinggi dengan Metformin). |
| **M79** | Other and unspecified soft tissue disorders | Nyeri otot, rematik, dan gangguan jaringan lunak non-spesifik. |
| **L23/L30** | Dermatitis & Eczema | Penyakit kulit alergi dan inflamasi dermatologis. |
| **K08** | Other disorders of teeth and supporting structures | Kelainan gigi dan jaringan penyangga rongga mulut. |
| **R50** | Fever of unknown origin | Keluhan demam tanpa diketahui penyebab spesifiknya. |

## II.3 Exploratory Data Analysis (EDA) & Pola Sebaran Data

Untuk memahami karakteristik fluktuasi konsumsi logistik, dilakukan analisis statistik deskriptif terhadap **Top 15 Obat Terbanyak** di Puskesmas Teluk Sasah selama 22 periode dwibulanan. Analisis ini menggunakan parameter Rata-rata ($\mu$), Standar Deviasi ($\sigma$), dan **Koefisien Variasi ($CV = \frac{\sigma}{\mu}$)** untuk mengukur tingkat volatilitas permintaan.

$$\mu = \frac{1}{N}\sum_{t=1}^{N} x_t, \quad \sigma = \sqrt{\frac{1}{N}\sum_{t=1}^{N}(x_t - \mu)^2}, \quad CV = \frac{\sigma}{\mu}$$

**Tabel II.3** Statistik Deskriptif dan Koefisien Variasi Top 15 Obat di Puskesmas Teluk Sasah

| No | Nama Item Obat | Satuan | Total Pemakaian | Rata-rata ($\mu$) | Standar Deviasi ($\sigma$) | Koefisien Variasi ($CV$) | Kategori Volatilitas Permintaan |
| --- | --- | --- | --- | --- | --- | --- | --- |
| 1 | Parasetamol 500 mg | Tablet | 58.420 | 2.655,45 | 1.104,25 | 0,42 | Stabil (Fluktuasi Moderat) |
| 2 | Amoksisilin 500 mg | Kaplet | 36.150 | 1.643,18 | 782,40 | 0,48 | Stabil (Fluktuasi Moderat) |
| 3 | Antasida DOEN I | Tablet | 29.840 | 1.356,36 | 412,10 | 0,30 | Stabil ($\text{CV} < 0,5$) |
| 4 | Amlodipin 10 mg | Tablet | 34.120 | 1.550,91 | 895,30 | 0,58 | Fluktuasi Moderat |
| 5 | Amlodipin 5 mg | Tablet | 18.450 | 838,64 | 310,50 | 0,37 | Stabil ($\text{CV} < 0,5$) |
| 6 | Metformin 500 mg | Tablet | 24.600 | 1.118,18 | 490,20 | 0,44 | Stabil (Fluktuasi Moderat) |
| 7 | Ambroksol 30 mg | Tablet | 16.800 | 763,64 | 512,80 | 0,67 | Fluktuasi Tinggi |
| 8 | Vitamin B Kompleks | Tablet | 41.200 | 1.872,73 | 1.450,10 | 0,77 | Fluktuasi Tinggi |
| 9 | Oralit 200 mg | Sachet | 8.900 | 404,55 | 285,60 | 0,71 | Fluktuasi Tinggi |
| 10 | Natrium Diklofenak 50 mg | Tablet | 15.200 | 690,91 | 295,40 | 0,43 | Stabil (Fluktuasi Moderat) |
| 11 | Kaptopril 25 mg | Tablet | 9.400 | 427,27 | 210,80 | 0,49 | Stabil (Fluktuasi Moderat) |
| 12 | Glibenklamid 5 mg | Tablet | 4.800 | 218,18 | 95,40 | 0,44 | Stabil (Fluktuasi Moderat) |
| 13 | Salep Kloramfenikol 2% | Tube | 3.100 | 140,91 | 72,30 | 0,51 | Fluktuasi Moderat |
| 14 | Gliseril Guaikolat (GG) | Tablet | 12.500 | 568,18 | 480,50 | 0,85 | Fluktuasi Tinggi |
| 15 | Fero Sulfat (Tablet Tambah Darah) | Tab | 28.500 | 1.295,45 | 2.850,20 | **2,20** | **Fluktuasi Ekstrem (Program KIA)** |

### Interpretasi Pola Sebaran Data EDA:

1. **Kelompok Permintaan Stabil ($CV < 0,5$):**  
   Obat-obatan esensial penunjang penyakit kronis dan keluhan harian seperti **Antasida DOEN I** ($CV = 0,30$), **Amlodipin 5 mg** ($CV = 0,37$), dan **Parasetamol 500 mg** ($CV = 0,42$) menunjukkan pola sebaran yang relatif stabil. Hal ini merepresentasikan tingkat kunjungan rutin pasien hipertensi dan keluhan ringan yang konstan di poli umum.
2. **Kelompok Fluktuasi Moderat hingga Tinggi ($0,5 \le CV \le 1,0$):**  
   Item penunjang pernapasan seperti **Ambroksol 30 mg** ($CV = 0,67$) dan **Gliseril Guaikolat** ($CV = 0,85$) memiliki deviasi standar yang tinggi terhadap rata-ratanya. Pola ini mengonfirmasi adanya sifat musiman (*seasonality*), di mana konsumsi meroket tajam pada periode pancaroba akibat lonjakan kasus ISPA, namun melandai pada periode normal.
3. **Kelompok Fluktuasi Ekstrem ($CV > 1,0$):**  
   **Fero Sulfat (Tablet Tambah Darah)** mencatatkan koefisien variasi tertinggi yakni **$CV = 2,20$** ($\mu = 1.295$, $\sigma = 2.850$). Tingginya nilai $CV$ ini bukan disebabkan oleh galat pencatatan, melainkan karakteristik logistik program kesehatan ibu dan anak (KIA) yang distribusinya bersifat kampanye massal berkala (intermiten) ke sekolah-sekolah dan posyandu, sehingga pemakaiannya terkonsentrasi pada bulan-bulan tertentu saja.

Analisis sebaran data ini memperkuat argumen bahwa peramalan logistik di Puskesmas Teluk Sasah tidak boleh menggunakan asumsi linier sederhana, melainkan memerlukan model peramalan seperti $\text{WMA-3}$ dan indikator momentum $\text{MACD}$ yang adaptif terhadap lonjakan musiman.

---

# BAB III: PREPROCESS (DATA PREPARATION)

Fase *Data Preparation* (*Preprocess*) dalam metodologi CRISP-DM merupakan tahapan krusial yang menentukan validitas model penambangan data pada UPTD Puskesmas Teluk Sasah. Data administratif fasilitas kesehatan tingkat pertama secara alami memiliki karakteristik semi-terstruktur, heterogen dalam resolusi waktu, serta rentan terhadap distorsi pencatatan akibat kekosongan stok fisik (*stockout*). Bab ini menguraikan enam permasalahan struktural data mentah beserta solusi algoritmik yang diimplementasikan pada *pipeline* prapemrosesan data Kelompok 23.

## III.1 Penyelesaian Masalah 1: Inkonsistensi Periode Waktu Pelaporan (Standardisasi 22 Periode Dwibulanan)

### Identifikasi Masalah Data
Eksplorasi terhadap 29 berkas mentah Laporan Pemakaian dan Lembar Permintaan Obat (LPLPO) serta berkas morbiditas SIMPUS mengungkap ketidaksinkronan resolusi waktu (*temporal misalignment*):
* Data LPLPO tahun 2023 (6 berkas), tahun 2025 (6 berkas), dan tahun 2026 hingga Agustus (4 berkas) dicatat dalam siklus **dwibulanan** (Periode P1 hingga P6).
* Data LPLPO tahun 2024 (12 berkas ditambah 1 berkas rekapitulasi) dicatat dalam siklus **bulanan** terpisah dari Januari hingga Desember.
* Data morbiditas 10 penyakit terbesar SIMPUS direkapitulasi dalam resolusi **bulanan** selama 44 bulan pengamatan.

Apabila deret waktu dengan satuan interval yang berbeda ini digabungkan secara langsung, maka perhitungan *Moving Average* dan indikator momentum MACD akan mengalami distorsi skala frekuensi pada tahun 2024.

### Justifikasi & Solusi Penambangan Data
Untuk menciptakan sumbu waktu yang seragam (*equidistant time-series grid*), diterapkan teknik **Temporal Resampling & Alignment** ke dalam resolusi acuan dwibulanan (*bimonthly*) sepanjang $N = 22$ periode kontinu (2023-P1 hingga 2026-P4). Dalam ilmu manajemen rantai pasok, agregasi temporal wajib membedakan perlakuan matematis antara **variabel aliran (*flow variables*)** dan **variabel posisi (*stock/state variables*)**.

Untuk setiap periode dwibulanan ke-$k$ pada tahun 2024 yang terdiri atas bulan ganjil $m_1 = 2k - 1$ dan bulan genap $m_2 = 2k$ (dengan $k \in \{1, 2, \dots, 6\}$), berlaku formulasi agregasi berikut:

$$P_{\text{terima}, k} = P_{\text{terima}, m_1} + P_{\text{terima}, m_2}$$

$$K_{\text{pakai}, k} = K_{\text{pakai}, m_1} + K_{\text{pakai}, m_2}$$

$$S_{\text{awal}, k} = S_{\text{awal}, m_1}$$

$$S_{\text{akhir}, k} = S_{\text{akhir}, m_2}$$

$$S_{\text{sedia}, k} = S_{\text{awal}, k} + P_{\text{terima}, k}$$

Variabel aliran seperti penerimaan ($P_{\text{terima}}$), pemakaian obat ($K_{\text{pakai}}$), serta jumlah kasus penyakit SIMPUS ($M_{j,k} = M_{j,m_1} + M_{j,m_2}$) dijumlahkan secara kumulatif. Sebaliknya, variabel posisi tidak boleh dijumlahkan agar tidak terjadi penghitungan ganda (*double counting*): stok awal periode dwibulanan mengambil posisi stok awal bulan ganjil ($m_1$), sedangkan stok akhir mengambil posisi stok akhir bulan genap ($m_2$).

**Tabel III.1** Pemetaan Standardisasi 22 Periode Dwibulanan Kontinu (2023–2026)

| Kode Periode | Rentang Kalender | Jumlah Berkas Asal | Resolusi Asal | Metode Penyelarasan |
| --- | --- | --- | --- | --- |
| 2023-P1 s.d. 2023-P6 | Jan 2023 – Des 2023 | 6 Berkas LPLPO | Dwibulanan | Pemetaan langsung |
| 2024-P1 s.d. 2024-P6 | Jan 2024 – Des 2024 | 12 Berkas LPLPO | Bulanan | Agregasi alir & posisi |
| 2025-P1 s.d. 2025-P6 | Jan 2025 – Des 2025 | 6 Berkas LPLPO | Dwibulanan | Pemetaan langsung |
| 2026-P1 s.d. 2026-P4 | Jan 2026 – Agu 2026 | 4 Berkas LPLPO | Dwibulanan | Pemetaan langsung |
| SIMPUS P1 s.d. P22 | Jan 2023 – Agu 2026 | 1 Master SIMPUS | 44 Bulan | Penjumlahan 2 bulanan |

Tabel III.1 memastikan seluruh 22 titik waktu memiliki lebar jendela observasi yang identik (masing-masing 60 hari kalender), sekaligus menjaga konsistensi persamaan identitas logistik $S_{\text{akhir}, k} = S_{\text{awal}, k} + P_{\text{terima}, k} - K_{\text{pakai}, k}$ pada setiap baris transaksi.

---

## III.2 Penyelesaian Masalah 2: Pembersihan Artefak Header, Footer, dan Baris Pengesahan

### Identifikasi Masalah Data
Berkas lembar kerja Excel LPLPO dan Berita Acara Obat Kadaluarsa (ED) dirancang untuk keperluan cetak dokumen birokrasi, sehingga memuat banyak baris non-transaksi di dalam tabel data:
* Baris judul kop surat instansi (`UPTD PUSKESMAS TELUK SASAH`, `DINAS KESEHATAN KABUPATEN BINTAN`).
* Baris pemisah sub-kategori farmakoterapi di tengah tabel (`I. OBAT GENERIK`, `II. TABLET / KAPSUL`, `III. SIRUP / SUSPENSI`, `BMHP`).
* Baris rekapitulasi total (`JUMLAH TOTAL`) dan blok legalitas tanda tangan pejabat di bagian bawah (`Mengetahui, Kepala UPTD Puskesmas Teluk Sasah`, `dr. Kurniawan, M.H.`, beserta baris `NIP. ...`).

### Justifikasi & Solusi Penambangan Data
Pembersihan dilakukan secara terprogram menggunakan *Rule-Based Structural Filtering* pada Python Pandas. Sebuah baris $r_i$ di dalam lembar kerja didefinisikan sebagai entitas transaksi valid jika dan hanya jika atribut kolom nomor urut (`No`) bernilai numerik bulat positif dan kolom `Nama_Obat` bukan merupakan teks kata kunci struktural:

$$\text{Valid}(r_i) \iff \left( r_i[\text{No}] \in \mathbb{Z}^+ \right) \land \left( r_i[\text{Nama\_Obat}] \notin \{\text{Kop}, \text{Kategori}, \text{Subtotal}, \text{Tanda\_Tangan}\} \right)$$

Selain pemfilteran baris, dilakukan pula normalisasi string (*case folding*, penghapusan spasi ganda, dan penyatuan sinonim penulisan seperti `PCT 500 mg` menjadi `Parasetamol 500 mg`) untuk mencegah fragmentasi kode item obat.

**Tabel III.2** Hasil Pembersihan Baris Artefak pada Dokumen Berita Acara Obat ED (2023–2025)

| Tahun Audit ED | Baris Mentah | Baris Artefak Dihapus | Item Murni Tervalidasi | Status Integritas |
| --- | --- | --- | --- | --- |
| Berita Acara 2023 | 42 baris | 2 baris | 40 item obat/alkes | Bersih 100% |
| Berita Acara 2024 | 49 baris | 2 baris | 47 item obat/alkes | Bersih 100% |
| Berita Acara 2025 | 53 baris | 2 baris | 51 item obat/alkes | Bersih 100% |
| **Total Akumulasi** | **144 baris** | **6 baris** | **138 item murni** | **Siap Audit** |

Tabel III.2 menunjukkan bahwa algoritma penyaringan berhasil mengeliminasi tepat 6 baris artefak pengesahan dan kop surat dari 144 baris mentah, menyisakan **138 item obat dan alat kesehatan kadaluarsa murni** yang valid untuk dihitung beban kerugiannya.

---

## III.3 Penyelesaian Masalah 3: Penanganan Nilai Kosong (*Missing Value*) & Domain-Specific Lookup

### Identifikasi Masalah Data
Pada pemeriksaan kelengkapan atribut (*completeness check*) terhadap 138 item obat kadaluarsa tahun 2023–2025, ditemukan nilai kosong (`NaN` / *missing value*) pada kolom `Harga_Satuan` dan `Total_Nilai` di sejumlah item pada dokumen tahun 2024 dan 2025. Dalam dokumen rekapitulasi fisik gudang, petugas terkadang hanya mencatat kuantitas fisik barang yang rusak/kadaluarsa tanpa mencantumkan harga perolehan satuannya.

### Justifikasi & Solusi Penambangan Data
Dalam proyek penambangan data umum, *missing value* numerik sering ditangani menggunakan imputasi nilai tengah (*mean* atau *median*). Namun, dalam domain audit keuangan negara (dana APBD dan DAK), **imputasi rata-rata atau median dilarang secara metodologis** karena harga satuan obat memiliki rentang dispersi yang ekstrem (mulai dari Rp 150,- per tablet hingga puluhan ribu rupiah per vial/alkes). Mengisi harga obat yang kosong dengan nilai rata-rata seluruh obat akan menghasilkan angka kerugian fiktif yang tidak dapat dipertanggungjawabkan secara hukum.

Sebagai solusinya, diterapkan metode **Cross-Table Domain-Specific Lookup** dengan menjadikan tabel Master Harga LPLPO resmi Puskesmas sebagai referensi kunci (*lookup dictionary*):

$$H_{\text{ED}}(i) = \begin{cases} h_{\text{asli}}(i), & \text{jika } h_{\text{asli}}(i) \neq \text{NaN} \\ \text{Lookup}_{\text{Master\_LPLPO}}(\text{Norm}(i)), & \text{jika } h_{\text{asli}}(i) = \text{NaN} \end{cases}$$

$$V_{\text{kerugian}} = \sum_{i=1}^{138} Q_{\text{ED}}(i) \times H_{\text{ED}}(i)$$

Di mana $\text{Norm}(i)$ adalah kunci nama obat terstandarisasi, $Q_{\text{ED}}(i)$ adalah volume fisik obat kadaluarsa pada item ke-$i$, dan $H_{\text{ED}}(i)$ adalah harga perolehan satuan resmi. Resolusi *lookup* ini menghasilkan nilai kerugian finansial yang presisi dan terverifikasi sebesar **Rp 43.348.626,-** (terdiri atas Rp 9.104.210,- pada 2023; Rp 11.319.856,- pada 2024; dan melonjak menjadi Rp 22.924.560,- pada 2025).

---

## III.4 Penyelesaian Masalah 4: Pemisahan Anomali Obat Program Nasional vs Obat Kuratif Poli

### Identifikasi Masalah Data
Pada saat dilakukan pengurutan volume pemakaian kumulatif tertinggi selama 22 periode dwibulanan, ditemukan fenomena statistik yang mencolok: item **Fero Sulfat (Tablet Tambah Darah)** menempati urutan pertama dengan total pengeluaran **255.180 tablet** dan Koefisien Variasi ekstrem sebesar **$CV = 2,20$**, melampaui **Parasetamol 500 mg** yang mencatat total pemakaian **142.715 tablet** ($CV = 0,42$). Apabila anomali ini tidak dianalisis secara kontekstual, model akan salah menyimpulkan bahwa penyakit anemia defisiensi besi memiliki insidensi kunjungan harian yang lebih tinggi dibandingkan demam/ISPA.

### Justifikasi & Solusi Penambangan Data
Dilakukan penelusuran forensik data (*data lineage inspection*) ke dalam berkas sumber `1 JANUARI 2024.xls`. Pada **baris ke-122**, ditemukan lonjakan pengeluaran tunggal sebesar **118.295 tablet** Fero Sulfat dalam satu bulan dengan anotasi kolom keterangan bertuliskan **`KET: GIZI`**.

Temuan ini membuktikan bahwa Fero Sulfat bukan dikonsumsi melalui peresepan kuratif harian di poli rawat jalan, melainkan merupakan **Obat Program Nasional Gizi/KIA** yang didistribusikan secara massal (*bulk dropping*) ke sekolah-sekolah menengah (program pencegahan anemia remaja putri dan pencegahan *stunting*) serta Posyandu pada waktu-waktu kampanye tertentu.

**Tabel III.3** Pemisahan Karakteristik Obat Program Nasional vs Obat Kuratif Poli

| Parameter Pembanding | Fero Sulfat (Obat Program) | Parasetamol 500 mg (Obat Kuratif) |
| --- | --- | --- |
| Total Volume (22 Periode) | 255.180 tablet | 142.715 tablet |
| Koefisien Variasi ($CV$) | 2,20 (Fluktuasi Ekstrem) | 0,42 (Stabil / Terkendali) |
| Anomali Puncak Tunggal | 118.295 tab (`KET: GIZI` Jan 2024) | 6.444 tab (Puncak ISPA 2026-P3) |
| Mekanisme Distribusi | *Push-Distribution* (Drop Massal Gizi) | *Pull-Demand* (Resep Pasien Poli) |
| Perlakuan Pemodelan | Dipisahkan dari peringkat kuratif poli | **Obat Kuratif Utama #1 Puskesmas** |

Sebagaimana dirangkum pada Tabel III.3, pemisahan kategori ini menjaga kemurnian analisis: Fero Sulfat diklasifikasikan ke dalam klaster logistik program kampanye terjadwal, sedangkan **Parasetamol 500 mg** dikukuhkan sebagai obat pelayanan kuratif harian nomor satu yang paling krusial di UPTD Puskesmas Teluk Sasah.

---

## III.5 Penyelesaian Masalah 5: Rekonstruksi Permintaan Tersensor (*Censored Demand Adjustment*) Parasetamol 500 mg Periode 2026-P4

### Identifikasi Masalah Data
Masalah kualitas data paling berbahaya dalam peramalan deret waktu logistik kesehatan adalah *right-censored demand* (permintaan tersensor akibat kehabisan stok). Pada periode **2026-P3 (Mei–Juni 2026)**, terjadi lonjakan kasus ISPA yang menyerap habis seluruh persediaan Parasetamol 500 mg sebanyak **6.444 tablet**, menyisakan stok akhir sebesar **0 tablet**. Memasuki periode **2026-P4 (Juli–Agustus 2026)**, keterlambatan suplai menyebabkan catatan LPLPO merekam:
* $\text{Stok Awal}_{\text{2026-P4}} = 0$
* $\text{Penerimaan}_{\text{2026-P4}} = 0$
* $\text{Pemakaian}_{\text{2026-P4}} = 0$
* $\text{Stok Akhir}_{\text{2026-P4}} = 0$

Secara statistik, angka pemakaian $0$ pada 2026-P4 adalah **nol semu (*artificial zero*)**. Pasien ISPA tetap datang berobat ke Puskesmas (terbukti dari data SIMPUS), namun tidak terlayani dari stok gudang. Apabila angka $0$ ini diumpankan langsung ke model $\text{SMA-2}$, proyeksi periode 2026-P5 akan terpangkas 50% menjadi $\frac{6.444 + 0}{2} = 3.222$ tablet, yang pasti memicu krisis *stockout* berulang pada musim pancaroba September–Oktober.

### Justifikasi & Solusi Penambangan Data
Untuk membedakan secara ketat antara **angka 0 akibat *stockout* (permintaan tersensor)** dengan **angka 0 karena obat memang tidak digunakan**, sistem menerapkan tiga syarat logika simultan (*Three-Point Conditional Gate*):

$$\text{Is\_Censored}(i, t) \iff \underbrace{(S_{\text{awal}, i, t} = 0 \land P_{\text{terima}, i, t} = 0 \land K_{\text{pakai}, i, t} = 0)}_{\text{Syarat 1: Ketiadaan Fisik diperiode } t} \land \underbrace{(K_{\text{pakai}, i, t-1} > 0 \land S_{\text{akhir}, i, t-1} = 0)}_{\text{Syarat 2: Pengurasan Habis di } t-1} \land \underbrace{(M_{j, t} > 0)}_{\text{Syarat 3: Morbiditas Aktif}}$$

Karena Parasetamol 500 mg pada periode 2026-P4 memenuhi ketiga syarat tersebut secara mutlak, dilakukan rekonstruksi nilai permintaan riil ($\hat{y}_{\text{2026-P4}}$) menggunakan rata-rata bergerak lokal dari dua periode aktif terakhir sebelum *stockout* ($y_{\text{2026-P2}} = 5.256$ dan $y_{\text{2026-P3}} = 6.444$):

$$\hat{y}_{\text{2026-P4}}^{\text{imputed}} = \frac{y_{\text{2026-P2}} + y_{\text{2026-P3}}}{2} = \frac{5.256 + 6.444}{2} = 5.850 \text{ tablet}$$

Catatan asli di basis data fisik tetap dipertahankan bernilai $0$ demi kepatuhan audit inventaris, namun lapisan analitik peramalan menggunakan nilai terekonstruksi $5.850$ tablet untuk menghitung proyeksi periode 2026-P5.

**Tabel III.4** Dampak Rekonstruksi *Censored Demand* Parasetamol 500 mg terhadap Proyeksi 2026-P5

| Parameter Evaluasi | Tanpa Koreksi (Unadjusted) | Dengan Koreksi (Imputed) | Selisih Pemulihan |
| --- | --- | --- | --- |
| Nilai Konsumsi 2026-P4 | 0 tablet (Bias Stockout) | 5.850 tablet (Rekonstruksi) | +5.850 tablet |
| Proyeksi Titik SMA-2 (P5) | 3.222 tablet | 6.147 tablet | +2.925 tablet |
| Proyeksi Titik WMA-3 (P5) | 3.024 tablet | 5.949 tablet | +2.925 tablet |
| Rekomendasi P5 (+SS 95%) | 4.562 tablet (Kritis) | **7.487 tablet (Aman)** | **+2.925 tablet** |
| Sinyal Histogram MACD | -485,42 (Crash Semu) | **+34,58 (Rebound Positif)** | **Pulih Akurat** |

Tabel III.4 membuktikan bahwa koreksi permintaan tersensor menyelamatkan Puskesmas dari defisit perencanaan sebesar $2.925$ tablet sekaligus memulihkan arah sinyal indikator momentum MACD dari *crash* palsu menjadi *rebound* positif yang akurat.

---

## III.6 Penyelesaian Masalah 6: Transformasi Matriks Deret Waktu (*Wide-Format Pivoting*) & Analisis Dua Lensa Granularitas

### Identifikasi Masalah Data
Setelah tahap pembersihan dan standardisasi selesai, data transaksi LPLPO tersimpan dalam struktur tabel memanjang (*long-format transactional table*) sebanyak **15.813 baris** observasi. Struktur memanjang ini tidak dapat diproses secara vektorial oleh algoritma *Sliding Window Moving Average*, perhitungan peluruhan eksponensial EMA/MACD, maupun uji validasi silang *Walk-Forward*.

### Justifikasi & Solusi Penambangan Data
Diterapkan operasi transformasi **Wide-Format Matrix Pivoting** ($\mathbf{X} \in \mathbb{R}^{I \times T}$) yang memetakan setiap unik item obat sebagai baris ($I = 414$ total katalog obat/alkes, yang kemudian disaring menjadi **149 item obat aktif** yang memiliki histori transaksi memadai) dan 22 periode dwibulanan sebagai kolom dimensi waktu ($T = 22$):

$$\mathbf{X}_{I \times T} = \text{Pivot}\left(\text{Data}_{\text{Long}}, \text{index}=\text{kode\_obat}, \text{columns}=\text{periode\_id}, \text{values}=\text{pemakaian}\right)$$

Matriks hasil transformasi ini disimpan ke dalam artefak `lplpo_obat_pivot_pemakaian.csv`. Lebih lanjut, menindaklanjuti arahan metodologis dosen pengampu mengenai sensitivitas resolusi waktu, prapemrosesan data dibagi ke dalam **Analisis Dua Lensa Granularitas Temporal** (*Dual Temporal Lens Framework*):

1. **Lensa Mikro 1-Bulanan (*Intra-Year High-Frequency Lens* — 12 Bulan Tahun 2024):** Mempertahankan matriks khusus tahun 2024 dalam resolusi bulanan ($T = 12$ titik bulan kalender) untuk mengamati dinamika fluktuasi jangka pendek di dalam kuartal yang sama, memvalidasi kecepatan serapan stok mingguan/bulanan, serta mendeteksi puncak lonjakan awal penyakit sebelum teredam oleh agregasi dua bulanan.
2. **Lensa Makro 2-Bulanan Kontinu (*Multi-Year Strategic Lens* — 22 Periode 2023–2026):** Menggunakan matriks terstandarisasi 22 periode dwibulanan sebagai landasan utama pelatihan model $\text{WMA-3}$, $\text{SMA-2}$, dan $\text{MACD (2, 4, 2)}$ karena siklus pengajuan anggaran dan distribusi fisik LPLPO dari Dinas Kesehatan Kabupaten Bintan ke Puskesmas Teluk Sasah berlangsung setiap 2 bulan sekali.

**Tabel III.5** Spesifikasi Matriks Keluaran Tahap *Preprocess* (*Data Preparation*)

| Nama Artefak Matriks | Dimensi Matriks | Lensa Temporal | Fungsi pada Tahap Modeling |
| --- | --- | --- | --- |
| `lplpo_pivot_22periode.csv` | $414 \times 22$ | Makro Dwibulanan | Basis data matriks total katalog 2023–2026 |
| `dataset_149_obat_aktif.csv` | $149 \times 22$ | Makro Dwibulanan | Input utama WMA-3, MACD (2,4,2) & 10-Fold CV |
| `lplpo_2024_mikro_12bln.csv` | $149 \times 12$ | Mikro Bulanan | Evaluasi volatilitas bulanan & *sanity check* |
| `simpus_morb_22periode.csv` | $10 \times 22$ | Makro Dwibulanan | Input korelasi Pearson/Spearman & Rasio $R_{i,j}$ |
| `audit_ed_bersih_138.csv` | $138 \times 7$ | Tahunan (2023–2025) | Evaluasi risiko finansial ED & batas FEFO |

Melalui enam tahapan prapemrosesan terstruktur pada Tabel III.5, seluruh anomali administratif, *missing value* harga audit, bias obat program nasional, hingga distorsi *censored demand* telah berhasil dieliminasi, menghasilkan struktur matriks deret waktu berkualitas tinggi yang siap diolah pada tahap **Bab IV (Modeling)**.

---

# BAB IV: MODELING

Fase *Modeling* dalam kerangka kerja CRISP-DM pada proyek ini berfokus pada perancangan arsitektur matematis untuk mengekstraksi pola deret waktu pemakaian obat dan memetakan asosiasi epidemiologi penyakit pada data UPTD Puskesmas Teluk Sasah (`FinPD_2401020069_Laporan_Akhir_Kelompok_23.docx`). Seluruh formulasi dirancang agar kompatibel dengan matriks berdimensi $149 \times 22$ periode dwibulanan yang telah melalui tahap prapemrosesan.

## IV.1 Konsep dan Formulasi Deret Waktu Moving Average

Metode *Moving Average* (Rata-Rata Bergerak) memodelkan nilai proyeksi periode mendatang ($\hat{y}_{t+1}$) sebagai fungsi linier dari sejumlah $k$ observasi historis terakhir. Pendekatan ini meredam fluktuasi acak jangka pendek (*random white noise*) sekaligus mempertahankan level dasar permintaan logistik.

### 1. Simple Moving Average ($\text{SMA-}k$)

Model $\text{Simple Moving Average}$ memberikan bobot yang seragam ($\frac{1}{k}$) pada setiap titik observasi di dalam jendela geser (*sliding window*) sepanjang $k$ periode:

$$\hat{y}_{t+1}^{\text{SMA-}k} = \frac{1}{k} \sum_{i=0}^{k-1} y_{t-i}$$

Untuk jendela pengamatan dua periode dwibulanan ($k=2$, setara 4 bulan kalender) dan tiga periode dwibulanan ($k=3$, setara 6 bulan kalender), persamaan operasionalnya adalah:

$$\hat{y}_{t+1}^{\text{SMA-2}} = \frac{y_{t-1} + y_t}{2}, \quad \hat{y}_{t+1}^{\text{SMA-3}} = \frac{y_{t-2} + y_{t-1} + y_t}{3}$$

### 2. Weighted Moving Average ($\text{WMA-3}$)

Dalam dinamika penyakit menular dan cuaca pesisir Kabupaten Bintan, observasi pada periode paling mutakhir ($y_t$) mengandung informasi perubahan tren epidemiologi yang lebih tinggi dibandingkan periode yang lebih lampau. Oleh karena itu, diformulasikan model $\text{Weighted Moving Average}$ berorde $k=3$ ($\text{WMA-3}$) dengan vektor bobot aritmatika linier meningkat $w = [1, 2, 3]$:

$$\hat{y}_{t+1}^{\text{WMA-3}} = \frac{\sum_{i=0}^{2} w_{3-i} \cdot y_{t-i}}{\sum_{j=1}^{3} w_j} = \frac{1 \cdot y_{t-2} + 2 \cdot y_{t-1} + 3 \cdot y_t}{1 + 2 + 3} = \frac{y_{t-2} + 2y_{t-1} + 3y_t}{6}$$

Melalui normalisasi pembagi $\sum w = 6$, model $\text{WMA-3}$ mengalokasikan proporsi bobot sebesar **50,0%** ($\frac{3}{6}$) pada pemakaian periode terakhir ($y_t$), **33,3%** ($\frac{2}{6}$) pada satu periode sebelumnya ($y_{t-1}$), dan **16,7%** ($\frac{1}{6}$) pada dua periode sebelumnya ($y_{t-2}$).

---

## IV.2 Simulasi Perhitungan Matematis Langkah Demi Langkah (Step-by-Step)

Untuk memvalidasi transparansi komputasi (*white-box explainability*), dilakukan simulasi perhitungan manual langkah demi langkah dari periode historis hingga target proyeksi **2026-P5 (September–Oktober 2026)** pada dua representasi kelas terapi utama: **Parasetamol 500 mg** (obat kuratif akut ISPA) dan **Metformin 500 mg** (obat rumatan kronis Diabetes Melitus Tipe 2).

### 1. Simulasi Langkah Demi Langkah: Parasetamol 500 mg (Kasus Akut Terkoreksi)

Data deret waktu pemakaian Parasetamol 500 mg dari periode 2025-P6 hingga 2026-P4 (setelah imputasi *Censored Demand* pada 2026-P4) adalah:
* $y_{\text{2025-P6}} = 4.820$ tablet
* $y_{\text{2026-P1}} = 5.110$ tablet
* $y_{\text{2026-P2}} = 5.256$ tablet
* $y_{\text{2026-P3}} = 6.444$ tablet (*habis terpakai / stockout di akhir periode*)
* $\hat{y}_{\text{2026-P4}}^{\text{imputed}} = 5.850$ tablet (*hasil rekonstruksi $\frac{5.256 + 6.444}{2}$*)

Perhitungan pergeseran jendela (*sliding window*) menuju target proyeksi **2026-P5** dieksekusi sebagai berikut:

1. **Langkah Evaluasi Periode 2026-P3** (menggunakan histori P6, P1, P2):

$$\hat{y}_{\text{2026-P3}}^{\text{SMA-2}} = \frac{5.110 + 5.256}{2} = 5.183,00 \text{ tablet}$$

$$\hat{y}_{\text{2026-P3}}^{\text{WMA-3}} = \frac{1(4.820) + 2(5.110) + 3(5.256)}{6} = \frac{25.808}{6} = 5.134,67 \text{ tablet}$$

2. **Langkah Evaluasi Periode 2026-P4** (menggunakan histori P1, P2, P3):

$$\hat{y}_{\text{2026-P4}}^{\text{SMA-2}} = \frac{5.256 + 6.444}{2} = 5.850,00 \text{ tablet}$$

$$\hat{y}_{\text{2026-P4}}^{\text{WMA-3}} = \frac{1(5.110) + 2(5.256) + 3(6.444)}{6} = \frac{34.954}{6} = 5.825,67 \text{ tablet}$$

3. **Langkah Proyeksi Target Periode 2026-P5** (menggunakan histori P2, P3, dan P4 terkoreksi):

$$\hat{y}_{\text{2026-P5}}^{\text{SMA-2}} = \frac{y_{\text{P3}} + \hat{y}_{\text{P4}}^{\text{imputed}}}{2} = \frac{6.444 + 5.850}{2} = \frac{12.294}{2} = \mathbf{6.147} \text{ tablet}$$

$$\hat{y}_{\text{2026-P5}}^{\text{WMA-3}} = \frac{1(5.256) + 2(6.444) + 3(5.850)}{6} = \frac{5.256 + 12.888 + 17.550}{6} = \frac{35.694}{6} = \mathbf{5.949} \text{ tablet}$$

**Tabel IV.1** Jejak Perhitungan Deret Waktu Parasetamol 500 mg (2025-P6 s.d. 2026-P5)

| Periode Dwibulanan | Konsumsi Riil | Konsumsi Terkoreksi | Proyeksi SMA-2 | Proyeksi WMA-3 |
| --- | --- | --- | --- | --- |
| 2025-P6 (Nov–Des) | 4.820 | 4.820 | - | - |
| 2026-P1 (Jan–Feb) | 5.110 | 5.110 | - | - |
| 2026-P2 (Mar–Apr) | 5.256 | 5.256 | 4.965 | - |
| 2026-P3 (Mei–Jun) | 6.444 | 6.444 | 5.183 | 5.135 |
| 2026-P4 (Jul–Agu) | 0 (Stockout) | 5.850 (Imputed) | 5.850 | 5.826 |
| **2026-P5 (Target)** | **Proyeksi** | **-** | **6.147** | **5.949** |

Tabel IV.1 memperlihatkan bagaimana substitusi nilai rekonstruksi $5.850$ tablet pada periode 2026-P4 menjaga kontinuitas proyeksi 2026-P5 di kisaran $5.949\text{--}6.147$ tablet.

### 2. Simulasi Langkah Demi Langkah: Metformin 500 mg (Kasus Rumatan Kronis)

Berbeda dengan Parasetamol yang dipengaruhi cuaca, **Metformin 500 mg** memiliki pola konsumsi rumatan yang tumbuh konsisten seiring penambahan pasien kontrol rutin Diabetes Melitus Tipe 2. Data historis 4 periode terakhir sebelum target P5 adalah:
* $y_{\text{2026-P1}} = 2.100$ tablet
* $y_{\text{2026-P2}} = 2.450$ tablet
* $y_{\text{2026-P3}} = 2.700$ tablet
* $y_{\text{2026-P4}} = 2.910$ tablet

Proyeksi titik kebutuhan Metformin 500 mg untuk periode **2026-P5** dihitung sebagai berikut:

$$\hat{y}_{\text{2026-P5}}^{\text{SMA-2}} = \frac{y_{\text{P3}} + y_{\text{P4}}}{2} = \frac{2.700 + 2.910}{2} = \mathbf{2.805} \text{ tablet}$$

$$\hat{y}_{\text{2026-P5}}^{\text{WMA-3}} = \frac{1(2.450) + 2(2.700) + 3(2.910)}{6} = \frac{2.450 + 5.400 + 8.730}{6} = \frac{16.580}{6} = \mathbf{2.763,33} \approx \mathbf{2.763} \text{ tablet}$$

**Tabel IV.2** Jejak Perhitungan Deret Waktu Metformin 500 mg (2026-P1 s.d. 2026-P5)

| Periode Dwibulanan | Pasien DM (SIMPUS) | Konsumsi Riil | Proyeksi SMA-2 | Proyeksi WMA-3 |
| --- | --- | --- | --- | --- |
| 2026-P1 (Jan–Feb) | 52 kasus | 2.100 | - | - |
| 2026-P2 (Mar–Apr) | 60 kasus | 2.450 | - | - |
| 2026-P3 (Mei–Jun) | 66 kasus | 2.700 | 2.275 | - |
| 2026-P4 (Jul–Agu) | 71 kasus | 2.910 | 2.575 | 2.630 |
| **2026-P5 (Target)** | **69 kasus (Est)** | **Proyeksi** | **2.805** | **2.763** |

Tabel IV.2 menunjukkan kestabilan proyeksi $\text{SMA-2}$ ($2.805$ tablet) dan $\text{WMA-3}$ ($2.763$ tablet) yang sangat rapat dengan estimasi berbasis morbiditas SIMPUS ($69 \text{ kasus} \times 40,95 = 2.826$ tablet).

---

## IV.3 Formulasi Indikator Momentum MACD Dwibulanan (2, 4, 2)

Untuk mendeteksi perubahan arah dan percepatan (*momentum*) permintaan obat sebelum stok gudang terkuras, sistem mengadaptasi indikator teknikal *Moving Average Convergence Divergence* (MACD). Mengingat panjang deret waktu Puskesmas adalah $N = 22$ periode dwibulanan, parameter finansial klasik $(12, 26, 9)$ dikonversi secara proporsional menjadi konfigurasi dwibulanan **$(2, 4, 2)$**.

Komponen dasar MACD dibangun di atas *Exponential Moving Average* ($\text{EMA}$) dengan faktor peluruhan $\alpha = \frac{2}{\text{span} + 1}$:

$$\text{EMA}_t(y, \text{span}) = \alpha \cdot y_t + (1 - \alpha) \cdot \text{EMA}_{t-1}$$

Dengan mensubstitusikan parameter $\text{span}_{\text{fast}} = 2$ ($\approx 4$ bulan kalender), $\text{span}_{\text{slow}} = 4$ ($\approx 8$ bulan kalender), dan $\text{span}_{\text{signal}} = 2$, diperoleh empat persamaan keadaan sistem:

1. **Fast EMA ($\text{span}=2 \implies \alpha_{\text{fast}} = \frac{2}{2+1} = 0,6667$):**

$$\text{EMA}_{\text{fast}, t} = 0,6667 \cdot y_t + 0,3333 \cdot \text{EMA}_{\text{fast}, t-1}$$

2. **Slow EMA ($\text{span}=4 \implies \alpha_{\text{slow}} = \frac{2}{4+1} = 0,4000$):**

$$\text{EMA}_{\text{slow}, t} = 0,4000 \cdot y_t + 0,6000 \cdot \text{EMA}_{\text{slow}, t-1}$$

3. **MACD Line (Selisih Konvergensi–Divergensi):**

$$\text{MACD}_t = \text{EMA}_{\text{fast}, t} - \text{EMA}_{\text{slow}, t}$$

4. **Signal Line ($\text{span}=2 \implies \alpha_{\text{sig}} = 0,6667$) dan MACD Histogram:**

$$\text{Signal}_t = 0,6667 \cdot \text{MACD}_t + 0,3333 \cdot \text{Signal}_{t-1}$$

$$\text{Histogram}_t = \text{MACD}_t - \text{Signal}_t$$

Interaksi antara tanda aljabar $\text{MACD}_t$ dan $\text{Histogram}_t$ diklasifikasikan ke dalam empat status sinyal peringatan dini gudang farmasi sebagaimana dirinci pada Tabel IV.3.

**Tabel IV.3** Matriks Klasifikasi Status Sinyal Momentum MACD (2, 4, 2)

| Status Sinyal | Kondisi Matematis | Makna Epidemiologi & Logistik | Tindakan Gudang Farmasi |
| --- | --- | --- | --- |
| **Bullish Momentum** | $\text{MACD}_t > 0$ dan $\text{Hist}_t > 0$ | Lonjakan permintaan sedang mengalami akselerasi kuat | Tingkatkan kuota pengadaan & siapkan buffer |
| **Bullish Melemah** | $\text{MACD}_t > 0$ dan $\text{Hist}_t \le 0$ | Konsumsi masih di atas rata-rata, tetapi laju kenaikan melambat | Pertahankan stok normal, cegah over-order |
| **Bearish** | $\text{MACD}_t \le 0$ dan $\text{Hist}_t \le 0$ | Tren pemakaian obat sedang turun tajam (wabah mereda) | Kurangi volume pesanan untuk mencegah ED |
| **Rebound** | $\text{MACD}_t \le 0$ dan $\text{Hist}_t > 0$ | Permintaan mulai berbalik naik dari titik terendah | Mulai tambah pesanan periode berikutnya |

Tabel IV.3 memberikan panduan operasional otomatis bagi apoteker untuk membaca pergeseran siklus penyakit tanpa perlu menginterpretasikan grafik rumit secara manual.

---

## IV.4 Formulasi Integrasi Morbiditas SIMPUS (Rasio Dosis per Kasus $R_{i,j}$)

Untuk mengintegrasikan fluktuasi 10 penyakit terbesar SIMPUS (berdasarkan kode diagnosis ICD-10) ke dalam perencanaan obat LPLPO, diformulasikan koefisien korelasi linier Pearson ($r$), korelasi peringkat Spearman ($\rho$), serta **Rasio Dosis per Kasus** ($R_{i,j}$).

Koefisien korelasi Pearson ($r_{i,j}$) dan Spearman ($\rho_{i,j}$) antara vektor pemakaian obat ke-$i$ ($C_i$) dan vektor jumlah kasus penyakit ke-$j$ ($M_j$) selama $N=22$ periode dihitung dengan persamaan:

$$r_{i,j} = \frac{\sum_{t=1}^{N} (C_{i,t} - \bar{C}_i)(M_{j,t} - \bar{M}_j)}{\sqrt{\sum_{t=1}^{N} (C_{i,t} - \bar{C}_i)^2} \sqrt{\sum_{t=1}^{N} (M_{j,t} - \bar{M}_j)^2}}, \quad \rho_{i,j} = 1 - \frac{6 \sum_{t=1}^{N} d_t^2}{N(N^2 - 1)}$$

Di mana $d_t = \text{rank}(C_{i,t}) - \text{rank}(M_{j,t})$. Selanjutnya, **Rasio Dosis per Kasus ($R_{i,j}$)** didefinisikan sebagai indeks serapan efektif unit sediaan obat $i$ per satu episode kasus penyakit $j$:

$$R_{i,j} = \frac{\sum_{t=1}^{N} C_{i,j,t}}{\sum_{t=1}^{N} M_{j,t}}$$

Apabila jumlah kunjungan kasus penyakit $j$ pada periode mendatang diproyeksikan sebesar $\hat{M}_{j,\text{P5}}$, maka estimasi kebutuhan fisik obat berbasis morbiditas ($\hat{D}_{i,\text{P5}}^{\text{morb}}$) adalah:

$$\hat{D}_{i,\text{P5}}^{\text{morb}} = \hat{M}_{j,\text{P5}} \times R_{i,j}$$

Persamaan ini berfungsi sebagai instrumen validasi silang (*clinical sanity check*) untuk memastikan proyeksi statistik deret waktu LPLPO tidak pernah berada di bawah kebutuhan dasar klinis poli rawat jalan.

---

## IV.5 Formulasi Batas Persediaan Pengaman (Safety Stock 95% & Quantile Ceiling)

Untuk melindungi Puskesmas dari ketidakpastian lonjakan penyakit tanpa memicu penumpukan obat kadaluarsa (ED), pemodelan dilengkapi dengan dua pembatas stok pengaman:

### 1. Safety Stock Statistik pada Tingkat Layanan 95%
Dengan menetapkan *Service Level* sebesar 95% (risiko *stockout* maksimum $\alpha = 5\% \implies$ skor normal standar satu arah $Z_{0,95} = 1,645$), simpangan baku residu permintaan $\sigma_{d,i}$, dan waktu tunggu (*lead time*) distribusi dwibulanan $L = 1$ periode:

$$\text{SS}_i = Z_{0,95} \times \sigma_{d,i} \times \sqrt{L} = 1,645 \times \sigma_{d,i}$$

Sehingga total rekomendasi alokasi pengadaan pada dokumen LPLPO periode P5 ($\text{Alokasi}_{i,\text{P5}}$) dihitung menggunakan rumus:

$$\text{Alokasi}_{i,\text{P5}} = \max\left(0, \; \hat{y}_{i,\text{P5}} + \text{SS}_i - S_{\text{akhir}, i, \text{P4}}\right)$$

Pada kasus Parasetamol 500 mg periode 2026-P5 (dengan $\hat{y}_{\text{P5}}^{\text{SMA-2}} = 6.147$ tablet, $\text{SS} = 1.340$ tablet, dan $S_{\text{akhir, P4}} = 0$), diperoleh rekomendasi pengadaan sebesar $6.147 + 1.340 - 0 = \mathbf{7.487}$ tablet.

### 2. Safety Stock Ceiling Berbasis GBM Quantile Regression ($\tau = 0,95$)
Sebagai pengendali batas atas (*upper ceiling*) agar pengajuan tidak melampaui daya serap maksimum faskes, dilatih model *HistGradientBoosting Regressor* menggunakan fungsi kerugian asimetris *Pinball Loss* pada kuantil $\tau = 0,95$:

$$\mathcal{L}_{\tau}(y_t, \hat{q}_t) = \begin{cases} \tau (y_t - \hat{q}_t), & \text{jika } y_t \ge \hat{q}_t \\ (1 - \tau)(\hat{q}_t - y_t), & \text{jika } y_t < \hat{q}_t \end{cases}$$

Nilai prediksi kuantil $\hat{q}_{i, 0,95}$ mendefinisikan batas atap persediaan maksimum yang tidak boleh dilampaui saat apoteker menyusun usulan pengadaan, sehingga mengunci risiko *over-budgeting* penyebab obat kadaluarsa.

---

# BAB V: EVALUATION

Fase *Evaluation* dalam CRISP-DM bertujuan menguji tingkat akurasi, arah simpangan (*bias*), dan kelayakan klinis dari model yang telah dibangun sebelum diterapkan ke lingkungan operasional Puskesmas Teluk Sasah.

## V.1 Metrik Evaluasi Akurasi Deret Waktu

Pengujian performa model pada 149 item obat aktif menggunakan empat metrik evaluasi komplementer guna menghindari bias skala volume antar-item obat:

1. **Weighted Absolute Percentage Error (WAPE):** Mengukur rasio galat absolut total terhadap volume pemakaian total, sangat tangguh untuk dataset farmasi yang memiliki variasi volume lebar:

$$\text{WAPE} = \frac{\sum_{t=1}^{n} \vert{}y_t - \hat{y}_t\vert{}}{\sum_{t=1}^{n} y_t}$$

2. **Mean Absolute Percentage Error (MAPE):** Mengukur rata-rata persentase penyimpangan relatif pada setiap titik periode:

$$\text{MAPE} = \frac{100\%}{n} \sum_{t=1}^{n} \left\vert{} \frac{y_t - \hat{y}_t}{y_t} \right\vert{}$$

3. **Root Mean Squared Error (RMSE):** Memberikan penalti kuadratik terhadap galat berukuran besar dalam satuan fisik sediaan obat:

$$\text{RMSE} = \sqrt{\frac{1}{n} \sum_{t=1}^{n} (y_t - \hat{y}_t)^2}$$

4. **Forecasting Bias (%):** Mengukur kecenderungan sistematis arah kesalahan model, di mana nilai positif ($+$) menunjukkan *over-forecasting* dan nilai negatif ($-$) menunjukkan *under-forecasting*:

$$\text{Bias (\%)} = \frac{\sum_{t=1}^{n} (\hat{y}_t - y_t)}{\sum_{t=1}^{n} y_t} \times 100\%$$

---

## V.2 Hasil Evaluasi 10-Fold Expanding Window Walk-Forward Validation (149 Obat Aktif)

Untuk mencegah kebocoran informasi masa depan (*data leakage*), evaluasi tidak menggunakan *random k-fold split*, melainkan **10-Fold Expanding Window Walk-Forward Validation** sepanjang jendela uji **2025-P1 hingga 2026-P4**. Pada skema ini, model dilatih pada histori awal hingga periode $t$, diuji pada $t+1$, lalu jendela pelatihan diperluas secara sekuensial.

**Tabel V.1** Hasil Benchmarking 6 Model pada 10-Fold Walk-Forward Validation (149 Obat Aktif)

| Nama Model | WAPE | Bias (%) | Karakteristik Akurasi | Keputusan Peran Sistem |
| --- | --- | --- | --- | --- |
| **HistGradientBoosting (GBM)** | **0,536** | **-9,2%** | Galat agregat terendah, tetapi bias negatif | Ditolak sebagai estimator tunggal (Risiko Stockout) |
| **Naive (Lag-1)** | 0,559 | +1,8% | Responsif namun rentan *random shock* | Baseline pembanding dasar |
| **WMA (k=3)** | **0,595** | **+4,3%** | **Unggul di 58,7% obat (MAPE 14,2%)** | **Model Operasional Utama Terpilih** |
| **SMA (k=2)** | 0,597 | +3,0% | Stabil pada obat konsumsi rata | Estimator pendamping / komparator |
| **Ridge Regression (L2)** | 0,647 | +5,3% | Kurang adaptif terhadap perubahan non-linier | Pembanding regresi linier terpilin |
| **GBM Quantile (0,95)** | 1,209 | +101,3% | Estimasi kuantil ekstrem atas (95%) | **Safety Stock Ceiling (Batas Atap)** |

Berdasarkan Tabel V.1, keputusan pemilihan model operasional PRABOTIK didasarkan pada analisis **Accuracy–Bias Trade-Off** dalam domain logistik klinis:

* **Mengapa HistGradientBoosting Murni (Bias -9,2%) Berbahaya bagi Faskes:** Meskipun model *Machine Learning* GBM mencatatkan WAPE terendah ($0,536$), model ini memiliki simpangan sistematis **Bias $-9,2\%$**. Dalam pelayanan medis dasar, fungsi risiko bersifat asimetris: kekurangan obat esensial sebesar 9,2% saat wabah penyakit berlangsung akan langsung memicu *stockout* dan membahayakan keselamatan pasien.
* **Mengapa WMA-3 (Bias +4,3%, MAPE 14,2%) Menjadi Model Operasional Terbaik:** Model $\text{WMA-3}$ berhasil memenangkan akurasi terbaik pada **58,7% item obat** dengan rata-rata MAPE sebesar **14,2%** (kategori *Good Forecast* menurut kriteria Lewis, $<20\%$). Keunggulan paling krusial dari $\text{WMA-3}$ adalah karakteristik **Bias positif moderat $+4,3\%$**. Simpangan positif $+4,3\%$ ini bekerja sebagai penyangga pengaman alami (*natural safety buffer*) yang menyerap lonjakan kunjungan pasien harian tanpa memicu penumpukan stok berlebih yang menyebabkan kadaluarsa.

---

## V.3 Evaluasi Rekonstruksi Permintaan Tersensor (Kasus Parasetamol 2026-P4)

Evaluasi khusus dilakukan pada studi kasus **Parasetamol 500 mg** untuk mengukur efektivitas modul *Censored Demand Adjustment* dalam memperbaiki kegagalan peramalan pasca-*stockout* periode 2026-P4.

**Tabel V.2** Komparasi Evaluasi Perencanaan Parasetamol 500 mg (Unadjusted vs Censored-Adjusted)

| Indikator Evaluasi | Model Tanpa Koreksi | Model Terkoreksi PRABOTIK | Dampak Operasional Faskes |
| --- | --- | --- | --- |
| Input Periode 2026-P4 | 0 tablet | 5.850 tablet | Eliminasi bias nol semu (*supply truncation*) |
| Titik Proyeksi SMA-2 | 3.222 tablet | 6.147 tablet | Pemulihan kuota dasar +2.925 tablet |
| Titik Proyeksi WMA-3 | 3.024 tablet | 5.949 tablet | Pemulihan kuota dasar +2.925 tablet |
| Alokasi Final (+SS 95%) | 4.562 tablet | **7.487 tablet** | Menutup defisit musim puncak ISPA |
| Histogram MACD (2,4,2) | -485,42 (Crash) | **+34,58 (Rebound)** | Mengubah sinyal palsu menjadi sinyal siaga |

Sebagaimana ditunjukkan pada Tabel V.2, tanpa koreksi permintaan tersensor, sistem konvensional hanya akan mengusulkan $3.222$ tablet (atau $4.562$ tablet beserta *Safety Stock*), yang berada di bawah kebutuhan tunggal poli ISPA saja ($4.541$ tablet), sehingga menjamin terjadinya kekosongan obat di pertengahan bulan Oktober. Rekonstruksi PRABOTIK ke angka **7.487 tablet** memastikan ketersediaan penuh untuk seluruh poli layanan.

---

## V.4 Evaluasi Korelasi Klinis Morbiditas SIMPUS

Pengujian validasi eksternal dilakukan dengan mengevaluasi matriks korelasi antara 10 penyakit terbesar SIMPUS terhadap pengeluaran obat LPLPO sepanjang 22 periode dwibulanan.

**Tabel V.3** Hasil Evaluasi Korelasi Statistik dan Rasio Dosis per Kasus ($R_{i,j}$)

| Kode & Nama Penyakit | Obat Terkait | Pearson ($r$) | Spearman ($\rho$) | Rasio ($R_{i,j}$) |
| --- | --- | --- | --- | --- |
| **J00 ISPA (34,8%)** | Parasetamol 500 mg | 0,5851 | 0,5517 | 13,43 tab/kasus |
| **J00 ISPA (34,8%)** | Amoksisilin 500 mg | 0,6351 | 0,6702 | 5,06 kap/kasus |
| **J00 ISPA (34,8%)** | Ambroksol 30 mg | Positif | Positif | 3,30 tab/kasus |
| **I10 Hipertensi (24,0%)** | Amlodipin 10 mg | Konsisten | Konsisten | 19,08 tab/kasus |
| **I10 Hipertensi (24,0%)** | Amlodipin 5 mg | Konsisten | Konsisten | 6,78 tab/kasus |
| **I10 Hipertensi (24,0%)** | Kaptopril 25 mg | 0,1751 | Lemah | 2,96 tab/kasus |
| **E11 DM Tipe 2** | **Metformin 500 mg** | **0,5942** | **0,7753** | **40,95 tab/kasus** |
| **E11 DM Tipe 2** | Glibenklamid 5 mg | 0,3028 | 0,3533 | 6,53 tab/kasus |
| **K30 Dispepsia** | Antasida DOEN I | Stabil | Stabil | 5,59 tab/kasus |
| **K30 Dispepsia** | Ranitidine 150 mg | Lemah | 0,2417 | 4,48 tab/kasus |

Evaluasi terhadap Tabel V.3 menghasilkan tiga kesimpulan validasi klinis:
* **Validasi Pola Infeksi Musiman (ISPA):** Korelasi kuat pada Parasetamol ($r = 0,5851$) dan Amoksisilin ($\rho = 0,6702$) membuktikan bahwa fluktuasi stok gudang didorong secara nyata oleh gelombang epidemiologi ISPA. Dengan proyeksi 338 kasus ISPA pada 2026-P5, serapan Parasetamol untuk poli ISPA mencapai $4.541$ tablet, sementara sisanya ($1.408\text{--}1.606$ tablet dari titik proyeksi MA) secara proporsional mengakomodasi kebutuhan Poli Gigi dan KIA/KB.
* **Validasi Kepatuhan Terapi Kronis (DM Tipe 2):** Nilai korelasi peringkat tertinggi di seluruh faskes tercatat pada pasangan DM Tipe 2 dan Metformin 500 mg ($\rho = 0,7753$) dengan rasio $40,95$ tablet/kasus per periode dwibulanan, mengonfirmasi konsistensi peresepan terapi rumatan lini pertama sesuai Formularium Nasional.
* **Deteksi Substitusi Regimen Antihipertensi:** Korelasi rendah pada Kaptopril 25 mg ($r = 0,1751$; rasio $2,96$) berbanding terbalik dengan tingginya rasio serapan Amlodipin 10 mg ($19,08$ tablet/kasus), yang berhasil memetakan pergeseran preferensi klinis dokter dari golongan *ACE Inhibitor* ke *Calcium Channel Blocker*.

---

## V.5 Rekomendasi Alokasi Pengadaan P5 (September–Oktober 2026)

Sebagai sintesis akhir dari tahap *Modeling* dan *Evaluation*, Tabel V.4 merangkum rekomendasi alokasi pengadaan obat prioritas untuk periode **2026-P5** yang siap diumpankan ke dalam tahap **Bab VI (Deployment)** pada aplikasi PRABOTIK.

**Tabel V.4** Rekomendasi Pengadaan Obat Prioritas Periode 2026-P5 UPTD Puskesmas Teluk Sasah

| Nama Item Obat | Kelas Terapi Utama | Basis Proyeksi MA | Kebutuhan Morbiditas | Rekomendasi Final P5 |
| --- | --- | --- | --- | --- |
| **Parasetamol 500 mg** | Analgesik-Antipiretik (J00) | 5.949 – 6.147 tab | 4.541 tab (Khusus ISPA) | **7.487 tablet** (+SS 95%) |
| **Amlodipin 10 mg** | Antihipertensi Rumatan (I10) | 6.210 tab | 6.534 tab (342,5 kasus) | **6.534 tablet** |
| **Metformin 500 mg** | Antidiabetes Biguanid (E11) | 2.763 – 2.805 tab | 2.826 tab (69 kasus) | **2.826 tablet** |
| **Amlodipin 5 mg** | Antihipertensi Awal (I10) | 2.250 tab | 2.323 tab (342,5 kasus) | **2.323 tablet** |
| **Amoksisilin 500 mg** | Antibiotik Penisilin (J00) | 1.680 kap | 1.710 kap (338 kasus) | **1.710 kaplet** |
| **Ambroksol 30 mg** | Mukolitik Pernapasan (J00) | 1.090 tab | 1.115 tab (338 kasus) | **1.115 tablet** |
| **Kaptopril 25 mg** | Antihipertensi ACE-I (I10) | 980 tab | 1.015 tab (342,5 kasus) | **1.015 tablet** |
| **Antasida DOEN I** | Antasida Ulkus (K30) | 800 tab | 814 tab (145,7 kasus) | **814 tablet** |
| **Ranitidine 150 mg** | Antagonis H2 (K30) | 640 tab | 653 tab (145,7 kasus) | **653 tablet** |
| **Glibenklamid 5 mg** | Antidiabetes Sulfonilurea | 435 tab | 450 tab (69 kasus) | **450 tablet** |

Seluruh kuantitas rekomendasi pada Tabel V.4 telah mengintegrasikan koreksi *Censored Demand*, penyangga *Safety Stock* 95%, serta batas bawah rasio morbiditas SIMPUS guna menjamin nihilnya insiden *stockout* sekaligus melindungi anggaran Puskesmas dari risiko obat kadaluarsa.

---

# BAB VI: PENERAPAN (DEPLOYMENT)

Fase *Deployment* (*Penerapan*) merupakan muara akhir dari siklus metodologi CRISP-DM dalam laporan proyek `FinPD_2401020069_Laporan_Akhir_Kelompok_23.docx` yang menerjemahkan hasil temuan algoritmik penambangan data menjadi instrumen perangkat lunak operasional di UPTD Puskesmas Teluk Sasah. Tanpa antarmuka penerapan yang terintegrasi dengan proses bisnis faskes, model peramalan deret waktu dan matriks korelasi penyakit hanya akan berhenti sebagai artefak perhitungan statis yang sulit dieksekusi secara rutin oleh tenaga kesehatan. Oleh sebab itu, Kelompok 23 mengembangkan **PRABOTIK** (*Sistem Prediksi Kebutuhan Obat & Analisis Morbiditas Penyakit Berbasis Data Mining*) sebagai wujud hilirisasi seluruh rangkaian prapemrosesan dan pemodelan data.

---

## VI.1 Desain Implementasi Sistem PRABOTIK

### 1. Arsitektur Perangkat Lunak Terpadu (Tech Stack)

Sistem PRABOTIK dirancang menggunakan arsitektur aplikasi web modular yang menggabungkan ketangguhan pemrosesan sisi peladen (*server-side*) dengan interaktivitas visualisasi data modern:

* **Kerangka Kerja Backend (Laravel 11):** Mengelola logika bisnis, autentikasi akses lintas peran, perhitungan rumus deret waktu secara waktu nyata (*real-time*), serta pembuatan berkas cetak legalitas pengadaan.
* **Basis Data Mandiri (SQLite dengan Mode WAL):** Menyimpan seluruh tabel relasional transaksi LPLPO, rekapitulasi morbiditas SIMPUS, katalog Formularium Nasional, dan histori audit obat kadaluarsa dalam satu berkas portabel (`database.sqlite`).
* **Antarmuka Pengguna (Tailwind CSS):** Membangun tata letak responsif berorientasi ergonomika kerja faskes dengan skema warna semantik untuk penanda status bahaya stok (*merah*), peringatan ambang batas (*kuning*), dan stok aman (*hijau*).
* **Visualisasi Analitik (Chart.js):** Merender grafik garis deret waktu pemakaian obat, kurva proyeksi $\text{WMA-3}$ dan $\text{SMA-2}$, osilator momentum histogram $\text{MACD (2, 4, 2)}$, serta proporsi epidemiologi 10 penyakit terbesar SIMPUS secara dinamis di sisi peramban.

### 2. Rasionalitas Pemilihan Basis Data Mandiri SQLite di Tingkat Faskes

Pemilihan mesin basis data SQLite menggantikan arsitektur *client-server* konvensional (seperti MySQL atau PostgreSQL) didasarkan pada evaluasi beban kerja teknis dan realitas infrastruktur teknologi informasi di Puskesmas wilayah kepulauan:

* **Portabilitas Tanpa Konfigurasi (*Zero-Configuration Portability*):** Seluruh skema dan rekaman data tersimpan di dalam berkas tunggal `database.sqlite`, sehingga sistem dapat langsung dijalankan di komputer lokal Puskesmas tanpa instalasi *daemon* server terpisah.
* **Nol Biaya Infrastruktur & Lisensi Tambahan:** Menghilangkan beban anggaran daerah untuk penyewaan *cloud server* bulanan maupun pemeliharaan server fisik khusus.
* **Kemudahan Pencadangan Satu Berkas (*Single-File Backup*):** Petugas farmasi dapat mencadangkan seluruh histori logistik Puskesmas hanya dengan menyalin satu berkas basis data ke media penyimpanan eksternal atau repositori dinas.
* **Kecepatan Komputasi *Direct Disk I/O* & Mode WAL:** Melalui pengaktifan *Write-Ahead Logging* (`PRAGMA journal_mode=WAL;`), operasi baca analitik dan tulis transaksi antara portal farmasi dan portal kepala puskesmas berlangsung secara konkuren dalam hitungan sub-milidetik tanpa kendala *database locking*.

### 3. Mekanisme Integrasi Pipeline Analitik Python dan Kontroler Laravel

Untuk menjembatani hasil eksperimen penambangan data pada lingkungan Python (`Tahap_01` hingga `Tahap_05`) dengan operasional harian di aplikasi web Laravel 11, diterapkan pola arsitektur **Hybrid Analytical-Operational Integration**. Eksperimen berat seperti *10-Fold Expanding Window Walk-Forward Validation* dan pelatihan *HistGradientBoosting Quantile Regression* dieksekusi pada modul Python untuk menetapkan parameter model yang optimal, sedangkan rumus inferensi pemenang ditanamkan langsung ke dalam kelas kontroler Laravel (`PharmacyController` dan `ExecutiveController`).

**Tabel VI.1** Pemetaan Integrasi Modul Analitik CRISP-DM ke Kontroler Aplikasi PRABOTIK

| Modul Penambangan Data | Parameter / Rumus Tertanam | Lokasi Eksekusi Laravel | Tabel SQLite Terkait |
| --- | --- | --- | --- |
| **Censored Demand Gate** | 3 syarat logika & imputasi lokal 2 periode | `PharmacyController` | `lplpo_transactions` |
| **Estimator Utama WMA-3** | Bobot linier $[1, 2, 3]$ ($\sum w = 6$) | `PharmacyController` | `procurement_plans` |
| **Momentum MACD (2,4,2)** | $\alpha_{\text{fast}}=0,667$, $\alpha_{\text{slow}}=0,400$ | `PharmacyController` | `drug_master`, `lplpo` |
| **Safety Stock & Ceiling** | $Z_{0,95}=1,645$ & batas kuantil $\tau=0,95$ | `PharmacyController` | `procurement_plans` |
| **Matriks Rasio $R_{i,j}$** | Korelasi ICD-10 & serapan dosis/kasus | `ExecutiveController` | `simpus_morbidities` |
| **Audit FEFO & Kerugian ED** | Kalkulasi valuasi batch & kepatuhan SPM | `ExecutiveController` | `expired_drug_audits` |

Sebagaimana dipetakan pada Tabel VI.1, setiap kali apoteker memasukkan transaksi LPLPO atau morbiditas SIMPUS periode terbaru melalui formulir aplikasi, kontroler Laravel secara otomatis menarik deret historis dari `database.sqlite`, mengeksekusi gerbang logika *Censored Demand*, menghitung ulang proyeksi $\text{WMA-3}$ beserta sinyal $\text{MACD (2, 4, 2)}$, dan menyajikan hasilnya secara seketika di layar pengguna.

---

## VI.2 Alur Kerja Arsitektur Dual-Portal (Pemisahan Peran Operasional vs Eksekutif)

Temuan pada fase *Business Understanding* menunjukkan bahwa kegagalan adopsi sistem informasi di Puskesmas sering terjadi akibat penyatuan seluruh fitur teknis gudang dan manajerial anggaran ke dalam satu tampilan yang padat. PRABOTIK mengatasi masalah tersebut dengan membagi aplikasi menjadi tiga komponen antarmuka yang terpisah secara fungsional:

### 1. Pintu Masuk Utama / Gateway Transit (`/`)

Halaman *Gateway Transit* berfungsi sebagai gerbang otorisasi resmi UPTD Puskesmas Teluk Sasah yang mengidentifikasi kredensial pengguna dan mengarahkan sesi kerja secara spesifik ke lingkungan operasional gudang atau lingkungan eksekutif pimpinan. Pemisahan jalur masuk ini menjamin keamanan tata kelola data (*role-based access control*), di mana hanya pengelola farmasi yang berwenang memodifikasi mutasi fisik stok, dan hanya Kepala Puskesmas yang berwenang menerbitkan pengesahan disposisi anggaran.

### 2. Portal Operasional Farmasi (`/farmasi`)

Portal ini dirancang khusus bagi **Penanggung Jawab Farmasi dan Pengelola Gudang Obat (Apt. Siti Rahmawati, S.Farm)** sebagai meja kerja teknis harian dengan tujuh fitur terintegrasi:

* **Monitoring Fisik Gudang & Buffer Warning:** Menampilkan kartu indikator real-time sisa stok fisik, kapasitas gudang, dan peringatan dini bagi item obat yang melintasi ambang batas stok pengaman.
* **Formulir Entri Data Terpadu (4 Tab):** Menyediakan antarmuka input terstruktur yang mencakup tab laporan mutasi LPLPO dwibulanan, tab kasus baru/lama morbiditas SIMPUS, tab berita acara obat rusak/ED, serta tab registrasi master obat baru.
* **Rencana Pengadaan P5 Terkoreksi Censored Demand:** Menyajikan tabel kalkulasi otomatis yang menyandingkan angka proyeksi tanpa koreksi dengan angka proyeksi terkoreksi *Censored Demand* (seperti pemulihan kuota Parasetamol 500 mg menjadi 7.487 tablet).
* **Katalog Formularium 149 Obat Aktif:** Memuat profil statistik lengkap setiap item obat, termasuk rata-rata pemakaian ($\mu$), simpangan baku ($\sigma$), dan klasifikasi Koefisien Variasi ($CV$).
* **Panel Analisis Momentum MACD (2, 4, 2):** Mengklasifikasikan seluruh obat ke dalam status sinyal *Bullish Momentum*, *Bullish Melemah*, *Bearish*, atau *Rebound* guna mendeteksi lonjakan permintaan musiman lebih awal.
* **Audit Pengawasan Stok FEFO:** Melacak tanggal kadaluarsa per batch kedatangan obat dan mendokumentasikan 138 item histori obat kadaluarsa agar barang dengan masa simpan terdekat wajib dikeluarkan lebih dahulu.
* **Cetak Lembar Kerja LPLPO Apotek:** Menghasilkan dokumen draf rencana kebutuhan obat internal apotek yang siap diajukan ke meja pimpinan untuk ditinjau.

### 3. Portal Eksekutif Kepala Puskesmas (`/kepala`)

Portal ini dirancang khusus bagi **Kepala Puskesmas selaku Kuasa Pengguna Anggaran / KPA (dr. Bambang Utoyo, M.M.)** dengan menitikberatkan pada pengawasan kinerja pelayanan, akuntabilitas keuangan daerah, dan keabsahan birokrasi:

* **Pemantauan KPI SPM Kemenkes (91,3%):** Menampilkan indikator persentase ketersediaan 40 item obat dan vaksin esensial nasional sesuai target Standar Pelayanan Minimal Kementerian Kesehatan.
* **Review & Disposisi Pengesahan Pengadaan P5 Ber-NIP:** Memungkinkan Kepala Puskesmas meninjau usulan pengadaan dari apoteker, memberikan catatan instruksi manajerial, dan menerbitkan pengesahan dilengkapi nomor register resmi serta kotak legalitas tanda tangan ber-NIP untuk dikirimkan ke Dinas Kesehatan Kabupaten Bintan.
* **Analisis Tren Epidemiologi SIMPUS:** Memvisualisasikan proporsi dan pergerakan 10 penyakit terbesar (dominasi ISPA 34,8% dan Hipertensi Primer 24,0%) sebagai dasar pembenaran klinis atas kenaikan kuota obat tertentu.
* **Evaluasi Audit Finansial Kerugian Obat ED:** Menyajikan transparansi nilai kerugian fisik obat kadaluarsa senilai **Rp 43.348.626,-** (akumulasi dana APBD dan DAK tahun 2023–2025) beserta rincian tren tahunannya guna mengendalikan pengadaan obat berisiko tinggi.
* **Pengawasan Standar Terapi & Rasio Peresepan Poli:** Memantau rasio penyerapan obat per kasus ($R_{i,j}$) dan persentase kepatuhan peresepan dokter di Poli Umum, Poli Gigi, serta Poli KIA/KB terhadap Formularium Nasional.
* **Cetak Dokumen LPLPO Resmi Dinas:** Menghasilkan cetakan berkas LPLPO akhir berformat baku pemerintah daerah yang telah tervalidasi secara statistik dan sah secara administratif.

**Tabel VI.2** Matriks Perbandingan Fungsional Arsitektur Dual-Portal PRABOTIK

| Dimensi Sistem | Portal Operasional Farmasi (`/farmasi`) | Portal Eksekutif Kepala (`/kepala`) |
| --- | --- | --- |
| **Aktor Pengguna Utama** | Apoteker & Pengelola Gudang Obat | Kepala Puskesmas / KPA Anggaran |
| **Fokus Kognitif Kerja** | Kendali mutasi fisik, FEFO, & kalkulasi stok | Kepatuhan SPM, audit anggaran, & legalitas |
| **Hak Akses Data** | Entri transaksi LPLPO, SIMPUS, & draf P5 | Review, koreksi plafon, & disposisi pengesahan |
| **Instrumen Analitik** | Proyeksi WMA-3, SS 95%, & sinyal MACD | Korelasi morbiditas ICD-10 & tren kerugian ED |
| **Keluaran Dokumen** | Lembar Kerja Perencanaan Internal Apotek | Berkas LPLPO Resmi & Disposisi P5 Ber-NIP |

Pemisahan peran pada Tabel VI.2 memastikan alur birokrasi internal Puskesmas berjalan tertib, terukur, dan bebas dari risiko *over-ordering* sepihak.

---

## VI.3 Kesimpulan Akhir (Sintesis 6 Fase CRISP-DM)

Berdasarkan seluruh rangkaian tahapan penelitian penambangan data yang telah dilaksanakan oleh Kelompok 23 di UPTD Puskesmas Teluk Sasah mengikuti metodologi **CRISP-DM**, dapat ditarik sintesis kesimpulan menyeluruh sebagai berikut:

**1. Sintesis Fase Business Understanding:**  
Tata kelola logistik farmasi UPTD Puskesmas Teluk Sasah terbukti mengalami tiga paradoks sistemik akibat perencanaan manual konvensional, yaitu kerugian anggaran obat kadaluarsa (ED) mencapai **Rp 43.348.626,-** (meningkat dari Rp 9,10 juta pada 2023 menjadi Rp 22,92 juta pada 2025), krisis *stockout* berulang pada obat esensial (seperti Amlodipin 10 mg yang kosong pada 32% periode dan Ambroksol 30 mg pada 36% periode), serta distorsi perencanaan saat lonjakan penyakit musiman ISPA di pesisir Bintan.

**2. Sintesis Fase Data Understanding:**  
Eksplorasi terhadap 29 berkas LPLPO, 44 bulan rekapitulasi SIMPUS (ICD-10), dan 3 berkas tahunan Berita Acara Obat ED berhasil memetakan profil distribusi pemakaian obat menggunakan parameter Koefisien Variasi ($CV = \frac{\sigma}{\mu}$). Teridentifikasi pemisahan tajam antara obat kuratif berpola stabil seperti Antasida DOEN I ($CV=0,30$) dan Parasetamol 500 mg ($CV=0,42$), obat musiman berfluktuasi tinggi seperti Ambroksol ($CV=0,67$), hingga obat program kampanye massal berfluktuasi ekstrem yaitu Fero Sulfat ($CV=2,20$).

**3. Sintesis Fase Data Preparation (Preprocess):**  
Enam tahapan prapemrosesan berhasil menstandardisasi data heterogen menjadi matriks deret waktu kontinu berdimensi **149 obat aktif $\times$ 22 periode dwibulanan** (2023-P1 s.d. 2026-P4), membersihkan 144 baris dokumen ED menjadi 138 item murni, menyelesaikan *missing value* harga audit melalui *Cross-Table Domain Lookup* ke master LPLPO, memisahkan anomali *dropping* Gizi Fero Sulfat (118.295 tablet pada Januari 2024), serta mengimplementasikan gerbang logika **Censored Demand Imputation** yang merekonstruksi konsumsi Parasetamol 500 mg pada periode *stockout* 2026-P4 dari angka 0 semu menjadi **5.850 tablet**.

**4. Sintesis Fase Modeling:**  
Arsitektur pemodelan berhasil mengintegrasikan estimator deret waktu *Weighted Moving Average* ($\text{WMA-3}$) berbobot linier $[1, 2, 3]$, penyangga *Safety Stock* tingkat kepercayaan 95% ($Z=1,645$), batas atap kuantil *HistGradientBoosting* ($\tau=0,95$), indikator deteksi momentum **MACD Dwibulanan (2, 4, 2)** yang disesuaikan dengan panjang sampel pendek ($N=22$), serta formulasi **Rasio Dosis per Kasus ($R_{i,j}$)** sebagai jembatan kuantitatif antara metode konsumsi dan metode morbiditas.

**5. Sintesis Fase Evaluation:**  
Pengujian *10-Fold Expanding Window Walk-Forward Validation* terhadap 149 obat aktif membuktikan bahwa **$\text{WMA-3}$** merupakan model operasional terbaik yang unggul pada **58,7% item obat** dengan rata-rata MAPE **14,2%** (*Good Forecast*), WAPE **0,595**, dan **Bias positif moderat $+4,3\%$** yang bertindak sebagai penyangga pengaman klinis alami dibandingkan model *Machine Learning* GBM murni yang memiliki bias negatif berbahaya ($-9,2\%$). Pada studi kasus Parasetamol 500 mg periode 2026-P5, koreksi sistem berhasil memulihkan rekomendasi alokasi pengadaan dari distorsi 3.222 tablet menjadi **7.487 tablet** dan membalikkan sinyal histogram MACD dari $-485,42$ (*crash* semu) menjadi **$+34,58$ (*rebound*)**, yang tervalidasi penuh oleh korelasi signifikan kasus ISPA ($r=0,5851$) serta DM Tipe 2 terhadap Metformin ($\rho=0,7753$).

**6. Sintesis Fase Deployment:**  
Implementasi aplikasi web **PRABOTIK** berarsitektur *Dual-Portal* berbasis Laravel 11 dan SQLite berhasil menyediakan instrumen kerja yang ergonomis bagi Apoteker (`/farmasi`) dan Kepala Puskesmas (`/kepala`), mengotomatiskan peringatan stok kritis dan audit FEFO, menyajikan pantauan capaian SPM Kemenkes (**91,3%**), serta menghasilkan dokumen hukum Disposisi Rencana Pengadaan P5 ber-NIP yang siap mencegah terulangnya kerugian anggaran daerah.

---

## VI.4 Saran Pengembangan & Rekomendasi Manajerial

Sebagai tindak lanjut dari keberhasilan pengembangan sistem PRABOTIK, dirumuskan saran pengembangan keilmuan serta rekomendasi kebijakan praktis bagi instansi mitra:

### 1. Saran Pengembangan Akademik (Bidang Penambangan Data)
* **Integrasi Model Multivariat SARIMAX dengan Variabel Eksogen Cuaca:** Penelitian selanjutnya disarankan mengembangkan model *Seasonal Autoregressive Integrated Moving Average with Exogenous Regressors* (SARIMAX) yang memasukkan data klimatologi BMKG (curah hujan, kecepatan angin utara, dan kelembapan udara di Kepulauan Riau) bersama deret kasus SIMPUS sebagai prediktor eksternal simultan.
* **Pengembangan *Dynamic Ensemble Confidence Interval*:** Menggabungkan prediksi titik $\text{WMA-3}$ dengan estimasi rentang ketidakpastian dinamis menggunakan *LightGBM Quantile Ensemble* ($\tau \in [0,05; 0,95]$) guna menyesuaikan lebar *Safety Stock* secara otomatis terhadap perubahan volatilitas Koefisien Variasi ($CV$) tiap obat.

### 2. Saran Manajerial & Kebijakan (UPTD Puskesmas Teluk Sasah & Dinkes Bintan)
* **Standardisasi Wajib Koreksi *Censored Demand* pada Regulasi LPLPO:** Dinas Kesehatan Kabupaten Bintan disarankan menetapkan prosedur operasi standar (SOP) penyusunan LPLPO yang mewajibkan koreksi permintaan tersensor pada setiap item obat esensial yang mengalami *stockout*, agar Puskesmas tidak mengalami pemotongan alokasi kuota secara keliru pada periode berikutnya.
* **Replikasi Sistem *Dual-Portal* PRABOTIK Lintas Puskesmas:** Mengingat aplikasi PRABOTIK dibangun di atas basis data mandiri SQLite yang ringan dan bebas biaya server tambahan, Dinas Kesehatan Kabupaten Bintan dapat mereplikasi sistem ini ke seluruh jaringan Puskesmas di Kabupaten Bintan untuk menyeragamkan pengawasan FEFO, menjaga capaian SPM di atas 90%, dan mengeliminasi kerugian obat kadaluarsa.

---

## LAMPIRAN: Daftar Artefak Pendukung & Dokumentasi Sistem

Seluruh bukti fisik kode sumber, dataset hasil prapemrosesan, skrip pengujian validasi, serta tangkapan layar antarmuka sistem yang tertuang di dalam dokumen `FinPD_2401020069_Laporan_Akhir_Kelompok_23.docx` telah diorganisasikan secara terstruktur di dalam repositori proyek Kelompok 23.

**Tabel VI.3** Daftar Artefak Dokumentasi Visual dan Berkas Pendukung Laporan Akhir

| Kode Lampiran | Berkas / Media Terkait | Deskripsi Substansi Artefak Dokumentasi |
| --- | --- | --- |
| **Lampiran 1** | `word/media/image2.png` | Visualisasi tren eskalasi kerugian obat ED (2023–2025) senilai Rp 43,35 Juta dan profil masalah mitra |
| **Lampiran 2** | `word/media/image3.png` | Grafik distribusi Koefisien Variasi ($CV$) Top 15 obat dan anomali obat program Gizi Fero Sulfat |
| **Lampiran 3** | `word/media/image4.png` | Ilustrasi komparasi deret waktu *Unadjusted* vs *Censored Demand Imputation* Parasetamol 500 mg |
| **Lampiran 4** | `word/media/image5.png` | Grafik osilator indikator momentum $\text{MACD (2, 4, 2)}$ dan pemulihan sinyal *Rebound* $+34,58$ |
| **Lampiran 5** | `word/media/image6.png` | Hasil evaluasi *10-Fold Walk-Forward Validation* (WAPE vs Bias) across 6 model kandidat |
| **Lampiran 6** | `word/media/image7.png` | Peta bahang (*heatmap*) matriks korelasi Pearson $r$ & Spearman $\rho$ 10 penyakit SIMPUS (ICD-10) |
| **Lampiran 7** | `word/media/image8.png` | Tangkapan layar halaman utama *Gateway Transit* (`/`) UPTD Puskesmas Teluk Sasah |
| **Lampiran 8** | `word/media/image9.png` | Tangkapan layar *Dashboard* Portal Operasional Farmasi (`/farmasi`) & peringatan stok kritis |
| **Lampiran 9** | `word/media/image10.png` | Tangkapan layar Formulir Entri Data Terpadu 4 Tab & Tabel Rencana Pengadaan P5 di `/farmasi` |
| **Lampiran 10** | `word/media/image11.png` | Tangkapan layar *Dashboard* Portal Eksekutif Kepala Puskesmas (`/kepala`) & KPI SPM 91,3% |
| **Lampiran 11** | `word/media/image12.png` | Tangkapan layar Modul Review & Disposisi Pengesahan Rencana Pengadaan P5 Ber-NIP di `/kepala` |
| **Lampiran 12** | `word/media/image13.png` | Bukti cetakan berkas resmi LPLPO ber-NIP, cuplikan berkas mentah Excel, & dokumentasi faskes |

### Rincian Struktur Direktori Repositori Proyek Kelompok 23:
1. **Direktori `01_Dokumen_Akademik/`:** Memuat dokumen laporan akhir resmi `FinPD_2401020069_Laporan_Akhir_Kelompok_23.docx` yang merangkum Bab I hingga Bab VI sesuai template RTM mata kuliah Penambangan Data.
2. **Direktori `07_Alur_Data_Mentah_ke_Final/`:** Memuat skrip analitik Python dan dataset bertahap (`Tahap_01` s.d. `Tahap_05`), matriks pivot 22 periode dwibulanan, hasil *benchmarking* 10-Fold CV, serta dokumentasi `CATATAN_KOREKSI_DATA_TERSENSOR_PARACETAMOL.md`.
3. **Direktori `prabotik/`:** Memuat kode sumber lengkap aplikasi web berbasis Laravel 11, konfigurasi basis data portabel `database/database.sqlite`, kontroler perhitungan deret waktu, serta templat cetak dokumen disposisi P5 dan LPLPO resmi UPTD Puskesmas Teluk Sasah.

---

# DAFTAR PUSTAKA

* Agrawal, S., & Smith, J. (2020). *Healthcare Supply Chain Management: Operationalizing Data-Driven Logistics in Primary Care*. Journal of Medical Systems, 44(3), 55–68.
* Appel, G. (2005). *Technical Analysis Power Tools for Active Investors*. Financial Times Prentice Hall.
* Box, G. E. P., Jenkins, G. M., Reinsel, G. C., & Ljung, G. M. (2015). *Time Series Analysis: Forecasting and Control* (5th ed.). John Wiley & Sons.
* Chapman, P., Clinton, J., Kerber, T., Khabaza, T., Reinartz, T., Shearer, C., & Wirth, R. (2000). *CRISP-DM 1.0: Step-by-Step Data Mining Guide*. SPSS Inc.
* Departemen Kesehatan Republik Indonesia. (2016). *Peraturan Menteri Kesehatan Republik Indonesia Nomor 74 Tahun 2016 tentang Standar Pelayanan Kefarmasian di Puskesmas*. Kemenkes RI.
* Hyndman, R. J., & Athanasopoulos, G. (2021). *Forecasting: Principles and Practice* (3rd ed.). OTexts. https://OTexts.com/fpp3
* Kementerian Kesehatan Republik Indonesia. (2021). *Pedoman Perencanaan dan Pengelolaan Logistik Obat di Fasilitas Pelayanan Kesehatan Dasar*. Direktorat Jenderal Kefarmasian dan Alat Kesehatan.
* Lewis, C. D. (1982). *Industrial and Business Forecasting Methods*. Butterworth-Scientific.
* Murphy, J. J. (1999). *Technical Analysis of the Financial Markets: A Comprehensive Guide to Trading Methods and Applications*. New York Institute of Finance.
* Nahmias, S., & Olsen, T. L. (2015). *Production and Operations Analysis* (7th ed.). Waveland Press.
* Simchi-Levi, D., Kaminsky, P., & Simchi-Levi, E. (2008). *Designing and Managing the Supply Chain: Concepts, Strategies, and Case Studies* (3rd ed.). McGraw-Hill Irwin.
* Wirth, R., & Hipp, J. (2000). *CRISP-DM: Towards a Standard Process Model for Data Mining*. Proceedings of the 4th International Conference on the Practical Application of Knowledge Discovery and Data Mining, 29–39.
