<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Cards.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-cards';
$allowedTypes = ['kelola-cards'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=cards&page=$type&error=Tipe tidak valid");
  exit;
}

// Ambil data dari form
$judul = $_POST['judul'] ?? '';
$subJudul = $_POST['sub_judul'] ?? '';
$redirect = $_POST['redirect'] ?? '';
$urutan = $_POST['urutan'] ?? '';
$status = $_POST['status'] ?? 'Aktif';

// Validasi input wajib
if (empty($judul) || empty($subJudul) || empty($redirect) || empty($urutan) || empty($status)) {
  $_SESSION['error'] = "Semua field wajib diisi.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=cards&page=$type&error=Semua field wajib diisi");
  exit;
}

$data = [
  'judul' => $judul,
  'sub_judul' => $subJudul,
  'redirect' => $redirect,
  'urutan' => $urutan,
  'status' => $status
];

// Upload Gambar
$projectRoot = $_SERVER['DOCUMENT_ROOT'];
$target_dir = "$projectRoot/PWD-Project-Mandiri/asset/cards/";

if (!is_dir($target_dir)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=cards&page=$type&error=Folder penyimpanan tidak ditemukan");
  exit;
}

$gambar = basename($_FILES["gambar"]["name"]);
$imageFileType = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
$allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

if (empty($gambar)) {
  $_SESSION['error'] = "Gambar harus diupload";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=cards&page=$type");
  exit;
}

if (!in_array($imageFileType, $allowedExtensions)) {
  $_SESSION['error'] = "Format file tidak didukung. Hanya JPG, JPEG, PNG & WEBP yang diperbolehkan.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=cards&page=$type");
  exit;
}

if ($_FILES["gambar"]["size"] > 2 * 1024 * 1024) {
  $_SESSION['error'] = "Ukuran file terlalu besar. Maksimal 2MB.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=cards&page=$type");
  exit;
}

$target_file = $target_dir . $gambar;

if (!move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
  $_SESSION['error'] = "Gagal mengupload gambar.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=cards&page=$type");
  exit;
}

$data['gambar'] = $gambar;

// Simpan ke database
$cards = new cards();
if ($cards->simpan('cards', $data)) {
  $_SESSION['success'] = "Data cards berhasil ditambahkan.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=cards&page=$type&success=Data berhasil disimpan");
} else {
  // Hapus gambar jika gagal simpan
  if (file_exists($target_file)) {
    unlink($target_file);
  }
  $_SESSION['error'] = "Gagal menambahkan cards.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=cards&page=$type&error=Gagal menyimpan data");
}

exit;
