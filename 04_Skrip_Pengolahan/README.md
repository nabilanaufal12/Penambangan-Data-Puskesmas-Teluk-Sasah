# 🐍 04_Skrip_Pengolahan — Skrip Pipeline Python

Semua skrip pemrosesan data proyek tersimpan di sini, diurutkan berdasarkan alur eksekusi.

---

## 📋 Daftar Skrip (Urutan Eksekusi)

| No | File | Fungsi | Terakhir Diperbarui |
|----|------|--------|---------------------|
| **1** | `1_preprocess_lplpo.py` | Ekstrak & bersihkan 29 file LPLPO + data penyakit + kadaluarsa → `03_Data_Terproses/` | Nov 2026 |
| **2** | `2_eda_visual_analysis.py` | EDA & visualisasi grafik 1–14 → `05_Visualisasi_Grafik/` | Okt 2026 |
| **3** | `3_forecasting_models.py` | Model peramalan & evaluasi MAPE/MAE/RMSE | Okt 2026 |
| **4** | `4_analisis_penyakit_obat_macd.py` | Analisis tren, MACD, korelasi, prediksi 4 penyakit | Nov 2026 |
| **5** | `5_visualisasi_tambahan_saran_dosen.py` | Grafik tambahan saran dosen (grafik 15–20) → `05_Visualisasi_Grafik/` | Nov 2026 |
| **6** | `6_bangun_alur_data_mentah_ke_final.py` | **Pipeline utama**: susun 7 tahapan → `07_Alur_Data_Mentah_ke_Final/` | Nov 2026 |
| **7** | `7_perbaikan_audit_claude.py` | Perbaikan & pengayaan hasil audit: stockout, CV fair, indeks musiman, validasi P5 | Nov 2026 |

### Skrip Dokumen & Laporan
| File | Fungsi |
|------|--------|
| `generate_pd10_docx.py` | Generate PD10 (Materi Klasifikasi) format DOCX |
| `generate_pd11_docx.py` | Generate PD11 (Materi Kluster) format DOCX |
| `generate_pd12_docx.py` | Generate PD12 (Materi Asosiasi) format DOCX |
| `generate_pd13_docx.py` | Generate PD13 (Pengukuran Kinerja) format DOCX |
| `generate_docx_report.py` | Generate Laporan Akhir Kelompok 23 format DOCX |
| `build_colab_notebook.py` | Build notebook Jupyter/Colab dari skrip pipeline |
| `update_markdown_descriptions.py` | Sinkronisasi deskripsi Markdown antar dokumen |

---

## 🚀 Urutan Eksekusi Lengkap (dari nol)

```bash
cd "/home/mizuuu/Documents/Penambangan Data"

.venv/bin/python 04_Skrip_Pengolahan/1_preprocess_lplpo.py
.venv/bin/python 04_Skrip_Pengolahan/4_analisis_penyakit_obat_macd.py
.venv/bin/python 04_Skrip_Pengolahan/5_visualisasi_tambahan_saran_dosen.py
.venv/bin/python 04_Skrip_Pengolahan/6_bangun_alur_data_mentah_ke_final.py
.venv/bin/python 04_Skrip_Pengolahan/7_perbaikan_audit_claude.py
```
| **8** | `8_benchmarking_machine_learning.py` | Benchmarking ML (HistGBM, Ridge, Quantile GBM 95%) vs Naive, SMA, WMA via 10-Fold Walk-Forward | Nov 2026 |
| **9** | `9_koreksi_data_tersensor_parasetamol.py` | Imputasi & koreksi permintaan tersensor (Censored Demand) Parasetamol akibat stockout 2026-P4 | Nov 2026 |
