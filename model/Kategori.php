<?php
require_once('Koneksi.php');

class Kategori extends Koneksi
{
  private $conn;

  public function __construct()
  {
    parent::__construct();
    $this->conn = $this->getConnection();
  }

  public function getAll()
  {
    $query = "SELECT *FROM kategori ORDER BY id_kategori DESC";
    $result = $this->conn->query($query);

    $data = array();
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
      }
    }
    return $data;
  }

  public function getByJenis($jenis)
  {
    $stmt = $this->conn->prepare("SELECT * FROM kategori WHERE jenis = ? AND status = 'Aktif'");
    $stmt->bind_param("s", $jenis);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }
    return $data;
  }
}
