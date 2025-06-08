<?php
session_start(); // Penting untuk $_SESSION

require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Video.php';

$session = new AppSession();
$videoModel = new Video();

// Pastikan user sudah login DAN adalah admin
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  $_SESSION['error'] = "Akses ditolak.";
  header("Location: /PWD-Project-Mandiri/admin/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-video';
$id_video = intval($_GET['id'] ?? 0);

// Validasi type
$allowedTypes = ['kelola-video'];
if (!in_array($type, $allowedTypes) || $id_video <= 0) {
  $_SESSION['error'] = "Parameter tidak valid.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=video&page=$type");
  exit;
}

// Ambil data video (opsional tapi baik untuk logika tambahan)
$data = $videoModel->getById($id_video);

if ($data) {
  // Hapus video berdasarkan ID
  if ($videoModel->deleteById($id_video)) {
    $_SESSION['success'] = "Video berhasil dihapus.";
  } else {
    $_SESSION['error'] = "Gagal menghapus video dari database.";
  }
} else {
  $_SESSION['error'] = "Video tidak ditemukan.";
}

header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=video&page=$type");
exit;
