# 🏥 PRABOTIK — Sistem Prediksi Kebutuhan Obat & Analisis Morbiditas Penyakit
### **UPTD Puskesmas Teluk Sasah, Kabupaten Bintan, Kepulauan Riau**
**Proyek Penambangan Data (INF11121) — Kelompok 23 (Kelas TI24)**  
*Program Studi S1 Teknik Informatika, Universitas Maritim Raja Ali Haji (UMRAH)*

---

## 🌟 Tentang Aplikasi
**PRABOTIK** adalah sistem web logistik farmasi berbasis **Laravel 11 & SQLite** yang mengadopsi antarmuka modern *Healthcare & Medical Analytics* dengan **Panel Navigasi di Sebelah Kiri (Left Sidebar Panel)**.

Sistem ini merealisasikan seluruh arahan penyempurnaan dosen pengampu (**Pak Tekad Matulatan & Pak Muhamad Fadli**):
1. **Tren Waktu Multi-Granularitas**: Komparasi tren mikro 1-bulanan (12 bulan 2024) vs tren makro 2-bulanan kontinu (22 periode dwibulanan).
2. **Algoritma Moving Average & MACD Farmasi**: Evaluasi galat SMA-2, SMA-3, dan WMA-3 pada 150 obat aktif, dipadukan dengan indikator deteksi dini *Moving Average Convergence Divergence (Fast=2, Slow=4, Signal=2)*.
3. **Korelasi Klinis & Morbiditas**: Analisis hubungan antara 4 penyakit utama (ISPA, Hipertensi, Dispepsia, Diabetes) dengan konsumsi obat serta rasio dosis per pasien.
4. **Proteksi Safety Stock 95%**: Pencegahan dini kekosongan stok (*understock*) dan eliminasi pemborosan obat kadaluarsa baru (Rp 43,35 Juta historis).
5. **Koreksi Permintaan Tersensor (*Censored Demand*)**: Rekonstruksi ilmiah atas anomali stok habis Parasetamol 500 mg pada 2026-P4 menjadi rekomendasi aman 7.487 tablet.
6. **Benchmarking Machine Learning**: Evaluasi komparatif 10-Fold Walk-Forward model ML (HistGradientBoosting & Ridge) vs baseline WMA-3.

---

## 🛠️ Persyaratan Sistem
- **PHP** &ge; 8.2 (dengan ekstensi `pdo_sqlite`, `sqlite3`, `curl`, `mbstring`, `xml`)
- **Composer** &ge; 2.0
- Browser modern (Chrome, Edge, Firefox, Safari)

---

## 🚀 Panduan Menjalankan Aplikasi

### 1. Masuk ke Direktori Proyek
```bash
cd prabotik
```

### 2. Instalasi Dependensi (Jika baru clone)
```bash
composer install
```

### 3. Konfigurasi Environment & Database SQLite
```bash
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
```

### 4. Eksekusi Migrasi & Seeding Otomatis Data Riil Puskesmas
```bash
php artisan migrate:fresh --seed
```
*Database SQLite akan otomatis terisi dengan 150 obat aktif, 450 rekam morbiditas ICD-10 (45 bulan), 10 pasangan korelasi klinis, 8 model benchmark ML, dan 138 data obat kadaluarsa.*

### 5. Jalankan Web Server Laravel
```bash
php artisan serve --port=8008
```
Buka peramban (browser) di alamat: **[http://127.0.0.1:8008](http://127.0.0.1:8008)**

---

## 📂 Struktur Modul & Navigasi Kiri (Left Sidebar)
- **Beranda & Profil** (`/`): Dashboard eksekutif bergaya medical healthcare dengan counter KPI, 6 pelayanan poli, profil puskesmas, obat unggulan 2026-P5, tren 4 penyakit, tim pengembang, FAQ, dan mitra resmi.
- **Rekomendasi 2026-P5** (`/rekomendasi-pengadaan`): Tabel rekomendasi alokasi pengadaan resmi ke Dinkes Bintan dengan batas *Safety Stock 95%* dan tombol cetak format LPLPO.
- **Katalog 150 Obat** (`/obat`): Katalog sediaan obat lengkap dengan evaluasi akurasi MAPE, MAE, RMSE model Moving Average serta filter pencarian cepat.
- **Prediksi 4 Morbiditas** (`/prediksi-penyakit`): Analisis deret waktu 45 bulan dan grafik interaktif Chart.js untuk ISPA (J00), Hipertensi (I10), Dispepsia (K30), dan Diabetes (E11).
- **Indikator MACD Farmasi** (`/momentum-macd`): Sistem deteksi dini akselerasi momentum konsumsi (*Bullish / Bearish*).
- **Korelasi Klinis & Dosis** (`/korelasi-morbiditas`): Matriks korelasi Pearson/Spearman dan rasio dosis butir per pasien.
- **Benchmark Machine Learning** (`/benchmarking-ml`): Tabel evaluasi *10-Fold Expanding Window Walk-Forward* HistGB vs WMA-3.
- **Audit Stockout & Kadaluarsa** (`/audit-stok`): Kuantifikasi 482 kejadian kehabisan stok dan 138 item berita acara obat kadaluarsa (Rp 43,35 Jt).
- **Tim Peneliti & Faskes** (`/tentang`): Profil Kelompok 23 TI24, Dosen Pembimbing, dan profil UPTD Puskesmas Teluk Sasah.

---

## 👥 Tim Peneliti (Kelompok 23 - Kelas TI24 UMRAH)
- **Tekad Matulatan, S.Sos., S.Kom., M.Info.Tech.** (Dosen Pembimbing I)
- **Muhamad Fadli, S.Kom., M.Kom.** (Dosen Pembimbing II)
- **Nabil** - NIM: 2401020073 (Ketua / ML Lead)
- **Balqis Manisa** - NIM: 2401020069
- **Jian Safitri Wibowo** - NIM: 2401020056
- **Annisa Darmawan** - NIM: 2401020087
- **Yehezkiel Alman Siambaton** - NIM: 2401020110
