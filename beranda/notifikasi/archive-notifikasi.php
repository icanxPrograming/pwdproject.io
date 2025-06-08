<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn()) {
  die('Anda harus login terlebih dahulu untuk mengarsipkan notifikasi.');
}

// Hanya user biasa
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
$arsip = $notifikasi->archiveById($id_notifikasi);

if ($arsip) {
  header("Location: /PWD-Project-Mandiri/index.php?page=notifikasi");
  exit;
} else {
  die("Gagal mengarsipkan notifikasi.");
}
