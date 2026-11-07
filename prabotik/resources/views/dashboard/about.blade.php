@extends('layouts.app')

@section('title', 'Tim Peneliti & Faskes')
@section('page_title', 'Profil Tim & Puskesmas Teluk Sasah')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Title Banner -->
    <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-medical-950 rounded-3xl p-6 sm:p-8 text-white shadow-lg mb-8">
        <span class="px-3 py-1 rounded-full bg-medical-500/20 text-medical-300 text-xs font-bold border border-medical-500/30 mb-2 inline-block">
            Mata Kuliah Penambangan Data (INF11121)
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Tim Peneliti Akademik & Profil Faskes</h1>
        <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl leading-relaxed">
            Kolaborasi riset data science farmasi antara Program Studi S1 Teknik Informatika UMRAH dengan UPTD Puskesmas Teluk Sasah, Kabupaten Bintan.
        </p>
    </div>

    <!-- Dosen Pengampu Grid -->
    <div class="mb-12">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Dosen Pengampu Mata Kuliah:</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-start space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-medical-600 to-sky-400 text-white flex items-center justify-center text-2xl font-bold shrink-0 shadow-md">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-base">Tekad Matulatan, S.Sos., S.Kom., M.Info.Tech.</h4>
                    <span class="text-xs font-semibold text-medical-600">Dosen Pengampu Utama</span>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                        Pengarah tiga saran perbaikan penelitian: penyelarasan granularitas waktu 1 vs 2 bulanan, eksplorasi algoritma Moving Average & MACD, serta korelasi dan peramalan 4 penyakit utama dan stok obat.
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-start space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-cyan-600 to-teal-400 text-white flex items-center justify-center text-2xl font-bold shrink-0 shadow-md">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-base">Muhamad Fadli, S.Kom., M.Kom.</h4>
                    <span class="text-xs font-semibold text-teal-600">Dosen Pengampu Pendamping</span>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                        Pembimbing teknis implementasi penambangan data, evaluasi metrik galat (MAPE, MAE, RMSE), dan metodologi penelitian ilmiah.
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- Mahasiswa Kelompok 23 Grid -->
    <div class="mb-12">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Mahasiswa Pelaksana (Kelompok 23 - Kelas TI24):</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
            
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm text-center">
                <div class="w-14 h-14 mx-auto rounded-full bg-medical-100 text-medical-700 flex items-center justify-center text-xl font-bold mb-3">
                    N
                </div>
                <h5 class="font-bold text-slate-900 text-sm">Nabil</h5>
                <span class="text-[11px] font-mono text-slate-500 block">NIM: 2401020073</span>
                <span class="mt-2 inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-medical-50 text-medical-700 border border-medical-200">
                    Ketua / ML Lead
                </span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm text-center">
                <div class="w-14 h-14 mx-auto rounded-full bg-sky-100 text-sky-700 flex items-center justify-center text-xl font-bold mb-3">
                    B
                </div>
                <h5 class="font-bold text-slate-900 text-sm">Balqis Manisa</h5>
                <span class="text-[11px] font-mono text-slate-500 block">NIM: 2401020069</span>
                <span class="mt-2 inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200">
                    Anggota / Data Analyst
                </span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm text-center">
                <div class="w-14 h-14 mx-auto rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xl font-bold mb-3">
                    J
                </div>
                <h5 class="font-bold text-slate-900 text-sm">Jian Safitri Wibowo</h5>
                <span class="text-[11px] font-mono text-slate-500 block">NIM: 2401020056</span>
                <span class="mt-2 inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    Anggota / Research
                </span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm text-center">
                <div class="w-14 h-14 mx-auto rounded-full bg-rose-100 text-rose-700 flex items-center justify-center text-xl font-bold mb-3">
                    A
                </div>
                <h5 class="font-bold text-slate-900 text-sm">Annisa Darmawan</h5>
                <span class="text-[11px] font-mono text-slate-500 block">NIM: 2401020087</span>
                <span class="mt-2 inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                    Anggota / UI/UX
                </span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm text-center">
                <div class="w-14 h-14 mx-auto rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl font-bold mb-3">
                    Y
                </div>
                <h5 class="font-bold text-slate-900 text-sm">Yehezkiel Alman S.</h5>
                <span class="text-[11px] font-mono text-slate-500 block">NIM: 2401020110</span>
                <span class="mt-2 inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Anggota / Data Prep
                </span>
            </div>

        </div>
    </div>

    <!-- Profil Faskes Card -->
    <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between pb-6 border-b border-slate-100 gap-4">
            <div>
                <span class="text-xs font-bold text-medical-600 uppercase tracking-widest">Mitra Pengambilan Data & Studi Kasus</span>
                <h3 class="text-2xl font-extrabold text-navy-950 mt-1">UPTD Puskesmas Teluk Sasah</h3>
                <p class="text-xs text-slate-500">Dinas Kesehatan Pemerintah Kabupaten Bintan, Provinsi Kepulauan Riau</p>
            </div>
            <div class="px-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 font-semibold">
                Kode Faskes Kemenkes: <span class="font-bold text-slate-900">1020584</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 text-xs text-slate-600">
            <div>
                <strong class="font-bold text-slate-900 block mb-1">Wilayah Kerja:</strong>
                <p>Meliputi Desa Teluk Sasah, Desa Busung, dan kawasan industri Bintan Industrial Estate (BIE) Lobam, Kecamatan Seri Kuala Lobam.</p>
            </div>
            <div>
                <strong class="font-bold text-slate-900 block mb-1">Populasi & Karakteristik:</strong>
                <p>Kombinasi masyarakat pesisir maritim dan pekerja migran industri dengan pola penyakit dominan ISPA dan hipertensi akibat stres lingkungan dan cuaca pesisir.</p>
            </div>
            <div>
                <strong class="font-bold text-slate-900 block mb-1">Siklus LPLPO Resmi:</strong>
                <p>Pengusulan dan pelaporan logistik dilakukan secara dwibulanan (Jan-Feb, Mar-Apr, Mei-Jun, Jul-Agu, Sep-Okt, Nov-Des) ke Dinas Kesehatan Kabupaten Bintan.</p>
            </div>
        </div>
    </div>

</div>
@endsection
