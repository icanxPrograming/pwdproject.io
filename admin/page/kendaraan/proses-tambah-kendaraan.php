<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'mobil';

$typeMap = [
  'alat-berat'       => 'alat_berat',
  'kendaraan-khusus' => 'kend_khusus'
];

$dbType = array_key_exists($type, $typeMap) ? $typeMap[$type] : $type;

$allowedDbTypes = ['mobil', 'motor', 'truk', 'alat_berat', 'sepeda', 'kend_khusus'];

if (!in_array($dbType, $allowedDbTypes)) {
  $_SESSION['error'] = "Jenis kendaraan tidak valid.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type&error=Jenis kendaraan tidak valid");
  exit;
}

$kendaraan = new Kendaraan();

$nama = $_POST["nama_$type"];
$tahun = $_POST['tahun'];
$jumlah_unit = $_POST['jumlah_unit'];
$harga_per_unit = $_POST['harga_per_unit'];
$deskripsi = $_POST['deskripsi'];
$status_post = $_POST['status_post'];

$projectRoot = $_SERVER['DOCUMENT_ROOT']; // e.g., /var/www/html

$target_dir = $projectRoot . "/PWD-Project-Mandiri/asset/$type/";

// Buat folder jika belum ada
if (!is_dir($target_dir)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type&error=Folder penyimpanan tidak ditemukan");
  exit;
}

// Nama file gambar
$gambar = basename($_FILES["gambar"]["name"]);
$target_file = $target_dir . $gambar;

// Ekstensi file (huruf kecil)
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
if (!in_array($imageFileType, $allowedExtensions)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type&error=Format file tidak didukung");
  exit;
}

if ($_FILES["gambar"]["size"] > 2 * 1024 * 1024) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type&error=Ukuran file terlalu besar");
  exit;
}

if (!move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type&error=Gagal mengupload gambar");
  exit;
}

$data = [
  "nama_$type" => $nama,
  'tahun' => $tahun,
  'jumlah_unit' => $jumlah_unit,
  'harga_per_unit' => $harga_per_unit,
  'deskripsi' => $deskripsi,
  'status_post' => $status_post,
  'gambar' => $gambar
];

if ($kendaraan->simpan($type, $data)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type&success=Data berhasil disimpan");
} else {
  if (file_exists($target_file)) {
    unlink($target_file);
  }
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type&error=Gagal menyimpan data");
}
exit;
