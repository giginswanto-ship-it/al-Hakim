<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Allocation;
use App\Models\BankSetting;

class AllocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allocations = [
            [
                'code' => 'alloc-1',
                'name' => 'Operasional & Utilitas',
                'target_percentage' => 45,
                'focus_description' => 'Listrik, air wudhu, kebersihan ruang shalat, sound system, & marbot',
                'color_hex' => '#059669',
                'icon_name' => 'zap',
            ],
            [
                'code' => 'alloc-2',
                'name' => 'Dakwah & Kajian',
                'target_percentage' => 25,
                'focus_description' => 'Honor penceramah, takjil buka puasa, kajian rutin subuh & remaja',
                'color_hex' => '#2563eb',
                'icon_name' => 'book-open',
            ],
            [
                'code' => 'alloc-3',
                'name' => 'Sosial & Yatim Dhuafa',
                'target_percentage' => 20,
                'focus_description' => 'Bantuan santunan sembako dhuafa sekitar masjid dan beasiswa yatim',
                'color_hex' => '#d97706',
                'icon_name' => 'heart-handshake',
            ],
            [
                'code' => 'alloc-4',
                'name' => 'Dana Cadangan Bangunan',
                'target_percentage' => 10,
                'focus_description' => 'Renovasi berkala fasilitas, kubah, karpet, dan pemeliharaan genset',
                'color_hex' => '#7c3aed',
                'icon_name' => 'hammer',
            ],
        ];

        foreach ($allocations as $a) {
            Allocation::updateOrCreate(['code' => $a['code']], $a);
        }

        BankSetting::updateOrCreate(
            ['id' => 1],
            [
                'bank_name' => 'Bank Syariah Indonesia (BSI)',
                'account_number' => '7182-9012-34',
                'account_name' => 'Masjid Al-Hakim Kas Utama',
                'bank_code' => '451',
                'qris_nmid' => 'ID1020261902481',
                'whatsapp_number' => '6281290123456',
                'address' => 'Kompleks Perumahan Al-Hakim Asri, Jakarta',
                'active_period' => 'Oktober 2026',
                'starting_balance' => 47000000.00,
            ]
        );
    }
}
