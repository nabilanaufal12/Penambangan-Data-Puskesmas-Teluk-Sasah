# 🎓 Panduan Simulasi Tanya-Jawab Ujian Sidang Skripsi (Defense Q&A)
**Proyek:** PRABOTIK — UPTD Puskesmas Teluk Sasah, Kabupaten Bintan

Dokumen ini memuat rangkuman pertanyaan-pertanyaan paling kritis dan menjebak yang lazim diajukan oleh dosen penguji bidang Informatika, Data Science, dan Rekayasa Perangkat Lunak, beserta strategi jawaban taktis dan ilmiah berbasis bukti (*evidence-based answer*).

---

### Pertanyaan 1: Pemilihan Algoritma Time Series vs. Deep Learning

**Pertanyaan Penguji:**  
*"Saat ini algoritma berbasis Deep Learning seperti LSTM (Long Short-Term Memory) atau Time-Series Transformer sangat populer. Mengapa Anda justru memilih Weighted Moving Average (WMA-3) yang secara matematis tergolong klasik sebagai model operasional utama?"*

**Jawaban Taktis & Ilmiah:**  
Pemilihan $\text{WMA-3}$ didasarkan pada prinsip *Parsimony* (*Occam's Razor*), karakteristik dimensi data riil di Puskesmas, serta bukti empiris dari pengujian *10-Fold Walk-Forward Validation*:

1. **Keterbatasan Dimensi Sampel (*Small-Sample Regime*):**  
   Pelaporan LPLPO Puskesmas Teluk Sasah bersifat dwibulanan dengan panjang histori $N = 22$ titik observasi per obat (setara hampir 4 tahun). Arsitektur *Deep Learning* seperti LSTM atau Transformer memiliki ribuan parameter bobot matriks yang membutuhkan ribuan titik deret waktu untuk mencapai konvergensi. Memaksakan LSTM pada $N = 22$ akan memicu *overfitting* parah dan varians galat yang tinggi.
2. **Bukti Empiris Asimetri Risiko Bias:**  
   Dalam eksperimen komparatif kami terhadap 149 obat aktif, kami telah menguji algoritma *Machine Learning* modern yakni *HistGradientBoosting (GBM)*. Meskipun GBM menghasilkan WAPE terendah ($0,536$), model tersebut memiliki **bias negatif sebesar $-9,2\%$**. Dalam logistik klinis, bias negatif sangat berbahaya karena memicu *under-forecasting* dan kekosongan obat (*stockout*). Sebaliknya, $\text{WMA-3}$ unggul pada 58,7% item obat dengan rata-rata MAPE **14,2%** (*Good Forecast*) dan menghasilkan **bias positif moderat $+4,3\%$** yang bertindak sebagai *natural safety buffer*.
3. **Keterjelasan Algoritma (*White-Box Explainability*):**  
   Rencana Pengadaan P5 merupakan dokumen anggaran negara yang harus diaudit oleh Kepala Puskesmas (KPA) dan Dinas Kesehatan. $\text{WMA-3}$ memberikan transparansi perhitungan 100% yang dapat diverifikasi secara birokratis, sedangkan *Deep Learning* bersifat *black-box* yang sulit dipertanggungjawabkan dalam berita acara pengadaan daerah.

---

### Pertanyaan 2: Validitas Ilmiah Imputasi *Censored Demand*

**Pertanyaan Penguji:**  
*"Pada kasus Parasetamol 500 mg periode 2026-P4, pemakaian tercatat 0 di LPLPO tetapi sistem Anda mengubahnya menjadi 5.850 tablet. Apakah tindakan ini tidak dianggap memanipulasi data mentah rekam medis/logistik?"*

**Jawaban Taktis & Ilmiah:**  
Secara metodologis dan rekayasa perangkat lunak, terdapat pemisahan tegas antara **manipulasi data historis** dengan **koreksi statistik atas data tersensor (*Censored Demand Imputation*)**:

1. **Integritas Data Mentah Tetap Utuh di Database:**  
   Pada skema basis data SQLite PRABOTIK, catatan tabel fisik LPLPO periode 2026-P4 sama sekali tidak diubah—stok awal, penerimaan, dan pemakaian riil tetap tersimpan bernilai **0** sesuai fakta audit fisik gudang.
2. **Resolusi *Supply-Side Truncation* pada Lapisan Analitik:**  
   Dalam ilmu *Supply Chain Data Science*, angka 0 saat terjadi *total stockout* (habisnya 6.444 tablet pada 2026-P3) disebut sebagai *right-censored demand*. Permintaan pasien di poli tidak berhenti, melainkan tidak terlayani karena stok kosong. Jika angka 0 tersebut dimasukkan mentah-mentah ke dalam rumus prediksi, terjadi cacat logika statistik karena menyamakan *ketiadaan pasokan* dengan *ketiadaan penyakit*, yang membuat proyeksi 2026-P5 anjlok 50% menjadi 3.222 tablet.
3. **Validasi Silang Klinis SIMPUS:**  
   Angka imputasi 5.850 tablet (rata-rata lokal dua periode aktif sebelum *stockout*) terbukti valid ketika diuji silang dengan matriks morbiditas SIMPUS: pada periode P5, proyeksi kasus ISPA saja mencapai 338 kasus yang membutuhkan minimal 4.541 tablet Parasetamol (rasio $13,43$ tablet/kasus) belum termasuk poli lain. Tanpa imputasi ini, Puskesmas dipastikan mengalami *stockout* berulang di puncak musim ISPA.

---

### Pertanyaan 3: Rasionalitas Arsitektur Basis Data SQLite

**Pertanyaan Penguji:**  
*"Mengapa Anda menggunakan SQLite untuk sistem informasi tingkat instansi pemerintah seperti Puskesmas? Bukankah MySQL atau PostgreSQL lebih standar untuk aplikasi berbasis web?"*

**Jawaban Taktis & Ilmiah:**  
Pemilihan SQLite pada Laravel 11 merupakan keputusan arsitektural yang disengaja (*deliberate architectural decision*) berdasarkan analisis beban kerja (*workload analysis*) dan kondisi infrastruktur faskes daerah:

1. **Kesesuaian Konkurensi Pengguna (*Dual-Portal Concurrency Profile*):**  
   Berbeda dengan sistem transaksi publik masif, alur kerja logistik PRABOTIK memiliki profil konkurensi tulis (*write-concurrency*) yang sangat terstruktur dan spesifik, yaitu berfokus pada dua aktor otorisasi utama: Penanggung Jawab Farmasi (`/farmasi`) dan Kepala Puskesmas (`/kepala`). Dengan mode *Write-Ahead Logging* (WAL) pada SQLite modern, operasi baca-tulis simultan untuk skala faskes primer dieksekusi dalam hitungan sub-milidetik tanpa hambatan *database locking*.
2. **Skala Dimensi Data dan Kecepatan Komputasi:**  
   Volume data matriks 149 obat aktif selama 22 periode beserta histori morbiditas SIMPUS berukuran sangat kompak (di bawah 50 MB). Membaca berkas SQLite secara langsung melalui sistem berkas lokal (*direct disk I/O*) justru lebih cepat dibandingkan melewati *network socket overhead* pada arsitektur *client-server* MySQL/PostgreSQL.
3. **Portabilitas Nol-Konfigurasi & Integrasi Data Mining:**  
   Di wilayah kepulauan seperti Kabupaten Bintan, ketergantungan pada pemeliharaan *dedicated database server* sering menjadi titik kegagalan (*single point of failure*). Dengan SQLite (`database.sqlite`), seluruh basis data bersifat portabel, mudah dicadangkan (*single-file backup*), nol biaya lisensi/server tambahan, dan memungkinkan skrip analitik Python membaca berkas basis data yang sama secara langsung tanpa konfigurasi *database driver* yang rumit.

---

### Pertanyaan 4: Mitigasi Perubahan Pola Peresepan Dokter & Kepatuhan Fornas

**Pertanyaan Penguji:**  
*"Model Anda mengandalkan pola historis. Bagaimana sistem PRABOTIK merespons jika terjadi pergantian dokter di Puskesmas yang tiba-tiba mengubah pola peresepan obat, atau meresepkan obat di luar Formularium Nasional?"*

**Jawaban Taktis & Ilmiah:**  
Sistem PRABOTIK mengantisipasi dinamika perubahan perilaku klinis tersebut melalui tiga mekanisme pertahanan berlapis (preventif, detektif, dan korektif):

1. **Lapisan Preventif & Evaluatif (Audit Kepatuhan Fornas di Portal Kepala):**  
   Pada `/kepala`, tersedia fitur pengawasan rasio peresepan lintas poli rawat jalan (Poli Umum, KIA/KB, Gigi) yang terikat langsung dengan Master Formularium Nasional (Fornas) dan pemantauan 40 obat esensial SPM Kemenkes. Jika terjadi deviasi peresepan di luar Fornas, persentase kepatuhan poli akan turun dan langsung terlihat oleh Kepala Puskesmas.
2. **Lapisan Detektif Respons Cepat via MACD (2, 4, 2):**  
   Berbeda dengan *Moving Average* biasa yang lambat merespons perubahan tren, PRABOTIK mengintegrasikan indikator momentum MACD dengan parameter adaptif $\text{Fast EMA}$ ($\text{span}=2$). Dengan koefisien peluruhan $\alpha = \frac{2}{2+1} = 0,667$, perubahan pola peresepan pada satu periode terbaru langsung diberikan bobot **66,7%**. Jika dokter baru mengalihkan peresepan (misalnya dari Kaptopril ke Amlodipin), nilai Histogram MACD akan langsung memicu sinyal *Bullish Crossover* sebagai peringatan dini bagi apoteker.
3. **Lapisan Korektif (*Human-in-the-Loop Disposisi*):**  
   Sistem tidak melakukan pemesanan buta secara otomatis, melainkan menyajikan rekomendasi pada alur kerja Rencana Pengadaan P5 yang harus melewati tahap *Review* dan *Disposisi Pengesahan* oleh Kepala Puskesmas sebelum dicetak menjadi berkas resmi ke Dinas Kesehatan.

---

### Pertanyaan 5: Kontribusi Kebaruan Ilmiah (*Novelty*) di Bidang Informatika

**Pertanyaan Penguji:**  
*"Sistem informasi manajemen gudang obat di Puskesmas sudah banyak diteliti. Apa letak kebaruan ilmiah (novelty) utama dari penelitian Anda jika diukur dari kontribusi Teknik Informatika dan Data Mining?"*

**Jawaban Taktis & Ilmiah:**  
Kebaruan ilmiah (*novelty*) dari penelitian PRABOTIK terletak pada tiga kontribusi metodologis dan arsitektural yang belum diterapkan pada sistem LPLPO Puskesmas konvensional:

1. **Adaptasi Skala Domain Waktu Indikator Finansial MACD (2, 4, 2) pada Epidemiologi Logistik:**  
   Penelitian ini memformulasikan ulang parameter MACD finansial standar $(12, 26, 9)$ menjadi konfigurasi dwibulanan $(2, 4, 2)$ yang terbukti mampu mendeteksi percepatan (*momentum*) lonjakan kebutuhan obat musiman pada dataset berfrekuensi rendah ($N = 22$ periode) sebelum stok menyentuh ambang kritis.
2. **Pipeline Peramalan Hibrida Asimetris (*Censored-Adjusted WMA-3 & Quantile Ceiling*):**  
   Menggabungkan teknik rekonstruksi *Censored Demand* untuk mengatasi bias *stockout*, memilih estimator $\text{WMA-3}$ berdasarkan sifat bias positif moderat ($+4,3\%$) sebagai penyangga klinis, serta memanfaatkan *Machine Learning HistGradientBoosting Quantile Regression* ($\alpha = 0,95$) sebagai batas atap persediaan pengaman (*Safety Stock Ceiling*) untuk mencegah kerugian obat kadaluarsa (yang sebelumnya mencapai Rp 43,35 juta).
3. **Validasi Silang Matriks Rasio Episode Morbiditas ($R_{i,j}$) dalam Arsitektur Dual-Portal:**  
   Mengintegrasikan korelasi statistik penyakit ICD-10 SIMPUS terhadap kelas terapi LPLPO ke dalam arsitektur perangkat lunak yang memisahkan secara ketat beban kognitif operasional gudang (`/farmasi`) dan akuntabilitas legalitas anggaran KPA ber-NIP (`/kepala`).
