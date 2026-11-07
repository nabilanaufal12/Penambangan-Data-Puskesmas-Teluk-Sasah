# 🏥 Folder: 02_Data_Mentah_Puskesmas

Folder ini menyimpan seluruh data mentah asli (*primary source data*) yang diperoleh langsung dari instalasi farmasi UPTD Puskesmas Teluk Sasah, Kabupaten Bintan.

---

### 📋 Daftar Berkas & Struktur Folder

1. **`LPLPO TEKSAS 23-26.zip` & `LPLPO TEKSAS 23-26/`**
   * Berisi **29 berkas LPLPO** (Laporan Pemakaian dan Lembar Permintaan Obat) format `.xls` (BIRO KEUANGAN DAERAH / BKN KABUPATEN BINTAN) periode 2023 s/d 2026.
   * **Struktur Periode**:
     * **2023** (7 file): Januari, Februari (bulanan), dilanjutkan format dwibulanan (Maret-April, Mei-Juni, Juli-Agustus, September-Oktober, November-Desember).
     * **2024** (12 file): Format bulanan lengkap (Januari hingga Desember 2024).
     * **2025** (6 file): Format dwibulanan lengkap (P1 hingga P6).
     * **2026** (4 file): Format dwibulanan berjalan (P1 Januari-Februari hingga P4 Juli-Agustus 2026).
   * **Kolom Kunci**: Nama Obat, Satuan, Stok Awal, Penerimaan, Persediaan, Pemakaian, Rusak/Kadaluarsa, Stok Akhir, Permintaan, Pemberian, Keterangan.

2. **`DATA PENYAKIT UPTD. PUSKESMAS TELUK SASAH.xlsx`**
   * Data rekapitulasi 10 besar diagnosis penyakit bulanan periode 2023 hingga Agustus 2026.
   * **Kolom Kunci**: No, Kode Penyakit (ICD-10 seperti `J00` Common Cold, `I10` Hipertensi, `E11` Diabetes Mellitus, `K30` Dispepsia), Nama Penyakit, Jumlah Kasus, Bulan/Tahun.

3. **`relplpo2023.zip` & `relplpo2023/`**
   * Data audit fisik dan Berita Acara Rekapitulasi Obat Kadaluarsa/Rusak tahun 2023, 2024, dan 2025.
   * Berisi rincian item, nomor batch, tanggal expired, jumlah unit kadaluarsa, harga satuan per item, dan total nilai kerugian nominal (Rp).

---

> [!NOTE]
> Seluruh berkas dalam folder ini dipertahankan keasliannya (*immutable raw data*). Segala transformasi, pembersihan noise, dan penyeragaman periode dilakukan secara otomatis melalui kode program di folder [`04_Skrip_Pengolahan`](../04_Skrip_Pengolahan).
