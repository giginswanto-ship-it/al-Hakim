<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#064e3b">
  <title>@yield('title', 'Masjid Al-Hakim — Sistem Transparansi Kas & Pelaporan Publik Jamaah')</title>
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            emerald: {
              50: '#ecfdf5',
              100: '#d1fae5',
              200: '#a7f3d0',
              300: '#6ee7b7',
              400: '#34d399',
              500: '#10b981',
              600: '#059669',
              700: '#047857',
              800: '#065f46',
              900: '#064e3b',
              950: '#022c22',
            },
            amber: {
              400: '#fbbf24',
              500: '#f59e0b',
              600: '#d97706',
            }
          },
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
            arabic: ['Amiri', 'serif']
          }
        }
      }
    }
  </script>

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  
  <!-- Favicon SVG -->
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23059669' stroke-width='2'><path d='M3 21h18M5 21V9l7-6 7 6v12M9 21v-6a3 3 0 0 1 6 0v6'/></svg>">
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 min-h-screen flex flex-col transition-colors duration-300">

  <!-- ==================== TOP BAR & JADWAL SHALAT ==================== -->
  <header class="bg-emerald-900 text-emerald-100 text-xs py-2 px-3 sm:px-8 border-b border-emerald-800/60 no-print">
    <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2">
      <!-- Islamic Date & Live Clock -->
      <div class="flex items-center space-x-3 text-[11px] sm:text-xs">
        <span class="flex items-center space-x-1.5 font-medium text-emerald-200">
          <i data-lucide="calendar" class="w-3.5 h-3.5 text-amber-400 shrink-0"></i>
          <span class="liveDate">Memuat tanggal...</span>
        </span>
        <span class="text-emerald-700">|</span>
        <span class="flex items-center space-x-1.5 font-mono text-emerald-300">
          <i data-lucide="clock" class="w-3.5 h-3.5 text-emerald-400 shrink-0"></i>
          <span class="liveClock">--:--:-- WIB</span>
        </span>
      </div>

      <!-- Jadwal Shalat Ringkas (Scrollable on small screens) -->
      <div class="flex items-center space-x-2 text-[10px] sm:text-[11px] overflow-x-auto no-scrollbar py-0.5 max-w-full">
        <span class="text-emerald-400 font-semibold uppercase tracking-wider flex items-center shrink-0">
          <i data-lucide="sun" class="w-3 h-3 mr-1 text-amber-400"></i> Shalat:
        </span>
        <span class="px-2 py-0.5 rounded bg-emerald-800/80 shrink-0">Subuh 04:28</span>
        <span class="px-2 py-0.5 rounded bg-emerald-800/80 shrink-0">Dzuhur 11:45</span>
        <span class="px-2 py-0.5 rounded bg-emerald-800/80 shrink-0">Ashar 14:52</span>
        <span class="px-2 py-0.5 rounded bg-emerald-800/80 shrink-0">Maghrib 17:51</span>
        <span class="px-2 py-0.5 rounded bg-emerald-800/80 shrink-0">Isya 19:01</span>
      </div>

      <!-- Quick Actions (Desktop) -->
      <div class="hidden sm:flex items-center space-x-2">
        <button onclick="openAdminModal()" class="flex items-center space-x-1 px-2.5 py-1 rounded-lg bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold transition shadow-sm text-xs">
          <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
          <span>Input Kas (Bendahara)</span>
        </button>
        <button aria-label="Toggle Theme" class="themeToggleBtn p-1.5 rounded-lg bg-emerald-800/80 hover:bg-emerald-700 text-emerald-200 hover:text-white transition">
          <i data-lucide="sun-moon" class="w-4 h-4"></i>
        </button>
      </div>
    </div>
  </header>

  <!-- ==================== MAIN HEADER & BRANDING ==================== -->
  <nav class="bg-white/95 dark:bg-slate-900/95 sticky top-0 z-30 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 no-print transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-8 py-3 flex items-center justify-between">
      <div class="flex items-center space-x-3">
        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-gradient-to-tr from-emerald-700 to-emerald-500 flex items-center justify-center text-white shadow-md shadow-emerald-500/20 shrink-0">
          <i data-lucide="landmark" class="w-5 h-5 sm:w-6 sm:h-6"></i>
        </div>
        <div>
          <div class="flex items-center space-x-1.5 sm:space-x-2">
            <h1 class="text-lg sm:text-2xl font-black tracking-tight text-slate-900 dark:text-white leading-tight">MASJID AL-HAKIM</h1>
            <span class="px-1.5 py-0.5 text-[9px] sm:text-[10px] font-bold uppercase rounded-md bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700">
              {{ $bank->active_period ?? 'Okt 2026' }}
            </span>
          </div>
          <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium line-clamp-1">Sistem Transparansi Kas & Pelaporan Publik</p>
        </div>
      </div>

      <!-- Navigation & Status Indicator (Desktop) -->
      <div class="hidden lg:flex items-center space-x-5">
        <div class="flex items-center space-x-2 px-3 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-950/70 border border-emerald-200 dark:border-emerald-800/60">
          <span class="relative flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
          </span>
          <span class="text-xs font-bold text-emerald-700 dark:text-emerald-300 uppercase tracking-wider">STATUS: REAL-TIME</span>
        </div>

        <button onclick="switchMenu('ringkasan')" class="text-xs font-semibold hover:text-emerald-600 dark:hover:text-emerald-400">Ringkasan</button>
        <button onclick="switchMenu('mutasi')" class="text-xs font-semibold hover:text-emerald-600 dark:hover:text-emerald-400">Mutasi Kas</button>
        <button onclick="switchMenu('donasi')" class="text-xs font-semibold hover:text-emerald-600 dark:hover:text-emerald-400">Kanal Donasi</button>
        <button onclick="switchMenu('alokasi')" class="text-xs font-semibold hover:text-emerald-600 dark:hover:text-emerald-400">Alokasi Infaq</button>

        <button onclick="printReport()" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-200 transition shadow-sm">
          <i data-lucide="printer" class="w-4 h-4 text-emerald-600"></i>
          <span>Cetak Dokumen</span>
        </button>
      </div>

      <!-- Mobile Right Controls (Theme + Hamburger) -->
      <div class="flex lg:hidden items-center space-x-2">
        <button aria-label="Toggle Theme" class="themeToggleBtn p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700 transition">
          <i data-lucide="sun-moon" class="w-4 h-4"></i>
        </button>

        <button id="mobileMenuToggleBtn" aria-label="Buka Menu Navigasi" class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 flex flex-col justify-center items-center w-10 h-10 space-y-1.5 focus:outline-none transition active:scale-95">
          <span class="hamburger-line hamburger-line-1 w-5 h-0.5 bg-emerald-700 dark:bg-emerald-300 rounded-full"></span>
          <span class="hamburger-line hamburger-line-2 w-5 h-0.5 bg-emerald-700 dark:bg-emerald-300 rounded-full"></span>
          <span class="hamburger-line hamburger-line-3 w-5 h-0.5 bg-emerald-700 dark:bg-emerald-300 rounded-full"></span>
        </button>
      </div>
    </div>
  </nav>

  <!-- ==================== MOBILE NAVIGATION DRAWER ==================== -->
  <div id="mobileMenuOverlay" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 overlay-hidden no-print"></div>

  <aside id="mobileDrawer" class="fixed top-0 right-0 h-full w-[84vw] max-w-xs bg-white dark:bg-slate-900 z-50 shadow-2xl p-5 flex flex-col justify-between drawer-closed border-l border-slate-200 dark:border-slate-800 no-print overflow-y-auto">
    <div class="space-y-5">
      <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
        <div class="flex items-center space-x-2.5">
          <div class="w-9 h-9 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-md">
            <i data-lucide="landmark" class="w-5 h-5"></i>
          </div>
          <div>
            <h3 class="font-extrabold text-sm text-slate-900 dark:text-white">MASJID AL-HAKIM</h3>
            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold block uppercase tracking-wider">Transparansi Kas DKM</span>
          </div>
        </div>
        <button id="closeMobileMenuBtn" aria-label="Tutup Menu" class="p-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-800 dark:hover:text-white transition">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <div class="bg-emerald-50 dark:bg-emerald-950/60 p-3 rounded-xl border border-emerald-200 dark:border-emerald-800/60 text-xs">
        <div class="flex items-center space-x-2 text-emerald-800 dark:text-emerald-300 font-bold mb-1">
          <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
          <span>Status: Aktif & Real-Time</span>
        </div>
        <p class="text-[11px] text-slate-600 dark:text-slate-400 font-mono liveClock">--:--:-- WIB</p>
      </div>

      <div class="space-y-1">
        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-2 block mb-1">Menu Utama</span>
        
        <button onclick="switchMenu('ringkasan')" class="mobile-nav-link tab-btn-ringkasan w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/50 transition text-left">
          <i data-lucide="bar-chart-2" class="w-4 h-4 text-emerald-600"></i>
          <span>1. Ringkasan & Grafik Kas</span>
        </button>

        <button onclick="switchMenu('mutasi')" class="mobile-nav-link tab-btn-mutasi w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/50 transition text-left">
          <i data-lucide="receipt" class="w-4 h-4 text-emerald-600"></i>
          <span>2. Mutasi Kas Terkini</span>
        </button>

        <button onclick="switchMenu('donasi')" class="mobile-nav-link tab-btn-donasi w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/50 transition text-left">
          <i data-lucide="qr-code" class="w-4 h-4 text-emerald-600"></i>
          <span>3. Kanal Donasi & QRIS</span>
        </button>

        <button onclick="switchMenu('alokasi')" class="mobile-nav-link tab-btn-alokasi w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/50 transition text-left">
          <i data-lucide="pie-chart" class="w-4 h-4 text-emerald-600"></i>
          <span>4. Alokasi 4 Pilar Syariah</span>
        </button>

        <button onclick="switchMenu('all')" class="mobile-nav-link tab-btn-all w-full flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/50 transition text-left">
          <i data-lucide="layout-grid" class="w-4 h-4 text-emerald-600"></i>
          <span>Tampilkan Semua Bagian</span>
        </button>
      </div>

      <div class="space-y-2 pt-2 border-t border-slate-200 dark:border-slate-800">
        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-2 block mb-1">Aksi Khusus</span>

        <button onclick="openAdminModal()" class="w-full flex items-center justify-center space-x-2 px-3 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold text-xs shadow-sm transition">
          <i data-lucide="plus-circle" class="w-4 h-4"></i>
          <span>Input Kas (Bendahara)</span>
        </button>

        <button onclick="printReport()" class="w-full flex items-center justify-center space-x-2 px-3 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-200 transition">
          <i data-lucide="printer" class="w-4 h-4 text-emerald-600"></i>
          <span>Cetak Dokumen / Simpan PDF</span>
        </button>

        <button onclick="toggleFullscreen()" class="w-full flex items-center justify-center space-x-2 px-3 py-2.5 rounded-xl bg-emerald-800 hover:bg-emerald-700 text-emerald-100 text-xs font-semibold transition">
          <i data-lucide="maximize" class="w-4 h-4"></i>
          <span>Layar Penuh TV (Kiosk)</span>
        </button>
      </div>
    </div>

    <div class="pt-4 border-t border-slate-200 dark:border-slate-800 text-center text-[10px] text-slate-400 space-y-1">
      <p class="font-bold text-slate-600 dark:text-slate-300">Masjid Al-Hakim</p>
      <p>Laporan Publik Periode: <strong>{{ $bank->active_period ?? 'Oktober 2026' }}</strong></p>
    </div>
  </aside>

  <!-- ==================== ROTASI MENU OTOMATIS (KIOSK / TV DISPLAY BAR) ==================== -->
  <div class="bg-emerald-950 text-white border-b border-emerald-800/80 sticky top-[57px] sm:top-[61px] z-20 shadow-md no-print">
    <!-- Animated Countdown Progress Bar -->
    <div class="w-full bg-emerald-900/60 h-1 sm:h-1.5 overflow-hidden">
      <div id="rotationProgressBar" class="kiosk-progress-bar h-full bg-gradient-to-r from-amber-400 via-emerald-400 to-emerald-300 w-full"></div>
    </div>

    <div class="max-w-7xl mx-auto px-3 sm:px-8 py-2 sm:py-2.5 flex flex-wrap items-center justify-between gap-2">
      <!-- Active Menu Tabs (Responsive Horizontal Scroll) -->
      <div class="flex items-center space-x-1 sm:space-x-2 overflow-x-auto no-scrollbar max-w-full pb-0.5">
        <button onclick="switchMenu('ringkasan')" class="menu-tab-btn tab-btn-ringkasan flex items-center space-x-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap bg-emerald-600 text-white shadow-sm">
          <i data-lucide="bar-chart-2" class="w-3.5 h-3.5"></i>
          <span>1. Ringkasan & Grafik</span>
        </button>
        <button onclick="switchMenu('mutasi')" class="menu-tab-btn tab-btn-mutasi flex items-center space-x-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl text-xs font-semibold text-emerald-200 hover:text-white hover:bg-emerald-900/60 transition whitespace-nowrap">
          <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
          <span>2. Mutasi Kas</span>
        </button>
        <button onclick="switchMenu('donasi')" class="menu-tab-btn tab-btn-donasi flex items-center space-x-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl text-xs font-semibold text-emerald-200 hover:text-white hover:bg-emerald-900/60 transition whitespace-nowrap">
          <i data-lucide="qr-code" class="w-3.5 h-3.5"></i>
          <span>3. Donasi & QRIS</span>
        </button>
        <button onclick="switchMenu('alokasi')" class="menu-tab-btn tab-btn-alokasi flex items-center space-x-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl text-xs font-semibold text-emerald-200 hover:text-white hover:bg-emerald-900/60 transition whitespace-nowrap">
          <i data-lucide="pie-chart" class="w-3.5 h-3.5"></i>
          <span>4. Alokasi Infaq</span>
        </button>
        <button onclick="switchMenu('all')" class="menu-tab-btn tab-btn-all flex items-center space-x-1 px-2.5 py-1.5 rounded-xl text-xs font-semibold text-emerald-300 hover:text-white hover:bg-emerald-900/60 transition whitespace-nowrap">
          <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i>
          <span>Semua Menu</span>
        </button>
      </div>

      <!-- Rotation Timer Status & Interactive Controls -->
      <div class="flex items-center space-x-2 text-xs">
        <div class="flex items-center space-x-1.5 bg-emerald-900/80 border border-emerald-700/60 px-2.5 py-1 rounded-xl shadow-inner">
          <span class="w-2 h-2 rounded-full bg-amber-400 rotationDot radar-dot"></span>
          <span class="text-emerald-300 hidden md:inline text-[11px]">Rotasi:</span>
          <span class="rotationCountdownEl font-mono font-bold text-amber-300 text-xs">60s</span>
        </div>

        <div class="flex items-center space-x-0.5 bg-emerald-900/70 p-0.5 rounded-xl border border-emerald-800">
          <button onclick="prevMenu()" title="Menu Sebelumnya" class="p-1.5 rounded-lg text-emerald-200 hover:text-white hover:bg-emerald-800 transition">
            <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
          </button>
          <button onclick="toggleAutoRotation()" title="Jeda / Lanjutkan Rotasi" class="p-1.5 rounded-lg text-amber-300 hover:text-white hover:bg-emerald-800 transition">
            <i class="pausePlayIcon w-3.5 h-3.5" data-lucide="pause"></i>
          </button>
          <button onclick="nextMenu()" title="Menu Selanjutnya" class="p-1.5 rounded-lg text-emerald-200 hover:text-white hover:bg-emerald-800 transition">
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
          </button>
        </div>

        <button onclick="toggleFullscreen()" title="Mode TV Layar Penuh" class="p-1.5 rounded-xl bg-emerald-800 hover:bg-emerald-700 text-emerald-100 hover:text-white transition hidden sm:flex items-center space-x-1">
          <i data-lucide="maximize" class="w-3.5 h-3.5"></i>
          <span class="text-[11px] font-bold">TV Kiosk</span>
        </button>
      </div>
    </div>
  </div>

  <!-- PRINT ONLY HEADER -->
  <div class="print-only p-6 border-b-2 border-emerald-800">
    <div class="flex justify-between items-start">
      <div>
        <h1 class="text-2xl font-black text-emerald-900 tracking-tight">MASJID AL-HAKIM</h1>
        <p class="text-xs text-slate-600 font-medium">Sistem Transparansi Kas & Pelaporan Publik Jamaah</p>
      </div>
      <div class="text-right">
        <span class="inline-block px-3 py-1 rounded bg-emerald-100 text-emerald-800 font-bold text-xs">STATUS: AKTIF & REAL-TIME</span>
        <p class="text-xs text-slate-600 mt-1">Periode: {{ $bank->active_period ?? 'Oktober 2026' }}</p>
      </div>
    </div>
  </div>

  <!-- Main Content Body -->
  @yield('content')

  <!-- Footer -->
  <footer class="mt-8 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 py-6 px-4 sm:px-8 text-xs text-slate-500 dark:text-slate-400 no-print">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
      <div>
        <p class="font-bold text-slate-800 dark:text-slate-200">Masjid Al-Hakim — Laporan Transparansi Keuangan</p>
        <p class="text-[11px] text-slate-400">Dikelola secara amanah oleh Dewan Kemakmuran Masjid (DKM)</p>
      </div>
      <div class="flex items-center space-x-4">
        <span>Periode: <strong>{{ $bank->active_period ?? 'Oktober 2026' }}</strong></span>
        <span class="text-slate-300 dark:text-slate-700">|</span>
        <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 font-mono text-[11px]">Laravel + SQL Edition</span>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="{{ asset('js/data.js') }}"></script>
  <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
