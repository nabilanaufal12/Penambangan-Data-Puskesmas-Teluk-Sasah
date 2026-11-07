# TUGAS MENULIS INDIVIDU MINGGU 10: TEKNIK KLASIFIKASI
**Mata Kuliah:** Penambangan Data (INF11121) — Semester Ganjil 2026/2027  
**Program Studi:** S1 Teknik Informatika, Fakultas Teknik dan Teknologi Kemaritiman  
**Universitas Maritim Raja Ali Haji (UMRAH)**  
**Dosen Pengampu:** Tekad Matulatan, S.T., M.Kom. & Muhamad Fadli, S.T., M.Kom.  
**Identitas Mahasiswa:** Balqis Manisa (NIM: 2401020069)  
**Kode Dokumen Pengumpulan:** `PD10_2401020069.pdf` / `PD10_2401020069.docx`  
**Tautan Pengumpulan RTM:** [Google Form Submission Sub-CPMK0415](https://forms.gle/nHWei7YjRDCkkCVC9)

---

```
========================================================================================
Cover Halaman Depan Resmi (Sesuai Rencana Tugas Mahasiswa / RTM):
Penambangan Data - Minggu 10 - 2401020069 - Balqis Manisa
========================================================================================
```

---

## DAFTAR ISI
1. [BAB I: PENGANTAR DAN PARADIGMA SUPERVISED LEARNING DALAM DATA MINING](#bab-i-pengantar-dan-paradigma-supervised-learning-dalam-data-mining)
   - 1.1 Definisi dan Formalisasi Masalah Klasifikasi
   - 1.2 Taksonomi Pembelajaran Mesin: Klasifikasi vs Regresi vs Kluster
   - 1.3 Siklus Hidup Pemodelan Klasifikasi (*Training*, *Validation*, *Testing*)
   - 1.4 *Inductive Bias* dan *No Free Lunch Theorem*
2. [BAB II: KELUARGA ALGORITMA POHON KEPUTUSAN (DECISION TREE)](#bab-ii-keluarga-algoritma-pohon-keputusan-decision-tree)
   - 2.1 Konsep Dasar dan Algoritma Hunt (Partisi Rekursif)
   - 2.2 Algoritma ID3 (*Iterative Dichotomiser 3*): Shannon Entropy dan Information Gain
   - 2.3 Algoritma C4.5: Penanganan Atribut Kontinu, Missing Values, dan Gain Ratio
   - 2.4 Algoritma CART (*Classification and Regression Trees*): Gini Impurity dan Gini Split
   - 2.5 Masalah *Overfitting* dan Strategi *Tree Pruning* (*Pre-pruning* vs *Post-pruning*)
3. [BAB III: PENGKLASIFIKASI PROBABILISTIK: NAÏVE BAYES](#bab-iii-pengklasifikasi-probabilistik-naïve-bayes)
   - 3.1 Teorema Bayes dan Penurunan Matematis *Maximum A Posteriori* (MAP)
   - 3.2 Asumsi *Conditional Independence* dan Formulasi Log-Likelihood
   - 3.3 Penanganan Masalah Probabilitas Nol: *Laplacian Smoothing* (Aditif)
   - 3.4 Varian Naïve Bayes: Gaussian Naïve Bayes, Multinomial Naïve Bayes, Bernoulli Naïve Bayes
4. [BAB IV: SUPPORT VECTOR MACHINE (SVM)](#bab-iv-support-vector-machine-svm)
   - 4.1 Geometri Pemisahan Ruang Vektor: Hyperplane dan Margin Maksimal
   - 4.2 Formulasi Optimasi Hard-Margin SVM (Primal dan Dual Lagrange)
   - 4.3 Soft-Margin SVM: Slack Variables ($\xi$) dan Trade-Off Regularisasi ($C$)
   - 4.4 Transformasi Ruang dan *The Kernel Trick* (Linear, RBF, Polynomial, Sigmoid)
   - 4.5 Strategi Klasifikasi Multi-Kelas: *One-vs-Rest* (OvR) vs *One-vs-One* (OvO)
5. [BAB V: REGRESI LOGISTIK DAN JARINGAN SARAF TIRUAN (ARTIFICIAL NEURAL NETWORK)](#bab-v-regresi-logistik-dan-jaringan-saraf-tiruan-artificial-neural-network)
   - 5.1 Regresi Logistik sebagai Pemodelan Klasifikasi Linier Probabilistik
   - 5.2 Fungsi Sigmoid, Log-Odds (Logit), dan Optimasi Binary Cross-Entropy Loss
   - 5.3 Dari Biologis ke Artificial Neuron: Perceptron Frank Rosenblatt dan Keterbatasan Garis Linear (XOR Problem)
   - 5.4 Multi-Layer Perceptron (MLP): Arsitektur Feedforward
   - 5.5 Fungsi Aktivasi Non-Linier: Sigmoid, Tanh, ReLU, Softmax
   - 5.6 Pembelajaran Jaringan Saraf: Algoritma *Backpropagation* dan *Gradient Descent*
6. [BAB VI: SIMULASI NUMERIK LANGKAH DEMI LANGKAH (MANUAL CALCULATION TRACE)](#bab-vi-simulasi-numerik-langkah-demi-langkah-manual-calculation-trace)
   - 6.1 Definisi Dataset Kasus Riil Diagnosa Penyakit (14 Pasien)
   - 6.2 Perhitungan Manual Pohon Keputusan ID3 (Entropy dan Information Gain Lengkap)
   - 6.3 Perhitungan Manual Naïve Bayes Classifier (Peluang Prior, Kondisional, dan Inferensi Uji Baru)
7. [BAB VII: EVALUASI KINERJA MODEL KLASIFIKASI](#bab-vii-evaluasi-kinerja-model-klasifikasi)
   - 7.1 Confusion Matrix: TP, TN, FP, FN
   - 7.2 Metrik Kinerja: Accuracy, Precision, Recall, Specificity, F1-Score, Balanced Accuracy
   - 7.3 Kurva ROC (*Receiver Operating Characteristic*) dan AUC (*Area Under Curve*)
   - 7.4 Metodologi Validasi: K-Fold Cross Validation, Stratified K-Fold, dan Train-Validation-Test Split
8. [BAB VIII: ANALISIS KOMPARATIF KOMPLEKSITAS DAN KARAKTERISTIK ALGORITMA](#bab-viii-analisis-komparatif-kompleksitas-dan-karakteristik-algoritma)
   - 8.1 Tabel Komparasi Big-O Kompleksitas Waktu dan Ruang
   - 8.2 Matriks Kelebihan, Kekurangan, dan Skenario Penerapan Terbaik
9. [BAB IX: IMPLEMENTASI PROGRAM DENGAN PYTHON DAN SCIKIT-LEARN](#bab-ix-implementasi-program-dengan-python-dan-scikit-learn)
   - 9.1 Konstruksi Dataset dan Pra-Pemrosesan
   - 9.2 Pelatihan Model Komparatif (Decision Tree, Naïve Bayes, SVM, LogReg, MLP)
   - 9.3 Evaluasi Terpadu dan Visualisasi Confusion Matrix
10. [BAB X: KESIMPULAN DAN REKOMENDASI](#bab-x-kesimpulan-dan-rekomendasi)
11. [DAFTAR PUSTAKA](#daftar-pustaka)

---

# BAB I: PENGANTAR DAN PARADIGMA SUPERVISED LEARNING DALAM DATA MINING

## 1.1 Definisi dan Formalisasi Masalah Klasifikasi
Dalam domain rekayasa perangkat lunak dan penambangan data (*data mining*), **klasifikasi** merupakan pilar utama dalam paradigma *supervised learning* (pembelajaran terbimbing). Secara formal matematis, klasifikasi didefinisikan sebagai tugas memetakan sebuah vektor fitur masukan $\mathbf{x} \in \mathcal{X} \subseteq \mathbb{R}^d$ ke dalam sebuah label diskrit target $y \in \mathcal{Y} = \{c_1, c_2, \dots, c_K\}$, di mana $K \ge 2$ merepresentasikan jumlah kelas diskrit yang saling lepas (*mutually exclusive*).

Diberikan sebuah dataset historis latih (training dataset):
$$\mathcal{D} = \{(\mathbf{x}_1, y_1), (\mathbf{x}_2, y_2), \dots, (\mathbf{x}_N, y_N)\}$$
di mana setiap pasangan $(\mathbf{x}_i, y_i)$ diasumsikan ditarik secara independen dan terdistribusi identik (*independent and identically distributed* / i.i.d.) dari distribusi probabilitas gabungan yang tidak diketahui $P(\mathbf{X}, Y)$.

Tujuan utama algoritma pembelajaran mesin induktif adalah menginduksi sebuah fungsi hipotesis:
$$f: \mathcal{X} \to \mathcal{Y}$$
yang meminimalkan ekspektasi risiko kesalahan klasifikasi (*expected classification risk* atau *generalization error*) terhadap data baru yang belum pernah dilihat sebelumnya (*unseen test data*):
$$R(f) = \mathbb{E}_{(\mathbf{x}, y) \sim P}[\mathcal{L}(f(\mathbf{x}), y)] = \int_{\mathcal{X} \times \mathcal{Y}} \mathcal{L}(f(\mathbf{x}), y) \, dP(\mathbf{x}, y)$$
di mana $\mathcal{L}(\hat{y}, y)$ adalah fungsi kerugian (*loss function*), seperti fungsi kerugian 0-1 (*zero-one loss*):
$$\mathcal{L}_{0-1}(\hat{y}, y) = \begin{cases} 0, & \text{jika } \hat{y} = y \\ 1, & \text{jika } \hat{y} \ne y \end{cases}$$

## 1.2 Taksonomi Pembelajaran Mesin: Klasifikasi vs Regresi vs Kluster
Pemahaman yang mendalam mengenai batas-batas fungsional ketiga teknik dasar penambangan data sangat krusial bagi mahasiswa teknik informatika:

1. **Klasifikasi (*Supervised Learning - Discrete Target*):**
   - Ruang target bersifat kategorikal/diskrit ($\mathcal{Y} = \{0, 1\}$ atau $\{c_1, \dots, c_K\}$).
   - Contoh kasus: Klasifikasi apakah email merupakan *spam* atau *ham*, penentuan apakah transaksi perbankan merupakan kecurangan (*fraud*) atau legal, deteksi penyakit pada citra medis radiologi.
2. **Regresi (*Supervised Learning - Continuous Target*):**
   - Ruang target bersifat kontinu riil ($\mathcal{Y} \subseteq \mathbb{R}$).
   - Contoh kasus: Prediksi harga saham penutupan esok hari, peramalan (*forecasting*) curah hujan bulanan, estimasi sisa usia operasional mesin pabrik (*remaining useful life*).
3. **Kluster / Pengelompokan (*Unsupervised Learning - No Label*):**
   - Dataset tidak memiliki variabel sasaran ($y$ absen), $\mathcal{D} = \{\mathbf{x}_1, \dots, \mathbf{x}_N\}$.
   - Algoritma bertugas mengekstraksi struktur intrinsik, geometri kepadatan data, atau partisi alami dari data berdasarkan ukuran jarak/kemiripan (*Euclidean distance*, *Manhattan distance*, *Cosine similarity*).
   - Contoh kasus: Segmentasi profil pelanggan, kompresi warna gambar, deteksi anomali pada log lalu lintas jaringan.

## 1.3 Siklus Hidup Pemodelan Klasifikasi (*Training*, *Validation*, *Testing*)
Dalam rekayasa data tingkat lanjut, pembagian dataset yang benar mencegah terjadinya kebocoran data (*data leakage*):
1. **Training Set ($60\% - 70\%$):** Digunakan secara eksklusif oleh algoritma pengoptimasi untuk menyesuaikan parameter internal model (misalnya mencari bobot $\mathbf{w}$ dan bias $b$ pada neural network atau membelah node pada pohon keputusan).
2. **Validation Set ($15\% - 20\%$):** Digunakan untuk memilih model terbaik (*model selection*), menyetel hyperparameter (misalnya kedalaman maksimum pohon *max_depth*, parameter penalti SVM $C$, nilai gamma $\gamma$), dan mendeteksi kondisi *overfitting*.
3. **Testing Set ($15\% - 20\%$):** Disimpan terkunci dan hanya diuji sekali pada akhir penelitian untuk mengevaluasi kemampuan generalisasi model murni tanpa bias optimasi.

## 1.4 *Inductive Bias* dan *No Free Lunch Theorem*
Dalam teori pembelajaran komputasional (*computational learning theory*), setiap algoritma klasifikasi memiliki **Inductive Bias** — himpunan asumsi apriori yang digunakan model untuk memprediksi keluaran dari masukan yang belum pernah diobservasi.
- Pohon Keputusan mengasumsikan batas keputusan (*decision boundary*) berbentuk sumbu orthogonal sejajar (*axis-parallel hyperplanes*).
- Naïve Bayes mengasumsikan semua fitur saling bebas secara kondisional terhadap label kelas.
- SVM Linier dan Regresi Logistik mengasumsikan data dapat dipisahkan secara linier dalam ruang fitur yang diberikan.

Berdasarkan **No Free Lunch (NFL) Theorem** yang dirumuskan oleh Wolpert & Macready (1997), tidak ada satu pun algoritma klasifikasi tunggal yang secara universal mengungguli algoritma lain pada semua kemungkinan distribusi masalah. Oleh karena itu, seorang insinyur informatika dituntut untuk menguasai berbagai keluarga algoritma klasifikasi beserta karakteristik matematisnya guna menentukan model optimal bagi kasus spesifik.

---

# BAB II: KELUARGA ALGORITMA POHON KEPUTUSAN (DECISION TREE)

## 2.1 Konsep Dasar dan Algoritma Hunt (Partisi Rekursif)
Pohon keputusan (*Decision Tree*) adalah model prediksi non-parametrik yang merepresentasikan aturan penentuan keputusan dalam struktur pohon hierarkis terarah (*directed tree*). Pohon ini terdiri dari:
- **Root Node (Simpul Akar):** Simpul paling atas yang mewakili atribut pemilah terbaik pertama dari seluruh dataset.
- **Internal Nodes (Simpul Perantara):** Menguji kondisi nilai atribut tertentu dan memiliki cabang keluar.
- **Leaf Nodes / Terminal Nodes (Simpul Daun):** Menetapkan label kelas target akhir.

Sebagian besar algoritma pembentukan pohon keputusan modern (ID3, C4.5, CART) berakar pada paradigma serakah (*greedy*) dari **Algoritma Hunt (1966)** yang bekerja secara rekursif membagi ruang data (*divide-and-conquer*):
Misalkan $D_t$ adalah himpunan rekaman pelatihan yang mencapai simpul $t$:
1. **Langkah 1:** Jika seluruh rekaman dalam $D_t$ memiliki label kelas yang sama ($y \in c_k$), maka simpul $t$ dijadikan simpul daun (*leaf node*) dengan label $c_k$.
2. **Langkah 2:** Jika $D_t$ kosong (tidak ada rekaman yang lolos), maka simpul $t$ dijadikan simpul daun dengan label kelas mayoritas dari simpul induknya (*parent node*).
3. **Langkah 3:** Jika $D_t$ memuat rekaman dengan lebih dari satu kelas, sebuah kondisi uji atribut (*attribute test condition*) dipilih untuk mempartisi $D_t$ menjadi beberapa subset yang lebih kecil $\{D_{t, 1}, D_{t, 2}, \dots, D_{t, m}\}$. Prosedur ini kemudian diterapkan secara rekursif pada setiap anak simpul.

## 2.2 Algoritma ID3 (*Iterative Dichotomiser 3*): Shannon Entropy dan Information Gain
Dikembangkan oleh Ross Quinlan pada tahun 1986, ID3 memanfaatkan teori informasi Claude Shannon untuk memilih atribut pemilah terbaik di setiap simpul.

### Formulasi Shannon Entropy
Entropy mengukur tingkat ketidakmurnian (*impurity*) atau keacakan distribusi kelas dalam suatu himpunan data $S$:
$$H(S) = - \sum_{i=1}^K p_i \log_2 (p_i)$$
di mana $p_i$ merepresentasikan proporsi sampel dalam $S$ yang termasuk ke dalam kelas $c_i$.
- Jika seluruh sampel dalam $S$ berasal dari kelas yang sama (homogen/murni), maka $p_1 = 1$, sehingga $H(S) = -1 \log_2(1) = 0$.
- Jika sampel terbagi secara merata pada dua kelas ($p_1 = 0.5, p_2 = 0.5$), maka tingkat ketidakpastian maksimal: $H(S) = -0.5 \log_2(0.5) - 0.5 \log_2(0.5) = 1.0 \text{ bit}$.

### Formulasi Information Gain (IG)
Information Gain mengukur reduksi nilai entropi yang diperoleh setelah himpunan data $S$ dipartisi menggunakan atribut $A$:
$$IG(S, A) = H(S) - \sum_{v \in \text{Values}(A)} \frac{|S_v|}{|S|} H(S_v)$$
di mana:
- $\text{Values}(A)$ adalah himpunan semua nilai diskrit yang mungkin dimiliki oleh atribut $A$.
- $S_v$ adalah subset dari $S$ di mana atribut $A$ bernilai $v$.
- $\frac{|S_v|}{|S|}$ adalah bobot relatif dari subset $S_v$.

Algoritma ID3 memilih atribut dengan nilai $IG(S, A)$ tertinggi sebagai atribut pemisah pada simpul tersebut.

### Kelemahan Mendasar ID3
ID3 memiliki bias intrinsik yang merugikan: model cenderung memilih atribut yang memiliki banyak nilai unik (kardinalitas tinggi), seperti atribut nomor identitas (*ID_Pelanggan*, *No_KTP*, atau *Timestamp*). Jika atribut nomor unik dipilih, setiap partisi hanya memiliki 1 baris data ($H(S_v) = 0$), menghasilkan Information Gain maksimal yang semu namun tidak memiliki kemampuan generalisasi sama sekali.

## 2.3 Algoritma C4.5: Penanganan Atribut Kontinu, Missing Values, dan Gain Ratio
Sebagai penyempurnaan langsung terhadap ID3, Ross Quinlan merilis **C4.5 (1993)** dengan sejumlah inovasi kunci:

### Formulasi Gain Ratio dan Split Information
Untuk mengatasi kelemahan pemilihan atribut berkardinalitas tinggi, C4.5 menormalisasi Information Gain menggunakan **Split Information** ($SplitInfo_A(S)$):
$$SplitInfo_A(S) = - \sum_{v \in \text{Values}(A)} \frac{|S_v|}{|S|} \log_2 \left(\frac{|S_v|}{|S|}\right)$$
Rasio perolehan (*Gain Ratio*) didefinisikan sebagai:
$$GainRatio(S, A) = \frac{IG(S, A)}{SplitInfo_A(S)}$$
Atribut dengan banyak partisi kecil akan menghasilkan $SplitInfo_A(S)$ yang sangat besar, sehingga nilai $GainRatio(S, A)$-nya tertekan turun secara adil.

### Penanganan Atribut Kontinu / Numerik
C4.5 mampu menangani atribut kontinu dengan menciptakan ambang batas biner dinamis (*threshold splitting*):
1. Urutkan seluruh nilai unik kontinu dari atribut $A$: $\{v_1, v_2, \dots, v_m\}$.
2. Evaluasi titik-titik pemisah kandidat pada titik tengah: $\theta_i = \frac{v_i + v_{i+1}}{2}$.
3. Untuk setiap $\theta_i$, bagi data menjadi dua partisi: $A \le \theta_i$ dan $A > \theta_i$.
4. Hitung Information Gain untuk setiap $\theta_i$, dan pilih ambang batas $\theta^*$ yang menghasilkan Information Gain tertinggi.

### Penanganan Nilai Hilang (*Missing Values*)
C4.5 dapat memproses rekaman yang kehilangan nilai atribut dengan mendistribusikan probabilitas fraksional bobot rekaman ke seluruh cabang simpul sebanding dengan frekuensi kemunculan nilai atribut yang diketahui.

## 2.4 Algoritma CART (*Classification and Regression Trees*): Gini Impurity dan Gini Split
Dikembangkan oleh Leo Breiman, Jerome Friedman, Richard Olshen, dan Charles Stone (1984), **CART** memiliki karakteristik utama:
1. Menghasilkan **Pohon Biner Murni (*Strict Binary Tree*)**, di mana setiap simpul internal hanya menghasilkan tepat dua cabang (*True* / *False* atau $\le \theta$ / $> \theta$).
2. Mendukung klasifikasi (label diskrit) maupun regresi (target kontinu numerik).

### Formulasi Gini Impurity
Untuk simpul $S$, Gini Impurity mengukur kemungkinan salah klasifikasi elemen yang dipilih secara acak jika diberi label acak sesuai distribusi kelas pada subset tersebut:
$$Gini(S) = 1 - \sum_{i=1}^K p_i^2$$
- Jika simpul sepenuhnya homogen ($p_1 = 1$), maka $Gini(S) = 1 - (1)^2 = 0$.
- Untuk klasifikasi biner dengan sebaran seimbang ($p_1 = 0.5, p_2 = 0.5$): $Gini(S) = 1 - (0.5^2 + 0.5^2) = 1 - 0.5 = 0.5$.

### Formulasi Gini Split
Ketika simpul $S$ dipartisi oleh atribut $A$ menjadi dua subset biner $S_1$ dan $S_2$:
$$Gini_{split}(S, A) = \frac{|S_1|}{|S|} Gini(S_1) + \frac{|S_2|}{|S|} Gini(S_2)$$
Reduksi Gini (*Reduction in Impurity*) dihitung sebagai:
$$\Delta Gini(A) = Gini(S) - Gini_{split}(S, A)$$
CART memilih pemisahan biner yang meminimalkan $Gini_{split}$ (atau memaksimalkan $\Delta Gini$).

## 2.5 Masalah *Overfitting* dan Strategi *Tree Pruning*
Jika pohon dibiarkan tumbuh bebas hingga setiap simpul daun memiliki tingkat kemurnian $100\%$, pohon akan mempelajari noise dan fluktuasi acak pada data latih (*overfitting*). Hal ini menyebabkan akurasi latih sangat tinggi ($100\%$) namun akurasi uji anjlok.

Dua strategi pemangkasan (*pruning*) pohon:
1. **Pre-pruning (Early Stopping):** Menghentikan ekspansi pohon secara dini sebelum mencapai daun murni berdasarkan kriteria henti:
   - Kedalaman maksimum pohon tercapai (*max_depth*).
   - Jumlah sampel dalam simpul kurang dari ambang batas minimum (*min_samples_split* atau *min_samples_leaf*).
   - Penurunan impurity tidak signifikan ($\Delta Gini < \epsilon$).
2. **Post-pruning (Cost-Complexity Pruning):** Pohon dibiarkan tumbuh maksimal, kemudian simpul-simpul yang tidak berkontribusi signifikan dipangkas kembali dari bawah ke atas (*bottom-up*).
   Fungsi biaya penalti (*Cost-Complexity Criterion*):
   $$R_\alpha(T) = R(T) + \alpha |T|$$
   di mana $R(T)$ adalah kesalahan klasifikasi pohon, $|T|$ adalah jumlah simpul daun, dan $\alpha \ge 0$ adalah parameter kompleksitas penalti. Nilai $\alpha$ optimal dicari melalui validasi silang (*cross-validation*).

---

# BAB III: PENGKLASIFIKASI PROBABILISTIK: NAÏVE BAYES

## 3.1 Teorema Bayes dan Penurunan Matematis *Maximum A Posteriori* (MAP)
Pengklasifikasi Naïve Bayes bekerja berdasarkan prinsip penalaran probabilistik formal menggunakan **Teorema Bayes (Rev. Thomas Bayes, 1763)**.
Diberikan sebuah vektor fitur pengamatan $\mathbf{x} = (x_1, x_2, \dots, x_d)^T$, probabilitas posterior bahwa objek tersebut tergolong ke dalam kelas $y = c_k$ dinyatakan sebagai:
$$P(y = c_k | \mathbf{x}) = \frac{P(\mathbf{x} | y = c_k) P(y = c_k)}{P(\mathbf{x})}$$
Keterangan komponen probabilitas:
- $P(y = c_k | \mathbf{x})$: **Posterior Probability** — probabilitas hipotesis kelas $c_k$ setelah mengamati vektor fitur $\mathbf{x}$.
- $P(\mathbf{x} | y = c_k)$: **Likelihood (Kemungkinan)** — probabilitas mengamati bukti $\mathbf{x}$ jika objek diketahui berasal dari kelas $c_k$.
- $P(y = c_k)$: **Prior Probability** — probabilitas awal kelas $c_k$ sebelum data diobservasi (dihitung dari proporsi kelas pada dataset latih: $\frac{N_k}{N}$).
- $P(\mathbf{x})$: **Evidence (Marginal Probability)** — probabilitas total dari vektor fitur $\mathbf{x}$, yang berfungsi sebagai konstanta normalisasi:
  $$P(\mathbf{x}) = \sum_{j=1}^K P(\mathbf{x} | y = c_j) P(y = c_j)$$

Dalam aturan keputusan **Maximum A Posteriori (MAP)**, kita memilih kelas $\hat{y}$ yang memaksimalkan probabilitas posterior. Karena $P(\mathbf{x})$ bernilai konstan dan positif untuk semua kandidat kelas $c_k$, penyebut dapat diabaikan:
$$\hat{y} = \arg\max_{c_k \in \mathcal{Y}} P(y = c_k | \mathbf{x}) = \arg\max_{c_k \in \mathcal{Y}} \left[ P(\mathbf{x} | y = c_k) P(y = c_k) \right]$$

## 3.2 Asumsi *Conditional Independence* dan Formulasi Log-Likelihood
Menghitung probabilitas gabungan multidimensi $P(\mathbf{x} | y = c_k) = P(x_1, x_2, \dots, x_d | y = c_k)$ secara langsung membutuhkan jumlah data eksponensial terhadap dimensi fitur ($O(K \cdot V^d)$), suatu fenomena yang dikenal sebagai *Curse of Dimensionality*.

Untuk memecahkan batasan tersebut, Naïve Bayes menetapkan asumsi penyederhanaan yang mendasar: **semua atribut diasumsikan saling bebas secara kondisional (*mutually conditionally independent*) terhadap kelas target**:
$$P(\mathbf{x} | y = c_k) = P(x_1, x_2, \dots, x_d | y = c_k) = \prod_{j=1}^d P(x_j | y = c_k)$$
Sehingga perumusan klasifikasi Naïve Bayes menjadi:
$$\hat{y} = \arg\max_{c_k \in \mathcal{Y}} \left[ P(y = c_k) \prod_{j=1}^d P(x_j | y = c_k) \right]$$

### Formulasi Log-Likelihood untuk Mencegah Underflow Numerik
Dalam komputasi floating-point pada komputer riil, mengalikan puluhan atau ratusan nilai probabilitas kecil ($P(x_j|y) \ll 1$) akan menyebabkan pembulatan ke nol secara tidak terkontrol (*arithmetic underflow*). Oleh karena itu, kita menerapkan transformasi logaritma natural ($\ln$):
$$\hat{y} = \arg\max_{c_k \in \mathcal{Y}} \left[ \ln P(y = c_k) + \sum_{j=1}^d \ln P(x_j | y = c_k) \right]$$
Karena logaritma adalah fungsi monoton naik murni, operasi ini menjaga urutan peringkat kelas secara identik tanpa risiko numerik.

## 3.3 Penanganan Masalah Probabilitas Nol: *Laplacian Smoothing* (Aditif)
Jika sebuah kombinasi nilai fitur $x_j = v$ dan kelas $y = c_k$ tidak pernah muncul sama sekali dalam data pelatihan, maka estimasi frekuensi relatifnya adalah:
$$P(x_j = v | y = c_k) = \frac{0}{N_k} = 0$$
Karena model menggunakan perkalian berantai, keberadaan satu saja probabilitas bernilai 0 akan membatalkan seluruh bukti dari fitur-fitur lainnya:
$$\prod_{j=1}^d P(x_j | y = c_k) = 0$$
Untuk mencegah fenomena ini, diterapkan **Laplace Correction / Laplacian Smoothing** (atau *Lidstone Smoothing* dengan parameter $\alpha$):
$$P(x_j = v | y = c_k) = \frac{count(x_j = v, y = c_k) + \alpha}{count(y = c_k) + \alpha \cdot K_j}$$
di mana:
- $\alpha > 0$ adalah parameter penghalusan (standar $\alpha = 1$).
- $K_j$ adalah kardinalitas (jumlah nilai unik) dari atribut fitur ke-$j$.
Jika data belum pernah terlihat, estimasi probabilitasnya tidak menjadi 0, melainkan $\frac{1}{N_k + K_j}$.

## 3.4 Varian Naïve Bayes
1. **Gaussian Naïve Bayes:**
   Digunakan untuk fitur kontinu riil. Diasumsikan bahwa nilai fitur kontinu pada kelas $c_k$ mengikuti distribusi normal Gaussian $\mathcal{N}(\mu_{k, j}, \sigma_{k, j}^2)$:
   $$P(x_j = v | y = c_k) = \frac{1}{\sqrt{2\pi \sigma_{k, j}^2}} \exp \left( - \frac{(v - \mu_{k, j})^2}{2\sigma_{k, j}^2} \right)$$
   di mana rata-rata $\mu_{k, j}$ dan varians $\sigma_{k, j}^2$ diestimasi dari sampel latih kelas $c_k$.
2. **Multinomial Naïve Bayes:**
   Umumnya diterapkan pada pemodelan pemrosesan bahasa alami (NLP) dan klasifikasi dokumen teks, di mana fitur merepresentasikan frekuensi kemunculan kata (*term frequency* / TF).
3. **Bernoulli Naïve Bayes:**
   Diterapkan pada vektor fitur biner ($\{0, 1\}$), misalnya ada atau tidak adanya kata tertentu dalam teks (*bag of words* biner).

---

# BAB IV: SUPPORT VECTOR MACHINE (SVM)

## 4.1 Geometri Pemisahan Ruang Vektor: Hyperplane dan Margin Maksimal
Dirintis oleh Vladimir Vapnik dan Alexey Chervonenkis (1963-1995), **Support Vector Machine (SVM)** bertumpu pada teori pembelajaran statistik (*Statistical Learning Theory*) dan prinsip minimalisasi risiko struktural (*Structural Risk Minimization*).

Misalkan kita memiliki masalah klasifikasi biner dengan label kelas $y_i \in \{-1, +1\}$. Sebuah bidang pemisah (*hyperplane*) dalam ruang $\mathbb{R}^d$ didefinisikan oleh persamaan:
$$\mathbf{w}^T \mathbf{x} + b = 0$$
di mana $\mathbf{w} \in \mathbb{R}^d$ adalah vektor normal bidang dan $b \in \mathbb{R}$ adalah bias (jarak dari titik asal).

Aturan penentu keputusan klasifikasi adalah:
$$f(\mathbf{x}) = \text{sign}(\mathbf{w}^T \mathbf{x} + b)$$

Sebuah dataset dikatakan terpisah secara linier sempurna (*linearly separable*) jika terdapat pasangan $(\mathbf{w}, b)$ sedemikian rupa sehingga:
$$y_i (\mathbf{w}^T \mathbf{x}_i + b) \ge 1, \quad \forall i = 1, \dots, N$$
Jarak tegak lurus dari titik data sembarang $\mathbf{x}_i$ ke bidang pemisah adalah:
$$\text{dist}(\mathbf{x}_i) = \frac{|\mathbf{w}^T \mathbf{x}_i + b|}{\|\mathbf{w}\|}$$
Margin geometris total di antara dua bidang batas penyokong ($H_1: \mathbf{w}^T \mathbf{x} + b = +1$ dan $H_2: \mathbf{w}^T \mathbf{x} + b = -1$) adalah:
$$\gamma = \frac{2}{\|\mathbf{w}\|}$$

## 4.2 Formulasi Optimasi Hard-Margin SVM
SVM bertujuan menemukan hyperplane yang memisahkan kedua kelas dengan **margin terlebar** (*maximum margin classifier*). Memaksimalkan $\frac{2}{\|\mathbf{w}\|}$ secara matematis setara dengan meminimalkan $\frac{1}{2} \|\mathbf{w}\|^2$:

$$\min_{\mathbf{w}, b} \frac{1}{2} \|\mathbf{w}\|^2$$
$$\text{dengan kendala: } y_i (\mathbf{w}^T \mathbf{x}_i + b) \ge 1, \quad \forall i = 1, \dots, N$$

Masalah ini diselesaikan melalui metode Pengali Lagrange (*Lagrangian Multipliers*). Fungsi Lagrangian primal:
$$\mathcal{L}_P(\mathbf{w}, b, \boldsymbol{\alpha}) = \frac{1}{2} \|\mathbf{w}\|^2 - \sum_{i=1}^N \alpha_i \left[ y_i (\mathbf{w}^T \mathbf{x}_i + b) - 1 \right]$$
di mana $\alpha_i \ge 0$ adalah pengali Lagrange.

Dengan mencari turunan parsial terhadap $\mathbf{w}$ dan $b$ sama dengan nol ($\nabla_{\mathbf{w}} \mathcal{L}_P = 0$ dan $\frac{\partial \mathcal{L}_P}{\partial b} = 0$), kita peroleh:
$$\mathbf{w} = \sum_{i=1}^N \alpha_i y_i \mathbf{x}_i, \quad \sum_{i=1}^N \alpha_i y_i = 0$$

Substitusi kembali ke bentuk primal menghasilkan **Masalah Dual Wolfe**:
$$\max_{\boldsymbol{\alpha}} \sum_{i=1}^N \alpha_i - \frac{1}{2} \sum_{i=1}^N \sum_{j=1}^N \alpha_i \alpha_j y_i y_j (\mathbf{x}_i^T \mathbf{x}_j)$$
$$\text{dengan kendala: } \alpha_i \ge 0, \quad \sum_{i=1}^N \alpha_i y_i = 0$$

Berdasarkan kondisi Karush-Kuhn-Tucker (KKT), berlaku kondisi komplemen:
$$\alpha_i \left[ y_i (\mathbf{w}^T \mathbf{x}_i + b) - 1 \right] = 0$$
Hanya titik-titik data yang berada tepat di perbatasan margin yang memiliki $\alpha_i > 0$. Titik-titik krusial inilah yang disebut sebagai **Support Vectors**. Titik-titik lain yang jauh dari batas memiliki $\alpha_i = 0$ dan sama sekali tidak memengaruhi letak hyperplane.

## 4.3 Soft-Margin SVM: Slack Variables ($\xi$) dan Parameter Penalti ($C$)
Pada data riil di lapangan, data jarang dapat dipisahkan secara linier sempurna karena adanya derau (*noise*) atau pencilan (*outliers*). Cortes dan Vapnik (1995) memperkenalkan variabel kelonggaran (*slack variable*) $\xi_i \ge 0$ yang memperbolehkan sejumlah data melanggar margin:

$$\min_{\mathbf{w}, b, \boldsymbol{\xi}} \frac{1}{2} \|\mathbf{w}\|^2 + C \sum_{i=1}^N \xi_i$$
$$\text{dengan kendala: } y_i (\mathbf{w}^T \mathbf{x}_i + b) \ge 1 - \xi_i, \quad \xi_i \ge 0, \quad \forall i = 1, \dots, N$$
di mana:
- $\xi_i = 0$: Data berada di luar margin dan terklasifikasi benar.
- $0 < \xi_i \le 1$: Data berada di dalam margin namun masih di sisi pemisah yang benar.
- $\xi_i > 1$: Data salah diklasifikasikan (*misclassified*).
- $C > 0$: Hyperparameter penalti regularisasi. Nilai $C$ besar memaksa margin ketat (rendah bias, tinggi varians / risiko overfitting). Nilai $C$ kecil memberikan margin toleran (tinggi bias, rendah varians / risiko underfitting).

## 4.4 Transformasi Ruang dan *The Kernel Trick*
Jika data tidak terpisahkan secara linier pada ruang aslinya $\mathbb{R}^d$, data dapat dipetakan ke ruang fitur berdimensi jauh lebih tinggi (atau tak terhingga) $\mathcal{H}$ menggunakan fungsi transformasi $\phi(\mathbf{x}): \mathbb{R}^d \to \mathcal{H}$.

Perhatikan bahwa dalam formulasi dual SVM, data hanya muncul dalam bentuk operasi perkalian titik (*inner product*): $\mathbf{x}_i^T \mathbf{x}_j$. Dengan **Kernel Trick**, kita tidak perlu menghitung koordinat $\phi(\mathbf{x})$ secara eksplisit di ruang dimensi tinggi yang mahal secara komputasi. Kita cukup menggunakan **Fungsi Kernel** $K(\mathbf{x}_i, \mathbf{x}_j)$ sedemikian rupa sehingga:
$$K(\mathbf{x}_i, \mathbf{x}_j) = \langle \phi(\mathbf{x}_i), \phi(\mathbf{x}_j) \rangle$$

Fungsi Kernel Standar (memenuhi Teorema Mercer):
1. **Linear Kernel:** $K(\mathbf{x}, \mathbf{z}) = \mathbf{x}^T \mathbf{z}$
2. **Polynomial Kernel:** $K(\mathbf{x}, \mathbf{z}) = (\gamma \mathbf{x}^T \mathbf{z} + r)^d$
3. **Radial Basis Function (RBF) / Gaussian Kernel:**
   $$K(\mathbf{x}, \mathbf{z}) = \exp \left( -\gamma \|\mathbf{x} - \mathbf{z}\|^2 \right), \quad \gamma = \frac{1}{2\sigma^2}$$
   Kernel RBF memetakan data ke ruang fitur berdimensi tak terhingga (*infinite Hilbert space*), menjadikannya sangat fleksibel memodelkan batas keputusan non-linier kompleks.
4. **Sigmoid (Hyperbolic Tangent) Kernel:** $K(\mathbf{x}, \mathbf{z}) = \tanh(\gamma \mathbf{x}^T \mathbf{z} + r)$

## 4.5 Strategi Klasifikasi Multi-Kelas
Karena SVM murni dirancang untuk klasifikasi biner, klasifikasi multi-kelas ($K > 2$) diselesaikan dengan dekomposisi:
1. **One-vs-Rest (OvR) / One-vs-All:** Melatih $K$ buah model biner terpisah. Model ke-$k$ membedakan kelas $c_k$ melawan gabungan seluruh kelas lainnya. Keputusan akhir: $\hat{y} = \arg\max_{k} f_k(\mathbf{x})$.
2. **One-vs-One (OvO):** Melatih $\frac{K(K-1)}{2}$ buah model biner untuk setiap pasangan kelas $(c_i, c_j)$. Prediksi ditentukan melalui sistem pemungutan suara terbanyak (*majority voting*).

---

# BAB V: REGRESI LOGISTIK DAN JARINGAN SARAF TIRUAN (ARTIFICIAL NEURAL NETWORK)

## 5.1 Regresi Logistik sebagai Pemodelan Klasifikasi Linier Probabilistik
Meskipun menyandang istilah "regresi", **Regresi Logistik (*Logistic Regression*)** adalah algoritma klasifikasi linier probabilistik. Model ini memodelkan probabilitas kejadian kelas target bersyarat $P(Y=1|\mathbf{x})$.

Kombinasi linier masukan didefinisikan sebagai $z = \mathbf{w}^T \mathbf{x} + b$. Untuk membatasi keluaran dalam rentang probabilitas valid $[0, 1]$, nilai $z$ dipetakan menggunakan **Fungsi Logistik (Sigmoid)**:
$$\sigma(z) = \frac{1}{1 + e^{-z}} = \frac{e^z}{1 + e^z}$$

Karakteristik penting fungsi Sigmoid:
- $\lim_{z \to +\infty} \sigma(z) = 1$
- $\lim_{z \to -\infty} \sigma(z) = 0$
- $\sigma(0) = 0.5$
- Turunan pertama memiliki properti komputasi yang sangat efisien:
  $$\frac{d\sigma(z)}{dz} = \sigma(z)(1 - \sigma(z))$$

## 5.2 Log-Odds (Logit) dan Binary Cross-Entropy Loss
Rasio peluang (*Odds Ratio*) didefinisikan sebagai perbandingan probabilitas sukses terhadap gagal:
$$\text{Odds} = \frac{P(Y=1|\mathbf{x})}{1 - P(Y=1|\mathbf{x})} = \frac{\sigma(z)}{1 - \sigma(z)} = e^z = e^{\mathbf{w}^T \mathbf{x} + b}$$
Transformasi log-odds menghasilkan fungsi **Logit**, yang mengembalikan bentuk kombinasi linier:
$$\ln(\text{Odds}) = \ln \left( \frac{P}{1-P} \right) = \mathbf{w}^T \mathbf{x} + b$$

### Fungsi Kerugian: Binary Cross-Entropy (Log Loss)
Jika kita menggunakan Mean Squared Error (MSE) pada fungsi sigmoid non-linier, permukaan fungsi biaya akan bersifat non-konveks dengan banyak jebakan minimum lokal. Oleh karena itu, parameter diestimasi menggunakan metode *Maximum Likelihood Estimation* (MLE) yang menghasilkan fungsi kerugian konveks **Binary Cross-Entropy Loss**:
$$J(\mathbf{w}, b) = - \frac{1}{N} \sum_{i=1}^N \left[ y_i \ln(\hat{y}_i) + (1 - y_i) \ln(1 - \hat{y}_i) \right]$$
di mana $\hat{y}_i = \sigma(\mathbf{w}^T \mathbf{x}_i + b)$.
Pembaruan bobot dilakukan menggunakan algoritma optimasi *Gradient Descent*:
$$\mathbf{w} \leftarrow \mathbf{w} - \eta \frac{\partial J}{\partial \mathbf{w}} = \mathbf{w} - \eta \frac{1}{N} \sum_{i=1}^N (\hat{y}_i - y_i) \mathbf{x}_i$$

## 5.3 Dari Biologis ke Artificial Neuron: Perceptron dan Keterbatasan XOR
Model komputasi neuron pertama kali diajukan oleh Warren McCulloch & Walter Pitts (1943) dan diimplementasikan oleh Frank Rosenblatt (1958) sebagai **Perceptron**:
$$y = f\left( \sum_{j=1}^d w_j x_j + b \right)$$
dengan fungsi aktivasi tangga Heaviside (*Step Function*):
$$f(z) = \begin{cases} 1, & \text{jika } z \ge 0 \\ 0, & \text{jika } z < 0 \end{cases}$$

### Keterbatasan Garis Linear dan Masalah XOR
Pada tahun 1969, Marvin Minsky dan Seymour Papert menerbitkan buku legendaris *Perceptrons* yang membuktikan secara matematis bahwa Single-Layer Perceptron hanya mampu memisahkan ruang yang dapat dipisahkan secara linier (*linearly separable*), seperti gerbang logika AND dan OR. Perceptron tunggal secara teoretis gagal memecahkan fungsi logika eksklusif-OR (**XOR Problem**) karena tidak ada satupun garis lurus yang dapat memisahkan label $(0,1)$ dan $(1,0)$ dari $(0,0)$ dan $(1,1)$. Penemuan ini memicu era kemunduran AI pertama (*first AI winter*), hingga ditemukannya arsitektur Multi-Layer Perceptron dengan algoritma Backpropagation.

## 5.4 Multi-Layer Perceptron (MLP): Arsitektur Feedforward
Multi-Layer Perceptron mengatasi keterbatasan XOR dengan menambahkan satu atau lebih lapisan tersembunyi (*hidden layers*) di antara lapisan masukan (*input layer*) dan keluaran (*output layer*).

Berdasarkan **Universal Approximation Theorem** (Cybenko 1989; Hornik 1991), sebuah jaringan saraf feedforward dengan setidaknya satu lapisan tersembunyi dan fungsi aktivasi non-linier kontinu dapat mendekati sembarang fungsi kontinu pada subset kompak $\mathbb{R}^d$ dengan tingkat presisi berapapun.

Struktur komputasi maju (*feedforward*):
1. Hidden Layer: $\mathbf{a}^{(1)} = \phi(\mathbf{W}^{(1)} \mathbf{x} + \mathbf{b}^{(1)})$
2. Output Layer: $\hat{\mathbf{y}} = g(\mathbf{W}^{(2)} \mathbf{a}^{(1)} + \mathbf{b}^{(2)})$

## 5.5 Fungsi Aktivasi Non-Linier
Tanpa fungsi aktivasi non-linier, tumpukan berapapun banyaknya lapisan linear hanya akan mereduksi kembali menjadi sebuah transformasi linier tunggal tunggal: $\mathbf{W}_2(\mathbf{W}_1 \mathbf{x}) = (\mathbf{W}_2 \mathbf{W}_1)\mathbf{x} = \mathbf{W}'\mathbf{x}$.

Fungsi aktivasi modern yang umum digunakan:
1. **Sigmoid:** $\phi(z) = \frac{1}{1 + e^{-z}}$ (Kelemahan: *Vanishing Gradient Problem* saat $|z|$ besar, tidak zero-centered).
2. **Hyperbolic Tangent (Tanh):** $\phi(z) = \frac{e^z - e^{-z}}{e^z + e^{-z}}$, rentang $(-1, 1)$, bersifat *zero-centered*.
3. **Rectified Linear Unit (ReLU):** $\phi(z) = \max(0, z)$.
   - Keunggulan: Menghilangkan masalah *vanishing gradient* pada domain positif, komputasi sangat cepat (hanya operasi ambang batas).
   - Kelemahan: Masalah *Dying ReLU* ketika neuron non-aktif secara permanen jika gradien nol.
4. **Leaky ReLU:** $\phi(z) = \max(\alpha z, z)$ dengan $\alpha \approx 0.01$ untuk mengatasi neuron mati.
5. **Softmax:** Digunakan pada lapisan keluaran multi-kelas untuk menghasilkan distribusi probabilitas terstandarisasi:
   $$\text{Softmax}(z_k) = \frac{e^{z_k}}{\sum_{j=1}^K e^{z_j}}$$

## 5.6 Pembelajaran Jaringan Saraf: Algoritma *Backpropagation*
Pelatihan jaringan saraf dilakukan melalui algoritma **Backpropagation** (Rumelhart, Hinton, & Williams, 1986), yang memanfaatkan aturan rantai kalkulus (*chain rule*) untuk menghitung gradien parsial kerugian terhadap setiap bobot dalam jaringan:

Misalkan kita memiliki fungsi kerugian $E$. Untuk memperbarui bobot $w_{jk}^{(l)}$ yang menghubungkan neuron $j$ pada layer $l-1$ ke neuron $k$ pada layer $l$:
$$\frac{\partial E}{\partial w_{jk}^{(l)}} = \frac{\partial E}{\partial z_k^{(l)}} \frac{\partial z_k^{(l)}}{\partial w_{jk}^{(l)}} = \delta_k^{(l)} \cdot a_j^{(l-1)}$$
di mana error signal $\delta_k^{(l)}$ dirambatkan balik dari lapisan berikutnya:
$$\delta_k^{(l)} = \left( \sum_{m} \delta_m^{(l+1)} w_{km}^{(l+1)} \right) \cdot \phi'(z_k^{(l)})$$

Pembaruan bobot dengan Stochastic Gradient Descent (SGD) dan momentum:
$$w_{jk} \leftarrow w_{jk} - \eta \frac{\partial E}{\partial w_{jk}}$$

---

# BAB VI: SIMULASI NUMERIK LANGKAH DEMI LANGKAH (MANUAL CALCULATION TRACE)

Untuk memberikan pemahaman empiris dan matematis yang komprehensif, pada bagian ini disajikan perhitungan manual langkah demi langkah (*hand calculation*) menggunakan sebuah kasus diagnosis medis kardiologi.

## 6.1 Definisi Dataset Kasus Riil Diagnosa Penyakit (14 Pasien)
Dataset berikut terdiri dari 14 baris data rekam medis pasien dengan 4 atribut fitur kategorikal dan 1 atribut label target biner:
- **Usia (X1):** Muda, Paruh_Baya, Lanjut
- **Tekanan_Darah (X2):** Normal, Tinggi
- **Kolesterol (X3):** Normal, Tinggi
- **Riwayat_Keluarga (X4):** Ya, Tidak
- **Penyakit_Jantung (Target Y):** Positif (P) atau Negatif (N)

### Tabel 6.1: Matriks Data Rekam Medis 14 Pasien
| ID Pasien | Usia ($X_1$) | Tekanan Darah ($X_2$) | Kolesterol ($X_3$) | Riwayat Keluarga ($X_4$) | Penyakit Jantung ($Y$) |
|:---:|:---:|:---:|:---:|:---:|:---:|
| P01 | Muda | Tinggi | Tinggi | Tidak | Negatif (N) |
| P02 | Muda | Tinggi | Tinggi | Ya | Negatif (N) |
| P03 | Paruh_Baya | Tinggi | Tinggi | Tidak | Positif (P) |
| P04 | Lanjut | Normal | Tinggi | Tidak | Positif (P) |
| P05 | Lanjut | Normal | Normal | Tidak | Positif (P) |
| P06 | Lanjut | Normal | Normal | Ya | Negatif (N) |
| P07 | Paruh_Baya | Normal | Normal | Ya | Positif (P) |
| P08 | Muda | Normal | Tinggi | Tidak | Negatif (N) |
| P09 | Muda | Normal | Normal | Tidak | Positif (P) |
| P10 | Lanjut | Normal | Tinggi | Tidak | Positif (P) |
| P11 | Muda | Normal | Normal | Ya | Positif (P) |
| P12 | Paruh_Baya | Normal | Tinggi | Ya | Positif (P) |
| P13 | Paruh_Baya | Tinggi | Normal | Tidak | Positif (P) |
| P14 | Lanjut | Normal | Tinggi | Ya | Negatif (N) |

Ringkasan Distribusi Kelas Target Keseluruhan ($S$):
- Total data ($|S|$) = 14
- Jumlah Kasus Positif ($P$) = 9
- Jumlah Kasus Negatif ($N$) = 5

---

## 6.2 Perhitungan Manual Pohon Keputusan ID3

### Langkah 1: Menghitung Entropi Total Sistem $H(S)$
$$p_+ = \frac{9}{14} \approx 0.642857$$
$$p_- = \frac{5}{14} \approx 0.357143$$

$$H(S) = - \left( \frac{9}{14} \log_2 \frac{9}{14} + \frac{5}{14} \log_2 \frac{5}{14} \right)$$
Perhitungan logaritma basis 2:
- $\log_2(0.642857) = \frac{\ln(0.642857)}{\ln(2)} \approx \frac{-0.441833}{0.693147} \approx -0.637430$
- $\log_2(0.357143) = \frac{\ln(0.357143)}{\ln(2)} \approx \frac{-1.029619}{0.693147} \approx -1.485427$

$$H(S) = - \left[ (0.642857 \times -0.637430) + (0.357143 \times -1.485427) \right]$$
$$H(S) = - [-0.409776 - 0.530509] = - [-0.940285] \approx \mathbf{0.940285 \text{ bit}}$$

---

### Langkah 2: Menghitung Entropi dan Information Gain Tiap Atribut

#### 1. Atribut: Usia
Atribut Usia memiliki 3 nilai: **Muda**, **Paruh_Baya**, **Lanjut**.

a. Nilai `Muda` ($|S_{Muda}| = 5$):
- Data: P01(N), P02(N), P08(N), P09(P), P11(P) $\to$ Positif = 2, Negatif = 3
$$H(S_{Muda}) = - \left( \frac{2}{5} \log_2 \frac{2}{5} + \frac{3}{5} \log_2 \frac{3}{5} \right) = - [0.4(-1.321928) + 0.6(-0.736966)] = - [-0.528771 - 0.442179] = \mathbf{0.970951}$$

b. Nilai `Paruh_Baya` ($|S_{Paruh\_Baya}| = 4$):
- Data: P03(P), P07(P), P12(P), P13(P) $\to$ Positif = 4, Negatif = 0 (Murni)
$$H(S_{Paruh\_Baya}) = - \left( \frac{4}{4} \log_2 \frac{4}{4} + 0 \right) = - (1 \times 0) = \mathbf{0.000000}$$

c. Nilai `Lanjut` ($|S_{Lanjut}| = 5$):
- Data: P04(P), P05(P), P06(N), P10(P), P14(N) $\to$ Positif = 3, Negatif = 2
$$H(S_{Lanjut}) = - \left( \frac{3}{5} \log_2 \frac{3}{5} + \frac{2}{5} \log_2 \frac{2}{5} \right) = \mathbf{0.970951}$$

Menghitung Entropi Rata-rata Berbobot $H(S, Usia)$:
$$H(S, Usia) = \frac{5}{14} H(S_{Muda}) + \frac{4}{14} H(S_{Paruh\_Baya}) + \frac{5}{14} H(S_{Lanjut})$$
$$H(S, Usia) = \frac{5}{14}(0.970951) + \frac{4}{14}(0) + \frac{5}{14}(0.970951) = \frac{10}{14}(0.970951) \approx \mathbf{0.693536}$$

Menghitung Information Gain:
$$IG(S, Usia) = H(S) - H(S, Usia) = 0.940285 - 0.693536 = \mathbf{0.246749}$$

---

#### 2. Atribut: Tekanan Darah
Atribut Tekanan Darah memiliki 2 nilai: **Tinggi**, **Normal**.

a. Nilai `Tinggi` ($|S_{Tinggi}| = 4$):
- Data: P01(N), P02(N), P03(P), P13(P) $\to$ Positif = 2, Negatif = 2
$$H(S_{Tinggi}) = - \left( \frac{2}{4} \log_2 \frac{2}{4} + \frac{2}{4} \log_2 \frac{2}{4} \right) = \mathbf{1.000000}$$

b. Nilai `Normal` ($|S_{Normal}| = 10$):
- Data: P04(P), P05(P), P06(N), P07(P), P08(N), P09(P), P10(P), P11(P), P12(P), P14(N) $\to$ Positif = 7, Negatif = 3
$$H(S_{Normal}) = - \left( \frac{7}{10} \log_2 \frac{7}{10} + \frac{3}{10} \log_2 \frac{3}{10} \right) = - [0.7(-0.514573) + 0.3(-1.736966)] = - [-0.360201 - 0.521090] = \mathbf{0.881291}$$

Menghitung Entropi Rata-rata Berbobot $H(S, Tekanan\_Darah)$:
$$H(S, Tekanan\_Darah) = \frac{4}{14}(1.000000) + \frac{10}{14}(0.881291) = 0.285714 + 0.629494 = \mathbf{0.915208}$$

Menghitung Information Gain:
$$IG(S, Tekanan\_Darah) = 0.940285 - 0.915208 = \mathbf{0.025077}$$

---

#### 3. Atribut: Kolesterol
Atribut Kolesterol memiliki 2 nilai: **Tinggi**, **Normal**.

a. Nilai `Tinggi` ($|S_{Tinggi}| = 7$):
- Data: P01(N), P02(N), P03(P), P04(P), P08(N), P10(P), P12(P) $\to$ Positif = 4, Negatif = 3
$$H(S_{Kol\_Tinggi}) = - \left( \frac{4}{7} \log_2 \frac{4}{7} + \frac{3}{7} \log_2 \frac{3}{7} \right) = - [0.571429(-0.807355) + 0.428571(-1.222392)] = \mathbf{0.985228}$$

b. Nilai `Normal` ($|S_{Normal}| = 7$):
- Data: P05(P), P06(N), P07(P), P09(P), P11(P), P13(P), P14(N) $\to$ Positif = 5, Negatif = 2
$$H(S_{Kol\_Normal}) = - \left( \frac{5}{7} \log_2 \frac{5}{7} + \frac{2}{7} \log_2 \frac{2}{7} \right) = - [0.714286(-0.485427) + 0.285714(-1.807355)] = \mathbf{0.863121}$$

Menghitung Entropi Rata-rata Berbobot $H(S, Kolesterol)$:
$$H(S, Kolesterol) = \frac{7}{14}(0.985228) + \frac{7}{14}(0.863121) = 0.492614 + 0.431560 = \mathbf{0.924174}$$

Menghitung Information Gain:
$$IG(S, Kolesterol) = 0.940285 - 0.924174 = \mathbf{0.016111}$$

---

#### 4. Atribut: Riwayat Keluarga
Atribut Riwayat Keluarga memiliki 2 nilai: **Ya**, **Tidak**.

a. Nilai `Ya` ($|S_{Ya}| = 6$):
- Data: P02(N), P06(N), P07(P), P11(P), P12(P), P14(N) $\to$ Positif = 3, Negatif = 3
$$H(S_{Riw\_Ya}) = - \left( \frac{3}{6} \log_2 \frac{3}{6} + \frac{3}{6} \log_2 \frac{3}{6} \right) = \mathbf{1.000000}$$

b. Nilai `Tidak` ($|S_{Tidak}| = 8$):
- Data: P01(N), P03(P), P04(P), P05(P), P08(N), P09(P), P10(P), P13(P) $\to$ Positif = 6, Negatif = 2
$$H(S_{Riw\_Tidak}) = - \left( \frac{6}{8} \log_2 \frac{6}{8} + \frac{2}{8} \log_2 \frac{2}{8} \right) = - [0.75(-0.415037) + 0.25(-2.000000)] = \mathbf{0.811278}$$

Menghitung Entropi Rata-rata Berbobot $H(S, Riwayat\_Keluarga)$:
$$H(S, Riwayat\_Keluarga) = \frac{6}{14}(1.000000) + \frac{8}{14}(0.811278) = 0.428571 + 0.463587 = \mathbf{0.892158}$$

Menghitung Information Gain:
$$IG(S, Riwayat\_Keluarga) = 0.940285 - 0.892158 = \mathbf{0.048127}$$

---

### Rekapitulasi Information Gain Iterasi 1 (Root Node Selection)
| No | Atribut Kandidat | Entropi Kondisional $H(S, A)$ | Information Gain $IG(S, A)$ | Keputusan |
|:---:|:---|:---:|:---:|:---:|
| 1 | **Usia** | **0.693536** | **0.246749** | **TERPILIH SEBAGAI ROOT NODE** |
| 2 | Tekanan Darah | 0.915208 | 0.025077 | Tidak Terpilih |
| 3 | Kolesterol | 0.924174 | 0.016111 | Tidak Terpilih |
| 4 | Riwayat Keluarga | 0.892158 | 0.048127 | Tidak Terpilih |

**Kesimpulan:** Atribut **Usia** memiliki nilai Information Gain tertinggi ($0.246749$) dan terpilih sebagai **Simpul Akar (Root Node)** pohon keputusan.

---

### Langkah 3: Ekspansi Cabang Turunan
Dari Root Node **Usia**, tercipta 3 cabang:

1. **Cabang `Paruh_Baya`:**
   Subset data: P03, P07, P12, P13. Seluruh 4 pasien berstatus **Positif (P)** ($H = 0$). Cabang ini langsung menghasilkan **Simpul Daun (*Leaf Node*): POSITIF**.

2. **Cabang `Muda`:**
   Subset data ($|S_{Muda}| = 5$): P01, P02, P08, P09, P11 (2 Positif, 3 Negatif).
   Kita evaluasi sisa atribut:
   - Jika diuji dengan **Riwayat_Keluarga**:
     - `Ya`: P02(N), P11(P) $\to$ Positif = 1, Negatif = 1 ($H=1$)
     - `Tidak`: P01(N), P08(N), P09(P) $\to$ Positif = 1, Negatif = 2 ($H=0.918$)
   - Jika diuji dengan **Tekanan_Darah**:
     - `Tinggi`: P01(N), P02(N) $\to$ Positif = 0, Negatif = 2 ($H=0$, murni Negatif)
     - `Normal`: P08(N), P09(P), P11(P) $\to$ Positif = 2, Negatif = 1 ($H=0.918$)
   - Jika diuji dengan **Kolesterol**:
     - `Tinggi`: P01(N), P02(N), P08(N) $\to$ Positif = 0, Negatif = 3 ($H=0$, **Murni Negatif!**)
     - `Normal`: P09(P), P11(P) $\to$ Positif = 2, Negatif = 0 ($H=0$, **Murni Positif!**)
   
   Menghitung Gain Kolesterol pada cabang Muda:
   $$H(S_{Muda}, Kolesterol) = \frac{3}{5}(0) + \frac{2}{5}(0) = 0$$
   $$IG(S_{Muda}, Kolesterol) = 0.970951 - 0 = \mathbf{0.970951 \text{ (Maksimal)}}$$
   **Atribut Kolesterol mempartisi cabang `Muda` secara sempurna!**
   - Jika Kolesterol = `Tinggi` $\to$ **NEGATIF**
   - Jika Kolesterol = `Normal` $\to$ **POSITIF**

3. **Cabang `Lanjut`:**
   Subset data ($|S_{Lanjut}| = 5$): P04, P05, P06, P10, P14 (3 Positif, 2 Negatif).
   Seluruh pasien pada cabang ini memiliki Tekanan Darah = `Normal` (P04, P05, P06, P10, P14), sehingga Tekanan Darah tidak dapat membedakan data.
   Kita evaluasi atribut **Riwayat_Keluarga**:
   - `Tidak`: P04(P), P05(P), P10(P) $\to$ Positif = 3, Negatif = 0 ($H=0$, **Murni Positif!**)
   - `Ya`: P06(N), P14(N) $\to$ Positif = 0, Negatif = 2 ($H=0$, **Murni Negatif!**)
   
   Menghitung Gain Riwayat Keluarga pada cabang Lanjut:
   $$H(S_{Lanjut}, Riwayat\_Keluarga) = \frac{3}{5}(0) + \frac{2}{5}(0) = 0$$
   $$IG(S_{Lanjut}, Riwayat\_Keluarga) = 0.970951 - 0 = \mathbf{0.970951 \text{ (Maksimal)}}$$
   **Atribut Riwayat Keluarga mempartisi cabang `Lanjut` secara sempurna!**
   - Jika Riwayat Keluarga = `Tidak` $\to$ **POSITIF**
   - Jika Riwayat Keluarga = `Ya` $\to$ **NEGATIF**

### Struktur Akhir Pohon Keputusan (Decision Tree)
```
[Usia?]
  ├── Paruh_Baya ───────────────────────────> [ POSITIF ]
  ├── Muda
  │     └── [Kolesterol?]
  │           ├── Tinggi ───────────────────> [ NEGATIF ]
  │           └── Normal ───────────────────> [ POSITIF ]
  └── Lanjut
        └── [Riwayat_Keluarga?]
              ├── Tidak ────────────────────> [ POSITIF ]
              └── Ya ───────────────────────> [ NEGATIF ]
```

---

## 6.3 Perhitungan Manual Naïve Bayes Classifier

### Langkah 1: Menghitung Probabilitas Prior Kelas
Dari $N = 14$ data pelatihan:
$$P(Y = \text{Positif}) = \frac{9}{14} \approx 0.642857$$
$$P(Y = \text{Negatif}) = \frac{5}{14} \approx 0.357143$$

### Langkah 2: Menghitung Tabel Likelihood (Kondisional) Tiap Fitur Terhadap Kelas
Berdasarkan frekuensi kemunculan pada data latih:

#### 1. Fitur Usia ($X_1$)
- $P(\text{Muda} | \text{Positif}) = \frac{2}{9}$ ; $P(\text{Muda} | \text{Negatif}) = \frac{3}{5}$
- $P(\text{Paruh\_Baya} | \text{Positif}) = \frac{4}{9}$ ; $P(\text{Paruh\_Baya} | \text{Negatif}) = \frac{0}{5} = 0.00$
- $P(\text{Lanjut} | \text{Positif}) = \frac{3}{9}$ ; $P(\text{Lanjut} | \text{Negatif}) = \frac{2}{5}$

#### 2. Fitur Tekanan Darah ($X_2$)
- $P(\text{Tinggi} | \text{Positif}) = \frac{2}{9}$ ; $P(\text{Tinggi} | \text{Negatif}) = \frac{2}{5}$
- $P(\text{Normal} | \text{Positif}) = \frac{7}{9}$ ; $P(\text{Normal} | \text{Negatif}) = \frac{3}{5}$

#### 3. Fitur Kolesterol ($X_3$)
- $P(\text{Tinggi} | \text{Positif}) = \frac{4}{9}$ ; $P(\text{Tinggi} | \text{Negatif}) = \frac{3}{5}$
- $P(\text{Normal} | \text{Positif}) = \frac{5}{9}$ ; $P(\text{Normal} | \text{Negatif}) = \frac{2}{5}$

#### 4. Fitur Riwayat Keluarga ($X_4$)
- $P(\text{Ya} | \text{Positif}) = \frac{3}{9}$ ; $P(\text{Ya} | \text{Negatif}) = \frac{3}{5}$
- $P(\text{Tidak} | \text{Positif}) = \frac{6}{9}$ ; $P(\text{Tidak} | \text{Negatif}) = \frac{2}{5}$

---

### Langkah 3: Pengujian Pasien Baru (Kasus Uji)
Diberikan sebuah data pasien baru yang datang untuk diagnosis:
$$\mathbf{x}_{baru} = (\text{Usia} = \text{Muda}, \text{Tekanan\_Darah} = \text{Tinggi}, \text{Kolesterol} = \text{Normal}, \text{Riwayat\_Keluarga} = \text{Ya})$$

Kita hitung nilai skor kemungkinan tidak ternormalisasi (*unnormalized posterior*) untuk kedua kelas:

#### A. Untuk Kelas Target = Positif ($Y = P$)
$$\text{Score}(P) = P(\text{Positif}) \times P(\text{Muda}|P) \times P(\text{Tinggi}|P) \times P(\text{Normal}|P) \times P(\text{Ya}|P)$$
Substitusi nilai pecahan:
$$\text{Score}(P) = \frac{9}{14} \times \frac{2}{9} \times \frac{2}{9} \times \frac{5}{9} \times \frac{3}{9}$$
Penyederhanaan matematis:
$$\text{Score}(P) = \left( \frac{9}{14} \times \frac{2}{9} \right) \times \left( \frac{2}{9} \times \frac{5}{9} \times \frac{3}{9} \right) = \frac{2}{14} \times \frac{30}{729} = \frac{1}{7} \times \frac{10}{243} = \frac{10}{1701} \approx \mathbf{0.00587889}$$

#### B. Untuk Kelas Target = Negatif ($Y = N$)
$$\text{Score}(N) = P(\text{Negatif}) \times P(\text{Muda}|N) \times P(\text{Tinggi}|N) \times P(\text{Normal}|N) \times P(\text{Ya}|N)$$
Substitusi nilai pecahan:
$$\text{Score}(N) = \frac{5}{14} \times \frac{3}{5} \times \frac{2}{5} \times \frac{2}{5} \times \frac{3}{5}$$
Penyederhanaan matematis:
$$\text{Score}(N) = \left( \frac{5}{14} \times \frac{3}{5} \right) \times \left( \frac{2}{5} \times \frac{2}{5} \times \frac{3}{5} \right) = \frac{3}{14} \times \frac{12}{125} = \frac{36}{1750} = \frac{18}{875} \approx \mathbf{0.02057143}$$

---

### Langkah 4: Normalisasi Probabilitas dan Keputusan Akhir
Menghitung Evidence $P(\mathbf{x}_{baru})$:
$$P(\mathbf{x}_{baru}) = \text{Score}(P) + \text{Score}(N) = 0.00587889 + 0.02057143 = 0.02645032$$

Probabilitas Posterior Ternormalisasi:
$$P(\text{Positif} | \mathbf{x}_{baru}) = \frac{0.00587889}{0.02645032} \approx \mathbf{22.23\%}$$
$$P(\text{Negatif} | \mathbf{x}_{baru}) = \frac{0.02057143}{0.02645032} \approx \mathbf{77.77\%}$$

### Evaluasi Keputusan:
$$\arg\max \{P(P|\mathbf{x}_{baru}), P(N|\mathbf{x}_{baru})\} = \arg\max \{0.2223, 0.7777\} \to \mathbf{NEGATIF}$$
Model Naïve Bayes memprediksi dengan tingkat keyakinan **$77.77\%$** bahwa pasien baru tersebut **TIDAK MENDERITA PENYAKIT JANTUNG (NEGATIF)**.

---

# BAB VII: EVALUASI KINERJA MODEL KLASIFIKASI

## 7.1 Confusion Matrix
Confusion Matrix adalah tabel kontinjensi $2 \times 2$ (untuk kasus biner) yang membandingkan prediksi model terhadap ground truth aktual:

| | Aktual Positif ($Y=1$) | Aktual Negatif ($Y=0$) |
|:---:|:---:|:---:|
| **Prediksi Positif ($\hat{Y}=1$)** | **True Positive (TP)** | **False Positive (FP)** *(Type I Error)* |
| **Prediksi Negatif ($\hat{Y}=0$)** | **False Negative (FN)** *(Type II Error)* | **True Negative (TN)** |

Dalam diagnosis medis, **False Negative (FN)** merupakan kesalahan fatal (pasien sakit dinyatakan sehat sehingga tidak mendapat penanganan medis segera).

## 7.2 Metrik Kinerja Standar
1. **Akurasi (*Accuracy*):** Proporsi seluruh prediksi yang tepat dari total data.
   $$\text{Accuracy} = \frac{TP + TN}{TP + TN + FP + FN}$$
   *Catatan Kritis:* Akurasi menjadi metrik yang menyesatkan (*paradox of accuracy*) jika dataset mengalami ketidakseimbangan kelas ekstrem (*imbalanced classes*).
2. **Presisi (*Precision* / *Positive Predictive Value*):** Tingkat ketepatan saat model memprediksi positif.
   $$\text{Precision} = \frac{TP}{TP + FP}$$
3. **Recall (*Sensitivity* / *True Positive Rate*):** Kemampuan model mendeteksi seluruh kasus positif riil.
   $$\text{Recall} = \frac{TP}{TP + FN}$$
4. **Spesifisitas (*Specificity* / *True Negative Rate*):** Kemampuan model mengenali kasus negatif riil.
   $$\text{Specificity} = \frac{TN}{TN + FP}$$
5. **F1-Score:** Rata-rata harmonik (*harmonic mean*) antara presisi dan recall. Digunakan ketika keseimbangan antara presisi dan recall dibutuhkan:
   $$\text{F1-Score} = 2 \times \frac{\text{Precision} \times \text{Recall}}{\text{Precision} + \text{Recall}} = \frac{2TP}{2TP + FP + FN}$$
6. **Balanced Accuracy:** Rata-rata aritmatika dari recall untuk setiap kelas:
   $$\text{Balanced Accuracy} = \frac{\text{Sensitivity} + \text{Specificity}}{2}$$

## 7.3 Kurva ROC (*Receiver Operating Characteristic*) dan AUC
Kurva ROC menggambarkan trade-off antara **True Positive Rate (TPR / Sensitivity)** pada sumbu Y terhadap **False Positive Rate (FPR = $1 - \text{Specificity}$)** pada sumbu X untuk berbagai variasi ambang batas (*threshold* keputusan $\theta \in [0, 1]$).
- **Garis Diagonal ($y = x$):** Merepresentasikan performa tebakan acak (*random guess*) dengan nilai $\text{AUC} = 0.50$.
- **Model Sempurna:** Melewati titik $(0, 1)$ dengan nilai $\text{AUC} = 1.00$.
- **Interpretasi AUC (*Area Under Curve*):** Probabilitas bahwa model akan memberi skor prediksi lebih tinggi kepada sampel positif yang dipilih secara acak daripada sampel negatif yang dipilih secara acak.

## 7.4 Metodologi Validasi Silang (*Cross-Validation*)
Untuk menjamin estimasi kinerja tidak bergantung pada keberuntungan pemisahan satu kali:
1. **K-Fold Cross Validation:** Dataset dibagi secara acak menjadi $K$ subset berukuran sama. Model dilatih $K$ kali, di mana setiap iterasi menggunakan $K-1$ fold untuk pelatihan dan 1 fold tersisa untuk pengujian. Nilai akhir dihitung sebagai rata-rata dari $K$ iterasi.
2. **Stratified K-Fold:** Varian wajib untuk data tidak seimbang, di mana setiap fold dijamin mempertahankan proporsi rasio kelas target yang sama persis dengan dataset asal.

---

# BAB VIII: ANALISIS KOMPARATIF KOMPLEKSITAS DAN KARAKTERISTIK ALGORITMA

## 8.1 Tabel Komparasi Big-O Kompleksitas Waktu dan Ruang
Misalkan $N$ adalah jumlah baris sampel, $d$ adalah jumlah dimensi fitur, $K$ adalah jumlah kelas, $n_{sv}$ adalah jumlah support vector, dan $L$ adalah jumlah epoch:

| Algoritma | Waktu Pelatihan (*Training*) | Waktu Inferensi (*Testing*) | Kompleksitas Memori |
|---|:---:|:---:|:---:|
| **Decision Tree (CART/C4.5)** | $O(d \cdot N \log N)$ | $O(\text{kedalaman}) \le O(d)$ | $O(\text{jumlah simpul})$ |
| **Naïve Bayes (Gaussian)** | $O(N \cdot d)$ *(Linear)* | $O(K \cdot d)$ | $O(K \cdot d)$ *(Sangat Ringan)* |
| **SVM (Linear)** | $O(N^2 \cdot d)$ | $O(d)$ | $O(d)$ |
| **SVM (RBF Kernel)** | $O(N^2 \cdot d)$ hingga $O(N^3)$ | $O(n_{sv} \cdot d)$ | $O(n_{sv} \cdot d)$ |
| **Logistic Regression** | $O(L \cdot N \cdot d)$ | $O(d)$ | $O(d)$ |
| **Neural Network (MLP)** | $O(L \cdot N \cdot \sum W_l)$ | $O(\sum W_l)$ | $O(\sum W_l)$ *(Tinggi)* |

---

## 8.2 Matriks Kelebihan, Kekurangan, dan Rekomendasi Kasus
| Algoritma | Kelebihan Utama | Kelemahan Utama | Skenario Rekomendasi Terbaik |
|---|---|---|---|
| **Decision Tree** | Interpretabilitas sangat tinggi (*white-box*), tidak butuh normalisasi fitur, menangani fitur kategorikal & kontinu secara alami. | Sangat rentan *overfitting*, batas keputusan kaku sejajar sumbu (*axis-parallel*), instabilitas data kecil. | Sistem pendukung keputusan klinis, audit regulasi perbankan, deteksi aturan bisnis. |
| **Naïve Bayes** | Komputasi tercepat di kelasnya, tidak rentan overfitting pada data kecil, sangat tangguh pada dimensi teks tinggi. | Asumsi *conditional independence* sering tidak realistis pada dunia nyata (*loss of feature correlation*). | Filter spam email, analisis sentimen teks cepat, klasifikasi topik dokumen. |
| **SVM** | Jaminan optimum global (fungsi konveks), margin maksimal mencegah overfitting, sangat ampuh pada ruang dimensi tinggi. | Komputasi kuadratik terhadap jumlah baris ($N$), interpretasi sulit (*black-box*), sensitif terhadap skala fitur. | Pengenalan citra wajah, klasifikasi ekspresi gen/bioinformatika, klasifikasi teks terbobot TF-IDF. |
| **Logistic Regression** | Menghasilkan probabilitas posterior terkalibrasi secara langsung, komputasi ringan, fungsi konveks. | Hanya mampu membentuk batas pemisah linier sederhana tanpa rekayasa fitur polinomial manual. | Credit scoring perbankan, estimasi risiko churn pelanggan, baseline benchmark pemodelan. |
| **Neural Network (MLP)** | Kemampuan representasi fungsi non-linier universal tak terbatas, mampu mengekstraksi representasi laten hierarkis. | Membutuhkan volume data latih masif, sangat boros komputasi/GPU, rentan jebakan overfitting, *black-box*. | Pengenalan pola suara, visi komputer (*computer vision*), pemodelan deret waktu multi-variat kompleks. |

---

# BAB IX: IMPLEMENTASI PROGRAM DENGAN PYTHON DAN SCIKIT-LEARN

Kode sumber Python berikut mengimplementasikan seluruh pipeline secara terpadu: membangun matriks data dari 14 pasien diagnosis jantung, melakukan pra-pemrosesan encoding, melatih kelima algoritma klasifikasi, dan mengevaluasi hasil kinerjanya.

```python
"""
========================================================================================
TUGAS TEKNIK KLASIFIKASI PENAMBANGAN DATA (INF11121)
Implementasi Komparasi Model Klasifikasi: Decision Tree, Naïve Bayes, SVM, LogReg, & MLP
Penulis: Balqis Manisa (NIM: 2401020069)
========================================================================================
"""

import numpy as np
import pandas as pd
from sklearn.model_selection import LeaveOneOut, cross_val_score
from sklearn.preprocessing import OrdinalEncoder
from sklearn.tree import DecisionTreeClassifier
from sklearn.naive_bayes import CategoricalNB
from sklearn.svm import SVC
from sklearn.linear_model import LogisticRegression
from sklearn.neural_network import MLPClassifier
from sklearn.metrics import accuracy_score, classification_report, confusion_matrix

# 1. KONSTRUKSI DATASET REKAM MEDIS 14 PASIEN
data_dict = {
    'Usia': ['Muda', 'Muda', 'Paruh_Baya', 'Lanjut', 'Lanjut', 'Lanjut', 'Paruh_Baya', 
             'Muda', 'Muda', 'Lanjut', 'Muda', 'Paruh_Baya', 'Paruh_Baya', 'Lanjut'],
    'Tekanan_Darah': ['Tinggi', 'Tinggi', 'Tinggi', 'Normal', 'Normal', 'Normal', 'Normal', 
                      'Normal', 'Normal', 'Normal', 'Normal', 'Normal', 'Tinggi', 'Normal'],
    'Kolesterol': ['Tinggi', 'Tinggi', 'Tinggi', 'Tinggi', 'Normal', 'Normal', 'Normal', 
                   'Tinggi', 'Normal', 'Tinggi', 'Normal', 'Tinggi', 'Normal', 'Tinggi'],
    'Riwayat_Keluarga': ['Tidak', 'Ya', 'Tidak', 'Tidak', 'Tidak', 'Ya', 'Ya', 
                         'Tidak', 'Tidak', 'Tidak', 'Ya', 'Ya', 'Tidak', 'Ya'],
    'Penyakit_Jantung': ['Negatif', 'Negatif', 'Positif', 'Positif', 'Positif', 'Negatif', 'Positif', 
                         'Negatif', 'Positif', 'Positif', 'Positif', 'Positif', 'Positif', 'Negatif']
}

df = pd.DataFrame(data_dict)
feature_cols = ['Usia', 'Tekanan_Darah', 'Kolesterol', 'Riwayat_Keluarga']
target_col = 'Penyakit_Jantung'

print("=== 5 Baris Pertama Dataset Rekam Medis ===")
print(df.head())

# 2. ENCODING FITUR KATEGORIKAL KE NUMERIK ORDINAL
encoder = OrdinalEncoder()
X_encoded = encoder.fit_transform(df[feature_cols])
y_encoded = np.where(df[target_col] == 'Positif', 1, 0)

# 3. INSTANSIASI LIMA MODEL KLASIFIKASI
models = {
    "Decision Tree (ID3/Entropy)": DecisionTreeClassifier(criterion='entropy', random_state=42),
    "Categorical Naïve Bayes": CategoricalNB(alpha=1.0),
    "Support Vector Machine (RBF)": SVC(kernel='rbf', C=1.0, probability=True, random_state=42),
    "Logistic Regression": LogisticRegression(solver='lbfgs', random_state=42),
    "Multi-Layer Perceptron (MLP)": MLPClassifier(hidden_layer_sizes=(8, 4), max_iter=1000, random_state=42)
}

# 4. PELATIHAN DAN PENGUJIAN KINERJA
print("\n=== HASIL PELATIHAN DAN EVALUASI PADA DATASET ASLI ===")
for name, clf in models.items():
    clf.fit(X_encoded, y_encoded)
    preds = clf.predict(X_encoded)
    acc = accuracy_score(y_encoded, preds)
    print(f"\nAlgoritma: {name}")
    print(f"Akurasi Pelatihan: {acc * 100:.2f}%")
    cm = confusion_matrix(y_encoded, preds)
    print(f"Confusion Matrix (TN={cm[0,0]}, FP={cm[0,1]}, FN={cm[1,0]}, TP={cm[1,1]}):")
    print(cm)

# 5. INFERENSI PADA PASIEN BARU SESUAI HITUNGAN MANUAL
# Pasien Baru: Muda, Tinggi, Normal, Ya
pasien_baru_raw = pd.DataFrame([['Muda', 'Tinggi', 'Normal', 'Ya']], columns=feature_cols)
pasien_baru_enc = encoder.transform(pasien_baru_raw)

print("\n" + "="*70)
print("INFERENSI PREDIKSI PASIEN BARU: [Muda, Tekanan=Tinggi, Kolesterol=Normal, Riwayat=Ya]")
print("="*70)

for name, clf in models.items():
    pred_class = clf.predict(pasien_baru_enc)[0]
    label_pred = "POSITIF" if pred_class == 1 else "NEGATIF"
    if hasattr(clf, "predict_proba"):
        probs = clf.predict_proba(pasien_baru_enc)[0]
        prob_neg, prob_pos = probs[0], probs[1]
        print(f"{name:30s} -> Prediksi: {label_pred:7s} | P(Neg)={prob_neg:.4f}, P(Pos)={prob_pos:.4f}")
    else:
        print(f"{name:30s} -> Prediksi: {label_pred:7s}")
```

---

# BAB X: KESIMPULAN DAN REKOMENDASI

Berdasarkan kajian teoritis, matematis, dan simulasi numerik yang telah dilakukan dalam naskah materi ajar teknik klasifikasi ini, dapat ditarik sejumlah kesimpulan komprehensif bagi mahasiswa teknik informatika:

1. **Pemilihan Model Berbasis Masalah:** Tidak ada algoritma klasifikasi tunggal yang paling unggul untuk segala kondisi (*No Free Lunch Theorem*). Untuk dataset berukuran kecil hingga menengah dengan kebutuhan audit dan penjelasan yang transparan, **Pohon Keputusan (Decision Tree)** merupakan pilihan utama berkat karakteristik *white-box* modelnya.
2. **Kekuatan Naïve Bayes pada Ruang Teks dan Probabilistik:** Asumsi *conditional independence* yang sederhana memungkinkan **Naïve Bayes** bekerja sangat efisien secara komputasional ($O(N \cdot d)$) dan tahan terhadap ancaman overfitting pada data sampel kecil, menjadikannya standar baku untuk klasifikasi teks, filter spam, dan diagnosis awal.
3. **Ketangguhan SVM pada Ruang Dimensi Tinggi:** **Support Vector Machine (SVM)** dengan *Kernel Trick* (terutama RBF) mampu menemukan bidang pemisah non-linier kompleks dengan prinsip pemaksimalan margin geometris yang meminimalkan risiko struktural, sangat ideal untuk data berdimensi tinggi seperti citra medis dan ekspresi genetika.
4. **Fleksibilitas Jaringan Saraf (ANN/MLP):** Menawarkan kapasitas pemodelan fungsi universal tanpa batas (*Universal Approximation Theorem*) melalui arsitektur berlapis dan fungsi aktivasi non-linier. Meskipun membutuhkan data latih masif dan komputasi intensif, MLP menjadi fondasi mendasar bagi transisi menuju paradigma *Deep Learning*.
5. **Validasi dan Metrik yang Objektif:** Akurasi bukanlah satu-satunya tolak ukur evaluasi yang valid, terutama pada domain kritis seperti rekam medis atau fraud detection. Insinyur data mining wajib mengintegrasikan metrik presisi, recall, F1-score, dan analisis kurva ROC-AUC dengan prosedur *Stratified K-Fold Cross Validation* guna menjamin keandalan model saat diterapkan di lingkungan produksi.

---

# DAFTAR PUSTAKA
1. Breiman, L., Friedman, J., Stone, C. J., & Olshen, R. A. (1984). *Classification and Regression Trees*. Belmont: CRC Press.
2. Cortes, C., & Vapnik, V. (1995). Support-vector networks. *Machine Learning*, 20(3), 273–297.
3. Cybenko, G. (1989). Approximation by superpositions of a sigmoidal function. *Mathematics of Control, Signals and Systems*, 2(4), 303–314.
4. Han, J., Kamber, M., & Pei, J. (2012). *Data Mining: Concepts and Techniques* (3rd ed.). Waltham: Morgan Kaufmann Publishers.
5. Matulatan, T., & Fadli, M. (2026). *Rencana Tugas Mahasiswa (RTM) dan Rencana Pembelajaran Semester (RPS) Penambangan Data (INF11121)*. Tanjungpinang: Program Studi S1 Teknik Informatika, Universitas Maritim Raja Ali Haji.
6. Minsky, M., & Papert, S. A. (1969). *Perceptrons: An Introduction to Computational Geometry*. Cambridge: MIT Press.
7. Mitchell, T. M. (1997). *Machine Learning*. New York: McGraw-Hill Education.
8. Pedregosa, F., Varoquaux, G., Gramfort, A., Michel, V., Thirion, B., Grisel, O., Blondel, M., Prettenhofer, P., Weiss, R., Dubourg, V., Vanderplas, J., Passos, A., Cournapeau, D., Brucher, M., Perrot, M., & Duchesnay, É. (2011). Scikit-learn: Machine learning in Python. *Journal of Machine Learning Research*, 12, 2825–2830.
9. Quinlan, J. R. (1986). Induction of decision trees. *Machine Learning*, 1(1), 81–106.
10. Quinlan, J. R. (1993). *C4.5: Programs for Machine Learning*. San Mateo: Morgan Kaufmann Publishers.
11. Rosenblatt, F. (1958). The perceptron: A probabilistic model for information storage and organization in the brain. *Psychological Review*, 65(6), 386–408.
12. Rumelhart, D. E., Hinton, G. E., & Williams, R. J. (1986). Learning representations by back-propagating errors. *Nature*, 323(6088), 533–536.
13. Shannon, C. E. (1948). A mathematical theory of communication. *Bell System Technical Journal*, 27(3), 379–423.
14. Tan, P.-N., Steinbach, M., Karpatne, A., & Kumar, V. (2019). *Introduction to Data Mining* (2nd ed.). New York: Pearson Education.
15. Vapnik, V. (1998). *Statistical Learning Theory*. New York: John Wiley & Sons.
16. Wolpert, D. H., & Macready, W. G. (1997). No free lunch theorems for optimization. *IEEE Transactions on Evolutionary Computation*, 1(1), 67–82.
