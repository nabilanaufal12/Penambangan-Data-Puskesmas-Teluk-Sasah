<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PRABOTIK &bull; Portal Terpadu UPTD Puskesmas Teluk Sasah</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        medical: {
                            50: '#F0FDF4',
                            100: '#DCFCE7',
                            200: '#BBF7D0',
                            500: '#22C55E',
                            600: '#16A34A',
                            700: '#15803D',
                            900: '#14532D',
                        },
                        navy: {
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen text-slate-100 flex flex-col justify-between selection:bg-emerald-500 selection:text-white relative overflow-x-hidden">

    <!-- Decorative Gradient Background Glows -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-emerald-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/3 -right-40 w-96 h-96 bg-amber-600/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-cyan-600/15 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Header Instansi Pemerintah -->
    <header class="relative z-10 border-b border-slate-800/80 bg-slate-950/60 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-3.5">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-600 to-cyan-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                    <i class="fa-solid fa-hospital text-xl"></i>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="font-black text-lg text-white tracking-wider">PRA<span class="text-emerald-400">BOTIK</span></span>
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Versi 2.4 Faskes</span>
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium">UPTD Puskesmas Teluk Sasah &bull; Dinas Kesehatan Kab. Bintan</p>
                </div>
            </div>

            <div class="flex items-center space-x-3 text-xs text-slate-400">
                <span class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-slate-900 border border-slate-800">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Database Terhubung (SQLite)</span>
                </span>
                <span class="hidden md:inline text-slate-600">&bull;</span>
                <span class="hidden md:inline font-mono">Periode 2026-P5</span>
            </div>
        </div>
    </header>

    <!-- Main Portal Selection Section -->
    <main class="relative z-10 flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16 space-y-12">
        
        <!-- Hero Text -->
        <div class="text-center max-w-3xl mx-auto space-y-3">
            <span class="px-4 py-1.5 rounded-full bg-slate-800/80 text-slate-300 text-xs font-semibold border border-slate-700/80 inline-flex items-center space-x-2 shadow-xs">
                <i class="fa-solid fa-layer-group text-emerald-400"></i>
                <span>Gerbang Akses Terpisah Sistem Faskes</span>
            </span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white leading-tight">
                Pilih Portal Aplikasi PRABOTIK
            </h1>
            <p class="text-sm sm:text-base text-slate-400 leading-relaxed font-normal">
                Aplikasi dipisahkan secara independen berdasarkan peran dan wewenang kedinasan. Silakan pilih portal kerja Anda di bawah ini:
            </p>
        </div>

        <!-- 2 Distinct Application Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            
            <!-- CARD 1: PORTAL FARMASI -->
            <div class="group relative bg-gradient-to-b from-slate-900/90 to-slate-950/90 rounded-3xl p-8 border border-emerald-500/30 hover:border-emerald-400 transition-all duration-300 shadow-2xl hover:shadow-emerald-500/10 flex flex-col justify-between">
                <div class="space-y-6">
                    <!-- Icon & Role Badge -->
                    <div class="flex items-center justify-between">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-prescription-bottle-medical"></i>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            Akses Gudang & Logistik
                        </span>
                    </div>

                    <!-- Title & Identity -->
                    <div>
                        <h2 class="text-2xl font-black text-white group-hover:text-emerald-400 transition-colors">
                            Portal Bagian Farmasi
                        </h2>
                        <div class="mt-2 p-3 rounded-xl bg-slate-800/60 border border-slate-700/60 text-xs space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Pengguna Resmi:</span>
                                <span class="font-bold text-white">Apt. Siti Rahmawati, S.Farm</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="text-slate-400">Jabatan:</span>
                                <span class="text-slate-300">Pengelola Obat Puskesmas</span>
                            </div>
                            <div class="flex items-center justify-between text-[10px] font-mono">
                                <span class="text-slate-400">Nomor SIP:</span>
                                <span class="text-emerald-400">SIP. 19890415/SIPA/2018/021</span>
                            </div>
                        </div>
                    </div>

                    <!-- Key Features Included -->
                    <div class="space-y-2 pt-2 text-xs text-slate-300">
                        <p class="font-bold text-slate-200 text-[11px] uppercase tracking-wider">Fitur & Wewenang Kerja:</p>
                        <div class="grid grid-cols-1 gap-1.5 text-[11px]">
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-check text-emerald-400"></i>
                                <span>Audit & Monitoring Stok Fisik (SOH Gudang)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-check text-emerald-400"></i>
                                <span>Pusat Entri Data (Stok LPLPO, Kasus SIMPUS, ED)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-check text-emerald-400"></i>
                                <span>Penyusunan Usulan Order P5 (WMA & 95% SS)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-check text-emerald-400"></i>
                                <span>Deteksi Momentum Permintaan Obat (MACD)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-check text-emerald-400"></i>
                                <span>Cetak Lembar Kerja Verifikasi Fisik LPLPO</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-8">
                    <a href="{{ route('pharmacy.dashboard') }}" class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400 text-white font-extrabold text-sm shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center space-x-2 group-hover:translate-y-[-2px]">
                        <span>Buka Aplikasi Farmasi</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- CARD 2: PORTAL KEPALA PUSKESMAS -->
            <div class="group relative bg-gradient-to-b from-slate-900/90 to-slate-950/90 rounded-3xl p-8 border border-amber-500/30 hover:border-amber-400 transition-all duration-300 shadow-2xl hover:shadow-amber-500/10 flex flex-col justify-between">
                <div class="space-y-6">
                    <!-- Icon & Role Badge -->
                    <div class="flex items-center justify-between">
                        <div class="w-14 h-14 rounded-2xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                            Akses Eksekutif & Kebijakan
                        </span>
                    </div>

                    <!-- Title & Identity -->
                    <div>
                        <h2 class="text-2xl font-black text-white group-hover:text-amber-400 transition-colors">
                            Portal Kepala Puskesmas
                        </h2>
                        <div class="mt-2 p-3 rounded-xl bg-slate-800/60 border border-slate-700/60 text-xs space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Pengguna Resmi:</span>
                                <span class="font-bold text-white">dr. Bambang Utoyo, M.M.</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="text-slate-400">Jabatan:</span>
                                <span class="text-slate-300">Kepala UPTD Puskesmas Teluk Sasah</span>
                            </div>
                            <div class="flex items-center justify-between text-[10px] font-mono">
                                <span class="text-slate-400">Nomor NIP:</span>
                                <span class="text-amber-400">NIP. 19780512 200604 1 008</span>
                            </div>
                        </div>
                    </div>

                    <!-- Key Features Included -->
                    <div class="space-y-2 pt-2 text-xs text-slate-300">
                        <p class="font-bold text-slate-200 text-[11px] uppercase tracking-wider">Fitur & Wewenang Kerja:</p>
                        <div class="grid grid-cols-1 gap-1.5 text-[11px]">
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-stamp text-amber-400"></i>
                                <span>Disposisi & Pengesahan Usulan LPLPO P5 (Approval)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-shield-halved text-amber-400"></i>
                                <span>Evaluasi Indeks Ketersediaan Obat Dasar (SPM Kemenkes)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-sack-dollar text-amber-400"></i>
                                <span>Audit Akuntabilitas Kerugian Obat ED (APBD / DAK)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-chart-pie text-amber-400"></i>
                                <span>Analisis Beban 10 Besar Morbiditas SIMPUS</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-print text-amber-400"></i>
                                <span>Cetak Dokumen Legal LPLPO Resmi Ber-NIP ke Dinkes</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-8">
                    <a href="{{ route('executive.dashboard') }}" class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-amber-600 to-amber-500 hover:from-amber-500 hover:to-amber-400 text-white font-extrabold text-sm shadow-lg shadow-amber-600/30 transition-all flex items-center justify-center space-x-2 group-hover:translate-y-[-2px]">
                        <span>Buka Aplikasi Kepala Puskesmas</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

        </div>

        <!-- Quick Summary Bar -->
        <div class="max-w-5xl mx-auto bg-slate-950/40 rounded-2xl p-4 border border-slate-800 text-xs text-slate-400 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-circle-info text-emerald-400"></i>
                <span>Data di kedua aplikasi tersinkronisasi secara otomatis melalui database SQLite faskes.</span>
            </div>
            <div class="font-mono text-[11px] text-slate-500">
                150 Sediaan Obat &bull; 31.125 Pasien SIMPUS &bull; 138 Berita Acara ED
            </div>
        </div>

    </main>

    <!-- Footer Kedinasan -->
    <footer class="relative z-10 border-t border-slate-800/80 bg-slate-950/80 py-6 text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>&copy; 2026 UPTD Puskesmas Teluk Sasah, Kecamatan Seri Kuala Lobam, Kabupaten Bintan, Kepulauan Riau.</p>
            <div class="flex items-center space-x-4">
                <span>PSC 119: (0771) 482-123</span>
                <span>&bull;</span>
                <span>Farmasi & SIMPUS Terintegrasi</span>
            </div>
        </div>
    </footer>

</body>
</html>
