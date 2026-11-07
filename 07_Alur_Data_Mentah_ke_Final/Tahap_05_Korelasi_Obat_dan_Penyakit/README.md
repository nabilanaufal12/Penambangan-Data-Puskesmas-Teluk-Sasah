# 🔗 Tahap 05: Analisis Korelasi Obat dan Penyakit (Saran 3a Pak Tekad)

> **Saran Pak Tekad**:  
> *"Kalau bisa cari korelasi antara obat dan penyakit."*

---

### 💡 Temuan Analisis Korelasi:
1. **Koefisien Korelasi Positif Signifikan ($r > 0.70$)**:
   - **ISPA ($J00$) $\leftrightarrow$ Parasetamol & Gliseril Guaiakolat**: $r = +0.7824$ ($p < 0.001$). Menunjukkan bahwa pemakaian analgesik-antipiretik dan mukolitik bergerak sinkron dengan musim pancaroba ISPA.
   - **Hipertensi ($I10$) $\leftrightarrow$ Amlodipin 5 mg**: $r = +0.8412$ ($p < 0.0001$). Menunjukkan kepatuhan terapi kronis pasien lansia di wilayah kerja Teluk Sasah.
   - **Dispepsia ($K30$) $\leftrightarrow$ Antasida Doen**: $r = +0.7156$ ($p < 0.001$). Terjadi peningkatan konsumsi saat momen pergantian musim dan pola makan libur hari raya.
   - **Diabetes Mellitus ($E11$) $\leftrightarrow$ Metformin 500 mg**: $r = +0.8190$ ($p < 0.0001$). Menunjukkan kestabilan kontrol gula darah pada program Prolanis Puskesmas.
2. **Rasio Dosis per Kasus Pasien**:
   - Memungkinkan peramalan kebutuhan obat secara turunan (*epidemiology-driven procurement*).

---

### 📋 Berkas Dataset:
* **`01_korelasi_penyakit_dan_obat_detail.csv` & `.xlsx`**: Koefisien Pearson $r$, Spearman $\rho$, dan signifikansi statistik.
* **`02_rasio_dosis_konsumsi_per_kasus.csv` & `.xlsx`**: Rata-rata konsumsi butir obat per 1 pasien terdaftar.
* **`03_matriks_pasangan_klinis.csv` & `.xlsx`**: Rujukan tata laksana klinis standar Kemenkes RI & PNPK.
