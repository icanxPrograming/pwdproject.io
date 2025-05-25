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

  /**
   * Validasi nama tabel agar hanya tabel yang diizinkan yang diproses.
   *
   * @param string $table
   * @throws Exception
   * @return void
   */
  private function validateTable(string $table): void
  {
    $allowedTables = ['mobil', 'motor', 'truk', 'alat_berat', 'sepeda', 'kend_khusus'];
    if (!in_array($table, $allowedTables)) {
      throw new Exception("Tabel '$table' tidak diizinkan.");
    }
  }

  /**
   * Mengambil semua data dari tabel tertentu.
   *
   * @param string $table
   * @return array<int, array<string, mixed>>
   */
  public function getAllFromTable(string $table): array
  {
    $this->validateTable($table);

    // Sesuaikan nama ID jika tidak konsisten
    switch ($table) {
      case 'alatberat':
        $idColumn = 'id_alat_berat';
        break;
      case 'kend_khusus':
        $idColumn = 'id_kend_khusus';
        break;
      default:
        $idColumn = "id_$table";
        break;
    }

    $query = "SELECT * FROM `$table` ORDER BY `$idColumn` ASC";
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
   * Mengambil satu data berdasarkan ID.
   *
   * @param string $table
   * @param int $id
   * @return array<string, mixed>|null
   */
  public function getById(string $table, int $id): ?array
  {
    $this->validateTable($table);

    // Sesuaikan kolom ID untuk tiap jenis kendaraan
    switch ($table) {
      case 'alatberat':
        $idColumn = 'id_alat_berat';
        break;
      case 'kend_khusus':
        $idColumn = 'id_kend_khusus';
        break;
      default:
        $idColumn = "id_$table";
        break;
    }

    $stmt = $this->conn->prepare("SELECT * FROM `$table` WHERE `$idColumn` = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result ? $result->fetch_assoc() : null;
  }

  /**
   * Menyimpan data baru ke tabel tertentu.
   *
   * @param string $table
   * @param array<string, mixed> $data
   * @return bool
   */
  public function simpan(string $table, array $data): bool
  {
    $this->validateTable($table);

    $columns = implode(", ", array_keys($data));
    $placeholders = implode(", ", array_fill(0, count($data), "?"));

    // Deteksi tipe parameter
    $types = '';
    foreach ($data as $value) {
      if (is_int($value)) {
        $types .= 'i';
      } elseif (is_double($value)) {
        $types .= 'd';
      } elseif (is_string($value)) {
        $types .= 's';
      } else {
        $types .= 'b';
      }
    }

    $values = array_values($data);

    // Cetak query jika error
    $query = "INSERT INTO `$table` ($columns) VALUES ($placeholders)";
    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
      die("Gagal prepare query: " . $this->conn->error);
    }

    $stmt->bind_param($types, ...$values);
    return $stmt->execute();
  }

  /**
   * Mengupdate data berdasarkan ID.
   *
   * @param string $table
   * @param int $id
   * @param array<string, mixed> $data
   * @return bool
   */
  public function update(string $table, int $id, array $data): bool
  {
    $this->validateTable($table);

    $setClauses = [];
    $types = '';
    $values = [];

    foreach ($data as $key => $value) {
      $setClauses[] = "$key = ?";
      if (is_int($value)) {
        $types .= 'i';
      } elseif (is_double($value)) {
        $types .= 'd';
      } elseif (is_string($value)) {
        $types .= 's';
      } else {
        $types .= 'b';
      }
      $values[] = $value;
    }

    $setClauseStr = implode(", ", $setClauses);

    // Tentukan kolom ID
    switch ($table) {
      case 'alatberat':
        $idColumn = 'id_alat_berat';
        break;
      case 'kend_khusus':
        $idColumn = 'id_kend_khusus';
        break;
      default:
        $idColumn = "id_$table";
        break;
    }

    $stmt = $this->conn->prepare("UPDATE `$table` SET $setClauseStr WHERE `$idColumn` = ?");
    $types .= 'i';
    $values[] = $id;

    $stmt->bind_param($types, ...$values);
    return $stmt->execute();
  }

  /**
   * Menghapus data berdasarkan ID.
   *
   * @param string $table
   * @param int $id
   * @return bool
   */
  public function delete(string $table, int $id): bool
  {
    $this->validateTable($table);

    // Tentukan nama kolom ID
    switch ($table) {
      case 'alatberat':
        $idColumn = 'id_alat_berat';
        break;
      case 'kend_khusus':
        $idColumn = 'id_kend_khusus';
        break;
      default:
        $idColumn = "id_$table";
        break;
    }

    $stmt = $this->conn->prepare("DELETE FROM `$table` WHERE `$idColumn` = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
  }

  /**
   * Ambil semua data mobil.
   *
   * @return array<int, array<string, mixed>>
   */
  public function getMobil(): array
  {
    return $this->getAllFromTable('mobil');
  }

  /**
   * Ambil semua data motor.
   *
   * @return array<int, array<string, mixed>>
   */
  public function getMotor(): array
  {
    return $this->getAllFromTable('motor');
  }

  /**
   * Ambil semua data truk.
   *
   * @return array<int, array<string, mixed>>
   */
  public function getTruk(): array
  {
    return $this->getAllFromTable('truk');
  }

  /**
   * Ambil semua data alat berat.
   *
   * @return array<int, array<string, mixed>>
   */
  public function getAlatBerat(): array
  {
    return $this->getAllFromTable('alat_berat');
  }

  /**
   * Ambil semua data sepeda.
   *
   * @return array<int, array<string, mixed>>
   */
  public function getSepeda(): array
  {
    return $this->getAllFromTable('sepeda');
  }

  /**
   * Ambil semua data kendaraan khusus.
   *
   * @return array<int, array<string, mixed>>
   */
  public function getKendKhusus(): array
  {
    return $this->getAllFromTable('kend_khusus');
  }
}
