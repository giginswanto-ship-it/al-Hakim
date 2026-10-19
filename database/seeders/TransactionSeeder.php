<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = [
            [
                'code' => 'TX-202610-008',
                'transaction_date' => '2026-10-18',
                'date_formatted' => '18 Okt 2026',
                'description' => 'Infaq Kotak Amal Shalat Jumat',
                'type' => 'Penerimaan',
                'allocation_category' => 'Operasional & Utilitas',
                'amount' => 8420000.00,
                'receipt_number' => 'KWT/2026/10/018',
                'pic_name' => 'Ustadz Zulkifli (Ketua DKM)',
                'payment_method' => 'Tunai / Kotak Amal',
                'audit_notes' => 'Penghitungan bersama saksi 3 pengurus masjid setelah pelaksanaan shalat Jumat secara terbuka di ruang sekretariat.',
                'is_verified' => true,
            ],
            [
                'code' => 'TX-202610-007',
                'transaction_date' => '2026-10-17',
                'date_formatted' => '17 Okt 2026',
                'description' => 'Donasi QRIS Online — Hamba Allah',
                'type' => 'Penerimaan',
                'allocation_category' => 'Dakwah & Kajian',
                'amount' => 500000.00,
                'receipt_number' => 'QRIS/2026/10/017',
                'pic_name' => 'Sistem Otomatis QRIS',
                'payment_method' => 'QRIS Nasional',
                'audit_notes' => 'Infaq digital jamaah kajian subuh melalui scan barcode QRIS resmi Masjid Al-Hakim.',
                'is_verified' => true,
            ],
            [
                'code' => 'TX-202610-006',
                'transaction_date' => '2026-10-15',
                'date_formatted' => '15 Okt 2026',
                'description' => 'Pembayaran Listrik PLN & Air PDAM',
                'type' => 'Pengeluaran',
                'allocation_category' => 'Operasional & Utilitas',
                'amount' => 2350000.00,
                'receipt_number' => 'NOTA/PLN-PDAM/202610',
                'pic_name' => 'H. Ahmad S. (Bendahara)',
                'payment_method' => 'Auto-Debit Bank BSI',
                'audit_notes' => 'Pelunasan tagihan listrik ruang utama & AC masjid (IDPEL: 53210984 - Rp 1.850.000) dan tagihan air wudhu (PDAM: 049281 - Rp 500.000).',
                'is_verified' => true,
            ],
            [
                'code' => 'TX-202610-005',
                'transaction_date' => '2026-10-14',
                'date_formatted' => '14 Okt 2026',
                'description' => 'Bisyarah Ustadz Kajian Ahad Subuh',
                'type' => 'Pengeluaran',
                'allocation_category' => 'Dakwah & Kajian',
                'amount' => 1000000.00,
                'receipt_number' => 'KWT/KJ-044/2026',
                'pic_name' => 'Divisi Dakwah & Ta\'mir',
                'payment_method' => 'Tunai',
                'audit_notes' => 'Bisyarah kajian tematik kitab Riyadhus Shalihin bersama Ustadz Dr. Fauzan, M.A.',
                'is_verified' => true,
            ],
            [
                'code' => 'TX-202610-004',
                'transaction_date' => '2026-10-12',
                'date_formatted' => '12 Okt 2026',
                'description' => 'Transfer Bank Infaq Operasional',
                'type' => 'Penerimaan',
                'allocation_category' => 'Operasional & Utilitas',
                'amount' => 2500000.00,
                'receipt_number' => 'BSI/TRF-88912/2026',
                'pic_name' => 'H. Ahmad S. (Bendahara)',
                'payment_method' => 'Transfer BSI',
                'audit_notes' => 'Infaq operasional bulanan keluarga jamaah H. Mansur melalui transfer rekening BSI Utama.',
                'is_verified' => true,
            ],
            [
                'code' => 'TX-202610-003',
                'transaction_date' => '2026-10-10',
                'date_formatted' => '10 Okt 2026',
                'description' => 'Perawatan & Cuci AC Ruang Utama (4 Unit)',
                'type' => 'Pengeluaran',
                'allocation_category' => 'Operasional & Utilitas',
                'amount' => 800000.00,
                'receipt_number' => 'NOTA/SVC-AC/102',
                'pic_name' => 'Biro Sarana & Prasarana',
                'payment_method' => 'Tunai',
                'audit_notes' => 'Servis berkala, pencucian evaporator, filter serta penambahan freon 4 unit AC Daikin 2 PK ruang shalat utama.',
                'is_verified' => true,
            ],
            [
                'code' => 'TX-202610-002',
                'transaction_date' => '2026-10-08',
                'date_formatted' => '08 Okt 2026',
                'description' => 'Donasi Santunan Anak Yatim Bulanan',
                'type' => 'Penerimaan',
                'allocation_category' => 'Sosial & Yatim Dhuafa',
                'amount' => 5000000.00,
                'receipt_number' => 'KWT/YTM-081/2026',
                'pic_name' => 'Divisi Sosial & Yatim',
                'payment_method' => 'Transfer Bank',
                'audit_notes' => 'Donasi khusus santunan anak yatim binaan masjid untuk periode Oktober 2026.',
                'is_verified' => true,
            ],
            [
                'code' => 'TX-202610-001',
                'transaction_date' => '2026-10-05',
                'date_formatted' => '05 Okt 2026',
                'description' => 'Penyaluran Santunan Yatim & Dhuafa',
                'type' => 'Pengeluaran',
                'allocation_category' => 'Sosial & Yatim Dhuafa',
                'amount' => 5000000.00,
                'receipt_number' => 'DST/YTM-BLN10/2026',
                'pic_name' => 'Divisi Sosial & Yatim',
                'payment_method' => 'Tunai & Paket Sembako',
                'audit_notes' => 'Penyaluran santunan tunai @Rp 200.000 + paket sembako untuk 25 anak yatim & dhuafa di lingkungan sekitar masjid.',
                'is_verified' => true,
            ],
        ];

        foreach ($transactions as $t) {
            Transaction::updateOrCreate(['code' => $t['code']], $t);
        }
    }
}
