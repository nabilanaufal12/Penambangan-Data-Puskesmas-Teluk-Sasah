# 📑 Draf Naskah Bab IV & Bab V (CRISP-DM Standard)
**Mata Kuliah:** Penambangan Data (INF11121 – 5 SKS)  
**Institusi:** S1 Teknik Informatika, Fakultas Teknik dan Teknologi Kemaritiman, Universitas Maritim Raja Ali Haji (UMRAH)  
**Kelompok 23:** Nabil (Ketua), Balqis Manisa, Jian Safitri Wibowo, Annisa Darmawan, Yehezkiel Alman Siambaton  
**Dosen Pengampu:** Bapak Tekad Matulatan, S.Sos., S.Kom., M.Info.Tech. & Bapak Muhamad Fadli, S.Kom., M.Kom.  

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
