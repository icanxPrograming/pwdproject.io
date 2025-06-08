<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kebutuhan.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();
$kebutuhan = new Kebutuhan();

// Pastikan user sudah login DAN adalah admin
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-kebutuhan';
$id = intval($_GET['id']);

// Validasi type
$allowedTypes = ['kelola-kebutuhan'];
if (!in_array($type, $allowedTypes) || $id <= 0) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kebutuhan&page=$type");
  exit;
}

// Ambil data berdasarkan ID
$data = $kebutuhan->getById('kebutuhan', $id);

if ($data) {
  // Hapus data dari database
  if ($kebutuhan->delete('kebutuhan', $id)) {
    $_SESSION['success'] = "Data kebutuhan berhasil dihapus.";
  } else {
    $_SESSION['error'] = "Gagal menghapus data dari database.";
  }
} else {
  $_SESSION['error'] = "Data kebutuhan tidak ditemukan.";
}

header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kebutuhan&page=$type");
exit;
