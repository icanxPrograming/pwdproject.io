<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn()) {
  die('Anda harus login untuk menghapus notifikasi.');
}

// Hanya user biasa yang bisa akses
if ($session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php");
  exit;
}

$id_notifikasi = intval($_GET['id'] ?? 0);
if ($id_notifikasi <= 0) {
  die("ID notifikasi tidak valid.");
}

// Load model notifikasi
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Notifikasi.php';
$notifikasi = new Notifikasi();

// Hapus notifikasi
$hapus = $notifikasi->deleteById($id_notifikasi);

if ($hapus) {
  header("Location: /PWD-Project-Mandiri/index.php?page=notifikasi");
  exit;
} else {
  die("Gagal menghapus notifikasi.");
}
