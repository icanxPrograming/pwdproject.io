<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Promo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();
$promo = new Promo();

// Validasi login admin
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-promo';
$id = intval($_GET['id']);

// Validasi type
$allowedTypes = ['kelola-promo'];
if (!in_array($type, $allowedTypes)) {
  $_SESSION['error'] = "Tipe promo tidak valid.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=promo&page=$type");
  exit;
}

// Validasi ID
if ($id <= 0) {
  $_SESSION['error'] = "ID tidak valid.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=promo&page=$type");
  exit;
}

// Ambil data lama untuk hapus gambar
$row = $promo->getById('promo', $id);
if (!$row) {
  $_SESSION['error'] = "Data promo tidak ditemukan.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=promo&page=$type");
  exit;
}

// Hapus data dari database
if ($promo->delete('promo', $id)) {
  $_SESSION['success'] = "Data promo berhasil dihapus.";
} else {
  $_SESSION['error'] = "Gagal menghapus data promo dari database.";
}

header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=promo&page=$type");
exit;
