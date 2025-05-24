<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'mobil';
$allowedTypes = ['mobil', 'motor'];

if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?error=Jenis kendaraan tidak valid");
  exit;
}

$kendaraan = new Kendaraan();

$nama = $_POST["nama_$type"];
$tahun = $_POST['tahun'];
$jumlah_unit = $_POST['jumlah_unit'];
$harga_per_unit = $_POST['harga_per_unit'];
$deskripsi = $_POST['deskripsi'];
$status_post = $_POST['status_post'];

// Mendapatkan root folder proyek dengan aman (folder satu tingkat di atas model)
$projectRoot = realpath(dirname(__DIR__));

// Path folder target untuk simpan gambar
$target_dir = $projectRoot . "/asset/$type/";

// Buat folder jika belum ada
if (!is_dir($target_dir)) {
  mkdir($target_dir, 0777, true);
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
