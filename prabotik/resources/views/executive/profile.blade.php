@extends('layouts.executive')

@section('title', 'Profil Puskesmas & Struktur Organisasi')
@section('page_title', 'Profil UPTD Puskesmas Teluk Sasah')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Title Banner -->
    <div id="profile-banner" class="bg-gradient-to-r from-navy-950 via-slate-900 to-amber-950 rounded-3xl p-6 sm:p-8 text-white shadow-lg scroll-mt-28">
        <span class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-bold border border-amber-500/30 mb-2 inline-block">
            Profil Fasilitas Kesehatan Tingkat Pertama (FKTP)
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">UPTD Puskesmas Teluk Sasah</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl leading-relaxed">
            Faskes Pemerintah Tingkat Pertama di Kecamatan Seri Kuala Lobam, Kabupaten Bintan, Kepulauan Riau yang melayani masyarakat kawasan industri dan pesisir.
        </p>
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
                <p class="text-xs text-slate-500 font-mono">NIP. 19680512 199803 1 004</p>
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
