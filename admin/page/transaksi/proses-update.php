<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';
$session = new AppSession();

// Cek login dan admin
if (!$session->isLoggedIn() || !$session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/admin/login.php");
  exit;
}

// Ambil parameter
$id_transaksi = intval($_GET['id'] ?? 0);
$action = $_GET['action'] ?? ''; // confirm / reject

// Validasi input
if ($id_transaksi <= 0 || !in_array($action, ['confirm', 'reject'])) {
  die("Parameter tidak valid.");
}

// Load model Transaksi
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Transaksi.php';
$transaksiModel = new Transaksi();

// Ambil data pesanan
$dataPesanan = $transaksiModel->getById($id_transaksi);
if (!$dataPesanan) {
  die("Data pesanan tidak ditemukan.");
}

$type = $dataPesanan['type'];
$id_produk = $dataPesanan['id_produk'];
$jumlah_unit_pesanan = $dataPesanan['jumlah_unit'];

// Tentukan status baru
$statusBaru = ($action === 'confirm') ? 'dikonfirmasi' : 'dibatalkan';

// Update status pesanan
$updateStatus = $transaksiModel->updateStatusById($id_transaksi, $statusBaru);

if (!$updateStatus) {
  die("Gagal memperbarui status pesanan.");
}

// Jika dikonfirmasi → kurangi stok
if ($action === 'confirm') {
  require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php';
  $produkModel = new Kendaraan();

  if ($type === 'kebutuhan') {
    // Load model Kebutuhan jika jenis kebutuhan
    require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kebutuhan.php';
    $produkModelKebutuhan = new Kebutuhan();
    $dataProduk = $produkModelKebutuhan->getById('kebutuhan', $id_produk);
    $stok_sekarang = $dataProduk['jumlah'] ?? 0;

    $stok_baru = $stok_sekarang - $jumlah_unit_pesanan;
    if ($stok_baru < 0) {
      die("Stok tidak mencukupi untuk mengurangi jumlah unit.");
    }

    $updateStock = $produkModelKebutuhan->updateStockKebutuhan($id_produk, $stok_baru);
  } else {
    // Untuk kendaraan
    $dataProduk = $produkModel->getById($type, $id_produk);
    $stok_sekarang = $dataProduk['jumlah_unit'] ?? 0;

    $stok_baru = $stok_sekarang - $jumlah_unit_pesanan;
    if ($stok_baru < 0) {
      die("Stok tidak mencukupi untuk mengurangi jumlah unit.");
    }

    $updateStock = $produkModel->updateStockKendaraan($type, $id_produk, $stok_baru);
  }

  if (!$updateStock) {
    die("Gagal memperbarui stok.");
  }
}

// Jika dibatalkan → tambahkan stok kembali
if ($action === 'reject') {
  require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php';
  $produkModel = new Kendaraan();

  if ($type === 'kebutuhan') {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kebutuhan.php';
    $produkModelKebutuhan = new Kebutuhan();
    $dataProduk = $produkModelKebutuhan->getById('kebutuhan', $id_produk);

    $stok_sekarang = $dataProduk['jumlah'] ?? 0;
    $stok_baru = $stok_sekarang + $jumlah_unit_pesanan;

    $updateStock = $produkModelKebutuhan->updateStockKebutuhan($id_produk, $stok_baru);
  } else {
    $dataProduk = $produkModel->getById($type, $id_produk);
    $stok_sekarang = $dataProduk['jumlah_unit'] ?? 0;
    $stok_baru = $stok_sekarang + $jumlah_unit_pesanan;

    $updateStock = $produkModel->updateStockKendaraan($type, $id_produk, $stok_baru);
  }

  if (!$updateStock) {
    die("Gagal mengembalikan stok.");
  }
}

// Simpan notifikasi ke user
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Notifikasi.php';
$notifikasi = new Notifikasi();

$isiPesan = "Pesanan Anda \"$dataPesanan[nama_produk]\" telah $statusBaru.";
$simpanNotifikasi = $notifikasi->add([
  'id_pengguna' => $dataPesanan['id_pembeli'],
  'isi' => $isiPesan
]);

if (!$simpanNotifikasi) {
  error_log("Gagal simpan notifikasi untuk id_pengguna={$dataPesanan['id_pembeli']}");
}

// Redirect sukses
header("Location: /PWD-Project-Mandiri/admin/dashboard.php?module=transaksi&page=kelola-transaksi&pesan=berhasil");
exit;
