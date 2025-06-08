<?php
require_once 'Koneksi.php';

/**
 * Class Notifikasi
 * 
 * Kelas ini bertanggung jawab untuk mengelola notifikasi yang diberikan kepada pengguna.
 * Notifikasi bisa berupa pesan transaksi, pembatalan, atau informasi lainnya.
 */
class Notifikasi extends Koneksi
{
  private $conn;

  /**
   * Inisialisasi koneksi database
   */
  public function __construct()
  {
    parent::__construct();
    $this->conn = $this->getConnection();
  }

  /**
   * Menyimpan notifikasi baru ke dalam database
   *
   * @param array $data Array data notifikasi yang berisi:
   *                    - id_pengguna: ID pengguna penerima notifikasi
   *                    - isi: konten notifikasi
   * @return bool TRUE jika berhasil, FALSE jika gagal
   */
  public function add($data)
  {
    $stmt = $this->conn->prepare("INSERT INTO notifikasi (id_pengguna, isi) VALUES (?, ?)");
    $stmt->bind_param("is", $data['id_pengguna'], $data['isi']);
    return $stmt->execute();
  }

  /**
   * Menghapus notifikasi secara soft delete dengan mengubah status menjadi 'dihapus'
   *
   * @param int $id_notifikasi ID dari notifikasi yang akan dihapus
   * @return bool TRUE jika berhasil, FALSE jika gagal
   */
  public function deleteById(int $id_notifikasi): bool
  {
    $stmt = $this->conn->prepare("UPDATE notifikasi SET status = 'dihapus' WHERE id_notifikasi = ?");
    $stmt->bind_param("i", $id_notifikasi);
    return $stmt->execute();
  }

  /**
   * Mengambil semua notifikasi aktif untuk pengguna tertentu
   *
   * @param int $id_pengguna ID pengguna
   * @return array Data notifikasi yang ditemukan
   */
  public function getByUserId($id_pengguna)
  {
    $stmt = $this->conn->prepare("SELECT * FROM notifikasi WHERE id_pengguna = ? AND status != 'dihapus'");
    $stmt->bind_param("i", $id_pengguna);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }

    return $data;
  }

  /**
   * Mengambil semua notifikasi (termasuk yang diarsip/dihapus)
   * 
   * Digunakan untuk halaman arsip atau admin
   *
   * @param int $id_pengguna ID pengguna
   * @return array Semua notifikasi milik pengguna
   */
  public function getAllByUserId($id_pengguna)
  {
    $stmt = $this->conn->prepare("SELECT * FROM notifikasi WHERE id_pengguna = ?");
    $stmt->bind_param("i", $id_pengguna);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }

    return $data;
  }

  /**
   * Mengambil notifikasi berdasarkan user dengan pagination
   *
   * @param int $id_pengguna ID pengguna
   * @param int $start Nomor awal data (untuk pagination)
   * @param int $limit Jumlah data per halaman
   * @return array Data notifikasi paginated
   */
  public function getPaginatedByUserId($id_pengguna, $start, $limit)
  {
    $stmt = $this->conn->prepare("SELECT * FROM notifikasi WHERE id_pengguna = ? AND status != 'dihapus' LIMIT ?, ?");
    $stmt->bind_param("sii", $id_pengguna, $start, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }

    return $data;
  }

  /**
   * Mengarsipkan notifikasi dengan mengubah status menjadi 'diarsip'
   *
   * @param int $id_notifikasi ID notifikasi yang akan diarsip
   * @return bool TRUE jika berhasil, FALSE jika gagal
   */
  public function archiveById(int $id_notifikasi): bool
  {
    $stmt = $this->conn->prepare("UPDATE notifikasi SET status = 'diarsip' WHERE id_notifikasi = ?");
    $stmt->bind_param("i", $id_notifikasi);
    return $stmt->execute();
  }
}
