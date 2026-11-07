# 📋 Catatan Metodologi & Limitasi Data — Audit Internal 7 November 2026

> [!IMPORTANT]
> Dokumen ini merangkum temuan audit mendalam yang perlu diungkap secara jujur dalam laporan akademik
> dan presentasi sebagai bentuk integritas ilmiah (*scientific transparency*).

---

## ✅ Yang Sudah Terverifikasi dan Benar

| Item | Nilai | Metode Verifikasi |
|------|-------|-------------------|
| Total baris LPLPO bersih | 15.781 baris | Filter baris noise numerik murni |
| Total kadaluarsa | 138 item | Hapus 6 baris artefak NIP/tanda tangan |
| Total kasus penyakit | 450 baris (12 bln × 10 diagnosis × 3,75 thn) | 12 bulan × 10 baris per tahun (2026: 9 bulan) |
| Total kerugian kadaluarsa | Rp 43.348.626 | Verifikasi manual 3 sheet `lap ED` |
| Data 2026 sampai | Agustus (LPLPO) / September (penyakit) | Pemeriksaan langsung file sumber |
| Parser bulan penyakit | Diperbaiki: April/Sep/Nov (datetime), "Feberuari", spasi ganda | Bug fix v2 di `1_preprocess_lplpo.py` |
| Sheet ganda Mar-Apr 2026 | Parser memilih `lplpo th 2026` (bukan `SELENA APRIL`) | Kode baris 152 di parser |

---

## ⚠️ Limitasi Metodologi yang Harus Disebutkan di Laporan

### 1. Data Penyakit Hanya Top-10 Per Bulan (Data Tersensor)

> [!WARNING]
> **Data penyakit dari UPTD Puskesmas Teluk Sasah hanya mencatat 10 diagnosis teratas per bulan.**
> Penyakit yang sesekali keluar dari peringkat 10 besar **tidak berarti nol kasus** — melainkan data
> terpotong (*censored data*). Dampaknya:
> - J06.9 hanya muncul di 27 dari 45 bulan → bukan tidak ada, tapi keluar Top-10
> - E11 absen di beberapa bulan → kemungkinan di bawah peringkat 10

**Narasi laporan:**
*"Data penyakit merupakan rekapitulasi 10 besar diagnosis terbanyak per bulan yang diperoleh dari sistem
rekam medis SIMPUS Puskesmas Teluk Sasah. Data ini bersifat tersensor (top-censored) sehingga penyakit
yang berada di bawah peringkat ke-10 tidak tercatat pada periode tertentu. Keterbatasan ini tidak
mempengaruhi analisis 4 penyakit utama (ISPA, Hipertensi, Dispepsia, Diabetes) yang konsisten menempati
peringkat 1-4 sepanjang periode pengamatan."*

---

### 2. Parameter MACD — Digunakan sebagai Indikator Deskriptif

> [!NOTE]
> Parameter MACD klasik (12/26/9) dirancang untuk deret waktu bulanan > 36 bulan. Dataset ini memiliki
> **22 periode dwibulanan** (~44 bulan). Oleh karena itu:
> - **Fast EMA = span 2** (≈ 4 bulan)
> - **Slow EMA = span 4** (≈ 8 bulan)
> - **Signal = span 2** (≈ 4 bulan)

**Narasi laporan:**
*"Indikator MACD dalam penelitian ini digunakan sebagai alat analisis momentum deskriptif untuk
mengidentifikasi arah tren konsumsi obat (akselerasi vs deselerasi), bukan sebagai model prediksi
kuantitatif. Parameter disesuaikan dari standar (12/26/9) menjadi (2/4/2) untuk menyesuaikan dengan
panjang deret waktu 22 periode dwibulanan yang tersedia. Interpretasi histogram MACD positif mengindikasikan
momentum peningkatan konsumsi, sedangkan histogram negatif mengindikasikan perlambatan."*

---

### 3. Target Prediksi 2026-P5 (September–Oktober 2026)

> [!NOTE]
> **Status prediksi 2026-P5 adalah semi-validasi:**
> - Data penyakit September 2026 **sudah tersedia** (10 diagnosis) → bisa dibandingkan prediksi vs aktual
>   untuk 1 bulan pertama periode
> - Data LPLPO September–Oktober 2026 **belum tersedia** → prediksi stok obat masih murni proyeksi

**Narasi laporan:**
*"Rekomendasi pengadaan ditujukan untuk Periode 5 tahun 2026 (September–Oktober 2026). Data penyakit
bulan September 2026 telah tersedia sebagai validasi parsial untuk komponen prediksi penyakit.
Data LPLPO periode September–Oktober 2026 belum tersedia pada saat penelitian dilaksanakan,
sehingga prediksi stok obat masih merupakan proyeksi yang dapat divalidasi setelah data aktual tersedia."*

---

### 4. Reduksi CV — Konteks Perbandingan yang Fair

> [!NOTE]
> Perbandingan CV harus dilakukan pada tahun yang sama untuk menghilangkan efek aritmetika penjumlahan.
> File `05_komparasi_cv_fair_2024_bulanan_vs_2bulanan.csv` di Tahap 3 menyajikan perbandingan CV 
> yang adil (Apple-to-Apple) menggunakan data 2024 yang diagregasi ke format dwibulanan.

---

### 5. Label Berkas LPLPO yang Salah (Anomali Data Mentah)

> [!WARNING]
> Dua anomali label ditemukan pada berkas LPLPO mentah (tidak dimodifikasi — immutable):
> - **Berkas `5 MEI 2024.xls`**: Isi berkas bertuliskan "BULAN: APRIL". Parser menggunakan nama berkas
>   sebagai label periode (Mei 2024) sesuai konvensi penamaan berkas, bukan teks di dalam berkas.
> - **Sheet `JULI - AGUSTUS 2026`**: Sheet di-rename "SELENA JUNI" oleh petugas, namun konten adalah
>   data Juli-Agustus 2026. Parser menggunakan sheet pertama (index 0) untuk berkas ini.

---

### 6. Stockout Nyata — Obat dengan Pemakaian > 0 saat Stok Akhir = 0

Lihat detail di: `Tahap_02_Pembersihan_dan_Standardisasi/04_analisis_stockout_nyata_lplpo.csv`

Dari 15.781 baris LPLPO bersih, ditemukan **482 kejadian stockout nyata** (stok akhir = 0 & pemakaian > 0).
Ini adalah bukti kuantitatif bahwa sistem pengadaan saat ini belum optimal — mendukung urgensi penelitian ini.

---

### 7. Sheet LAP ED NKT&PSK — Keputusan Eksklusi

Sheet `LAP ED NKT&PSK` yang isinya identik di tiga file berita acara kadaluarsa merupakan salinan statis
tahun 2023 yang tidak diperbarui untuk 2024 dan 2025. Total nilai sheet ini sebesar **Rp 644.614**.

**Keputusan**: Nilai ini **dieksklusi** dari total Rp 43.348.626 karena:
1. Isi identik di 3 file → tidak dapat dibedakan apakah ini kadaluarsa 2023, 2024, atau 2025
2. Memasukkan satu kali akan bersifat arbitrary (memilih tahun mana?)
3. Nilai ini seharusnya masuk ke total 2023 jika diperbarui dengan benar oleh petugas

*Jika ditambahkan sekali: total menjadi Rp 43.993.240 — selisih 1,5% dari total yang dilaporkan.*
