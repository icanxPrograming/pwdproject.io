<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Berita.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();
$berita = new Berita();

// Validasi login admin
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-berita';
$id = intval($_GET['id']);

// Validasi type
$allowedTypes = ['kelola-berita'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=berita&page=$type&error=Tipe berita tidak valid");
  exit;
}

// Validasi ID
if ($id <= 0) {
  $_SESSION['error'] = "ID tidak valid.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=berita&page=$type&error=ID tidak valid");
  exit;
}

// Ambil data lama untuk penanganan gambar
$dataLama = $berita->getById('berita', $id);
if (!$dataLama) {
  $_SESSION['error'] = "Data berita tidak ditemukan";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=berita&page=$type&error=Data tidak ditemukan");
  exit;
}

// Ambil data dari form
$judul = $_POST['judul'] ?? '';
$isi = $_POST['isi'] ?? '';
$kategori = $_POST['kategori'] ?? '';
$tanggal = $_POST['tanggal'] ?? '';
$penulis = $_POST['penulis'] ?? '';
$status = $_POST['status'] ?? 'Aktif';

// Validasi input wajib
if (empty($judul) || empty($isi) || empty($kategori) || empty($tanggal) || empty($penulis)) {
  $_SESSION['error'] = "Semua field wajib diisi.";
  header("Location: /PWD-Project-Mandiri/admin/page/berita/edit-berita.php?type=$type&id=$id");
  exit;
}

// Siapkan data untuk update
$updateData = [
  'judul' => $judul,
  'isi' => $isi,
  'kategori' => $kategori,
  'tanggal' => $tanggal,
  'penulis' => $penulis,
  'status' => $status
];

// Handle upload gambar baru jika ada
if (isset($_FILES["gambar"]) && !empty($_FILES["gambar"]["name"])) {
  $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/asset/berita/';
  $gambar = basename($_FILES["gambar"]["name"]);
  $imageFileType = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
  $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

  if (!in_array($imageFileType, $allowedExtensions)) {
    $_SESSION['error'] = "Format file tidak didukung. Hanya JPG, JPEG, PNG & WEBP yang diperbolehkan.";
    header("Location: /PWD-Project-Mandiri/admin/page/berita/edit-berita.php?type=$type&id=$id");
    exit;
  }

  if ($_FILES["gambar"]["size"] > 2 * 1024 * 1024) {
    $_SESSION['error'] = "Ukuran file terlalu besar. Maksimal 2MB.";
    header("Location: /PWD-Project-Mandiri/admin/page/berita/edit-berita.php?type=$type&id=$id");
    exit;
  }

  // Hapus gambar lama jika bukan default
  if ($dataLama['gambar'] !== 'default.jpg') {
    $oldImage = $target_dir . $dataLama['gambar'];
    if (file_exists($oldImage)) {
      unlink($oldImage); // hapus file lama
    }
  }

  // Upload gambar baru
  $target_file = $target_dir . $gambar;

  if (!move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
    $_SESSION['error'] = "Gagal mengupload gambar baru.";
    header("Location: /PWD-Project-Mandiri/admin/page/berita/edit-berita.php?type=$type&id=$id");
    exit;
  }

  $updateData['gambar'] = $gambar;
}

// Update data ke database
if ($berita->update('berita', $id, $updateData)) {
  $_SESSION['success'] = "Data berhasil diperbarui.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=berita&page=$type&success=Data berhasil diperbarui");
} else {
  $_SESSION['error'] = "Gagal memperbarui data berita.";
  header("Location: /PWD-Project-Mandiri/admin/page/berita/edit-berita.php?type=$type&id=$id");
}

exit;
