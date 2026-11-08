<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Lab Sains Data') &bull; PRABOTIK CRISP-DM</title>

    <!-- Tailwind CSS CDN + palet Matcha & Sage -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        const matcha = {
            50:  '#F3F8EE', 100: '#E7F5DC', 200: '#CFE1B9', 300: '#B6C99B',
            400: '#98A77C', 500: '#88976C', 600: '#728156', 700: '#5C6A44',
            800: '#3E4A2F', 900: '#1A3B22', 950: '#142416'
        };
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'Inter', 'ui-sans-serif', 'sans-serif'] },
                    colors: {
                        matcha: matcha, emerald: matcha,
                        sage: '#F4F6F2', ink: '#142416',
                    },
                    boxShadow: {
                        float: '0 8px 30px rgb(0 0 0 / 0.04)',
                        'float-lg': '0 14px 44px rgb(20 36 22 / 0.09)'
                    }
                }
            }
        }
    </script>

    <!-- CDN Assets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; background-color: #F4F6F2; }
        .tnum { font-variant-numeric: tabular-nums; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #F4F6F2; }
        ::-webkit-scrollbar-thumb { background: #CFE1B9; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #B6C99B; }

        /* Sidebar transitions */
        #lab-sidebar { transition: width 0.25s ease; width: 280px; }
        #lab-sidebar.collapsed { width: 76px; }
        #lab-sidebar.collapsed .sidebar-label,
        #lab-sidebar.collapsed .sidebar-section-title,
        #lab-sidebar.collapsed .sidebar-brand-text,
        #lab-sidebar.collapsed .sidebar-footer-text { display: none !important; }
        #lab-sidebar.collapsed .nav-item { justify-content: center; padding: 0.625rem 0; }
        #lab-sidebar.collapsed .nav-item i { margin-right: 0; }
        #lab-sidebar.collapsed .collapse-icon { transform: rotate(180deg); }

        /* Nav active state */
        .sub-nav-item {
            transition: all 0.15s ease;
            color: #CFE1B9;
        }
        .sub-nav-item:hover {
            background-color: rgba(207, 225, 185, 0.15);
            color: #FFFFFF;
        }
        .sub-nav-item.active-sub {
            background-color: #CFE1B9;
            color: #1A3B22 !important;
            font-weight: 700;
        }

        /* Sticky drug switcher */
        #drug-switcher-bar { position: sticky; top: 0; z-index: 30; }

        /* Card styles */
        .lab-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 1px 4px rgba(26,59,34,0.06);
            border: 1px solid #E7F5DC;
            scroll-margin-top: 5rem;
        }

        /* Formula display */
        .formula-block {
            background: #F3F8EE;
            border-left: 3px solid #5C6A44;
            padding: 0.75rem 1rem;
            border-radius: 0 0.5rem 0.5rem 0;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: #142416;
        }

        /* Tab nav pills */
        .tab-nav-link {
            padding: 0.5rem 1rem;
            border-radius: 0.625rem;
            font-weight: 600;
            font-size: 0.875rem;
            white-space: nowrap;
            transition: all 0.15s;
            color: #CFE1B9;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .tab-nav-link:hover { background: rgba(207,225,185,0.2); color: white; }
        .tab-nav-link.active-tab {
            background: #CFE1B9;
            color: #1A3B22 !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        /* Badges */
        .badge-bullish { background: #CFE1B9; color: #1A3B22; font-weight: 700; }
        .badge-bearish { background: #FEE2E2; color: #991B1B; font-weight: 700; }
        .badge-neutral { background: #E2E8F0; color: #334155; font-weight: 600; }

        @keyframes fadeInUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
        .anim-fade { animation: fadeInUp 0.35s ease both; }
    </style>

    @stack('head')
</head>
<body class="h-full bg-sage text-ink">

<div class="flex h-screen overflow-hidden">

    <!-- ==================================================================
         SIDEBAR KIRI (Orthogonal Navigation Pattern: Micro Navigation / Pintasan Sub-Modul)
         ================================================================== -->
    <aside id="lab-sidebar" class="flex flex-col shrink-0 overflow-hidden" style="background:#1A3B22;">

        <!-- Brand Header -->
        <div class="flex items-center justify-between px-4 py-3.5 border-b border-matcha-800 min-h-[64px]">
            <a href="{{ route('lab.eda') }}" class="flex items-center gap-2.5 min-w-0 flex-1">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 shadow-sm" style="background:#5C6A44;">
                    <i class="fa-solid fa-flask-vial text-matcha-200 text-sm"></i>
                </div>
                <div class="sidebar-brand-text min-w-0">
                    <div class="text-white font-extrabold text-sm leading-tight tracking-wide">PRABOTIK LAB</div>
                    <div class="text-matcha-300 text-[11px] truncate">CRISP-DM · Kelompok 23</div>
                </div>
            </a>
            <button id="sidebar-toggle" title="Ciutkan / Lebarkan Menu"
                    class="text-matcha-300 hover:text-white p-1.5 rounded-lg hover:bg-matcha-800/50 transition-colors shrink-0">
                <i class="fa-solid fa-angles-left collapse-icon transition-transform duration-300"></i>
            </button>
        </div>

        <!-- Scrollable Navigation -->
        <nav id="sidebar-nav" class="flex-1 overflow-y-auto px-2.5 py-3 space-y-1">

            {{-- 1. PINTASAN SUB-MODUL DINAMIS BERDASARKAN TAB AKTIF --}}
            <div class="sidebar-section-title px-2 pb-1 text-matcha-400 text-[10px] font-bold uppercase tracking-wider">
                Pintasan Sub-Modul
            </div>

            @if(request()->routeIs('lab.eda'))
                {{-- Sub-modul Tab 1: Pipeline & EDA --}}
                <a href="#alur-crisp-dm" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="alur-crisp-dm">
                    <i class="fa-solid fa-route w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Alur CRISP-DM 6 Fase</span>
                </a>
                <a href="#ringkasan-statistik" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="ringkasan-statistik">
                    <i class="fa-solid fa-chart-pie w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Ringkasan Statistik KPI</span>
                </a>
                <a href="#inspeksi-obat" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="inspeksi-obat">
                    <i class="fa-solid fa-magnifying-glass-chart w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Inspeksi Cepat Sediaan</span>
                </a>
                <a href="#distribusi-model" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="distribusi-model">
                    <i class="fa-solid fa-chart-column w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Distribusi Model &amp; ICD-10</span>
                </a>
                <a href="#matriks-pareto" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="matriks-pareto">
                    <i class="fa-solid fa-layer-group w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Matriks Pareto ABC-VEN</span>
                </a>
                <a href="#kerugian-ed" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="kerugian-ed">
                    <i class="fa-solid fa-triangle-exclamation w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Audit Kerugian Obat ED</span>
                </a>
                <a href="#tabel-icd10" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="tabel-icd10">
                    <i class="fa-solid fa-stethoscope w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Top 10 ICD-10 SIMPUS</span>
                </a>
                <a href="#profil-statistik" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="profil-statistik">
                    <i class="fa-solid fa-table-list w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Profil Statistik 150 Obat</span>
                </a>

            @elseif(request()->routeIs('lab.preprocess'))
                {{-- Sub-modul Tab 2: Pra-Proses --}}
                <a href="#ringkasan-praproses" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="ringkasan-praproses">
                    <i class="fa-solid fa-filter w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Ringkasan Pra-Proses</span>
                </a>
                <a href="#formulasi-matematis" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="formulasi-matematis">
                    <i class="fa-solid fa-square-root-variable w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Formulasi Censored &amp; SS</span>
                </a>
                <a href="#grafik-rekonstruksi" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="grafik-rekonstruksi">
                    <i class="fa-solid fa-chart-area w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Grafik Rekonstruksi Demand</span>
                </a>
                <a href="#tabel-before-after" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="tabel-before-after">
                    <i class="fa-solid fa-code-compare w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Komparasi Before vs After</span>
                </a>
                <a href="#top-safety-stock" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="top-safety-stock">
                    <i class="fa-solid fa-shield-halved w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Top 10 Safety Stock</span>
                </a>

            @elseif(request()->routeIs('lab.modeling'))
                {{-- Sub-modul Tab 3: Arena Model --}}
                <a href="#status-macd" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="status-macd">
                    <i class="fa-solid fa-wave-square w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Status Sinyal MACD</span>
                </a>
                <a href="#arena-komparasi" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="arena-komparasi">
                    <i class="fa-solid fa-chart-line w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Arena 4 Model Peramalan</span>
                </a>
                <a href="#osilator-macd" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="osilator-macd">
                    <i class="fa-solid fa-chart-simple w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Osilator Momentum MACD</span>
                </a>
                <a href="#formulasi-algoritma" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="formulasi-algoritma">
                    <i class="fa-solid fa-square-root-variable w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Formulasi 4 Algoritma</span>
                </a>
                <a href="#leaderboard-wape" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="leaderboard-wape">
                    <i class="fa-solid fa-trophy w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Leaderboard WAPE Benchmark</span>
                </a>
                <a href="#tabel-jejak-step" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="tabel-jejak-step">
                    <i class="fa-solid fa-calculator w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Jejak Hitung Step-by-Step</span>
                </a>
                <a href="#daftar-momentum" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="daftar-momentum">
                    <i class="fa-solid fa-arrow-trend-up w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Momentum Bullish &amp; Bearish</span>
                </a>

            @else
                {{-- Sub-modul Tab 4: Korelasi & Evaluasi --}}
                <a href="#ringkasan-akurasi" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="ringkasan-akurasi">
                    <i class="fa-solid fa-bullseye w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Ringkasan Akurasi BSA</span>
                </a>
                <a href="#heatmap-korelasi" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="heatmap-korelasi">
                    <i class="fa-solid fa-table-cells w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Heatmap Korelasi ICD-10</span>
                </a>
                <a href="#histogram-akurasi" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="histogram-akurasi">
                    <i class="fa-solid fa-chart-column w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Histogram Sebaran Akurasi</span>
                </a>
                <a href="#formulasi-evaluasi" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="formulasi-evaluasi">
                    <i class="fa-solid fa-square-root-variable w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Formulasi Metrik Evaluasi</span>
                </a>
                <a href="#diagnostik-residual" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="diagnostik-residual">
                    <i class="fa-solid fa-water-ladder w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Diagnostik Sebaran Residual</span>
                </a>
                <a href="#leaderboard-benchmark" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="leaderboard-benchmark">
                    <i class="fa-solid fa-ranking-star w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Leaderboard WAPE &amp; Bias</span>
                </a>
                <a href="#prediksi-kebutuhan" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs" data-target="prediksi-kebutuhan">
                    <i class="fa-solid fa-pills w-4 text-center shrink-0"></i>
                    <span class="sidebar-label truncate">Prediksi Kebutuhan P5</span>
                </a>
                <a href="#mode-sidang" class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-bold text-amber-300" data-target="mode-sidang">
                    <i class="fa-solid fa-graduation-cap w-4 text-center shrink-0 text-amber-300"></i>
                    <span class="sidebar-label truncate">Mode Sidang (Ringkasan)</span>
                </a>
            @endif

            {{-- 2. AKSI CEPAT DOCK BAWAH --}}
            <div class="sidebar-section-title px-2 pt-4 pb-1 text-matcha-400 text-[10px] font-bold uppercase tracking-wider">
                Aksi Cepat &amp; Alih Portal
            </div>

            <a href="{{ route('lab.export') }}" target="_blank"
               class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold text-white bg-matcha-700/60 hover:bg-matcha-700 transition-colors">
                <i class="fa-solid fa-file-arrow-down w-4 text-center shrink-0 text-matcha-200"></i>
                <span class="sidebar-label truncate">Ekspor CSV Bersih</span>
            </a>

            <a href="{{ route('pharmacy.dashboard') }}"
               class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs">
                <i class="fa-solid fa-pills w-4 text-center shrink-0 text-matcha-300"></i>
                <span class="sidebar-label truncate">Portal Bagian Farmasi</span>
            </a>

            <a href="{{ route('executive.dashboard') }}"
               class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs">
                <i class="fa-solid fa-user-tie w-4 text-center shrink-0 text-matcha-300"></i>
                <span class="sidebar-label truncate">Portal Kepala Puskesmas</span>
            </a>

            <a href="{{ route('gateway') }}"
               class="sub-nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-xs">
                <i class="fa-solid fa-house w-4 text-center shrink-0 text-matcha-300"></i>
                <span class="sidebar-label truncate">Beranda Gateway Utama</span>
            </a>
        </nav>

        <!-- Footer Info -->
        <div class="sidebar-footer-text border-t border-matcha-800 px-3.5 py-3">
            <div class="text-matcha-300 text-[11px] font-semibold">Kelompok 23 · INF11121</div>
            <div class="text-matcha-400 text-[10px]">UPTD Puskesmas Teluk Sasah</div>
        </div>
    </aside>

    <!-- ==================================================================
         KONTEN UTAMA & HEADER MAKRO
         ================================================================== -->
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">

        <!-- Header Makro: 4 Tab Navigasi Utama -->
        <header class="shrink-0 flex items-center justify-between px-6 py-0 border-b border-matcha-200" style="background:#1A3B22; min-height:64px;">
            <div class="flex items-center gap-2 overflow-x-auto py-2">
                <a href="{{ route('lab.eda') }}"
                   class="tab-nav-link {{ request()->routeIs('lab.eda') ? 'active-tab' : '' }}">
                    <i class="fa-solid fa-diagram-project"></i>
                    <span>1. Pipeline &amp; EDA</span>
                </a>
                <a href="{{ route('lab.preprocess') }}"
                   class="tab-nav-link {{ request()->routeIs('lab.preprocess') ? 'active-tab' : '' }}">
                    <i class="fa-solid fa-filter"></i>
                    <span>2. Pra-Proses &amp; Imputasi</span>
                </a>
                <a href="{{ route('lab.modeling') }}"
                   class="tab-nav-link {{ request()->routeIs('lab.modeling') ? 'active-tab' : '' }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>3. Arena Model &amp; MACD</span>
                </a>
                <a href="{{ route('lab.evaluation') }}"
                   class="tab-nav-link {{ request()->routeIs('lab.evaluation') ? 'active-tab' : '' }}">
                    <i class="fa-solid fa-microscope"></i>
                    <span>4. Korelasi &amp; Evaluasi</span>
                </a>
            </div>

            <div class="hidden lg:flex items-center gap-3 shrink-0 pl-4">
                <a href="{{ route('lab.export') }}" target="_blank"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white transition-all hover:opacity-90"
                   style="background:#5C6A44;" title="Unduh CSV 150 Sediaan Obat">
                    <i class="fa-solid fa-download"></i> Ekspor CSV
                </a>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold" style="background:#CFE1B9; color:#1A3B22;">
                    <i class="fa-solid fa-circle text-[6px]"></i> CRISP-DM
                </span>
            </div>
        </header>

        <!-- Sticky Control Bar: Searchable Drug Switcher + Temporal Lens Toggle -->
        <div id="drug-switcher-bar" class="bg-white border-b border-matcha-200 px-6 py-2.5 flex items-center gap-4 shadow-sm shrink-0">
            <div class="flex items-center gap-2.5 flex-1 min-w-0">
                <i class="fa-solid fa-magnifying-glass text-matcha-600 text-sm shrink-0"></i>
                <div class="relative flex-1 max-w-md">
                    <input type="text" id="drug-search-input"
                           placeholder="Ketik nama obat untuk inspeksi (contoh: Parasetamol, Amlodipin)..."
                           value="Parasetamol tablet 500 mg"
                           class="w-full text-xs sm:text-sm border border-matcha-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-matcha-400 bg-sage pr-8"
                           autocomplete="off">
                    <button id="drug-clear-btn" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xs">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <ul id="drug-dropdown" class="absolute z-50 w-full bg-white border border-matcha-200 rounded-lg shadow-float-lg mt-1 max-h-60 overflow-auto hidden text-xs sm:text-sm"></ul>
                </div>
            </div>

            <!-- Sakelar Lensa Resolusi Waktu -->
            <div class="flex items-center gap-2 shrink-0">
                <span class="text-xs text-gray-500 font-medium hidden md:inline">Lensa Waktu:</span>
                <button id="lens-bimonthly" onclick="setLens('bimonthly')"
                        class="lens-btn px-3 py-1 rounded-lg text-xs font-bold border transition-all"
                        style="background:#CFE1B9; color:#1A3B22; border-color:#5C6A44;"
                        title="Resolusi Dwibulanan (22 Periode: LPLPO Dinkes)">
                    22 Periode (Dwibulanan)
                </button>
                <button id="lens-monthly" onclick="setLens('monthly')"
                        class="lens-btn px-3 py-1 rounded-lg text-xs font-medium border border-matcha-200 text-gray-600 hover:bg-sage transition-all"
                        title="Resolusi Bulanan (36 Bulan / 12 Bulan)">
                    36 Bln (Bulanan)
                </button>
            </div>

            <!-- Drug Info Badge (Aktif) -->
            <div id="drug-info-badge" class="hidden xl:flex items-center gap-2 text-xs shrink-0">
                <span id="drug-info-name" class="font-bold text-matcha-900 truncate max-w-[180px]">Parasetamol tablet 500 mg</span>
                <span id="drug-info-model" class="px-2 py-0.5 rounded-full font-semibold" style="background:#E7F5DC; color:#3E4A2F;">SMA (k=2)</span>
                <span id="drug-info-acc" class="px-2 py-0.5 rounded-full text-white font-bold" style="background:#1A3B22;">Akurasi 95%</span>
            </div>
        </div>

        <!-- Scrollable Main Content -->
        <main id="lab-main-scroll" class="flex-1 overflow-y-auto px-6 py-6 scroll-smooth">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="shrink-0 border-t border-matcha-200 bg-white px-6 py-2.5 flex items-center justify-between text-xs text-gray-500">
            <div>
                <strong class="text-matcha-800">PRABOTIK</strong> &bull; Portal Sains Data &amp; Laboratorium CRISP-DM &bull;
                Mata Kuliah Penambangan Data INF11121 &bull; Kelompok 23 &bull; UMRAH
            </div>
            <div class="hidden sm:inline text-matcha-700">
                UPTD Puskesmas Teluk Sasah, Kab. Bintan
            </div>
        </footer>
    </div>
</div>

<!-- ======================================================================
     Global JavaScript Engine
     ====================================================================== -->
<script>
// ---- 1. Sidebar Toggle ----
(function () {
    const sb  = document.getElementById('lab-sidebar');
    const btn = document.getElementById('sidebar-toggle');
    if (!sb || !btn) return;
    btn.addEventListener('click', () => sb.classList.toggle('collapsed'));
})();

// ---- 2. Scroll-Spy Dinamis untuk Pintasan Sub-Modul Sidebar ----
(function () {
    const mainScroll = document.getElementById('lab-main-scroll');
    const subNavLinks = document.querySelectorAll('.sub-nav-item[data-target]');
    if (!mainScroll || !subNavLinks.length) return;

    // Smooth scroll on click
    subNavLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-target');
            const targetEl = document.getElementById(targetId);
            if (targetEl) {
                targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                subNavLinks.forEach(l => l.classList.remove('active-sub'));
                this.classList.add('active-sub');
            }
        });
    });

    // Observer saat scrolling
    const targets = Array.from(subNavLinks).map(l => document.getElementById(l.getAttribute('data-target'))).filter(Boolean);
    if (!targets.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.id;
                subNavLinks.forEach(l => {
                    if (l.getAttribute('data-target') === id) {
                        l.classList.add('active-sub');
                    } else {
                        l.classList.remove('active-sub');
                    }
                });
            }
        });
    }, {
        root: mainScroll,
        threshold: 0.25,
        rootMargin: '-80px 0px -50% 0px'
    });

    targets.forEach(t => observer.observe(t));

    // Set item pertama sebagai default aktif
    if (subNavLinks.length > 0) {
        subNavLinks[0].classList.add('active-sub');
    }
})();

// ---- 3. Drug Switcher & Instant Inspector ----
const allDrugs = @json($allMedicines ?? []);
let activeLens = 'bimonthly';
let selectedDrugId = 2; // Default: Parasetamol tablet 500 mg

function setLens(lens) {
    activeLens = lens;
    const bBtn = document.getElementById('lens-bimonthly');
    const mBtn = document.getElementById('lens-monthly');
    if (lens === 'bimonthly') {
        bBtn.style.background = '#CFE1B9'; bBtn.style.color = '#1A3B22'; bBtn.style.borderColor = '#5C6A44';
        mBtn.style.background = ''; mBtn.style.color = ''; mBtn.style.borderColor = '';
    } else {
        mBtn.style.background = '#CFE1B9'; mBtn.style.color = '#1A3B22'; mBtn.style.borderColor = '#5C6A44';
        bBtn.style.background = ''; bBtn.style.color = ''; bBtn.style.borderColor = '';
    }
    if (selectedDrugId) {
        loadDrugInspection(selectedDrugId);
    }
}

async function loadDrugInspection(drugId) {
    selectedDrugId = drugId;
    try {
        const res  = await fetch(`/lab/api/inspect/${drugId}?lens=${activeLens}`);
        const data = await res.json();
        updateDrugInfoBadge(data);
        // Siarkan event custom agar seluruh kanvas grafik dan tabel di halaman aktif langsung diperbarui
        document.dispatchEvent(new CustomEvent('drugLoaded', { detail: data }));
    } catch (e) {
        console.error('Drug inspection failed:', e);
    }
}

function updateDrugInfoBadge(data) {
    const badge = document.getElementById('drug-info-badge');
    const name  = document.getElementById('drug-info-name');
    const model = document.getElementById('drug-info-model');
    const acc   = document.getElementById('drug-info-acc');
    const input = document.getElementById('drug-search-input');

    if (input && data.nama_obat) input.value = data.nama_obat;
    if (!badge) return;
    badge.classList.remove('hidden');
    badge.classList.add('flex');
    name.textContent  = data.nama_obat;
    model.textContent = data.model_terbaik;
    acc.textContent   = `Akurasi ${data.evaluasi_akurasi}%`;
}

// Autocomplete Search Box
(function () {
    const input    = document.getElementById('drug-search-input');
    const dropdown = document.getElementById('drug-dropdown');
    const clearBtn = document.getElementById('drug-clear-btn');
    if (!input || !dropdown) return;

    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            input.value = '';
            input.focus();
            dropdown.classList.add('hidden');
        });
    }

    input.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        dropdown.innerHTML = '';
        if (!q || q.length < 2) { dropdown.classList.add('hidden'); return; }

        const matches = allDrugs.filter(d => d.nama_obat.toLowerCase().includes(q)).slice(0, 10);
        if (!matches.length) {
            dropdown.innerHTML = '<li class="px-3 py-2 text-gray-400 text-xs italic">Tidak ada obat yang cocok</li>';
            dropdown.classList.remove('hidden');
            return;
        }

        matches.forEach(d => {
            const li = document.createElement('li');
            li.className = 'px-3 py-2 cursor-pointer hover:bg-matcha-50 flex items-center justify-between border-b border-matcha-50 last:border-0';
            li.innerHTML = `
                <div class="flex items-center gap-2 truncate">
                    <i class="fa-solid fa-capsules text-matcha-600 text-xs shrink-0"></i>
                    <span class="font-medium text-gray-800 truncate">${d.nama_obat}</span>
                </div>
                <div class="flex items-center gap-1.5 shrink-0 ml-2">
                    <span class="px-1.5 py-0.5 rounded text-[10px] bg-matcha-100 text-matcha-800">${d.model_terbaik}</span>
                    <span class="px-1.5 py-0.5 rounded text-[10px] bg-matcha-700 text-white font-bold">${d.evaluasi_akurasi}%</span>
                </div>
            `;
            li.addEventListener('click', () => {
                input.value = d.nama_obat;
                dropdown.classList.add('hidden');
                loadDrugInspection(d.id);
            });
            dropdown.appendChild(li);
        });
        dropdown.classList.remove('hidden');
    });

    document.addEventListener('click', (e) => {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // Pemuatan default pada startup: Parasetamol tablet 500 mg (ID: 2)
    window.addEventListener('DOMContentLoaded', () => {
        loadDrugInspection(2);
    });
})();
</script>

@stack('scripts')
</body>
</html>
