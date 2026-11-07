# 🔮 Tahap 06: Prediksi Penyakit dan Pengadaan Stok Obat (Saran 3b & 3c Pak Tekad)

> **Saran Pak Tekad**:
> *"Predik pengadaan stok obat, Predik penyakit paling sering (minimal 4), Predik obat dan penyakit."*

---

### 💡 Rangkuman Hasil Peramalan:

#### 1. Prediksi 4 Penyakit Terbanyak (Periode 2026-P5 / September–Oktober 2026)

| Penyakit | Proyeksi Kasus | Model Terbaik | MAPE |
|----------|---------------|---------------|------|
| ISPA (J00) | 752 kasus | WMA-3 | 8.6% |
| Hipertensi (I10) | 438 kasus | SMA-2 | 6.4% |
| Dispepsia (K30) | 286 kasus | WMA-3 | 9.8% |
| Diabetes (E11) | 204 kasus | SMA-3 | 7.2% |

> [!NOTE]
> Data penyakit September 2026 **sudah tersedia** dan digunakan sebagai validasi parsial
> (1 bulan pertama dari 2 bulan periode P5). Data Oktober 2026 belum tersedia.

#### 2. Rekomendasi Pengadaan Stok Obat 2026-P5 (Termasuk Safety Stock 95%)

| Obat | Prediksi Titik | Safety Stock (Z=1.645) | Total Rekomendasi | Catatan Khusus |
|------|---------------|------------------------|-------------------|----------------|
| Parasetamol 500 mg | 6.147 tablet | 1.340 tablet | **7.487 tablet** | ⭐ *Disesuaikan dari Permintaan Tersensor (Stockout P4 = 5.850)* |
| Amlodipin 5 mg | 5.820 tablet | 980 tablet | **6.800 tablet** | Stabil / Terapi Kronis |
| Metformin 500 mg | 3.650 tablet | 650 tablet | **4.300 tablet** | Stabil / Prolanis |
| Antasida Doen | 2.450 tablet | 480 tablet | **2.930 tablet** | Fast-Moving Musiman |

---

### 📋 Berkas Dataset:

| File | Deskripsi |
|------|-----------|
| `01_prediksi_4_penyakit_terbanyak.csv/.xlsx` | Prediksi kasus + evaluasi model 4 penyakit |
| `02_prediksi_stok_obat_berbasis_penyakit.csv/.xlsx` | Proyeksi obat via rasio dosis × kasus |
| `03_peramalan_stok_obat_komprehensif.csv/.xlsx` | Peramalan 150 obat aktif + safety stock |
| `04_simulasi_hitung_manual_step_by_step.csv/.xlsx` | Pembuktian matematis langkah demi langkah |
| `05_indeks_musiman_4_penyakit_utama.csv/.xlsx` | **Baru**: Indeks musiman bulanan 4 penyakit (2023-2025) |
| `06_validasi_prediksi_vs_aktual_2026_P5.csv/.xlsx` | **Baru**: Perbandingan prediksi vs aktual Sep 2026 |

---

### 📊 Indeks Musiman Penyakit (2023–2025)

> [!TIP]
> Indeks musiman > 1.0 menandakan bulan tersebut di atas rata-rata. Gunakan ini untuk
> menentukan periode pengadaan prioritas:

| Penyakit | Bulan Puncak | Indeks | Bulan Lembah | Indeks |
|----------|-------------|--------|--------------|--------|
| ISPA (J00) | November | ~1.60× | April | ~0.65× |
| Hipertensi (I10) | Oktober-Nov | ~1.20× | Februari | ~0.80× |
| Dispepsia (K30) | Juli-Agustus | ~1.25× | Januari | ~0.75× |
| Diabetes (E11) | Oktober-Des | ~1.15× | Maret | ~0.85× |

**Implikasi**: Periode P5 (Sep–Okt) dan P6 (Nov–Des) adalah periode paling kritis untuk
pengadaan obat ISPA dan Hipertensi.
