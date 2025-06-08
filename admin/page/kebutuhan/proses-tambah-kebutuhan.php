<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kebutuhan.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-kebutuhan';
$allowedTypes = ['kelola-kebutuhan'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kebutuhan&page=$type&error=Tipe tidak valid");
  exit;
}

// Ambil data dari form
$nama = $_POST['nama_kebutuhan'] ?? '';
$jenis = $_POST['jenis_kebutuhan'] ?? '';
$jumlah = intval($_POST['jumlah'] ?? 0);
$harga = intval($_POST['harga'] ?? 0);
$kategori = $_POST['kategori'] ?? '';
$status = $_POST['status_kebutuhan'] ?? 'Tersedia';
$statusPost = $_POST['status_post'] ?? 'Posting';

// Validasi input wajib
if (empty($nama) || empty($jenis) || empty($jumlah) || empty($harga) || empty($kategori)) {
  $_SESSION['error'] = "Semua field wajib diisi.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kebutuhan&page=$type&error=Semua field wajib diisi");
  exit;
}

$data = [
  'nama_kebutuhan' => $nama,
  'jenis_kebutuhan' => $jenis,
  'jumlah' => $jumlah,
  'harga' => $harga,
  'kategori' => $kategori,
  'status_kebutuhan' => $status,
  'status_post' => $statusPost
];

// Upload Gambar
$projectRoot = $_SERVER['DOCUMENT_ROOT'];
$target_dir = "$projectRoot/PWD-Project-Mandiri/asset/kebutuhan/";

if (!is_dir($target_dir)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kebutuhan&page=$type&error=Folder penyimpanan tidak ditemukan");
  exit;
}

$gambar = basename($_FILES["gambar"]["name"]);
$imageFileType = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
$allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

if (empty($gambar)) {
  $_SESSION['error'] = "Gambar harus diupload";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kebutuhan&page=$type");
  exit;
}

if (!in_array($imageFileType, $allowedExtensions)) {
  $_SESSION['error'] = "Format file tidak didukung. Hanya JPG, JPEG, PNG & WEBP yang diperbolehkan.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kebutuhan&page=$type");
  exit;
}

if ($_FILES["gambar"]["size"] > 2 * 1024 * 1024) {
  $_SESSION['error'] = "Ukuran file terlalu besar. Maksimal 2MB.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kebutuhan&page=$type");
  exit;
}

$target_file = $target_dir . $gambar;

if (!move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
  $_SESSION['error'] = "Gagal mengupload gambar.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kebutuhan&page=$type");
  exit;
}

$data['gambar'] = $gambar;

// Simpan ke database
$kebutuhan = new Kebutuhan();
if ($kebutuhan->simpan('kebutuhan', $data)) {
  $_SESSION['success'] = "Data kebutuhan berhasil ditambahkan.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kebutuhan&page=$type&success=Data berhasil disimpan");
} else {
  // Hapus gambar jika gagal simpan
  if (file_exists($target_file)) {
    unlink($target_file);
  }
  $_SESSION['error'] = "Gagal menambahkan kebutuhan.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kebutuhan&page=$type&error=Gagal menyimpan data");
}

exit;
