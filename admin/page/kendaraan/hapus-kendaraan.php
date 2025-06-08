<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();
$kendaraan = new Kendaraan();

// Pastikan user sudah login DAN adalah admin
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'mobil';
$id = intval($_GET['id']);

$typeMap = [
  'alat-berat'       => 'alat_berat',
  'kendaraan-khusus' => 'kend_khusus'
];

$dbType = array_key_exists($type, $typeMap) ? $typeMap[$type] : $type;

$allowedDbTypes = ['mobil', 'motor', 'truk', 'alat_berat', 'sepeda', 'kend_khusus'];

if (!in_array($dbType, $allowedDbTypes) || !$id) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type");
  exit;
}

$row = $kendaraan->getById($dbType, $id);

if ($row) {
  // Hapus gambar dari server jika ada
  // if (!empty($row['gambar'])) {
  //   $projectRoot = $_SERVER['DOCUMENT_ROOT'];
  //   $gambarPath = "$projectRoot/PWD-Project-Mandiri/asset/$type/" . $row['gambar'];

  //   if (file_exists($gambarPath)) {
  //     unlink($gambarPath); // Hapus file gambar
  //   }
  // }

  // Hapus data dari database
  if ($kendaraan->delete($dbType, $id)) {
    $_SESSION['success'] = "Data berhasil dihapus.";
  } else {
    $_SESSION['error'] = "Gagal menghapus data dari database.";
  }
} else {
  $_SESSION['error'] = "Data tidak ditemukan.";
}

header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type");
exit;
