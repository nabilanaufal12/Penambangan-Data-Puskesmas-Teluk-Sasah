# 📈 Tahap 04: Pemodelan Moving Average dan Indikator MACD (Saran 2 Pak Tekad)

> **Saran Pak Tekad**:
> *"Algoritma MA sudah bagus atau MADC/MACD."*

---

### 💡 Konsep dan Formulasi:

#### Moving Average — Model Peramalan Utama

| Model | Formulasi | Keunggulan |
|-------|-----------|-----------|
| **SMA-2** | $\hat{y}_{t+1} = \frac{y_t + y_{t-1}}{2}$ | Responsif terhadap perubahan mendadak |
| **SMA-3** | $\hat{y}_{t+1} = \frac{y_t + y_{t-1} + y_{t-2}}{3}$ | Lebih halus, kurangi noise |
| **WMA-3** | $\hat{y}_{t+1} = \frac{3y_t + 2y_{t-1} + y_{t-2}}{6}$ | Bobot lebih besar pada data terkini |

**Hasil Evaluasi**: WMA-3 unggul pada 58.7% obat dengan rata-rata MAPE **14.2%** (kategori *Good Forecast*).

#### Indikator MACD — Konfirmasi Arah Tren (Deskriptif)

> [!IMPORTANT]
> MACD dalam penelitian ini digunakan sebagai **alat analisis momentum deskriptif**, bukan model prediksi
> kuantitatif. Parameter disesuaikan untuk deret 22 periode dwibulanan:
>
> - **Fast EMA = span 2** (≈ 4 bulan kalender)
> - **Slow EMA = span 4** (≈ 8 bulan kalender)
> - **Signal Line = EMA span 2 dari MACD**
>
> Parameter klasik (12/26/9) tidak digunakan karena membutuhkan minimal 52 titik data,
> sedangkan dataset ini memiliki 22 periode (~44 bulan).

**Interpretasi Histogram MACD**:
- **Positif & Meningkat** (📈 Bullish Momentum): Laju konsumsi akselerasi → sinyal awal potensi stockout
- **Positif & Menurun** (📊 Bullish Melemah): Konsumsi masih tinggi namun mulai stabil
- **Negatif & Menurun** (📉 Bearish): Perlambatan konsumsi → ruang overstocking menyempit
- **Negatif & Membalik** (🔄 Rebound): Pemulihan tren → perlu monitoring pengadaan

---

### 📋 Berkas Dataset:

| File | Deskripsi |
|------|-----------|
| `01_evaluasi_model_moving_average.csv/.xlsx` | Metrik MAPE, MAE, RMSE untuk 150 obat aktif |
| `02_analisis_indikator_macd_obat.csv/.xlsx` | Nilai MACD, Signal, Histogram, klasifikasi momentum |
| `03_ringkasan_sinyal_ma_dan_macd.csv/.xlsx` | Proyeksi MA + konfirmasi arah tren MACD |
