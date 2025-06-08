<?php
require_once 'Koneksi.php';

class Video extends Koneksi
{
  private $conn;

  public function __construct()
  {
    parent::__construct();
    $this->conn = $this->getConnection();
  }

  /**
   * Ambil semua data video
   *
   * @return array
   */
  public function getAll()
  {
    $query = "SELECT * FROM video ORDER BY tanggal ASC";
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
   * Ambil satu video berdasarkan ID
   *
   * @param int $id_video
   * @return array|null
   */
  public function getById(int $id_video): ?array
  {
    $stmt = $this->conn->prepare("SELECT * FROM video WHERE id_video = ?");
    $stmt->bind_param("i", $id_video);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc() ?: null;
  }

  /**
   * Simpan video baru
   *
   * @param array $data
   * @return bool
   */
  public function add(array $data): bool
  {
    $stmt = $this->conn->prepare("
            INSERT INTO video (judul_video, url, kategori, status_post, tanggal)
            VALUES (?, ?, ?, ?, NOW())
        ");
    $stmt->bind_param(
      "ssss",
      $data['judul_video'],
      $data['url'],
      $data['kategori'],
      $data['status_post']
    );
    return $stmt->execute();
  }

  public function getAllActive()
  {
    $query = "SELECT * FROM video WHERE status_post = 'Posting' ORDER BY tanggal DESC";
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
   * Update video
   *
   * @param array $data
   * @param int $id_video
   * @return bool
   */
  public function update(array $data, int $id_video): bool
  {
    $stmt = $this->conn->prepare("
            UPDATE video SET judul_video = ?, url = ?, kategori = ?, status_post = ?
            WHERE id_video = ?
        ");
    $stmt->bind_param(
      "ssssi",
      $data['judul_video'],
      $data['url'],
      $data['kategori'],
      $data['status_post'],
      $id_video
    );
    return $stmt->execute();
  }

  /**
   * Hapus video
   *
   * @param int $id_video
   * @return bool
   */
  public function deleteById(int $id_video): bool
  {
    $stmt = $this->conn->prepare("DELETE FROM video WHERE id_video = ?");
    $stmt->bind_param("i", $id_video);
    return $stmt->execute();
  }
}
