# 💊 PRABOTIK (Sistem Prediksi dan Analisis Kebutuhan Obat)
**UPTD Puskesmas Teluk Sasah, Kecamatan Seri Kuala Lobam, Kabupaten Bintan, Kepulauan Riau**

Aplikasi web sistem pendukung keputusan pengadaan dan peramalan kebutuhan stok obat berbasis **Penambangan Data (*Data Mining*)**, analisis tren penyakit musiman (*ICD-10*), serta indikator momentum teknis (*MACD*) dan cadangan pengaman (*Safety Stock 95%*).

Merupakan bagian dari penelitian ilmiah:
> **"Prediksi Kebutuhan Stok Obat dan Analisis Tren Penyakit Musiman Berdasarkan Pola Pemakaian Obat pada Puskesmas Teluk Sasah di Kabupaten Bintan"**

---

## 🚀 Panduan Menjalankan Aplikasi

Aplikasi dibangun menggunakan **Python Flask** yang ringan, mandiri, dan responsif.

### 1. Prasyarat Sistem
- Python >= 3.10
- Paket utama: `flask`, `pandas`, `numpy`, `openpyxl`

Jika belum terpasang:
```bash
pip install flask pandas numpy openpyxl
```

### 2. Cara Menjalankan
Buka terminal / PowerShell di direktori proyek, lalu jalankan salah satu perintah berikut:

```bash
# Opsi 1: Dari root folder
python run_prabotik.py

# Opsi 2: Dari dalam subfolder proyek
cd Penambangan-Data-Puskesmas-Teluk-Sasah-main
python app.py
```

Buka peramban (*browser*) pada alamat:
👉 **`http://127.0.0.1:5000`**

---

## 🧭 5 Menu Utama PRABOTIK

1. **📊 Dashboard:**
   - Ringkasan KPI: 15.781 transaksi LPLPO bersih (737 sediaan aktif), 31.125 kunjungan pasien (ICD-10), kerugian obat kedaluwarsa Rp 43.348.626 (138 item fisik), dan 482 kejadian kehabisan stok (*stockout* nyata).
   - Grafik interaktif tren pemakaian obat fast-moving dan tren bulanan penyakit utama.
   - Peringatan dini (*early warning alert*) lonjakan musim ISPA di kuartal akhir.

2. **💾 Data:**
   - **LPLPO Granular Bersih:** 15.781 baris (22 dwibulan) lengkap dengan status stok, penerimaan, pemakaian, dan flag gizi.
   - **Rekapitulasi Penyakit ICD-10:** 450 baris observasi bulanan terstandarisasi kode WHO (klaster sistem pernapasan, sirkulasi, pencernaan, endokrin).
   - **Audit Obat Kedaluwarsa:** 138 baris berita acara pemusnahan resmi 2023–2025.
   - Pencarian cerdas, filter tahun, dan ekspor instan ke format `.csv`.

3. **📈 Analisis:**
   - **Eksplorasi Data (EDA):** Distribusi kategori logistik dan bukti kuantitatif stockout nyata.
   - **Prediksi Moving Average:** Komparasi dinamis SMA-2, SMA-3, dan WMA-3 per obat dengan evaluasi akurasi MAPE, MAE, dan RMSE.
   - **Tren Musiman Penyakit:** Analisis indeks musiman 12 bulan (Januari s/d Desember), membuktikan lonjakan ekstrem kasus ISPA di bulan **November (indeks 1,72×)**.
   - **Korelasi Farmakoterapi:** Hubungan preskripsi simultan antara diagnosis penyakit dan obat terkait (ISPA ↔ Parasetamol/Amoksisilin; DM Tipe 2 ↔ Metformin; Hipertensi ↔ Amlodipin).
   - **Benchmarking Machine Learning:** Komparasi 10-Fold Walk-Forward Validation membuktikan Moving Average dengan bias positif aman (+3% s/d +4%) lebih tepat dibanding ML bias negatif (-9,2%) yang berisiko memicu krisis stok.

4. **📋 Rekomendasi:**
   - Tabel Perencanaan Kebutuhan Pengadaan Obat Periode 2026-P5 (September–Oktober 2026).
   - Membedakan dengan tegas antara **Prediksi Kebutuhan Konsumsi** dengan **Rekomendasi Total Pengadaan**:
     $$\text{Rekomendasi Pengadaan} = \text{Prediksi Pemakaian} + \text{Safety Stock 95\%} - \text{Sisa Stok}$$
   - Status momentum teknis MACD (Bullish, Rebound pasca-kosong, Bearish) dan kategori prioritas pengadaan (Vital, Buffer, Monitoring).

5. **📄 Laporan:**
   - Ringkasan eksekutif formal dengan kop instansi UPTD Puskesmas Teluk Sasah.
   - Siap cetak (*print-friendly* layout tanpa elemen navigasi) atau disimpan sebagai PDF.
   - Fitur ekspor tabel rekomendasi pengadaan ke format CSV.

---

## 👥 2 Jenis Peran Pengguna (Role Switcher)

Pengguna dapat beralih peran dengan 1 klik pada navbar:
- **Admin / Pengelola Farmasi:** Hak akses teknis penuh untuk audit stok, analisis variabilitas obat, metrik galat, dan parameter peramalan.
- **Kepala Puskesmas (Pimpinan):** Tampilan eksekutif terfokus pada kebijakan alokasi pengadaan, pencegahan kerugian obat kedaluwarsa, mitigasi musim penyakit, dan lembar pengesahan disposisi logistik.

---

## 📁 Struktur Berkas Aplikasi

```
Penambangan-Data-Puskesmas-Teluk-Sasah-main/
├── app.py                      # Server Flask utama & routing 5 menu
├── data_loader.py              # Engine komputasi & loader dataset hasil cleaning
├── templates/                  # Template UI Jinja2
│   ├── base.html               # Layout induk responsif (Navbar, Role Switcher, Footer)
│   ├── dashboard.html          # Menu 1: Dashboard KPI & Grafik Tren
│   ├── data.html               # Menu 2: Tabel LPLPO, Penyakit ICD-10, Kedaluwarsa
│   ├── analisis.html           # Menu 3: EDA, MA Forecast, Indeks Musiman, Korelasi
│   ├── rekomendasi.html        # Menu 4: Perencanaan Pengadaan 2026-P5 & Safety Stock
│   └── laporan.html            # Menu 5: Dokumen Eksekutif Resmi & Cetak PDF
├── static/
│   ├── css/style.css           # Desain modern dominan biru, putih, aksen hijau
│   ├── js/main.js              # Script interaktif pencarian, tab, dan ekspor CSV
│   └── img/                    # Aset logo instansi
└── test_prabotik_routes.py     # Script pengujian validasi 100% rute
```

