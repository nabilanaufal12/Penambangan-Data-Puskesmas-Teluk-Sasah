#!/usr/bin/env python3
"""
04_Skrip_Pengolahan/update_markdown_descriptions.py

Menyisipkan gambar dan deskripsi penjelasan komprehensif di bawah setiap tabel dan gambar
pada naskah Laporan Akhir Markdown (LAPORAN_AKHIR_PENAMBANGAN_DATA_KELOMPOK_23.md).
"""

import os

md_path = os.path.join(os.path.dirname(os.path.dirname(os.path.abspath(__file__))),
                       '01_Dokumen_Akademik', 'LAPORAN_AKHIR_PENAMBANGAN_DATA_KELOMPOK_23.md')

with open(md_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Update Tabel 4.1 Deskripsi
t4_old = """   $$F_{23} = \\frac{(3 \\times 4.475) + (2 \\times 3.370) + (1 \\times 3.370)}{6} = \\frac{13.425 + 6.740 + 3.370}{6} = \\frac{23.535}{6} = 3.922,5\\text{ butir}$$

### IV.5 Formulasi Stok Pengaman"""

t4_new = """   $$F_{23} = \\frac{(3 \\times 4.475) + (2 \\times 3.370) + (1 \\times 3.370)}{6} = \\frac{13.425 + 6.740 + 3.370}{6} = \\frac{23.535}{6} = 3.922,5\\text{ butir}$$

> **Deskripsi Penjelasan Simulasi Matematis (Tabel 4.1 & 4.2)**: Simulasi di atas mendemonstrasikan secara transparan langkah komputasi matematis Moving Average pada Parasetamol tablet 500 mg dan Metformin 500 mg. Terlihat bahwa pada periode normal, model WMA-3 menghasilkan galat persentase mutlak sangat kecil (3,76% sampai 6,03%), membuktikan akurasi pembobotan pada tren terkini. Namun pada periode 2026-P4, terjadi pencatatan pemakaian 0 akibat anomali stok habis di puskesmas, yang secara otomatis menurunkan nilai ramalan murni periode 2026-P5 menjadi 3.222 butir. Oleh karena itu, penambahan batas Safety Stock pada sub-bab IV.5 menjadi syarat mutlak agar jumlah pesanan tidak terlalu rendah dan menyebabkan krisis obat.

### IV.5 Formulasi Stok Pengaman"""
if t4_old in content:
    content = content.replace(t4_old, t4_new)

# 2. Update Tabel 5.1 & Gambar 5.5
t51_old = """| **Fero Sulfat tab 300 mg (Gizi)**| 452,58% | **404,36%** | 442,19% | **SMA (k=3)** | Drop Massal Sekolah |

### V.3 Analisis Pengaruh Fluktuasi"""

t51_new = """| **Fero Sulfat tab 300 mg (Gizi)**| 452,58% | **404,36%** | 442,19% | **SMA (k=3)** | Drop Massal Sekolah |

> **Deskripsi Penjelasan Tabel 5.1**: Tabel 5.1 membandingkan performa akurasi ketiga model peramalan berdasarkan metrik MAPE. Hasil pengujian menunjukkan bahwa model Weighted Moving Average (WMA-3) unggul pada mayoritas obat konsumsi rutin, ditandai dengan capaian MAPE 15,25% pada Metformin dan 17,35% pada Amoksisilin (kategori Baik menurut Lewis, 1982). Sementara itu, model SMA-2 memberikan respon terbaik pada Parasetamol (MAPE 36,42%), dan SMA-3 unggul pada Kalsium Laktat (MAPE 30,94%). Tingginya nilai galat pada Amlodipin dan Fero Sulfat tidak mencerminkan kegagalan pemodelan, melainkan akibat distorsi data lapangan (stockout dan drop program gizi sekolah).

![Gambar 5.5](../05_Visualisasi_Grafik/13_perbandingan_mape_model.png)
*Gambar 5.5: Diagram Batang Perbandingan Nilai Galat MAPE Antar-Model pada 10 Obat Utama*

> **Deskripsi Penjelasan Gambar 5.5**: Gambar 5.5 menyajikan visualisasi diagram batang komparasi nilai galat MAPE antara SMA-2 (merah), SMA-3 (biru), dan WMA-3 (hijau). Terlihat jelas keunggulan konsisten WMA-3 dalam menekan galat pada kelompok obat kronis dan antibiotik. Grafik ini memberikan konfirmasi visual bahwa pemberian bobot bertingkat pada periode terbaru berhasil meningkatkan sensitivitas peramalan terhadap tren kebutuhan puskesmas.

### V.3 Analisis Pengaruh Fluktuasi"""
if t51_old in content:
    content = content.replace(t51_old, t51_new)

# 3. Update Tabel 5.3 (Bukti Stockout)
t53_old = """Ketika stok akhir = 0, puskesmas tidak dapat melayani resep sehingga angka pemakaian tercatat **0 butir**, padahal permintaan pasien di poli tetap tinggi. Saat kiriman dinas tiba, konsumsi melonjak secara semu (*spike*). Pola data yang terdistorsi oleh ketiadaan stok inilah yang mendongkrak galat MAPE.

### V.5 Visualisasi Kurva Aktual vs Prediksi Obat Esensial"""

t53_new = """Ketika stok akhir = 0, puskesmas tidak dapat melayani resep sehingga angka pemakaian tercatat **0 butir**, padahal permintaan pasien di poli tetap tinggi. Saat kiriman dinas tiba, konsumsi melonjak secara semu (*spike*). Pola data yang terdistorsi oleh ketiadaan stok inilah yang mendongkrak galat MAPE.

> **Deskripsi Penjelasan Tabel 5.3**: Tabel 5.3 membuktikan secara kronologis anomali rantai pasok pada Amlodipin 10 mg dan Ambroksol 30 mg. Tercatat bahwa Amlodipin mengalami kekosongan stok akhir di 7 periode (bahkan kosong total 2 bulan penuh di 2026-P2), sementara Ambroksol mengalami kekosongan stok di 8 periode berturut-turut pada akhir 2023. Fenomena ini membuktikan bahwa lonjakan MAPE pada kedua obat tersebut merupakan representasi riil dari masalah understock di puskesmas, bukan kesalahan matematis model.

### V.5 Visualisasi Kurva Aktual vs Prediksi Obat Esensial"""
if t53_old in content:
    content = content.replace(t53_old, t53_new)

# 4. Embed Gambar 5.1 s/d 5.4 di V.5
v5_old = """### V.5 Visualisasi Kurva Aktual vs Prediksi Obat Esensial
Kurva perbandingan antara data historis aktual dengan hasil prediksi model memperlihatkan bahwa:
* Pada **Metformin 500 mg** ([`visualizations/10_forecast_metformin_actual_vs_pred.png`](file:///home/mizuuu/Documents/Penambangan%20Data/05_Visualisasi_Grafik/10_forecast_metformin_actual_vs_pred.png)), kurva WMA-3 mampu mengikuti tren kenaikan konsumsi pasien diabetes secara mulus dengan deviasi yang sangat tipis.
* Pada **Amoksisilin 500 mg** ([`visualizations/12_forecast_amoksisilin_actual_vs_pred.png`](file:///home/mizuuu/Documents/Penambangan%20Data/05_Visualisasi_Grafik/12_forecast_amoksisilin_actual_vs_pred.png)), model WMA-3 berhasil meredam fluktuasi acak musiman dengan presisi tinggi.
* Pada **Parasetamol 500 mg** ([`visualizations/09_forecast_parasetamol_actual_vs_pred.png`](file:///home/mizuuu/Documents/Penambangan%20Data/05_Visualisasi_Grafik/09_forecast_parasetamol_actual_vs_pred.png)), model SMA-2 memberikan respon paling cepat terhadap pergeseran kasus demam musiman.

### V.6 Rekomendasi Alokasi Pengadaan Obat Periode 2026-P5"""

v5_new = """### V.5 Visualisasi Kurva Aktual vs Prediksi Obat Esensial
Berikut disajikan kurva perbandingan antara data historis pemakaian aktual dengan hasil peramalan model untuk obat-obat esensial puskesmas beserta deskripsi pembahasannya:

![Gambar 5.1](../05_Visualisasi_Grafik/09_forecast_parasetamol_actual_vs_pred.png)
*Gambar 5.1: Kurva Aktual vs Prediksi Pemakaian Parasetamol 500 mg*

> **Deskripsi Penjelasan Gambar 5.1**: Gambar 5.1 menampilkan kurva konsumsi aktual Parasetamol 500 mg (garis hitam tebal) yang dibandingkan dengan hasil peramalan SMA-2, SMA-3, dan WMA-3. Model SMA-2 (garis merah putus-putus) terbukti paling adaptif dan mampu bermanuver cepat mengikuti fluktuasi kasus demam musiman, menjadikannya pilihan model peramalan terbaik untuk obat antipiretik ini.

![Gambar 5.2](../05_Visualisasi_Grafik/10_forecast_metformin_actual_vs_pred.png)
*Gambar 5.2: Kurva Aktual vs Prediksi Pemakaian Metformin 500 mg (WMA-3 MAPE 15.25%)*

> **Deskripsi Penjelasan Gambar 5.2**: Gambar 5.2 memperlihatkan keselarasan kurva yang sangat presisi antara data aktual Metformin 500 mg dengan model peramalan WMA-3 (garis hijau). Deviasi yang sangat kecil sepanjang deret waktu membuktikan bahwa stabilitas konsumsi pasien diabetes dapat diprediksi dengan tingkat akurasi tinggi menggunakan pembobotan linier.

![Gambar 5.3](../05_Visualisasi_Grafik/11_forecast_amlodipin_actual_vs_pred.png)
*Gambar 5.3: Kurva Aktual vs Prediksi Pemakaian Amlodipin 10 mg*

> **Deskripsi Penjelasan Gambar 5.3**: Gambar 5.3 memperlihatkan bukti empiris dampak stockout pada kurva Amlodipin 10 mg. Terlihat beberapa jurang penurunan tajam hingga titik nol (seperti pada 2026-P2) yang langsung diikuti oleh lonjakan ekstrem akibat rapel pasokan. Visualisasi ini menegaskan bahwa galat peramalan pada obat antihipertensi dipicu oleh instabilitas pasokan gudang dinas.

![Gambar 5.4](../05_Visualisasi_Grafik/12_forecast_amoksisilin_actual_vs_pred.png)
*Gambar 5.4: Kurva Aktual vs Prediksi Pemakaian Amoksisilin 500 mg (WMA-3 MAPE 17.35%)*

> **Deskripsi Penjelasan Gambar 5.4**: Gambar 5.4 menggambarkan kurva peramalan Amoksisilin kaplet 500 mg. Model WMA-3 berhasil meratakan noise konsumsi musiman dan menghasilkan estimasi kebutuhan yang sangat stabil di kisaran 1.700 – 2.400 butir per periode dwibulanan.

### V.6 Rekomendasi Alokasi Pengadaan Obat Periode 2026-P5"""
if v5_old in content:
    content = content.replace(v5_old, v5_new)

# 5. Update Tabel 5.4 & Gambar 5.6 di V.6
t54_old = """| **Vitamin B-Kompleks tablet** | WMA-3 | 0 butir | 3.464 butir | 4.500 butir | **0 butir** *(Stok Aman)* |

---

# BAB VI: PENERAPAN"""

t54_new = """| **Vitamin B-Kompleks tablet** | WMA-3 | 0 butir | 3.464 butir | 4.500 butir | **0 butir** *(Stok Aman)* |

> **Deskripsi Penjelasan Tabel 5.4**: Tabel 5.4 menyajikan keputusan manajerial pengadaan obat siap eksekusi untuk periode September–Oktober 2026 (2026-P5). Formula pengadaan berhasil mengintegrasikan peramalan konsumsi dengan stok pengaman. Pada Parasetamol 500 mg, karena stok akhir tercatat 0 butir, rekomendasi pesanan diajukan penuh sebesar 7.060 butir (3.222 butir ramalan + 3.838 butir safety stock). Sebaliknya, pada Amoksisilin 500 mg dan Vitamin B-Kompleks, karena sisa stok fisik di gudang masih melampaui batas kebutuhan aman (masing-masing 3.010 butir dan 4.500 butir), sistem merekomendasikan pemesanan 0 butir. Logika eliminasi pemesanan berlebih ini secara langsung mencegah timbulnya kerugian obat kadaluarsa baru di puskesmas.

![Gambar 5.6](../05_Visualisasi_Grafik/14_rekomendasi_pengadaan_safety_stock.png)
*Gambar 5.6: Visualisasi Alokasi Rekomendasi Pengadaan dan Batas Safety Stock Periode 2026-P5*

> **Deskripsi Penjelasan Gambar 5.6**: Gambar 5.6 menyajikan diagram batang bertumpuk (stacked bar chart) yang memperlihatkan komposisi proporsional rekomendasi pengadaan. Batang biru merepresentasikan peramalan kebutuhan riil, sedangkan batang oranye merepresentasikan buffer Safety Stock 95%. Grafik ini memberikan panduan visual yang intuitif bagi pengelola obat puskesmas untuk memahami struktur permintaan logistik sebelum dikirimkan ke Dinas Kesehatan.

---

# BAB VI: PENERAPAN"""
if t54_old in content:
    content = content.replace(t54_old, t54_new)

with open(md_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Sukses memperbarui LAPORAN_AKHIR_PENAMBANGAN_DATA_KELOMPOK_23.md dengan gambar dan deskripsi lengkap!")
