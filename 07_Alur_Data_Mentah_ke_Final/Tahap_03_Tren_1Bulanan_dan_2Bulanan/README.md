# 📅 Tahap 03: Penyelarasan Tren 1-Bulanan dan 2-Bulanan (Saran 1 Pak Tekad)

> **Saran Pak Tekad**:
> *"Biarkan saja ada data 2 bulanan dan 1 bulanan, kita ingin melihat tren tersebut."*

---

### 💡 Justifikasi Ilmiah & Temuan Kunci:

#### Pola 1-Bulanan (Tahun 2024 — 12 Titik Data)
Menangkap fluktuasi granular bulanan: lonjakan antibiotik pasca-musim hujan, pola penerimaan logistik
bulanan. Rentan distorsi siklus administratif (pengiriman faktur tertunda atau pencatatan ganda lintas-bulan).

#### Pola 2-Bulanan (22 Periode Kontinu 2023–2026)
Menstabilkan deret waktu dengan menggabungkan 2 bulan → mengurangi noise faktur tanpa menghilangkan
tren musiman penyakit.

#### Perbandingan CV yang Fair (Apple-to-Apple — data 2024 yang sama):
- **CV rata-rata Bulanan 2024**: **74.6%**
- **CV rata-rata Dwibulanan 2024** (12 bulan diagregasi → 6 periode): **51.9%**
- **Reduksi volatilitas nyata**: 22.7 poin persentase

> [!NOTE]
> Reduksi CV sebagian merupakan efek aritmetika (menjumlahkan 2 bulan otomatis menurunkan variansi).
> Namun hasil perbandingan Apple-to-Apple di atas tetap menunjukkan bahwa format dwibulanan menghasilkan
> deret waktu yang lebih stabil untuk pemodelan MA.

---

### 📋 Berkas Dataset:

| File | Deskripsi |
|------|-----------|
| `00_lplpo_29_periode_mentah_as_is.csv/.xlsx` | Rekapitulasi pemakaian per berkas LPLPO asli (29 periode mentah *as-is*) |
| `01_tren_1bulanan_2024_obat.csv/.xlsx` | Matriks konsumsi 12 bulan 2024: mean, std dev, CV bulanan |
| `02_tren_2bulanan_2023_2026_kontinu.csv/.xlsx` | Deret waktu 22 periode dwibulanan kontinu |
| `03_lplpo_obat_pivot_matriks.csv/.xlsx` | Matriks pivot obat × 22 periode |
| `04_komparasi_tren_1bulanan_vs_2bulanan.csv/.xlsx` | Komparasi 50 obat teratas (lintas tahun) |
| `05_komparasi_cv_fair_2024_bulanan_vs_2bulanan.csv/.xlsx` | **Baru**: Perbandingan CV Apple-to-Apple tahun 2024 |
