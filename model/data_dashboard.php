<?php
require_once __DIR__ . '/Koneksi.php';

$db = new Koneksi();
$conn = $db->getConnection();

$dataDashboard = [];

// Total Kendaraan (dari tabel mobil, motor, truk, alatberat, sepeda, kend_khusus, status_post = 'Posting')
$queryMobil = $conn->query("SELECT COUNT(*) as total FROM mobil WHERE status_post = 'Posting'");
$rowMobil = $queryMobil->fetch_assoc();
$totalMobil = $rowMobil['total'];

$queryMotor = $conn->query("SELECT COUNT(*) as total FROM motor WHERE status_post = 'Posting'");
$rowMotor = $queryMotor->fetch_assoc();
$totalMotor = $rowMotor['total'];

$queryTruk = $conn->query("SELECT COUNT(*) as total FROM truk WHERE status_post = 'Posting'");
$rowTruk = $queryTruk->fetch_assoc();
$totalTruk = $rowTruk['total'];

$queryAlatBerat = $conn->query("SELECT COUNT(*) as total FROM alat_berat WHERE status_post = 'Posting'");
$rowAlatBerat = $queryAlatBerat->fetch_assoc();
$totalAlatBerat = $rowAlatBerat['total'];

$querySepeda = $conn->query("SELECT COUNT(*) as total FROM sepeda WHERE status_post = 'Posting'");
$rowSepeda = $querySepeda->fetch_assoc();
$totalSepeda = $rowSepeda['total'];

$queryKendKhusus = $conn->query("SELECT COUNT(*) as total FROM kend_khusus WHERE status_post = 'Posting'");
$rowKendKhusus = $queryKendKhusus->fetch_assoc();
$totalKendKhusus = $rowKendKhusus['total'];

// Jumlahkan semua total kendaraan
$dataDashboard['kendaraan'] = $totalMobil + $totalMotor + $totalTruk + $totalAlatBerat + $totalSepeda + $totalKendKhusus;


// Penjual (status = 'Aktif')
$query = $conn->query("SELECT COUNT(*) as total FROM penjual WHERE status = 'Aktif'");
$row = $query->fetch_assoc();
$dataDashboard['penjual'] = $row['total'];

// Transaksi Penjualan (status = 'Selesai')
$query = $conn->query("SELECT COUNT(*) as total FROM trans_penjualan WHERE status = 'Selesai'");
$row = $query->fetch_assoc();
$dataDashboard['penjualan'] = $row['total'];

// Transaksi Pembelian (status = 'Selesai')
$query = $conn->query("SELECT COUNT(*) as total FROM trans_pembelian WHERE status = 'Selesai'");
$row = $query->fetch_assoc();
$dataDashboard['pembelian'] = $row['total'];

// Kategori (status = 'Aktif')
$query = $conn->query("SELECT COUNT(*) as total FROM kategori WHERE status = 'Aktif'");
$row = $query->fetch_assoc();
$dataDashboard['kategori'] = $row['total'];

// Lokasi (status = 'Aktif')
$query = $conn->query("SELECT COUNT(*) as total FROM lokasi WHERE status = 'Aktif'");
$row = $query->fetch_assoc();
$dataDashboard['lokasi'] = $row['total'];

// Promo (status = 'Aktif')
$query = $conn->query("SELECT COUNT(*) as total FROM promo WHERE status = 'Aktif'");
$row = $query->fetch_assoc();
$dataDashboard['promo'] = $row['total'];

// Berita (status = 'Publish')
$query = $conn->query("SELECT COUNT(*) as total FROM berita WHERE status = 'Publish'");
$row = $query->fetch_assoc();
$dataDashboard['berita'] = $row['total'];
