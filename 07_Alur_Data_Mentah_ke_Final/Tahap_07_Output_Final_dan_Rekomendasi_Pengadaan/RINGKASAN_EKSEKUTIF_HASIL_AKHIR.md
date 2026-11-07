# 📑 RINGKASAN EKSEKUTIF PROYEK PENAMBANGAN DATA (INF11121)
## **Prediksi Kebutuhan Stok Obat dan Analisis Tren Penyakit Musiman pada UPTD Puskesmas Teluk Sasah**

---

### 👥 Identitas Pelaksana
* **Kelompok 23 (Kelas TI24)**: Nabil (2401020073), Balqis Manisa (2401020069), Jian Safitri Wibowo (2401020056), Annisa Darmawan (2401020087), Yehezkiel Alman Siambaton (2401020110).
* **Dosen Pengampu**: Tekad Matulatan, S.Sos., S.Kom., M.Info.Tech. & Muhamad Fadli, S.Kom., M.Kom.
* **Mitra Instansi**: UPTD Puskesmas Teluk Sasah, Kabupaten Bintan.

---

### 🎯 Respon Komprehensif atas Tiga Saran Pak Tekad

#### 1. Saran 1: Analisis Tren Bulanan (1-Bulanan) vs Dwibulanan (2-Bulanan)
* **Status**: **Terpenuhi Sempurna** pada Folder `Tahap_03_Tren_1Bulanan_dan_2Bulanan`.
* **Temuan**:
  - Data bulanan 2024 (12 bulan) mendeteksi lonjakan jangka pendek musiman, namun memiliki variabilitas tinggi (CV rata-rata **48.6%**) akibat siklus penerimaan logistik dinas.
  - Penyelarasan ke format dwibulanan (22 periode kontinu) menstabilkan deret waktu (CV turun menjadi **24.1%**), membuang *noise* faktur tanpa menghilangkan tren pola penyakit musiman.

#### 2. Saran 2: Algoritma Moving Average (MA), Indikator MACD, dan Benchmarking Machine Learning
* **Status**: **Terpenuhi Sempurna** pada Folder `Tahap_04_Pemodelan_MA_dan_Indikator_MACD`.
* **Temuan**:
  - Model **WMA (k=3)** terbukti sebagai model paling akurat untuk mayoritas obat konsumsi stabil dengan rata-rata MAPE **14.2%** – **17.3%** (kategori *Good Forecast*).
  - Indikator **MACD (Fast=2, Slow=4, Signal=2)** berhasil mendeteksi akselerasi momentum konsumsi (*Bullish Surge*) pada Amlodipin, Asam Mefenamat, dan Metformin sebelum terjadi kekosongan stok (*stockout*).
  - **Benchmarking Machine Learning (10-Fold Walk-Forward)**: Eksperimen komparasi pada panel 148 obat aktif menunjukkan HistGradientBoosting menghasilkan WAPE 53,6%, namun mengalami **bias negatif (-9,2%)** yang berisiko fatal memicu *understock*. Sebaliknya, WMA-3 memiliki bias positif alami (+4,3%) yang ketika dipadukan dengan *Safety Stock 95%* terbukti paling aman dan andal bagi instalasi farmasi primer.

#### 3. Saran 3: Korelasi Obat-Penyakit, Prediksi 4 Penyakit, dan Prediksi Stok Obat
* **Status**: **Terpenuhi Sempurna** pada Folder `Tahap_05` dan `Tahap_06`.
* **Temuan**:
  - **Korelasi Terbukti Signifikan**: Diabetes Melitus $\leftrightarrow$ Metformin ($r = +0.5281; \rho = +0.5869$, rasio resep rutin Prolanis 39,95 tablet/pasien), ISPA $\leftrightarrow$ Amoksisilin ($r = +0.3924$) dan Parasetamol ($r = +0.3381$). Lemahnya korelasi pada Amlodipin dan Ambroksol terbukti disebabkan oleh distorsi kekosongan stok historis (*stockout*), bukan ketidaksesuaian klinis.
  - **Proyeksi 4 Penyakit (2026-P5)**: ISPA diproyeksikan 428,5 pasien (WMA-3), Hipertensi 383,3 pasien (SMA-3), Dispepsia 165,7 pasien (SMA-3), dan Diabetes 87,5 pasien (WMA-3). J00, I10, dan K30 hadir konsisten di 45/45 bulan (100%), sedangkan E11 hadir di 43/45 bulan akibat penyensoran 10 besar (*top-censored*).
  - **Rekomendasi Pengadaan 2026-P5 & Koreksi Permintaan Tersensor**: 
    - Parasetamol 500 mg direkomendasikan sebesar **7.487 tablet** (ramalan terkoreksi 6.147 + buffer 1.340), mengoreksi proyeksi naif 3.222 tablet yang timbul akibat kekosongan total stok gudang di 2026-P4.
    - Metformin 500 mg dialokasikan **4.046 tablet** (ramalan 3.923 + buffer 1.173 - sisa stok 1.050).
    - Amlodipin 10 mg dialokasikan **8.871 tablet** (ramalan 5.092 + buffer 8.504 - sisa stok 4.725).
    - Amoksisilin dan Vitamin B-Kompleks direkomendasikan **0 tablet (Stok Aman)** karena stok fisik gudang masih mencukupi.
  - **Dampak Finansial**: Mengamankan ketersediaan obat esensial rawat jalan sekaligus mencegah potensi kerugian kadaluarsa berulang (berdasarkan data Berita Acara Pemusnahan 138 item fisik senilai **Rp 43.348.626,-** pada 2023–2025).

---

### 📂 Struktur Direktori Alur Data Mentah ke Final:
```text
07_Alur_Data_Mentah_ke_Final/
├── Tahap_01_Data_Mentah_Primer/               # Arsip LPLPO, data penyakit, & obat kadaluarsa asli
├── Tahap_02_Pembersihan_dan_Standardisasi/    # Transaksi granular bersih, ICD-10, & audit kadaluarsa
├── Tahap_03_Tren_1Bulanan_dan_2Bulanan/       # Matriks 12 bulan 2024 vs 22 periode dwibulanan
├── Tahap_04_Pemodelan_MA_dan_Indikator_MACD/  # Evaluasi galat MAPE MA & analisis momentum MACD
├── Tahap_05_Korelasi_Obat_dan_Penyakit/       # Matriks korelasi Pearson/Spearman & rasio dosis
├── Tahap_06_Prediksi_Penyakit_dan_Stok_Obat/  # Prediksi 4 penyakit & stok obat 2026-P5
├── Tahap_07_Output_Final_dan_Rekomendasi.../  # Master workbook 9 sheets & prioritas pengadaan
└── README.md                                  # Panduan utama navigasi tahapan data
```
