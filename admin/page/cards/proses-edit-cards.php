<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Cards.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();
$card = new Cards();

// Validasi login admin
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'kelola-cards';
$id = intval($_GET['id']);

// Validasi type
$allowedTypes = ['kelola-cards'];
if (!in_array($type, $allowedTypes)) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=cards&page=$type&error=Tipe cards tidak valid");
  exit;
}

// Validasi ID
if ($id <= 0) {
  $_SESSION['error'] = "ID tidak valid.";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=cards&page=$type&error=ID tidak valid");
  exit;
}

// Ambil data lama untuk penanganan gambar
$dataLama = $card->getById('cards', $id);
if (!$dataLama) {
  $_SESSION['error'] = "Data cards tidak ditemukan";
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=cards&page=$type&error=Data tidak ditemukan");
  exit;
}

// Ambil data dari form
$judul = $_POST['judul'] ?? '';
$subJudul = $_POST['sub_judul'] ?? '';
$redirect = $_POST['redirect'] ?? '';
$urutan = $_POST['urutan'] ?? '';
$status = $_POST['status'] ?? '';

// Validasi input wajib
if (
  empty($judul) || empty($subJudul) || empty($redirect) ||
  empty($urutan) || empty($status)
) {
  $_SESSION['error'] = "Semua field wajib diisi.";
  header("Location: /PWD-Project-Mandiri/admin/page/cards/edit-cards.php?type=$type&id=$id");
  exit;
}

// Siapkan data untuk update
$updateData = [
  'judul' => $judul,
  'sub_judul' => $subJudul,
  'redirect' => $redirect,
  'urutan' => $urutan,
  'status' => $status,
  'gambar' => $dataLama['gambar']
];

// Handle upload gambar baru jika ada
if (isset($_FILES["gambar"]) && !empty($_FILES["gambar"]["name"])) {
  $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/asset/cards/';
  $gambar = basename($_FILES["gambar"]["name"]);
  $imageFileType = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
  $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

  // Validasi ekstensi file
  if (!in_array($imageFileType, $allowedExtensions)) {
    $_SESSION['error'] = "Format file tidak didukung. Hanya JPG, JPEG, PNG & WEBP yang diperbolehkan.";
    header("Location: /PWD-Project-Mandiri/admin/page/cards/edit-cards.php?type=$type&id=$id");
    exit;
  }

  // Validasi ukuran file
  if ($_FILES["gambar"]["size"] > 2 * 1024 * 1024) {
    $_SESSION['error'] = "Ukuran file terlalu besar. Maksimal 2MB.";
    header("Location: /PWD-Project-Mandiri/admin/page/cards/edit-cards.php?type=$type&id=$id");
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
    header("Location: /PWD-Project-Mandiri/admin/page/cards/edit-cards.php?type=$type&id=$id");
    exit;
  }

  $updateData['gambar'] = $gambar;
}

// Update data cards
if ($card->update('cards', $id, $updateData)) {
  $_SESSION['success'] = "Data berhasil diperbarui.";
} else {
  $_SESSION['error'] = "Gagal memperbarui data.";
}

header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=cards&page=$type");
exit;
