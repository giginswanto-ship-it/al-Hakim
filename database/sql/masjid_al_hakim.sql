-- ==========================================================
-- SISTEM TRANSPARANSI KAS MASJID AL-HAKIM (DATABASE SQL)
-- Periode: Oktober 2026
-- Engine: MySQL / MariaDB / SQLite Compatible
-- ==========================================================

-- 1. Tabel Pengaturan Kas & Kanal Donasi
CREATE TABLE IF NOT EXISTS `bank_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `bank_name` VARCHAR(100) NOT NULL,
  `account_number` VARCHAR(64) NOT NULL,
  `account_name` VARCHAR(150) NOT NULL,
  `bank_code` VARCHAR(10) DEFAULT '451',
  `qris_nmid` VARCHAR(64) NOT NULL,
  `whatsapp_number` VARCHAR(32) NOT NULL,
  `address` TEXT,
  `active_period` VARCHAR(50) DEFAULT 'Oktober 2026',
  `starting_balance` DECIMAL(15,2) DEFAULT 47000000.00,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabel Pos Alokasi Penggunaan Dana Infaq
CREATE TABLE IF NOT EXISTS `allocations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(32) UNIQUE NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `target_percentage` INT NOT NULL,
  `focus_description` TEXT NOT NULL,
  `color_hex` VARCHAR(16) NOT NULL,
  `icon_name` VARCHAR(32) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabel Mutasi Kas (Penerimaan & Pengeluaran)
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(32) UNIQUE NOT NULL,
  `transaction_date` DATE NOT NULL,
  `date_formatted` VARCHAR(32) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `type` ENUM('Penerimaan', 'Pengeluaran') NOT NULL,
  `allocation_category` VARCHAR(100) NOT NULL,
  `amount` DECIMAL(15,2) NOT NULL,
  `receipt_number` VARCHAR(64) NOT NULL,
  `pic_name` VARCHAR(100) NOT NULL,
  `payment_method` VARCHAR(64) NOT NULL,
  `audit_notes` TEXT,
  `is_verified` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================================
-- DATA AWAL (SEED DATA RESMI LAPORAN BULAN OKTOBER 2026)
-- ==========================================================

-- Data Pengaturan Rekening & QRIS
INSERT INTO `bank_settings` (`id`, `bank_name`, `account_number`, `account_name`, `bank_code`, `qris_nmid`, `whatsapp_number`, `address`, `active_period`, `starting_balance`) VALUES
(1, 'Bank Syariah Indonesia (BSI)', '7182-9012-34', 'Masjid Al-Hakim Kas Utama', '451', 'ID1020261902481', '6281290123456', 'Kompleks Perumahan Al-Hakim Asri, Jakarta', 'Oktober 2026', 47000000.00)
ON DUPLICATE KEY UPDATE `account_number` = VALUES(`account_number`);

-- Data Alokasi 4 Pilar Syariah
INSERT INTO `allocations` (`id`, `code`, `name`, `target_percentage`, `focus_description`, `color_hex`, `icon_name`) VALUES
(1, 'alloc-1', 'Operasional & Utilitas', 45, 'Listrik, air wudhu, kebersihan ruang shalat, sound system, & marbot', '#059669', 'zap'),
(2, 'alloc-2', 'Dakwah & Kajian', 25, 'Honor penceramah, takjil buka puasa, kajian rutin subuh & remaja', '#2563eb', 'book-open'),
(3, 'alloc-3', 'Sosial & Yatim Dhuafa', 20, 'Bantuan santunan sembako dhuafa sekitar masjid dan beasiswa yatim', '#d97706', 'heart-handshake'),
(4, 'alloc-4', 'Dana Cadangan Bangunan', 10, 'Renovasi berkala fasilitas, kubah, karpet, dan pemeliharaan genset', '#7c3aed', 'hammer')
ON DUPLICATE KEY UPDATE `target_percentage` = VALUES(`target_percentage`);

-- Data 8 Mutasi Kas Resmi Oktober 2026
INSERT INTO `transactions` (`code`, `transaction_date`, `date_formatted`, `description`, `type`, `allocation_category`, `amount`, `receipt_number`, `pic_name`, `payment_method`, `audit_notes`, `is_verified`) VALUES
('TX-202610-008', '2026-10-18', '18 Okt 2026', 'Infaq Kotak Amal Shalat Jumat', 'Penerimaan', 'Operasional & Utilitas', 8420000.00, 'KWT/2026/10/018', 'Ustadz Zulkifli (Ketua DKM)', 'Tunai / Kotak Amal', 'Penghitungan bersama saksi 3 pengurus masjid setelah pelaksanaan shalat Jumat secara terbuka di ruang sekretariat.', 1),
('TX-202610-007', '2026-10-17', '17 Okt 2026', 'Donasi QRIS Online — Hamba Allah', 'Penerimaan', 'Dakwah & Kajian', 500000.00, 'QRIS/2026/10/017', 'Sistem Otomatis QRIS', 'QRIS Nasional', 'Infaq digital jamaah kajian subuh melalui scan barcode QRIS resmi Masjid Al-Hakim.', 1),
('TX-202610-006', '2026-10-15', '15 Okt 2026', 'Pembayaran Listrik PLN & Air PDAM', 'Pengeluaran', 'Operasional & Utilitas', 2350000.00, 'NOTA/PLN-PDAM/202610', 'H. Ahmad S. (Bendahara)', 'Auto-Debit Bank BSI', 'Pelunasan tagihan listrik ruang utama & AC masjid (IDPEL: 53210984 - Rp 1.850.000) dan tagihan air wudhu (PDAM: 049281 - Rp 500.000).', 1),
('TX-202610-005', '2026-10-14', '14 Okt 2026', 'Bisyarah Ustadz Kajian Ahad Subuh', 'Pengeluaran', 'Dakwah & Kajian', 1000000.00, 'KWT/KJ-044/2026', 'Divisi Dakwah & Ta\'mir', 'Tunai', 'Bisyarah kajian tematik kitab Riyadhus Shalihin bersama Ustadz Dr. Fauzan, M.A.', 1),
('TX-202610-004', '2026-10-12', '12 Okt 2026', 'Transfer Bank Infaq Operasional', 'Penerimaan', 'Operasional & Utilitas', 2500000.00, 'BSI/TRF-88912/2026', 'H. Ahmad S. (Bendahara)', 'Transfer BSI', 'Infaq operasional bulanan keluarga jamaah H. Mansur melalui transfer rekening BSI Utama.', 1),
('TX-202610-003', '2026-10-10', '10 Okt 2026', 'Perawatan & Cuci AC Ruang Utama (4 Unit)', 'Pengeluaran', 'Operasional & Utilitas', 800000.00, 'NOTA/SVC-AC/102', 'Biro Sarana & Prasarana', 'Tunai', 'Servis berkala, pencucian evaporator, filter serta penambahan freon 4 unit AC Daikin 2 PK ruang shalat utama.', 1),
('TX-202610-002', '2026-10-08', '08 Okt 2026', 'Donasi Santunan Anak Yatim Bulanan', 'Penerimaan', 'Sosial & Yatim Dhuafa', 5000000.00, 'KWT/YTM-081/2026', 'Divisi Sosial & Yatim', 'Transfer Bank', 'Donasi khusus santunan anak yatim binaan masjid untuk periode Oktober 2026.', 1),
('TX-202610-001', '2026-10-05', '05 Okt 2026', 'Penyaluran Santunan Yatim & Dhuafa', 'Pengeluaran', 'Sosial & Yatim Dhuafa', 5000000.00, 'DST/YTM-BLN10/2026', 'Divisi Sosial & Yatim', 'Tunai & Paket Sembako', 'Penyaluran santunan tunai @Rp 200.000 + paket sembako untuk 25 anak yatim & dhuafa di lingkungan sekitar masjid.', 1)
ON DUPLICATE KEY UPDATE `description` = VALUES(`description`);
