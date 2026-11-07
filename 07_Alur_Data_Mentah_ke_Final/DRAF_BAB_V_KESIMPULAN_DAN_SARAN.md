# 📑 Draf Naskah Akademik: Bab V (Kesimpulan dan Saran)
**Proyek:** PRABOTIK — UPTD Puskesmas Teluk Sasah, Kabupaten Bintan

---

## 5.1 Kesimpulan

Berdasarkan hasil perancangan arsitektur perangkat lunak, pemodelan matematis deret waktu, serta pengujian empiris pada data logistik dan klinis UPTD Puskesmas Teluk Sasah, diperoleh lima kesimpulan utama sebagai berikut:

**1. Resolusi Bias Permintaan Tersensor (*Censored Demand*):**  
Penerapan algoritma *Censored Demand Adjustment* berbasis imputasi rata-rata bergerak lokal terbukti efektif memutus siklus kekosongan obat berulang (*perpetual stockout*) akibat pemotongan data sisi suplai. Pada studi kasus kritis Parasetamol 500 mg periode 2026-P4 yang mengalami kekosongan stok fisik, rekonstruksi permintaan sebesar 5.850 tablet berhasil mengangkat titik proyeksi periode 2026-P5 pada model $\text{SMA-2}$ dari 3.222 menjadi 6.147 tablet dan pada model $\text{WMA-3}$ dari 3.024 menjadi 5.949 tablet ($+2.925$ tablet). Penambahan *Safety Stock* 95% sebesar 1.340 tablet menghasilkan rekomendasi alokasi pengadaan P5 sebesar **7.487 tablet** yang menjamin keamanan terapi pada puncak musim pancaroba.

**2. Keunggulan Operasional $\text{WMA-3}$ dan Pengendalian Risiko Asimetris:**  
Pengujian komparatif *10-Fold Expanding Window Walk-Forward Validation* terhadap 149 item obat aktif (2025-P1 hingga 2026-P4) membuktikan bahwa *Weighted Moving Average* ($\text{WMA-3}$) merupakan estimator operasional paling optimal untuk faskes primer. Meskipun algoritma *HistGradientBoosting* (GBM) mencatatkan WAPE agregat terendah sebesar 0,536, karakteristik bias negatifnya ($-9,2\%$) berisiko tinggi memicu *under-forecasting* pada obat vital. Sebaliknya, $\text{WMA-3}$ unggul pada 58,7% item obat dengan rata-rata MAPE **14,2%** (*Good Forecast*), WAPE 0,595, serta menghasilkan bias positif moderat ($+4,3\%$) yang berfungsi sebagai penyangga pengaman alami (*natural safety buffer*), sementara model *GBM Quantile* ($\alpha = 0,95$, bias $+101,3\%$) efektif difungsikan sebagai batas atap persediaan pengaman (*Safety Stock Ceiling*).

**3. Sensitivitas Deteksi Dini melalui Parameterisasi MACD Dwibulanan (2, 4, 2):**  
Adaptasi skala domain waktu indikator teknikal *Moving Average Convergence Divergence* dari parameter finansial klasik $(12, 26, 9)$ menjadi konfigurasi dwibulanan **$(2, 4, 2)$** berhasil mengatasi keterbatasan dimensi sampel pendek ($N = 22$ periode). Bobot peluruhan eksponensial $\text{Fast EMA}$ ($\alpha = 0,667$) terhadap $\text{Slow EMA}$ ($\alpha = 0,400$) mampu mendeteksi percepatan lonjakan permintaan obat musiman secara responsif, di mana koreksi *Censored Demand* terbukti memulihkan indikator histogram MACD Parasetamol 500 mg dari sinyal *crash* semu ($-485,42$) menjadi sinyal *rebound* positif ($+34,58$).

**4. Validasi Silang Matriks Morbiditas SIMPUS dan Rasio Dosis per Kasus:**  
Integrasi data epidemiologi 10 penyakit terbesar SIMPUS (ICD-10) dengan pengeluaran LPLPO melalui formulasi Rasio Dosis per Kasus ($R_{i,j}$) berhasil menjembatani metode konsumsi dan metode morbiditas Kemenkes. Pengujian bivariat membuktikan korelasi positif yang kuat antara fluktuasi kasus ISPA (34,8% beban penyakit) terhadap konsumsi Parasetamol 500 mg ($r = 0,5851$, rasio $13,43$ tablet/kasus, proyeksi poli ISPA 4.541 tablet) dan Amoksisilin 500 mg ($\rho = 0,6702$, rasio $5,06$ kaplet/kasus), serta memetakan kontras kebutuhan volume terapi rumatan penyakit kronis pada Diabetes Melitus Tipe 2 terhadap Metformin 500 mg ($\rho = 0,7753$, rasio $40,95$ tablet/kasus) dan Hipertensi Primer (24,0% beban penyakit) terhadap Amlodipin 10 mg (rasio $19,08$ tablet/kasus, proyeksi 6.534 tablet).

**5. Efektivitas Tata Kelola pada Arsitektur Perangkat Lunak *Dual-Portal*:**  
Implementasi sistem berbasis Laravel 11 dan basis data mandiri SQLite dengan pemisahan ketat antara Portal Operasional Farmasi (`/farmasi`) dan Portal Eksekutif Kepala Puskesmas (`/kepala`) mampu menghilangkan tumpang-tindih beban kognitif pengguna. Integrasi pengawasan rotasi *First Expired First Out* (FEFO), pemantauan KPI SPM Kemenkes (91,3% pada 40 obat esensial), serta fitur disposisi pengesahan Rencana Pengadaan P5 ber-NIP secara langsung memitigasi risiko kerugian finansial obat kadaluarsa yang pada audit historis 3 tahun sebelumnya mencapai **Rp 43,35 Juta**.

---

## 5.2 Saran

Berdasarkan temuan penelitian dan batasan sistem saat ini, dirumuskan saran pengembangan akademik serta rekomendasi kebijakan manajerial sebagai berikut:

**1. Saran Pengembangan Akademik dan Algoritmik (Teknik Informatika):**
* Mengembangkan model **SARIMAX (*Seasonal ARIMA with Exogenous Regressors*)** untuk mengakomodasi variabel eksogen ($X$) berupa jumlah kasus SIMPUS dan parameter meteorologi (curah hujan/angin utara) secara simultan.
* Mengimplementasikan arsitektur *hybrid ensemble* yang menggabungkan prediksi titik $\text{WMA-3}$ dengan interval kepercayaan dinamis dari *LightGBM/Random Forest* melalui eksekusi skrip latar belakang pada berkas SQLite.
* Memperluas cakupan pemodelan ke tingkat *multi-echelon supply chain* dengan memasukkan variabel waktu tunggu pengiriman (*lead time*) dan ketersediaan penyangga dari Instalasi Farmasi Kabupaten (IFK).

**2. Saran Manajerial dan Kebijakan (Dinas Kesehatan Kabupaten Bintan):**
* Menetapkan regulasi teknis penyusunan LPLPO di seluruh Puskesmas wilayah Kabupaten Bintan agar wajib menyertakan koreksi *Censored Demand* pada item obat yang mengalami kekosongan stok fisik.
* Mengintegrasikan indikator batas atap kuantil (*Safety Stock Ceiling*) dan audit kepatuhan FEFO dari PRABOTIK sebagai syarat verifikasi usulan pengadaan obat guna mencegah terulangnya kerugian anggaran APBD dan DAK akibat obat kadaluarsa.
* Mereplikasi sistem *Dual-Portal* PRABOTIK ke seluruh FKTP di Kabupaten Bintan mengingat arsitektur berbasis SQLite bersifat portabel, ringan, dan tidak membebani anggaran daerah dengan biaya pengadaan server baru.
