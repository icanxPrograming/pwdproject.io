<?php
include 'Koneksi.php';

$db = new Koneksi();
$conn = $db->getConnection();

$dataDashboard = [];

// Total Kendaraan (dengan status_post = 'Posting')
$query = $conn->query("SELECT COUNT(*) as total FROM kendaraan WHERE status_post = 'Posting'");
$row = $query->fetch_assoc();
$dataDashboard['kendaraan'] = $row['total'];

// Penjual (status = 'Aktif')
$query = $conn->query("SELECT COUNT(*) as total FROM penjual WHERE status = 'Aktif'");
$row = $query->fetch_assoc();
$dataDashboard['penjual'] = $row['total'];

// Transaksi Penjualan (status = 'Selesai')
$query = $conn->query("SELECT COUNT(*) as total FROM trans_penjualan WHERE status = 'Selesai'");
$row = $query->fetch_assoc();
$dataDashboard['penjualan'] = $row['total'];

// Transaksi Pembelian (status = 'Selesai')
$query = $conn->query("SELECT COUNT(*) as total FROM trans_pembelian WHERE status = 'Selesai'");
$row = $query->fetch_assoc();
$dataDashboard['pembelian'] = $row['total'];

// Kategori (status = 'Aktif')
$query = $conn->query("SELECT COUNT(*) as total FROM kategori WHERE status = 'Aktif'");
$row = $query->fetch_assoc();
$dataDashboard['kategori'] = $row['total'];

// Lokasi (status = 'Aktif')
$query = $conn->query("SELECT COUNT(*) as total FROM lokasi WHERE status = 'Aktif'");
$row = $query->fetch_assoc();
$dataDashboard['lokasi'] = $row['total'];

// Promo (status = 'Aktif')
$query = $conn->query("SELECT COUNT(*) as total FROM promo WHERE status = 'Aktif'");
$row = $query->fetch_assoc();
$dataDashboard['promo'] = $row['total'];
