# 📋 Catatan Metodologi, Limitasi Data & Benchmarking — Audit Komprehensif (November 2026)

> [!IMPORTANT]
> Dokumen ini merangkum seluruh temuan audit ilmiah, limitasi metodologi, serta pertimbangan teknis
> yang perlu diungkapkan secara transparan dalam laporan akademik dan sidang ujian akhir (*scientific transparency*).

---

## ✅ 1. Temuan Data yang Terverifikasi Valid

| Indikator | Nilai Tervalidasi | Metode & Catatan Verifikasi |
|-----------|-------------------|-----------------------------|
| **Total Transaksi LPLPO Bersih** | **15.781 baris** | Pembersihan baris noise numerik murni dari 29 file mentah (15.813 → 15.781 baris). |
| **Total Berita Acara Kadaluarsa** | **138 item** | Jumlah akumulasi baris fisik `lap ED` (35 item di 2023 + 55 item di 2024 + 48 item di 2025). |
| **Total Kerugian Kadaluarsa** | **Rp 43.348.626** | Verifikasi 100% dari 3 lembar berita acara fisik resmi farmasi. |
| **Total Rekapitulasi Penyakit** | **450 baris** | 45 bulan observasi (Januari 2023 s/d September 2026) × 10 diagnosis teratas per bulan. |
| **Total Kunjungan Pasien** | **31.125 pasien** | 12.915 laki-laki dan 18.210 perempuan terlayani di poli puskesmas. |
| **Kejadian Stockout Nyata** | **482 kejadian** | Tercatat pada 292 jenis obat/BMHP (kondisi stok akhir = 0 saat masih ada pemakaian atau kebutuhan). |
| **Parser Tanggal Bulan Penyakit** | Terkoreksi Penuh | Bug sel datetime (April, September, November), typo "Feberuari", dan spasi ganda telah tuntas diperbaiki. |
| **Sheet Ganda Mar-Apr 2026** | Terverifikasi Tepat | Parser secara otomatis memilih sheet utama `lplpo th 2026` (bukan sheet duplikat `SELENA APRIL`). |

---

## ⚠️ 2. Limitasi Metodologi yang Wajib Diungkapkan dalam Laporan

### A. Sifat Data Penyakit: Tersensor Peringkat 10 Besar (*Top-Censored Data*)
* Data rekam medis SIMPUS Puskesmas Teluk Sasah hanya mencatat **10 besar diagnosis penyakit terbanyak per bulan**.
* Penyakit yang tidak tercantum pada bulan tertentu **bukan berarti nol kasus di lapangan**, melainkan frekuensinya berada di bawah peringkat ke-10 (*top-censored*).
* **Fakta Distribusi Empiris 4 Penyakit Terbanyak**:
  * Tiga penyakit utama: **`J00` (ISPA)**, **`I10` (Hipertensi)**, dan **`K30` (Dispepsia)** hadir secara konsisten di **seluruh 45 dari 45 bulan pengamatan (100%)**.
  * Penyakit **`E11` (Diabetes Melitus Tipe 2)** hadir di **43 dari 45 bulan** (sempat turun ke peringkat 11–12 pada September 2024 dan Januari 2025, bukan berarti tidak ada kasus).

### B. Klarifikasi Angka 138 Item
* Angka **138** adalah total baris fisik obat/BMHP rusak dan kadaluarsa yang tercatat di Berita Acara Pemusnahan Obat (`lap ED` 2023–2025).
* **Bukan jumlah katalog LPLPO**: Katalog logistik LPLPO Puskesmas mencakup 737 nama sediaan unik, di mana 150 obat aktif dikaji secara mendalam dalam pemodelan deret waktu.

### C. Analisis Korelasi Farmakoterapi Simultan vs Kausalitas
* Korelasi antara penyakit dan obat bersifat **korelasi simultan / farmakoterapi preskripsi**, bukan pembuktian kausalitas murni satu arah (seperti *Granger Causality*).
* Hal ini disebabkan resep dokter keluar pada hari yang sama dengan diagnosis, sedangkan pembukuan LPLPO diagregasikan per dwibulan.
* Rasio dosis Metformin (~39,95 butir/kasus) merepresentasikan konsumsi pemeliharaan kronis rutin pasien Prolanis selama 2 bulan, bukan dosis episode akut satu kali datang.

---

## 🔍 3. Koreksi Permintaan Tersensor (*Censored Demand*) Parasetamol 2026-P4

* **Temuan Kasus**: Pada LPLPO periode Juli–Agustus 2026 (`2026-P4`), Parasetamol 500 mg tercatat dengan:  
  $$\text{Stok Awal} = 0, \quad \text{Penerimaan} = 0, \quad \text{Pemakaian} = 0, \quad \text{Stok Akhir} = 0$$
* **Akar Masalah**: Pada 2026-P3, seluruh stok 6.444 tablet habis diserap pasien. Puskesmas mengalami **kekosongan stok total (*Total Stockout*)** di P4 sehingga pemakaian tercatat 0 bukan karena ketiadaan penyakit, melainkan ketiadaan pasokan fisik.
* **Metodologi Imputasi**: Nilai kebutuhan P4 diimputasi menggunakan rata-rata konsumsi periode normal sebelum stockout:
  $$\hat{y}_{\text{P4}} = \frac{5.256 + 6.444}{2} = \mathbf{5.850 \text{ tablet}}$$
* **Dampak Rekalkulasi Pengadaan 2026-P5**:
  * Proyeksi Titik SMA-2 meningkat dari **3.222 tablet** (cacat tanpa koreksi) menjadi **6.147 tablet** (realistis).
  * Ditambah Cadangan Pengaman (*Safety Stock 95%*) sebesar **1.340 tablet**, maka rekomendasi total pengadaan menjadi **7.487 tablet**, menjamin ketersediaan obat saat puncak musim ISPA tiba.

---

## 🤖 4. Evaluasi Benchmarking Machine Learning (10-Fold Walk-Forward)

Pengujian komparatif dilakukan menggunakan skema *10-Fold Expanding Window Walk-Forward Validation* (periode latih selalu $t < \text{Fold}$ untuk menguji $t = \text{Fold}$) pada panel 148 obat aktif:

| Model / Algoritma | Kategori | WAPE | Bias Persentase | Karakteristik Operasional |
| :--- | :---: | :---: | :---: | :--- |
| **HistGradientBoosting (GBM)** | Machine Learning | **0.536 (53.6%)** | **-9.2%** | Galat terendah, namun **bias negatif** konsisten meramal lebih rendah dari aktual. |
| **Naive (Lag-1)** | Statistik Baseline | 0.559 (55.9%) | +1.8% | Sangat kompetitif meski tanpa parameter kompleks. |
| **WMA (k=3)** | Statistik Baseline | 0.595 (59.5%) | +4.3% | Model baseline resmi, bias positif bertindak sebagai buffer alami. |
| **SMA (k=2)** | Statistik Baseline | 0.597 (59.7%) | +3.0% | Responsif terhadap fluktuasi cepat. |
| **Ridge Regression (L2)** | Machine Learning | 0.647 (64.7%) | +5.3% | Kurang fleksibel pada pola konsumsi non-linear. |
| **Seasonal Naive (Lag-6)** | Statistik Baseline | 0.851 (85.1%) | +6.4% | Pola musiman murni per-obat sangat bising. |
| **GBM Quantile ($\alpha=0.95$)** | Machine Learning | 1.209 (120.9%) | +101.3% | Plafon batas atas pengadaan (*Safety Stock Ceiling*). |

### 💡 Kesimpulan Ilmiah untuk Presentasi & Laporan:
1. **Permintaan Farmasi Sangat Fluktuatif**: Nilai WAPE terbaik berada di kisaran ~53%, mencerminkan dinamika droping logistik dinas yang tidak selalu mulus setiap periode.
2. **Bahaya Bias Negatif ML**: HistGB meramal rata-rata 9.2% lebih rendah dari kebutuhan aktual. Dalam manajemen obat puskesmas, under-forecast adalah hal yang fatal karena memicu krisis obat (*stockout*).
3. **Keunggulan Moving Average + Buffer**: Moving Average (WMA-3) dengan bias positif (+4.3%) yang dipadukan dengan *Safety Stock 95%* terbukti menjadi kebijakan manajemen inventaris yang paling aman dan tangguh bagi fasilitas kesehatan tingkat pertama.

---

## 🍎 5. Penjelasan Anomali Fero Sulfat 119.120 Tablet (Januari 2024)

* Lonjakan konsumsi **119.120 tablet** pada periode 2024-P1 adalah **100% data riil otentik** dari berkas LPLPO Puskesmas (tercatat penerimaan 117.100 tablet dan pemakaian 118.295 tablet pada baris 122 berkas `1 JANUARI 2024.xls` dengan keterangan resmi **`"GIZI"`**).
* Lonjakan ini merupakan **program aksi bergizi serentak / pemberian Tablet Tambah Darah (TTD) massal** untuk seluruh siswi SMP/SMA dan Posyandu ibu hamil di wilayah kerja Puskesmas Teluk Sasah.
* Dalam analisis penambangan data, sediaan ini diberi bendera `flag_program_gizi` dan dipisahkan dari peramalan kuratif poli umum agar tidak mendistorsi variansi data obat harian.
