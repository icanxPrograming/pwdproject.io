<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Penjual.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();
$penjual = new Penjual();

// Cek login dan role admin
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-penjual';
$id = intval($_GET['id']);

// Validasi tipe
$allowedTypes = ['kelola-penjual'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=penjual&page=$type");
  exit;
}

$row = $penjual->getById('penjual', $id);

if (!$row) {
  $_SESSION['error'] = "Data penjual tidak ditemukan";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=penjual&page=$type");
  exit;
}

// Ambil data dari form
$updateData = [
  'nama_penjual' => $_POST['nama_penjual'] ?? '',
  'email' => $_POST['email'] ?? '',
  'no_hp' => $_POST['no_hp'] ?? '',
  'alamat' => $_POST['alamat'] ?? '',
  'tipe_penjual' => $_POST['tipe_penjual'] ?? '',
  'status' => $_POST['status'] ?? ''
];

// Validasi input dasar
if (empty($updateData['nama_penjual']) || empty($updateData['email']) || empty($updateData['no_hp']) || empty($updateData['alamat']) || empty($updateData['tipe_penjual'])) {
  $_SESSION['error'] = "Semua field wajib diisi.";
  header("Location: edit-penjual.php?type=$type&id=$id");
  exit;
}

// Validasi email
if (!filter_var($updateData['email'], FILTER_VALIDATE_EMAIL)) {
  $_SESSION['error'] = "Email tidak valid.";
  header("Location: edit-penjual.php?type=$type&id=$id");
  exit;
}

// Simpan ke database
if ($penjual->update('penjual', $id, $updateData)) {
  $_SESSION['success'] = "Data penjual berhasil diperbarui";
} else {
  $_SESSION['error'] = "Gagal memperbarui data penjual";
}

header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=penjual&page=$type");
exit;
