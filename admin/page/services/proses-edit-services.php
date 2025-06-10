<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Services.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();
$services = new Services();

// Cek login dan role admin
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-services';
$id = intval($_GET['id']);

// Validasi tipe
$allowedTypes = ['kelola-services'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=services&page=$type");
  exit;
}

$row = $services->getById('services', $id);
if (!$row) {
  $_SESSION['error'] = "Data services tidak ditemukan";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=services&page=$type");
  exit;
}

// Ambil data dari form
$updateData = [
  'icon' => $_POST['icon'] ?? '',
  'judul_section' => $_POST['judul_section'] ?? '',
  'konten' => $_POST['konten'] ?? '',
  'urutan' => intval($_POST['urutan'] ?? 0),
  'status' => $_POST['status'] ?? ''
];

// Validasi input dasar
if (empty($updateData['icon']) || empty($updateData['judul_section']) || empty($updateData['konten']) || $updateData['urutan'] < 0 || empty($updateData['status'])) {
  $_SESSION['error'] = "Semua field wajib diisi.";
  header("Location: edit-services.php?type=$type&id=$id");
  exit;
}

// Simpan ke database
if ($services->update('services', $id, $updateData)) {
  $_SESSION['success'] = "Data services berhasil diperbarui";
} else {
  $_SESSION['error'] = "Gagal memperbarui data services";
}

header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=services&page=$type");
exit;
