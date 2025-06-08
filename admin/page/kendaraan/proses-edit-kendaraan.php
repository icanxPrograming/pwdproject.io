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
  // Data dasar dari form
  $updateData = [];

  // Nama kendaraan dinamis
  $namaKey = "nama_$dbType";
  $updateData[$namaKey] = $_POST[$namaKey] ?? null;

  // Merk
  $updateData['merk'] = $_POST['merk'] ?? null;

  // Jenis
  $jenisKey = "jenis_$dbType";
  $updateData[$jenisKey] = $_POST[$jenisKey] ?? null;

  // Kondisi
  $updateData['kondisi'] = $_POST['kondisi'] ?? null;

  // Bahan Bakar
  if ($dbType !== 'sepeda') {
    $updateData['bahan_bakar'] = $_POST['bahan_bakar'] ?? null;
  }

  // Jumlah Unit & Harga
  $updateData['jumlah_unit'] = $_POST['jumlah_unit'] ?? null;
  $updateData['harga_per_unit'] = $_POST['harga_per_unit'] ?? null;
  $updateData['deskripsi'] = $_POST['deskripsi'] ?? null;
  $updateData['status_post'] = $_POST['status_post'] ?? null;

  // Kolom tambahan berdasarkan jenis kendaraan
  if ($dbType === 'mobil') {
    $updateData['jumlah_kursi'] = $_POST['jumlah_kursi'] ?? null;
    $updateData['transmisi'] = $_POST['transmisi'] ?? null;
  } elseif ($dbType === 'motor') {
    $updateData['transmisi'] = $_POST['transmisi'] ?? null;
  } elseif ($dbType === 'truk') {
    $updateData['kapasitas_muatan'] = $_POST['kapasitas_muatan'] ?? null;
    $updateData['transmisi'] = $_POST['transmisi'] ?? null;
  } elseif ($dbType === 'alat_berat') {
    $updateData['transmisi'] = 'Manual'; // Otomatis Manual untuk alat_berat
  } elseif ($dbType === 'kend_khusus') {
    $updateData['transmisi'] = $_POST['transmisi'] ?? null;
  }

  // Upload Gambar (Opsional)
  $projectRoot = $_SERVER['DOCUMENT_ROOT'];
  $target_dir = "$projectRoot/PWD-Project-Mandiri/asset/$type/";

  if (!empty($_FILES["gambar"]["name"])) {
    $new_gambar = basename($_FILES["gambar"]["name"]);
    $target_file = $target_dir . $new_gambar;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedExtensions)) {
      $_SESSION['error'] = "Format gambar tidak didukung.";
      header("Location: /PWD-Project-Mandiri/admin/page/kendaraan/edit-kendaraan.php?type=$type&id=$id");
      exit;
    }

    if ($_FILES["gambar"]["size"] > 2 * 1024 * 1024) {
      $_SESSION['error'] = "Ukuran gambar terlalu besar.";
      header("Location: /PWD-Project-Mandiri/admin/page/kendaraan/edit-kendaraan.php?type=$type&id=$id");
      exit;
    }

    if (!move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
      $_SESSION['error'] = "Gagal mengupload gambar.";
      header("Location: /PWD-Project-Mandiri/admin/page/kendaraan/edit-kendaraan.php?type=$type&id=$id");
      exit;
    }

    // Hapus gambar lama jika ada
    if (!empty($row['gambar']) && file_exists($target_dir . $row['gambar'])) {
      unlink($target_dir . $row['gambar']);
    }

    $updateData['gambar'] = $new_gambar;
  }

  // Bersihkan field null (agar tidak error saat update)
  $updateData = array_filter($updateData, function ($value) {
    return $value !== null;
  });

  // Jalankan proses update
  if ($kendaraan->update($type, $id, $updateData)) {
    $_SESSION['success'] = "Data berhasil diperbarui.";
  } else {
    $_SESSION['error'] = "Gagal memperbarui data.";
  }

  header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=kendaraan&page=$type");
  exit;
}
