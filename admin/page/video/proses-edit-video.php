<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Video.php';

$session = new AppSession();
$videoModel = new Video();

// Cek login dan akses admin
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  $_SESSION['error'] = "Akses ditolak.";
  header("Location: /PWD-Project-Mandiri/admin/login.php");
  exit;
}

// Ambil parameter dari URL
$type = $_GET['type'] ?? 'kelola-video';
$id_video = intval($_GET['id']);

// Validasi type
$allowedTypes = ['kelola-video'];
if (!in_array($type, $allowedTypes) || $id_video <= 0) {
  $_SESSION['error'] = "Parameter tidak valid.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=video&page=$type");
  exit;
}

// Ambil data lama (untuk validasi)
$dataLama = $videoModel->getById($id_video);
if (!$dataLama) {
  $_SESSION['error'] = "Data video tidak ditemukan.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=video&page=$type&error=Data tidak ditemukan");
  exit;
}

// Ambil data dari form
$judul_video = $_POST['judul_video'] ?? '';
$url = $_POST['url'] ?? '';
$kategori = $_POST['kategori'] ?? '';
$status_post = $_POST['status_post'] ?? '';

// Validasi input wajib
if (empty($judul_video) || empty($url) || empty($kategori) || !in_array($status_post, ['Posting', 'Belum'])) {
  $_SESSION['error'] = "Judul, URL, kategori, dan status harus diisi dengan benar.";
  header("Location: /PWD-Project-Mandiri/admin/page/video/edit-video.php?type=$type&id=$id_video");
  exit;
}

// Data untuk update
$updateData = [
  'judul_video' => $judul_video,
  'url' => $url,
  'kategori' => $kategori,
  'status_post' => $status_post
];

// Update data video
if ($videoModel->update($updateData, $id_video)) {
  $_SESSION['success'] = "Data video berhasil diperbarui.";
} else {
  $_SESSION['error'] = "Gagal memperbarui data video.";
}

header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=video&page=$type");
exit;
