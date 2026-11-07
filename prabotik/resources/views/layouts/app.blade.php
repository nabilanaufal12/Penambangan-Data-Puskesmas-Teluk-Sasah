<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PRABOTIK') — Sistem Prediksi Kebutuhan Obat & Tren Penyakit Puskesmas Teluk Sasah</title>
    
    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        medical: {
                            50: '#F0F9FF',
                            100: '#E0F2FE',
                            200: '#BAE6FD',
                            300: '#7DD3FC',
                            400: '#38BDF8',
                            500: '#0EA5E9',
                            600: '#0284C7',
                            700: '#0369A1',
                            800: '#075985',
                            900: '#0C4A6E',
                            950: '#082F49',
                        },
                        navy: {
                            800: '#0F2744',
                            900: '#0A1D33',
                            950: '#061324',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #F1F5F9;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full text-slate-800 antialiased flex flex-col md:flex-row overflow-x-hidden">

    <!-- Mobile Header & Toggle -->
    <div class="md:hidden bg-navy-950 text-white px-4 py-3 flex items-center justify-between sticky top-0 z-50 shadow-md">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-medical-600 to-medical-400 flex items-center justify-center text-white shadow-md shadow-medical-500/20">
                <i class="fa-solid fa-staff-snake text-lg"></i>
            </div>
            <div>
                <span class="font-extrabold tracking-tight text-lg text-white">PRA<span class="text-medical-400">BOTIK</span></span>
                <span class="block text-[10px] text-slate-400 -mt-1">Puskesmas Teluk Sasah</span>
            </div>
        </div>
        <button id="mobileMenuBtn" class="text-slate-300 hover:text-white p-2 focus:outline-none">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
    </div>

    <!-- LEFT SIDEBAR PANEL (Navigasi Fitur di Sebelah Kiri) -->
    <aside id="sidebar" class="fixed md:sticky top-0 inset-y-0 left-0 z-40 w-72 bg-navy-950 text-slate-300 flex flex-col justify-between transition-transform duration-300 -translate-x-full md:translate-x-0 h-screen shadow-xl border-r border-slate-800/60 select-none">
        
        <!-- Sidebar Brand Header -->
        <div class="p-6 border-b border-slate-800/80">
            <div class="flex items-center space-x-3.5">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-medical-600 to-cyan-400 flex items-center justify-center text-white shadow-lg shadow-medical-500/30">
                    <i class="fa-solid fa-capsules text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-white tracking-wider">PRA<span class="text-medical-400">BOTIK</span></h1>
                    <p class="text-[11px] font-medium text-slate-400 uppercase tracking-widest leading-none mt-0.5">Sistem Farmasi & SIMPUS</p>
                </div>
            </div>
            <div class="mt-4 px-3 py-1.5 rounded-lg bg-slate-900/80 border border-slate-800 flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-400">Faskes Mitra:</span>
                <span class="text-[11px] font-bold text-medical-300">Puskesmas Teluk Sasah</span>
            </div>
        </div>

        <!-- Sidebar Navigation Links -->
        <nav class="flex-1 px-4 py-5 space-y-6 overflow-y-auto">
            
            <!-- Group 1: Navigasi Utama -->
            <div>
                <p class="px-3 text-[10px] font-bold tracking-wider text-slate-400 uppercase">Panel Utama</p>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-medical-600 text-white shadow-md shadow-medical-600/30' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                        <i class="fa-solid fa-house-chimney-medical w-6 text-center text-base {{ request()->routeIs('dashboard') ? 'text-white' : 'text-medical-400' }}"></i>
                        <span class="ml-2.5">Beranda & Profil</span>
                    </a>
                    <a href="{{ route('procurement') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('procurement') ? 'bg-medical-600 text-white shadow-md shadow-medical-600/30' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                        <i class="fa-solid fa-cart-flatbed-suitcase w-6 text-center text-base {{ request()->routeIs('procurement') ? 'text-white' : 'text-emerald-400' }}"></i>
                        <span class="ml-2.5">Rekomendasi 2026-P5</span>
                        <span class="ml-auto text-[10px] px-2 py-0.5 rounded-full font-bold {{ request()->routeIs('procurement') ? 'bg-white/20 text-white' : 'bg-emerald-500/20 text-emerald-300' }}">95% SS</span>
                    </a>
                </div>
            </div>

            <!-- Group 2: Analisis & Penambangan Data -->
            <div>
                <p class="px-3 text-[10px] font-bold tracking-wider text-slate-400 uppercase">Peramalan & Analisis (Saran Dosen)</p>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('medicines') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('medicines') ? 'bg-medical-600 text-white shadow-md shadow-medical-600/30' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                        <i class="fa-solid fa-pills w-6 text-center text-base {{ request()->routeIs('medicines') ? 'text-white' : 'text-cyan-400' }}"></i>
                        <span class="ml-2.5">Katalog 150 Obat (MA)</span>
                    </a>
                    <a href="{{ route('diseases') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('diseases') ? 'bg-medical-600 text-white shadow-md shadow-medical-600/30' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                        <i class="fa-solid fa-stethoscope w-6 text-center text-base {{ request()->routeIs('diseases') ? 'text-white' : 'text-rose-400' }}"></i>
                        <span class="ml-2.5">Prediksi 4 Morbiditas</span>
                        <span class="ml-auto text-[10px] px-2 py-0.5 rounded-full font-bold {{ request()->routeIs('diseases') ? 'bg-white/20 text-white' : 'bg-rose-500/20 text-rose-300' }}">ISPA+3</span>
                    </a>
                    <a href="{{ route('macd') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('macd') ? 'bg-medical-600 text-white shadow-md shadow-medical-600/30' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                        <i class="fa-solid fa-chart-line w-6 text-center text-base {{ request()->routeIs('macd') ? 'text-white' : 'text-amber-400' }}"></i>
                        <span class="ml-2.5">Indikator MACD Farmasi</span>
                        <span class="ml-auto text-[10px] px-2 py-0.5 rounded-full font-bold {{ request()->routeIs('macd') ? 'bg-white/20 text-white' : 'bg-amber-500/20 text-amber-300' }}">Momentum</span>
                    </a>
                    <a href="{{ route('correlation') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('correlation') ? 'bg-medical-600 text-white shadow-md shadow-medical-600/30' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                        <i class="fa-solid fa-dna w-6 text-center text-base {{ request()->routeIs('correlation') ? 'text-white' : 'text-indigo-400' }}"></i>
                        <span class="ml-2.5">Korelasi Klinis & Dosis</span>
                    </a>
                    <a href="{{ route('ml.benchmark') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('ml.benchmark') ? 'bg-medical-600 text-white shadow-md shadow-medical-600/30' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                        <i class="fa-solid fa-microchip w-6 text-center text-base {{ request()->routeIs('ml.benchmark') ? 'text-white' : 'text-purple-400' }}"></i>
                        <span class="ml-2.5">Benchmark Machine Learning</span>
                        <span class="ml-auto text-[10px] px-2 py-0.5 rounded-full font-bold {{ request()->routeIs('ml.benchmark') ? 'bg-white/20 text-white' : 'bg-purple-500/20 text-purple-300' }}">10-Fold</span>
                    </a>
                </div>
            </div>

            <!-- Group 3: Pengawasan Logistik & Audit -->
            <div>
                <p class="px-3 text-[10px] font-bold tracking-wider text-slate-400 uppercase">Pengawasan & Profil</p>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('audit.stock') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('audit.stock') ? 'bg-medical-600 text-white shadow-md shadow-medical-600/30' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                        <i class="fa-solid fa-triangle-exclamation w-6 text-center text-base {{ request()->routeIs('audit.stock') ? 'text-white' : 'text-amber-500' }}"></i>
                        <span class="ml-2.5">Audit Stockout & Kadaluarsa</span>
                    </a>
                    <a href="{{ route('about') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('about') ? 'bg-medical-600 text-white shadow-md shadow-medical-600/30' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}">
                        <i class="fa-solid fa-user-doctor w-6 text-center text-base {{ request()->routeIs('about') ? 'text-white' : 'text-teal-400' }}"></i>
                        <span class="ml-2.5">Tim Peneliti & Faskes</span>
                    </a>
                </div>
            </div>

        </nav>

        <!-- Sidebar Bottom Footer -->
        <div class="p-4 border-t border-slate-800/80 bg-slate-950/40">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-medical-400 text-xs">
                    <i class="fa-solid fa-server"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <p class="text-xs font-bold text-white truncate">SQLite Database</p>
                    </div>
                    <p class="text-[10px] text-slate-400 truncate">Periode 2026-P5 Terpadu</p>
                </div>
            </div>
            <div class="mt-3 text-[10px] text-slate-500 text-center">
                Kelompok 23 &bull; UMRAH &bull; Genap 2026
            </div>
        </div>

    </aside>

    <!-- MAIN CONTENT AREA (Area Kanan) -->
    <div class="flex-1 flex flex-col min-h-screen bg-slate-50 overflow-x-hidden">
        
        <!-- Top Operational & Emergency Header Bar -->
        <header class="bg-white border-b border-slate-200/80 sticky top-0 z-30 shadow-xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    
                    <!-- Search or Title Context -->
                    <div class="flex items-center space-x-4">
                        <div class="hidden sm:flex items-center text-slate-500 text-xs font-medium space-x-2">
                            <span class="text-slate-400">UPTD Puskesmas Teluk Sasah</span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
                            <span class="text-medical-600 font-semibold">@yield('page_title', 'Dashboard Farmasi')</span>
                        </div>
                    </div>

                    <!-- Right Quick Badges & Call to Actions -->
                    <div class="flex items-center space-x-3 sm:space-x-4">
                        
                        <!-- 24/7 Care Hotline -->
                        <div class="hidden lg:flex items-center space-x-2.5 px-3 py-1.5 bg-sky-50 rounded-xl border border-sky-100 text-sky-800">
                            <i class="fa-solid fa-phone-volume text-medical-600 animate-bounce"></i>
                            <div class="text-left">
                                <span class="block text-[10px] text-slate-500 leading-none">Hotline Siaga Bintan:</span>
                                <span class="text-xs font-bold text-medical-700 leading-none">0812-7000-SASAH</span>
                            </div>
                        </div>

                        <!-- Current Active Cycle Badge -->
                        <div class="flex items-center space-x-2 px-3 py-1.5 bg-emerald-50 rounded-xl border border-emerald-200 text-emerald-800 text-xs font-bold">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>2026-P5 (Sep–Okt)</span>
                        </div>

                        <!-- Quick Export / Action -->
                        <a href="{{ route('procurement') }}" class="inline-flex items-center px-3.5 py-1.5 rounded-xl bg-medical-600 hover:bg-medical-700 text-white text-xs font-bold shadow-sm shadow-medical-600/30 transition">
                            <i class="fa-solid fa-file-prescription mr-1.5"></i>
                            <span>Draf LPLPO P5</span>
                        </a>

                    </div>
                </div>
            </div>
        </header>

        <!-- Main Body View Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        <!-- Modern Medical Footer (Sesuai Desain Referensi) -->
        <footer class="bg-navy-950 text-slate-400 text-sm border-t border-slate-800 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    
                    <!-- Col 1: About App -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-medical-600 to-cyan-400 flex items-center justify-center text-white">
                                <i class="fa-solid fa-hospital text-lg"></i>
                            </div>
                            <span class="font-extrabold text-lg text-white">PRA<span class="text-medical-400">BOTIK</span></span>
                        </div>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Sistem Penambangan Data Peramalan Kebutuhan Logistik Obat dan Analisis Morbiditas Penyakit Musiman berbasis Moving Average, MACD Farmasi, dan Safety Stock 95%.
                        </p>
                        <div class="flex space-x-3 text-slate-400">
                            <a href="#" class="w-8 h-8 rounded-lg bg-slate-900 hover:bg-medical-600 hover:text-white flex items-center justify-center transition"><i class="fa-brands fa-facebook-f text-xs"></i></a>
                            <a href="#" class="w-8 h-8 rounded-lg bg-slate-900 hover:bg-medical-600 hover:text-white flex items-center justify-center transition"><i class="fa-brands fa-twitter text-xs"></i></a>
                            <a href="#" class="w-8 h-8 rounded-lg bg-slate-900 hover:bg-medical-600 hover:text-white flex items-center justify-center transition"><i class="fa-brands fa-linkedin-in text-xs"></i></a>
                            <a href="#" class="w-8 h-8 rounded-lg bg-slate-900 hover:bg-medical-600 hover:text-white flex items-center justify-center transition"><i class="fa-brands fa-instagram text-xs"></i></a>
                        </div>
                    </div>

                    <!-- Col 2: Layanan Poli Faskes -->
                    <div>
                        <h4 class="text-white text-xs font-bold uppercase tracking-wider mb-4">Layanan Poli Puskesmas</h4>
                        <ul class="space-y-2 text-xs">
                            <li><a href="#" class="hover:text-white transition">Poli Pemeriksaan Umum Dewasa</a></li>
                            <li><a href="#" class="hover:text-white transition">Poli Kesehatan Gigi & Mulut</a></li>
                            <li><a href="#" class="hover:text-white transition">Poli KIA & Imunisasi / Gizi</a></li>
                            <li><a href="#" class="hover:text-white transition">Instalasi Farmasi & Gudang Obat</a></li>
                            <li><a href="#" class="hover:text-white transition">Laboratorium Diagnostik Darah</a></li>
                            <li><a href="#" class="hover:text-white transition">UGD 24 Jam & Rawat Inap Persalinan</a></li>
                        </ul>
                    </div>

                    <!-- Col 3: Fitur Penambangan Data -->
                    <div>
                        <h4 class="text-white text-xs font-bold uppercase tracking-wider mb-4">Modul Penambangan Data</h4>
                        <ul class="space-y-2 text-xs">
                            <li><a href="{{ route('procurement') }}" class="hover:text-white transition">Peramalan WMA-3 & Safety Stock 95%</a></li>
                            <li><a href="{{ route('macd') }}" class="hover:text-white transition">Deteksi Momentum MACD (2/4/2)</a></li>
                            <li><a href="{{ route('diseases') }}" class="hover:text-white transition">Deret Waktu 4 Morbiditas Utama</a></li>
                            <li><a href="{{ route('correlation') }}" class="hover:text-white transition">Korelasi Klinis & Rasio Dosis Pasien</a></li>
                            <li><a href="{{ route('ml.benchmark') }}" class="hover:text-white transition">Benchmarking Machine Learning 10-Fold</a></li>
                            <li><a href="{{ route('audit.stock') }}" class="hover:text-white transition">Mitigasi Kerugian Kadaluarsa Rp 43 Jt</a></li>
                        </ul>
                    </div>

                    <!-- Col 4: Informasi Kontak & Jam Pelayanan -->
                    <div>
                        <h4 class="text-white text-xs font-bold uppercase tracking-wider mb-4">Kontak & Lokasi</h4>
                        <div class="space-y-2.5 text-xs">
                            <div class="flex items-start space-x-2.5">
                                <i class="fa-solid fa-location-dot text-medical-400 mt-0.5"></i>
                                <span>Jl. Raya Teluk Sasah, Kec. Seri Kuala Lobam, Kab. Bintan, Kepri 29152</span>
                            </div>
                            <div class="flex items-center space-x-2.5">
                                <i class="fa-solid fa-phone text-medical-400"></i>
                                <span>(0771) 482-123 / UGD: 119</span>
                            </div>
                            <div class="flex items-center space-x-2.5">
                                <i class="fa-solid fa-envelope text-medical-400"></i>
                                <span>puskesmas.teluksasah@bintankab.go.id</span>
                            </div>
                            <div class="pt-2 border-t border-slate-800 text-[11px] text-slate-400">
                                <span class="font-bold text-slate-300">Jam Rawat Jalan:</span><br>
                                Senin – Kamis: 08:00 – 14:00 WIB<br>
                                Jumat – Sabtu: 08:00 – 12:00 WIB<br>
                                <span class="text-emerald-400 font-semibold">UGD & Persalinan: 24 Jam Non-Stop</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Mitra & Hak Cipta -->
                <div class="mt-8 pt-8 border-t border-slate-800/80 flex flex-col md:flex-row items-center justify-between text-xs text-slate-500">
                    <p>&copy; 2026 PRABOTIK — Kelompok 23 TI24 Universitas Maritim Raja Ali Haji (UMRAH) & UPTD Puskesmas Teluk Sasah. All Rights Reserved.</p>
                    <div class="mt-4 md:mt-0 flex items-center space-x-4">
                        <span class="text-slate-400 font-semibold">Mitra Resmi:</span>
                        <span class="hover:text-slate-300">Kemenkes RI</span>
                        <span>&bull;</span>
                        <span class="hover:text-slate-300">Dinkes Bintan</span>
                        <span>&bull;</span>
                        <span class="hover:text-slate-300">BPJS Kesehatan</span>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <!-- Mobile Drawer Toggle Script -->
    <script>
        const btn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');
        if (btn && sidebar) {
            btn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
