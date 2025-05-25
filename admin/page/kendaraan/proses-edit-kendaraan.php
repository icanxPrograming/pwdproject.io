<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';

$session = new AppSession();

if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/login.php");
  exit;
}

$type = $_GET['type'] ?? 'mobil';
$id = intval($_GET['id']);

$typeMap = [
  'alat-berat'       => 'alat_berat',
  'kendaraan-khusus' => 'kend_khusus'
];

$dbType = array_key_exists($type, $typeMap) ? $typeMap[$type] : $type;

$allowedDbTypes = ['mobil', 'motor', 'truk', 'alat_berat', 'sepeda', 'kend_khusus'];

if (!in_array($dbType, $allowedDbTypes) || !$id) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type");
  exit;
}

$kendaraan = new Kendaraan();

$row = $kendaraan->getById($dbType, $id);

if (!$row) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $updateData = [
    "nama_$type" => $_POST["nama_$type"],
    'tahun' => $_POST['tahun'],
    'jumlah_unit' => $_POST['jumlah_unit'],
    'harga_per_unit' => $_POST['harga_per_unit'],
    'deskripsi' => $_POST['deskripsi'],
    'status_post' => $_POST['status_post']
  ];

  $projectRoot = $_SERVER['DOCUMENT_ROOT'];
  $target_dir = "$projectRoot/PWD-Project-Mandiri/asset/$type/";

  if (!empty($_FILES["gambar"]["name"])) {
    $new_gambar = basename($_FILES["gambar"]["name"]);
    $target_file = $target_dir . $new_gambar;

    // Validasi ekstensi
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (!in_array($imageFileType, $allowedExtensions)) {
      $_SESSION['error'] = "Format gambar tidak didukung.";
      header("Location: edit-kendaraan.php?type=$type&id=$id");
      exit;
    }

    if (!move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
      $_SESSION['error'] = "Gagal mengupload gambar.";
      header("Location: edit-kendaraan.php?type=$type&id=$id");
      exit;
    }

    // Hapus gambar lama jika ada
    if (!empty($row['gambar']) && file_exists($target_dir . $row['gambar'])) {
      unlink($target_dir . $row['gambar']);
    }

    $updateData['gambar'] = $new_gambar;
  } else {
    $updateData['gambar'] = $row['gambar'];
  }

  if ($kendaraan->update($type, $id, $updateData)) {
    $_SESSION['success'] = "Data berhasil diperbarui";
  } else {
    $_SESSION['error'] = "Gagal memperbarui data";
  }

  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type");
  exit;
}
