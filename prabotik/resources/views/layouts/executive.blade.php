<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PRABOTIK Eksekutif') &bull; Kepala UPTD Puskesmas Teluk Sasah</title>

    <!-- Tailwind CSS (CDN) + palet Matcha & Sage -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Skala Matcha & Sage (Uxintace). Skala yang sama dipakai untuk "emerald"
        // agar halaman lama (entri data, katalog, MACD, audit, dll.) otomatis
        // ikut berwarna matcha tanpa perlu diubah.
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
    </style>
    @stack('styles')
</head>
<body class="min-h-full bg-sage text-ink antialiased overflow-x-hidden">

@php
    // Menu navigasi Portal Eksekutif (nama rute tidak diubah)
    $approved = (bool) session('p5_procurement_approved');
    $navGroups = [
        'Menu' => [
            ['route' => 'executive.dashboard',    'icon' => 'fa-building-columns', 'label' => 'Ringkasan Eksekutif & SPM'],
            ['route' => 'executive.disposition',  'icon' => 'fa-stamp',            'label' => 'Disposisi Pengadaan P5',
                'badge' => $approved ? 'Disahkan' : 'Otorisasi', 'tone' => $approved ? 'ok' : 'warn'],
        ],
        'Laporan & Kebijakan' => [
            ['route' => 'executive.diseases',     'icon' => 'fa-heart-pulse',      'label' => 'Morbiditas SIMPUS',  'badge' => '10 Besar'],
            ['route' => 'executive.financial',    'icon' => 'fa-sack-dollar',      'label' => 'Audit Kerugian Obat ED'],
            ['route' => 'executive.correlations', 'icon' => 'fa-notes-medical',    'label' => 'Standar Terapi & Peresepan'],
        ],
        'Dokumen & Profil' => [
            ['route' => 'executive.print',        'icon' => 'fa-print',            'label' => 'Cetak LPLPO Resmi Ber-NIP', 'blank' => true],
            ['route' => 'executive.profile',      'icon' => 'fa-hospital-user',    'label' => 'Profil Puskesmas & Wilayah'],
        ],
    ];
@endphp

    <!-- Header mobile -->
    <div class="lg:hidden sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-matcha-100 px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-matcha-600 text-white flex items-center justify-center">
                <i class="fa-solid fa-user-doctor"></i>
            </div>
            <div class="leading-tight">
                <span class="block font-extrabold text-sm text-ink tracking-wide">PRABOTIK <span class="text-matcha-600">EKSEKUTIF</span></span>
                <span class="block text-[11px] text-slate-500">Kepala Puskesmas Teluk Sasah</span>
            </div>
        </div>
        <button id="mobileMenuBtn" type="button" aria-controls="sidebar" aria-expanded="false" aria-label="Buka menu navigasi"
                class="w-10 h-10 rounded-full bg-matcha-50 text-matcha-800 hover:bg-matcha-100 transition flex items-center justify-center">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>

    <!-- Latar gelap saat menu mobile terbuka -->
    <div id="sidebarBackdrop" class="hidden fixed inset-0 z-40 bg-ink/40 backdrop-blur-[2px] lg:hidden" aria-hidden="true"></div>

    <div class="lg:flex">

        <!-- SIDEBAR KIRI -->
        <aside id="sidebar"
               class="fixed lg:sticky top-0 left-0 z-50 w-[19rem] h-screen lg:h-[calc(100vh-2rem)] lg:m-4 lg:shrink-0
                      bg-white rounded-r-[28px] lg:rounded-[28px] shadow-float-lg lg:shadow-float
                      flex flex-col -translate-x-full lg:translate-x-0 transition-transform duration-300 select-none">

            <!-- Brand -->
            <div class="px-6 pt-7 pb-5">
                <a href="{{ route('executive.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-matcha-600 text-white flex items-center justify-center shadow-md shadow-matcha-600/25">
                        <i class="fa-solid fa-user-doctor text-lg"></i>
                    </div>
                    <div class="leading-tight">
                        <h1 class="text-lg font-extrabold tracking-wide text-ink">PRA<span class="text-matcha-600">BOTIK</span></h1>
                        <span class="text-[11px] font-semibold text-slate-500">Portal Eksekutif Kepala Faskes</span>
                    </div>
                </a>
                <div class="mt-4 px-3.5 py-2 rounded-2xl bg-matcha-50 flex items-center justify-between text-[11px]">
                    <span class="text-slate-500 font-medium">Satker</span>
                    <span class="font-bold text-matcha-800 truncate ml-2">UPTD Puskesmas Teluk Sasah</span>
                </div>
            </div>

            <!-- Navigasi -->
            <nav class="flex-1 px-4 pb-4 space-y-5 overflow-y-auto" aria-label="Navigasi Portal Eksekutif">
                @foreach($navGroups as $groupLabel => $items)
                <div>
                    <p class="px-3 mb-1.5 text-[10px] font-bold tracking-[0.14em] text-slate-400 uppercase">{{ $groupLabel }}</p>
                    <div class="space-y-1">
                        @foreach($items as $item)
                            @php $active = empty($item['blank']) && request()->routeIs($item['route']); @endphp
                            <a href="{{ route($item['route']) }}"
                               @if(!empty($item['blank'])) target="_blank" rel="noopener" @endif
                               @if($active) aria-current="page" @endif
                               class="flex items-center gap-3 px-4 py-2.5 rounded-full text-[13px] font-semibold transition-all duration-150
                                      {{ $active ? 'bg-matcha-600 text-white shadow-md shadow-matcha-600/25' : 'text-slate-600 hover:bg-matcha-50 hover:text-matcha-800' }}">
                                <i class="fa-solid {{ $item['icon'] }} w-5 text-center text-[15px] {{ $active ? 'text-white' : 'text-matcha-500' }}"></i>
                                <span class="truncate">{{ $item['label'] }}</span>
                                @if(!empty($item['badge']))
                                    <span class="ml-auto shrink-0 text-[9px] px-2 py-0.5 rounded-full font-bold {{ $active ? 'bg-white/25 text-white' : (($item['tone'] ?? 'ok') === 'warn' ? 'bg-amber-100 text-amber-800' : 'bg-matcha-100 text-matcha-800') }}">{{ $item['badge'] }}</span>
                                @elseif(!empty($item['blank']))
                                    <i class="fa-solid fa-arrow-up-right-from-square ml-auto text-[10px] text-slate-300"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </nav>

            <!-- Kartu profil pengguna -->
            <div class="p-4">
                <div class="rounded-3xl bg-matcha-50 p-3.5 flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-matcha-600 text-white flex items-center justify-center font-extrabold text-sm shrink-0">BU</div>
                    <div class="flex-1 min-w-0 leading-tight">
                        <p class="text-xs font-bold text-ink truncate">dr. Bambang Utoyo, M.M.</p>
                        <p class="text-[11px] text-slate-500 truncate">Kepala UPTD Puskesmas</p>
                        <p class="text-[9px] text-matcha-700 font-mono truncate">NIP. 19780512 200604 1 008</p>
                    </div>
                    <a href="{{ route('logout') }}" title="Kunci & Keluar Portal" aria-label="Kunci & Keluar Portal"
                       class="w-9 h-9 rounded-full bg-white text-matcha-700 hover:bg-matcha-600 hover:text-white shadow-sm transition flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-right-from-bracket text-sm"></i>
                    </a>
                </div>
            </div>
        </aside>

        <!-- AREA UTAMA -->
        <div class="flex-1 min-w-0 flex flex-col min-h-screen">

            <!-- Topbar -->
            <header class="px-4 sm:px-6 lg:px-8 pt-5 lg:pt-6">
                <div class="max-w-7xl mx-auto flex items-center justify-between gap-3 bg-white rounded-full shadow-float px-3 sm:px-4 py-2.5">
                    <div class="flex items-center gap-2.5 min-w-0 pl-1">
                        <span class="hidden sm:inline-flex px-3 py-1 rounded-full bg-matcha-100 text-matcha-800 text-[10px] font-extrabold uppercase tracking-wider">Aplikasi Eksekutif</span>
                        <div class="flex items-center gap-2 text-xs font-medium text-slate-500 min-w-0">
                            <span class="hidden md:inline text-slate-400 whitespace-nowrap">UPTD Puskesmas Teluk Sasah</span>
                            <i class="hidden md:inline fa-solid fa-chevron-right text-[9px] text-slate-300"></i>
                            <span class="text-matcha-800 font-bold truncate">@yield('page_title', 'Ringkasan Eksekutif & Pengawasan')</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <div class="hidden sm:flex items-center gap-2 px-3.5 py-1.5 bg-matcha-50 rounded-full text-matcha-800 text-xs font-bold">
                            <span class="w-2 h-2 rounded-full bg-matcha-600 animate-pulse"></span>
                            <span>Periode 2026-P5</span>
                        </div>
                        <a href="{{ route('logout') }}"
                           class="px-3.5 py-1.5 rounded-full bg-matcha-600 hover:bg-matcha-700 text-white text-xs font-bold transition flex items-center gap-1.5">
                            <i class="fa-solid fa-lock text-[11px]"></i>
                            <span class="hidden sm:inline">Kunci & Ganti Portal</span>
                        </a>
                    </div>
                </div>
            </header>

            <main class="flex-1">
                @yield('content')
            </main>

            <footer class="px-4 sm:px-6 lg:px-8 pb-6 pt-2 text-xs text-slate-500">
                <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 border-t border-matcha-200/60 pt-5">
                    <p>&copy; 2026 PRABOTIK Eksekutif &bull; UPTD Puskesmas Teluk Sasah, Kabupaten Bintan.</p>
                    <p class="text-center sm:text-right">Pengesah: dr. Bambang Utoyo, M.M. (NIP. 19780512 200604 1 008) &bull; Format Standar LPLPO Dinkes Bintan</p>
                </div>
            </footer>
        </div>
    </div>

    <script>
        (function () {
            var btn = document.getElementById('mobileMenuBtn');
            var sidebar = document.getElementById('sidebar');
            var backdrop = document.getElementById('sidebarBackdrop');
            if (!btn || !sidebar || !backdrop) return;

            function setOpen(open) {
                sidebar.classList.toggle('-translate-x-full', !open);
                backdrop.classList.toggle('hidden', !open);
                btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            }
            btn.addEventListener('click', function () {
                setOpen(sidebar.classList.contains('-translate-x-full'));
            });
            backdrop.addEventListener('click', function () { setOpen(false); });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setOpen(false); });
        })();
    </script>
    @stack('scripts')
</body>
</html>
