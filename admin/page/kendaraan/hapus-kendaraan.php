<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();
$kendaraan = new Kendaraan();

if (!$session->isLoggedIn()) {
  header("Location: /PWD-Project-Mandiri/index.php");
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

$row = $kendaraan->getById($type, $id);

if ($row && $kendaraan->delete($type, $id)) {
  // $gambarPath = $_SERVER['DOCUMENT_ROOT'] . "/PWD-Project-Mandiri/asset/$type/" . $row['gambar'];
  // if (!empty($row['gambar']) && file_exists($gambarPath)) {
  //   unlink($gambarPath);
  // }
}

header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type");
exit;
