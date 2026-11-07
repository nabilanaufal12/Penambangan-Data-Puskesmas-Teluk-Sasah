@extends('layouts.app')

@section('title', 'Dashboard Eksekutif Kepala Puskesmas & Admin')
@section('page_title', 'Dashboard Eksekutif Kepala Puskesmas & Manajemen')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- ROLE SWITCHER & ACCOUNT IDENTITY BAR -->
    <div class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center space-x-3.5">
            <div class="w-12 h-12 rounded-2xl bg-amber-600 text-white flex items-center justify-center text-xl shadow-md shadow-amber-600/30 shrink-0">
                <i class="fa-solid fa-user-doctor"></i>
            </div>
            <div>
                <div class="flex items-center space-x-2">
                    <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-extrabold uppercase border border-amber-200">
                        Akun Pimpinan Faskes
                    </span>
                    <span class="text-xs text-slate-400 font-mono">NIP. 19780512 200604 1 008</span>
                </div>
                <h2 class="text-base sm:text-lg font-black text-slate-900 mt-0.5">
                    dr. Bambang Utoyo, M.M.
                </h2>
                <p class="text-xs text-slate-500">
                    Kepala UPTD Puskesmas Teluk Sasah &bull; Kuasa Pengguna Anggaran (KPA)
                </p>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-2 w-full md:w-auto pt-2 md:pt-0 border-t md:border-t-0 border-slate-100">
            <span class="text-xs text-slate-400 font-medium hidden sm:inline mr-1">Beralih Peran:</span>
            <a href="{{ route('user.switch', 'farmasi') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center space-x-1.5">
                <i class="fa-solid fa-prescription-bottle-medical text-emerald-600"></i>
                <span>&larr; Apt. Siti Rahmawati (Farmasi)</span>
            </a>
            <a href="{{ route('dashboard.executive') }}" class="px-4 py-2 rounded-xl bg-amber-600 text-white text-xs font-bold shadow-md shadow-amber-600/20 flex items-center space-x-1.5">
                <i class="fa-solid fa-circle-check"></i>
                <span>Kepala Puskesmas</span>
            </a>
        </div>
    </div>

    <!-- PANDUAN WEWENANG: APA SAJA YANG BISA DILAKUKAN KEPALA PUSKESMAS -->
    <div class="bg-amber-50/70 border border-amber-200 rounded-3xl p-5 sm:p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-amber-200/80">
            <div class="flex items-center space-x-2.5 text-amber-950 font-black text-sm">
                <i class="fa-solid fa-stamp text-amber-600 text-base"></i>
                <span>Wewenang & Hak Akses Akun Kepala Puskesmas (dr. Bambang Utoyo)</span>
            </div>
            <span class="text-[11px] font-bold text-amber-800 bg-white/80 px-3 py-1 rounded-full border border-amber-300">
                Akses Otorisasi & Eksekutif (Level Pimpinan)
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
            <div class="space-y-2 bg-white/80 p-3.5 rounded-2xl border border-amber-100">
                <span class="font-extrabold text-amber-900 block flex items-center space-x-1.5">
                    <i class="fa-solid fa-check text-amber-600"></i>
                    <span>Kewenangan Strategis yang BISA Dilakukan:</span>
                </span>
                <ul class="space-y-1.5 text-slate-700 text-[11px] list-disc list-inside">
                    <li><strong>Disposisi & Pengesahan Usulan LPLPO:</strong> Memberikan persetujuan resmi atas 184.077 unit obat yang diajukan bagian farmasi.</li>
                    <li><strong>Pengawasan SPM Kemenkes:</strong> Memantau indeks ketersediaan obat pelayanan dasar faskes (target $\ge 80\%$, capaian 91,3%).</li>
                    <li><strong>Audit Akuntabilitas Kerugian ED:</strong> Memeriksa riwayat kerugian obat kadaluarsa/rusak (Rp 43,35 Jt dalam 3 tahun dari APBD/DAK) untuk audit BPKP.</li>
                    <li><strong>Analisis Morbiditas Penyakit SIMPUS:</strong> Menggunakan data epidemiologi 10 penyakit terbesar untuk penyusunan rencana intervensi puskesmas.</li>
                    <li><strong>Cetak Dokumen LPLPO Resmi Ber-NIP:</strong> Mengunduh dokumen final LPLPO ber-NIP resmi untuk dikirim ke Kepala Dinas Kesehatan Kabupaten Bintan.</li>
                </ul>
            </div>

            <div class="space-y-2 bg-white/80 p-3.5 rounded-2xl border border-slate-200">
                <span class="font-extrabold text-slate-800 block flex items-center space-x-1.5">
                    <i class="fa-solid fa-shield text-slate-500"></i>
                    <span>Prinsip Pembagian Tugas (De-konsentrasi):</span>
                </span>
                <ul class="space-y-1.5 text-slate-600 text-[11px] list-disc list-inside">
                    <li><strong class="text-slate-800">Tidak Menginput Teknis Transaksi:</strong> Pencatatan butir obat, kartu stok gudang, dan stok opname fisik didelegasikan penuh kepada <strong>Apt. Siti Rahmawati</strong>.</li>
                    <li><strong class="text-slate-800">Fokus Pengawasan & Evaluasi:</strong> Kepala Puskesmas fokus menelaah efisiensi anggaran, mencegah keterlambatan suplai dinas, dan menandatangani disposisi resmi.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- MAIN EXECUTIVE HERO BANNER -->
    <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-amber-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-slate-800">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-bold border border-amber-500/30">
                    <i class="fa-solid fa-building-columns mr-1"></i>Panel Pengawasan & Pengambilan Keputusan Strategis
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    Ringkasan Eksekutif Tata Kelola Faskes & Logistik
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 max-w-3xl leading-relaxed">
                    Menyajikan indikator makro ketersediaan obat pelayanan dasar Kemenkes, efisiensi anggaran pengadaan, pengendalian kerugian obat kadaluarsa, beban morbiditas penyakit SIMPUS, serta disposisi pengesahan lembar LPLPO resmi UPTD Puskesmas Teluk Sasah.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('lplpo.print') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold shadow-md shadow-amber-500/30 transition flex items-center">
                    <i class="fa-solid fa-file-signature mr-2"></i>
                    <span>Tinjau & Sahkan LPLPO</span>
                </a>
                <a href="{{ route('diseases') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition flex items-center">
                    <i class="fa-solid fa-chart-pie mr-2 text-amber-300"></i>
                    <span>Analisis Morbiditas</span>
                </a>
            </div>
        </div>

        <!-- 4 EXECUTIVE KPI SCORECARDS -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-8 pt-6 border-t border-slate-800/80">
            
            <!-- KPI 1: Ketersediaan Obat Pelayanan Dasar -->
            <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] text-slate-400 uppercase font-semibold">Tingkat Ketersediaan Obat</span>
                    <i class="fa-solid fa-shield-halved text-emerald-400 text-sm"></i>
                </div>
                <p class="text-2xl font-black text-emerald-400 mt-1">{{ $kpis['ketersediaan_pct'] }}%</p>
                <span class="text-[10px] text-slate-300 mt-1 block">Target Kemenkes: &ge; 80% (Terpenuhi)</span>
            </div>

            <!-- KPI 2: Pasien Terlayani SIMPUS -->
            <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] text-slate-400 uppercase font-semibold">Pasien Terlayani (SIMPUS)</span>
                    <i class="fa-solid fa-hospital-user text-cyan-400 text-sm"></i>
                </div>
                <p class="text-2xl font-black text-cyan-400 mt-1">{{ number_format($kpis['total_pasien'], 0, ',', '.') }}</p>
                <span class="text-[10px] text-slate-300 mt-1 block">Akumulasi 22 Periode Faskes</span>
            </div>

            <!-- KPI 3: Estimasi Belanja Pengadaan P5 -->
            <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] text-slate-400 uppercase font-semibold">Usulan Pengadaan P5</span>
                    <i class="fa-solid fa-receipt text-amber-400 text-sm"></i>
                </div>
                <p class="text-2xl font-black text-amber-400 mt-1">Rp {{ number_format($kpis['estimasi_anggaran'] / 1000000, 1, ',', '.') }} Jt</p>
                <span class="text-[10px] text-slate-300 mt-1 block">{{ number_format($kpis['total_order_unit'], 0, ',', '.') }} Unit Sediaan</span>
            </div>

            <!-- KPI 4: Akumulasi Kerugian Obat ED -->
            <div class="bg-white/5 rounded-2xl p-4 border border-rose-500/20 bg-rose-500/5">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] text-rose-300 uppercase font-semibold">Kerugian Obat Rusak / ED</span>
                    <i class="fa-solid fa-sack-xmark text-rose-400 text-sm"></i>
                </div>
                <p class="text-2xl font-black text-rose-400 mt-1">Rp {{ number_format($kpis['total_ed_loss'] / 1000000, 2, ',', '.') }} Jt</p>
                <span class="text-[10px] text-rose-300 mt-1 block">Audit 3 Tahun (APBD/DAK)</span>
            </div>

        </div>
    </div>

    <!-- 2 MAIN EXECUTIVE SECTIONS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Lembar Disposisi & Pengesahan Usulan LPLPO P5 -->
        <div class="lg:col-span-7 space-y-6">
            
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-slate-100">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2.5 py-1 rounded-lg bg-amber-100 text-amber-800 text-[10px] font-extrabold uppercase">
                                Dokumen Otorisasi
                            </span>
                            <span class="text-xs text-slate-400 font-mono">LPLPO-TS-2026-P5</span>
                        </div>
                        <h2 class="text-lg font-black text-slate-900 mt-1.5">
                            Lembar Disposisi Pengesahan Pengadaan Obat P5 (2026)
                        </h2>
                    </div>
                    <span class="px-3 py-1.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold inline-flex items-center shrink-0">
                        <i class="fa-solid fa-clock-rotate-left mr-1.5"></i>
                        <span>Menunggu Disposisi Kapus</span>
                    </span>
                </div>

                <!-- 6 Item Pengadaan Terbesar yang Menuntut Persetujuan Kapus -->
                <div>
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">
                        6 Alokasi Terbesar yang Diajukan Bagian Farmasi:
                    </h3>
                    <div class="overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-[10px] border-b border-slate-200">
                                <tr>
                                    <th class="py-2.5 px-3">Nama Sediaan</th>
                                    <th class="py-2.5 px-3 text-right">Sisa Stok</th>
                                    <th class="py-2.5 px-3 text-right">Rekomendasi</th>
                                    <th class="py-2.5 px-3">Indikasi Utama</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                @foreach($topProcurement as $tp)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-2.5 px-3">
                                        <div class="font-bold text-slate-900">{{ $tp->nama_obat }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $tp->satuan }}</div>
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-800">
                                        {{ number_format($tp->sisa_stok, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-mono font-black text-amber-600">
                                        {{ number_format($tp->rekomendasi_total, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 px-3 text-[11px] text-slate-600">
                                        @if(str_contains(strtolower($tp->nama_obat), 'fero'))
                                        Program Bumil & Anemia
                                        @elseif(str_contains(strtolower($tp->nama_obat), 'amlodipin'))
                                        Penyakit Hipertensi (I10)
                                        @elseif(str_contains(strtolower($tp->nama_obat), 'parasetamol'))
                                        Antipiretik ISPA (J00)
                                        @elseif(str_contains(strtolower($tp->nama_obat), 'metformin'))
                                        Diabetes Melitus (E11)
                                        @else
                                        Analgesik / Alergi
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Signatory & Legal Disposition Box -->
                <div class="bg-slate-50 rounded-2xl p-4 sm:p-5 border border-slate-200 space-y-4">
                    @if($kpis['is_approved'])
                    <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-300 flex items-center space-x-3 text-emerald-900 text-xs shadow-xs">
                        <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold shrink-0 shadow-sm">
                            <i class="fa-solid fa-stamp text-base"></i>
                        </div>
                        <div class="flex-1">
                            <span class="font-black text-emerald-950 block text-xs">DOKUMEN RESMI TELAH DISAHKAN OLEH KEPALA PUSKESMAS</span>
                            <span class="text-[11px] text-emerald-800">Tanda tangan digital dr. Bambang Utoyo, M.M. telah tersemat dan siap dikirim ke Dinas Kesehatan Kabupaten Bintan.</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-emerald-600 text-white text-[10px] font-black uppercase">
                            Disahkan
                        </span>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-3 bg-white rounded-xl border border-slate-200">
                            <span class="text-[10px] text-slate-400 block uppercase font-semibold">Petugas Penelaah Farmasi:</span>
                            <span class="font-bold text-slate-900 text-sm mt-0.5 block">{{ $kpis['pengelola_nama'] }}</span>
                            <span class="text-[10px] text-slate-500 font-mono">{{ $kpis['pengelola_sip'] }}</span>
                            <span class="inline-block mt-2 px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200">
                                Selesai Verifikasi Stok Opname
                            </span>
                        </div>

                        <div class="p-3 bg-white rounded-xl border {{ $kpis['is_approved'] ? 'border-emerald-300 bg-emerald-50/40' : 'border-amber-200' }}">
                            <span class="text-[10px] text-amber-600 block uppercase font-semibold">Kepala UPTD Puskesmas Teluk Sasah:</span>
                            <span class="font-bold text-slate-900 text-sm mt-0.5 block">{{ $kpis['kapus_nama'] }}</span>
                            <span class="text-[10px] text-slate-500 font-mono">{{ $kpis['kapus_nip'] }}</span>
                            @if($kpis['is_approved'])
                            <span class="inline-block mt-2 px-2 py-0.5 rounded bg-emerald-600 text-white text-[10px] font-bold shadow-xs">
                                <i class="fa-solid fa-stamp mr-1"></i>Telah Disahkan Secara Digital
                            </span>
                            @else
                            <span class="inline-block mt-2 px-2 py-0.5 rounded bg-amber-50 text-amber-700 text-[10px] font-bold border border-amber-200">
                                Menunggu Otorisasi Kepala Faskes
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-slate-200">
                        <p class="text-[11px] text-slate-500 leading-snug">
                            Dengan mengesahkan dokumen ini, rencana kebutuhan obat resmi diteruskan ke Dinas Kesehatan Kabupaten Bintan.
                        </p>
                        <div class="flex items-center space-x-2 shrink-0 w-full sm:w-auto">
                            <a href="{{ route('lplpo.print') }}" target="_blank" class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition flex items-center justify-center space-x-1.5 shadow-sm">
                                <i class="fa-solid fa-print"></i>
                                <span>Cetak LPLPO Resmi</span>
                            </a>
                            
                            @if(!$kpis['is_approved'])
                            <form method="POST" action="{{ route('procurement.approve') }}" class="flex-1 sm:flex-none">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-600/30 transition flex items-center justify-center space-x-1.5">
                                    <i class="fa-solid fa-stamp"></i>
                                    <span>Setujui Usulan P5 (Sahkan)</span>
                                </button>
                            </form>
                            @else
                            <span class="px-4 py-2.5 rounded-xl bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center space-x-1.5 border border-emerald-300">
                                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                                <span>Usulan Telah Disahkan</span>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <!-- Evaluasi Akumulasi Kerugian Obat Kadaluarsa (ED) -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-chart-line-down"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-sm">Tren Akuntabilitas Kerugian Obat ED (3 Tahun)</h3>
                            <p class="text-[11px] text-slate-500">Evaluasi efisiensi pengadaan anggaran APBD & DAK Farmasi</p>
                        </div>
                    </div>
                    <a href="{{ route('audit.stock') }}" class="text-[11px] font-bold text-rose-600 hover:text-rose-700">
                        Buka Audit Rinci &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    @foreach($edLossByYear as $ey)
                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 text-center">
                        <span class="text-[11px] font-bold text-slate-400 block uppercase">Tahun {{ $ey->tahun }}</span>
                        <span class="text-base font-black text-rose-600 font-mono mt-1 block">Rp {{ number_format($ey->total_loss, 0, ',', '.') }}</span>
                        <span class="text-[10px] text-slate-500 mt-0.5 block">{{ $ey->count }} Berita Acara ED</span>
                    </div>
                    @endforeach
                </div>

                <div class="p-3.5 bg-amber-50 rounded-xl border border-amber-200 text-xs text-amber-900 space-y-1">
                    <div class="font-bold flex items-center space-x-1.5">
                        <i class="fa-solid fa-lightbulb text-amber-600"></i>
                        <span>Rekomendasi Kebijakan Kepala Puskesmas:</span>
                    </div>
                    <p class="text-[11px] text-amber-800 leading-relaxed">
                        Lonjakan obat kadaluarsa pada tahun 2025 (Rp 21,8 Juta) dipicu oleh pengiriman drop alokasi sediaan slow-moving dari dinas dengan masa simpan pendek. Diperlukan penegakan sistem rotasi FEFO dan koordinasi retur 6 bulan sebelum tanggal kadaluarsa.
                    </p>
                </div>
            </div>

        </div>

        <!-- Right: Beban Morbiditas SIMPUS & Korelasi Pelayanan -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Top 5 Penyakit SIMPUS -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-xl bg-cyan-100 text-cyan-700 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-heart-pulse"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-sm">Profil Beban 5 Penyakit Terbanyak</h3>
                            <p class="text-[11px] text-slate-500">Rekapitulasi SIMPUS Wilayah Kerja Teluk Sasah</p>
                        </div>
                    </div>
                    <a href="{{ route('diseases') }}" class="text-[11px] font-bold text-cyan-600 hover:text-cyan-700">
                        SIMPUS &rarr;
                    </a>
                </div>

                <div class="space-y-4">
                    @foreach($topDiseases as $td)
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-800">
                                <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-700 font-mono text-[10px] mr-1">{{ $td->kode_icd }}</span>
                                {{ $td->nama_penyakit }}
                            </span>
                            <span class="font-mono font-bold text-slate-900">
                                {{ number_format($td->total_pasien, 0, ',', '.') }} <span class="text-[10px] text-slate-400">({{ $td->persentase }}%)</span>
                            </span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 {{ $loop->index == 0 ? 'bg-rose-500' : ($loop->index == 1 ? 'bg-medical-500' : ($loop->index == 2 ? 'bg-emerald-500' : ($loop->index == 3 ? 'bg-amber-500' : 'bg-cyan-500'))) }}" style="width: {{ $td->persentase }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 text-xs text-slate-600">
                    <span class="font-bold text-slate-800 block mb-0.5">Catatan Epidemiologi Wilayah:</span>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        ISPA (34,8%) dan Hipertensi (24,0%) mendominasi 58,8% total beban kunjungan poli rawat jalan, menegaskan bahwa suplai Parasetamol dan Amlodipin tidak boleh terputus sama sekali.
                    </p>
                </div>
            </div>

            <!-- Rasio Kepatuhan Standar Terapi & Formularium -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-notes-medical"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-sm">Kepatuhan Standar Terapi Morbiditas</h3>
                        <p class="text-[11px] text-slate-500">Rasio Kesesuaian Diagnosis dengan Terapi Obat</p>
                    </div>
                </div>

                <div class="space-y-3">
                    @foreach($correlations as $c)
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between text-xs">
                        <div>
                            <span class="font-bold text-slate-900 block truncate max-w-[200px]">{{ $c->nama_obat }}</span>
                            <span class="text-[10px] text-slate-500">{{ $c->nama_penyakit }} ({{ $c->kode_icd }})</span>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Rasio {{ $c->rasio_resep_per_pasien }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="pt-2 text-center">
                    <a href="{{ route('correlation') }}" class="text-xs font-bold text-purple-700 hover:text-purple-800">
                        Lihat Seluruh Analisis Korelasi &rarr;
                    </a>
                </div>
            </div>

            <!-- Profil Legalitas Puskesmas Teluk Sasah -->
            <div class="bg-gradient-to-br from-navy-950 to-slate-900 rounded-3xl p-6 text-white space-y-3 border border-slate-800">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-amber-400 text-lg">
                        <i class="fa-solid fa-hospital"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-sm text-white">UPTD Puskesmas Teluk Sasah</h4>
                        <p class="text-[10px] text-slate-400">Kecamatan Seri Kuala Lobam, Kab. Bintan</p>
                    </div>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed pt-1">
                    Faskes Tingkat Pertama (FKTP) Akreditasi Paripurna melayani masyarakat wilayah industri dan pesisir Lobam - Teluk Sasah.
                </p>
                <div class="pt-2 border-t border-slate-800 flex items-center justify-between text-xs text-slate-400">
                    <span>Telepon UGD: (0771) 482-123</span>
                    <a href="{{ route('about') }}" class="text-amber-300 hover:text-amber-200 font-bold">Profil Faskes &rarr;</a>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
