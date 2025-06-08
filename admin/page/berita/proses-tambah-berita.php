<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';
$session = new AppSession();

// Validasi login admin
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-berita';
$allowedTypes = ['kelola-berita'];

if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=berita&page=$type&error=Tipe berita tidak valid");
  exit;
}

// Ambil data dari form
$judulBerita = $_POST['judul'] ?? '';
$isi = $_POST['isi'] ?? '';
$tanggal = $_POST['tanggal'] ?? date('Y-m-d');
$kategori = $_POST['kategori'] ?? '';
$penulis = $_POST['penulis'] ?? '';
$status = $_POST['status'] ?? 'Aktif';

// Validasi input wajib
if (empty($judulBerita) || empty($isi) || empty($tanggal) || empty($kategori) || empty($penulis)) {
  $_SESSION['error'] = "Semua field wajib diisi.";
  header("Location: /PWD-Project-Mandiri/admin/page/berita/tambah-berita.php?type=$type");
  exit;
}

$data = [
  'judul' => $judulBerita,
  'isi' => $isi,
  'tanggal' => $tanggal,
  'kategori' => $kategori,
  'penulis' => $penulis,
  'status' => $status
];

// Upload gambar - Hanya jalankan jika gambar diupload
$target_dir = $_SERVER['DOCUMENT_ROOT'] . "/PWD-Project-Mandiri/asset/berita/";

if (isset($_FILES["gambar"]) && !empty($_FILES["gambar"]["name"])) {
  $gambar = basename($_FILES["gambar"]["name"]);
  $imageFileType = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
  $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

  // Validasi ekstensi file
  if (!in_array($imageFileType, $allowedExtensions)) {
    $_SESSION['error'] = "Format file tidak didukung. Hanya JPG, JPEG, PNG & WEBP yang diperbolehkan.";
    header("Location: /PWD-Project-Mandiri/admin/page/berita/tambah-berita.php?type=$type");
    exit;
  }

  // Validasi ukuran file
  if ($_FILES["gambar"]["size"] > 2 * 1024 * 1024) { // Max 2MB
    $_SESSION['error'] = "Ukuran file terlalu besar. Maksimal 2MB.";
    header("Location: /PWD-Project-Mandiri/admin/page/berita/tambah-berita.php?type=$type");
    exit;
  }

  $target_file = $target_dir . $gambar;

  // Validasi direktori upload
  if (!is_dir($target_dir)) {
    $_SESSION['error'] = "Folder penyimpanan tidak ditemukan. Pastikan folder sudah dibuat.";
    header("Location: /PWD-Project-Mandiri/admin/page/berita/tambah-berita.php?type=$type");
    exit;
  }

  // Upload file ke server
  if (!move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
    $_SESSION['error'] = "Gagal mengupload gambar.";
    header("Location: /PWD-Project-Mandiri/admin/page/berita/tambah-berita.php?type=$type");
    exit;
  }

  $data['gambar'] = $gambar;
} else {
  $_SESSION['error'] = "Gambar harus diupload.";
  header("Location: /PWD-Project-Mandiri/admin/page/berita/tambah-berita.php?type=$type");
  exit;
}

// Load model Berita
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Berita.php';
$berita = new Berita();

// Simpan ke database
if ($berita->simpan('berita', $data)) {
  $_SESSION['success'] = "Data berhasil ditambahkan.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=berita&page=$type&success=Data berhasil disimpan");
} else {
  $_SESSION['error'] = "Gagal menambahkan data.";
  if (isset($target_file) && file_exists($target_file)) {
    unlink($target_file);
  }
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=berita&page=$type&error=Gagal menyimpan data");
}

exit;
