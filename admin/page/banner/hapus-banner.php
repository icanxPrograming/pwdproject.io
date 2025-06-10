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
if (!in_array($type, $allowedTypes) || !$id) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=banner&page=$type");
  exit;
}

// Nama tabel dinamis (untuk fleksibilitas dan keterbacaan)
$tableName = 'banner';

// Hapus data banner
if ($banner->delete($tableName, $id)) {
  $_SESSION['success'] = "Data berhasil dihapus.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=banner&page=$type&success=Data banner berhasil dihapus");
} else {
  $_SESSION['error'] = "Gagal menghapus data dari database.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=banner&page=$type&error=Gagal menghapus data banner");
}
exit;
