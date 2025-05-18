<?php
require_once('Koneksi.php');

class Lokasi extends Koneksi
{
  private $conn;

  public function __construct()
  {
    parent::__construct();
    $this->conn = $this->getConnection();
  }

  public function getAll()
  {
    $query = "SELECT *FROM lokasi ORDER BY id_lokasi ASC";
    $result = $this->conn->query($query);

    $data = array();
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
      }
    }
    return $data;
  }
}
