# 📑 Bagian Pelengkap Laporan (Front-Matter & Back-Matter) — CRISP-DM Standard
**Mata Kuliah:** Penambangan Data (INF11121 – 5 SKS)  
**Institusi:** S1 Teknik Informatika, Fakultas Teknik dan Teknologi Kemaritiman, Universitas Maritim Raja Ali Haji (UMRAH)  
**Kelompok 23:** Nabil (Ketua), Balqis Manisa, Jian Safitri Wibowo, Annisa Darmawan, Yehezkiel Alman Siambaton  
**Dosen Pengampu:** Bapak Tekad Matulatan, S.Sos., S.Kom., M.Info.Tech. & Bapak Muhamad Fadli, S.Kom., M.Kom.  

---

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
