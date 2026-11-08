@extends('layouts.pharmacy')

@section('title', 'Pusat Entri Data Faskes')
@section('page_title', 'Pusat Entri Data Terpadu (4 Modul Operasional)')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <!-- Top Header Banner -->
    <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-emerald-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-slate-800">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold border border-emerald-500/30">
                        <i class="fa-solid fa-hospital-user mr-1.5"></i>Instalasi Farmasi Puskesmas
                    </span>
                    <span class="px-3 py-1 rounded-full bg-cyan-500/20 text-cyan-300 text-xs font-bold border border-cyan-500/30">
                        4 Modul Entri Terintegrasi
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    Pusat Entri & Pemutakhiran Data Farmasi
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 max-w-3xl leading-relaxed">
                    Formulir entri satu pintu untuk petugas pengelola obat farmasi dan SIMPUS: stok fisik dwibulanan LPLPO, rekap morbiditas penyakit ICD-10, berita acara obat rusak/kadaluarsa (ED), serta registrasi sediaan obat baru.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('pharmacy.print') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition flex items-center shadow-xs">
                    <i class="fa-solid fa-print mr-2 text-emerald-300"></i>
                    <span>Cetak Lembar LPLPO</span>
                </a>
                <a href="{{ route('pharmacy.procurement') }}" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-600/30 transition flex items-center">
                    <i class="fa-solid fa-clipboard-check mr-2"></i>
                    <span>Review Rencana Pengadaan</span>
                </a>
            </div>
        </div>

        <!-- Metric Badges Bar -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-6 pt-6 border-t border-slate-800/80">
            <div class="bg-white/5 rounded-2xl p-3 border border-white/10">
                <p class="text-[10px] text-slate-400 uppercase font-semibold">Katalog Sediaan Obat</p>
                <p class="text-lg font-black text-white mt-0.5">{{ $medicines->count() }} <span class="text-xs font-normal text-slate-300">Item Aktif</span></p>
            </div>
            <div class="bg-white/5 rounded-2xl p-3 border border-white/10">
                <p class="text-[10px] text-slate-400 uppercase font-semibold">Morbiditas SIMPUS</p>
                <p class="text-lg font-black text-emerald-400 mt-0.5">{{ number_format($totalDiseaseRecords, 0, ',', '.') }} <span class="text-xs font-normal text-slate-300">Catatan Rekam</span></p>
            </div>
            <div class="bg-white/5 rounded-2xl p-3 border border-white/10">
                <p class="text-[10px] text-slate-400 uppercase font-semibold">Berita Acara ED</p>
                <p class="text-lg font-black text-amber-400 mt-0.5">{{ $totalExpiredItems }} <span class="text-xs font-normal text-slate-300">Batch Rusak/ED</span></p>
            </div>
            <div class="bg-white/5 rounded-2xl p-3 border border-white/10">
                <p class="text-[10px] text-slate-400 uppercase font-semibold">Total Kerugian ED</p>
                <p class="text-lg font-black text-rose-400 mt-0.5">Rp {{ number_format($totalExpiredLoss, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Notification Messages -->
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-300 rounded-2xl p-4 text-emerald-900 text-xs flex items-center space-x-3 shadow-xs">
        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-check"></i>
        </div>
        <div class="font-bold flex-1 leading-relaxed">
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-rose-50 border border-rose-300 rounded-2xl p-4 text-rose-900 text-xs space-y-1 shadow-xs">
        <div class="font-black flex items-center space-x-2 text-rose-700">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span>Terdapat beberapa kesalahan input:</span>
        </div>
        <ul class="list-disc list-inside space-y-0.5 text-rose-800">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Interactive Navigation Tabs -->
    <div class="bg-white rounded-2xl p-2 border border-slate-200 shadow-xs flex flex-wrap gap-2">
        <button onclick="switchTab('lplpo')" id="btn-tab-lplpo" class="tab-btn flex-1 min-w-[200px] py-3 px-4 rounded-xl font-bold text-xs transition flex items-center justify-center space-x-2.5 {{ $tab === 'lplpo' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
            <i class="fa-solid fa-boxes-packing text-sm"></i>
            <span>1. Stok & Pemakaian LPLPO</span>
        </button>
        <button onclick="switchTab('penyakit')" id="btn-tab-penyakit" class="tab-btn flex-1 min-w-[200px] py-3 px-4 rounded-xl font-bold text-xs transition flex items-center justify-center space-x-2.5 {{ $tab === 'penyakit' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
            <i class="fa-solid fa-stethoscope text-sm"></i>
            <span>2. Morbiditas SIMPUS (ICD-10)</span>
        </button>
        <button onclick="switchTab('kadaluarsa')" id="btn-tab-kadaluarsa" class="tab-btn flex-1 min-w-[200px] py-3 px-4 rounded-xl font-bold text-xs transition flex items-center justify-center space-x-2.5 {{ $tab === 'kadaluarsa' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
            <i class="fa-solid fa-calendar-xmark text-sm"></i>
            <span>3. Berita Acara ED / Kadaluarsa</span>
        </button>
        <button onclick="switchTab('obat')" id="btn-tab-obat" class="tab-btn flex-1 min-w-[200px] py-3 px-4 rounded-xl font-bold text-xs transition flex items-center justify-center space-x-2.5 {{ $tab === 'obat' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
            <i class="fa-solid fa-pills text-sm"></i>
            <span>4. Registrasi Obat Baru</span>
        </button>
    </div>

    <!-- TAB 1: STOK & PEMAKAIAN LPLPO -->
    <div id="content-tab-lplpo" class="tab-content {{ $tab === 'lplpo' ? '' : 'hidden' }} space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-5">
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-float sticky top-24 space-y-5">
                    <div class="flex items-center space-x-3 pb-4 border-b border-slate-100">
                        <div class="w-11 h-11 rounded-2xl bg-matcha-100 text-matcha-800 flex items-center justify-center text-xl shadow-xs">
                            <i class="fa-solid fa-file-invoice"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-sm">Pencatatan Lembar LPLPO Puskesmas</h3>
                            <p class="text-[11px] text-slate-500">Kolom disesuaikan langsung dengan lembar Excel resmi Puskesmas Teluk Sasah</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('pharmacy.entry.lplpo') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                <span>1. Nama Sediaan Obat:</span>
                                <span class="text-[10px] text-matcha-700 font-semibold">150 Obat Aktif</span>
                            </label>
                            <select name="medicine_id" id="medSelect" required class="w-full py-2.5 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-matcha-600/30 text-ink font-medium">
                                <option value="">-- Pilih Sediaan Obat dari Daftar --</option>
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
                        </div>

                        <!-- Panel Evaluasi Akurasi Model & Prediksi Sebelumnya -->
                        <div id="medInfoCard" class="hidden bg-matcha-50/80 rounded-2xl p-4 border border-matcha-200 text-xs space-y-3">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-bold text-matcha-900 flex items-center gap-1.5">
                                    <i class="fa-solid fa-chart-line text-matcha-700"></i>
                                    <span>Evaluasi Akurasi Perkiraan Model</span>
                                </span>
                                <span id="infoAkurasiPill" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-matcha-200 text-matcha-900">
                                    Akurasi: 95%
                                </span>
                            </div>
                            <div class="grid grid-cols-3 gap-2 pt-1 text-center">
                                <div class="bg-white/80 p-2 rounded-xl">
                                    <span class="text-[10px] text-slate-500 block">Prediksi Model Lalu</span>
                                    <span id="infoPred" class="font-extrabold text-matcha-800 font-mono text-xs">-</span>
                                </div>
                                <div class="bg-white/80 p-2 rounded-xl">
                                    <span class="text-[10px] text-slate-500 block">Cadangan Pengaman</span>
                                    <span id="infoSs" class="font-extrabold text-slate-700 font-mono text-xs">-</span>
                                </div>
                                <div class="bg-white/80 p-2 rounded-xl">
                                    <span class="text-[10px] text-slate-500 block">Usulan Permintaan</span>
                                    <span id="infoOrder" class="font-extrabold text-amber-700 font-mono text-xs">-</span>
                                </div>
                            </div>
                            <p id="infoEvaluasiTeks" class="text-[10px] text-matcha-800 leading-tight">
                                Sistem otomatis membandingkan pemakaian riil dengan perkiraan model sebelumnya.
                            </p>
                        </div>

                        <!-- Kolom Stok Awal & Penerimaan (Format Excel LPLPO) -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">2. Stok Awal Periode:</label>
                                <input type="number" name="stok_awal" id="inputStokAwal" min="0" step="1" placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-matcha-600/30 font-mono">
                                <span class="text-[10px] text-slate-400 block mt-0.5">Sisa awal periode</span>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">3. Penerimaan (Dinkes):</label>
                                <input type="number" name="penerimaan" id="inputPenerimaan" min="0" step="1" placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-matcha-600/30 font-mono">
                                <span class="text-[10px] text-slate-400 block mt-0.5">Drop dari GFK Bintan</span>
                            </div>
                        </div>

                        <!-- Kolom Total Persediaan & Pemakaian Riil -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center justify-between">
                                    <span>4. Total Persediaan:</span>
                                    <span class="text-[9px] text-matcha-600 font-normal">Stok Awal + Terima</span>
                                </label>
                                <input type="number" id="previewPersediaan" readonly placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-100 border border-slate-200 text-slate-700 font-mono font-bold cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">5. Pemakaian Riil (Keluar):</label>
                                <input type="number" name="pemakaian_terbaru" id="inputPemakaian" min="0" step="1" required placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-matcha-600/30 font-mono font-bold text-ink">
                                <span class="text-[10px] text-slate-400 block mt-0.5">Jumlah resep dilayani</span>
                            </div>
                        </div>

                        <!-- Kolom Sisa Stok Fisik & Flag Kekosongan -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">6. Sisa Stok Fisik Gudang:</label>
                                <input type="number" name="sisa_stok_terbaru" id="inputStokAkhir" min="0" step="1" placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-matcha-600/30 font-mono font-bold text-ink">
                                <span class="text-[10px] text-slate-400 block mt-0.5">Hasil stock opname fisik</span>
                            </div>
                            <div class="flex items-center pt-5">
                                <label class="flex items-start gap-2 cursor-pointer select-none">
                                    <input type="checkbox" name="pernah_kosong" value="1" id="checkPernahKosong" class="w-4 h-4 rounded text-matcha-600 focus:ring-matcha-500 border-slate-300 mt-0.5">
                                    <span class="text-[11px] text-slate-700 leading-tight">
                                        <strong>Stok Sempat Kosong</strong>
                                        <span class="block text-[10px] text-slate-400">Pernah habis saat pasien butuh (stok 0)</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">7. Catatan Petugas Farmasi (Opsional):</label>
                            <textarea name="keterangan_tambahan" rows="2" placeholder="Contoh: Terjadi lonjakan resep ISPA karena pancaroba / drop Dinkes berkurang..." class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-matcha-600/30"></textarea>
                        </div>

                        <div class="pt-1">
                            <button type="submit" class="w-full py-3.5 px-4 rounded-full bg-matcha-600 hover:bg-matcha-700 text-white font-extrabold text-xs shadow-md shadow-matcha-600/25 transition flex items-center justify-center space-x-2 cursor-pointer">
                                <i class="fa-solid fa-calculator text-sm"></i>
                                <span>Simpan Lembar LPLPO &amp; Evaluasi Model</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-7">
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-float overflow-hidden">
                    <div class="px-5 py-4 bg-slate-50/70 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-sm">Tabel Lembar Kerja LPLPO &amp; Evaluasi Akurasi</h3>
                            <p class="text-[11px] text-slate-500">150 Sediaan Obat Terdaftar di UPTD Puskesmas Teluk Sasah</p>
                        </div>
                        <a href="{{ route('pharmacy.print') }}" target="_blank" class="text-[11px] font-bold text-matcha-700 hover:text-matcha-800 flex items-center gap-1.5 self-start sm:self-auto">
                            <i class="fa-solid fa-print"></i>
                            <span>Cetak Format LPLPO &rarr;</span>
                        </a>
                    </div>

                    <div class="overflow-x-auto max-h-[640px] overflow-y-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead class="bg-slate-100 text-slate-700 font-bold sticky top-0 uppercase tracking-wider text-[10px] border-b border-slate-200">
                                <tr>
                                    <th class="py-3 px-3.5">Nama Obat &amp; Bentuk</th>
                                    <th class="py-3 px-2 text-right">Stok Awal</th>
                                    <th class="py-3 px-2 text-right">Terima</th>
                                    <th class="py-3 px-2 text-right">Sedia</th>
                                    <th class="py-3 px-2 text-right">Pakai</th>
                                    <th class="py-3 px-2.5 text-right">Sisa Fisik</th>
                                    <th class="py-3 px-2.5 text-center">Akurasi Model</th>
                                    <th class="py-3 px-3 text-right">Usulan Permintaan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                @foreach($medicines as $m)
                                <tr class="hover:bg-matcha-50/40 transition">
                                    <td class="py-2.5 px-3.5">
                                        <div class="font-bold text-slate-900 leading-tight">{{ $m->nama_obat }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $m->satuan }}</div>
                                    </td>
                                    <td class="py-2.5 px-2 text-right font-mono text-slate-600">
                                        {{ number_format($m->stok_awal ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 px-2 text-right font-mono text-slate-600">
                                        {{ number_format($m->penerimaan ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 px-2 text-right font-mono text-slate-800 font-semibold">
                                        {{ number_format($m->persediaan ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 px-2 text-right font-mono font-bold text-ink">
                                        {{ number_format($m->pemakaian ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 px-2.5 text-right font-mono font-bold {{ $m->sisa_stok <= $m->safety_stock ? 'text-rose-600' : 'text-slate-800' }}">
                                        {{ number_format($m->sisa_stok, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 px-2.5 text-center">
                                        @php
                                            $acc = (float) ($m->evaluasi_akurasi ?? 92);
                                        @endphp
                                        @if($acc >= 85)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-matcha-100 text-matcha-900">
                                                <i class="fa-solid fa-check text-[9px]"></i>{{ number_format($acc, 1) }}%
                                            </span>
                                        @elseif($acc >= 70)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900">
                                                {{ number_format($acc, 1) }}%
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-900">
                                                {{ number_format($acc, 1) }}%
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-mono font-black text-matcha-800">
                                        {{ number_format($m->rekomendasi_total, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 2: MORBIDITAS SIMPUS -->
    <div id="content-tab-penyakit" class="tab-content {{ $tab === 'penyakit' ? '' : 'hidden' }} space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-5">
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm sticky top-24 space-y-5">
                    <div class="flex items-center space-x-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-file-medical"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-sm">Entri Kasus Morbiditas SIMPUS</h3>
                            <p class="text-[11px] text-slate-500">Pencatatan kasus bulanan diagnosis ICD-10 untuk analisis tren</p>
                        </div>
                    </div>

                    <div class="bg-emerald-50/70 rounded-xl p-3 border border-emerald-200">
                        <label class="block text-[11px] font-bold text-emerald-900 mb-1">
                            <i class="fa-solid fa-bolt text-emerald-600 mr-1"></i>Pilih Cepat 10 Penyakit Terbanyak:
                        </label>
                        <select id="quickDiseaseSelect" class="w-full py-1.5 px-2.5 rounded-lg text-xs bg-white border border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium">
                            <option value="">-- Pilih Diagnosis Umum Puskesmas --</option>
                            @foreach($commonDiseases as $cd)
                            <option value="{{ $cd['kode'] }}" data-nama="{{ $cd['nama'] }}" data-klaster="{{ $cd['klaster'] }}">
                                [{{ $cd['kode'] }}] {{ $cd['nama'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <form method="POST" action="{{ route('pharmacy.entry.disease') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Tahun Rekap:</label>
                                <select name="tahun" required class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-emerald-500">
                                    <option value="2026" selected>2026</option>
                                    <option value="2025">2025</option>
                                    <option value="2024">2024</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Bulan Pelaporan:</label>
                                <select name="bulan_angka" required class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-emerald-500">
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
                        </div>

                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Kode ICD-10:</label>
                                <input type="text" name="kode_icd" id="inputKodeIcd" required placeholder="Mis: J00" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-emerald-500 font-mono uppercase">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Diagnosis / Penyakit:</label>
                                <input type="text" name="nama_penyakit" id="inputNamaPenyakit" required placeholder="Mis: ISPA (Nasofaringitis Akut)" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Klaster Kategori ICD-10:</label>
                            <input type="text" name="klaster_icd" id="inputKlasterIcd" placeholder="Penyakit Sistem Pernapasan" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Pasien Laki-laki:</label>
                                <input type="number" name="pasien_laki" id="pasienLaki" min="0" step="1" required placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-emerald-500 font-mono">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Pasien Perempuan:</label>
                                <input type="number" name="pasien_perempuan" id="pasienPerempuan" min="0" step="1" required placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-emerald-500 font-mono">
                            </div>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-semibold">Total Pasien (Otomatis):</span>
                            <span id="previewTotalPasien" class="font-black text-emerald-700 text-sm font-mono">0 Pasien</span>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                <span>Simpan Kasus Morbiditas SIMPUS</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-7">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-sm">Rekam Morbiditas Terkini (SIMPUS)</h3>
                            <p class="text-[11px] text-slate-500">15 Entri Terkini Terintegrasi dalam Basis Data Puskesmas</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead class="bg-slate-100 text-slate-600 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                                <tr>
                                    <th class="py-3 px-4">Periode</th>
                                    <th class="py-3 px-3">Kode ICD</th>
                                    <th class="py-3 px-4">Nama Diagnosis</th>
                                    <th class="py-3 px-3 text-right">L</th>
                                    <th class="py-3 px-3 text-right">P</th>
                                    <th class="py-3 px-4 text-right">Total Pasien</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                @forelse($recentDiseases as $rd)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-2.5 px-4 font-mono text-[11px]">
                                        <span class="font-bold text-slate-800">{{ $rd->bulan }} {{ $rd->tahun }}</span>
                                    </td>
                                    <td class="py-2.5 px-3">
                                        <span class="px-2 py-0.5 rounded-md font-mono font-bold text-[10px] bg-slate-100 text-slate-800 border border-slate-200">
                                            {{ $rd->kode_icd }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-4">
                                        <div class="font-bold text-slate-900">{{ $rd->nama_penyakit }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $rd->klaster_icd }}</div>
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-mono text-slate-600">{{ number_format($rd->pasien_laki, 0, ',', '.') }}</td>
                                    <td class="py-2.5 px-3 text-right font-mono text-slate-600">{{ number_format($rd->pasien_perempuan, 0, ',', '.') }}</td>
                                    <td class="py-2.5 px-4 text-right font-mono font-black text-emerald-700">
                                        {{ number_format($rd->total_pasien, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-slate-400">Belum ada rekam morbiditas terbaru.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 3: BERITA ACARA ED -->
    <div id="content-tab-kadaluarsa" class="tab-content {{ $tab === 'kadaluarsa' ? '' : 'hidden' }} space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-5">
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm sticky top-24 space-y-5">
                    <div class="flex items-center space-x-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-sm">Berita Acara Obat Rusak / ED</h3>
                            <p class="text-[11px] text-slate-500">Pencatatan resmi obat kadaluarsa untuk audit kerugian faskes</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('pharmacy.entry.expired') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Tahun ED:</label>
                                <select name="tahun" required class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-amber-500">
                                    <option value="2026" selected>2026</option>
                                    <option value="2025">2025</option>
                                    <option value="2024">2024</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Satuan Kemasan:</label>
                                <input type="text" name="satuan" required placeholder="Tablet / Botol / Vial" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-amber-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Obat Kadaluarsa / Rusak:</label>
                            <input type="text" name="nama_obat" list="medicineCatalogList" required placeholder="Ketik atau pilih dari katalog..." class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-amber-500">
                            <datalist id="medicineCatalogList">
                                @foreach($medicines as $m)
                                <option value="{{ $m->nama_obat }}">
                                @endforeach
                            </datalist>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Kadaluarsa (Fisik):</label>
                                <input type="number" name="jumlah_kadaluarsa" id="jumlahEd" min="1" step="1" required placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-amber-500 font-mono">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Harga Satuan (Rp):</label>
                                <input type="number" name="harga_satuan" id="hargaSatuanEd" min="0" step="1" required placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-amber-500 font-mono">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Sumber Anggaran Pengadaan:</label>
                            <select name="sumber_anggaran" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-amber-500">
                                <option value="APBD / DAK Farmasi Bintan" selected>APBD / DAK Farmasi Kab. Bintan</option>
                                <option value="Kapitasi JKN / BPJS Kesehatan">Kapitasi JKN / BPJS Kesehatan</option>
                                <option value="Drop Dinkes Provinsi Kepri">Drop Dinkes Provinsi Kepri</option>
                            </select>
                        </div>

                        <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 flex items-center justify-between text-xs">
                            <span class="text-amber-900 font-semibold">Subtotal Kerugian (Otomatis):</span>
                            <span id="previewKerugian" class="font-black text-rose-600 text-sm font-mono">Rp 0</span>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-md shadow-amber-600/20 transition flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-file-signature"></i>
                                <span>Simpan Berita Acara Kadaluarsa (ED)</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-7">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-sm">Riwayat Berita Acara Pemusnahan / ED</h3>
                            <p class="text-[11px] text-slate-500">15 Catatan Fisik Terkini untuk Audit Kerugian Farmasi</p>
                        </div>
                        <a href="{{ route('pharmacy.audit') }}" class="text-[11px] font-bold text-amber-600 hover:text-amber-700">
                            Buka Audit Stok Lengkap &rarr;
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead class="bg-slate-100 text-slate-600 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                                <tr>
                                    <th class="py-3 px-4">Tahun</th>
                                    <th class="py-3 px-4">Nama Obat</th>
                                    <th class="py-3 px-3 text-right">Jumlah ED</th>
                                    <th class="py-3 px-3 text-right">Harga Satuan</th>
                                    <th class="py-3 px-4 text-right">Total Kerugian</th>
                                    <th class="py-3 px-3">Sumber Anggaran</th>
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
                                    <td class="py-2.5 px-4 text-right font-mono font-black text-rose-600">
                                        Rp {{ number_format($re->sub_total_kerugian, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 px-3 text-[10px] text-slate-500">
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
        </div>
    </div>

    <!-- TAB 4: REGISTRASI OBAT BARU -->
    <div id="content-tab-obat" class="tab-content {{ $tab === 'obat' ? '' : 'hidden' }} space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-5">
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm sticky top-24 space-y-5">
                    <div class="flex items-center space-x-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-plus-circle"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-sm">Registrasi Sediaan Obat Baru</h3>
                            <p class="text-[11px] text-slate-500">Menambahkan formularium baru ke katalog dan inisialisasi AI WMA</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('pharmacy.entry.medicine') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Sediaan Obat & Dosis:</label>
                            <input type="text" name="nama_obat" required placeholder="Contoh: Cetirizine tablet 10 mg" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-purple-500">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Satuan Terkecil:</label>
                                <select name="satuan" required class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-purple-500">
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
                                <label class="block text-xs font-bold text-slate-700 mb-1">Klasifikasi Prioritas:</label>
                                <select name="prioritas_kategori" required class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-purple-500">
                                    <option value="Prioritas Tinggi (Fast Moving)" selected>Prioritas Tinggi (Fast Moving)</option>
                                    <option value="Prioritas Sedang">Prioritas Sedang</option>
                                    <option value="Prioritas Rendah (Slow Moving)">Prioritas Rendah (Slow Moving)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Stok Awal Gudang:</label>
                                <input type="number" name="stok_awal" id="regStokAwal" min="0" step="1" required placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-purple-500 font-mono">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Estimasi Kebutuhan Dwibulanan:</label>
                                <input type="number" name="estimasi_kebutuhan" id="regEstimasi" min="0" step="1" required placeholder="0" class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-purple-500 font-mono">
                            </div>
                        </div>

                        <div class="p-3.5 bg-purple-50 rounded-xl border border-purple-200 text-xs space-y-2">
                            <div class="flex items-center space-x-1.5 font-bold text-purple-900 text-[11px]">
                                <i class="fa-solid fa-microchip"></i>
                                <span>Inisialisasi Otomatis Model PRABOTIK:</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-[11px]">
                                <div>
                                    <span class="text-slate-500 block">Safety Stock (25% Buffer):</span>
                                    <span id="previewRegSs" class="font-bold text-purple-800 font-mono">0</span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block">Rekomendasi Order Awal:</span>
                                    <span id="previewRegOrder" class="font-black text-purple-900 font-mono">0</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-md shadow-purple-600/20 transition flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-folder-plus"></i>
                                <span>Daftarkan Obat Baru ke Katalog Aktif</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-7 space-y-6">
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
                    <div class="flex items-center space-x-3 pb-3 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-base">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-sm">Alur Operasional Pendaftaran Sediaan Obat Baru</h3>
                            <p class="text-[11px] text-slate-500">Standar Pengelolaan Formularium UPTD Puskesmas Teluk Sasah</p>
                        </div>
                    </div>

                    <div class="space-y-3 text-xs text-slate-600 leading-relaxed">
                        <div class="flex items-start space-x-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="w-6 h-6 rounded-full bg-purple-600 text-white font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">1</div>
                            <div>
                                <span class="font-bold text-slate-900">Inisialisasi Model Peramalan:</span>
                                <p class="text-[11px] text-slate-500 mt-0.5">Sediaan baru langsung dihubungkan dengan model Weighted Moving Average (WMA k=3) sebagai baseline deret waktu.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="w-6 h-6 rounded-full bg-purple-600 text-white font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">2</div>
                            <div>
                                <span class="font-bold text-slate-900">Kalkulasi Buffer Cadangan Penyangga:</span>
                                <p class="text-[11px] text-slate-500 mt-0.5">Sistem secara baku menetapkan safety stock sebesar 25% dari estimasi konsumsi dwibulanan.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="w-6 h-6 rounded-full bg-purple-600 text-white font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">3</div>
                            <div>
                                <span class="font-bold text-slate-900">Langsung Masuk Lembar Rekomendasi P5:</span>
                                <p class="text-[11px] text-slate-500 mt-0.5">Setelah didaftarkan, sediaan langsung tampil pada tabel usulan pengadaan P5 (2026) dan lembar cetak LPLPO resmi.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 flex items-center justify-between border-t border-slate-100 text-xs">
                        <span class="text-slate-500">Total Sediaan Aktif Saat Ini:</span>
                        <span class="font-extrabold text-purple-700 font-mono">{{ $medicines->count() }} Sediaan Obat</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Client-side Interactive Tab Switcher & Dynamic Calculators -->
<script>
    function switchTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.className = 'tab-btn flex-1 min-w-[200px] py-3 px-4 rounded-xl font-bold text-xs transition flex items-center justify-center space-x-2.5 bg-slate-50 text-slate-600 hover:bg-slate-100';
        });

        const targetContent = document.getElementById('content-tab-' + tabName);
        if (targetContent) {
            targetContent.classList.remove('hidden');
        }

        const targetBtn = document.getElementById('btn-tab-' + tabName);
        if (targetBtn) {
            targetBtn.className = 'tab-btn flex-1 min-w-[200px] py-3 px-4 rounded-xl font-bold text-xs transition flex items-center justify-center space-x-2.5 bg-emerald-600 text-white shadow-md shadow-emerald-600/20';
        }

        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.pushState({}, '', url);
    }

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
        previewPersediaan.value = persediaan;

        const pakai = parseFloat(inputPemakaian.value) || 0;
        const stokAkhir = Math.max(0, persediaan - pakai);
        inputStokAkhir.value = stokAkhir;

        // Evaluasi akurasi terhadap baseline prediksi model sebelumnya
        if (currentBaselinePred > 0 || pakai > 0) {
            const diff = Math.abs(pakai - currentBaselinePred);
            const maxVal = Math.max(pakai, currentBaselinePred, 1);
            const acc = Math.max(0, Math.round((1 - (diff / maxVal)) * 1000) / 10);

            let status = 'Sangat Sesuai';
            let pillClass = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-matcha-200 text-matcha-900';
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

            infoAkurasiPill.className = pillClass;
            infoAkurasiPill.textContent = 'Akurasi: ' + acc + '% (' + status + ')';

            // Hitungan baru untuk periode berikutnya
            let basis = pakai;
            if (checkPernahKosong && checkPernahKosong.checked) {
                basis = Math.round(pakai * 1.15); // koreksi data tersensor
            }
            const newPred = Math.round((currentBaselinePred * 0.4) + (basis * 0.6));
            const newSs = Math.round(newPred * 0.25);
            const newOrder = Math.max(0, (newPred + newSs) - stokAkhir);

            infoPred.textContent = newPred.toLocaleString('id-ID');
            infoSs.textContent = newSs.toLocaleString('id-ID');
            infoOrder.textContent = newOrder.toLocaleString('id-ID');

            infoEvaluasiTeks.textContent = 'Pemakaian nyata (' + pakai.toLocaleString('id-ID') + ') dibandingkan dengan perkiraan awal (' + currentBaselinePred.toLocaleString('id-ID') + '). Selisih: ' + (pakai - currentBaselinePred).toLocaleString('id-ID') + ' unit.';
        }
    }

    if (medSelect) {
        medSelect.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            if (this.value) {
                medInfoCard.classList.remove('hidden');
                currentBaselinePred = parseFloat(opt.dataset.pred) || 0;

                inputStokAwal.value = opt.dataset.stokawal || 0;
                inputPenerimaan.value = opt.dataset.terima || 0;
                previewPersediaan.value = opt.dataset.sedia || 0;
                inputPemakaian.value = opt.dataset.pakai || 0;
                inputStokAkhir.value = opt.dataset.stokakhir || 0;

                infoPred.textContent = Number(opt.dataset.pred).toLocaleString('id-ID');
                infoSs.textContent = Number(opt.dataset.ss).toLocaleString('id-ID');
                infoOrder.textContent = Number(opt.dataset.order).toLocaleString('id-ID');
                
                const initialAcc = parseFloat(opt.dataset.akurasi) || 95;
                infoAkurasiPill.textContent = 'Akurasi: ' + initialAcc.toFixed(1) + '%';
                infoEvaluasiTeks.textContent = opt.dataset.catatan || 'Sesuai dengan rata-rata konsumsi historis.';

                recalculateLplpo();
            } else {
                medInfoCard.classList.add('hidden');
            }
        });
    }

    if (inputStokAwal && inputPenerimaan && inputPemakaian) {
        inputStokAwal.addEventListener('input', recalculateLplpo);
        inputPenerimaan.addEventListener('input', recalculateLplpo);
        inputPemakaian.addEventListener('input', recalculateLplpo);
        if (checkPernahKosong) {
            checkPernahKosong.addEventListener('change', recalculateLplpo);
        }
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
                inputKodeIcd.value = opt.value;
                inputNamaPenyakit.value = opt.dataset.nama || '';
                inputKlasterIcd.value = opt.dataset.klaster || '';
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
        previewTotalPasien.textContent = (l + p).toLocaleString('id-ID') + ' Pasien';
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
        previewKerugian.textContent = 'Rp ' + (j * h).toLocaleString('id-ID');
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
        
        previewRegSs.textContent = ss.toLocaleString('id-ID');
        previewRegOrder.textContent = order.toLocaleString('id-ID');
    }

    if (regStokAwal && regEstimasi) {
        regStokAwal.addEventListener('input', calculateNewMed);
        regEstimasi.addEventListener('input', calculateNewMed);
    }
</script>
@endsection
