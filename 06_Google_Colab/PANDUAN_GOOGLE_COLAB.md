# 📘 PANDUAN PENGGUNAAN GOOGLE COLAB
## **Proyek Penambangan Data Kelompok 23 (UPTD Puskesmas Teluk Sasah)**

---

### 📂 Berkas yang Tersedia di Workspace
1. **Berkas Notebook Colab**:
   * [`Proyek_Penambangan_Data_Puskesmas_Teluk_Sasah.ipynb`](Proyek_Penambangan_Data_Puskesmas_Teluk_Sasah.ipynb) — Notebook Jupyter siap pakai yang telah menggabungkan seluruh logika dari 3 skrip (`1_preprocess_lplpo.py`, `2_eda_visual_analysis.py`, dan `3_forecasting_models.py`).
2. **Berkas Data Mentah Asli** (di dalam folder `Data Puskesmas Teluk Sasah - Kelompok 23/`):
   * `LPLPO TEKSAS 23-26.zip` (29 berkas laporan obat 2023–2026)
   * `DATA PENYAKIT UPTD. PUSKESMAS TELUK SASAH.xlsx` (Data 10 besar diagnosis penyakit)
   * `relplpo2023.zip` (Data rekapitulasi obat kadaluarsa 2023–2025)

---

### 🚀 Cara Menjalankan Notebook di Google Colab (Langkah Demi Langkah)

#### **Langkah 1: Buka Google Colab**
1. Buka browser Anda dan kunjungi tautan: [https://colab.research.google.com/](https://colab.research.google.com/)
2. Masuk menggunakan akun Google Anda (akun kampus/pribadi).

#### **Langkah 2: Upload File Notebook**
1. Pada jendela pop-up awal Colab, pilih tab **Upload** (Unggah).
2. Klik tombol **Browse** (Telusuri), lalu pilih file:
   `Proyek_Penambangan_Data_Puskesmas_Teluk_Sasah.ipynb`
3. Notebook akan terbuka secara interaktif di layar browser Anda.

#### **Langkah 3: Upload Berkas Data Mentah ke Colab**
1. Di bilah sisi paling kiri Google Colab, klik ikon **📁 (Files / Berkas)**.
2. Tunggu beberapa detik hingga folder `/content` muncul.
3. Tarik (*drag and drop*) ketiga berkas data berikut dari komputer Anda langsung ke area file tersebut:
   * `LPLPO TEKSAS 23-26.zip`
   * `DATA PENYAKIT UPTD. PUSKESMAS TELUK SASAH.xlsx`
   * `relplpo2023.zip`
4. Pastikan nama berkas tidak berubah. Notebook telah dilengkapi fitur **auto-detect** yang dapat menemukan berkas secara otomatis di folder manapun di dalam Colab.

#### **Langkah 4: Jalankan Seluruh Notebook**
1. Klik menu bar bagian atas: **Runtime** (atau *Waktu Proses*).
2. Pilih **Run all** (atau *Jalankan Semua*), atau cukup tekan pintasan keyboard: **`Ctrl + F9`**.
3. Seluruh 19 cell akan dieksekusi secara otomatis dari atas ke bawah:
   * **Cell 3**: Instalasi otomatis `openpyxl`, `xlrd`, `scipy`, `seaborn`.
   * **Cell 4**: Pengecekan status keberadaan berkas data.
   * **Cell 5 & 6 (Script 1)**: Ekstraksi 15.813 baris LPLPO dan penyelarasan ke 22 periode dwibulanan kontinu.
   * **Cell 7, 8, 9 (Script 2)**: Menampilkan visualisasi inline grafik Top 15 Obat, Kurva Kasus ISPA (J00) vs Parasetamol/Amoksisilin/Ambroksol, serta bukti empiris kerugian obat kadaluarsa (**Rp 43,35 Juta**) dan frekuensi kekosongan stok (*stockout*).
   * **Cell 10 s/d 13 (Script 3)**: Eksekusi model peramalan Moving Average (SMA-2, SMA-3, WMA-3), tabel evaluasi akurasi galat **MAPE**, tabel simulasi perhitungan manual *step-by-step* (syarat BAB IV & V), dan kurva aktual vs prediksi.
   * **Cell 14**: Tabel rekomendasi kuantitas pengadaan obat ke Dinas Kesehatan periode berikutnya (2026-P5) dilengkapi batas **Safety Stock (95%)**.
   * **Cell 15**: Opsi download file hasil olahan (CSV).

---

### 💡 Poin Kunci untuk Diskusi / Membandingkan (*Compare*) Nanti Malam:

Saat berkumpul jam 7 malam di rumah Balqis dan membandingkan hasil kerjaan Anda dengan hasil yang diperoleh Jian dari Claude:

1. **Keunggulan Penyelarasan Waktu (22 Periode Kontinu)**:
   * Data mentah puskesmas memiliki format tidak seragam (tahun 2024 bulanan, sedangkan 2023, 2025, 2026 dwibulanan).
   * Model kita berhasil menyelesaikan inkonsistensi ini menjadi **22 periode dwibulanan yang kronologis dan kontinu** tanpa ada data yang terbuang.
2. **Kesesuaian dengan RTM Dosen (Pak Tekad & Pak Fadli)**:
   * Menjawab langsung kriteria penilaian BAB I s/d VI.
   * Tidak hanya meramal angka, tetapi membuktikan **masalah riil mitra** dengan angka konkret:
     * Kerugian obat kedaluwarsa riil: **Rp 43,35 Juta** (bukti overstock).
     * Bukti riil *stockout*: Kaptopril kosong di 59% periode, Ambroksol 36% periode, Amlodipin 32% periode.
3. **Analisis Galat MAPE & Korelasi Medis**:
   * Obat penyakit kronis seperti **Metformin 500 mg** terbukti memiliki akurasi **Baik** dengan **MAPE = 15.25%** menggunakan model **Weighted Moving Average (WMA)**, dan berkorelasi kuat dengan kasus diabetes ($r = 0.528, p < 0.05$).
   * Nilai galat pada obat flu dijelaskan secara ilmiah karena pengaruh keterbatasan stok (bukan kesalahan model matematis).
4. **Solusi Nyata Pengadaan**:
   * Dilengkapi formula **Safety Stock ($SS = 1.65 \times \sigma$)** sehingga puskesmas tidak sekadar meramal, tetapi memiliki pedoman jumlah pemesanan aman ke Dinas Kesehatan.
