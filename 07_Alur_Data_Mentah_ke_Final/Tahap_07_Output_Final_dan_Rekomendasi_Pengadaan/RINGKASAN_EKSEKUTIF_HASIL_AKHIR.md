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

#### 2. Saran 2: Algoritma Moving Average (MA) dan Indikator MACD
* **Status**: **Terpenuhi Sempurna** pada Folder `Tahap_04_Pemodelan_MA_dan_Indikator_MACD`.
* **Temuan**:
  - Model **WMA (k=3)** terbukti sebagai model paling akurat untuk mayoritas obat fast-moving dengan rata-rata MAPE **14.2%** (kategori *Good Forecast*).
  - Indikator **MACD (Fast=2, Slow=4, Signal=2)** berhasil mendeteksi lonjakan momentum konsumsi (*Bullish Surge*) pada Parasetamol, Amlodipin, dan Metformin sebelum terjadi kekosongan stok (*stockout*).

#### 3. Saran 3: Korelasi Obat-Penyakit, Prediksi 4 Penyakit, dan Prediksi Stok Obat
* **Status**: **Terpenuhi Sempurna** pada Folder `Tahap_05` dan `Tahap_06`.
* **Temuan**:
  - **Korelasi Terbukti Signifikan**: ISPA $\leftrightarrow$ Parasetamol ($r = +0.7824$), Hipertensi $\leftrightarrow$ Amlodipin ($r = +0.8412$), Diabetes $\leftrightarrow$ Metformin ($r = +0.8190$), Dispepsia $\leftrightarrow$ Antasida ($r = +0.7156$).
  - **Proyeksi 4 Penyakit (2026-P5)**: ISPA diproyeksikan 752 kasus, Hipertensi 438 kasus, Dispepsia 286 kasus, Diabetes 204 kasus.
  - **Rekomendasi Pengadaan 2026-P5**: Alokasi aman Parasetamol 8.490 tablet, Amlodipin 6.800 tablet, Metformin 4.300 tablet, Antasida 2.930 tablet.
  - **Dampak Finansial**: Berpotensi menghemat anggaran pemborosan obat kadaluarsa hingga **Rp 34 Juta / tahun** dari baseline riil kerugian historis sebesar Rp 43,3 Juta.

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
