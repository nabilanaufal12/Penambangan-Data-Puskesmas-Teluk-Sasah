# 📈 Folder: 05_Visualisasi_Grafik

Folder ini memuat 14 visualisasi beresolusi tinggi (**300 DPI**, format PNG) yang dihasilkan secara otomatis oleh skrip visualisasi data dan peramalan. Seluruh gambar siap disisipkan langsung ke naskah Laporan Akhir (BAB II, IV, dan V) maupun salindia presentasi (*slide deck*) sidang.

---

### 📋 Daftar Grafik & Relevansi Bab Laporan

| No | Nama Berkas | Judul & Deskripsi Visualisasi | Penempatan Bab |
| :---: | :--- | :--- | :---: |
| **01** | [`01_top_obat_pemakaian.png`](01_top_obat_pemakaian.png) | **Top 15 Obat Berdasarkan Volume Pemakaian Riil (2023–2026)**<br>Menampilkan dominasi Fero Sulfat (program gizi) dan Parasetamol 500 mg (kuratif poli). | BAB IV |
| **02** | [`02_tren_ispa_dan_obat.png`](02_tren_ispa_dan_obat.png) | **Tren Kasus Penyakit ISPA (Common Cold - J00) vs Pemakaian Obat Terkait**<br>Kurva deret waktu kasus ISPA vs Parasetamol 500 mg, Amoksisilin 500 mg, dan Ambroksol. | BAB IV / V |
| **03** | [`03_tren_hipertensi_diabetes.png`](03_tren_hipertensi_diabetes.png) | **Tren Penyakit Kronis: Hipertensi (I10) & Diabetes (E11) vs Terapi Obat**<br>Pola konsumsi stabil Amlodipin 10 mg dan Metformin 500 mg. | BAB IV / V |
| **04** | [`04_tren_pencernaan_dispepsia.png`](04_tren_pencernaan_dispepsia.png) | **Tren Penyakit Pencernaan: Dispepsia (K30) vs Antasida Doen**<br>Pola fluktuasi kasus lambung terhadap konsumsi antasida di puskesmas. | BAB IV / V |
| **05** | [`05_heatmap_korelasi_penyakit_obat.png`](05_heatmap_korelasi_penyakit_obat.png) | **Matriks Heatmap Korelasi Pearson (Penyakit vs Pola Pemakaian Obat)**<br>Membuktikan validitas klinis hubungan linear kausal antar-variabel. | BAB IV |
| **06** | [`06_analisis_kerugian_kadaluarsa.png`](06_analisis_kerugian_kadaluarsa.png) | **Analisis Dampak Finansial Obat Kadaluarsa Per Tahun (Total Rp 43,35 Juta)**<br>Bukti empiris overstock untuk memperkuat latar belakang masalah di BAB I & BAB V. | BAB I / V |
| **07** | [`07_sebaran_fluktuasi_cv.png`](07_sebaran_fluktuasi_cv.png) | **Sebaran Fluktuasi Pola Pemakaian Obat (Koefisien Variasi / CV)**<br>Pengelompokan obat stabil ($CV < 0.5$), moderat, dan sangat fluktuatif ($CV > 1.0$). | BAB IV |
| **08** | [`08_analisis_stockout_kosong.png`](08_analisis_stockout_kosong.png) | **Analisis Frekuensi dan Durasi Kejadian Kekosongan Stok (Stockout)**<br>Membuktikan understock dan ketidakakuratan perencanaan pengadaan manual sebelumnya. | BAB I / V |
| **09** | [`09_forecast_parasetamol_actual_vs_pred.png`](09_forecast_parasetamol_actual_vs_pred.png) | **Kurva Aktual vs Prediksi: Parasetamol Tablet 500 mg**<br>Perbandingan model SMA-2, SMA-3, dan WMA-3 terhadap data aktual. | BAB V |
| **10** | [`10_forecast_metformin_actual_vs_pred.png`](10_forecast_metformin_actual_vs_pred.png) | **Kurva Aktual vs Prediksi: Metformin Tablet 500 mg**<br>Model terbaik WMA-3 dengan MAPE rendah (**15.25%**). | BAB V |
| **11** | [`11_forecast_amlodipin_actual_vs_pred.png`](11_forecast_amlodipin_actual_vs_pred.png) | **Kurva Aktual vs Prediksi: Amlodipin Tablet 10 mg**<br>Perbandingan performa prediksi model terhadap pola pemakaian hipertensi. | BAB V |
| **12** | [`12_forecast_amoksisilin_actual_vs_pred.png`](12_forecast_amoksisilin_actual_vs_pred.png) | **Kurva Aktual vs Prediksi: Amoksisilin Kaplet 500 mg**<br>Model terbaik WMA-3 dengan akurasi tinggi (MAPE **17.35%**). | BAB V |
| **13** | [`13_perbandingan_mape_model.png`](13_perbandingan_mape_model.png) | **Diagram Batang Perbandingan Akurasi Galat (MAPE) Antar-Model**<br>Ringkasan komparasi performa SMA-2, SMA-3, dan WMA-3 pada 10 obat utama. | BAB V |
| **14** | [`14_rekomendasi_pengadaan_safety_stock.png`](14_rekomendasi_pengadaan_safety_stock.png) | **Rekomendasi Alokasi Pengadaan Obat Periode 2026-P5 + Safety Stock (95%)**<br>Formulasi praktis buffer stok untuk mencegah risiko kekosongan maupun kadaluarsa. | BAB V / VI |
| **15** | [`15_tren_bulanan_2024_vs_dwibulanan.png`](15_tren_bulanan_2024_vs_dwibulanan.png) | **Komparasi Tren Bulanan 2024 vs Dwibulanan (Saran 1)**<br>Dinamika mikro fluktuasi konsumsi bulanan vs dwibulanan 2024. | BAB IV / V |
| **16** | [`16_prediksi_penyakit_top4.png`](16_prediksi_penyakit_top4.png) | **Peramalan Kasus 4 Penyakit Terbanyak (Saran 3b)**<br>Proyeksi tren kasus ISPA, Hipertensi, Dispepsia, dan Diabetes 2026-P5. | BAB IV / V |
| **17** | [`17_analisis_macd_obat.png`](17_analisis_macd_obat.png) | **Analisis Indikator MACD Pemakaian Obat (Saran 2)**<br>Fast/Slow EMA, Signal Line, Histogram, dan status momentum stok. | BAB V |
| **18** | [`18_korelasi_morbiditas_dosis_obat.png`](18_korelasi_morbiditas_dosis_obat.png) | **Korelasi Morbiditas Kasus Penyakit vs Dosis Obat (Saran 3a)**<br>Matriks korelasi klinis dan rasio konsumsi unit per pasien. | BAB IV |
| **19** | [`19_tren_29_titik_data_mentah_asli.png`](19_tren_29_titik_data_mentah_asli.png) | **Kurva Tren 29 Laporan Asli Sesuai Data Mentah Apa Adanya**<br>Ploting kronologis 14 laporan 1-bulanan & 15 laporan 2-bulanan asli tanpa penggabungan paksa. | BAB IV / V |
| **20** | [`20_hasil_tahap2_pembersihan_dan_standardisasi.png`](20_hasil_tahap2_pembersihan_dan_standardisasi.png) | **Hasil Komprehensif Tahap 2: Pembersihan & Standardisasi Data**<br>Audit volume reduksi data, normalisasi satuan dosis, pemisahan anomali program gizi, dan pemetaan ICD-10. | BAB III |
| **21** | [`21_tren_dan_proyeksi_12_bulan_fero_sulfat.png`](21_tren_dan_proyeksi_12_bulan_fero_sulfat.png) | **Tren Pemakaian & Proyeksi Kebutuhan 12 Bulan: Fero Sulfat 300 mg**<br>Analisis tren 22 periode historis dan peramalan kebutuhan obat 12 bulan ke depan (Cubic Spline Curve 300 DPI) lengkap dengan Safety Buffer 95%. | BAB V / VI |
| **EDA-M** | [`EDA_MASTER_PREDIKSI_STOK_DAN_TREN_PENYAKIT.png`](EDA_MASTER_PREDIKSI_STOK_DAN_TREN_PENYAKIT.png) | **Dashboard Master EDA Komprehensif (6-in-1, 300 DPI)**<br>Panel terpadu sintesis tren musiman penyakit, pola konsumsi obat, korelasi klinis, rasio dosis, proyeksi safety stock, dan mitigasi risiko stok. | BAB IV / V |
| **EDA-01** | [`EDA_01_tren_penyakit_musiman.png`](EDA_01_tren_penyakit_musiman.png) | **Analisis Tren Penyakit Musiman & Heatmap Morbiditas Bulanan**<br>Kurva seasonality bulanan (puncak musim penghujan ISPA) dan heatmap intensitas 8 penyakit utama. | BAB IV |
| **EDA-02** | [`EDA_02_pola_pemakaian_obat_dan_volatilitas.png`](EDA_02_pola_pemakaian_obat_dan_volatilitas.png) | **Pola Pemakaian Obat & Karakteristik Variabilitas (CV)**<br>Top 10 obat kuratif, tren lintas 22 periode dwibulanan, dan distribusi koefisien variasi stabilitas obat. | BAB IV |
| **EDA-03** | [`EDA_03_korelasi_klinis_penyakit_obat.png`](EDA_03_korelasi_klinis_penyakit_obat.png) | **Korelasi Klinis Penyakit vs Konsumsi Obat & Rasio Dosis per Kasus**<br>Koefisien Pearson (r), Spearman (ρ), scatter plot OLS regresi linier, dan rasio dosis unit obat per pasien. | BAB IV / V |
| **EDA-04** | [`EDA_04_prediksi_kebutuhan_stok_safety_stock.png`](EDA_04_prediksi_kebutuhan_stok_safety_stock.png) | **Prediksi Kebutuhan Stok Obat, Safety Stock 95% & Mitigasi Risiko Stok**<br>Alokasi stok obat periode 2026-P5 + buffer safety stock 95% serta perbandingan understock vs overstock. | BAB V / VI |
