# 💾 03_Data_Terproses — Dataset Terproses (Intermediate Pipeline)

Folder ini menyimpan seluruh dataset hasil pemrosesan pipeline otomatis dari data mentah
yang digunakan sebagai **sumber data perantara** (*intermediate*) oleh skrip-skrip
analisis lanjutan di `04_Skrip_Pengolahan/`.

> [!NOTE]
> Luaran final yang telah diformat dan didokumentasikan lengkap tersedia di
> `07_Alur_Data_Mentah_ke_Final/` per tahapan.

---

## 📋 Daftar File & Fungsinya

### Sumber Data Utama (Dibuat oleh `1_preprocess_lplpo.py`)
| File | Deskripsi | Baris |
|------|-----------|-------|
| `lplpo_granular_records.csv` | Rekaman transaksi LPLPO granular bersih (15.781 baris, 20 kolom) | 15.781 |
| `lplpo_bimonthly_timeseries.csv` / `.xlsx` | Deret waktu dwibulanan 22 periode per obat | ~15K |
| `lplpo_obat_pivot_pemakaian.csv` / `.xlsx` | Matriks pivot pemakaian 22 periode × 414 obat | 414 |
| `lplpo_29_periode_mentah_as_is.csv` / `.xlsx` | Rekapitulasi 29 berkas LPLPO mentah as-is | 29 |
| `penyakit_clean_records.csv` | 450 rekap penyakit terstandarisasi ICD-10 (45 bulan) | 450 |
| `obat_kadaluarsa_clean.csv` | 138 item obat kadaluarsa tervalidasi (Rp 43.348.626) | 138 |
| `tren_bulanan_2024_obat.csv` | Matriks pemakaian 12 bulan 2024 per obat | ~600 |

### Hasil Analisis & Pemodelan (Dibuat oleh `4_analisis_penyakit_obat_macd.py`)
| File | Deskripsi |
|------|-----------|
| `model_evaluation_metrics.csv` / `.xlsx` | Evaluasi MAPE/MAE/RMSE untuk SMA-2, SMA-3, WMA-3 (150 obat aktif) |
| `macd_obat_analysis.csv` | Nilai MACD, Signal, Histogram, dan klasifikasi momentum 96 obat |
| `korelasi_penyakit_obat_detail.csv` | Koefisien korelasi Pearson & Spearman 4 penyakit × obat terkait |
| `prediksi_penyakit_top4.csv` | Prediksi kasus 4 penyakit terbanyak untuk periode 2026-P5 |
| `MASTER_DATASET_PUSKESMAS_TELUK_SASAH.xlsx` | Master Workbook terpadu 9 Sheets (semua hasil analisis) |

---

## 🔄 Cara Meregenerasi Semua File

```bash
# Langkah 1 — Preprocessing & ekstraksi data mentah
.venv/bin/python 04_Skrip_Pengolahan/1_preprocess_lplpo.py

# Langkah 2 — Pemodelan MA, MACD, korelasi, & prediksi
.venv/bin/python 04_Skrip_Pengolahan/4_analisis_penyakit_obat_macd.py

# Langkah 3 — Susun alur data 7 tahapan (Tahap 01-07)
.venv/bin/python 04_Skrip_Pengolahan/6_bangun_alur_data_mentah_ke_final.py

# Langkah 4 — Perbaikan & pengayaan hasil audit
.venv/bin/python 04_Skrip_Pengolahan/7_perbaikan_audit_claude.py
```
