<?php
require_once 'Koneksi.php';

class Kendaraan extends Koneksi
{
  private $conn;

  public function __construct()
  {
    parent::__construct();
    $this->conn = $this->getConnection();
  }

  private function validateTable($table)
  {
    $allowedTables = ['mobil', 'motor', 'truk', 'alatberat', 'sepeda', 'kend_khusus'];
    if (!in_array($table, $allowedTables)) {
      throw new Exception("Tabel '$table' tidak diizinkan.");
    }
  }

  public function getAllFromTable($table)
  {
    $this->validateTable($table);

    $query = "SELECT * FROM `$table` ORDER BY id_$table ASC";
    $result = $this->conn->query($query);

    $data = [];
    if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $data[] = $row;
      }
    }

    return $data;
  }

  public function getById($table, $id)
  {
    $this->validateTable($table);

    $stmt = $this->conn->prepare("SELECT * FROM `$table` WHERE id_$table = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
  }

  public function simpan($table, $data)
  {
    $this->validateTable($table);

    $columns = implode(", ", array_keys($data));
    $placeholders = implode(", ", array_fill(0, count($data), "?"));

    $types = str_repeat("s", count($data)); // Sesuaikan jika ada int/double
    $values = array_values($data);

    $stmt = $this->conn->prepare("INSERT INTO `$table` ($columns) VALUES ($placeholders)");
    $stmt->bind_param($types, ...$values);
    return $stmt->execute();
  }

  public function update($table, $id, $data)
  {
    $this->validateTable($table);

    $setClauses = [];
    $types = '';
    $values = [];

    foreach ($data as $key => $value) {
      $setClauses[] = "$key = ?";
      $types .= 's';
      $values[] = $value;
    }

    $setClauseStr = implode(", ", $setClauses);
    $stmt = $this->conn->prepare("UPDATE `$table` SET $setClauseStr WHERE id_$table = ?");
    $types .= 'i';
    $values[] = $id;

    $stmt->bind_param($types, ...$values);
    return $stmt->execute();
  }

  public function delete($table, $id)
  {
    $this->validateTable($table);

    $stmt = $this->conn->prepare("DELETE FROM `$table` WHERE id_$table = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
  }

  public function getMobil()
  {
    return $this->getAllFromTable('mobil');
  }

  public function getMotor()
  {
    return $this->getAllFromTable('motor');
  }
}
