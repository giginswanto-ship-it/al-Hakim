/**
 * MASJID AL-HAKIM - Interactive Web Application Engine
 * Mobile-Responsive, Touch-Optimized & 1-Minute TV Display Engine
 */

// State Management
let transactions = [];
let allocationChartInstance = null;
let cashflowChartInstance = null;
let isMobileMenuOpen = false;

// Initialize on DOM Ready
document.addEventListener('DOMContentLoaded', () => {
  loadTransactions();
  initTheme();
  initClockAndPrayerTimes();
  initMobileNavigation();
  renderSummaryCards();
  renderAllocations();
  renderTransactionsTable();
  initCharts();
  initFilterListeners();
  initDonationCalculator();
  initAutoRotation();
  initTableAutoScroll();
  initModalListeners();
  lucide.createIcons();
});

// Load transactions from localStorage or default data
function loadTransactions() {
  const saved = localStorage.getItem('alhakim_transactions');
  if (saved) {
    try {
      transactions = JSON.parse(saved);
    } catch (e) {
      transactions = [...DEFAULT_TRANSACTIONS];
    }
  } else {
    transactions = [...DEFAULT_TRANSACTIONS];
  }
}

function saveTransactions() {
  localStorage.setItem('alhakim_transactions', JSON.stringify(transactions));
}

// Format Currency to IDR (Rp X.XXX.XXX)
function formatRupiah(amount) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(amount).replace(/\s/g, ' ');
}

// Financial Summary Calculations
function calculateFinancials() {
  let totalPemasukan = 0;
  let totalPengeluaran = 0;

  transactions.forEach(t => {
    if (t.kategori === 'Penerimaan') {
      totalPemasukan += Number(t.nominal);
    } else if (t.kategori === 'Pengeluaran') {
      totalPengeluaran += Number(t.nominal);
    }
  });

  // Base starting balance: 47.000.000 (October 2026 report)
  const baseStartingKas = 47000000;
  const saldoKasBersih = baseStartingKas + totalPemasukan - totalPengeluaran;

  return {
    saldoKasBersih,
    totalPemasukan,
    totalPengeluaran,
    surplusKas: totalPemasukan - totalPengeluaran
  };
}

// Render Summary Cards
function renderSummaryCards() {
  const fin = calculateFinancials();

  animateValue('saldoKasBersihEl', fin.saldoKasBersih);
  animateValue('pemasukanBulanIniEl', fin.totalPemasukan);
  animateValue('pengeluaranBulanIniEl', fin.totalPengeluaran);
  animateValue('surplusKasEl', fin.surplusKas);

  // Update mobile summary stats badges if present
  const mobileSurplusEl = document.getElementById('mobileSurplusBadge');
  if (mobileSurplusEl) {
    mobileSurplusEl.textContent = `Surplus: ${formatRupiah(fin.surplusKas)}`;
  }
}

function animateValue(elementId, targetValue) {
  const el = document.getElementById(elementId);
  if (!el) return;
  el.textContent = formatRupiah(targetValue);
}

// Render Allocation Progress Cards (Mobile & Desktop)
function renderAllocations() {
  const container = document.getElementById('allocationCardsContainer');
  if (!container) return;

  const fin = calculateFinancials();
  container.innerHTML = '';

  ALLOCATION_DATA.forEach(alloc => {
    const targetNominal = (fin.totalPemasukan * alloc.targetPercent) / 100;
    const actualExpense = transactions
      .filter(t => t.kategori === 'Pengeluaran' && t.posAnggaran === alloc.pos)
      .reduce((sum, t) => sum + Number(t.nominal), 0);

    const percentUsed = targetNominal > 0 ? Math.min(Math.round((actualExpense / targetNominal) * 100), 100) : 0;

    const card = document.createElement('div');
    card.className = 'bg-white dark:bg-slate-800 rounded-2xl p-4 sm:p-5 border border-slate-200 dark:border-slate-700/80 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between';
    card.innerHTML = `
      <div>
        <div class="flex items-start justify-between gap-2 mb-3">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white shrink-0 shadow-sm" style="background-color: ${alloc.color}">
              <i data-lucide="${alloc.icon}" class="w-5 h-5"></i>
            </div>
            <div>
              <h4 class="font-bold text-slate-900 dark:text-white text-sm sm:text-base leading-tight">${alloc.pos}</h4>
              <span class="inline-block mt-0.5 text-[11px] font-bold px-2 py-0.5 rounded-full" style="background-color: ${alloc.color}15; color: ${alloc.color}">
                Porsi: ${alloc.targetPercent}%
              </span>
            </div>
          </div>
          <div class="text-right shrink-0">
            <span class="text-[10px] sm:text-xs text-slate-400 block uppercase font-mono">Pagu Target</span>
            <span class="font-black text-slate-800 dark:text-slate-100 text-xs sm:text-sm">${formatRupiah(targetNominal)}</span>
          </div>
        </div>
        
        <p class="text-xs text-slate-600 dark:text-slate-400 mb-4 line-clamp-2 leading-relaxed">${alloc.fokus}</p>
      </div>
      
      <div class="pt-2 border-t border-slate-100 dark:border-slate-700/60">
        <div class="flex justify-between items-center text-xs mb-1.5">
          <span class="text-slate-500 dark:text-slate-400 text-[11px]">Realisasi: <strong class="text-slate-800 dark:text-slate-200">${formatRupiah(actualExpense)}</strong></span>
          <span class="font-bold text-slate-700 dark:text-slate-300 text-xs">${percentUsed}%</span>
        </div>
        <div class="w-full bg-slate-100 dark:bg-slate-700 h-2 sm:h-2.5 rounded-full overflow-hidden">
          <div class="h-full rounded-full transition-all duration-700" style="width: ${percentUsed}%; background-color: ${alloc.color}"></div>
        </div>
      </div>
    `;
    container.appendChild(card);
  });

  lucide.createIcons();
}

// Render Transactions Table & Mobile Card Feed
function renderTransactionsTable() {
  const tbody = document.getElementById('transactionsTableBody');
  const mobileCardsContainer = document.getElementById('transactionsMobileCardsContainer');
  const countBadge = document.getElementById('transactionCountBadge');
  if (!tbody && !mobileCardsContainer) return;

  const searchTerm = (document.getElementById('searchInput')?.value || '').toLowerCase().trim();
  const kategoriFilter = document.getElementById('kategoriFilter')?.value || 'all';
  const posFilter = document.getElementById('posFilter')?.value || 'all';
  const sortOrder = document.getElementById('sortOrder')?.value || 'date-desc';

  let filtered = transactions.filter(t => {
    const matchesSearch = 
      t.keterangan.toLowerCase().includes(searchTerm) ||
      t.posAnggaran.toLowerCase().includes(searchTerm) ||
      t.tanggalFormatted.toLowerCase().includes(searchTerm) ||
      t.nominal.toString().includes(searchTerm) ||
      (t.buktiNo && t.buktiNo.toLowerCase().includes(searchTerm));

    const matchesKategori = (kategoriFilter === 'all') || (t.kategori === kategoriFilter);
    const matchesPos = (posFilter === 'all') || (t.posAnggaran === posFilter);

    return matchesSearch && matchesKategori && matchesPos;
  });

  // Sorting
  filtered.sort((a, b) => {
    if (sortOrder === 'date-desc') return new Date(b.tanggal) - new Date(a.tanggal);
    if (sortOrder === 'date-asc') return new Date(a.tanggal) - new Date(b.tanggal);
    if (sortOrder === 'amount-desc') return b.nominal - a.nominal;
    if (sortOrder === 'amount-asc') return a.nominal - b.nominal;
    return 0;
  });

  if (countBadge) {
    countBadge.textContent = `${filtered.length} Mutasi`;
  }

  // 1. Render Desktop Table View
  if (tbody) {
    if (filtered.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="5" class="py-12 text-center text-slate-400 dark:text-slate-500">
            <i data-lucide="search-x" class="w-10 h-10 mx-auto mb-2 opacity-50"></i>
            <p class="font-medium text-sm">Tidak ada transaksi yang cocok dengan filter pencarian.</p>
            <button onclick="resetFilters()" class="mt-3 text-xs text-emerald-600 dark:text-emerald-400 hover:underline font-bold">Reset Semua Filter</button>
          </td>
        </tr>
      `;
    } else {
      tbody.innerHTML = '';
      filtered.forEach(t => {
        const isPenerimaan = t.kategori === 'Penerimaan';
        const tr = document.createElement('tr');
        tr.className = 'border-b border-slate-100 dark:border-slate-800/80 hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition cursor-pointer';
        tr.onclick = (e) => {
          if (e.target.closest('button')) return;
          openReceiptModal(t.id);
        };

        tr.innerHTML = `
          <td class="py-3.5 px-4 text-xs font-semibold text-slate-600 dark:text-slate-300 whitespace-nowrap">
            <div class="flex items-center space-x-2">
              <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
              <span>${t.tanggalFormatted}</span>
            </div>
          </td>
          <td class="py-3.5 px-4">
            <div class="font-bold text-slate-800 dark:text-slate-100 text-sm leading-snug">${t.keterangan}</div>
            <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center space-x-2 mt-0.5">
              <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 dark:bg-slate-700/80 text-slate-600 dark:text-slate-300">
                ${t.posAnggaran}
              </span>
              <span class="text-slate-300 dark:text-slate-600">•</span>
              <span>No: ${t.buktiNo || 'KWT-DIGITAL'}</span>
            </div>
          </td>
          <td class="py-3.5 px-4 whitespace-nowrap">
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold ${
              isPenerimaan 
                ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50' 
                : 'bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200 dark:border-rose-800/50'
            }">
              <span class="w-1.5 h-1.5 rounded-full mr-1.5 ${isPenerimaan ? 'bg-emerald-500' : 'bg-rose-500'}"></span>
              ${t.kategori}
            </span>
          </td>
          <td class="py-3.5 px-4 text-right whitespace-nowrap">
            <span class="text-sm font-black tracking-tight ${
              isPenerimaan ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'
            }">
              ${isPenerimaan ? '+ ' : '- '}${formatRupiah(t.nominal)}
            </span>
          </td>
          <td class="py-3.5 px-4 text-center whitespace-nowrap">
            <button onclick="openReceiptModal('${t.id}')" 
              class="inline-flex items-center space-x-1.5 text-xs font-semibold px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-300 dark:hover:bg-emerald-900/50 border border-emerald-200 dark:border-emerald-700 transition"
              title="Verifikasi Bukti Kuitansi & Nota">
              <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
              <span>Bukti Nota</span>
            </button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }
  }

  // 2. Render Mobile Card Feed View (Optimized for Handphone Screens)
  if (mobileCardsContainer) {
    if (filtered.length === 0) {
      mobileCardsContainer.innerHTML = `
        <div class="py-10 text-center text-slate-400 dark:text-slate-500">
          <i data-lucide="search-x" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
          <p class="font-medium text-xs">Tidak ada mutasi yang cocok.</p>
          <button onclick="resetFilters()" class="mt-2 text-xs text-emerald-600 dark:text-emerald-400 hover:underline font-bold">Reset Filter</button>
        </div>
      `;
    } else {
      mobileCardsContainer.innerHTML = '';
      filtered.forEach(t => {
        const isPenerimaan = t.kategori === 'Penerimaan';
        const card = document.createElement('div');
        card.className = 'bg-slate-50/70 dark:bg-slate-850 p-4 rounded-xl border border-slate-200/80 dark:border-slate-700/80 space-y-2.5 transition active:bg-slate-100 dark:active:bg-slate-800';
        card.onclick = (e) => {
          if (e.target.closest('button')) return;
          openReceiptModal(t.id);
        };

        card.innerHTML = `
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-1.5 text-[11px] font-semibold text-slate-500 dark:text-slate-400">
              <i data-lucide="calendar" class="w-3.5 h-3.5 text-amber-500"></i>
              <span>${t.tanggalFormatted}</span>
            </div>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold ${
              isPenerimaan 
                ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' 
                : 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300'
            }">
              <span class="w-1.5 h-1.5 rounded-full mr-1 ${isPenerimaan ? 'bg-emerald-500' : 'bg-rose-500'}"></span>
              ${t.kategori}
            </span>
          </div>

          <div>
            <h4 class="font-bold text-slate-900 dark:text-white text-sm leading-snug">${t.keterangan}</h4>
            <div class="flex items-center space-x-1.5 text-[11px] text-slate-500 dark:text-slate-400 mt-1">
              <span class="px-1.5 py-0.5 rounded bg-slate-200/80 dark:bg-slate-700/80 text-[10px] font-medium text-slate-700 dark:text-slate-200">
                ${t.posAnggaran}
              </span>
              <span>•</span>
              <span class="font-mono text-[10px]">${t.buktiNo || 'KWT-DIGITAL'}</span>
            </div>
          </div>

          <div class="pt-2 border-t border-slate-200/60 dark:border-slate-700/60 flex items-center justify-between">
            <div>
              <span class="text-[10px] text-slate-400 uppercase font-mono block">Nominal</span>
              <span class="text-sm font-black tracking-tight ${
                isPenerimaan ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'
              }">
                ${isPenerimaan ? '+ ' : '- '}${formatRupiah(t.nominal)}
              </span>
            </div>

            <button onclick="openReceiptModal('${t.id}')" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold flex items-center space-x-1 shadow-sm">
              <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
              <span>Nota</span>
            </button>
          </div>
        `;
        mobileCardsContainer.appendChild(card);
      });
    }
  }

  lucide.createIcons();

  // Trigger auto scroll check after rendering
  setTimeout(() => {
    checkAndTriggerTableAutoScroll();
  }, 120);
}

function resetFilters() {
  const searchInput = document.getElementById('searchInput');
  const kategoriFilter = document.getElementById('kategoriFilter');
  const posFilter = document.getElementById('posFilter');
  const sortOrder = document.getElementById('sortOrder');

  if (searchInput) searchInput.value = '';
  if (kategoriFilter) kategoriFilter.value = 'all';
  if (posFilter) posFilter.value = 'all';
  if (sortOrder) sortOrder.value = 'date-desc';

  renderTransactionsTable();
}

function initFilterListeners() {
  document.getElementById('searchInput')?.addEventListener('input', renderTransactionsTable);
  document.getElementById('kategoriFilter')?.addEventListener('change', renderTransactionsTable);
  document.getElementById('posFilter')?.addEventListener('change', renderTransactionsTable);
  document.getElementById('sortOrder')?.addEventListener('change', renderTransactionsTable);
}

// Chart Initializations (Responsive for Mobile & TV Display)
function initCharts() {
  // Chart 1: Alokasi Penggunaan Dana Infaq (Doughnut)
  const allocCtx = document.getElementById('allocationChart')?.getContext('2d');
  if (allocCtx) {
    if (allocationChartInstance) allocationChartInstance.destroy();
    
    allocationChartInstance = new Chart(allocCtx, {
      type: 'doughnut',
      data: {
        labels: ALLOCATION_DATA.map(a => a.pos),
        datasets: [{
          data: ALLOCATION_DATA.map(a => a.targetPercent),
          backgroundColor: ALLOCATION_DATA.map(a => a.color),
          borderWidth: 2,
          borderColor: '#ffffff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              boxWidth: 10,
              font: { family: 'Plus Jakarta Sans', size: 10 },
              padding: 10
            }
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                const label = context.label || '';
                const val = context.raw || 0;
                return ` ${label}: ${val}% Target Alokasi`;
              }
            }
          }
        },
        cutout: '66%'
      }
    });
  }

  // Chart 2: Mutasi Pemasukan vs Pengeluaran (Bar Chart)
  const flowCtx = document.getElementById('cashflowChart')?.getContext('2d');
  if (flowCtx) {
    if (cashflowChartInstance) cashflowChartInstance.destroy();

    const fin = calculateFinancials();
    cashflowChartInstance = new Chart(flowCtx, {
      type: 'bar',
      data: {
        labels: ['Pemasukan', 'Pengeluaran', 'Surplus Kas'],
        datasets: [{
          label: 'Nominal (Rp)',
          data: [fin.totalPemasukan, fin.totalPengeluaran, fin.surplusKas],
          backgroundColor: ['#059669', '#e11d48', '#2563eb'],
          borderRadius: 8,
          maxBarThickness: 40
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: function(context) {
                return ` Nominal: ${formatRupiah(context.raw)}`;
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return 'Rp ' + (value / 1000000).toFixed(0) + ' Jt';
              },
              font: { family: 'Plus Jakarta Sans', size: 9 }
            },
            grid: { color: 'rgba(226, 232, 240, 0.4)' }
          },
          x: {
            grid: { display: false },
            ticks: { font: { family: 'Plus Jakarta Sans', size: 10, weight: '600' } }
          }
        }
      }
    });
  }
}

function updateCharts() {
  if (cashflowChartInstance) {
    const fin = calculateFinancials();
    cashflowChartInstance.data.datasets[0].data = [fin.totalPemasukan, fin.totalPengeluaran, fin.surplusKas];
    cashflowChartInstance.update();
  }
}

// Receipt Modal View
function openReceiptModal(transactionId) {
  const item = transactions.find(t => t.id === transactionId);
  if (!item) return;

  const modal = document.getElementById('receiptModal');
  const content = document.getElementById('receiptModalContent');
  if (!modal || !content) return;

  const isPenerimaan = item.kategori === 'Penerimaan';

  content.innerHTML = `
    <!-- Header Nota -->
    <div class="border-b border-dashed border-slate-300 dark:border-slate-700 pb-4 mb-4 text-center relative">
      <div class="w-12 h-12 mx-auto mb-2 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-700 dark:text-emerald-300 shadow-sm">
        <i data-lucide="landmark" class="w-6 h-6"></i>
      </div>
      <h3 class="text-lg sm:text-xl font-black text-slate-900 dark:text-white uppercase tracking-wider">MASJID AL-HAKIM</h3>
      <p class="text-[11px] text-slate-500 dark:text-slate-400">Sistem Transparansi Kas & Pelaporan Publik Jamaah</p>
      
      <div class="mt-2.5 inline-block px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-xs font-mono font-bold text-slate-700 dark:text-slate-300">
        BUKTI MUTASI #${item.buktiNo || item.id}
      </div>
    </div>

    <!-- Data Detail -->
    <div class="space-y-2.5 text-xs sm:text-sm">
      <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800">
        <span class="text-slate-500 dark:text-slate-400">Tanggal Pencatatan</span>
        <span class="font-bold text-slate-800 dark:text-slate-200">${item.tanggalFormatted}</span>
      </div>
      <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800">
        <span class="text-slate-500 dark:text-slate-400">Jenis Transaksi</span>
        <span class="font-bold ${isPenerimaan ? 'text-emerald-600' : 'text-rose-600'}">${item.kategori.toUpperCase()}</span>
      </div>
      <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800">
        <span class="text-slate-500 dark:text-slate-400">Pos Alokasi</span>
        <span class="font-semibold text-slate-800 dark:text-slate-200">${item.posAnggaran}</span>
      </div>
      <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800">
        <span class="text-slate-500 dark:text-slate-400">Keterangan</span>
        <span class="font-bold text-slate-800 dark:text-slate-200 text-right max-w-[210px] leading-tight">${item.keterangan}</span>
      </div>
      <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800">
        <span class="text-slate-500 dark:text-slate-400">Metode / Kanal</span>
        <span class="font-medium text-slate-800 dark:text-slate-200">${item.metode || 'Kas Tunai'}</span>
      </div>
      <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800">
        <span class="text-slate-500 dark:text-slate-400">Penanggung Jawab</span>
        <span class="font-medium text-slate-800 dark:text-slate-200">${item.pic || 'Bendahara DKM'}</span>
      </div>
    </div>

    <!-- Catatan Verifikasi Fisik -->
    <div class="mt-3.5 p-3 rounded-xl bg-emerald-50/70 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-800/40 text-xs">
      <div class="font-bold text-emerald-800 dark:text-emerald-300 mb-1 flex items-center">
        <i data-lucide="shield-check" class="w-4 h-4 mr-1 text-emerald-600"></i>
        Catatan Audit & Verifikasi Fisik:
      </div>
      <p class="text-emerald-900/80 dark:text-emerald-200/90 leading-relaxed text-[11px] sm:text-xs">${item.catatan || 'Arsip nota fisik tersimpan di buku kas sekretariat.'}</p>
    </div>

    <!-- Total Amount Box -->
    <div class="mt-4 p-3.5 rounded-xl bg-slate-900 text-white dark:bg-slate-950 flex justify-between items-center shadow-inner">
      <span class="text-[10px] sm:text-xs uppercase font-medium text-slate-400">Total Transaksi</span>
      <span class="text-lg sm:text-xl font-black ${isPenerimaan ? 'text-emerald-400' : 'text-rose-400'}">
        ${isPenerimaan ? '+ ' : '- '}${formatRupiah(item.nominal)}
      </span>
    </div>

    <!-- Digital Validation & Stamped Watermark -->
    <div class="mt-4 pt-3 border-t border-dashed border-slate-200 dark:border-slate-700 flex items-center justify-between">
      <div class="verified-stamp text-[10px] sm:text-xs py-1 px-3">
        TERVERIFIKASI
      </div>
      <div class="text-right">
        <p class="text-[9px] text-slate-400">Otorisasi DKM</p>
        <p class="text-xs font-bold text-slate-800 dark:text-slate-200">H. Ahmad S.</p>
        <p class="text-[9px] text-slate-500">Bendahara DKM</p>
      </div>
    </div>
  `;

  modal.classList.remove('hidden');
  lucide.createIcons();
  pauseAutoRotationTemporarily();
}

function closeReceiptModal() {
  document.getElementById('receiptModal')?.classList.add('hidden');
  resumeAutoRotation();
}

// Mobile Hamburger Navigation Engine
function initMobileNavigation() {
  const toggleBtn = document.getElementById('mobileMenuToggleBtn');
  const closeBtn = document.getElementById('closeMobileMenuBtn');
  const overlay = document.getElementById('mobileMenuOverlay');
  const drawer = document.getElementById('mobileDrawer');

  if (toggleBtn) {
    toggleBtn.addEventListener('click', toggleMobileMenu);
  }

  if (closeBtn) {
    closeBtn.addEventListener('click', closeMobileMenu);
  }

  if (overlay) {
    overlay.addEventListener('click', closeMobileMenu);
  }

  // Close on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && isMobileMenuOpen) {
      closeMobileMenu();
    }
  });

  // Close drawer when any mobile nav link is clicked
  const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
  mobileNavLinks.forEach(link => {
    link.addEventListener('click', () => {
      closeMobileMenu();
    });
  });
}

function openMobileMenu() {
  const drawer = document.getElementById('mobileDrawer');
  const overlay = document.getElementById('mobileMenuOverlay');
  const toggleBtn = document.getElementById('mobileMenuToggleBtn');

  if (drawer && overlay) {
    drawer.classList.remove('drawer-closed');
    drawer.classList.add('drawer-open');
    overlay.classList.remove('overlay-hidden');
    overlay.classList.add('overlay-visible');
    document.body.style.overflow = 'hidden'; // Lock background scrolling
    isMobileMenuOpen = true;

    if (toggleBtn) toggleBtn.classList.add('hamburger-active');
  }
}

function closeMobileMenu() {
  const drawer = document.getElementById('mobileDrawer');
  const overlay = document.getElementById('mobileMenuOverlay');
  const toggleBtn = document.getElementById('mobileMenuToggleBtn');

  if (drawer && overlay) {
    drawer.classList.add('drawer-closed');
    drawer.classList.remove('drawer-open');
    overlay.classList.add('overlay-hidden');
    overlay.classList.remove('overlay-visible');
    document.body.style.overflow = ''; // Restore background scrolling
    isMobileMenuOpen = false;

    if (toggleBtn) toggleBtn.classList.remove('hamburger-active');
  }
}

function toggleMobileMenu() {
  if (isMobileMenuOpen) {
    closeMobileMenu();
  } else {
    openMobileMenu();
  }
}

// Donation Calculator & WhatsApp Generator
function initDonationCalculator() {
  const nominalBtns = document.querySelectorAll('.nominal-preset-btn');
  const customInput = document.getElementById('customNominalInput');
  const targetPosSelect = document.getElementById('targetPosSelect');
  const donorNameInput = document.getElementById('donorNameInput');
  const hambaAllahCheck = document.getElementById('hambaAllahCheck');
  const generateWaBtn = document.getElementById('generateWaBtn');

  nominalBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      nominalBtns.forEach(b => b.classList.remove('bg-emerald-600', 'text-white', 'border-emerald-600'));
      btn.classList.add('bg-emerald-600', 'text-white', 'border-emerald-600');
      if (customInput) customInput.value = btn.dataset.value;
    });
  });

  if (customInput) {
    customInput.addEventListener('input', () => {
      nominalBtns.forEach(b => b.classList.remove('bg-emerald-600', 'text-white', 'border-emerald-600'));
    });
  }

  if (hambaAllahCheck && donorNameInput) {
    hambaAllahCheck.addEventListener('change', (e) => {
      if (e.target.checked) {
        donorNameInput.value = "Hamba Allah";
        donorNameInput.disabled = true;
      } else {
        donorNameInput.value = "";
        donorNameInput.disabled = false;
      }
    });
  }

  if (generateWaBtn) {
    generateWaBtn.addEventListener('click', () => {
      const nominal = customInput?.value || "50000";
      const donorName = donorNameInput?.value.trim() || "Hamba Allah";
      const targetPos = targetPosSelect?.value || "Infaq Operasional & Umum";
      
      const message = `*KONFIRMASI INFAQ / SEDEKAH MASJID AL-HAKIM*%0A%0A` +
        `Assalamu'alaikum Warahmatullahi Wabarakatuh DKM Masjid Al-Hakim,%0A` +
        `Saya bermaksud mengonfirmasi penyaluran infaq/sedekah:%0A%0A` +
        `• *Nama Jamaah:* ${donorName}%0A` +
        `• *Nominal:* ${formatRupiah(nominal)}%0A` +
        `• *Alokasi Pos:* ${targetPos}%0A` +
        `• *Metode:* Transfer BSI / Scan QRIS%0A` +
        `• *Tanggal:* ${new Date().toLocaleDateString('id-ID', { dateStyle: 'full' })}%0A%0A` +
        `Mohon dicatat dalam pembukuan kas transparansi masjid. Semoga membawa berkah bagi kemakmuran masjid dan jamaah. Aamiin ya Rabbal 'Alamin.`;

      const waUrl = `https://wa.me/${BANK_INFO.whatsappNumber}?text=${message}`;
      window.open(waUrl, '_blank');
    });
  }
}

// Copy Bank Account Number to Clipboard
function copyAccountNumber() {
  const accountNum = "7182901234";
  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(accountNum).then(() => {
      showToast("Nomor rekening BSI (7182-9012-34) berhasil disalin!");
    }).catch(() => {
      fallbackCopyText(accountNum, "Nomor rekening BSI (7182-9012-34) berhasil disalin!");
    });
  } else {
    fallbackCopyText(accountNum, "Nomor rekening BSI (7182-9012-34) berhasil disalin!");
  }
}

// Copy QRIS NMID to Clipboard
function copyQrisNmid() {
  const nmid = BANK_INFO.qrisNmid || "ID1020261902481";
  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(nmid).then(() => {
      showToast("NMID QRIS (" + nmid + ") berhasil disalin!");
    }).catch(() => {
      fallbackCopyText(nmid, "NMID QRIS (" + nmid + ") berhasil disalin!");
    });
  } else {
    fallbackCopyText(nmid, "NMID QRIS (" + nmid + ") berhasil disalin!");
  }
}

function fallbackCopyText(text, successMessage) {
  const tempInput = document.createElement("input");
  tempInput.value = text;
  document.body.appendChild(tempInput);
  tempInput.select();
  document.execCommand("copy");
  document.body.removeChild(tempInput);
  showToast(successMessage);
}

// Toast notification helper
function showToast(message, type = 'success') {
  let toast = document.getElementById('toastNotification');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'toastNotification';
    toast.className = 'fixed bottom-5 right-4 sm:right-6 z-50 flex items-center space-x-2.5 px-4 py-3 rounded-2xl shadow-2xl transition-all duration-300 transform translate-y-20 opacity-0 text-white font-semibold text-xs sm:text-sm max-w-[90vw]';
    document.body.appendChild(toast);
  }

  toast.className = `fixed bottom-5 right-4 sm:right-6 z-50 flex items-center space-x-2.5 px-4 py-3 rounded-2xl shadow-2xl transition-all duration-300 transform text-white font-semibold text-xs sm:text-sm max-w-[90vw] ${
    type === 'success' ? 'bg-emerald-600' : 'bg-rose-600'
  }`;
  
  toast.innerHTML = `
    <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-4 h-4 shrink-0"></i>
    <span class="truncate">${message}</span>
  `;
  lucide.createIcons();

  setTimeout(() => {
    toast.classList.remove('translate-y-20', 'opacity-0');
  }, 40);

  setTimeout(() => {
    toast.classList.add('translate-y-20', 'opacity-0');
  }, 3200);
}

// Real-Time Clock & Prayer Times
function initClockAndPrayerTimes() {
  function updateTime() {
    const now = new Date();
    const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    
    document.querySelectorAll('.liveClock').forEach(el => {
      el.textContent = timeStr + ' WIB';
    });

    const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
    const dateFormatted = now.toLocaleDateString('id-ID', options);

    document.querySelectorAll('.liveDate').forEach(el => {
      el.textContent = dateFormatted;
    });
  }

  updateTime();
  setInterval(updateTime, 1000);
}

// Theme (Dark/Light) Mode Toggle
function initTheme() {
  const themeToggles = document.querySelectorAll('.themeToggleBtn');
  const isDark = localStorage.getItem('alhakim_theme') === 'dark' || 
    (!localStorage.getItem('alhakim_theme') && window.matchMedia('(prefers-color-scheme: dark)').matches);

  if (isDark) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }

  themeToggles.forEach(btn => {
    btn.addEventListener('click', () => {
      document.documentElement.classList.toggle('dark');
      const currentDark = document.documentElement.classList.contains('dark');
      localStorage.setItem('alhakim_theme', currentDark ? 'dark' : 'light');
    });
  });
}

// Modal Admin: Tambah Mutasi Kas Baru
function openAdminModal() {
  closeMobileMenu();
  const modal = document.getElementById('adminModal');
  if (modal) modal.classList.remove('hidden');
  pauseAutoRotationTemporarily();
}

function closeAdminModal() {
  const modal = document.getElementById('adminModal');
  if (modal) modal.classList.add('hidden');
  resumeAutoRotation();
}

function initModalListeners() {
  ['adminModal', 'receiptModal', 'qrisZoomModal'].forEach(modalId => {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.addEventListener('click', (e) => {
        if (e.target === modal) {
          if (modalId === 'adminModal') closeAdminModal();
          if (modalId === 'receiptModal') closeReceiptModal();
          if (modalId === 'qrisZoomModal') closeQrisZoom();
        }
      });
    }
  });
}

// Handle Form Submission for New Mutation
function handleAddTransaction(event) {
  event.preventDefault();

  const tanggal = document.getElementById('inputTanggal').value;
  const kategori = document.getElementById('inputKategori').value;
  const keterangan = document.getElementById('inputKeterangan').value.trim();
  const posAnggaran = document.getElementById('inputPosAnggaran').value;
  const nominal = Number(document.getElementById('inputNominal').value);
  const metode = document.getElementById('inputMetode').value;
  const pic = document.getElementById('inputPic').value.trim() || 'Bendahara DKM';
  const catatan = document.getElementById('inputCatatan').value.trim() || 'Pencatatan kas via portal transparansi DKM.';

  if (!tanggal || !keterangan || nominal <= 0) {
    showToast('Mohon lengkapi semua isian formulir dengan benar.', 'error');
    return;
  }

  const d = new Date(tanggal);
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
  const tanggalFormatted = `${String(d.getDate()).padStart(2, '0')} ${months[d.getMonth()]} ${d.getFullYear()}`;

  const newTx = {
    id: `TX-${Date.now()}`,
    tanggal,
    tanggalFormatted,
    keterangan,
    kategori,
    posAnggaran,
    nominal,
    buktiNo: `KWT/${d.getFullYear()}/${String(d.getMonth() + 1).padStart(2, '0')}/${Math.floor(100 + Math.random() * 900)}`,
    pic,
    metode,
    catatan
  };

  transactions.unshift(newTx);
  saveTransactions();

  renderSummaryCards();
  renderAllocations();
  renderTransactionsTable();
  updateCharts();

  closeAdminModal();
  showToast(`Mutasi ${keterangan} berhasil dicatat!`);
  document.getElementById('addTransactionForm').reset();
}

// Reset data to default (Clear LocalStorage)
function resetToDefaultData() {
  if (confirm('Apakah Anda yakin ingin mengembalikan seluruh data kas ke kondisi awal laporan Oktober 2026?')) {
    localStorage.removeItem('alhakim_transactions');
    transactions = [...DEFAULT_TRANSACTIONS];
    renderSummaryCards();
    renderAllocations();
    renderTransactionsTable();
    updateCharts();
    showToast('Data kas berhasil dikembalikan ke standar laporan resmi!');
  }
}

// Export Transactions to CSV / Excel compatible
function exportToCSV() {
  const headers = ['ID Transaksi', 'Tanggal', 'Keterangan', 'Kategori', 'Pos Anggaran', 'Nominal (Rp)', 'No Bukti', 'Metode', 'PIC', 'Catatan'];
  
  const rows = transactions.map(t => [
    `"${t.id}"`,
    `"${t.tanggalFormatted}"`,
    `"${t.keterangan.replace(/"/g, '""')}"`,
    `"${t.kategori}"`,
    `"${t.posAnggaran}"`,
    t.nominal,
    `"${t.buktiNo || ''}"`,
    `"${t.metode || ''}"`,
    `"${t.pic || ''}"`,
    `"${(t.catatan || '').replace(/"/g, '""')}"`
  ]);

  const csvContent = 'data:text/csv;charset=utf-8,\uFEFF' + 
    [headers.join(','), ...rows.map(e => e.join(','))].join('\n');

  const encodedUri = encodeURI(csvContent);
  const link = document.createElement('a');
  link.setAttribute('href', encodedUri);
  link.setAttribute('download', `Laporan_Kas_Masjid_Al_Hakim_${new Date().toISOString().slice(0, 10)}.csv`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);

  showToast('Laporan mutasi berhasil diekspor ke CSV!');
}

// Trigger Print Report
function printReport() {
  closeMobileMenu();
  window.print();
}

// QRIS Modal Zoom
function openQrisZoom() {
  const modal = document.getElementById('qrisZoomModal');
  if (modal) modal.classList.remove('hidden');
  pauseAutoRotationTemporarily();
}

function closeQrisZoom() {
  const modal = document.getElementById('qrisZoomModal');
  if (modal) modal.classList.add('hidden');
  resumeAutoRotation();
}

// ==================== AUTO ROTATION ENGINE (1 MENIT PER MENU) ====================
const ROTATION_MENUS = ['ringkasan', 'mutasi', 'donasi', 'alokasi'];
const MENU_NAMES = {
  'ringkasan': '1. Ringkasan & Grafik Kas',
  'mutasi': '2. Mutasi Kas Terkini',
  'donasi': '3. Kanal Donasi & QRIS',
  'alokasi': '4. Alokasi Penggunaan Dana'
};

let currentMenuIndex = 0;
let rotationDuration = 60;
let remainingSeconds = 60;
let rotationIntervalTimer = null;
let isRotationPaused = false;
let wasPausedBeforeModal = false;
let isAllMode = false;

function initAutoRotation() {
  switchMenu('ringkasan', true);
  startRotationTimer();
}

function startRotationTimer() {
  if (rotationIntervalTimer) clearInterval(rotationIntervalTimer);

  rotationIntervalTimer = setInterval(() => {
    if (isRotationPaused || isAllMode) return;

    remainingSeconds--;

    document.querySelectorAll('.rotationCountdownEl').forEach(el => {
      el.textContent = `${remainingSeconds}s`;
    });

    const progressBar = document.getElementById('rotationProgressBar');
    if (progressBar) {
      const percent = Math.max(0, (remainingSeconds / rotationDuration) * 100);
      progressBar.style.width = `${percent}%`;
    }

    if (remainingSeconds <= 0) {
      currentMenuIndex = (currentMenuIndex + 1) % ROTATION_MENUS.length;
      switchMenu(ROTATION_MENUS[currentMenuIndex], true);
      remainingSeconds = rotationDuration;
      if (progressBar) progressBar.style.width = '100%';
    }
  }, 1000);
}

function switchMenu(menuKey, fromTimer = false) {
  if (menuKey === 'all') {
    isAllMode = true;
    document.querySelectorAll('.menu-section').forEach(sec => {
      sec.classList.remove('menu-hidden');
      sec.classList.add('menu-active');
    });

    document.querySelectorAll('.menu-tab-btn').forEach(btn => {
      btn.classList.remove('bg-emerald-600', 'text-white', 'shadow-sm');
      btn.classList.add('text-emerald-200');
    });
    
    document.querySelectorAll('.tab-btn-all').forEach(btn => {
      btn.classList.add('bg-emerald-600', 'text-white', 'shadow-sm');
      btn.classList.remove('text-emerald-200');
    });

    document.querySelectorAll('.rotationCountdownEl').forEach(el => {
      el.textContent = 'Semua Menu';
    });

    const activeMenuTitleEl = document.getElementById('activeMenuMobileTitle');
    if (activeMenuTitleEl) activeMenuTitleEl.textContent = 'Semua Menu Ditampilkan';

    const progressBar = document.getElementById('rotationProgressBar');
    if (progressBar) progressBar.style.width = '0%';
    return;
  }

  isAllMode = false;
  const targetIndex = ROTATION_MENUS.indexOf(menuKey);
  if (targetIndex !== -1) {
    currentMenuIndex = targetIndex;
  }

  remainingSeconds = rotationDuration;
  document.querySelectorAll('.rotationCountdownEl').forEach(el => {
    el.textContent = `${remainingSeconds}s`;
  });

  const progressBar = document.getElementById('rotationProgressBar');
  if (progressBar) progressBar.style.width = '100%';

  const activeMenuTitleEl = document.getElementById('activeMenuMobileTitle');
  if (activeMenuTitleEl) {
    activeMenuTitleEl.textContent = MENU_NAMES[menuKey] || menuKey;
  }

  ROTATION_MENUS.forEach(key => {
    const el = document.getElementById(`menu-view-${key}`);
    const tabBtns = document.querySelectorAll(`.tab-btn-${key}`);
    
    if (el) {
      if (key === menuKey) {
        el.classList.remove('menu-hidden');
        el.classList.add('menu-active');
      } else {
        el.classList.remove('menu-active');
        el.classList.add('menu-hidden');
      }
    }

    tabBtns.forEach(tabBtn => {
      if (key === menuKey) {
        tabBtn.classList.add('bg-emerald-600', 'text-white', 'shadow-sm');
        tabBtn.classList.remove('text-emerald-200');
      } else {
        tabBtn.classList.remove('bg-emerald-600', 'text-white', 'shadow-sm');
        tabBtn.classList.add('text-emerald-200');
      }
    });
  });

  document.querySelectorAll('.tab-btn-all').forEach(btn => {
    btn.classList.remove('bg-emerald-600', 'text-white', 'shadow-sm');
    btn.classList.add('text-emerald-200');
  });

  if (menuKey === 'ringkasan') {
    setTimeout(() => {
      if (cashflowChartInstance) cashflowChartInstance.resize();
      if (allocationChartInstance) allocationChartInstance.resize();
    }, 150);
  }

  if (menuKey === 'mutasi' || menuKey === 'all') {
    setTimeout(() => {
      startTableAutoScroll();
    }, 200);
  } else {
    stopTableAutoScroll();
  }

  if (fromTimer) {
    showRotationBanner(MENU_NAMES[menuKey] || menuKey);
  }
}

function nextMenu() {
  currentMenuIndex = (currentMenuIndex + 1) % ROTATION_MENUS.length;
  switchMenu(ROTATION_MENUS[currentMenuIndex]);
}

function prevMenu() {
  currentMenuIndex = (currentMenuIndex - 1 + ROTATION_MENUS.length) % ROTATION_MENUS.length;
  switchMenu(ROTATION_MENUS[currentMenuIndex]);
}

function toggleAutoRotation() {
  isRotationPaused = !isRotationPaused;
  updateRotationPlayPauseUI();

  if (isRotationPaused) {
    showToast('Rotasi 1 menit dijeda');
  } else {
    showToast('Rotasi 1 menit dilanjutkan');
  }
}

function updateRotationPlayPauseUI() {
  const icons = document.querySelectorAll('.pausePlayIcon');
  const dots = document.querySelectorAll('.rotationDot');

  icons.forEach(icon => {
    if (isRotationPaused) {
      icon.setAttribute('data-lucide', 'play');
    } else {
      icon.setAttribute('data-lucide', 'pause');
    }
  });

  dots.forEach(dot => {
    if (isRotationPaused) {
      dot.classList.remove('radar-dot', 'bg-amber-400');
      dot.classList.add('bg-slate-400');
    } else {
      dot.classList.add('radar-dot', 'bg-amber-400');
      dot.classList.remove('bg-slate-400');
    }
  });

  lucide.createIcons();
}

function pauseAutoRotationTemporarily() {
  wasPausedBeforeModal = isRotationPaused;
  isRotationPaused = true;
  updateRotationPlayPauseUI();
}

function resumeAutoRotation() {
  if (!wasPausedBeforeModal) {
    isRotationPaused = false;
    updateRotationPlayPauseUI();
  }
}

function changeRotationInterval(val) {
  rotationDuration = Number(val);
  remainingSeconds = rotationDuration;
  document.querySelectorAll('.rotationCountdownEl').forEach(el => {
    el.textContent = `${remainingSeconds}s`;
  });
  const progressBar = document.getElementById('rotationProgressBar');
  if (progressBar) progressBar.style.width = '100%';
  showToast(`Durasi rotasi diubah menjadi ${val} detik`);
}

function toggleFullscreen() {
  if (!document.fullscreenElement) {
    document.documentElement.requestFullscreen().catch(err => {
      console.log(`Fullscreen notice: ${err.message}`);
    });
  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen();
    }
  }
}

function showRotationBanner(menuTitle) {
  let banner = document.getElementById('rotationBanner');
  if (!banner) {
    banner = document.createElement('div');
    banner.id = 'rotationBanner';
    banner.className = 'fixed top-24 right-4 sm:right-6 z-40 bg-emerald-950/95 text-white border border-emerald-500/60 backdrop-blur-md px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-2xl shadow-2xl flex items-center space-x-2.5 text-xs font-bold transition-all duration-300 transform translate-y-[-20px] opacity-0 pointer-events-none max-w-[90vw]';
    document.body.appendChild(banner);
  }

  banner.innerHTML = `
    <div class="w-6 h-6 rounded-full bg-emerald-600/60 flex items-center justify-center text-amber-300 shrink-0">
      <i data-lucide="refresh-cw" class="w-3.5 h-3.5 animate-spin"></i>
    </div>
    <div class="truncate">
      <span class="text-[9px] text-emerald-300 block uppercase font-mono">Beralih Layar TV:</span>
      <span class="text-white font-extrabold text-xs sm:text-sm truncate">${menuTitle}</span>
    </div>
  `;
  lucide.createIcons();

  banner.classList.remove('translate-y-[-20px]', 'opacity-0');
  setTimeout(() => {
    banner.classList.add('translate-y-[-20px]', 'opacity-0');
  }, 2400);
}

// ==================== AUTO-SCROLL ENGINE FOR MUTASI KAS ====================
let tableAutoScrollInterval = null;
let isTableAutoScrollEnabled = true;
let isUserScrollingTable = false;
let userScrollTimeout = null;
let isWaitingAtBoundary = false;

function initTableAutoScroll() {
  const container = document.getElementById('transactionsScrollContainer');
  if (!container) return;

  container.addEventListener('mouseenter', () => {
    isUserScrollingTable = true;
  });

  container.addEventListener('mouseleave', () => {
    isUserScrollingTable = false;
  });

  container.addEventListener('wheel', () => {
    isUserScrollingTable = true;
    clearTimeout(userScrollTimeout);
    userScrollTimeout = setTimeout(() => {
      isUserScrollingTable = false;
    }, 2800);
  }, { passive: true });

  container.addEventListener('touchstart', () => {
    isUserScrollingTable = true;
  }, { passive: true });

  container.addEventListener('touchend', () => {
    clearTimeout(userScrollTimeout);
    userScrollTimeout = setTimeout(() => {
      isUserScrollingTable = false;
    }, 2800);
  }, { passive: true });
}

function checkAndTriggerTableAutoScroll() {
  const mutasiView = document.getElementById('menu-view-mutasi');
  const isMutasiActive = mutasiView && !mutasiView.classList.contains('menu-hidden');
  if (isMutasiActive || isAllMode) {
    startTableAutoScroll();
  }
}

function startTableAutoScroll() {
  stopTableAutoScroll();
  const container = document.getElementById('transactionsScrollContainer');
  if (!container) return;

  const maxScroll = container.scrollHeight - container.clientHeight;
  if (maxScroll <= 15) {
    updateTableScrollBadgeUI(false, 'Tampil Penuh');
    return;
  }

  updateTableScrollBadgeUI(isTableAutoScrollEnabled, isTableAutoScrollEnabled ? 'Auto-Scroll: Aktif' : 'Auto-Scroll: Dijeda');

  container.scrollTop = 0;
  isWaitingAtBoundary = true;

  setTimeout(() => {
    isWaitingAtBoundary = false;
  }, 2200);

  tableAutoScrollInterval = setInterval(() => {
    if (!isTableAutoScrollEnabled || isUserScrollingTable || isWaitingAtBoundary) return;

    const mutasiView = document.getElementById('menu-view-mutasi');
    if (mutasiView && mutasiView.classList.contains('menu-hidden') && !isAllMode) return;

    const currentMaxScroll = container.scrollHeight - container.clientHeight;
    if (currentMaxScroll <= 15) return;

    container.scrollTop += 1;

    if (container.scrollTop >= currentMaxScroll - 2) {
      isWaitingAtBoundary = true;
      setTimeout(() => {
        if (!isTableAutoScrollEnabled) return;
        container.scrollTo({ top: 0, behavior: 'smooth' });
        setTimeout(() => {
          isWaitingAtBoundary = false;
        }, 2500);
      }, 3500);
    }
  }, 40);
}

function stopTableAutoScroll() {
  if (tableAutoScrollInterval) {
    clearInterval(tableAutoScrollInterval);
    tableAutoScrollInterval = null;
  }
}

function toggleTableAutoScroll() {
  isTableAutoScrollEnabled = !isTableAutoScrollEnabled;
  updateTableScrollBadgeUI(isTableAutoScrollEnabled, isTableAutoScrollEnabled ? 'Auto-Scroll: Aktif' : 'Auto-Scroll: Dijeda');
  
  if (isTableAutoScrollEnabled) {
    startTableAutoScroll();
    showToast('Auto-scroll mutasi diaktifkan');
  } else {
    stopTableAutoScroll();
    showToast('Auto-scroll mutasi dijeda');
  }
}

function updateTableScrollBadgeUI(active, text) {
  const badge = document.getElementById('tableAutoScrollBadge');
  const statusEl = document.getElementById('tableAutoScrollStatus');
  const icon = document.getElementById('tableScrollIcon');

  if (statusEl) statusEl.textContent = text;
  if (badge) {
    if (active) {
      badge.className = 'inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700 cursor-pointer hover:bg-emerald-200 dark:hover:bg-emerald-900 transition';
      if (icon) icon.className = 'w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400 animate-bounce';
    } else {
      badge.className = 'inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 border border-slate-300 dark:border-slate-600 cursor-pointer hover:bg-slate-200 transition';
      if (icon) icon.className = 'w-3.5 h-3.5 text-slate-400';
    }
  }
  lucide.createIcons();
}
