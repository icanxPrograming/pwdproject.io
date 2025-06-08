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
   * @param string $table Nama tabel kendaraan (mobil, motor, dll.)
   * @throws Exception Jika tabel tidak diizinkan
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
   * Ambil semua data dari tabel tertentu.
   *
   * @param string $table Nama tabel kendaraan (mobil, motor, dll.)
   * @return array<int, array<string, mixed>> Data kendaraan dalam bentuk array asosiatif
   */
  public function getAllFromTable(string $table): array
  {
    $this->validateTable($table);

    switch ($table) {
      case 'alat_berat':
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
   * Ambil satu data berdasarkan ID kendaraan.
   *
   * @param string $table Nama tabel kendaraan (mobil, motor, dll.)
   * @param int $id ID kendaraan
   * @return array<string, mixed>|null Data kendaraan atau null jika tidak ditemukan
   */
  public function getById(string $table, int $id): ?array
  {
    $this->validateTable($table);

    switch ($table) {
      case 'alat_berat':
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
   * Cari tabel mana ID produk berasal
   * @param int $id_produk
   * @return string|null
   */
  public function getTableFromId(int $id_produk): ?string
  {
    $tables = ['mobil', 'motor', 'truk', 'sepeda'];
    foreach ($tables as $table) {
      $field = "id_$table";
      $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM `$table` WHERE `$field` = ?");
      $stmt->bind_param("i", $id_produk);
      $stmt->execute();
      $res = $stmt->get_result()->fetch_assoc();
      if ($res['total'] > 0) return $table;
    }

    // Untuk alat_berat dan kend_khusus (ID tidak ikuti pola id_$table)
    $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM alat_berat WHERE id_alat_berat = ?");
    $stmt->bind_param("i", $id_produk);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    if ($res['total'] > 0) return 'alat_berat';

    $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM kend_khusus WHERE id_kend_khusus = ?");
    $stmt->bind_param("i", $id_produk);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    if ($res['total'] > 0) return 'kend_khusus';

    return null;
  }

  /**
   * Update jumlah_unit kendaraan setelah pesanan dikonfirmasi
   * @param string $type (mobil/motor/truk/alat_berat/kend_khusus/sepeda)
   * @param int $id_produk
   * @param int $jumlah_baru
   * @return bool
   */
  public function updateStockKendaraan(string $type, int $id_produk, int $jumlah_baru): bool
  {
    switch ($type) {
      case 'mobil':
        $field = 'id_mobil';
        break;
      case 'motor':
        $field = 'id_motor';
        break;
      case 'truk':
        $field = 'id_truk';
        break;
      case 'sepeda':
        $field = 'id_sepeda';
        break;
      case 'alat_berat':
        $field = 'id_alat_berat';
        break;
      case 'kend_khusus':
        $field = 'id_kend_khusus';
        break;
      default:
        return false;
    }

    $stmt = $this->conn->prepare("UPDATE `$type` SET jumlah_unit = ? WHERE `$field` = ?");
    $stmt->bind_param("ii", $jumlah_baru, $id_produk);
    return $stmt->execute();
  }

  /**
   * Mengambil daftar kendaraan yang diposting dan tersedia untuk ditampilkan di beranda.
   *
   * @param string $table Nama tabel kendaraan (mobil, motor, truk, alat_berat, sepeda, kend_khusus)
   * @param array $filters [optional] Array filter seperti ['merk' => 'Toyota', 'tahun' => 2020]
   * @return array<int, array<string, mixed>> Data kendaraan yang sesuai
   * @throws Exception Jika nama tabel tidak valid
   */
  public function getPostedAvailable(string $table, array $filters = []): array
  {
    $this->validateTable($table);

    switch ($table) {
      case 'alat_berat':
        $statusColumn = 'status_alat_berat';
        break;
      case 'kend_khusus':
        $statusColumn = 'status_kend_khusus';
        break;
      default:
        $statusColumn = "status_$table";
        break;
    }

    $query = "SELECT * FROM `$table` WHERE status_post = 'Posting' AND `$statusColumn` = 'Tersedia'";

    // === Mapping jenis kolom ===
    $jenisColumn = null;
    switch ($table) {
      case 'mobil':
        $jenisColumn = 'jenis_mobil';
        break;
      case 'motor':
        $jenisColumn = 'jenis_motor';
        break;
      case 'truk':
        $jenisColumn = 'jenis_truk';
        break;
      case 'alat_berat':
        $jenisColumn = 'jenis_alat_berat';
        break;
      case 'kend_khusus':
        $jenisColumn = 'jenis_kend_khusus';
        break;
      case 'sepeda':
        $jenisColumn = 'jenis_sepeda';
        break;
      default:
        $jenisColumn = null;
    }

    $columnMap = [
      'jenis' => $jenisColumn,
      'merk' => 'merk',
      'kondisi' => 'kondisi',
      'bahan_bakar' => 'bahan_bakar',
      'transmisi' => 'transmisi',
      'tahun' => 'tahun'
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

    error_log("Query for $table: $query");
    error_log("Params: " . json_encode($params));

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
   * Menyimpan data baru ke tabel tertentu.
   *
   * @param string $table Nama tabel kendaraan (mobil, motor, dll.)
   * @param array<string, mixed> $data Data kendaraan untuk disimpan
   * @return bool True jika berhasil, false jika gagal
   */
  public function simpan(string $table, array $data): bool
  {
    $this->validateTable($table);

    $columns = implode(", ", array_keys($data));
    $placeholders = implode(", ", array_fill(0, count($data), "?"));

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

    $query = "INSERT INTO `$table` ($columns) VALUES ($placeholders)";
    $stmt = $this->conn->prepare($query);

    if (!$stmt) {
      die("Gagal prepare query: " . $this->conn->error);
    }

    $stmt->bind_param($types, ...$values);
    return $stmt->execute();
  }

  /**
   * Mengupdate data kendaraan berdasarkan ID.
   *
   * @param string $table Nama tabel kendaraan (mobil, motor, dll.)
   * @param int $id ID kendaraan
   * @param array<string, mixed> $data Data kendaraan yang akan di-update
   * @return bool True jika berhasil, false jika gagal
   */
  public function update(string $table, int $id, array $data): bool
  {
    $this->validateTable($table);

    if (!$this->conn) {
      die("Database connection not established.");
    }

    $keys = array_keys($data);
    $values = array_values($data);

    if (empty($keys)) {
      die("Data kosong, tidak ada yang bisa di-update.");
    }

    // Tentukan kolom ID
    switch ($table) {
      case 'alat_berat':
        $idColumn = 'id_alat_berat';
        break;
      case 'kend_khusus':
        $idColumn = 'id_kend_khusus';
        break;
      default:
        $idColumn = "id_$table";
        break;
    }

    // Buat SET clause
    $setClauses = array_map(fn($key) => "`$key` = ?", $keys);
    $setClauseStr = implode(', ', $setClauses);

    // Buat SQL
    $sql = "UPDATE `$table` SET $setClauseStr WHERE `$idColumn` = ?";
    error_log("SQL: " . $sql);

    // Prepare statement
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
      die("Prepare failed: " . htmlspecialchars($this->conn->error));
    }

    // Siapkan types
    $types = '';
    foreach ($values as $val) {
      if (is_int($val)) $types .= 'i';
      elseif (is_double($val)) $types .= 'd';
      elseif (is_string($val)) $types .= 's';
      else $types .= 'b';
    }
    $types .= 'i'; // tipe untuk id
    $values[] = $id;

    // Bind param
    if (!$stmt->bind_param($types, ...$values)) {
      die("Bind param failed: " . $stmt->error);
    }

    return $stmt->execute();
  }

  /**
   * Menghapus data kendaraan berdasarkan ID.
   *
   * @param string $table Nama tabel kendaraan (mobil, motor, dll.)
   * @param int $id ID kendaraan
   * @return bool True jika berhasil, false jika gagal
   */
  public function delete(string $table, int $id): bool
  {
    $this->validateTable($table);

    switch ($table) {
      case 'alat_berat':
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
   * Ambil kendaraan berdasarkan jenis dan merk.
   *
   * @param string $table Nama tabel kendaraan (mobil, motor, dll.)
   * @param string $jenis Jenis kendaraan (SUV, Matic, dll.)
   * @param string $merk Merk kendaraan (Toyota, Yamaha, dll.)
   * @return array<int, array<string, mixed>> Daftar kendaraan sesuai jenis & merk
   */
  public function getByJenisDanMerk(string $table, string $jenis, string $merk): array
  {
    $this->validateTable($table);

    switch ($table) {
      case 'mobil':
        $jenisColumn = 'jenis_mobil';
        break;
      case 'motor':
        $jenisColumn = 'jenis_motor';
        break;
      case 'truk':
        $jenisColumn = 'jenis_truk';
        break;
      case 'alat_berat':
        $jenisColumn = 'jenis_alat_berat';
        break;
      case 'sepeda':
        $jenisColumn = 'jenis_sepeda';
        break;
      case 'kend_khusus':
        $jenisColumn = 'jenis_kend_khusus';
        break;
      default:
        return [];
    }

    $stmt = $this->conn->prepare("SELECT * FROM `$table` WHERE `$jenisColumn` = ? AND merk = ?");
    $stmt->bind_param("ss", $jenis, $merk);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }

    return $data;
  }

  /**
   * Ambil kendaraan berdasarkan jenis dan rentang harga.
   *
   * @param string $table Nama tabel kendaraan (mobil, motor, dll.)
   * @param string $jenis Jenis kendaraan (SUV, Matic, dll.)
   * @param float $minHarga Harga minimum
   * @param float $maxHarga Harga maksimum
   * @return array<int, array<string, mixed>> Daftar kendaraan sesuai filter
   */
  public function getByJenisHarga(string $table, string $jenis, float $minHarga, float $maxHarga): array
  {
    $this->validateTable($table);

    switch ($table) {
      case 'mobil':
        $jenisColumn = 'jenis_mobil';
        break;
      case 'motor':
        $jenisColumn = 'jenis_motor';
        break;
      case 'truk':
        $jenisColumn = 'jenis_truk';
        break;
      case 'alat_berat':
        $jenisColumn = 'jenis_alat_berat';
        break;
      case 'sepeda':
        $jenisColumn = 'jenis_sepeda';
        break;
      case 'kend_khusus':
        $jenisColumn = 'jenis_kend_khusus';
        break;
      default:
        return [];
    }

    $hargaColumn = 'harga_per_unit';
    $stmt = $this->conn->prepare("SELECT * FROM `$table` WHERE `$jenisColumn` = ? AND `$hargaColumn` BETWEEN ? AND ?");
    $stmt->bind_param("sdd", $jenis, $minHarga, $maxHarga);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }

    return $data;
  }

  /**
   * Ambil semua kendaraan berdasarkan merk.
   *
   * @param string $table Nama tabel kendaraan (mobil, motor, dll.)
   * @param string $merk Merk kendaraan (Toyota, Yamaha, dll.)
   * @return array<int, array<string, mixed>> Daftar kendaraan sesuai merk
   */
  public function getByMerk(string $table, string $merk): array
  {
    $this->validateTable($table);

    $stmt = $this->conn->prepare("SELECT * FROM `$table` WHERE merk = ?");
    $stmt->bind_param("s", $merk);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }

    return $data;
  }

  /**
   * Ambil semua kendaraan berdasarkan jenis.
   *
   * @param string $table Nama tabel kendaraan (mobil, motor, dll.)
   * @param string $jenis Jenis kendaraan (MPV, SUV, Manual, Trail)
   * @return array<int, array<string, mixed>> Daftar kendaraan sesuai jenis
   */
  public function getByJenis(string $table, string $jenis): array
  {
    $this->validateTable($table);

    switch ($table) {
      case 'mobil':
        $jenisColumn = 'jenis_mobil';
        break;
      case 'motor':
        $jenisColumn = 'jenis_motor';
        break;
      case 'truk':
        $jenisColumn = 'jenis_truk';
        break;
      case 'alat_berat':
        $jenisColumn = 'jenis_alat_berat';
        break;
      case 'sepeda':
        $jenisColumn = 'jenis_sepeda';
        break;
      case 'kend_khusus':
        $jenisColumn = 'jenis_kend_khusus';
        break;
      default:
        return [];
    }

    $stmt = $this->conn->prepare("SELECT * FROM `$table` WHERE `$jenisColumn` = ? AND status_post = 'Posting'");
    $stmt->bind_param("s", $jenis);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }

    return $data;
  }

  /**
   * Ambil daftar jenis kendaraan unik dari tabel tertentu.
   *
   * @param string $table Nama tabel kendaraan (mobil, motor, dll.)
   * @return array<string> Daftar jenis kendaraan (SUV, Matic, dll.)
   */
  public function getJenisByType(string $table): array
  {
    $this->validateTable($table);

    switch ($table) {
      case 'mobil':
        $jenisColumn = 'jenis_mobil';
        break;
      case 'motor':
        $jenisColumn = 'jenis_motor';
        break;
      case 'truk':
        $jenisColumn = 'jenis_truk';
        break;
      case 'alat_berat':
        $jenisColumn = 'jenis_alat_berat';
        break;
      case 'sepeda':
        $jenisColumn = 'jenis_sepeda';
        break;
      case 'kend_khusus':
        $jenisColumn = 'jenis_kend_khusus';
        break;
      default:
        return [];
    }

    $stmt = $this->conn->prepare("SELECT DISTINCT `$jenisColumn` AS jenis FROM `$table` WHERE status_post = 'Posting'");
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
      if (!empty($row['jenis'])) {
        $data[] = $row['jenis'];
      }
    }

    return $data;
  }

  /**
   * Ambil daftar merk unik dari tabel tertentu.
   *
   * @param string $table Nama tabel kendaraan (mobil, motor, dll.)
   * @return array<string> Daftar merk kendaraan
   */
  public function getMerkByType(string $table): array
  {
    $this->validateTable($table);

    $stmt = $this->conn->prepare("SELECT DISTINCT merk FROM `$table` WHERE status_post = 'Posting'");
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
      if (!empty($row['merk'])) {
        $data[] = $row['merk'];
      }
    }

    return $data;
  }

  /**
   * Ambil daftar bahan bakar unik dari tabel tertentu.
   *
   * @param string $table Nama tabel kendaraan
   * @return array<string> Daftar bahan bakar (Bensin, Diesel, Listrik)
   */
  public function getBahanBakarByType(string $table): array
  {
    $this->validateTable($table);

    if ($table === 'sepeda') {
      return []; // Sepeda tidak punya bahan_bakar
    }

    $stmt = $this->conn->prepare("SELECT DISTINCT bahan_bakar FROM `$table` WHERE status_post = 'Posting'");
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
      if (!empty($row['bahan_bakar'])) {
        $data[] = $row['bahan_bakar'];
      }
    }

    return $data;
  }

  /**
   * Ambil daftar transmisi unik dari tabel tertentu.
   *
   * @param string $table Nama tabel kendaraan (mobil, motor, dll.)
   * @return array<string> Daftar transmisi (Manual, Matic)
   */
  public function getTransmisiByType(string $table): array
  {
    $this->validateTable($table);

    if (!in_array($table, ['mobil', 'motor', 'truk', 'kend_khusus']) || $table === 'sepeda') {
      return [];
    }

    $stmt = $this->conn->prepare("SELECT DISTINCT transmisi FROM `$table` WHERE status_post = 'Posting'");
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
      if (!empty($row['transmisi'])) {
        $data[] = $row['transmisi'];
      }
    }

    return $data;
  }

  /**
   * Ambil daftar kondisi kendaraan unik (Baru / Bekas).
   *
   * @param string $table Nama tabel kendaraan (mobil, motor, dll.)
   * @return array<string> Daftar kondisi kendaraan
   */
  public function getKondisiByType(string $table): array
  {
    $this->validateTable($table);

    $stmt = $this->conn->prepare("SELECT DISTINCT kondisi FROM `$table` WHERE status_post = 'Posting'");
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
      if (!empty($row['kondisi'])) {
        $data[] = $row['kondisi'];
      }
    }

    return $data;
  }

  /**
   * Ambil semua data mobil.
   *
   * @return array<int, array<string, mixed>> Daftar mobil
   */
  public function getMobil(): array
  {
    return $this->getAllFromTable('mobil');
  }

  /**
   * Ambil semua data motor.
   *
   * @return array<int, array<string, mixed>> Daftar motor
   */
  public function getMotor(): array
  {
    return $this->getAllFromTable('motor');
  }

  /**
   * Ambil semua data truk.
   *
   * @return array<int, array<string, mixed>> Daftar truk
   */
  public function getTruk(): array
  {
    return $this->getAllFromTable('truk');
  }

  /**
   * Ambil semua data alat berat.
   *
   * @return array<int, array<string, mixed>> Daftar alat berat
   */
  public function getAlatBerat(): array
  {
    return $this->getAllFromTable('alat_berat');
  }

  /**
   * Ambil semua data sepeda.
   *
   * @return array<int, array<string, mixed>> Daftar sepeda
   */
  public function getSepeda(): array
  {
    return $this->getAllFromTable('sepeda');
  }

  /**
   * Ambil semua data kendaraan khusus.
   *
   * @return array<int, array<string, mixed>> Daftar kendaraan khusus
   */
  public function getKendKhusus(): array
  {
    return $this->getAllFromTable('kend_khusus');
  }

  /**
   * Ambil kendaraan berdasarkan merk saja.
   *
   * @param string $table Nama tabel kendaraan (mobil, motor, dll.)
   * @param string $merk Merk kendaraan (Toyota, Honda, dll.)
   * @return array<int, array<string, mixed>> Daftar kendaraan berdasarkan merk
   */
  public function getByMerkOnly(string $table, string $merk): array
  {
    return $this->getByMerk($table, $merk);
  }
}
