@extends('layouts.app')

@section('title', 'Beranda & Profil')
@section('page_title', 'Beranda PRABOTIK')

@section('content')

<!-- HERO SECTION (Gaya Medical Healthcare Sesuai Referensi) -->
<section class="relative bg-gradient-to-b from-white via-sky-50/40 to-slate-50 border-b border-slate-200/60 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Hero Content -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Trust Badge -->
                <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-medical-50 border border-medical-200 text-medical-700 text-xs font-bold tracking-wide shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-medical-500 animate-ping"></span>
                    <i class="fa-solid fa-shield-heart text-medical-600"></i>
                    <span>Sistem Logistik Farmasi Puskesmas Terpercaya</span>
                </div>

                <!-- Main Heading (Your Health Our Priority) -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-navy-950 tracking-tight leading-[1.15]">
                    Kesehatan Masyarakat, <br>
                    <span class="bg-gradient-to-r from-medical-600 via-medical-500 to-cyan-500 bg-clip-text text-transparent">Prioritas Utama Kami</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl font-normal">
                    PRABOTIK mengintegrasikan rekam medis morbiditas SIMPUS dengan data logistik LPLPO menggunakan algoritma 
                    <strong class="text-slate-800 font-semibold">Weighted Moving Average (WMA)</strong>, sistem deteksi dini 
                    <strong class="text-slate-800 font-semibold">MACD Farmasi</strong>, serta perlindungan 
                    <strong class="text-medical-600 font-semibold">Safety Stock 95%</strong> untuk mengeliminasi kekosongan obat dan pemborosan kadaluarsa di UPTD Puskesmas Teluk Sasah.
                </p>

                <!-- Action CTA Buttons -->
                <div class="flex flex-wrap items-center gap-3.5 pt-2">
                    <a href="{{ route('procurement') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-medical-600 hover:bg-medical-700 text-white font-bold text-sm shadow-lg shadow-medical-600/30 transition-all hover:shadow-medical-600/50">
                        <i class="fa-solid fa-cart-shopping mr-2.5"></i>
                        <span>Lihat Rekomendasi 2026-P5</span>
                    </a>
                    <a href="{{ route('diseases') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-sm border border-slate-200 shadow-sm transition-all hover:border-slate-300">
                        <span>Prediksi Morbiditas Penyakit</span>
                        <i class="fa-solid fa-arrow-right ml-2.5 text-medical-600 text-xs"></i>
                    </a>
                </div>

                <!-- 4 Statistics Counter Badges (Sesuai Referensi) -->
                <div class="pt-8 border-t border-slate-200/80 grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="p-3 bg-white/80 rounded-2xl border border-slate-100 shadow-xs">
                        <div class="flex items-center space-x-2 text-medical-600 mb-1">
                            <i class="fa-solid fa-file-waveform text-lg"></i>
                            <span class="text-2xl font-extrabold text-navy-950">{{ $stats['total_transaksi'] }}</span>
                        </div>
                        <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Transaksi LPLPO</p>
                    </div>

                    <div class="p-3 bg-white/80 rounded-2xl border border-slate-100 shadow-xs">
                        <div class="flex items-center space-x-2 text-medical-600 mb-1">
                            <i class="fa-solid fa-hospital-user text-lg"></i>
                            <span class="text-2xl font-extrabold text-navy-950">{{ $stats['total_pasien'] }}</span>
                        </div>
                        <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Pasien Rawat Jalan</p>
                    </div>

                    <div class="p-3 bg-white/80 rounded-2xl border border-slate-100 shadow-xs">
                        <div class="flex items-center space-x-2 text-medical-600 mb-1">
                            <i class="fa-solid fa-calendar-check text-lg"></i>
                            <span class="text-2xl font-extrabold text-navy-950">{{ $stats['total_periode'] }}</span>
                        </div>
                        <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Periode Kontinu</p>
                    </div>

                    <div class="p-3 bg-white/80 rounded-2xl border border-slate-100 shadow-xs">
                        <div class="flex items-center space-x-2 text-medical-600 mb-1">
                            <i class="fa-solid fa-capsules text-lg"></i>
                            <span class="text-2xl font-extrabold text-navy-950">{{ $stats['total_obat_aktif'] }}</span>
                        </div>
                        <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Katalog Obat Aktif</p>
                    </div>
                </div>

            </div>

            <!-- Right Hero Card / Doctor Presentation -->
            <div class="lg:col-span-5 relative">
                <div class="relative mx-auto max-w-md lg:max-w-none">
                    
                    <!-- Decorative backdrop gradient -->
                    <div class="absolute -top-4 -bottom-4 -left-4 -right-4 bg-gradient-to-tr from-medical-200/50 via-sky-100/40 to-cyan-100/50 rounded-3xl transform -rotate-1 filter blur-lg"></div>

                    <!-- Main Presentation Card -->
                    <div class="relative bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-100">
                        
                        <div class="flex items-center justify-between pb-6 border-b border-slate-100">
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-medical-600">Fasilitas Pelayanan Primer</span>
                                <h3 class="text-xl font-extrabold text-navy-950">UPTD Puskesmas Teluk Sasah</h3>
                                <p class="text-xs text-slate-500">Kec. Seri Kuala Lobam, Kab. Bintan</p>
                            </div>
                            <img src="/images/logo_umrah.png" alt="Logo UMRAH" class="w-12 h-12 object-contain rounded-xl">
                        </div>

                        <!-- Status Highlight -->
                        <div class="mt-6 space-y-3.5">
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100/80 flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg">
                                        <i class="fa-solid fa-shield-halved"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-800">Buffer Stok Pengaman 95%</p>
                                        <p class="text-[11px] text-slate-500">Mencegah Krisis Obat Esensial</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Aktif</span>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100/80 flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-sky-100 text-medical-600 flex items-center justify-center text-lg">
                                        <i class="fa-solid fa-chart-line"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-800">Sistem Deteksi Dini MACD</p>
                                        <p class="text-[11px] text-slate-500">{{ $stats['bullish_count'] }} Obat Alami Tren Naik (Bullish)</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-sky-50 text-medical-700 border border-sky-200">2/4/2</span>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100/80 flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-lg">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-800">Audit Kerugian Obat Kadaluarsa</p>
                                        <p class="text-[11px] text-slate-500">138 Item Berita Acara Fisik</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">{{ $stats['total_kerugian_ed'] }}</span>
                            </div>
                        </div>

                        <!-- Card Action -->
                        <div class="mt-6 pt-4 border-t border-slate-100">
                            <a href="{{ route('medicines') }}" class="block w-full text-center py-2.5 px-4 rounded-xl bg-navy-950 hover:bg-slate-800 text-white text-xs font-bold tracking-wide transition shadow-sm">
                                Buka Seluruh Katalog Obat (150 Item) &rarr;
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- SECTION 1: PELAYANAN POLI KESEHATAN (Our Medical Services) -->
<section class="py-16 bg-white border-b border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-xs font-bold text-medical-600 uppercase tracking-widest bg-medical-50 px-3 py-1 rounded-full border border-medical-200">Fasilitas Rawat Jalan</span>
            <h2 class="text-3xl font-extrabold text-navy-950 mt-3 tracking-tight">Pelayanan Kesehatan Terpadu Puskesmas</h2>
            <p class="text-sm text-slate-500 mt-2">Dukungan logistik obat terdistribusi ke seluruh unit poli dan instalasi medis UPTD Puskesmas Teluk Sasah.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-5">
            
            <!-- Service 1: Poli Umum -->
            <div class="bg-slate-50/70 hover:bg-white rounded-2xl p-5 border border-slate-200/80 hover:border-medical-300 hover:shadow-lg transition-all group">
                <div class="w-12 h-12 rounded-2xl bg-sky-100 group-hover:bg-medical-600 text-medical-600 group-hover:text-white flex items-center justify-center text-xl transition mb-4">
                    <i class="fa-solid fa-stethoscope"></i>
                </div>
                <h4 class="font-bold text-slate-800 text-sm mb-1.5">Poli Umum</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Pelayanan kuratif penyakit ISPA (J00), Hipertensi (I10), dan Maag (K30).</p>
            </div>

            <!-- Service 2: Poli Gigi -->
            <div class="bg-slate-50/70 hover:bg-white rounded-2xl p-5 border border-slate-200/80 hover:border-medical-300 hover:shadow-lg transition-all group">
                <div class="w-12 h-12 rounded-2xl bg-sky-100 group-hover:bg-medical-600 text-medical-600 group-hover:text-white flex items-center justify-center text-xl transition mb-4">
                    <i class="fa-solid fa-tooth"></i>
                </div>
                <h4 class="font-bold text-slate-800 text-sm mb-1.5">Poli Gigi & Mulut</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Pencabutan, tumpatan gigi, serta analgetik dan antibiotik oral.</p>
            </div>

            <!-- Service 3: KIA & Gizi -->
            <div class="bg-slate-50/70 hover:bg-white rounded-2xl p-5 border border-slate-200/80 hover:border-medical-300 hover:shadow-lg transition-all group">
                <div class="w-12 h-12 rounded-2xl bg-sky-100 group-hover:bg-medical-600 text-medical-600 group-hover:text-white flex items-center justify-center text-xl transition mb-4">
                    <i class="fa-solid fa-person-breastfeeding"></i>
                </div>
                <h4 class="font-bold text-slate-800 text-sm mb-1.5">Poli KIA & Gizi</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Pemeriksaan ibu hamil, tablet Fe gizi sekolah, dan balita stunting.</p>
            </div>

            <!-- Service 4: Farmasi -->
            <div class="bg-slate-50/70 hover:bg-white rounded-2xl p-5 border border-slate-200/80 hover:border-medical-300 hover:shadow-lg transition-all group">
                <div class="w-12 h-12 rounded-2xl bg-sky-100 group-hover:bg-medical-600 text-medical-600 group-hover:text-white flex items-center justify-center text-xl transition mb-4">
                    <i class="fa-solid fa-prescription-bottle-medical"></i>
                </div>
                <h4 class="font-bold text-slate-800 text-sm mb-1.5">Instalasi Farmasi</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Peracikan resep obat, audit stok LPLPO, dan kontrol tanggal kadaluarsa.</p>
            </div>

            <!-- Service 5: Laboratorium -->
            <div class="bg-slate-50/70 hover:bg-white rounded-2xl p-5 border border-slate-200/80 hover:border-medical-300 hover:shadow-lg transition-all group">
                <div class="w-12 h-12 rounded-2xl bg-sky-100 group-hover:bg-medical-600 text-medical-600 group-hover:text-white flex items-center justify-center text-xl transition mb-4">
                    <i class="fa-solid fa-vial-virus"></i>
                </div>
                <h4 class="font-bold text-slate-800 text-sm mb-1.5">Laboratorium</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Pemeriksaan darah rutin, GDP/GD2PP diabetes, kolesterol & asam urat.</p>
            </div>

            <!-- Service 6: UGD 24 Jam -->
            <div class="bg-slate-50/70 hover:bg-white rounded-2xl p-5 border border-slate-200/80 hover:border-medical-300 hover:shadow-lg transition-all group">
                <div class="w-12 h-12 rounded-2xl bg-red-100 group-hover:bg-red-600 text-red-600 group-hover:text-white flex items-center justify-center text-xl transition mb-4">
                    <i class="fa-solid fa-truck-medical"></i>
                </div>
                <h4 class="font-bold text-slate-800 text-sm mb-1.5">UGD 24 Jam</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Penanganan gawat darurat kecelakaan dan ambulans siaga 24 jam.</p>
            </div>

        </div>

    </div>
</section>

<!-- SECTION 2: PROFIL FASKES (About Our Puskesmas Sesuai Desain Referensi) -->
<section class="py-16 bg-slate-50 border-b border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            
            <!-- Left Text Content -->
            <div class="lg:col-span-6 space-y-6">
                <span class="text-xs font-bold text-medical-600 uppercase tracking-widest bg-medical-50 px-3 py-1 rounded-full border border-medical-200">Tentang UPTD Puskesmas</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-navy-950 tracking-tight leading-tight">
                    Fasilitas Kesehatan Tingkat Pertama dengan Komitmen Pelayanan Terbaik
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    UPTD Puskesmas Teluk Sasah terletak di Kecamatan Seri Kuala Lobam, Kabupaten Bintan, melayani ribuan warga pesisir dan kawasan industri Lobam. Puskesmas ini menyelenggarakan upaya kesehatan masyarakat (UKM) dan upaya kesehatan perseorangan (UKP) dengan fokus pencegahan penyakit kronis dan infeksi.
                </p>

                <!-- Checklist Keunggulan -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                    <div class="flex items-center space-x-2.5">
                        <i class="fa-solid fa-circle-check text-medical-600 text-base"></i>
                        <span class="text-xs font-semibold text-slate-700">Akurasi Peramalan WMA 14.2%</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <i class="fa-solid fa-circle-check text-medical-600 text-base"></i>
                        <span class="text-xs font-semibold text-slate-700">Proteksi 95% Safety Stock</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <i class="fa-solid fa-circle-check text-medical-600 text-base"></i>
                        <span class="text-xs font-semibold text-slate-700">Data Transaksi 22 Periode Valid</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <i class="fa-solid fa-circle-check text-medical-600 text-base"></i>
                        <span class="text-xs font-semibold text-slate-700">SOP Standar Kemenkes RI</span>
                    </div>
                </div>

                <div class="pt-4">
                    <a href="{{ route('about') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-medical-600 hover:bg-medical-700 text-white font-bold text-xs tracking-wide shadow-md shadow-medical-600/20 transition">
                        <span>Pelajari Selengkapnya</span>
                        <i class="fa-solid fa-arrow-right ml-2 text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Right Visual Banner Card -->
            <div class="lg:col-span-6">
                <div class="bg-gradient-to-br from-navy-950 to-slate-900 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden border border-slate-800">
                    <div class="absolute -right-8 -bottom-8 w-48 h-48 bg-medical-500/10 rounded-full blur-2xl"></div>
                    
                    <div class="relative z-10 space-y-6">
                        <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-medical-500/20 text-medical-300 text-xs font-bold border border-medical-500/30">
                            <i class="fa-solid fa-bell-concierge"></i>
                            <span>Fokus Utama Inovasi Penambangan Data</span>
                        </div>

                        <h3 class="text-2xl font-bold tracking-tight text-white leading-snug">
                            Mengubah Data Laporan LPLPO Menjadi Keputusan Pengadaan Presisi
                        </h3>

                        <p class="text-xs text-slate-300 leading-relaxed">
                            Penelitian Kelompok 23 Program Studi Teknik Informatika UMRAH berhasil mengonversi 29 berkas format beragam menjadi matriks waktu kontinu, mengeliminasi bias kekosongan stok (*stockout*), dan mencegah pemborosan obat kadaluarsa baru di puskesmas.
                        </p>

                        <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between text-xs">
                            <span class="text-slate-400">Semester Ganjil 2026/2027</span>
                            <span class="font-bold text-medical-400">Kelas TI24 &bull; INF11121</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- SECTION 3: REKOMENDASI PENGADAAN 2026-P5 UNGGULAN -->
<section class="py-16 bg-white border-b border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-8 gap-4">
            <div>
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">Output Keputusan Logistik</span>
                <h2 class="text-3xl font-extrabold text-navy-950 mt-2 tracking-tight">Rekomendasi Alokasi Pengadaan Obat 2026-P5</h2>
                <p class="text-xs text-slate-500 mt-1">Dihitung otomatis dengan Formula: <code>Order = max(0, Prediksi MA + Safety Stock 95% - Sisa Stok)</code></p>
            </div>
            <a href="{{ route('procurement') }}" class="inline-flex items-center text-xs font-bold text-medical-600 hover:text-medical-700 bg-sky-50 px-4 py-2 rounded-xl border border-sky-100 hover:bg-sky-100 transition">
                <span>Lihat Seluruh 150 Obat</span>
                <i class="fa-solid fa-chevron-right ml-1.5 text-[10px]"></i>
            </a>
        </div>

        <!-- Procurement Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider text-[11px] border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-4">Nama Sediaan Obat</th>
                            <th class="px-4 py-4 text-center">Satuan</th>
                            <th class="px-4 py-4 text-center">Model Terbaik</th>
                            <th class="px-4 py-4 text-right">Prediksi ($F_{t+1}$)</th>
                            <th class="px-4 py-4 text-right">Safety Stock (95%)</th>
                            <th class="px-4 py-4 text-right bg-emerald-50/50 text-emerald-900 font-extrabold">Total Alokasi Order</th>
                            <th class="px-4 py-4 text-center">Momentum MACD</th>
                            <th class="px-5 py-4 text-center">Prioritas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($vitalMedicines as $med)
                        <tr class="hover:bg-slate-50/80 transition {{ $med->is_censored_adjusted ? 'bg-amber-50/30' : '' }}">
                            <td class="px-5 py-3.5 font-bold text-slate-900">
                                {{ $med->nama_obat }}
                                @if($med->is_censored_adjusted)
                                    <span class="block text-[10px] text-amber-700 font-normal italic mt-0.5">
                                        <i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $med->keterangan }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-center text-slate-600">{{ $med->satuan }}</td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="px-2 py-0.5 rounded-md font-semibold text-[10px] bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $med->model_terbaik }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-right font-semibold text-slate-700">
                                {{ number_format($med->prediksi_P5, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3.5 text-right font-semibold text-amber-600">
                                +{{ number_format($med->safety_stock, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3.5 text-right font-extrabold text-emerald-700 bg-emerald-50/30 text-sm">
                                {{ number_format($med->rekomendasi_total, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ str_contains($med->momentum_status, 'Bullish') || str_contains($med->momentum_status, 'Rebound') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                    {{ $med->momentum_status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-medical-50 text-medical-800 border border-medical-200">
                                    {{ $med->prioritas_kategori }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>

<!-- SECTION 4: PREDIKSI 4 MORBIDITAS PENYAKIT & KURVA VISUALISASI -->
<section class="py-16 bg-slate-50 border-b border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-xs font-bold text-rose-600 uppercase tracking-widest bg-rose-50 px-3 py-1 rounded-full border border-rose-200">Pemodelan Beban Kasus</span>
            <h2 class="text-3xl font-extrabold text-navy-950 mt-3 tracking-tight">Peramalan Morbiditas 4 Penyakit Terbanyak</h2>
            <p class="text-sm text-slate-500 mt-2">Menindaklanjuti arahan dosen pembimbing untuk memprediksi minimal empat diagnosis penyakit paling sering di UPTD Puskesmas Teluk Sasah.</p>
        </div>

        <!-- 4 Disease Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @foreach($topDiseases as $dis)
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
                <div class="w-2 h-full absolute top-0 left-0" style="background-color: {{ $dis['color'] }}"></div>
                <div class="pl-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">ICD-10: {{ $dis['kode'] }}</span>
                    <h3 class="font-extrabold text-slate-900 text-base mt-1 line-clamp-1">{{ $dis['nama'] }}</h3>
                    
                    <div class="mt-4 pt-4 border-t border-slate-100 flex items-end justify-between">
                        <div>
                            <span class="text-[10px] font-semibold text-slate-400 block uppercase">Proyeksi 2026-P5</span>
                            <span class="text-2xl font-extrabold" style="color: {{ $dis['color'] }}">
                                {{ $dis['prediksi'] }} <span class="text-xs font-bold text-slate-500">Jiwa</span>
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-semibold text-slate-400 block uppercase">Model & Akurasi</span>
                            <span class="text-xs font-bold text-slate-700">{{ $dis['model'] }}</span>
                            <span class="text-[10px] font-bold text-emerald-600 block">{{ $dis['mape'] }} MAPE</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Callout Note on Top-Censored Data -->
        <div class="bg-sky-50 border border-sky-200 rounded-2xl p-5 text-sky-900 text-xs flex items-start space-x-3.5">
            <i class="fa-solid fa-circle-info text-medical-600 text-base mt-0.5"></i>
            <div>
                <strong class="font-bold">Catatan Audit Ilmiah Data Morbiditas Tersensor (Top-Censored):</strong>
                <p class="mt-1 text-slate-600 leading-relaxed">
                    Sistem SIMPUS hanya mencatat 10 besar diagnosis penyakit per bulan (450 baris selama 45 bulan). 
                    Penyakit ISPA (J00), Hipertensi (I10), dan Dispepsia (K30) hadir di <strong>45 dari 45 bulan (100%)</strong>. 
                    Sedangkan Diabetes Melitus (E11) hadir di <strong>43 dari 45 bulan</strong> karena sempat tergeser ke peringkat 11–12 pada September 2024 dan Januari 2025 (bukan berarti nol kasus).
                </p>
            </div>
        </div>

    </div>
</section>

<!-- SECTION 5: BENCHMARKING MACHINE LEARNING VS MOVING AVERAGE -->
<section class="py-16 bg-white border-b border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            
            <div class="lg:col-span-5 space-y-5">
                <span class="text-xs font-bold text-purple-600 uppercase tracking-widest bg-purple-50 px-3 py-1 rounded-full border border-purple-200">Validasi Ilmiah 10-Fold</span>
                <h2 class="text-3xl font-extrabold text-navy-950 tracking-tight leading-tight">
                    Benchmarking Machine Learning vs Baseline Moving Average
                </h2>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Eksperimen <em>10-Fold Expanding Window Walk-Forward Validation</em> dilakukan pada panel 148 obat aktif untuk menguji apakah algoritma Machine Learning mampu mengalahkan baseline statistik:
                </p>

                <div class="space-y-3 text-xs">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                        <strong class="font-bold text-slate-800">1. HistGradientBoosting (GBM)</strong>
                        <p class="text-slate-600 mt-0.5">WAPE terendah (53.6%), namun mengalami <strong>bias negatif sistematis (-9.2%)</strong> yang berbahaya karena memicu peramalan terlalu rendah (understock).</p>
                    </div>

                    <div class="p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-200">
                        <strong class="font-bold text-emerald-900">2. WMA (k=3) + Safety Stock 95% (Rekomendasi Utama)</strong>
                        <p class="text-emerald-800 mt-0.5">WAPE 59.5% dengan <strong>bias positif alami (+4.3%)</strong> yang bertindak sebagai bantalan pengaman anti-stockout paling andal dan transparan.</p>
                    </div>
                </div>

                <a href="{{ route('ml.benchmark') }}" class="inline-flex items-center text-xs font-bold text-purple-700 hover:text-purple-800">
                    <span>Buka Rincian Evaluasi 10-Fold Walk-Forward</span>
                    <i class="fa-solid fa-arrow-right ml-1.5 text-[10px]"></i>
                </a>
            </div>

            <!-- Table Benchmark Compact -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 bg-slate-900 text-white flex items-center justify-between">
                        <span class="font-bold text-xs uppercase tracking-wider">Hasil Benchmarking Model</span>
                        <span class="text-[10px] text-slate-400">Panel 148 Obat &bull; 10 Fold</span>
                    </div>
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 text-[10px] uppercase">
                            <tr>
                                <th class="px-4 py-3">Model</th>
                                <th class="px-3 py-3 text-center">Tipe</th>
                                <th class="px-3 py-3 text-right">WAPE</th>
                                <th class="px-3 py-3 text-right">Bias %</th>
                                <th class="px-4 py-3">Karakteristik Operasional</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-[11px]">
                            @foreach($mlBenchmarks as $bm)
                            <tr class="hover:bg-slate-50 {{ str_contains($bm->model_name, 'WMA') ? 'bg-emerald-50/40 font-bold' : '' }}">
                                <td class="px-4 py-3 font-semibold text-slate-900">{{ $bm->model_name }}</td>
                                <td class="px-3 py-3 text-center text-slate-500">{{ $bm->kategori }}</td>
                                <td class="px-3 py-3 text-right font-bold text-slate-800">{{ number_format($bm->wape * 100, 1) }}%</td>
                                <td class="px-3 py-3 text-right font-bold {{ str_contains($bm->bias_percentage, '-') ? 'text-rose-600' : 'text-emerald-600' }}">
                                    {{ $bm->bias_percentage }}
                                </td>
                                <td class="px-4 py-3 text-slate-600 text-[10px] truncate max-w-xs">{{ $bm->interpretasi }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- SECTION 6: TIM PENELITI & DOSEN PEMBIMBING (Meet Our Team Sesuai Desain) -->
<section class="py-16 bg-slate-50 border-b border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-xs font-bold text-medical-600 uppercase tracking-widest bg-medical-50 px-3 py-1 rounded-full border border-medical-200">Tim Pengembang Akademik</span>
            <h2 class="text-3xl font-extrabold text-navy-950 mt-3 tracking-tight">Dosen Pembimbing & Tim Kelompok 23</h2>
            <p class="text-sm text-slate-500 mt-2">Program Studi S1 Teknik Informatika, Fakultas Teknik dan Teknologi Kemaritiman, Universitas Maritim Raja Ali Haji (UMRAH).</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Card 1: Dosen Pembimbing I -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm text-center">
                <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-tr from-medical-600 to-sky-400 text-white flex items-center justify-center text-3xl font-extrabold shadow-md mb-4">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
                <h4 class="font-bold text-slate-900 text-sm">Tekad Matulatan, S.Sos., S.Kom., M.Info.Tech.</h4>
                <p class="text-xs text-medical-600 font-semibold mt-0.5">Dosen Pengampu Utama</p>
                <p class="text-[11px] text-slate-500 mt-2">Memberikan 3 arahan strategis: perbandingan 1 vs 2 bulanan, MA & MACD, serta peramalan 4 penyakit & stok obat.</p>
            </div>

            <!-- Card 2: Dosen Pembimbing II -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm text-center">
                <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-tr from-cyan-600 to-teal-400 text-white flex items-center justify-center text-3xl font-extrabold shadow-md mb-4">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <h4 class="font-bold text-slate-900 text-sm">Muhamad Fadli, S.Kom., M.Kom.</h4>
                <p class="text-xs text-teal-600 font-semibold mt-0.5">Dosen Pengampu Pendamping</p>
                <p class="text-[11px] text-slate-500 mt-2">Membimbing evaluasi komparatif metrik galat deret waktu (MAPE, MAE, RMSE) dan validasi ilmiah.</p>
            </div>

            <!-- Card 3: Lead Developer / Peneliti -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm text-center">
                <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-tr from-indigo-600 to-purple-500 text-white flex items-center justify-center text-3xl font-extrabold shadow-md mb-4">
                    <i class="fa-solid fa-user-gear"></i>
                </div>
                <h4 class="font-bold text-slate-900 text-sm">Nabil (2401020073)</h4>
                <p class="text-xs text-indigo-600 font-semibold mt-0.5">Ketua Tim / Machine Learning Engineer</p>
                <p class="text-[11px] text-slate-500 mt-2">Arsitek pipeline data mining, formula Safety Stock 95%, dan sistem web PRABOTIK.</p>
            </div>

        </div>

    </div>
</section>

<!-- SECTION 7: FAQ & TESTIMONIALS (Sesuai Desain Referensi) -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- Left: Testimonials / Catatan Mitra -->
            <div class="lg:col-span-5 space-y-6">
                <span class="text-xs font-bold text-medical-600 uppercase tracking-widest bg-medical-50 px-3 py-1 rounded-full border border-medical-200">Dampak Nyata Mitra</span>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-navy-950 tracking-tight">Solusi Nyata Logistik Puskesmas</h3>
                
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200/80 relative shadow-xs">
                    <i class="fa-solid fa-quote-left text-3xl text-medical-200 absolute top-4 right-4"></i>
                    <p class="text-xs text-slate-700 leading-relaxed italic relative z-10">
                        "Sebelumnya perhitungan pemesanan LPLPO kerap menghadapi kendala kehabisan stok pada obat antihipertensi dan batuk anak, sementara beberapa obat lain menumpuk hingga kadaluarsa. Dengan peramalan WMA-3 dan buffer Safety Stock 95%, alokasi pengadaan obat menjadi sangat terukur dan rasional diajukan ke dinas."
                    </p>
                    <div class="mt-4 pt-4 border-t border-slate-200/60 flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-full bg-medical-600 text-white flex items-center justify-center font-bold text-xs">
                            IF
                        </div>
                        <div>
                            <h5 class="text-xs font-bold text-slate-900">Instalasi Farmasi Puskesmas</h5>
                            <p class="text-[10px] text-slate-500">Pengelola Obat UPTD Puskesmas Teluk Sasah</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs">
                    <p class="font-bold flex items-center">
                        <i class="fa-solid fa-shield-halved mr-1.5 text-amber-600"></i>
                        Potensi Efisiensi Anggaran:
                    </p>
                    <p class="mt-1 text-slate-600">
                        Mengeliminasi pemborosan obat kadaluarsa baru hingga <strong>Rp 34 Juta / tahun</strong> dari baseline historis Rp 43,35 Juta (2023–2025).
                    </p>
                </div>
            </div>

            <!-- Right: Frequently Asked Questions (FAQ) -->
            <div class="lg:col-span-7 space-y-4">
                <span class="text-xs font-bold text-medical-600 uppercase tracking-widest bg-medical-50 px-3 py-1 rounded-full border border-medical-200">Pertanyaan Umum</span>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-navy-950 tracking-tight">Frequently Asked Questions</h3>

                <div class="space-y-3 pt-2">
                    
                    <div class="border border-slate-200 rounded-2xl p-4 bg-slate-50/50">
                        <h5 class="font-bold text-xs text-slate-900 flex items-center justify-between">
                            <span>Mengapa data diselaraskan ke format 2-bulanan (dwibulanan)?</span>
                            <i class="fa-solid fa-chevron-down text-slate-400 text-xs"></i>
                        </h5>
                        <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                            Format pelaporan resmi LPLPO di Kabupaten Bintan diterbitkan setiap dua bulan (P1 s/d P6). Data bulanan 2024 terbukti memiliki noise variansi tinggi (CV 48.6%) akibat keterlambatan faktur droping dinas, sedangkan dwibulanan meratakan deret waktu secara stabil (CV 24.1%).
                        </p>
                    </div>

                    <div class="border border-slate-200 rounded-2xl p-4 bg-slate-50/50">
                        <h5 class="font-bold text-xs text-slate-900 flex items-center justify-between">
                            <span>Bagaimana cara kerja Safety Stock 95%?</span>
                            <i class="fa-solid fa-chevron-down text-slate-400 text-xs"></i>
                        </h5>
                        <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                            Menggunakan formula standar industri farmasi: <code>SS = Z (1.65) &times; &sigma; &times; &radic;L</code>. Batas pengaman ini menjamin 95% resep pasien tetap terlayani saat terjadi lonjakan kunjungan atau keterlambatan pengiriman obat dari gudang dinas kesehatan.
                        </p>
                    </div>

                    <div class="border border-slate-200 rounded-2xl p-4 bg-slate-50/50">
                        <h5 class="font-bold text-xs text-slate-900 flex items-center justify-between">
                            <span>Mengapa Parasetamol 500 mg dikoreksi permintaannya?</span>
                            <i class="fa-solid fa-chevron-down text-slate-400 text-xs"></i>
                        </h5>
                        <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                            Pada 2026-P4 pemakaian tercatat 0 butir akibat kehabisan stok fisik total (stok awal 0 dan penerimaan 0). Jika dihitung naif, ramalan anjlok menjadi 3.222 butir. Melalui imputasi permintaan tersensor (Censored Demand = 5.850 butir), proyeksi pengadaan dinaikkan menjadi 7.487 butir untuk melindungi pasien dari kekosongan obat.
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>
</section>

<!-- SECTION 8: MITRA INSTITUSI & REGULATOR (Our Partners Sesuai Desain) -->
<section class="py-12 bg-slate-50 border-t border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Didukung & Mengacu Pada Standar Resmi:</p>
        <div class="flex flex-wrap items-center justify-center gap-8 sm:gap-12 opacity-80 grayscale hover:grayscale-0 transition-all">
            <div class="flex items-center space-x-2 font-bold text-slate-700 text-sm">
                <i class="fa-solid fa-building-columns text-xl text-medical-600"></i>
                <span>Kementerian Kesehatan RI</span>
            </div>
            <div class="flex items-center space-x-2 font-bold text-slate-700 text-sm">
                <i class="fa-solid fa-hospital text-xl text-emerald-600"></i>
                <span>Dinas Kesehatan Bintan</span>
            </div>
            <div class="flex items-center space-x-2 font-bold text-slate-700 text-sm">
                <i class="fa-solid fa-hand-holding-medical text-xl text-sky-600"></i>
                <span>BPJS Kesehatan</span>
            </div>
            <div class="flex items-center space-x-2 font-bold text-slate-700 text-sm">
                <i class="fa-solid fa-graduation-cap text-xl text-indigo-600"></i>
                <span>Universitas Maritim Raja Ali Haji</span>
            </div>
        </div>
    </div>
</section>

@endsection
