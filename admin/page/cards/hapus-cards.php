<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Cards.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();
$card = new Cards();

// Pastikan user sudah login DAN adalah admin
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-cards';
$id = intval($_GET['id']);

// Validasi type
$allowedTypes = ['kelola-cards'];
if (!in_array($type, $allowedTypes) || $id <= 0) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=cards&page=$type");
  exit;
}

// Ambil data berdasarkan ID
$data = $card->getById('cards', $id);

if ($data) {
  // Hapus data dari database
  if ($card->delete('cards', $id)) {
    $_SESSION['success'] = "Data cards berhasil dihapus.";
  } else {
    $_SESSION['error'] = "Gagal menghapus data dari database.";
  }
} else {
  $_SESSION['error'] = "Data cards tidak ditemukan.";
}

header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=cards&page=$type");
exit;
