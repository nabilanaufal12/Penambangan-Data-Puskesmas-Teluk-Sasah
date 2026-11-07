@extends('layouts.app')

@section('title', 'Pusat Entri Data Faskes')
@section('page_title', 'Pusat Entri & Pemutakhiran Data Operasional Faskes')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <!-- Top Header Banner -->
    <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-medical-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-slate-800">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 rounded-full bg-medical-500/20 text-medical-300 text-xs font-bold border border-medical-500/30">
                        <i class="fa-solid fa-hospital-user mr-1.5"></i>UPTD Puskesmas Teluk Sasah
                    </span>
                    <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold border border-emerald-500/30">
                        4 Modul Entri Terintegrasi
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    Pusat Entri & Pemutakhiran Data Terpadu
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 max-w-3xl leading-relaxed">
                    Sistem entri data satu pintu untuk seluruh petugas operasional faskes: pengelola obat farmasi (LPLPO & Kadaluarsa), petugas SIMPUS (Morbiditas Penyakit ICD-10), dan penanggung jawab perbekalan kesehatan.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('lplpo.print') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition flex items-center shadow-xs">
                    <i class="fa-solid fa-print mr-2 text-medical-300"></i>
                    <span>Cetak Lembar LPLPO Resmi</span>
                </a>
                <a href="{{ route('procurement') }}" class="px-4 py-2.5 rounded-xl bg-medical-600 hover:bg-medical-700 text-white text-xs font-bold shadow-md shadow-medical-600/30 transition flex items-center">
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
                <p class="text-[10px] text-slate-400 uppercase font-semibold">Total Nilai Kerugian ED</p>
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
        <button onclick="switchTab('lplpo')" id="btn-tab-lplpo" class="tab-btn flex-1 min-w-[200px] py-3 px-4 rounded-xl font-bold text-xs transition flex items-center justify-center space-x-2.5 {{ $tab === 'lplpo' ? 'bg-medical-600 text-white shadow-md shadow-medical-600/20' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
            <i class="fa-solid fa-boxes-packing text-sm"></i>
            <span>1. Stok & Pemakaian LPLPO</span>
        </button>
        <button onclick="switchTab('penyakit')" id="btn-tab-penyakit" class="tab-btn flex-1 min-w-[200px] py-3 px-4 rounded-xl font-bold text-xs transition flex items-center justify-center space-x-2.5 {{ $tab === 'penyakit' ? 'bg-medical-600 text-white shadow-md shadow-medical-600/20' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
            <i class="fa-solid fa-stethoscope text-sm"></i>
            <span>2. Morbiditas SIMPUS (ICD-10)</span>
        </button>
        <button onclick="switchTab('kadaluarsa')" id="btn-tab-kadaluarsa" class="tab-btn flex-1 min-w-[200px] py-3 px-4 rounded-xl font-bold text-xs transition flex items-center justify-center space-x-2.5 {{ $tab === 'kadaluarsa' ? 'bg-medical-600 text-white shadow-md shadow-medical-600/20' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
            <i class="fa-solid fa-calendar-xmark text-sm"></i>
            <span>3. Berita Acara ED / Kadaluarsa</span>
        </button>
        <button onclick="switchTab('obat')" id="btn-tab-obat" class="tab-btn flex-1 min-w-[200px] py-3 px-4 rounded-xl font-bold text-xs transition flex items-center justify-center space-x-2.5 {{ $tab === 'obat' ? 'bg-medical-600 text-white shadow-md shadow-medical-600/20' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
            <i class="fa-solid fa-pills text-sm"></i>
            <span>4. Registrasi Obat Baru</span>
        </button>
    </div>

    <!-- ========================================== -->
    <!-- TAB 1: STOK & PEMAKAIAN LPLPO -->
    <!-- ========================================== -->
    <div id="content-tab-lplpo" class="tab-content {{ $tab === 'lplpo' ? '' : 'hidden' }} space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left: Form Input Stok LPLPO -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm sticky top-24 space-y-5">
                    <div class="flex items-center space-x-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-medical-100 text-medical-700 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-sm">Pembaruan Stok & Pemakaian LPLPO</h3>
                            <p class="text-[11px] text-slate-500">Kalkulasi ulang rekomendasi otomatis (Safety Stock & WMA)</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('data.entry.lplpo') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Sediaan Obat Farmasi:</label>
                            <select name="medicine_id" id="medSelect" required class="w-full py-2.5 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-medical-500">
                                <option value="">-- Pilih Obat dari Katalog --</option>
                                @foreach($medicines as $m)
                                <option value="{{ $m->id }}" 
                                    data-satuan="{{ $m->satuan }}" 
                                    data-stok="{{ $m->sisa_stok }}" 
                                    data-pred="{{ $m->prediksi_P5 }}"
                                    data-ss="{{ $m->safety_stock }}"
                                    data-order="{{ $m->rekomendasi_total }}"
                                    data-model="{{ $m->model_terbaik }}">
                                    {{ $m->nama_obat }} ({{ $m->satuan }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Live Selected Info Card -->
                        <div id="medInfoCard" class="hidden bg-slate-50 rounded-xl p-3.5 border border-slate-200 text-xs space-y-2">
                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-slate-500">Model Algoritma:</span>
                                <span id="infoModel" class="font-bold text-medical-700">WMA</span>
                            </div>
                            <div class="grid grid-cols-3 gap-2 pt-2 border-t border-slate-200 text-center">
                                <div>
                                    <span class="text-[10px] text-slate-400 block">Sisa Stok Saat Ini</span>
                                    <span id="infoStok" class="font-extrabold text-slate-800 font-mono">-</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-400 block">Prediksi P5</span>
                                    <span id="infoPred" class="font-extrabold text-medical-600 font-mono">-</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-400 block">Order P5 Aktif</span>
                                    <span id="infoOrder" class="font-extrabold text-amber-600 font-mono">-</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Pemakaian Riil Periode:</label>
                                <input type="number" name="pemakaian_terbaru" min="0" step="1" required placeholder="0" class="w-full py-2.5 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-medical-500 font-mono">
                                <span class="text-[10px] text-slate-400 block mt-1">Total obat keluar dari gudang</span>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Sisa Stok Fisik Akhir (SOH):</label>
                                <input type="number" name="sisa_stok_terbaru" min="0" step="1" required placeholder="0" class="w-full py-2.5 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-medical-500 font-mono">
                                <span class="text-[10px] text-slate-400 block mt-1">Hasil stock opname fisik gudang</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan / Keterangan Farmasi (Opsional):</label>
                            <textarea name="keterangan_tambahan" rows="2" placeholder="Contoh: Stok menipis karena lonjakan kasus musiman atau kekosongan dari IFK..." class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-medical-500"></textarea>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-medical-600 hover:bg-medical-700 text-white font-bold text-xs shadow-md shadow-medical-600/20 transition flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-calculator"></i>
                                <span>Simpan & Hitung Ulang Rekomendasi P5</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right: Katalog Stok LPLPO Ringkas -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-sm">Status Stok & Alokasi Terkini</h3>
                            <p class="text-[11px] text-slate-500">150 Sediaan Obat Terdaftar di UPTD Puskesmas Teluk Sasah</p>
                        </div>
                        <span class="text-[10px] bg-medical-50 text-medical-700 font-bold px-2.5 py-1 rounded-lg border border-medical-200">
                            Sinkronisasi LPLPO
                        </span>
                    </div>

                    <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead class="bg-slate-100 text-slate-600 font-bold sticky top-0 uppercase tracking-wider text-[10px] border-b border-slate-200">
                                <tr>
                                    <th class="py-3 px-4">Nama Sediaan Obat</th>
                                    <th class="py-3 px-3 text-right">Sisa Stok Fisik</th>
                                    <th class="py-3 px-3 text-right">Prediksi P5</th>
                                    <th class="py-3 px-3 text-right">Safety Stock</th>
                                    <th class="py-3 px-3 text-right">Rekomendasi</th>
                                    <th class="py-3 px-4 text-center">Prioritas</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                @foreach($medicines as $m)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-2.5 px-4">
                                        <div class="font-bold text-slate-900">{{ $m->nama_obat }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $m->satuan }}</div>
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-mono font-bold {{ $m->sisa_stok <= $m->safety_stock ? 'text-rose-600' : 'text-slate-700' }}">
                                        {{ number_format($m->sisa_stok, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-mono text-medical-700">
                                        {{ number_format($m->prediksi_P5, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-mono text-slate-500">
                                        {{ number_format($m->safety_stock, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-mono font-black text-amber-600">
                                        {{ number_format($m->rekomendasi_total, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 px-4 text-center">
                                        @if($m->prioritas_kategori == 'Prioritas Tinggi (Fast Moving)')
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Tinggi</span>
                                        @elseif($m->prioritas_kategori == 'Prioritas Sedang')
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Sedang</span>
                                        @else
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Rendah</span>
                                        @endif
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

    <!-- ========================================== -->
    <!-- TAB 2: MORBIDITAS PENYAKIT SIMPUS -->
    <!-- ========================================== -->
    <div id="content-tab-penyakit" class="tab-content {{ $tab === 'penyakit' ? '' : 'hidden' }} space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left: Form Input Morbiditas SIMPUS -->
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

                    <!-- Quick Preset Dropdown -->
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

                    <form method="POST" action="{{ route('data.entry.disease') }}" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Tahun Rekap:</label>
                                <select name="tahun" required class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-emerald-500">
                                    <option value="2026" selected>2026</option>
                                    <option value="2025">2025</option>
                                    <option value="2024">2024</option>
                                    <option value="2023">2023</option>
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

                        <!-- Total Preview -->
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

            <!-- Right: 15 Rekam Morbiditas SIMPUS Terkini -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-sm">Rekam Morbiditas Terkini (SIMPUS)</h3>
                            <p class="text-[11px] text-slate-500">15 Entri Terkini Terintegrasi dalam Basis Data Puskesmas</p>
                        </div>
                        <a href="{{ route('diseases') }}" class="text-[11px] font-bold text-emerald-600 hover:text-emerald-700">
                            Lihat Analisis Morbiditas &rarr;
                        </a>
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
                                        <span class="text-[9px] text-slate-400 block">({{ $rd->periode_bimonthly_id }})</span>
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

    <!-- ========================================== -->
    <!-- TAB 3: BERITA ACARA ED / KADALUARSA -->
    <!-- ========================================== -->
    <div id="content-tab-kadaluarsa" class="tab-content {{ $tab === 'kadaluarsa' ? '' : 'hidden' }} space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left: Form Input Obat Kadaluarsa -->
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

                    <form method="POST" action="{{ route('data.entry.expired') }}" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Tahun Pemusnahan / ED:</label>
                                <select name="tahun" required class="w-full py-2 px-3 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-amber-500">
                                    <option value="2026" selected>2026</option>
                                    <option value="2025">2025</option>
                                    <option value="2024">2024</option>
                                    <option value="2023">2023</option>
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
                                <option value="Program Bantuan Kemenkes">Program Bantuan Kemenkes RI</option>
                            </select>
                        </div>

                        <!-- Real-time Subtotal Loss Preview -->
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

            <!-- Right: 15 Berita Acara Terkini -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-sm">Riwayat Berita Acara Pemusnahan / ED</h3>
                            <p class="text-[11px] text-slate-500">15 Catatan Fisik Terkini untuk Audit Kerugian Farmasi</p>
                        </div>
                        <a href="{{ route('audit.stock') }}" class="text-[11px] font-bold text-amber-600 hover:text-amber-700">
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

    <!-- ========================================== -->
    <!-- TAB 4: REGISTRASI OBAT BARU -->
    <!-- ========================================== -->
    <div id="content-tab-obat" class="tab-content {{ $tab === 'obat' ? '' : 'hidden' }} space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left: Form Registrasi Obat Baru -->
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

                    <form method="POST" action="{{ route('data.entry.medicine') }}" class="space-y-4">
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
                                    <option value="Pcs">Pcs / Buah</option>
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

                        <!-- Auto-Generated Parameters Preview -->
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

            <!-- Right: Panduan Integrasi Formularium -->
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
                                <span class="font-bold text-slate-900">Inisialisasi Model Peramalan Time-Series:</span>
                                <p class="text-[11px] text-slate-500 mt-0.5">Sediaan baru akan otomatis dipetakan ke model Weighted Moving Average (WMA k=3) sebagai baseline sebelum terbentuk deret riwayat pemakaian dwibulanan.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="w-6 h-6 rounded-full bg-purple-600 text-white font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">2</div>
                            <div>
                                <span class="font-bold text-slate-900">Kalkulasi Buffer Cadangan Penyangga (Safety Stock):</span>
                                <p class="text-[11px] text-slate-500 mt-0.5">Sistem secara baku menetapkan safety stock sebesar 25% dari estimasi konsumsi dwibulanan untuk mencegah kekosongan stok mendadak selama masa transisi pengiriman IFK Bintan.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="w-6 h-6 rounded-full bg-purple-600 text-white font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">3</div>
                            <div>
                                <span class="font-bold text-slate-900">Masuk Otomatis ke Lembar Rekomendasi & Cetak LPLPO:</span>
                                <p class="text-[11px] text-slate-500 mt-0.5">Setelah didaftarkan, sediaan langsung tampil pada tabel rekomendasi pengadaan P5 (2026) dan lembar cetak LPLPO resmi tanpa perlu re-import data.</p>
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
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        
        // Remove active class from buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.className = 'tab-btn flex-1 min-w-[200px] py-3 px-4 rounded-xl font-bold text-xs transition flex items-center justify-center space-x-2.5 bg-slate-50 text-slate-600 hover:bg-slate-100';
        });

        // Show target tab
        const targetContent = document.getElementById('content-tab-' + tabName);
        if (targetContent) {
            targetContent.classList.remove('hidden');
        }

        // Activate button
        const targetBtn = document.getElementById('btn-tab-' + tabName);
        if (targetBtn) {
            targetBtn.className = 'tab-btn flex-1 min-w-[200px] py-3 px-4 rounded-xl font-bold text-xs transition flex items-center justify-center space-x-2.5 bg-medical-600 text-white shadow-md shadow-medical-600/20';
        }

        // Update URL query param without full page reload
        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.pushState({}, '', url);
    }

    // Dynamic LPLPO Medicine Selection Preview
    const medSelect = document.getElementById('medSelect');
    const medInfoCard = document.getElementById('medInfoCard');
    const infoModel = document.getElementById('infoModel');
    const infoStok = document.getElementById('infoStok');
    const infoPred = document.getElementById('infoPred');
    const infoOrder = document.getElementById('infoOrder');

    if (medSelect) {
        medSelect.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            if (this.value) {
                medInfoCard.classList.remove('hidden');
                infoModel.textContent = opt.dataset.model || 'WMA';
                infoStok.textContent = Number(opt.dataset.stok).toLocaleString('id-ID') + ' ' + (opt.dataset.satuan || '');
                infoPred.textContent = Number(opt.dataset.pred).toLocaleString('id-ID');
                infoOrder.textContent = Number(opt.dataset.order).toLocaleString('id-ID');
            } else {
                medInfoCard.classList.add('hidden');
            }
        });
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

    // Dynamic SIMPUS Male + Female Patient Sum
    const pasienLaki = document.getElementById('pasienLaki');
    const pasienPerempuan = document.getElementById('pasienPerempuan');
    const previewTotalPasien = document.getElementById('previewTotalPasien');

    function calculateTotalPasien() {
        const l = parseFloat(pasienLaki.value) || 0;
        const p = parseFloat(pasienPerempuan.value) || 0;
        const total = l + p;
        previewTotalPasien.textContent = total.toLocaleString('id-ID') + ' Pasien';
    }

    if (pasienLaki && pasienPerempuan) {
        pasienLaki.addEventListener('input', calculateTotalPasien);
        pasienPerempuan.addEventListener('input', calculateTotalPasien);
    }

    // Dynamic Expired Medicine Subtotal Loss Calculation
    const jumlahEd = document.getElementById('jumlahEd');
    const hargaSatuanEd = document.getElementById('hargaSatuanEd');
    const previewKerugian = document.getElementById('previewKerugian');

    function calculateKerugian() {
        const j = parseFloat(jumlahEd.value) || 0;
        const h = parseFloat(hargaSatuanEd.value) || 0;
        const total = j * h;
        previewKerugian.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    if (jumlahEd && hargaSatuanEd) {
        jumlahEd.addEventListener('input', calculateKerugian);
        hargaSatuanEd.addEventListener('input', calculateKerugian);
    }

    // Dynamic New Medicine Safety Stock & Order Recommendation Calculation
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
