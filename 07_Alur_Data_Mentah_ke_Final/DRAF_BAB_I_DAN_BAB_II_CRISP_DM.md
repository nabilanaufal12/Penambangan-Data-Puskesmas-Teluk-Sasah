# 📑 Draf Naskah Bab I & Bab II (CRISP-DM Standard)
**Mata Kuliah:** Penambangan Data (INF11121 – 5 SKS)  
**Institusi:** S1 Teknik Informatika, Fakultas Teknik dan Teknologi Kemaritiman, Universitas Maritim Raja Ali Haji (UMRAH)  
**Kelompok 23:** Nabil (Ketua), Balqis Manisa, Jian Safitri Wibowo, Annisa Darmawan, Yehezkiel Alman Siambaton  
**Dosen Pengampu:** Bapak Tekad Matulatan, S.Sos., S.Kom., M.Info.Tech. & Bapak Muhamad Fadli, S.Kom., M.Kom.  

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
