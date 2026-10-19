<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Get transactions JSON list for real-time charts and table sync
     */
    public function index(Request $request)
    {
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

        $sort = $request->get('sort', 'date-desc');
        switch ($sort) {
            case 'date-asc':
                $query->orderBy('transaction_date', 'asc');
                break;
            case 'amount-desc':
                $query->orderBy('amount', 'desc');
                break;
            case 'amount-asc':
                $query->orderBy('amount', 'asc');
                break;
            case 'date-desc':
            default:
                $query->orderBy('transaction_date', 'desc');
                break;
        }

        $transactions = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $transactions,
        ]);
    }

    /**
     * Store new mutation into SQL database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'description' => 'required|string|max:255',
            'type' => 'required|in:Penerimaan,Pengeluaran',
            'allocation_category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1000',
            'payment_method' => 'nullable|string|max:64',
            'pic_name' => 'nullable|string|max:100',
            'audit_notes' => 'nullable|string',
        ]);

        $date = Carbon::parse($validated['transaction_date']);
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $dateFormatted = sprintf('%02d %s %d', $date->day, $months[$date->month - 1], $date->year);

        $code = 'TX-' . time();
        $receiptNumber = sprintf('KWT/%s/%02d/%03d', $date->year, $date->month, rand(100, 999));

        $transaction = Transaction::create([
            'code' => $code,
            'transaction_date' => $validated['transaction_date'],
            'date_formatted' => $dateFormatted,
            'description' => $validated['description'],
            'type' => $validated['type'],
            'allocation_category' => $validated['allocation_category'],
            'amount' => $validated['amount'],
            'receipt_number' => $receiptNumber,
            'pic_name' => $validated['pic_name'] ?? 'H. Ahmad S. (Bendahara DKM)',
            'payment_method' => $validated['payment_method'] ?? 'Kas Tunai',
            'audit_notes' => $validated['audit_notes'] ?? 'Pencatatan kas via portal transparansi DKM.',
            'is_verified' => true,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Mutasi kas berhasil dicatat ke dalam database SQL.',
                'data' => $transaction,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Mutasi kas berhasil ditambahkan!');
    }

    /**
     * Export all transactions to CSV
     */
    public function exportCsv()
    {
        $transactions = Transaction::orderBy('transaction_date', 'desc')->get();
        $filename = "Laporan_Kas_Masjid_Al_Hakim_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Kode Transaksi', 'Tanggal', 'Keterangan', 'Jenis', 'Pos Anggaran', 'Nominal (Rp)', 'No Bukti', 'PIC', 'Metode', 'Catatan Audit'];

        $callback = function () use ($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM
            fputcsv($file, $columns);

            foreach ($transactions as $t) {
                fputcsv($file, [
                    $t->code,
                    $t->date_formatted,
                    $t->description,
                    $t->type,
                    $t->allocation_category,
                    $t->amount,
                    $t->receipt_number,
                    $t->pic_name,
                    $t->payment_method,
                    $t->audit_notes,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
