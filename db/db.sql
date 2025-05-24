CREATE TABLE auth (
  id_pengguna TINYINT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(128) NOT NULL UNIQUE,
  username VARCHAR(128) NOT NULL,
  password VARCHAR(255) NOT NULL,
  level TINYINT NOT NULL DEFAULT 1
);

-- contoh insert admin level 0 langsung dari database
INSERT INTO auth VALUES
(DEFAULT, 'your email', 'your username', 'your password hash', 0);
-- Jika daftar melalui form default nya adalah level 1 = user

CREATE TABLE mobil (
  id_mobil MEDIUMINT PRIMARY KEY AUTO_INCREMENT,
  nama_mobil VARCHAR(128) NOT NULL,
  tahun MEDIUMINT NOT NULL,
  jumlah_unit TINYINT NOT NULL,
  harga_per_unit DEC(15, 2) NOT NULL,
  deskripsi TEXT NOT NULL,
  status_post ENUM('Posting','Belum') NOT NULL DEFAULT 'Belum',
  gambar VARCHAR(128) NOT NULL
);

CREATE TABLE motor (
  id_mobil MEDIUMINT PRIMARY KEY AUTO_INCREMENT,
  nama_motor VARCHAR(128) NOT NULL,
  tahun MEDIUMINT NOT NULL,
  jumlah_unit TINYINT NOT NULL,
  harga_per_unit DEC(15, 2) NOT NULL,
  deskripsi TEXT NOT NULL,
  status_post ENUM('Posting','Belum') NOT NULL DEFAULT 'Belum',
  gambar VARCHAR(128) NOT NULL
);

CREATE TABLE truk (
  id_truk MEDIUMINT PRIMARY KEY AUTO_INCREMENT,
  nama_truk VARCHAR(128) NOT NULL,
  tahun MEDIUMINT NOT NULL,
  jumlah_unit TINYINT NOT NULL,
  harga_per_unit DEC(15, 2) NOT NULL,
  deskripsi TEXT NOT NULL,
  status_post ENUM('Posting','Belum') NOT NULL DEFAULT 'Belum',
  gambar VARCHAR(128) NOT NULL
);

CREATE TABLE alatBerat (
  id_alat_berat MEDIUMINT PRIMARY KEY AUTO_INCREMENT,
  nama_alat_berat VARCHAR(128) NOT NULL,
  tahun MEDIUMINT NOT NULL,
  jumlah_unit TINYINT NOT NULL,
  harga_per_unit DEC(15, 2) NOT NULL,
  deskripsi TEXT NOT NULL,
  status_post ENUM('Posting','Belum') NOT NULL DEFAULT 'Belum',
  gambar VARCHAR(128) NOT NULL
);

CREATE TABLE sepeda (
  id_sepeda MEDIUMINT PRIMARY KEY AUTO_INCREMENT,
  nama_sepeda VARCHAR(128) NOT NULL,
  tahun MEDIUMINT NOT NULL,
  jumlah_unit TINYINT NOT NULL,
  harga_per_unit DECIMAL(15, 2) NOT NULL,
  deskripsi TEXT NOT NULL,
  status_post ENUM('Posting','Belum') NOT NULL DEFAULT 'Belum',
  gambar VARCHAR(128) NOT NULL
);

CREATE TABLE kend_khusus (
  id_kend_khusus MEDIUMINT PRIMARY KEY AUTO_INCREMENT,
  nama_kend_khusus VARCHAR(128) NOT NULL,
  tahun MEDIUMINT NOT NULL,
  jumlah_unit TINYINT NOT NULL,
  harga_per_unit DECIMAL(15, 2) NOT NULL,
  deskripsi TEXT NOT NULL,
  status_post ENUM('Posting','Belum') NOT NULL DEFAULT 'Belum',
  gambar VARCHAR(128) NOT NULL
);

CREATE TABLE kategori (
  id_kategori MEDIUMINT PRIMARY KEY AUTO_INCREMENT,
  nama_kategori VARCHAR(128) NOT NULL,
  unit TINYINT NOT NULL,
  deskripsi TEXT NOT NULL,
  jenis VARCHAR(128) NOT NULL,
  status ENUM('Aktif','Tidak') NOT NULL DEFAULT 'Aktif'
);

CREATE TABLE penjual (
  id_penjual MEDIUMINT PRIMARY KEY AUTO_INCREMENT,
  nama_penjual VARCHAR(128) NOT NULL,
  email VARCHAR(128) NOT NULL,
  no_hp VARCHAR(20) NOT NULL,
  alamat TEXT NOT NULL,
  tipe_penjual ENUM('Dealer','Perorangan') NOT NULL,
  status ENUM('Aktif','Tidak') NOT NULL DEFAULT 'Aktif'
);

CREATE TABLE promo (
  id_promo MEDIUMINT PRIMARY KEY AUTO_INCREMENT,
  judul_promo VARCHAR(128) NOT NULL,
  deskripsi TEXT NOT NULL,
  tanggal_mulai DATE NOT NULL,
  tanggal_selesai DATE NOT NULL,
  status ENUM('Aktif','Tidak') NOT NULL DEFAULT 'Aktif'
);

CREATE TABLE lokasi (
  id_lokasi MEDIUMINT PRIMARY KEY AUTO_INCREMENT,
  nama_lokasi VARCHAR(128) NOT NULL,
  status ENUM('Aktif','Tidak') NOT NULL DEFAULT 'Aktif'
);

CREATE TABLE trans_pembelian (
  id_transaksi MEDIUMINT PRIMARY KEY AUTO_INCREMENT,
  nama_penjual VARCHAR(128) NOT NULL,
  nama_kendaraan VARCHAR(128) NOT NULL,
  tanggal DATE NOT NULL,
  jumlah_unit TINYINT NOT NULL,
  total_harga DEC(15,2) NOT NULL,
  metode_bayar ENUM('Tunai','Transfer', 'Debit') NOT NULL DEFAULT 'Tunai',
  status ENUM('Pending','Selesai', 'Batal') NOT NULL DEFAULT 'Pending'
);

CREATE TABLE trans_penjualan (
  id_penjualan MEDIUMINT PRIMARY KEY AUTO_INCREMENT,
  nama_pembeli VARCHAR(128) NOT NULL,
  nama_kendaraan VARCHAR(128) NOT NULL,
  tanggal DATE NOT NULL,
  jumlah_unit TINYINT NOT NULL,
  total_harga DEC(15,2) NOT NULL,
  metode_bayar ENUM('Tunai', 'Kredit', 'Transfer', 'Debit') NOT NULL DEFAULT 'Tunai',
  status ENUM('Pending','Selesai', 'Batal') NOT NULL DEFAULT 'Pending'
);