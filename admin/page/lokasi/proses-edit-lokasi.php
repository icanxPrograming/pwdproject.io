<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Lokasi.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();
$lokasi = new Lokasi();

// Cek login dan role admin
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-lokasi';
$id = intval($_GET['id']);

// Validasi tipe
$allowedTypes = ['kelola-lokasi'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=lokasi&page=$type");
  exit;
}

$row = $lokasi->getById('lokasi', $id);

if (!$row) {
  $_SESSION['error'] = "Data lokasi tidak ditemukan";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=lokasi&page=$type");
  exit;
}

// Ambil data dari form
$updateData = [
  'nama_lokasi' => $_POST['nama_lokasi'] ?? '',
  'status' => $_POST['status'] ?? ''
];

// Validasi input dasar
if (empty($updateData['nama_lokasi'])) {
  $_SESSION['error'] = "Semua field wajib diisi.";
  header("Location: edit-lokasi.php?type=$type&id=$id");
  exit;
}

// Simpan ke database
if ($lokasi->update('lokasi', $id, $updateData)) {
  $_SESSION['success'] = "Data lokasi berhasil diperbarui";
} else {
  $_SESSION['error'] = "Gagal memperbarui data lokasi";
}

header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=lokasi&page=$type");
exit;
