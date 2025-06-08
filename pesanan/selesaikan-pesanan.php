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
if (!$dataPesanan || strtolower($dataPesanan['status']) !== 'dikonfirmasi') {
  die("Pesanan tidak ditemukan atau belum dikonfirmasi.");
}

// Update status jadi 'selesai'
$statusUpdate = $transaksiModel->updateStatusById($id_transaksi, 'selesai');
if (!$statusUpdate) {
  die("Gagal memperbarui status pesanan.");
}

// Simpan notifikasi ke user
require_once $_SERVER['DOCUMENT_ROOT'] . '/PWD-Project-Mandiri/model/Notifikasi.php';
$notifikasi = new Notifikasi();
$isiPesan = "Pesanan \"$dataPesanan[nama_produk]\" telah diselesaikan.";
$simpanNotifikasi = $notifikasi->add([
  'id_pengguna' => $dataPesanan['id_pembeli'],
  'isi' => $isiPesan
]);

if (!$simpanNotifikasi) {
  error_log("Gagal simpan notifikasi untuk id_pengguna={$dataPesanan['id_pembeli']}");
}

// Redirect sukses
header("Location: /PWD-Project-Mandiri/index.php?page=riwayat");
exit;
