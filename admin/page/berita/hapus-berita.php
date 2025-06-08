<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Berita.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();
$berita = new Berita();

// Validasi login admin
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-berita';
$id = intval($_GET['id']);

// Validasi type
$allowedTypes = ['kelola-berita'];
if (!in_array($type, $allowedTypes)) {
  $_SESSION['error'] = "Tipe berita tidak valid.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=berita&page=$type");
  exit;
}

// Validasi ID
if ($id <= 0) {
  $_SESSION['error'] = "ID tidak valid.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=berita&page=$type");
  exit;
}

// Ambil data lama untuk hapus gambar
$row = $berita->getById('berita', $id);
if (!$row) {
  $_SESSION['error'] = "Data berita tidak ditemukan.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=berita&page=$type");
  exit;
}

// Hapus data dari database
if ($berita->delete('berita', $id)) {
  $_SESSION['success'] = "Data berita berhasil dihapus.";
} else {
  $_SESSION['error'] = "Gagal menghapus data berita dari database.";
}

header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=berita&page=$type");
exit;
