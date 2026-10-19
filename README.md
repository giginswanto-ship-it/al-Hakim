# 🕌 Masjid Al-Hakim — Sistem Transparansi Kas & Pelaporan Publik Jamaah

Portal web dinamis, interaktif, dan akuntabel berbasis **Framework Laravel** dan **Database SQL (MySQL/SQLite)** yang dirancang khusus untuk mempublikasikan laporan keuangan, mutasi kas harian, alokasi penggunaan dana infaq, serta layanan kanal penerimaan donasi digital (Transfer Bank & QRIS) DKM Masjid Al-Hakim.

> **Catatan Desain & Tampilan:**
> Seluruh gaya visual (*style*), tema modern Islami (*emerald & amber*), responsivitas, mode gelap/terang, auto-rotasi menu 1 menit, serta fitur auto-scroll mutasi kas tetap **100% identik dan terjaga**, baik pada versi **Laravel Framework** maupun versi **Standalone**.

---

## 🌟 Fitur Utama Sistem

### 1. 🔄 Rotasi Tampilan Otomatis Setiap 1 Menit (Mode Layar TV & Kiosk Masjid)
- **Otomatis Berpindah Setiap 60 Detik (1 Menit):** Tampilan layar berpindah bergantian secara berurutan:
  1. *Menu 1:* **Ringkasan & Grafik Kas**
  2. *Menu 2:* **Mutasi Kas Terkini (Penerimaan & Pengeluaran)**
  3. *Menu 3:* **Kanal Donasi & Standar QRIS Nasional**
  4. *Menu 4:* **Alokasi Penggunaan Dana Infaq 4 Pilar & Audit**
- **Indikator Progres & Countdown:** Bilah progres waktu dan hitung mundur `60s... 0s` yang bergerak halus.
- **Kontrol Fleksibel:** Tombol jeda/lanjutkan rotasi (⏸️/▶️), tombol menu sebelumnya/selanjutnya (◀️/▶️), serta pemilih durasi rotasi (30 detik, 1 menit, 2 menit).
- **Mode Layar Penuh (TV Masjid):** Tombol satu klik untuk mode *Fullscreen*, ideal untuk dipasang pada monitor/TV informasi masjid.
- **Mode Tampilkan Semua:** Pilihan untuk menampilkan semua bagian secara bersamaan jika ingin membaca halaman secara menyeluruh tanpa rotasi.
- **Auto-Pause Cerdas:** Rotasi otomatis terjeda saat jamaah membuka bukti nota/kuitansi atau mengisi formulir kas bendahara, dan berlanjut kembali saat modal ditutup.

### 2. 📋 Mutasi Kas Terkini & Auto-Scroll Dinamis
- **🔄 Auto-Scroll Dinamis dari Awal ke Akhir:** Jika daftar mutasi kas melebihi satu halaman / area tampilan kontainer, tabel akan otomatis bergulir turun perlahan (*smooth continuous scroll*) dari baris pertama sampai baris terakhir, berhenti sejenak di akhir agar jamaah dapat membaca transaksi terbawah, lalu kembali bergulir dari awal.
- **Sticky Table Header:** Judul kolom (Tanggal, Keterangan, Kategori, Nominal, Kuitansi) tetap menempel di bagian atas saat baris mutasi bergulir ke bawah.
- **Tombol Kontrol Auto-Scroll:** Badge interaktif `[Auto-Scroll: Aktif / Dijeda]` yang dapat diklik langsung untuk menghentikan atau melanjutkan gulir otomatis.
- **Sensor Interaksi Cerdas:** Jika pengguna menyentuh layar, mengarahkan kursor (*hover*), atau menggunakan roda *mouse*, gulir otomatis terjeda secara instan dan berlanjut kembali saat interaksi selesai.
- **Pencarian Cepat & Filter:** Cari berdasarkan keterangan, pos anggaran, atau nomor nota, serta filter Penerimaan / Pengeluaran.
- **Verifikasi Kuitansi & Nota Fisik (Modal Interaktif):** Klik pada baris transaksi mana saja untuk membuka bukti nota digital lengkap dengan nomor kuitansi, tanggal, penanggung jawab (PIC), catatan fisik, cap resmi *"TERVERIFIKASI"*, serta tanda tangan Bendahara DKM.

### 3. 📊 Ringkasan Finansial Real-Time (Data SQL)
- **Saldo Kas Bersih:** Rp 68.450.000 (Otomatis terhitung dari kalkulasi agregat SQL)
- **Pemasukan Bulan Ini:** Rp 34.200.000
- **Pengeluaran Bulan Ini:** Rp 12.750.000
- **Surplus Kas Operasional:** Rp 21.450.000
- Jam digital real-time & penanggalan masehi/hijriah + jadwal waktu shalat.

### 4. 📈 Visualisasi Grafik Interaktif (Chart.js)
- **Arus Kas Bulanan (Bar Chart):** Visualisasi perbandingan pemasukan vs pengeluaran serta surplus kas bersih.
- **Distribusi Target Infaq (Doughnut Chart):** Komposisi target persentase syariah untuk 4 pilar alokasi.

### 5. 💳 Kanal Donasi Digital (BSI & QRIS)
- **Rekening Bank Resmi:** Bank Syariah Indonesia (BSI), No. Rek: `7182-9012-34` a.n. **Masjid Al-Hakim Kas Utama** (Fitur 1-klik Salin Rekening).
- **Standar QRIS Nasional:** NMID: `ID1020261902481` (Dukungan BCA, Mandiri, BSI, GoPay, OVO, ShopeePay, DANA, LinkAja).
- **Kalkulator & Generator Konfirmasi WhatsApp:** Menghitung donasi dan menghasilkan pesan WhatsApp otomatis ke nomor DKM.

### 6. 🎯 Alokasi Penggunaan Dana Infaq (4 Pilar Syariah)
1. **Operasional & Utilitas (45%):** Listrik PLN, air PDAM wudhu, kebersihan ruang shalat, sound system, & marbot.
2. **Dakwah & Kajian (25%):** Honor penceramah, takjil buka puasa, kajian rutin subuh & remaja.
3. **Sosial & Yatim Dhuafa (20%):** Bantuan santunan sembako dhuafa sekitar masjid dan beasiswa yatim.
4. **Dana Cadangan Bangunan (10%):** Renovasi berkala fasilitas, kubah, karpet, dan pemeliharaan genset.

---

## 🗄️ Struktur Database SQL

Skrip SQL lengkap tersedia di: [`database/sql/masjid_al_hakim.sql`](file:///c:/Users/Swanto/OneDrive/Desktop/AL%20HAKIM/database/sql/masjid_al_hakim.sql)

Terdiri dari 3 tabel utama:
1. `transactions`: Menyimpan mutasi penerimaan & pengeluaran kas, nomor nota/kuitansi, PIC penanggung jawab, kategori pilar, catatan audit, dan status verifikasi.
2. `allocations`: Menyimpan 4 pilar alokasi syariah, target persentase, kode warna, ikon, dan deskripsi fokus belanja.
3. `bank_settings`: Menyimpan konfigurasi rekening perbankan (BSI), kode transfer, NMID QRIS, nomor WhatsApp konfirmasi, saldo awal, dan periode aktif.

---

## 🏗️ Arsitektur Framework Laravel

Proyek disusun mengikuti konvensi standar Laravel:

```
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Controller.php
│   │       ├── DashboardController.php   # Agregasi data finansial & view binding
│   │       └── TransactionController.php # API CRUD mutasi, validasi, & ekspor CSV
│   └── Models/
│       ├── Transaction.php               # Model mutasi kas + scope query
│       ├── Allocation.php                # Model pilar alokasi syariah
│       └── BankSetting.php               # Model konfigurasi perbankan & QRIS
├── config/
│   ├── app.php                           # Konfigurasi aplikasi & timezone Asia/Jakarta
│   └── database.php                      # Konfigurasi koneksi MySQL & SQLite
├── database/
│   ├── migrations/                       # Skema migrasi tabel Laravel
│   ├── seeders/                          # Seeder data resmi Oktober 2026
│   └── sql/
│       └── masjid_al_hakim.sql           # File SQL murni siap import ke phpMyAdmin/MySQL
├── public/
│   ├── css/style.css                     # Gaya CSS custom, glassmorphism, & print layout
│   ├── js/app.js                         # Logika interaktif, Chart.js, auto-rotasi, auto-scroll
│   ├── js/data.js                        # Data referensi
│   └── index.php                         # Titik masuk publik web server
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php                 # Layout Blade utama, top bar, TV ticker, & navigation
│   └── dashboard.blade.php               # Tampilan 4 menu modular berbasis Blade
├── routes/
│   ├── web.php                           # Route halaman utama & ekspor data
│   └── api.php                           # Route RESTful API transaksi
├── .env.example & .env                   # Pengaturan environment & kredensial DB
└── composer.json                         # Dependensi PHP & Laravel
```

---

## 🚀 Panduan Menjalankan Sistem

### Opsi A: Menjalankan dengan Framework Laravel (PHP & MySQL)

1. **Persiapan Database:**
   - Buat database baru bernama `masjid_al_hakim` di MySQL / MariaDB (melalui phpMyAdmin atau terminal):
     ```sql
     CREATE DATABASE masjid_al_hakim CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
     ```
   - **Metode 1 (Import SQL):** Import file `database/sql/masjid_al_hakim.sql` langsung melalui phpMyAdmin atau MySQL CLI:
     ```bash
     mysql -u root -p masjid_al_hakim < database/sql/masjid_al_hakim.sql
     ```
   - **Metode 2 (Migrasi Laravel):** Atau jalankan migrasi & seeder bawaan Laravel:
     ```bash
     php artisan migrate --seed
     ```

2. **Pasang Dependensi & Jalankan Aplikasi:**
   ```bash
   composer install
   php artisan key:generate
   php artisan serve
   ```
   Buka peramban di: **`http://127.0.0.1:8000`**

---

### Opsi B: Pratinjau Cepat Tanpa Menunggu PHP/Composer (Mode Standalone)

Jika pada perangkat Windows Anda belum terinstal PHP/Composer atau ingin langsung melihat tampilan TV dan menguji fitur rotasi dan auto-scroll seketika:

1. **Klik Ganda File:** **`Buka_Website.bat`**  
   *(Website langsung terbuka di Google Chrome / Microsoft Edge)*
2. Atau jalankan server Node.js lokal:
   ```bash
   node serve.js
   ```
   Akses di: **`http://localhost:3000`**

---

*Dikelola secara amanah oleh Dewan Kemakmuran Masjid (DKM) Masjid Al-Hakim.*
