<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Allocation;
use App\Models\BankSetting;

class DashboardController extends Controller
{
    /**
     * Display the mosque financial transparency portal.
     */
    public function index(Request $request)
    {
        $bank = BankSetting::first() ?? new BankSetting([
            'bank_name' => 'Bank Syariah Indonesia (BSI)',
            'account_number' => '7182-9012-34',
            'account_name' => 'Masjid Al-Hakim Kas Utama',
            'bank_code' => '451',
            'qris_nmid' => 'ID1020261902481',
            'whatsapp_number' => '6281290123456',
            'address' => 'Kompleks Perumahan Al-Hakim Asri, Jakarta',
            'active_period' => 'Oktober 2026',
            'starting_balance' => 47000000.00,
        ]);

        $allocations = Allocation::all();
        if ($allocations->isEmpty()) {
            $allocations = collect([
                (object)['id' => 1, 'code' => 'alloc-1', 'name' => 'Operasional & Utilitas', 'target_percentage' => 45, 'focus_description' => 'Listrik, air wudhu, kebersihan ruang shalat, sound system, & marbot', 'color_hex' => '#059669', 'icon_name' => 'zap'],
                (object)['id' => 2, 'code' => 'alloc-2', 'name' => 'Dakwah & Kajian', 'target_percentage' => 25, 'focus_description' => 'Honor penceramah, takjil buka puasa, kajian rutin subuh & remaja', 'color_hex' => '#2563eb', 'icon_name' => 'book-open'],
                (object)['id' => 3, 'code' => 'alloc-3', 'name' => 'Sosial & Yatim Dhuafa', 'target_percentage' => 20, 'focus_description' => 'Bantuan santunan sembako dhuafa sekitar masjid dan beasiswa yatim', 'color_hex' => '#d97706', 'icon_name' => 'heart-handshake'],
                (object)['id' => 4, 'code' => 'alloc-4', 'name' => 'Dana Cadangan Bangunan', 'target_percentage' => 10, 'focus_description' => 'Renovasi berkala fasilitas, kubah, karpet, dan pemeliharaan genset', 'color_hex' => '#7c3aed', 'icon_name' => 'hammer'],
            ]);
        }

        // Transactions list with optional filtering
        $query = Transaction::query();
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('type') && $request->type !== 'all') {
            $query->ofType($request->type);
        }
        if ($request->filled('category') && $request->category !== 'all') {
            $query->ofCategory($request->category);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->get();

        // Financial calculations
        $totalPemasukan = Transaction::where('type', 'Penerimaan')->sum('amount');
        $totalPengeluaran = Transaction::where('type', 'Pengeluaran')->sum('amount');
        
        // If DB is empty, use PDF baseline values
        if ($totalPemasukan == 0 && $totalPengeluaran == 0) {
            $totalPemasukan = 34200000.00;
            $totalPengeluaran = 12750000.00;
        }

        $startingBalance = $bank->starting_balance ?? 47000000.00;
        $saldoKasBersih = $startingBalance + $totalPemasukan - $totalPengeluaran;
        $surplusKas = $totalPemasukan - $totalPengeluaran;

        return view('dashboard', compact(
            'bank',
            'allocations',
            'transactions',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoKasBersih',
            'surplusKas'
        ));
    }
}
