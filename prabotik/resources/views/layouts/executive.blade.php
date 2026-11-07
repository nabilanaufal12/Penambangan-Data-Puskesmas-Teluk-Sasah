<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PRABOTIK Eksekutif') &bull; Kepala UPTD Puskesmas Teluk Sasah</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        executive: {
                            50: '#FFFBEB',
                            100: '#FEF3C7',
                            200: '#FDE68A',
                            500: '#F59E0B',
                            600: '#D97706',
                            700: '#B45309',
                            800: '#92400E',
                            900: '#78350F',
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
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
    @stack('styles')
</head>
<body class="h-full text-slate-800 antialiased flex flex-col md:flex-row overflow-x-hidden">

    <!-- Mobile Header -->
    <div class="md:hidden bg-navy-950 text-white px-4 py-3 flex items-center justify-between sticky top-0 z-50 shadow-md">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-xl bg-amber-600 flex items-center justify-center text-white">
                <i class="fa-solid fa-user-doctor"></i>
            </div>
            <div>
                <span class="font-extrabold text-sm text-white">PRABOTIK <span class="text-amber-400">EKSEKUTIF</span></span>
                <span class="block text-[10px] text-slate-400 -mt-0.5">Kepala Puskesmas Teluk Sasah</span>
            </div>
        </div>
        <button id="mobileMenuBtn" class="text-slate-300 hover:text-white p-2 focus:outline-none">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
    </div>

    <!-- LEFT SIDEBAR: KHUSUS KEPALA PUSKESMAS & MANAJEMEN -->
    <aside id="sidebar" class="fixed md:sticky top-0 inset-y-0 left-0 z-40 w-72 bg-navy-950 text-slate-300 flex flex-col justify-between transition-transform duration-300 -translate-x-full md:translate-x-0 h-screen shadow-xl border-r border-slate-800/80 select-none">
        
        <!-- Sidebar Brand Header -->
        <div class="p-5 border-b border-slate-800/80">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-amber-600 to-amber-400 flex items-center justify-center text-white shadow-lg shadow-amber-500/20">
                    <i class="fa-solid fa-user-doctor text-lg"></i>
                </div>
                <div>
                    <h1 class="text-lg font-black text-white tracking-wider leading-tight">PRA<span class="text-amber-400">BOTIK</span></h1>
                    <span class="text-[10px] font-bold text-amber-400 tracking-wider uppercase block">Portal Eksekutif Kepala Faskes</span>
                </div>
            </div>
            <div class="mt-3 px-3 py-1.5 rounded-lg bg-slate-900/90 border border-slate-800 flex items-center justify-between text-[11px]">
                <span class="text-slate-400 font-medium">Satker:</span>
                <span class="font-bold text-amber-300 truncate">UPTD Puskesmas Teluk Sasah</span>
            </div>
        </div>

        <!-- Sidebar Navigation Menu: KHUSUS KEPALA PUSKESMAS -->
        <nav class="flex-1 px-4 py-5 space-y-6 overflow-y-auto">
            
            <div>
                <p class="px-3 text-[10px] font-bold tracking-wider text-slate-400 uppercase">Pengawasan & Legalitas</p>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('executive.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('executive.dashboard') ? 'bg-amber-600 text-white shadow-md shadow-amber-600/30' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                        <i class="fa-solid fa-building-columns w-6 text-center text-base {{ request()->routeIs('executive.dashboard') ? 'text-white' : 'text-amber-400' }}"></i>
                        <span class="ml-2.5">Ringkasan Eksekutif & SPM</span>
                    </a>

                    <a href="{{ route('executive.disposition') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('executive.disposition') ? 'bg-amber-600 text-white shadow-md shadow-amber-600/30' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                        <i class="fa-solid fa-stamp w-6 text-center text-base {{ request()->routeIs('executive.disposition') ? 'text-white' : 'text-emerald-400' }}"></i>
                        <span class="ml-2.5">Disposisi Pengadaan P5</span>
                        <span class="ml-auto text-[9px] px-1.5 py-0.5 rounded font-bold {{ session('p5_procurement_approved') ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300' }}">
                            {{ session('p5_procurement_approved') ? 'Disahkan' : 'Otorisasi' }}
                        </span>
                    </a>
                </div>
            </div>

            <div>
                <p class="px-3 text-[10px] font-bold tracking-wider text-slate-400 uppercase">Laporan & Kebijakan Faskes</p>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('executive.diseases') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('executive.diseases') ? 'bg-amber-600 text-white shadow-md shadow-amber-600/30' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                        <i class="fa-solid fa-heart-pulse w-6 text-center text-base {{ request()->routeIs('executive.diseases') ? 'text-white' : 'text-cyan-400' }}"></i>
                        <span class="ml-2.5">Morbiditas SIMPUS</span>
                        <span class="ml-auto text-[9px] px-1.5 py-0.5 rounded font-bold shrink-0 {{ request()->routeIs('executive.diseases') ? 'bg-white/20 text-white' : 'bg-cyan-500/20 text-cyan-300' }}">10 Besar</span>
                    </a>

                    <a href="{{ route('executive.financial') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('executive.financial') ? 'bg-amber-600 text-white shadow-md shadow-amber-600/30' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                        <i class="fa-solid fa-sack-dollar w-6 text-center text-base {{ request()->routeIs('executive.financial') ? 'text-white' : 'text-rose-400' }}"></i>
                        <span class="ml-2.5">Audit Kerugian Obat ED</span>
                    </a>

                    <a href="{{ route('executive.correlations') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('executive.correlations') ? 'bg-amber-600 text-white shadow-md shadow-amber-600/30' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                        <i class="fa-solid fa-notes-medical w-6 text-center text-base {{ request()->routeIs('executive.correlations') ? 'text-white' : 'text-purple-400' }}"></i>
                        <span class="ml-2.5">Standar Terapi & Peresepan</span>
                    </a>

                    <a href="{{ route('executive.print') }}" target="_blank" class="flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-300 hover:bg-slate-900 hover:text-white transition">
                        <i class="fa-solid fa-print w-6 text-center text-base text-amber-400"></i>
                        <span class="ml-2.5">Cetak LPLPO Resmi Ber-NIP</span>
                    </a>

                    <a href="{{ route('executive.profile') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('executive.profile') ? 'bg-amber-600 text-white shadow-md shadow-amber-600/30' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                        <i class="fa-solid fa-hospital-user w-6 text-center text-base {{ request()->routeIs('executive.profile') ? 'text-white' : 'text-teal-400' }}"></i>
                        <span class="ml-2.5">Profil Puskesmas & Wilayah</span>
                    </a>
                </div>
            </div>

        </nav>

        <!-- Sidebar Bottom: Dedicated Head of Puskesmas Identity -->
        <div class="p-4 border-t border-slate-800/80 bg-slate-950/60 space-y-3">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold shrink-0 shadow-md shadow-amber-600/30">
                    <i class="fa-solid fa-user-doctor"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-white truncate">dr. Bambang Utoyo, M.M.</p>
                    <p class="text-[10px] text-slate-400 truncate">Kepala UPTD Puskesmas</p>
                    <p class="text-[9px] text-amber-400 font-mono truncate">NIP. 19780512 200604 1 008</p>
                </div>
            </div>

            <a href="{{ route('gateway') }}" class="w-full py-2 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-bold transition flex items-center justify-center space-x-2 border border-slate-700/60">
                <i class="fa-solid fa-door-open text-slate-400"></i>
                <span>Keluar ke Portal Utama</span>
            </a>
        </div>

    </aside>

    <!-- MAIN EXECUTIVE VIEW AREA -->
    <div class="flex-1 flex flex-col min-h-screen bg-slate-50 overflow-x-hidden">
        
        <!-- Top Executive Bar -->
        <header class="bg-white border-b border-slate-200/80 sticky top-0 z-30 shadow-xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    
                    <div class="flex items-center space-x-3">
                        <span class="px-2.5 py-1 rounded-lg bg-amber-100 text-amber-800 text-[10px] font-extrabold uppercase border border-amber-200">
                            Aplikasi Eksekutif
                        </span>
                        <div class="hidden sm:flex items-center text-slate-500 text-xs font-medium space-x-2">
                            <span class="text-slate-400">UPTD Puskesmas Teluk Sasah</span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
                            <span class="text-amber-800 font-bold">@yield('page_title', 'Ringkasan Eksekutif & Pengawasan')</span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="flex items-center space-x-2 px-3 py-1.5 bg-amber-50 rounded-xl border border-amber-200 text-amber-800 text-xs font-bold">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            <span>Periode 2026-P5</span>
                        </div>

                        <a href="{{ route('gateway') }}" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center space-x-1.5 border border-slate-200">
                            <i class="fa-solid fa-arrow-right-from-bracket text-slate-500"></i>
                            <span class="hidden sm:inline">Ganti Portal</span>
                        </a>
                    </div>

                </div>
            </div>
        </header>

        <main class="flex-1">
            @yield('content')
        </main>

        <footer class="bg-white border-t border-slate-200 py-6 text-xs text-slate-500 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p>&copy; 2026 PRABOTIK Eksekutif &bull; UPTD Puskesmas Teluk Sasah, Kabupaten Bintan.</p>
                <div class="flex items-center space-x-4">
                    <span>Pengesah: dr. Bambang Utoyo, M.M. (NIP. 19780512 200604 1 008)</span>
                    <span>&bull;</span>
                    <span>Format Standar LPLPO Dinkes Bintan</span>
                </div>
            </div>
        </footer>

    </div>

    <script>
        const mobileBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');
        if (mobileBtn && sidebar) {
            mobileBtn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
