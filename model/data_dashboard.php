<?php
require_once __DIR__ . '/Koneksi.php';

$db = new Koneksi();
$conn = $db->getConnection();

$dataDashboard = [];

// Helper function untuk ambil jumlah data dengan validasi
function getCount($conn, $table, $condition = "1=1")
{
  $query = $conn->query("SELECT COUNT(*) AS total FROM `$table` WHERE $condition");
  if ($query === false) {
    return 0;
  }
  $row = $query->fetch_assoc();
  return $row ? (int)$row['total'] : 0;
}

// Total Kendaraan Aktif (`status_post = 'Posting'`)
$dataDashboard['kendaraan'] = getCount($conn, 'mobil', "status_post = 'Posting'")
  + getCount($conn, 'motor', "status_post = 'Posting'")
  + getCount($conn, 'truk', "status_post = 'Posting'")
  + getCount($conn, 'alat_berat', "status_post = 'Posting'")
  + getCount($conn, 'sepeda', "status_post = 'Posting'")
  + getCount($conn, 'kend_khusus', "status_post = 'Posting'");

// Penjual (status = 'Aktif')
$dataDashboard['penjual'] = getCount($conn, 'penjual', "status = 'Aktif'");

// Transaksi (status = 'Selesai')
$dataDashboard['transaksi'] = getCount($conn, 'transaksi', "status = 'Selesai'");

// Lokasi (status = 'Aktif')
$dataDashboard['lokasi'] = getCount($conn, 'lokasi', "status = 'Aktif'");

// Promo (status = 'Aktif')
$dataDashboard['promo'] = getCount($conn, 'promo', "status = 'Aktif'");

// Berita (status = 'Publish')
$dataDashboard['berita'] = getCount($conn, 'berita', "status = 'Publish'");

// Kebutuhan (status = 'Posting')
$dataDashboard['kebutuhan'] = getCount($conn, 'kebutuhan', "status_post = 'Posting'");
