# 📑 Draf Naskah Bab III: Preprocess / Data Preparation (CRISP-DM Standard)
**Mata Kuliah:** Penambangan Data (INF11121 – 5 SKS)  
**Institusi:** S1 Teknik Informatika, Fakultas Teknik dan Teknologi Kemaritiman, Universitas Maritim Raja Ali Haji (UMRAH)  
**Kelompok 23:** Nabil (Ketua), Balqis Manisa, Jian Safitri Wibowo, Annisa Darmawan, Yehezkiel Alman Siambaton  
**Dosen Pengampu:** Bapak Tekad Matulatan, S.Sos., S.Kom., M.Info.Tech. & Bapak Muhamad Fadli, S.Kom., M.Kom.  

---

# BAB III: PREPROCESS (DATA PREPARATION)

Fase *Data Preparation* (*Preprocess*) dalam metodologi CRISP-DM merupakan tahapan krusial yang menentukan validitas model penambangan data pada UPTD Puskesmas Teluk Sasah. Data administratif fasilitas kesehatan tingkat pertama secara alami memiliki karakteristik semi-terstruktur, heterogen dalam resolusi waktu, serta rentan terhadap distorsi pencatatan akibat kekosongan stok fisik (*stockout*). Bab ini menguraikan enam permasalahan struktural data mentah beserta solusi algoritmik yang diimplementasikan pada *pipeline* prapemrosesan data Kelompok 23.

## III.1 Penyelesaian Masalah 1: Inkonsistensi Periode Waktu Pelaporan (Standardisasi 22 Periode Dwibulanan)

### Identifikasi Masalah Data
Eksplorasi terhadap 29 berkas mentah Laporan Pemakaian dan Lembar Permintaan Obat (LPLPO) serta berkas morbiditas SIMPUS mengungkap ketidaksinkronan resolusi waktu (*temporal misalignment*):
* Data LPLPO tahun 2023 (6 berkas), tahun 2025 (6 berkas), dan tahun 2026 hingga Agustus (4 berkas) dicatat dalam siklus **dwibulanan** (Periode P1 hingga P6).
* Data LPLPO tahun 2024 (12 berkas ditambah 1 berkas rekapitulasi) dicatat dalam siklus **bulanan** terpisah dari Januari hingga Desember.
* Data morbiditas 10 penyakit terbesar SIMPUS direkapitulasi dalam resolusi **bulanan** selama 44 bulan pengamatan.

Apabila deret waktu dengan satuan interval yang berbeda ini digabungkan secara langsung, maka perhitungan *Moving Average* dan indikator momentum MACD akan mengalami distorsi skala frekuensi pada tahun 2024.

### Justifikasi & Solusi Penambangan Data
Untuk menciptakan sumbu waktu yang seragam (*equidistant time-series grid*), diterapkan teknik **Temporal Resampling & Alignment** ke dalam resolusi acuan dwibulanan (*bimonthly*) sepanjang $N = 22$ periode kontinu (2023-P1 hingga 2026-P4). Dalam ilmu manajemen rantai pasok, agregasi temporal wajib membedakan perlakuan matematis antara **variabel aliran (*flow variables*)** dan **variabel posisi (*stock/state variables*)**.

Untuk setiap periode dwibulanan ke-$k$ pada tahun 2024 yang terdiri atas bulan ganjil $m_1 = 2k - 1$ dan bulan genap $m_2 = 2k$ (dengan $k \in \{1, 2, \dots, 6\}$), berlaku formulasi agregasi berikut:

$$P_{\text{terima}, k} = P_{\text{terima}, m_1} + P_{\text{terima}, m_2}$$

$$K_{\text{pakai}, k} = K_{\text{pakai}, m_1} + K_{\text{pakai}, m_2}$$

$$S_{\text{awal}, k} = S_{\text{awal}, m_1}$$

$$S_{\text{akhir}, k} = S_{\text{akhir}, m_2}$$

$$S_{\text{sedia}, k} = S_{\text{awal}, k} + P_{\text{terima}, k}$$

Variabel aliran seperti penerimaan ($P_{\text{terima}}$), pemakaian obat ($K_{\text{pakai}}$), serta jumlah kasus penyakit SIMPUS ($M_{j,k} = M_{j,m_1} + M_{j,m_2}$) dijumlahkan secara kumulatif. Sebaliknya, variabel posisi tidak boleh dijumlahkan agar tidak terjadi penghitungan ganda (*double counting*): stok awal periode dwibulanan mengambil posisi stok awal bulan ganjil ($m_1$), sedangkan stok akhir mengambil posisi stok akhir bulan genap ($m_2$).

**Tabel III.1** Pemetaan Standardisasi 22 Periode Dwibulanan Kontinu (2023–2026)

| Kode Periode | Rentang Kalender | Jumlah Berkas Asal | Resolusi Asal | Metode Penyelarasan |
| --- | --- | --- | --- | --- |
| 2023-P1 s.d. 2023-P6 | Jan 2023 – Des 2023 | 6 Berkas LPLPO | Dwibulanan | Pemetaan langsung |
| 2024-P1 s.d. 2024-P6 | Jan 2024 – Des 2024 | 12 Berkas LPLPO | Bulanan | Agregasi alir & posisi |
| 2025-P1 s.d. 2025-P6 | Jan 2025 – Des 2025 | 6 Berkas LPLPO | Dwibulanan | Pemetaan langsung |
| 2026-P1 s.d. 2026-P4 | Jan 2026 – Agu 2026 | 4 Berkas LPLPO | Dwibulanan | Pemetaan langsung |
| SIMPUS P1 s.d. P22 | Jan 2023 – Agu 2026 | 1 Master SIMPUS | 44 Bulan | Penjumlahan 2 bulanan |

Tabel III.1 memastikan seluruh 22 titik waktu memiliki lebar jendela observasi yang identik (masing-masing 60 hari kalender), sekaligus menjaga konsistensi persamaan identitas logistik $S_{\text{akhir}, k} = S_{\text{awal}, k} + P_{\text{terima}, k} - K_{\text{pakai}, k}$ pada setiap baris transaksi.

---

## III.2 Penyelesaian Masalah 2: Pembersihan Artefak Header, Footer, dan Baris Pengesahan

### Identifikasi Masalah Data
Berkas lembar kerja Excel LPLPO dan Berita Acara Obat Kadaluarsa (ED) dirancang untuk keperluan cetak dokumen birokrasi, sehingga memuat banyak baris non-transaksi di dalam tabel data:
* Baris judul kop surat instansi (`UPTD PUSKESMAS TELUK SASAH`, `DINAS KESEHATAN KABUPATEN BINTAN`).
* Baris pemisah sub-kategori farmakoterapi di tengah tabel (`I. OBAT GENERIK`, `II. TABLET / KAPSUL`, `III. SIRUP / SUSPENSI`, `BMHP`).
* Baris rekapitulasi total (`JUMLAH TOTAL`) dan blok legalitas tanda tangan pejabat di bagian bawah (`Mengetahui, Kepala UPTD Puskesmas Teluk Sasah`, `dr. Kurniawan, M.H.`, beserta baris `NIP. ...`).

### Justifikasi & Solusi Penambangan Data
Pembersihan dilakukan secara terprogram menggunakan *Rule-Based Structural Filtering* pada Python Pandas. Sebuah baris $r_i$ di dalam lembar kerja didefinisikan sebagai entitas transaksi valid jika dan hanya jika atribut kolom nomor urut (`No`) bernilai numerik bulat positif dan kolom `Nama_Obat` bukan merupakan teks kata kunci struktural:

$$\text{Valid}(r_i) \iff \left( r_i[\text{No}] \in \mathbb{Z}^+ \right) \land \left( r_i[\text{Nama\_Obat}] \notin \{\text{Kop}, \text{Kategori}, \text{Subtotal}, \text{Tanda\_Tangan}\} \right)$$

Selain pemfilteran baris, dilakukan pula normalisasi string (*case folding*, penghapusan spasi ganda, dan penyatuan sinonim penulisan seperti `PCT 500 mg` menjadi `Parasetamol 500 mg`) untuk mencegah fragmentasi kode item obat.

**Tabel III.2** Hasil Pembersihan Baris Artefak pada Dokumen Berita Acara Obat ED (2023–2025)

| Tahun Audit ED | Baris Mentah | Baris Artefak Dihapus | Item Murni Tervalidasi | Status Integritas |
| --- | --- | --- | --- | --- |
| Berita Acara 2023 | 42 baris | 2 baris | 40 item obat/alkes | Bersih 100% |
| Berita Acara 2024 | 49 baris | 2 baris | 47 item obat/alkes | Bersih 100% |
| Berita Acara 2025 | 53 baris | 2 baris | 51 item obat/alkes | Bersih 100% |
| **Total Akumulasi** | **144 baris** | **6 baris** | **138 item murni** | **Siap Audit** |

Tabel III.2 menunjukkan bahwa algoritma penyaringan berhasil mengeliminasi tepat 6 baris artefak pengesahan dan kop surat dari 144 baris mentah, menyisakan **138 item obat dan alat kesehatan kadaluarsa murni** yang valid untuk dihitung beban kerugiannya.

---

## III.3 Penyelesaian Masalah 3: Penanganan Nilai Kosong (*Missing Value*) & Domain-Specific Lookup

### Identifikasi Masalah Data
Pada pemeriksaan kelengkapan atribut (*completeness check*) terhadap 138 item obat kadaluarsa tahun 2023–2025, ditemukan nilai kosong (`NaN` / *missing value*) pada kolom `Harga_Satuan` dan `Total_Nilai` di sejumlah item pada dokumen tahun 2024 dan 2025. Dalam dokumen rekapitulasi fisik gudang, petugas terkadang hanya mencatat kuantitas fisik barang yang rusak/kadaluarsa tanpa mencantumkan harga perolehan satuannya.

### Justifikasi & Solusi Penambangan Data
Dalam proyek penambangan data umum, *missing value* numerik sering ditangani menggunakan imputasi nilai tengah (*mean* atau *median*). Namun, dalam domain audit keuangan negara (dana APBD dan DAK), **imputasi rata-rata atau median dilarang secara metodologis** karena harga satuan obat memiliki rentang dispersi yang ekstrem (mulai dari Rp 150,- per tablet hingga puluhan ribu rupiah per vial/alkes). Mengisi harga obat yang kosong dengan nilai rata-rata seluruh obat akan menghasilkan angka kerugian fiktif yang tidak dapat dipertanggungjawabkan secara hukum.

Sebagai solusinya, diterapkan metode **Cross-Table Domain-Specific Lookup** dengan menjadikan tabel Master Harga LPLPO resmi Puskesmas sebagai referensi kunci (*lookup dictionary*):

$$H_{\text{ED}}(i) = \begin{cases} h_{\text{asli}}(i), & \text{jika } h_{\text{asli}}(i) \neq \text{NaN} \\ \text{Lookup}_{\text{Master\_LPLPO}}(\text{Norm}(i)), & \text{jika } h_{\text{asli}}(i) = \text{NaN} \end{cases}$$

$$V_{\text{kerugian}} = \sum_{i=1}^{138} Q_{\text{ED}}(i) \times H_{\text{ED}}(i)$$

Di mana $\text{Norm}(i)$ adalah kunci nama obat terstandarisasi, $Q_{\text{ED}}(i)$ adalah volume fisik obat kadaluarsa pada item ke-$i$, dan $H_{\text{ED}}(i)$ adalah harga perolehan satuan resmi. Resolusi *lookup* ini menghasilkan nilai kerugian finansial yang presisi dan terverifikasi sebesar **Rp 43.348.626,-** (terdiri atas Rp 9.104.210,- pada 2023; Rp 11.319.856,- pada 2024; dan melonjak menjadi Rp 22.924.560,- pada 2025).

---

## III.4 Penyelesaian Masalah 4: Pemisahan Anomali Obat Program Nasional vs Obat Kuratif Poli

### Identifikasi Masalah Data
Pada saat dilakukan pengurutan volume pemakaian kumulatif tertinggi selama 22 periode dwibulanan, ditemukan fenomena statistik yang mencolok: item **Fero Sulfat (Tablet Tambah Darah)** menempati urutan pertama dengan total pengeluaran **255.180 tablet** dan Koefisien Variasi ekstrem sebesar **$CV = 2,20$**, melampaui **Parasetamol 500 mg** yang mencatat total pemakaian **142.715 tablet** ($CV = 0,42$). Apabila anomali ini tidak dianalisis secara kontekstual, model akan salah menyimpulkan bahwa penyakit anemia defisiensi besi memiliki insidensi kunjungan harian yang lebih tinggi dibandingkan demam/ISPA.

### Justifikasi & Solusi Penambangan Data
Dilakukan penelusuran forensik data (*data lineage inspection*) ke dalam berkas sumber `1 JANUARI 2024.xls`. Pada **baris ke-122**, ditemukan lonjakan pengeluaran tunggal sebesar **118.295 tablet** Fero Sulfat dalam satu bulan dengan anotasi kolom keterangan bertuliskan **`KET: GIZI`**.

Temuan ini membuktikan bahwa Fero Sulfat bukan dikonsumsi melalui peresepan kuratif harian di poli rawat jalan, melainkan merupakan **Obat Program Nasional Gizi/KIA** yang didistribusikan secara massal (*bulk dropping*) ke sekolah-sekolah menengah (program pencegahan anemia remaja putri dan pencegahan *stunting*) serta Posyandu pada waktu-waktu kampanye tertentu.

**Tabel III.3** Pemisahan Karakteristik Obat Program Nasional vs Obat Kuratif Poli

| Parameter Pembanding | Fero Sulfat (Obat Program) | Parasetamol 500 mg (Obat Kuratif) |
| --- | --- | --- |
| Total Volume (22 Periode) | 255.180 tablet | 142.715 tablet |
| Koefisien Variasi ($CV$) | 2,20 (Fluktuasi Ekstrem) | 0,42 (Stabil / Terkendali) |
| Anomali Puncak Tunggal | 118.295 tab (`KET: GIZI` Jan 2024) | 6.444 tab (Puncak ISPA 2026-P3) |
| Mekanisme Distribusi | *Push-Distribution* (Drop Massal Gizi) | *Pull-Demand* (Resep Pasien Poli) |
| Perlakuan Pemodelan | Dipisahkan dari peringkat kuratif poli | **Obat Kuratif Utama #1 Puskesmas** |

Sebagaimana dirangkum pada Tabel III.3, pemisahan kategori ini menjaga kemurnian analisis: Fero Sulfat diklasifikasikan ke dalam klaster logistik program kampanye terjadwal, sedangkan **Parasetamol 500 mg** dikukuhkan sebagai obat pelayanan kuratif harian nomor satu yang paling krusial di UPTD Puskesmas Teluk Sasah.

---

## III.5 Penyelesaian Masalah 5: Rekonstruksi Permintaan Tersensor (*Censored Demand Adjustment*) Parasetamol 500 mg Periode 2026-P4

### Identifikasi Masalah Data
Masalah kualitas data paling berbahaya dalam peramalan deret waktu logistik kesehatan adalah *right-censored demand* (permintaan tersensor akibat kehabisan stok). Pada periode **2026-P3 (Mei–Juni 2026)**, terjadi lonjakan kasus ISPA yang menyerap habis seluruh persediaan Parasetamol 500 mg sebanyak **6.444 tablet**, menyisakan stok akhir sebesar **0 tablet**. Memasuki periode **2026-P4 (Juli–Agustus 2026)**, keterlambatan suplai menyebabkan catatan LPLPO merekam:
* $\text{Stok Awal}_{\text{2026-P4}} = 0$
* $\text{Penerimaan}_{\text{2026-P4}} = 0$
* $\text{Pemakaian}_{\text{2026-P4}} = 0$
* $\text{Stok Akhir}_{\text{2026-P4}} = 0$

Secara statistik, angka pemakaian $0$ pada 2026-P4 adalah **nol semu (*artificial zero*)**. Pasien ISPA tetap datang berobat ke Puskesmas (terbukti dari data SIMPUS), namun tidak terlayani dari stok gudang. Apabila angka $0$ ini diumpankan langsung ke model $\text{SMA-2}$, proyeksi periode 2026-P5 akan terpangkas 50% menjadi $\frac{6.444 + 0}{2} = 3.222$ tablet, yang pasti memicu krisis *stockout* berulang pada musim pancaroba September–Oktober.

### Justifikasi & Solusi Penambangan Data
Untuk membedakan secara ketat antara **angka 0 akibat *stockout* (permintaan tersensor)** dengan **angka 0 karena obat memang tidak digunakan**, sistem menerapkan tiga syarat logika simultan (*Three-Point Conditional Gate*):

$$\text{Is\_Censored}(i, t) \iff \underbrace{(S_{\text{awal}, i, t} = 0 \land P_{\text{terima}, i, t} = 0 \land K_{\text{pakai}, i, t} = 0)}_{\text{Syarat 1: Ketiadaan Fisik diperiode } t} \land \underbrace{(K_{\text{pakai}, i, t-1} > 0 \land S_{\text{akhir}, i, t-1} = 0)}_{\text{Syarat 2: Pengurasan Habis di } t-1} \land \underbrace{(M_{j, t} > 0)}_{\text{Syarat 3: Morbiditas Aktif}}$$

Karena Parasetamol 500 mg pada periode 2026-P4 memenuhi ketiga syarat tersebut secara mutlak, dilakukan rekonstruksi nilai permintaan riil ($\hat{y}_{\text{2026-P4}}$) menggunakan rata-rata bergerak lokal dari dua periode aktif terakhir sebelum *stockout* ($y_{\text{2026-P2}} = 5.256$ dan $y_{\text{2026-P3}} = 6.444$):

$$\hat{y}_{\text{2026-P4}}^{\text{imputed}} = \frac{y_{\text{2026-P2}} + y_{\text{2026-P3}}}{2} = \frac{5.256 + 6.444}{2} = 5.850 \text{ tablet}$$

Catatan asli di basis data fisik tetap dipertahankan bernilai $0$ demi kepatuhan audit inventaris, namun lapisan analitik peramalan menggunakan nilai terekonstruksi $5.850$ tablet untuk menghitung proyeksi periode 2026-P5.

**Tabel III.4** Dampak Rekonstruksi *Censored Demand* Parasetamol 500 mg terhadap Proyeksi 2026-P5

| Parameter Evaluasi | Tanpa Koreksi (Unadjusted) | Dengan Koreksi (Imputed) | Selisih Pemulihan |
| --- | --- | --- | --- |
| Nilai Konsumsi 2026-P4 | 0 tablet (Bias Stockout) | 5.850 tablet (Rekonstruksi) | +5.850 tablet |
| Proyeksi Titik SMA-2 (P5) | 3.222 tablet | 6.147 tablet | +2.925 tablet |
| Proyeksi Titik WMA-3 (P5) | 3.024 tablet | 5.949 tablet | +2.925 tablet |
| Rekomendasi P5 (+SS 95%) | 4.562 tablet (Kritis) | **7.487 tablet (Aman)** | **+2.925 tablet** |
| Sinyal Histogram MACD | -485,42 (Crash Semu) | **+34,58 (Rebound Positif)** | **Pulih Akurat** |

Tabel III.4 membuktikan bahwa koreksi permintaan tersensor menyelamatkan Puskesmas dari defisit perencanaan sebesar $2.925$ tablet sekaligus memulihkan arah sinyal indikator momentum MACD dari *crash* palsu menjadi *rebound* positif yang akurat.

---

## III.6 Penyelesaian Masalah 6: Transformasi Matriks Deret Waktu (*Wide-Format Pivoting*) & Analisis Dua Lensa Granularitas

### Identifikasi Masalah Data
Setelah tahap pembersihan dan standardisasi selesai, data transaksi LPLPO tersimpan dalam struktur tabel memanjang (*long-format transactional table*) sebanyak **15.813 baris** observasi. Struktur memanjang ini tidak dapat diproses secara vektorial oleh algoritma *Sliding Window Moving Average*, perhitungan peluruhan eksponensial EMA/MACD, maupun uji validasi silang *Walk-Forward*.

### Justifikasi & Solusi Penambangan Data
Diterapkan operasi transformasi **Wide-Format Matrix Pivoting** ($\mathbf{X} \in \mathbb{R}^{I \times T}$) yang memetakan setiap unik item obat sebagai baris ($I = 414$ total katalog obat/alkes, yang kemudian disaring menjadi **149 item obat aktif** yang memiliki histori transaksi memadai) dan 22 periode dwibulanan sebagai kolom dimensi waktu ($T = 22$):

$$\mathbf{X}_{I \times T} = \text{Pivot}\left(\text{Data}_{\text{Long}}, \text{index}=\text{kode\_obat}, \text{columns}=\text{periode\_id}, \text{values}=\text{pemakaian}\right)$$

Matriks hasil transformasi ini disimpan ke dalam artefak `lplpo_obat_pivot_pemakaian.csv`. Lebih lanjut, menindaklanjuti arahan metodologis dosen pengampu mengenai sensitivitas resolusi waktu, prapemrosesan data dibagi ke dalam **Analisis Dua Lensa Granularitas Temporal** (*Dual Temporal Lens Framework*):

1. **Lensa Mikro 1-Bulanan (*Intra-Year High-Frequency Lens* — 12 Bulan Tahun 2024):** Mempertahankan matriks khusus tahun 2024 dalam resolusi bulanan ($T = 12$ titik bulan kalender) untuk mengamati dinamika fluktuasi jangka pendek di dalam kuartal yang sama, memvalidasi kecepatan serapan stok mingguan/bulanan, serta mendeteksi puncak lonjakan awal penyakit sebelum teredam oleh agregasi dua bulanan.
2. **Lensa Makro 2-Bulanan Kontinu (*Multi-Year Strategic Lens* — 22 Periode 2023–2026):** Menggunakan matriks terstandarisasi 22 periode dwibulanan sebagai landasan utama pelatihan model $\text{WMA-3}$, $\text{SMA-2}$, dan $\text{MACD (2, 4, 2)}$ karena siklus pengajuan anggaran dan distribusi fisik LPLPO dari Dinas Kesehatan Kabupaten Bintan ke Puskesmas Teluk Sasah berlangsung setiap 2 bulan sekali.

**Tabel III.5** Spesifikasi Matriks Keluaran Tahap *Preprocess* (*Data Preparation*)

| Nama Artefak Matriks | Dimensi Matriks | Lensa Temporal | Fungsi pada Tahap Modeling |
| --- | --- | --- | --- |
| `lplpo_pivot_22periode.csv` | $414 \times 22$ | Makro Dwibulanan | Basis data matriks total katalog 2023–2026 |
| `dataset_149_obat_aktif.csv` | $149 \times 22$ | Makro Dwibulanan | Input utama WMA-3, MACD (2,4,2) & 10-Fold CV |
| `lplpo_2024_mikro_12bln.csv` | $149 \times 12$ | Mikro Bulanan | Evaluasi volatilitas bulanan & *sanity check* |
| `simpus_morb_22periode.csv` | $10 \times 22$ | Makro Dwibulanan | Input korelasi Pearson/Spearman & Rasio $R_{i,j}$ |
| `audit_ed_bersih_138.csv` | $138 \times 7$ | Tahunan (2023–2025) | Evaluasi risiko finansial ED & batas FEFO |

Melalui enam tahapan prapemrosesan terstruktur pada Tabel III.5, seluruh anomali administratif, *missing value* harga audit, bias obat program nasional, hingga distorsi *censored demand* telah berhasil dieliminasi, menghasilkan struktur matriks deret waktu berkualitas tinggi yang siap diolah pada tahap **Bab IV (Modeling)**.
