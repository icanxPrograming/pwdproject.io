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

// Ambil data dasar dari form
$merk = $_POST['merk'];
$jenis = $_POST["jenis_$dbType"];
$nama = $_POST["nama_$dbType"];
$tahun = $_POST['tahun'];
$kondisi = $_POST['kondisi'];
$jumlah_unit = $_POST['jumlah_unit'];
$harga_per_unit = $_POST['harga_per_unit'];
$deskripsi = $_POST['deskripsi'] ?? '';
$status_post = $_POST['status_post'];

// Bahan Bakar (hanya jika tersedia)
$bahan_bakar = null;
if ($dbType !== 'sepeda') {
  $bahan_bakar = $_POST['bahan_bakar'] ?? null;
}

// Transmisi
$transmisi = null;
if ($dbType === 'alat_berat') {
  $transmisi = 'Manual'; // Default Manual
} elseif (in_array($dbType, ['mobil', 'motor', 'truk', 'kend_khusus'])) {
  $transmisi = $_POST['transmisi'] ?? null;
}

// Buat array data
$data = [
  "nama_$dbType" => $nama,
  'merk' => $merk,
  "jenis_$dbType" => $jenis,
  'tahun' => $tahun,
  'kondisi' => $kondisi,
  'jumlah_unit' => $jumlah_unit,
  'harga_per_unit' => $harga_per_unit,
  'deskripsi' => $deskripsi,
  'status_post' => $status_post,
  'bahan_bakar' => $bahan_bakar,
  'transmisi' => $transmisi,
  "status_$dbType" => 'Tersedia'
];

// Kolom tambahan spesifik kendaraan
if ($dbType === 'mobil') {
  $data['jumlah_kursi'] = $_POST['jumlah_kursi'] ?? null;
} elseif ($dbType === 'truk') {
  $data['kapasitas_muatan'] = $_POST['kapasitas_muatan'] ?? null;
}

// Filter null values
$data = array_filter($data, function ($value) {
  return $value !== null;
});

// Upload Gambar
$projectRoot = $_SERVER['DOCUMENT_ROOT'];
$target_dir = $projectRoot . "/PWD-Project-Mandiri/asset/$type/";

if (!is_dir($target_dir)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type&error=Folder penyimpanan tidak ditemukan");
  exit;
}

$gambar = basename($_FILES["gambar"]["name"]);
$imageFileType = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

if (!in_array($imageFileType, $allowedExtensions)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type&error=Format file tidak didukung");
  exit;
}

if ($_FILES["gambar"]["size"] > 2 * 1024 * 1024) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type&error=Ukuran file terlalu besar");
  exit;
}

$target_file = $target_dir . $gambar;

if (!move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type&error=Gagal mengupload gambar");
  exit;
}

$data['gambar'] = $gambar;

// Simpan ke database
if ($kendaraan->simpan($type, $data)) {
  $_SESSION['success'] = "Data berhasil ditambahkan.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type&success=Data berhasil disimpan");
} else {
  $_SESSION['error'] = "Gagal menambahkan data.";
  if (file_exists($target_file)) {
    unlink($target_file);
  }
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type&error=Gagal menyimpan data");
}

exit;
