<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PRABOTIK &bull; Portal Terpadu UPTD Puskesmas Teluk Sasah</title>

    <!-- Tailwind CSS (CDN) + palet Matcha & Sage -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        const matcha = {
            50:  '#F3F8EE', 100: '#E7F5DC', 200: '#CFE1B9', 300: '#B6C99B', 400: '#98A77C',
            500: '#88976C', 600: '#728156', 700: '#5C6A44', 800: '#3E4A2F', 900: '#1A3B22', 950: '#142416'
        };
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                    colors: {
                        matcha: matcha,
                        emerald: matcha,
                        sage: '#F4F6F2',
                        ink: '#142416',
                        navy: { 800: '#1e293b', 900: '#0f172a', 950: '#020617' }
                    },
                    boxShadow: {
                        float: '0 8px 30px rgb(0 0 0 / 0.04)',
                        'float-lg': '0 18px 50px rgb(20 36 22 / 0.10)'
                    }
                }
            }
        }
    </script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; background-color: #F4F6F2; }
        .tnum { font-variant-numeric: tabular-nums; }
        a:focus-visible { outline: 2px solid #728156; outline-offset: 3px; }
    </style>
</head>
<body class="min-h-full bg-sage text-ink antialiased selection:bg-matcha-200 selection:text-matcha-950 relative overflow-x-hidden flex flex-col">

@php
    // Angka ringkasan dari controller (dengan nilai cadangan agar halaman tetap tampil)
    $gTotalObat   = number_format($stats['total_obat'] ?? 0, 0, ',', '.');
    $gTotalPasien = number_format($stats['total_pasien'] ?? 0, 0, ',', '.');
    $gKerugian    = number_format(($stats['total_kerugian_ed'] ?? 0) / 1000000, 2, ',', '.');
    $gUnit        = number_format($stats['total_usulan_unit'] ?? 0, 0, ',', '.');
    $gApproved    = (bool) ($stats['is_approved'] ?? false);
@endphp

    <!-- Dekorasi latar lembut -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
        <div class="absolute -top-40 -left-32 w-[28rem] h-[28rem] rounded-full bg-matcha-200/50 blur-3xl"></div>
        <div class="absolute top-1/3 -right-40 w-[26rem] h-[26rem] rounded-full bg-matcha-100 blur-3xl"></div>
        <div class="absolute -bottom-48 left-1/3 w-[28rem] h-[28rem] rounded-full bg-matcha-300/30 blur-3xl"></div>
    </div>

    <!-- Header instansi -->
    <header class="relative z-10 px-4 sm:px-6 lg:px-8 pt-5">
        <div class="max-w-7xl mx-auto bg-white/90 backdrop-blur rounded-full shadow-float px-4 sm:px-5 py-2.5 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-matcha-600 text-white flex items-center justify-center shadow-md shadow-matcha-600/25">
                    <i class="fa-solid fa-hospital text-lg"></i>
                </div>
                <div class="leading-tight">
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-lg tracking-wide text-ink">PRA<span class="text-matcha-600">BOTIK</span></span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-matcha-100 text-matcha-800">Versi 2.4 Faskes</span>
                    </div>
                    <p class="text-[11px] text-slate-500 font-medium">UPTD Puskesmas Teluk Sasah &bull; Dinas Kesehatan Kab. Bintan</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-matcha-50 text-matcha-800 font-semibold">
                    <span class="w-2 h-2 rounded-full bg-matcha-600 animate-pulse"></span>Database Terhubung (SQLite)
                </span>
                <span class="hidden md:inline px-3.5 py-1.5 rounded-full bg-matcha-50 font-mono text-matcha-800 font-semibold">Periode 2026-P5</span>
            </div>
        </div>
    </header>

    <!-- Pilihan portal -->
    <main class="relative z-10 flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-10">

        <!-- Flash Notification Banners -->
        @if(session('error'))
            <div class="max-w-3xl mx-auto p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-start gap-3 shadow-float animate-pulse">
                <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-lock text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-rose-900">Akses Terproteksi / Peringatan Keamanan</p>
                    <p class="text-xs text-rose-700 mt-0.5 leading-relaxed">{{ session('error') }}</p>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-700 text-xs p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('info'))
            <div class="max-w-3xl mx-auto p-4 rounded-2xl bg-white border border-matcha-200 text-slate-700 text-sm flex items-start gap-3 shadow-float">
                <div class="w-8 h-8 rounded-xl bg-matcha-100 text-matcha-700 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-circle-info text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-semibold text-slate-700 mt-1.5">{{ session('info') }}</p>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-700 text-xs p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('success'))
            <div class="max-w-3xl mx-auto p-4 rounded-2xl bg-matcha-50 border border-matcha-200 text-matcha-900 text-sm flex items-start gap-3 shadow-float">
                <div class="w-8 h-8 rounded-xl bg-matcha-600 text-white flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-semibold mt-1.5">{{ session('success') }}</p>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-matcha-600 hover:text-matcha-800 text-xs p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        <div class="text-center max-w-3xl mx-auto space-y-3">
            <span class="px-4 py-1.5 rounded-full bg-white text-matcha-800 text-xs font-bold shadow-float inline-flex items-center gap-2">
                <i class="fa-solid fa-layer-group text-matcha-500"></i><span>Gerbang Akses Terpisah Sistem Faskes</span>
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-ink leading-[1.1]">
                Pilih Portal <span class="text-matcha-600">PRABOTIK</span>
            </h1>
            <p class="text-sm sm:text-base text-slate-500 leading-relaxed">
                Aplikasi dipisahkan berdasarkan peran dan wewenang kedinasan. Silakan pilih portal kerja Anda di bawah ini.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">

            <!-- KARTU 1: PORTAL FARMASI -->
            <section class="group relative bg-white rounded-[32px] p-7 sm:p-8 shadow-float hover:shadow-float-lg transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between" aria-labelledby="judul-farmasi">
                <div class="space-y-6">
                    <div class="flex items-center justify-between gap-3">
                        <div class="w-14 h-14 rounded-2xl bg-matcha-100 text-matcha-700 flex items-center justify-center text-2xl group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-prescription-bottle-medical"></i>
                        </div>
                        <span class="px-3.5 py-1.5 rounded-full text-[11px] font-bold bg-matcha-100 text-matcha-800">Akses Gudang &amp; Logistik</span>
                    </div>

                    <div>
                        <h2 id="judul-farmasi" class="text-2xl font-extrabold text-ink">Portal Bagian Farmasi</h2>
                        <div class="mt-3 p-4 rounded-2xl bg-matcha-50 text-xs space-y-1.5">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-slate-500">Pengguna Resmi</span>
                                <span class="font-bold text-ink text-right">Apt. Siti Rahmawati, S.Farm</span>
                            </div>
                            <div class="flex items-center justify-between gap-3 text-[11px]">
                                <span class="text-slate-500">Jabatan</span>
                                <span class="text-slate-700 text-right">Pengelola Obat Puskesmas</span>
                            </div>
                            <div class="flex items-center justify-between gap-3 text-[10px] font-mono">
                                <span class="text-slate-500 font-sans text-[11px]">Nomor SIP</span>
                                <span class="text-matcha-700 font-semibold text-right">SIP. 19890415/SIPA/2018/021</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        <p class="font-bold text-slate-400 text-[11px] uppercase tracking-[0.12em]">Fitur &amp; Wewenang Kerja</p>
                        <ul class="space-y-2 text-[12px] text-slate-600">
                            <li class="flex items-start gap-2.5"><i class="fa-solid fa-circle-check text-matcha-500 mt-0.5"></i><span>Audit &amp; monitoring stok fisik (SOH gudang)</span></li>
                            <li class="flex items-start gap-2.5"><i class="fa-solid fa-circle-check text-matcha-500 mt-0.5"></i><span>Pusat entri data (stok LPLPO, kasus SIMPUS, ED)</span></li>
                            <li class="flex items-start gap-2.5"><i class="fa-solid fa-circle-check text-matcha-500 mt-0.5"></i><span>Penyusunan usulan order P5 (WMA &amp; 95% SS)</span></li>
                            <li class="flex items-start gap-2.5"><i class="fa-solid fa-circle-check text-matcha-500 mt-0.5"></i><span>Deteksi momentum permintaan obat (MACD)</span></li>
                            <li class="flex items-start gap-2.5"><i class="fa-solid fa-circle-check text-matcha-500 mt-0.5"></i><span>Cetak lembar kerja verifikasi fisik LPLPO</span></li>
                        </ul>
                    </div>
                </div>

                <div class="pt-8">
                    <form method="POST" action="{{ route('login.pharmacy') }}">
                        @csrf
                        <input type="hidden" name="password" value="farmasi123">
                        <button type="submit"
                                class="w-full py-4 px-6 rounded-full bg-matcha-600 hover:bg-matcha-700 text-white font-extrabold text-sm shadow-lg shadow-matcha-600/25 transition flex items-center justify-center gap-2 cursor-pointer group-hover:scale-[1.01]">
                            <span>Buka Aplikasi Farmasi</span><i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </form>
                </div>
            </section>

            <!-- KARTU 2: PORTAL KEPALA PUSKESMAS (kartu aksen hijau tua) -->
            <section class="group relative overflow-hidden rounded-[32px] p-7 sm:p-8 text-white shadow-float-lg hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between"
                     style="background: linear-gradient(150deg, #1A3B22 0%, #2A4A2E 100%);" aria-labelledby="judul-kepala">
                <div class="absolute inset-0 opacity-[0.10] pointer-events-none"
                     style="background-image: radial-gradient(#CFE1B9 1px, transparent 1px); background-size: 16px 16px;" aria-hidden="true"></div>

                <div class="relative space-y-6">
                    <div class="flex items-center justify-between gap-3">
                        <div class="w-14 h-14 rounded-2xl bg-white text-matcha-900 flex items-center justify-center text-2xl group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                        <span class="px-3.5 py-1.5 rounded-full text-[11px] font-bold bg-matcha-200 text-matcha-900">Akses Eksekutif &amp; Kebijakan</span>
                    </div>

                    <div>
                        <h2 id="judul-kepala" class="text-2xl font-extrabold text-white">Portal Kepala Puskesmas</h2>
                        <div class="mt-3 p-4 rounded-2xl bg-white/10 text-xs space-y-1.5">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-matcha-200">Pengguna Resmi</span>
                                <span class="font-bold text-white text-right">dr. Bambang Utoyo, M.M.</span>
                            </div>
                            <div class="flex items-center justify-between gap-3 text-[11px]">
                                <span class="text-matcha-200">Jabatan</span>
                                <span class="text-white/90 text-right">Kepala UPTD Puskesmas Teluk Sasah</span>
                            </div>
                            <div class="flex items-center justify-between gap-3 text-[10px] font-mono">
                                <span class="text-matcha-200 font-sans text-[11px]">Nomor NIP</span>
                                <span class="text-matcha-100 font-semibold text-right">NIP. 19780512 200604 1 008</span>
                            </div>
                            <div class="flex items-center justify-between gap-3 text-[11px] pt-1.5 mt-1.5 border-t border-white/15">
                                <span class="text-matcha-200">Usulan P5</span>
                                @if($gApproved)
                                    <span class="inline-flex items-center gap-1.5 font-bold text-white"><i class="fa-solid fa-stamp"></i>Telah disahkan</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 font-bold text-white"><span class="w-1.5 h-1.5 rounded-full bg-amber-300"></span>Menunggu otorisasi</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        <p class="font-bold text-matcha-200 text-[11px] uppercase tracking-[0.12em]">Fitur &amp; Wewenang Kerja</p>
                        <ul class="space-y-2 text-[12px] text-white/90">
                            <li class="flex items-start gap-2.5"><i class="fa-solid fa-stamp text-matcha-200 mt-0.5"></i><span>Disposisi &amp; pengesahan usulan LPLPO P5 (approval)</span></li>
                            <li class="flex items-start gap-2.5"><i class="fa-solid fa-shield-halved text-matcha-200 mt-0.5"></i><span>Evaluasi indeks ketersediaan obat dasar (SPM Kemenkes)</span></li>
                            <li class="flex items-start gap-2.5"><i class="fa-solid fa-sack-dollar text-matcha-200 mt-0.5"></i><span>Audit akuntabilitas kerugian obat ED (APBD / DAK)</span></li>
                            <li class="flex items-start gap-2.5"><i class="fa-solid fa-chart-pie text-matcha-200 mt-0.5"></i><span>Analisis beban 10 besar morbiditas SIMPUS</span></li>
                            <li class="flex items-start gap-2.5"><i class="fa-solid fa-print text-matcha-200 mt-0.5"></i><span>Cetak dokumen legal LPLPO resmi ber-NIP ke Dinkes</span></li>
                        </ul>
                    </div>
                </div>

                <div class="relative pt-8 space-y-2">
                    <button type="button"
                            onclick="openLoginModal()"
                            class="w-full py-4 px-6 rounded-full bg-white hover:bg-matcha-100 text-matcha-900 font-extrabold text-sm shadow-lg shadow-black/20 transition flex items-center justify-center gap-2 cursor-pointer group-hover:scale-[1.01]">
                        <i class="fa-solid fa-lock text-matcha-700 text-xs"></i>
                        <span>Buka Aplikasi Kepala Puskesmas</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                    <p class="text-center text-[11px] text-matcha-200 flex items-center justify-center gap-1.5 pt-1">
                        <i class="fa-solid fa-shield-halved text-[10px] text-matcha-300"></i>
                        <span>Wajib Kata Sandi Otoritas Pimpinan</span>
                    </p>
                </div>
            </section>

            <!-- KARTU 3: PORTAL LAB SAINS DATA (Terbuka untuk Sidang) -->
            <section class="group relative overflow-hidden rounded-[32px] p-7 sm:p-8 shadow-float hover:shadow-float-lg transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between border-2"
                     style="background: linear-gradient(150deg, #F3F8EE 0%, #E7F5DC 100%); border-color:#5C6A44;" aria-labelledby="judul-lab">
                <div class="absolute inset-0 opacity-[0.07] pointer-events-none"
                     style="background-image: radial-gradient(#1A3B22 1px, transparent 1px); background-size: 14px 14px;" aria-hidden="true"></div>

                <div class="space-y-5 relative">
                    <div class="flex items-center justify-between gap-3">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-105 transition-transform" style="background:#1A3B22;">
                            <i class="fa-solid fa-flask-vial text-matcha-200"></i>
                        </div>
                        <span class="px-3.5 py-1.5 rounded-full text-[11px] font-bold" style="background:#CFE1B9; color:#1A3B22;">
                            Terbuka · Sidang Akademik
                        </span>
                    </div>

                    <div>
                        <h2 id="judul-lab" class="text-2xl font-extrabold" style="color:#1A3B22;">Portal Sains Data</h2>
                        <p class="text-sm mt-1" style="color:#5C6A44;">CRISP-DM Lab — Kelompok 23 · INF11121</p>
                    </div>

                    <p class="text-sm leading-relaxed" style="color:#728156;">
                        Validasi metodologi penambangan data end-to-end: Pipeline CRISP-DM, Censored Demand, Arena Model WMA-3 vs SMA vs SES, MACD Momentum, dan Korelasi ICD-10.
                    </p>

                    <ul class="space-y-1.5 text-xs" style="color:#3E4A2F;">
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-check-circle text-xs" style="color:#1A3B22;"></i>
                            Pipeline CRISP-DM 6 Fase Interaktif
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-check-circle text-xs" style="color:#1A3B22;"></i>
                            150 Obat · 31.125 Kunjungan · 138 BA Obat ED
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-check-circle text-xs" style="color:#1A3B22;"></i>
                            Drug Switcher Instan &lt;35ms · Chart.js 4.4.1
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-graduation-cap text-xs" style="color:#1A3B22;"></i>
                            Mode Sidang — Ringkasan Pembuktian
                        </li>
                    </ul>
                </div>

                <div class="relative mt-6">
                    <a href="{{ route('lab.eda') }}"
                       class="w-full flex items-center justify-center gap-2.5 py-3.5 rounded-2xl text-sm font-bold text-white transition-all hover:opacity-90 hover:scale-[1.02]"
                       style="background:#1A3B22;">
                        <i class="fa-solid fa-flask-vial"></i>
                        Masuk Portal Sains Data
                        <i class="fa-solid fa-arrow-right text-matcha-300 ml-auto"></i>
                    </a>
                    <p class="mt-3 flex items-center gap-1.5 text-xs" style="color:#728156;">
                        <i class="fa-solid fa-unlock text-[10px]"></i>
                        <span>Tanpa kata sandi · Akses verifikasi publik sidang</span>
                    </p>
                </div>
            </section>
        </div>

        <!-- Ringkasan data -->
        <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-float p-4 sm:p-5 flex flex-col lg:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <div class="flex items-center gap-2.5 text-center lg:text-left">
                <i class="fa-solid fa-circle-info text-matcha-500"></i>
                <span>Data kedua aplikasi tersinkronisasi otomatis melalui database SQLite faskes.</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 w-full lg:w-auto">
                <div class="px-3.5 py-2 rounded-2xl bg-matcha-50 text-center">
                    <p class="font-extrabold text-ink text-sm tnum">{{ $gTotalObat }}</p>
                    <p class="text-[10px] text-slate-500">Sediaan Obat</p>
                </div>
                <div class="px-3.5 py-2 rounded-2xl bg-matcha-50 text-center">
                    <p class="font-extrabold text-ink text-sm tnum">{{ $gTotalPasien }}</p>
                    <p class="text-[10px] text-slate-500">Kasus SIMPUS</p>
                </div>
                <div class="px-3.5 py-2 rounded-2xl bg-matcha-50 text-center">
                    <p class="font-extrabold text-ink text-sm tnum">{{ $gUnit }}</p>
                    <p class="text-[10px] text-slate-500">Unit Usulan P5</p>
                </div>
                <div class="px-3.5 py-2 rounded-2xl bg-rose-50 text-center">
                    <p class="font-extrabold text-rose-600 text-sm tnum">Rp {{ $gKerugian }} Jt</p>
                    <p class="text-[10px] text-slate-500">Kerugian ED</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer kedinasan -->
    <footer class="relative z-10 px-4 sm:px-6 lg:px-8 pb-6 text-xs text-slate-500">
        <div class="max-w-7xl mx-auto border-t border-matcha-200/60 pt-5 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-center sm:text-left">&copy; 2026 UPTD Puskesmas Teluk Sasah, Kecamatan Seri Kuala Lobam, Kabupaten Bintan, Kepulauan Riau.</p>
            <p>PSC 119: (0771) 482-123 &bull; Farmasi &amp; SIMPUS Terintegrasi</p>
        </div>
    </footer>

    <!-- MODAL OTORISASI LOGIN KEPALA PUSKESMAS -->
    <div id="loginKepalaModal" class="fixed inset-0 z-50 hidden transition-opacity duration-300" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop Blur Overlay -->
        <div class="fixed inset-0 bg-slate-950/65 backdrop-blur-sm transition-opacity" onclick="closeLoginModal()"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto flex items-center justify-center p-4 sm:p-6">
            <div class="relative w-full max-w-md bg-white rounded-[32px] shadow-float-lg overflow-hidden transform transition-all border border-matcha-200/80">
                <!-- Modal Header -->
                <div class="p-6 text-white relative" style="background: linear-gradient(150deg, #1A3B22 0%, #2A4A2E 100%);">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-white text-matcha-900 flex items-center justify-center text-xl shadow-md shrink-0">
                                <i class="fa-solid fa-user-doctor"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-matcha-200 text-matcha-900 uppercase tracking-wider">Akses Pimpinan</span>
                                    <span class="text-matcha-200 text-[11px]"><i class="fa-solid fa-lock text-[10px]"></i> Dilindungi Sandi</span>
                                </div>
                                <h3 id="modal-title" class="text-lg font-extrabold text-white mt-1">Otorisasi Kepala Puskesmas</h3>
                                <p class="text-[11px] text-matcha-200">UPTD Puskesmas Teluk Sasah</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeLoginModal()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-5 sm:p-6 space-y-4">
                    @if(session('error') && session('error_target') === 'kepala')
                    <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 text-xs shrink-0"></i>
                        <div class="flex-1 font-medium leading-relaxed">
                            {{ session('error') }}
                        </div>
                    </div>
                    @endif

                    <!-- Profil Pejabat -->
                    <div class="p-3.5 rounded-2xl bg-matcha-50 border border-matcha-200/70 flex items-center justify-between text-xs">
                        <div class="space-y-0.5">
                            <p class="font-extrabold text-ink text-sm">dr. Bambang Utoyo, M.M.</p>
                            <p class="text-slate-500 text-[11px]">Kepala UPTD Puskesmas Teluk Sasah</p>
                            <p class="font-mono text-matcha-800 text-[10px] font-bold">NIP. 19780512 200604 1 008</p>
                        </div>
                        <div class="px-3 py-1.5 rounded-xl bg-matcha-100 text-matcha-800 font-bold text-[10px] text-center shrink-0">
                            <i class="fa-solid fa-stamp text-matcha-600 block text-sm mb-0.5"></i>
                            <span>Disposisi &amp; SPM</span>
                        </div>
                    </div>

                    <!-- Security Notice -->
                    <div class="text-[11px] text-slate-600 leading-relaxed bg-amber-50/70 border border-amber-200/80 p-3 rounded-xl flex items-start gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-0.5 text-xs shrink-0"></i>
                        <span>Area ini memuat disposisi persetujuan anggaran dan dokumen legal dinas. Petugas farmasi / logistik tidak diizinkan masuk ke portal eksekutif ini.</span>
                    </div>

                    <!-- Form Password -->
                    <form method="POST" action="{{ route('login.kepala') }}" id="loginKepalaForm" class="space-y-4 pt-1">
                        @csrf
                        <div>
                            <label for="kepala_password" class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                                <span>Kata Sandi Pimpinan</span>
                                <span class="text-[10px] font-normal text-slate-400">Verifikasi NIP / Password</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-key text-xs"></i>
                                </div>
                                <input type="password"
                                       id="kepala_password"
                                       name="password"
                                       required
                                       autofocus
                                       autocomplete="current-password"
                                       placeholder="Ketik kata sandi Kepala Puskesmas..."
                                       class="w-full pl-10 pr-10 py-3 rounded-2xl bg-slate-50 border border-slate-300 focus:border-matcha-600 focus:bg-white focus:outline-none focus:ring-2 focus:ring-matcha-600/20 text-sm font-medium transition text-ink">
                                <button type="button"
                                       onclick="togglePasswordVisibility('kepala_password', this)"
                                       title="Lihat / Sembunyikan Sandi"
                                       class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </button>
                            </div>

                            <!-- Petunjuk & Tombol Isi Cepat -->
                            <div class="mt-2.5 p-2.5 rounded-2xl bg-matcha-50/70 border border-matcha-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                                <span class="text-[11px] font-medium text-slate-600 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-question text-matcha-600"></i>
                                    <span>Pintasan Cepat:</span>
                                </span>
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <button type="button"
                                            onclick="fillPasswordAndSubmit('kepala123')"
                                            title="Klik untuk langsung masuk menggunakan 'kepala123'"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white hover:bg-matcha-100 text-matcha-900 border border-matcha-300 font-mono font-bold text-[11px] shadow-xs transition hover:scale-105 cursor-pointer">
                                        <span>kepala123</span>
                                        <i class="fa-solid fa-arrow-turn-down text-[9px] text-matcha-600"></i>
                                    </button>
                                    <button type="button"
                                            onclick="fillPasswordAndSubmit('197805122006041008')"
                                            title="Klik untuk langsung masuk menggunakan NIP Kapus"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white hover:bg-matcha-100 text-matcha-900 border border-matcha-300 font-mono font-bold text-[11px] shadow-xs transition hover:scale-105 cursor-pointer">
                                        <span>NIP Kapus</span>
                                        <i class="fa-solid fa-arrow-turn-down text-[9px] text-matcha-600"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2 flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2">
                            <button type="button"
                                    onclick="closeLoginModal()"
                                    class="w-full sm:w-auto px-4 py-2.5 rounded-full border border-slate-300 hover:bg-slate-100 text-slate-600 text-xs font-bold transition text-center">
                                Batal
                            </button>
                            <button type="button"
                                    onclick="fillPasswordAndSubmit('kepala123')"
                                    class="w-full sm:w-auto px-4 py-2.5 rounded-full bg-matcha-100 hover:bg-matcha-200 text-matcha-900 text-xs font-extrabold border border-matcha-300 transition flex items-center justify-center gap-1.5 cursor-pointer">
                                <i class="fa-solid fa-bolt text-matcha-700 text-xs"></i>
                                <span>Masuk Cepat Demo</span>
                            </button>
                            <button type="submit"
                                    class="w-full sm:w-auto px-5 py-2.5 rounded-full bg-matcha-700 hover:bg-matcha-800 text-white text-xs font-extrabold shadow-md shadow-matcha-700/30 transition flex items-center justify-center gap-2 cursor-pointer">
                                <i class="fa-solid fa-lock-open text-xs"></i>
                                <span>Verifikasi &amp; Masuk</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openLoginModal() {
            const modal = document.getElementById('loginKepalaModal');
            if (!modal) return;
            modal.classList.remove('hidden');
            setTimeout(() => {
                const passInput = document.getElementById('kepala_password');
                if (passInput) {
                    passInput.focus();
                    passInput.select();
                }
            }, 100);
        }

        function closeLoginModal() {
            const modal = document.getElementById('loginKepalaModal');
            if (!modal) return;
            modal.classList.add('hidden');
        }

        function fillPasswordAndSubmit(pwd) {
            const passInput = document.getElementById('kepala_password');
            if (passInput) {
                passInput.value = pwd;
                const form = document.getElementById('loginKepalaForm');
                if (form) form.submit();
            }
        }

        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            } else {
                input.type = 'password';
                if (icon) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        }

        // Tutup modal jika tombol Escape ditekan
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeLoginModal();
        });

        // Auto open modal jika terdapat error login Kepala
        @if(session('error') && session('error_target') === 'kepala')
            document.addEventListener('DOMContentLoaded', function () {
                openLoginModal();
            });
        @endif
    </script>
</body>
</html>
