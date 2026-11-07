# 📑 Abstrak Skripsi Resmi (Bilingual) & Rekomendasi Judul Penelitian
**Proyek:** PRABOTIK — UPTD Puskesmas Teluk Sasah, Kabupaten Bintan

---

## BAGIAN 1: DRAF ABSTRAK SKRIPSI RESMI (BILINGUAL)

### ABSTRAK (Bahasa Indonesia)

Perencanaan logistik farmasi di UPTD Puskesmas Teluk Sasah menghadapi permasalahan ganda berupa kerugian fisik obat kadaluarsa (ED) senilai Rp 43,35 juta dari akumulasi dana APBD dan DAK selama tiga tahun, serta kekosongan (*stockout*) obat esensial saat lonjakan kasus Infeksi Saluran Pernapasan Akut (ISPA). Metode perencanaan konvensional yang hanya mengandalkan pengeluaran mentah Laporan Pemakaian dan Lembar Permintaan Obat (LPLPO) mengalami distorsi permintaan tersensor (*censored demand*) ketika stok habis, sehingga memicu proyeksi bias ke bawah secara berulang. Penelitian ini mengembangkan PRABOTIK, sistem prediksi kebutuhan obat dan analisis morbiditas berarsitektur *Dual-Portal* berbasis Laravel 11 dan SQLite yang memisahkan kendali operasional gudang farmasi dari disposisi anggaran Kepala Puskesmas. Metodologi sistem mengintegrasikan imputasi *Censored Demand*, peramalan *Weighted Moving Average* tiga periode ($\text{WMA-3}$), batas atap *HistGradientBoosting Quantile Regression* ($\alpha=0,95$), indikator momentum *Moving Average Convergence Divergence* (MACD) dwibulanan parameter $(2, 4, 2)$, serta matriks korelasi penyakit SIMPUS berbasis kode ICD-10. Hasil pengujian menunjukkan rekonstruksi *Censored Demand* pada kasus Parasetamol 500 mg berhasil memulihkan rekomendasi pengadaan periode 2026-P5 dari distorsi 3.222 tablet menjadi 7.487 tablet sekaligus mengoreksi sinyal histogram MACD dari $-485,42$ menjadi $+34,58$. Evaluasi *10-Fold Walk-Forward Validation* terhadap 149 obat aktif membuktikan $\text{WMA-3}$ unggul pada 58,7% item obat dengan rata-rata MAPE 14,2% (WAPE 0,595) dan bias positif moderat $+4,3\%$ sebagai penyangga klinis dibandingkan model GBM murni yang berbias negatif ($-9,2\%$), didukung korelasi signifikan morbiditas SIMPUS pada ISPA ($r=0,585$) dan Diabetes Melitus Tipe 2 ($\rho=0,775$). Implementasi PRABOTIK terbukti efektif menjamin ketersediaan obat esensial, mencegah pemborosan anggaran daerah melalui kontrol FEFO, serta menawarkan portabilitas tinggi bagi faskes primer.

**Kata Kunci:** *Censored Demand*, *Weighted Moving Average*, Indikator MACD, Morbiditas SIMPUS, Perencanaan Obat Puskesmas.

---

### ABSTRACT (English Version)

Pharmaceutical supply chain planning at UPTD Puskesmas Teluk Sasah faces a dual challenge: physical financial losses of IDR 43.35 million from expired drugs (ED) accumulated over three years of APBD and DAK funding, alongside critical stockouts of essential medicines during seasonal surges of Acute Respiratory Tract Infections (ISPA). Conventional planning methods relying solely on unadjusted LPLPO consumption records suffer from censored demand distortion during stockout periods, triggering recurring downward forecasting bias. This study develops PRABOTIK, a Dual-Portal drug demand forecasting and morbidity analysis system built on Laravel 11 and SQLite that separates pharmacy warehouse operations from executive budget disposition by the Head of Puskesmas. The methodology integrates Censored Demand imputation, three-period Weighted Moving Average ($\text{WMA-3}$) forecasting, a 95% *HistGradientBoosting Quantile Regression* safety stock ceiling, a bimonthly Moving Average Convergence Divergence (MACD) momentum indicator parameterized at $(2, 4, 2)$, and ICD-10 SIMPUS morbidity correlation matrices. Empirical results demonstrate that Censored Demand reconstruction for Paracetamol 500 mg successfully restores the 2026-P5 procurement allocation from a distorted 3,222 tablets to 7,487 tablets while correcting the MACD histogram signal from $-485.42$ to $+34.58$. A 10-Fold Walk-Forward Validation across 149 active drugs confirms that $\text{WMA-3}$ outperforms competing models on 58.7% of items with a mean MAPE of 14.2% (WAPE 0.595) and a $+4.3\%$ natural safety bias compared to pure GBM ($-9.2\%$ bias), supported by strong SIMPUS morbidity correlations in ISPA ($r=0.585$) and Type 2 Diabetes Mellitus ($\rho=0.775$). PRABOTIK effectively safeguards essential drug availability, prevents regional budget waste, and ensures high portability for primary healthcare facilities.

**Keywords:** *Censored Demand*, *Weighted Moving Average*, MACD Indicator, SIMPUS Morbidity, Pharmaceutical Supply Chain.

---

## BAGIAN 2: REKOMENDASI 3 ALTERNATIF JUDUL SKRIPSI RESMI

### Opsi 1: Fokus Data Mining & Algoritma (Penekanan Metodologi Komputasi)
**Judul Bahasa Indonesia:**
> **Prediksi Kebutuhan Obat dan Analisis Morbiditas Penyakit Menggunakan Metode *Weighted Moving Average* Terkoreksi *Censored Demand* serta Indikator Momentum MACD Dwibulanan (Studi Kasus: UPTD Puskesmas Teluk Sasah)**

**Judul Bahasa Inggris:**
> *Drug Demand Forecasting and Disease Morbidity Analysis Using Censored-Demand Adjusted Weighted Moving Average and Bimonthly MACD Momentum Indicator (Case Study: UPTD Puskesmas Teluk Sasah)*

* **Karakteristik & Keunggulan:** Sangat cocok apabila dosen pembimbing dan penguji berasal dari kelompok keahlian **Data Science, Kecerdasan Buatan, atau Komputasi Statistik**. Judul ini langsung menonjolkan kebaruan algoritmik (*Censored Demand Adjustment* + $\text{WMA-3}$ + adaptasi MACD dwibulanan) sejak halaman sampul.

---

### Opsi 2: Fokus Rekayasa Perangkat Lunak & Arsitektur Sistem (Penekanan Sistem Informasi)
**Judul Bahasa Indonesia:**
> **Rancang Bangun Sistem Informasi Perencanaan Kebutuhan Obat Berarsitektur *Dual-Portal* melalui Integrasi Data LPLPO dan Morbiditas SIMPUS Berbasis *Time-Series Data Mining* pada UPTD Puskesmas Teluk Sasah**

**Judul Bahasa Inggris:**
> *Design and Implementation of a Dual-Portal Drug Procurement Planning Information System Integrating LPLPO and SIMPUS Morbidity Data Based on Time-Series Data Mining at UPTD Puskesmas Teluk Sasah*

* **Karakteristik & Keunggulan:** Sangat cocok apabila fokus penilaian program studi menitikberatkan pada **Rekayasa Perangkat Lunak (Software Engineering), Sistem Informasi, dan Tata Kelola Proses Bisnis**. Judul ini menggarisbawahi inovasi arsitektur *Dual-Portal* (`/farmasi` dan `/kepala`) serta integrasi dua basis data faskes (LPLPO dan SIMPUS).

---

### Opsi 3: Fokus Informatika Kesehatan & Manajemen Rantai Pasok (Penekanan Solusi Domain)
**Judul Bahasa Indonesia:**
> **Pengembangan Sistem Pendukung Keputusan Pengadaan Obat Esensial dan Mitigasi Risiko *Stockout*–*Expired Date* Berbasis Koreksi *Censored Demand* serta Korelasi Epidemiologi di UPTD Puskesmas Teluk Sasah**

**Judul Bahasa Inggris:**
> *Development of a Decision Support System for Essential Drug Procurement and Stockout–Expired Date Risk Mitigation Based on Censored Demand Correction and Epidemiological Correlation at UPTD Puskesmas Teluk Sasah*

* **Karakteristik & Keunggulan:** Sangat kuat untuk dipublikasikan pada jurnal **Health Informatics, Sistem Pendukung Keputusan (SPK), atau hibah inovasi daerah (LPPM/Dinas Kesehatan)**. Judul ini membingkai sistem PRABOTIK sebagai instrumen penyelamatan anggaran daerah (mitigasi risiko *stockout* dan kerugian obat ED) berbasis bukti klinis.
