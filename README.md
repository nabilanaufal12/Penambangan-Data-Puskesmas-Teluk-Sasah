# 🏥 Proyek Penambangan Data — Kelompok 23 (INF11121)
## Prediksi Kebutuhan Stok Obat & Analisis Tren Penyakit Musiman
### UPTD Puskesmas Teluk Sasah, Kabupaten Bintan, Kepulauan Riau

**Mata Kuliah**: Penambangan Data (INF11121) — Semester Ganjil 2026/2027  
**Dosen**: Tekad Matulatan, S.Sos., S.Kom., M.Info.Tech. & Muhamad Fadli, S.Kom., M.Kom.  
**Kelompok 23**: Nabil (2401020073), Balqis (2401020069), Jian (2401020056), Annisa (2401020087), Yehezkiel (2401020110)

---

## 📂 Struktur Direktori Proyek

```text
Penambangan Data/
├── 01_Dokumen_Akademik/          # Laporan akhir, PD10–PD13, proposal, panduan
├── 02_Data_Mentah_Puskesmas/     # Data primer asli LPLPO, penyakit, kadaluarsa (immutable)
├── 03_Data_Terproses/            # Dataset intermediate hasil pipeline preprocessing
├── 04_Skrip_Pengolahan/          # Semua skrip Python pipeline (1–7)
├── 05_Visualisasi_Grafik/        # 20 grafik analisis (PNG 300 DPI, line curve smooth)
├── 06_Google_Colab/              # Notebook Colab & panduan eksekusi cloud
├── 07_Alur_Data_Mentah_ke_Final/ # ✅ DELIVERABLE UTAMA: 7 tahapan pipeline lengkap
│   ├── Tahap_01_Data_Mentah_Primer/
│   ├── Tahap_02_Pembersihan_dan_Standardisasi/
│   ├── Tahap_03_Tren_1Bulanan_dan_2Bulanan/
│   ├── Tahap_04_Pemodelan_MA_dan_Indikator_MACD/
│   ├── Tahap_05_Korelasi_Obat_dan_Penyakit/
│   ├── Tahap_06_Prediksi_Penyakit_dan_Stok_Obat/
│   └── Tahap_07_Output_Final_dan_Rekomendasi_Pengadaan/
├── 07_Alur_Data_Mentah_ke_Final.zip  # Arsip siap kirim (3.6 MB)
├── Alur_Data_Mentah_ke_Final         # Shortcut → 07_Alur_Data_Mentah_ke_Final/
├── app.py                            # Dashboard interaktif Streamlit
├── logo_umrah.png                    # Logo UMRAH (digunakan app.py & laporan)
└── README.md                         # Dokumen ini
```

---

## 🎯 Angka-Angka Kunci Proyek

| Indikator | Nilai |
|-----------|-------|
| Total Berkas LPLPO Mentah | 29 berkas (2023–2026) |
| Total Transaksi Stok Bersih | **15.781 baris** |
| Total Rekap Penyakit | **450 baris** (45 bulan × 10 diagnosis) |
| Total Obat Kadaluarsa | **138 item** |
| Kerugian Kadaluarsa | **Rp 43.348.626** |
| Obat Aktif Dimodelkan MA | **150 obat** |
| Periode Dwibulanan | **22 periode** (Jan 2023 – Agu 2026) |
| Stockout Nyata Terdeteksi | **482 kejadian** (292 obat) |
| Total Pasien Tercatat | **31.125 pasien** |

---

## ⭐ 3 Saran Pak Tekad — Status Implementasi

| Saran | Implementasi | Folder |
|-------|-------------|--------|
| **Saran 1**: Analisis tren 1 bulanan vs 2 bulanan | ✅ Selesai | `Tahap_03` |
| **Saran 2**: Algoritma Moving Average & MACD | ✅ Selesai | `Tahap_04` |
| **Saran 3a**: Korelasi obat dan penyakit | ✅ Selesai | `Tahap_05` |
| **Saran 3b**: Prediksi 4 penyakit terbanyak | ✅ Selesai | `Tahap_06` |
| **Saran 3c**: Prediksi pengadaan stok obat | ✅ Selesai | `Tahap_06` |

---

## 🚀 Cara Menjalankan

```bash
# Pastikan virtual environment aktif
source .venv/bin/activate

# Jalankan dashboard Streamlit
.venv/bin/streamlit run app.py

# Regenerasi seluruh pipeline dari nol
.venv/bin/python 04_Skrip_Pengolahan/1_preprocess_lplpo.py
.venv/bin/python 04_Skrip_Pengolahan/4_analisis_penyakit_obat_macd.py
.venv/bin/python 04_Skrip_Pengolahan/6_bangun_alur_data_mentah_ke_final.py
.venv/bin/python 04_Skrip_Pengolahan/7_perbaikan_audit_claude.py
```
