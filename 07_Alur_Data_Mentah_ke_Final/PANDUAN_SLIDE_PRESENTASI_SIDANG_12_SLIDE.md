# 🖥️ Panduan Struktur Slide & Naskah Presentasi Sidang Skripsi
**Sistem PRABOTIK — UPTD Puskesmas Teluk Sasah, Kabupaten Bintan**  
*Format: 12 Slide Presentasi | Alokasi Waktu: 15 Menit*

---

### Slide 1: Judul Penelitian & Identitas Sistem (Menit 0:00 – 1:00)
* **Komponen Visual & Poin Layar:**
  - Judul Sistem: **PRABOTIK** (*Sistem Prediksi Kebutuhan Obat & Analisis Morbiditas Penyakit Berbasis Data Mining*).
  - Lokus: UPTD Puskesmas Teluk Sasah, Kec. Seri Kuala Lobam, Kab. Bintan.
  - Stack: Laravel 11, SQLite, Tailwind CSS, Chart.js, Python Analytics.
* **Naskah Lisan (Speaker Notes):**
  > "Selamat pagi/siang Bapak dan Ibu Dewan Penguji. Pada kesempatan ini, saya akan mempresentasikan hasil penelitian tugas akhir kami yang berjudul PRABOTIK: Sistem Prediksi Kebutuhan Obat dan Analisis Morbiditas Penyakit Berbasis Data Mining dengan studi kasus di UPTD Puskesmas Teluk Sasah, Kabupaten Bintan. Penelitian ini berangkat dari masalah nyata dalam tata kelola rantai pasok farmasi di fasilitas kesehatan tingkat pertama, yang kami selesaikan melalui pendekatan hibrida antara rekayasa perangkat lunak berarsitektur Dual-Portal dan pemodelan deret waktu terapan."

---

### Slide 2: Urgensi & Empat Akar Masalah Riil di Lapangan (Menit 1:00 – 2:15)
* **Komponen Visual & Poin Layar:**
  - **Kerugian Obat ED:** Rp 43,35 Juta (akumulasi 3 tahun dana APBD & DAK) akibat *over-budgeting* dan lemahnya kontrol FEFO.
  - **Stockout Musiman:** Kekosongan Parasetamol & antibiotik saat lonjakan ISPA musim utara.
  - **Distorsi *Censored Demand*:** Perencanaan LPLPO manual tidak mengoreksi periode stok kosong.
  - **Tumpang-Tindih Peran:** Kebutuhan operasional gudang farmasi berbeda dengan kebutuhan disposisi anggaran Kepala Puskesmas.
* **Naskah Lisan (Speaker Notes):**
  > "Berdasarkan audit data historis selama tiga tahun di Puskesmas Teluk Sasah, kami menemukan paradoks logistik yang krusial. Di satu sisi, terjadi kerugian fisik akibat obat kadaluarsa atau Expired Date senilai 43,35 juta rupiah dari dana APBD dan DAK karena pengadaan yang tidak terukur dan rotasi FEFO yang belum terpantau otomatis. Namun di sisi lain, justru terjadi kekosongan obat esensial seperti Parasetamol dan antibiotik saat kasus ISPA melonjak pada musim angin utara. Akar masalahnya terletak pada metode perencanaan konvensional yang hanya membaca pengeluaran mentah LPLPO tanpa mengoreksi periode saat stok habis, serta belum adanya pemisahan sistem kerja antara staf gudang farmasi dan Kepala Puskesmas selaku Kuasa Pengguna Anggaran."

---

### Slide 3: Anatomi Masalah Statistik — *Censored Demand* (Menit 2:15 – 3:30)
* **Komponen Visual & Poin Layar:**
  - Ilustrasi Grafik: Garis permintaan riil pasien vs garis pengeluaran tercatat LPLPO yang terpotong di angka 0 saat *stockout*.
  - Bahaya Rumus Konvensional: Saat stok habis, konsumsi tercatat 0 $\rightarrow$ Rata-rata periode berikutnya anjlok 50% $\rightarrow$ Siklus *stockout* berulang.
* **Naskah Lisan (Speaker Notes):**
  > "Secara keilmuan data mining, kelemahan utama perencanaan Puskesmas selama ini adalah terjebak pada fenomena Censored Demand atau permintaan tersensor. Ketika stok suatu obat habis total di gudang, pengeluaran pada buku LPLPO tercatat nol. Padahal, angka nol tersebut adalah batas kapasitas suplai, bukan berhentinya penyakit di masyarakat. Jika angka nol ini langsung dimasukkan ke dalam rumus rata-rata bergerak tanpa koreksi, maka hasil prediksi untuk periode berikutnya akan anjlok drastis dan memicu kekosongan obat yang berulang."

---

### Slide 4: Solusi Arsitektur — *Dual-Portal System* PRABOTIK (Menit 3:30 – 4:45)
* **Komponen Visual & Poin Layar:**
  - Diagram Arsitektur: Gateway Portal (`/`) membagi otorisasi ke `/farmasi` dan `/kepala` di atas Laravel 11 & SQLite.
  - **Portal Farmasi (`/farmasi`):** Operasional gudang, peringatan stok kritis, FEFO, entri LPLPO/SIMPUS, prediksi P5 & sinyal MACD.
  - **Portal Kepala (`/kepala`):** KPI SPM Kemenkes (91,3%), audit kerugian ED, kepatuhan Fornas poli, dan disposisi legalitas P5 ber-NIP.
* **Naskah Lisan (Speaker Notes):**
  > "Untuk menjawab permasalahan tersebut pada level rekayasa perangkat lunak, kami merancang PRABOTIK dengan arsitektur Dual-Portal menggunakan Laravel 11 dan database SQLite yang portabel. Kami memisahkan secara tegas antara Portal Operasional Farmasi untuk apoteker pengelola gudang dan Portal Eksekutif untuk Kepala Puskesmas. Apoteker fokus pada kendali stok fisik, pengawasan batch FEFO, serta kalkulasi rencana pengadaan P5, sedangkan Kepala Puskesmas fokus pada pengawasan indikator SPM Kemenkes, audit nilai finansial obat kadaluarsa, hingga penerbitan disposisi pengesahan anggaran ber-NIP ke Dinas Kesehatan."

---

### Slide 5: Pipeline Metodologi Data Mining Terintegrasi (Menit 4:45 – 6:00)
* **Komponen Visual & Poin Layar:**
  - Tahap 1: *Censored Demand Imputation* (Rekonstruksi nilai periode *stockout*).
  - Tahap 2: *Time-Series Forecasting* ($\text{WMA-3}$ sebagai estimator utama & *GBM Quantile 0,95* sebagai *Safety Stock Ceiling*).
  - Tahap 3: *Momentum Detection* (Indikator MACD dwibulanan parameter 2, 4, 2).
  - Tahap 4: *Clinical Cross-Validation* (Matriks korelasi penyakit ICD-10 SIMPUS & rasio dosis per kasus).
* **Naskah Lisan (Speaker Notes):**
  > "Di balik antarmuka Dual-Portal tersebut, bekerja empat tahapan pipeline data mining yang saling mengunci. Pertama, modul imputasi Censored Demand memperbaiki distorsi data pada periode stok kosong. Kedua, mesin peramalan menghitung proyeksi titik menggunakan Weighted Moving Average tiga periode yang dikombinasikan dengan batas atap persediaan dari Gradient Boosting Quantile Regression. Ketiga, indikator momentum MACD mendeteksi percepatan lonjakan permintaan musiman, dan keempat, seluruh hasil prediksi divalidasi silang terhadap matriks morbiditas penyakit dari SIMPUS."

---

### Slide 6: Studi Kasus Kritis — Parasetamol 500 mg (Menit 6:00 – 7:30)
* **Komponen Visual & Poin Layar:**
  - Fakta Lapangan 2026-P4: Stok awal 0, penerimaan 0, konsumsi 0 akibat habisnya 6.444 tablet pada 2026-P3.
  - Tanpa Koreksi (*Unadjusted*): Proyeksi $\text{SMA-2}$ anjlok ke **3.222 tablet**; MACD sinyal *crash* semu ($-485,42$).
  - Dengan Koreksi PRABOTIK: Imputasi $\hat{y}_{\text{P4}} = 5.850$ $\rightarrow$ Proyeksi naik ke **6.147 tablet** ($\text{SMA-2}$) dan **5.949 tablet** ($\text{WMA-3}$).
  - Alokasi Final 2026-P5: **7.487 tablet** (termasuk *Safety Stock* 1.340 tablet); sinyal MACD pulih ke **$+34,58$ (*Rebound*)**.
* **Naskah Lisan (Speaker Notes):**
  > "Berikut adalah pembuktian matematis nyata pada kasus Parasetamol 500 miligram. Pada periode 2026-P3, seluruh stok sebanyak 6.444 tablet habis terpakai, sehingga pada 2026-P4 pemakaian tercatat nol. Tanpa algoritma koreksi kami, rumus Moving Average memprediksi kebutuhan periode P5 hanya sekitar 3.024 hingga 3.222 tablet—turun separuh tepat saat memasuki musim ISPA. Melalui imputasi Censored Demand, sistem merekonstruksi permintaan P4 sebesar 5.850 tablet, sehingga proyeksi titik terangkat kembali sebesar 2.925 tablet. Ditambah Safety Stock 95 persen sebesar 1.340 tablet, rekomendasi pengadaan P5 ditetapkan sebesar 7.487 tablet dan sinyal MACD berhasil dikoreksi dari minus 485,42 menjadi positif 34,58."

---

### Slide 7: Adaptasi Ilmiah Indikator MACD Dwibulanan (2, 4, 2) (Menit 7:30 – 8:45)
* **Komponen Visual & Poin Layar:**
  - Kendala Klasik: MACD finansial $(12, 26, 9)$ butuh minimal 52 periode, sedangkan histori Puskesmas adalah 22 periode dwibulanan ($\approx 44$ bulan).
  - Formulasi PRABOTIK: $\text{Fast EMA}=2$ ($\approx 4$ bulan, $\alpha=0,667$), $\text{Slow EMA}=4$ ($\approx 8$ bulan, $\alpha=0,400$), $\text{Signal EMA}=2$.
  - Status Sinyal: *Bullish* (Akselerasi Naik), *Bearish* (Penurunan), *Sideways* (Stabil).
* **Naskah Lisan (Speaker Notes):**
  > "Mengapa kami menggunakan parameter MACD 2, 4, 2 dan bukan 12, 26, 9? Dalam analisis finansial, data bersifat harian, sedangkan pelaporan LPLPO Puskesmas Teluk Sasah bersifat dwibulanan dengan dimensi 22 titik periode. Parameter klasik 12, 26, 9 tidak dapat konvergen pada 22 titik data. Oleh karena itu, kami melakukan penyelarasan skala waktu ke parameter 2, 4, 2. Fast EMA dengan span 2 memberikan bobot 66,7 persen pada periode terbaru untuk menangkap lonjakan musim pancaroba dalam jendela 4 bulan, sedangkan Slow EMA span 4 memetakan keseimbangan 8 bulanan."

---

### Slide 8: Hasil Eksperimen *10-Fold Walk-Forward Validation* (Menit 8:45 – 10:30)
* **Komponen Visual & Poin Layar:**
  - Tabel Komparasi 149 Obat Aktif (Periode Uji 2025-P1 s/d 2026-P4):
    - **HistGradientBoosting (GBM):** WAPE 0,536, **Bias -9,2%** (Risiko *under-forecast / stockout*).
    - **WMA (k=3) [Terpilih]:** WAPE 0,595, **Bias +4,3%**, unggul pada 58,7% obat, rata-rata MAPE **14,2%** (*Good Forecast*).
    - **GBM Quantile (0,95):** WAPE 1,209, **Bias +101,3%** (Difungsikan sebagai *Safety Stock Ceiling*).
* **Naskah Lisan (Speaker Notes):**
  > "Untuk menguji validitas model secara objektif tanpa kebocoran data masa depan, kami menjalankan 10-Fold Expanding Window Walk-Forward Validation pada 149 item obat aktif. Hasil pengujian ini mengungkap temuan penting: meskipun algoritma Machine Learning HistGradientBoosting menghasilkan WAPE terendah yaitu 0,536, ia memiliki bias negatif sebesar minus 9,2 persen. Dalam logistik medis, bias negatif sangat berbahaya karena memicu kekurangan stok. Oleh sebab itu, WMA-3 dipilih sebagai model utama karena unggul pada 58,7 persen obat dengan MAPE 14,2 persen dan memiliki bias positif moderat plus 4,3 persen sebagai penyangga alami. Adapun GBM Quantile 0,95 kami fungsikan sebagai batas atap pengadaan agar tidak terjadi over-budgeting."

---

### Slide 9: Matriks Korelasi SIMPUS & Rasio Dosis per Kasus (Menit 10:30 – 12:00)
* **Komponen Visual & Poin Layar:**
  - **ISPA (J00 — 34,8%):** Korelasi kuat dengan Parasetamol ($r = 0,5851$, rasio $13,43$ tab/kasus $\rightarrow 4.541$ tab) & Amoksisilin ($\rho = 0,6702$, rasio $5,06$ kap/kasus).
  - **DM Tipe 2 (E11):** Korelasi tertinggi pada Metformin ($\rho = 0,7753$, rasio rumatan $40,95$ tab/kasus $\rightarrow 2.826$ tab).
  - **Hipertensi (I10 — 24,0%):** Dominasi terapi rumatan Amlodipin 10 mg (rasio $19,08$ tab/kasus $\rightarrow 6.534$ tab) dibanding Kaptopril ($r = 0,1751$).
* **Naskah Lisan (Speaker Notes):**
  > "Selanjutnya, kami menguji korelasi antara 10 penyakit terbesar di SIMPUS terhadap pengeluaran kelas terapi obat. Terbukti bahwa fluktuasi ISPA berkorelasi signifikan dengan Parasetamol pada r sebesar 0,585 dan Amoksisilin pada rho Spearman 0,670. Kami juga merumuskan metrik Rasio Dosis per Kasus sebagai jembatan antara metode konsumsi dan metode morbiditas. Terlihat jelas perbedaan pola farmakoepidemiologi: penyakit akut seperti ISPA menyerap 13,43 tablet Parasetamol per kasus, sedangkan penyakit kronis membutuhkan terapi rumatan jangka panjang yang jauh lebih besar, yaitu 19,08 tablet per kasus pada Amlodipin 10 mg untuk Hipertensi dan 40,95 tablet per kasus pada Metformin untuk Diabetes Melitus."

---

### Slide 10: Implementasi Portal Operasional Farmasi (`/farmasi`) (Menit 12:00 – 13:00)
* **Komponen Visual & Poin Layar:**
  - Tangkapan Layar Antarmuka `/farmasi` (Pengguna: Apt. Siti Rahmawati, S.Farm).
  - Fitur Utama: Kartu indikator stok kritis (*buffer warning*), tabel proyeksi P5 terkoreksi *Censored Demand*, grafik sinyal MACD, dan modul kontrol batch FEFO.
* **Naskah Lisan (Speaker Notes):**
  > "Pada implementasi Portal Operasional Farmasi, apoteker disajikan instrumen kendali gudang yang terintegrasi. Halaman utama langsung menampilkan peringatan dini untuk item yang mendekati ambang kritis dan pelacakan masa kadaluarsa berbasis FEFO. Saat menyusun rencana pengadaan P5, sistem secara otomatis menyandingkan hasil prediksi WMA-3 yang telah dikoreksi terhadap Censored Demand bersama indikator arah momentum MACD dan batas atas Safety Stock, lalu mencetak lembar kerja LPLPO apotek secara presisi."

---

### Slide 11: Implementasi Portal Eksekutif Kepala Puskesmas (`/kepala`) (Menit 13:00 – 14:00)
* **Komponen Visual & Poin Layar:**
  - Tangkapan Layar Antarmuka `/kepala` (Pengguna: dr. Bambang Utoyo, M.M.).
  - Fitur Utama: Capaian KPI SPM Kemenkes (**91,3%** dari 40 obat esensial), audit transparansi kerugian ED (**Rp 43,35 Juta**), rasio kepatuhan Fornas poli, dan modul Disposisi Pengesahan P5 ber-NIP.
* **Naskah Lisan (Speaker Notes):**
  > "Beralih ke Portal Eksekutif Kepala Puskesmas, tampilan dirancang khusus untuk pengambilan keputusan manajerial dan akuntabilitas anggaran. Kepala Puskesmas dapat memantau capaian Standar Pelayanan Minimal ketersediaan 40 obat esensial yang berada pada angka 91,3 persen, mengevaluasi histori kerugian obat kadaluarsa senilai 43,35 juta rupiah, serta mengawasi rasio peresepan sesuai Formularium Nasional di Poli Umum, Gigi, dan KIA. Puncaknya, pengesahan usulan pengadaan P5 dilengkapi fitur disposisi resmi bernomor register dan kotak legalitas ber-NIP yang siap dikirimkan ke Dinas Kesehatan Kabupaten Bintan."

---

### Slide 12: Kesimpulan Utama & Kontribusi Penelitian (Menit 14:00 – 15:00)
* **Komponen Visual & Poin Layar:**
  - **Kontribusi Algoritmik:** Kombinasi *Censored Demand Imputation*, estimator $\text{WMA-3}$ (MAPE 14,2%, bias $+4,3\%$), *Quantile Ceiling* 95%, dan MACD $(2, 4, 2)$.
  - **Kontribusi Klinis & Manajerial:** Validasi rasio episode morbiditas SIMPUS dan pencegahan pemborosan obat ED Rp 43,35 Juta melalui arsitektur *Dual-Portal* SQLite yang siap direplikasi.
* **Naskah Lisan (Speaker Notes):**
  > "Sebagai penutup, sistem PRABOTIK memberikan kontribusi nyata baik secara keilmuan informatika maupun tata kelola layanan publik. Secara algoritmik, penggabungan koreksi Censored Demand, estimator WMA-3 berbias positif moderat, batas atap kuantil 95 persen, serta adaptasi MACD dwibulanan 2, 4, 2 terbukti menghasilkan prediksi yang akurat sekaligus aman secara klinis. Secara praktis, arsitektur Dual-Portal yang ringan berbasis SQLite ini siap membantu Puskesmas dan Dinas Kesehatan Kabupaten Bintan dalam menjamin ketersediaan obat esensial sekaligus melindungi anggaran daerah dari kerugian obat kadaluarsa. Demikian pemaparan saya, terima kasih, dan saya siap menerima masukan serta pertanyaan dari Bapak dan Ibu Dewan Penguji."
