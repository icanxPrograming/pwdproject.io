<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Lokasi.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();

// Cek login dan role admin
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-lokasi';
$allowedTypes = ['kelola-lokasi'];

if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?error=Tipe+tidak+valid");
  exit;
}

// Ambil data dari form
$nama_lokasi = $_POST['nama_lokasi'] ?? '';
$status = $_POST['status'] ?? 'Aktif';

// Validasi input dasar
if (empty($nama_lokasi)) {
  $error = urlencode('Semua field wajib diisi');
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=lokasi&page=$type&error=$error");
  exit;
}

// Siapkan data untuk disimpan
$data = [
  'nama_lokasi' => $nama_lokasi,
  'status' => $status
];

// Simpan ke database
$lokasi = new lokasi();
if ($lokasi->simpan('lokasi', $data)) {
  $_SESSION['success'] = "Data berhasil ditambahkan.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=lokasi&page=$type&success=$success");
} else {
  $_SESSION['error'] = "Gagal menambahkan data.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=lokasi&page=$type&error=$error");
}
exit;
