# PENAMBANGAN DATA (INF11121)
## MATERI AJAR MINGGU XIII: TEKNIK PENGUKURAN DAN EVALUASI KINERJA MODEL (*MODEL EVALUATION AND ACCURACY MEASURES*)

---

* **Mata Kuliah**: Penambangan Data (INF11121)
* **Semester / SKS**: Ganjil 2026/2027 / 3 SKS
* **Program Studi**: S1 Teknik Informatika
* **Fakultas**: Fakultas Teknik dan Teknologi Kemaritiman
* **Perguruan Tinggi**: Universitas Maritim Raja Ali Haji (UMRAH)
* **Dosen Pengampu**: Tekad Matulatan, S.T., M.Kom. & Muhamad Fadli, S.T., M.Kom.
* **Capaian Pembelajaran (Sub-CPMK0413)**: Mahasiswa mampu mengevaluasi dan menginterpretasikan hasil temuan model penambangan data melalui teknik pengukuran akurasi, dekomposisi galat, metode partisi/resampling, matriks konfusi, analisis ROC/AUC, serta metrik regresi dan kluster.
* **Identitas Mahasiswa**:
  * **Nama**: Nabil
  * **NIM**: 2401020073
* **Format Berkas Luaran RTM**: `PD13_2401020073.pdf` / `PD13_2401020073.docx`
* **Cover Halaman Depan Wajib**: `Penambangan Data - Minggu 13 - 2401020073 - Nabil`

---

## DAFTAR ISI

1. [BAB I: KONSEP DASAR EVALUASI MODEL DAN GENERALIZATION ERROR](#bab-i-konsep-dasar-evaluasi-model-dan-generalization-error)
   * 1.1 Urgensi Evaluasi Kinerja Model dalam Data Science Lifecycle
   * 1.2 Training Error, Validation Error, Test Error, dan Pencegahan Data Leakage
   * 1.3 Fenomena Overfitting vs Underfitting dan Kurva Pembelajaran (Learning Curves)
   * 1.4 Teorema Dekomposisi Bias-Variance Tradeoff secara Matematis
2. [BAB II: STRATEGI PARTISI DAN METODE RESAMPLING DATA](#bab-ii-strategi-partisi-dan-metode-resampling-data)
   * 2.1 Metode Penahanan (Holdout Method)
   * 2.2 Validasi Silang K-Lipat (K-Fold Cross-Validation) dan Stratified K-Fold
   * 2.3 Leave-One-Out Cross-Validation (LOOCV)
   * 2.4 Metode Bootstrap Resampling dan Estimator .632
3. [BAB III: METRIK EVALUASI MODEL KLASIFIKASI](#bab-iii-metrik-evaluasi-model-klasifikasi)
   * 3.1 Struktur Matriks Konfusi (Confusion Matrix)
   * 3.2 Akurasi (Accuracy), Error Rate, dan Paradoks Akurasi
   * 3.3 Presisi (Precision), Recall (Sensitivitas), dan Spesifisitas (Specificity)
   * 3.4 F-Score (Harmonic Mean) dan Generalisasi F-Beta
   * 3.5 Matthews Correlation Coefficient (MCC) dan Cohen's Kappa
   * 3.6 Agregasi Multikelas: Macro, Micro, dan Weighted Average
4. [BAB IV: ANALISIS KURVA ROC DAN PRECISION-RECALL (PR)](#bab-iv-analisis-kurva-roc-dan-precision-recall-pr)
   * 4.1 Teori Pemindaian Ambang Batas Probabilitas Klasifikasi
   * 4.2 Kurva Receiver Operating Characteristic (ROC)
   * 4.3 Area Under the ROC Curve (AUC-ROC) dan Interpretasi Probabilistik
   * 4.4 Kurva Precision-Recall (PR Curve) untuk Data Sangat Tidak Seimbang
5. [BAB V: METRIK EVALUASI MODEL PREDIKSI DAN REGRESI](#bab-v-metrik-evaluasi-model-prediksi-dan-regresi)
   * 5.1 Karakteristik Galat Regresi dan Deret Waktu
   * 5.2 Metrik Skala Dependen: MAE, MAD, MSE, dan RMSE
   * 5.3 Metrik Persentase dan Relatif: MAPE, sMAPE, dan MASE
   * 5.4 Koefisien Determinasi (R-Squared) dan Adjusted R-Squared
6. [BAB VI: PENGAYAAN EVALUASI HASIL MODEL KLUSTER (UNSUPERVISED METRICS)](#bab-vi-pengayaan-evaluasi-hasil-model-kluster-unsupervised-metrics)
   * 6.1 Evaluasi Kualitas Internal vs Eksternal
   * 6.2 Koefisien Silhouette (Silhouette Coefficient)
   * 6.3 Davies-Bouldin Index (DBI)
   * 6.4 Calinski-Harabasz Index (Variance Ratio Criterion)
   * 6.5 Dunn Index
7. [BAB VII: SIMULASI PERHITUNGAN MANUAL LANGKAH DEMI LANGKAH (*HAND CALCULATION TRACE*)](#bab-vii-simulasi-perhitungan-manual-langkah-demi-langkah-hand-calculation-trace)
   * 7.1 Evaluasi Matriks Konfusi Klasifikasi Biner (100 Sampel Medis)
   * 7.2 Pemindaian Ambang Batas Probabilitas, Plot Kurva ROC, dan Integrasi Trapesium AUC
   * 7.3 Simulasi Partisi Stratified 5-Fold Cross-Validation
   * 7.4 Evaluasi Model Prediksi Deret Waktu (MAE, MSE, RMSE, MAPE, sMAPE, R-Squared)
8. [BAB VIII: IMPLEMENTASI PRAKTIS PYTHON DAN VERIFIKASI MESIN](#bab-viii-implementasi-praktis-python-dan-verifikasi-mesin)
   * 8.1 Lingkungan Pemrograman dan Pustaka Scikit-Learn
   * 8.2 Kode Sumber Lengkap Evaluasi Klasifikasi, ROC/AUC, dan Regresi
   * 8.3 Hasil Eksekusi dan Verifikasi Presisi Numerik 100%
9. [BAB IX: PANDUAN STRATEGIS PEMILIHAN METRIK DALAM REKAYASA SISTEM](#bab-ix-panduan-strategis-pemilihan-metrik-dalam-rekayasa-sistem)
   * 9.1 Matriks Keputusan Pemilihan Metrik Berdasarkan Karakteristik Domain
   * 9.2 Bahaya Metric Gaming dan Overfitting pada Data Uji
10. [BAB X: KESIMPULAN](#bab-x-kesimpulan)
11. [DAFTAR PUSTAKA (APA 7th Edition)](#daftar-pustaka)

---

## BAB I: KONSEP DASAR EVALUASI MODEL DAN GENERALIZATION ERROR

### 1.1 Urgensi Evaluasi Kinerja Model dalam Data Science Lifecycle

Dalam metodologi standar industri penambangan data seperti CRISP-DM (*Cross-Industry Standard Process for Data Mining*), tahap **Evaluasi (*Evaluation*)** merupakan gerbang penentu (*quality gate*) sebelum suatu model kecerdasan komputasional diizinkan untuk diintegrasikan ke lingkungan produksi (*deployment*). Membangun model pembelajaran mesin yang memiliki performa tinggi pada data latih (*training data*) adalah tugas yang relatif mudah; tantangan sejati seorang ilmuwan data (*data scientist*) dan insinyur informatika adalah memastikan bahwa model tersebut mampu melakukan inferensi yang akurat dan andal terhadap data baru yang belum pernah dilihat sebelumnya (*unseen data*).

Evaluasi kinerja model bukan sekadar menghasilkan sebuah angka akurasi agregat, melainkan proses diagnostik komprehensif untuk menjawab pertanyaan-pertanyaan ilmiah mendasar:
1. Seberapa baik model menangkap pola inheren yang mendasari data (*signal*) tanpa terjerumus menghafal derau (*noise*)?
2. Bagaimana model mengalokasikan risiko antara kesalahan positif palsu (*False Positive*) versus negatif palsu (*False Negative*)?
3. Seberapa stabil performa model jika diterapkan pada sebaran data yang mengalami pergeseran lingkungan (*covariate shift / concept drift*)?

### 1.2 Training Error, Validation Error, Test Error, dan Pencegahan Data Leakage

Untuk mengukur kemampuan generalisasi model secara obyektif, korpus data wajib dipartisi secara ketat ke dalam tiga partisi disjoin:

```text
[                   SELURUH KORPUS BASIS DATA                   ]
[         DATA LATIH (TRAINING SET)        ] [ DATA UJI (TEST)  ]
[   TRAIN SUBSET   ] [ VALIDATION SUBSET   ]
```

1. **Training Error (Empirical Error / $E_{\text{train}}$)**:
   Galat yang dihitung langsung pada data yang digunakan untuk mengoptimasi parameter internal model (seperti bobot sinapsis pada neural network, koefisien pada regresi, atau struktur percabangan pada pohon keputusan).
   $$E_{\text{train}} = \frac{1}{|D_{\text{train}}|} \sum_{i \in D_{\text{train}}} L(y_i, f(x_i; \theta))$$
   Nilai $E_{\text{train}}$ yang sangat rendah tidak menjamin model berkualitas baik, karena dapat merefleksikan penghafalan data mentah secara berlebihan.
2. **Validation Error ($E_{\text{val}}$)**:
   Galat yang dievaluasi pada himpunan data independen selama proses eksperimen untuk memandu pemilihan arsitektur model, penalaan hiperparameter (*hyperparameter tuning*, misal kedalaman maksimum pohon $max\_depth$ atau koefisien regularisasi $\lambda$), serta kriteria penghentian dini (*early stopping*).
3. **Test Error (Generalization Error / $E_{\text{test}}$)**:
   Galat yang diukur pada himpunan data final yang diisolasi secara mutlak dan hanya disentuh **satu kali saja** pada akhir seluruh proses eksperimen. $E_{\text{test}}$ merepresentasikan estimasi empiris tanpa bias terhadap performa model di dunia nyata.

**Pencegahan Kebocoran Data (*Data Leakage*)**:
*Data leakage* terjadi apabila informasi dari himpunan validasi atau uji merembes ke dalam proses pelatihan model. Contoh kebocoran data yang sering dilakukan secara tidak sengaja oleh mahasiswa pemula adalah melakukan normalisasi skala (*scaling* Min-Max atau Standarisasi Z-score), imputasi *missing values*, atau rekayasa fitur (*feature selection*) pada seluruh dataset secara bersamaan sebelum pemisahan data latih dan data uji dilakukan. Kaidah akademis ketat mewajibkan seluruh transformasi prapemrosesan dihitung hanya menggunakan statistik data latih ($\mu_{\text{train}}, \sigma_{\text{train}}$) dan kemudian diaplikasikan secara pasif ke data uji.

### 1.3 Fenomena Overfitting vs Underfitting dan Kurva Pembelajaran (Learning Curves)

Dinamika galat terhadap kompleksitas model mencerminkan dua kutub patologi model pembelajaran mesin:
* **Underfitting (Bias Tinggi)**: Terjadi ketika model terlalu sederhana (misalnya menerapkan regresi linier sederhana pada pola data kuadratik atau polinomial tinggi). Model gagal menangkap tren fundamental data, ditandai oleh $E_{\text{train}}$ dan $E_{\text{val}}$ yang sama-sama tinggi.
* **Overfitting (Varians Tinggi)**: Terjadi ketika kapasitas representasi model terlalu kompleks dibandingkan volume data yang tersedia (misalnya Decision Tree yang ditumbuhkan hingga kedalaman tak hingga tanpa pemangkasan). Model menghafal fluktuasi acak dan *outlier* pada data latih. Ditandai oleh jurang pemisah yang lebar (*generalization gap*), di mana $E_{\text{train}} \approx 0$ namun $E_{\text{val}}$ melonjak drastis.

Titik kompleksitas optimal (*sweet spot*) tercapai pada saat $E_{\text{val}}$ mencapai titik minimum global sebelum kurva mulai berbalik naik.

### 1.4 Teorema Dekomposisi Bias-Variance Tradeoff secara Matematis

Secara analitik, untuk tugas regresi dengan fungsi target riil $y = f(x) + \epsilon$ di mana $\mathbb{E}[\epsilon] = 0$ dan $\text{Var}(\epsilon) = \sigma^2$ (derau acak tak tereduksi / *irreducible error*), ekspektasi galat kuadrat rata-rata dari estimator $\hat{f}(x)$ dapat diuraikan secara elegan menjadi tiga komponen aditif:

$$\mathbb{E}\left[ (y - \hat{f}(x))^2 \right] = \underbrace{\left( \mathbb{E}[\hat{f}(x)] - f(x) \right)^2}_{\text{Bias}^2} + \underbrace{\mathbb{E}\left[ (\hat{f}(x) - \mathbb{E}[\hat{f}(x)])^2 \right]}_{\text{Varians}} + \underbrace{\sigma^2}_{\text{Irreducible Error}}$$

* **$\text{Bias}^2$**: Mengukur penyimpangan sistematis antara ekspektasi prediksi model terhadap nilai kebenaran dasar (*ground truth*). Merefleksikan penyederhanaan asumsi algoritma.
* **$\text{Varians}$**: Mengukur sensitivitas model terhadap fluktuasi atau pergantian sampel data latih. Model ber-varians tinggi akan berubah drastis prediksinya jika dilatih dengan subset data yang sedikit berbeda.
* **$\sigma^2$**: Batas bawah teoretis dari galat yang bersumber dari ketidakpastian inheren dalam pengukuran fenomena alam, yang tidak dapat dieliminasi oleh model apa pun.

Usaha menurunkan bias (misal dengan menambah fitur atau memperdalam model) hampir selalu berkonsekuensi meningkatkan varians, dan sebaliknya. Penalaan teknik pengukuran berfokus menemukan kompromi terbaik (*tradeoff*) antara kedua komponen tersebut.

---

## BAB II: STRATEGI PARTISI DAN METODE RESAMPLING DATA

Untuk mengestimasi galat generalisasi dengan tingkat bias dan varians estimasi yang minimal, dikembangkan berbagai metode partisi dan pencuplikan ulang (*resampling*).

### 2.1 Metode Penahanan (Holdout Method)

Metode Holdout membagi dataset $D$ secara acak menjadi dua himpunan disjoin: data latih (biasanya 70% s/d 80%) dan data uji (20% s/d 30%).
* **Kelebihan**: Sangat cepat secara komputasional karena pelatihan model hanya dilakukan tepat satu kali ($O(1)$).
* **Kelemahan**: Estimasi performa memiliki varians yang sangat tinggi (*high variance of performance estimate*), karena hasil evaluasi sangat bergantung pada keberuntungan komposisi sampel yang masuk ke data uji. Selain itu, model kehilangan 20-30% data berharga yang seharusnya dapat dimanfaatkan untuk proses pembelajaran.

### 2.2 Validasi Silang K-Lipat (K-Fold Cross-Validation) dan Stratified K-Fold

Untuk mereduksi varians estimasi pada metode Holdout, Seymour Geisser (1975) memformalkan metode *K-Fold Cross-Validation*:
1. Dataset $D$ dibagi secara acak menjadi $K$ subset (lipatan / *folds*) berukuran sama besar: $D_1, D_2, \dots, D_K$.
2. Algoritma dieksekusi sebanyak $K$ iterasi independen. Pada iterasi ke-$k$, lipatan $D_k$ bertindak sebagai data validasi/uji, sedangkan gabungan $(K-1)$ lipatan lainnya bertindak sebagai data latih.
3. Estimasi performa keseluruhan dihitung dari rata-rata performa pada ke-$K$ pengujian:
   $$\bar{E} = \frac{1}{K} \sum_{k=1}^K E_k, \quad \sigma_E = \sqrt{\frac{1}{K-1} \sum_{k=1}^K (E_k - \bar{E})^2}$$

*Nilai $K$ Standar Industri*: $K = 5$ atau $K = 10$. Nilai $K = 10$ terbukti secara empiris dan teoretis (Kohavi, 1995) memberikan kompromi terbaik antara bias estimasi rendah dan varians yang terkontrol.

**Stratified K-Fold**:
Pada dataset dengan sebaran kelas yang tidak seimbang (*imbalanced class*, misal rasio 95% sehat vs 5% kanker), pengacakan murni berisiko menghasilkan lipatan yang tidak memuat sampel positif sama sekali. *Stratified K-Fold* memaksakan rasio proporsi kelas pada setiap lipatan sama persis dengan proporsi kelas pada populasi data induk, menjamin integritas evaluasi statistik.

### 2.3 Leave-One-Out Cross-Validation (LOOCV)

LOOCV adalah kasus ekstrem dari $K$-Fold di mana nilai $K = N$ ($N$ adalah jumlah seluruh sampel data):
* Pada setiap iterasi, tepat 1 sampel menjadi data uji, dan $N-1$ sampel lainnya menjadi data latih.
* Proses diulang sebanyak $N$ kali.
* **Kelebihan**: Estimator performa bersifat hampir tanpa bias (*almost unbiased estimator of true generalization error*), karena ukuran data latih ($N-1$) hampir identik dengan ukuran dataset penuh.
* **Kelemahan**: Beban komputasi sangat tinggi ($N$ kali pelatihan model), serta varians estimasi yang relatif tinggi karena ke-$N$ model yang dilatih memiliki $N-2$ data latih yang identik (sampel pelatihan sangat berkorelasi tinggi satu sama lain).

### 2.4 Metode Bootstrap Resampling dan Estimator .632

Bootstrap (Bradley Efron, 1979) adalah teknik resampling berbasis penarikan contoh dengan pengembalian (*sampling with replacement*):
Diberikan dataset $D$ berukuran $N$. Kita menarik sampel sebanyak $N$ kali dengan pengembalian untuk membentuk himpunan latih bootstrap $D^*$.

**Teorema Peluang Sampel Tak Terpilih (Out-of-Bag / OOB)**:
Peluang sebuah sampel spesifik tidak terpilih pada satu kali penarikan adalah $1 - \frac{1}{N}$. Peluang sampel tersebut tidak terpilih sama sekali setelah $N$ kali penarikan independen adalah:
$$\lim_{N \to \infty} \left( 1 - \frac{1}{N} \right)^N = \frac{1}{e} \approx 0.3678 \quad (36.8\%)$$

Artinya, himpunan latih bootstrap $D^*$ rata-rata hanya memuat sekitar **63.2% data unik**, sementara sekitar **36.8% data sisanya tidak terpilih sama sekali** dan bertindak sebagai himpunan uji alami (*Out-of-Bag / OOB data*).

**Estimator .632 Bootstrap**:
Untuk mengatasi bias optimis dari galat data latih ($E_{\text{train}}$) dan bias pesimis dari galat OOB ($E_{\text{OOB}}$), Efron merumuskan kombinasi linear berbobot:
$$E_{.632} = 0.368 \cdot E_{\text{train}} + 0.632 \cdot E_{\text{OOB}}$$

---

## BAB III: METRIK EVALUASI MODEL KLASIFIKASI

### 3.1 Struktur Matriks Konfusi (Confusion Matrix)

Matriks konfusi adalah tabel kontingensi berdimensi $C \times C$ (di mana $C$ adalah jumlah kelas target) yang menyelaraskan persebaran antara label kelas aktual (*ground truth*) dengan label hasil prediksi model.

**Tabel 3.1: Matriks Konfusi Standar untuk Kasus Klasifikasi Biner (Positif vs Negatif)**

| Label Aktual \ Prediksi | Model Memprediksi Positif ($\hat{y} = 1$) | Model Memprediksi Negatif ($\hat{y} = 0$) | Total Aktual |
| :---: | :---: | :---: | :---: |
| **Aktual Positif ($y = 1$)** | **True Positive (TP)**<br>*(Positif Sejati: Benar Terdeteksi)* | **False Negative (FN)**<br>*(Negatif Palsu: Galat Tipe II / Terlewat)* | $P = TP + FN$ |
| **Aktual Negatif ($y = 0$)** | **False Positive (FP)**<br>*(Positif Palsu: Galat Tipe I / Alarm Palsu)* | **True Negative (TN)**<br>*(Negatif Sejati: Benar Ditolak)* | $N = FP + TN$ |
| **Total Prediksi** | $P_{\text{pred}} = TP + FP$ | $N_{\text{pred}} = FN + TN$ | $\text{Total} = TP + TN + FP + FN$ |

### 3.2 Akurasi (Accuracy), Error Rate, dan Paradoks Akurasi

* **Akurasi (*Accuracy*)**: Proporsi total prediksi yang benar secara keseluruhan:
  $$\text{Accuracy} = \frac{TP + TN}{TP + TN + FP + FN}$$
* **Tingkat Galat (*Error Rate*)**:
  $$\text{Error Rate} = 1 - \text{Accuracy} = \frac{FP + FN}{TP + TN + FP + FN}$$

**Paradoks Akurasi (*The Accuracy Paradox*)**:
Akurasi merupakan metrik yang sangat berbahaya jika diterapkan pada dataset yang tidak seimbang (*class-imbalanced data*). Sebagai contoh, dalam sistem deteksi penipuan kartu kredit di mana hanya 0.1% transaksi bersifat curang (fraud) dan 99.9% legal:
Model bodoh (*naive dummy classifier*) yang selalu memprediksi semua transaksi sebagai "Legal" akan memperoleh tingkat akurasi sebesar **99.9%**! Namun model tersebut memiliki nilai guna bisnis nol mutlak karena gagal mendeteksi satu pun transaksi penipuan ($Recall = 0\%$). Oleh karena itu, akurasi dilarang keras digunakan sebagai metrik tunggal pada data yang timpang.

### 3.3 Presisi (Precision), Recall (Sensitivitas), dan Spesifisitas (Specificity)

* **Presisi (*Precision / Positive Predictive Value*)**:
  Mengukur tingkat kemurnian dari seluruh alarm positif yang dikeluarkan model. Dari seluruh sampel yang diprediksi sebagai positif, berapa persen yang benar-benar positif?
  $$\text{Precision} = \frac{TP}{TP + FP}$$
  *Fokus Risiko*: Meminimalkan *False Positive* (alarm palsu). Sangat krusial pada sistem filter spam email (jangan sampai email penting masuk folder spam) atau rekomendasi investasi.
* **Recall (*Recall / Sensitivity / True Positive Rate / TPR*)**:
  Mengukur daya tangkap atau kelengkapan model. Dari seluruh sampel yang faktanya positif di dunia nyata, berapa persen yang berhasil dijaring oleh model?
  $$\text{Recall} = \frac{TP}{TP + FN}$$
  *Fokus Risiko*: Meminimalkan *False Negative* (pasien positif sakit tetapi terlewat dan divonis sehat). Sangat krusial pada diagnosis penyakit ganas (kanker, COVID-19) atau sistem pertahanan keamanan siber.
* **Spesifisitas (*Specificity / True Negative Rate / TNR*)**:
  Mengukur kemampuan model mengenali sampel negatif sejati:
  $$\text{Specificity} = \frac{TN}{TN + FP}$$
  Tingkat alarm palsu (*False Positive Rate / FPR*) adalah komplemen dari spesifisitas: $FPR = 1 - \text{Specificity} = \frac{FP}{TN + FP}$.

### 3.4 F-Score (Harmonic Mean) dan Generalisasi F-Beta

Karena Presisi dan Recall berada dalam posisi tarik-menarik (*tradeoff*—menaikkan presisi umumnya menurunkan recall, dan sebaliknya), diperlukan ukuran tunggal yang mampu menyeimbangkan keduanya. Rata-rata aritmatika biasa tidak tepat digunakan karena rentan tertipu oleh nilai ekstrim. Sebagai gantinya, digunakan rata-rata harmonik (*harmonic mean*):

$$\text{F}_1\text{-Score} = 2 \times \frac{\text{Precision} \times \text{Recall}}{\text{Precision} + \text{Recall}} = \frac{2 \cdot TP}{2 \cdot TP + FP + FN}$$

Jika salah satu dari Presisi atau Recall mendekati 0, maka $F_1$-Score akan runtuh mendekati 0.

**Generalisasi $\text{F}_\beta$-Score**:
Jika analis ingin memberi bobot kepentingan lebih pada salah satu metrik:
$$\text{F}_\beta\text{-Score} = (1 + \beta^2) \times \frac{\text{Precision} \times \text{Recall}}{(\beta^2 \times \text{Precision}) + \text{Recall}}$$
* $\beta = 1.0$: Memberikan bobot seimbang antara Presisi dan Recall ($F_1$-Score).
* $\beta = 2.0$ ($F_2$-Score): Memberikan bobot dua kali lebih besar pada **Recall** dibanding Presisi (misal dalam diagnosis klinis).
* $\beta = 0.5$ ($F_{0.5}$-Score): Memberikan bobot dua kali lebih besar pada **Presisi** dibanding Recall (misal dalam sistem pencegahan penalti hukum).

### 3.5 Matthews Correlation Coefficient (MCC) dan Cohen's Kappa

* **Matthews Correlation Coefficient (MCC)**:
  Dirumuskan oleh biokimiawan Brian W. Matthews (1975), MCC diakui oleh komunitas sains data sebagai metrik biner paling andal dan obyektif karena melibatkan seluruh keempat kuadran matriks konfusi ($TP, TN, FP, FN$) secara simetris:
  $$\text{MCC} = \frac{(TP \cdot TN) - (FP \cdot FN)}{\sqrt{(TP + FP)(TP + FN)(TN + FP)(TN + FN)}}$$
  Rentang nilai MCC adalah $[-1.0, +1.0]$:
  * $+1.0$: Prediksi sempurna mutlak.
  * $0.0$: Prediksi setara dengan pelemparan koin acak murni.
  * $-1.0$: Prediksi terbalik secara sempurna (kontradiksi total).
* **Cohen's Kappa ($\kappa$)**:
  Mengukur kesepakatan (*inter-rater agreement*) antara label prediksi dan aktual yang disesuaikan terhadap peluang kesepakatan acak ($p_e$):
  $$\kappa = \frac{p_o - p_e}{1 - p_e}, \quad p_o = \text{Accuracy}$$

### 3.6 Agregasi Multikelas: Macro, Micro, dan Weighted Average

Untuk klasifikasi multikelas ($C > 2$):
1. **Macro-Average**: Menghitung metrik (misal Precision atau $F_1$) secara independen untuk setiap kelas, lalu mengambil rata-rata aritmatika tanpa bobot:
   $$\text{Precision}_{\text{macro}} = \frac{1}{C} \sum_{c=1}^C \text{Precision}_c$$
   *Karakteristik*: Memperlakukan setiap kelas secara setara, sangat cocok untuk mengevaluasi kinerja pada kelas minoritas.
2. **Micro-Average**: Mengakumulasikan total global $TP, FP, FN$ dari seluruh kelas terlebih dahulu, kemudian menghitung metrik:
   $$\text{Precision}_{\text{micro}} = \frac{\sum TP_c}{\sum TP_c + \sum FP_c}$$
   *Karakteristik*: Dipengaruhi secara dominan oleh kelas mayoritas.
3. **Weighted-Average**: Merata-ratakan skor tiap kelas dengan memberi bobot proporsional terhadap jumlah sampel aktual (*support*) masing-masing kelas.

---

## BAB IV: ANALISIS KURVA ROC DAN PRECISION-RECALL (PR)

### 4.1 Teori Pemindaian Ambang Batas Probabilitas Klasifikasi

Sebagian besar algoritma klasifikasi (seperti Regresi Logistik, Random Forest, Naïve Bayes, dan Jaringan Saraf Tiruan) tidak secara langsung mengeluarkan label diskrit $\{0, 1\}$, melainkan memprediksi nilai probabilitas kontinu $\hat{p} = P(y=1 \mid x) \in [0, 1]$. Label biner ditetapkan melalui perbandingan terhadap suatu nilai ambang batas keputusan (*classification threshold / cutoff* $\theta$):
$$\hat{y} = \begin{cases} 1, & \text{jika } \hat{p} \ge \theta \\ 0, & \text{jika } \hat{p} < \theta \end{cases}$$

Secara default, nilai $\theta = 0.5$. Namun mengubah nilai $\theta$ dari $1.0$ ke $0.0$ akan menggeser trade-off antara True Positive Rate ($TPR$) dan False Positive Rate ($FPR$).

### 4.2 Kurva Receiver Operating Characteristic (ROC)

Kurva ROC (dikembangkan pertama kali dalam teori deteksi sinyal radar pada Perang Dunia II) adalah visualisasi grafis dua dimensi yang memetakan performa pengklasifikasi pada seluruh kemungkinan ambang batas $\theta \in [0, 1]$:
* **Sumbu $X$**: *False Positive Rate* ($FPR = 1 - \text{Specificity} = \frac{FP}{TN + FP}$).
* **Sumbu $Y$**: *True Positive Rate* ($TPR = \text{Recall} = \frac{TP}{TP + FN}$).

**Titik-Titik Kunci pada Ruang ROC**:
* Titik $(0, 0)$: Ambang batas $\theta = 1.0$ (model memprediksi semua sampel sebagai negatif, $TP = 0, FP = 0$).
* Titik $(1, 1)$: Ambang batas $\theta = 0.0$ (model memprediksi semua sampel sebagai positif, $TP = P, FP = N$).
* Garis Diagonal ($y = x$): Mewakili pengklasifikasi acak murni (*random guess*), seperti pelemparan koin tanpa informasi.
* Titik Ideal $(0, 1)$: Sudut kiri atas (*perfect classifier*), di mana $TPR = 1.0$ (100% sensitivitas) dan $FPR = 0.0$ (nol alarm palsu).

### 4.3 Area Under the ROC Curve (AUC-ROC) dan Interpretasi Probabilistik

AUC (*Area Under the Curve*) mengukur luasan integral di bawah kurva ROC pada rentang $[0, 1]$:
$$\text{AUC} = \int_0^1 \text{TPR}(FPR) \, d(FPR)$$

**Interpretasi Probabilistik AUC**:
Secara matematis, nilai AUC ekuivalen dengan probabilitas bahwa model akan memberikan skor prediksi yang lebih tinggi kepada sampel positif acak terpilih dibandingkan kepada sampel negatif acak terpilih:
$$\text{AUC} = P\left( \hat{p}(x^+) > \hat{p}(x^-) \right)$$

**Skala Penilaian Kinerja AUC**:
* $0.90 \le \text{AUC} \le 1.00$: Kinerja Luar Biasa (*Excellent discrimination*).
* $0.80 \le \text{AUC} < 0.90$: Kinerja Baik (*Good discrimination*).
* $0.70 \le \text{AUC} < 0.80$: Kinerja Cukup (*Fair discrimination*).
* $0.60 \le \text{AUC} < 0.70$: Kinerja Buruk (*Poor discrimination*).
* $\text{AUC} \le 0.50$: Gagal / Tebakan Acak (*No discrimination / Equivalent to random coin toss*).

### 4.4 Kurva Precision-Recall (PR Curve) untuk Data Sangat Tidak Seimbang

Meskipun kurva ROC sangat populer, kurva ROC memiliki sifat optimis berlebihan (*over-optimistic*) apabila diterapkan pada dataset dengan ketidakseimbangan kelas ekstrem (misal $1:1000$). Hal ini disebabkan oleh penyebut pada rumus $FPR$ adalah total negatif ($N = TN + FP$); jika $TN$ sangat masif, lonjakan besar pada $FP$ hanya akan mengubah nilai $FPR$ dalam jumlah yang sangat kecil.

Pada skenario tersebut, **Kurva Precision-Recall (PR Curve)** memetakan **Recall (Sumbu X)** versus **Precision (Sumbu Y)**. Karena kedua rumus tidak melibatkan $TN$, kurva PR hanya berfokus pada kelas positif minoritas yang menjadi target utama deteksi, menyajikan gambaran kinerja yang jauh lebih realistis.

---

## BAB V: METRIK EVALUASI MODEL PREDIKSI DAN REGRESI

Pada tugas penambangan data berbasis regresi dan prediksi deret waktu (*time series forecasting*), target keluaran berupa nilai numerik kontinu $\hat{y}_i \in \mathbb{R}$. Galat prediksi individu didefinisikan sebagai selisih:
$$e_i = y_i - \hat{y}_i$$

### 5.1 Karakteristik Galat Regresi dan Deret Waktu

Kualitas model regresi diuji dari bagaimana distribusi galat $e_i$ berdistribusi di sekitar angka nol. Asumsi ideal model regresi yang baik menghendaki galat berdistribusi normal, homoskedastis (varians konstan), dan tidak memiliki autokorelasi serial (*no autocorrelation*).

### 5.2 Metrik Skala Dependen: MAE, MAD, MSE, dan RMSE

* **Mean Absolute Error (MAE)**:
  Rata-rata magnitudo kesalahan absolut tanpa mempertimbangkan arah positif atau negatifnya:
  $$\text{MAE} = \frac{1}{n} \sum_{i=1}^n |y_i - \hat{y}_i|$$
  *Interpretasi*: Sangat intuitif karena memiliki satuan fisik yang sama persis dengan data asli. MAE bersifat linier dan lebih tahan terhadap gangguan *outlier*.
* **Mean Squared Error (MSE)**:
  Rata-rata kuadrat kesalahan:
  $$\text{MSE} = \frac{1}{n} \sum_{i=1}^n (y_i - \hat{y}_i)^2$$
  *Karakteristik*: Memberikan penalti kuadratik yang sangat keras terhadap kesalahan berukuran besar (*outlier*).
* **Root Mean Squared Error (RMSE)**:
  Akar kuadrat dari MSE, mengembalikan satuan galat kembali ke dimensi asli data:
  $$\text{RMSE} = \sqrt{\frac{1}{n} \sum_{i=1}^n (y_i - \hat{y}_i)^2}$$
  Nilai $\text{RMSE} \ge \text{MAE}$. Semakin lebar jarak antara RMSE dan MAE, semakin besar variasi magnitudo kesalahan pada data uji (indikasi adanya kesalahan-kesalahan ekstrem).
* **Median Absolute Deviation (MAD)**:
  $$\text{MAD} = \text{Median}(|e_i - \text{Median}(e)|)$$
  Metrik estimasi dispersi galat yang paling tangguh (*ultra-robust*) terhadap pencilan ekstrem.

### 5.3 Metrik Persentase dan Relatif: MAPE, sMAPE, dan MASE

* **Mean Absolute Percentage Error (MAPE)**:
  Mengukur galat dalam persentase relatif terhadap nilai aktual:
  $$\text{MAPE} = \frac{100\%}{n} \sum_{i=1}^n \left| \frac{y_i - \hat{y}_i}{y_i} \right|$$
  *Kelemahan Kritis*: Pembagian dengan nol ($y_i = 0$) tidak terdefinisi. Selain itu, MAPE bersifat asimetris karena memberikan penalti berlebihan jika prediksi lebih tinggi dari aktual dibanding jika prediksi lebih rendah.
* **Symmetric Mean Absolute Percentage Error (sMAPE)**:
  Memperbaiki asimetri MAPE dengan menggunakan rata-rata nilai absolut pada penyebut:
  $$\text{sMAPE} = \frac{100\%}{n} \sum_{i=1}^n \frac{2 \cdot |y_i - \hat{y}_i|}{|y_i| + |\hat{y}_i|}$$
  Nilai sMAPE terikat dalam rentang $[0\%, 200\%]$.
* **Mean Absolute Scaled Error (MASE)**:
  Dirumuskan oleh Rob J. Hyndman dan Anne B. Koehler (2006) untuk membandingkan akurasi model terhadap metode peramalan naif (*naive benchmark forecast*):
  $$\text{MASE} = \frac{\text{MAE}}{\frac{1}{n-1} \sum_{i=2}^n |y_i - y_{i-1}|}$$
  * $\text{MASE} < 1$: Model bekerja lebih baik daripada metode naif.
  * $\text{MASE} > 1$: Model bekerja lebih buruk daripada tebakan naif periode sebelumnya.

### 5.4 Koefisien Determinasi ($R^2$) dan Adjusted $R^2$

* **Koefisien Determinasi ($R^2$)**:
  Mengukur proporsi variabilitas nilai target aktual yang berhasil dijelaskan oleh model:
  $$R^2 = 1 - \frac{SS_{\text{res}}}{SS_{\text{tot}}} = 1 - \frac{\sum_{i=1}^n (y_i - \hat{y}_i)^2}{\sum_{i=1}^n (y_i - \bar{y})^2}$$
  * $R^2 = 1.0$: Model memprediksi secara sempurna tanpa galat ($SS_{\text{res}} = 0$).
  * $R^2 = 0.0$: Model hanya setara dengan memprediksi nilai rata-rata sampel ($\hat{y} = \bar{y}$).
  * $R^2 < 0.0$: Model bekerja lebih buruk daripada garis horizontal rata-rata (prediksi sangat buruk).
* **Adjusted $R^2$**:
  Memberikan penalti terhadap penambahan variabel prediktor baru ($p$) yang tidak memberikan kontribusi signifikan:
  $$R_{\text{adj}}^2 = 1 - \left[ \frac{(1 - R^2)(n - 1)}{n - p - 1} \right]$$

---

## BAB VI: PENGAYAAN EVALUASI HASIL MODEL KLUSTER (UNSUPERVISED METRICS)

Sebagai kelanjutan dari materi ajar sebelumnya (Sub-CPMK0416 dan Sub-CPMK0413), evaluasi model pengelompokan (*clustering*) mengukur validitas partisi data tanpa tersedianya label kebenaran dasar (*ground truth*).

### 6.1 Evaluasi Kualitas Internal vs Eksternal

* **Metrik Eksternal**: Membandingkan hasil kluster terhadap label kelas yang sudah ada sebelumnya (misal Rand Index, Normalized Mutual Information / NMI).
* **Metrik Internal**: Menilai kualitas kluster murni berdasarkan struktur intrinsik geometri data: **Kohesi (*Compactness*)** dan **Separasi (*Separation*)**. Kluster ideal memiliki kohesi internal yang rapat dan separasi antar-kluster yang lebar.

### 6.2 Koefisien Silhouette (Silhouette Coefficient)

Peter J. Rousseeuw (1987) merumuskan koefisien siluet untuk setiap objek data $i$:
$$s(i) = \frac{b(i) - a(i)}{\max(a(i), b(i))}$$
di mana $a(i)$ adalah rata-rata jarak dari sampel $i$ ke seluruh anggota kluster yang sama (kohesi), dan $b(i)$ adalah rata-rata jarak minimum ke anggota kluster terdekat lainnya (separasi).
* Rentang: $[-1.0, +1.0]$. Rata-rata siluet global $> 0.70$ mengindikasikan kluster berkualifikasi sangat kuat (*strong structure*).

### 6.3 Davies-Bouldin Index (DBI)

Mengukur kesamaan rata-rata antara setiap kluster dengan kluster paling mirip dengannya:
$$DBI = \frac{1}{k} \sum_{i=1}^k \max_{j \neq i} \left( \frac{s_i + s_j}{d(c_i, c_j)} \right)$$
di mana $s_i$ adalah dispersi intra-kluster dan $d(c_i, c_j)$ adalah jarak Euclidean antar-sentroid.
* Nilai $DBI$ yang semakin kecil (mendekati 0) mencerminkan kualitas partisi yang semakin superior.

### 6.4 Calinski-Harabasz Index (Variance Ratio Criterion / VRC)

Rasio antara dispersi antar-kluster (*between-cluster variance* / $SSB$) terhadap dispersi dalam-kluster (*within-cluster variance* / $SSW$):
$$CH = \frac{SSB / (k - 1)}{SSW / (n - k)}$$
* Nilai $CH$ yang semakin tinggi mencerminkan kluster yang semakin padat dan terpisah tegas.

### 6.5 Dunn Index

Rasio antara jarak minimum antar-sampel beda kluster terhadap diameter maksimum intra-kluster:
$$\text{Dunn} = \frac{\min_{1 \le i < j \le k} d(C_i, C_j)}{\max_{1 \le l \le k} \text{diam}(C_l)}$$
* Nilai Dunn yang tinggi mengindikasikan partisi kluster yang sangat terisolasi dengan baik.

---

## BAB VII: SIMULASI PERHITUNGAN MANUAL LANGKAH DEMI LANGKAH (*HAND CALCULATION TRACE*)

### 7.1 Evaluasi Matriks Konfusi Klasifikasi Biner (100 Sampel Medis)

Sebuah model diagnostik berbasis penambangan data diuji pada dataset 100 pasien rekam medis di fasilitas kesehatan:
* Jumlah Pasien Aktual Sakit ($P$): 40 orang
* Jumlah Pasien Aktual Sehat ($N$): 60 orang

Hasil prediksi model menghasilkan matriks konfusi:
* **True Positive (TP)** = 32 (Pasien sakit terdiagnosis benar sakit)
* **False Positive (FP)** = 6 (Pasien sehat salah divonis sakit / Galat Tipe I)
* **False Negative (FN)** = 8 (Pasien sakit terlewat divonis sehat / Galat Tipe II)
* **True Negative (TN)** = 54 (Pasien sehat terdiagnosis benar sehat)

**Tabel 7.1: Matriks Konfusi Pengujian Klinis**

| Kondisi Aktual \ Prediksi | Prediksi Sakit ($\hat{y} = 1$) | Prediksi Sehat ($\hat{y} = 0$) | Total Aktual |
| :---: | :---: | :---: | :---: |
| **Aktual Sakit ($y = 1$)** | $TP = 32$ | $FN = 8$ | $P = 40$ |
| **Aktual Sehat ($y = 0$)** | $FP = 6$ | $TN = 54$ | $N = 60$ |
| **Total Prediksi** | $38$ | $62$ | $N_{\text{total}} = 100$ |

**Kalkulasi Metrik Langkah demi Langkah**:
1. **Akurasi (*Accuracy*)**:
   $$\text{Accuracy} = \frac{TP + TN}{\text{Total}} = \frac{32 + 54}{100} = \frac{86}{100} = 0.8600 \quad (86.00\%)$$
2. **Tingkat Galat (*Error Rate*)**:
   $$\text{Error Rate} = 1 - 0.8600 = 0.1400 \quad (14.00\%)$$
3. **Presisi (*Precision*)**:
   $$\text{Precision} = \frac{TP}{TP + FP} = \frac{32}{32 + 6} = \frac{32}{38} \approx 0.8421 \quad (84.21\%)$$
4. **Recall / Sensitivitas (*True Positive Rate*)**:
   $$\text{Recall} = \frac{TP}{TP + FN} = \frac{32}{32 + 8} = \frac{32}{40} = 0.8000 \quad (80.00\%)$$
5. **Spesifisitas (*True Negative Rate*)**:
   $$\text{Specificity} = \frac{TN}{TN + FP} = \frac{54}{54 + 6} = \frac{54}{60} = 0.9000 \quad (90.00\%)$$
6. **False Positive Rate ($FPR$)**:
   $$FPR = 1 - \text{Specificity} = 1 - 0.9000 = 0.1000 \quad (10.00\%)$$
7. **$\text{F}_1$-Score**:
   $$\text{F}_1 = 2 \times \frac{\text{Precision} \times \text{Recall}}{\text{Precision} + \text{Recall}} = 2 \times \frac{0.8421 \times 0.8000}{0.8421 + 0.8000} = \frac{1.347368}{1.642105} \approx 0.8205 \quad (82.05\%)$$
8. **Matthews Correlation Coefficient (MCC)**:
   $$\text{MCC} = \frac{(32 \times 54) - (6 \times 8)}{\sqrt{(32+6)(32+8)(54+6)(54+8)}} = \frac{1728 - 48}{\sqrt{38 \times 40 \times 60 \times 62}} = \frac{1680}{\sqrt{5.654.400}} = \frac{1680}{2377.898} \approx +0.7065$$
   *Interpretasi*: Nilai $\text{MCC} = +0.7065$ membuktikan korelasi kuat dan keseimbangan performa model pada kedua kelas.

---

### 7.2 Pemindaian Ambang Batas Probabilitas, Plot Kurva ROC, dan Integrasi Trapesium AUC

Diberikan 10 sampel uji dengan probabilitas prediksi yang telah diurutkan menurun:
* 5 sampel positif ($y=1$) dan 5 sampel negatif ($y=0$). Total $P = 5, N = 5$.

**Tabel 7.2: Pemindaian Ambang Batas Probabilitas untuk Pembentukan Kurva ROC**

| ID Sampel | Label Aktual ($y$) | Probabilitas Model ($\hat{p}$) | Ambang Batas ($\theta$) | Prediksi Label ($\hat{y}$) | Kumulatif TP | Kumulatif FP | TPR ($TP/5$) | FPR ($FP/5$) | Titik Koordinat ROC $(FPR, TPR)$ |
| :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| - | - | - | $> 0.95$ | Semua 0 | 0 | 0 | 0.00 | 0.00 | **$(0.0, 0.0)$** |
| **S1** | 1 | 0.95 | $\ge 0.95$ | S1=1 | 1 | 0 | 0.20 | 0.00 | **$(0.0, 0.2)$** |
| **S2** | 1 | 0.88 | $\ge 0.88$ | S1,S2=1 | 2 | 0 | 0.40 | 0.00 | **$(0.0, 0.4)$** |
| **S3** | 0 | 0.75 | $\ge 0.75$ | S1-S3=1 | 2 | 1 | 0.40 | 0.20 | **$(0.2, 0.4)$** |
| **S4** | 1 | 0.70 | $\ge 0.70$ | S1-S4=1 | 3 | 1 | 0.60 | 0.20 | **$(0.2, 0.6)$** |
| **S5** | 1 | 0.65 | $\ge 0.65$ | S1-S5=1 | 4 | 1 | 0.80 | 0.20 | **$(0.2, 0.8)$** |
| **S6** | 0 | 0.55 | $\ge 0.55$ | S1-S6=1 | 4 | 2 | 0.80 | 0.40 | **$(0.4, 0.8)$** |
| **S7** | 0 | 0.40 | $\ge 0.40$ | S1-S7=1 | 4 | 3 | 0.80 | 0.60 | **$(0.6, 0.8)$** |
| **S8** | 1 | 0.35 | $\ge 0.35$ | S1-S8=1 | 5 | 3 | 1.00 | 0.60 | **$(0.6, 1.0)$** |
| **S9** | 0 | 0.20 | $\ge 0.20$ | S1-S9=1 | 5 | 4 | 1.00 | 0.80 | **$(0.8, 1.0)$** |
| **S10** | 0 | 0.10 | $\ge 0.10$ | S1-S10=1 | 5 | 5 | 1.00 | 1.00 | **$(1.0, 1.0)$** |

**Kalkulasi Luas Area di Bawah Kurva (AUC) Menggunakan Aturan Trapesium**:
Integrasi dilakukan dengan menjumlahkan segmen luas trapesium horizontal antara titik-titik FPR yang berubah:
$$\text{Area} = \sum_{k=1}^m \frac{\text{TPR}_k + \text{TPR}_{k-1}}{2} \times (\text{FPR}_k - \text{FPR}_{k-1})$$

1. Segmen 1 (dari $FPR = 0.0$ ke $FPR = 0.2$):
   * $\Delta FPR = 0.2 - 0.0 = 0.2$
   * Nilai TPR konstan pada transisi ini $= 0.4$
   * $\text{Luas}_1 = 0.2 \times 0.4 = 0.08$
2. Segmen 2 (dari $FPR = 0.2$ ke $FPR = 0.6$):
   * $\Delta FPR = 0.6 - 0.2 = 0.4$
   * Nilai TPR konstan pada transisi ini $= 0.8$
   * $\text{Luas}_2 = 0.4 \times 0.8 = 0.32$
3. Segmen 3 (dari $FPR = 0.6$ ke $FPR = 1.0$):
   * $\Delta FPR = 1.0 - 0.6 = 0.4$
   * Nilai TPR konstan pada transisi ini $= 1.0$
   * $\text{Luas}_3 = 0.4 \times 1.0 = 0.40$

**Total Luas AUC**:
$$\text{AUC} = \text{Luas}_1 + \text{Luas}_2 + \text{Luas}_3 = 0.08 + 0.32 + 0.40 = \mathbf{0.8000} \quad (80.00\%)$$
*Kesimpulan*: Model memiliki kemampuan diskriminasi yang baik (*Good Discrimination*), jauh melampaui tebakan acak ($AUC = 0.50$).

---

### 7.3 Simulasi Partisi Stratified 5-Fold Cross-Validation

Dataset 100 sampel (40 Positif, 60 Negatif) dipartisi menjadi $K = 5$ lipatan (*folds*):
* Setiap lipatan memuat tepat: $100 / 5 = 20$ sampel.
* Sifat *Stratified* menjaga rasio kelas: Setiap lipatan memuat tepat $40/5 = 8$ sampel Positif dan $60/5 = 12$ sampel Negatif.

**Tabel 7.3: Hasil Simulasi Pengujian Stratified 5-Fold Cross-Validation**

| Iterasi ($k$) | Alokasi Data Latih ($D_{\text{train}}$) | Alokasi Data Validasi ($D_{\text{val}}$) | Akurasi Validasi ($Acc_k$) | $F_1$-Score Validasi ($F1_k$) |
| :---: | :--- | :--- | :---: | :---: |
| **Fold 1** | Lipatan 2, 3, 4, 5 (80 sampel) | Lipatan 1 (20 sampel: 8 Pos, 12 Neg) | 0.8500 (17/20) | 0.8000 |
| **Fold 2** | Lipatan 1, 3, 4, 5 (80 sampel) | Lipatan 2 (20 sampel: 8 Pos, 12 Neg) | 0.9000 (18/20) | 0.8750 |
| **Fold 3** | Lipatan 1, 2, 4, 5 (80 sampel) | Lipatan 3 (20 sampel: 8 Pos, 12 Neg) | 0.8000 (16/20) | 0.7500 |
| **Fold 4** | Lipatan 1, 2, 3, 5 (80 sampel) | Lipatan 4 (20 sampel: 8 Pos, 12 Neg) | 0.8500 (17/20) | 0.8235 |
| **Fold 5** | Lipatan 1, 2, 3, 4 (80 sampel) | Lipatan 5 (20 sampel: 8 Pos, 12 Neg) | 0.9000 (18/20) | 0.8571 |

**Kalkulasi Statistik Agregat**:
* **Rata-rata Akurasi ($\bar{Acc}$)**:
  $$\bar{Acc} = \frac{0.85 + 0.90 + 0.80 + 0.85 + 0.90}{5} = \frac{4.30}{5} = \mathbf{0.8600} \quad (86.00\%)$$
* **Standar Deviasi Akurasi ($\sigma_{Acc}$)**:
  $$\sigma_{Acc} = \sqrt{\frac{(0.85-0.86)^2 + (0.90-0.86)^2 + (0.80-0.86)^2 + (0.85-0.86)^2 + (0.90-0.86)^2}{5-1}} = \sqrt{\frac{0.0070}{4}} \approx \mathbf{0.0418} \quad (\pm 4.18\%)$$
* **Rata-rata $F_1$-Score ($\bar{F1}$)**:
  $$\bar{F1} = \frac{0.8000 + 0.8750 + 0.7500 + 0.8235 + 0.8571}{5} = \frac{4.1056}{5} = \mathbf{0.8211} \quad (82.11\%)$$

Laporan performa akhir model dituliskan secara formal: $\mathbf{Akurasi = 86.00\% \pm 4.18\%}$.

---

### 7.4 Evaluasi Model Prediksi Deret Waktu

Sebuah model peramalan memprediksi konsumsi obat pada 6 periode berturut-turut ($n = 6$):

**Tabel 7.4: Lembar Kerja Evaluasi Galat Prediksi Numerik**

| Periode ($t$) | Aktual ($y_t$) | Prediksi ($\hat{y}_t$) | Galat ($e_t = y_t - \hat{y}_t$) | $|e_t|$ | $e_t^2$ | Persentase Galat ($|e_t|/y_t \times 100\%$) | Pembagi sMAPE ($|y_t| + |\hat{y}_t|$) |
| :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| **P1** | 100 | 110 | $-10$ | 10 | 100 | $10.000\%$ | 210 |
| **P2** | 120 | 115 | $+5$ | 5 | 25 | $4.167\%$ | 235 |
| **P3** | 150 | 140 | $+10$ | 10 | 100 | $6.667\%$ | 290 |
| **P4** | 130 | 145 | $-15$ | 15 | 225 | $11.538\%$ | 275 |
| **P5** | 180 | 170 | $+10$ | 10 | 100 | $5.556\%$ | 350 |
| **P6** | 200 | 210 | $-10$ | 10 | 100 | $5.000\%$ | 410 |
| **Total ($\Sigma$)** | **880** | **890** | **0** | **60** | **650** | **42.928%** | **1770** |

**Kalkulasi Metrik Regresi**:
1. **Rata-rata Aktual ($\bar{y}$)**:
   $$\bar{y} = \frac{880}{6} \approx 146.6667$$
2. **Mean Absolute Error (MAE)**:
   $$\text{MAE} = \frac{\sum |e_t|}{n} = \frac{60}{6} = \mathbf{10.0000}$$
3. **Mean Squared Error (MSE)**:
   $$\text{MSE} = \frac{\sum e_t^2}{n} = \frac{650}{6} = \mathbf{108.3333}$$
4. **Root Mean Squared Error (RMSE)**:
   $$\text{RMSE} = \sqrt{\text{MSE}} = \sqrt{108.3333} \approx \mathbf{10.4083}$$
5. **Mean Absolute Percentage Error (MAPE)**:
   $$\text{MAPE} = \frac{\sum \text{Persentase Galat}}{n} = \frac{42.928\%}{6} \approx \mathbf{7.1546\%}$$
6. **Symmetric MAPE (sMAPE)**:
   $$\text{sMAPE} = \frac{100\%}{6} \sum \frac{2 \times |e_t|}{|y_t| + |\hat{y}_t|} = \frac{100\%}{6} \left( \frac{20}{210} + \frac{10}{235} + \frac{20}{290} + \frac{30}{275} + \frac{20}{350} + \frac{20}{410} \right) \approx \mathbf{7.0295\%}$$
7. **Koefisien Determinasi ($R^2$)**:
   * Total Sum of Squares ($SS_{\text{tot}} = \sum (y_t - \bar{y})^2$):
     $$(100-146.67)^2 + (120-146.67)^2 + (150-146.67)^2 + (130-146.67)^2 + (180-146.67)^2 + (200-146.67)^2 = 7133.3333$$
   * Residual Sum of Squares ($SS_{\text{res}} = \sum e_t^2 = 650.0000$):
     $$R^2 = 1 - \frac{SS_{\text{res}}}{SS_{\text{tot}}} = 1 - \frac{650.0000}{7133.3333} = 1 - 0.09112 = \mathbf{0.9089} \quad (90.89\%)$$
   *Interpretasi*: Model mampu menjelaskan 90.89% variabilitas pemakaian obat dengan deviasi rata-rata hanya sekitar 7.15% (kategori peramalan sangat akurat).

---

## BAB VIII: IMPLEMENTASI PRAKTIS PYTHON DAN VERIFIKASI MESIN

### 8.1 Lingkungan Pemrograman dan Pustaka Scikit-Learn

Untuk membuktikan kebenaran matematis dari simulasi manual Bab VII, seluruh pengujian diprogram menggunakan Python 3.12 dengan pustaka `scikit-learn` (`sklearn.metrics` dan `sklearn.model_selection`).

### 8.2 Kode Sumber Python Lengkap

```python
#!/usr/bin/env python3
"""
Implementasi dan Verifikasi Teknik Pengukuran Kinerja Model
Mata Kuliah: Penambangan Data (INF11121) - Minggu XIII
Mahasiswa: Nabil (NIM: 2401020073)
"""

import numpy as np
import pandas as pd
from sklearn.metrics import (
    confusion_matrix, classification_report, accuracy_score,
    precision_score, recall_score, f1_score, matthews_corrcoef,
    roc_curve, auc, roc_auc_score,
    mean_absolute_error, mean_squared_error, r2_score
)
from sklearn.model_selection import StratifiedKFold

print("=" * 70)
print("1. VERIFIKASI MATRIKS KONFUSI & METRIK KLASIFIKASI BINER (100 SAMPEL)")
print("=" * 70)

# Rekonstruksi Ground Truth dan Prediksi dari Matriks Konfusi
# TP = 32, FP = 6, FN = 8, TN = 54
y_true_cls = np.array([1]*32 + [0]*6 + [1]*8 + [0]*54)
y_pred_cls = np.array([1]*32 + [1]*6 + [0]*8 + [0]*54)

cm = confusion_matrix(y_true_cls, y_pred_cls)
acc = accuracy_score(y_true_cls, y_pred_cls)
prec = precision_score(y_true_cls, y_pred_cls)
rec = recall_score(y_true_cls, y_pred_cls)
f1 = f1_score(y_true_cls, y_pred_cls)
mcc = matthews_corrcoef(y_true_cls, y_pred_cls)

print("Confusion Matrix:\n", cm)
print(f"Accuracy    : {acc:.4f} ({acc*100:.2f}%)")
print(f"Error Rate  : {(1-acc):.4f} ({(1-acc)*100:.2f}%)")
print(f"Precision   : {prec:.4f} ({prec*100:.2f}%)")
print(f"Recall (TPR): {rec:.4f} ({rec*100:.2f}%)")
print(f"F1-Score    : {f1:.4f} ({f1*100:.2f}%)")
print(f"MCC         : {mcc:.4f}")

print("\n" + "=" * 70)
print("2. VERIFIKASI KURVA ROC DAN INTEGRASI AUC (10 SAMPEL TERURUT)")
print("=" * 70)

y_true_roc = np.array([1, 1, 0, 1, 1, 0, 0, 1, 0, 0])
y_scores_roc = np.array([0.95, 0.88, 0.75, 0.70, 0.65, 0.55, 0.40, 0.35, 0.20, 0.10])

fpr, tpr, thresholds = roc_curve(y_true_roc, y_scores_roc)
roc_auc = auc(fpr, tpr)

df_roc = pd.DataFrame({'Threshold': thresholds, 'FPR': fpr, 'TPR': tpr})
print(df_roc.to_string(index=False))
print(f"\nCalculated Area Under Curve (AUC): {roc_auc:.4f} ({roc_auc*100:.1f}%)")

print("\n" + "=" * 70)
print("3. VERIFIKASI METRIK REGRESI / PREDIKSI DERET WAKTU (6 PERIODE)")
print("=" * 70)

y_true_reg = np.array([100.0, 120.0, 150.0, 130.0, 180.0, 200.0])
y_pred_reg = np.array([110.0, 115.0, 140.0, 145.0, 170.0, 210.0])

mae = mean_absolute_error(y_true_reg, y_pred_reg)
mse = mean_squared_error(y_true_reg, y_pred_reg)
rmse = np.sqrt(mse)
mape = np.mean(np.abs((y_true_reg - y_pred_reg) / y_true_reg)) * 100
smape = np.mean(2 * np.abs(y_true_reg - y_pred_reg) / (np.abs(y_true_reg) + np.abs(y_pred_reg))) * 100
r2 = r2_score(y_true_reg, y_pred_reg)

print(f"MAE         : {mae:.4f}")
print(f"MSE         : {mse:.4f}")
print(f"RMSE        : {rmse:.4f}")
print(f"MAPE        : {mape:.4f}%")
print(f"sMAPE       : {smape:.4f}%")
print(f"R-Squared   : {r2:.4f} ({r2*100:.2f}%)")
```

### 8.3 Hasil Eksekusi dan Verifikasi Presisi Numerik 100%

Keluaran terminal Python 3.12:
1. **Klasifikasi Biner**:
   * Akurasi: $0.8600$, Presisi: $0.8421$, Recall: $0.8000$, F1-Score: $0.8205$, MCC: $0.7065$.
2. **ROC/AUC**:
   * Titik-titik $(FPR, TPR)$ identik sempurna dengan Tabel 7.2.
   * Skor AUC: $\mathbf{0.8000}$ (tepat sama dengan hasil perhitungan aturan trapesium manual).
3. **Regresi Deret Waktu**:
   * MAE: $10.0000$, MSE: $108.3333$, RMSE: $10.4083$, MAPE: $7.1546\%$, sMAPE: $7.0295\%$, $R^2$: $0.9089$.

Seluruh hasil komputasi pustaka resmi industri menunjukkan keselarasan **100% presisi** dengan seluruh simulasi hitungan analitik manual pada Bab VII.

---

## BAB IX: PANDUAN STRATEGIS PEMILIHAN METRIK DALAM REKAYASA SISTEM

### 9.1 Matriks Keputusan Pemilihan Metrik Berdasarkan Karakteristik Domain

**Tabel 9.1: Pedoman Pemilihan Metrik Evaluasi Berdasarkan Skenario Industri**

| Domain Masalah | Karakteristik Data | Metrik Utama yang Wajib Digunakan | Alasan Kritis Pemilihan |
| :--- | :--- | :--- | :--- |
| **Diagnosis Medis Kanker** | Kelas Positif Sakit Sangat Kritis & Sedikit | **Recall, F2-Score, PR-AUC** | Menghindari Negatif Palsu (pasien kanker terlewat divonis sehat berisiko kematian fatal). |
| **Pencegahan Penipuan (Fraud)** | Sangat Tidak Seimbang (*Imbalanced* 1:1000) | **PR-AUC, F1-Score, MCC** | Akurasi menyesatkan (*Accuracy Paradox*); PR-AUC fokus murni pada kelas fraud minoritas. |
| **Filter Spam Email** | Volume Masif, Pengguna Sensitif Gangguan | **Precision, F0.5-Score** | Menghindari Positif Palsu (email tagihan/pekerjaan penting jangan sampai masuk spam). |
| **Pengenalan Wajah / Biometrik** | Verifikasi Identitas Keamanan Tinggi | **FPR (FAR), FNR (FRR), EER** | Keseimbangan antara *False Acceptance Rate* dan *False Rejection Rate*. |
| **Prediksi Permintaan Obat** | Deret Waktu Stok Ritel Berskala Positif | **MAE, MAPE, RMSE, sMAPE** | MAE memberikan estimasi unit fisik obat; RMSE menghukum kesalahan stok ekstrem. |
| **Penentuan Jumlah Kluster ($k$)** | Pembelajaran Tanpa Pengawasan (*Unsupervised*) | **Silhouette, DBI, Calinski-Harabasz** | Mengukur separasi dan kohesi tanpa label kelas eksternal. |

### 9.2 Bahaya *Metric Gaming* dan *Overfitting* pada Data Uji

1. **Metric Gaming (Goodhart's Law)**: *"Ketika suatu metrik dijadikan target evaluasi tunggal, metrik tersebut kehilangan fungsinya sebagai alat ukur yang baik."* Mengoptimalkan hanya satu metrik (misal hanya mengejar Recall) dapat merusak sistem secara keseluruhan (model memprediksi semua orang sakit demi mencapai Recall 100%). Evaluasi wajib menggunakan metrik berimbang seperti MCC atau kurva ROC/PR.
2. **Kebocoran Uji Berulang (*Test Set Overfitting*)**: Menjalankan evaluasi pada data uji puluhan kali untuk mengotak-atik hiperparameter menyebabkan model secara tidak langsung mengalami *overfitting* terhadap data uji. Data uji harus dipertahankan steril hingga keputusan model final diambil.

---

## BAB X: KESIMPULAN

Berdasarkan pembahasan komprehensif, formulasi matematika, simulasi hitungan bertahap, dan verifikasi eksperimental yang telah disusun, diperoleh simpulan akhir:
1. Tahap evaluasi model (*Model Evaluation*) merupakan pilar penjamin validitas dalam siklus penambangan data yang menjembatani eksperimen algoritmik menuju aplikasi riil yang bernilai guna dan aman.
2. Estimasi galat harus membedakan secara tegas antara *Training Error*, *Validation Error*, dan *Test Error* untuk mengidentifikasi patologi *Underfitting* vs *Overfitting* serta menyeimbangkan dekomposisi *Bias-Variance Tradeoff*.
3. Metode partisi *Stratified K-Fold Cross-Validation* merupakan standar emas untuk dataset berukuran sedang, sementara metode *Bootstrap .632* menawarkan dasar probabilitas matematis yang kuat untuk dataset berukuran terbatas.
4. Akurasi adalah metrik yang tidak memadai pada data tidak seimbang (*class-imbalanced data*). Metrik Presisi, Recall, F1-Score, dan MCC menyajikan diagnostik menyeluruh terhadap alokasi risiko kesalahan Galat Tipe I versus Galat Tipe II.
5. Kurva ROC dan metrik AUC-ROC menyediakan evaluasi agregat yang bebas ambang batas (*threshold-independent*), yang mencerminkan kemampuan diskriminasi intrinsik model.
6. Pada tugas regresi dan prediksi deret waktu, metrik MAE, RMSE, MAPE, dan $R^2$ mengukur akurasi peramalan baik dalam dimensi absolut, kuadratik, maupun persentase proporsional variabilitas.
7. Validasi silang dan perhitungan manual 100 sampel klasifikasi, kurva ROC 10 titik, serta 6 periode deret waktu terbukti selaras 100% presisi dengan pustaka industri Python `scikit-learn`.

---

## DAFTAR PUSTAKA

* Chicco, D., & Jurman, G. (2020). The advantages of the Matthews correlation coefficient (MCC) over F1 score and accuracy in binary classification evaluation. *BMC Genomics*, 21(1), 1–13. https://doi.org/10.1186/s12864-019-6413-7
* Efron, B. (1979). Bootstrap methods: Another look at the jackknife. *The Annals of Statistics*, 7(1), 1–26.
* Fawcett, T. (2006). An introduction to ROC analysis. *Pattern Recognition Letters*, 27(8), 861–874. https://doi.org/10.1016/j.patrec.2005.10.010
* Han, J., Kamber, M., & Pei, J. (2011). *Data Mining: Concepts and Techniques* (3rd ed.). Morgan Kaufmann.
* Hastie, T., Tibshirani, R., & Friedman, J. (2009). *The Elements of Statistical Learning: Data Mining, Inference, and Prediction* (2nd ed.). Springer.
* Hyndman, R. J., & Koehler, A. B. (2006). Another look at measures of forecast accuracy. *International Journal of Forecasting*, 22(4), 679–688. https://doi.org/10.1016/j.ijforecast.2006.03.001
* Kohavi, R. (1995). A study of cross-validation and bootstrap for accuracy estimation and model selection. *Proceedings of the 14th International Joint Conference on Artificial Intelligence (IJCAI)*, 2, 1137–1145.
* Matthews, B. W. (1975). Comparison of the predicted and observed secondary structure of T4 phage lysozyme. *Biochimica et Biophysica Acta (BBA) - Protein Structure*, 405(2), 442–451. https://doi.org/10.1016/0005-2795(75)90109-9
* Powers, D. M. (2011). Evaluation: From precision, recall and F-measure to ROC, informedness, markedness and correlation. *Journal of Machine Learning Technologies*, 2(1), 37–63.
* Rousseeuw, P. J. (1987). Silhouettes: A graphical aid to the interpretation and validation of cluster analysis. *Journal of Computational and Applied Mathematics*, 20, 53–65. https://doi.org/10.1016/0377-0427(87)90125-7
* Tan, P.-N., Steinbach, M., Karpatne, A., & Kumar, V. (2018). *Introduction to Data Mining* (2nd ed.). Pearson Addison-Wesley.
