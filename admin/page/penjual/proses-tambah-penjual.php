<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Penjual.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();

// Cek login dan role admin
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-penjual';
$allowedTypes = ['kelola-penjual'];

if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?error=Tipe+tidak+valid");
  exit;
}

// Ambil data dari form
$nama_penjual = $_POST['nama_penjual'] ?? '';
$email = $_POST['email'] ?? '';
$no_hp = $_POST['no_hp'] ?? '';
$alamat = $_POST['alamat'] ?? '';
$tipe_penjual = $_POST['tipe_penjual'] ?? '';
$status = $_POST['status'] ?? 'Aktif';

// Validasi input dasar
if (empty($nama_penjual) || empty($email) || empty($no_hp) || empty($alamat) || empty($tipe_penjual)) {
  $error = urlencode('Semua field wajib diisi');
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=penjual&page=$type&error=$error");
  exit;
}

// Validasi email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $error = urlencode('Email tidak valid');
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=penjual&page=$type&error=$error");
  exit;
}

// Siapkan data untuk disimpan
$data = [
  'nama_penjual' => $nama_penjual,
  'email' => $email,
  'no_hp' => $no_hp,
  'alamat' => $alamat,
  'tipe_penjual' => $tipe_penjual,
  'status' => $status
];

// Simpan ke database
$penjual = new Penjual();
if ($penjual->simpan('penjual', $data)) {
  $success = urlencode('Data penjual berhasil ditambahkan');
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=penjual&page=$type&success=$success");
} else {
  error_log("Gagal menyimpan penjual: " . print_r($data, true));
  $error = urlencode('Gagal menyimpan data penjual');
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=penjual&page=$type&error=$error");
}
exit;
