<?php
require_once('Koneksi.php');

/**
 * Class Transaksi
 * 
 * Kelas ini bertanggung jawab untuk mengelola data transaksi pesanan dari user.
 * Termasuk menyimpan, mengambil, dan memperbarui status pesanan.
 */
class Transaksi extends Koneksi
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
   * Ambil semua data transaksi
   *
   * @return array Semua data transaksi dalam bentuk array
   */
  public function getAll()
  {
    $query = "SELECT * FROM transaksi ORDER BY id_transaksi ASC";
    $result = $this->conn->query($query);

    $data = [];
    if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
      }
    }
    return $data;
  }

  /**
   * Simpan pesanan baru ke dalam tabel transaksi
   *
   * @param array $data Data pesanan yang akan disimpan
   *                     Harus berisi:
   *                     - id_pembeli
   *                     - type
   *                     - id_produk
   *                     - nama_pembeli
   *                     - nama_produk
   *                     - jumlah_unit
   *                     - total_harga
   *                     - metode_bayar
   *                     - pesan_tambahan
   *                     - no_invoice
   * @return bool TRUE jika berhasil, FALSE jika gagal
   */
  public function add($data)
  {
    $query = "
            INSERT INTO transaksi (
                id_pembeli, type, id_produk, email_pembeli, nama_produk,
                jumlah_unit, total_harga, metode_bayar, pesan_tambahan, no_invoice
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
      error_log("Query prepare failed: " . $this->conn->error);
      die("Gagal mempersiapkan query. Silakan coba lagi.");
    }

    // Format bind: i s i s s i s s s s
    $stmt->bind_param(
      "isississss",
      $data['id_pembeli'],
      $data['type'],
      $data['id_produk'],
      $data['email_pembeli'],
      $data['nama_produk'],
      $data['jumlah_unit'],
      $data['total_harga'],
      $data['metode_bayar'],
      $data['pesan_tambahan'],
      $data['no_invoice']
    );

    return $stmt->execute();
  }

  /**
   * Ambil satu data transaksi berdasarkan ID
   *
   * @param int $id ID transaksi
   * @return array|null Data transaksi atau null jika tidak ditemukan
   */
  public function getById($id)
  {
    $id = intval($id);
    $stmt = $this->conn->prepare("SELECT * FROM transaksi WHERE id_transaksi = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result ? $result->fetch_assoc() : null;
  }

  /**
   * Update status pesanan berdasarkan ID transaksi
   *
   * @param int $id_transaksi ID transaksi
   * @param string $status Status baru (pending/dikonfirmasi/dibatalkan/selesai)
   * @return bool TRUE jika berhasil, FALSE jika gagal
   */
  public function updateStatusById($id_transaksi, $status)
  {
    $stmt = $this->conn->prepare("UPDATE transaksi SET status = ?, tanggal_dikonfirmasi = NOW() WHERE id_transaksi = ?");
    $stmt->bind_param("si", $status, $id_transaksi);
    return $stmt->execute();
  }

  /**
   * Ambil detail pesanan berdasarkan invoice
   *
   * @param string $invoice Nomor invoice
   * @return array|null Data transaksi atau null jika tidak ditemukan
   */
  public function getByInvoice($invoice)
  {
    $stmt = $this->conn->prepare("SELECT * FROM transaksi WHERE no_invoice = ?");
    $stmt->bind_param("s", $invoice);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }

  /**
   * Ambil semua pesanan pengguna + gambar dari tabel terkait
   *
   * @param string $idUser ID pengguna
   * @return array Semua pesanan pengguna beserta informasi tambahan seperti gambar
   */
  public function getByUserIdWithImage($idUser)
  {
    $query = "
            SELECT 
                t.id_transaksi,
                t.type,
                t.id_produk,
                t.email_pembeli,
                t.nama_produk,
                t.jumlah_unit,
                t.total_harga,
                t.metode_bayar,
                t.status,
                t.tanggal_dipesan,
                m.gambar AS gambar
            FROM transaksi t
            LEFT JOIN mobil m ON t.type = 'mobil' AND t.id_produk = m.id_mobil
            LEFT JOIN motor mo ON t.type = 'motor' AND t.id_produk = mo.id_motor
            LEFT JOIN truk tr ON t.type = 'truk' AND t.id_produk = tr.id_truk
            LEFT JOIN alat_berat ab ON t.type = 'alat_berat' AND t.id_produk = ab.id_alat_berat
            LEFT JOIN sepeda s ON t.type = 'sepeda' AND t.id_produk = s.id_sepeda
            LEFT JOIN kend_khusus kk ON t.type = 'kend_khusus' AND t.id_produk = kk.id_kend_khusus
            LEFT JOIN kebutuhan kb ON t.type = 'kebutuhan' AND t.id_produk = kb.id_kebutuhan
            WHERE t.id_pembeli = ?
            ORDER BY t.tanggal_dipesan DESC";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
      error_log("Query prepare failed: " . $this->conn->error);
      return [];
    }

    $stmt->bind_param("s", $idUser);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }

    return $data;
  }

  /**
   * Ambil pesanan pengguna dengan pagination
   *
   * @param string $idUser ID pengguna
   * @param int $start Awal data (untuk LIMIT)
   * @param int $limit Jumlah maksimal data per halaman
   * @return array Pesanan pengguna sesuai batas pagination
   */
  public function getByUserIdLimit($idUser, $start, $limit)
  {
    $stmt = $this->conn->prepare("SELECT * FROM transaksi WHERE id_pembeli = ? ORDER BY tanggal_dipesan DESC LIMIT ?, ?");
    $stmt->bind_param("sii", $idUser, $start, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }

    return $data;
  }
}
