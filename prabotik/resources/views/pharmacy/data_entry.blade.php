@extends('layouts.pharmacy')

@section('title', 'Pusat Entri Data Faskes')
@section('page_title', 'Pencatatan Data & Evaluasi Model')

@section('content')
<div class="w-full space-y-6">
    
    <!-- Top Header Banner (Desain Modern Sesuai Nuansa Faskes) -->
    <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-matcha-950 rounded-3xl p-6 sm:p-8 text-white shadow-float border border-slate-800/80">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 rounded-full bg-matcha-500/20 text-matcha-300 text-xs font-bold border border-matcha-500/30 flex items-center gap-1.5">
                        <i class="fa-solid fa-hospital-user"></i>
                        <span>Instalasi Farmasi Puskesmas</span>
                    </span>
                    <span class="px-3 py-1 rounded-full bg-white/10 text-slate-200 text-xs font-bold border border-white/15">
                        4 Modul Operasional Faskes
                    </span>
                    <span class="px-3 py-1 rounded-full bg-matcha-400/20 text-matcha-200 text-xs font-bold border border-matcha-400/30">
                        Evaluasi Akurasi Berkelanjutan
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    Pencatatan Data Faskes &amp; Evaluasi Model
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 max-w-4xl leading-relaxed">
                    Satu tempat terpadu untuk petugas farmasi dan SIMPUS: entri stok LPLPO resmi, rekap kunjungan penyakit ICD-10, berita acara obat kadaluarsa (ED), dan registrasi obat baru. Setiap pengisian data langsung dievaluasi dengan model peramalan kebutuhan obat Puskesmas Teluk Sasah.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('pharmacy.print') }}" target="_blank" class="px-4 py-2.5 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition flex items-center gap-2 shadow-xs">
                    <i class="fa-solid fa-print text-matcha-300"></i>
                    <span>Cetak Format LPLPO &rarr;</span>
                </a>
                <a href="{{ route('pharmacy.procurement') }}" class="px-4 py-2.5 rounded-full bg-matcha-600 hover:bg-matcha-700 text-white text-xs font-bold shadow-md shadow-matcha-600/30 transition flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-check"></i>
                    <span>Lembar Usulan Pengadaan</span>
                </a>
            </div>
        </div>

        <!-- Bar Metrik Rekapitulasi Cepat -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-6 pt-6 border-t border-slate-800/80">
            <div class="bg-white/5 rounded-2xl p-3 border border-white/10">
                <p class="text-[10px] text-slate-400 uppercase font-semibold">Katalog Sediaan Obat</p>
                <p class="text-lg font-black text-white mt-0.5">{{ $medicines->count() }} <span class="text-xs font-normal text-slate-300">Item Aktif</span></p>
            </div>
            <div class="bg-white/5 rounded-2xl p-3 border border-white/10">
                <p class="text-[10px] text-slate-400 uppercase font-semibold">Kunjungan SIMPUS</p>
                <p class="text-lg font-black text-matcha-300 mt-0.5">{{ number_format($totalDiseaseRecords, 0, ',', '.') }} <span class="text-xs font-normal text-slate-300">Catatan Rekam</span></p>
            </div>
            <div class="bg-white/5 rounded-2xl p-3 border border-white/10">
                <p class="text-[10px] text-slate-400 uppercase font-semibold">Berita Acara ED</p>
                <p class="text-lg font-black text-amber-300 mt-0.5">{{ $totalExpiredItems }} <span class="text-xs font-normal text-slate-300">Batch Rusak/ED</span></p>
            </div>
            <div class="bg-white/5 rounded-2xl p-3 border border-white/10">
                <p class="text-[10px] text-slate-400 uppercase font-semibold">Total Nilai Rusak/ED</p>
                <p class="text-lg font-black text-rose-300 mt-0.5">Rp {{ number_format($totalExpiredLoss, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Pesan Notifikasi Sukses / Error -->
    @if(session('success'))
    <div class="bg-matcha-50 border border-matcha-300 rounded-2xl p-4 text-matcha-950 text-xs flex items-center space-x-3 shadow-xs">
        <div class="w-8 h-8 rounded-full bg-matcha-700 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-check text-sm"></i>
        </div>
        <div class="font-bold flex-1 leading-relaxed">
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if(isset($errors) && $errors->any())
    <div class="bg-rose-50 border border-rose-300 rounded-2xl p-4 text-rose-900 text-xs shadow-xs space-y-1">
        <div class="font-bold flex items-center space-x-2">
            <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
            <span>Terdapat data yang belum sesuai:</span>
        </div>
        <ul class="list-disc pl-5 space-y-0.5 text-[11px] text-rose-800">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Indikator Sub-Modul Aktif & Pengalih Cepat Mobile -->
    <div class="bg-white rounded-2xl px-5 py-3.5 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-2.5">
            <span class="text-xs font-semibold text-slate-500">Sub-Modul Aktif:</span>
            <span id="activeModuleBadge" class="px-3.5 py-1 rounded-full bg-matcha-100 text-matcha-900 text-xs font-black border border-matcha-200/70 flex items-center gap-2">
                <i id="activeModuleIcon" class="fa-solid fa-boxes-packing text-matcha-700"></i>
                <span id="activeModuleText">1. Stok &amp; Pemakaian LPLPO (Format Lembar Kerja Kemenkes)</span>
            </span>
        </div>
        
        <!-- Quick Switcher Chips (Tampil di layar untuk kemudahan beralih langsung) -->
        <div class="flex items-center gap-1.5 overflow-x-auto py-0.5 text-xs">
            <span class="hidden md:inline text-[11px] text-slate-400 mr-1">Beralih Cepat:</span>
            <button type="button" onclick="switchTab('lplpo')" id="chip-tab-lplpo" class="tab-chip px-3 py-1 rounded-full font-bold text-[11px] transition {{ $tab === 'lplpo' ? 'bg-matcha-700 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                1. LPLPO
            </button>
            <button type="button" onclick="switchTab('penyakit')" id="chip-tab-penyakit" class="tab-chip px-3 py-1 rounded-full font-bold text-[11px] transition {{ $tab === 'penyakit' ? 'bg-matcha-700 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                2. SIMPUS
            </button>
            <button type="button" onclick="switchTab('kadaluarsa')" id="chip-tab-kadaluarsa" class="tab-chip px-3 py-1 rounded-full font-bold text-[11px] transition {{ $tab === 'kadaluarsa' ? 'bg-matcha-700 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                3. Berita ED
            </button>
            <button type="button" onclick="switchTab('obat')" id="chip-tab-obat" class="tab-chip px-3 py-1 rounded-full font-bold text-[11px] transition {{ $tab === 'obat' ? 'bg-matcha-700 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                4. Registrasi Baru
            </button>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 1: STOK & PEMAKAIAN LPLPO (TATA LETAK VERTIKAL: FORM ATAS, TABEL BAWAH) -->
    <!-- ========================================================================= -->
    <div id="content-tab-lplpo" class="tab-content {{ $tab === 'lplpo' ? '' : 'hidden' }} space-y-6">
        
        <!-- 1. KARTU FORM PENCATATAN DI ATAS (FULL WIDTH) -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-float space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
                <div class="flex items-center space-x-3">
                    <div class="w-11 h-11 rounded-2xl bg-matcha-100 text-matcha-800 flex items-center justify-center text-xl shadow-xs">
                        <i class="fa-solid fa-file-invoice"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">Pencatatan Lembar LPLPO &amp; Evaluasi Model</h3>
                        <p class="text-xs text-slate-500">Kolom input disesuaikan langsung dengan format lembar Excel resmi Puskesmas Teluk Sasah</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <span class="px-3 py-1 rounded-full bg-matcha-50 text-matcha-800 text-[11px] font-bold border border-matcha-200/60">
                        <i class="fa-solid fa-circle-info mr-1 text-matcha-600"></i>Periode Pengamatan 2026-P5
                    </span>
                </div>
            </div>

            <form method="POST" action="{{ route('pharmacy.entry.lplpo') }}" class="space-y-5">
                @csrf

                <!-- Baris 1: Pilihan Sediaan Obat & Live Evaluasi -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                    <div class="lg:col-span-5">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                            <span>1. Pilih Sediaan Obat dari Lembar LPLPO:</span>
                            <span class="text-[10px] text-matcha-700 font-semibold">150 Sediaan Terdaftar</span>
                        </label>
                        <select name="medicine_id" id="medSelect" required class="w-full py-2.5 px-3 rounded-2xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-matcha-600/30 text-ink font-semibold">
                            <option value="">-- Ketik / Pilih Sediaan Obat --</option>
                            @foreach($medicines as $m)
                            <option value="{{ $m->id }}" 
                                data-satuan="{{ $m->satuan }}" 
                                data-stokawal="{{ (float) $m->stok_awal }}"
                                data-terima="{{ (float) $m->penerimaan }}"
                                data-sedia="{{ (float) $m->persediaan }}"
                                data-pakai="{{ (float) $m->pemakaian }}"
                                data-stokakhir="{{ (float) $m->sisa_stok }}"
                                data-pred="{{ (float) $m->prediksi_P5 }}"
                                data-ss="{{ (float) $m->safety_stock }}"
                                data-order="{{ (float) $m->rekomendasi_total }}"
                                data-akurasi="{{ (float) ($m->evaluasi_akurasi ?? 95) }}"
                                data-catatan="{{ $m->evaluasi_catatan ?? 'Sesuai Standar Konsumsi' }}">
                                {{ $m->nama_obat }} ({{ $m->satuan }})
                            </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1">Memilih obat akan otomatis mengisi data stok awal dan penerimaan periode sebelumnya.</p>
                    </div>

                    <!-- Panel Evaluasi Akurasi Model & Prediksi Sebelumnya -->
                    <div class="lg:col-span-7">
                        <div id="medInfoCard" class="hidden bg-matcha-50/80 rounded-2xl p-4 border border-matcha-200 text-xs space-y-2.5">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-bold text-matcha-900 flex items-center gap-1.5">
                                    <i class="fa-solid fa-chart-line text-matcha-700"></i>
                                    <span>Evaluasi Akurasi Model Terhadap Pemakaian Riil</span>
                                </span>
                                <span id="infoAkurasiPill" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-matcha-200 text-matcha-900">
                                    Akurasi: 95%
                                </span>
                            </div>
                            <div class="grid grid-cols-3 gap-2 text-[11px] pt-1 border-t border-matcha-200/60">
                                <div class="bg-white/80 p-2 rounded-xl">
                                    <span class="text-slate-500 block text-[10px]">Prediksi Pemakaian:</span>
                                    <span id="infoPred" class="font-bold text-matcha-900 font-mono text-xs">0</span>
                                </div>
                                <div class="bg-white/80 p-2 rounded-xl">
                                    <span class="text-slate-500 block text-[10px]">Cadangan Pengaman:</span>
                                    <span id="infoSs" class="font-bold text-matcha-900 font-mono text-xs">0</span>
                                </div>
                                <div class="bg-white/80 p-2 rounded-xl">
                                    <span class="text-slate-500 block text-[10px]">Usulan Pengadaan:</span>
                                    <span id="infoOrder" class="font-black text-matcha-950 font-mono text-xs">0</span>
                                </div>
                            </div>
                            <p id="infoEvaluasiTeks" class="text-[10px] text-matcha-800 italic leading-snug">
                                Model otomatis mengevaluasi deviasi antara pemakaian riil dengan peramalan Moving Average.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Baris 2: Kolom Data Mentah Sesuai Lembar Excel LPLPO (Grid 5 Kolom Lebar) -->
                <div class="p-4 bg-slate-50/70 rounded-2xl border border-slate-200/70 space-y-3">
                    <p class="text-[11px] font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-table-cells text-matcha-700"></i>
                        <span>Kolom Standar Format LPLPO Kemenkes RI (Dwibulanan)</span>
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3.5">
                        <!-- Kolom 2: Stok Awal -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                2. Stok Awal:
                            </label>
                            <input type="number" name="stok_awal" id="inputStokAwal" min="0" step="any" required placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-white border border-slate-300 focus:ring-2 focus:ring-matcha-600/30 font-mono font-medium">
                            <span class="text-[10px] text-slate-400">Sisa dari periode lalu</span>
                        </div>

                        <!-- Kolom 3: Penerimaan -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                3. Penerimaan:
                            </label>
                            <input type="number" name="penerimaan" id="inputPenerimaan" min="0" step="any" required placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-white border border-slate-300 focus:ring-2 focus:ring-matcha-600/30 font-mono font-medium">
                            <span class="text-[10px] text-slate-400">Drop dari Dinkes Bintan</span>
                        </div>

                        <!-- Kolom 4: Total Persediaan (Otomatis) -->
                        <div>
                            <label class="block text-xs font-bold text-matcha-900 mb-1 flex items-center justify-between">
                                <span>4. Persediaan:</span>
                                <span class="text-[9px] px-1.5 py-0.2 rounded bg-matcha-200 text-matcha-900 font-mono">Awal+Terima</span>
                            </label>
                            <input type="number" id="previewPersediaan" readonly placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-matcha-50 border border-matcha-300 text-matcha-900 font-mono font-bold cursor-not-allowed">
                            <span class="text-[10px] text-matcha-700">Total siap digunakan</span>
                        </div>

                        <!-- Kolom 5: Pemakaian -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                5. Pemakaian (Resep):
                            </label>
                            <input type="number" name="pemakaian" id="inputPemakaian" min="0" step="any" required placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-white border border-slate-300 focus:ring-2 focus:ring-matcha-600/30 font-mono font-bold text-slate-900">
                            <span class="text-[10px] text-slate-400 block">Jumlah resep keluar</span>
                            <div id="pemakaianAlert" class="hidden text-[10px] text-rose-600 font-bold mt-1">⚠️ Melebihi persediaan!</div>
                        </div>

                        <!-- Kolom 6: Sisa Stok Fisik Akhir -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center justify-between">
                                <span>6. Sisa Stok Fisik:</span>
                                <span class="text-[9px] px-1.5 py-0.2 rounded bg-slate-200 text-slate-700 font-mono">Persediaan-Pakai</span>
                            </label>
                            <input type="number" name="sisa_stok_fisik" id="inputStokAkhir" min="0" step="any" required placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-white border border-slate-300 focus:ring-2 focus:ring-matcha-600/30 font-mono font-bold text-matcha-900">
                            <span class="text-[10px] text-slate-400">Hasil hitung fisik gudang</span>
                        </div>
                    </div>
                </div>

                <!-- Baris 3: Catatan, Checkbox Pernah Kosong & Tombol Simpan -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">
                    <div class="lg:col-span-4">
                        <label class="flex items-center gap-2.5 p-3 rounded-2xl bg-slate-50 border border-slate-200 hover:bg-slate-100 cursor-pointer transition">
                            <input type="checkbox" name="pernah_kosong" id="checkPernahKosong" value="1" class="w-4 h-4 text-matcha-600 rounded focus:ring-matcha-500 border-slate-300">
                            <span class="text-xs text-slate-700 leading-tight">
                                <strong>Stok Sempat Kosong di Poli / Apotek</strong>
                                <span class="block text-[10px] text-slate-400">Pernah stok 0 saat pasien membutuhkan (data tersensor)</span>
                            </span>
                        </label>
                    </div>

                    <div class="lg:col-span-5">
                        <input type="text" name="keterangan_tambahan" placeholder="Catatan petugas (opsional): lonjakan resep batuk pilek, drop Dinkes berkurang, dll." class="w-full py-2.5 px-3 rounded-2xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-matcha-600/30 text-ink">
                    </div>

                    <div class="lg:col-span-3">
                        <button type="submit" class="w-full py-3 px-4 rounded-full bg-matcha-700 hover:bg-matcha-800 text-white font-extrabold text-xs shadow-md shadow-matcha-700/25 transition flex items-center justify-center space-x-2 cursor-pointer">
                            <i class="fa-solid fa-calculator text-sm"></i>
                            <span>Simpan &amp; Evaluasi Model</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 2. KARTU TABEL DATA DI BAWAH (FULL WIDTH DENGAN PENCARIAN CEPAT) -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-float overflow-hidden">
            <div class="px-6 py-4 bg-slate-50/80 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">Tabel Lengkap Lembar Kerja LPLPO &amp; Evaluasi Akurasi</h3>
                    <p class="text-xs text-slate-500">150 Sediaan Obat Terdaftar di Gudang Farmasi UPTD Puskesmas Teluk Sasah</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <!-- Search Input Langsung di Tabel -->
                    <div class="relative w-full sm:w-64">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-xs text-slate-400"></i>
                        <input type="text" id="lplpoSearchInput" placeholder="Cari nama obat / satuan..." class="w-full pl-8 pr-3 py-1.5 rounded-full text-xs bg-white border border-slate-300 focus:outline-none focus:ring-2 focus:ring-matcha-600/30 text-ink">
                    </div>
                    <a href="{{ route('pharmacy.print') }}" target="_blank" class="shrink-0 px-3.5 py-1.5 rounded-full bg-matcha-50 text-matcha-800 hover:bg-matcha-100 text-xs font-bold transition flex items-center gap-1.5 border border-matcha-200/60">
                        <i class="fa-solid fa-print text-[11px]"></i>
                        <span>Cetak LPLPO &rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Quick Filter Status Pills (Rekomendasi Auditor Gemini Argon) -->
            <div class="px-6 py-2.5 bg-slate-50/90 border-b border-slate-200 flex flex-wrap items-center justify-between gap-2">
                <div class="flex flex-wrap items-center gap-1.5 text-xs">
                    <span class="text-[11px] font-bold text-slate-500 mr-1">Filter Cepat:</span>
                    <button type="button" onclick="filterLplpoTable('semua')" id="btn-flt-semua" class="lplpo-flt-btn px-3 py-1 rounded-full text-[11px] font-bold bg-matcha-700 text-white shadow-xs">
                        Semua Obat ({{ $medicines->count() }})
                    </button>
                    <button type="button" onclick="filterLplpoTable('order')" id="btn-flt-order" class="lplpo-flt-btn px-3 py-1 rounded-full text-[11px] font-bold bg-white text-slate-700 hover:bg-matcha-50 border border-slate-200">
                        Perlu Usulan P5 ({{ $medicines->where('rekomendasi_total', '>', 0)->count() }})
                    </button>
                    <button type="button" onclick="filterLplpoTable('kritis')" id="btn-flt-kritis" class="lplpo-flt-btn px-3 py-1 rounded-full text-[11px] font-bold bg-white text-amber-800 hover:bg-amber-50 border border-amber-200">
                        Stok Menipis &le; SS ({{ $medicines->filter(fn($m) => $m->sisa_stok <= $m->safety_stock)->count() }})
                    </button>
                    <button type="button" onclick="filterLplpoTable('akurat')" id="btn-flt-akurat" class="lplpo-flt-btn px-3 py-1 rounded-full text-[11px] font-bold bg-white text-matcha-800 hover:bg-matcha-50 border border-matcha-200">
                        Akurasi &ge;85% ({{ $medicines->filter(fn($m) => ($m->evaluasi_akurasi ?? 90) >= 85)->count() }})
                    </button>
                </div>
                <div class="text-[11px] text-slate-500 font-medium">
                    Ditampilkan: <span id="filteredLplpoCount" class="font-bold text-ink">{{ $medicines->count() }}</span> dari {{ $medicines->count() }} Obat
                </div>
            </div>

            <div class="overflow-x-auto max-h-[700px] overflow-y-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead class="bg-slate-100/90 text-slate-700 font-bold sticky top-0 uppercase tracking-wider text-[10px] border-b border-slate-200 z-10 backdrop-blur-xs">
                        <tr>
                            <th class="py-3 px-4">Nama Obat &amp; Satuan Kemasan</th>
                            <th class="py-3 px-3 text-right">Stok Awal</th>
                            <th class="py-3 px-3 text-right">Penerimaan</th>
                            <th class="py-3 px-3 text-right">Persediaan</th>
                            <th class="py-3 px-3 text-right">Pemakaian</th>
                            <th class="py-3 px-3 text-right">Sisa Stok Fisik</th>
                            <th class="py-3 px-3 text-center">Akurasi Model</th>
                            <th class="py-3 px-4 text-right">Usulan Permintaan (P5)</th>
                        </tr>
                    </thead>
                    <tbody id="lplpoTableBody" class="divide-y divide-slate-100 font-medium text-slate-700">
                        @foreach($medicines as $m)
                        <tr class="lplpo-row hover:bg-matcha-50/40 transition" 
                            data-nama="{{ strtolower($m->nama_obat . ' ' . $m->satuan) }}" 
                            data-order="{{ (float) $m->rekomendasi_total > 0 ? '1' : '0' }}" 
                            data-kritis="{{ $m->sisa_stok <= $m->safety_stock ? '1' : '0' }}" 
                            data-akurat="{{ ($m->evaluasi_akurasi ?? 90) >= 85 ? '1' : '0' }}">
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900 leading-tight">{{ $m->nama_obat }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $m->satuan }} &bull; {{ $m->kategori ?? 'Umum' }}</div>
                            </td>
                            <td class="py-3 px-3 text-right font-mono text-slate-600">
                                {{ number_format($m->stok_awal ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3 text-right font-mono text-slate-600">
                                {{ number_format($m->penerimaan ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3 text-right font-mono text-slate-800 font-semibold">
                                {{ number_format($m->persediaan ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-bold text-ink">
                                {{ number_format($m->pemakaian ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-bold {{ $m->sisa_stok <= $m->safety_stock ? 'text-rose-600' : 'text-slate-800' }}">
                                {{ number_format($m->sisa_stok, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3 text-center">
                                @php
                                    $acc = (float) ($m->evaluasi_akurasi ?? 92);
                                @endphp
                                @if($acc >= 85)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-matcha-100 text-matcha-900">
                                        <i class="fa-solid fa-check text-[9px]"></i>{{ number_format($acc, 1) }}%
                                    </span>
                                @elseif($acc >= 70)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900">
                                        {{ number_format($acc, 1) }}%
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-900">
                                        {{ number_format($acc, 1) }}%
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right font-mono font-black text-matcha-800 text-sm">
                                {{ number_format($m->rekomendasi_total, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 2: MORBIDITAS SIMPUS (TATA LETAK VERTIKAL: FORM ATAS, TABEL BAWAH)    -->
    <!-- ========================================================================= -->
    <div id="content-tab-penyakit" class="tab-content {{ $tab === 'penyakit' ? '' : 'hidden' }} space-y-6">
        
        <!-- 1. KARTU FORM ENTRI KASUS MORBIDITAS DI ATAS (FULL WIDTH) -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-float space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
                <div class="flex items-center space-x-3">
                    <div class="w-11 h-11 rounded-2xl bg-matcha-100 text-matcha-800 flex items-center justify-center text-xl shadow-xs">
                        <i class="fa-solid fa-file-medical"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">Entri Kasus Kunjungan Penyakit SIMPUS (ICD-10)</h3>
                        <p class="text-xs text-slate-500">Pencatatan data morbiditas bulanan untuk analisis hubungan penyakit dan kebutuhan obat</p>
                    </div>
                </div>

                <!-- Pilihan Cepat 10 Penyakit Terbanyak -->
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-700 whitespace-nowrap"><i class="fa-solid fa-bolt text-amber-500 mr-1"></i>Pilih Cepat:</span>
                    <select id="quickDiseaseSelect" class="py-1.5 px-3 rounded-full text-xs bg-slate-50 border border-slate-300 focus:outline-none focus:ring-2 focus:ring-matcha-600/30 font-medium">
                        <option value="">-- 10 Penyakit Terbanyak Puskesmas --</option>
                        @foreach($commonDiseases as $cd)
                        <option value="{{ $cd['kode'] }}" data-nama="{{ $cd['nama'] }}" data-klaster="{{ $cd['klaster'] }}">
                            [{{ $cd['kode'] }}] {{ $cd['nama'] }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <form method="POST" action="{{ route('pharmacy.entry.disease') }}" class="space-y-4">
                @csrf

                <!-- Baris 1: Tahun, Bulan, Kode ICD-10, Nama Penyakit & Klaster -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tahun Rekap:</label>
                        <select name="tahun" required class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:ring-2 focus:ring-matcha-600/30">
                            <option value="2026" selected>2026</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Bulan Pelaporan:</label>
                        <select name="bulan_angka" required class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:ring-2 focus:ring-matcha-600/30">
                            <option value="1">Januari (P1)</option>
                            <option value="2">Februari (P1)</option>
                            <option value="3">Maret (P2)</option>
                            <option value="4">April (P2)</option>
                            <option value="5">Mei (P3)</option>
                            <option value="6">Juni (P3)</option>
                            <option value="7">Juli (P4)</option>
                            <option value="8">Agustus (P4)</option>
                            <option value="9">September (P5)</option>
                            <option value="10" selected>Oktober (P5)</option>
                            <option value="11">November (P6)</option>
                            <option value="12">Desember (P6)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kode ICD-10:</label>
                        <input type="text" name="kode_icd" id="inputKodeIcd" required placeholder="Mis: J00" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:ring-2 focus:ring-matcha-600/30 font-mono uppercase font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Diagnosis:</label>
                        <input type="text" name="nama_penyakit" id="inputNamaPenyakit" required placeholder="Mis: ISPA (Nasofaringitis Akut)" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:ring-2 focus:ring-matcha-600/30">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Klaster Penyakit:</label>
                        <input type="text" name="klaster_icd" id="inputKlasterIcd" placeholder="Penyakit Pernapasan" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:ring-2 focus:ring-matcha-600/30">
                    </div>
                </div>

                <!-- Baris 2: Pasien L, Pasien P, Total Pasien Otomatis & Tombol Simpan -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 pt-2 items-center">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Pasien Laki-laki (L):</label>
                        <input type="number" name="pasien_laki" id="pasienLaki" min="0" step="1" required placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:ring-2 focus:ring-matcha-600/30 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Pasien Perempuan (P):</label>
                        <input type="number" name="pasien_perempuan" id="pasienPerempuan" min="0" step="1" required placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:ring-2 focus:ring-matcha-600/30 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-matcha-900 mb-1">Total Kunjungan Pasien:</label>
                        <div class="py-2 px-3 bg-matcha-50 rounded-xl border border-matcha-200 text-center">
                            <span id="previewTotalPasien" class="font-black text-matcha-800 text-sm font-mono">0 Pasien</span>
                        </div>
                    </div>
                    <div class="pt-5 sm:pt-0">
                        <button type="submit" class="w-full py-3 px-4 rounded-full bg-matcha-700 hover:bg-matcha-800 text-white font-extrabold text-xs shadow-md shadow-matcha-700/25 transition flex items-center justify-center space-x-2 cursor-pointer">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <span>Simpan Rekam SIMPUS</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 2. KARTU TABEL DATA DI BAWAH (FULL WIDTH) -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-float overflow-hidden">
            <div class="px-6 py-4 bg-slate-50/80 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">Rekam Morbiditas Terkini (SIMPUS Puskesmas)</h3>
                    <p class="text-xs text-slate-500">15 Entri Kunjungan Pasien Terkini yang Terdaftar dalam Basis Data</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead class="bg-slate-100 text-slate-700 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Periode</th>
                            <th class="py-3 px-3">Kode ICD-10</th>
                            <th class="py-3 px-4">Nama Diagnosis / Penyakit</th>
                            <th class="py-3 px-3 text-right">Laki-laki</th>
                            <th class="py-3 px-3 text-right">Perempuan</th>
                            <th class="py-3 px-4 text-right">Total Pasien</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($recentDiseases as $rd)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-2.5 px-4 font-mono text-[11px]">
                                <span class="font-bold text-slate-800">{{ $rd->bulan }} {{ $rd->tahun }}</span>
                            </td>
                            <td class="py-2.5 px-3 font-mono font-bold text-matcha-800">
                                <span class="px-2 py-0.5 rounded bg-matcha-50 border border-matcha-200">{{ $rd->kode_icd }}</span>
                            </td>
                            <td class="py-2.5 px-4">
                                <span class="font-bold text-slate-900">{{ $rd->nama_penyakit }}</span>
                                <div class="text-[10px] text-slate-400">{{ $rd->klaster_icd }}</div>
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono text-slate-600">{{ number_format($rd->pasien_laki, 0, ',', '.') }}</td>
                            <td class="py-2.5 px-3 text-right font-mono text-slate-600">{{ number_format($rd->pasien_perempuan, 0, ',', '.') }}</td>
                            <td class="py-2.5 px-4 text-right font-mono font-black text-matcha-900 text-sm">
                                {{ number_format($rd->total_pasien, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400">Belum ada catatan morbiditas SIMPUS.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 3: BERITA ACARA ED (TATA LETAK VERTIKAL: FORM ATAS, TABEL BAWAH)     -->
    <!-- ========================================================================= -->
    <div id="content-tab-kadaluarsa" class="tab-content {{ $tab === 'kadaluarsa' ? '' : 'hidden' }} space-y-6">
        
        <!-- 1. KARTU FORM BERITA ACARA ED DI ATAS (FULL WIDTH) -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-float space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
                <div class="flex items-center space-x-3">
                    <div class="w-11 h-11 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl shadow-xs">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">Berita Acara Obat Rusak / Kadaluarsa (ED)</h3>
                        <p class="text-xs text-slate-500">Pencatatan resmi obat rusak untuk audit logistik farmasi dan evaluasi batas aman stok</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('pharmacy.entry.expired') }}" class="space-y-4">
                @csrf

                <!-- Baris 1: Tahun ED, Satuan Kemasan, Nama Obat, Sumber Anggaran -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tahun Kadaluarsa:</label>
                        <select name="tahun" required class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:ring-2 focus:ring-amber-500">
                            <option value="2026" selected>2026</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Satuan Kemasan:</label>
                        <input type="text" name="satuan" required placeholder="Tablet / Botol / Vial" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:ring-2 focus:ring-amber-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Obat Kadaluarsa:</label>
                        <input type="text" name="nama_obat" list="medicineCatalogList" required placeholder="Ketik atau pilih dari katalog..." class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:ring-2 focus:ring-amber-500 font-medium">
                        <datalist id="medicineCatalogList">
                            @foreach($medicines as $m)
                            <option value="{{ $m->nama_obat }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Sumber Anggaran:</label>
                        <select name="sumber_anggaran" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:ring-2 focus:ring-amber-500">
                            <option value="APBD / DAK Farmasi Bintan" selected>APBD / DAK Farmasi Kab. Bintan</option>
                            <option value="Kapitasi JKN / BPJS Kesehatan">Kapitasi JKN / BPJS Kesehatan</option>
                            <option value="Drop Dinkes Provinsi Kepri">Drop Dinkes Provinsi Kepri</option>
                        </select>
                    </div>
                </div>

                <!-- Baris 2: Jumlah ED, Harga Satuan, Total Kerugian Otomatis & Tombol Simpan -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 pt-2 items-center">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Fisik ED:</label>
                        <input type="number" name="jumlah_kadaluarsa" id="jumlahEd" min="1" step="1" required placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:ring-2 focus:ring-amber-500 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Harga Satuan (Rp):</label>
                        <input type="number" name="harga_satuan" id="hargaSatuanEd" min="0" step="1" required placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:ring-2 focus:ring-amber-500 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-amber-900 mb-1">Total Nilai Kerugian:</label>
                        <div class="py-2 px-3 bg-amber-50 rounded-xl border border-amber-200 text-center">
                            <span id="previewKerugian" class="font-black text-rose-600 text-sm font-mono">Rp 0</span>
                        </div>
                    </div>
                    <div class="pt-5 sm:pt-0">
                        <button type="submit" class="w-full py-3 px-4 rounded-full bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-xs shadow-md shadow-amber-600/20 transition flex items-center justify-center space-x-2 cursor-pointer">
                            <i class="fa-solid fa-file-signature"></i>
                            <span>Simpan Berita Acara ED</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 2. KARTU TABEL DATA DI BAWAH (FULL WIDTH) -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-float overflow-hidden">
            <div class="px-6 py-4 bg-slate-50/80 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">Riwayat Berita Acara Pemusnahan / ED</h3>
                    <p class="text-xs text-slate-500">15 Catatan Fisik Terkini untuk Audit Kerugian &amp; Evaluasi Stok</p>
                </div>
                <a href="{{ route('pharmacy.audit') }}" class="text-xs font-bold text-amber-700 hover:text-amber-800">
                    Buka Halaman Audit Stok Lengkap &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead class="bg-slate-100 text-slate-700 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Tahun</th>
                            <th class="py-3 px-4">Nama Sediaan Obat</th>
                            <th class="py-3 px-3 text-right">Jumlah ED</th>
                            <th class="py-3 px-3 text-right">Harga Satuan</th>
                            <th class="py-3 px-4 text-right">Total Kerugian</th>
                            <th class="py-3 px-4">Sumber Anggaran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($recentExpired as $re)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-2.5 px-4 font-mono font-bold text-slate-800">{{ $re->tahun }}</td>
                            <td class="py-2.5 px-4">
                                <div class="font-bold text-slate-900">{{ $re->nama_obat }}</div>
                                <div class="text-[10px] text-slate-400">{{ $re->satuan }}</div>
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-800">
                                {{ number_format($re->jumlah_kadaluarsa, 0, ',', '.') }}
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono text-slate-500">
                                Rp {{ number_format($re->harga_satuan, 0, ',', '.') }}
                            </td>
                            <td class="py-2.5 px-4 text-right font-mono font-black text-rose-600 text-sm">
                                Rp {{ number_format($re->sub_total_kerugian, 0, ',', '.') }}
                            </td>
                            <td class="py-2.5 px-4 text-xs text-slate-600">
                                {{ $re->sumber_anggaran }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400">Belum ada catatan berita acara kadaluarsa.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 4: REGISTRASI OBAT BARU (TATA LETAK VERTIKAL: FORM ATAS, TABEL BAWAH) -->
    <!-- ========================================================================= -->
    <div id="content-tab-obat" class="tab-content {{ $tab === 'obat' ? '' : 'hidden' }} space-y-6">
        
        <!-- 1. KARTU FORM REGISTRASI DI ATAS (FULL WIDTH) -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-float space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
                <div class="flex items-center space-x-3">
                    <div class="w-11 h-11 rounded-2xl bg-matcha-100 text-matcha-800 flex items-center justify-center text-xl shadow-xs">
                        <i class="fa-solid fa-plus-circle"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">Registrasi Sediaan Obat Baru ke Katalog Puskesmas</h3>
                        <p class="text-xs text-slate-500">Menambahkan item obat baru ke daftar inventaris dan menginisialisasi parameter peramalan</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('pharmacy.entry.medicine') }}" class="space-y-4">
                @csrf

                <!-- Baris 1: Nama Lengkap, Satuan Terkecil, Kategori Prioritas -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Sediaan Obat &amp; Dosis:</label>
                        <input type="text" name="nama_obat" required placeholder="Contoh: Cetirizine tablet 10 mg" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:ring-2 focus:ring-matcha-600/30 font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Satuan Kemasan Terkecil:</label>
                        <select name="satuan" required class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:ring-2 focus:ring-matcha-600/30">
                            <option value="Tablet" selected>Tablet</option>
                            <option value="Kapsul">Kapsul</option>
                            <option value="Botol">Botol (Sirup)</option>
                            <option value="Tube">Tube (Salep)</option>
                            <option value="Ampul">Ampul (Injeksi)</option>
                            <option value="Vial">Vial (Injeksi)</option>
                            <option value="Sachet">Sachet</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Klasifikasi Prioritas Pemakaian:</label>
                        <select name="prioritas_kategori" required class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:ring-2 focus:ring-matcha-600/30">
                            <option value="Prioritas Tinggi (Fast Moving)" selected>Prioritas Tinggi (Sering Diresepkan)</option>
                            <option value="Prioritas Sedang">Prioritas Sedang</option>
                            <option value="Prioritas Rendah (Slow Moving)">Prioritas Rendah (Jarang Keluar)</option>
                        </select>
                    </div>
                </div>

                <!-- Baris 2: Stok Awal, Estimasi Konsumsi, Inisialisasi Otomatis & Tombol Simpan -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 pt-2 items-center">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Stok Awal Fisik:</label>
                        <input type="number" name="stok_awal" id="regStokAwal" min="0" step="1" required placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:ring-2 focus:ring-matcha-600/30 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Estimasi Kebutuhan Dwibulanan:</label>
                        <input type="number" name="estimasi_kebutuhan" id="regEstimasi" min="0" step="1" required placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:ring-2 focus:ring-matcha-600/30 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-matcha-900 mb-1">Inisialisasi Otomatis:</label>
                        <div class="py-1.5 px-3 bg-matcha-50 rounded-xl border border-matcha-200 text-center flex items-center justify-around text-xs">
                            <div>
                                <span class="text-[9px] text-slate-500 block">Cadangan (25%):</span>
                                <span id="previewRegSs" class="font-bold text-matcha-800 font-mono">0</span>
                            </div>
                            <div class="border-l border-matcha-200 pl-2">
                                <span class="text-[9px] text-slate-500 block">Order Awal:</span>
                                <span id="previewRegOrder" class="font-black text-matcha-950 font-mono">0</span>
                            </div>
                        </div>
                    </div>
                    <div class="pt-5 sm:pt-0">
                        <button type="submit" class="w-full py-3 px-4 rounded-full bg-matcha-700 hover:bg-matcha-800 text-white font-extrabold text-xs shadow-md shadow-matcha-700/25 transition flex items-center justify-center space-x-2 cursor-pointer">
                            <i class="fa-solid fa-folder-plus"></i>
                            <span>Daftarkan ke Katalog Aktif</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 2. KARTU INFORMASI & ALUR OPERASIONAL DI BAWAH (FULL WIDTH) -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-float space-y-4">
            <div class="flex items-center space-x-3 pb-3 border-b border-slate-100">
                <div class="w-10 h-10 rounded-2xl bg-matcha-100 text-matcha-800 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">Alur Kerja Pengelolaan Sediaan Baru di Puskesmas Teluk Sasah</h3>
                    <p class="text-xs text-slate-500">Standar operasional prosedur terintegrasi dengan lembar peramalan kebutuhan obat</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                <div class="flex items-start space-x-3 p-4 bg-slate-50/80 rounded-2xl border border-slate-200/70">
                    <div class="w-7 h-7 rounded-full bg-matcha-700 text-white font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">1</div>
                    <div>
                        <span class="font-bold text-slate-900 text-xs">Inisialisasi Model Peramalan:</span>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Sediaan baru langsung dihubungkan dengan model Moving Average sebagai acuan awal kebutuhan stok.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3 p-4 bg-slate-50/80 rounded-2xl border border-slate-200/70">
                    <div class="w-7 h-7 rounded-full bg-matcha-700 text-white font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">2</div>
                    <div>
                        <span class="font-bold text-slate-900 text-xs">Cadangan Pengaman 25%:</span>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Sistem secara baku menetapkan cadangan pengaman (safety stock) 25% untuk mengantisipasi lonjakan resep.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3 p-4 bg-slate-50/80 rounded-2xl border border-slate-200/70">
                    <div class="w-7 h-7 rounded-full bg-matcha-700 text-white font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">3</div>
                    <div>
                        <span class="font-bold text-slate-900 text-xs">Tampil di Lembar Usulan:</span>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Setelah didaftarkan, sediaan otomatis masuk ke lembar usulan permintaan obat P5 dan lembar cetak LPLPO resmi.</p>
                    </div>
                </div>
            </div>

            <div class="pt-3 flex items-center justify-between border-t border-slate-100 text-xs">
                <span class="text-slate-500">Total Sediaan Aktif Terdaftar Saat Ini:</span>
                <span class="font-extrabold text-matcha-800 font-mono text-sm">{{ $medicines->count() }} Sediaan Obat</span>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Tab Switching Functionality & Sinkronisasi dengan Panel Kiri
    const moduleMeta = {
        'lplpo': {
            icon: 'fa-solid fa-boxes-packing text-matcha-700',
            text: '1. Stok & Pemakaian LPLPO (Format Lembar Kerja Kemenkes)'
        },
        'penyakit': {
            icon: 'fa-solid fa-stethoscope text-matcha-700',
            text: '2. Kunjungan SIMPUS (Kasus Diagnosis ICD-10 Bulanan)'
        },
        'kadaluarsa': {
            icon: 'fa-solid fa-calendar-xmark text-matcha-700',
            text: '3. Berita Acara Kerusakan / Kadaluarsa Obat (ED)'
        },
        'obat': {
            icon: 'fa-solid fa-pills text-matcha-700',
            text: '4. Registrasi Sediaan Obat Baru ke Katalog Aktif'
        }
    };

    function switchTab(tabName) {
        // 1. Sembunyikan semua tab konten
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        // 2. Tampilkan tab konten target
        const targetContent = document.getElementById('content-tab-' + tabName);
        if (targetContent) {
            targetContent.classList.remove('hidden');
        }

        // 3. Sinkronisasi Chip di Halaman
        document.querySelectorAll('.tab-chip').forEach(chip => {
            chip.className = 'tab-chip px-3 py-1 rounded-full font-bold text-[11px] transition bg-slate-100 text-slate-600 hover:bg-slate-200';
        });
        const activeChip = document.getElementById('chip-tab-' + tabName);
        if (activeChip) {
            activeChip.className = 'tab-chip px-3 py-1 rounded-full font-bold text-[11px] transition bg-matcha-700 text-white shadow-xs';
        }

        // 4. Sinkronisasi Item di Sidebar Kiri (Sub-Modul Dock)
        const sidebarTabMap = {
            'lplpo': 'sb-tab-lplpo',
            'penyakit': 'sb-tab-penyakit',
            'kadaluarsa': 'sb-tab-kadaluarsa',
            'obat': 'sb-tab-obat'
        };
        Object.keys(sidebarTabMap).forEach(key => {
            const el = document.getElementById(sidebarTabMap[key]);
            if (el) {
                if (key === tabName) {
                    el.className = 'sidebar-nav-item sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all duration-150 bg-matcha-700 text-white shadow-md shadow-matcha-700/25 active-dock-item';
                    el.setAttribute('aria-current', 'page');
                    const icon = el.querySelector('i');
                    if (icon) {
                        icon.classList.remove('text-matcha-600');
                        icon.classList.add('text-white');
                    }
                    const badge = el.querySelector('.sidebar-badge');
                    if (badge) {
                        badge.classList.remove('bg-matcha-100', 'text-matcha-800');
                        badge.classList.add('bg-white/25', 'text-white');
                    }
                } else {
                    el.className = 'sidebar-nav-item sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all duration-150 text-slate-600 hover:bg-matcha-50 hover:text-matcha-900';
                    el.removeAttribute('aria-current');
                    const icon = el.querySelector('i');
                    if (icon) {
                        icon.classList.remove('text-white');
                        icon.classList.add('text-matcha-600');
                    }
                    const badge = el.querySelector('.sidebar-badge');
                    if (badge) {
                        badge.classList.remove('bg-white/25', 'text-white');
                        badge.classList.add('bg-matcha-100', 'text-matcha-800');
                    }
                }
            }
        });

        // 5. Update Badge Teks dan Ikon Aktif di Halaman
        if (moduleMeta[tabName]) {
            const iconEl = document.getElementById('activeModuleIcon');
            const textEl = document.getElementById('activeModuleText');
            if (iconEl) iconEl.className = moduleMeta[tabName].icon;
            if (textEl) textEl.textContent = moduleMeta[tabName].text;
        }

        // 6. Update URL parameter tanpa reload
        if (window.history && window.history.pushState) {
            const url = new URL(window.location);
            url.searchParams.set('tab', tabName);
            window.history.pushState({}, '', url);
        }
    }
    window.switchTab = switchTab;

    // Dynamic Medicine Selector & LPLPO Calculation Preview
    const medSelect = document.getElementById('medSelect');
    const medInfoCard = document.getElementById('medInfoCard');
    const infoPred = document.getElementById('infoPred');
    const infoSs = document.getElementById('infoSs');
    const infoOrder = document.getElementById('infoOrder');
    const infoAkurasiPill = document.getElementById('infoAkurasiPill');
    const infoEvaluasiTeks = document.getElementById('infoEvaluasiTeks');

    const inputStokAwal = document.getElementById('inputStokAwal');
    const inputPenerimaan = document.getElementById('inputPenerimaan');
    const previewPersediaan = document.getElementById('previewPersediaan');
    const inputPemakaian = document.getElementById('inputPemakaian');
    const inputStokAkhir = document.getElementById('inputStokAkhir');
    const checkPernahKosong = document.getElementById('checkPernahKosong');

    let currentBaselinePred = 0;

    function recalculateLplpo() {
        const awal = parseFloat(inputStokAwal.value) || 0;
        const terima = parseFloat(inputPenerimaan.value) || 0;
        const persediaan = awal + terima;
        if (previewPersediaan) previewPersediaan.value = persediaan;

        const pakai = parseFloat(inputPemakaian.value) || 0;
        const stokAkhir = Math.max(0, persediaan - pakai);
        if (inputStokAkhir && document.activeElement !== inputStokAkhir) {
            inputStokAkhir.value = stokAkhir;
        }

        // Edge-Case Warning: Pemakaian melebihi persediaan
        const alertEl = document.getElementById('pemakaianAlert');
        if (pakai > persediaan) {
            inputPemakaian.classList.add('border-rose-500', 'bg-rose-50', 'text-rose-900');
            if (alertEl) alertEl.classList.remove('hidden');
        } else {
            inputPemakaian.classList.remove('border-rose-500', 'bg-rose-50', 'text-rose-900');
            if (alertEl) alertEl.classList.add('hidden');
        }

        // Evaluasi akurasi terhadap baseline prediksi model sebelumnya
        if (infoAkurasiPill && (currentBaselinePred > 0 || pakai > 0)) {
            let basis = pakai;
            let status = 'Sangat Sesuai';
            let pillClass = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-matcha-200 text-matcha-900';

            if (checkPernahKosong && checkPernahKosong.checked) {
                basis = Math.round(pakai * 1.15); // koreksi data tersensor
                status = 'Terbatas Stok Fisik (Stockout) - Model Disesuaikan';
                pillClass = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900';
            }

            const denominator = Math.max(basis, currentBaselinePred, 1);
            const diff = Math.abs(basis - currentBaselinePred);
            const acc = Math.max(0, Math.round((1 - (diff / denominator)) * 1000) / 10);

            if (!checkPernahKosong || !checkPernahKosong.checked) {
                if (acc >= 85) {
                    status = 'Sangat Sesuai (Akurat)';
                    pillClass = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-matcha-200 text-matcha-900';
                } else if (acc >= 70) {
                    status = 'Cukup Sesuai';
                    pillClass = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900';
                } else {
                    status = 'Lonjakan Resep Pasien';
                    pillClass = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-900';
                }
            }

            infoAkurasiPill.className = pillClass;
            infoAkurasiPill.textContent = 'Akurasi: ' + acc + '% (' + status + ')';

            // Hitungan baru untuk periode berikutnya
            const newPred = Math.round((currentBaselinePred * 0.4) + (basis * 0.6));
            const newSs = Math.round(newPred * 0.25);
            const currentFisik = parseFloat(inputStokAkhir.value) || stokAkhir;
            const newOrder = Math.max(0, (newPred + newSs) - currentFisik);

            if (infoPred) infoPred.textContent = newPred.toLocaleString('id-ID');
            if (infoSs) infoSs.textContent = newSs.toLocaleString('id-ID');
            if (infoOrder) infoOrder.textContent = newOrder.toLocaleString('id-ID');

            if (infoEvaluasiTeks) {
                infoEvaluasiTeks.textContent = 'Pemakaian nyata (' + pakai.toLocaleString('id-ID') + ') dibandingkan dengan perkiraan awal (' + currentBaselinePred.toLocaleString('id-ID') + '). Selisih: ' + (pakai - currentBaselinePred).toLocaleString('id-ID') + ' unit.';
            }
        }
    }

    if (medSelect) {
        medSelect.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            if (this.value) {
                if (medInfoCard) medInfoCard.classList.remove('hidden');
                currentBaselinePred = parseFloat(opt.dataset.pred) || 0;

                if (inputStokAwal) inputStokAwal.value = opt.dataset.stokawal || 0;
                if (inputPenerimaan) inputPenerimaan.value = opt.dataset.terima || 0;
                if (previewPersediaan) previewPersediaan.value = opt.dataset.sedia || 0;
                if (inputPemakaian) inputPemakaian.value = opt.dataset.pakai || 0;
                if (inputStokAkhir) inputStokAkhir.value = opt.dataset.stokakhir || 0;

                if (infoPred) infoPred.textContent = Number(opt.dataset.pred).toLocaleString('id-ID');
                if (infoSs) infoSs.textContent = Number(opt.dataset.ss).toLocaleString('id-ID');
                if (infoOrder) infoOrder.textContent = Number(opt.dataset.order).toLocaleString('id-ID');
                
                const initialAcc = parseFloat(opt.dataset.akurasi) || 95;
                if (infoAkurasiPill) infoAkurasiPill.textContent = 'Akurasi: ' + initialAcc.toFixed(1) + '%';
                if (infoEvaluasiTeks) infoEvaluasiTeks.textContent = opt.dataset.catatan || 'Sesuai dengan rata-rata konsumsi historis.';

                recalculateLplpo();
            } else {
                if (medInfoCard) medInfoCard.classList.add('hidden');
            }
        });
    }

    if (inputStokAwal && inputPenerimaan && inputPemakaian) {
        inputStokAwal.addEventListener('input', recalculateLplpo);
        inputPenerimaan.addEventListener('input', recalculateLplpo);
        inputPemakaian.addEventListener('input', recalculateLplpo);
        if (inputStokAkhir) inputStokAkhir.addEventListener('input', recalculateLplpo);
        if (checkPernahKosong) {
            checkPernahKosong.addEventListener('change', recalculateLplpo);
        }
    }

    // Live Search Filter & Quick Status Filter untuk 150 Baris Tabel LPLPO
    const lplpoSearchInput = document.getElementById('lplpoSearchInput');
    let currentLplpoFilter = 'semua';

    function filterLplpoTable(type) {
        currentLplpoFilter = type;

        document.querySelectorAll('.lplpo-flt-btn').forEach(btn => {
            btn.className = 'lplpo-flt-btn px-3 py-1 rounded-full text-[11px] font-bold bg-white text-slate-700 hover:bg-matcha-50 border border-slate-200 transition';
        });
        const activeBtn = document.getElementById('btn-flt-' + type);
        if (activeBtn) {
            activeBtn.className = 'lplpo-flt-btn px-3 py-1 rounded-full text-[11px] font-bold bg-matcha-700 text-white shadow-xs transition';
        }

        applyLplpoFiltering();
    }
    window.filterLplpoTable = filterLplpoTable;

    function applyLplpoFiltering() {
        const query = (lplpoSearchInput ? lplpoSearchInput.value : '').toLowerCase().trim();
        const rows = document.querySelectorAll('#lplpoTableBody tr.lplpo-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const nama = row.getAttribute('data-nama') || '';
            const matchQuery = query === '' || nama.includes(query);

            let matchFilter = true;
            if (currentLplpoFilter === 'order') {
                matchFilter = row.getAttribute('data-order') === '1';
            } else if (currentLplpoFilter === 'kritis') {
                matchFilter = row.getAttribute('data-kritis') === '1';
            } else if (currentLplpoFilter === 'akurat') {
                matchFilter = row.getAttribute('data-akurat') === '1';
            }

            if (matchQuery && matchFilter) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const counterEl = document.getElementById('filteredLplpoCount');
        if (counterEl) counterEl.textContent = visibleCount;
    }

    if (lplpoSearchInput) {
        lplpoSearchInput.addEventListener('input', applyLplpoFiltering);
    }

    // Dynamic SIMPUS Quick Preset Disease Selection
    const quickDiseaseSelect = document.getElementById('quickDiseaseSelect');
    const inputKodeIcd = document.getElementById('inputKodeIcd');
    const inputNamaPenyakit = document.getElementById('inputNamaPenyakit');
    const inputKlasterIcd = document.getElementById('inputKlasterIcd');

    if (quickDiseaseSelect) {
        quickDiseaseSelect.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            if (this.value) {
                if (inputKodeIcd) inputKodeIcd.value = opt.value;
                if (inputNamaPenyakit) inputNamaPenyakit.value = opt.dataset.nama || '';
                if (inputKlasterIcd) inputKlasterIcd.value = opt.dataset.klaster || '';
            }
        });
    }

    // Dynamic Patient Sum
    const pasienLaki = document.getElementById('pasienLaki');
    const pasienPerempuan = document.getElementById('pasienPerempuan');
    const previewTotalPasien = document.getElementById('previewTotalPasien');

    function calculateTotalPasien() {
        const l = parseFloat(pasienLaki.value) || 0;
        const p = parseFloat(pasienPerempuan.value) || 0;
        if (previewTotalPasien) {
            previewTotalPasien.textContent = (l + p).toLocaleString('id-ID') + ' Pasien';
        }
    }

    if (pasienLaki && pasienPerempuan) {
        pasienLaki.addEventListener('input', calculateTotalPasien);
        pasienPerempuan.addEventListener('input', calculateTotalPasien);
    }

    // Dynamic Loss Calculation
    const jumlahEd = document.getElementById('jumlahEd');
    const hargaSatuanEd = document.getElementById('hargaSatuanEd');
    const previewKerugian = document.getElementById('previewKerugian');

    function calculateKerugian() {
        const j = parseFloat(jumlahEd.value) || 0;
        const h = parseFloat(hargaSatuanEd.value) || 0;
        if (previewKerugian) {
            previewKerugian.textContent = 'Rp ' + (j * h).toLocaleString('id-ID');
        }
    }

    if (jumlahEd && hargaSatuanEd) {
        jumlahEd.addEventListener('input', calculateKerugian);
        hargaSatuanEd.addEventListener('input', calculateKerugian);
    }

    // Dynamic New Medicine Preview
    const regStokAwal = document.getElementById('regStokAwal');
    const regEstimasi = document.getElementById('regEstimasi');
    const previewRegSs = document.getElementById('previewRegSs');
    const previewRegOrder = document.getElementById('previewRegOrder');

    function calculateNewMed() {
        const stok = parseFloat(regStokAwal.value) || 0;
        const est = parseFloat(regEstimasi.value) || 0;
        const ss = Math.round(est * 0.25);
        const order = Math.max(0, (est + ss) - stok);
        
        if (previewRegSs) previewRegSs.textContent = ss.toLocaleString('id-ID');
        if (previewRegOrder) previewRegOrder.textContent = order.toLocaleString('id-ID');
    }

    if (regStokAwal && regEstimasi) {
        regStokAwal.addEventListener('input', calculateNewMed);
        regEstimasi.addEventListener('input', calculateNewMed);
    }
</script>
@endpush
