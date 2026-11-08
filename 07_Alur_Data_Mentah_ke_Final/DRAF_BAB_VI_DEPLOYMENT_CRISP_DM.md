# 📑 Draf Naskah Bab VI: Penerapan (Deployment) — CRISP-DM Standard
**Mata Kuliah:** Penambangan Data (INF11121 – 5 SKS)  
**Institusi:** S1 Teknik Informatika, Fakultas Teknik dan Teknologi Kemaritiman, Universitas Maritim Raja Ali Haji (UMRAH)  
**Kelompok 23:** Nabil (Ketua), Balqis Manisa, Jian Safitri Wibowo, Annisa Darmawan, Yehezkiel Alman Siambaton  
**Dosen Pengampu:** Bapak Tekad Matulatan, S.Sos., S.Kom., M.Info.Tech. & Bapak Muhamad Fadli, S.Kom., M.Kom.  

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
