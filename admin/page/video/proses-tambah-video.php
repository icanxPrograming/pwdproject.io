<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  $_SESSION['error'] = "Akses ditolak.";
  header("Location: /PWD-Project-Mandiri/admin/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-video';
$allowedTypes = ['kelola-video'];
if (!in_array($type, $allowedTypes)) {
  $_SESSION['error'] = "Tipe tidak valid.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=video&page=$type&error=Tipe tidak valid");
  exit;
}

// Ambil data dari form
$judul_video = trim($_POST['judul_video'] ?? '');
$url = trim($_POST['url'] ?? '');
$kategori = trim($_POST['kategori'] ?? '');
$status_post = trim($_POST['status_post'] ?? 'Draft');

// Validasi input wajib
if (empty($judul_video) || empty($url) || empty($kategori)) {
  $_SESSION['error'] = "Judul, URL, dan kategori harus diisi.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=video&page=$type&error=Form belum lengkap");
  exit;
}

// Format URL (opsional)
if (strpos($url, 'youtu') !== false && strpos($url, 'embed') === false) {
  // Jika bukan link embed, bisa ubah otomatis
  preg_match('/(?:v=|\/)([a-zA-Z0-9\-_]{11})/', $url, $matches);
  if (!empty($matches[1])) {
    $url = "https://www.youtube.com/embed/"  . $matches[1];
  } else {
    $_SESSION['error'] = "URL video tidak valid.";
    header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=video&page=$type&error=URL tidak valid");
    exit;
  }
}

// Data untuk simpan
$data = [
  'judul_video' => $judul_video,
  'url' => $url,
  'kategori' => $kategori,
  'status_post' => $status_post
];

// Simpan ke database
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Video.php';
$videoModel = new Video();

if ($videoModel->add($data)) {
  $_SESSION['success'] = "Video berhasil ditambahkan.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=video&page=$type&success=Data berhasil disimpan");
} else {
  $_SESSION['error'] = "Gagal menyimpan video.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=video&page=$type&error=Gagal menyimpan data");
}

exit;
