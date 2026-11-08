@extends('layouts.executive')

@section('title', 'Profil Puskesmas & Struktur Organisasi')
@section('page_title', 'Profil UPTD Puskesmas Teluk Sasah')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Title Banner -->
    <div id="profile-banner" class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 scroll-mt-28">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-full bg-matcha-100 text-matcha-900 text-[10px] font-extrabold uppercase tracking-wider">
                    Fasilitas Kesehatan Tingkat Pertama (FKTP)
                </span>
                <span class="text-xs font-mono text-slate-400">KEMENKES RI</span>
            </div>
            <h1 class="mt-2 text-2xl sm:text-[30px] leading-tight font-extrabold tracking-tight text-ink">
                UPTD Puskesmas Teluk Sasah
            </h1>
            <p class="mt-1 text-xs sm:text-sm text-slate-500 max-w-2xl leading-relaxed">
                Faskes Pemerintah Tingkat Pertama di Kecamatan Seri Kuala Lobam, Kabupaten Bintan, Kepulauan Riau yang melayani masyarakat kawasan industri dan pesisir.
            </p>
        </div>
        
        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('executive.dashboard') }}" class="px-5 py-2.5 rounded-full bg-white hover:bg-matcha-50 text-matcha-900 text-xs font-bold shadow-float border border-slate-200/80 transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-left text-matcha-700"></i>
                <span>Ringkasan Pimpinan</span>
            </a>
            <a href="{{ route('executive.print') }}" target="_blank" class="px-5 py-2.5 rounded-full bg-matcha-700 hover:bg-matcha-800 text-white text-xs font-bold shadow-md shadow-matcha-700/25 transition flex items-center gap-2">
                <i class="fa-solid fa-print"></i>
                <span>Cetak LPLPO Resmi</span>
            </a>
        </div>
    </div>

    <!-- Pimpinan & Pejabat Faskes Grid -->
    <div id="leadership-card" class="grid grid-cols-1 md:grid-cols-2 gap-6 scroll-mt-28">
        
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex items-start space-x-4">
            <div class="w-14 h-14 rounded-2xl bg-amber-600 text-white flex items-center justify-center text-2xl font-bold shrink-0 shadow-md">
                <i class="fa-solid fa-user-doctor"></i>
            </div>
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-amber-600 uppercase">Kepala UPTD Puskesmas / KPA</span>
                <h4 class="font-extrabold text-slate-900 text-base">dr. Bambang Utoyo, M.M.</h4>
                <p class="text-xs text-slate-500 font-mono">NIP. 19780512 200604 1 008</p>
                <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                    Penanggung jawab operasional, otorisator pengesahan rencana pengadaan LPLPO resmi ke Dinas Kesehatan Kabupaten Bintan, serta pimpinan penjaminan mutu akreditasi faskes.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex items-start space-x-4">
            <div class="w-14 h-14 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-2xl font-bold shrink-0 shadow-md">
                <i class="fa-solid fa-prescription-bottle-medical"></i>
            </div>
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-emerald-600 uppercase">Penanggung Jawab Farmasi</span>
                <h4 class="font-extrabold text-slate-900 text-base">Apt. Siti Rahmawati, S.Farm</h4>
                <p class="text-xs text-slate-500 font-mono">SIP. 19890415/SIPA/2018/021</p>
                <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                    Pengelola perbekalan farmasi, penelaah ketersediaan fisik stok opname (SOH), dan penyusun kalkulasi peramalan kebutuhan obat dwibulanan LPLPO.
                </p>
            </div>
        </div>

    </div>

    <!-- Informasi Wilayah Kerja & Layanan -->
    <div id="services-card" class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4 scroll-mt-28">
        <h3 class="font-extrabold text-slate-900 text-sm">Informasi Wilayah Kerja & Pelayanan</h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <span class="text-[10px] text-slate-400 uppercase font-semibold">Wilayah Kerja:</span>
                <p class="font-bold text-slate-900 text-sm mt-1">Kec. Seri Kuala Lobam</p>
                <p class="text-slate-500 mt-0.5">Kabupaten Bintan, Kepulauan Riau</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <span class="text-[10px] text-slate-400 uppercase font-semibold">Layanan Kegawatdaruratan:</span>
                <p class="font-bold text-slate-900 text-sm mt-1">UGD 24 Jam & PSC 119</p>
                <p class="text-slate-500 mt-0.5">Telepon: (0771) 482-123</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <span class="text-[10px] text-slate-400 uppercase font-semibold">Status Akreditasi:</span>
                <p class="font-bold text-emerald-700 text-sm mt-1">Paripurna (Kemenkes RI)</p>
                <p class="text-slate-500 mt-0.5">Fasilitas Pelayanan Kesehatan Tingkat Pertama</p>
            </div>
        </div>
    </div>

</div>
@endsection
