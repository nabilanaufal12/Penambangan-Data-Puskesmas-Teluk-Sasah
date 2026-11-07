# 📑 Draf Naskah Akademik (Bab IV) & Ringkasan Eksekutif PRABOTIK
**UPTD Puskesmas Teluk Sasah — Kabupaten Bintan**

---

## BAGIAN 1: DRAF NASKAH AKADEMIK (BAB IV — HASIL DAN PEMBAHASAN)

### 4.1 Evaluasi Mitigasi *Censored Demand* pada Rantai Pasok Obat Esensial

Permasalahan fundamental dalam perencanaan kebutuhan obat di Fasilitas Kesehatan Tingkat Pertama (FKTP) yang mengandalkan data Laporan Pemakaian dan Lembar Permintaan Obat (LPLPO) adalah terjadinya fenomena *censored demand* (permintaan tersensor). Fenomena ini muncul ketika stok fisik obat di gudang habis seluruhnya (*total stockout*), sehingga catatan pengeluaran pada periode berikutnya bernilai nol. Secara statistik, angka nol tersebut bukanlah representasi dari berhentinya morbiditas atau hilangnya kebutuhan terapi di masyarakat, melainkan batas kapasitas suplai (*supply-side truncation*).

Studi kasus empiris pada UPTD Puskesmas Teluk Sasah teridentifikasi secara nyata pada item vital **Parasetamol 500 mg** selama periode pelaporan 2026-P4 (Juli–Agustus 2026). Akibat lonjakan konsumsi pada periode 2026-P3 yang menghabiskan seluruh persediaan sebanyak 6.444 tablet, catatan logistik pada periode 2026-P4 merekam stok awal sebesar 0, penerimaan 0, pemakaian 0, dan sisa stok 0.

Apabila sistem peramalan menerapkan metode *Simple Moving Average* dua periode ($\text{SMA-2}$) atau *Weighted Moving Average* tiga periode ($\text{WMA-3}$) secara naif tanpa koreksi (*unadjusted*), maka proyeksi kebutuhan untuk periode 2026-P5 (September–Oktober 2026) mengalami distorsi penurunan yang ekstrem:

$$\hat{y}_{\text{2026-P5}}^{\text{SMA-2 (unadj)}} = \frac{y_{\text{P3}} + y_{\text{P4}}}{2} = \frac{6.444 + 0}{2} = 3.222 \text{ tablet}$$

$$\hat{y}_{\text{2026-P5}}^{\text{WMA-3 (unadj)}} = \frac{1 \cdot y_{\text{P2}} + 2 \cdot y_{\text{P3}} + 3 \cdot y_{\text{P4}}}{1 + 2 + 3} = \frac{1(5.256) + 2(6.444) + 3(0)}{6} = 3.024 \text{ tablet}$$

Penurunan proyeksi hingga 50% pada saat memasuki musim pancaroba dan angin utara—di mana kasus Infeksi Saluran Pernapasan Akut (ISPA) mendominasi 34,8% beban penyakit SIMPUS—akan memicu siklus kekosongan obat berulang (*perpetual stockout cycle*). Untuk memutus bias tersebut, sistem PRABOTIK mengimplementasikan algoritma *Censored Demand Adjustment* melalui imputasi rata-rata bergerak lokal berbasis dua periode aktif terakhir sebelum *stockout* ($y_{\text{P2}} = 5.256$ dan $y_{\text{P3}} = 6.444$):

$$\hat{y}_{\text{2026-P4}}^{\text{imputed}} = \frac{y_{\text{P2}} + y_{\text{P3}}}{2} = \frac{5.256 + 6.444}{2} = 5.850 \text{ tablet}$$

Substitusi nilai permintaan terekonstruksi ($\hat{y}_{\text{2026-P4}}^{\text{imputed}}$) ke dalam matriks deret waktu menghasilkan pemulihan kuantitas proyeksi titik (*point forecast*) pada periode 2026-P5 sebesar $+2.925$ tablet baik pada model $\text{SMA-2}$ maupun $\text{WMA-3}$:

$$\hat{y}_{\text{2026-P5}}^{\text{SMA-2 (adj)}} = \frac{6.444 + 5.850}{2} = 6.147 \text{ tablet}$$

$$\hat{y}_{\text{2026-P5}}^{\text{WMA-3 (adj)}} = \frac{1(5.256) + 2(6.444) + 3(5.850)}{6} = 5.949 \text{ tablet}$$

Dengan mengintegrasikan *Safety Stock* pada tingkat kepercayaan 95% sebesar 1.340 tablet terhadap titik proyeksi terkoreksi, total rekomendasi alokasi pengadaan periode 2026-P5 terangkat menjadi **7.487 tablet**. Selain menyelamatkan ketersediaan fisik obat, koreksi ini juga memulihkan validitas indikator teknikal sistem, di mana nilai histogram MACD berbalik dari sinyal *crash* semu ($-485,42$) menjadi sinyal *rebound* positif ($+34,58$) yang selaras dengan peningkatan tren epidemiologi ISPA di lapangan.

---

### 4.2 Analisis Komparatif *Walk-Forward Validation* dan Rasionalitas Pemilihan Model

Untuk menjamin objektivitas pemilihan algoritma tanpa kebocoran data masa depan (*data leakage*), evaluasi dilakukan terhadap 149 item obat aktif menggunakan skema *10-Fold Expanding Window Walk-Forward Validation* sepanjang rentang uji 2025-P1 hingga 2026-P4.

| Model Prediksi | Kategori Model | WAPE | Bias Persentase | Peran dalam Sistem PRABOTIK |
| :--- | :---: | :---: | :---: | :--- |
| **HistGradientBoosting (GBM)** | Machine Learning | **0,536** | **-9,2%** | Tolok Ukur Pembanding Algoritmik |
| **Naive (Lag-1)** | Baseline Deret Waktu | 0,559 | +1,8% | Kontrol Baseline Minimum |
| **WMA (k=3)** | Time-Series Weighted | 0,595 | +4,3% | **Model Operasional Utama Terpilih** |
| **SMA (k=2)** | Time-Series Moving Avg | 0,597 | +3,0% | Model Evaluasi Sekunder |
| **Ridge Regression (L2)** | Linear Regularized | 0,647 | +5,3% | Pembanding Linier Multivariat |
| **GBM Quantile (alpha=0,95)** | Probabilistic ML | 1,209 | +101,3% | **Batas Atas Persediaan (*Safety Stock Ceiling*)** |

Berdasarkan hasil pengujian di atas, terdapat temuan ilmiah krusial terkait pertukaran antara akurasi absolut dan arah simpangan (*accuracy-bias trade-off*) dalam informatika logistik kesehatan:

1. **Risiko Asimetris Bias Negatif pada Machine Learning Murni (GBM):** Secara metrik galat agregat, algoritma *HistGradientBoosting* mencatatkan nilai *Weighted Absolute Percentage Error* (WAPE) terendah yaitu 0,536. Namun, model ini menghasilkan *forecasting bias* sebesar $-9,2\%$. Dalam konteks rantai pasok klinis Puskesmas, fungsi kerugian (*loss function*) bersifat sangat asimetris: dampak negatif dari *under-forecasting* (kekurangan stok obat esensial saat pasien membutuhkan tindakan medis) jauh lebih berbahaya dibandingkan kelebihan cadangan moderat. Bias negatif $-9,2\%$ pada GBM cenderung menekan estimasi ke bawah ketika berhadapan dengan fluktuasi stok yang sporadis, sehingga berisiko tinggi memicu *stockout* sistematis.
2. **Keunggulan Operasional WMA-3 sebagai Estimator Utama:** Model *Weighted Moving Average* ($\text{WMA}$, $k=3$) dipilih sebagai mesin prediksi operasional utama di PRABOTIK. Meskipun memiliki WAPE 0,595, $\text{WMA-3}$ mendominasi akurasi terbaik pada 58,7% item obat dengan rata-rata MAPE sebesar **14,2%** yang masuk dalam kualifikasi *Good Forecast* ($10\% \le \text{MAPE} \le 20\%$). Lebih penting lagi, $\text{WMA-3}$ memiliki karakteristik bias positif moderat ($+4,3\%$). Bias positif ini berfungsi sebagai penyangga pengaman struktural (*natural safety buffer*) yang melindungi Puskesmas dari lonjakan kasus mendadak tanpa memicu pembengkakan anggaran (*over-budgeting*) yang menyebabkan obat kadaluarsa.
3. **Pemanfaatan GBM Quantile ($\alpha = 0,95$) sebagai Batas Atas Persediaan:** Alih-alih membuang arsitektur *Gradient Boosting*, PRABOTIK menempatkan *GBM Quantile Regression* pada kuantil ke-95 (WAPE 1,209; Bias $+101,3\%$) bukan sebagai peramal nilai tengah, melainkan sebagai *Safety Stock Ceiling* (batas atap toleransi pengadaan maksimum) guna mencegah pengadaan berlebih yang melampaui kapasitas serap faskes.

---

### 4.3 Rasionalitas Adaptasi Parameter Indikator MACD Dwibulanan (2, 4, 2)

Indikator *Moving Average Convergence Divergence* (MACD) yang dikembangkan oleh Gerald Appel dalam analisis deret waktu finansial menggunakan konfigurasi standar $(12, 26, 9)$ berbasis siklus harian atau mingguan. Penerapan parameter klasik tersebut secara langsung pada logistik farmasi Puskesmas tidak valid secara matematis karena dua batasan struktural:

* **Resolusi Frekuensi Data:** Pelaporan LPLPO dan perencanaan pengadaan di UPTD Puskesmas Teluk Sasah beroperasi pada siklus dwibulanan (1 periode $= 2$ bulan kalender), dengan panjang histori observasi $N = 22$ titik data ($\approx 44$ bulan).
* **Syarat Konvergensi Sampel:** Parameter *Slow EMA* sebesar 26 ditambah *Signal EMA* sebesar 9 membutuhkan minimal 35 hingga 52 titik periode dwibulanan (setara 6 hingga 8,5 tahun data bersih) hanya untuk menginisialisasi satu titik sinyal yang stabil.

Oleh sebab itu, dilakukan penyelarasan skala domain waktu (*time-domain scaling*) menjadi konfigurasi **MACD (2, 4, 2)** dengan bobot peluruhan eksponensial $\alpha = \frac{2}{\text{span} + 1}$:

$$\text{MACD Line}_t = \text{EMA}_t(\text{span}=2) - \text{EMA}_t(\text{span}=4)$$

$$\text{Signal Line}_t = \text{EMA}_t(\text{MACD Line}, \text{span}=2)$$

$$\text{Histogram}_t = \text{MACD Line}_t - \text{Signal Line}_t$$

Secara epidemiologis dan matematis, parameterisasi $(2, 4, 2)$ memiliki rasionalitas kuat:
* **Fast EMA ($\text{span}=2, \alpha = 0,667$):** Merepresentasikan jendela respons cepat selama 4 bulan kalender. Bobot $\alpha$ sebesar 66,7% pada periode terbaru memungkinkan sistem langsung menangkap akselerasi lonjakan penyakit musiman (seperti transisi musim pancaroba ke musim angin utara di wilayah pesisir Bintan) begitu terjadi kenaikan morbiditas pada SIMPUS.
* **Slow EMA ($\text{span}=4, \alpha = 0,400$):** Merepresentasikan tren ekuilibrium jangka menengah selama 8 bulan kalender (melampaui satu siklus penuh pergantian musim setengah tahunan).
* **Signal Line ($\text{span}=2$):** Mengidentifikasi titik potong (*crossover*) momentum secara responsif tanpa jeda waktu (*lag*) yang panjang, memberikan peringatan dini (*Bullish/Bearish/Sideways*) kepada pengelola gudang farmasi tepat sebelum stok fisik melintasi ambang batas kritis.

---

## BAGIAN 2: DRAF RINGKASAN EKSEKUTIF (EXECUTIVE BRIEFING)

**RINGKASAN EKSEKUTIF KEBIJAKAN & INOVASI SISTEM INFORMASI KESEHATAN**  
**Kepada Yth.:** Kepala Dinas Kesehatan Kabupaten Bintan  
**Dari:** Tim Pengembang Sistem & Manajemen UPTD Puskesmas Teluk Sasah  
**Perihal:** Implementasi Sistem PRABOTIK untuk Penyelamatan Anggaran Obat Kadaluarsa (ED) dan Jaminan Ketersediaan Obat Esensial Berbasis Data Mining  

### 1. Urgensi & Permasalahan Riil di Tingkat Faskes
Berdasarkan evaluasi tata kelola logistik farmasi selama 3 tahun terakhir di UPTD Puskesmas Teluk Sasah, ditemukan dua risiko utama yang berdampak langsung pada akuntabilitas anggaran daerah dan mutu layanan klinis:
* **Kerugian Finansial Obat Kadaluarsa (ED):** Tercatat akumulasi kerugian fisik obat ED mencapai **Rp 43,35 Juta** (bersumber dari dana APBD & DAK) akibat perencanaan konvensional yang *over-budgeting* serta belum adanya sistem peringatan dini rotasi *First Expired First Out* (FEFO).
* **Kekosongan Obat Vital (*Stockout*) Saat Cuaca Ekstrem:** Terjadi kekosongan obat esensial (seperti Parasetamol dan antibiotik) saat lonjakan kasus ISPA (34,8% dari total penyakit) pada musim utara. Perencanaan manual selama ini terjebak pada kelemahan konsumsi historis murni: ketika stok habis (pemakaian tercatat nol di LPLPO), rumus rata-rata otomatis memangkas proyeksi periode berikutnya hingga 50%, sehingga memicu kekosongan berulang.

### 2. Solusi Inovasi: Arsitektur Dual-Portal PRABOTIK
PRABOTIK (*Sistem Prediksi Kebutuhan Obat & Analisis Morbiditas Penyakit Berbasis Data Mining*) dirancang khusus untuk menyelaraskan alur kerja teknis dan manajerial melalui pemisahan dua portal independen:
* **Portal Operasional Farmasi:** Digunakan oleh Apoteker pengelola gudang untuk memantau stok fisik, peringatan ambang batas kritis (*buffer warning*), pengawasan ketat batch FEFO, serta penyusunan Rencana Pengadaan dilengkapi algoritma deteksi lonjakan permintaan (MACD) dan koreksi otomatis periode *stockout* (*Censored Demand Adjustment*).
* **Portal Eksekutif Kepala Puskesmas:** Digunakan oleh Kepala Puskesmas selaku Kuasa Pengguna Anggaran (KPA) untuk memantau indikator Standar Pelayanan Minimal (SPM) Kemenkes (capaian saat ini **91,3%** across 40 obat esensial), mengaudit nilai finansial obat ED, memantau kesesuaian peresepan poli (Umum, KIA/KB, Gigi) terhadap Formularium Nasional, serta menerbitkan **Disposisi Pengesahan Rencana Pengadaan (P5)** ber-NIP dan bernomor register resmi ke Dinas Kesehatan.

### 3. Dampak Terukur terhadap Efisiensi Anggaran & Layanan Pasien
* **Pencegahan Kerugian Anggaran (Cost Avoidance):** Melalui pembatasan atap stok pengaman (*Safety Stock Ceiling*) dan pelacakan FEFO otomatis, potensi pemborosan anggaran akibat obat menumpuk hingga kadaluarsa seperti temuan historis Rp 43,35 Juta dapat dicegah sejak tahap pengajuan usulan.
* **Akurasi Perencanaan Teruji Ilmiah:** Berdasarkan validasi pada 149 item obat aktif, algoritma terpilih menghasilkan tingkat akurasi tinggi (rata-rata galat MAPE 14,2%). Pada simulasi riil kasus Parasetamol 500 mg pasca-kekosongan stok (2026-P4), PRABOTIK berhasil mengoreksi bias penurunan sebesar $+2.925$ tablet dan menetapkan rekomendasi pengadaan yang presisi sebanyak **7.487 tablet** untuk periode 2026-P5, mengamankan layanan pasien pada puncak musim ISPA.
* **Nol Biaya Infrastruktur Server Baru (Portabel & Mandiri):** Dibangun di atas kerangka kerja modern Laravel 11 dengan basis data mandiri SQLite, PRABOTIK sangat ringan, tidak membutuhkan pengadaan server berbiaya tinggi maupun lisensi perangkat lunak berbayar, serta siap direplikasi ke seluruh Puskesmas di wilayah kerja Kabupaten Bintan.
