# 🧹 Tahap 02: Pembersihan dan Standardisasi Data (Data Cleaning & Normalization)

Tahap ini memproses data mentah melalui standardisasi nama obat, penyeragaman satuan dosis,
penghapusan baris artefak tanda tangan/NIP/angka header, serta pemetaan kode diagnosis standar WHO (ICD-10).

---

### 📋 Berkas yang Dihasilkan:

1. **`01_lplpo_granular_bersih.csv` & `.xlsx`** — 15.781 catatan transaksi bersih dari 29 file LPLPO.
   - Kolom: `file_sumber`, `tahun`, `periode_bulan_id`, `periode_bimonthly_id`, `kategori`, `nama_obat`,
     `satuan`, `stok_awal`, `penerimaan`, `pemakaian`, `stok_akhir`, `flag_stok_discrepancy`, `flag_program_gizi`.

2. **`02_penyakit_terstandarisasi_icd10.csv` & `.xlsx`** — 450 rekap penyakit bulanan dengan kode ICD-10.
   - Kolom: `tahun`, `bulan`, `bulan_angka`, `periode_bimonthly_id`, `kode_icd`, `nama_penyakit`,
     `klaster_icd`, `pasien_laki`, `pasien_perempuan`, `total_pasien`.

3. **`03_audit_obat_kadaluarsa_bersih.csv` & `.xlsx`** — 138 item kadaluarsa tervalidasi,
   total kerugian **Rp 43.348.626**.
   - Tanggal kadaluarsa dikonversi dari serial Excel ke format `YYYY-MM`.
   - 6 baris artefak (NIP, tanda tangan, nama petugas) telah dihapus.

4. **`04_analisis_stockout_nyata_lplpo.csv` & `.xlsx`** *(Baru — Perbaikan Audit)* —
   Kuantifikasi 292 obat yang mengalami stockout nyata (stok akhir = 0 & pemakaian > 0).
   - Total: **482 kejadian** dari 15.781 baris (3.1% baris).
   - Ini adalah **bukti kuantitatif** utama urgensi penelitian prediksi pengadaan ini.

---

### 🔍 Proses Pembersihan yang Dilakukan:

| Langkah | Sebelum | Sesudah |
|---------|---------|---------|
| Filter baris noise (nama obat = angka) | ~15.813 baris | 15.781 baris |
| Artefak NIP/tanda tangan kadaluarsa | 144 baris | 138 baris |
| Parser bulan penyakit | Bug: 7-9 label/tahun, ada bulan 20 baris | Fix: 12 label/tahun presisi |
| Konversi serial Excel tanggal | `45688.0` | `2025-01` |
| Standardisasi nama obat | 746 nama unik | Kanonik via mapping |
| Klaster ICD-10 | Generik (K→Dispepsia semua) | Spesifik medis |

> [!NOTE]
> Data penyakit hanya merekam **10 diagnosis teratas per bulan** (top-censored). Penyakit yang
> turun dari Top-10 pada bulan tertentu tidak berarti nol kasus — ini adalah keterbatasan
> sistem pencatatan SIMPUS, bukan kesalahan pengolahan data.
