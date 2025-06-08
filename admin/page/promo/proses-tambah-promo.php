<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

// Validasi type promo
$type = $_GET['type'] ?? 'kelola-promo';
$allowedTypes = ['kelola-promo'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=promo&page=$type&error=Tipe promo tidak valid");
  exit;
}

// Ambil data dasar dari form
$judulPromo = $_POST['judul_promo'] ?? '';
$deskripsi = $_POST['deskripsi'] ?? '';
$tanggalMulai = $_POST['tanggal_mulai'] ?? '';
$tanggalSelesai = $_POST['tanggal_selesai'] ?? '';
$status = $_POST['status'] ?? '';

// Validasi input wajib
if (empty($judulPromo) || empty($tanggalMulai) || empty($tanggalSelesai) || empty($status)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=promo&page=$type&error=Semua field wajib diisi");
  exit;
}

$data = [
  'judul_promo' => $judulPromo,
  'deskripsi' => $deskripsi,
  'tanggal_mulai' => $tanggalMulai,
  'tanggal_selesai' => $tanggalSelesai,
  'status' => $status,
];

// Upload Gambar - Hanya jalankan jika gambar diupload
if (isset($_FILES["gambar"]) && $_FILES["gambar"]["name"] !== '') {
  $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/PWD-Project-Mandiri/asset/promo/";

  // Cek ekstensi file
  $gambar = basename($_FILES["gambar"]["name"]);
  $imageFileType = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
  $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

  if (!in_array($imageFileType, $allowedExtensions)) {
    header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=promo&page=$type&error=Format file tidak didukung");
    exit;
  }

  if ($_FILES["gambar"]["size"] > 2 * 1024 * 1024) {
    header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=promo&page=$type&error=Ukuran file terlalu besar");
    exit;
  }

  $target_file = $target_dir . $gambar;

  if (!is_dir($target_dir)) {
    header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=promo&page=$type&error=Folder penyimpanan tidak ditemukan");
    exit;
  }

  if (!move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
    header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=promo&page=$type&error=Gagal mengupload gambar");
    exit;
  }

  $data['gambar'] = $gambar;
} else {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=promo&page=$type&error=Gambar harus diupload");
  exit;
}

// Load model Promo
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Promo.php';
$promo = new Promo();

// Simpan ke database
if ($promo->simpan('promo', $data)) {
  $_SESSION['success'] = "Data berhasil ditambahkan.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=promo&page=$type&success=Data berhasil disimpan");
} else {
  $_SESSION['error'] = "Gagal menambahkan data.";
  if (file_exists($target_file)) {
    unlink($target_file);
  }
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=promo&page=$type&error=Gagal menyimpan data");
}

exit;
