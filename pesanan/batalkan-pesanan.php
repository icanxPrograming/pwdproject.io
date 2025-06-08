<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Session.php';
$session = new AppSession();

if (!$session->isLoggedIn()) {
  header("Location: /PWD-Project-Mandiri/admin/login.php");
  exit;
}

// Hanya user biasa yang boleh akses halaman ini
if ($session->isAdmin()) {
  header("Location: /PWD-Project-Mandiri/admin/dashboard.php");
  exit;
}

$id_transaksi = intval($_GET['id'] ?? 0);
if (!$id_transaksi) {
  die("ID transaksi tidak valid.");
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

// Update status menjadi 'dibatalkan'
$statusUpdate = $transaksiModel->updateStatusById($id_transaksi, 'dibatalkan');
if (!$statusUpdate) {
  die("Gagal memperbarui status pesanan.");
}

// Cek apakah pesanan sudah dikonfirmasi sebelumnya
if ($dataPesanan['status'] === 'dikonfirmasi') {
  // Pesanan sudah dikonfirmasi → stok harus dikembalikan
  if ($type === 'kebutuhan') {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kebutuhan.php';
    $produkModel = new Kebutuhan();
    $dataProduk = $produkModel->getById('kebutuhan', $id_produk);

    $stok_sekarang = $dataProduk['jumlah'] ?? 0;
    $stok_baru = $stok_sekarang + $dataPesanan['jumlah'];

    $produkModel->updateStockKebutuhan($id_produk, $stok_baru);
  } else {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Kendaraan.php';
    $produkModel = new Kendaraan();
    $dataProduk = $produkModel->getById($type, $id_produk);

    $stok_sekarang = $dataProduk['jumlah_unit'] ?? 0;
    $stok_baru = $stok_sekarang + $dataPesanan['jumlah_unit'];

    $produkModel->updateStockKendaraan($type, $id_produk, $stok_baru);
  }
}

// Simpan notifikasi ke USER bahwa pesanan dibatalkan
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Notifikasi.php';
$notifikasi = new Notifikasi();

$isiPesan = "Anda telah membatalkan pesanan \"$dataPesanan[nama_produk]\".";

$simpanNotifikasi = $notifikasi->add([
  'id_pengguna' => $dataPesanan['id_pembeli'],
  'isi' => $isiPesan
]);

if (!$simpanNotifikasi) {
  error_log("Gagal simpan notifikasi untuk id_pengguna={$dataPesanan['id_pembeli']}");
}

// Redirect ke riwayat pesanan
header("Location: /PWD-Project-Mandiri/index.php?page=riwayat");
exit;
