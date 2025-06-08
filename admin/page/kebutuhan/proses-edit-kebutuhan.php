<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kebutuhan.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();
$kebutuhan = new Kebutuhan();

// Validasi login admin
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-kebutuhan';
$id = intval($_GET['id']);

// Validasi type
$allowedTypes = ['kelola-kebutuhan'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kebutuhan&page=$type&error=Tipe kebutuhan tidak valid");
  exit;
}

// Validasi ID
if ($id <= 0) {
  $_SESSION['error'] = "ID tidak valid.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kebutuhan&page=$type&error=ID tidak valid");
  exit;
}

// Ambil data lama untuk penanganan gambar
$dataLama = $kebutuhan->getById('kebutuhan', $id);
if (!$dataLama) {
  $_SESSION['error'] = "Data kebutuhan tidak ditemukan";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kebutuhan&page=$type&error=Data tidak ditemukan");
  exit;
}

// Ambil data dari form
$namaKebutuhan = $_POST['nama_kebutuhan'] ?? '';
$jenisKebutuhan = $_POST['jenis_kebutuhan'] ?? '';
$jumlah = $_POST['jumlah'] ?? 0;
$harga = $_POST['harga'] ?? 0;
$kategori = $_POST['kategori'] ?? '';
$statusKebutuhan = $_POST['status_kebutuhan'] ?? '';
$statusPost = $_POST['status_post'] ?? '';

// Validasi input wajib
if (
  empty($namaKebutuhan) || empty($jenisKebutuhan) || empty($jumlah) ||
  empty($harga) || empty($kategori) || empty($statusKebutuhan) || empty($statusPost)
) {
  $_SESSION['error'] = "Semua field wajib diisi.";
  header("Location: /PWD-Project-Mandiri/admin/page/kebutuhan/edit-kebutuhan.php?type=$type&id=$id");
  exit;
}

// Siapkan data untuk update
$updateData = [
  'nama_kebutuhan' => $namaKebutuhan,
  'jenis_kebutuhan' => $jenisKebutuhan,
  'jumlah' => $jumlah,
  'harga' => $harga,
  'kategori' => $kategori,
  'status_kebutuhan' => $statusKebutuhan,
  'status_post' => $statusPost,
  'gambar' => $dataLama['gambar'] // default jika tidak ada gambar baru
];

// Handle upload gambar baru jika ada
if (isset($_FILES["gambar"]) && !empty($_FILES["gambar"]["name"])) {
  $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/asset/kebutuhan/';
  $gambar = basename($_FILES["gambar"]["name"]);
  $imageFileType = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
  $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

  // Validasi ekstensi file
  if (!in_array($imageFileType, $allowedExtensions)) {
    $_SESSION['error'] = "Format file tidak didukung. Hanya JPG, JPEG, PNG & WEBP yang diperbolehkan.";
    header("Location: /PWD-Project-Mandiri/admin/page/kebutuhan/edit-kebutuhan.php?type=$type&id=$id");
    exit;
  }

  // Validasi ukuran file
  if ($_FILES["gambar"]["size"] > 2 * 1024 * 1024) {
    $_SESSION['error'] = "Ukuran file terlalu besar. Maksimal 2MB.";
    header("Location: /PWD-Project-Mandiri/admin/page/kebutuhan/edit-kebutuhan.php?type=$type&id=$id");
    exit;
  }

  // Hapus gambar lama jika bukan default
  if ($dataLama['gambar'] !== 'default.jpg') {
    $oldImage = $target_dir . $dataLama['gambar'];
    if (file_exists($oldImage)) {
      unlink($oldImage);
    }
  }

  // Upload gambar baru
  $target_file = $target_dir . $gambar;
  if (!move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
    $_SESSION['error'] = "Gagal mengupload gambar baru.";
    header("Location: /PWD-Project-Mandiri/admin/page/kebutuhan/edit-kebutuhan.php?type=$type&id=$id");
    exit;
  }

  $updateData['gambar'] = $gambar;
}

// Update data kebutuhan
if ($kebutuhan->update('kebutuhan', $id, $updateData)) {
  $_SESSION['success'] = "Data berhasil diperbarui.";
} else {
  $_SESSION['error'] = "Gagal memperbarui data.";
}

header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kebutuhan&page=$type");
exit;
