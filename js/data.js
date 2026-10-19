/**
 * MASJID AL-HAKIM - Database Transparansi Kas & Mutasi
 * Periode: Oktober 2026
 */

const DEFAULT_TRANSACTIONS = [
  {
    id: "TX-202610-008",
    tanggal: "2026-10-18",
    tanggalFormatted: "18 Okt 2026",
    keterangan: "Infaq Kotak Amal Shalat Jumat",
    kategori: "Penerimaan",
    posAnggaran: "Operasional & Utilitas",
    nominal: 8420000,
    buktiNo: "KWT/2026/10/018",
    pic: "Ustadz Zulkifli (Ketua DKM)",
    metode: "Tunai / Kotak Amal",
    catatan: "Penghitungan bersama saksi 3 pengurus masjid setelah pelaksanaan shalat Jumat secara terbuka di ruang sekretariat."
  },
  {
    id: "TX-202610-007",
    tanggal: "2026-10-17",
    tanggalFormatted: "17 Okt 2026",
    keterangan: "Donasi QRIS Online — Hamba Allah",
    kategori: "Penerimaan",
    posAnggaran: "Dakwah & Kajian",
    nominal: 500000,
    buktiNo: "QRIS/2026/10/017",
    pic: "Sistem Otomatis QRIS",
    metode: "QRIS Nasional",
    catatan: "Infaq digital jamaah kajian subuh melalui scan barcode QRIS resmi Masjid Al-Hakim."
  },
  {
    id: "TX-202610-006",
    tanggal: "2026-10-15",
    tanggalFormatted: "15 Okt 2026",
    keterangan: "Pembayaran Listrik PLN & Air PDAM",
    kategori: "Pengeluaran",
    posAnggaran: "Operasional & Utilitas",
    nominal: 2350000,
    buktiNo: "NOTA/PLN-PDAM/202610",
    pic: "H. Ahmad S. (Bendahara)",
    metode: "Auto-Debit Bank BSI",
    catatan: "Pelunasan tagihan listrik ruang utama & AC masjid (IDPEL: 53210984 - Rp 1.850.000) dan tagihan air wudhu (PDAM: 049281 - Rp 500.000)."
  },
  {
    id: "TX-202610-005",
    tanggal: "2026-10-14",
    tanggalFormatted: "14 Okt 2026",
    keterangan: "Bisyarah Ustadz Kajian Ahad Subuh",
    kategori: "Pengeluaran",
    posAnggaran: "Dakwah & Kajian",
    nominal: 1000000,
    buktiNo: "KWT/KJ-044/2026",
    pic: "Divisi Dakwah & Ta'mir",
    metode: "Tunai",
    catatan: "Bisyarah kajian tematik kitab Riyadhus Shalihin bersama Ustadz Dr. Fauzan, M.A."
  },
  {
    id: "TX-202610-004",
    tanggal: "2026-10-12",
    tanggalFormatted: "12 Okt 2026",
    keterangan: "Transfer Bank Infaq Operasional",
    kategori: "Penerimaan",
    posAnggaran: "Operasional & Utilitas",
    nominal: 2500000,
    buktiNo: "BSI/TRF-88912/2026",
    pic: "H. Ahmad S. (Bendahara)",
    metode: "Transfer BSI",
    catatan: "Infaq operasional bulanan keluarga jamaah H. Mansur melalui transfer rekening BSI Utama."
  },
  {
    id: "TX-202610-003",
    tanggal: "2026-10-10",
    tanggalFormatted: "10 Okt 2026",
    keterangan: "Perawatan & Cuci AC Ruang Utama (4 Unit)",
    kategori: "Pengeluaran",
    posAnggaran: "Operasional & Utilitas",
    nominal: 800000,
    buktiNo: "NOTA/SVC-AC/102",
    pic: "Biro Sarana & Prasarana",
    metode: "Tunai",
    catatan: "Servis berkala, pencucian evaporator, filter serta penambahan freon 4 unit AC Daikin 2 PK ruang shalat utama."
  },
  {
    id: "TX-202610-002",
    tanggal: "2026-10-08",
    tanggalFormatted: "08 Okt 2026",
    keterangan: "Donasi Santunan Anak Yatim Bulanan",
    kategori: "Penerimaan",
    posAnggaran: "Sosial & Yatim Dhuafa",
    nominal: 5000000,
    buktiNo: "KWT/YTM-081/2026",
    pic: "Divisi Sosial & Yatim",
    metode: "Transfer Bank",
    catatan: "Donasi khusus santunan anak yatim binaan masjid untuk periode Oktober 2026."
  },
  {
    id: "TX-202610-001",
    tanggal: "2026-10-05",
    tanggalFormatted: "05 Okt 2026",
    keterangan: "Penyaluran Santunan Yatim & Dhuafa",
    kategori: "Pengeluaran",
    posAnggaran: "Sosial & Yatim Dhuafa",
    nominal: 5000000,
    buktiNo: "DST/YTM-BLN10/2026",
    pic: "Divisi Sosial & Yatim",
    metode: "Tunai & Paket Sembako",
    catatan: "Penyaluran santunan tunai @Rp 200.000 + paket sembako untuk 25 anak yatim & dhuafa di lingkungan sekitar masjid."
  }
];

const ALLOCATION_DATA = [
  {
    id: "alloc-1",
    pos: "Operasional & Utilitas",
    targetPercent: 45,
    fokus: "Listrik, air wudhu, kebersihan ruang shalat, sound system, & marbot",
    color: "#059669", // emerald-600
    icon: "zap"
  },
  {
    id: "alloc-2",
    pos: "Dakwah & Kajian",
    targetPercent: 25,
    fokus: "Honor penceramah, takjil buka puasa, kajian rutin subuh & remaja",
    color: "#2563eb", // blue-600
    icon: "book-open"
  },
  {
    id: "alloc-3",
    pos: "Sosial & Yatim Dhuafa",
    targetPercent: 20,
    fokus: "Bantuan santunan sembako dhuafa sekitar masjid dan beasiswa yatim",
    color: "#d97706", // amber-600
    icon: "heart-handshake"
  },
  {
    id: "alloc-4",
    pos: "Dana Cadangan Bangunan",
    targetPercent: 10,
    fokus: "Renovasi berkala fasilitas, kubah, karpet, dan pemeliharaan genset",
    color: "#7c3aed", // purple-600
    icon: "hammer"
  }
];

const BANK_INFO = {
  bankName: "Bank Syariah Indonesia (BSI)",
  accountNumber: "7182-9012-34",
  accountName: "Masjid Al-Hakim Kas Utama",
  qrisNmid: "ID1020261902481",
  qrisSupport: ["BCA", "Mandiri", "BSI", "GoPay", "OVO", "ShopeePay", "DANA", "LinkAja"],
  whatsappNumber: "6281290123456",
  email: "sekretariat@masjidalhakim.id",
  address: "Kompleks Perumahan Al-Hakim Asri, Jakarta",
  periode: "Oktober 2026"
};
