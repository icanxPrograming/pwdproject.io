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
if (!in_array($type, $allowedTypes) || !$id) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=penjual&page=$type");
  exit;
}

// Nama tabel dinamis (untuk fleksibilitas dan keterbacaan)
$tableName = 'penjual';

// Hapus data penjual
if ($penjual->delete($tableName, $id)) {
  // Redirect dengan pesan sukses
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=penjual&page=$type&success=Data penjual berhasil dihapus");
} else {
  // Redirect dengan pesan error
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=penjual&page=$type&error=Gagal menghapus data penjual");
}
exit;
