<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Services.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();
$service = new Services();

// Cek login dan role admin
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-services';
$id = intval($_GET['id']);

// Validasi tipe
$allowedTypes = ['kelola-services'];
if (!in_array($type, $allowedTypes) || !$id) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=services&page=$type");
  exit;
}

$tableName = 'services';

// Hapus data services
if ($service->delete($tableName, $id)) {
  $_SESSION['success'] = "Data berhasil dihapus.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=services&page=$type&success=Data services berhasil dihapus");
} else {
  $_SESSION['error'] = "Gagal menghapus data dari database.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=services&page=$type&error=Gagal menghapus data services");
}
exit;
