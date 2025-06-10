<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

// Validasi type services
$type = $_GET['type'] ?? 'kelola-services';
$allowedTypes = ['kelola-services'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=services&page=$type&error=Tipe services tidak valid");
  exit;
}

// Ambil data dasar dari form
$icon = $_POST['icon'] ?? '';
$judulSection = $_POST['judul_section'] ?? '';
$konten = $_POST['konten'] ?? '';
$urutan = $_POST['urutan'] ?? '';
$status = $_POST['status'] ?? '';

// Validasi input wajib
if (empty($icon) || empty($judulSection) || empty($konten) || empty($urutan) || empty($status)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=services&page=$type&error=Semua field wajib diisi");
  exit;
}

$data = [
  'icon' => $icon,
  'judul_section' => $judulSection,
  'konten' => $konten,
  'urutan' => $urutan,
  'status' => $status,
];

// Load model Services
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Services.php';
$services = new Services();

// Simpan ke database
if ($services->simpan('services', $data)) {
  $_SESSION['success'] = "Data berhasil ditambahkan.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=services&page=$type&success=Data berhasil disimpan");
} else {
  $_SESSION['error'] = "Gagal menambahkan data.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=services&page=$type&error=Gagal menyimpan data");
}

exit;
