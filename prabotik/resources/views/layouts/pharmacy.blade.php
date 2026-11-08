<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PRABOTIK Farmasi') &bull; UPTD Puskesmas Teluk Sasah</title>

    <!-- Tailwind CSS (CDN) + palet Matcha & Sage -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        const matcha = {
            50:  '#F3F8EE',
            100: '#E7F5DC',
            200: '#CFE1B9',
            300: '#B6C99B',
            400: '#98A77C',
            500: '#88976C',
            600: '#728156',
            700: '#5C6A44',
            800: '#3E4A2F',
            900: '#1A3B22',
            950: '#142416'
        };
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif']
                    },
                    colors: {
                        matcha: matcha,
                        emerald: matcha,
                        sage: '#F4F6F2',
                        ink: '#142416',
                        medical: {
                            50: '#F0FDF4', 100: '#DCFCE7', 200: '#BBF7D0', 500: '#22C55E',
                            600: '#16A34A', 700: '#15803D', 800: '#166534', 900: '#14532D', 950: '#052e16'
                        },
                        navy: { 800: '#1e293b', 900: '#0f172a', 950: '#020617' }
                    },
                    boxShadow: {
                        float: '0 8px 30px rgb(0 0 0 / 0.04)',
                        'float-lg': '0 14px 44px rgb(20 36 22 / 0.09)'
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; background-color: #F4F6F2; }
        .tnum { font-variant-numeric: tabular-nums; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #CFE1B9; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #98A77C; }
        a:focus-visible, button:focus-visible { outline: 2px solid #728156; outline-offset: 2px; }

        /* Transisi & Mode Ringkas (Compact Dock) Sidebar Kiri */
        #sidebar {
            transition: width 0.28s cubic-bezier(0.4, 0, 0.2, 1), transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #sidebar.is-compact {
            width: 76px !important;
            padding-left: 0.625rem !important;
            padding-right: 0.625rem !important;
        }
        #sidebar.is-compact .sidebar-text,
        #sidebar.is-compact .sidebar-heading,
        #sidebar.is-compact .sidebar-badge,
        #sidebar.is-compact .sidebar-brand-text,
        #sidebar.is-compact .sidebar-profile-text {
            display: none !important;
        }
        #sidebar.is-compact .sidebar-link {
            width: 48px !important;
            height: 48px !important;
            padding: 0 !important;
            justify-content: center !important;
            border-radius: 9999px !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }
        #sidebar.is-compact .sidebar-link i {
            margin: 0 !important;
            font-size: 16px !important;
            width: auto !important;
        }
        #sidebar:not(.is-compact) {
            width: 17.5rem !important;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-full bg-sage text-ink antialiased overflow-x-hidden">

@php
    $isDashboard   = request()->routeIs('pharmacy.dashboard*');
    $isEntry       = request()->routeIs('pharmacy.entry*');
    $isProcurement = request()->routeIs('pharmacy.procurement*');
    $isMonitoring  = request()->routeIs('pharmacy.medicines*') || request()->routeIs('pharmacy.macd*') || request()->routeIs('pharmacy.audit*');
    $currentTab    = request()->query('tab', 'lplpo');

    // =========================================================================
    // 1. HEADER ATAS: 4 ALUR UTAMA PUSKESMAS (MACRO NAVIGATION - TIDAK ADA DUPLIKASI)
    // =========================================================================
    $navPills = [
        ['route' => 'pharmacy.dashboard',   'label' => 'Beranda',            'icon' => 'fa-house',           'active' => $isDashboard],
        ['route' => 'pharmacy.entry',       'label' => 'Pencatatan Data',    'icon' => 'fa-pen-to-square',   'active' => $isEntry],
        ['route' => 'pharmacy.procurement', 'label' => 'Usulan Pengadaan',   'icon' => 'fa-clipboard-check', 'active' => $isProcurement],
        ['route' => 'pharmacy.medicines',   'label' => 'Monitoring & Tren',  'icon' => 'fa-chart-line',      'active' => $isMonitoring],
    ];

    // =========================================================================
    // 2. PANEL KIRI: SUB-MODUL & ALAT BANTU KHUSUS (TERPISAH MURNI SESUAI MENU AKTIF)
    // =========================================================================
    if ($isDashboard) {
        // KHUSUS MENU 1: BERANDA GUDANG
        $sidebarSectionTitle = 'Pintasan Beranda';
        $sidebarItems = [
            [
                'icon' => 'fa-gauge-high',
                'label' => 'Ringkasan Metrik (KPI)',
                'badge' => 'Gudang',
                'href' => '#kpi-section',
                'active' => true
            ],
            [
                'icon' => 'fa-triangle-exclamation',
                'label' => 'Prioritas Restock Kritis',
                'badge' => 'Restock',
                'href' => '#restock-section',
                'active' => false
            ],
            [
                'icon' => 'fa-chart-pie',
                'label' => 'Distribusi Kondisi Stok',
                'badge' => 'Fisik',
                'href' => '#stok-section',
                'active' => false
            ],
            [
                'icon' => 'fa-cart-flatbed',
                'label' => 'Top 8 Usulan Order P5',
                'badge' => 'Terbesar',
                'href' => '#usulan-chart-section',
                'active' => false
            ],
        ];
        $quickActions = [
            ['route' => 'pharmacy.entry', 'icon' => 'fa-pen-to-square', 'label' => 'Input LPLPO Baru'],
            ['route' => 'pharmacy.print', 'icon' => 'fa-print', 'label' => 'Cetak Lembar LPLPO', 'blank' => true],
        ];

    } elseif ($isEntry) {
        // KHUSUS MENU 2: PENCATATAN DATA (4 SUB-MODUL ENTRI RESMI)
        $sidebarSectionTitle = 'Sub-Modul Entri Data';
        $sidebarItems = [
            [
                'id' => 'sb-tab-lplpo',
                'icon' => 'fa-boxes-packing',
                'label' => '1. Stok LPLPO (Excel)',
                'badge' => 'LPLPO',
                'href' => route('pharmacy.entry', ['tab' => 'lplpo']),
                'onclick' => "if(window.switchTab){ switchTab('lplpo'); return false; }",
                'active' => ($currentTab === 'lplpo')
            ],
            [
                'id' => 'sb-tab-penyakit',
                'icon' => 'fa-stethoscope',
                'label' => '2. Kunjungan SIMPUS',
                'badge' => 'ICD-10',
                'href' => route('pharmacy.entry', ['tab' => 'penyakit']),
                'onclick' => "if(window.switchTab){ switchTab('penyakit'); return false; }",
                'active' => ($currentTab === 'penyakit')
            ],
            [
                'id' => 'sb-tab-kadaluarsa',
                'icon' => 'fa-calendar-xmark',
                'label' => '3. Berita Acara ED',
                'badge' => 'Audit',
                'href' => route('pharmacy.entry', ['tab' => 'kadaluarsa']),
                'onclick' => "if(window.switchTab){ switchTab('kadaluarsa'); return false; }",
                'active' => ($currentTab === 'kadaluarsa')
            ],
            [
                'id' => 'sb-tab-obat',
                'icon' => 'fa-pills',
                'label' => '4. Registrasi Sediaan',
                'badge' => 'Baru',
                'href' => route('pharmacy.entry', ['tab' => 'obat']),
                'onclick' => "if(window.switchTab){ switchTab('obat'); return false; }",
                'active' => ($currentTab === 'obat')
            ],
        ];
        $quickActions = [
            ['route' => 'pharmacy.print', 'icon' => 'fa-print', 'label' => 'Cetak Lembar LPLPO', 'blank' => true],
            ['route' => 'pharmacy.procurement', 'icon' => 'fa-clipboard-check', 'label' => 'Lihat Usulan Pengadaan P5'],
        ];

    } elseif ($isProcurement) {
        // KHUSUS MENU 3: USULAN PENGADAAN (PERENCANAAN PERIODIK)
        $sidebarSectionTitle = 'Perencanaan Pengadaan';
        $sidebarItems = [
            [
                'icon' => 'fa-clipboard-list',
                'label' => 'Daftar Usulan Order P5',
                'badge' => '150 Item',
                'href' => '#procurement-table',
                'active' => true
            ],
            [
                'icon' => 'fa-filter-circle-dollar',
                'label' => 'Filter Prioritas Restock',
                'badge' => 'Kritis',
                'href' => '#kritis-section',
                'active' => false
            ],
            [
                'icon' => 'fa-shield-halved',
                'label' => 'Cadangan Pengaman 25%',
                'badge' => 'Buffer SS',
                'href' => '#buffer-section',
                'active' => false
            ],
        ];
        $quickActions = [
            ['route' => 'pharmacy.print', 'icon' => 'fa-print', 'label' => 'Cetak Lembar LPLPO Resmi', 'blank' => true],
            ['route' => 'pharmacy.entry', 'icon' => 'fa-pen-to-square', 'label' => 'Koreksi Stok LPLPO'],
        ];

    } else {
        // KHUSUS MENU 4: MONITORING & TREN (SAINS DATA, MACD & AUDIT)
        $sidebarSectionTitle = 'Monitoring & Audit';
        $sidebarItems = [
            [
                'route' => 'pharmacy.medicines',
                'icon' => 'fa-pills',
                'label' => 'Katalog 150 Obat',
                'badge' => '150 Item',
                'active' => request()->routeIs('pharmacy.medicines*')
            ],
            [
                'route' => 'pharmacy.macd',
                'icon' => 'fa-chart-line',
                'label' => 'Tren Permintaan (MACD)',
                'badge' => 'AI Momentum',
                'active' => request()->routeIs('pharmacy.macd*')
            ],
            [
                'route' => 'pharmacy.audit',
                'icon' => 'fa-hourglass-end',
                'label' => 'Audit Obat Kadaluarsa',
                'badge' => 'Nilai ED',
                'active' => request()->routeIs('pharmacy.audit*')
            ],
        ];
        $quickActions = [
            ['route' => 'pharmacy.entry', 'icon' => 'fa-pen-to-square', 'label' => 'Pencatatan LPLPO Baru'],
            ['route' => 'pharmacy.print', 'icon' => 'fa-print', 'label' => 'Cetak Lembar LPLPO', 'blank' => true],
        ];
    }
@endphp

    <!-- Latar gelap saat menu mobile terbuka -->
    <div id="sidebarBackdrop" class="hidden fixed inset-0 z-40 bg-ink/40 backdrop-blur-[2px] lg:hidden" aria-hidden="true"></div>

    <!-- WRAPPER APLIKASI -->
    <div class="min-h-screen p-3 sm:p-5 lg:p-6 flex flex-col gap-5 max-w-[1720px] mx-auto">

        <!-- FLOATING TOP HEADER (4 ALUR UTAMA PUSKESMAS) -->
        <header class="w-full bg-white/95 backdrop-blur-md rounded-full shadow-float px-4 sm:px-6 py-2.5 sm:py-3 flex items-center justify-between gap-3 border border-slate-200/70 sticky top-3 sm:top-4 z-40">
            
            <!-- Sisi Kiri: Brand Logo & Title -->
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('pharmacy.dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-full bg-matcha-700 text-white flex items-center justify-center shadow-md shadow-matcha-700/25 group-hover:scale-105 transition transform">
                        <i class="fa-solid fa-mortar-pestle text-base"></i>
                    </div>
                    <div class="leading-tight">
                        <div class="flex items-center gap-1.5">
                            <span class="text-base font-black tracking-tight text-ink">PRA<span class="text-matcha-700">BOTIK</span></span>
                            <span class="w-2 h-2 rounded-full bg-matcha-600 animate-pulse"></span>
                        </div>
                        <span class="hidden sm:block text-[11px] font-semibold text-slate-500">Puskesmas Teluk Sasah</span>
                    </div>
                </a>
            </div>

            <!-- Bagian Tengah: 4 Menu Alur Utama Faskes (Tanpa Duplikasi) -->
            <nav class="hidden lg:flex items-center bg-[#f1f4ee] rounded-full p-1 gap-1 text-xs font-semibold shadow-inner" aria-label="Navigasi Alur Utama">
                @foreach($navPills as $pill)
                    <a href="{{ route($pill['route']) }}" 
                       class="px-5 py-2 rounded-full transition-all duration-150 whitespace-nowrap flex items-center gap-2 {{ $pill['active'] ? 'bg-white text-ink shadow-sm font-bold' : 'text-slate-600 hover:text-ink hover:bg-white/50' }}">
                        <i class="fa-solid {{ $pill['icon'] }} text-xs {{ $pill['active'] ? 'text-matcha-700' : 'text-slate-400' }}"></i>
                        <span>{{ $pill['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <!-- Sisi Kanan: Sidebar Toggle, Bell, Profil Pengguna & Mobile Hamburger -->
            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                <!-- Tombol Ciutkan / Lebarkan Sidebar Kiri -->
                <button id="sidebarToggleBtn" type="button" title="Ciutkan / Lebarkan Panel Kiri" aria-label="Toggle Panel Kiri"
                        class="hidden lg:flex w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-slate-100 hover:bg-matcha-50 text-slate-600 hover:text-matcha-800 transition items-center justify-center shadow-xs">
                    <i id="sidebarToggleIcon" class="fa-solid fa-angles-left text-xs transition transform"></i>
                </button>

                <!-- Status Periode Dwibulanan Aktif -->
                <div class="hidden sm:flex items-center gap-1.5 px-3.5 py-1.5 bg-matcha-50 rounded-full text-matcha-800 text-xs font-bold border border-matcha-200/50">
                    <i class="fa-solid fa-calendar-days text-[11px] text-matcha-600"></i>
                    <span>2026-P5</span>
                </div>

                <!-- Notifikasi Bell -->
                <a href="{{ route('pharmacy.audit') }}" title="Peringatan Stok & Kadaluarsa"
                   class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-slate-100 hover:bg-matcha-50 text-slate-600 hover:text-matcha-800 relative transition flex items-center justify-center shadow-xs">
                    <i class="fa-regular fa-bell text-sm"></i>
                    <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                </a>

                <!-- Profil Kapsul Pengguna -->
                <div class="flex items-center gap-2 pl-1.5 pr-2.5 py-1 bg-slate-100/90 rounded-full border border-slate-200/60">
                    <div class="w-8 h-8 rounded-full bg-matcha-700 text-white font-extrabold text-xs flex items-center justify-center shadow-xs">
                        SR
                    </div>
                    <div class="hidden md:block text-left leading-none pr-1">
                        <span class="block text-xs font-bold text-ink">Apt. Siti Rahmawati</span>
                        <span class="block text-[10px] text-slate-500 mt-0.5">Farmasi Puskesmas</span>
                    </div>
                    <a href="{{ route('logout') }}" title="Ganti Portal / Keluar" aria-label="Keluar" 
                       class="w-7 h-7 rounded-full bg-white text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition flex items-center justify-center ml-0.5 shadow-2xs">
                        <i class="fa-solid fa-right-from-bracket text-xs"></i>
                    </a>
                </div>

                <!-- Tombol Menu Mobile -->
                <button id="mobileMenuBtn" type="button" aria-controls="sidebar" aria-expanded="false" aria-label="Buka menu navigasi"
                        class="lg:hidden w-9 h-9 rounded-full bg-matcha-50 text-matcha-800 hover:bg-matcha-100 transition flex items-center justify-center">
                    <i class="fa-solid fa-bars text-sm"></i>
                </button>
            </div>
        </header>

        <!-- AREA UTAMA: SIDEBAR DOCK (SUB-MODUL KHUSUS) + KONTEN HALAMAN -->
        <div class="flex-1 flex gap-5 items-start relative">

            <!-- SIDEBAR DOCK KIRI (SUB-MODUL & ALAT KERJA KONTEKSTUAL) -->
            <aside id="sidebar"
                   class="is-compact fixed lg:sticky top-0 lg:top-[5.5rem] left-0 z-50 h-screen lg:h-[calc(100vh-7rem)]
                          bg-white rounded-r-[32px] lg:rounded-[32px] shadow-float-lg lg:shadow-float border border-slate-200/60
                          flex flex-col justify-between py-4 shrink-0 -translate-x-full lg:translate-x-0 select-none overflow-y-auto overflow-x-hidden">

                <!-- Header Sidebar -->
                <div class="px-3 pb-3 border-b border-slate-100 flex items-center justify-between">
                    <div class="sidebar-brand-text flex items-center gap-2.5 px-2">
                        <div class="w-8 h-8 rounded-full bg-matcha-600 text-white flex items-center justify-center text-xs font-bold">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                        </div>
                        <div class="leading-tight">
                            <span class="font-extrabold text-xs text-ink block">{{ $sidebarSectionTitle }}</span>
                            <span class="text-[10px] text-slate-400 block truncate">Puskesmas Teluk Sasah</span>
                        </div>
                    </div>
                    <!-- Mini Collapse Button di dalam sidebar -->
                    <button id="sidebarInnerToggle" type="button" title="Ciutkan Panel" class="w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 hover:text-ink transition flex items-center justify-center mx-auto">
                        <i id="sidebarInnerToggleIcon" class="fa-solid fa-chevron-left text-xs"></i>
                    </button>
                </div>

                <!-- List Sub-Modul Kontekstual -->
                <nav class="flex-1 px-2 py-3 space-y-4" aria-label="Navigasi Sub-Modul">
                    <div>
                        <p class="sidebar-heading px-3 mb-1.5 text-[10px] font-bold tracking-[0.12em] text-slate-400 uppercase">{{ $sidebarSectionTitle }}</p>
                        <div class="space-y-1.5" id="sidebarItemsContainer">
                            @foreach($sidebarItems as $item)
                                @php 
                                    $active = !empty($item['active']);
                                    $linkId = $item['id'] ?? ('sb-link-' . \Illuminate\Support\Str::slug($item['label']));
                                    $href = $item['href'] ?? (isset($item['route']) ? route($item['route']) : '#');
                                @endphp
                                <a href="{{ $href }}"
                                   id="{{ $linkId }}"
                                   title="{{ $item['label'] }}"
                                   @if(!empty($item['onclick'])) onclick="{!! $item['onclick'] !!}" @endif
                                   @if($active) aria-current="page" @endif
                                   class="sidebar-nav-item sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-full text-xs font-semibold transition-all duration-150
                                          {{ $active ? 'bg-matcha-700 text-white shadow-md shadow-matcha-700/25 active-dock-item' : 'text-slate-600 hover:bg-matcha-50 hover:text-matcha-900' }}">
                                    <i class="fa-solid {{ $item['icon'] }} text-center text-sm {{ $active ? 'text-white' : 'text-matcha-600' }}"></i>
                                    <span class="sidebar-text truncate">{{ $item['label'] }}</span>
                                    @if(!empty($item['badge']))
                                        <span class="sidebar-badge ml-auto shrink-0 text-[9px] px-2 py-0.5 rounded-full font-bold {{ $active ? 'bg-white/25 text-white' : 'bg-matcha-100 text-matcha-800' }}">{{ $item['badge'] }}</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Bagian Aksi Cepat / Quick Actions -->
                    <div>
                        <p class="sidebar-heading px-3 mb-1.5 text-[10px] font-bold tracking-[0.12em] text-slate-400 uppercase">Aksi Cepat</p>
                        <div class="space-y-1.5">
                            @foreach($quickActions as $qa)
                                <a href="{{ route($qa['route']) }}"
                                   title="{{ $qa['label'] }}"
                                   @if(!empty($qa['blank'])) target="_blank" rel="noopener" @endif
                                   class="sidebar-link flex items-center gap-3 px-3.5 py-2 rounded-full text-xs font-semibold text-slate-600 hover:bg-slate-100 hover:text-ink transition">
                                    <i class="fa-solid {{ $qa['icon'] }} text-center text-sm text-slate-400"></i>
                                    <span class="sidebar-text truncate">{{ $qa['label'] }}</span>
                                    @if(!empty($qa['blank']))
                                        <i class="sidebar-badge fa-solid fa-arrow-up-right-from-square ml-auto text-[10px] text-slate-300"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </nav>

                <!-- Footer Sidebar Profil -->
                <div class="px-2 pt-2 border-t border-slate-100">
                    <div class="sidebar-profile-text p-2 rounded-2xl bg-matcha-50/70 mb-2 text-center text-[10px] text-slate-500">
                        <p class="font-bold text-matcha-900">SIPA 19890415/2018/021</p>
                        <p>Format Resmi LPLPO Kemenkes</p>
                    </div>
                    <a href="{{ route('logout') }}" title="Ganti Portal / Keluar"
                       class="sidebar-link flex items-center gap-3 px-3.5 py-2 rounded-full text-xs font-bold text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition">
                        <i class="fa-solid fa-right-from-bracket text-center text-sm"></i>
                        <span class="sidebar-text">Ganti Portal</span>
                    </a>
                </div>
            </aside>

            <!-- KONTEN UTAMA -->
            <main class="flex-1 min-w-0">
                @yield('content')

                <!-- Footer Halaman -->
                <footer class="pt-8 pb-4 text-xs text-slate-500">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-2 border-t border-matcha-200/60 pt-5">
                        <p>&copy; 2026 PRABOTIK Farmasi &bull; UPTD Puskesmas Teluk Sasah, Kabupaten Bintan.</p>
                        <p class="text-center sm:text-right">Penanggung Jawab: Apt. Siti Rahmawati, S.Farm &bull; Format Standar LPLPO Kemenkes</p>
                    </div>
                </footer>
            </main>
        </div>
    </div>

    <!-- Script Pengendali Sidebar (Collapse / Expand / Mobile Drawer) -->
    <script>
        (function () {
            var sidebar = document.getElementById('sidebar');
            var toggleBtn = document.getElementById('sidebarToggleBtn');
            var toggleIcon = document.getElementById('sidebarToggleIcon');
            var innerToggle = document.getElementById('sidebarInnerToggle');
            var innerToggleIcon = document.getElementById('sidebarInnerToggleIcon');
            var mobileBtn = document.getElementById('mobileMenuBtn');
            var backdrop = document.getElementById('sidebarBackdrop');

            if (!sidebar) return;

            // Baca preferensi simpanan pengguna dari localStorage (default: compact)
            var savedCompact = localStorage.getItem('prabotik_sidebar_compact');
            var isCompact = savedCompact === null ? true : (savedCompact === '1');

            function applyCompact(compact) {
                isCompact = compact;
                if (isCompact) {
                    sidebar.classList.add('is-compact');
                    if (toggleIcon) toggleIcon.className = 'fa-solid fa-angles-right text-xs transition transform';
                    if (innerToggleIcon) innerToggleIcon.className = 'fa-solid fa-chevron-right text-xs';
                    if (toggleBtn) toggleBtn.setAttribute('title', 'Lebarkan Panel Kiri');
                } else {
                    sidebar.classList.remove('is-compact');
                    if (toggleIcon) toggleIcon.className = 'fa-solid fa-angles-left text-xs transition transform';
                    if (innerToggleIcon) innerToggleIcon.className = 'fa-solid fa-chevron-left text-xs';
                    if (toggleBtn) toggleBtn.setAttribute('title', 'Ciutkan Panel Kiri');
                }
                localStorage.setItem('prabotik_sidebar_compact', isCompact ? '1' : '0');
            }

            // Terapkan saat inisialisasi
            applyCompact(isCompact);

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    applyCompact(!isCompact);
                });
            }

            if (innerToggle) {
                innerToggle.addEventListener('click', function () {
                    applyCompact(!isCompact);
                });
            }

            // Mobile menu drawer
            function setMobileOpen(open) {
                sidebar.classList.toggle('-translate-x-full', !open);
                backdrop.classList.toggle('hidden', !open);
                if (mobileBtn) mobileBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
            }

            if (mobileBtn && backdrop) {
                mobileBtn.addEventListener('click', function () {
                    var isClosed = sidebar.classList.contains('-translate-x-full');
                    setMobileOpen(isClosed);
                });
                backdrop.addEventListener('click', function () { setMobileOpen(false); });
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') setMobileOpen(false);
                });
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>
