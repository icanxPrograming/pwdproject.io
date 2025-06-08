<?php
require_once('Koneksi.php');

class Kebutuhan extends Koneksi
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
    $allowedTables = ['kebutuhan'];
    if (!in_array($table, $allowedTables)) {
      throw new Exception("Tabel '$table' tidak diizinkan.");
    }
  }

  /**
   * Ambil daftar kebutuhan yang diposting dan tersedia
   */
  public function getPostedAvailable(string $table, array $filters = []): array
  {
    $this->validateTable($table);

    // Status kolom
    $query = "SELECT * FROM `$table` WHERE status_post = 'Posting' AND status_kebutuhan = 'Tersedia'";

    // Mapping kolom filter
    $columnMap = [
      'jenis' => 'jenis_kebutuhan',
      'kategori' => 'kategori',
      'status' => 'status_kebutuhan'
    ];

    $types = '';
    $params = [];

    foreach ($filters as $key => $values) {
      if (!isset($columnMap[$key])) continue;

      $dbColumn = $columnMap[$key];
      if (!$dbColumn) {
        error_log("Kolom '$key' tidak ditemukan untuk tabel '$table'");
        continue;
      }

      if (!is_array($values)) $values = [$values];

      $placeholders = implode(',', array_fill(0, count($values), '?'));
      $query .= " AND `$dbColumn` IN ($placeholders)";
      foreach ($values as $value) {
        $types .= is_int($value) ? 'i' : 's';
        $params[] = $value;
      }
    }

    $stmt = $this->conn->prepare($query);
    if (!$stmt) throw new \Exception("Prepare failed: " . $this->conn->error);

    if (!empty($params)) {
      $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }

    return $data;
  }

  /**
   * Ambil semua jenis_kebutuhan unik dari database
   */
  public function getJenisKebutuhan(): array
  {
    $result = $this->conn->query("SELECT DISTINCT jenis_kebutuhan FROM kebutuhan");
    $data = [];
    while ($row = $result->fetch_assoc()) {
      $data[] = $row['jenis_kebutuhan'];
    }
    return $data;
  }

  /**
   * Ambil semua kategori unik dari database
   */
  public function getKategoriList(): array
  {
    $result = $this->conn->query("SELECT DISTINCT kategori FROM kebutuhan");
    $data = [];
    while ($row = $result->fetch_assoc()) {
      $data[] = $row['kategori'];
    }
    return $data;
  }

  /**
   * Ambil semua status_kebutuhan unik dari database
   */
  public function getStatusKebutuhan(): array
  {
    return ['Tersedia', 'Terjual'];
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

    $stmt = $this->conn->prepare("SELECT * FROM `$table` WHERE id_$table = ?");
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

    // Deteksi tipe data otomatis
    $types = '';
    foreach ($data as $value) {
      if (is_int($value)) {
        $types .= 'i'; // integer
      } elseif (is_double($value)) {
        $types .= 'd'; // double
      } elseif (is_string($value)) {
        $types .= 's'; // string
      } else {
        $types .= 'b'; // binary/blob (fallback)
      }
    }

    $values = array_values($data);

    $stmt = $this->conn->prepare("INSERT INTO `$table` ($columns) VALUES ($placeholders)");
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
    $stmt = $this->conn->prepare("UPDATE `$table` SET $setClauseStr WHERE id_$table = ?");
    $types .= 'i'; // tambahkan tipe untuk ID
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

    $stmt = $this->conn->prepare("DELETE FROM `$table` WHERE id_$table = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
  }

  public function updateStockKebutuhan(int $id_produk, int $jumlah_baru): bool
  {
    $stmt = $this->conn->prepare("UPDATE kebutuhan SET jumlah = ? WHERE id_kebutuhan = ?");
    $stmt->bind_param("ii", $jumlah_baru, $id_produk);
    return $stmt->execute();
  }

  /**
   * Ambil semua data mobil.
   *
   * @return array<int, array<string, mixed>>
   */
  public function getKebutuhan(): array
  {
    return $this->getAllFromTable('kebutuhan');
  }
}
