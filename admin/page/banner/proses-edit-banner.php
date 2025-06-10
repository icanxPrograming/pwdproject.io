<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Banner.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();
$banner = new Banner();

// Cek login dan role admin
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-banner';
$id = intval($_GET['id']);

// Validasi tipe
$allowedTypes = ['kelola-banner'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=banner&page=$type");
  exit;
}

$row = $banner->getById('banner', $id);

if (!$row) {
  $_SESSION['error'] = "Data banner tidak ditemukan";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=banner&page=$type");
  exit;
}

// Ambil data dari form
$updateData = [
  'url_gambar' => $_POST['url_gambar'] ?? '',
  'urutan' => $_POST['urutan'] ?? '',
  'status' => $_POST['status'] ?? ''
];

// Validasi input dasar
if (empty($updateData['url_gambar']) || empty($updateData['urutan']) || empty($updateData['status'])) {
  $_SESSION['error'] = "Semua field wajib diisi.";
  header("Location: edit-banner.php?type=$type&id=$id");
  exit;
}

// Simpan ke database
if ($banner->update('banner', $id, $updateData)) {
  $_SESSION['success'] = "Data banner berhasil diperbarui";
} else {
  $_SESSION['error'] = "Gagal memperbarui data banner";
}

header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=banner&page=$type");
exit;
