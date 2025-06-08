<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Transaksi.php';

$session = new AppSession();

if (!$session->isLoggedIn()) {
  header("Location: /PWD-Project-Mandiri/admin/login.php");
  exit;
}

$type = $_POST['type'] ?? '';
$id_produk = intval($_POST['id_produk'] ?? 0);
$jumlah_unit = intval($_POST['jumlah_unit'] ?? 1);
$metode_bayar = $_POST['metode_bayar'] ?? '';
$pesan_tambahan = $_POST['pesan_tambahan'] ?? '';

// Validasi type
$allowedTypes = ['mobil', 'motor', 'truk', 'alat_berat', 'sepeda', 'kend_khusus', 'kebutuhan'];
if (!in_array($type, $allowedTypes)) {
  die("Jenis produk tidak valid.");
}

// Load model utama
if ($type === 'kebutuhan') {
  require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kebutuhan.php';
  $produkModel = new Kebutuhan();
  $data = $produkModel->getById('kebutuhan', $id_produk);
} else {
  require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php';
  $produkModel = new Kendaraan();
  $data = $produkModel->getById($type, $id_produk);
}

if (!$data) {
  die("Produk tidak ditemukan.");
}

// Ambil stok tergantung jenis produk
$stok = ($type === 'kebutuhan') ? ($data['jumlah'] ?? 0) : ($data['jumlah_unit'] ?? 0);

// Validasi jumlah unit ≤ stok
if ($jumlah_unit <= 0 || $jumlah_unit > $stok) {
  die("Jumlah pesanan tidak valid.");
}

$rawHarga = $data['harga_per_unit'] ?? $data['harga'] ?? '0';

// Bersihkan harga
if (is_numeric($rawHarga)) {
  $hargaSatuan = floatval($rawHarga);
  $hargaSatuan = intval($hargaSatuan); // → 200000000
} else {
  $hargaSatuan = preg_replace('/[^0-9]/', '', $rawHarga); // → "200000000"
  $hargaSatuan = intval($hargaSatuan);
}

$totalHarga = $hargaSatuan * $jumlah_unit;
$totalHargaDB = number_format($totalHarga, 2, '.', ''); // → "200000000.00"

// Generate nomor invoice
function generateInvoiceNumber()
{
  $date = date("Ymd");
  $random = str_pad(mt_rand(1, 9999), 4, "0", STR_PAD_LEFT);
  return "INV-$date-$random";
}

$invoice = generateInvoiceNumber();

// Ambil data user
$user = $session->getUserData();
$id_user = $user['id_pengguna'];

// Simpan ke tabel transaksi
$transaksi = new Transaksi();
$simpan = $transaksi->add([
  'id_pembeli' => $id_user,
  'type' => $type,
  'id_produk' => $id_produk,
  'nama_pembeli' => $user['username'],
  'nama_produk' => $data["nama_$type"] ?? $data['judul_promo'] ?? $data['nama_kebutuhan'] ?? 'Tidak tersedia',
  'jumlah_unit' => $jumlah_unit,
  'total_harga' => $totalHargaDB,
  'metode_bayar' => $metode_bayar,
  'pesan_tambahan' => $pesan_tambahan,
  'no_invoice' => $invoice
]);

if ($simpan) {
  header("Location: /PWD-Project-Mandiri/index.php?pesan=berhasil");
  exit;
} else {
  die("Gagal menyimpan pesanan.");
}
