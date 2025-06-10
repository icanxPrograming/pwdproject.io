<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Banner.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();

// Cek login dan role admin
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-banner';
$allowedTypes = ['kelola-banner'];

if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?error=Tipe+tidak+valid");
  exit;
}

// Ambil data dari form
$url = $_POST['url_gambar'] ?? '';
$urutan = $_POST['urutan'] ?? '';
$status = $_POST['status'] ?? 'Aktif';

// Validasi input dasar
if (empty($url) || empty($urutan) || empty($status)) {
  $error = urlencode('Semua field wajib diisi');
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=banner&page=$type&error=$error");
  exit;
}

// Siapkan data untuk disimpan
$data = [
  'url_gambar' => $url,
  'urutan' => $urutan,
  'status' => $status
];

// Simpan ke database
$banner = new Banner();
if ($banner->simpan('banner', $data)) {
  $_SESSION['success'] = "Data berhasil ditambahkan.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=banner&page=$type&success=$success");
} else {
  $_SESSION['error'] = "Gagal menambahkan data.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=banner&page=$type&error=$error");
}
exit;
