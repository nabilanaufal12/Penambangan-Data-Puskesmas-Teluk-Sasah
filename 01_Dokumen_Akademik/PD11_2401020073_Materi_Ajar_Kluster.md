# TUGAS MENULIS INDIVIDU MINGGU 11: TEKNIK KLUSTER (CLUSTERING ANALYSIS)
**Mata Kuliah:** Penambangan Data (INF11121) — Semester Ganjil 2026/2027  
**Program Studi:** S1 Teknik Informatika, Fakultas Teknik dan Teknologi Kemaritiman  
**Universitas Maritim Raja Ali Haji (UMRAH)**  
**Dosen Pengampu:** Tekad Matulatan, S.T., M.Kom. & Muhamad Fadli, S.T., M.Kom.  
**Identitas Mahasiswa:** Nabil (NIM: 2401020073)  
**Kode Dokumen Pengumpulan:** `PD11_2401020073.pdf` / `PD11_2401020073.docx`  
**Tautan Pengumpulan RTM:** [Google Form Submission Sub-CPMK0416](https://forms.gle/nHWei7YjRDCkkCVC9)

---

```
========================================================================================
Cover Halaman Depan Resmi (Sesuai Rencana Tugas Mahasiswa / RTM):
Penambangan Data - Minggu 11 - 2401020073 - Nabil
========================================================================================
```

---

## DAFTAR ISI
1. [BAB I: PENGANTAR DAN PARADIGMA UNSUPERVISED LEARNING DALAM DATA MINING](#bab-i-pengantar-dan-paradigma-unsupervised-learning-dalam-data-mining)
   - 1.1 Definisi Formal dan Esensi Analisis Kluster
   - 1.2 Taksonomi Metode Kluster: Partisional, Hierarkis, Kepadatan, dan Kisi
   - 1.3 Perbedaan Mendasar Kluster (Unsupervised) vs Klasifikasi (Supervised)
   - 1.4 Prinsip Homogenitas Internal dan Separabilitas Eksternal
2. [BAB II: GEOMETRI RUANG METRIK DAN FUNGSI JARAK (NORMA L1, L2, L3/LP)](#bab-ii-geometri-ruang-metrik-dan-fungsi-jarak-norma-l1-l2-l3lp)
   - 2.1 Konsep Ruang Metrik dan Aksioma Pengukuran Jarak
   - 2.2 Metrik Jarak L1 (Manhattan Distance / City-Block Metric)
   - 2.3 Metrik Jarak L2 (Euclidean Distance / Straight-Line Metric)
   - 2.4 Metrik Jarak L3 dan Lp General (Minkowski Distance)
   - 2.5 Jarak Chebyshev ($L_\infty$), Mahalanobis Distance, Cosine Similarity, dan Jaccard Similarity
   - 2.6 Pengaruh Skala Fitur dan Urgensi Standarisasi Data ($Z$-Score vs Min-Max)
3. [BAB III: KELUARGA ALGORITMA PARTITIONAL CLUSTERING (K-MEANS & K-MEDOIDS)](#bab-iii-keluarga-algoritma-partitional-clustering-k-means--k-medoids)
   - 3.1 Algoritma K-Means (Lloyd-Forgy Algorithm): Inisialisasi, Penugasan, dan Pembaruan Centroid
   - 3.2 Formulasi Fungsi Objektif: Within-Cluster Sum of Squares (WCSS) / SSE
   - 3.3 Keterbatasan K-Means: Sensitivitas Centroid Awal (*Local Minima*), Outlier, dan Bentuk Non-Sferis
   - 3.4 Varian K-Means++ (Probabilistic Seeding Initialization)
   - 3.5 Algoritma K-Medoids (PAM - Partitioning Around Medoids): Kekebalan Terhadap Derau dan Outlier
4. [BAB IV: KELUARGA ALGORITMA HIERARCHICAL CLUSTERING](#bab-iv-keluarga-algoritma-hierarchical-clustering)
   - 4.1 Konsep Struktur Hierarki dan Representasi Grafis Dendrogram
   - 4.2 Agglomerative Hierarchical Clustering (Bottom-Up) vs Divisive (Top-Down)
   - 4.3 Kriteria Penautan Antar-Kluster (Linkage Metrics):
     - Single Linkage (Jarak Minimum & Efek Chaining)
     - Complete Linkage (Jarak Maksimum & Kompak Sferis)
     - Average Linkage (UPGMA - Rata-rata Jarak Pasangan)
     - Centroid Linkage (UPGMC)
     - Ward's Minimum Variance Method (Minimisasi Pertambahan Varians SSE)
   - 4.4 Penentuan Jumlah Kluster Melalui Pemotongan Horizontal (*Horizontal Cut*) pada Dendrogram
5. [BAB V: ALGORITMA KLUSTER BERBASIS KEPADATAN (DENSITY-BASED CLUSTERING: DBSCAN)](#bab-v-algoritma-kluster-berbasis-kepadatan-density-based-clustering-dbscan)
   - 5.1 Keterbatasan Model Partisional dan Hierarki dalam Menemukan Kluster Berbentuk Arbitrer
   - 5.2 Konsep Kepadatan DBSCAN: Epsilon Neighborhood ($\epsilon$) dan Minimum Points ($MinPts$)
   - 5.3 Taksonomi Titik Data: Core Points, Border Points, dan Noise/Outlier
   - 5.4 Konsep Density-Reachability dan Density-Connectivity
   - 5.5 Keunggulan dan Sensitivitas Hyperparameter DBSCAN
6. [BAB VI: EVALUASI KUALITAS DAN PENENTUAN JUMLAH KLUSTER OPTIMAL](#bab-vi-evaluasi-kualitas-dan-penentuan-jumlah-kluster-optimal)
   - 6.1 Validasi Kluster Internal vs Eksternal
   - 6.2 Metode Siku (*The Elbow Method*) Berbasis Evaluasi WCSS/SSE
   - 6.3 Analisis Silhouette Coefficient ($a(i), b(i), s(i)$)
   - 6.4 Davies-Bouldin Index (DBI) — Rasio Sebaran Internal terhadap Separasi Sentroid
   - 6.5 Calinski-Harabasz Index (Variance Ratio Criterion)
7. [BAB VII: SIMULASI NUMERIK LANGKAH DEMI LANGKAH (MANUAL CALCULATION TRACE)](#bab-vii-simulasi-numerik-langkah-demi-langkah-manual-calculation-trace)
   - 7.1 Definisi Dataset Kasus Riil (8 Sampel 2 Dimensi)
   - 7.2 Simulasi Manual Lengkap Algoritma K-Means ($K=2$): Matriks Jarak L1 & L2, Update Centroid, dan Konvergensi WCSS
   - 7.3 Simulasi Manual Lengkap Agglomerative Hierarchical Clustering: Matriks Jarak Awal, Proksimitas Single Linkage, Complete Linkage, dan Konstruksi Dendrogram
   - 7.4 Simulasi Manual Perhitungan Nilai Silhouette Score Sampel Data
8. [BAB VIII: ANALISIS KOMPARATIF KOMPLEKSITAS BIG-O DAN KARAKTERISTIK ALGORITMA](#bab-viii-analisis-komparatif-kompleksitas-big-o-dan-karakteristik-algoritma)
   - 8.1 Tabel Komparasi Big-O: Kompleksitas Waktu Latih, Inferensi, dan Memori
   - 8.2 Matriks Kelebihan, Kelemahan, Toleransi Outlier, dan Skenario Penerapan Industri
9. [BAB IX: IMPLEMENTASI PROGRAM LENGKAP DENGAN PYTHON DAN SCIKIT-LEARN](#bab-ix-implementasi-program-lengkap-dengan-python-dan-scikit-learn)
   - 9.1 Konstruksi Dataset dan Pra-Pemrosesan
   - 9.2 Implementasi Model Komparatif (K-Means, K-Means++, Hierarchical Ward, DBSCAN)
   - 9.3 Evaluasi Metrik Elbow Curve dan Visualisasi Silhouette Plot
10. [BAB X: KESIMPULAN DAN REKOMENDASI](#bab-x-kesimpulan-dan-rekomendasi)
11. [DAFTAR PUSTAKA](#daftar-pustaka)

---

# BAB I: PENGANTAR DAN PARADIGMA UNSUPERVISED LEARNING DALAM DATA MINING

## 1.1 Definisi Formal dan Esensi Analisis Kluster
Dalam ranah penambangan data (*data mining*) dan sains komputasi modern, **Analisis Kluster (*Cluster Analysis*)** menempati posisi fundamental dalam paradigma pembelajaran tanpa pengawasan (*unsupervised learning*). Berbeda dengan *supervised learning* yang bergantung pada keberadaan variabel target berlabel ($y$), analisis kluster beroperasi pada dataset tanpa anotasi label kelas apriori.

Secara formal matematis, diberikan sebuah himpunan objek data tanpa label:
$$\mathcal{D} = \{\mathbf{x}_1, \mathbf{x}_2, \dots, \mathbf{x}_N\}$$
di mana setiap objek $\mathbf{x}_i \in \mathbb{R}^d$ adalah sebuah vektor fitur numerik $d$-dimensi:
$$\mathbf{x}_i = (x_{i1}, x_{i2}, \dots, x_{id})^T$$

Tujuan utama komputasional dari algoritma pengelompokan (*clustering*) adalah mempartisi $\mathcal{D}$ menjadi $K$ buah subset himpunan bagian yang saling lepas (*disjoint clusters*) $\mathcal{C} = \{C_1, C_2, \dots, C_K\}$ sedemikian rupa sehingga memenuhi kondisi matematis:
1. $\bigcup_{k=1}^K C_k = \mathcal{D}$ (seluruh data teralokasi ke dalam ruang kluster).
2. $C_i \cap C_j = \emptyset, \quad \forall i \ne j$ (tidak ada interseksi antar-kluster pada kluster keras / *hard clustering*).
3. $C_k \ne \emptyset, \quad \forall k \in \{1, \dots, K\}$ (tidak ada kluster kosong).

## 1.2 Taksonomi Metode Kluster
Berdasarkan filosofi geometri dan mekanisme pengelompokannya, algoritma kluster diklasifikasikan ke dalam 4 paradigma utama:
1. **Partitional Clustering (Pemartisian Langsung):** Membagi dataset ke dalam sejumlah $K$ partisi tetap secara simultan melalui optimasi fungsi objektif iteratif. Contoh utama: *K-Means*, *K-Medoids (PAM)*, *K-Modes*.
2. **Hierarchical Clustering (Pengelompokan Bertingkat):** Membangun dekomposisi struktur pohon bertingkat (*dendrogram*). Dibagi menjadi dua pendekatan:
   - *Agglomerative (Bottom-Up):* Setiap objek dimulai sebagai kluster mandiri, lalu digabungkan bertahap berdasarkan fungsi penautan (*linkage*).
   - *Divisive (Top-Down):* Seluruh objek dimulai dalam satu kluster raksasa, lalu dipecah bertahap menjadi kluster-kluster kecil.
3. **Density-Based Clustering (Berbasis Kepadatan):** Membentuk kluster berdasarkan kerapatan spasial titik data dalam ruang metrik, mampu menemukan bentuk arbitrer non-linear dan memisahkan derau (*noise*). Contoh utama: *DBSCAN (Density-Based Spatial Clustering of Applications with Noise)* dan *OPTICS*.
4. **Grid-Based Clustering (Berbasis Kisi Ruang):** Mengkuantisasi ruang objek menjadi struktur kisi berhingga sel (*finite grid cells*) untuk komputasi cepat berskala masif. Contoh utama: *STING* dan *CLIQUE*.

## 1.3 Perbedaan Mendasar Kluster vs Klasifikasi
Bagi mahasiswa Teknik Informatika Semester 5, pemahaman batas epistemologis kedua teknik ini sangat esensial:

| Aspek Komparasi | Teknik Klasifikasi (*Supervised Learning*) | Teknik Kluster (*Unsupervised Learning*) |
|---|---|---|
| **Variabel Sasaran ($y$)** | Tersedia label kelas diskrit apriori ($y \in \{c_1, \dots, c_K\}$). | Tidak ada label target (absen variabel $y$). |
| **Fungsi Objektif** | Meminimalkan kesalahan klasifikasi (*generalization loss* $\mathcal{L}(\hat{y}, y)$). | Memaksimalkan kemiripan intra-kluster dan minimalkan kemiripan inter-kluster. |
| **Batas Keputusan** | Hyperplane atau batas pemisah antar-kelas yang dipandu oleh data latih. | Partisi alami berbasis topologi jarak metrik atau kepadatan densitas data. |
| **Metrik Evaluasi** | Confusion Matrix, Accuracy, Precision, Recall, F1-Score, ROC-AUC. | Silhouette Coefficient, Davies-Bouldin Index, Calinski-Harabasz, WCSS / Elbow. |
| **Skenario Aplikasi** | Diagnosis penyakit jantung, filter spam email, deteksi fraud transaksi. | Segmentasi profil nasabah, kluster wilayah sebaran epidemiologi, pengelompokan pola obat. |

## 1.4 Prinsip Homogenitas Internal dan Separabilitas Eksternal
Keberhasilan algoritma kluster diukur dari dua prinsip aksioma statistik yang saling melengkapi:
1. **High Intra-cluster Similarity (Homogenitas Internal Tinggi):** Jarak atau ketidaksamaan antara pasangan objek yang berada dalam satu kluster yang sama ($C_k$) harus sekecil mungkin:
   $$\min \sum_{\mathbf{x}_i, \mathbf{x}_j \in C_k} \text{dist}(\mathbf{x}_i, \mathbf{x}_j)$$
2. **Low Inter-cluster Similarity (Separabilitas Eksternal Tinggi):** Jarak antara kluster yang berbeda ($C_k$ dan $C_m$, $k \ne m$) harus sebesar mungkin:
   $$\max \text{dist}(C_k, C_m)$$

---

# BAB II: GEOMETRI RUANG METRIK DAN FUNGSI JARAK (NORMA L1, L2, L3/LP)

Sesuai silabus resmi RTM & RPS Minggu ke-11 mata kuliah Penambangan Data, penguasaan teknik pengukuran jarak normatif **L1, L2, dan L3** merupakan prasyarat mutlak dalam analisis kluster.

## 2.1 Konsep Ruang Metrik dan Aksioma Jarak
Misalkan $\mathcal{X}$ adalah ruang fitur numerik. Sebuah fungsi real $d: \mathcal{X} \times \mathcal{X} \to \mathbb{R}$ sah disebut sebagai **metrik jarak (*metric distance*)** jika dan hanya jika memenuhi 4 aksioma metrik formal berikut untuk setiap $\mathbf{x}, \mathbf{y}, \mathbf{z} \in \mathcal{X}$:
1. **Non-negativitas (*Non-negativity*):** $d(\mathbf{x}, \mathbf{y}) \ge 0$.
2. **Identitas Titik yang Tak Terbedakan (*Identity of Indiscernibles*):** $d(\mathbf{x}, \mathbf{y}) = 0 \iff \mathbf{x} = \mathbf{y}$.
3. **Simetri (*Symmetry*):** $d(\mathbf{x}, \mathbf{y}) = d(\mathbf{y}, \mathbf{x})$.
4. **Ketaksamaan Segitiga (*Triangle Inequality*):** $d(\mathbf{x}, \mathbf{z}) \le d(\mathbf{x}, \mathbf{y}) + d(\mathbf{y}, \mathbf{z})$.

## 2.2 Metrik Jarak L1 (Manhattan Distance / City-Block Metric)
Jarak L1 mengukur panjang lintasan grid ortogonal antara dua titik vektor pada sumbu kartesius sejajar. Diilhami oleh navigasi jalanan berpetak di kota Manhattan, New York:
$$d_{L1}(\mathbf{x}, \mathbf{y}) = \|\mathbf{x} - \mathbf{y}\|_1 = \sum_{j=1}^d |x_j - y_j|$$

Karakteristik Komputasional:
- Tidak melibatkan operasi perpangkatan dan akar kuadrat, sehingga sangat efisien secara komputasi CPU ($O(d)$ ringan).
- Lebih robust (kebal) terhadap kehadiran pencilan (*outliers*) ekstrem dibandingkan jarak Euclidean, karena tidak mengkuadratkan selisih nilai fitur.

## 2.3 Metrik Jarak L2 (Euclidean Distance)
Jarak L2 merepresentasikan panjang garis lurus terpendek secara geometris antara dua titik dalam ruang Euclidean berdimensi $d$:
$$d_{L2}(\mathbf{x}, \mathbf{y}) = \|\mathbf{x} - \mathbf{y}\|_2 = \sqrt{\sum_{j=1}^d (x_j - y_j)^2}$$

Karakteristik Komputasional:
- Merupakan jarak standar baku dalam algoritma K-Means, Agglomerative Ward, dan DBSCAN.
- Sangat sensitif terhadap outlier: adanya satu atribut dengan selisih besar akan didominasi akibat operasi pengkuadratan $(x_j - y_j)^2$.

## 2.4 Metrik Jarak L3 dan Lp General (Minkowski Distance)
Norma Minkowski adalah generalisasi formal berparameter $p \ge 1$ yang mencakup L1, L2, dan L3 sebagai kasus khususnya:
$$d_{Lp}(\mathbf{x}, \mathbf{y}) = \|\mathbf{x} - \mathbf{y}\|_p = \left( \sum_{j=1}^d |x_j - y_j|^p \right)^{\frac{1}{p}}$$

Khusus untuk **Teknik L3 ($p = 3$)**:
$$d_{L3}(\mathbf{x}, \mathbf{y}) = \left( \sum_{j=1}^d |x_j - y_j|^3 \right)^{\frac{1}{3}}$$
Pada norma L3, pembobotan selisih ekstrem diberikan penekanan pangkat tiga kubik sebelum ditarik akar pangkat tiga, memberikan sensitivitas asimetris yang berguna dalam domain deteksi anomali sinyal multi-aksial.

## 2.5 Metrik Jarak Lainnya dalam Penambangan Data
1. **Jarak Chebyshev ($L_\infty$ Metric):** Batas limit Minkowski saat $p \to \infty$, mengukur selisih koordinat terbesar:
   $$d_\infty(\mathbf{x}, \mathbf{y}) = \max_{j=1}^d |x_j - y_j|$$
2. **Jarak Mahalanobis:** Menghitung jarak statistik terbobot matriks kovarians $\mathbf{\Sigma}$, mengatasi korelasi antar-fitur dan varians skala yang berbeda:
   $$d_M(\mathbf{x}, \mathbf{y}) = \sqrt{(\mathbf{x} - \mathbf{y})^T \mathbf{\Sigma}^{-1} (\mathbf{x} - \mathbf{y})}$$
3. **Cosine Similarity (Kemiripan Sudut):** Mengabaikan magnitudo panjang vektor dan hanya mengevaluasi arah orientasi sudut $\theta$ (umum pada data teks/NLP):
   $$\text{Sim}_{\cos}(\mathbf{x}, \mathbf{y}) = \frac{\mathbf{x} \cdot \mathbf{y}}{\|\mathbf{x}\|_2 \|\mathbf{y}\|_2} = \frac{\sum x_j y_j}{\sqrt{\sum x_j^2} \sqrt{\sum y_j^2}}$$
   Jarak Cosine didefinisikan sebagai $d_{\cos} = 1 - \text{Sim}_{\cos}$.
4. **Jaccard Distance:** Diterapkan untuk himpunan atau vektor biner kategorikal:
   $$d_J(A, B) = 1 - \frac{|A \cap B|}{|A \cup B|}$$

## 2.6 Urgensi Standarisasi Data
Karena metrik jarak L1, L2, dan L3 bergantung pada skala numerik absolut fitur, fitur dengan rentang nilai besar (misalnya *Pendapatan* Rp 10.000.000) akan mendominasi secara mutlak fitur dengan rentang kecil (misalnya *Jumlah Kunjungan Puskesmas* 1–10). Oleh karena itu, standarisasi fitur merupakan tahapan wajib:
- **Z-Score Standardization (StandardScaler):** Menghasilkan rata-rata $\mu = 0$ dan varians $\sigma^2 = 1$:
  $$z_j = \frac{x_j - \mu_j}{\sigma_j}$$
- **Min-Max Normalization (MinMaxScaler):** Memetakan rentang ke domain $[0, 1]$:
  $$x'_{j} = \frac{x_j - \min(x_j)}{\max(x_j) - \min(x_j)}$$

---

# BAB III: KELUARGA ALGORITMA PARTITIONAL CLUSTERING (K-MEANS & K-MEDOIDS)

## 3.1 Algoritma K-Means (Lloyd-Forgy Algorithm)
Algoritma K-Means (Stuart Lloyd, 1957; J. MacQueen, 1967) adalah algoritma partisional paling populer yang mengelompokkan data ke dalam $K$ buah kluster melalui siklus iteratif dua tahap: *Assignment Step* dan *Update Step*.

Prosedur Eksekusi Algoritma K-Means:
1. **Inisialisasi:** Tentukan jumlah kluster $K$. Pilih secara acak $K$ titik awal dari dataset sebagai titik pusat massa (*centroids*) awal $\{\mathbf{\mu}_1^{(0)}, \mathbf{\mu}_2^{(0)}, \dots, \mathbf{\mu}_K^{(0)}\}$.
2. **Tahap Penugasan (*Assignment Step*):** Alokasikan setiap titik data $\mathbf{x}_i \in \mathcal{D}$ ke kluster $C_k$ yang memiliki jarak terdekat dengan centroidnya (menggunakan metrik jarak L2):
   $$C_k^{(t)} = \{\mathbf{x}_i : \|\mathbf{x}_i - \mathbf{\mu}_k^{(t)}\|^2 \le \|\mathbf{x}_i - \mathbf{\mu}_j^{(t)}\|^2, \quad \forall j \in \{1, \dots, K\}\}$$
3. **Tahap Pembaruan Centroid (*Update Step*):** Hitung ulang posisi sentroid $\mathbf{\mu}_k$ sebagai rata-rata aritmatika (*mean vector*) dari seluruh titik yang menjadi anggota kluster $C_k$:
   $$\mathbf{\mu}_k^{(t+1)} = \frac{1}{|C_k^{(t)}|} \sum_{\mathbf{x}_i \in C_k^{(t)}} \mathbf{x}_i$$
4. **Uji Konvergensi:** Ulangi langkah 2 dan 3 hingga kondisi henti terpenuhi:
   - Tidak ada lagi perubahan alokasi keanggotaan titik data pada seluruh kluster ($C_k^{(t+1)} = C_k^{(t)}$).
   - Pergeseran posisi centroid berada di bawah ambang batas toleransi ($\sum \|\mathbf{\mu}_k^{(t+1)} - \mathbf{\mu}_k^{(t)}\| < \epsilon$).
   - Jumlah iterasi maksimum (*max_iter*) tercapai.

## 3.2 Formulasi Fungsi Objektif WCSS / SSE
Secara matematis, K-Means beroperasi sebagai algoritma optimasi yang meminimalkan fungsi energi **Within-Cluster Sum of Squares (WCSS)**, yang juga dikenal sebagai **Sum of Squared Errors (SSE)**:
$$\text{SSE} = \sum_{k=1}^K \sum_{\mathbf{x}_i \in C_k} \|\mathbf{x}_i - \mathbf{\mu}_k\|_2^2$$

Dapat dibuktikan bahwa pada setiap iterasi, nilai SSE dijamin menurun atau konstan secara monotonik ($\text{SSE}^{(t+1)} \le \text{SSE}^{(t)}$). Namun, K-Means hanya menjamin konvergensi menuju **minimum lokal (*local optimum*)**, bukan minimum global.

## 3.3 Keterbatasan Mendasar K-Means
1. **Sensitivitas terhadap Centroid Awal:** Pemilihan inisialisasi titik pusat awal yang buruk dapat menjebak model pada partisi suboptimal dengan SSE tinggi.
2. **Sensitivitas Ekstrem terhadap Outlier:** Nilai rata-rata aritmatika ($\mathbf{\mu}$) sangat mudah bergeser akibat pencilan ekstrem, yang merusak struktur kluster.
3. **Batas Kluster Selalu Berbentuk Bola (*Spherical Bias*):** K-Means hanya mampu memisahkan kluster yang berukuran sebanding, seimbang densitasnya, dan memiliki bentuk cembung sferis (*convex spherical clusters*). K-Means gagal memisahkan kluster konsentris (seperti lingkaran di dalam lingkaran) atau bentuk pita melengkung.
4. **Kebutuhan Spesifikasi Parameter $K$:** Nilai $K$ harus ditentukan sejak awal oleh pengguna secara manual.

## 3.4 Varian K-Means++ (Probabilistic Seeding)
Untuk mengatasi masalah inisialisasi acak, Arthur & Vassilvitskii (2007) mengembangkan **K-Means++**:
1. Pilih sentroid pertama $\mathbf{\mu}_1$ secara acak seragam dari dataset $\mathcal{D}$.
2. Untuk setiap titik data $\mathbf{x}_i$, hitung jarak terdekatnya ke centroid yang sudah terpilih:
   $$D(\mathbf{x}_i) = \min_{j} \|\mathbf{x}_i - \mathbf{\mu}_j\|_2$$
3. Pilih centroid berikutnya $\mathbf{\mu}_{baru}$ dari dataset dengan peluang proporsional terhadap kuadrat jaraknya:
   $$P(\mathbf{x}_i) = \frac{D(\mathbf{x}_i)^2}{\sum_{\mathbf{x} \in \mathcal{D}} D(\mathbf{x})^2}$$
4. Ulangi langkah 2 dan 3 hingga $K$ sentroid terpilih. K-Means++ terbukti secara matematis memberikan jaminan aproksimasi $O(\log K)$-kompetitif terhadap solusi optimal global.

## 3.5 Algoritma K-Medoids (PAM - Partitioning Around Medoids)
Untuk mengatasi kelemahan K-Means terhadap outlier, Kaufman & Rousseeuw (1990) merancang **K-Medoids**. Perbedaan krusialnya: titik pusat kluster (*medoid*) **wajib merupakan salah satu titik data riil** dalam himpunan data, bukan titik rata-rata hipotetis.

Fungsi objektif meminimalkan deviasi absolut (umumnya menggunakan jarak L1 Manhattan):
$$\text{Total Cost} = \sum_{k=1}^K \sum_{\mathbf{x}_i \in C_k} d(\mathbf{x}_i, \mathbf{m}_k)$$
di mana $\mathbf{m}_k \in C_k$ adalah medoid titik riil. Algoritma PAM secara iteratif menukar (*swap*) medoid terpilih dengan titik non-medoid dan mempertahankan pertukaran tersebut jika total cost menurun. K-Medoids sangat tangguh terhadap derau dan nilai pencilan.

---

# BAB IV: KELUARGA ALGORITMA HIERARCHICAL CLUSTERING

## 4.1 Konsep Dasar dan Representasi Visual Dendrogram
Hierarchical Clustering menghasilkan dekomposisi partisi bertingkat yang diilustrasikan melalui **Dendrogram** — diagram pohon terbalik di mana:
- Sumbu horizontal merepresentasikan indeks objek data individu.
- Sumbu vertikal merepresentasikan tingkat ketidaksamaan (*dissimilarity / distance threshold*) pada saat penggabungan terjadi. Semakin tinggi garis horizontal penggabungan, semakin besar perbedaan karakteristik antar-kelompok yang digabungkan.

## 4.2 Agglomerative Hierarchical Clustering (AGNES)
Pendekatan agglomerative adalah paradigma hierarki yang paling umum digunakan:
1. **Inisialisasi:** Misalkan ada $N$ sampel data. Mulai dengan $N$ kluster mandiri ($C_i = \{\mathbf{x}_i\}$). Hitung matriks jarak awal $\mathbf{D}_{N \times N}$.
2. **Penggabungan Terdekat:** Cari pasangan kluster $(C_A, C_B)$ yang memiliki jarak inter-kluster terkecil:
   $$\text{dist}(C_A, C_B) = \min_{i, j} \text{dist}(C_i, C_j)$$
3. **Pembaruan Matriks Jarak:** Gabungkan $C_A$ dan $C_B$ menjadi satu kluster baru $C_{baru} = C_A \cup C_B$. Perbarui nilai jarak antara $C_{baru}$ ke semua kluster lain yang tersisa menggunakan fungsi penautan (*linkage criterion*).
4. **Terminasi:** Ulangi langkah 2 dan 3 hingga seluruh data menyatu ke dalam satu simpul akar kluster tunggal ($K = 1$).

## 4.3 Kriteria Penautan Antar-Kluster (Linkage Metrics)
Kriteria linkage mendefinisikan cara menghitung jarak antara dua subset kluster $A$ dan $B$:

1. **Single Linkage (Minimum Distance):** Jarak ditentukan oleh pasangan titik terdekat antara kedua kluster:
   $$d_{single}(A, B) = \min_{\mathbf{x} \in A, \mathbf{y} \in B} \|\mathbf{x} - \mathbf{y}\|$$
   *Kelemahan:* Sangat rentan terhadap **Chaining Effect**, di mana kluster memanjang dan terhubung oleh jembatan beberapa titik noise perantara.
2. **Complete Linkage (Maximum Distance):** Jarak ditentukan oleh pasangan titik terjauh:
   $$d_{complete}(A, B) = \max_{\mathbf{x} \in A, \mathbf{y} \in B} \|\mathbf{x} - \mathbf{y}\|$$
   *Kelebihan:* Menghasilkan kluster berbentuk bulat kompak dengan diameter seragam, menghindari efek rantai.
3. **Average Linkage (UPGMA):** Rata-rata jarak seluruh kombinasi pasangan titik antar kedua kluster:
   $$d_{avg}(A, B) = \frac{1}{|A| |B|} \sum_{\mathbf{x} \in A} \sum_{\mathbf{y} \in B} \|\mathbf{x} - \mathbf{y}\|$$
4. **Centroid Linkage (UPGMC):** Jarak geometris antara sentroid kedua kluster:
   $$d_{centroid}(A, B) = \|\mathbf{\mu}_A - \mathbf{\mu}_B\|$$
5. **Ward's Minimum Variance Method:** Menggabungkan dua kluster yang menghasilkan peningkatan varians kuadrat kesalahan terkecil ($\Delta \text{SSE}$ minimum):
   $$\Delta \text{SSE}(A, B) = \text{SSE}_{A \cup B} - (\text{SSE}_A + \text{SSE}_B) = \frac{|A| |B|}{|A| + |B|} \|\mathbf{\mu}_A - \mathbf{\mu}_B\|_2^2$$
   Ward's method menghasilkan kluster berukuran sangat homogen dan merupakan standar emas hierarki untuk data numerik kontinu.

## 4.4 Penentuan Jumlah Kluster via Horizontal Cut
Keunggulan utama Hierarchical Clustering adalah fleksibilitas: pengguna tidak perlu menetapkan nilai $K$ sebelum algoritma berjalan. Setelah dendrogram selesai dikonstruksi, pengguna dapat menarik sebuah **garis horizontal pemotong (*horizontal cut line*)** pada ketinggian jarak tertentu. Jumlah garis cabang vertikal yang terpotong oleh garis horizontal tersebut mendefinisikan jumlah kluster $K$ akhir.

---

# BAB V: ALGORITMA KLUSTER BERBASIS KEPADATAN (DENSITY-BASED: DBSCAN)

## 5.1 Keterbatasan Model Partisional dan Hierarki
Baik K-Means maupun Hierarchical Ward mengasumsikan kluster berbentuk cembung (*convex* atau *spherical*). Ketika dihadapkan pada dataset spasial yang memuat kluster berbentuk bulan sabit, cincin konsentris, atau pola jalan berkelok-kelok, algoritma berbasis sentroid akan gagal total mempartisi data secara tepat.

## 5.2 Konsep Kepadatan DBSCAN
DBSCAN (Ester, Kriegel, Sander, & Xu, 1996) memandang kluster sebagai area berdensitas tinggi (*dense regions*) yang dipisahkan oleh area berkepadatan rendah (*sparse regions*). DBSCAN hanya membutuhkan dua parameter utama:
1. **$\epsilon$ (Epsilon):** Radius batas ketetanggaan spasial di sekitar sebuah titik.
   $$N_\epsilon(\mathbf{p}) = \{\mathbf{q} \in \mathcal{D} : \text{dist}(\mathbf{p}, \mathbf{q}) \le \epsilon\}$$
2. **$MinPts$:** Jumlah minimum titik data yang wajib berada di dalam radius $\epsilon$ agar suatu titik dianggap berada di daerah padat.

## 5.3 Taksonomi Titik Data pada DBSCAN
Setiap titik dalam dataset dikategorikan ke dalam salah satu dari tiga status:
1. **Core Point (Titik Inti):** Titik $\mathbf{p}$ yang memiliki setidaknya $MinPts$ titik tetangga dalam radius $\epsilon$:
   $$|N_\epsilon(\mathbf{p})| \ge MinPts$$
2. **Border Point (Titik Perbatasan):** Titik $\mathbf{q}$ yang bukan merupakan titik inti ($|N_\epsilon(\mathbf{q})| < MinPts$), tetapi berada di dalam lingkungan radius $\epsilon$ dari setidaknya satu Core Point.
3. **Noise Point / Outlier (Derau):** Titik yang bukan Core Point dan tidak terjangkau oleh Core Point manapun. DBSCAN secara otomatis melabeli titik-titik ini sebagai *noise* (label $-1$).

## 5.4 Konsep Relasi Kepadatan Spasial
- **Directly Density-Reachability:** Titik $\mathbf{q}$ dapat dijangkau secara langsung dari $\mathbf{p}$ jika $\mathbf{p}$ adalah Core Point dan $\mathbf{q} \in N_\epsilon(\mathbf{p})$.
- **Density-Reachability:** Titik $\mathbf{q}$ dapat dijangkau dari $\mathbf{p}$ melalui sebuah rantai titik-titik inti perantara $\mathbf{p}_1, \mathbf{p}_2, \dots, \mathbf{p}_k$ di mana $\mathbf{p}_1 = \mathbf{p}$ dan $\mathbf{p}_{i+1}$ directly reachable dari $\mathbf{p}_i$.
- **Density-Connectivity:** Titik $\mathbf{p}$ dan $\mathbf{q}$ saling terhubung jika terdapat sebuah titik inti ketiga $\mathbf{o}$ sedemikian rupa sehingga $\mathbf{p}$ dan $\mathbf{q}$ sama-sama reachable dari $\mathbf{o}$.

Sebuah kluster pada DBSCAN didefinisikan secara elegan sebagai himpunan maksimal dari titik-titik yang saling *density-connected*.

---

# BAB VI: EVALUASI KUALITAS DAN PENENTUAN JUMLAH KLUSTER OPTIMAL

## 6.1 Validasi Kluster Internal vs Eksternal
- **Validasi Eksternal:** Digunakan jika tersedia ground-truth label acuan kelas (misal: *Adjusted Rand Index / ARI*, *Normalized Mutual Information / NMI*).
- **Validasi Internal:** Digunakan pada kondisi murni penambangan data riil di mana label kelas tidak tersedia, mengukur rasio kekompakan (*compactness*) terhadap keterpisahan (*separation*).

## 6.2 Metode Siku (The Elbow Method)
Metode Siku mengevaluasi nilai SSE/WCSS untuk berbagai kandidat nilai $K \in \{1, 2, \dots, 10\}$:
$$\text{WCSS}(K) = \sum_{k=1}^K \sum_{\mathbf{x} \in C_k} \|\mathbf{x} - \mathbf{\mu}_k\|^2$$
Plotting kurva WCSS terhadap $K$ akan menghasilkan kurva menurun tajam yang kemudian melandai. Titik infleksi di mana laju penurunan SSE melambat secara drastis (*the elbow point*) dipilih sebagai jumlah kluster optimal $K^*$.

## 6.3 Analisis Silhouette Coefficient
Dikembangkan oleh Peter Rousseeuw (1987), koefisien Silhouette mengevaluasi seberapa tepat sebuah titik $\mathbf{x}_i$ berada di klusternya sendiri dibandingkan kluster tetangga terdekat:
1. Hitung $a(\mathbf{x}_i)$: Rata-rata jarak titik $\mathbf{x}_i$ ke seluruh titik lain dalam kluster yang sama ($C_A$):
   $$a(\mathbf{x}_i) = \frac{1}{|C_A| - 1} \sum_{\mathbf{x}_j \in C_A, j \ne i} \text{dist}(\mathbf{x}_i, \mathbf{x}_j)$$
2. Hitung $b(\mathbf{x}_i)$: Rata-rata jarak titik $\mathbf{x}_i$ ke titik-titik di kluster terdekat lain ($C_B \ne C_A$):
   $$b(\mathbf{x}_i) = \min_{C_B \ne C_A} \frac{1}{|C_B|} \sum_{\mathbf{y} \in C_B} \text{dist}(\mathbf{x}_i, \mathbf{y})$$
3. Koefisien Silhouette untuk titik $\mathbf{x}_i$ didefinisikan sebagai:
   $$s(\mathbf{x}_i) = \frac{b(\mathbf{x}_i) - a(\mathbf{x}_i)}{\max(a(\mathbf{x}_i), b(\mathbf{x}_i))}$$
   Rentang nilai $s(\mathbf{x}_i) \in [-1, +1]$:
   - $s(\mathbf{x}_i) \approx +1$: Objek berada jauh dari kluster tetangga dan sangat dekat dengan klusternya sendiri (*pengelompokan sangat ideal*).
   - $s(\mathbf{x}_i) \approx 0$: Objek berada tepat di perbatasan ambang antara dua kluster.
   - $s(\mathbf{x}_i) < 0$: Objek lebih dekat ke kluster tetangga daripada klusternya sendiri (*salah penempatan kluster*).

Silhouette Score rata-rata keseluruhan dataset $\bar{s} = \frac{1}{N} \sum_{i=1}^N s(\mathbf{x}_i)$ digunakan untuk memilih $K$ terbaik.

## 6.4 Davies-Bouldin Index (DBI)
DBI (Davies & Bouldin, 1979) mengukur kesamaan maksimum antar-kluster berdasarkan rasio sebaran internal terhadap jarak sentroid:
$$R_{ij} = \frac{s_i + s_j}{d(\mathbf{\mu}_i, \mathbf{\mu}_j)}$$
$$\text{DBI} = \frac{1}{K} \sum_{i=1}^K \max_{j \ne i} R_{ij}$$
di mana $s_i$ adalah rata-rata jarak titik dalam kluster $C_i$ ke sentroidnya. **Semakin kecil nilai DBI, semakin unggul kualitas partisi kluster.**

## 6.5 Calinski-Harabasz Index (Variance Ratio Criterion)
Menghitung rasio dispersi antara-kluster (*between-cluster variance*) terhadap dispersi dalam-kluster (*within-cluster variance*):
$$\text{CH} = \frac{\text{SSB} / (K - 1)}{\text{SSW} / (N - K)}$$
**Semakin tinggi nilai CH Index, semakin terpisah dan kompak kluster yang terbentuk.**

---

# BAB VII: SIMULASI NUMERIK LANGKAH DEMI LANGKAH (MANUAL CALCULATION TRACE)

Untuk memberikan pembuktian matematis yang konkret, pada bab ini disajikan perhitungan manual langkah demi langkah (*hand calculation trace*) pada dataset 8 sampel pasien/pelanggan 2 dimensi.

## 7.1 Definisi Dataset Simulasi (8 Sampel 2 Dimensi)
Dataset memuat 8 titik observasi dengan 2 atribut fitur terukur:
- $X_1$: Frekuensi Kunjungan Puskesmas per Semester (skala 1–10).
- $X_2$: Volume Total Peresepan Obat per Semester (skala 1–10).

### Tabel 7.1: Matriks Koordinat Data 8 Sampel Observasi
| ID Sampel | Fitur $X_1$ | Fitur $X_2$ | Deskripsi Sifat Pengamatan |
|:---:|:---:|:---:|:---|
| **$P_1$** | 2 | 2 | Frekuensi rendah, peresepan rendah |
| **$P_2$** | 2 | 4 | Frekuensi rendah, peresepan sedang |
| **$P_3$** | 3 | 3 | Frekuensi sedang-rendah, peresepan sedang |
| **$P_4$** | 3 | 5 | Frekuensi sedang-rendah, peresepan tinggi |
| **$P_5$** | 7 | 6 | Frekuensi tinggi, peresepan tinggi |
| **$P_6$** | 7 | 8 | Frekuensi tinggi, peresepan sangat tinggi |
| **$P_7$** | 8 | 6 | Frekuensi sangat tinggi, peresepan tinggi |
| **$P_8$** | 8 | 8 | Frekuensi sangat tinggi, peresepan sangat tinggi |

Secara spasial, terlihat adanya dua kelompok alami: kelompok kiri-bawah $\{P_1, P_2, P_3, P_4\}$ dan kelompok kanan-atas $\{P_5, P_6, P_7, P_8\}$.

---

## 7.2 Simulasi Manual Lengkap Algoritma K-Means ($K = 2$)

### ITERASI 1:
Kita tetapkan jumlah kluster $K = 2$.
Inisialisasi Centroid Awal:
- **Centroid 1 ($\mathbf{\mu}_1^{(0)}$):** Titik $P_1 = (2, 2)$
- **Centroid 2 ($\mathbf{\mu}_2^{(0)}$):** Titik $P_5 = (7, 6)$

#### 1. Perhitungan Jarak Tiap Titik ke Centroid
Kita gunakan jarak Euclidean (L2) kuadrat $d^2(\mathbf{x}, \mathbf{\mu}) = (x_1 - \mu_1)^2 + (x_2 - \mu_2)^2$ serta jarak L1 Manhattan $d_{L1} = |x_1 - \mu_1| + |x_2 - \mu_2|$:

1. **Untuk Titik $P_1(2, 2)$:**
   - Jarak ke $\mathbf{\mu}_1(2, 2)$: $d_{L2}^2 = (2-2)^2 + (2-2)^2 = 0$ ($d_{L1} = 0$)
   - Jarak ke $\mathbf{\mu}_2(7, 6)$: $d_{L2}^2 = (2-7)^2 + (2-6)^2 = 25 + 16 = 41$ ($d_{L2} = 6.403, d_{L1} = 9$)
   - Alokasi: Karena $0 < 41 \to$ **Kluster 1 ($C_1$)**

2. **Untuk Titik $P_2(2, 4)$:**
   - Jarak ke $\mathbf{\mu}_1(2, 2)$: $d_{L2}^2 = (2-2)^2 + (4-2)^2 = 0 + 4 = 4$ ($d_{L2} = 2.000, d_{L1} = 2$)
   - Jarak ke $\mathbf{\mu}_2(7, 6)$: $d_{L2}^2 = (2-7)^2 + (4-6)^2 = 25 + 4 = 29$ ($d_{L2} = 5.385, d_{L1} = 7$)
   - Alokasi: Karena $4 < 29 \to$ **Kluster 1 ($C_1$)**

3. **Untuk Titik $P_3(3, 3)$:**
   - Jarak ke $\mathbf{\mu}_1(2, 2)$: $d_{L2}^2 = (3-2)^2 + (3-2)^2 = 1 + 1 = 2$ ($d_{L2} = 1.414, d_{L1} = 2$)
   - Jarak ke $\mathbf{\mu}_2(7, 6)$: $d_{L2}^2 = (3-7)^2 + (3-6)^2 = 16 + 9 = 25$ ($d_{L2} = 5.000, d_{L1} = 7$)
   - Alokasi: Karena $2 < 25 \to$ **Kluster 1 ($C_1$)**

4. **Untuk Titik $P_4(3, 5)$:**
   - Jarak ke $\mathbf{\mu}_1(2, 2)$: $d_{L2}^2 = (3-2)^2 + (5-2)^2 = 1 + 9 = 10$ ($d_{L2} = 3.162, d_{L1} = 4$)
   - Jarak ke $\mathbf{\mu}_2(7, 6)$: $d_{L2}^2 = (3-7)^2 + (5-6)^2 = 16 + 1 = 17$ ($d_{L2} = 4.123, d_{L1} = 5$)
   - Alokasi: Karena $10 < 17 \to$ **Kluster 1 ($C_1$)**

5. **Untuk Titik $P_5(7, 6)$:**
   - Jarak ke $\mathbf{\mu}_1(2, 2)$: $d_{L2}^2 = (7-2)^2 + (6-2)^2 = 25 + 16 = 41$
   - Jarak ke $\mathbf{\mu}_2(7, 6)$: $d_{L2}^2 = (7-7)^2 + (6-6)^2 = 0$
   - Alokasi: Karena $0 < 41 \to$ **Kluster 2 ($C_2$)**

6. **Untuk Titik $P_6(7, 8)$:**
   - Jarak ke $\mathbf{\mu}_1(2, 2)$: $d_{L2}^2 = (7-2)^2 + (8-2)^2 = 25 + 36 = 61$
   - Jarak ke $\mathbf{\mu}_2(7, 6)$: $d_{L2}^2 = (7-7)^2 + (8-6)^2 = 0 + 4 = 4$
   - Alokasi: Karena $4 < 61 \to$ **Kluster 2 ($C_2$)**

7. **Untuk Titik $P_7(8, 6)$:**
   - Jarak ke $\mathbf{\mu}_1(2, 2)$: $d_{L2}^2 = (8-2)^2 + (6-2)^2 = 36 + 16 = 52$
   - Jarak ke $\mathbf{\mu}_2(7, 6)$: $d_{L2}^2 = (8-7)^2 + (6-6)^2 = 1 + 0 = 1$
   - Alokasi: Karena $1 < 52 \to$ **Kluster 2 ($C_2$)**

8. **Untuk Titik $P_8(8, 8)$:**
   - Jarak ke $\mathbf{\mu}_1(2, 2)$: $d_{L2}^2 = (8-2)^2 + (8-2)^2 = 36 + 36 = 72$
   - Jarak ke $\mathbf{\mu}_2(7, 6)$: $d_{L2}^2 = (8-7)^2 + (8-6)^2 = 1 + 4 = 5$
   - Alokasi: Karena $5 < 72 \to$ **Kluster 2 ($C_2$)**

#### Ringkasan Partisi Iterasi 1:
- Anggota Kluster 1: $C_1 = \{P_1, P_2, P_3, P_4\}$
- Anggota Kluster 2: $C_2 = \{P_5, P_6, P_7, P_8\}$

#### 2. Pembaruan Posisi Centroid (Update Step Iterasi 1):
$$\mathbf{\mu}_1^{(1)} = \left( \frac{2 + 2 + 3 + 3}{4}, \frac{2 + 4 + 3 + 5}{4} \right) = \left( \frac{10}{4}, \frac{14}{4} \right) = \mathbf{(2.5, 3.5)}$$
$$\mathbf{\mu}_2^{(1)} = \left( \frac{7 + 7 + 8 + 8}{4}, \frac{6 + 8 + 6 + 8}{4} \right) = \left( \frac{30}{4}, \frac{28}{4} \right) = \mathbf{(7.5, 7.0)}$$

---

### ITERASI 2:
Centroid Baru: $\mathbf{\mu}_1 = (2.5, 3.5)$ dan $\mathbf{\mu}_2 = (7.5, 7.0)$.

Kita evaluasi kembali jarak seluruh titik data ke centroid baru:
1. $P_1(2, 2) \to d^2(\mathbf{\mu}_1) = (2-2.5)^2 + (2-3.5)^2 = 0.25 + 2.25 = \mathbf{2.50}$; $d^2(\mathbf{\mu}_2) = 5.5^2 + 5.0^2 = 55.25 \implies \mathbf{C_1}$
2. $P_2(2, 4) \to d^2(\mathbf{\mu}_1) = (2-2.5)^2 + (4-3.5)^2 = 0.25 + 0.25 = \mathbf{0.50}$; $d^2(\mathbf{\mu}_2) = 5.5^2 + 3.0^2 = 39.25 \implies \mathbf{C_1}$
3. $P_3(3, 3) \to d^2(\mathbf{\mu}_1) = (3-2.5)^2 + (3-3.5)^2 = 0.25 + 0.25 = \mathbf{0.50}$; $d^2(\mathbf{\mu}_2) = 4.5^2 + 4.0^2 = 36.25 \implies \mathbf{C_1}$
4. $P_4(3, 5) \to d^2(\mathbf{\mu}_1) = (3-2.5)^2 + (5-3.5)^2 = 0.25 + 2.25 = \mathbf{2.50}$; $d^2(\mathbf{\mu}_2) = 4.5^2 + 2.0^2 = 24.25 \implies \mathbf{C_1}$
5. $P_5(7, 6) \to d^2(\mathbf{\mu}_1) = 4.5^2 + 2.5^2 = 26.50$; $d^2(\mathbf{\mu}_2) = (7-7.5)^2 + (6-7.0)^2 = 0.25 + 1.00 = \mathbf{1.25} \implies \mathbf{C_2}$
6. $P_6(7, 8) \to d^2(\mathbf{\mu}_1) = 4.5^2 + 4.5^2 = 40.50$; $d^2(\mathbf{\mu}_2) = (7-7.5)^2 + (8-7.0)^2 = 0.25 + 1.00 = \mathbf{1.25} \implies \mathbf{C_2}$
7. $P_7(8, 6) \to d^2(\mathbf{\mu}_1) = 5.5^2 + 2.5^2 = 36.50$; $d^2(\mathbf{\mu}_2) = (8-7.5)^2 + (6-7.0)^2 = 0.25 + 1.00 = \mathbf{1.25} \implies \mathbf{C_2}$
8. $P_8(8, 8) \to d^2(\mathbf{\mu}_1) = 5.5^2 + 4.5^2 = 50.50$; $d^2(\mathbf{\mu}_2) = (8-7.5)^2 + (8-7.0)^2 = 0.25 + 1.00 = \mathbf{1.25} \implies \mathbf{C_2}$

#### Uji Konvergensi:
Keanggotaan kluster pada Iterasi 2 adalah:
- $C_1 = \{P_1, P_2, P_3, P_4\}$
- $C_2 = \{P_5, P_6, P_7, P_8\}$

Karena keanggotaan kluster pada Iterasi 2 **sama persis (*identik*)** dengan Iterasi 1, posisi centroid tidak mengalami pergeseran ($\Delta \mathbf{\mu} = 0$). **Algoritma K-Means dinyatakan konvergen sempurna pada Iterasi ke-2.**

#### Perhitungan Nilai WCSS / SSE Akhir:
$$\text{SSE}_1 = 2.50 + 0.50 + 0.50 + 2.50 = 6.00$$
$$\text{SSE}_2 = 1.25 + 1.25 + 1.25 + 1.25 = 5.00$$
$$\text{Total WCSS} = \text{SSE}_1 + \text{SSE}_2 = 6.00 + 5.00 = \mathbf{11.00}$$

---

## 7.3 Simulasi Manual Agglomerative Hierarchical Clustering

### Langkah 1: Matriks Jarak Euclidean Awal $d(P_i, P_j)$
Kita hitung matriks jarak berpasangan $8 \times 8$:

| | $P_1(2,2)$ | $P_2(2,4)$ | $P_3(3,3)$ | $P_4(3,5)$ | $P_5(7,6)$ | $P_6(7,8)$ | $P_7(8,6)$ | $P_8(8,8)$ |
|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|
| **$P_1$** | 0.000 | 2.000 | **1.414** | 3.162 | 6.403 | 7.810 | 7.211 | 8.485 |
| **$P_2$** | 2.000 | 0.000 | 1.414 | **1.414** | 5.385 | 6.403 | 6.325 | 7.211 |
| **$P_3$** | **1.414** | 1.414 | 0.000 | 2.000 | 5.000 | 6.403 | 5.831 | 7.071 |
| **$P_4$** | 3.162 | **1.414** | 2.000 | 0.000 | 4.123 | 5.000 | 5.099 | 5.831 |
| **$P_5$** | 6.403 | 5.385 | 5.000 | 4.123 | 0.000 | 2.000 | **1.000** | 2.236 |
| **$P_6$** | 7.810 | 6.403 | 6.403 | 5.000 | 2.000 | 0.000 | 2.236 | **1.000** |
| **$P_7$** | 7.211 | 6.325 | 5.831 | 5.099 | **1.000** | 2.236 | 0.000 | 2.000 |
| **$P_8$** | 8.485 | 7.211 | 7.071 | 5.831 | 2.236 | **1.000** | 2.000 | 0.000 |

### Langkah 2: Proses Penggabungan Berurutan (Single Linkage)

1. **Penggabungan 1 (Jarak Minimum = 1.000):**
   Pasangan $(P_5, P_7)$ memiliki jarak terkecil $d(P_5, P_7) = 1.000$.
   Bentuk kluster baru: $C_A = \{P_5, P_7\}$ pada ketinggian $h = 1.000$.

2. **Penggabungan 2 (Jarak Minimum = 1.000):**
   Pasangan $(P_6, P_8)$ memiliki jarak $d(P_6, P_8) = 1.000$.
   Bentuk kluster baru: $C_B = \{P_6, P_8\}$ pada ketinggian $h = 1.000$.

3. **Penggabungan 3 (Jarak Minimum = 1.414):**
   Pasangan $(P_1, P_3)$ dan $(P_2, P_4)$ memiliki jarak $1.414$.
   Gabungkan $(P_1, P_3) \to C_C = \{P_1, P_3\}$ pada $h = 1.414$.

4. **Penggabungan 4 (Jarak Minimum = 1.414):**
   Gabungkan $(P_2, P_4) \to C_D = \{P_2, P_4\}$ pada $h = 1.414$.

5. **Penggabungan 5 (Jarak Minimum = 1.414):**
   Jarak Single Linkage antara $C_C\{P_1, P_3\}$ dan $C_D\{P_2, P_4\}$:
   $\min \{d(P_1, P_2)=2, d(P_3, P_2)=1.414, d(P_3, P_4)=2, d(P_1, P_4)=3.162\} = 1.414$.
   Gabungkan $C_C$ dan $C_D$ menjadi satu kluster besar $C_{kiri} = \{P_1, P_2, P_3, P_4\}$ pada $h = 1.414$.

6. **Penggabungan 6 (Jarak Minimum = 2.000):**
   Jarak Single Linkage antara $C_A\{P_5, P_7\}$ dan $C_B\{P_6, P_8\}$:
   $\min \{d(P_5, P_6)=2, d(P_7, P_8)=2\} = 2.000$.
   Gabungkan $C_A$ dan $C_B$ menjadi $C_{kanan} = \{P_5, P_6, P_7, P_8\}$ pada $h = 2.000$.

7. **Penggabungan 7 (Final - Jarak Minimum Antar Dua Kluster Besar):**
   Jarak Single Linkage antara $C_{kiri}$ dan $C_{kanan}$:
   Titik terdekat adalah antara $P_4(3, 5)$ dan $P_5(7, 6)$:
   $d(P_4, P_5) = \sqrt{(7-3)^2 + (6-5)^2} = \sqrt{16 + 1} = \sqrt{17} \approx \mathbf{4.123}$.
   Seluruh data menyatu pada ketinggian $h = 4.123$.

### Konstruksi Dendrogram Hierarki
```
Tinggi Jarak (Dissimilarity)
4.123 |                   ┌───────────────────────────────┐
      |                   │                               │
2.000 |                   │                       ┌───────┴───────┐
1.414 |           ┌───────┴───────┐               │               │
1.000 |       ┌───┴───┐       ┌───┴───┐       ┌───┴───┐       ┌───┴───┐
0.000 |───────┴───────┴───────┴───────┴───────┴───────┴───────┴───────┴──────
             P1      P3      P2      P4      P5      P7      P6      P8
      <───────── Kluster 1 ─────────> <───────── Kluster 2 ─────────>
```
Jika kita melakukan pemotongan garis horizontal (*Horizontal Cut*) pada ketinggian $h = 3.0$, terbentuk tepat **2 kluster optimal** yang identik dengan hasil K-Means.

---

## 7.4 Simulasi Manual Perhitungan Nilai Silhouette Score
Kita ambil contoh evaluasi titik **$P_3(3, 3)$** yang berada di Kluster 1 ($C_1 = \{P_1, P_2, P_3, P_4\}$):

1. **Menghitung $a(P_3)$ (Kekompakan Internal):**
   Rata-rata jarak $P_3$ ke titik-titik lain di kluster sendiri ($P_1, P_2, P_4$):
   - $d(P_3, P_1) = \sqrt{(3-2)^2 + (3-2)^2} = \sqrt{2} \approx 1.4142$
   - $d(P_3, P_2) = \sqrt{(3-2)^2 + (3-4)^2} = \sqrt{2} \approx 1.4142$
   - $d(P_3, P_4) = \sqrt{(3-3)^2 + (3-5)^2} = \sqrt{4} = 2.0000$
   $$a(P_3) = \frac{1.4142 + 1.4142 + 2.0000}{3} = \frac{4.8284}{3} \approx \mathbf{1.6095}$$

2. **Menghitung $b(P_3)$ (Separasi ke Kluster Tetangga Terdekat $C_2$):**
   Rata-rata jarak $P_3$ ke seluruh titik di $C_2 = \{P_5, P_6, P_7, P_8\}$:
   - $d(P_3, P_5) = \sqrt{(3-7)^2 + (3-6)^2} = \sqrt{16 + 9} = 5.0000$
   - $d(P_3, P_6) = \sqrt{(3-7)^2 + (3-8)^2} = \sqrt{16 + 25} = \sqrt{41} \approx 6.4031$
   - $d(P_3, P_7) = \sqrt{(3-8)^2 + (3-6)^2} = \sqrt{25 + 9} = \sqrt{34} \approx 5.8310$
   - $d(P_3, P_8) = \sqrt{(3-8)^2 + (3-8)^2} = \sqrt{25 + 25} = \sqrt{50} \approx 7.0711$
   $$b(P_3) = \frac{5.0000 + 6.4031 + 5.8310 + 7.0711}{4} = \frac{24.3052}{4} \approx \mathbf{6.0763}$$

3. **Menghitung Koefisien Silhouette $s(P_3)$:**
   $$s(P_3) = \frac{b(P_3) - a(P_3)}{\max(a(P_3), b(P_3))} = \frac{6.0763 - 1.6095}{\max(1.6095, 6.0763)} = \frac{4.4668}{6.0763} \approx \mathbf{+0.7351}$$

**Interpretasi Nilai:** Nilai $s(P_3) = +0.7351$ mendekati angka $+1.0$, membuktikan secara kuantitatif bahwa titik $P_3$ terklasterisasi sangat kuat dan berada jauh dari batas wilayah kluster tetangga.

---

# BAB VIII: ANALISIS KOMPARATIF KOMPLEKSITAS BIG-O DAN KARAKTERISTIK ALGORITMA

## 8.1 Tabel Komparasi Big-O Kompleksitas Komputasi
Misalkan $N$ adalah jumlah sampel data, $d$ adalah dimensi atribut, $K$ adalah jumlah kluster, $I$ adalah jumlah iterasi konvergensi, dan $\epsilon$ adalah parameter ketetanggaan spasial:

| Algoritma Kluster | Kompleksitas Waktu (*Training*) | Kompleksitas Waktu (*Inferensi / Assign*) | Kompleksitas Ruang (*Memori*) |
|---|:---:|:---:|:---:|
| **K-Means (Standard)** | $O(I \cdot N \cdot K \cdot d)$ *(Efisien Linier)* | $O(K \cdot d)$ | $O((N + K) \cdot d)$ |
| **K-Means++** | $O(I \cdot N \cdot K \cdot d)$ | $O(K \cdot d)$ | $O((N + K) \cdot d)$ |
| **K-Medoids (PAM)** | $O(I \cdot K \cdot (N - K)^2 \cdot d)$ *(Mahal)* | $O(K \cdot d)$ | $O(N^2 + N \cdot d)$ |
| **Agglomerative Hierarchical** | $O(N^2 \log N)$ hingga $O(N^3)$ | $O(N)$ | $O(N^2)$ *(Boros Memori Matriks)* |
| **DBSCAN (dengan k-d tree / BallTree)** | $O(N \log N)$ | $O(\log N)$ | $O(N \cdot d)$ |
| **DBSCAN (Brute-Force)** | $O(N^2 \cdot d)$ | $O(N \cdot d)$ | $O(N \cdot d)$ |

---

## 8.2 Matriks Kelebihan, Kelemahan, dan Skenario Penerapan Industri
| Algoritma | Kelebihan Utama | Kelemahan Utama | Toleransi Outlier | Skenario Rekomendasi Industri |
|---|---|---|:---:|---|
| **K-Means** | Sangat cepat, skalabel untuk data jutaan baris, implementasi mudah. | Terjebak local minima, sensitif terhadap outlier, hanya bentuk sferis. | **Rendah** | Segmentasi pelanggan e-commerce besar, kompresi warna gambar. |
| **K-Medoids** | Robust terhadap pencilan ekstrem, mendukung berbagai metrik jarak L1/L2. | Komputasi sangat lambat pada dataset besar ($O(N^2)$). | **Sangat Tinggi** | Analisis keuangan fraud sensitif, bioinformatika ekspresi genetika. |
| **Agglomerative Hierarchical** | Menghasilkan struktur pohon interpretatif, tidak butuh asumsi $K$ apriori. | Memori kuadratik $O(N^2)$, tidak bisa membatalkan penggabungan salah. | **Moderat** | Taksonomi biologi filogenetik, analisis segmentasi hierarkis pasar. |
| **DBSCAN** | Menemukan bentuk kluster sembarang, mendeteksi noise/anomali otomatis. | Kesulitan pada dataset dengan kerapatan densitas bertingkat, sensitif parameter $\epsilon$. | **Sangat Tinggi** (Noise diisolasi) | Analisis geospasial GPS, deteksi anomali jaringan, pemetaan lalu lintas. |

---

# BAB IX: IMPLEMENTASI PROGRAM DENGAN PYTHON DAN SCIKIT-LEARN

Kode sumber Python berikut mengimplementasikan seluruh pipeline analisis kluster: konstruksi dataset 8 pasien, standarisasi fitur, pemodelan K-Means, Agglomerative Hierarchical, DBSCAN, serta evaluasi metrik Silhouette Score.

```python
"""
========================================================================================
TUGAS TEKNIK KLUSTER PENAMBANGAN DATA (INF11121)
Implementasi Komparasi Model Kluster: K-Means, K-Means++, Hierarchical Ward, & DBSCAN
Penulis: Nabil (NIM: 2401020073)
========================================================================================
"""

import numpy as np
import pandas as pd
from sklearn.cluster import KMeans, AgglomerativeClustering, DBSCAN
from sklearn.metrics import silhouette_score, davies_bouldin_score, calinski_harabasz_score
from scipy.cluster.hierarchy import dendrogram, linkage

# 1. KONSTRUKSI DATASET 8 SAMPEL OBSERVASI
data = {
    'ID': ['P1', 'P2', 'P3', 'P4', 'P5', 'P6', 'P7', 'P8'],
    'Frekuensi_Kunjungan': [2, 2, 3, 3, 7, 7, 8, 8],
    'Volume_Obat': [2, 4, 3, 5, 6, 8, 6, 8]
}

df = pd.DataFrame(data)
X = df[['Frekuensi_Kunjungan', 'Volume_Obat']].values

print("=== MATRIKS DATASET OBSERVASI KLUSTER ===")
print(df.to_string(index=False))

# 2. IMPLEMENTASI K-MEANS CLUSTERING (K=2)
kmeans = KMeans(n_clusters=2, init='k-means++', n_init=10, random_state=42)
labels_km = kmeans.fit_predict(X)
centroids_km = kmeans.cluster_centers_
wcss_km = kmeans.inertia_

print("\n=== HASIL PEMODELAN K-MEANS (K=2) ===")
for i, (pid, label) in enumerate(zip(df['ID'], labels_km)):
    print(f"Sampel {pid} -> Terpilih ke Kluster {label}")
print(f"Centroid Akhir Kluster:\n{centroids_km}")
print(f"Nilai WCSS / SSE: {wcss_km:.2f}")

# 3. IMPLEMENTASI AGGLOMERATIVE HIERARCHICAL CLUSTERING (WARD LINKAGE)
agg = AgglomerativeClustering(n_clusters=2, metric='euclidean', linkage='ward')
labels_agg = agg.fit_predict(X)

print("\n=== HASIL AGGLOMERATIVE HIERARCHICAL (K=2, WARD) ===")
for pid, label in zip(df['ID'], labels_agg):
    print(f"Sampel {pid} -> Terpilih ke Kluster {label}")

# 4. IMPLEMENTASI DENSITY-BASED SPATIAL CLUSTERING (DBSCAN)
dbscan = DBSCAN(eps=2.5, min_samples=2)
labels_db = dbscan.fit_predict(X)

print("\n=== HASIL DBSCAN (eps=2.5, min_samples=2) ===")
for pid, label in zip(df['ID'], labels_db):
    status = "Noise" if label == -1 else f"Kluster {label}"
    print(f"Sampel {pid} -> Status: {status}")

# 5. EVALUASI METRIK KUALITAS VALIDASI INTERNAL
sil_km = silhouette_score(X, labels_km)
dbi_km = davies_bouldin_score(X, labels_km)
ch_km = calinski_harabasz_score(X, labels_km)

print("\n" + "="*60)
print("METRIK EVALUASI KUALITAS INTERNAL K-MEANS (K=2):")
print(f"1. Silhouette Score         : {sil_km:.4f} (Ideal > 0.70)")
print(f"2. Davies-Bouldin Index (DBI): {dbi_km:.4f} (Semakin kecil semakin baik)")
print(f"3. Calinski-Harabasz Index   : {ch_km:.4f} (Semakin besar semakin baik)")
print("="*60)
```

---

# BAB X: KESIMPULAN DAN REKOMENDASI

Berdasarkan kajian teoritis, formulasi geometris fungsi jarak, simulasi numerik manual, dan implementasi algoritma yang telah dipaparkan dalam tugas teknik kluster ini, dapat ditarik sejumlah kesimpulan penting:

1. **Signifikansi Pengukuran Jarak L1, L2, dan L3:** Pemilihan fungsi jarak menentukan batas geometri partisi. Jarak Manhattan (L1) sangat unggul pada data berdimensi tinggi dan tahan terhadap outlier, sedangkan jarak Euclidean (L2) menjadi tolok ukur alamiah untuk pemodelan sentroid sferis.
2. **Kesesuaian Algoritma terhadap Topologi Data:** K-Means dan K-Medoids sangat efektif untuk mengelompokkan data yang memiliki kluster cembung homogen. Namun, ketika data memuat derau signifikan atau bentuk geometri non-linear bebas, algoritma berbasis kepadatan seperti **DBSCAN** menjadi pilihan yang jauh lebih tangguh.
3. **Fleksibilitas Hierarkis:** Agglomerative Hierarchical Clustering memberikan keunggulan interpretatif melalui visualisasi dendrogram yang memungkinkan pengambil keputusan memilih ambang batas granularitas kluster secara dinamis tanpa harus menebak nilai $K$ di awal.
4. **Validasi Tanpa Label:** Karena ketiadaan variabel target terikat, evaluasi kualitas kluster wajib menggunakan metrik internal terstandarisasi. Kombinasi antara **Metode Elbow** dan **Silhouette Coefficient** memberikan kepastian analitis dalam menentukan jumlah kluster optimal yang seimbang antara homogenitas internal dan separasi eksternal.

---

# DAFTAR PUSTAKA

1. Arthur, D., & Vassilvitskii, S. (2007). k-means++: The advantages of careful seeding. In *Proceedings of the Eighteenth Annual ACM-SIAM Symposium on Discrete Algorithms* (pp. 1027–1035). Philadelphia: Society for Industrial and Applied Mathematics.
2. Davies, D. L., & Bouldin, D. W. (1979). A cluster separation measure. *IEEE Transactions on Pattern Analysis and Machine Intelligence*, PAMI-1(2), 224–227.
3. Ester, M., Kriegel, H.-P., Sander, J., & Xu, X. (1996). A density-based algorithm for discovering clusters in large spatial databases with noise. In *Proceedings of the Second International Conference on Knowledge Discovery and Data Mining (KDD-96)* (pp. 226–231). AAAI Press.
4. Han, J., Kamber, M., & Pei, J. (2012). *Data Mining: Concepts and Techniques* (3rd ed.). Waltham: Morgan Kaufmann Publishers.
5. Kaufman, L., & Rousseeuw, P. J. (1990). *Finding Groups in Data: An Introduction to Cluster Analysis*. New York: John Wiley & Sons.
6. Lloyd, S. (1982). Least squares quantization in PCM. *IEEE Transactions on Information Theory*, 28(2), 129–137.
7. MacQueen, J. (1967). Some methods for classification and analysis of multivariate observations. In *Proceedings of the Fifth Berkeley Symposium on Mathematical Statistics and Probability* (Vol. 1, pp. 281–297). Berkeley: University of California Press.
8. Matulatan, T., & Fadli, M. (2026). *Rencana Tugas Mahasiswa (RTM) dan Rencana Pembelajaran Semester (RPS) Penambangan Data (INF11121)*. Tanjungpinang: Program Studi S1 Teknik Informatika, Universitas Maritim Raja Ali Haji.
9. Pedregosa, F., Varoquaux, G., Gramfort, A., Michel, V., Thirion, B., Grisel, O., Blondel, M., Prettenhofer, P., Weiss, R., Dubourg, V., Vanderplas, J., Passos, A., Cournapeau, D., Brucher, M., Perrot, M., & Duchesnay, É. (2011). Scikit-learn: Machine learning in Python. *Journal of Machine Learning Research*, 12, 2825–2830.
10. Rousseeuw, P. J. (1987). Silhouettes: A graphical aid to the interpretation and validation of cluster analysis. *Journal of Computational and Applied Mathematics*, 20, 53–65.
11. Tan, P.-N., Steinbach, M., Karpatne, A., & Kumar, V. (2019). *Introduction to Data Mining* (2nd ed.). New York: Pearson Education.
12. Ward, J. H. (1963). Hierarchical grouping to optimize an objective function. *Journal of the American Statistical Association*, 58(301), 236–244.
