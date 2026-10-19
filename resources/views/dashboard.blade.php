@extends('layouts.app')

@section('title', 'Masjid Al-Hakim — Sistem Transparansi Kas & Pelaporan Publik Jamaah')

@section('content')
<main class="flex-grow max-w-7xl mx-auto w-full px-3 sm:px-8 py-5 sm:py-6 space-y-6 sm:space-y-8">

  <!-- Bismillah & Notice Banner -->
  <section class="text-center pt-1 pb-1 no-print">
    <p class="font-arabic text-xl sm:text-2xl text-emerald-800 dark:text-emerald-400 mb-1">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</p>
    <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 italic">"Perumpamaan orang yang menginfakkan hartanya di jalan Allah seperti sebutir biji yang menumbuhkan tujuh tangkai..." (QS. Al-Baqarah: 261)</p>
  </section>

  <!-- ==================== MENU 1: RINGKASAN & GRAFIK KAS ==================== -->
  <div id="menu-view-ringkasan" class="menu-section menu-active space-y-5 sm:space-y-6">
    <!-- 1. FINANCIAL SUMMARY KPI CARDS -->
    <section id="ringkasan" class="grid grid-cols-1 md:grid-cols-3 gap-3.5 sm:gap-5">
      <!-- Card 1: Saldo Kas Bersih -->
      <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-800 via-emerald-700 to-emerald-900 text-white p-5 sm:p-6 shadow-lg shadow-emerald-900/20 border border-emerald-600/40">
        <div class="absolute -right-6 -bottom-6 opacity-10 text-white pointer-events-none">
          <i data-lucide="wallet" class="w-32 h-32 sm:w-36 sm:h-36"></i>
        </div>
        <div class="relative z-10">
          <div class="flex items-center justify-between mb-2">
            <span class="text-[10px] sm:text-xs font-bold tracking-wider uppercase text-emerald-200">SALDO KAS BERSIH</span>
            <span class="px-2 py-0.5 rounded-full text-[9px] sm:text-[10px] font-bold bg-emerald-500/30 text-emerald-100 border border-emerald-400/30">
              Kas Aktif
            </span>
          </div>
          <div id="saldoKasBersihEl" class="text-2xl sm:text-3xl font-black tracking-tight text-white mb-2 break-words">
            Rp {{ number_format($saldoKasBersih, 0, ',', '.') }}
          </div>
          <div class="flex items-center space-x-1.5 text-xs text-emerald-100/90">
            <i data-lucide="shield-check" class="w-4 h-4 text-amber-300 shrink-0"></i>
            <span>Tersedia & Siap Dialokasikan</span>
          </div>
        </div>
      </div>

      <!-- Card 2: Pemasukan Bulan Ini -->
      <div class="rounded-2xl bg-white dark:bg-slate-800 p-5 sm:p-6 shadow-sm border border-slate-200 dark:border-slate-700/80 hover:shadow-md transition">
        <div class="flex items-center justify-between mb-2">
          <span class="text-[10px] sm:text-xs font-bold tracking-wider uppercase text-slate-500 dark:text-slate-400">PEMASUKAN BULAN INI</span>
          <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
            <i data-lucide="arrow-down-left" class="w-5 h-5"></i>
          </div>
        </div>
        <div id="pemasukanBulanIniEl" class="text-2xl sm:text-3xl font-black tracking-tight text-emerald-600 dark:text-emerald-400 mb-2 break-words">
          Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
        </div>
        <div class="flex items-center space-x-1 text-xs text-slate-500 dark:text-slate-400">
          <i data-lucide="trending-up" class="w-4 h-4 text-emerald-500 shrink-0"></i>
          <span class="truncate">Kotak Amal, QRIS, & Transfer</span>
        </div>
      </div>

      <!-- Card 3: Pengeluaran Bulan Ini -->
      <div class="rounded-2xl bg-white dark:bg-slate-800 p-5 sm:p-6 shadow-sm border border-slate-200 dark:border-slate-700/80 hover:shadow-md transition">
        <div class="flex items-center justify-between mb-2">
          <span class="text-[10px] sm:text-xs font-bold tracking-wider uppercase text-slate-500 dark:text-slate-400">PENGELUARAN BULAN INI</span>
          <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-950/60 flex items-center justify-center text-rose-600 dark:text-rose-400">
            <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
          </div>
        </div>
        <div id="pengeluaranBulanIniEl" class="text-2xl sm:text-3xl font-black tracking-tight text-rose-600 dark:text-rose-400 mb-2 break-words">
          Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
        </div>
        <div class="flex items-center space-x-1 text-xs text-slate-500 dark:text-slate-400">
          <i data-lucide="activity" class="w-4 h-4 text-rose-500 shrink-0"></i>
          <span class="truncate">Operasional, Listrik, Yatim & Dhuafa</span>
        </div>
      </div>
    </section>

    <!-- 2. INTERACTIVE CHARTS SECTION -->
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6 no-print">
      <!-- Chart: Mutasi Arus Kas -->
      <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl p-4 sm:p-6 border border-slate-200 dark:border-slate-700/80 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
          <div>
            <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white flex items-center">
              <i data-lucide="bar-chart-3" class="w-4 h-4 mr-2 text-emerald-600 shrink-0"></i>
              Perbandingan Arus Kas (Pemasukan vs Pengeluaran)
            </h3>
            <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400">Visualisasi realisasi keuangan masjid periode berjalan (SQL Sync)</p>
          </div>
          <div class="text-right">
            <span class="text-[10px] sm:text-xs text-slate-400">Surplus Kas Bersih:</span>
            <div id="surplusKasEl" class="text-xs sm:text-sm font-extrabold text-blue-600 dark:text-blue-400">Rp {{ number_format($surplusKas, 0, ',', '.') }}</div>
          </div>
        </div>
        <div class="h-60 sm:h-72 w-full">
          <canvas id="cashflowChart"></canvas>
        </div>
      </div>

      <!-- Chart: Alokasi Penggunaan Dana Infaq -->
      <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 sm:p-6 border border-slate-200 dark:border-slate-700/80 shadow-sm flex flex-col justify-between">
        <div>
          <div class="flex items-center justify-between mb-1">
            <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white flex items-center">
              <i data-lucide="pie-chart" class="w-4 h-4 mr-2 text-emerald-600 shrink-0"></i>
              Distribusi Porsi Infaq
            </h3>
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">4 Pilar</span>
          </div>
          <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mb-4">Target alokasi syariah penggunaan dana umat</p>
          
          <div class="h-56 sm:h-60 w-full relative">
            <canvas id="allocationChart"></canvas>
          </div>
        </div>

        <div class="pt-3 border-t border-slate-100 dark:border-slate-700/60 text-[11px] text-center text-slate-400">
          Data tersinkronisasi dengan database SQL.
        </div>
      </div>
    </section>
  </div>

  <!-- ==================== MENU 2: MUTASI KAS TERKINI ==================== -->
  <div id="menu-view-mutasi" class="menu-section menu-hidden space-y-4 sm:space-y-5">
    <section id="mutasi" class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 shadow-sm overflow-hidden">
      <!-- Header & Auto-Scroll Controller -->
      <div class="p-4 sm:p-6 border-b border-slate-200 dark:border-slate-700/80 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
          <div class="flex items-center space-x-2 mb-1">
            <h2 class="text-lg sm:text-xl font-black text-slate-900 dark:text-white">MUTASI KAS TERKINI</h2>
            <span id="transactionCountBadge" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
              {{ $transactions->count() }} Mutasi
            </span>
          </div>
          <p class="text-xs text-slate-500 dark:text-slate-400">Buku kas umum penerimaan & pengeluaran resmi periode {{ $bank->active_period ?? 'Oktober 2026' }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
          <!-- Auto Scroll Toggle Badge -->
          <button id="tableAutoScrollBadge" onclick="toggleTableAutoScroll()" title="Klik untuk menjeda atau mengaktifkan scroll otomatis" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700 transition">
            <i id="tableScrollIcon" data-lucide="arrow-down" class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400 animate-bounce"></i>
            <span id="tableAutoScrollStatus">Auto-Scroll: Aktif</span>
          </button>

          <!-- Export CSV -->
          <button onclick="exportToCSV()" class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-xl border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-750 text-xs font-semibold text-slate-700 dark:text-slate-200 transition">
            <i data-lucide="download" class="w-3.5 h-3.5 text-emerald-600"></i>
            <span>Unduh CSV</span>
          </button>
        </div>
      </div>

      <!-- Filters & Search Toolbar (Mobile Stacked Layout) -->
      <div class="p-3.5 sm:p-4 bg-slate-50 dark:bg-slate-850 border-b border-slate-200 dark:border-slate-700/80 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 no-print">
        <div class="relative">
          <i data-lucide="search" class="w-4 h-4 absolute left-3 top-3 text-slate-400"></i>
          <input type="text" id="searchInput" placeholder="Cari keterangan / nota..." class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>

        <div>
          <select id="kategoriFilter" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="all">Semua Kategori (Penerimaan & Pengeluaran)</option>
            <option value="Penerimaan">Penerimaan (Infaq / Donasi)</option>
            <option value="Pengeluaran">Pengeluaran (Operasional / Belanja)</option>
          </select>
        </div>

        <div>
          <select id="posFilter" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="all">Semua Pos Anggaran</option>
            <option value="Operasional & Utilitas">Operasional & Utilitas</option>
            <option value="Dakwah & Kajian">Dakwah & Kajian</option>
            <option value="Sosial & Yatim Dhuafa">Sosial & Yatim Dhuafa</option>
            <option value="Dana Cadangan Bangunan">Dana Cadangan Bangunan</option>
          </select>
        </div>

        <div>
          <select id="sortOrder" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="date-desc">Urutkan: Tanggal Terbaru</option>
            <option value="date-asc">Urutkan: Tanggal Terlama</option>
            <option value="amount-desc">Urutkan: Nominal Terbesar</option>
            <option value="amount-asc">Urutkan: Nominal Terkecil</option>
          </select>
        </div>
      </div>

      <!-- AUTO-SCROLL CONTAINER (RESPONSIVE TABLE & CARDS) -->
      <div id="transactionsScrollContainer" class="max-h-[480px] sm:max-h-[520px] overflow-y-auto relative scroll-smooth">
        <!-- 1. Desktop & Tablet View (Table) -->
        <table class="hidden md:table w-full text-left border-collapse">
          <thead class="bg-slate-100/90 dark:bg-slate-850/90 text-slate-600 dark:text-slate-300 text-xs font-bold uppercase tracking-wider sticky top-0 z-10 backdrop-blur-sm shadow-sm">
            <tr>
              <th class="py-3.5 px-4">Tanggal</th>
              <th class="py-3.5 px-4">Keterangan & Rincian Transaksi</th>
              <th class="py-3.5 px-4">Kategori</th>
              <th class="py-3.5 px-4 text-right">Nominal (Rp)</th>
              <th class="py-3.5 px-4 text-center">Verifikasi</th>
            </tr>
          </thead>
          <tbody id="transactionsTableBody" class="divide-y divide-slate-100 dark:divide-slate-800/80 text-sm">
            @forelse($transactions as $t)
            <tr class="border-b border-slate-100 dark:border-slate-800/80 hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition cursor-pointer" onclick="openReceiptModal('{{ $t->code }}')">
              <td class="py-3.5 px-4 text-xs font-semibold text-slate-600 dark:text-slate-300 whitespace-nowrap">
                <div class="flex items-center space-x-2">
                  <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                  <span>{{ $t->date_formatted }}</span>
                </div>
              </td>
              <td class="py-3.5 px-4">
                <div class="font-bold text-slate-800 dark:text-slate-100 text-sm leading-snug">{{ $t->description }}</div>
                <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center space-x-2 mt-0.5">
                  <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 dark:bg-slate-700/80 text-slate-600 dark:text-slate-300">
                    {{ $t->allocation_category }}
                  </span>
                  <span class="text-slate-300 dark:text-slate-600">•</span>
                  <span>No: {{ $t->receipt_number }}</span>
                </div>
              </td>
              <td class="py-3.5 px-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $t->type === 'Penerimaan' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200 dark:border-rose-800/50' }}">
                  <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $t->type === 'Penerimaan' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                  {{ $t->type }}
                </span>
              </td>
              <td class="py-3.5 px-4 text-right whitespace-nowrap">
                <span class="text-sm font-black tracking-tight {{ $t->type === 'Penerimaan' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                  {{ $t->type === 'Penerimaan' ? '+ ' : '- ' }}Rp {{ number_format($t->amount, 0, ',', '.') }}
                </span>
              </td>
              <td class="py-3.5 px-4 text-center whitespace-nowrap">
                <button onclick="openReceiptModal('{{ $t->code }}')" class="inline-flex items-center space-x-1.5 text-xs font-semibold px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-300 dark:hover:bg-emerald-900/50 border border-emerald-200 dark:border-emerald-700 transition" title="Verifikasi Bukti Kuitansi & Nota">
                  <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                  <span>Bukti Nota</span>
                </button>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="py-12 text-center text-slate-400 dark:text-slate-500">
                <i data-lucide="search-x" class="w-10 h-10 mx-auto mb-2 opacity-50"></i>
                <p class="font-medium text-sm">Tidak ada transaksi dalam database SQL.</p>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>

        <!-- 2. Mobile Responsive Card Feed (Handphone Viewports < 768px) -->
        <div id="transactionsMobileCardsContainer" class="md:hidden p-3 space-y-2.5">
          @foreach($transactions as $t)
          <div class="bg-slate-50/70 dark:bg-slate-850 p-4 rounded-xl border border-slate-200/80 dark:border-slate-700/80 space-y-2.5 transition active:bg-slate-100 dark:active:bg-slate-800" onclick="openReceiptModal('{{ $t->code }}')">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-1.5 text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                <i data-lucide="calendar" class="w-3.5 h-3.5 text-amber-500"></i>
                <span>{{ $t->date_formatted }}</span>
              </div>
              <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $t->type === 'Penerimaan' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300' }}">
                <span class="w-1.5 h-1.5 rounded-full mr-1 {{ $t->type === 'Penerimaan' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                {{ $t->type }}
              </span>
            </div>

            <div>
              <h4 class="font-bold text-slate-900 dark:text-white text-sm leading-snug">{{ $t->description }}</h4>
              <div class="flex items-center space-x-1.5 text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                <span class="px-1.5 py-0.5 rounded bg-slate-200/80 dark:bg-slate-700/80 text-[10px] font-medium text-slate-700 dark:text-slate-200">
                  {{ $t->allocation_category }}
                </span>
                <span>•</span>
                <span class="font-mono text-[10px]">{{ $t->receipt_number }}</span>
              </div>
            </div>

            <div class="pt-2 border-t border-slate-200/60 dark:border-slate-700/60 flex items-center justify-between">
              <div>
                <span class="text-[10px] text-slate-400 uppercase font-mono block">Nominal</span>
                <span class="text-sm font-black tracking-tight {{ $t->type === 'Penerimaan' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                  {{ $t->type === 'Penerimaan' ? '+ ' : '- ' }}Rp {{ number_format($t->amount, 0, ',', '.') }}
                </span>
              </div>

              <button onclick="openReceiptModal('{{ $t->code }}')" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold flex items-center space-x-1 shadow-sm">
                <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                <span>Nota</span>
              </button>
            </div>
          </div>
          @endforeach
        </div>
      </div>

      <!-- Accountability Banner -->
      <div class="p-4 sm:p-5 bg-emerald-50/70 dark:bg-emerald-950/30 border-t border-emerald-200/80 dark:border-emerald-800/50 flex items-start space-x-3">
        <div class="p-2 rounded-xl bg-emerald-600 text-white shrink-0">
          <i data-lucide="shield-check" class="w-4 h-4"></i>
        </div>
        <div class="text-xs leading-relaxed text-slate-700 dark:text-slate-300">
          <strong class="text-emerald-900 dark:text-emerald-400 font-bold block mb-0.5">Komitmen Akuntabilitas DKM:</strong>
          Seluruh mutasi kas di atas dapat diverifikasi secara daring melalui portal publik resmi Masjid Al-Hakim, lengkap dengan arsip digital kuitansi dan nota pembelanjaan fisik yang diunggah bendahara.
        </div>
      </div>
    </section>
  </div>

  <!-- ==================== MENU 3: KANAL PENERIMAAN INFAQ & SEDEKAH ==================== -->
  <div id="menu-view-donasi" class="menu-section menu-hidden space-y-5 sm:space-y-6">
    <section id="donasi" class="rounded-2xl bg-white dark:bg-slate-800 p-5 sm:p-8 shadow-sm border border-slate-200 dark:border-slate-700/80 space-y-6">
      <div class="border-b border-slate-200 dark:border-slate-700 pb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
          <div>
            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 mb-1">
              Layanan Amanah Umat
            </span>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">KANAL DONASI & STANDAR QRIS NASIONAL</h2>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400">Kemudahan Donasi Jamaah Melalui Transfer Bank dan QRIS Nasional</p>
          </div>
          <div class="hidden sm:block text-right">
            <span class="text-xs text-emerald-600 font-bold">Otomatis & Terverifikasi</span>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 sm:gap-6">
        <!-- 1. Rekening Bank Resmi -->
        <div class="rounded-2xl p-5 sm:p-6 bg-gradient-to-b from-slate-50 to-white dark:from-slate-900 dark:to-slate-850 border border-slate-200 dark:border-slate-700 flex flex-col justify-between shadow-sm">
          <div>
            <div class="flex items-center space-x-2 mb-4">
              <span class="w-7 h-7 rounded-lg bg-emerald-600 text-white font-bold flex items-center justify-center text-xs">1</span>
              <h3 class="font-bold text-slate-900 dark:text-white text-base">Rekening Bank Resmi</h3>
            </div>

            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 mb-4 shadow-inner">
              <div class="flex justify-between items-center text-xs text-slate-500 dark:text-slate-400 mb-1">
                <span>Nama Bank</span>
                <span class="font-bold text-emerald-700 dark:text-emerald-400">{{ $bank->bank_name ?? 'Bank Syariah Indonesia (BSI)' }}</span>
              </div>
              <div class="flex justify-between items-center py-2.5 border-y border-slate-100 dark:border-slate-700/60 my-2">
                <div>
                  <span class="text-[10px] text-slate-400 block font-mono">NOMOR REKENING</span>
                  <span class="text-xl sm:text-2xl font-mono font-black tracking-wider text-slate-900 dark:text-white">{{ $bank->account_number ?? '7182-9012-34' }}</span>
                </div>
                <button onclick="copyAccountNumber()" class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100 dark:hover:bg-emerald-900 transition flex items-center space-x-1.5 text-xs font-bold" title="Salin Nomor Rekening">
                  <i data-lucide="copy" class="w-4 h-4"></i>
                  <span>Salin</span>
                </button>
              </div>
              <div class="flex justify-between items-center text-xs text-slate-600 dark:text-slate-300">
                <span class="text-slate-500">Atas Nama:</span>
                <span class="font-bold">{{ $bank->account_name ?? 'Masjid Al-Hakim Kas Utama' }}</span>
              </div>
            </div>

            <div class="text-xs text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40 p-3 rounded-xl border border-amber-200 dark:border-amber-900/50 flex items-start space-x-2">
              <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5"></i>
              <span>Harap cantumkan keterangan atau konfirmasi via WhatsApp setelah melakukan transfer.</span>
            </div>
          </div>

          <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between text-xs">
            <span class="text-slate-500">Kebutuhan Kode Bank BSI:</span>
            <span class="font-mono font-bold px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-700">{{ $bank->bank_code ?? '451' }}</span>
          </div>
        </div>

        <!-- 2. QRIS Standar Nasional -->
        <div class="rounded-2xl p-5 sm:p-6 bg-gradient-to-b from-slate-50 to-white dark:from-slate-900 dark:to-slate-850 border border-slate-200 dark:border-slate-700 flex flex-col justify-between shadow-sm">
          <div>
            <div class="flex items-center space-x-2 mb-4">
              <span class="w-7 h-7 rounded-lg bg-emerald-600 text-white font-bold flex items-center justify-center text-xs">2</span>
              <h3 class="font-bold text-slate-900 dark:text-white text-base">QRIS (Standar Nasional)</h3>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-4 bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 mb-4">
              <div class="p-2.5 bg-white rounded-xl border border-slate-200 shadow-sm shrink-0 cursor-pointer text-center" onclick="openQrisZoom()">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=00020101021126600014ID.LINKAJA.WWW01189360091100202619020215ID10202619024810303UMI51440014ID.CO.QRIS.WWW0215ID10202619024810303UMI5204549953033605802ID5915MASJID%20AL-HAKIM6007JAKARTA61051234062070703A01630472EA" 
                  alt="QRIS Masjid Al-Hakim" class="w-28 h-28 mx-auto object-contain">
                <span class="text-[10px] text-emerald-700 font-bold block mt-1">Ketuk Perbesar</span>
              </div>

              <div class="space-y-2 text-xs w-full text-center sm:text-left">
                <div>
                  <span class="text-slate-400 block text-[10px] uppercase font-mono">NMID RESMI</span>
                  <span class="font-mono font-bold text-slate-800 dark:text-slate-200 text-sm">{{ $bank->qris_nmid ?? 'ID1020261902481' }}</span>
                </div>
                <div>
                  <span class="text-slate-400 block text-[10px] uppercase font-mono">MERCHANT</span>
                  <span class="font-semibold text-slate-800 dark:text-slate-200">MASJID AL-HAKIM</span>
                </div>
                <div class="flex flex-wrap gap-1 justify-center sm:justify-start pt-1">
                  <span class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-[10px]">BCA</span>
                  <span class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-[10px]">Mandiri</span>
                  <span class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-[10px]">BSI</span>
                  <span class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-[10px]">GoPay</span>
                  <span class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-[10px]">OVO</span>
                  <span class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-[10px]">DANA</span>
                </div>
              </div>
            </div>
          </div>

          <div class="flex items-center space-x-2 pt-2">
            <button onclick="openQrisZoom()" class="flex-1 py-2 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition flex items-center justify-center space-x-1.5 shadow-sm">
              <i data-lucide="maximize-2" class="w-3.5 h-3.5"></i>
              <span>Perbesar QR</span>
            </button>
            <button onclick="copyQrisNmid()" class="py-2 px-3 rounded-xl border border-slate-300 dark:border-slate-700 text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 transition">
              Salin NMID
            </button>
          </div>
        </div>
      </div>

      <!-- 3. Kalkulator & Konfirmasi Donasi WhatsApp -->
      <div class="rounded-2xl p-5 sm:p-6 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700">
        <div class="mb-4">
          <h3 class="font-bold text-slate-900 dark:text-white text-base flex items-center">
            <i data-lucide="calculator" class="w-4 h-4 mr-2 text-emerald-600"></i>
            Kalkulator Donasi & Konfirmasi WhatsApp
          </h3>
          <p class="text-xs text-slate-500 dark:text-slate-400">Pilih nominal donasi atau masukkan jumlah yang Anda kehendaki:</p>
        </div>

        <div class="grid grid-cols-3 sm:grid-cols-6 gap-2 mb-4">
          <button data-value="20000" class="nominal-preset-btn py-2 px-1 rounded-xl text-xs font-bold border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 hover:border-emerald-500 transition text-center">20 Ribu</button>
          <button data-value="50000" class="nominal-preset-btn py-2 px-1 rounded-xl text-xs font-bold border border-emerald-600 bg-emerald-600 text-white transition text-center shadow-sm">50 Ribu</button>
          <button data-value="100000" class="nominal-preset-btn py-2 px-1 rounded-xl text-xs font-bold border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 hover:border-emerald-500 transition text-center">100 Ribu</button>
          <button data-value="250000" class="nominal-preset-btn py-2 px-1 rounded-xl text-xs font-bold border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 hover:border-emerald-500 transition text-center">250 Ribu</button>
          <button data-value="500000" class="nominal-preset-btn py-2 px-1 rounded-xl text-xs font-bold border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 hover:border-emerald-500 transition text-center">500 Ribu</button>
          <button data-value="1000000" class="nominal-preset-btn py-2 px-1 rounded-xl text-xs font-bold border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 hover:border-emerald-500 transition text-center">1 Juta</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
          <div>
            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nominal Bebas (Rp)</label>
            <input type="number" id="customNominalInput" value="50000" inputmode="numeric" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500">
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Pilihan Pos Penyaluran</label>
            <select id="targetPosSelect" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500">
              <option value="Infaq Operasional & Umum">Infaq Operasional & Listrik</option>
              <option value="Infaq Dakwah & Kajian">Infaq Dakwah & Kajian</option>
              <option value="Sedekah Anak Yatim & Dhuafa">Santunan Anak Yatim & Dhuafa</option>
              <option value="Wakaf / Renovasi Bangunan">Wakaf Cadangan Bangunan</option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Jamaah (Opsional)</label>
            <div class="flex items-center space-x-2">
              <input type="text" id="donorNameInput" placeholder="Nama Anda" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500">
              <label class="flex items-center space-x-1 text-xs text-slate-500 shrink-0 cursor-pointer">
                <input type="checkbox" id="hambaAllahCheck" class="rounded text-emerald-600 focus:ring-emerald-500">
                <span>Hamba Allah</span>
              </label>
            </div>
          </div>
        </div>

        <button id="generateWaBtn" class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm flex items-center justify-center space-x-2 shadow-md transition">
          <i data-lucide="message-circle" class="w-4 h-4"></i>
          <span>Konfirmasi Donasi via WhatsApp Resmi DKM</span>
        </button>
      </div>
    </section>
  </div>

  <!-- ==================== MENU 4: ALOKASI 4 PILAR SYARIAH ==================== -->
  <div id="menu-view-alokasi" class="menu-section menu-hidden space-y-5 sm:space-y-6">
    <section id="alokasi" class="rounded-2xl bg-white dark:bg-slate-800 p-5 sm:p-8 shadow-sm border border-slate-200 dark:border-slate-700/80 space-y-6">
      <div class="border-b border-slate-200 dark:border-slate-700 pb-4">
        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 mb-1">
          Kaidah Syariah
        </span>
        <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">ALOKASI PENGGUNAAN DANA INFAQ (4 PILAR)</h2>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400">Pemanfaatan Dana Infaq dan Sedekah Berdasarkan Ketetapan Musyawarah DKM</p>
      </div>

      <!-- 4 Pilar Cards Container -->
      <div id="allocationCardsContainer" class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
        <!-- Rendered dynamically via JS or server-side fallback -->
        @foreach($allocations as $a)
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 sm:p-5 border border-slate-200 dark:border-slate-700/80 shadow-sm flex flex-col justify-between">
          <div>
            <div class="flex items-start justify-between gap-2 mb-3">
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white shrink-0 shadow-sm" style="background-color: {{ $a->color_hex }}">
                  <i data-lucide="{{ $a->icon_name }}" class="w-5 h-5"></i>
                </div>
                <div>
                  <h4 class="font-bold text-slate-900 dark:text-white text-sm sm:text-base leading-tight">{{ $a->name }}</h4>
                  <span class="inline-block mt-0.5 text-[11px] font-bold px-2 py-0.5 rounded-full" style="background-color: {{ $a->color_hex }}15; color: {{ $a->color_hex }}">
                    Porsi: {{ $a->target_percentage }}%
                  </span>
                </div>
              </div>
              <div class="text-right shrink-0">
                <span class="text-[10px] sm:text-xs text-slate-400 block uppercase font-mono">Pagu Target</span>
                <span class="font-black text-slate-800 dark:text-slate-100 text-xs sm:text-sm">
                  Rp {{ number_format(($totalPemasukan * $a->target_percentage) / 100, 0, ',', '.') }}
                </span>
              </div>
            </div>
            <p class="text-xs text-slate-600 dark:text-slate-400 mb-4 line-clamp-2 leading-relaxed">{{ $a->focus_description }}</p>
          </div>
        </div>
        @endforeach
      </div>

      <!-- Standar Audit & Akuntabilitas -->
      <div class="rounded-2xl p-5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 space-y-3">
        <h4 class="font-bold text-slate-900 dark:text-white text-sm flex items-center">
          <i data-lucide="award" class="w-4 h-4 mr-2 text-amber-500"></i>
          Standar Audit Transparansi & Akuntabilitas Jamaah
        </h4>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-slate-600 dark:text-slate-400">
          <div class="p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 flex items-start space-x-2">
            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5"></i>
            <div>
              <strong class="text-slate-800 dark:text-slate-200 block mb-0.5">Pemeriksaan Fisik</strong>
              Penghitungan kotak amal dilakukan bersama saksi minimal 3 pengurus masjid.
            </div>
          </div>
          <div class="p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 flex items-start space-x-2">
            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5"></i>
            <div>
              <strong class="text-slate-800 dark:text-slate-200 block mb-0.5">Arsip Digital & Nota</strong>
              Setiap mutasi pengeluaran wajib disertai nota/faktur bermaterai/stempel toko penyedia.
            </div>
          </div>
          <div class="p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 flex items-start space-x-2">
            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5"></i>
            <div>
              <strong class="text-slate-800 dark:text-slate-200 block mb-0.5">Laporan Terbuka</strong>
              Diumumkan sebelum shalat Jumat dan dapat diakses daring via layar TV & portal web.
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

</main>

<!-- ==================== MODAL 1: BUKTI NOTA / KUITANSI DIGITAL ==================== -->
<div id="receiptModal" class="modal-overlay hidden fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center p-3 sm:p-4 no-print">
  <div class="receipt-paper max-w-md w-full p-5 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-2xl relative max-h-[92vh] overflow-y-auto">
    <button onclick="closeReceiptModal()" aria-label="Tutup Bukti Nota" class="absolute top-4 right-4 p-1.5 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-300 transition">
      <i data-lucide="x" class="w-4 h-4"></i>
    </button>

    <div id="receiptModalContent">
      <!-- Rendered dynamically via JS -->
    </div>

    <div class="mt-5 text-center">
      <button onclick="closeReceiptModal()" class="w-full py-2 px-4 rounded-xl bg-slate-900 text-white dark:bg-slate-800 text-xs font-bold hover:bg-slate-850 transition">
        Tutup Bukti Kuitansi
      </button>
    </div>
  </div>
</div>

<!-- ==================== MODAL 2: INPUT KAS BENDAHARA ==================== -->
<div id="adminModal" class="modal-overlay hidden fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center p-3 sm:p-4 no-print">
  <div class="bg-white dark:bg-slate-900 max-w-lg w-full rounded-2xl p-5 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-2xl relative max-h-[92vh] overflow-y-auto">
    <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-200 dark:border-slate-800">
      <div class="flex items-center space-x-2">
        <div class="w-8 h-8 rounded-lg bg-amber-500 text-slate-900 flex items-center justify-center font-bold">
          <i data-lucide="plus-circle" class="w-5 h-5"></i>
        </div>
        <h3 class="font-black text-slate-900 dark:text-white text-base">Pencatatan Kas Bendahara</h3>
      </div>
      <button onclick="closeAdminModal()" aria-label="Tutup Form" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-white transition">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>

    <form id="addTransactionForm" onsubmit="handleAddTransaction(event)" class="space-y-3.5 text-xs">
      @csrf
      <div>
        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Transaksi</label>
        <input type="date" id="inputTanggal" required class="w-full py-2 px-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500">
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Jenis Transaksi</label>
          <select id="inputKategori" required class="w-full py-2 px-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="Penerimaan">Penerimaan (Kas Masuk)</option>
            <option value="Pengeluaran">Pengeluaran (Kas Keluar)</option>
          </select>
        </div>
        <div>
          <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Nominal (Rp)</label>
          <input type="number" id="inputNominal" placeholder="Contoh: 500000" inputmode="numeric" required class="w-full py-2 px-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>
      </div>

      <div>
        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Pos Alokasi Anggaran</label>
        <select id="inputPosAnggaran" required class="w-full py-2 px-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500">
          <option value="Operasional & Utilitas">Operasional & Utilitas (Listrik, Air, Kebersihan)</option>
          <option value="Dakwah & Kajian">Dakwah & Kajian (Honor Penceramah, Takjil)</option>
          <option value="Sosial & Yatim Dhuafa">Sosial & Yatim Dhuafa (Santunan & Sembako)</option>
          <option value="Dana Cadangan Bangunan">Dana Cadangan Bangunan (Pemeliharaan)</option>
        </select>
      </div>

      <div>
        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Keterangan / Uraian</label>
        <input type="text" id="inputKeterangan" placeholder="Contoh: Infaq Kotak Amal Shalat Jumat" required class="w-full py-2 px-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500">
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Metode Penerimaan / Bayar</label>
          <select id="inputMetode" class="w-full py-2 px-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="Tunai">Tunai / Kotak Amal</option>
            <option value="Transfer BSI">Transfer Bank BSI</option>
            <option value="QRIS Nasional">QRIS Nasional</option>
            <option value="Auto-Debit Bank">Auto-Debit Rekening</option>
          </select>
        </div>
        <div>
          <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Penanggung Jawab (PIC)</label>
          <input type="text" id="inputPic" placeholder="H. Ahmad S. (Bendahara)" class="w-full py-2 px-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>
      </div>

      <div>
        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Catatan Verifikasi Nota Fisik</label>
        <textarea id="inputCatatan" rows="2" placeholder="Catatan bukti nota fisik tersimpan di kantor DKM..." class="w-full py-2 px-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
      </div>

      <div class="flex items-center space-x-2 pt-2">
        <button type="button" onclick="closeAdminModal()" class="flex-1 py-2 px-3 rounded-xl border border-slate-300 dark:border-slate-700 font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
          Batal
        </button>
        <button type="submit" class="flex-1 py-2 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-md transition">
          Simpan Transaksi
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ==================== MODAL 3: QRIS ZOOM ==================== -->
<div id="qrisZoomModal" class="modal-overlay hidden fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 no-print">
  <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl max-w-sm w-full text-center border border-slate-200 dark:border-slate-700 shadow-2xl relative">
    <button onclick="closeQrisZoom()" aria-label="Tutup QR" class="absolute top-4 right-4 p-1.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-300 transition">
      <i data-lucide="x" class="w-4 h-4"></i>
    </button>

    <div class="mb-3">
      <h3 class="font-extrabold text-slate-900 dark:text-white text-base">QRIS MASJID AL-HAKIM</h3>
      <p class="text-xs text-slate-500">Scan untuk Infaq & Sedekah Jamaah</p>
    </div>

    <div class="p-3 bg-white rounded-2xl border border-slate-200 inline-block shadow-inner mb-3">
      <img src="https://api.qrserver.com/v1/create-qr-code/?size=260x260&data=00020101021126600014ID.LINKAJA.WWW01189360091100202619020215ID10202619024810303UMI51440014ID.CO.QRIS.WWW0215ID10202619024810303UMI5204549953033605802ID5915MASJID%20AL-HAKIM6007JAKARTA61051234062070703A01630472EA" 
        alt="QRIS Zoom" class="w-60 h-60 mx-auto object-contain">
    </div>

    <p class="text-xs font-mono font-bold text-slate-700 dark:text-slate-300">NMID: {{ $bank->qris_nmid ?? 'ID1020261902481' }}</p>
    <p class="text-[11px] text-slate-400 mt-1">Dukung seluruh bank (BCA, Mandiri, BSI) & dompet digital</p>

    <button onclick="closeQrisZoom()" class="mt-4 w-full py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition">
      Tutup Tampilan QR
    </button>
  </div>
</div>
@endsection
