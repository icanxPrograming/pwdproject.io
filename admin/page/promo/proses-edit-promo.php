<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Promo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();
$promo = new Promo();

// Validasi login admin
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-promo';
$id = intval($_GET['id']);

// Validasi type
$allowedTypes = ['kelola-promo'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=promo&page=$type&error=Tipe promo tidak valid");
  exit;
}

// Validasi ID
if ($id <= 0) {
  $_SESSION['error'] = "ID tidak valid.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=promo&page=$type&error=ID tidak valid");
  exit;
}

// Ambil data lama untuk penanganan gambar
$dataLama = $promo->getById('promo', $id);
if (!$dataLama) {
  $_SESSION['error'] = "Data promo tidak ditemukan";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=promo&page=$type&error=Data tidak ditemukan");
  exit;
}

// Ambil data dari form
$judulPromo = $_POST['judul_promo'] ?? '';
$deskripsi = $_POST['deskripsi'] ?? '';
$tanggalMulai = $_POST['tanggal_mulai'] ?? '';
$tanggalSelesai = $_POST['tanggal_selesai'] ?? '';
$status = $_POST['status'] ?? '';

// Validasi input wajib
if (empty($judulPromo) || empty($tanggalMulai) || empty($tanggalSelesai) || empty($status)) {
  $_SESSION['error'] = "Semua field wajib diisi.";
  header("Location: /PWD-Project-Mandiri/admin/page/promo/edit-promo.php?type=$type&id=$id");
  exit;
}

// Siapkan data untuk update
$updateData = [
  'judul_promo' => $judulPromo,
  'deskripsi' => $deskripsi,
  'tanggal_mulai' => $tanggalMulai,
  'tanggal_selesai' => $tanggalSelesai,
  'status' => $status
];

// Handle upload gambar baru jika ada
if (isset($_FILES["gambar"]) && !empty($_FILES["gambar"]["name"])) {
  $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/asset/promo/';
  $gambar = basename($_FILES["gambar"]["name"]);
  $imageFileType = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
  $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

  // Validasi ekstensi file
  if (!in_array($imageFileType, $allowedExtensions)) {
    $_SESSION['error'] = "Format file tidak didukung. Hanya JPG, JPEG, PNG & WEBP yang diperbolehkan.";
    header("Location: /PWD-Project-Mandiri/admin/page/promo/edit-promo.php?type=$type&id=$id");
    exit;
  }

  // Validasi ukuran file
  if ($_FILES["gambar"]["size"] > 2 * 1024 * 1024) {
    $_SESSION['error'] = "Ukuran file terlalu besar. Maksimal 2MB.";
    header("Location: /PWD-Project-Mandiri/admin/page/promo/edit-promo.php?type=$type&id=$id");
    exit;
  }

  // Hapus gambar lama jika bukan default
  if ($dataLama['gambar'] !== 'default.jpg') {
    $oldImage = $target_dir . $dataLama['gambar'];
    if (file_exists($oldImage)) {
      unlink($oldImage); // hapus gambar lama
    }
  }

  // Upload gambar baru
  $target_file = $target_dir . $gambar;

  if (!move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
    $_SESSION['error'] = "Gagal mengupload gambar baru.";
    header("Location: /PWD-Project-Mandiri/admin/page/promo/edit-promo.php?type=$type&id=$id");
    exit;
  }

  $updateData['gambar'] = $gambar;
} else {
  // Jika tidak ada gambar baru, gunakan yang lama
  $updateData['gambar'] = $dataLama['gambar'];
}

// Update data promo
if ($promo->update('promo', $id, $updateData)) {
  $_SESSION['success'] = "Data berhasil diperbarui.";
} else {
  $_SESSION['error'] = "Gagal memperbarui data.";
}
header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=promo&page=$type");
exit;
