# 📑 Catatan Khusus: Koreksi Permintaan Tersensor (*Censored Demand*) Parasetamol 500 mg

> [!IMPORTANT]
> Dokumen ini menjelaskan koreksi metodologis atas anomali nilai nol pada Parasetamol 500 mg
> di periode **2026-P4 (Juli–Agustus 2026)** untuk presentasi ujian dan laporan tugas akhir.

---

## 🔍 1. Identifikasi Masalah di Data Mentah
Pada berkas LPLPO periode Juli–Agustus 2026 (`2026-P4`), tercatat transaksi untuk **Parasetamol tablet 500 mg** sebagai berikut:
- **Stok Awal**: `0 tablet`
- **Penerimaan Pasokan**: `0 tablet`
- **Pemakaian / Konsumsi**: `0 tablet`
- **Stok Akhir**: `0 tablet`

Kondisi ini terjadi karena pada periode sebelumnya (`2026-P3`), seluruh stok Parasetamol sebanyak **6.444 tablet habis terpakai** sehingga stok akhir menjadi 0. Akibatnya, sepanjang bulan Juli hingga Agustus 2026, **Puskesmas Teluk Sasah mengalami kekosongan stok total (*Total Stockout*)**.

Dalam literatur manajemen inventaris farmasi, nilai pemakaian nol ini disebut sebagai **Permintaan Tersensor (*Censored Demand*)**, di mana kebutuhan pasien yang sebenarnya tidak tercatat karena ketiadaan pasokan fisik.

---

## ⚠️ 2. Dampak Jika Tidak Dikoreksi (*Unadjusted Flaw*)
Jika nilai pemakaian nol tersebut dimasukkan mentah-mentah ke dalam rumus Moving Average:
$$\text{Prediksi SMA-2 (2026-P5)} = \frac{6.444 + 0}{2} = \mathbf{3.222 \text{ tablet}}$$

Rekomendasi alokasi pengadaan menjadi anjlok separuh dari kebutuhan riil bulanan. Pada bulan September–Oktober (musim pancaroba ISPA), Puskesmas dipastikan akan kembali mengalami **krisis kehabisan obat (*stockout*) parah**.

---

## 🛠️ 3. Metodologi Imputasi Ilmiah (*Censored Demand Imputation*)
Sesuai standar operasional farmasi, nilai permintaan pada periode *stockout* diestimasi menggunakan rata-rata konsumsi periode normal sebelum kekosongan:
$$\hat{y}_{\text{2026-P4}} = \frac{y_{\text{P2}} + y_{\text{P3}}}{2} = \frac{5.256 + 6.444}{2} = \mathbf{5.850 \text{ tablet}}$$

### Hasil Rekalkulasi Model untuk Periode 2026-P5:
| Indikator | Sebelum Koreksi (Nol Palsu) | Setelah Koreksi (*Censored Adjusted*) | Dampak Klinis Operasional |
| :--- | :---: | :---: | :--- |
| **Nilai Dasar P4** | $0$ tablet | **$5.850$ tablet** | Mencerminkan kebutuhan riil pasien |
| **Prediksi Titik SMA-2** | $3.222$ tablet | **$6.147$ tablet** | Meningkat $+2.925$ tablet |
| **Prediksi Titik WMA-3** | $3.024$ tablet | **$5.949$ tablet** | Meningkat $+2.925$ tablet |
| **Cadangan Pengaman (*Safety Stock 95%*)** | $1.340$ tablet | **$1.340$ tablet** | Perlindungan lonjakan kasus |
| **Total Rekomendasi Alokasi Pengadaan** | $4.562$ tablet | **$7.487$ tablet** | **Aman dari risiko kekosongan obat** |
| **Sinyal Momentum MACD** | $-485.42$ (Bearish Crash Semu) | **$+34.58$ (Rebound Positif)** | Sesuai dengan tren stabil penyakit ISPA |

---

## 🎯 4. Narasi Siap Pakai untuk Presentasi (Di Hadapan Dosen Penguji)
> *"Berdasarkan audit mendalam terhadap data LPLPO 2026-P4, kami menemukan bahwa pemakaian Parasetamol bernilai nol bukan karena ketiadaan penyakit, melainkan akibat kehabisan stok total (stok awal 0 dan penerimaan 0). Jika data ini dihitung secara naif, proyeksi pengadaan 2026-P5 akan anjlok menjadi 3.222 tablet dan membahayakan keselamatan pasien. Kami menerapkan koreksi permintaan tersensor (Censored Demand Imputation) sebesar 5.850 tablet, sehingga rekomendasi pengadaan yang dihasilkan naik menjadi 7.487 tablet—angka yang sangat rasional dan aman untuk menyambut puncak musim ISPA di Kabupaten Bintan."*
