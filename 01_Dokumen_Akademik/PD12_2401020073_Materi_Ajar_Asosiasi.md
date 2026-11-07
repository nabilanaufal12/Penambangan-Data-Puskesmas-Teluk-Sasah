# PENAMBANGAN DATA (INF11121)
## MATERI AJAR MINGGU XII: TEKNIK ASOSIASI (*ASSOCIATION RULE MINING*)

---

* **Mata Kuliah**: Penambangan Data (INF11121)
* **Semester / SKS**: Ganjil 2026/2027 / 3 SKS
* **Program Studi**: S1 Teknik Informatika
* **Fakultas**: Fakultas Teknik dan Teknologi Kemaritiman
* **Perguruan Tinggi**: Universitas Maritim Raja Ali Haji (UMRAH)
* **Dosen Pengampu**: Tekad Matulatan, S.T., M.Kom. & Muhamad Fadli, S.T., M.Kom.
* **Capaian Pembelajaran (Sub-CPMK0414)**: Mahasiswa mampu mengidentifikasi dan menerapkan penyelesaian masalah asosiasi serta melakukan analisis keranjang pasar (*market basket analysis*) beserta metrik pengukurannya.
* **Identitas Mahasiswa**:
  * **Nama**: Nabil
  * **NIM**: 2401020073
* **Format Berkas Luaran RTM**: `PD12_2401020073.pdf` / `PD12_2401020073.docx`
* **Cover Halaman Depan Wajib**: `Penambangan Data - Minggu 12 - 2401020073 - Nabil`

---

## DAFTAR ISI

1. [BAB I: KONSEP DASAR PENAMBANGAN ATURAN ASOSIASI DAN ANALISIS KERANJANG PASAR](#bab-i-konsep-dasar-penambangan-aturan-asosiasi-dan-analisis-keranjang-pasar)
   * 1.1 Definisi Fundamental dan Terminologi Basis Data Transaksional
   * 1.2 Formulasi Formal Aturan Asosiasi
   * 1.3 Ruang Pencarian Kisi (*Lattice Search Space*) dan Tantangan Kompleksitas
   * 1.4 Penerapan Nyata dalam Domain Ritel, Web, dan Bioinformatika
2. [BAB II: METRIK EVALUASI DAN VALIDASI ATURAN ASOSIASI](#bab-ii-metrik-evaluasi-dan-validasi-aturan-asosiasi)
   * 2.1 Metrik Support (Dukungan Frekuensi)
   * 2.2 Metrik Confidence (Tingkat Kepastian Bersyarat)
   * 2.3 Metrik Lift (Korelasi dan Ketergantungan Statistik)
   * 2.4 Metrik Conviction (Implikasi Logis dan Penanganan Aturan Semu)
   * 2.5 Metrik Leverage (Pengukuran Deviasi Probabilitas Bersama)
   * 2.6 Sifat *Null-Invariance*, Imbalance Ratio (IR), dan Ukuran Alternatif
3. [BAB III: ALGORITMA APRIORI — PRINSIP DASAR DAN PEMBANGKITAN KANDIDAT](#bab-iii-algoritma-apriori--prinsip-dasar-dan-pembangkitan-kandidat)
   * 3.1 Prinsip Apriori (*Downward-Closure / Anti-Monotonicity Property*)
   * 3.2 Prosedur Pembangkitan Kandidat: *Join Step* ($L_{k-1} \bowtie L_{k-1}$) dan *Prune Step*
   * 3.3 Ekstraksi Aturan Asosiasi dari *Frequent Itemsets*
   * 3.4 Analisis Kompleksitas Komputasi dan Keterbatasan I/O Disk
4. [BAB IV: ALGORITMA FP-GROWTH — PENAMBANGAN POLA FREKUEN TANPA KANDIDAT](#bab-iv-algoritma-fp-growth--penambangan-pola-frekuen-tanpa-kandidat)
   * 4.1 Konsep Struktur Data *Frequent Pattern Tree* (FP-Tree) dan *Header Table*
   * 4.2 Prosedur Pembangunan FP-Tree melalui 2-Pass Scan
   * 4.3 Pembangkitan *Conditional Pattern Base* (Jalur Prefiks)
   * 4.4 Rekonstruksi *Conditional FP-Tree* dan Penambangan Rekursif
   * 4.5 Analisis Efisiensi Waktu dan Manajemen Memori vs Apriori
5. [BAB V: ALGORITMA ECLAT — PENDEKATAN KISI DENGAN FORMAT VERTIKAL](#bab-v-algoritma-eclat--pendekatan-kisi-dengan-format-vertikal)
   * 5.1 Format Representasi Basis Data: Horizontal vs Vertikal (TID-List)
   * 5.2 Operasi Irisan Himpunan (*Set / Bitset Intersection*)
   * 5.3 Strategi Penelusuran Kisi (*Lattice Traversal*: Depth-First Search)
   * 5.4 Kelebihan dan Kelemahan Skalabilitas Memori Vertikal
6. [BAB VI: ALGORITMA OPUS DAN OPTIMASI RUANG PENCARIAN](#bab-vi-algoritma-opus-dan-optimasi-ruang-pencarian)
   * 6.1 Latar Belakang dan Konsep *Optimized Pruning for Unordered Search* (OPUS)
   * 6.2 Mekanisme Pemangkasan Dinamis (*Dynamic Pruning*)
   * 6.3 Kemampuan *Anytime Performance* dan Pencarian Terarah
   * 6.4 Posisi OPUS dalam Ekosistem Algoritma Asosiasi Modern
7. [BAB VII: SIMULASI PERHITUNGAN MANUAL LANGKAH DEMI LANGKAH (*HAND CALCULATION TRACE*)](#bab-vii-simulasi-perhitungan-manual-langkah-demi-langkah-hand-calculation-trace)
   * 7.1 Deskripsi Dataset Transaksional Mini (8 Transaksi, 5 Item Produk)
   * 7.2 Parameter Ambang Batas ($\text{min\_sup} = 37.5\%$, $\text{min\_conf} = 60\%$)
   * 7.3 Simulasi Perhitungan Algoritma Apriori Tingkat Demi Tingkat ($C_1 \to L_1 \to C_2 \to L_2 \to C_3 \to L_3 \to C_4$)
   * 7.4 Simulasi Algoritma FP-Growth (FP-Tree, Header Table, Conditional Pattern Base, Frequent Itemset)
   * 7.5 Simulasi Algoritma ECLAT (Inisialisasi TID-List dan Operasi Irisan)
   * 7.6 Pembentukan dan Perhitungan Metrik Lengkap Aturan Asosiasi (Support, Confidence, Lift, Conviction, Leverage)
8. [BAB VIII: IMPLEMENTASI PRAKTIS PYTHON DAN VERIFIKASI MESIN](#bab-viii-implementasi-praktis-python-dan-verifikasi-mesin)
   * 8.1 Lingkungan Pemrograman dan Pustaka `mlxtend`
   * 8.2 Kode Sumber Lengkap Prapemrosesan, Apriori, FP-Growth, dan Aturan Asosiasi
   * 8.3 Hasil Eksekusi dan Verifikasi Keselarasan Akurasi (100% Cocok dengan Hitungan Manual)
9. [BAB IX: MATRIKS KOMPARASI DAN PANDUAN PEMILIHAN ALGORITMA](#bab-ix-matriks-komparasi-dan-panduan-pemilihan-algoritma)
   * 9.1 Tabel Komparasi Menyeluruh: Apriori vs FP-Growth vs ECLAT vs OPUS
   * 9.2 Panduan Rekomendasi Pemilihan Berdasarkan Karakteristik Dataset Industri
10. [BAB X: KESIMPULAN](#bab-x-kesimpulan)
11. [DAFTAR PUSTAKA (APA 7th Edition)](#daftar-pustaka)

---

## BAB I: KONSEP DASAR PENAMBANGAN ATURAN ASOSIASI DAN ANALISIS KERANJANG PASAR

### 1.1 Definisi Fundamental dan Terminologi Basis Data Transaksional

Penambangan aturan asosiasi (*Association Rule Mining* / ARM) merupakan paradigma pembelajaran mesin tanpa pengawasan (*unsupervised machine learning*) yang bertujuan untuk menemukan hubungan implikasi, korelasi tersembunyi, dan pola kemunculan bersama (*co-occurrence patterns*) antar-item dalam basis data transaksional bervolume besar. Metode ini pertama kali diperkenalkan oleh Rakesh Agrawal, Tomasz Imieliński, dan Arun Swami (1993) dalam konteks analisis keranjang pasar (*Market Basket Analysis*).

Secara formal, komponen basis data transaksional didefinisikan sebagai berikut:
1. **Semesta Item (*Item Universe*)**: Misalkan $I = \{i_1, i_2, \dots, i_m\}$ adalah himpunan berhingga dari $m$ item berbeda yang tersedia dalam sistem.
2. **Itemset ($k$-Itemset)**: Suatu himpunan bagian $X \subseteq I$ disebut sebagai *itemset*. Jika himpunan $X$ memuat tepat $k$ item ($|X| = k$), maka $X$ disebut sebagai $k$-itemset. Himpunan kosong $\emptyset$ adalah 0-itemset.
3. **Transaksi ($T$)**: Setiap transaksi $T$ adalah pasangan unik $(TID, X)$ di mana $TID$ merupakan pengenal unik (*Transaction Identifier*) dan $X \subseteq I$ adalah himpunan item yang dibeli/dilibatkan dalam transaksi tersebut.
4. **Basis Data Transaksional ($D$)**: Himpunan transaksi $D = \{T_1, T_2, \dots, T_N\}$ yang terdiri atas $N = |D|$ buah transaksi.

### 1.2 Formulasi Formal Aturan Asosiasi

Suatu aturan asosiasi dinyatakan dalam bentuk ekspresi implikasi:
$$X \Rightarrow Y$$
di mana:
* $X \subset I$ disebut sebagai **anteseden** (*antecedent* atau *Left-Hand Side* / LHS),
* $Y \subset I$ disebut sebagai **konsekuen** (*consequent* atau *Right-Hand Side* / RHS),
* Kedua himpunan saling lepas secara mutlak: $X \cap Y = \emptyset$, dan keduanya tidak kosong: $X \neq \emptyset, Y \neq \emptyset$.

Aturan $X \Rightarrow Y$ dibaca secara intuitif: *"Jika transaksi memuat itemset $X$, maka transaksi tersebut memiliki kecenderungan tinggi untuk juga memuat itemset $Y$."*

Penting untuk dicatat bagi mahasiswa teknik informatika bahwa tanda panah $\Rightarrow$ dalam penambangan data merepresentasikan dependensi empiris dan korelasi statistik berbasis frekuensi bersama, bukan implikasi logika formal deterministik mutlak ($X \to Y$) dan bukan hubungan kausalitas langsung (*causality*).

### 1.3 Ruang Pencarian Kisi (*Lattice Search Space*) dan Tantangan Kompleksitas

Untuk sebuah basis data yang memiliki $m$ item unik, total kemungkinan *itemset* yang dapat dibentuk adalah seluruh anggota himpunan kuasa (*power set*) dari $I$, yaitu:
$$2^m - 1 \quad \text{(tanpa himpunan kosong)}$$

Hubungan inklusi himpunan antar-itemset dapat divisualisasikan dalam bentuk kisi terurut parsial (*partially ordered set / Boolean lattice*). Sebagai contoh, jika $m = 4$ dengan $I = \{A, B, C, D\}$, maka terdapat $2^4 = 16$ kemungkinan itemset yang membentuk kisi berjenjang dari tingkat 0 (himpunan kosong $\emptyset$), tingkat 1 ($4$ buah 1-itemset), tingkat 2 ($6$ buah 2-itemset), tingkat 3 ($4$ buah 3-itemset), hingga tingkat 4 ($1$ buah 4-itemset).

Jumlah aturan asosiasi potensial yang dapat diekstraksi dari kisi tersebut bertumbuh secara kombinatorial:
$$R = \sum_{k=2}^m \binom{m}{k} \sum_{j=1}^{k-1} \binom{k}{j} = 3^m - 2^{m+1} + 1$$

Jika suatu supermarket memiliki $m = 100$ jenis barang umum:
* Jumlah itemset potensial: $2^{100} - 1 \approx 1.267 \times 10^{30}$.
* Jumlah aturan asosiasi potensial: $3^{100} - 2^{101} + 1 \approx 5.15 \times 10^{47}$.

Eksplorasi ruang pencarian berukuran super-eksponensial ini mustahil dilakukan melalui pendekatan pencarian membabi buta (*brute-force search*). Oleh karena itu, seluruh algoritma modern menerapkan strategi pemangkasan (*pruning*) berbasis ambang batas frekuensi minimum (*minimum support threshold*).

### 1.4 Penerapan Nyata dalam Domain Ritel, Web, dan Bioinformatika

1. **Tata Letak Toko dan Manajemen Ritel (*Cross-Selling*)**: Menempatkan produk yang berasosiasi tinggi (misal: Roti dan Selai Cokelat) di rak yang saling berdekatan, atau meletakkannya di ujung lorong berlawanan agar pelanggan terdorong menyusuri seluruh lorong belanja.
2. **Sistem Rekomendasi E-Commerce**: Menyajikan saran *"Pelanggan yang membeli produk ini juga membeli produk tersebut"* secara *real-time* saat pengunjung menambahkan barang ke keranjang digital.
3. **Analisis Log Server Web (*Web Usage Mining*)**: Mengidentifikasi halaman web yang sering diakses bersamaan dalam satu sesi navigasi pengguna untuk optimasi *caching* dan desain arsitektur tautan internal.
4. **Bioinformatika dan Genomika**: Menemukan ko-ekspresi gen (*gene co-expression*) dan kombinasi mutasi DNA yang sering muncul bersama pada pasien penderita kelainan genetik tertentu.
5. **Deteksi Pola Serangan Jaringan (*Intrusion Detection*)**: Menemukan korelasi urutan paket data yang mengindikasikan serangan siber multi-vektor.

---

## BAB II: METRIK EVALUASI DAN VALIDASI ATURAN ASOSIASI

Tugas penambangan aturan asosiasi umumnya dipecah menjadi dua sub-masalah:
1. **Pembangkitan Pola Frekuen (*Frequent Itemset Generation*)**: Menemukan seluruh itemset yang memiliki nilai dukungan (*support*) $\ge \text{min\_sup}$.
2. **Pembangkitan Aturan (*Rule Generation*)**: Mengekstraksi aturan dengan tingkat kepastian (*confidence*) $\ge \text{min\_conf}$ dari frequent itemsets yang ditemukan.

Untuk memilah aturan yang bermakna dan membuang aturan semu (*spurious rules*), digunakan serangkaian metrik evaluasi statistik berikut.

### 2.1 Metrik Support (Dukungan Frekuensi)

Support mengukur seberapa sering suatu itemset atau aturan muncul di dalam seluruh transaksi basis data $D$.

* **Support Count ($\sigma$)**: Jumlah transaksi absolut yang memuat itemset $X$:
  $$\sigma(X) = |\{T \in D \mid X \subseteq T\}|$$

* **Support Relatif Itemset**:
  $$\text{Support}(X) = \frac{\sigma(X)}{|D|} = P(X)$$

* **Support Aturan Asosiasi**: Peluang bersama bahwa sebuah transaksi yang dipilih secara acak memuat anteseden $X$ sekaligus konsekuen $Y$:
  $$\text{Support}(X \Rightarrow Y) = \frac{\sigma(X \cup Y)}{|D|} = P(X \cup Y)$$

Support berfungsi sebagai filter awal untuk memangkas itemset langka yang tidak memiliki signifikansi ekonomi atau statistik, sehingga mencegah beban komputasi berlebihan.

### 2.2 Metrik Confidence (Tingkat Kepastian Bersyarat)

Confidence mengukur reliabilitas aturan, yaitu estimasi peluang bersyarat $P(Y \mid X)$ bahwa suatu transaksi memuat konsekuen $Y$ dengan syarat transaksi tersebut telah memuat anteseden $X$:

$$\text{Confidence}(X \Rightarrow Y) = \frac{\sigma(X \cup Y)}{\sigma(X)} = \frac{\text{Support}(X \cup Y)}{\text{Support}(X)} = \frac{P(X \cup Y)}{P(X)} = P(Y \mid X)$$

Nilai confidence berada pada rentang interval $[0, 1]$ atau $0\%$ hingga $100\%$. Semakin mendekati 1, semakin tinggi kepastian bahwa kemunculan $X$ akan disertai dengan kemunculan $Y$.

### 2.3 Metrik Lift (Korelasi dan Ketergantungan Statistik)

Kelemahan fatal dari confidence adalah ketidakmampuannya memperhitungkan frekuensi dasar (*baseline popularity*) dari konsekuen $Y$. Jika $Y$ dibeli oleh 90% pelanggan, aturan apapun dengan konsekuen $Y$ akan memiliki nilai confidence tinggi meskipun $X$ dan $Y$ sebenarnya tidak berhubungan sama sekali.

Lift mengatasi masalah ini dengan membandingkan nilai confidence terhadap peluang independen dari konsekuen $Y$:

$$\text{Lift}(X \Rightarrow Y) = \frac{\text{Confidence}(X \Rightarrow Y)}{\text{Support}(Y)} = \frac{\text{Support}(X \cup Y)}{\text{Support}(X) \cdot \text{Support}(Y)} = \frac{P(X \cup Y)}{P(X) \cdot P(Y)}$$

Interpretasi nilai Lift:
* $\text{Lift}(X \Rightarrow Y) > 1$: **Asosiasi Positif (Komplementer)**. Itemset $X$ dan $Y$ muncul bersama lebih sering daripada ekspektasi acak. Kemunculan $X$ meningkatkan peluang kemunculan $Y$.
* $\text{Lift}(X \Rightarrow Y) = 1$: **Independen**. Kemunculan $X$ dan $Y$ murni kebetulan statistik yang tidak saling mempengaruhi ($P(X \cap Y) = P(X)P(Y)$).
* $\text{Lift}(X \Rightarrow Y) < 1$: **Asosiasi Negatif (Substitutif)**. Itemset $X$ dan $Y$ saling meniadakan atau berkompetisi. Pembelian $X$ justru menurunkan peluang pembelian $Y$.

Sifat penting: Lift bersifat simetris terhadap penukaran anteseden dan konsekuen, yaitu $\text{Lift}(X \Rightarrow Y) = \text{Lift}(Y \Rightarrow X)$.

### 2.4 Metrik Conviction (Implikasi Logis dan Penanganan Aturan Semu)

Conviction diperkenalkan oleh Sergey Brin, Rajeev Motwani, Jeffrey D. Ullman, dan Shalom Tsur (1997) untuk mengukur derajat implikasi logis asimetris. Conviction membandingkan probabilitas bahwa $X$ muncul tanpa $Y$ jika keduanya independen terhadap frekuensi riil kemunculan $X$ tanpa $Y$:

$$\text{Conviction}(X \Rightarrow Y) = \frac{1 - \text{Support}(Y)}{1 - \text{Confidence}(X \Rightarrow Y)} = \frac{P(X) \cdot P(\neg Y)}{P(X \cap \neg Y)}$$

Interpretasi nilai Conviction:
* $\text{Conviction} = 1$: Mengindikasikan bahwa $X$ dan $Y$ independen.
* $\text{Conviction} > 1$: Mengindikasikan bahwa aturan memiliki kekuatan implikasi logis terarah.
* $\text{Conviction} = \infty$: Terjadi ketika $\text{Confidence} = 1.0$ (penyebut menjadi 0). Hal ini berarti $P(X \cap \neg Y) = 0$, yaitu itemset $X$ tidak pernah muncul tanpa didampingi $Y$ (implikasi deterministik sempurna).

Berbeda dengan Lift yang simetris, Conviction bersifat **asimetris**: $\text{Conviction}(X \Rightarrow Y) \neq \text{Conviction}(Y \Rightarrow X)$, menjadikannya sangat ideal untuk memodelkan hubungan anteseden-konsekuen berarah.

### 2.5 Metrik Leverage (Piatetsky-Shapiro Measure)

Leverage (Gregory Piatetsky-Shapiro, 1991) mengukur selisih absolut antara frekuensi kemunculan bersama $X$ dan $Y$ terhadap frekuensi yang diharapkan jika keduanya beroperasi secara independen:

$$\text{Leverage}(X \Rightarrow Y) = \text{Support}(X \cup Y) - (\text{Support}(X) \cdot \text{Support}(Y)) = P(X \cup Y) - P(X)P(Y)$$

Nilai Leverage berada pada rentang $[-0.25, +0.25]$:
* $\text{Leverage} = 0$: $X$ dan $Y$ sepenuhnya independen.
* $\text{Leverage} > 0$: Menunjukkan jumlah transaksi surplus di atas ekspektasi independensi.
* $\text{Leverage} < 0$: Menunjukkan defisit transaksi bersama.

Kelebihan Leverage dibanding Lift adalah penekanannya pada volume proporsional. Lift dapat bernilai sangat tinggi untuk dua barang langka yang kebetulan dibeli bersama sekali, sedangkan Leverage akan memberikan bobot yang realistis sesuai kontribusi transaksi nyata.

### 2.6 Sifat *Null-Invariance*, Imbalance Ratio (IR), dan Ukuran Alternatif

Dalam basis data keranjang pasar nyata, sebagian besar transaksi tidak memuat item $X$ maupun item $Y$ (dikenal sebagai *null transactions* atau transaksi kosong terhadap $X$ dan $Y$).
Suatu ukuran evaluasi dikatakan memiliki sifat **Null-Invariance** jika nilainya tidak berubah ketika sejumlah besar transaksi yang tidak memuat $X$ maupun $Y$ ditambahkan ke dalam basis data.

* Metrik yang **TIDAK** *null-invariant*: $\chi^2$ (*Chi-Square*), Lift, dan Leverage. Metrik-metrik ini rentan terdistorsi apabila jumlah total transaksi kosong melonjak drastis.
* Metrik yang **BERSIFAT** *null-invariant*:
  1. **Kulczynski Measure**:
     $$\text{Kulc}(X, Y) = \frac{1}{2} (P(Y \mid X) + P(X \mid Y)) = \frac{1}{2} \left( \frac{\text{Support}(X \cup Y)}{\text{Support}(X)} + \frac{\text{Support}(X \cup Y)}{\text{Support}(Y)} \right)$$
  2. **Cosine Similarity Itemset**:
     $$\text{Cosine}(X, Y) = \frac{\text{Support}(X \cup Y)}{\sqrt{\text{Support}(X) \cdot \text{Support}(Y)}}$$
  3. **Imbalance Ratio (IR)**:
     $$\text{IR}(X, Y) = \frac{|\text{Support}(X) - \text{Support}(Y)|}{\text{Support}(X) + \text{Support}(Y) - \text{Support}(X \cup Y)}$$
     IR mengukur ketidakseimbangan frekuensi marginal antara anteseden dan konsekuen.

---

## BAB III: ALGORITMA APRIORI — PRINSIP DASAR DAN PEMBANGKITAN KANDIDAT

### 3.1 Prinsip Apriori (*Downward-Closure / Anti-Monotonicity Property*)

Algoritma Apriori dikembangkan oleh Agrawal dan Srikant pada tahun 1994. Landasan matematis paling esensial dari algoritma ini adalah **Prinsip Apriori**:

> *"Jika suatu itemset bersifat frekuen, maka seluruh himpunan bagian (subset) darinya pasti bersifat frekuen."*
>
> Secara kontrapositif (bentuk pemangkasan):
> *"Jika suatu itemset bersifat tidak frekuen (infrequent), maka seluruh himpunan atas (superset) darinya dijamin pasti tidak frekuen."*

Secara formal, sifat anti-monotonik support menyatakan bahwa untuk setiap itemset $X$ dan $Y$:
$$X \subseteq Y \implies \text{Support}(Y) \le \text{Support}(X)$$

Jika $\text{Support}(X) < \text{min\_sup}$, maka untuk setiap $Y \supseteq X$, sudah pasti $\text{Support}(Y) < \text{min\_sup}$. Dengan demikian, seluruh sub-pohon (*sub-lattice*) yang berakar di atas $X$ dapat dipangkas (*pruned*) seketika tanpa perlu menghitung frekuensinya pada basis data!

### 3.2 Prosedur Pembangkitan Kandidat: *Join Step* dan *Prune Step*

Algoritma Apriori beroperasi secara berjenjang (*level-wise / breadth-first search*):
1. Menemukan seluruh 1-itemset frekuen ($L_1$) melalui pemindaian pertama ke basis data.
2. Membangkitkan himpunan kandidat $k$-itemset ($C_k$) dari himpunan itemset frekuen sebelumnya ($L_{k-1}$).
3. Menghitung frekuensi kemunculan setiap kandidat dalam basis data.
4. Memangkas kandidat yang tidak memenuhi ambang batas untuk menghasilkan $L_k$.
5. Proses berhenti saat $C_k = \emptyset$ atau $L_k = \emptyset$.

Pembangkitan kandidat $C_k$ dari $L_{k-1}$ dilakukan melalui dua langkah ketat:
* **Langkah Penggabungan (*Join Step*)**:
  Item di dalam setiap itemset diurutkan secara leksikografis (misal alfabetis). Dua itemset $p, q \in L_{k-1}$ digabungkan jika dan hanya jika keduanya memiliki $k-2$ item pertama yang identik:
  $$(p[1] = q[1]) \land (p[2] = q[2]) \land \dots \land (p[k-2] = q[k-2]) \land (p[k-1] < q[k-1])$$
  Hasil penggabungan menghasilkan kandidat baru berukuran $k$:
  $$c = \{p[1], p[2], \dots, p[k-2], p[k-1], q[k-1]\}$$
* **Langkah Pemangkasan (*Prune Step*)**:
  Untuk setiap kandidat $c \in C_k$, periksa apakah seluruh subset berukuran $(k-1)$ dari $c$ terdapat di dalam $L_{k-1}$. Jika ditemukan setidaknya satu subset yang tidak ada di dalam $L_{k-1}$, maka berdasarkan Prinsip Apriori, kandidat $c$ segera dihapus dari $C_k$ sebelum pemindaian data dilakukan.

### 3.3 Ekstraksi Aturan Asosiasi dari *Frequent Itemsets*

Setelah seluruh *frequent itemset* ditemukan, aturan asosiasi $X \Rightarrow (Y - X)$ dibangkitkan untuk setiap $Y \in L$ dan setiap subset tak kosong $X \subset Y$.

Teorema Anti-Monotonik Confidence:
Untuk sebuah itemset frekuen yang tetap $Y$, jika sebuah aturan $X \Rightarrow (Y - X)$ tidak memenuhi $\text{min\_conf}$, maka untuk setiap subset $X' \subset X$, aturan $X' \Rightarrow (Y - X')$ juga dijamin tidak memenuhi $\text{min\_conf}$:
$$X' \subset X \implies \text{Support}(X') \ge \text{Support}(X) \implies \frac{\text{Support}(Y)}{\text{Support}(X')} \le \frac{\text{Support}(Y)}{\text{Support}(X)}$$
Oleh karena itu, pembangkitan aturan dapat dilakukan secara efisien menggunakan pendekatan *pruning* bertingkat dari konsekuen 1-item menuju konsekuen multi-item.

### 3.4 Analisis Kompleksitas Komputasi dan Keterbatasan I/O Disk

Meskipun memangkas ruang pencarian secara signifikan, algoritma Apriori memiliki dua kelemahan mendasar:
1. **Pembangkitan Kandidat Berukuran Masif**: Jika terdapat $10^4$ buah 1-itemset frekuen di $L_1$, jumlah kandidat 2-itemset $C_2$ yang dibangkitkan adalah $\binom{10^4}{2} \approx 5 \times 10^7$ itemset!
2. **Biaya I/O Disk Berulang (*Multiple Database Scans*)**: Jika panjang transaksi maksimum adalah $k_{max}$, Apriori membutuhkan $k_{max} + 1$ kali pemindaian penuh terhadap basis data disk. Pada basis data industri berskala gigabyte hingga terabyte, biaya pembacaan disk menjadi *bottleneck* komputasi utama.

---

## BAB IV: ALGORITMA FP-GROWTH — PENAMBANGAN POLA FREKUEN TANPA KANDIDAT

### 4.1 Konsep Struktur Data *Frequent Pattern Tree* (FP-Tree) dan *Header Table*

Algoritma FP-Growth (*Frequent Pattern Growth*) diperkenalkan oleh Jiawei Han, Jian Pei, dan Yiwen Yin pada tahun 2000 untuk mengatasi kedua kelemahan utama Apriori. Filosofi kunci FP-Growth adalah memadatkan basis data transaksional ke dalam struktur pohon terkompresi di memori (*Frequent Pattern Tree* / FP-Tree) dan menambang pola frekuen secara rekursif menggunakan pendekatan *divide-and-conquer* **tanpa pernah membangkitkan kandidat**.

Struktur FP-Tree terdiri dari:
1. **Simpul Akar (*Root Node*)**: Simpul kosong bertanda `null`.
2. **Simpul Item (*Item Nodes*)**: Memuat tiga atribut:
   * `item-name`: Pengenal item.
   * `count`: Jumlah transaksi yang berbagi jalur prefiks hingga simpul ini.
   * `node-link`: Penunjuk (*pointer*) ke simpul berikutnya dalam FP-Tree yang memiliki `item-name` serupa.
3. **Tabel Kepala (*Header Table*)**: Menyimpan daftar seluruh 1-item frekuen yang terurut menurun berdasarkan frekuensi dukungannya (*L-order*), disertai penunjuk awal (*head pointer*) ke simpul pertama item tersebut di dalam pohon.

### 4.2 Prosedur Pembangunan FP-Tree melalui 2-Pass Scan

FP-Tree dibangun hanya dengan **tepat 2 kali pemindaian basis data**:
* **Pemindaian 1 (*First Pass*)**:
  1. Hitung frekuensi kemunculan setiap item tunggal dalam basis data.
  2. Eliminasi item yang memiliki frekuensi $< \text{min\_sup\_count}$.
  3. Urutkan item yang lolos secara menurun (*descending*) berdasarkan frekuensinya. Urutan ini disebut **L-Order** (misal: $Susu:6, Roti:6, Keju:5, Gula:4, Kopi:3$).
* **Pemindaian 2 (*Second Pass*)**:
  1. Inisialisasi simpul akar FP-Tree dengan label `null`.
  2. Untuk setiap transaksi dalam basis data, saring hanya item yang frekuen dan urutkan kembali item-item tersebut sesuai L-Order.
  3. Masukkan transaksi terurut tersebut ke dalam FP-Tree. Jika prefiks transaksi sudah ada di pohon, inkremen nilai `count` pada simpul yang bersesuaian; jika bercabang, buat simpul anak baru dengan `count = 1`.
  4. Perbarui rantai `node-link` dari *Header Table*.

### 4.3 Pembangkitan *Conditional Pattern Base* (Jalur Prefiks)

Penambangan dilakukan dari item dengan frekuensi terendah pada *Header Table* (pendekatan *bottom-up*).
Untuk suatu item target $\alpha$:
1. Telusuri seluruh simpul berlabel $\alpha$ di dalam FP-Tree menggunakan rantai `node-link`.
2. Untuk setiap simpul $\alpha$, telusuri jalurnya ke arah atas (*bottom-up traversal*) menuju simpul akar untuk mendapatkan **jalur prefiks** (*prefix path*).
3. Bobot frekuensi dari jalur prefiks tersebut sama dengan nilai `count` pada simpul $\alpha$ yang bersangkutan.
4. Kumpulan seluruh jalur prefiks untuk item $\alpha$ disebut sebagai **Conditional Pattern Base** dari $\alpha$.

### 4.4 Rekonstruksi *Conditional FP-Tree* dan Penambangan Rekursif

Dari *Conditional Pattern Base* suatu item $\alpha$:
1. Akumulasikan frekuensi kemunculan setiap item di dalam kumpulan jalur prefiks tersebut.
2. Hapus item yang total frekuensi akumulasinya $< \text{min\_sup\_count}$.
3. Bangun pohon terkompresi lokal yang disebut **Conditional FP-Tree** untuk $\alpha$.
4. Jika *Conditional FP-Tree* hanya memuat satu jalur tunggal (*single path*), seluruh kombinasi subset dari simpul-simpul di jalur tersebut langsung digabungkan dengan $\alpha$ untuk membentuk *frequent patterns*.
5. Jika memuat banyak cabang (*multiple paths*), algoritma dipanggil secara rekursif.

### 4.5 Analisis Efisiensi Waktu dan Manajemen Memori vs Apriori

Keunggulan dramatis FP-Growth terhadap Apriori:
1. **Hanya 2 Kali Pemindaian I/O**: Tidak peduli seberapa panjang itemset yang terbentuk, basis data disk hanya dibaca tepat dua kali.
2. **Bebas Pembangkitan Kandidat (*No Candidate Generation*)**: Menghindari alokasi memori untuk jutaan kandidat kombinatorial yang tidak berguna.
3. **Kompresi Data Signifikan**: Transaksi yang memiliki prefiks serupa digabungkan ke dalam simpul yang sama, menghasilkan jejak memori (*memory footprint*) yang jauh lebih kecil daripada data mentah.

---

## BAB V: ALGORITMA ECLAT — PENDEKATAN KISI DENGAN FORMAT VERTIKAL

### 5.1 Format Representasi Basis Data: Horizontal vs Vertikal (TID-List)

Algoritma ECLAT (*Equivalence Class Clustering and along with Lattice Traversal*) dikembangkan oleh Mohammed J. Zaki pada tahun 2000. Perbedaan paling mencolok antara ECLAT dengan Apriori dan FP-Growth terletak pada format representasi data:

* **Format Horizontal (Layout Tradisional)**:
  Setiap baris merepresentasikan sebuah transaksi yang diidentifikasi oleh TID, diikuti oleh daftar item yang terdapat di dalamnya:
  $$TID_1: \{i_1, i_2, i_5\}, \quad TID_2: \{i_2, i_4\}, \quad \dots$$
* **Format Vertikal (Inverted Index / TID-List)**:
  Setiap baris merepresentasikan sebuah item tunggal, diikuti oleh daftar seluruh TID dari transaksi yang memuat item tersebut:
  $$i_1: \{T_1, T_3, T_5\}, \quad i_2: \{T_1, T_2, T_4\}, \quad \dots$$

### 5.2 Operasi Irisan Himpunan (*Set / Bitset Intersection*)

Keunggulan utama representasi vertikal adalah bahwa support dari gabungan dua itemset $X$ dan $Y$ dapat dihitung secara instan melalui **operasi irisan himpunan (*set intersection*)** terhadap daftar TID masing-masing:

$$\text{TID}(X \cup Y) = \text{TID}(X) \cap \text{TID}(Y)$$

Nilai support count langsung diperoleh dari kardinalitas himpunan hasil irisan:
$$\sigma(X \cup Y) = |\text{TID}(X \cup Y)| = |\text{TID}(X) \cap \text{TID}(Y)|$$

Dengan demikian, untuk menentukan apakah $\{A, B, C\}$ adalah itemset frekuen, sistem **sama sekali tidak perlu memindai basis data transaksi**. Sistem cukup melakukan operasi irisan antara $\text{TID}(AB)$ dan $\text{TID}(AC)$! Dalam implementasi perangkat keras modern, operasi ini dapat dieksekusi dengan kecepatan sangat tinggi menggunakan operasi logika `AND` pada representasi vektor bit (*bitset / bitmap array*).

### 5.3 Strategi Penelusuran Kisi (*Lattice Traversal*: Depth-First Search)

ECLAT mengorganisasikan penelusuran kisi itemset menggunakan strategi pencarian mendalam (*Depth-First Search* / DFS) berbasis kelas ekuivalensi (*equivalence classes*):
* Dua $k$-itemset berada dalam satu kelas ekuivalensi prefiks jika keduanya berbagi $(k-1)$ item awal yang sama.
* Pasangan itemset dalam satu kelas ekuivalensi diiriskan untuk menghasilkan $(k+1)$-itemset baru.
* Penelusuran langsung dilanjutkan ke cabang anak yang lebih dalam sebelum menelusuri cabang saudara (*sibling*).

### 5.4 Kelebihan dan Kelemahan Skalabilitas Memori Vertikal

* **Kelebihan**:
  * Tidak memerlukan pemindaian ulang data mentah.
  * Komputasi irisan sangat cepat (*CPU cache friendly*).
  * Sangat efisien untuk basis data yang bersifat renggang (*sparse datasets*).
* **Kelemahan**:
  * Jika basis data sangat padat (*dense*) dan memiliki miliaran transaksi, panjang daftar TID (*TID-list*) menjadi sangat besar sehingga membebani kapasitas RAM.
  * Membutuhkan langkah prapemrosesan awal untuk membalik format horizontal ke format vertikal.

---

## BAB VI: ALGORITMA OPUS DAN OPTIMASI RUANG PENCARIAN

### 6.1 Latar Belakang dan Konsep *Optimized Pruning for Unordered Search* (OPUS)

Algoritma OPUS (*Optimized Pruning for Unordered Search*) dirumuskan oleh Geoffrey I. Webb (1995, 2000) untuk mengatasi kekakuan strategi penelusuran ruang pencarian tradisional. Baik Apriori maupun ECLAT sangat bergantung pada urutan item statis (*fixed item ordering*) untuk mencegah redundansi pencarian. Namun, urutan statis tersebut membatasi kemampuan algoritma untuk memangkas cabang pencarian secara fleksibel berdasarkan informasi lokal yang ditemukan di tengah proses pencarian.

OPUS memperkenalkan paradigma penelusuran tanpa urutan (*unordered search*) yang memandang ruang pencarian sebagai eksplorasi himpunan bebas urutan, dilengkapi dengan mekanisme pemangkasan sistematis yang menjamin setiap itemset unik dievaluasi tepat satu kali tanpa redundansi.

### 6.2 Mekanisme Pemangkasan Dinamis (*Dynamic Pruning*)

Pada setiap simpul penelusuran, OPUS mengelola dua himpunan disjoin:
1. **Himpunan Saat Ini (*Current Set* / $P$)**: Itemset yang sedang dieksplorasi.
2. **Himpunan Tersedia (*Available Set* / $A$)**: Kumpulan item yang masih diizinkan untuk digabungkan dengan $P$ guna membentuk kandidat simpul anak.

Kelebihan utama OPUS adalah kemampuan **Pemangkasan Dinamis Dua Arah**:
* Jika item $i \in A$ terbukti tidak memenuhi kriteria kelayakan (misalnya support atau nilai optimasi fungsi heuristik tidak terpenuhi saat digabung dengan $P$), maka item $i$ segera dihapus secara permanen dari $A$ untuk seluruh sub-pohon di bawah $P$.
* Penghapusan item dari $A$ secara dinamis mereduksi faktor percabangan (*branching factor*) secara drastis pada tingkat-tingkat penelusuran berikutnya.

### 6.3 Kemampuan *Anytime Performance* dan Pencarian Terarah

Dalam aplikasi industri di mana waktu respons sangat kritis (seperti *real-time recommendation* atau penambangan interaktif), pengguna sering kali tidak dapat menunggu hingga algoritma selesai mengeksplorasi seluruh basis data secara tuntas.

OPUS secara inheren mendukung sifat **Anytime Algorithm**:
* Penelusuran dapat dihentikan kapan saja oleh batasan waktu (*timeout*), dan algoritma dijamin telah mengembalikan himpunan aturan terbaik yang berhasil ditemukan sejauh penelusuran berlangsung.
* OPUS dapat diarahkan menggunakan berbagai fungsi optimasi selain support (misal: mencari $k$ aturan teratas dengan nilai Lift atau Leverage tertinggi), menjadikannya cikal bakal dari teknik penambangan pola berbobot modern (*weighted pattern mining*).

### 6.4 Posisi OPUS dalam Ekosistem Algoritma Asosiasi Modern

| Aspek Komparasi | Apriori | FP-Growth | ECLAT | OPUS |
| :--- | :--- | :--- | :--- | :--- |
| **Pencetus Utama** | Agrawal & Srikant (1994) | Han, Pei, & Yin (2000) | Zaki (2000) | Webb (1995, 2000) |
| **Strategi Penelusuran** | *Breadth-First* (Level-wise) | *Divide & Conquer* (Recursive) | *Depth-First Search* (Lattice) | *Unordered Branch & Bound* |
| **Struktur Data Kunci** | Hash Tree / Arrays | FP-Tree & Header Table | TID-Lists (Bitsets) | Available Sets ($A$) |
| **Pembangkitan Kandidat** | Ya (Sangat Masif) | Tidak Sama Sekali | Tidak (Irisan TID) | Terbatas & Dinamis |
| **Jumlah Scan Basis Data** | $k_{max} + 1$ kali | Tepat 2 kali | 1 kali (Konversi Vertikal) | Fleksibel / Sesuai Index |
| **Konsumsi Memori** | Menengah (Kandidat $C_k$) | Kompak / Sangat Hemat | Tinggi bila TID Padat | Efisien / Dinamis |
| **Keunggulan Utama** | Sederhana & Intuitif | Kecepatan Tinggi & Skalabel | Operasi Bitwise Cepat | *Anytime Search* & Optimasi Heuristik |

---

## BAB VII: SIMULASI PERHITUNGAN MANUAL LANGKAH DEMI LANGKAH (*HAND CALCULATION TRACE*)

Untuk memberikan pemahaman yang mendalam, terverifikasi, dan transparan bagi mahasiswa teknik informatika, berikut disajikan simulasi perhitungan manual langkah demi langkah (*step-by-step trace*) menggunakan dataset transaksional mini keranjang pasar.

### 7.1 Deskripsi Dataset Transaksional Mini

Sebuah minimarket kampus mencatat $N = 8$ transaksi belanja harian ($T_1$ s/d $T_8$) yang melibatkan $5$ jenis produk pangan:
* **A**: Roti Tawar (*Bread*)
* **B**: Susu Sapi (*Milk*)
* **C**: Keju Lembaran (*Cheese*)
* **D**: Kopi Bubuk (*Coffee*)
* **E**: Gula Pasir (*Sugar*)

**Tabel 7.1: Basis Data Transaksi Mentah ($D$)**

| Transaction ID (TID) | Daftar Item yang Dibeli (Nama Produk) | Kode Itemset |
| :---: | :--- | :---: |
| **T1** | Roti, Susu, Keju | {A, B, C} |
| **T2** | Roti, Susu, Kopi, Gula | {A, B, D, E} |
| **T3** | Roti, Susu, Keju | {A, B, C} |
| **T4** | Susu, Kopi, Gula | {B, D, E} |
| **T5** | Roti, Susu, Keju, Gula | {A, B, C, E} |
| **T6** | Kopi, Gula | {D, E} |
| **T7** | Roti, Keju | {A, C} |
| **T8** | Roti, Susu, Keju | {A, B, C} |

### 7.2 Parameter Ambang Batas

* Total Transaksi: $N = |D| = 8$.
* **Ambang Batas Minimum Support ($\text{min\_sup}$)**:
  $$\text{min\_sup} = 37.5\% = \frac{3}{8} \implies \text{min\_sup\_count} = 3$$
  *(Sebuah itemset harus muncul minimal pada 3 transaksi berbeda agar dinyatakan frekuen).*
* **Ambang Batas Minimum Confidence ($\text{min\_conf}$)**:
  $$\text{min\_conf} = 60.0\% = 0.60$$

---

### 7.3 Simulasi Perhitungan Algoritma Apriori Tingkat Demi Tingkat

#### Tahap 1: Pembangkitan Itemset Ukuran 1 ($C_1 \to L_1$)
Pindai seluruh transaksi $D$ untuk menghitung frekuensi kemunculan setiap item tunggal:

**Tabel 7.2: Penghitungan Kandidat 1-Itemset ($C_1$) dan Penyaringan ($L_1$)**

| Item | Transaksi Kemunculan | Support Count ($\sigma$) | Support Relatif ($P(I)$) | Status ($\sigma \ge 3$) |
| :---: | :---: | :---: | :---: | :---: |
| **{A} (Roti)** | T1, T2, T3, T5, T7, T8 | 6 | $6/8 = 0.750$ (75.0%) | **Lolos ($L_1$)** |
| **{B} (Susu)** | T1, T2, T3, T4, T5, T8 | 6 | $6/8 = 0.750$ (75.0%) | **Lolos ($L_1$)** |
| **{C} (Keju)** | T1, T3, T5, T7, T8 | 5 | $5/8 = 0.625$ (62.5%) | **Lolos ($L_1$)** |
| **{D} (Kopi)** | T2, T4, T6 | 3 | $3/8 = 0.375$ (37.5%) | **Lolos ($L_1$)** |
| **{E} (Gula)** | T2, T4, T5, T6 | 4 | $4/8 = 0.500$ (50.0%) | **Lolos ($L_1$)** |

*Hasil $L_1$*: Seluruh 5 item lolos ke tingkat berikutnya.

---

#### Tahap 2: Pembangkitan Itemset Ukuran 2 ($C_2 \to L_2$)
* **Langkah Join ($L_1 \bowtie L_1$)**:
  Kombinasikan seluruh pasangan unik dari $L_1$:
  $$\binom{5}{2} = 10 \text{ kandidat}: \{A, B\}, \{A, C\}, \{A, D\}, \{A, E\}, \{B, C\}, \{B, D\}, \{B, E\}, \{C, D\}, \{C, E\}, \{D, E\}$$
* **Langkah Prune**:
  Setiap subset 1-item dari ke-10 kandidat berada di $L_1$. Tidak ada kandidat yang dipangkas sebelum pemindaian.
* **Pemindaian Basis Data untuk $C_2$**:

**Tabel 7.3: Evaluasi Kandidat 2-Itemset ($C_2$) dan Penyaringan ($L_2$)**

| Kandidat 2-Itemset | Transaksi Kemunculan | Support Count ($\sigma$) | Support Relatif | Status ($\sigma \ge 3$) |
| :---: | :---: | :---: | :---: | :---: |
| **{A, B}** | T1, T2, T3, T5, T8 | 5 | $5/8 = 0.625$ (62.5%) | **Lolos ($L_2$)** |
| **{A, C}** | T1, T3, T5, T7, T8 | 5 | $5/8 = 0.625$ (62.5%) | **Lolos ($L_2$)** |
| **{A, D}** | T2 | 1 | $1/8 = 0.125$ (12.5%) | Dieliminasi (< 3) |
| **{A, E}** | T2, T5 | 2 | $2/8 = 0.250$ (25.0%) | Dieliminasi (< 3) |
| **{B, C}** | T1, T3, T5, T8 | 4 | $4/8 = 0.500$ (50.0%) | **Lolos ($L_2$)** |
| **{B, D}** | T2, T4 | 2 | $2/8 = 0.250$ (25.0%) | Dieliminasi (< 3) |
| **{B, E}** | T2, T4, T5 | 3 | $3/8 = 0.375$ (37.5%) | **Lolos ($L_2$)** |
| **{C, D}** | (Tidak ada) | 0 | $0/8 = 0.000$ (0.0%) | Dieliminasi (< 3) |
| **{C, E}** | T5 | 1 | $1/8 = 0.125$ (12.5%) | Dieliminasi (< 3) |
| **{D, E}** | T2, T4, T6 | 3 | $3/8 = 0.375$ (37.5%) | **Lolos ($L_2$)** |

*Hasil $L_2$*: Terdapat 5 buah 2-itemset frekuen:
$$L_2 = \{\{A, B\}, \{A, C\}, \{B, C\}, \{B, E\}, \{D, E\}\}$$

---

#### Tahap 3: Pembangkitan Itemset Ukuran 3 ($C_3 \to L_3$)
* **Langkah Join ($L_2 \bowtie L_2$)**:
  Gabungkan pasangan itemset di $L_2$ yang memiliki item pertama identik:
  * Dari $\{A, B\}$ dan $\{A, C\} \implies$ Menghasilkan kandidat $\{A, B, C\}$.
  * Dari $\{B, C\}$ dan $\{B, E\} \implies$ Menghasilkan kandidat $\{B, C, E\}$.
* **Langkah Prune (Pemeriksaan Subset Apriori)**:
  * Uji kandidat $\{A, B, C\}$:
    * Subset 2-item: $\{A, B\}, \{A, C\}, \{B, C\}$.
    * Ketiga subset berada di $L_2$. **Kandidat $\{A, B, C\}$ lolos pemangkasan**.
  * Uji kandidat $\{B, C, E\}$:
    * Subset 2-item: $\{B, C\}, \{B, E\}, \{C, E\}$.
    * Periksa keberadaan $\{C, E\}$ di $L_2$: Ternyata $\{C, E\} \notin L_2$ (karena $\sigma(\{C, E\}) = 1 < 3$).
    * **Berdasarkan Prinsip Apriori, $\{B, C, E\}$ langsung DIPANGKAS (*PRUNED*) seketika tanpa perlu menghitung frekuensinya ke database!**
* **Pemindaian Basis Data untuk $C_3$**:
  Hanya terdapat 1 kandidat yang tersisa, yaitu $\{A, B, C\}$:

**Tabel 7.4: Evaluasi Kandidat 3-Itemset ($C_3$) dan Penyaringan ($L_3$)**

| Kandidat 3-Itemset | Transaksi Kemunculan | Support Count ($\sigma$) | Support Relatif | Status ($\sigma \ge 3$) |
| :---: | :---: | :---: | :---: | :---: |
| **{A, B, C}** | T1, T3, T5, T8 | 4 | $4/8 = 0.500$ (50.0%) | **Lolos ($L_3$)** |

*Hasil $L_3$*:
$$L_3 = \{\{A, B, C\}\}$$

---

#### Tahap 4: Pembangkitan Itemset Ukuran 4 ($C_4 \to L_4$)
* Untuk membentuk 4-itemset, diperlukan minimal dua buah 3-itemset di $L_3$ yang memiliki 2 item pertama sama.
* Karena $|L_3| = 1$, maka tidak ada pasangan yang dapat digabungkan: $C_4 = \emptyset$.
* **Algoritma Apriori Berhenti secara Konvergen.**

**Rekapitulasi Seluruh Frequent Itemsets**:
* Ukuran 1 ($L_1$): $\{A\}:6, \{B\}:6, \{C\}:5, \{E\}:4, \{D\}:3$ (5 itemset)
* Ukuran 2 ($L_2$): $\{A, B\}:5, \{A, C\}:5, \{B, C\}:4, \{B, E\}:3, \{D, E\}:3$ (5 itemset)
* Ukuran 3 ($L_3$): $\{A, B, C\}:4$ (1 itemset)
* **Total Frequent Itemsets Ditemukan**: $5 + 5 + 1 = 11$ itemset.

---

### 7.4 Simulasi Algoritma FP-Growth (FP-Tree dan Conditional Pattern Base)

#### 1. Pembentukan L-Order dan Pengurutan Transaksi
Frekuensi item tunggal dari Pemindaian 1:
* $B$ (Susu): 6
* $A$ (Roti): 6
* $C$ (Keju): 5
* $E$ (Gula): 4
* $D$ (Kopi): 3

Urutan Prioritas Header Table (**L-Order**):
$$B \to A \to C \to E \to D$$

**Tabel 7.5: Rekonstruksi Transaksi Sesuai Urutan L-Order**

| TID | Transaksi Mentah | Item Frekuen Terurut L-Order |
| :---: | :--- | :--- |
| **T1** | {A, B, C} | $(B, A, C)$ |
| **T2** | {A, B, D, E} | $(B, A, E, D)$ |
| **T3** | {A, B, C} | $(B, A, C)$ |
| **T4** | {B, D, E} | $(B, E, D)$ |
| **T5** | {A, B, C, E} | $(B, A, C, E)$ |
| **T6** | {D, E} | $(E, D)$ |
| **T7** | {A, C} | $(A, C)$ |
| **T8** | {A, B, C} | $(B, A, C)$ |

---

#### 2. Konstruksi Struktur FP-Tree
Penyisipan transaksi terurut ke dalam FP-Tree:
1. Masukkan $T_1 (B, A, C)$: Simpul `(B:1)` $\to$ `(A:1)` $\to$ `(C:1)`.
2. Masukkan $T_2 (B, A, E, D)$: Berbagi prefiks $B, A$. Update `(B:2)` $\to$ `(A:2)`, buat cabang baru `(E:1)` $\to$ `(D:1)`.
3. Masukkan $T_3 (B, A, C)$: Berbagi prefiks $B, A, C$. Update `(B:3)` $\to$ `(A:3)` $\to$ `(C:2)`.
4. Masukkan $T_4 (B, E, D)$: Berbagi prefiks $B$. Update `(B:4)`, buat cabang baru dari $B$: `(E:1)` $\to$ `(D:1)`.
5. Masukkan $T_5 (B, A, C, E)$: Berbagi prefiks $B, A, C$. Update `(B:5)` $\to$ `(A:4)` $\to$ `(C:3)`, buat cabang baru dari $C$: `(E:1)`.
6. Masukkan $T_6 (E, D)$: Tidak berbagi prefiks dengan $B$. Buat cabang baru langsung dari `null`: `(E:1)` $\to$ `(D:1)`.
7. Masukkan $T_7 (A, C)$: Buat cabang baru langsung dari `null`: `(A:1)` $\to$ `(C:1)`.
8. Masukkan $T_8 (B, A, C)$: Berbagi prefiks $B, A, C$. Update `(B:6)` $\to$ `(A:5)` $\to$ `(C:4)`.

**Representasi Tekstual Struktur FP-Tree Final**:
```text
(root: null)
├── (B:6)
│   ├── (A:5)
│   │   ├── (C:4)
│   │   │   └── (E:1)
│   │   └── (E:1)
│   │       └── (D:1)
│   └── (E:1)
│       └── (D:1)
├── (E:1)
│   └── (D:1)
└── (A:1)
    └── (C:1)
```

---

#### 3. Penambangan Rekursif (Conditional Pattern Base dan Conditional FP-Tree)
Penambangan dilakukan dari item terbawah pada *Header Table* ($D \to E \to C \to A \to B$):

**Tabel 7.6: Hasil Penambangan FP-Growth Komprehensif**

| Item Target | Penelusuran Simpul & Jalur Prefiks (*Conditional Pattern Base*) | Total Akumulasi Frekuensi Item Prefiks | Kondisional FP-Tree ($\sigma \ge 3$) | Frequent Patterns yang Dihasilkan |
| :---: | :--- | :--- | :---: | :--- |
| **D (Kopi)** | • Jalur 1: $(B, A, E): 1$<br>• Jalur 2: $(B, E): 1$<br>• Jalur 3: $(E): 1$ | $E: 3$, $B: 2$, $A: 1$ | Simpul tunggal `(E:3)` | **{D}: 3**, **{D, E}: 3** |
| **E (Gula)** | • Jalur 1: $(B, A, C): 1$<br>• Jalur 2: $(B, A): 1$<br>• Jalur 3: $(B): 1$ | $B: 3$, $A: 2$, $C: 1$ | Simpul tunggal `(B:3)` | **{E}: 4**, **{E, B}: 3** |
| **C (Keju)** | • Jalur 1: $(B, A): 4$<br>• Jalur 2: $(A): 1$ | $A: 5$, $B: 4$ | Jalur terurut `(B:4) -> (A:5)` *(Catatan: A muncul di kedua jalur)* | **{C}: 5**, **{C, A}: 5**, **{C, B}: 4**, **{C, A, B}: 4** |
| **A (Roti)** | • Jalur 1: $(B): 5$ | $B: 5$ | Simpul tunggal `(B:5)` | **{A}: 6**, **{A, B}: 5** |
| **B (Susu)** | *(Tidak memiliki jalur prefiks)* | - | $\emptyset$ | **{B}: 6** |

**Verifikasi Keselarasan**:
Pola frekuen yang dihasilkan FP-Growth adalah:
* Ukuran 1: $\{D\}:3, \{E\}:4, \{C\}:5, \{A\}:6, \{B\}:6$ (5 itemset)
* Ukuran 2: $\{D, E\}:3, \{B, E\}:3, \{A, C\}:5, \{B, C\}:4, \{A, B, C\}:4, \{A, B\}:5$ (5 itemset ukuran 2 dan 1 itemset ukuran 3)
* **Hasil FP-Growth 100% identik dengan hasil Algoritma Apriori!**

---

### 7.5 Simulasi Algoritma ECLAT (Operasi Irisan Himpunan TID-List)

#### 1. Inisialisasi Representasi Vertikal (TID-Lists 1-Itemset)
Dari Tabel 7.1, daftar TID untuk setiap item dipetakan:
* $\text{TID}(A) = \{1, 2, 3, 5, 7, 8\} \implies |\text{TID}(A)| = 6$
* $\text{TID}(B) = \{1, 2, 3, 4, 5, 8\} \implies |\text{TID}(B)| = 6$
* $\text{TID}(C) = \{1, 3, 5, 7, 8\} \implies |\text{TID}(C)| = 5$
* $\text{TID}(D) = \{2, 4, 6\} \implies |\text{TID}(D)| = 3$
* $\text{TID}(E) = \{2, 4, 5, 6\} \implies |\text{TID}(E)| = 4$

Seluruh 5 item memenuhi ambang batas $\text{min\_sup\_count} \ge 3$.

---

#### 2. Operasi Irisan untuk 2-Itemsets
Hitung irisan antar pasangan himpunan TID:
* $\text{TID}(AB) = \text{TID}(A) \cap \text{TID}(B) = \{1, 2, 3, 5, 7, 8\} \cap \{1, 2, 3, 4, 5, 8\} = \{1, 2, 3, 5, 8\} \implies \sigma = 5$ **(Frekuen)**
* $\text{TID}(AC) = \text{TID}(A) \cap \text{TID}(C) = \{1, 2, 3, 5, 7, 8\} \cap \{1, 3, 5, 7, 8\} = \{1, 3, 5, 7, 8\} \implies \sigma = 5$ **(Frekuen)**
* $\text{TID}(AD) = \text{TID}(A) \cap \text{TID}(D) = \{1, 2, 3, 5, 7, 8\} \cap \{2, 4, 6\} = \{2\} \implies \sigma = 1$ (< 3, Dieliminasi)
* $\text{TID}(AE) = \text{TID}(A) \cap \text{TID}(E) = \{1, 2, 3, 5, 7, 8\} \cap \{2, 4, 5, 6\} = \{2, 5\} \implies \sigma = 2$ (< 3, Dieliminasi)
* $\text{TID}(BC) = \text{TID}(B) \cap \text{TID}(C) = \{1, 2, 3, 4, 5, 8\} \cap \{1, 3, 5, 7, 8\} = \{1, 3, 5, 8\} \implies \sigma = 4$ **(Frekuen)**
* $\text{TID}(BD) = \text{TID}(B) \cap \text{TID}(D) = \{1, 2, 3, 4, 5, 8\} \cap \{2, 4, 6\} = \{2, 4\} \implies \sigma = 2$ (< 3, Dieliminasi)
* $\text{TID}(BE) = \text{TID}(B) \cap \text{TID}(E) = \{1, 2, 3, 4, 5, 8\} \cap \{2, 4, 5, 6\} = \{2, 4, 5\} \implies \sigma = 3$ **(Frekuen)**
* $\text{TID}(CD) = \text{TID}(C) \cap \text{TID}(D) = \{1, 3, 5, 7, 8\} \cap \{2, 4, 6\} = \emptyset \implies \sigma = 0$ (< 3, Dieliminasi)
* $\text{TID}(CE) = \text{TID}(C) \cap \text{TID}(E) = \{1, 3, 5, 7, 8\} \cap \{2, 4, 5, 6\} = \{5\} \implies \sigma = 1$ (< 3, Dieliminasi)
* $\text{TID}(DE) = \text{TID}(D) \cap \text{TID}(E) = \{2, 4, 6\} \cap \{2, 4, 5, 6\} = \{2, 4, 6\} \implies \sigma = 3$ **(Frekuen)**

---

#### 3. Operasi Irisan untuk 3-Itemsets
Iriskan 2-itemset yang berada dalam kelas ekuivalensi prefiks yang sama:
* $\text{TID}(ABC) = \text{TID}(AB) \cap \text{TID}(AC) = \{1, 2, 3, 5, 8\} \cap \{1, 3, 5, 7, 8\} = \{1, 3, 5, 8\} \implies \sigma = 4$ **(Frekuen)**

Karena tidak ada lagi irisan kelas ekuivalensi yang menghasilkan kardinalitas $\ge 3$, algoritma ECLAT selesai dengan hasil yang kembali tepat sama!

---

### 7.6 Pembentukan dan Perhitungan Metrik Lengkap Aturan Asosiasi

Dari 11 frequent itemsets yang ditemukan, aturan asosiasi potensial dibangkitkan dan diuji terhadap ambang batas $\text{min\_conf} = 60.0\%$.

Mari kita tinjau 4 aturan paling representatif untuk menunjukkan perhitungan manual 5 metrik utama:

#### Kasus Aturan 1: $\{D\} \Rightarrow \{E\}$ (Kopi $\Rightarrow$ Gula)
* $\sigma(D) = 3 \implies \text{Support}(D) = 3/8 = 0.375$
* $\sigma(E) = 4 \implies \text{Support}(E) = 4/8 = 0.500$
* $\sigma(D \cup E) = 3 \implies \text{Support}(D \cup E) = 3/8 = 0.375$

1. **Support**:
   $$\text{Support} = \frac{3}{8} = 0.375 \quad (37.5\%)$$
2. **Confidence**:
   $$\text{Confidence} = \frac{\text{Support}(D \cup E)}{\text{Support}(D)} = \frac{0.375}{0.375} = 1.000 \quad (100.0\%)$$
   *(Lolos karena $100\% \ge 60\%$).*
3. **Lift**:
   $$\text{Lift} = \frac{\text{Confidence}}{\text{Support}(E)} = \frac{1.000}{0.500} = 2.000$$
   *(Nilai $\text{Lift} = 2.0 > 1$: Asosiasi positif sangat kuat! Pembeli kopi memiliki kecenderungan dua kali lipat lebih tinggi untuk membeli gula dibanding rata-rata populasi).*
4. **Leverage**:
   $$\text{Leverage} = \text{Support}(D \cup E) - (\text{Support}(D) \cdot \text{Support}(E)) = 0.375 - (0.375 \cdot 0.500) = 0.375 - 0.1875 = +0.1875$$
   *(Surplus probabilitas bersama sebesar $+18.75\%$).*
5. **Conviction**:
   $$\text{Conviction} = \frac{1 - \text{Support}(E)}{1 - \text{Confidence}} = \frac{1 - 0.500}{1 - 1.000} = \frac{0.500}{0} = \infty$$
   *(Karena kepastiannya mutlak $100\%$, tidak ada satu pun transaksi di mana pelanggan membeli kopi tanpa membeli gula).*

---

#### Kasus Aturan 2: $\{A\} \Rightarrow \{C\}$ (Roti $\Rightarrow$ Keju)
* $\sigma(A) = 6 \implies \text{Support}(A) = 6/8 = 0.750$
* $\sigma(C) = 5 \implies \text{Support}(C) = 5/8 = 0.625$
* $\sigma(A \cup C) = 5 \implies \text{Support}(A \cup C) = 5/8 = 0.625$

1. **Support**:
   $$\text{Support} = \frac{5}{8} = 0.625 \quad (62.5\%)$$
2. **Confidence**:
   $$\text{Confidence} = \frac{\text{Support}(A \cup C)}{\text{Support}(A)} = \frac{0.625}{0.750} = \frac{5}{6} \approx 0.8333 \quad (83.33\%)$$
   *(Lolos ambang batas $60\%$).*
3. **Lift**:
   $$\text{Lift} = \frac{\text{Confidence}}{\text{Support}(C)} = \frac{5/6}{5/8} = \frac{5}{6} \cdot \frac{8}{5} = \frac{8}{6} \approx 1.3333$$
   *(Asosiasi positif: peluang pembelian keju meningkat 33.3% saat pelanggan membeli roti).*
4. **Leverage**:
   $$\text{Leverage} = 0.625 - (0.750 \cdot 0.625) = 0.625 - 0.46875 = +0.15625$$
5. **Conviction**:
   $$\text{Conviction} = \frac{1 - 0.625}{1 - 0.8333} = \frac{0.375}{0.1667} = 2.250$$

---

#### Kasus Aturan 3: $\{B, C\} \Rightarrow \{A\}$ (Susu, Keju $\Rightarrow$ Roti)
* $\sigma(B, C) = 4 \implies \text{Support}(B, C) = 4/8 = 0.500$
* $\sigma(A) = 6 \implies \text{Support}(A) = 6/8 = 0.750$
* $\sigma(A, B, C) = 4 \implies \text{Support}(A, B, C) = 4/8 = 0.500$

1. **Support**:
   $$\text{Support} = \frac{4}{8} = 0.500 \quad (50.0\%)$$
2. **Confidence**:
   $$\text{Confidence} = \frac{\text{Support}(A, B, C)}{\text{Support}(B, C)} = \frac{0.500}{0.500} = 1.000 \quad (100.0\%)$$
3. **Lift**:
   $$\text{Lift} = \frac{1.000}{0.750} = \frac{4}{3} \approx 1.3333$$
4. **Leverage**:
   $$\text{Leverage} = 0.500 - (0.500 \cdot 0.750) = 0.500 - 0.375 = +0.1250$$
5. **Conviction**:
   $$\text{Conviction} = \frac{1 - 0.750}{1 - 1.000} = \infty$$

---

#### Kasus Aturan 4: $\{E\} \Rightarrow \{B\}$ (Gula $\Rightarrow$ Susu) — Studi Kasus Independen
* $\sigma(E) = 4 \implies \text{Support}(E) = 4/8 = 0.500$
* $\sigma(B) = 6 \implies \text{Support}(B) = 6/8 = 0.750$
* $\sigma(E \cup B) = 3 \implies \text{Support}(E \cup B) = 3/8 = 0.375$

1. **Support**:
   $$\text{Support} = \frac{3}{8} = 0.375 \quad (37.5\%)$$
2. **Confidence**:
   $$\text{Confidence} = \frac{0.375}{0.500} = 0.750 \quad (75.0\%)$$
   *(Tampak meyakinkan karena confidence mencapai 75%). Namun perhatikan metrik lainnya:*
3. **Lift**:
   $$\text{Lift} = \frac{0.750}{0.750} = 1.0000$$
   *(Tepat bernilai 1.0! Ini membuktikan bahwa gula dan susu adalah dua kejadian independen. Nilai confidence 75% semata-mata muncul karena susu memang dibeli oleh 75% dari seluruh populasi).*
4. **Leverage**:
   $$\text{Leverage} = 0.375 - (0.500 \cdot 0.750) = 0.375 - 0.375 = 0.0000$$
   *(Leverage bernilai tepat nol, membuktikan tidak ada surplus statistik di luar independensi).*
5. **Conviction**:
   $$\text{Conviction} = \frac{1 - 0.750}{1 - 0.750} = \frac{0.250}{0.250} = 1.0000$$
   *(Conviction bernilai tepat 1.0, menegaskan tidak adanya implikasi logis terarah).*

---

**Tabel 7.7: Matriks Evaluasi Lengkap Seluruh Aturan Asosiasi Terpilih ($\text{min\_conf} \ge 60\%$)**

| No | Aturan Asosiasi ($X \Rightarrow Y$) | Support ($P(XY)$) | Confidence ($P(Y \mid X)$) | Lift ($P(XY)/(P(X)P(Y))$) | Leverage ($P(XY)-P(X)P(Y)$) | Conviction ($P(X)P(\neg Y)/P(X\neg Y)$) | Karakteristik Hubungan |
| :---: | :--- | :---: | :---: | :---: | :---: | :---: | :--- |
| 1 | **{Kopi} $\Rightarrow$ {Gula}** | 0.375 (3/8) | 1.000 (100%) | 2.000 | +0.1875 | $\infty$ | Asosiasi positif sangat kuat |
| 2 | **{Gula} $\Rightarrow$ {Kopi}** | 0.375 (3/8) | 0.750 (75%) | 2.000 | +0.1875 | 2.5000 | Asosiasi positif sangat kuat |
| 3 | **{Keju} $\Rightarrow$ {Roti}** | 0.625 (5/8) | 1.000 (100%) | 1.333 | +0.1562 | $\infty$ | Asosiasi komplementer kuat |
| 4 | **{Roti} $\Rightarrow$ {Keju}** | 0.625 (5/8) | 0.833 (83.3%) | 1.333 | +0.1562 | 2.2500 | Asosiasi komplementer kuat |
| 5 | **{Susu, Keju} $\Rightarrow$ {Roti}** | 0.500 (4/8) | 1.000 (100%) | 1.333 | +0.1250 | $\infty$ | Multi-item deterministik |
| 6 | **{Roti} $\Rightarrow$ {Susu, Keju}** | 0.500 (4/8) | 0.667 (66.7%) | 1.333 | +0.1250 | 1.5000 | Multi-item prediktif |
| 7 | **{Susu, Roti} $\Rightarrow$ {Keju}** | 0.500 (4/8) | 0.800 (80%) | 1.280 | +0.1094 | 1.8750 | Asosiasi komplementer |
| 8 | **{Keju} $\Rightarrow$ {Susu, Roti}** | 0.500 (4/8) | 0.800 (80%) | 1.280 | +0.1094 | 1.8750 | Asosiasi komplementer |
| 9 | **{Susu} $\Rightarrow$ {Roti}** | 0.625 (5/8) | 0.833 (83.3%) | 1.111 | +0.0625 | 1.5000 | Asosiasi positif moderat |
| 10 | **{Roti} $\Rightarrow$ {Susu}** | 0.625 (5/8) | 0.833 (83.3%) | 1.111 | +0.0625 | 1.5000 | Asosiasi positif moderat |
| 11 | **{Keju} $\Rightarrow$ {Susu}** | 0.500 (4/8) | 0.800 (80%) | 1.067 | +0.0312 | 1.2500 | Asosiasi positif lemah |
| 12 | **{Susu} $\Rightarrow$ {Keju}** | 0.500 (4/8) | 0.667 (66.7%) | 1.067 | +0.0312 | 1.1250 | Asosiasi positif lemah |
| 13 | **{Gula} $\Rightarrow$ {Susu}** | 0.375 (3/8) | 0.750 (75%) | **1.000** | **0.0000** | **1.0000** | **Murni Independen (Acak)** |

---

## BAB VIII: IMPLEMENTASI PRAKTIS PYTHON DAN VERIFIKASI MESIN

### 8.1 Lingkungan Pemrograman dan Pustaka `mlxtend`

Untuk mengonfirmasi kebenaran kalkulasi manual pada Bab VII, digunakan kakas industri standar berbasis bahasa pemrograman Python 3.12 dengan pustaka `pandas` dan `mlxtend` (*Machine Learning Extensions* oleh Sebastian Raschka). Modul `mlxtend.frequent_patterns` menyediakan implementasi optimal dari `apriori`, `fpgrowth`, serta `association_rules`.

### 8.2 Kode Sumber Python Lengkap

```python
#!/usr/bin/env python3
"""
Implementasi dan Verifikasi Algoritma Apriori, FP-Growth, dan Aturan Asosiasi
Mata Kuliah: Penambangan Data (INF11121) - Minggu XII
Mahasiswa: Nabil (NIM: 2401020073)
"""

import pandas as pd
import numpy as np
from mlxtend.preprocessing import TransactionEncoder
from mlxtend.frequent_patterns import apriori, fpgrowth, association_rules

# 1. Definisi Dataset Transaksional Mini (8 Transaksi)
dataset = [
    ['Roti', 'Susu', 'Keju'],                 # T1
    ['Roti', 'Susu', 'Kopi', 'Gula'],         # T2
    ['Roti', 'Susu', 'Keju'],                 # T3
    ['Susu', 'Kopi', 'Gula'],                 # T4
    ['Roti', 'Susu', 'Keju', 'Gula'],         # T5
    ['Kopi', 'Gula'],                         # T6
    ['Roti', 'Keju'],                         # T7
    ['Roti', 'Susu', 'Keju']                  # T8
]

# 2. Transformasi ke Format One-Hot Encoding (Matriks Biner)
te = TransactionEncoder()
te_ary = te.fit(dataset).transform(dataset)
df_transaksi = pd.DataFrame(te_ary, columns=te.columns_)

print("=" * 70)
print("TABEL BASIS DATA TRANSAKSI ONE-HOT ENCODING (8 Transaksi x 5 Item)")
print("=" * 70)
print(df_transaksi.astype(int))

# 3. Eksekusi Algoritma Apriori (min_sup = 3/8 = 0.375)
MIN_SUPPORT = 3.0 / 8.0  # 37.5%
MIN_CONFIDENCE = 0.60    # 60.0%

freq_apriori = apriori(df_transaksi, min_support=MIN_SUPPORT, use_colnames=True)
freq_apriori['panjang'] = freq_apriori['itemsets'].apply(len)
freq_apriori['support_count'] = (freq_apriori['support'] * len(dataset)).round().astype(int)

print("\n" + "=" * 70)
print(f"HASIL FREQUENT ITEMSETS DENGAN APRIORI (min_sup = {MIN_SUPPORT:.3f})")
print("=" * 70)
print(freq_apriori.sort_values(by=['panjang', 'support'], ascending=[True, False]).to_string(index=False))

# 4. Eksekusi Algoritma FP-Growth untuk Verifikasi Keselarasan
freq_fpgrowth = fpgrowth(df_transaksi, min_support=MIN_SUPPORT, use_colnames=True)
freq_fpgrowth['panjang'] = freq_fpgrowth['itemsets'].apply(len)
freq_fpgrowth['support_count'] = (freq_fpgrowth['support'] * len(dataset)).round().astype(int)

# 5. Pembangkitan dan Evaluasi Aturan Asosiasi (Association Rules)
rules = association_rules(freq_apriori, metric='confidence', min_threshold=MIN_CONFIDENCE)

# Pemilihan dan Pengurutan Kolom Metrik Utama
cols_target = [
    'antecedents', 'consequents', 'support', 'confidence', 
    'lift', 'leverage', 'conviction'
]
rules_sorted = rules[cols_target].sort_values(by=['lift', 'confidence'], ascending=[False, False])

print("\n" + "=" * 70)
print(f"HASIL ATURAN ASOSIASI (min_conf = {MIN_CONFIDENCE*100:.1f}%) DENGAN 5 METRIK LENGKAP")
print("=" * 70)

for idx, r in rules_sorted.reset_index(drop=True).iterrows():
    ant = list(r['antecedents'])
    con = list(r['consequents'])
    print(f"Rule #{idx+1:02d}: {ant} => {con}")
    print(f"   Support   : {r['support']:.4f} ({r['support']*100:.1f}%)")
    print(f"   Confidence: {r['confidence']:.4f} ({r['confidence']*100:.1f}%)")
    print(f"   Lift      : {r['lift']:.4f}")
    print(f"   Leverage  : {r['leverage']:.4f}")
    print(f"   Conviction: {r['conviction']:.4f}")
    print("-" * 50)
```

### 8.3 Hasil Eksekusi dan Verifikasi Keselarasan

Output eksekusi program di lingkungan Python 3.12:
1. **Verifikasi Jumlah Pola Frekuen**: Baik fungsi `apriori()` maupun `fpgrowth()` menghasilkan tepat **11 frequent itemsets** dengan nilai support count yang sama persis:
   * 1-itemset (5): `{Roti}:6, {Susu}:6, {Keju}:5, {Gula}:4, {Kopi}:3`.
   * 2-itemset (5): `{Keju, Roti}:5, {Susu, Roti}:5, {Susu, Keju}:4, {Kopi, Gula}:3, {Susu, Gula}:3`.
   * 3-itemset (1): `{Susu, Keju, Roti}:4`.
2. **Verifikasi Nilai Metrik Evaluasi**:
   * Rule `{Kopi} => {Gula}`: $\text{Support} = 0.3750$, $\text{Confidence} = 1.0000$, $\text{Lift} = 2.0000$, $\text{Leverage} = 0.1875$, $\text{Conviction} = \text{inf}$.
   * Rule `{Keju} => {Roti}`: $\text{Support} = 0.6250$, $\text{Confidence} = 1.0000$, $\text{Lift} = 1.3333$, $\text{Leverage} = 0.1562$, $\text{Conviction} = \text{inf}$.
   * Rule `{Gula} => {Susu}`: $\text{Support} = 0.3750$, $\text{Confidence} = 0.7500$, $\text{Lift} = 1.0000$, $\text{Leverage} = 0.0000$, $\text{Conviction} = 1.0000$.

Tingkat kesesuaian antara hasil perhitungan manual (Bab VII) dan hasil eksekusi program mesin (Bab VIII) mencapai **100% presisi sempurna tanpa selisih pembulatan signifikan**.

---

## BAB IX: MATRIKS KOMPARASI DAN PANDUAN PEMILIHAN ALGORITMA

### 9.1 Tabel Komparasi Menyeluruh Kinerja Algoritma

**Tabel 9.1: Matriks Perbandingan Teknis Algoritma Asosiasi**

| Dimensi Evaluasi | Apriori (Agrawal, 1994) | FP-Growth (Han et al., 2000) | ECLAT (Zaki, 2000) | OPUS (Webb, 1995) |
| :--- | :--- | :--- | :--- | :--- |
| **Model Representasi** | Horizontal Transaksional | Pohon Terkompresi (FP-Tree) | Vertikal Inverted (TID-List) | Status Ruang Pencarian $(P, A)$ |
| **Pembangkitan Kandidat** | Eksplisit berjenjang ($C_k$) | Nihil (*Candidate-Free*) | Nihil (Irisan Bitset) | Dinamis Terpangkas |
| **Arah Penelusuran** | *Breadth-First* (Level-wise) | *Divide-and-Conquer* (DFS) | *Depth-First Search* (Lattice) | *Branch and Bound* Bebas Urutan |
| **Jumlah Scan Basis Data** | $k_{max} + 1$ kali | Tepat 2 kali | 1 kali (Konversi format) | Tergantung Index Transaksi |
| **Beban I/O Disk** | Sangat Berat | Sangat Ringan | Ringan | Menengah |
| **Skalabilitas Data Besar** | Buruk pada data rapat | Luar biasa tinggi | Tinggi pada data renggang | Sangat baik untuk aturan terarah |
| **Sensitivitas Nilai Support** | Meledak jika support rendah | Tetap stabil | Memori membengkak | Adaptif dengan batasan waktu |
| **Kelemahan Kritis** | Pembangkitan kandidat $O(2^m)$ | Overhead rekursi pointer | Ukuran TID-list pada data rapat | Desain implementasi kompleks |

### 9.2 Panduan Rekomendasi Pemilihan Berdasarkan Karakteristik Industri

1. **Gunakan Algoritma FP-Growth Jika**:
   * Basis data transaksi berukuran sangat besar (jutaan transaksi).
   * Nilai $\text{min\_sup}$ yang diinginkan relatif rendah (menghasilkan pola frekuen panjang).
   * Kapasitas RAM peladen (*server*) memadai untuk menampung FP-Tree.
   * Ini adalah algoritma *default* standar industri terbaik untuk analisis keranjang pasar umum.
2. **Gunakan Algoritma ECLAT Jika**:
   * Basis data memiliki karakteristik **sangat renggang (*sparse*)**, di mana setiap transaksi hanya memuat sedikit item unik (misal: analisis keranjang apotek atau sistem teks pencarian dokumen).
   * Perangkat keras mendukung instruksi komputasi vektor bit (*bitwise parallelism*).
   * Tidak ingin menghadapi overhead pengelolaan pointer pohon yang rumit seperti FP-Tree.
3. **Gunakan Algoritma OPUS Jika**:
   * Sistem beroperasi di bawah batasan waktu ketat (*anytime recommendation engine*).
   * Pengguna hanya tertarik mencari aturan terbaik berdasarkan fungsi optimasi non-support (misal: $k$ aturan dengan Lift tertinggi atau Leverage terbesar).
4. **Gunakan Algoritma Apriori Jika**:
   * Tujuan utama adalah edukasi akademis dan verifikasi manual, karena alur perhitungannya sangat transparan dan mudah dipahami.
   * Transaksi hanya memuat pola-pola sangat pendek ($k \le 2$).

---

## BAB X: KESIMPULAN

Berdasarkan kajian teoritis, formulasi matematis, simulasi manual, dan verifikasi eksperimental yang telah disusun dalam materi ajar ini, diperoleh beberapa kesimpulan penting:
1. Penambangan aturan asosiasi (*Association Rule Mining*) menyediakan kerangka kerja analitis yang sangat kuat untuk mengungkap korelasi empiris dalam data transaksional tanpa memerlukan label data awal (*unsupervised learning*).
2. Metrik **Support** dan **Confidence** adalah prasyarat dasar penambangan, namun tidak mencukupi untuk memvalidasi kualitas aturan karena rentan terhadap aturan semu (*spurious rules*). Metrik **Lift**, **Conviction**, dan **Leverage** menjadi instrumen validasi mutlak untuk memastikan bahwa korelasi yang ditemukan merupakan hubungan komplementer nyata di atas ekspektasi independensi statistik.
3. Algoritma **Apriori** mendemonstrasikan keindahan prinsip pemangkasan anti-monotonik (*downward-closure*), namun memiliki keterbatasan mendasar pada beban I/O disk berulang dan pembangkitan kandidat kombinatorial.
4. Algoritma **FP-Growth** secara revolusioner mengeliminasi pembangkitan kandidat melalui pemadatan basis data ke dalam FP-Tree dengan tepat 2 kali pembacaan disk, sementara **ECLAT** menyederhanakan perhitungan frekuensi menjadi operasi irisan himpunan TID berbasis aljabar relasional vertikal.
5. Algoritma **OPUS** melengkapi taksonomi asosiasi dengan memperkenalkan pemangkasan dinamis pada ruang pencarian bebas urutan yang adaptif terhadap batasan komputasi *real-time*.
6. Simulasi hitungan manual pada 8 transaksi mini yang diuji menghasilkan 11 frequent itemsets dan aturan-aturan asosiasi berkualitas tinggi yang terbukti 100% cocok (*exact match*) dengan keluaran mesin pustaka industri `mlxtend`.

---

## DAFTAR PUSTAKA

* Agrawal, R., Imieliński, T., & Swami, A. (1993). Mining association rules between sets of items in large databases. *Proceedings of the 1993 ACM SIGMOD International Conference on Management of Data*, 207–216. https://doi.org/10.1145/170035.170072
* Agrawal, R., & Srikant, R. (1994). Fast algorithms for mining association rules in large databases. *Proceedings of the 20th International Conference on Very Large Data Bases (VLDB)*, 487–499.
* Brin, S., Motwani, R., Ullman, J. D., & Tsur, S. (1997). Dynamic itemset counting and implication rules for market basket data. *ACM SIGMOD Record*, 26(2), 255–264. https://doi.org/10.1145/253262.253325
* Han, J., Pei, J., & Yin, Y. (2000). Mining frequent patterns without candidate generation. *ACM SIGMOD Record*, 29(2), 1–12. https://doi.org/10.1145/335191.335372
* Han, J., Kamber, M., & Pei, J. (2011). *Data Mining: Concepts and Techniques* (3rd ed.). Morgan Kaufmann.
* Piatetsky-Shapiro, G. (1991). Discovery, analysis, and presentation of strong rules. *Knowledge Discovery in Databases*, 229–248. AAAI/MIT Press.
* Tan, P.-N., Steinbach, M., Karpatne, A., & Kumar, V. (2018). *Introduction to Data Mining* (2nd ed.). Pearson Addison-Wesley.
* Webb, G. I. (2000). OPUS: An efficient admissible algorithm for unordered search. *Journal of Artificial Intelligence Research*, 13, 433–452. https://doi.org/10.1613/jair.769
* Zaki, M. J. (2000). Scalable algorithms for association mining. *IEEE Transactions on Knowledge and Data Engineering*, 12(3), 372–390. https://doi.org/10.1109/69.846291
