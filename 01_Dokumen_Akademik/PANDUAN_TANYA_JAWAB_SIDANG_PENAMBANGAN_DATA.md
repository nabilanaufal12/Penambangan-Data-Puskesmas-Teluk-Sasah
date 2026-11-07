# 🎓 PANDUAN STRATEGIS TANYA-JAWAB SIDANG AKHIR PENAMBANGAN DATA
## **Proyek Kelompok 23: Prediksi Kebutuhan Stok Obat & Analisis Tren Penyakit Musiman pada UPTD Puskesmas Teluk Sasah, Kabupaten Bintan**

* **Mata Kuliah**: Penambangan Data (INF11121) – Semester Ganjil 2026/2027
* **Dosen Pengampu**: Tekad Matulatan, S.Sos., S.Kom., M.Info.Tech. & Muhamad Fadli, S.Kom., M.Kom.
* **Tim Penyusun (Kelompok 23)**:
  1. **2401020069 – Balqis Manisa (Ketua)**
  2. **2401020056 – Jian Safitri Wibowo**
  3. **2401020073 – Nabil**
  4. **2401020087 – Annisa Darmawan**
  5. **2401020110 – Yehezkiel Alman Siambaton**

---

> ### 📌 PETUNJUK PENGGUNAAN DOKUMEN INI
> Dokumen ini dirancang khusus untuk memandu seluruh anggota kelompok dalam menghadapi sesi tanya-jawab (*defense*) dengan dosen penguji. Setiap pertanyaan telah dilengkapi dengan **Logika Jawaban Inti (Ringkas)**, **Bukti Angka dari Sel Data Mentah**, serta **Argumen Ilmiah / Kaidah Penambangan Data** yang sesuai dengan rubrik penilaian RTM (poin argumentasi logis dan bernalar tinggi).

---

```
                                 DAFTAR ISI PERTANYAAN KRUSIAL
 ─────────────────────────────────────────────────────────────────────────────────────────
  Q1: Anomali Fero Sulfat vs Parasetamol (Obat Mana yang Terbanyak?)
  Q2: Perbedaan Rentang Waktu (Kenapa Kadaluarsa s/d 2025, tetapi LPLPO s/d 2026?)
  Q3: Penanganan Data Kosong (Missing Values) pada Berkas Kadaluarsa & Imputasi
  Q4: Penyelarasan Waktu (Data 2024 Bulanan vs 2023, 2025, 2026 Dwibulanan)
  Q5: Alasan Pemilihan Metode Moving Average (SMA vs WMA)
  Q6: Mengapa Nilai MAPE Berbeda Drastis antar-Obat? (Fenomena Efek Stockout)
  Q7: Pembuktian Ilmiah Hubungan Klinis Tren Penyakit vs Pemakaian Obat di Tahap EDA
  Q8: Solusi Operasional Manajerial bagi Mitra Puskesmas (Safety Stock 95% & ROP)
  Q9: Definisi dan Pengukuran Fluktuasi pada Pola Pemakaian Obat (Koefisien Variasi / CV)
  Q10: Paradoks Amlodipin: Mengapa Obat Penyakit Kronis Memiliki Fluktuasi Tinggi?
  Q11: Peringkat Obat dengan Fluktuasi Tertinggi dan Klasifikasi Klusternya
  Q12: Mengapa Data LPLPO, Penyakit, dan Kadaluarsa Tidak Digabung Jadi 1 File CSV? (Normalisasi 3NF)
  Q13: Menangani Perbedaan Dimensi Waktu: LPLPO (2 Bulan), Penyakit (1 Bulan), dan Kadaluarsa (1 Tahun)
 ─────────────────────────────────────────────────────────────────────────────────────────
```

---

### ❓ PERTANYAAN 1: ANOMALI DATA OBAT TERBANYAK
**Dosen Penguji:**  
*"Di laporan kalian tertulis pemakaian Fero Sulfat mencapai 255.180 butir, sedangkan Parasetamol 142.715 butir. Apakah benar Fero Sulfat adalah obat yang paling banyak dikonsumsi pasien di Puskesmas Teluk Sasah? Kenapa proposal kalian fokus pada pereda demam dan batuk?"*

#### 💡 Jawaban Inti Mahasiswa:
> *"Secara akumulasi angka mentah di kolom LPLPO, Fero Sulfat memang tercatat lebih besar. Namun, secara fungsi medis dan peresepan pasien sakit di poli, **Parasetamol 500 mg adalah obat kuratif nomor 1 paling banyak dan paling konsisten**. Fero Sulfat tinggi karena merupakan **obat drop program gizi massal ke sekolah-sekolah**, bukan obat orang sakit harian."*

#### 🔍 Bukti Empiris dari Berkas Data Mentah:
1. **Bukti Sel LPLPO Januari 2024 (`1 JANUARI 2024.xls` Baris 122)**:
   * Dalam 1 bulan tersebut, keluar Fero Sulfat sebanyak **118.295 tablet** sekaligus.
   * Pada **Kolom Keterangan (Col 13)** tertulis resmi dari petugas puskesmas: **`GIZI`**.
   * Di bulan-bulan biasa (seperti Feb–Desember 2024), pemakaian Fero Sulfat normal hanya **600 – 1.000 butir per bulan**.
2. **Pemisahan Formularium di Berkas 2026 (`MEI - JUNI 2026 - PISAH.xls`)**:
   * Petugas farmasi puskesmas sendiri memisahkan Fero Sulfat ke sheet **`OBAT HIBAH`**, terpisah dari obat pelayanan umum (`OBAT APBD`).
3. **Analisis Statistik Fluktuasi ($CV$)**:
   * Fero Sulfat memiliki Koefisien Variasi ekstrem **$CV = 2.20$** (karena drop massal ratusan ribu tablet ke siswi SMP/SMA dalam rangka Program Aksi Bergizi Nasional Kemenkes).
   * Parasetamol memiliki **$CV = 0.36$** yang stabil dan berulang setiap bulan (rata-rata 6.487 butir/2 bulan) sejalan dengan penyakit nomor satu puskesmas, yaitu **Common Cold (J00)**.

---

### ❓ PERTANYAAN 2: PERBEDAAN RENTANG TAHUN DATA MENTAH
**Dosen Penguji:**  
*"Kenapa data obat kadaluarsa kelompok kalian hanya sampai tahun 2025, tetapi data LPLPO dan data penyakit sampai 2026? Kenapa data tahun 2026 tidak dibuang saja agar rentang waktunya seragam?"*

#### 💡 Jawaban Inti Mahasiswa:
> *"Data 2026 pada LPLPO dan Morbilitas Penyakit **mutlak harus dipertahankan** karena dalam *Time Series Forecasting*, data terbarulah yang paling bernilai untuk memprediksi kebutuhan masa depan riil mitra. Perbedaan rentang waktu ini terjadi karena **perbedaan siklus administratif** antara data transaksi logistik dwibulanan dengan data audit pemusnahan tahunan."*

#### 🔍 Penjelasan Konsep & Kaidah Data Mining:
1. **Perbedaan Siklus Pelaporan Administrasi**:
   * **LPLPO & Penyakit** adalah laporan operasional berkala yang dicatat per bulan/dwibulanan, sehingga kita bisa mendapatkan data berjalan hingga **Agustus 2026 (22 periode dwibulanan)**.
   * **Laporan Kadaluarsa** adalah berita acara audit tahunan (*annual fiscal audit*) yang baru ditutup buku pada akhir tahun anggaran (Desember). Karena tahun 2026 saat ini masih berjalan, berita acara resmi tahun 2026 belum diterbitkan oleh Dinas Kesehatan.
2. **Fungsi Data yang Berbeda dalam Struktur RTM**:
   * Data kadaluarsa berfungsi di **BAB I (Business Understanding)** sebagai bukti audit kerugian riil (**144 batch senilai Rp 43,35 Juta**). Data 3 tahun (2023–2025) sudah sangat representatif membuktikan masalah *overstock*.
   * Data LPLPO 2026 berfungsi di **BAB IV & V (Model & Evaluasi)** untuk melatih model time series dan meramalkan kebutuhan terkini puskesmas pada periode berjalan (**2026-P5 / September–Oktober 2026**).
3. **Jika Data 2026 Dibuang**:
   * Kita kehilangan sepertiga data deret waktu, dan hasil peramalan kita menjadi usang (hanya meramalkan awal 2026 yang sudah lewat).

---

### ❓ PERTANYAAN 3: IMPUTASI DATA KOSONG PADA OBAT KADALUARSA
**Dosen Penguji:**  
*"Apakah data obat kadaluarsa tahun 2026 boleh diisi dengan median atau nilai rata-rata dari tahun-tahun sebelumnya? Bagaimana kalian menangani data kosong pada berkas kadaluarsa?"*

#### 💡 Jawaban Inti Mahasiswa:
> *"Untuk tabel data historis, **tahun 2026 dibiarkan kosong dan tidak boleh diimputasi secara artifisial**, karena berkas kadaluarsa adalah dokumen legal audit negara. Namun, nilai median kerugian (Rp 11,24 Juta/tahun) kami gunakan sebagai **analisis estimasi risiko manajerial**. Untuk nilai kosong pada harga satuan obat tertentu, kami menggunakan **Domain Lookup ke master data LPLPO**, bukan median acak."*

#### 🔍 Penjelasan Teknis Pre-processing (BAB III):
1. **Klarifikasi Istilah**: *Outlier* adalah nilai pencilan ekstrem, sedangkan teknik pengisian data kosong disebut *Imputasi*.
2. **Etika Integritas Data**: Memasukkan baris data kadaluarsa fiktif pada tahun 2026 menggunakan median dianggap sebagai manipulasi data audit (*data fabrication*). Sebagai gantinya, angka median Rp 11,24 Juta per tahun disajikan dalam teks narasi Bab I sebagai **potensi risiko pemborosan** jika model prediksi tidak diterapkan.
3. **Penanganan Baris Kosong / Artefak Tanda Tangan**:
   * Saat mengekstrak file Excel kadaluarsa, terbawa baris pengesahan nama `dr. Kurniawan` dan `NIP...` yang memiliki harga 0 dan jumlah 0.
   * Tindakan yang benar adalah **Data Cleaning (Filtering/Penghapusan)**, sehingga diperoleh tepat **138 item murni obat/alkes kadaluarsa**.
4. **Lookup Master LPLPO**:
   * Untuk item seperti *Vitamin C* atau *Povidon Iodin* yang kolom harganya kosong di lembar kadaluarsa, nilainya tidak ditebak dengan median, melainkan dicocokkan langsung (*lookup*) ke harga beli resmi di berkas LPLPO puskesmas.

---

### ❓ PERTANYAAN 4: PENYELARASAN INKONSISTENSI PERIODE PELAPORAN
**Dosen Penguji:**  
*"Data mentah LPLPO tahun 2024 berbentuk bulanan (12 file), tetapi tahun 2023, 2025, dan 2026 berbentuk dwibulanan. Bagaimana strategi kalian mengatasi inkonsistensi waktu ini?"*

#### 💡 Jawaban Inti Mahasiswa:
> *"Kami menerapkan teknik **Agregasi Deret Waktu Bertingkat (Temporal Granularity Alignment)** dengan menyatukan seluruh laporan ke dalam **satuan waktu terkecil yang konsisten, yaitu 22 Periode Dwibulanan Kontinu (2023-P1 s/d 2026-P4)** tanpa membuang satu pun data transaksi."*

#### 🔍 Formula Agregasi yang Diterapkan:
1. **Variabel Aliran (Flow Variables / Pemakaian & Penerimaan)**:
   * Menggunakan operasi penjumlahan kumulatif ($\sum$):
     $$\text{Pemakaian}_{\text{P1 2024}} = \text{Pemakaian}_{\text{Januari 2024}} + \text{Pemakaian}_{\text{Februari 2024}}$$
2. **Variabel Posisi Stok (Stock-Level Variables / Stok Awal & Akhir)**:
   * $\text{Stok Awal Dwibulanan} = \text{Stok Awal bulan pertama (Januari)}$.
   * $\text{Stok Akhir Dwibulanan} = \text{Stok Akhir bulan kedua (Februari)}$.
3. **Hasil**: Menghasilkan deret waktu yang mulus (*smooth*) dengan 22 interval waktu seragam berjarak 2 bulan, sehingga memenuhi asumsi matematis model deret waktu.

---

### ❓ PERTANYAAN 5: ALASAN MEMILIH MOVING AVERAGE (SMA vs WMA)
**Dosen Penguji:**  
*"Kenapa memilih teknik Moving Average dan bukan regresi linier biasa? Dan kenapa membandingkan SMA-2, SMA-3, dengan WMA-3?"*

#### 💡 Jawaban Inti Mahasiswa:
> *"Data konsumsi obat puskesmas bersifat dinamis, musiman, dan dipengaruhi oleh pola stok jangka pendek, bukan tren garis lurus jangka panjang statis seperti pada regresi linier. Kami membandingkan SMA dan WMA untuk melihat apakah pembobotan pada tren terkini mampu meningkatkan akurasi prediksi."*

#### 🔍 Formulasi & Keunggulan Matematis (BAB IV):
1. **Simple Moving Average ($k=3$)**:
   $$F_{t+1} = \frac{A_t + A_{t-1} + A_{t-2}}{3}$$
   *Meratakan fluktuasi acak secara merata dengan bobot seimbang (33,3% per periode).*
2. **Weighted Moving Average ($k=3$, bobot $[1, 2, 3]$)**:
   $$F_{t+1} = \frac{3A_t + 2A_{t-1} + 1A_{t-2}}{6}$$
   *Memberikan bobot tertinggi (50%) pada pemakaian periode paling akhir ($A_t$), 33,3% pada periode lalu ($A_{t-1}$), dan 16,7% pada 2 periode sebelumnya ($A_{t-2}$).*
3. **Temuan Evaluasi**:
   * Pada obat-obat konsumsi rutin berkelanjutan seperti **Metformin 500 mg** dan **Amoksisilin 500 mg**, **WMA-3 terbukti memberikan nilai galat (MAPE) terendah ($15.25\%$ dan $17.35\%$)**, membuktikan bahwa kebutuhan farmasi sangat dipengaruhi oleh dinamika konsumsi periode terakhir.

---

### ❓ PERTANYAAN 6: ANALISIS NILAI GALAT MAPE & EFEK STOCKOUT
**Dosen Penguji:**  
*"Di evaluasi kalian, kenapa obat Metformin memiliki MAPE 15% (kategori Baik), tetapi Amlodipin MAPE-nya 73% dan Ambroksol tinggi? Apakah algoritma kalian gagal pada obat-obat tersebut?"*

#### 💡 Jawaban Inti Mahasiswa:
> *"Tingginya nilai MAPE pada Amlodipin dan Ambroksol **bukan karena kegagalan algoritma peramalan**, melainkan karena adanya **fenomena anomali riil di lapangan berupa Stockout (kekosongan obat)**. Ketika stok di puskesmas habis (stok = 0), konsumsi tercatat terpaksa drop ke angka 0, padahal permintaan riil pasien tetap tinggi."*

#### 🔍 Fakta Data Pendukung:
1. **Bukti Stockout Ambroksol 30 mg**:
   * Mengalami kekosongan stok akhir di **8 dari 22 periode (36% waktu kosong)**. Pada periode 2023-P4 s/d P6, stok Ambroksol habis total, sehingga pemakaian tercatat 0 butir padahal kasus batuk-pilek (J00) sedang memuncak.
2. **Bukti Stockout Amlodipin 10 mg**:
   * Mengalami kekosongan stok di **7 periode (32% waktu kosong)** akibat kendala pengiriman dari gudang farmasi dinas di akhir tahun.
3. **Perbandingan dengan Obat Stabil (Metformin 500 mg)**:
   * Metformin memiliki ketersediaan stok yang selalu aman (**0 kali stockout**) sehingga data pemakaiannya mencerminkan kebutuhan murni pasien diabetes ($CV = 0.26$). Hasilnya, model WMA berhasil memprediksi dengan akurasi sangat baik (**$\text{MAPE} = 15.25\%$**).

---

### ❓ PERTANYAAN 7: VALIDASI KORELASI KLINIS PENYAKIT VS OBAT
**Dosen Penguji:**  
*"Bagaimana kalian membuktikan secara statistik bahwa pemakaian obat memang berhubungan dengan pola penyakit di Puskesmas Teluk Sasah?"*

#### 💡 Jawaban Inti Mahasiswa:
> *"Kami melakukan uji korelasi bivariat **Pearson ($r$)** dan **Spearman** antara data bulanan pasien 10 besar penyakit ICD-10 dengan data pemakaian obat terkait sepanjang 22 periode."*

#### 🔍 Hasil Uji Statistik Signifikan (BAB II EDA):
1. **Diabetes Melitus (E11) vs Metformin 500 mg**:
   * Nilai korelasi **Pearson $r = 0.5281$** dengan **$p\text{-value} = 0.0115 < 0.05$** (Signifikan secara statistik pada tingkat kepercayaan 95%).
   * Nilai **Spearman $r = 0.5869$**.
2. **Common Cold / ISPA (J00) vs Obat Terkait**:
   * Berkorelasi positif dengan Amoksisilin 500 mg ($r = 0.3924$), Cetirizina 10 mg ($r = 0.3593$), dan Parasetamol 500 mg ($r = 0.3381$).
3. **Dispepsia / Maag (K30) vs Obat Saluran Cerna**:
   * Berkorelasi positif dengan Omeprazol 20 mg ($r = 0.3844$) dan Domperidon 10 mg ($r = 0.3753$).

---

### ❓ PERTANYAAN 8: SOLUSI OPERASIONAL BAGI MITRA (BAB VI)
**Dosen Penguji:**  
*"Apa kegunaan praktis dari sistem kalian untuk petugas farmasi di Puskesmas Teluk Sasah? Jika puskesmas hanya memesan pas sesuai ramalan, bukankah masih bisa terjadi kehabisan obat jika ada lonjakan mendadak?"*

#### 💡 Jawaban Inti Mahasiswa:
> *"Model peramalan kami tidak berdiri sendiri, melainkan diintegrasikan ke dalam sistem rantai pasok farmasi dengan menyertakan **Safety Stock (Stok Pengaman) tingkat layanan 95%**. Dengan demikian, rekomendasi pengadaan sudah memperhitungkan cadangan lonjakan penyakit musiman."*

#### 🔍 Formula Implementasi Manajerial:
1. **Safety Stock ($SS$)**:
   $$SS = Z \times \sigma_L = 1.65 \times \text{Std Deviasi Pemakaian}$$
2. **Rekomendasi Pemesanan ke Dinas Kesehatan**:
   $$\text{Rencana Pengadaan} = F_{t+1} + SS - \text{Sisa Stok Akhir Saat Ini}$$
3. **Dampak Nyata bagi Puskesmas Teluk Sasah**:
   * Mencegah terulangnya kerugian obat kedaluwarsa sebesar **Rp 43,35 Juta** akibat pemesanan berlebih (*overstock*).
   * Menghilangkan risiko kekosongan obat esensial (*anti-stockout*) saat terjadi musim wabah flu/ISPA tahunan.

---

### ❓ PERTANYAAN 9: DEFINISI & PENGUKURAN FLUKTUASI (KOEFISIEN VARIASI / CV)
**Dosen Penguji:**  
*"Apa yang dimaksud dengan fluktuasi pemakaian obat pada penelitian kalian? Mengapa kalian mengukurnya menggunakan Koefisien Variasi (CV) dan bukan sekadar standar deviasi biasa? Bagaimana klasifikasinya?"*

#### 💡 Jawaban Inti Mahasiswa:
> *"Fluktuasi adalah **derajat variabilitas atau ketidakteraturan naik-turunnya angka konsumsi obat antar-periode**. Kami menggunakan **Koefisien Variasi ($CV = \sigma / \mu$)** karena $CV$ menormalkan variabilitas terhadap rata-rata pemakaian, sehingga tingkat ketidakstabilan obat bervolume besar (ribuan butir) dapat dibandingkan secara adil dan objektif dengan obat bervolume kecil (ratusan butir)."*

#### 🔍 Formulasi & Klasifikasi Kuadran (BAB IV):
1. **Formulasi Matematis**:
   $$CV = \frac{\sigma}{\mu} = \frac{\text{Standar Deviasi Pemakaian}}{\text{Rata-rata Pemakaian (Mean)}}$$
2. **Tiga Kuadran Stabilitas Pemakaian Obat**:
   * **Kuadran 1: Stabil / Fluktuasi Rendah ($CV < 0.40$)**: Konsumsi rutin berulang, sangat mudah diprediksi dengan akurasi tinggi (MAPE rendah). Contoh: *Amoksisilin 500 mg* ($CV = 0.22$), *Metformin 500 mg* ($CV = 0.26$), *Parasetamol 500 mg* ($CV = 0.36$).
   * **Kuadran 2: Moderat ($0.40 \le CV \le 0.80$)**: Dipengaruhi dinamika keluhan penyakit musiman/alergi/nyeri. Contoh: *Vitamin B-Kompleks* ($CV = 0.50$), *Cetirizina 10 mg* ($CV = 0.58$), *Asam Mefenamat* ($CV = 0.62$).
   * **Kuadran 3: Fluktuasi Tinggi / Ekstrem ($CV > 0.80$)**: Pola konsumsi sangat tidak menentu akibat lonjakan program massal atau anomali kekosongan stok (*stockout*). Contoh: *Amlodipin 10 mg* ($CV = 0.81$), *Ambroksol 30 mg* ($CV = 1.24$), *Fero Sulfat* ($CV = 2.20$).
3. **Hubungan dengan Model Peramalan**: Obat pada Kuadran 1 dapat langsung dipesan mendekati hasil ramalan, sedangkan obat pada Kuadran 3 wajib diberikan alokasi *Safety Stock* yang lebih besar untuk menyerap guncangan ketidakpastian.

---

### ❓ PERTANYAAN 10: PARADOKS AMLODIPIN (OBAT KRONIS VS FLUKTUASI TINGGI)
**Dosen Penguji:**  
*"Amlodipin adalah obat antihipertensi yang dikonsumsi rutin seumur hidup oleh penderita darah tinggi. Secara teori medis pemakaiannya harusnya sangat stabil seperti Metformin. Tapi kenapa di data kalian Amlodipin 10 mg masuk kategori fluktuasi tinggi ($CV = 0.81$) dan galat peramalannya (MAPE) mencapai 73%? Apakah algoritma kalian gagal?"*

#### 💡 Jawaban Inti Mahasiswa:
> *"Tingginya nilai $CV$ ($0.81$) dan MAPE ($73.54\%$) pada Amlodipin 10 mg **bukan karena kegagalan algoritma Moving Average**, melainkan bukti empiris adanya **anomali berat pada rantai pasok puskesmas, yaitu fenomena Stockout (kekosongan stok) di 7 periode**. Ketika stok gudang habis, pemakaian terpaksa tercatat 0 butir, dan saat pasokan tiba terjadi rapel pengambilan obat masif."*

#### 🔍 Fakta Data Riil LPLPO (Bukti Sel Excel):
1. **Bukti Kekosongan Stok Akhir (*Stockout*) di 7 Periode**:
   * Amlodipin 10 mg mengalami stok akhir = 0 butir pada periode `2023-P6`, `2024-P6`, `2025-P4`, `2025-P5`, `2025-P6`, `2026-P1`, dan `2026-P2` (32% dari total rentang waktu penelitian kosong!).
   * Puncaknya pada **Periode 2026-P2 (Maret–April 2026)**: Stok awal = 0, kiriman dinas = 0, sehingga **pemakaian tercatat 0 butir** selama 2 bulan penuh bukan karena pasien sembuh, melainkan karena obatnya habis total.
2. **Efek Substitusi Dosis Antara 10 mg dan 5 mg**:
   * Pada Amlodipin 5 mg, terjadi kekosongan stok selama **6 bulan berturut-turut** di tahun 2025 (`2025-P2`, `P3`, `P4`). Saat dosis 5 mg habis, pasien dialihkan meminum dosis 10 mg (dibagi dua). Sebaliknya saat dosis 10 mg habis, pasien diberikan dosis 5 mg sebanyak 2 tablet. Hal ini menciptakan fluktuasi buatan (*artificial volatility*).
3. **Fenomena Rapel Konsumsi Saat Pasokan Tiba**:
   * Pada periode `2025-P5`, saat kiriman 11.000 butir dari dinas tiba setelah krisis stok, pemakaian langsung melonjak tajam ke angka **11.000 butir sekaligus** dalam satu periode.
4. **Kesimpulan Akademik**: Justru temuan inilah yang membuktikan pentingnya penelitian data mining ini, karena membuktikan kelemahan fatal sistem pengadaan manual puskesmas saat ini.

---

### ❓ PERTANYAAN 11: PERINGKAT TOP OBAT FLUKTUASI TERTINGGI & KLUSTERNYA
**Dosen Penguji:**  
*"Sebutkan obat-obat apa saja yang mengalami fluktuasi tertinggi di Puskesmas Teluk Sasah dan bagaimana klasifikasi penyebab di balik tingginya fluktuasi tersebut?"*

#### 💡 Jawaban Inti Mahasiswa:
> *"Dari 414 jenis obat di puskesmas, obat-obat dengan fluktuasi tertinggi terbagi ke dalam **4 kluster penyebab utama**: (1) Program Bantuan/Gizi Nasional, (2) Dinamika Penyakit Musiman yang diperparah Stockout, (3) Krisis Rantai Pasok Obat Kronis & Lambung, dan (4) Kejadian Luar Biasa/Wabah Sporadis."*

#### 🔍 Tabel Peringkat Top 10 Obat Fluktuasi Tertinggi (Volume > 20.000 Butir):
| No | Nama Obat | Total Konsumsi | Rata-rata ($\mu$) | Fluktuasi ($CV$) | Kluster & Penyebab Utama di Lapangan |
| :-: | :--- | :---: | :---: | :---: | :--- |
| **1** | **Fero Sulfat tab 300 mg** *(Tambah Darah)* | 255.180 butir | 11.599 butir | **2.20** | **Program Gizi Nasional**: Distribusi serentak ribuan butir ke siswi SMP/SMA (Jan 2024 drop 118.295 butir). |
| **2** | **Ambroksol tablet 30 mg** *(Batuk Dahak)* | 35.048 butir | 1.593 butir | **1.24** | **Musiman + Stockout Parah**: Terkena lonjakan ISPA dan kosong total (*stockout*) di 8 periode. |
| **3** | **Asetil Sisteina kapsul 200 mg** *(Mukolitik)* | 25.570 butir | 1.162 butir | **1.19** | **Musiman Batuk-Pilek**: Sangat fluktuatif mengikuti musim pancaroba dan keterlambatan pasokan dinas. |
| **4** | **Kaptopril tablet 25 mg** *(Antihipertensi)* | 21.760 butir | 989 butir | **1.13** | **Krisis Rantai Pasok**: Mengalami kekosongan berulang dan bergantian dengan Amlodipin. |
| **5** | **Ibuprofen tablet 400 mg** *(Anti-nyeri/Demam)* | 20.778 butir | 944 butir | **1.03** | **Substitusi Analgetik**: Sering habis sehingga peresepan dialihkan ke Parasetamol/Asam Mefenamat. |
| **6** | **Loratadin tablet 10 mg** *(Antihistamin)* | 27.392 butir | 1.245 butir | **0.97** | **Alergi Musiman**: Peresepan fluktuatif bergantian ketersediaan dengan Cetirizina. |
| **7** | **Omeprazol capsul 20 mg** *(Obat Lambung)* | 20.159 butir | 916 butir | **0.95** | **Dispepsia Kronis**: Keterlambatan dropping dinas menyebabkan lonjakan saat pasokan tiba. |
| **8** | **Metilprednisolon tab 4 mg** *(Kortikosteroid)* | 25.083 butir | 1.140 butir | **0.95** | **Antiinflamasi**: Stok sering terputus berkepanjangan di instalasi farmasi. |
| **9** | **Piroksikam capsul 10 mg** *(Anti-radang Sendi)*| 29.694 butir | 1.350 butir | **0.84** | **Penyakit Sendi Lansia**: Pasokan obat dari gudang dinas tidak rutin setiap periode. |
| **10**| **Amlodipin tablet 10 mg** *(Antihipertensi)* | 140.090 butir | 6.368 butir | **0.81** | **Stockout 7 Periode**: Anomali kekosongan stok dan rapel pasokan akhir tahun. |

*Catatan Kasus Khusus:* Pada obat non-rutin, fluktuasi tertinggi dipegang oleh **Asiklovir tablet 200 mg ($CV = 4.61$)** karena merupakan obat antivirus cacar air/herpes yang hanya keluar masif saat terjadi wabah lokal sporadis, sedangkan di periode lain bernilai 0.

---

### ❓ PERTANYAAN 12: ALASAN TIDAK MENGGABUNGKAN JADI 1 FILE CSV (NORMALISASI 3NF)
**Dosen Penguji:**  
*"Kenapa data LPLPO, data diagnosis penyakit, dan data kadaluarsa tidak digabungkan saja menjadi 1 file CSV atau Excel datar tunggal? Dan apakah data pemakaian obat ini kalian lakukan normalisasi fitur Min-Max Scaling?"*

#### 💡 Jawaban Inti Mahasiswa:
> *"Kami tidak menggabungkannya ke dalam satu file datar (*single flat table*) karena melanggar **Kaidah Normalisasi Basis Data (Bentuk Normal ke-3 / 3NF)** dan menimbulkan **Grain Mismatch (perbedaan granularitas entitas)** yang memicu redundansi jutaan data kosong. Data pemakaian juga **sengaja tidak di-normalisasi Min-Max ke skala $[0, 1]$** agar hasil ramalan tetap dalam satuan unit riil (butir) yang langsung dapat dieksekusi oleh staf apotek puskesmas."*

#### 🔍 Penjelasan Ilmiah Data Engineering & Data Mining:
1. **Perbedaan Granularitas & Bahaya Redundansi Data**:
   * LPLPO memiliki **414 entitas obat** dalam skala dwibulanan (8.878 baris deret waktu).
   * Data Penyakit memiliki **10 diagnosis penyakit** dalam skala bulanan (450 baris).
   * Data Kadaluarsa adalah **audit tahunan** (138 baris).
   * Jika dipaksakan digabung dalam 1 baris CSV, data penyakit harus diduplikasi 414 kali per periode, dan kolom kadaluarsa akan berisi **95% nilai kosong (`NaN`)**, merusak efisiensi komputasi dan memicu anomali relasi *Many-to-Many*.
2. **Kepatuhan pada Prinsip 3NF (Third Normal Form)**:
   * Struktur modular yang kami buat memisahkan entitas: Tabel Transaksi Obat (`lplpo_bimonthly_timeseries.csv`), Tabel Diagnosa Morbiditas (`penyakit_clean_records.csv`), dan Tabel Kerugian Audit (`obat_kadaluarsa_clean.csv`). Ketiganya dihubungkan secara dinamis (*in-memory join*) menggunakan pustaka Pandas melalui **Kunci Periode Dwibulanan (`periode_bimonthly_id`)**.
3. **Mengapa Menolak Normalisasi Min-Max $[0, 1]$ pada Moving Average?**:
   * Algoritma *Simple/Weighted Moving Average* menghitung rata-rata bergerak konsumsi fisik.
   * Jika dinormalisasi ke rentang $[0, 1]$, output prediksi akan berupa desimal abstrak (misal: `0.342`), yang tidak bermakna bagi petugas puskesmas.
   * Dengan mempertahankan satuan riil (**butir / tablet**), hasil ramalan langsung menghasilkan rekomendasi operasional: *"Pesan Parasetamol 3.222 butir + Safety Stock 3.838 butir"*.

---

### ❓ PERTANYAAN 13: METODOLOGI MENGATASI PERBEDAAN DIMENSI WAKTU DATA
**Dosen Penguji:**  
*"LPLPO berformat 2 bulanan, data penyakit 1 bulanan, dan kadaluarsa 1 tahunan. Bagaimana metodologi data mining kalian mengolah data jika skala waktunya (temporal resolution) berbeda-beda?"*

#### 💡 Jawaban Inti Mahasiswa:
> *"Kami menerapkan teknik **Penyelarasan Multiskala Deret Waktu (Temporal Resampling & Up-Aggregation)** dengan menetapkan **Dwibulanan sebagai Sumbu Waktu Utama (Master Time Axis - 22 Periode Kontinu)**. Data penyakit bulanan kami agregasikan (*sum*) setiap 2 bulan sehingga sejajar sempurna dengan LPLPO, sedangkan data kadaluarsa tahunan tetap dipertahankan tahunan untuk evaluasi kerugian finansial di BAB I dan BAB V."*

#### 🔍 Kaidah Metodologis Data Mining:
1. **Prinsip Agregasi Deret Waktu (*Downsampling / Up-Aggregation*)**:
   * Kaidah data mining melarang membagi rata data 2-bulanan ke 1-bulanan karena memicu bias data buatan (*artificial assumption*).
   * Sebaliknya, menjumlahkan data frekuensi tinggi (bulanan) ke frekuensi lebih rendah (dwibulanan) secara matematis **100% valid dan melestarikan volume konsumsi sebenarnya**.
2. **Penyelarasan Data Penyakit (Bulanan $\rightarrow$ Dwibulanan)**:
   * Kasus penyakit pada bulan ganjil dan genap dijumlahkan:
     $$\text{Kasus } P_1 = \text{Kasus Januari} + \text{Kasus Februari}$$
   * Hasilnya: 44 bulan bertransformasi mulus menjadi **22 periode dwibulanan kontinu (`2023-P1` s/d `2026-P4`)**, sejajar sempurna untuk uji korelasi Pearson dengan pemakaian obat.
3. **Penyelarasan LPLPO 2024 (Bulanan $\rightarrow$ Dwibulanan)**:
   * Sebanyak 12 file LPLPO bulanan tahun 2024 digabungkan pemakaiannya per 2 bulan sehingga deret waktu transaksi obat kontinu tanpa terputus.
4. **Perlakuan Data Kadaluarsa Tahunan**:
   * Data kadaluarsa adalah data peristiwa audit tutup buku (*stock opname* Desember) dari Badan Keuangan Daerah. Memaksakannya ke dwibulanan akan melanggar integritas sejarah audit.
   * Data kadaluarsa digunakan pada skala tahunan di **BAB I & BAB V** untuk membuktikan bahwa tanpa peramalan, terjadi tren lonjakan pemborosan obat kadaluarsa: **2023 (Rp 9,14 Jt) $\rightarrow$ 2024 (Rp 11,24 Jt) $\rightarrow$ 2025 (Rp 22,97 Jt) dengan Total Rp 43,35 Juta**.

---

### 🎯 RINGKASAN PEMBAGIAN BICARA SAAT PRESENTASI KELOMPOK
Agar terlihat kompak dan terstruktur di hadapan dosen pengampu:

* **Balqis Manisa (Ketua)**: Membuka presentasi, menjelaskan profil mitra Puskesmas Teluk Sasah, narasumber Dr. Kurniawan, serta perumusan masalah riil di **BAB I**.
* **Jian Safitri & Nabil**: Menjelaskan **BAB II (Data Understanding)** dan **BAB III (Pre-processing)**, termasuk strategi penyelarasan 22 periode dwibulanan, korelasi penyakit vs obat, dan anomali Fero Sulfat vs Parasetamol.
* **Annisa Darmawan (Icha)**: Menjelaskan **BAB IV (Model Moving Average)**, formulasi matematis, dan simulasi perhitungan manual langkah-demi-langkah.
* **Yehezkiel Alman**: Menjelaskan **BAB V (Evaluasi MAPE)**, penyebab perbedaan akurasi akibat efek *stockout*, serta rekomendasi **BAB VI (Penerapan Safety Stock 95%)** dan penutup.
