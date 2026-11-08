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
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #F4F6F2; }
        ::-webkit-scrollbar-thumb { background: #CFE1B9; border-radius: 4px; }

        /* Sidebar transitions */
        #lab-sidebar { transition: width 0.25s ease; width: 280px; }
        #lab-sidebar.collapsed { width: 76px; }
        #lab-sidebar.collapsed .sidebar-label,
        #lab-sidebar.collapsed .sidebar-section-title { display: none; }
        #lab-sidebar.collapsed .nav-item { justify-content: center; padding: 0.625rem 0; }
        #lab-sidebar.collapsed .nav-item i { margin-right: 0; }
        #lab-sidebar.collapsed .sidebar-brand-text { display: none; }
        #lab-sidebar.collapsed .collapse-icon { transform: rotate(180deg); }
        #main-content { transition: margin-left 0.25s ease; }

        /* Nav active state */
        .nav-item.active {
            background-color: #CFE1B9;
            color: #1A3B22;
            font-weight: 600;
        }
        .nav-item:hover:not(.active) { background-color: rgba(207,225,185,0.25); }

        /* Sticky drug switcher */
        #drug-switcher-bar { position: sticky; top: 0; z-index: 40; }

        /* Card styles */
        .lab-card { background: white; border-radius: 0.75rem; box-shadow: 0 1px 4px rgba(26,59,34,0.07); border: 1px solid #E7F5DC; }

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
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            white-space: nowrap;
            transition: all 0.15s;
            color: #CFE1B9;
        }
        .tab-nav-link:hover { background: rgba(207,225,185,0.2); color: white; }
        .tab-nav-link.active-tab {
            background: #CFE1B9;
            color: #1A3B22;
            font-weight: 700;
        }

        /* Badge */
        .badge-bullish { background: #CFE1B9; color: #1A3B22; }
        .badge-bearish { background: #FEE2E2; color: #991B1B; }
        .badge-neutral { background: #E0E7EF; color: #374151; }

        /* Animate on scroll */
        @keyframes fadeInUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        .anim-fade { animation: fadeInUp 0.4s ease both; }
    </style>

    @stack('head')
</head>
<body class="h-full bg-sage text-ink">

<!-- ======================================================================
     WRAPPER
     ====================================================================== -->
<div class="flex h-screen overflow-hidden">

    <!-- ==================================================================
         SIDEBAR KIRI (Collapsible 280px / 76px)
         ================================================================== -->
    <aside id="lab-sidebar" class="flex flex-col shrink-0 overflow-hidden" style="background:#1A3B22;">

        <!-- Brand -->
        <div class="flex items-center px-4 py-4 border-b border-matcha-800 min-h-[64px]">
            <div class="flex items-center gap-2 flex-1 min-w-0">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0" style="background:#5C6A44;">
                    <i class="fa-solid fa-flask-vial text-matcha-200 text-sm"></i>
                </div>
                <div class="sidebar-brand-text min-w-0">
                    <div class="text-white font-bold text-sm leading-tight">Lab CRISP-DM</div>
                    <div class="text-matcha-300 text-xs">Kelompok 23 · UMRAH</div>
                </div>
            </div>
            <button id="sidebar-toggle" class="text-matcha-300 hover:text-white ml-2 shrink-0 p-1 rounded">
                <i class="fa-solid fa-angles-left collapse-icon transition-transform duration-300"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto px-2 py-3 space-y-1">
            <div class="sidebar-section-title px-2 pb-1 text-matcha-400 text-xs font-semibold uppercase tracking-widest">Pipeline</div>

            <a href="{{ route('lab.eda') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-matcha-200 text-sm transition-all {{ request()->routeIs('lab.eda') ? 'active' : '' }}">
                <i class="fa-solid fa-diagram-project w-4 text-center shrink-0"></i>
                <span class="sidebar-label">Pipeline & EDA</span>
            </a>

            <a href="{{ route('lab.preprocess') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-matcha-200 text-sm transition-all {{ request()->routeIs('lab.preprocess') ? 'active' : '' }}">
                <i class="fa-solid fa-filter w-4 text-center shrink-0"></i>
                <span class="sidebar-label">Pra-Proses & Imputasi</span>
            </a>

            <div class="sidebar-section-title px-2 pt-3 pb-1 text-matcha-400 text-xs font-semibold uppercase tracking-widest">Pemodelan</div>

            <a href="{{ route('lab.modeling') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-matcha-200 text-sm transition-all {{ request()->routeIs('lab.modeling') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line w-4 text-center shrink-0"></i>
                <span class="sidebar-label">Arena Model & MACD</span>
            </a>

            <a href="{{ route('lab.evaluation') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-matcha-200 text-sm transition-all {{ request()->routeIs('lab.evaluation') ? 'active' : '' }}">
                <i class="fa-solid fa-microscope w-4 text-center shrink-0"></i>
                <span class="sidebar-label">Korelasi & Evaluasi</span>
            </a>

            <div class="sidebar-section-title px-2 pt-3 pb-1 text-matcha-400 text-xs font-semibold uppercase tracking-widest">Portal Lain</div>

            <a href="{{ route('pharmacy.dashboard') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-matcha-300 text-sm transition-all">
                <i class="fa-solid fa-pills w-4 text-center shrink-0"></i>
                <span class="sidebar-label">Portal Farmasi</span>
            </a>

            <a href="{{ route('executive.dashboard') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-matcha-300 text-sm transition-all">
                <i class="fa-solid fa-user-tie w-4 text-center shrink-0"></i>
                <span class="sidebar-label">Portal Kepala</span>
            </a>

            <a href="{{ route('gateway') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-matcha-300 text-sm transition-all">
                <i class="fa-solid fa-house w-4 text-center shrink-0"></i>
                <span class="sidebar-label">Beranda Gateway</span>
            </a>
        </nav>

        <!-- Bottom info -->
        <div class="border-t border-matcha-800 px-3 py-3">
            <div class="sidebar-label text-matcha-400 text-xs text-center leading-tight">
                INF11121 · 2401020069<br>
                UPTD Puskesmas Teluk Sasah
            </div>
        </div>
    </aside>

    <!-- ==================================================================
         KONTEN UTAMA
         ================================================================== -->
    <div id="main-content" class="flex-1 flex flex-col overflow-hidden">

        <!-- Header Atas -->
        <header class="shrink-0 flex items-center justify-between px-6 py-0 border-b border-matcha-200" style="background:#1A3B22; min-height:64px;">
            <div class="flex items-center gap-4">
                <!-- Tab navigasi makro -->
                <nav class="flex items-center gap-1">
                    <a href="{{ route('lab.eda') }}"
                       class="tab-nav-link {{ request()->routeIs('lab.eda') ? 'active-tab' : '' }}">
                        <i class="fa-solid fa-diagram-project mr-1.5"></i>Pipeline & EDA
                    </a>
                    <a href="{{ route('lab.preprocess') }}"
                       class="tab-nav-link {{ request()->routeIs('lab.preprocess') ? 'active-tab' : '' }}">
                        <i class="fa-solid fa-filter mr-1.5"></i>Pra-Proses
                    </a>
                    <a href="{{ route('lab.modeling') }}"
                       class="tab-nav-link {{ request()->routeIs('lab.modeling') ? 'active-tab' : '' }}">
                        <i class="fa-solid fa-chart-line mr-1.5"></i>Arena Model
                    </a>
                    <a href="{{ route('lab.evaluation') }}"
                       class="tab-nav-link {{ request()->routeIs('lab.evaluation') ? 'active-tab' : '' }}">
                        <i class="fa-solid fa-microscope mr-1.5"></i>Evaluasi
                    </a>
                </nav>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-matcha-300 text-xs hidden md:inline">
                    <i class="fa-solid fa-flask-vial mr-1"></i>Portal Sains Data — PRABOTIK
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold" style="background:#CFE1B9; color:#1A3B22;">
                    <i class="fa-solid fa-circle text-[6px]"></i> CRISP-DM
                </span>
            </div>
        </header>

        <!-- Sticky Drug Switcher Bar -->
        <div id="drug-switcher-bar" class="bg-white border-b border-matcha-200 px-6 py-2.5 flex items-center gap-4 shadow-sm">
            <div class="flex items-center gap-2 flex-1">
                <i class="fa-solid fa-magnifying-glass text-matcha-600 text-sm"></i>
                <div class="relative flex-1 max-w-sm">
                    <input type="text" id="drug-search-input" placeholder="Ketik nama obat untuk inspeksi…"
                           class="w-full text-sm border border-matcha-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-matcha-400 bg-sage"
                           autocomplete="off">
                    <ul id="drug-dropdown" class="absolute z-50 w-full bg-white border border-matcha-200 rounded-lg shadow-float-lg mt-1 max-h-60 overflow-auto hidden text-sm"></ul>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-500 hidden sm:inline">Lensa:</span>
                <button id="lens-monthly" onclick="setLens('monthly')"
                        class="lens-btn px-3 py-1 rounded-lg text-xs font-medium border transition-all active-lens" style="background:#CFE1B9; color:#1A3B22; border-color:#5C6A44;">
                    36 Bln
                </button>
                <button id="lens-bimonthly" onclick="setLens('bimonthly')"
                        class="lens-btn px-3 py-1 rounded-lg text-xs font-medium border border-matcha-200 text-gray-500 hover:bg-sage transition-all">
                    22 Periode
                </button>
            </div>
            <div id="drug-info-badge" class="hidden items-center gap-2 text-xs">
                <span id="drug-info-name" class="font-semibold text-matcha-900"></span>
                <span id="drug-info-model" class="px-2 py-0.5 rounded-full" style="background:#E7F5DC; color:#3E4A2F;"></span>
                <span id="drug-info-acc" class="px-2 py-0.5 rounded-full text-white" style="background:#5C6A44;"></span>
            </div>
        </div>

        <!-- Scrollable content -->
        <main class="flex-1 overflow-y-auto px-6 py-6">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="shrink-0 border-t border-matcha-200 bg-white px-6 py-2.5 flex items-center justify-between">
            <span class="text-xs text-gray-400">
                <strong class="text-matcha-700">PRABOTIK</strong> · Portal Sains Data & Lab CRISP-DM ·
                Penambangan Data INF11121 · Kelompok 23 · UMRAH 2024
            </span>
            <span class="text-xs text-gray-400">
                UPTD Puskesmas Teluk Sasah, Kab. Bintan
            </span>
        </footer>
    </div>
</div>

<!-- ===========================================================
     Global Scripts
     =========================================================== -->
<script>
// ---- Sidebar toggle ----
(function () {
    const sb   = document.getElementById('lab-sidebar');
    const btn  = document.getElementById('sidebar-toggle');
    if (!sb || !btn) return;
    btn.addEventListener('click', () => sb.classList.toggle('collapsed'));
})();

// ---- Drug Switcher ----
const allDrugs = @json($allMedicines ?? []);
let activeLens = 'monthly';
let selectedDrugId = null;

function setLens(lens) {
    activeLens = lens;
    document.querySelectorAll('.lens-btn').forEach(b => {
        b.style.background = '';
        b.style.color = '';
        b.style.borderColor = '';
        b.classList.remove('active-lens');
    });
    const active = lens === 'monthly' ? document.getElementById('lens-monthly') : document.getElementById('lens-bimonthly');
    if (active) {
        active.style.background = '#CFE1B9';
        active.style.color = '#1A3B22';
        active.style.borderColor = '#5C6A44';
    }
    if (selectedDrugId) loadDrugInspection(selectedDrugId);
}

async function loadDrugInspection(drugId) {
    selectedDrugId = drugId;
    try {
        const res  = await fetch(`/lab/api/inspect/${drugId}?lens=${activeLens}`);
        const data = await res.json();
        updateDrugInfoBadge(data);
        // Dispatch event so individual pages can listen
        document.dispatchEvent(new CustomEvent('drugLoaded', { detail: data }));
    } catch (e) { console.error('Drug inspection failed:', e); }
}

function updateDrugInfoBadge(data) {
    const badge = document.getElementById('drug-info-badge');
    const name  = document.getElementById('drug-info-name');
    const model = document.getElementById('drug-info-model');
    const acc   = document.getElementById('drug-info-acc');
    if (!badge) return;
    badge.classList.remove('hidden');
    badge.classList.add('flex');
    name.textContent  = data.nama_obat.substring(0, 30) + (data.nama_obat.length > 30 ? '…' : '');
    model.textContent = data.model_terbaik;
    acc.textContent   = `Akurasi: ${data.evaluasi_akurasi}%`;
}

// Drug search autocomplete
(function () {
    const input    = document.getElementById('drug-search-input');
    const dropdown = document.getElementById('drug-dropdown');
    if (!input || !dropdown) return;

    input.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        dropdown.innerHTML = '';
        if (!q || q.length < 2) { dropdown.classList.add('hidden'); return; }
        const matches = allDrugs.filter(d => d.nama_obat.toLowerCase().includes(q)).slice(0, 10);
        if (!matches.length) { dropdown.classList.add('hidden'); return; }
        matches.forEach(d => {
            const li = document.createElement('li');
            li.className = 'px-3 py-2 cursor-pointer hover:bg-matcha-50 flex items-center gap-2';
            li.innerHTML = `<i class="fa-solid fa-capsules text-matcha-600 text-xs"></i><span>${d.nama_obat}</span>`;
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
})();
</script>

@stack('scripts')
</body>
</html>
